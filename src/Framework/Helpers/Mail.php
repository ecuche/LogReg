<?php
declare(strict_types=1);
namespace Framework\Helpers;

use Framework\Helpers\Data;

class Mail
{
    protected array $errors = [];
    protected string $to = "";
    protected string $from = "";
    protected string $replyto = "";
    protected string $cc = '';
    protected string $bcc = "";
    protected string $subject = "";
    protected string $message = "";
    protected array $headers = [];
    protected string $attachments = "";
    protected string $boundary = "";
    protected string $html = "";

    public function __construct(){
        $this->boundary = md5(date('r', time()));
        $this->headers['From'] = $this->from = $_ENV["EMAIL_FROM"];
        $this->headers['Reply-To'] =$this->replyto =  $_ENV["EMAIL_REPLYTO"];
        $this->headers["MIME-Version"] = "1.0";
        $this->headers['Date'] = date('D, d M Y H:i:s O');
        $this->headers['Content-Type'] = "multipart/mixed; boundary={$this->boundary}";
    }

    protected function addError(string $field, string $message): string
    {
        $this->errors[$field] = $message;
        return $this->errors[$field];
    }
   
    protected function address(string $type, string|object|array $email, string $name = ""): string
    {
        $emails = $type;
        if (is_array($email) || is_object($email)) {
            $email = (array) $email;
            $count = count($email);
            $i = 1;
            foreach ($email as $value){
                if(Data::is_multi_dim($email)){
                    if(empty($value['name']) || empty( $value['email'] ) || !filter_var($value['email'], FILTER_VALIDATE_EMAIL)){
                        return $this->addError(strtok($type, ':'), "Invalid email format");
                    }
                    $emails .= $i !== $count ? " {$value['name']} <{$value['email']}>," : " {$value['name']} <{$value['email']}>";
                }else{
                    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        return $this->addError(strtok($type, ':'), "Invalid email format");
                    }
                    $emails .= $i !== $count ? "{$value}," : " {$value}";
                }
                $i++;
            }
            return ucwords($emails);
        }else{
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $string = empty($name) ? $email : "{$name} <{$email}>";
                return ucwords($string); 
            }
            return $this->addError(strtok($type, ':'), "Invalid email format");
        }
    }

    public function to(string $email, string $name = ""): string
    {
        $to = $this->address("To: ", $email,  $name);
        if(empty($this->to)){
            return $this->to = $to;
        }
        return  $this->to .= ", {$to}";
    }

    public function from(string $email , string $name = ""): string|bool
    {
        $from = $this->address('From: ', $email, $name);
        return $this->headers['From'] = $this->from = $from;
    }

    public function replyTo(string $email , string $name = ""): string|bool
    {
        $replyto = $this->address('Reply-To: ', $email, $name);
        return $this->headers['Reply-To'] = $this->replyto = $replyto;
    }

    public function cc(string $email , string $name = ""): string
    {
        $cc = $this->address('Cc: ', $email, $name);
        if(empty($this->cc)){
            return $this->headers['Cc'] = $this->cc = $cc;
        }
        $this->cc .= ", {$cc}";
        return  $this->headers['Cc'] =  $this->cc;
    }
    
    public function bcc(string $email , string $name = ""): string
    {
        $bcc = $this->address('Bcc: ', $email, $name);
        if(empty($this->bcc)){
            return $this->headers['Bcc'] = $this->bcc = $bcc;
        }
        $this->bcc .= ", {$bcc}";
        return  $this->headers['Bcc'] = $this->bcc;
    }

    public function subject(string $subject): string
    {
        return $this->subject = ucwords($subject);
    }

    public function message(string $message, bool|string $html = true): string
    {   
        $this->message .= "--{$this->boundary}\r\n";
        $this->message .= "Content-Type: multipart/alternative; boundary=alt-{$this->boundary}\r\n\r\n";

        $this->message .= "--alt-{$this->boundary}\r\n";
        $this->message .= "Content-Type: text/plain; charset=iso-8859-1\r\n"; 
        $this->message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $this->message .= "{$message} \r\n\r\n";    

        if ($html === true || is_string($html)) {
            $this->message .= "--alt-{$this->boundary}\r\n";
            $this->message .= "Content-Type: text/html; charset=iso-8859-1\r\n"; 
            $this->message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
            if($html === true){
                $this->message .= $this->html($message);                
            }elseif(is_string($html)){
                $this->message .= $html;
            }
        }
        $this->message .= "\r\n\r\n--alt-{$this->boundary}--\r\n\r\n";
        return  $this->message;
    }

    public function send(): bool|array|string
    {
        if(!empty($this->errors)) {return $this->errors;}
        if(empty($this->to)) {return "Kindly add a recipeint address";}  

        $message = "";
        $message .= "{$this->message} \r\n\r\n"; 
        $message .= "{$this->attachments} \r\n\r\n";
        $message .= "--{$this->boundary}--"; 

        return mail($this->to, $this->subject, $message,  $this->headers) ? true : false;
    }

    public function html($message): string
    {        
        $html = '<!DOCTYPE html>
                    <html lang="en">
                        <head>
                            <meta charset="UTF-8">
                            <meta name="viewport" content="width=device-width, initial-scale=1.0">
                            <title>Mail from '.$_ENV['SITE_NAME'].' </title>
                        </head>
                        <body>
                            '.$message.'
                        </body>
                    </html>';
        return $this->html = $html;
    }

    public function headers(): array
    {
        return $this->headers;
    }

    public function attachment(string $fullpath): string
    {
        $content = file_get_contents( $fullpath);
        $content = chunk_split(base64_encode($content));
        $file_name = basename($fullpath);

        $this->attachments .= "--{$this->boundary}\r\n";
        $this->attachments .= "Content-Type: application/octet-stream; name=\"{$file_name}\"\r\n";
        $this->attachments .= "Content-Transfer-Encoding: base64 \r\n";
        $this->attachments .= "Content-Disposition: attachment; filename=\"{$file_name}\" \r\n\r\n";
        $this->attachments .= $content;
        return $this->attachments;
    }   
}


