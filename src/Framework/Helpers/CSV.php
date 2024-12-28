<?php
declare(strict_types=1);
namespace Framework\Helpers;

use Framework\Helpers\MyCSV;
use Framework\Helpers\Data;

class CSV{
	private $_csv,
			$_table = null,
			$_table_dir = null,
			$_search = array(),
			$_last_id;

	public function __construct($table = null, $dir = null){
		$this->_table_dir 	= $dir ? $_ENV['CSV_PATH'].'/'.$dir: $_ENV['CSV_PATH'].'/';
		$this->_table		= $table ? $this->_table_dir.$table : null;
		$this->_csv 		= $table ? new MyCSV($this->_table): new MyCSV() ;
	}

	public function getRow($id = null): array|bool
	{
		if(isset($id)){
			return $this->_csv->data($id);
		}else{
			$result = [];
			if($this->_csv->count()){
				$ids = $this->_csv->ids();
				foreach($ids as $id){
					$rand_questions = $this->_csv->data($id);
					array_push($result, $rand_questions);
				}
				return $result;
			}
			return false;
		}
	}

	public function insertRows(array $data, $single = false): bool|int
	{
		if(Data::is_multi_dim($data) && !$single){
			foreach($data as $value){
				$this->_csv->insert($value);
			}
				$this->_csv->write();
				$this->_last_id = $this->_csv->insert_id();
				return $this->_last_id;
		}elseif(is_array($data) && $single){
			$this->_csv->insert($data);
			$this->_csv->write();
			$this->_last_id = $this->_csv->insert_id();
			return $this->_last_id;
		}
		return false;
	}

	public function addFields($field): bool|array
	{
		if(is_array($field)){
			foreach($field as $key=>$value){
				$this->_csv->add_field($value);
			}
			$this->_csv->write();
			return true;
		}else{
			$result = $this->_csv->add_field($field);
			$this->_csv->write();
			return $result;
		}
	}

	public function getFieldNames(): array
	{
		return $this->_csv->fields;
	}

	public function randRows($num = null): array|bool
	{
		if (isset($num)) {
			$result = [];
			$all_id = $this->_csv->ids();
			shuffle($all_id);
			$ids = isset($num) ? array_rand(array_flip($all_id), $num) : $all_id;
			$max = $this->_csv->count();
			$min = 1;
			if(!isset($num) || ((isset($num)) && ($num <= $max) && ($num > $min))){
				foreach($ids as $id){
					$rand_questions = $this->_csv->data($id);
					array_push($result, $rand_questions);
				}
				return $result;
			}
			return false;	
		}
		$id = self::randId();
		return $this->_csv->data($id);	
	}
	
	public function deleteRow($ids): bool
	{
		if(is_array($ids)){
			foreach($ids as $id){
				$this->_csv->delete($id);
			}
			$this->_csv->write();
			return true;
		}elseif(is_int($ids)){
			$this->_csv->delete($ids);
			$this->_csv->write();
			return true;
		}elseif(!isset($ids)){
			$this->_csv->delete();
			$this->_csv->write();
			return true;
		}
		return false;
	}

	public function updateRows(array $data, $id = null): int
	{
		if(Data::is_multi_dim($data) && !isset($id)){
			foreach($data as $key => $value){
				$value_id = $value['id'];
				unset($value['id']);
				$this->_csv->update($value, $value_id);
			}
		}elseif(isset($id)){
			$this->_csv->update($data, $id);
		}
		$this->_csv->write();
		$this->_last_id = $this->_csv->insert_id();
		return $this->_last_id;
	}

	public function lastId(): int|null
	{
		return $this->_csv->last();
	}

	public function tableExists(): bool
	{
		if($this->_csv->exists()){
			return true;
		}
		return false;
	}

	public function deleteCSV(): bool
	{
		if($this->_csv->exists()){
			unlink($this->_table);
			return true;
		}
		return false;
	}

	public function clearCSV(): bool
	{
		if($this->_csv->exists()){
			$this->_csv->drop_table();
			$this->_csv->write();
            return true;
		}
        return false;
	}

	public function randId(): int|null
	{
		return $this->_csv->rand();
	}

	public function tableName(): string
	{
		return $this->_csv->tablename();
	}

	public function andSearch(array $where1, $where2 = []): array
	{
        if(count($where1) === 2){
		    while ($row = $this->_csv->each()) {
				if($row[$where1[0]] == $where1[1]){
					array_push($this->_search, $row);
				}
			}
		}

        if(count($where2) === 2){
            $result         = $this->_search;
            $this->_search  = [];
            foreach($result as $row) {
				if($row[$where2[0]] == $where2[1]){
					array_push($this->_search, $row);
				}
			}
        }
		return $this->_search;
	}

    public function orSearch(array $where1, $where2 = []): array
	{
        if(count($where1) === 2){
            while ($row = $this->_csv->each()) {
				if($row[$where1[0]] == $where1[1]){
					array_push($this->_search, $row);
				}
			}
		}

		if(count($where2) === 2){
			$this->_csv->reset();
			while ($row = $this->_csv->each()) {
				if(($row[$where2[0]] == $where2[1]) && !in_array($row, $this->_search)){
					array_push($this->_search, $row);
				}
			}
		}
		return $this->_search;
	}
}