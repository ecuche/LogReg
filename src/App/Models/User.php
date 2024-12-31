<?php
declare(strict_types=1);
namespace App\Models;

use Framework\Model;
use Framework\Helpers\Session;
use Framework\Helpers\Token;
use Framework\Helpers\Mail;
use PDO;

class User extends Model
{
    protected $table = "user";

    
    public function getUser(string $email=''): object|bool
    {
        if (!empty($email)) {
            return $this->findByField('email', $email); 
        }elseif(Session::exists('id')){
            return $this->findById(Session::get('id'));
        }
        return false;
    }
    public function loginUser($data): bool|object
    {
        $user = $this->findByField('email', $data->email);
        if(!empty($user) && password_verify($data->password, $user->password)){
            return $user;
        }
        return false;
    }
    public function validateRegistration(array|object $data): void
    {
        $data = (object)$data;
        if(empty($data->name)){
            $this->addError('name', "Full Name field is required");
        }

        if($this->fieldValueExists("email",$data->email)){
            $this->addError("email", "email is already taken");
        }

        if(filter_var($data->email, FILTER_VALIDATE_EMAIL) === false){
            $this->addError("email", "Enter a valid email address");
        }

        if(preg_match("#^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z]{6,}$#" , $data->password) === 0){
            $this->addError("password", "Password must be at least 6 characters, contain a digit and UPPER and lower case alphabet");
        }
    }

    public function validatePasswordReset(array|object $data): void
    {
        $data = (object)$data;
        if(empty($data->password)){
            $this->addError('password', "kindly provide Password");
        }

        if(empty($this->errors) && (preg_match("#^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z]{6,}$#" , $data->password)) === 0){
            $this->addError("password", "Kindly provide a valid Password");
        }

        if(empty($this->errors) && ($data->password !== $data->password_again)){
            $this->addError("password", "Password does not Match");
        }
    }

    public function validateLogIn(array|object $data): void
    {
        $data = (object)$data;
        if(filter_var($data->email, FILTER_VALIDATE_EMAIL) === false){
            $this->addError("email", "Enter a valid email address");
        }

        if(empty($data->password)){
            $this->addError("password", "Password field is required");
        }

        if(empty($this->errors) && !$this->fieldValueExists("email",$data->email)){
            $this->addError("login", "Credentials do not match");
        }

        if(empty($this->errors) && empty($this->loginUser($data))){
            $this->addError("login", "Credentials do not match");
        }
    }

    public function validateRecoverAccount(string $email): void
    {
        if(filter_var($email, FILTER_VALIDATE_EMAIL) === false){
            $this->addError("email", "Enter a valid email address");
        }
    }

    public function validatePasswordUpdate($data, $user){
        $data = (object)$data;
        if(empty($data->old_password) || empty($data->new_password) || empty($data->password_again)){
            $this->addError('password', "All fields are required");
        }

        if(empty($this->errors) && !password_verify($data->old_password, $user->password)){
            $this->addError('password', "Kindly provide the correct old password");
        }

        if(empty($this->errors) && (preg_match("#^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z]{6,}$#" , $data->new_password)) === 0){
            $this->addError("password", "Kindly provide a valid Password");
        }

        if(empty($this->errors) && ($data->new_password !== $data->password_again)){
            $this->addError("password", "Password does not Match");
        }
    }

    public function validateProfileUpdate($data){
        $data = (object)$data;

        if(empty($data->name)){
            $this->addError('name', "Full Name field is required");
        }

        if(filter_var($data->email, FILTER_VALIDATE_EMAIL) === false){
            $this->addError("email", "Enter a valid email address");
        }
    }

    public function resetAccount($email): bool
    {
        $user = $this->findByField("email", $email);
        if(!empty($user)){
            $this->table = "password_reset";
            $token = new Token();
            $hash = $token->getHash();
            $expiry = date('Y-m-d H:i:s', strtotime('+2 hour'));

        
            if($reset = $this->findByField("user_id", $user->id)){
                $this->updateRow($reset->id, ['hash' => $hash, 'expiry' => $expiry]);
            }else{
                $this->insert(['user_id' => $user->id, 'hash' => $hash, 'expiry' => $expiry]);
            }

            $mail = new Mail();
            $mail->to($user->email, $user->name);
            $mail->subject('Password Reset');
            $mail->is_html();
            $mail->message("Click the link to reset your password: <a href=\"{$_ENV['URL_ROOT']}/reset/password/{$user->email}/{$hash}\">Reset Password.</a>\nNote: this link will expire in 60 minuites.\n kindly ignore if you did not request this");
            $mail->send();
        }
        return false;
    }

    public function getPasswordResetRow($id): object|bool
    {
        $id = (int)$id;
        $sql = "SELECT * FROM password_reset WHERE user_id = :user_id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":user_id", $id, PDO::PARAM_INT);
        $stmt ->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function sendActivation($user): bool
    {
        $sign = $user->updated_on ?? $user->created_on; 
        $token = new Token($sign);
        $token = $token->getHash();
        $mail = new Mail;
        $mail->to($user->email, $user->name);
        $mail->subject("{$_ENV['SITE_NAME']} Account Activation");
        $mail->is_html();
        $mail->message("Click the link to activate your account: <a href=\"{$_ENV['URL_ROOT']}/activate/account/{$user->email}/{$token}\">Activate Account.</a>\n kindly ignore if you did not request this");
        return $mail->send();
    }
   
}