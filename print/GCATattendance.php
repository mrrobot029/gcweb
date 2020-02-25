<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Student Information Sheet</title>
    <link rel="stylesheet" type="text/css" href="css/GCATAttendance.css">
</head>

<body>
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
                    <h2>GCAT Examinees A.Y. 2020-2021 1st Semester</h2>
                </td>
            </tr>
        </tbody>
    </table>
    <table width="100%" class="table-body">
        <thead>
            <th class="border-thin">No.</th>
            <th class="border-thin">ID Number</th>
            <th class="border-thin">Full Name</th>
            <th class="border-thin">Course</th>
            <th class="border-thin">E-mail</th>
            <th class="border-thin">CP Number</th>
            <th class="border-thin">Signature</th>
        </thead>
        <tbody>


        <?php 
            require_once "../config/connect.php";
        
            $sched_recno = $_GET['sched_recno'];
            $x = 1;
            $query = mysqli_query($conn, "SELECT * from tbl_studentinfo stud INNER JOIN tbl_gcat g ON g.gc_idnumber = stud.si_idnumber INNER JOIN tbl_gcatschedule gs ON g.gc_examtime = gs.sched_recno WHERE gs.sched_recno = '$sched_recno' ORDER BY stud.si_lastname,stud.si_firstname,stud.si_midname");
            if(mysqli_num_rows($query)>0){
                while($res = mysqli_fetch_assoc($query)){

        ?>
            <tr>
                <td class="border-thin"><?php echo $x; ?></td>
                <td class="border-thin"><?php echo $res['si_idnumber']; ?></td>
                <td class="border-thin"><?php echo $res['si_lastname']; ?>, <?php echo $res['si_firstname']; ?> <?php echo $res['si_midname']; ?> <?php echo $res['si_extname']; ?></td>
                <td class="border-thin"><?php echo $res['si_course']; ?></td>
                <td class="border-thin"><?php echo $res['si_email']; ?></td>
                <td class="border-thin"><?php echo $res['si_mobile']; ?></td>
                <td class="border-thin"></td>
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