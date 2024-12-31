<?php
declare(strict_types=1);
namespace Framework;
class Dotenv
{
    public function load(string $path): void
    {
        self::config();
        $lines = file($path, FILE_IGNORE_NEW_LINES);
        foreach($lines as $line){
            list($name, $value) = explode("=", $line, 2);
            $_ENV[$name] = $value;
            define($name, $value);  
        }
    }

    private static function config(): void
    {
        $config = [
            'CSV_PATH' => dirname(__DIR__)."/files/csv/",
            'MEDIA_PATH' => dirname(__DIR__)."/files/media/",
            'FILES_PATH' => dirname(__DIR__)."/files/",
        ];
        
        foreach($config as $key => $value) {
            $_ENV[$key] = $value;
            define($key, $value); 
        }
    }
}