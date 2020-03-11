<?php

    require_once '../config/connect.php';
    $sub_recno = $_GET['sub_recno'];
    date_default_timezone_set('Asia/Manila');
    $sqlGetSchedule = "SELECT * FROM tbl_gcatsubmitsched WHERE sub_recno = '$sub_recno'";
    $queryGetSchedule = mysqli_query($conn,$sqlGetSchedule);
    if(mysqli_num_rows($queryGetSchedule)>0){
        while($res = mysqli_fetch_assoc($queryGetSchedule)){
        $sub_date = strtotime($res['sub_date']);
        }
    } else{
        echo $conn->error;
    }
    header('Content-Type: application/vnd.ms-excel');
    header('Content-disposition: attachment; filename=GCAT APPLICANTS ATTENDANCE- '.date('F d Y', $sub_date).'.xls');
  
    require_once "../config/connect.php";
    $sql = "SELECT * FROM tbl_enlistment WHERE en_isactive='ACTIVE'";
    $query = mysqli_query($conn,$sql);
    if(mysqli_num_rows($query)>0){
        while($res = mysqli_fetch_assoc($query)){
        $es_sem = $res['en_sem'];
        $start = $res['en_cystart'];
        $end = $res['en_cyend'];
        }

        $es_start = explode("-",$start);
        $es_end = explode("-",$end);
    }
    $ciphering = "AES-128-CTR"; 
$iv_length = openssl_cipher_iv_length($ciphering); 
$options = 0; 
$id = 'forgcatadminonly!';
$key = '';
if(isset($_GET['key'])){
$key = rawurldecode($_GET['key']);
}
$decryption_iv = '1234567891011121'; 

// Store the decryption key 
$decryption_key = "fsociety"; 

// Use openssl_decrypt() function to decrypt the data 
$decryptedkey=openssl_decrypt ($key, $ciphering,  
        $decryption_key, $options, $decryption_iv); 
if($id == $decryptedkey){
    echo '
    <h1>GCAT Applicants A.Y. '.$en_schoolyear.' - '.$sem.' Semester<h1>
    <h2>Submission Date: '.date('F d, Y', $sub_date).'</h2>
    <table border="1">
        <thead>
            <tr>
                <th>No.</th>
                <th>Temporary Id No.</th>
                <th>Full Name</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>';

        $x = 1;
        $query = mysqli_query($conn, "SELECT * from tbl_studentinfo stud INNER JOIN tbl_gcat g ON g.gc_idnumber = stud.si_idnumber INNER JOIN tbl_gcatsubmitsched gs ON g.gc_subdate = gs.sub_recno WHERE gs.sub_recno = '$sub_recno' ORDER BY stud.si_idnumber ASC");
        if(mysqli_num_rows($query)>0){
            while($res = mysqli_fetch_assoc($query)){
                echo '
                    <tr>
                        <td><strong>' .$x.'</strong> </td>
                        <td>'.$res['si_idnumber'].'</td>
                        <td style="width: 200%">'.$res['si_lastname'].','.$res['si_firstname'].','.$res['si_midname'].'</td>
                        <td></td>
                    </tr>';
                    $x++;
            }
        }

    echo '
    <tr>
        <td colspan="4">Total Applicants: <strong> '.($x-1).' <strong></td>
    </tr>';
    echo '
        </tbody>
    </table>';
} else{
    echo 'UNAUTHORIZED ACCESS';
}
    
    
?>