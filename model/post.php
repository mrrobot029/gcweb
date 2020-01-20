<?php
    class Post{

        private $conn;
        private $sql;
        private $result;
        private $data = array();
        private $info = [];

        public function __construct($db){
            $this->conn = $db;
        }

        // admin/facultymembers (page)
                function getFaculty($d){
                    return $this->executeWithRes("SELECT * from tbl_faculty WHERE fa_department='".$d->data[0]->fa_department."' ORDER BY fa_lname,fa_fname,fa_mname,fa_extname ASC");
                }

                function delFaculty($d){
                    return $this->executeWithoutRes("DELETE from tbl_faculty WHERE fa_empnumber='$d->empNo'");
                }

                function addFaculty($d){
                    return $this->executeWithoutRes("DELETE from tbl_faculty WHERE fa_empnumber='$d->empNo'");
                }

        // admin/subjectprospectus (page)
                function getProspectus($d){
                    return $this->executeWithRes("SELECT * from tbl_subjects WHERE su_course='$d->courseName'AND su_cy='$d->syCy' ORDER BY su_yrlevel, su_sem ASC");
                }

                function addProspectus($d){
                    return $this->executeWithoutRes("INSERT INTO tbl_subjects (su_code, su_description, su_lecunits, su_labunits, su_rleunits, su_prereq, su_sem, su_yrlevel, su_cy, su_course) VALUES ('$d->suCode', '$d->suDesc', '$d->suLecu', '$d->suLabu', '$d->suRleu', '$d->suPre', '$d->suSem', $d->suYear, '$d->suCy', '$d->suCourse')");
                }

                function delProspectus($d){
                    return $this->executeWithoutRes("DELETE from tbl_subjects WHERE su_recno='$d->su_recno'");
                }
                
                function updateProspectus($d){
                    return $this->executeWithoutRes("UPDATE tbl_subjects SET su_code='$d->suCode',su_description='$d->suDesc',su_lecunits='$d->suLecu',su_labunits='$d->suLabu',su_rleunits='$d->suRleu',su_prereq='$d->suPre',su_sem='$d->suSem',su_yrlevel='$d->suYear',su_cy='$d->suCy',su_course='$d->suCourse' WHERE su_recno = '$d->recNo'");
                }

                function uploadProspectus(){
                    if(isset($_FILES['file'])){
            
                        $file_name = $_FILES['file']['name'];
                        $target_dir = "../filesFP/".$file_name;
                        $file_explodedname = explode('.', $file_name);
                        $file_ext = strtolower(end($file_explodedname) );
            
                        $extensions = array("xlsx");
            
                        if(in_array($file_ext,$extensions)){ // check if file is excel
            
                            if(move_uploaded_file($_FILES['file']['tmp_name'], $target_dir)){
            
                                require_once "Classes/PHPExcel.php";
                                
                                $excelReader = PHPExcel_IOFactory::createReaderForFile($target_dir);
                                $excelObj = $excelReader->load($target_dir);
                                $worksheet = $excelObj->getSheet(0);
                                $lastRow = $worksheet->getHighestRow();
            
                                for ($row = 4; $row <= $lastRow; $row++) {
                                    
                                    $proCode = $worksheet->getCell('A'.$row)->getValue();
                                    $proTitle = $worksheet->getCell('B'.$row)->getValue();
                                    $proLecu = $worksheet->getCell('C'.$row)->getValue();
                                    $proLabu = $worksheet->getCell('D'.$row)->getValue();
                                    $proRleu = $worksheet->getCell('E'.$row)->getValue();
                                    $proPre = $worksheet->getCell('F'.$row)->getValue();
                                    $proSem = $worksheet->getCell('G'.$row)->getValue();
                                    $proYear = $worksheet->getCell('H'.$row)->getValue();
                                    $proCourse = $worksheet->getCell('B2')->getValue();
                                    $proCy = $worksheet->getCell('H2')->getValue();
                                    $query = "INSERT INTO tbl_subjects(su_code,su_description,su_lecunits,su_labunits,su_rleunits,su_prereq,su_sem,su_yrlevel,su_cy,su_course)
                                    VALUES('$proCode','$proTitle','$proLecu','$proLabu','$proRleu','$proPre','$proSem','$proYear','$proCy','$proCourse')";
                                    
                                    $this->conn->query($query);
            
                                }
                                
                                return $this->info = array(
                                    'status'=>array(
                                        'remarks'=>true,
                                        'message'=>'Uploading success.'
                                    ),
                                    'data' =>$this->data,
                                    'timestamp'=>date_create(),
                                    'prepared_by'=>'F-Society'
                                );
            
                            }else{
                                return $this->info = array('status'=>array(
                                    'remarks'=>false,
                                    'message'=>'Uploading failed.'),
                                'timestamp'=>date_create(),
                                'prepared_by'=>'F-Society' );
                            }
            
                        }else{
                            return $this->info = array('status'=>array(
                                'remarks'=>false,
                                'message'=>'Invalid file for uploading faculty.'),
                            'timestamp'=>date_create(),
                            'prepared_by'=>'F-Society' );
                        }
                        
                        
                    }else{
                        return $this->info = array('status'=>array(
                            'remarks'=>false,
                            'message'=>'No file uploaded.'),
                        'timestamp'=>date_create(),
                        'prepared_by'=>'F-Society' );
                    }
            
                }

                // admin/filters
                function getProspectusCourse($d){
                    return $this->executeWithRes("SELECT DISTINCT co_name from tbl_courses WHERE co_dept = '$d->deptName'");
                }
                function getProspectusCy($d){
                    return $this->executeWithRes("SELECT DISTINCT su_cy from tbl_subjects WHERE su_course = '$d->courseName'");
                }



                // admin/classes (page)

                
                function getClass($d) {
                    return $this->executeWithRes("SELECT * from tbl_classes WHERE (cl_sem = '$d->sem' and cl_schoolyear='$d->SY') and cl_block LIKE '%$d->block%'");                    
                }

                function uploadClass(){
                    if(isset($_FILES['file'])){
            
                        $file_name = $_FILES['file']['name'];
                        $target_dir = "../filesFP/".$file_name;
                        $file_explodedname = explode('.', $file_name);
                        $file_ext = strtolower(end($file_explodedname) );
            
                        $extensions = array("xlsx");
            
                        if(in_array($file_ext,$extensions)){ // check if file is excel
            
                            if(move_uploaded_file($_FILES['file']['tmp_name'], $target_dir)){
            
                                require_once "Classes/PHPExcel.php";
                                
                                $excelReader = PHPExcel_IOFactory::createReaderForFile($target_dir);
                                $excelObj = $excelReader->load($target_dir);
                                $worksheet = $excelObj->getSheet(0);
                                $lastRow = $worksheet->getHighestRow();
            
                                for ($row = 4; $row <= $lastRow; $row++) {
                                    
                                    $a = $worksheet->getCell('A'.$row)->getValue();
                                    $b = $worksheet->getCell('B'.$row)->getValue();
                                    $c = $worksheet->getCell('C'.$row)->getValue();
                                    $d = PHPExcel_Style_NumberFormat::toFormattedString($worksheet->getCell('D'.$row)->getCalculatedValue(), 'hh:mm AM/PM');
                                    $e = PHPExcel_Style_NumberFormat::toFormattedString($worksheet->getCell('E'.$row)->getCalculatedValue(), 'hh:mm AM/PM');
                                    $f = $worksheet->getCell('F'.$row)->getValue();
                                    $g = $worksheet->getCell('G'.$row)->getValue();
                                    $h = $worksheet->getCell('H'.$row)->getValue();
                                    $i = $worksheet->getCell('B2')->getValue();
                                    $j = $worksheet->getCell('G2')->getValue();
                                    $query = "INSERT INTO tbl_classes(cl_code,
                                    cl_sucode,
                                    cl_room,
                                    cl_stime,
                                    cl_etime,
                                    cl_day,
                                    cl_block,
                                    cl_facultyid,
                                    cl_schoolyear,
                                    cl_sem,
                                    cl_isnormal)
                                    VALUES('$a','$b','$c','$d','$e','$f','$g','$h','$i','$j','normal')";
                                    
                                    $this->conn->query($query);
            
                                }
                                
                                return $this->info = array(
                                    'status'=>array(
                                        'remarks'=>true,
                                        'message'=>'Uploading success.'
                                    ),
                                    'data' =>$this->data,
                                    'timestamp'=>date_create(),
                                    'prepared_by'=>'F-Society'
                                );
            
                            }else{
                                return $this->info = array('status'=>array(
                                    'remarks'=>false,
                                    'message'=>'Uploading failed.'),
                                'timestamp'=>date_create(),
                                'prepared_by'=>'F-Society' );
                            }
            
                        }else{
                            return $this->info = array('status'=>array(
                                'remarks'=>false,
                                'message'=>'Invalid file for uploading faculty.'),
                            'timestamp'=>date_create(),
                            'prepared_by'=>'F-Society' );
                        }
                        
                        
                    }else{
                        return $this->info = array('status'=>array(
                            'remarks'=>false,
                            'message'=>'No file uploaded.'),
                        'timestamp'=>date_create(),
                        'prepared_by'=>'F-Society' );
                    }
            
                }

                function delClass($d) {
                    return $this->executeWithoutRes("DELETE from tbl_classes WHERE cl_recno='$d->cl_recno'");
                }

                // options in select in admin/classes
                function getSchoolYear() {
                    return $this->executeWithRes("SELECT DISTINCT cl_schoolyear from tbl_classes GROUP BY cl_schoolyear");                    
                }

                function getSem() {
                    return $this->executeWithRes("SELECT DISTINCT cl_sem from tbl_classes GROUP BY cl_sem");                    
                }

                function getBlocks($d) {
                    return $this->executeWithRes("SELECT DISTINCT cl_block from tbl_classes WHERE cl_sem = '$d->sem' and cl_schoolyear='$d->SY' GROUP BY cl_block"); 
                }


        // students/schedule
        function getStudentSchedule($d){
            return $this->executeWithRes("SELECT * from tbl_classes LEFT JOIN tbl_enrolledsubjects ON tbl_classes.cl_code = tbl_enrolledsubjects.es_clcode 
            INNER JOIN tbl_studentinfo ON tbl_enrolledsubjects.es_idnumber = tbl_studentinfo.si_idnumber 
            INNER JOIN tbl_faculty ON tbl_classes.cl_facultyid = tbl_faculty.fa_empnumber INNER JOIN tbl_subjects ON tbl_classes.cl_sucode = tbl_subjects.su_code 
            WHERE tbl_enrolledsubjects.es_idnumber=$d->si_idnumber group by es_clcode");
        }

        // students/prospectus
        function getProspectusCopy($d){
            return $this->executeWithRes("SELECT * FROM tbl_subjects WHERE su_course='$d->si_course' ORDER BY su_yrlevel ASC, su_sem ASC");
        }
        function getProspectusCopyF($d){
            return $this->executeWithRes("SELECT * FROM tbl_subjects LEFT JOIN tbl_studentinfo ON tbl_subjects.su_cy = tbl_studentinfo.si_cy WHERE tbl_subjects.su_course='$d->si_course' AND tbl_subjects.su_yrlevel=$d->year AND tbl_subjects.su_sem=$d->sem AND tbl_studentinfo.si_idnumber='$d->si_idnumber'");
        }
        
        // students/grades
        function getGrades($d){
            return $this->executeWithRes("SELECT * FROM tbl_enrolledsubjects WHERE es_idnumber = $d->si_idnumber");
        }

        // students/profile
        function updateStudentProfile($d){
            return $this->executeWithRes("UPDATE tbl_studentinfo SET si_email = $d->$email, si_address = $d->$address WHERE si_idnumber=$d->$si_idnumber");
        }

        function getCourses($d){
            if($d->dept!=''){
                return $this->executeWithRes("SELECT * FROM tbl_courses WHERE co_dept = '$d->dept'");
            } else{
                return $this->executeWithRes("SELECT * FROM tbl_courses");
            }
            
        }

        function uploadImageStudent(){
            if(isset($_FILES['file'])){

                $studId = $_POST['studId'];
                $file_name = $_FILES['file']['name'];
                $file_explodedname = explode('.', $file_name);
                $file_ext = strtolower(end($file_explodedname) );
                $newfile_name = $studId . '.' . $file_ext;
                $target_dir = "../imagesFP/".$newfile_name;

                $extensions = array("jpeg", "jpg", "png");

                if(in_array($file_ext,$extensions)){ // check if file is valid

                    if(move_uploaded_file($_FILES['file']['tmp_name'], $target_dir)){
                        $query = "UPDATE tbl_studentinfo SET si_picture = 'http://localhost/imagesFP/$newfile_name' WHERE si_idnumber=$studId";
                        $this->executeWithoutRes($query);
                        
                        return $this->info = array(
                            'status'=>array(
                                'remarks'=>true,
                                'message'=>'Uploading success.'
                            ),
                            'data' =>$this->data,
                            'timestamp'=>date_create(),
                            'prepared_by'=>'F-Society'
                        );

                    }else{
                        return $this->info = array('status'=>array(
                            'remarks'=>false,
                            'message'=>'Uploading failed.'),
                        'timestamp'=>date_create(),
                        'prepared_by'=>'F-Society' );
                    }

                }else{
                    return $this->info = array('status'=>array(
                        'remarks'=>false,
                        'message'=>'Invalid file for uploading grades.'),
                    'timestamp'=>date_create(),
                    'prepared_by'=>'F-Society' );
                }
                
                
            }else{
                return $this->info = array('status'=>array(
                    'remarks'=>false,
                    'message'=>'No file uploaded.'),
                'timestamp'=>date_create(),
                'prepared_by'=>'F-Society' );
            }

        }

        // faculty
        function myclass($d){
            return $this->executeWithRes("SELECT * from tbl_classes WHERE cl_facultyid=$d->fa_empnumber");
        }

        function myclassf($d){
            return $this->executeWithRes("SELECT * from tbl_classes WHERE cl_facultyid='".$d->data[0]->fa_empnumber."' and cl_isnormal=$d->type");
        }

        function classFiles($d){
            return $this->executeWithRes("SELECT * from tbl_files WHERE fi_clcode=$d->classId");
        }

        function students($d){
            return $this->executeWithRes("SELECT si_idnumber,CONCAT(si_lastname,', ',si_firstname,' ',si_extname,', ',si_midname) as si_fullname,si_department,si_course,si_block,si_yrlevel,si_sem from tbl_studentinfo WHERE si_department='$d->department' ORDER BY si_lastname,si_firstname,si_midname,si_extname DESC");
        }

        function students1($d){
            return $this->executeWithRes("SELECT si_idnumber,CONCAT(si_lastname,', ',si_firstname,' ',si_extname,', ',si_midname) as si_fullname, si_block, si_course, si_department from tbl_studentinfo WHERE (si_idnumber LIKE '%$d->searchClass%' or si_lastname LIKE '%$d->searchClass%' or si_firstname LIKE '%$d->searchClass%' or si_midname LIKE '%$d->searchClass%' or si_block LIKE '%$d->searchClass%' or si_department LIKE '%$d->searchClass%' or si_course LIKE '%$d->searchClass%') ORDER BY si_idnumber ASC ");
        }

        function students2($d){
            return $this->executeWithRes("SELECT si_idnumber,CONCAT(si_lastname,', ',si_firstname,' ',si_extname,', ',si_midname) as si_fullname,si_department,si_course,si_block,si_yrlevel,si_sem from tbl_studentinfo WHERE si_course='$d->program' ORDER BY si_lastname,si_firstname,si_midname,si_extname DESC");
        }

        function getClassStudents($d){
            return $this->executeWithRes("SELECT si.si_idnumber,CONCAT(si.si_lastname,', ',si.si_firstname,' ',si.si_midname,' ',si.si_extname) as si_fullname,es.es_mgrade from tbl_studentinfo as si INNER JOIN tbl_enrolledsubjects as es on es.es_idnumber = si.si_idnumber INNER JOIN tbl_classes as cl on cl.cl_code = es.es_clcode WHERE cl.cl_code=$d->classId ORDER BY si.si_lastname ASC");
        }


        // faculty/profiley
            function getStudent($d) {
                return $this->executeWithRes("SELECT * from tbl_studentinfo WHERE si_idnumber = '$d->idNumber'");
            }

            function getStudents($d) {
                return $this->executeWithRes("SELECT * from tbl_studentinfo");
            }

            function getActiveClasses($d) {
                return $this->executeWithRes("SELECT * from tbl_classes as cl JOIN tbl_subjects as su on su.su_code = cl.cl_sucode WHERE cl.cl_schoolyear = '$d->actSY' and cl.cl_sem = '$d->actSem' and (cl.cl_code LIKE '%$d->searchClass%' or su.su_description LIKE '%$d->searchClass%' or cl.cl_room LIKE '%$d->searchClass%' or su.su_code LIKE '%$d->searchClass%' or cl.cl_block LIKE '%$d->searchClass%') ORDER BY cl.cl_block,su.su_code,cl.cl_day,cl.cl_stime,cl.cl_etime ASC LIMIT 25");
            }

            function getSettings($d) {
                return $this->executeWithRes("SELECT * from tbl_enlistment WHERE en_isactive = 'ACTIVE'");
            }

            function getEnrolledClasses($d) {

               

                return $this->executeWithRes("SELECT * 
      FROM tbl_enrolledsubjects es 
      INNER JOIN tbl_classes cl
      on es.es_clcode = cl.cl_code and es.es_sucode = cl.cl_sucode
      INNER JOIN tbl_subjects su
      on es.es_sucode = su.su_code
      WHERE cl.cl_schoolyear = '$d->actSY' and es.es_idnumber = '$d->idNumber'
      GROUP BY es.es_sucode  ORDER BY cl.cl_block,su.su_code,cl.cl_day,cl.cl_stime,cl.cl_etime ASC");
            }

            function enrollSingleClass($d) {

                $this->result = $this->conn->query("SELECT * FROM tbl_enrolledsubjects WHERE es_clcode = '$d->classCode' and es_idnumber = '$d->idNumber' and es_sucode = '$d->subjectCode' and es_block='$d->block'");
    
                if ($this->result->num_rows>0) {
                  
                    return $this->info = array(
                        'status'=>array(
                            'remarks'=>false,
                            'message'=>'Subject already enrolled.'
                        ),
                        'data' =>$this->data,
                        'timestamp'=>date_create(),
                        'prepared_by'=>'F-Society'
                    );
        
                } else {
                    return $this->executeWithoutRes("INSERT INTO tbl_enrolledsubjects(es_idnumber,es_clcode,es_sucode,es_block, es_added) VALUES('$d->idNumber','$d->classCode','$d->subjectCode','$d->block', '1')");
                }
            }

            function removeEnrolledSubject($d) {
                return $this->executeWithoutRes("DELETE FROM tbl_enrolledsubjects WHERE es_recno='$d->recno'");
            }

            function getCourseBlocks($d) {
                return $this->executeWithRes("SELECT DISTINCT cl_block from tbl_classes WHERE cl_block LIKE '%$d->si_course%'");
            }

            function getDept($d) {
                return $this->executeWithRes("SELECT co_dept from tbl_courses GROUP BY co_dept");
            }


            function enrollByBlock($d) {
                $this->executeWithoutRes("DELETE FROM tbl_enrolledsubjects WHERE es_idnumber = '$d->si_idnumber' and es_added = '0'");
                return $this->executeWithoutRes("INSERT INTO tbl_enrolledsubjects(es_idnumber,es_clcode,es_sucode,es_block) SELECT '$d->si_idnumber',cl_code,cl_sucode,cl_block from tbl_classes WHERE cl_block = '$d->blockSelected'");
            }

            function insertNewStudent($d){
                $lname = $d->lname;
                $fname = $d->fname;
                $mname = $d->mname;
                $extname = $d->nameext;
                $address = $d->fulladdress;
                $zipcode = $d->addresszip;
                $gender = $d->gender;
                $bday = $d->dob;
                $email = $d->email;
                $contact = $d->mobile;
                $entrancescore = '';
                $course = $d->course;
                $course2 = '';
                if(isset($d->course2)){
                    $course2 = $d->course2;
                }
                $course3 = '';
                if(isset($d->course3)){
                    $course3 = $d->course3;
                }
                $religion = '';
                if(isset($d->religion)){
                    $religion = $d->religion;
                }
                $reason = $d->reasoncourse;
                if($reason == 'Other'){
                    if(isset($d->courseother)){
                        $reason = $d->courseother;
                    }
                }
                $reasongc = $d->reasonschool;
                if($reasongc == 'Other'){
                    if(isset($d->schoolother)){
                        $reasongc = $d->schoolother;
                    }
                }
                $scholarship = $d->scholar;
                $scholartype = $d->scholartype;
                $sponsor = '';
                if(isset($d->sponsor)){
                    $sponsor = $d->sponsor;
                }
                $sponsoroccupation = $d->sponsoroccupation;
                if(isset($d->sponsoroccupation)){
                    $sponsoroccupation = $d->sponsoroccupation;
                }
                $transferee = $d->transferee;
                $transferschool = $d->transfercourselevel;
                $highschool = $d->highschool;
                $highschoolgpa = $d->highschoolgpa;
                $honors = '';
                if(isset($d->honors)){
                    $honors = $d->honors;
                }
                $orgs = '';
                if(isset($d->orgs)){
                    $orgs = $d->orgs;
                }
                $interest = '';
                $interests = '';
                if(isset($d->interests)){
                    $interest = $d->interests;
                    if($interest==''){
                        $interests = '';
                    } else{
                        $interests = implode(", ", $interest);
                    }
                }
                if(isset($d->interestother)){
                    $interests = $interests.', '.$d->interestother;
                }
                $talent =  '';
                $talents = '';
                if(isset($d->talents)){
                    $talent =  $d->talents;
                    if($talent==''){
                        $talents = '';
                    } else{
                        $talents = implode(", ", $talent);
                    }
                }
                if(isset($d->talentsother)){
                        $talents = $talents.', '.$d->talentsother;
                }
                $device = '';
                $devices = '';
                if(isset($d->device)){
                        $device = $d->device;
                        $devices = implode(",", $device);
                }
                $department = $d->department;
                $siblings = $d->siblings;
                $mother = $d->mother;
                $motheroccupation = $d->motheroccupation;
                $momcontact = $d->mothercontact;
                $father = $d->father;
                $fatheroccupation = $d->fatheroccupation;
                $dadcontact = $d->fathercontact;
                $emergencynumber = $d->emergencynumber;
                $yearenrolled = $d->schoolyear;
                $regular = $d->regular;
                $sem = $d->sem;
                $sports = '';
                $sport = '';
                if(isset($d->sport)){
                    $sports = $d->sport;
                    if($sports==''){
                        $sport = '';
                    } else{
                        $sport = implode(", ", $sports);
                    }
                }
                if(isset($d->sportother)){
                    $sport = $sport.', '.$d->sportother;
                }
                $lrn = '';
                if(isset($d->lrn)){
                    $lrn = $d->lrn;
                }

                $strand = '';
                if(isset($d->strand)){
                    $strand = $d->strand;
                }
                $competitions = '';
                if(isset($d->competitions)){
                    $competitions = $d->competitions;
                }
                $spouse='';
                if(isset($d->spouse)){
                    $spouse = $d->spouse;
                }
                $spousecontact='';
                if(isset($d->spousecontact)){
                    $spousecontact = $d->spousecontact;
                }
                $guardname = $d->guardian;
                $guardrel = $d->relationship;
                $guardadd = $d->guardianadd;
                $govprojs = '';
                $govproj = '';
                if(isset($d->govproj)){
                    $govprojs = $d->govproj;
                    if($govprojs==''){
                        $govproj = '';
                    } else{
                        $govproj = implode(", ", $govprojs);
                    }
                }
                $govprojother='';
                if(isset($d->govprojother)){
                    $govprojother = $d->govprojother;
                }
                $famincome = $d->famincome;
                $disabled = $d->disabled;
                $disability ='';
                if(isset($d->disability)){
                    $disability = $d->disability;
                }
                $counten=0;

                $household='';
                if(isset($d->household)){
                    $household = $d->household;
                }
                $pob = $d->pob;
                $civilstatus = $d->civilstatus;
                $age = $d->age;
                $year = $d->year;
            
                // $insertNewStudent = "INSERT INTO tbl_studentinfo (si_lastname, si_firstname, si_midname, si_extname, si_address,  si_gender, si_bday, si_email, si_mobile, si_course, si_coursechoice, si_coursechoice2, si_reason, si_siblings, si_momname, si_dadname, si_emergencycontact, si_device, si_entranceexam, si_lastschool, si_average, si_istransferee, si_transfercourselevel, si_reasonstudy, si_scholartype, si_support, si_supportoccupation, si_specialaward, si_organization, si_competition, si_interest, si_talent,si_schoolyear,si_isregular, si_yrlevel, si_sem, si_enrolledyear, si_isenrolled, si_isenlisted, si_sport, si_momoccupation, si_dadoccupation, si_lrn, si_strand, si_guardname, si_guardrel, si_guardadd , si_govproj, si_govprojothers, si_famincome, si_isdisabled, si_disability, si_householdno, si_zipcode, si_momcontact, si_dadcontact, si_spouse, si_spousecontact, si_department, si_pob, si_civilstatus, si_age, si_studenttype) 
                // VALUES ('$lname', '$fname', '$mname', '$extname', '$address',  '$gender', '$bday', '$email', '$contact', '$course', '$course2', '$course3', '$reason', '$siblings', '$mother', '$father', '$emergencynumber', '$devices', '$entrancescore', '$highschool', '$highschoolgpa', '$transferee', '$transferschool', '$reasongc', '$scholartype', '$sponsor', '$sponsoroccupation', '$honors', '$orgs', '$competitions', '$interests', '$talents', '$yearenrolled', '$regular', 1,'$sem','$yearenrolled',0, 1, '$sport', '$motheroccupation', '$fatheroccupation', '$lrn', '$strand', '$guardname', '$guardrel', '$guardadd', '$govproj','$govprojother','$famincome','$disabled','$disability', '$household', '$zipcode', '$momcontact', '$dadcontact', '$spouse', '$spousecontact', '$department', '$pob', '$civilstatus', '$age', 'old')";


                $insertNewStudent = "INSERT INTO tbl_studentinfo (si_lastname, si_firstname, si_midname, si_extname, si_address,  si_gender, si_bday, si_email, si_mobile, si_course, si_coursechoice, si_coursechoice2, si_reason, si_siblings, si_momname, si_dadname, si_emergencycontact, si_device, si_entranceexam, si_lastschool, si_average, si_istransferee, si_transfercourselevel, si_reasonstudy, si_scholartype, si_support, si_supportoccupation, si_specialaward, si_organization, si_competition, si_interest, si_talent,si_schoolyear,si_isregular, si_yrlevel, si_sem, si_enrolledyear, si_isenrolled, si_isenlisted, si_sport, si_momoccupation, si_dadoccupation, si_lrn, si_strand, si_guardname, si_guardrel, si_guardadd , si_govproj, si_govprojothers, si_famincome, si_isdisabled, si_disability, si_householdno, si_zipcode, si_momcontact, si_dadcontact, si_spouse, si_spousecontact, si_department, si_pob, si_civilstatus, si_age, si_studenttype, si_religion) 
                VALUES ('$lname', '$fname', '$mname', '$extname', '$address',  '$gender', '$bday', '$email', '$contact', '$course', '$course2', '$course3', '$reason', '$siblings', '$mother', '$father', '$emergencynumber', '$devices', '$entrancescore', '$highschool', '$highschoolgpa', '$transferee', '$transferschool', '$reasongc', '$scholartype', '$sponsor', '$sponsoroccupation', '$honors', '$orgs', '$competitions', '$interests', '$talents', '$yearenrolled', '$regular', '$year','$sem','$yearenrolled',0, 1, '$sport', '$motheroccupation', '$fatheroccupation', '$lrn', '$strand', '$guardname', '$guardrel', '$guardadd', '$govproj','$govprojother','$famincome','$disabled','$disability', '$household', '$zipcode', '$momcontact', '$dadcontact', '$spouse', '$spousecontact', '$department', '$pob', '$civilstatus', '$age', 'old', '$religion')";

                if($this->conn->query($insertNewStudent)){
                    $insertid = $this->conn->insert_id;
			        $tempid = $insertid + 190000;
                    $sqlid = "UPDATE tbl_studentinfo set si_idnumber = '$d->idnumber' where si_recno = '$insertid'";
                    if($this->conn->query($sqlid)){
                        $valid[0]='success';
                        $valid[1]='Registration Successful';
                        $valid[2]='Please proceed to the enlistment admin for your printed form.';
                        return $valid;
                    } else{
                        return $this->conn->error;
                    }
                }else{
                    return $this->conn->error;
                }
                

            }



            function updateStudentInfo($d){
                $lname = $d->lname;
                $fname = $d->fname;
                $mname = $d->mname;
                $extname = $d->nameext;
                $address = $d->fulladdress;
                $zipcode = $d->addresszip;
                $gender = $d->gender;
                $bday = $d->dob;
                $email = $d->email;
                $contact = $d->mobile;
                $entrancescore = '';
                $course = $d->course;
                $course2 = '';
                $religion = '';
                if(isset($d->religion)){
                    $religion = $d->religion;
                }
                if(isset($d->course2)){
                    $course2 = $d->course2;
                }
                $course3 = '';
                if(isset($d->course3)){
                    $course3 = $d->course3;
                }
                $reason = $d->reasoncourse;
                if($reason == 'Other'){
                    if(isset($d->courseother)){
                        $reason = $d->courseother;
                    }
                }
                $reasongc = $d->reasonschool;
                if($reasongc == 'Other'){
                    if(isset($d->schoolother)){
                        $reasongc = $d->schoolother;
                    }
                }
                $scholarship = $d->scholar;
                $scholartype = $d->scholartype;
                $sponsor = '';
                if(isset($d->sponsor)){
                    $sponsor = $d->sponsor;
                }
                $sponsoroccupation = $d->sponsoroccupation;
                if(isset($d->sponsoroccupation)){
                    $sponsoroccupation = $d->sponsoroccupation;
                }
                $transferee = $d->transferee;
                $transferschool = $d->transfercourselevel;
                $highschool = $d->highschool;
                $highschoolgpa = $d->highschoolgpa;
                $honors = '';
                if(isset($d->honors)){
                    $honors = $d->honors;
                }
                $orgs = '';
                if(isset($d->orgs)){
                    $orgs = $d->orgs;
                }
                $interest = '';
                $interests = '';
                if(isset($d->interests)){
                    $interest = $d->interests;
                    if($interest==''){
                        $interests = '';
                    } else{
                        $interests = implode(", ", $interest);
                    }
                }
                if(isset($d->interestother)){
                    $interests = $interests.', '.$d->interestother;
                }
                $talent =  '';
                $talents = '';
                if(isset($d->talents)){
                    $talent =  $d->talents;
                    if($talent==''){
                        $talents = '';
                    } else{
                        $talents = implode(", ", $talent);
                    }
                }
                if(isset($d->talentsother)){
                        $talents = $talents.', '.$d->talentsother;
                }
                $device = '';
                $devices = '';
                if(isset($d->device)){
                        $device = $d->device;
                        $devices = implode(",", $device);
                }
                $department = $d->department;
                $siblings = $d->siblings;
                $mother = $d->mother;
                $motheroccupation = $d->motheroccupation;
                $momcontact = $d->mothercontact;
                $father = $d->father;
                $fatheroccupation = $d->fatheroccupation;
                $dadcontact = $d->fathercontact;
                $emergencynumber = $d->emergencynumber;
                $yearenrolled = $d->schoolyear;
                $regular = $d->regular;
                $sem = $d->sem;
                $sports = '';
                $sport = '';
                if(isset($d->sport)){
                    $sports = $d->sport;
                    if($sports==''){
                        $sport = '';
                    } else{
                        $sport = implode(", ", $sports);
                    }
                }
                if(isset($d->sportother)){
                    $sport = $sport.', '.$d->sportother;
                }
                $lrn = '';
                if(isset($d->lrn)){
                    $lrn = $d->lrn;
                }

                $strand = '';
                if(isset($d->strand)){
                    $strand = $d->strand;
                }
                $competitions = '';
                if(isset($d->competitions)){
                    $competitions = $d->competitions;
                }
                $spouse='';
                if(isset($d->spouse)){
                    $spouse = $d->spouse;
                }
                $spousecontact='';
                if(isset($d->spousecontact)){
                    $spousecontact = $d->spousecontact;
                }
                $guardname = $d->guardian;
                $guardrel = $d->relationship;
                $guardadd = $d->guardianadd;
                $govprojs = '';
                $govproj = '';
                if(isset($d->govproj)){
                    $govprojs = $d->govproj;
                    if($govprojs==''){
                        $govproj = '';
                    } else{
                        $govproj = implode(", ", $govprojs);
                    }
                }
                $govprojother='';
                if(isset($d->govprojother)){
                    $govprojother = $d->govprojother;
                }
                $famincome = $d->famincome;
                $disabled = $d->disabled;
                $disability ='';
                if(isset($d->disability)){
                    $disability = $d->disability;
                }
                $counten=0;

                $household='';
                if(isset($d->household)){
                    $household = $d->household;
                }
                $pob = $d->pob;
                $civilstatus = $d->civilstatus;
                $age = $d->age;
                $year = $d->year;

                $verify = "SELECT * FROM tbl_studentinfo WHERE si_idnumber='$d->idnumber'";
                $updateStudent = "UPDATE tbl_studentinfo SET si_lastname = '$lname', si_firstname = '$fname', si_midname = '$mname', si_extname = '$extname', si_address = '$address',  si_gender = '$gender', si_bday = '$bday', si_email = '$email', si_mobile = '$contact', si_course = '$course', si_coursechoice = '$course2', si_coursechoice2 = '$course3', si_reason = '$reason', si_siblings = '$siblings', si_momname = '$mother', si_dadname = '$father', si_emergencycontact = '$emergencynumber', si_device = '$devices', si_entranceexam = '$entrancescore', si_lastschool = '$highschool', si_average = '$highschoolgpa', si_istransferee = '$transferee', si_transfercourselevel = '$transferschool', si_reasonstudy = '$reasongc', si_scholartype = '$scholartype', si_support = '$sponsor', si_supportoccupation = '$sponsoroccupation', si_specialaward = '$honors', si_organization = '$orgs', si_competition = '$competitions', si_interest = '$interests', si_talent = '$talents',si_schoolyear = '$yearenrolled',si_isregular = '$regular', si_yrlevel = '$year', si_sem = '$sem', si_enrolledyear = '$yearenrolled', si_isenrolled = 0, si_isenlisted = 1, si_sport = '$sport', si_momoccupation = '$motheroccupation', si_dadoccupation = '$fatheroccupation', si_lrn = '$lrn', si_strand = '$strand', si_guardname = '$guardname', si_guardrel = '$guardrel', si_guardadd = '$guardadd', si_govproj = '$govproj', si_govprojothers = '$govprojother', si_famincome = '$famincome', si_isdisabled = '$disabled', si_disability = '$disability', si_householdno = '$household', si_zipcode = '$zipcode', si_momcontact = '$momcontact', si_dadcontact = '$dadcontact', si_spouse = '$spouse', si_spousecontact = '$spousecontact', si_department = '$department', si_pob = '$pob', si_civilstatus = '$civilstatus', si_age = '$age', si_studenttype = 'old', si_religion = '$religion' WHERE si_idnumber = '$d->idnumber'";
                $verification = $this->conn->query($verify);
                $verified = $verification->num_rows;
                if($verified!=0){
                    if($this->conn->query($updateStudent)){
                        $valid[0]='success';
                        $valid[1]='Successfully Updated';
                        $valid[2]='Please proceed to the enlistment admin for your printed form.';
                        return $valid;
                    }else{
                        $valid[0]='error';
                        $valid[1]=$this->conn->error;
                        $valid[2]=$this->conn->error;
                        return $valid;
                    }    
                } else{
                        $valid[0]='error';
                        $valid[1]='ID NUMBER NOT YET REGISTERED';
                        $valid[2]='Please select New Student before filling up the form.';
                        return $valid;
                }
            
            }

            function reenlist($d){
                $idnumber = $d->idnumber;
                $year = $d->year;
                $sem = $d->sem;
                $regular = $d->regular;
                $schoolyear = $d->schoolyear;
                $mobile = $d->mobile;
                $email = $d->email;

                $reenlistStudent = "UPDATE tbl_studentinfo SET si_yrlevel = '$year', si_sem = '$sem', si_isregular = '$regular', si_schoolyear = '$schoolyear', si_mobile = '$mobile', si_email = '$email', si_isenlisted = 1, si_isenrolled = 0 WHERE si_idnumber = '$idnumber'";
                if($this->conn->query($reenlistStudent)){
                    $valid[0]='success';
                    return $valid;
                }else{
                    return $this->conn->error;
                }
            }







            function updateId($d){
                $oldid = $d->idNumber;
                $newid = $d->newId;

                $updateID = "UPDATE tbl_studentinfo SET si_idnumber = '$newid' WHERE si_idnumber = '$oldid'";
                if($this->conn->query($updateID)){
                    $valid[0]='success';
                    $valid[1]='Successfully Updated';
                    $valid[2]='';
                    return $valid;
            }else{
                $valid[0]='error';
                $valid[1]=$this->conn->error;
                $valid[2]=$this->conn->error;
                return $valid;
            }                
            }




















        // all push method
        // function addfile(){

        //     if(isset($_FILES['file'])){

        //         $classId = $_POST['classId'];

        //         $errors= array();
        //         $target_dir = "../filesFP/".$classId;
        //         $file_name = $_FILES['file']['name'];
        //         $file_tmp =$_FILES['file']['tmp_name'];
        //         $file_size =$_FILES['file']['size'];

        //         if (!file_exists($target_dir)) {
        //             mkdir($target_dir);
        //         }

        //         if(move_uploaded_file($file_tmp,"../filesFP/".$_POST['classId']."/".$file_name)){
        //             return $this->executePushing("INSERT INTO tbl_files(fi_name,fi_path,fi_clcode) VALUES('$file_name','http://localhost/filesFP/$classId/$file_name','$classId')");
        //         }else{
        //             return $this->info = array('status'=>array(
        //                 'remarks'=>false,
        //                 'message'=>'Uploading failed.'),
        //             'timestamp'=>date_create(),
        //             'prepared_by'=>'F-Society  ' );
        //         }
                
        //     }else{
        //         return $this->info = array('status'=>array(
        //             'remarks'=>false,
        //             'message'=>'No file uploaded.'),
        //         'timestamp'=>date_create(),
        //         'prepared_by'=>'F-Society' );
        //     }
            

        // }

        function uploadGrade(){
            if(isset($_FILES['file'])){

                $classId = $_POST['classId'];
                $file_name = $_FILES['file']['name'];
                $target_dir = "../filesFP/".$file_name;
                $file_explodedname = explode('.', $file_name);
                $file_ext = strtolower(end($file_explodedname) );

                $extensions = array("xlsx");

                if(in_array($file_ext,$extensions)){ // check if file is excel

                    if(move_uploaded_file($_FILES['file']['tmp_name'], $target_dir)){

                        require_once "Classes/PHPExcel.php";
                        
                        $excelReader = PHPExcel_IOFactory::createReaderForFile($target_dir);
                        $excelObj = $excelReader->load($target_dir);
                        $worksheet = $excelObj->getSheet(0);
                        $lastRow = $worksheet->getHighestRow();

                        for ($row = 4; $row <= $lastRow; $row++) {

                            $idnumber = $worksheet->getCell('B'.$row)->getValue();
                            $grade = $worksheet->getCell('D'.$row)->getValue();

                            if(is_numeric($idnumber)){
                                $query = "UPDATE tbl_enrolledsubjects SET es_mgrade = $grade WHERE es_idnumber=$idnumber and es_clcode = $classId";
                                $this->executeWithoutRes($query);
                            }

                        }
                        
                        return $this->info = array(
                            'status'=>array(
                                'remarks'=>true,
                                'message'=>'Uploading success.'
                            ),
                            'data' =>$this->data,
                            'timestamp'=>date_create(),
                            'prepared_by'=>'F-Society'
                        );

                    }else{
                        return $this->info = array('status'=>array(
                            'remarks'=>false,
                            'message'=>'Uploading failed.'),
                        'timestamp'=>date_create(),
                        'prepared_by'=>'F-Society' );
                    }

                }else{
                    return $this->info = array('status'=>array(
                        'remarks'=>false,
                        'message'=>'Invalid file for uploading grades.'),
                    'timestamp'=>date_create(),
                    'prepared_by'=>'F-Society' );
                }
                
                
            }else{
                return $this->info = array('status'=>array(
                    'remarks'=>false,
                    'message'=>'No file uploaded.'),
                'timestamp'=>date_create(),
                'prepared_by'=>'F-Society' );
            }

        }



        // code for execution of sql queries
        function executeWithRes($query){

            $this->result = $this->conn->query($query);
    
            if ($this->result->num_rows>0) {
                while($res = $this->result->fetch_assoc()){
                    array_push($this->data,$res);
                }
              
                return $this->info = array(
                    'status'=>array(
                        'remarks'=>true,
                        'message'=>'Query with data success.'
                    ),
                    'data' =>$this->data,
                    'timestamp'=>date_create(),
                    'prepared_by'=>'F-Society'
                );
    
            } else {
                return $this->info = array('status'=>array(
                        'remarks'=>false,
                        'message'=>'No data pulled.'),
                    'timestamp'=>date_create(),
                    'prepared_by'=>'F-Society' );
            }

        }

        function executeWithoutRes($query){
            if ($this->conn->query($query)){
                return $this->info = array(
                    'status'=>array(
                        'remarks'=>true,
                        'message'=>'Query without data success.'
                    ),
                    'data' =>$this->data,
                    'timestamp'=>date_create(),
                    'prepared_by'=>'F-Society'
                );
            } else {
                return $this->info = array('status'=>array(
                        'remarks'=>false,
                        'message'=>'Data adding failed.'),
                    'timestamp'=>date_create(),
                    'prepared_by'=>'F-Society' );
            }
        }
    }
?>