<?php
            // $report = $_GET['report'];
            $sub_recno = $_GET['sched_recno'];
            require_once "../config/connect.php";
            date_default_timezone_set ('Asia/Manila');
            $sqlGetSchedule = "SELECT * FROM tbl_gcatsubmitsched WHERE sub_recno = '$sub_recno'";
            $queryGetSchedule = mysqli_query($conn,$sqlGetSchedule);
            if(mysqli_num_rows($queryGetSchedule)>0){
                while($res = mysqli_fetch_assoc($queryGetSchedule)){
                $sub_date = strtotime($res['sub_date']);
                }
            } else{
                echo $conn->error;
            }
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
$totalapplicants = $unconfirmed + $confirmed + $scheduled;
$result = $conn->query("SELECT co.co_description, gc.gc_course, count(gc.gc_idnumber) as courseCount FROM tbl_gcat gc INNER JOIN tbl_courses co ON gc.gc_course = co.co_name GROUP BY gc_course ORDER BY courseCount DESC");
$unscheduledDaily = mysqli_num_rows(mysqli_query($conn, "SELECT gc_idnumber FROM tbl_gcat WHERE gc_status = 1 and gc_subdate = $sub_recno"));
$scheduledDaily = mysqli_num_rows(mysqli_query($conn, "SELECT gc_idnumber FROM tbl_gcat WHERE gc_status = 2 and gc_subdate = $sub_recno"));
while($res = $result->fetch_array()){
  $countByCourse[] = $res;
}
foreach($countByCourse as $row){
    $course = $row['gc_course'];
    $description = $row['co_description'];
    $count = $row['courseCount'];
    $tbodyPerCourse .="
    <tr>
        <td class='border-thin' style='text-align: center'>$x</td>
        <td class='border-thin' style='text-align: center'>($course) $description</td>
        <td class='border-thin' style='text-align: center'>$count - <strong>". round(($count/$totalapplicants)*100, 2, PHP_ROUND_HALF_UP) ."%</strong></td>
    </tr>
    ";
    $x++;
}

$resultDepartment = $conn->query("SELECT co.co_dept as department, count(gc.gc_idnumber) as departmentCount FROM tbl_gcat as gc INNER JOIN tbl_courses as co ON gc.gc_course = co.co_name GROUP BY co.co_dept ORDER BY departmentCount DESC");
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
        <td class='border-thin'>$countdept - <strong>". round(($countdept/$totalapplicants)*100, 2, PHP_ROUND_HALF_UP) ."%</strong></td>
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
            <?php  ?>
            <!-- general -->
            <table class="table-body">
                <thead>
                    <th class="border-thin">Unconfirmed (Count & Percentage)</th>
                    <th class="border-thin">Confirmed (Count & Percentage)</th>
                    <th class="border-thin">Scheduled (Count & Percentage)</th>
                </thead>
                <tbody> 
                <tr>
                    <td class="border-thin"><?php echo $unconfirmed; ?> - <strong><?php echo round(($unconfirmed/$totalapplicants)*100, 2, PHP_ROUND_HALF_UP); ?>%</strong></td>
                    <td class="border-thin"><?php echo $confirmed; ?> - <strong><?php echo round(($confirmed/$totalapplicants)*100, 2, PHP_ROUND_HALF_UP); ?>%</strong></td>
                    <td class="border-thin"><?php echo $scheduled; ?> - <strong><?php echo round(($scheduled/$totalapplicants)*100, 2, PHP_ROUND_HALF_UP); ?>%</strong></td>
                <tr>
                <tr>
                    <td colspan="3" class="border-thin" style="text-align: right;">Total: <strong><?php echo $unconfirmed + $confirmed + $scheduled; ?><strong></td>
                </tr>
                </tbody>
            </table>

            <!-- daily -->
            <h3>Report for Submission Date - <?php echo date('F d, Y', $sub_date); ?></h3>
            <table class="table-body">
                <thead>
                    <th class="border-thin">Unscheduled</th>
                    <th class="border-thin">Scheduled</th>
                </thead>
                <tbody>
                <tr>
                    <td class="border-thin"><?php echo $unscheduledDaily; ?></td>
                    <td class="border-thin"><?php echo $scheduledDaily; ?></td>
                <tr>
                <tr>
                    <td colspan="3" class="border-thin" style="text-align: right;">Total(Scheduled/Total): <strong><?php echo $scheduledDaily; ?>/<?php echo $unscheduledDaily + $scheduledDaily; ?><strong></td>
                </tr>
                </tbody>
            </table>

    <!-- percourse -->
       <br>
            <table class="txt table-body">
            <tbody>
                <tr>
                    <td class="center" colspan="3">
                        <h3>Count of Registered Applicants per Desired Program(1st Choice)</h3>
                    </td>
                </tr>
            </tbody>
            </table>
            
            <table class="table-body">
                <thead>
                    <th class="border-thin">No.</th>
                    <th class="border-thin">Desired Program(1st Choice)</th>
                    <th class="border-thin">Count & Percentage</th>
                </thead>
                <tbody>
                    <?php echo $tbodyPerCourse; ?>
                </tbody>
            </table>

            <!-- perdepartment -->
            <table class="txt table-body">
            <tbody>
                <tr>
                    <td class="center" colspan="3">
                        <h3>Count of Registered Applicants per College(Based on 1st Choice Desired Program)</h3>
                    </td>
                </tr>
            </tbody>
            </table>
            <table class="table-body">
                <thead>
                    <th class="border-thin">No.</th>
                    <th class="border-thin">Colleges</th>
                    <th class="border-thin">Count & Percentage</th>
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