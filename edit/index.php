<?php
  require_once '../config/connect.php';

  // get enlistment info
  $query = mysqli_query($conn, "SELECT * FROM tbl_enlistment WHERE en_isactive='ACTIVE'");
  if(mysqli_num_rows($query)>0){
      while($res = mysqli_fetch_assoc($query)){
          $en_schoolyear = $res['en_schoolyear'];
          $en_sem = $res['en_sem'];
      }
  }

  // get student info
  
  $ciphering = "AES-128-CTR"; 
  $iv_length = openssl_cipher_iv_length($ciphering); 
  $options = 0; 
  $id = $_GET['id'];
  $key = rawurldecode($_GET['key']);
  $decryption_iv = '1234567891011121'; 
  
// Store the decryption key 
  $decryption_key = "fsociety"; 
  
// Use openssl_decrypt() function to decrypt the data 
  $decryptedkey=openssl_decrypt ($key, $ciphering,  
              $decryption_key, $options, $decryption_iv); 
  if($id == $decryptedkey){
    $query = mysqli_query($conn,"SELECT * FROM tbl_studentinfo WHERE si_idnumber = '$id'");
    if(mysqli_num_rows($query)>0){
        while($res = mysqli_fetch_assoc($query)){
  
  
            $sex = $res['si_gender'];
            $category =  $res['si_studenttype'];
            $govprojother =  $res['si_govprojothers'];
            $govprogs = $res['si_govproj'];
            $govprog = explode(", ", $govprogs);
            $lastname = $res['si_lastname'];
            $firstname = $res['si_firstname'];
            $extname = $res['si_extname'];
            $midname = $res['si_midname'];
            $email = $res['si_email'];
            $address = $res['si_address'];
            $zipcode = $res['si_zipcode'];
            $mobile = $res['si_mobile'];
            $gender = $res['si_gender'];
            $bday = $res['si_bday'];
            $course = $res['si_course'];
            $course2 = $res['si_coursechoice'];
            $course3 = $res['si_coursechoice2'];
            $year = $res['si_yrlevel'];
            $address = $res['si_address'];
            $department = $res['si_department'];
            $lastchool = $res['si_lastschool'];
            $type =$res['si_studenttype'];
  
            if($res['si_junior']!=""){
                $lastchool = $res['si_junior'];
            }
            if($res['si_senior']!=""){
                $lastchool = $res['si_senior'];
            }
  
            $dadname = $res['si_dadname'];
            $dadoccupation = $res['si_dadoccupation'];
            $motherdead = $res['si_momdeceased'];
            $fatherdead = $res['si_daddeceased'];
            $momname = $res['si_momname'];
            $momoccupation = $res['si_momoccupation'];
            $dadeduc = $res['si_educationdad'];
            $momeduc = $res['si_educationmom'];
  
            $age = $res['si_age'];
            $pob = $res['si_pob'];
            $lrn = $res['si_lrn'];
            $guardianrel = $res['si_guardrel'];
            $guardadd = $res['si_guardadd'];
            $emergency = $res['si_emergencycontact'];
            $govprojother = $res['si_govprojothers'];
            $famincome = $res['si_famincome'];
            $isdisabled = $res['si_isdisabled'];
            $disability = $res['si_disability'];
            $guardname = $res['si_guardname'];
            $household = $res['si_householdno'];
            $studenttype = $res['si_studenttype'];
            $guardnumber = $res['si_emergencycontact'];
  
            $elem = $res['si_elem'];
            $elemyear = $res['si_elemyear'];
  
            $senior = $res['si_senior'];
            $senioryear = $res['si_senioryear'];
            $strand = $res['si_strand'];
  
            $tertiary = $res['si_tertiary'];
            $tertiarycourse = $res['si_tertiarycourse'];
            $tertiaryyear = $res['si_tertiaryyear'];
  
            
            $vocational = $res['si_vocational'];
            $vocationalcourse = $res['si_vocationalcourse'];
            $vocationalyear = $res['si_vocationalyear'];
  
            $award = $res['si_specialaward'];
            $houseno = $res['si_houseno'];
            $brgy = $res['si_brgy'];
            $city = $res['si_city'];
            $province = $res['si_province'];
            $citizenship = $res['si_nationality'];
            $brothers = $res['si_brothers'];
            $sisters = $res['si_sisters'];
            $nc = $res['si_nc'];
            $nclvl = $res['si_nclvl'];
            $english = $res['si_english'];
            $math = $res['si_math'];
            $science = $res['si_science'];
            $civilstatus = $res['si_civilstatus'];
            $highschool = $res['si_lastschool'];
            $highschoolyear = $res['si_highschoolyear'];
            $gpa = $res['si_average'];
            $ipgroup = $res['si_ipgroup'];
        }
    }

?>


<!DOCTYPE html>
<html>
  <head>
  <script src="js/jquery.min.js"></script>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  <meta charset="UTF-8">
    <title>EDIT INFO</title>
    <link rel="stylesheet" type="text/css" href="css/SIS.css">
  </head>
  <body>
  <form>
  <div class="form-group">
    <label for="exampleInputEmail1">Email address</label>
    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
  </div>
  <div class="form-check">
    <input type="checkbox" class="form-check-input" id="exampleCheck1">
    <label class="form-check-label" for="exampleCheck1">Check me out</label>
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
  </body>
</html> 


<script src="js/jquery.min.js"></script>
  <?php $x=0;
 while($x<sizeof($govprog)){ 
   ?>
            <script>  
            $( document ).ready(function(){
              $("#<?php echo $govprog[$x];?>").prop('checked', true);
            });
            </script>
            <?php $x++; } ?>
<script>
    $( document ).ready(function(){

        sex = "<?php echo $sex; ?>";
        en_sem = "<?php echo $en_sem; ?>";
        momdead = "<?php echo $motherdead; ?>";
        daddead = "<?php echo $fatherdead; ?>";
        disabled = "<?php echo $isdisabled; ?>";

        if(sex === 'Male') {
            $('#male').prop('checked', true);
        } else { 
            $('#female').prop('checked', true);
        }

        if(disabled === '1') {
            $('#disabilitiesY').prop('checked', true);
        } else { 
            $('#disabilitiesN').prop('checked', true);
        }

        if(momdead === '0') {
            $('#motherdeceased').prop('checked', true);
        } else { 
            $('#motherliving').prop('checked', true);
        }

        if(daddead === '0') {
            $('#fatherdeceased').prop('checked', true);
        } else { 
            $('#fatherliving').prop('checked', true);
        }

        if(en_sem === '1') {
            $('#sem1').prop('checked', true);
        }else if(en_sem === '2'){
            $('#sem2').prop('checked', true);
        }else if(en_sem === 'Mid Year'){
            $('#semMY').prop('checked', true);
        }

        $('#<?php echo $studenttype; ?>').prop('checked', true);
        $('#<?php echo $civilstatus; ?>').prop('checked', true);

        window.print();
        
    });
</script> 

<?php
    } else{
?>
    <script>
    window.location.href = "https://gordoncollegeccs.edu.ph/gc/home/";
    </script>
<?php
}?>