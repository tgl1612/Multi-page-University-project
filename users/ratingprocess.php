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

          if(isset($_POST['submit'])){

            $seller_name =  mysqli_real_escape_string($conn, $_POST['sellername']);
            $seller_id =  mysqli_real_escape_string($conn, $_POST['sellerid']);
            $seller_ref =  mysqli_real_escape_string($conn, $_POST['sellerref']);
            $rating_data =  mysqli_real_escape_string($conn, $_POST['rating']);


          if($seller_ref == $seller_id){

            if($seller_ref != $user_id){


            if($rating_data>0 && $rating_data <11){

              $insertrating = "INSERT INTO elmtree_ratings (user_id, user_rating) VALUES ($seller_id, $rating_data) ";

              $resultrating = $conn->query($insertrating);


              echo "Rating successful!";

              if(!$resultrating){
                echo $conn->error;
              }




            }else{
              echo "Please enter a rating between 1 and 10";
            }

          }else{
            echo "You cannot rate yourself!";
          }


          }else{
            echo "The seller's unique reference number was incorrect. Please try again";
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