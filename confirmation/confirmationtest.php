<?php
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
    // $now = date("Y-m-d");
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
        $queryName = mysqli_query($conn, "SELECT gc.*, si.si_lastname as si_lastname, si.si_firstname as si_firstname, si.si_email as si_email FROM tbl_studentinfo si INNER JOIN tbl_gcat gc ON si.si_idnumber = gc.gc_idnumber WHERE si_idnumber = '$id'");
        if(mysqli_num_rows($queryName)>0){
            while($res = mysqli_fetch_assoc($queryName)){
                $fname = $res['si_firstname'];
                $lname = $res['si_lastname'];
            }
        }
                $authenticated = true;
                $mail->addAddress('gcat@gordoncollegeccs.edu.ph');
                $mail->Subject = "GCAT Registration Confirmation";
                $mail->Body = "Applicant <b>{$fname} {$lname} - ID Number: {$id}</b> has acknowledged receipt of his/her confirmation email.<br>";
                
                    if ($mail->send()) {
                        $confirmation = true;
                    } else {
                        $confirmation = false;
                    }
} else{
    $authenticated = false;
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
            echo "<h1>Thank you for your compliance.</h1>";
            echo "<h3>You may close this window now.</h3>";
        } else {
            if($confirmation == true){
            echo "<h1>Your GCAT application has been confirmed!</h1>";
            echo "<img src='image/gc-log.png' alt=''>";
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