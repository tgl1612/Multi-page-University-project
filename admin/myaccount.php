<?php

session_start();

if(!isset($_SESSION['40023794_elmtree_user'])){
  header('location: ../index.php');
}

$user_name = 	$_SESSION['40023794_elmtree_user'];
$user_id = $_SESSION['40023794_elmtree_userid'];

  include("../conn.php");







//get user info query//
$read = "SELECT * FROM elmtree_users WHERE elmtree_users.user_id = '$user_id' ";
$result = $conn->query($read);

if(!$result){
  echo $conn->error;
}

//get item info and photos query//
$readitems = "SELECT * FROM elmtree_items
                INNER JOIN elmtree_photos ON elmtree_items.item_id = elmtree_photos.item_id

                WHERE elmtree_items.user_id = '$user_id'
                ORDER BY post_date DESC";
$resultitems = $conn->query($readitems);

if(!$resultitems){
  echo $conn->error;
}





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
  <?php

  if(!$result){

  				echo $conn->error;

  			}else{

  		while($row = $result->fetch_assoc() ){



        //user info for bio
        $user_data = $row['user'];
        $user_photo_data = $row['user_photo'];
        $user_email_data = $row['user_email'];
        $user_phone_data = $row['user_phone'];
        $user_education_data = $row['user_school'];

          echo"

         <div class = 'col-12 itemname'>
            <h2>$user_name</h2>
         </div>



         <div class ='row'>

         <div class = 'col-xs-6 col-sm-6 col-md-6 largeimage'>


            <img src='../imgs/$user_photo_data' alt='Responsive image' class ='image1 image-fluid' />


            <div class = 'row smallimages'>



           </div>


          </div>

          <div class = 'col-xs-6 col-sm-6 col-md-6'>

          <h4>Phone: $user_phone_data</h4>
          <h4>Email: $user_email_data</h4>
          <h4>Education: $user_education_data</h4>
          <h4>Rating reference: $user_id</h4>

          <div class ='userinfo'>

          <a href='editbio.php'><button type='button' class='btn btn-primary contact'>Edit bio</button></a>
          <a href='viewall.php'><button type='button' class='btn btn-primary contact'>View users</button></a>
          </div>

          </div>
          </div>




                 ";



                }
              }

          ?>


 </div>



</div>



</div>

</div>
</div>

	<!-- Footer -->
  <footer class="page-footer">
    <div class="myfooter">© 2019Copyright: TLewis
    </div>
  </footer>



		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.js"></script>

</body>

</html>
