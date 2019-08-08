<?php

  include("conn.php");


  if(isset($_GET['searchquery'])){

$query_data = mysqli_real_escape_string($conn, $_GET['searchquery']);

$query_data = htmlspecialchars($query_data);

if(strlen($query_data)>30){
  header('location: index.php');
}else{

//php for item info search//

$read = "SELECT * FROM elmtree_items
                INNER JOIN elmtree_photos ON elmtree_items.item_id = elmtree_photos.item_id
                INNER JOIN elmtree_categories ON elmtree_items.category_id = elmtree_categories.category_id
                WHERE (`item_name` LIKE '%$query_data%') OR (`item_description` LIKE '%$query_data%')
                OR (`item_keywords` LIKE '%$query_data%') OR (`category` LIKE '%$query_data%')
                ORDER BY post_date DESC
                ";
$result = $conn->query($read);


if(!$result){
  echo $conn->error;
}

}

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

  <div class = 'row recentlyadded'>
    <?php
            echo"
          <h2>You searched for: $query_data</h2>
            ";

      ?>
  </div>






  <div class = 'row myitemrows'>

  <?php

  if(!$result){

  				echo $conn->error;

  			}else{
          $num = $result->num_rows;
        if($num >0){

  		while($row = $result->fetch_assoc() ){

  			$name_data = $row['item_name'];
  			$price_data = $row['item_price'];
  			$desc_data= $row['item_description'];
  			$item_id_data = $row['item_id'];
  			$post_date_data = $row['post_date'];
  			$photo_data = $row['photo_path'];






         echo"
         <div class= 'xs-col-12 sm-col-6 md-col-3 mycards'>

<a href='items.php?id=$item_id_data'>	<div class='card border-primary mb-3'>
   <div class='card-header'><strong>$name_data</strong></div>
   <img class='card-img-top' src='imgs/$photo_data' alt='Card image cap'>
           <div class='card-body'>
             <h5><strong>&pound$price_data</strong></h5>
             <p class='card-text'>$desc_data</p>

           </div>



</div></a>


       </div>

                  ";
            }
                }else{
                  echo"
                  <div class = 'nofind'>
                  <h3>I'm sorry we couldn't find anything like that!</h3>
                  </div>
                  ";
                }
              }
  ?>



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

</body>

</html>
