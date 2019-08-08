<?php

include('conn.php');

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
     <link rel="stylesheet" href="ui.css">
 	</head>

 	<body>

     <div id="wrap">
       <div id = "main">

 			<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark ">
 				<div class = "container-fluid mynav">

 				<a class="navbar-brand logo mr-auto" href="index.php"><img src="imgs/mylogo.png" id="mylogo"></a>

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
 							<a class="nav-link" id="mylogin" href="login.php">Login<span class="sr-only">(current)</span></a>
 						</li>

 						<li class="nav-item">
 							<a class="nav-link join" href="register.php">Join</a>
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
               $user_data = mysqli_real_escape_string($conn, $_POST['postuser']);
               $pass_data = mysqli_real_escape_string($conn, $_POST['postpw']);
               $email_data = mysqli_real_escape_string($conn, $_POST['emailuser']);
               $phone_data = mysqli_real_escape_string($conn, $_POST['phoneuser']);
               $education_data = mysqli_real_escape_string($conn, $_POST['educationuser']);


             //create function to help validation
             function validate_input($data) {
                 $data = trim($data);
                 $data = stripslashes($data);
                 $data = htmlspecialchars($data);
                 return $data;
               }

               //count errors//
               $errors_in_upload = 0;



             //empty validation//
             if(empty($user_data) || empty($pass_data) || empty($email_data) || empty($phone_data) || empty($education_data)){
               $errors_in_upload = 1;
               echo"We're sorry but one of your fields was empty! Please try again";
             }

             //username validation//
             $user_data = validate_input($user_data);
             if (!preg_match("/^[a-zA-Z0-9]*$/",$user_data)) {
               $errors_in_upload = 1;
               echo "Username not accepted! Please try again";
             }

             //username length//
             if((strlen($user_data) <3) || (strlen($user_data) > 16)){
               $errors_in_upload = 1;
               echo"Your username is not the correct length! Please try again";
             }

             //username exists//
             $username_repeat_query = "SELECT * FROM elmtree_users WHERE user = '$user_data'";
             $username_repeat_result = $conn->query($username_repeat_query);
             $username_repeat = $username_repeat_result->fetch_assoc();

             if($username_repeat){
               $errors_in_upload = 1;
               echo "We're sorry, that username already exists! Please choose a different name";
             }



             //password validation//
             $pass_data = validate_input($pass_data);
             if((strlen($pass_data) <8) ||(strlen($pass_data) >16)){
               $errors_in_upload = 1;
               echo"Password length incorrect! Please try again";
             }

             if (!preg_match("/^[a-zA-Z0-9]*$/",$pass_data)) {
               $errors_in_upload = 1;
               echo"Password not accepted! Please try again";
             }


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


             //file validation//
             //getting file info//
             $upload_data = $_FILES['uploadfile']['name'];
             $temp = $_FILES['uploadfile']['tmp_name'];
             $upload_size = $_FILES['uploadfile']['size'];
             $upload_error = $_FILES['uploadfile']['error'];


             //check file extensions//
               $upload_explode = explode('.', $upload_data);
               $upload_extension = strtolower(end($upload_explode));

               $accepted_extensions = array('jpeg', 'jpg', 'png');

               if(in_array($upload_extension, $accepted_extensions)){
                 if($upload_error === 0){
                   if($upload_size < 500000){
                     $upload_uniquename = uniqid('',true).'.'.$upload_extension;

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


             if($errors_in_upload ==0){
               //move file to new location
               move_uploaded_file($temp, "imgs/".$upload_uniquename);
               //insert query when all input has been checked
               $insert = "INSERT INTO elmtree_users (user, pw, user_photo, user_email, user_phone, user_school)
               VALUES('$user_data',MD5('$pass_data'), '$upload_uniquename', '$email_data', '$phone_data', '$education_data')";

               $resultinsert =$conn ->query($insert);

               echo"<p>You have registered as a new user!</p>
                   <p>Click the Login button at the top of the screen to go to your profile!</p>
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

 </html>-->
