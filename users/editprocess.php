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
               $item_id =  $_POST['myeditid'];
               $item_data =$_POST['nameedit'];
               $desc_data = $_POST['descedit'];
               $price_data = $_POST['priceedit'];
               $keyword_data = $_POST['editkeywords'];
               $cateinfo_data =$_POST['editcategory'];
               $status_data = $_POST['editstatus'];

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
               //file validation file 2 //
               //getting file info//


                if(!empty($_FILES['editfile1']['name'])){

               $upload1_data = $_FILES['editfile1']['name'];
               $temp1 = $_FILES['editfile1']['tmp_name'];
               $upload1_size = $_FILES['editfile1']['size'];
               $upload1_error = $_FILES['editfile1']['error'];


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

               }
                 //file validation file 3 //
                 //getting file info//


                  if(!empty($_FILES['editfile2']['name'])){
                 $upload2_data = $_FILES['editfile2']['name'];
                 $temp2 = $_FILES['editfile2']['tmp_name'];
                 $upload2_size = $_FILES['editfile2']['size'];
                 $upload2_error = $_FILES['editfile2']['error'];


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



                 }







             if($errors_in_upload ==0){

               //query to get category id for table
               $readcatid = "SELECT * FROM elmtree_categories WHERE category = '$cateinfo_data'";
               $resultcatid = $conn->query($readcatid);
               while($row =$resultcatid->fetch_assoc()){
                    $catefk_id = $row['category_id'];
                  }





               //update query when all input has been checked
               $update = "UPDATE elmtree_items SET item_name = '$item_data', item_description ='$desc_data', item_price = '$price_data', item_keywords= '$keyword_data', item_status='$status_data', category_id= '$catefk_id'
                          WHERE item_id = '$item_id'
               ";


               $resultupdate =$conn ->query($update);



               $readitemid ="SELECT * FROM elmtree_items WHERE item_name= '$item_data'";
               $resultitemid = $conn->query($readitemid);
               while($row=$resultitemid->fetch_assoc()){
                 $itemfk_id = $row['item_id'];
               }

               if(!$resultitemid){
                 echo $conn->error;
               }

               if(!empty($_FILES['editfile']['name'])){
                 move_uploaded_file($temp, "../imgs/".$upload_uniquename);
                 $updatephotos = "UPDATE elmtree_photos SET photo_path = '$upload_uniquename'
                  WHERE item_id = '$itemfk_id'
                ";

                $resultupdatephotos = $conn->query($updatephotos);

                if(!$resultupdatephotos){
                  echo $conn->error;
                }

               }
               if(!empty($_FILES['editfile1']['name'])){
                 move_uploaded_file($temp1, "../imgs/".$upload1_uniquename);
                 $updatephotos1 = "UPDATE elmtree_photos SET photo_path2 = '$upload1_uniquename'
                  WHERE item_id = '$itemfk_id'
                ";


                 $resultupdatephotos1 = $conn->query($updatephotos1);

                 if(!$resultupdatephotos1){
                   echo $conn->error;
                 }




               }
               if(!empty($_FILES['editfile2']['name'])){
                 move_uploaded_file($temp2, "../imgs/".$upload2_uniquename);
                 $updatephotos3 = "UPDATE elmtree_photos SET photo_path3 = '$upload2_uniquename'
                  WHERE item_id = '$itemfk_id'";


                 $resultupdatephotos3 = $conn->query($updatephotos3);

                 if(!$resultupdatephotos3){
                   echo $conn->error;
                 }

               }



               echo"<p>Your item has been updated successfully!</p>

                   ";

               if(!$resultupdate){
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
