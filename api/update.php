<?php

require_once './connect.php';


if(isset($_POST['oldID']) && isset($_POST['newID'])){
	$oldID = $_POST['oldID'];
	$newID = $_POST['newID'];

	$sql = "UPDATE tbl_studentinfo SET si_idnumber='$newID',si_isenrolled='1' WHERE si_idnumber='$oldID'";
	if(mysqli_query($conn,$sql)){
		$sqlupdateenrolled = "UPDATE tbl_enrolledsubjects SET es_idnumber='$newID' WHERE es_idnumber='$oldID'";
		if(mysqli_query($conn,$sqlupdateenrolled)){
			$data['message'] = "Update Student Id Success";
			$data['success'] = true;
		}else{
			$data['message'] = $conn->error;
			$data['success'] = false;
		}

	}else{
		$data['message'] = $conn->error;
		$data['success'] = false;
	}


echo json_encode($data);
}


if(isset($_GET['updateenrolled']) && isset($_GET['studentnumber'])){
	$studentnumber = $_GET['studentnumber'];
	$sql = "UPDATE tbl_studentinfo SET si_isenrolled='1' where si_idnumber='$studentnumber'";

	if(mysqli_query($conn,$sql)){
		$data['message']="Update success.";
		$data['success']=true;
	}else{
		$data['message']="Update failed.";
		$data['success']=false;
	}

echo json_encode($data);
}