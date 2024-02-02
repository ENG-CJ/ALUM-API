<?php
include './config.php';
include './Headers.php';
class Users extends Connections
{


    public static function RegisterUser($data)
    {
        $response = array();
        $user_id = strtoupper(uniqid("U"));

        $sql = "INSERT into users values('$user_id','$data->username','$data->Email','$data->Password','Admin','active')";
        $result = Users::Connect()->query($sql);
        if ($result) {
            $response = array("error" => "", "response" => "User Was Registered Successfully");
        } else
            $response = array("error" => Users::Connect()->error);

        echo json_encode($response);
    }



    public static function PublishUnPublish($data)
    {
        $response = array();
        if ($data->update == "one") {
            if ($data->type == "published") {
                $sql = "UPDATE Users SET published='unpublished' where EventCode='$data->id';";
                $result = Users::Connect()->query($sql);
                if ($result) {
                    $response = array("error" => "", "type" => "blocked", "response" => "Event has Been deactivated");
                } else
                    $response = array("error" => AlumniStudents::Connect()->error);
            } else if ($data->type == "unpublished") {
                $sql = "UPDATE Users SET published='published' where EventCode='$data->id';";
                $result = Users::Connect()->query($sql);
                if ($result) {
                    $response = array("error" => "", "type" => "active", "response" => "Event has Been activated");
                } else
                    $response = array("error" => Users::Connect()->error);
            }
        }


        echo json_encode($response);
    }

    public static function FetchAdminDetails($data)
    {
        $response = array();


        $sql = "Select * from users where id='$data->id'";
        $result = Users::Connect()->query($sql);
        if ($result) {
            $row = $result->fetch_assoc();
            $response = array("error" => "", "response" => $row);
        } else
            $response = array("error" => Users::Connect()->error);

        echo json_encode($response);
    }

    public static function VerifyUserCredentials($data)
    {
        $response = array();


        $sql = "Select * from users where Email='$data->email' AND Password='$data->password'";
        $result = Users::Connect()->query($sql);
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                $row = $result->fetch_assoc();
                $response = array("error" => "", "isValid" => true, "response" => $row);
            } else
                $response = array("error" => "", "isValid" => false, "response" => "Invalid Username Or Password");
        } else
            $response = array("error" => Users::Connect()->error);

        echo json_encode($response);
    }
    public static function UpdateUsers($Data)
    {
        $response = array();

        $sql = "Update Users set Username='$Data->Username',Email='$Data->Email' where id='$Data->id'";
        $result = Users::Connect()->query($sql);
        if ($result) {
            $response = array("error" => "","isProfile"=>false, "response" => "The record was successfully Updated");
        } else
            $response = array("error" => Users::Connect()->error);

        echo json_encode($response);
    }
    public static function UpdateAdminProfile($Data)
    {
        $response = array();

        $sql = "Update users set Username='$Data->Username',Email='$Data->Email' where id='$Data->id'";
        $result = Users::Connect()->query($sql);
        if ($result) {
            $response = array("error" => "","isProfile"=>true, "response" => "Your Profile Has ben Updated");
        } else
            $response = array("error" => Users::Connect()->error);

        echo json_encode($response);
    }
    public static function ChangePassword($data)
    {
        $response = array();

        $sql = "Update  users set Password='$data->newPass' where id='$data->id'";
        $result = Users::Connect()->query($sql);
        if ($result) {
            $response = array("error" => "", "response" => "Your Security Has Been Changed");
        } else
            $response = array("error" => Users::Connect()->error);

        echo json_encode($response);
    }
    public static function fetchCurrentPassword($data)
    {
        $response = array();


        $sql = "Select Password from users where id='$data->id'";
        $result = Users::Connect()->query($sql);
        if ($result) {
            $row = $result->fetch_assoc();
            $response = array("error" => "", "response" => $row);
        } else
            $response = array("error" => Users::Connect()->error);

        echo json_encode($response);
    }

    public static function CheckRequest($value)
    {
        switch ($value->action) {
            case "RegisterUser":
                Users::RegisterUser($value->data);
                break;
            case "PublishUnPublish":
                Users::PublishUnPublish($value->data);
                break;
            case "EditAdminDetails":
                Users::UpdateUsers($value->data);
                break;
            case "VerifyUserCredentials":
                Users::VerifyUserCredentials($value->data);
                break;
            case "UpdateAdminProfile":
                Users::UpdateAdminProfile($value->data);
                break;
            case "fetchCurrentPassword":
                Users::fetchCurrentPassword($value->data);
                break;
            case "ChangePassword":
                Users::ChangePassword($value->data);
                break;
            case "FetchAdminDetails":
                Users::FetchAdminDetails($value->data);
        }
    }
}




$method = $_SERVER['REQUEST_METHOD'];
$clientData = json_decode(file_get_contents("php://input"));
switch ($method) {
    case "POST":
        Users::CheckRequest($clientData);
}
