<?php

session_start();

if(!isset($_SESSION['40023794_elmtree_user'])){
  header('location: ../index.php');
}

$user_name = 	$_SESSION['40023794_elmtree_user'];
$user_id = $_SESSION['40023794_elmtree_userid'];

  include("../conn.php");



 if(isset($_POST['submit'])) {

   $messageinfo = mysqli_real_escape_string($conn, $_POST['postmessage']);
   $messageto = mysqli_real_escape_string($conn, $_POST['sellerid']);





   //message length validation
   if(strlen($messageinfo) > 500){

     echo "Message too long to submit!";

   }else{

     $insertmessage = "INSERT INTO elmtree_message (user_to_id, user_from_id, message, timesent)
                        VALUES ('$messageto', '$user_id', '$messageinfo', NOW())";

    $resultmessage = $conn->query($insertmessage);

    if($resultmessage){
      header("location: message.php?sender=$messageto");
    }else{
      echo $conn->error;
    }


   }

 }

?>
