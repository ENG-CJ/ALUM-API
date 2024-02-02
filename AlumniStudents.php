
<?php
include './config.php';
include './Headers.php';
include './delivery_email.php';
class AlumniStudents extends Connections
{




    public static function readtop3()
    {
        $response = array();


        $sql = "Select * from alumnistudents LIMIT 5 ";
        $result = AlumniStudents::Connect()->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $response[] = array("error" => "", "response" => $row);
            }
        } else
            $response = array("error" => AlumniStudents::Connect()->error);

        echo json_encode($response);
    }

    public static function RegisterGraduate($data)
    {
        $response = array();

        $alumni_code = strtoupper(("ALMN") . rand(100, 999));

        $sql = "INSERT INTO `alumnistudents`(`MemberCode`, `FullName`, `Gender`, `Mobile`, `Email`, `Password`, `UniversityID`, `GraduatedBatch`,`Class`, `GraduatedYear`, `IDCardPhoto`, `Verified`) 
        VALUES ('$alumni_code','$data->fullName','$data->gender','$data->mobile','$data->email',
        '$data->password','$data->uniID','$data->batch','$data->Class','$data->year','null','Unverified');";
        $result = AlumniStudents::Connect()->query($sql);
        if ($result) {
            $response = array("error" => "", "photo" => $data->photo, "response" => "Your Account Has Been Configured Successfully");
        } else
            $response = array("error" => AlumniStudents::Connect()->error);

        echo json_encode($response);
    }
    public static function Test($data)
    {
        $response = array($data);
        echo json_encode($response);
    }

    public static function VerifyUnVerify($data)
    {
        $response = array();
        if ($data->update == "one") {
            if ($data->type == "verified") {
                $sql = "UPDATE alumnistudents SET Verified='Unverified' where MemberCode='$data->id';";
                $result = AlumniStudents::Connect()->query($sql);
                if ($result) {
                    $response = array("error" => "", "type" => "blocked", "response" => "Account Has Been Blocked");
                } else
                    $response = array("error" => AlumniStudents::Connect()->error);
            } else if ($data->type == "Unverified") {
                $sql = "UPDATE alumnistudents SET Verified='verified' where MemberCode='$data->id';";
                $result = AlumniStudents::Connect()->query($sql);
                if ($result) {
                    $response = array("error" => "", "type" => "active", "response" => "Account Has Been Verified✔.");

                    $sendEmailMessage="SELECT FullName,Email from alumnistudents where MemberCode='$data->id';";
                    $resultRow=AlumniStudents::Connect()->query($sendEmailMessage);
                    if($resultRow){
                        $row=$resultRow->fetch_assoc();
                        $mail= new Mail();
                        $mail->setFullName($row['FullName']);
                        $mail->setReceiverEmail($row['Email']);
                        $mail->setMessageContent("<h2> Congrats! ".$row['FullName']. "</h2> <p style='line-height: 1.7'>For you graduated from jamhuuriya university, your account has been verified and now you are one of the members of <strong>alumni association</strong> students
                        of jamhuuriya university. you can use this site and you can join our events. <strong>Welcome Again</strong></p> <a href='http://localhost:5173/login' style='text-decoration: none; padding: 10px; font-size: 16px; background: #6c3fff; color: white;'>Login Here!</a>. ");
                        $mail->sendEmail();

                    }
                } else
                    $response = array("error" => AlumniStudents::Connect()->error);
            }
        } else {

            $sql = "UPDATE alumnistudents SET Verified='verified' ;";
            $result = AlumniStudents::Connect()->query($sql);
            if ($result) {
                $response = array("error" => "", "type" => "active", "response" => "All Accounts Has Been Activated Successfully");
            } else
                $response = array("error" => AlumniStudents::Connect()->error);
        }


        echo json_encode($response);
    }


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
    public static function fetchStudentDetail($data)
    {
        $response = array();


        $sql = "Select * from alumnistudents where MemberCode='$data->id'";
        $result = AlumniStudents::Connect()->query($sql);
        if ($result) {
            $row = $result->fetch_assoc();
            $response = array("error" => "", "response" => $row);
        } else
            $response = array("error" => AlumniStudents::Connect()->error);

        echo json_encode($response);
    }
    public static function verifyExistanse($data)
    {
        $response = array();


        $sql = "Select * from alumnistudents where Email='$data->email' OR UniversityID='$data->uniID';";
        $result = AlumniStudents::Connect()->query($sql);
        if ($result) {
            if(mysqli_num_rows($result)>0)
                $response = array("error" => "","HasData"=>true, "response" => "Email Or UniversityID Already Taken.");
            else
                $response = array("error" => "", "HasData" => false, "response" => "Email Or UniversityID Already Taken.");

        } else
            $response = array("error" => AlumniStudents::Connect()->error);

        echo json_encode($response);
    }
    public static function fetchCurrentPassword($data)
    {
        $response = array();


        $sql = "Select Password from alumnistudents where MemberCode='$data->id'";
        $result = AlumniStudents::Connect()->query($sql);
        if ($result) {
            $row = $result->fetch_assoc();
            $response = array("error" => "", "response" => $row);
        } else
            $response = array("error" => AlumniStudents::Connect()->error);

        echo json_encode($response);
    }
    public static function VerifyUserCredentials($data)
    {
        $response = array();


        $sql = "Select * from alumnistudents where Email='$data->email' AND Password='$data->password'";
        $result = AlumniStudents::Connect()->query($sql);
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                $row = $result->fetch_assoc();
                $response = array("error" => "", "isValid" => true, "response" => $row);
            } else
                $response = array("error" => "", "isValid" => false, "response" => "Invalid Username Or Password");
        } else
            $response = array("error" => AlumniStudents::Connect()->error);

        echo json_encode($response);
    }
    public static function UpdateStudentProfile($data)
    {
        $response = array();

        $sql = "Update  alumnistudents set FullName='$data->FullName',Email='$data->Email' where MemberCode='$data->MemberCode'";
        $result = AlumniStudents::Connect()->query($sql);
        if ($result) {
            $response = array("error" => "", "response" => "Your Profile Has Been Updated");
        } else
            $response = array("error" => AlumniStudents::Connect()->error);

        echo json_encode($response);
    }
    public static function ChangePassword($data)
    {
        $response = array();

        $sql = "Update  alumnistudents set Password='$data->newPass' where MemberCode='$data->id'";
        $result = AlumniStudents::Connect()->query($sql);
        if ($result) {
            $response = array("error" => "", "response" => "Your Security Has Been Changed");
        } else
            $response = array("error" => AlumniStudents::Connect()->error);

        echo json_encode($response);
    }


    public static function CheckRequest($value)
    {
        switch ($value->action) {
            case "ReadProfile":
                AlumniStudents::ReadProfile();
                break;

            case "UpdateStudentProfile":
                AlumniStudents::UpdateStudentProfile($value->data);
                break;
            case "RegisterGraduate":
                AlumniStudents::RegisterGraduate($value->data);
                break;
            case "VerifyUnVerify":
                AlumniStudents::VerifyUnVerify($value->data);
                break;
            case "fetchStudentDetail":
                AlumniStudents::fetchStudentDetail($value->data);
                break;
            case "fetchCurrentPassword":
                AlumniStudents::fetchCurrentPassword($value->data);
                break;
            case "VerifyUserCredentials":
                AlumniStudents::VerifyUserCredentials($value->data);
                break;
            case "ChangePassword":
                AlumniStudents::ChangePassword($value->data);
                break;
            case "verifyExistanse":
                AlumniStudents::verifyExistanse($value->data);
                break;
            case "readtop3":
                AlumniStudents::readtop3();
                break;
        }
    }
}




$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case "POST":
        $clientData = json_decode(file_get_contents("php://input"));

        AlumniStudents::CheckRequest($clientData);
}
