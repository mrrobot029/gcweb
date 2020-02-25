<?php
            $report = $_GET['report'];
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

?>
<!DOCTYPE html>
<html style="width: 8.5in!important; height: 11in!important">

<head>
    <meta charset="UTF-8">
    <title>GCAT Examinees Attendance</title>
    <link rel="stylesheet" type="text/css" href="css/gcatreport.css">
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
                    <h2>GCAT A.Y. <?php  echo $es_start[0]."-".$es_end[0]; ?> - <?php 

if($es_sem == '1'){
  echo "1st";
}elseif($es_sem == '2'){
  echo "2nd";
}elseif($es_sem == 'Mid Year'){
  echo $es_sem;
}

?> Semester</h2>
<?php 
$x = 1;
$y = 1;
$tbodyPerCourse = "";
$tbodyPerDept = "";
$unconfirmed = mysqli_num_rows(mysqli_query($conn, "SELECT gc_idnumber FROM tbl_gcat WHERE gc_status = 0"));
$confirmed = mysqli_num_rows(mysqli_query($conn, "SELECT gc_idnumber FROM tbl_gcat WHERE gc_status = 1"));
$scheduled = mysqli_num_rows(mysqli_query($conn, "SELECT gc_idnumber FROM tbl_gcat WHERE gc_status = 2"));
$result = $conn->query("SELECT gc_course, count(gc_idnumber) as courseCount FROM tbl_gcat GROUP BY gc_course");
while($res = $result->fetch_array()){
  $countByCourse[] = $res;
}
foreach($countByCourse as $row){
    $course = $row['gc_course'];
    $count = $row['courseCount'];
    $tbodyPerCourse .="
    <tr>
        <td class='border-thin'>$x</td>
        <td class='border-thin'>$course</td>
        <td class='border-thin'>$count</td>
    </tr>
    ";
    $x++;
}

$resultDepartment = $conn->query("SELECT co.co_dept as department, count(gc.gc_idnumber) as departmentCount FROM tbl_gcat as gc INNER JOIN tbl_courses as co ON gc.gc_course = co.co_name GROUP BY co.co_dept");
while($res = $resultDepartment->fetch_array()){
  $countByDept[] = $res;
}
foreach($countByDept as $row){
    $dept = $row['department'];
    $countdept = $row['departmentCount'];
    $tbodyPerDept .="
    <tr>
        <td class='border-thin'>$y</td>
        <td class='border-thin'>$dept</td>
        <td class='border-thin'>$countdept</td>
    </tr>
    ";
    $y++;
}

$title = "Statistics Report as of ".date("F d, Y, h:i a");
?>
                    <h3><?php echo $title; ?></h3>
                </td>
            </tr>
        </tbody>
    </table>

            <table class="table-body">
                <thead>
                    <th class="border-thin">Unconfirmed</th>
                    <th class="border-thin">Confirmed</th>
                    <th class="border-thin">Scheduled</th>
                </thead>
                <tbody>
                <tr>
                    <td class="border-thin"><?php echo $unconfirmed; ?></td>
                    <td class="border-thin"><?php echo $confirmed; ?></td>
                    <td class="border-thin"><?php echo $scheduled; ?></td>
                <tr>
                <tr>
                    <td colspan="3" class="border-thin" style="text-align: right;">Total: <strong><?php echo $unconfirmed + $confirmed + $scheduled; ?><strong></td>
                </tr>
                </tbody>
            </table>
       <br>
            <table class="txt table-body">
            <tbody>
                <tr>
                    <td class="center" colspan="3">
                        <h3>Count of Registered Students per Course</h3>
                    </td>
                </tr>
            </tbody>
            </table>
            <table class="table-body">
                <thead>
                    <th class="border-thin">No.</th>
                    <th class="border-thin">Course</th>
                    <th class="border-thin">Count</th>
                </thead>
                <tbody>
                    <?php echo $tbodyPerCourse; ?>
                </tbody>
            </table>

            <table class="txt table-body">
            <tbody>
                <tr>
                    <td class="center" colspan="3">
                        <h3>Count of Registered Students per Department</h3>
                    </td>
                </tr>
            </tbody>
            </table>
            <table class="table-body">
                <thead>
                    <th class="border-thin">No.</th>
                    <th class="border-thin">Department</th>
                    <th class="border-thin">Count</th>
                </thead>
                <tbody>
                    <?php echo $tbodyPerDept; ?>
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