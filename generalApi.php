<?php
include './config.php';
include './Headers.php';
class GeneralApi extends Connections
{

    public static function ReadAll($Data)
    {
        $response = array();

        $sql = "Select * from $Data->table";
        $result = GeneralApi::Connect()->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {

                $response[] = array("error" => "", "response" => $row);
            }
        } else
            $response = array("error" => GeneralApi::Connect()->error);

        echo json_encode($response);
    }
    public static function ReadPart()
    {
        $response = array();

        $sql = "SELECT events.Title, alumnistudents.FullName as Participators,alumnistudents.GraduatedBatch
        FROM participations
        JOIN alumnistudents ON participations.MemberCode=alumnistudents.MemberCode
        JOIN events ON participations.EventCode=events.EventCode";
        $result = GeneralApi::Connect()->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {

                $response[] = array("error" => "", "response" => $row);
            }
        } else
            $response = array("error" => GeneralApi::Connect()->error);

        echo json_encode($response);
    }
    public static function ReadProfile($Data)
    {
        $response = array();

        $sql = "Select * from $Data->table";
        $result = GeneralApi::Connect()->query($sql);
        if ($result) {
            ($row = $result->fetch_assoc());
            $response = array("error" => "", "response" => $row);
        } else
            $response = array("error" => GeneralApi::Connect()->error);

        echo json_encode($response);
    }
    public static function UpdateAlumniProfile($data)
    {
        $response = array();

        $sql = "UPDATE associationprofile set AssociationName='$data->AssociationName', Founded='$data->Founded',
        President='$data->President', AlumniDescription='$data->AlumniDescription';";
        $result = GeneralApi::Connect()->query($sql);
        if ($result) {

            $response = array("error" => "", "response" => "Alumni Profile Has Been Updated Successfully");
        } else
            $response = array("error" => GeneralApi::Connect()->error);

        echo json_encode($response);
    }
    public static function CountAll()
    {
        $response = array();

        $sql_users = "Select count(*) as Counter from users";
        $sql_events = "Select count(*) as Counter from events";
        $sql_alumni = "Select count(*) as Counter from alumnistudents";
        $result_users = GeneralApi::Connect()->query($sql_users);
        $result_events = GeneralApi::Connect()->query($sql_events);
        $result_alumni = GeneralApi::Connect()->query($sql_alumni);
        if ($result_users && $result_events && $result_alumni) {
            ($row_1 = $result_users->fetch_assoc());
            ($row_2 = $result_events->fetch_assoc());
            ($row_3 = $result_alumni->fetch_assoc());

            $response = array("error" => "", "response" => [
                "users" => $row_1['Counter'],
                "events" => $row_2['Counter'],
                "alumni" => $row_3['Counter'],
            ]);
        } else
            $response = array("error" => GeneralApi::Connect()->error);

        echo json_encode($response);
    }
    public static function Delete($Data)
    {
        $response = array();

        $sql = "Delete from $Data->table where $Data->id='$Data->DeleteId'";
        $result = GeneralApi::Connect()->query($sql);
        if ($result) {
            $response = array("error" => "", "response" => "The record was successfully removed");
        } else
            $response = array("error" => GeneralApi::Connect()->error);

        echo json_encode($response);
    }






    public static function CheckRequest($value)
    {
        switch ($value->action) {
            case "ReadAll":
                GeneralApi::ReadAll($value->data);
                break;
            case "ReadProfile":
                GeneralApi::ReadProfile($value->data);
                break;
            case "delete":
                GeneralApi::Delete($value->data);
                break;
            case "CountAll":
                GeneralApi::CountAll();
                break;
            case "ReadPart":
                GeneralApi::ReadPart();
                break;
            case "UpdateAlumniProfile":
                GeneralApi::UpdateAlumniProfile($value->data);
                break;
        }
    }
}




$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case "POST":
        $clientData = json_decode(file_get_contents("php://input"));
        GeneralApi::CheckRequest($clientData);
}

?>
