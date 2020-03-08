<?php
    date_default_timezone_set('Asia/Manila');
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    require '../model/mail/Exception.php';
    require '../model/mail/PHPMailer.php';
    require '../model/mail/SMTP.php';
    require_once '../config/connect.php';
    //mail configuration
    $mail = new PHPMailer(true);
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'gcat@gordoncollegeccs.edu.ph';                     // SMTP username
    $mail->Password   = 'infinitycore4477';                               // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
    $mail->Port       = 587;          
    $mail->setFrom('gcat@gordoncollegeccs.edu.ph', 'Gordon College');
    $mail->isHTML(true);        

    $query = mysqli_query($conn, "SELECT * FROM tbl_enlistment WHERE en_isactive='ACTIVE'");
    if(mysqli_num_rows($query)>0){
        while($res = mysqli_fetch_assoc($query)){
            $en_schoolyear = $res['en_schoolyear'];
            $en_sem = $res['en_sem'];
        }
    }
  
    // get student info
    $now = date("Y-m-d");
    $alreadyConfirmed = false;
    $ciphering = "AES-128-CTR"; 
    $iv_length = openssl_cipher_iv_length($ciphering); 
    $options = 0; 
    $id = '';
    if(isset($_GET['id'])){
    $id = $_GET['id'];
    }
    
    $key="123";
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
        $getSchedule = mysqli_query($conn, "SELECT * FROM tbl_gcatsubmitsched WHERE sub_date >= '$now' AND sub_count < 300 ORDER BY sub_date ASC LIMIT 1");
        while($resSchedule = mysqli_fetch_assoc($getSchedule)){
            $sub_recno = $resSchedule['sub_recno'];
            $sub_date = $resSchedule['sub_date'];
            $sub_count = $resSchedule['sub_count'];
            $schedDateTime = strtotime($sub_date);
            $schedDate = date("F d, Y", $schedDateTime);
        }
        $statusQuery = mysqli_query($conn, "SELECT gc_status FROM tbl_gcat WHERE gc_idnumber = '$id'");
        $status = mysqli_fetch_row($statusQuery);
        $queryName = mysqli_query($conn, "SELECT gc.*, si.si_lastname as si_lastname, si.si_firstname as si_firstname, si.si_email as si_email FROM tbl_studentinfo si INNER JOIN tbl_gcat gc ON si.si_idnumber = gc.gc_idnumber WHERE si_idnumber = '$id'");
        if(mysqli_num_rows($queryName)>0){
            while($res = mysqli_fetch_assoc($queryName)){
                $fname = $res['si_firstname'];
                $lname = $res['si_lastname'];
                $gcsubdate = $res['gc_subdate'];
                $email = $res['si_email'];
                $key = $res['gc_key'];
            }
        }
        $authenticated = true;
        if($status[0] == '0'){
            $updateStatus = "UPDATE tbl_gcat SET gc_status = '1' WHERE gc_idnumber = '$id'";
            if(mysqli_query($conn, $updateStatus)){
                if($gcsubdate == '0'){
                    $updateStatus2 = "UPDATE tbl_gcat SET gc_subdate = '$sub_recno' WHERE gc_idnumber = '$id'";
                    if(mysqli_query($conn, $updateStatus2)){
                        $updateStatus3 = "UPDATE tbl_gcatsubmitsched SET sub_count = sub_count + 1 WHERE sub_recno = '$sub_recno'";
                        if(mysqli_query($conn, $updateStatus3)){
                            $mail->addAddress($email);
                            $mail->Subject = "Gordon College Admission Test(GCAT) Registration";
                            $mail->Body = "Hello <b>{$fname} {$lname}</b> - Temporary ID Number: <b>{$id}</b><br>";
                            $mail->Body .="This is to inform you that you are advised to submit your GCAT registration requirements to be submitted on <b>{$schedDate} - 8:00AM to 3:00PM</b>:<br>";
                            $mail->Body .="1. Three (3) copies of <a href='https://gordoncollegeccs.edu.ph/gc/api/print/sis.php?id={$id}&key={$key}'>GCAT Printed Application Forms</a><br>";
                            $mail->Body .="2. Three (3) pieces 2x2 ID photos<br>";
                            $mail->Body .="3. Valid ID Card<br>";
                            $mail->Body .="4. F138/TOR (optional on this Submission but will be part of required documents when qualified to undergo program screening )<br><br>";
                            $mail->Body .="In case that you will not be able to submit your application personally, you may authorize your parent (mother and/or father) or your legal guardian to submit the requirements on your behalf by presenting these additional requirements:<br>";
                            $mail->Body .="5. Authorization letter from you<br>";
                            $mail->Body .="6. Copy of your VALID ID<br>";
                            $mail->Body .="7. Copy of VALID ID of your authorized representative<br><br>";
                            $mail->Body .="<b>DATE OF SUBMISSION : {$schedDate}</b><br>";
                            $mail->Body .="<b>TIME : 8:00am - 3:00 pm</b><br><br>";
                            $mail->Body .="Procedure:<br>";
                            $mail->Body .="1. Present to the Front Desk Security officer the printed GCAT form and get the temp Queue number<br>";
                            $mail->Body .="2. After that, present the temp Queue Number to the Internal Security Officer to exchange it for the GC REGISTRAR'S QUEUE NUMBER (GCRQN)<br>";
                            $mail->Body .="3. Wait for the GCRQN to be called or displayed in the monitor located at the GC REGISTRAR'S OFFICE<br>";
                            $mail->Body .="4. Once called, present the requirements to the Registrar's personnel for validation.<br>";
                            $mail->Body .="5. If all requirements are complete and complied, you will be given a schedule when you will take the GCAT.<br>";
                            $mail->Body .="6. End of GCAT APPLICATION VALIDATION.<br><br>";
                            $mail->Body .="Make sure to follow the said DATE of Submission. Your application will not be accommodated if you failed to follow the said DATE OF SUBMISSION.<br><br>";
                            $mail->Body .="This is your temporary id number: <b>{$id}</b>.<br><br>";
                            $mail->Body .="Please visit this link for your printable Form SR01(TO BE PRINTED ON A4 SIZE BOND PAPER):<br>";
                            // $mail->Body .="<b>http://localhost/gordoncollegeweb/print/sis.php?id={$id}&key={$key}</b><br><br>";
                            $mail->Body .="<b><a href='https://gordoncollegeccs.edu.ph/gc/api/print/sis.php?id={$id}&key={$key}'>https://gordoncollegeccs.edu.ph/gc/api/print/sis.php?id={$id}&key={$key}</a></b><br><br>";
                            $mail->Body .="If you need to edit your data, you can visit the link below:<br>";
                            $mail->Body .="<b><a href='https://gordoncollegeccs.edu.ph/gc/home/#/edit/{$id}/{$key}'>https://gordoncollegeccs.edu.ph/gc/home/#/edit/{$id}/{$key}</a></b><br><br>";
                            $mail->Body .="Kindly acknowledge receipt of this email by clicking on this link: <br>";
                            $mail->Body .="<b><a href='https://gordoncollegeccs.edu.ph/gc/api/confirmation/confirmation2.php?id={$id}&key={$key}'>https://gordoncollegeccs.edu.ph/gc/api/confirmation/confirmation2.php?id={$id}&key={$key}</a></b><br><br>";
                            $mail->Body .="Sincerely,<br>";
                            $mail->Body .="Gordon College Olongapo";
                            if ($mail->send()) {
                                $confirmation = true;
                            } else{
                                $confirmation = false;
                            echo $conn->error;
                            }
                        } else{
                            $confirmation = false;
                            echo $conn->error;
                          }
                           
                } else {
                        $confirmation = false;
                        echo $conn->error;

                }
            } else{
                $getApplicantSchedule = mysqli_query($conn, "SELECT * FROM tbl_gcatsubmitsched WHERE sub_recno = '$gcsubdate'");
                while($resAppSched = mysqli_fetch_assoc($getApplicantSchedule)){
                    $sub_date = $resAppSched['sub_date'];
                    $schedDateTime = strtotime($sub_date);
                    $schedDate = date("F d, Y", $schedDateTime);
                }
                $mail->addAddress($email);
                $mail->Subject = "Gordon College Admission Test(GCAT) Registration";
                $mail->Body = "Hello <b>{$fname} {$lname}</b> - Temporary ID Number: <b>{$id}</b><br>";
                $mail->Body .="This is to inform you that you are advised to submit your GCAT registration requirements to be submitted on <b>{$schedDate} - 8:00AM to 3:00PM</b>:<br>";
                $mail->Body .="1. Three (3) copies of <a href='https://gordoncollegeccs.edu.ph/gc/api/print/sis.php?id={$id}&key={$key}'>GCAT Printed Application Forms</a><br>";
                $mail->Body .="2. Three (3) pieces 2x2 ID photos<br>";
                $mail->Body .="3. Valid ID Card<br>";
                $mail->Body .="4. F138/TOR (optional on this Submission but will be part of required documents when qualified to undergo program screening )<br><br>";
                $mail->Body .="In case that you will not be able to submit your application personally, you may authorize your parent (mother and/or father) or your legal guardian to submit the requirements on your behalf by presenting these additional requirements:<br>";
                $mail->Body .="5. Authorization letter from you<br>";
                $mail->Body .="6. Copy of your VALID ID<br>";
                $mail->Body .="7. Copy of VALID ID of your authorized representative<br><br>";
                $mail->Body .="<b>DATE OF SUBMISSION : {$schedDate}</b><br>";
                $mail->Body .="<b>TIME : 8:00am - 3:00 pm</b><br><br>";
                $mail->Body .="Procedure:<br>";
                $mail->Body .="1. Present to the Front Desk Security officer the printed GCAT form and get the temp Queue number<br>";
                $mail->Body .="2. After that, present the temp Queue Number to the Internal Security Officer to exchange it for the GC REGISTRAR'S QUEUE NUMBER (GCRQN)<br>";
                $mail->Body .="3. Wait for the GCRQN to be called or displayed in the monitor located at the GC REGISTRAR'S OFFICE<br>";
                $mail->Body .="4. Once called, present the requirements to the Registrar's personnel for validation.<br>";
                $mail->Body .="5. If all requirements are complete and complied, you will be given a schedule when you will take the GCAT.<br>";
                $mail->Body .="6. End of GCAT APPLICATION VALIDATION.<br><br>";
                $mail->Body .="Make sure to follow the said DATE of Submission. Your application will not be accommodated if you failed to follow the said DATE OF SUBMISSION.<br><br>";
                $mail->Body .="This is your temporary id number: <b>{$id}</b>.<br><br>";
                $mail->Body .="Please visit this link for your printable Form SR01(TO BE PRINTED ON A4 SIZE BOND PAPER):<br>";
                // $mail->Body .="<b>http://localhost/gordoncollegeweb/print/sis.php?id={$id}&key={$key}</b><br><br>";
                $mail->Body .="<b><a href='https://gordoncollegeccs.edu.ph/gc/api/print/sis.php?id={$id}&key={$key}'>https://gordoncollegeccs.edu.ph/gc/api/print/sis.php?id={$id}&key={$key}</a></b><br><br>";
                $mail->Body .="If you need to edit your data, you can visit the link below:<br>";
                $mail->Body .="<b><a href='https://gordoncollegeccs.edu.ph/gc/home/#/edit/{$id}/{$key}'>https://gordoncollegeccs.edu.ph/gc/home/#/edit/{$id}/{$key}</a></b><br><br>";
                $mail->Body .="Kindly acknowledge receipt of this email by clicking on this link: <br>";
                $mail->Body .="<b><a href='https://gordoncollegeccs.edu.ph/gc/api/confirmation/confirmation2.php?id={$id}&key={$key}'>https://gordoncollegeccs.edu.ph/gc/api/confirmation/confirmation2.php?id={$id}&key={$key}</a></b><br><br>";
                $mail->Body .="Sincerely,<br>";
                $mail->Body .="Gordon College Olongapo";
                $alreadyConfirmed = true;
                if ($mail->send()) {
                    $confirmation = true;
                } else{
                    $confirmation = false;
                echo $conn->error;
                }
            }
                
            } else{
                $confirmation = false;
                echo $conn->error;

            }
        }
        else{   
            $getApplicantSchedule = mysqli_query($conn, "SELECT * FROM tbl_gcatsubmitsched WHERE sub_recno = '$gcsubdate'");
            while($resAppSched = mysqli_fetch_assoc($getApplicantSchedule)){
                $sub_date = $resAppSched['sub_date'];
                $schedDateTime = strtotime($sub_date);
                $schedDate = date("F d, Y", $schedDateTime);
            }
            $mail->addAddress($email);
            $mail->Subject = "Gordon College Admission Test(GCAT) Registration";
            $mail->Body = "Hello <b>{$fname} {$lname}</b> - Temporary ID Number: <b>{$id}</b><br>";
            $mail->Body .="This is to inform you that you are advised to submit your GCAT registration requirements to be submitted on <b>{$schedDate} - 8:00AM to 3:00PM</b>:<br>";
            $mail->Body .="1. Three (3) copies of <a href='https://gordoncollegeccs.edu.ph/gc/api/print/sis.php?id={$id}&key={$key}'>GCAT Printed Application Forms</a><br>";
            $mail->Body .="2. Three (3) pieces 2x2 ID photos<br>";
            $mail->Body .="3. Valid ID Card<br>";
            $mail->Body .="4. F138/TOR (optional on this Submission but will be part of required documents when qualified to undergo program screening )<br><br>";
            $mail->Body .="In case that you will not be able to submit your application personally, you may authorize your parent (mother and/or father) or your legal guardian to submit the requirements on your behalf by presenting these additional requirements:<br>";
            $mail->Body .="5. Authorization letter from you<br>";
            $mail->Body .="6. Copy of your VALID ID<br>";
            $mail->Body .="7. Copy of VALID ID of your authorized representative<br><br>";
            $mail->Body .="<b>DATE OF SUBMISSION : {$schedDate}</b><br>";
            $mail->Body .="<b>TIME : 8:00am - 3:00 pm</b><br><br>";
            $mail->Body .="Procedure:<br>";
            $mail->Body .="1. Present to the Front Desk Security officer the printed GCAT form and get the temp Queue number<br>";
            $mail->Body .="2. After that, present the temp Queue Number to the Internal Security Officer to exchange it for the GC REGISTRAR'S QUEUE NUMBER (GCRQN)<br>";
            $mail->Body .="3. Wait for the GCRQN to be called or displayed in the monitor located at the GC REGISTRAR'S OFFICE<br>";
            $mail->Body .="4. Once called, present the requirements to the Registrar's personnel for validation.<br>";
            $mail->Body .="5. If all requirements are complete and complied, you will be given a schedule when you will take the GCAT.<br>";
            $mail->Body .="6. End of GCAT APPLICATION VALIDATION.<br><br>";
            $mail->Body .="Make sure to follow the said DATE of Submission. Your application will not be accommodated if you failed to follow the said DATE OF SUBMISSION.<br><br>";
            $mail->Body .="This is your temporary id number: <b>{$id}</b>.<br><br>";
            $mail->Body .="Please visit this link for your printable Form SR01(TO BE PRINTED ON A4 SIZE BOND PAPER):<br>";
            // $mail->Body .="<b>http://localhost/gordoncollegeweb/print/sis.php?id={$id}&key={$key}</b><br><br>";
            $mail->Body .="<b><a href='https://gordoncollegeccs.edu.ph/gc/api/print/sis.php?id={$id}&key={$key}'>https://gordoncollegeccs.edu.ph/gc/api/print/sis.php?id={$id}&key={$key}</a></b><br><br>";
            $mail->Body .="If you need to edit your data, you can visit the link below:<br>";
            $mail->Body .="<b><a href='https://gordoncollegeccs.edu.ph/gc/home/#/edit/{$id}/{$key}'>https://gordoncollegeccs.edu.ph/gc/home/#/edit/{$id}/{$key}</a></b><br><br>";
            $mail->Body .="Kindly acknowledge receipt of this email by clicking on this link: <br>";
            $mail->Body .="<b><a href='https://gordoncollegeccs.edu.ph/gc/api/confirmation/confirmation2.php?id={$id}&key={$key}'>https://gordoncollegeccs.edu.ph/gc/api/confirmation/confirmation2.php?id={$id}&key={$key}</a></b><br><br>";
            $mail->Body .="Sincerely,<br>";
            $mail->Body .="Gordon College Olongapo";
            $alreadyConfirmed = true;
            if ($mail->send()) {
                $confirmation = true;
            } else{
                $confirmation = false;
            echo $conn->error;
            }     
            $alreadyConfirmed = true;

        }

    } else{
        $authenticated = false;
        echo $conn->error;

    }
