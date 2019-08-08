<?php

session_start();

include('conn.php');

if(isset($_POST['submit'])){

$user = mysqli_real_escape_string($conn, $_POST['postuser']);
$pass = mysqli_real_escape_string($conn, $_POST['postpw']);

$checkuser = "SELECT * FROM elmtree_users WHERE user ='$user' AND pw = MD5('$pass')";

$result = $conn->query($checkuser);

if(!$result){
	echo $conn->error;
}


//VALIDATE THE FIELDS

//create function to help validation
function validate_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}

//check if fields are empty
if(empty($user) || empty($pass)){
	header('location: login.php');
}

$user = validate_input($user);
if (!preg_match("/^[a-zA-Z0-9]*$/",$user)) {
	header('location: login.php');
}

//username length//
if((strlen($user) <3) || (strlen($user) > 16)){
	header('location: login.php');
}

//password validation//
$pass = validate_input($pass);
if((strlen($pass) <8) ||(strlen($pass) >16)){
	header('location: login.php');
}

if (!preg_match("/^[a-zA-Z0-9]*$/",$pass)) {
header('location: login.php');
}







//find user info in database//
$num = $result->num_rows;
if($num > 0){

	while($row = $result->fetch_assoc()){

		$myuser = $row['user'];
		$myuser_id = $row['user_id'];

		$_SESSION['40023794_elmtree_user']= $myuser;
		$_SESSION['40023794_elmtree_userid']= $myuser_id;
	}



	if($myuser === 'admin'){
		header('location: admin/index.php');
	}else{
		header('location: users/index.php');
	}

}else{
	header('location: login.php');
}


}else{
	header('location: login.php');
}










?>
