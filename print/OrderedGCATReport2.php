<?php
    require_once "../config/connect.php";
?>

<!DOCTYPE html>
<html style="width: 8.5in!important;">
    <head>
        <meta charset="UTF-8">
        <title>GCAT SUMMARY REPORT</title>
        <link rel="stylesheet" type="text/css" href="css/gcatsummaryreport.css">
    </head>

    <body style="width: 99%!important; height: 100%!important;">
        <table class="txt table-body">
            <tbody>
                <tr>
                    <td width="35%" class="right" colspan="1">
                        <br>
                        <img src="image/gc-log.png" width="80">
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
                        <h2>GCAT SUMMARY REPORT as of (<?php echo date("M d, Y"); ?>)</h2>
                    </td>
                </tr>
            </tbody>
        </table>

        <table class="table-body">
            <thead>
                <th class="border-thin">No.</th>
                <th class="border-thin">Program</th>
                <th class="border-thin">Status</th>
                <th class="border-thin">Exam Schedule</th>
                
                <th class="border-thin">Count</th>
            </thead>
            <tbody>
            <?php
                $sql = "SELECT DISTINCT *, count(*) as count FROM tbl_gcat as gcat INNER JOIN tbl_studentinfo as si ON si.si_idnumber=gcat.gc_idnumber LEFT JOIN tbl_gcatschedule as sched ON sched.sched_recno = gcat.gc_examtime GROUP BY si.si_course,gcat.gc_status,sched.sched_date,sched.sched_time ORDER BY si.si_course,gcat.gc_status,sched.sched_date,sched.sched_time";

                $query = mysqli_query($conn,$sql);
                if(mysqli_num_rows($query)>0){
                    $no = 1;
                    $count = 0;
                    while($res = mysqli_fetch_assoc($query)){
                        echo "<tr>
                            <td class='border-thin'> $no </td>
                            <td class='border-thin'>".$res['gc_course']."</td>";

                            if($res['gc_status'] == 0) {
                                echo "
                                <td class='border-thin bg-red'><strong>Unconfirmed</strong></td>
                                ";

                            }elseif($res['gc_status'] == 1) {
                                echo "
                                <td class='border-thin bg-green'><strong>Confirmed</strong></td>
                                ";

                            }elseif($res['gc_status'] == 2) {
                                echo "
                                <td class='border-thin bg-blue'><strong>Scheduled</strong></td>
                                ";
                            }
                         

                            
                            if($res['sched_date'] == null) {
                                echo "
                                <td class='border-thin'>-----</td>
                           ";
                            }else {
                                echo "
                                <td class='border-thin'><strong>".$res['sched_date']." ".$res['sched_time']."</strong></td>
                                ";


                        }

                        $count = $count + $res['count'];
                        echo "
                            <td class='border-thin'>".$res['count']."</td></tr>";
                        $no++;
                    }
                }
            ?>
            
            </tbody>
        </table>
    </body>
</html>
<!-- 
<script src="js/jquery.min.js"></script>
<script>
    window.print()
</script> -->