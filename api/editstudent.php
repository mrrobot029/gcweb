<?php

require_once ('connect.php');

$valid = [];

$d = json_decode(file_get_contents("php://input"));

$picture = "";
	if(isset($_POST['pic'])){
		$target_dir = "images/";
		$target_file = $target_dir . basename($_FILES["pic"]["name"]);
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		$target_file2 = $target_dir . $_POST['fname']."_".$_POST['lname'].".".$imageFileType;
		if (move_uploaded_file($_FILES["pic"]["tmp_name"], $target_file2)) {
			$picture = $_POST['fname']."_".$_POST['lname'].".".$imageFileType;
	}
}


$lname = $_POST['lname'];
$fname = $_POST['fname'];
$mname = $_POST['mname'];
$extname = $_POST['nameext'];
$address = $_POST['address'];
$zipcode = $_POST['addresszip'];
$gender = $_POST['gender'];
$bday = $_POST['dob'];
$email = $_POST['email'];
$contact = $_POST['mobile'];
$entrancescore = $_POST['entrancescore'];
$course = $_POST['course'];
$course2 = '';
if(isset($_POST['course2'])){
	$course2 = $_POST['course2'];
}
$course3 = '';
if(isset($_POST['course3'])){
	$course3 = $_POST['course3'];
}
$reason = $_POST['reasoncourse'];
if($reason == 'other'){
	if(isset($_POST['courseother'])){
		$reason = $_POST['courseother'];
	}
}
$reasongc = $_POST['reasonschool'];
if($reasongc == 'other'){
	if(isset($_POST['schoolother'])){
		$reasongc = $_POST['schoolother'];
	}
}
$scholarship = $_POST['scholar'];
$scholartype = $_POST['scholartype'];
$sponsor = '';
if(isset($_POST['sponsor'])){
	$sponsor = $_POST['sponsor'];
}
$sponsoroccupation = $_POST['sponsoroccupation'];
if(isset($_POST['sponsoroccupation'])){
	$sponsoroccupation = $_POST['sponsoroccupation'];
}
$transferee = $_POST['transferee'];
$transferschool = $_POST['transfercourselevel'];
$highschool = $_POST['highschool'];
$highschoolgpa = $_POST['highschoolgpa'];
$honors = '';
if(isset($_POST['honors'])){
	$honors = $_POST['honors'];
}
$orgs = '';
if(isset($_POST['orgs'])){
	$orgs = $_POST['orgs'];
}
$interest = '';
$interests = '';
if(isset($_POST['interests'])){
	$interest = $_POST['interests'];
	$interests = implode(", ", $interest);
}
if(isset($_POST['interestother'])){
	$interests = $interests.', '.$_POST['interestother'];
}
$talent =  '';
$talents = '';
if(isset($_POST['talents'])){
	$talent =  $_POST['talents'];
	$talents = implode(", ", $talent);
}
if(isset($_POST['talentsother'])){
		$talents = $talents.', '.$_POST['talentsother'];
}
$device = '';
$devices = '';
if(isset($_POST['device'])){
		$device = $_POST['device'];
		$devices = implode(",", $device);
}
$siblings = $_POST['siblings'];
$mother = $_POST['mother'];
$motheroccupation = $_POST['motheroccupation'];
$father = $_POST['father'];
$fatheroccupation = $_POST['fatheroccupation'];
$emergencynumber = $_POST['emergencynumber'];
$sem = $_POST['sem'];
$sports = '';
$sport = '';
if(isset($_POST['sport'])){
	$sports = $_POST['sport'];
	$sport = implode(", ", $sports);
}
if(isset($_POST['sportother'])){
	$sport = $sport.', '.$_POST['sportother'];
}
$lrn = '';
if(isset($_POST['lrn'])){
	$lrn = $_POST['lrn'];
}
$guardname = $_POST['guardian'];
$guardrel = $_POST['relationship'];
$guardadd = $_POST['guardianadd'];
$govprojs = '';
$govproj = '';
if(isset($_POST['govproj'])){
	$govprojs = $_POST['govproj'];
	$govproj = implode(", ", $govprojs);
}
$govprojother='';
if(isset($_POST['govprojother'])){
	$govprojother = $_POST['govprojother'];
}
$famincome = $_POST['famincome'];
$disabled = $_POST['disabled'];
$disability ='';
if(isset($_POST['disability'])){
	$disability = $_POST['disability'];
}
$counten=0;

$household='';
if(isset($_POST['household'])){
	$household = $_POST['household'];
}


$id1 = $_POST['id'];


$year = $_POST['year'];


$sqlverify = "SELECT * FROM tbl_studentinfo where si_idnumber = '$id1'";
$result = mysqli_query($conn,$sqlverify);
if($result->num_rows > 0){
	while($res = mysqli_fetch_assoc($result)){
		$id = $res['si_recno'];
		if($picture == ""){
			$picture = $res['si_picture'];
		}
		$sqlupdate = "UPDATE tbl_studentinfo SET si_lastname = '$lname', si_firstname = '$fname', si_midname = '$mname', si_extname = '$extname', si_address = '$address', si_picture = '$picture', si_gender = '$gender', si_bday = '$bday', si_email = '$email', si_mobile = '$contact', si_course = '$course', si_coursechoice = '$course2', si_coursechoice2 = '$course3', si_reason = '$reason', si_siblings = '$siblings', si_momname ='$mother' , si_dadname = '$father', si_emergencycontact = '$emergencynumber', si_device = '$devices', si_entranceexam = '$entrancescore', si_lastschool = '$highschool', si_average = '$highschoolgpa', si_istransferee = '$transferee', si_transfercourselevel = '$transferschool', si_reasonstudy = '$reasongc', si_scholartype = '$scholartype', si_support = '$sponsor', si_supportoccupation = '$sponsoroccupation', si_specialaward = '$honors', si_organization = '$orgs', si_interest = '$interests', si_talent = '$talents', si_yrlevel = '$year', si_sem = '$sem', si_sport = '$sport', si_momoccupation = '$motheroccupation', si_dadoccupation = '$fatheroccupation', si_lrn='$lrn', si_guardname = '$guardname', si_guardadd = '$guardadd', si_guardrel = '$guardrel', si_govproj = '$govproj', si_govprojothers = '$govprojother', si_famincome = '$famincome', si_isdisabled = '$disabled', si_disability = '$disability', si_householdno = '$household', si_zipcode = '$zipcode' where si_recno = '$id'";
		if(mysqli_query($conn,$sqlupdate)){
			$valid[0] = "success";
		} else {
			$valid[0] = $conn->error;
		}
	}
	
}  else{

	$valid[0] = 'errorlast';
}
		mysqli_close($conn);
		echo json_encode($valid);