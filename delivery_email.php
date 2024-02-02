



<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer-master/src/Exception.php';
require './PHPMailer-master/src/PHPMailer.php';
require './PHPMailer-master/src/SMTP.php';

class Mail
{

    private string $fullName;
    private string $type;
    private string $receiverEmail;
    private string $message;
    private string $error;


    public function setFullName($name)
    {
        $this->fullName = $name;
        return $this;
    }
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }
    public function setReceiverEmail($email)
    {
        $this->receiverEmail = $email;
        return $this;
    }
    public function setMessageContent($message)
    {
        $this->message = $message;
        return $this;
    }
    public function getFullName()
    {

        return $this->fullName;
    }
    public function getType()
    {

        return $this->type;
    }
    public function getReceiverEmail()
    {

        return $this->receiverEmail;
    }
    public function getMessageContent()
    {

        return $this->message;
    }
    public function getError()
    {

        return $this->error;
    }


    // rough function

    public function isConfirmed(): bool | string | array
    {

        $sqlAction = "SELECT *FROM subscribers where SUBSCRIBER_CODE=''";
        $resultAction = "";
        $response = array();
        if ($resultAction) {
            $response = array("status" => true, "code" => "200", "response" => [
                "message" => "All Email Was proceeded",
                "area_code" => 109191010,
                "PIP ID " => "P91010101",
                "UserEmail" => "abdulrahmandev10@gmail.com",
                "BusinessEmail" => "HirApp@gmail.com",
                'codeID'=>"GEN-ID-CODE".rand(1000,9999)
                
            ]);

        
        }

        return $response;
    }
    public function sendEmail()
    {

        try {

                $phpMail = new PHPMailer();
                $phpMail->isSMTP();
                $phpMail->Mailer = "smtp";
                $phpMail->SMTPAuth = true;
                $phpMail->SMTPDebug = 0;
                $phpMail->SMTPSecure = "tls";
                $phpMail->Host = "smtp.gmail.com";
                $phpMail->Username = "phonereserved7@gmail.com";
                $phpMail->Password = "ukjxcwusoyutkiai";

                // email
                $phpMail->Subject = "ALUMNI ASSOCIATION (JUST)";
                $phpMail->setFrom("phonereserved7@gmail.com", "ALUMNI ASSOCIATION");
                $phpMail->addAddress($this->receiverEmail, $this->fullName);

                $phpMail->isHTML();
             
                $phpMail->Body = $this->message;
                $phpMail->send();


                $responseMailer = array();

                if ($phpMail->send())
                    $responseMailer = array("success" => true, "Code" => "200", "data" => "Your mail has been success");

                return true;
            


        } catch (Exception $ex) {
            $this->error = $ex->getMessage();
            return false;
        }
    }
}


?>