?>

<html>
<head>
    <script src="js/jquery.min.js"></script>
    <style>
img.bg {
  /* Set rules to fill background */
  min-height: 100%;
  min-width: 1024px;
	
  /* Set up proportionate scaling */
  width: 100%;
  height: auto;
	
  /* Set up positioning */
  position: fixed;
  top: 0;
  left: 0;
  z-index: -1;
  opacity: .5;
}

@media screen and (max-width: 1024px) { /* Specific to this particular image */
  img.bg {
    left: 50%;
    margin-left: -512px;   /* 50% */
  }
}
    </style>
</head>
<body style="text-align: center">
<img src="image/gc.jpg" class="bg" alt="">
<div style="margin-top: 10%;">
<?php 
    if($authenticated == true){
        if($alreadyConfirmed == true){
            echo "<h1>Your GCAT application has already been confirmed!</h1>";
            echo "<img src='image/gc-log.png' alt=''>";
            echo "<h1>Please check your inbox at email: {$email} for our message regarding your requirements submission schedule.</h1>";
            echo "<h1>Thank you for your compliance.</h1>";
            echo "<h3>You may close this window now.</h3>";
        } else {
            if($confirmation == true){
            echo "<h1>Your GCAT application has been confirmed!</h1>";
            echo "<img src='image/gc-log.png' alt=''>";
            echo "<h1>Please check your inbox at email: {$email} for our message regarding your requirements submission schedule.</h1>";
            echo "<h1>Thank you for your compliance.</h1>";
            echo "<h3>You may close this window now.</h3>";
            } else{
                echo "<h1>There has been an error in your confirmation!</h1>";
                echo "<img src='image/gc-log.png' alt=''>";
                echo "<h1>Please try again, or reply to our email.</h1>";
                echo "<h3>You may close this window now.</h3>";
            }
        }
        
    } else{
        echo '<h1>Unrecognized Access!</h1>';
        echo "<img src='image/gc-log.png' alt=''>";

        echo '<h1>You are not authorized to view this page, please close this window now.</h1>';
    }
?>
</div>
</body>
</html>