<?php
include './config.php';
class Batch extends Connections 
{
    
    public static function RegisterBatch($data){
        $response= array();
        $BatchID=strtoupper("Batch".rand(1000,9999));
        
        $sql="INSERT into batch values('$BatchID','$data->Batch')";
        $result=Batch::Connect()->query($sql);
        if($result){
            $response=array("error"=>"","response"=>"Batch Was Registered Successfully");
        }else
        $response=array("error"=>Batch::Connect()->error);

        echo json_encode($response);
    }
    
    
    public static function UpdateBatch($Data){
        $response= array();
        
        $sql="Update from Batch set Batch=$Data->Batch where BatchID=$Data->BatchID";
        $result=GeneralApi::Connect()->query($sql);
        if($result){
            $response=array("error"=>"","response"=>"The record was successfully Updated");

        }else
        $response=array("error"=>GeneralApi::Connect()->error);

        echo json_encode($response);
    }


    public static function CheckRequest($value){
        switch($value->action){
            case "RegisterBatch":
                Batch::RegisterBatch($value->data);
                break;
            case "Update":
                Batch::UpdateBatch($value->data);
            

            

        }
    }

}




$method =$_SERVER['REQUEST_METHOD'];
$clientData=json_decode(file_get_contents("php//input"));
switch($method){
    case "POST":
       Batch::CheckRequest($clientData);
        

}
