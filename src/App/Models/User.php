<?php
declare(strict_types=1);
namespace App\Models;

use Framework\Model;
use Framework\Helpers\Session;

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

    public function resetAccount($email): bool
    {
        $user = $this->findByField("email", $email);
        if(!empty($user)){
            








            
        }
        return false;
    }
}