<?php 
include './Headers.php';


if(isset($_POST['action'])){
   $fullName=($_POST['data']);
   echo $fullName;
}


?>