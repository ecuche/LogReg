<?php
declare(strict_types= 1);
namespace App\Models;

use Framework\Helpers\Token;
use Framework\Helpers\Cookie;
use Framework\Helpers\Auth;
use Framework\Model;
use PDO;


class RememberedLogins extends Model
{
    protected $table = "remembered_logins";

    public function rememberLogin(object|array $user): bool
    {
        if(empty($user->remember_me)){
            return false;
        }
        $user = (object) $user;
        $token = new Token();
        $hashed_token = $token->getHash();
        $expiry_timestamp = time() + 60*60*24*30;

        $data = [
            'token_hash' => $hashed_token,
            'user_id' => $user->id,
            'expires_at' => date('Y-m-d H:i:s', $expiry_timestamp)
        ];
        $this->deleteRememberMe($user->id); 
        if($this->insert($data)){
            return Cookie::set('remember_me', $hashed_token, $expiry_timestamp);
        }
        return false;
    }

    public function loginFromRemeberCookie(): bool
    {
        $cookie = Cookie::get('remember_me');
        if(!empty($cookie)){
            $token = $this->findByField('token_hash', $cookie);
            if(!empty($token)){
                if(strtotime($token->expires_at) < time()){
                    $this->deleteRememberMe($token->user_id);
                }else{
                    $user = $this->getUser($token->user_id);
                    return Auth::login($user);
                }
            }
        }
        return false;
    }

    public function getUser($id): object
    {
        $this->table = 'user';
        return $this->findById($id) ?? false;
    }

    public function deleteRememberMe($user_id): bool
    {
        $sql = "DELETE FROM  remembered_logins WHERE user_id = :user_id";
        $conn = $this->database->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);
        return $stmt ->execute();
    }
}