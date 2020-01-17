<?php
require_once 'connect.php';
$data=[];

if(isset($_GET['studentnumber'])){
	$studentnumber = $_GET['studentnumber'];
	$sql = "SELECT * FROM tbl_studentinfo WHERE si_idnumber='$studentnumber'";
	$query = mysqli_query($conn,$sql);

	if(mysqli_num_rows($query)>0){
		while($res = mysqli_fetch_assoc($query)){
            $fullname = $res['si_lastname'].", ".$res['si_firstname']." ".$res['si_extname'].", ".$res['si_midname'];
            $data[0]= "<table class='striped'>
                    <tr>
                        <td><b>Student Id #</b></td>
                        <td></td>
                        <td><b>Program</b></td>
                        <td>".$res['si_course']."-".$res['si_yrlevel']."</td>
                    </tr>
                        <td><b>Full Name</b></td>
                        <td>".$fullname."</td>
                        
                    <td><b>Sex at Birth</b></td>
                    <td>".$res['si_gender']."</td>
                    </tr>
                    </tr>
                    <td><b>Address</b></td>
                    <td>".$res['si_address']."</td>

                    <td><b>Zip Code</b></td>
                    <td>".$res['si_zipcode']."</td>
                    <tr>
                    </tr>
                    <td><b>Email Address</b></td>
                    <td>".$res['si_email']."</td>
                    
                    <td><b>Contact #</b></td>
                    <td>".$res['si_mobile']."</td>

                    <tr>
                    </table>";
                $data[1]=$res['si_department'];
                $data[2] = $res['si_course'].$res['si_yrlevel'];
		}
	}
}


if(isset($_GET['studentnumber2'])){
    $data = "<table class='striped'>
                    <thead>
                        <th>Student Id</th>
                        <th>Fullname</th>
                        <th>E-mail Address</th>
                        <th>Course</th>
                        <th>Block</th>
                        <th>Action</th>
                    </thead>
                    <tbody>";
    $studentnumber = $_GET['studentnumber2'];
    $sql = "SELECT * FROM tbl_studentinfo WHERE si_idnumber LIKE '%$studentnumber%' or si_firstname LIKE '%$studentnumber%' or si_midname LIKE '%$studentnumber%' or si_lastname LIKE '%$studentnumber%' or si_extname LIKE '%$studentnumber%' or si_email LIKE '%$studentnumber%'";
    $query = mysqli_query($conn,$sql);

    if(mysqli_num_rows($query)>0){
        while($res = mysqli_fetch_assoc($query)){
            $data.= "<tr>
                        <td>".$res['si_idnumber']."</td>
                        <td>".$res['si_lastname'].", ".$res['si_firstname']." ".$res['si_midname']." ".$res['si_extname']." </td>
                        <td>".$res['si_email']."</td>
                        <td>".$res['si_course']."</td>
                        <td>".$res['si_block']."</td>";

            if(strlen($res['si_idnumber'])==9 && $res['si_isenrolled']==0){
                $data.="<td><a class='btn blue btn-small modal-trigger' onclick='MAE(".$res['si_idnumber'].")' >Mark As Enrolled</a>";
                   $data.="<a class='btn blue btn-small ml-1' href='edit.php?si_idnumber=".$res['si_idnumber']."' target='_blank'>Edit</a></td>
                    </tr>";
            }elseif(strlen($res['si_idnumber'])!=9){
                $data.="<td><a class='btn blue btn-small modal-trigger' onclick='UUP(".$res['si_idnumber'].")' href='#updateSID'>Update</a>";
                   $data.="<a class='btn blue btn-small ml-1' href='edit.php?si_idnumber=".$res['si_idnumber']."' target='_blank'>Edit</a></td>
                    </tr>";
            }else{
                $data.="<td><a class='btn blue btn-small ml-1' href='edit.php?si_idnumber=".$res['si_idnumber']."' target='_blank'>Edit</a></td>
                    </tr>";

            }




         
        }

          $data.= "</tbody></table>";
    }
}

if(isset($_GET['studentnum'])){
    $studid = $_GET['studentnum'];
    $sqledit = "SELECT * FROM tbl_studentinfo WHERE si_idnumber = '$studid'";
    $query = mysqli_query($conn,$sqledit);
    if(mysqli_num_rows($query)>0){
    while($res = mysqli_fetch_assoc($query)){
        $si_isdisabled = $res['si_isdisabled'];
        $si_disability = $res['si_disability'];
        $govprogs = $res['si_govproj'];
        $govprog = explode(", ", $govprogs);
        $lastname = $res['si_lastname'];
        $firstname = $res['si_firstname'];
        $midname = $res['si_midname'];
        $course = $res['si_course'];
        $extname = $res['si_extname'];
        $year = $res['si_yrlevel'];
        $address = $res['si_address'];
        $gender = $res['si_gender'];
        $address = $res['si_address'];
        $zipcode = $res['si_zipcode'];
        $email = $res['si_email'];
        $mobile = $res['si_mobile'];
        $lastchool = $res['si_lastschool'];
        $lrn = $res['si_lrn'];
        $guardianrel = $res['si_guardrel'];
        $guardadd = $res['si_guardadd'];
        $emergency = $res['si_emergencycontact'];
        $govprojother = $res['si_govprojothers'];
        $famincome = $res['si_famincome'];
        $isdisabled = $res['si_isdisabled'];
        $disability = $res['si_disability'];
        $guardname = $res['si_guardname'];
        $household = $res['si_householdno'];
        $si_mobile= $res['si_mobile'];
        $si_course = $res['si_course'];
        $si_bday = $res['si_bday'];
        $si_coursechoice = $res['si_coursechoice'];
        $si_coursechoice2 = $res['si_coursechoice2'];
        $si_reasonstudy = $res['si_reasonstudy'];
        $si_scholartype = $res['si_scholartype'];
        $si_entranceexam = $res['si_entranceexam'];
        $si_support = $res['si_support'];
        $si_supportoccupation = $res['si_supportoccupation'];
        $si_reason = $res['si_reason'];
        $si_transfercourselevel = $res['si_transfercourselevel'];
        $si_interest = $res['si_interest'];
        $si_momname = $res['si_momname'];
        $si_momoccupation = $res['si_momoccupation'];
        $si_dadname = $res['si_dadname'];
        $si_dadoccupation = $res['si_dadoccupation'];
        $si_lastschool = $res['si_lastschool'];
        $si_lrn    = $res['si_lrn'];
        $si_average    = $res['si_average'];
        $si_specialaward    = $res['si_specialaward'];
        $si_organization    = $res['si_organization'];
        $si_interest    = $res['si_interest'];
        $si_talent    = $res['si_talent'];
        $si_siblings    = $res['si_siblings'];
        $si_device    = $res['si_device'];
        $si_guardname = $res['si_guardname'];
        $si_guardadd = $res['si_guardadd']; 
        $si_device = $res['si_device'];
        $array_device = explode(",",$si_device);
        $si_guardrel  = $res['si_guardrel'];
        $si_sport = $res['si_sport'];
        $si_govprojothers = $res['si_govprojothers'];
        $si_emergencycontact = $res['si_emergencycontact'];
        $si_famincome = $res['si_famincome'];
    }

}
}

echo json_encode($data);
?>