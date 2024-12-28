<?php
declare(strict_types=1);
namespace App\Models;

use Framework\Model;

class Home extends Model
{

    public function validateContactUs(array|object $data): void
    {
        $data = (object)$data;
        if(empty($data->name)){
            $this->addError('name', "Full Name field is required");
        }

        if(filter_var($data->email, FILTER_VALIDATE_EMAIL) === false){
            $this->addError("email", "Enter a valid email address");
        }

        if(empty($data->message)){
            $this->addError('message', "Kindly enter a message");
        }

        if(empty($data->subject)){
            $this->addError('subject', "Subjectc field is required");
        }
        
    }

}