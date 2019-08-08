<?php

session_start();

if(!isset($_SESSION['40023794_elmtree_user'])){
  header('location: ../index.php');
}

$user_name = 	$_SESSION['40023794_elmtree_user'];
$user_id = $_SESSION['40023794_elmtree_userid'];

  include("../conn.php");

//Nav bar php for categories//
 $readcat ="SELECT * FROM elmtree_categories";
 $resultcat = $conn->query($readcat);

if(!$resultcat){
  echo $conn->error;
}


?>


 <!DOCTYPE html>
 <html>
 	<head>
 		<meta charset="utf-8">
 		<meta http-equiv="X-UA-Compatible" content="IE=edge">
 		<meta name="viewport" content="width=device-width, initial-scale=1">
 		<title>Elmtree, buy and sell your stuff!</title>
 		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.css" />
     <link rel="stylesheet" href="../ui.css">
 	</head>

 	<body>

     <div id="wrap">
       <div id = "main">

 			<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark ">
 				<div class = "container-fluid mynav">

 				<a class="navbar-brand logo mr-auto" href="index.php"><img src="../imgs/mylogo.png" id="mylogo"></a>

 				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
 	    		<span class="navbar-toggler-icon"></span>
 	  		</button>

   			<div class="collapse navbar-collapse" id="navbarSupportedContent">
     			<ul class="navbar-nav mr-auto">
 						<li class="nav-item dropdown mymenu">
 							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
 								Categories
 							</a>
 							<div class="dropdown-menu dropdown-menu-left navdropdown" aria-labelledby="navbarDropdown">
                 <?php
 									while($row = $resultcat->fetch_assoc() ){

 										$category_data = $row['category'];
                     $category_id = $row['category_id'];

 										echo"

 								<a class='dropdown-item' href='category.php?categories=$category_id'>$category_data</a>

                 	";

 							}
 								?>
 							</div>
 						</li>

 					</ul>

           <form class="mx-2 my-auto d-inline w-100" action = 'search.php' method = 'GET'>
 					   <div class="input-group mysearchinput">
 					      <input required type="text" id="myinputfield" class="form-control" name ='searchquery' placeholder="I'm searching for... " maxlength="30">
 					       <span class="input-group-append">
 					       <button class="btn btn-outline-secondary searchbutton" type="submit">GO</button>
 					       </span>
 					    </div>
 					</form>

 					<ul class="navbar-nav ml-auto">
 						<li class="nav-item active">
 							<a class="nav-link" id="mylogin" href="myaccount.php"><?php echo $user_name?><span class="sr-only">(current)</span></a>
 						</li>

 						<li class="nav-item">
 							<a class="nav-link join" href="logout.php">Logout</a>
 						</li>
 				</ul>

   	</div>
 </div>
 </nav>


 <div class= 'container-fluid mycategoryitems'>
     <div class = 'row myitemrows'>
       <div class= 'col-12 myiteminfo'>
         <div class='row'>

           <div class='col-12'>

             <?php
             //form post info
             if(isset($_POST['submit'])) {

               $pass_original_data = mysqli_real_escape_string($conn, $_POST['pworiginal']);
               $pass_new_data = mysqli_real_escape_string($conn, $_POST['pwnew']);
               $email_data = mysqli_real_escape_string($conn, $_POST['editemail']);
               $phone_data = mysqli_real_escape_string($conn, $_POST['editphone']);
               $education_data = mysqli_real_escape_string($conn, $_POST['editeducation']);


             //create function to help validation
             function validate_input($data) {
                 $data = trim($data);
                 $data = stripslashes($data);
                 $data = htmlspecialchars($data);
                 return $data;
               }

               //count errors//
               $errors_in_upload = 0;






             //email validation//
             $email_data = validate_input($email_data);
             if (!filter_var($email_data, FILTER_VALIDATE_EMAIL)) {
               $errors_in_upload = 1;
               echo "Email not in correct format";
             }

             if(strlen($email_data) >50){
               $errors_in_upload = 1;
               echo "Email address too long! Please use a different email address";
             }


             //phone validation//
             $phone_data = validate_input($phone_data);
             if(strlen($phone_data)>11){
               $errors_in_upload = 1;
               echo"Phone number too long! Please try again";

             }

             //education validation//
             $education_data = validate_input($education_data);
             if(strlen($education_data)>25){
               $errors_in_upload = 1;
               echo"Education information too long! Please try again";
             }



             if(!empty($pass_original_data) && !empty($pass_new_data)){



               //password validation//
               $originalpw_query = "SELECT * FROM elmtree_users WHERE user_id = '$user_id' ";
               $originalpw_read = $conn->query($originalpw_query);

               while($row = $originalpw_read->fetch_assoc()){
                 $originalpw = $row['pw'];
               }


               if(MD5($pass_original_data) == $originalpw){

             $pass_new_data = validate_input($pass_new_data);
             if((strlen($pass_new_data) <8) ||(strlen($pass_new_data) >16)){
               $errors_in_upload = 1;
               echo"Password length incorrect! Please try again";
             }

             if (!preg_match("/^[a-zA-Z0-9]*$/",$pass_new_data)) {
               $errors_in_upload = 1;
               echo"New password not accepted! Please try again";
             }

             if ($errors_in_upload == 0){

             $updatenewpw = "UPDATE elmtree_users SET pw = MD5('$pass_new_data')
                            WHERE user_id = $user_id;
             ";


             $resultnewpw = $conn->query($updatenewpw);
           }

           }
         }

             //file validation//
             //getting file info//

             if(!empty($_FILES['editfile']['name'])){

             $upload_data = $_FILES['editfile']['name'];
             $temp = $_FILES['editfile']['tmp_name'];
             $upload_size = $_FILES['editfile']['size'];
             $upload_error = $_FILES['editfile']['error'];


             //check file extensions//
               $upload_explode = explode('.', $upload_data);
               $upload_extension = strtolower(end($upload_explode));

               $accepted_extensions = array('jpeg', 'jpg', 'png');

               if(in_array($upload_extension, $accepted_extensions)){
                 if($upload_error === 0){
                   if($upload_size < 500000){
                     $upload_uniquename = uniqid('',true).'.'.$upload_extension;
                     //move file to new location
                     move_uploaded_file($temp, "../imgs/".$upload_uniquename);

                     //query to update image
                     $updatephotos = "UPDATE elmtree_users SET user_photo = '$upload_uniquename'
                      WHERE user_id = '$user_id'
                    ";

                    $resultupdatephotos = $conn->query($updatephotos);

                    if(!$resultupdatephotos){
                      echo $conn->error;
                    }

                   }else{
                     $errors_in_upload = 1;
                     echo "We're sorry. Your photo was too big to upload! Please try again";
                   }

                 }else {
                   $errors_in_upload = 1;
                   echo "We're sorry. There was an error during the file upload! Please try again";
                 }
               }else{
                   $errors_in_upload = 1;
                   echo "We're sorry. We only accept jpeg, jpg or png for photo uploads! Please try again";
               }


             }


             if($errors_in_upload ==0){

               //insert query when all input has been checked
               $update = "UPDATE elmtree_users SET user_email ='$email_data', user_phone ='$phone_data', user_school = '$education_data' WHERE user_id = $user_id
                ";

               $resultinsert =$conn ->query($update);

               echo"<p>You have updated your personal information!</p>

                   ";

               if(!$resultinsert){
                 echo $conn->error;
               }



             }

             }
             ?>


         </div>

 </div>

       </div>
     </div>
 </div>

 </div>
 </div>


   <footer class="page-footer">
     <div class="myfooter">© 2019 Copyright: TLewis
     </div>
   </footer>



 		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
 		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
 		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.js"></script>

 </body>

 </html>
