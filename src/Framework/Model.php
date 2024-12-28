<?php
declare(strict_types=1);
namespace Framework;
use PDO;
use App\Database;
abstract class Model
{
    protected $table;
    protected array $errors = [];


    public function __construct(protected Database $database)
    {
    }

    protected function addError(string $field, string $message): void
    {
        $this->errors[$field] = $message;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getTable(): string
    {
        if($this->table !== null){
            return $this->table;
        }
        $parts = explode("\\", $this::class);
        return strtolower(array_pop($parts));
    }

    public function getInsertId(): string
    {
        $conn = $this->database->getConnection();
        return $conn->lastInsertId();

    }

    public function findAll(): array|object
    {
        $conn = $this->database->getConnection();
        $sql = "SELECT * FROM {$this->getTable()} WHERE deleted_on IS NULL";
        $stmt = $conn->query($sql);
        return  $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function first(): array|object
    {
        $conn = $this->database->getConnection();
        $sql = "SELECT * FROM {$this->getTable()} WHERE deleted_on IS NULL";
        $stmt = $conn->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC); 
        $result = array_shift($result);
        $result = (object) $result;
        return  $result;
    }

    public function last(): array|object
    {
        $conn = $this->database->getConnection();
        $sql = "SELECT * FROM {$this->getTable()} WHERE deleted_on IS NULL";
        $stmt = $conn->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC); 
        $result = array_pop($result);
        $result = (object) $result;
        return  $result;
    }

    public function findById(string|int $id): object|bool
    {
        $conn = $this->database->getConnection();
        $sql = "SELECT * FROM {$this->getTable()} WHERE id = :id AND deleted_on IS NULL";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function findByField(string $field, $value): object|bool
    {
        $sql = "SELECT * FROM {$this->getTable()} WHERE {$field} = :field AND deleted_on IS NULL LIMIT 1";
        $type = match(gettype($value)){
            "boolean" => PDO::PARAM_BOOL,
            "integer" => PDO::PARAM_INT,
            "NULL" => PDO::PARAM_NULL,
            default => PDO::PARAM_STR
        };
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":field", $value, $type);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function insert(array|object $data): bool
    {
        if(!empty($this->errors)){
            return false;
        }
        $data = (array) $data;
        $conn = $this->database->getConnection();
        $fields = implode(",", array_keys($data));
        $placeholders = implode(",", array_fill(0, count($data), "?"));
        $sql = "INSERT INTO {$this->getTable()} ($fields) VALUES ($placeholders)";
        $stmt = $conn->prepare($sql);
        $i = 1;
        foreach($data as $value){
            $type = match(gettype($value)){
                "boolean" => PDO::PARAM_BOOL,
                "integer" => PDO::PARAM_INT,
                "NULL" => PDO::PARAM_NULL,
                default => PDO::PARAM_STR
            };
            $stmt->bindValue($i++, $value, $type);
        }

        return $stmt->execute();
    }
    public function updateRow(string $id, array|object $data): bool
    {
        if(!empty($this->errors)){
            return false;
        }
        $sql = "UPDATE {$this->getTable()} SET ";
        $data = (array) $data;
        unset($data['id']);
        $assignments = array_keys($data);
        array_walk($assignments, function(&$value){
            $value = " $value = ?";
        });
        $sql .= implode(',', $assignments)." WHERE id = ?";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($sql);
        $i = 1;
        foreach($data as $value){
            $type = match(gettype($value)){
                "boolean" => PDO::PARAM_BOOL,
                "integer" => PDO::PARAM_INT,
                "NULL" => PDO::PARAM_NULL,
                default => PDO::PARAM_STR
            };
            $stmt->bindValue($i++, $value, $type);
        }
        $stmt->bindValue($i, $id, PDO::PARAM_INT);


        return $stmt->execute();
    }

    public function deleteRow(string $id): bool
    {
        $sql = "UPDATE {$this->getTable()} SET deleted_on = NOW() WHERE id = :id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        return $stmt ->execute();
    }

    public function recoverRow(string $id): bool
    {
        $sql = "UPDATE {$this->getTable()} SET deleted_on = NULL WHERE id = :id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        return $stmt ->execute();
    }

    public function rowCount(): int
    {
        $sql = "SELECT COUNT(*) AS total FROM {$this->getTable()} WHERE deleted_on IS NULL";
        $conn = $this->database->getConnection();
        $stmt = $conn->query($sql);
        $row = $stmt->fetch(PDO::FETCH_OBJ);
        return (int) $row->total;	
    }

    public function fieldValueExists(string $field, string|int $value): bool
    {
        $conn = $this->database->getConnection();
        $sql = "SELECT * FROM {$this->getTable()} WHERE {$field} = :val";
        $stmt = $conn->prepare($sql);
        $type = match(gettype($value)){
            "integer" => PDO::PARAM_INT,
            default => PDO::PARAM_STR
        };
        $stmt->bindValue(":val", $value, $type);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ) ? true : false;	
    }
}