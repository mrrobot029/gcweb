<?php
require_once 'connect.php';
$data = [];

if(isset($_GET['CYB'])){
	$CYB = $_GET['CYB'];
	$sql = "SELECT * FROM tbl_classes WHERE cl_block='$CYB'";
	$query = mysqli_query($conn,$sql);

	if(mysqli_num_rows($query)>0){
		while($res = mysqli_fetch_assoc($query)){
			$data[] = $res;
		}
	}
}




if(isset($_GET['classes'])){
	$sql = "SELECT * FROM tbl_classes";
	
	$query = mysqli_query($conn,$sql);

	if(mysqli_num_rows($query)>0){
		while($res = mysqli_fetch_assoc($query)){
			$data[] = $res;
		}
	}
}


echo json_encode($data);
?>