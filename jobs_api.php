<?php
include './config.php';
class Jobs extends Connections 
{
    
    public static function RegisterJobs($data){
        $response= array();
        $Job_code=strtoupper(uniqid("Job"));
        
        $sql="INSERT into jobs values('$Job_code','$data->title','$data->description','$data->company','$data->published','$data->Due')";
        $result=Jobs::Connect()->query($sql);
        if($result){
            $response=array("error"=>"","response"=>"Jobs Was Registered Successfully");
        }else
        $response=array("error"=>Jobs::Connect()->error);

        echo json_encode($response);
    }
    
    public static function PublishUnPublish($data){
        $response= array();
      
        if($data->type=="Publish"){
            $sql="UPDATE jobs SET Published='$data->isPublished';";
            $result=Jobs::Connect()->query($sql);
            if($result){
                $response=array("error"=>"","response"=>"Jobs Was Published Successfully");
            }else
            $response=array("error"=>Jobs::Connect()->error);
        }else if ($data->type=="unpublish"){
            $sql="UPDATE Jobs SET Published='$data->isPublished';";
            $result=Jobs::Connect()->query($sql);
            if($result){
                $response=array("error"=>"","response"=>"Jobs Was Unpublished Successfully");
            }else
            $response=array("error"=>Jobs::Connect()->error);
        }
       

        echo json_encode($response);
    }
    public static function UpdateJobs($Data){
        $response= array();
        
        $sql="Update from Jobs set Title=$Data->name,description=$Data->description,Company=$Data->Company,DueToDate=$Data->date where JobCode=$Data->JobCode";
        $result=GeneralApi::Connect()->query($sql);
        if($result){
            $response=array("error"=>"","response"=>"The record was successfully Updated");

        }else
        $response=array("error"=>GeneralApi::Connect()->error);

        echo json_encode($response);
    }


    public static function CheckRequest($value){
        switch($value->action){
            case "RegisterJobs":
                Jobs::RegisterJobs($value->data);
                break;
            case "PublishUnPublish":
                Jobs::PublishUnPublish($value->data);
                break;
            case "Update":
                Jobs::UpdateJobs($value->data);
            

            

        }
    }

}




$method =$_SERVER['REQUEST_METHOD'];
$clientData=json_decode(file_get_contents("php//input"));
switch($method){
    case "POST":
       Jobs::CheckRequest($clientData);
        

}
