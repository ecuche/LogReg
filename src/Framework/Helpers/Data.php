<?php
declare(strict_types= 1);
namespace Framework\Helpers;
class Data{

    public static function date($date): string
    {
        return date('dS M, Y',strtotime($date));
    }

    public static function cap($text): string
    {
        return ucwords($text);  
    }

    public static function is_multi_dim($data): bool
    {
        if(is_array($data)){
            $is_array = array_filter($data, 'is_array');
            if(count($is_array)){
                return true;
            }
            return false;
        }
        return false;
    }

    public static function is_email($email){
        if(!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)){
            return true;
        }
            return false;
    }
}