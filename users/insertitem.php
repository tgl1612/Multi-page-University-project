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
               $item_data = mysqli_real_escape_string($conn, $_POST['postitem']);
               $desc_data = mysqli_real_escape_string($conn, $_POST['postdescription']);
               $price_data = $_POST['postprice'];
               $keyword_data = mysqli_real_escape_string($conn, $_POST['postkeywords']);
               $cateinfo_data =$_POST['postcategory'];

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
             if(empty($item_data) || empty($desc_data) || empty($price_data) || empty($keyword_data) || empty($cateinfo_data)){
               $errors_in_upload = 1;
               echo"We're sorry but one of your fields was empty! Please try again";
             }

             //item name validation//
             $item_data = validate_input($item_data);


             //item name length//
             if((strlen($item_data) <2) || (strlen($item_data) > 50)){
               $errors_in_upload = 1;
               echo"Your item name is not the correct length! Please try again";
             }

             //description validation//
             if((strlen($desc_data) <1) ||(strlen($desc_data) >250)){
               $errors_in_upload = 1;
               echo"Description length too long! Please try again";
             }




             //price length validation
             if(strlen((string)$price_data) > 10){
               $errors_in_upload = 1;
               echo "Price too long! Please try again";
             }

             //keywords validation//
             $keyword_data = validate_input($keyword_data);
             if(strlen($keyword_data)>60){
               $errors_in_upload = 1;
               echo"Keyword information too long! Please try again";
             }





            //file validation file 1 //
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


               //file validation file 2 //
               //getting file info//




               $upload1_data = $_FILES['uploadfile1']['name'];
               $temp1 = $_FILES['uploadfile1']['tmp_name'];
               $upload1_size = $_FILES['uploadfile1']['size'];
               $upload1_error = $_FILES['uploadfile1']['error'];


               //check file extensions//
                 $upload_explode = explode('.', $upload1_data);
                 $upload_extension1 = strtolower(end($upload_explode));

                 $accepted_extensions = array('jpeg', 'jpg', 'png');

                 if(in_array($upload_extension1, $accepted_extensions)){
                   if($upload1_error === 0){
                     if($upload1_size < 500000){
                       $upload1_uniquename = uniqid('',true).'.'.$upload_extension;

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
                 //file validation file 3 //
                 //getting file info//



                 $upload2_data = $_FILES['uploadfile2']['name'];
                 $temp2 = $_FILES['uploadfile2']['tmp_name'];
                 $upload2_size = $_FILES['uploadfile2']['size'];
                 $upload2_error = $_FILES['uploadfile2']['error'];


                 //check file extensions//
                   $upload_explode = explode('.', $upload_data);
                   $upload_extension2 = strtolower(end($upload_explode));

                   $accepted_extensions = array('jpeg', 'jpg', 'png');

                   if(in_array($upload_extension2, $accepted_extensions)){
                     if($upload2_error === 0){
                       if($upload2_size < 500000){
                         $upload2_uniquename = uniqid('',true).'.'.$upload_extension;

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

               //query to get category id for table
               $readcatid = "SELECT * FROM elmtree_categories WHERE category = '$cateinfo_data'";
               $resultcatid = $conn->query($readcatid);
               while($row =$resultcatid->fetch_assoc()){
                    $catefk_id = $row['category_id'];
                  }

               //move files to new location
               move_uploaded_file($temp, "../imgs/".$upload_uniquename);
               move_uploaded_file($temp1, "../imgs/".$upload1_uniquename);
               move_uploaded_file($temp2, "../imgs/".$upload2_uniquename);
               //insert query when all input has been checked
               $insert = "INSERT INTO elmtree_items (item_name, item_description, item_price, post_date, item_keywords, item_status, user_id, category_id)
               VALUES('$item_data','$desc_data', '$price_data', NOW(), '$keyword_data', 'Available', '$user_id', '$catefk_id')";


               $resultinsert =$conn ->query($insert);

               $readitemid ="SELECT * FROM elmtree_items WHERE item_name= '$item_data'";
               $resultitemid = $conn->query($readitemid);
               while($row=$resultitemid->fetch_assoc()){
                 $itemfk_id = $row['item_id'];
               }

               if(!$resultitemid){
                 echo $conn->error;
               }

               $insertphotos = "INSERT INTO elmtree_photos(photo_path, photo_path2, photo_path3, item_id)
               VALUES('$upload_uniquename', '$upload1_uniquename', '$upload2_uniquename', $itemfk_id)";


               $resultinsertphotos = $conn->query($insertphotos);

               if(!$resultinsertphotos){
                 echo $conn->error;
               }

               echo"<p>Your item has been added successfully!</p>

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
