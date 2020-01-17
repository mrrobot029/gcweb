<?php
require_once "connect.php";
session_start();
$valid = array('success' => false, 'message' => '');
$facno = "";

if(isset($_POST['fa_empnumber']) && isset($_POST['fa_password'])){


	$fa_empnumber = $_POST['fa_empnumber'];
	$fa_password = $_POST['fa_password'];

	$sql = "SELECT * FROM tbl_faculty WHERE fa_empnumber='$fa_empnumber'";
	$query = mysqli_query($conn,$sql);

	if(mysqli_num_rows($query)>0){
		while($data = mysqli_fetch_assoc($query)){
		
			$valid['fa_empnumber'] =  $data['fa_empnumber'];
			$_SESSION['fa_empnumber'] = $data['fa_empnumber'];
			$_SESSION['fa_fname'] = $data['fa_fname'];
			$dbpass = $data['fa_password'];
		}


		if(password_verify($fa_password,$dbpass)){
			$valid['success'] = true;
			$valid['message'] = "Login success!";
		}else{
			
			$valid['message'] = "Invalid ID or password.";
		}


	}else{
		$valid['message'] = "Invalid ID or password.";
	}

	echo json_encode($valid);

}



?>