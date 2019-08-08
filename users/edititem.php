<?php

session_start();

if(!isset($_SESSION['40023794_elmtree_user'])){
  header('location: ../index.php');
}

$user_name = 	$_SESSION['40023794_elmtree_user'];
$user_id = $_SESSION['40023794_elmtree_userid'];

  include("../conn.php");

//Nav bar query for categories//
	$readcat ="SELECT * FROM elmtree_categories";
	$resultcat = $conn->query($readcat);

if(!$resultcat){
  echo $conn->error;
}

//query for form category dropdown
$read ="SELECT * FROM elmtree_categories";
$result = $conn->query($read);

if(!$result){
echo $conn->error;
}

$myeditid = $conn->real_escape_string($_GET['editid']);


$readform = "SELECT * FROM elmtree_items
              INNER JOIN elmtree_categories ON elmtree_items.category_id = elmtree_categories.category_id
              INNER JOIN elmtree_photos ON elmtree_items.item_id = elmtree_photos.item_id
              WHERE elmtree_items.item_id = $myeditid";

$resultform = $conn->query($readform);

if(!$resultform){
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

            <h5 class ='regwelc1'>Edit item</h5>

            <form action='editprocess.php' method='POST' enctype='multipart/form-data'>
            <?php

              while($row = $resultform->fetch_assoc()){

                $item_id_data = $row['item_id'];
                $item_name_data = $row['item_name'];
                $item_desc = $row['item_description'];
                $item_price = $row['item_price'];
                $item_keyword = $row['item_keywords'];
                $item_status = $row['item_status'];
                $item_catid = $row['category_id'];
                $item_cat = $row['category'];
                $item_photo1 = $row['photo_path'];
                $item_photo2 = $row['photo_path2'];
                $item_photo3 = $row['photo_path3'];
                echo "
                  <input type = 'hidden' value ='$item_id_data' name= 'myeditid'>
                  <div class='form-group'>
                    <label for='itemname'>Edit Item Name</label>
                    <input type ='text' class='form-control' name ='nameedit' value = '$item_name_data' maxlength = '50'>

                  </div>
                  <div class='form-group'>
                    <label for='Itemdesc'>Edit Description</label>
                    <input type = 'text' class='form-control' name ='descedit' value = '$item_desc' maxlength = '250'>
                </div>

                <div class='form-group'>
                <label for='itemprice'>Edit Price (£)</label>
                <input type='number' step='0.01' required name ='priceedit' class='form-control' value = '$item_price' maxlength ='10'>
                </div>

                <div class='form-group'>

                <label for='inputState'>Change Category</label>
                   <select name = 'editcategory' id='inputState' class='form-control'>
                     <option selected>$item_cat</option>
                     <option>Art & Collectibles</option>
                     <option>Computers & Electronics</option>
                     <option>Clothing & Jewellery</option>
                     <option>Education</option>
                     <option>Home & Garden</option>
                     <option>Movies, Music & Books</option>
                     <option>Phones & Accessories</option>
                     <option>Sports & Leisure</option>
                     <option>TV, Cameras & Audio</option>
                     <option>Vehicles</option>
                     <option>Video Games & Consoles</option>
                     <option>Other Goods</option>



                  </select>
                </div>

                <div class='form-group'>

                <label for='inputstatus'>Change Status</label>
                   <select id='inputState' name='editstatus' class='form-control'>
                     <option selected>$item_status</option>
                     <option>Available</option>
                     <option>Sold</option>

                  </select>
                </div>

                <div class='form-group'>
                <label for='itemkeywords'>Change Keywords</label>
                <input type='text' required name = 'editkeywords' class='form-control' value = '$item_keyword' maxlength='60'>
                </div>


                <div class='form-group'>
                  <label for='item_photo1'> Change Main Photo: </label>
                  <input type='file'  name='editfile' id='userphoto' aria-describedby='fileHelp1'>
                  <small id='fileHelp1' class='form-text text-muted'>This will be your item's main photo. We accept .jpg, .jpeg or .png files</small>
                </div>


                <div class='form-group'>
                  <label for='item_photo2'>Change Second Photo: </label>
                  <input type='file' name='editfile1' id='userphoto' aria-describedby='fileHelp'>
                  <small id='fileHelp' class='form-text text-muted'>We accept .jpg, .jpeg or .png files</small>
                </div>
                <div class='form-group'>
                  <label for='item_photo3'>Change Third Photo: </label>
                  <input type='file'  name='editfile2' id='userphoto' aria-describedby='fileHelp'>
                  <small id='fileHelp' class='form-text text-muted'>We accept .jpg, .jpeg or .png files</small>
                </div>





                ";

              }

             ?>

              <div class ="Myloginbut">
              <button type="submit" name="submit" class="btn btn-primary loginbut">Edit</button>
            </div>
            </form>
        </div>

</div>

      </div>
    </div>
</div>

</div>
</div>

	<!-- Footer -->
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