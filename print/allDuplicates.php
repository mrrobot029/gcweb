<?php
    require_once '../config/connect.php';
    date_default_timezone_set('Asia/Manila');
    header('Content-Type: application/vnd.ms-excel');
    header('Content-disposition: attachment; filename=GCAT DUPLICATES- '.date('F d Y - h:i A').'.xls');
  
    // get enlistment info
    $query = mysqli_query($conn, "SELECT * FROM tbl_enlistment WHERE en_isactive='ACTIVE'");
    if(mysqli_num_rows($query)>0){
        while($res = mysqli_fetch_assoc($query)){
            $en_schoolyear = $res['en_schoolyear'];
            $en_sem = $res['en_sem'];
            switch($en_sem){
                case '1':
                    $sem = '1<sup>st</sup>';
                break;
                case '2':
                    $sem = '2<sup>nd</sup>';
                break;
                case 'Mid Year':
                    $sem = 'Mid Year';
                break;
                default:
                    $sem = 'ERROR';
            }
        }
    }
  
    // get student info
    
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
    <h1>List of GCAT DUPLICATE ENTRIES as of '.date('F d Y - h:i A').'<h1>
    <h2>School Year '.$en_schoolyear.' - '.$sem.' Semester</h2>
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
                <th>Count</th>
            </tr>
        </thead>
        <tbody>';

    $x=1;
    $sql = "SELECT *, COUNT(*) as counts
    FROM tbl_studentinfo inner join tbl_gcat on tbl_gcat.gc_idnumber = tbl_studentinfo.si_idnumber
    GROUP BY tbl_studentinfo.si_lastname, tbl_studentinfo.si_firstname, tbl_studentinfo.si_midname
    HAVING COUNT(*) > 1";

    $query = mysqli_query($conn,$sql);
    if(mysqli_num_rows($query)>0){
      while($res = mysqli_fetch_assoc($query)){
        echo '
            <tr>
                <td><strong>' .$x.'</strong> </td>
                <td>' .$res['si_idnumber'] . '</td>
                <td>' .$res['si_lastname'] . '</td>
                <td>' .$res['si_firstname'] . '</td>
                <td>' .$res['si_midname'] . '</td>
                <td>' .$res['si_extname'] . '</td>
                <td>' .$res['si_email'] . '</td>
                <td>' .$res['si_mobile'] . '</td>
                <td>' .$res['counts'] . '</td>
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
} else{
    echo 'UNAUTHORIZED ACCESS';
}
    
    
?>