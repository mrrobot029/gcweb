<?php

    require_once '../config/connect.php';
    date_default_timezone_set('Asia/Manila');
    header('Content-Type: application/vnd.ms-excel');
    header('Content-disposition: attachment; filename=GCAT APPLICANTS.xls');

    echo '
    <h1>List of GCAT Applicants as of '.date('F d Y - h:i A').'<h1>
    <table border="1">
        <thead>
            <tr>
                <th>No.</th>
                <th>Temporary Id No.</th>
                <th>Last Name</th>
                <th>First Name</th>
                <th>Middle Name</th>
                <th>Extension Name</th>
                <th>E-mail</th>
                <th>Contact Number</th>
                <th>Program</th>
            </tr>
        </thead>
        <tbody>';

    $x=1;
    $sql = "SELECT tbl_gcat.gc_idnumber, tbl_studentinfo.si_lastname, tbl_studentinfo.si_firstname, tbl_studentinfo.si_midname, tbl_studentinfo.si_extname, tbl_studentinfo.si_email, tbl_studentinfo.si_mobile, tbl_studentinfo.si_course FROM tbl_gcat INNER JOIN tbl_studentinfo ON tbl_studentinfo.si_idnumber = tbl_gcat.gc_idnumber";

    $query = mysqli_query($conn,$sql);
    if(mysqli_num_rows($query)>0){
      while($res = mysqli_fetch_assoc($query)){
  
        echo '
            <tr>
                <td><strong>' .$x.'</strong> </td>
                <td>' .$res['gc_idnumber'] . '</td>
                <td>' .$res['si_lastname'] . '</td>
                <td>' .$res['si_firstname'] . '</td>
                <td>' .$res['si_midname'] . '</td>
                <td>' .$res['si_extname'] . '</td>
                <td>' .$res['si_email'] . '</td>
                <td>' .$res['si_mobile'] . '</td>
                <td>' .$res['si_course'] . '</td>
            </tr>';
            $x++;
      }
    }

    echo '
    <tr>
        <td colspan="7">Total Records: <strong> '.($x-1).' <strong></td>
    </tr>';
    echo '
        </tbody>
    </table>';
    
?>
