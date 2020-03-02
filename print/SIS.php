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
    $statusQuery = mysqli_query($conn, "SELECT gc_status FROM tbl_gcat WHERE gc_idnumber = '$id'");
    $status = mysqli_fetch_row($statusQuery);
    $query = mysqli_query($conn,"SELECT * FROM tbl_studentinfo WHERE si_idnumber = '$id'");
    if(mysqli_num_rows($query)>0){
      if($status[0] == '0'){
        $sqlUpdateStatus = "UPDATE tbl_gcat SET gc_status = 1 WHERE gc_idnumber = '$id'";
        mysqli_query($conn, $sqlUpdateStatus);
      }
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
  <meta charset="UTF-8">
    <title>Student Information Sheet</title>
    <link rel="stylesheet" type="text/css" href="css/SIS.css">
  </head>
  <body>

  <div class="twobytwopic ">
<br><br><br><br><br><br><br><br>
    <h3>   2x2 Picture 
        White Background</h3>
  </div>
	 <table class="txt table-body"> 
    <tbody>
      <tr>
        <td width="100%" colspan="2">Form SR01 Rev.01.2020</td>
      </tr>
      <tr class="txt-box">
        <td width="10%"  colspan="1">
          <img src="image/gc-log.png" width="96">
        </td>
        <td class="left"  colspan="1">
          <h1 class="verticalmargin">GORDON COLLEGE</h1>
          <p  class="verticalmargin">Olongapo City Sports Complex, Donor Street</p>
          <p  class="verticalmargin">East Tapinac, Olongapo City</p>
          <h3  class="verticalmargin">Office of the Registrar</h3>
        </td>
      </tr>
    </tbody>
  </table>
  <table class="txt table-body"> 
    <tbody>
      <tr>
        <td class="center" colspan="3">
          <h2>STUDENT INFORMATION SHEET</h2>
        </td>
      </tr>
      <tr>
        <td class="left" width="10%" style="position: absolute; margin-top: -20px">
          <div  style="text-align:center;font-size: 15px"><strong><?php echo $id; ?></strong></div>
          <div class="top-border center">REGISTRATION #</div>
        </td>
        <td></td>
      </tr>
    </tbody>
  </table>
<div style="padding-top:15px"></div>
  <strong class="txt verticalmargin darken"><u>PLEASE FILL IN COMPLETELY, PROCESSING MAY BE DELAYED ON INCOMPLETE FORMS</u></strong>

    
  <table class="txt table-body"  width="1000" border="1">
    <tbody>
      <tr class="border-thin">
        
        <td class="border-thin" colspan="3">
          <strong>ACADEMIC YEAR: </strong> <?php echo $en_schoolyear; ?>
        </td>
        <td class="border-thin" colspan="4">
          <strong>Semester: </strong>
          <input type="checkbox" id="sem1" >
          <label for="sem1">First</label>

          <input type="checkbox" id="sem2" >
          <label for="sem2">Second</label>
          
          <input type="checkbox" id="semM" >
          <label for="semMY">Mid-Year</label>
        </td>
      </tr>
      <tr class="border-thin">
        <td class="border-thin darken" width="166"><strong>COURSE  DESIRED:</strong></td>
        <td class="border-thin " width="166"><strong>1st Choice:</strong></td>
        <td class="border-thin" width="166"><?php echo $course; ?></td>
        <td class="border-thin" width="125"><strong>2nd Choice:</strong></td>
        <td class="border-thin" width="125"><?php echo $course2; ?></td>
        <td class="border-thin" width="125"><strong>3rd Choice:</strong></td>
        <td class="border-thin" width="125"><?php echo $course3; ?></td>
      </tr>
    </tbody>
  </table>
  <h5 class="darken border-thin verticalmargin">1. PERSONAL BACKGROUND</h5>
  <table class="txt table-body"  width="1000" border="1">
    <tbody>
      <tr  class="border-thin">
        <td class="border-thin" width="250" colspan="2">Last Name: <?php echo $lastname; ?></td>
        <td class="border-thin" width="250"  colspan="2">First Name: <?php echo $firstname; ?></td>
        <td class="border-thin" width="250"  colspan="2">Middle Name: <?php echo $midname; ?></td>
        <td class="border-thin" width="250"  colspan="2">Extension Name: <?php echo $extname; ?></td>
      </tr>
      <tr  class="border-thin">
        <td class="border-thin" width="1000" colspan="8"><strong><i>Complete Address</strong></i></td>
      </tr>
    </tbody>
  </table>
  <table  class="txt table-body"  width="1000" border="1">
    <tbody>
      <tr  class="border-thin">
        <td class="border-thin" width="125" colspan="1">House# & Street:</td>
        <td class="border-thin" width="375" colspan="3"> <?php echo $houseno; ?></td>
        <td class="border-thin" width="125" colspan="1">Barangay:</td>
        <td class="border-thin" width="375" colspan="3"><?php echo $brgy; ?></td>
      </tr>
    </tbody>
  </table>  
  <table  class="txt table-body"  width="1000" border="1">
    <tbody>
      <tr  class="border-thin">
        <td class="border-thin" width="133" >City/Municipality:</td>
        <td class="border-thin" width="200"><?php echo $city; ?></td>
        <td class="border-thin" width="133" >Province:</td>
        <td class="border-thin" width="200" ><?php echo $province; ?></td>
        <td class="border-thin" width="133">Zip Code:</td>
        <td class="border-thin" width="200"><?php echo $zipcode; ?></td>
      </tr>
    </tbody>
  </table>
  <table  class="txt table-body"  width="1000" border="1">
    <tbody>
      <tr  class="border-thin">
        <td class="border-thin" width="333" >Contact Number/s: <?php echo $mobile; ?></td>
        <td class="border-thin" width="333">E-mail Address: <?php echo $email; ?></td>
        <td class="border-thin" width="333" >
          Sex: 
          <input type="checkbox" id="male" >
          <label for="male">Male</label>
          
          <input type="checkbox" id="female" >
          <label for="female">Female</label>
        </td>
      </tr>
    </tbody>
  </table>
  
  <table  class="txt table-body"  width="1000" border="1">
    <tbody>
      <tr  class="border-thin">
        <td class="border-thin" width="400" >Date of Birth: <?php echo $bday; ?></td>
        <td class="border-thin" width="200" >Age: <?php echo $age; ?></td>
        <td class="border-thin" width="400" >Citizenship: <?php echo $citizenship; ?></td>
      </tr>
      <tr  class="border-thin">
        <td class="border-thin" width="1000" colspan="3">Place of Birth: <?php echo $pob; ?></td>
      </tr>
      <tr  class="border-thin">
        <td class="border-thin" width="1000" colspan="3">Civil Status: 
          <input type="checkbox" id="SINGLE" >
          <label for="single">Single</label>
          <input type="checkbox" id="MARRIED" >
          <label for="married">Married</label>
          <input type="checkbox" id="WIDOW" >
          <label for="widow">Widow</label>
          <input type="checkbox" id="SEPARATED" >
          <label for="separated">Separated</label>
          </td>
      </tr>
    </tbody>
  </table>

  <table  class="txt table-body"  width="1000" border="1">
    <tbody>
      <tr  class="border-thin">
        <td class="border-thin" width="500" >Belongs to person with disabilities (PWDs): 
          <input type="checkbox" id="disabilitiesY" >
          <label for="disabilitiesY">Yes</label>
          <input type="checkbox" id="disabilitiesN" >
          <label for="disabilitiesN">No</label></td>
        <td class="border-thin" width="500" >Type of disability (<i>if applicable</i>): <?php echo $disability; ?></td>
      </tr>
    </tbody>
  </table>
  <h5 class="darken border-thin verticalmargin">2. ENTRANCE CATEGORY</h5>
  <table  class="txt table-body"  width="1000" border="1">
    <tbody>
      <tr  class="border-thin">
        <td class="border-thin center" width="" > 
          <input type="checkbox" id="new" >
          <label for="freshman">FRESHMAN</label>
  
          <input type="checkbox" id="transferee" >
          <label for="transferee">TRANSFEREE</label>
          
          <input type="checkbox" id="second" >
          <label for="secondcourser">SECOND COURSER</label>

      </tr>
    </tbody>
  </table>

  <h5 class="darken border-thin verticalmargin">3. FAMILY BACKGROUND</h5>
  <table  class="txt table-body"  width="1000" border="1">
    <tbody>
      <tr  class="border-thin">
        <td class="border-thin center" height="30" colspan="2">
    
          Father: 
          
          <input type="checkbox" id="fatherliving" >
          <label for="fatherliving">Living</label>
          
          <input type="checkbox" id="fatherdeceased" >
          <label for="fatherdeceased">Deceased</label> 
        
        </td>

        <td class="border-thin center" width="" colspan="2">
          Mother: 

          <input type="checkbox" id="motherliving" >
          <label for="motherliving">Living</label>
          
          <input type="checkbox" id="motherdeceased" >
          <label for="motherdeceased">Deceased</label></td>
      </tr>
      
      <tr  class="border-thin">
        <td class="border-thin" width="100" >Name: </td>
        <td class="border-thin" width="" > <?php echo $dadname; ?></td>
        <td class="border-thin" width="100" >Name: </td>
        <td class="border-thin" width="" >  <?php echo $momname; ?></td>
      </tr>
      <tr  class="border-thin">
        <td class="border-thin">Occupation:</td>
        <td class="border-thin"><?php echo $dadoccupation; ?> </td>
        <td class="border-thin">Occupation:</td>
        <td class="border-thin"><?php echo $momoccupation; ?></td>
      </tr>
      <tr  class="border-thin">
        <td class="border-thin">Educational Attainment:</td>
        <td class="border-thin"><?php echo $dadeduc; ?></td>
        <td class="border-thin">Educational Attainment:</td>
        <td class="border-thin"><?php echo $momeduc; ?></td>
      </tr>
      <tr  class="border-thin">
        <td class="border-thin">No. of Brothers:</td>
        <td class="border-thin"><?php echo $brothers; ?></td>
        <td class="border-thin">No. of Sisters:</td>
        <td class="border-thin"><?php echo $sisters; ?></td>
      </tr>
    </tbody>
  </table>
  <h5 class="darken border-thin verticalmargin"> <i>4. Guardian (if any) who supports your study</i> </h5>
  <table class="txt table-body" border="1">
    <tbody>
      <tr  class="border-thin">
        <td class="border-thin" width="100">Name of Guardian: </td>
        <td class="border-thin" width="200"><?php echo $guardname; ?></td>
        <td class="border-thin" width="100">Relationship: </td>
        <td class="border-thin " width="273"><?php echo $guardianrel; ?></td>
      </tr>
      <tr class="border-thin">
        <td class="border-thin">Address: </td>
        <td class="border-thin"><?php echo $guardadd; ?></td>
        <td class="border-thin">Contact No. </td>
        <td class="border-thin"><?php echo $guardnumber; ?></td>
      </tr>
    </tbody>
  </table>


<h5 class="darken border-thin verticalmargin"> <i>5. GOVERNMENT PROGRAM/ SCHOLARSHIP GRANT:</i> </h5>
  <table class="txt table-body" border="1" width="1000">
    <tbody>
      <tr>
        <td class="border-thin" width="500">
          
          <input type="checkbox" id="4ps">
          <label for="fourps" class="m-top">Pantawid Pamilyang Pilipino (4P's)</label>
          <br>
          <input type="checkbox" id="listahan">
          <label for="listahan" class="m-top">DSWD Listahan 2.0 ( Household No. __<u><?php echo $household; ?></u>__)</label>
          <br>
          <input type="checkbox" id="esgppa">
          <label for="esgppa" class="m-top">ESGPPA Beneficiary</label>
          <br>
          <input type="checkbox" id="ips">
          <label for="ips" class="m-top">Indigenous People (IP) Group: ____<u><?php echo $ipgroup; ?></u>____</label> 
          <br>
          <input type="checkbox" id="solo">
          <label for="solo" class="m-top">Solo parent / Child of a Solo Parent</label>
          
        </td><td class="border-thin" width="500">
          <input type="checkbox" id="noincome">
          <label for="noincome" class="m-top">No Income Household</label>

          <br>
          <input type="checkbox" id="Others">
          <label for="other-cc">Others: Please Specify ______<u><?php echo $govprojother; ?></u>_______</label>
          <br>
          Net Monthly Family Income (Total Monthly Income Less Expenses) ______<u><?php echo $famincome; ?></u>______
       
        </td>
      </tr>
    </tbody>
  </table>
  <h5 class="darken border-thin verticalmargin"> <i>6. EDUCATIONAL BACKGROUND</i> </h5>
  <table  class="txt table-body"  width="1000" border="1">
    <tbody>
      <tr  class="border-thin">
        <td class="border-thin" width="550" >Elementary: <?php echo $elem; ?> </td>
        <td class="border-thin" width="" style="font-size: 0.7rem">Year Completed:  <?php echo $elemyear; ?></td>
      </tr>
      
      <tr  class="border-thin">
        <td class="border-thin" width="" >High School/Senior High School: <?php echo $highschool; ?></td> </td>
        <td class="border-thin" width="" style="font-size: 0.7rem">Year Completed: <?php echo $highschoolyear; ?></td></td></td>
      </tr>
    </tbody>
  </table>


  <table  class="txt table-body"  width="1000" border="1">
    <tbody>
   
      
      <tr  class="border-thin">
        <td class="border-thin" width="200" >Senior High School Strand</td>
        <td class="border-thin" width="" ><?php echo $strand; ?></td> </td>
      </tr>
    </tbody>
  </table>





  <table  class="txt table-body"  width="1000" border="1">
    <tbody>
   
      
      <tr  class="border-thin">
        <td class="border-thin" width="250" >College: <?php echo $tertiary; ?></td>
        <td class="border-thin" width="325" >Course: <?php echo $tertiarycourse; ?></td>
        <td class="border-thin" width="" > Year Completed: <?php echo $tertiaryyear; ?></td>
      </tr>
    </tbody>
  </table>

  <table  class="txt table-body"  width="1000" border="1">
    <tbody>
      <tr  class="border-thin">
        <td class="border-thin" width="200">Last Term Grade (if SHS): </td>
        
        <td class="border-thin" >English: <?php echo $english; ?></td>
        <td class="border-thin" >Math: <?php echo $math; ?></td>
        <td class="border-thin" >Science: <?php echo $science; ?></td>
        <td class="border-thin" >GPA: <?php echo $gpa; ?></td>
      </tr>
    </tbody>
  </table>
  
  <table  class="txt table-body"  width="1000" border="1">
    <tbody>
      <tr  class="border-thin">
        <td class="border-thin" width="500" >Tech-Voc Course: <?php echo $vocationalcourse; ?></td>
        <td class="border-thin" width="" >Year Completed: <?php echo $vocationalyear; ?></td>
      </tr>
      
      <tr  class="border-thin">
        <td class="border-thin" width="" >National Certification (NC): <?php echo $nc; ?> </td>
        <td class="border-thin" width="" >NC Level: <?php echo $nclvl; ?></td>
      </tr>

      <tr  class="border-thin">
        <td class="border-thin" width="" colspan="2" >Scholastic Honors and Distinctions Obtained (if any):  <?php echo $award; ?></td>
      </tr>
      <tr  class="border-thin center" width="500">
        <td class="border-thin" >
          <i>I hereby certify that the above information is true and correct to the best of my knowledge and ability.</i><br>
          <strong>Student's Signature __________________________________ Date: ____________________________</strong>
          
        </td>
        <td class="border-thin left" >
           <small><i>(For registrar's use only)</i> </small> <br>
            <strong>Date & TIME: _________ - _______</strong> <br>
            <strong>Room #: ________________________</strong> <br>
            <strong>Issued By: _____________________</strong> 
          </td>
      </tr>
    </tbody>
  </table>
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