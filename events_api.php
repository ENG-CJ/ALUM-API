<?php
include './config.php';
include './Headers.php';
class Events extends Connections
{


    public static function RegisterEvent($data)
    {
        $response = array();
        $event_code = strtoupper(uniqid("EVCO"));

        $sql = "INSERT into events values('$event_code','$data->event','$data->location','$data->description','unpublished','$data->startDate',0)";
        $result = Events::Connect()->query($sql);
        if ($result) {
            $response = array("error" => "", "response" => "Events Was Registered Successfully");
        } else
            $response = array("error" => Events::Connect()->error);

        echo json_encode($response);
    }



    public static function PublishUnPublish($data)
    {
        $response = array();
        if ($data->update == "one") {
            if ($data->type == "published") {
                $sql = "UPDATE events SET published='unpublished' where EventCode='$data->id';";
                $result = Events::Connect()->query($sql);
                if ($result) {
                    $response = array("error" => "", "type" => "blocked", "response" => "Event has Been deactivated");
                } else
                    $response = array("error" => AlumniStudents::Connect()->error);
            } else if ($data->type == "unpublished") {
                $sql = "UPDATE events SET published='published' where EventCode='$data->id';";
                $result = Events::Connect()->query($sql);
                if ($result) {
                    $response = array("error" => "", "type" => "active", "response" => "Event has Been activated");
                } else
                    $response = array("error" => Events::Connect()->error);
            }
        }


        echo json_encode($response);
    }

    public static function FetchEvent($data)
    {
        $response = array();


        $sql = "Select * from events where EventCode='$data->id'";
        $result = Events::Connect()->query($sql);
        if ($result) {
            $row = $result->fetch_assoc();
            $response = array("error" => "", "response" => $row);
        } else
            $response = array("error" => Events::Connect()->error);

        echo json_encode($response);
    }
    public static function readPublishedEvents()
    {
        $response = array();


        $sql = "Select * from events where published='published' LIMIT 3 ";
        $result = Events::Connect()->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $response[] = array("error" => "", "response" => $row);
            }
        } else
            $response = array("error" => Events::Connect()->error);

        echo json_encode($response);
    }
    public static function readAllEvents()
    {
        $response = array();


        $sql = "Select * from events where published='published' ";
        $result = Events::Connect()->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $response[] = array("error" => "", "response" => $row);
            }
        } else
            $response = array("error" => Events::Connect()->error);

        echo json_encode($response);
    }
    public static function UpdateEvents($Data)
    {
        $response = array();

        $sql = "Update events set Location='$Data->Location', Title='$Data->Title', description='$Data->description', StartDate='$Data->StartDate' where EventCode='$Data->EventCode'";
        $result = Events::Connect()->query($sql);
        if ($result) {
            $response = array("error" => "", "response" => "The record was successfully Updated");
        } else
            $response = array("error" => Events::Connect()->error);

        echo json_encode($response);
    }
    public static function participate($data)
    {
        $response = array();

        $sql_checking = "SELECT *from  `participations` where MemberCode='$data->member' AND  EventCode='$data->event';";
        $result_check = Events::Connect()->query($sql_checking);
        if ($result_check) {
            if (mysqli_num_rows($result_check) > 0)
                $response = array("error" => "", "validity" => false, "response" => "Already Participated,
                You Can Participate Event One Time, If You want to UnParticipate This Event Click UnParticipate Button To Unlink");
            else {
                $sql = "INSERT INTO `participations`(`MemberCode`, `EventCode`) VALUES ('$data->member','$data->event')";

                $result = Events::Connect()->query($sql);
                if ($result) {
                    $sql_participate = "Update events set Participate=Participate+1 where EventCode='$data->event'";
                    $result_part = Events::Connect()->query($sql_participate);
                    if ($result_part)
                        $response = array("error" => "", "validity" => true, "response" => "You've Participated This Event, Thank For You Joining, Plz Read Event Description For
            More Details And Enjoy it. ");
                } else
                    $response = array("error" => Events::Connect()->error);
            }
        } else
            $response = array("error" => Events::Connect()->error);


        echo json_encode($response);
    }
    public static function unLink($data)
    {
        $response = array();

        $sql_checking = "DELETE FROM `participations` where MemberCode='$data->member' AND EventCode='$data->event';";
        $result_check = Events::Connect()->query($sql_checking);
        if ($result_check) {

            $sql_ = "SELECT Participate from events where EventCode='$data->event'";
            $result_ = Events::Connect()->query($sql_);
            if($result_){
                $row=$result_->fetch_assoc();
                if($row['Participate']==0){
                    $sql_participate = "Update events set Participate=0 where EventCode='$data->event'";
                    $result_part = Events::Connect()->query($sql_participate);
                    if ($result_part)
                        $response = array("error" => "", "response" => "You've Unlinked This Event You Can Join Again Before Reaching Required Number Of Audience ):");
                }else{
                    $sql_participate = "Update events set Participate=Participate-1 where EventCode='$data->event'";
                    $result_part = Events::Connect()->query($sql_participate);
                    if ($result_part)
                        $response = array("error" => "", "response" => "You've Unlinked This Event You Can Join Again Before Reaching Required Number Of Audience  ):");
                }
            }


      
        } else
            $response = array("error" => Events::Connect()->error);


        echo json_encode($response);
    }


    public static function CheckRequest($value)
    {
        switch ($value->action) {
            case "RegisterEvent":
                Events::RegisterEvent($value->data);
                break;
            case "PublishUnPublish":
                Events::PublishUnPublish($value->data);
                break;
            case "EditEvent":
                Events::UpdateEvents($value->data);
                break;
            case "readPublishedEvents":
                Events::readPublishedEvents();
                break;
            case "readAllEvents":
                Events::readAllEvents();
                break;
            case "participate":
                Events::participate($value->data);
                break;
            case "unLink":
                Events::unLink($value->data);
                break;
            case "FetchEvent":
                Events::FetchEvent($value->data);
        }
    }
}




$method = $_SERVER['REQUEST_METHOD'];
$clientData = json_decode(file_get_contents("php://input"));
switch ($method) {
    case "POST":
        Events::CheckRequest($clientData);
}
