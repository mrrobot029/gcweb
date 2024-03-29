<?php
            $sub_recno = $_GET['sub_recno'];
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
            $sqlGetSchedule = "SELECT * FROM tbl_gcatsubmitsched WHERE sub_recno = '$sub_recno'";
            $queryGetSchedule = mysqli_query($conn,$sqlGetSchedule);
            if(mysqli_num_rows($queryGetSchedule)>0){
                while($res = mysqli_fetch_assoc($queryGetSchedule)){
                $sub_date = strtotime($res['sub_date']);
                }
            } else{
                echo $conn->error;
            }
            $unscheduledDaily = mysqli_num_rows(mysqli_query($conn, "SELECT gc_idnumber FROM tbl_gcat WHERE gc_status = 1 and gc_subdate = $sub_recno"));
            $scheduledDaily = mysqli_num_rows(mysqli_query($conn, "SELECT gc_idnumber FROM tbl_gcat WHERE gc_status = 2 and gc_subdate = $sub_recno"));
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

?>
<!DOCTYPE html>
<html style="width: 8.5in!important; height: 11in!important">

<head>
    <meta charset="UTF-8">
    <title>GCAT Examinees Attendance</title>
    <link rel="stylesheet" type="text/css" href="css/GCATSubmissionAttendance.css">
</head>

<body style="width: 99%!important; height: 100%!important;">
    <table class="txt table-body">
        <tbody>
            <tr>
                <td width="35%" class="right" colspan="1">
                    <br>
                    <img src="image/gc-log.png" width="96">
                </td>
                <td width="30%" class="center" colspan="1">
                    <h1 class="verticalmargin">GORDON COLLEGE</h1>
                    <p class="verticalmargin">Olongapo City Sports Complex, Donor Street</p>
                    <p class="verticalmargin">East Tapinac, Olongapo City</p>
                    <h3 class="verticalmargin">Office of the Registrar</h3>
                </td>
                <td width="35%" colspan="1">
                    <br>
                    <br>
                </td>
            </tr>
        </tbody>
    </table>
    <table class="txt table-body">
        <tbody>
            <tr>
                <td class="center" colspan="3">
                    <h2>GCAT Examinees A.Y. <?php  echo $es_start[0]."-".$es_end[0]; ?> - <?php 

if($es_sem == '1'){
  echo "1st";
}elseif($es_sem == '2'){
  echo "2nd";
}elseif($es_sem == 'Mid Year'){
  echo $es_sem;
}

?> Semester</h2>
                    <h3>Submission Date: <?php echo date('F d, Y', $sub_date); ?></h3><br>
                </td>
            </tr>
            <tr>
                <td class="center" colspan="10">
                    <h3>Scheduled: <strong style="color: green;margin-right: 20px"><?php echo $scheduledDaily; ?></strong> Unscheduled: <strong style="color: red"><?php echo $unscheduledDaily; ?></strong></h3>
                </td>
            </tr>
        </tbody>
    </table>
    <table width="100%" class="table-body">
        <thead>
            <th class="border-thin">No.</th>
            <th class="border-thin">ID Number</th>
            <th class="border-thin">Full Name</th>
            <th class="border-thin">Remarks</th>
        </thead>
        <tbody>


        <?php     
            $x = 1;
            $query = mysqli_query($conn, "SELECT stud.si_idnumber, stud.si_lastname, stud.si_firstname, stud.si_midname, stud.si_extname, g.* from tbl_studentinfo stud INNER JOIN tbl_gcat g ON g.gc_idnumber = stud.si_idnumber INNER JOIN tbl_gcatsubmitsched gs ON g.gc_subdate = gs.sub_recno WHERE gs.sub_recno = '$sub_recno' ORDER BY stud.si_idnumber ASC");
            if(mysqli_num_rows($query)>0){
                while($res = mysqli_fetch_assoc($query)){

        ?>
            <tr>
                <td class="border-thin"><?php echo $x; ?></td>
                <td class="border-thin"><?php echo $res['si_idnumber']; ?></td>
                <td class="border-thin"><?php echo $res['si_lastname']; ?>, <?php echo $res['si_firstname']; ?> <?php echo $res['si_midname']; ?> <?php echo $res['si_extname']; ?></td>
                <?php if($res['gc_status']!=2){ echo '<td class="border-thin" style="color: red">UNSCHEDULED</td>'; } else{ echo '<td class="border-thin" style="color: green">SCHEDULED</td>'; }?>
            </tr>

        <?php 
                    $x++;
                }
            }
        ?>
        </tbody>
    </table>
</body>

</html>

<script src="js/jquery.min.js"></script>
<script>
    window.print()
</script>
<?php
}else{
    echo '<h1>UNAUTHORIZED!</h1>';
}
?>