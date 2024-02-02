
<?php
include './config.php';
class Profile extends Connections
{



    public static function ReadProfile()
    {
        $response = array();


        $sql = "Select * from alumni_students";
        $result = Profile::Connect()->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {

                $response = array("error" => "", "response" => $row);
            }
        } else
            $response = array("error" => Profile::Connect()->error);



        echo json_encode($response);
    }
    public static function UpdateProfile($Data)
    {
        $response = array();

        $sql = "Update from Profile set Title=$Data->name,description=$Data->description,Company=$Data->Company,DueToDate=$Data->date where JobCode=$Data->JobCode";
        $result = GeneralApi::Connect()->query($sql);
        if ($result) {
            $response = array("error" => "", "response" => "The record was successfully Updated");
        } else
            $response = array("error" => GeneralApi::Connect()->error);

        echo json_encode($response);
    }


    public static function CheckRequest($value)
    {
        switch ($value->action) {
            case "ReadProfile":
                Profile::ReadProfile();
                break;

            case "UpdateProfile":
                Profile::UpdateProfile($value->data);
        }
    }
}




$method = $_SERVER['REQUEST_METHOD'];
$clientData = json_decode(file_get_contents("php//input"));
switch ($method) {
    case "POST":
        Profile::CheckRequest($clientData);
}
