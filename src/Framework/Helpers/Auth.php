<?php
declare(strict_types=1);
namespace Framework\Helpers;
use Framework\Helpers\Redirect;
use Framework\Helpers\Session;
use Framework\Helpers\Cookie;

class Auth
{
    public function __construct()
    {

    }

    public static function isLoggedIn(): bool
    {
        $id = Session::get('id') ?? false;
        $email = Session::get('email') ?? false;
        $username = Session::get('username') ?? false;
        if($id && ($email || $username)){
            return true;
        }
        return false;
    }

    public static function passRedirect(array|object $args = []): null|bool
    {
        $args = (array) $args;
        $default = ['url'=>'', 'message'=> 'Kindly Logout to View this page'];
        foreach ($default as $key => $value){
            if(empty($args[$key])){
                $args[$key] = $value;
            }
        }

        if(self::isLoggedIn()){
            self::intendedPage();
            Session::set('warn', $args['message']);
            Redirect::to("/{$args['url']}");
        }
        return false;
    }

    public static function failRedirect(array|object $args = []): null|bool
    {
        $args = (array) $args;
        $default = ['url'=>'', 'message'=> 'Kindly Login to View this page'];
        foreach ($default as $key => $value){
            if(empty($args[$key])){
                $args[$key] = $value;
            }
        }
        if(!self::isLoggedIn()){
            self::intendedPage();
            Session::set('warn', $args['message']);
            Redirect::to("/{$args['url']}");
        }
        return false;
    }

    public static function login(object $user): bool
    {
        // Session::regenerate();
        $id = Session::set('id', $user->id);
        $email = Session::set('email', $user->email);
        $name = Session::set('name', $user->name);
        return $id && $email && $name ? true : false;
    }

    public static function logout(string $message = ''): bool
    {   
        Cookie::delete('remember_me');
        // $result = Session::destroy();
        $result = Session::delete('id');
        Session::delete('email');
        Session::delete('username');
        Session::set('success', $message); 
        return $result;  
    }

    public static function setPermission(string $value): bool
    {
        return Session::set('permission', $value);
    }

    public static function permissionRedirect(string $value): bool|null
    {
        if(Session::get('permission') !=$value){
            self::intendedPage();
            Redirect::to('');
        }
        return false;
    }

    public static function intendedPage(): string
    {
        $_SESSION['intended_url'] = $_SERVER['REQUEST_URI'];
        return  $_SESSION['intended_url'];
    }

    public static function returnPage(): string|bool
    {
        if(isset($_SESSION['intended_url'])){
            $page = substr($_SESSION['intended_url'], strlen($_ENV["SITE_DIR"]));
            Session::delete('intended_url');
            return $page;
        }
        return false;
    }
}