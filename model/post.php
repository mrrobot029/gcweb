<?php
        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\SMTP;
        use PHPMailer\PHPMailer\Exception;
        require 'mail/Exception.php';
        require 'mail/PHPMailer.php';
        require 'mail/SMTP.php';
        date_default_timezone_set('Asia/Manila');
    class Post{
        private $conn;
        private $sql;
        private $result;
        private $data = array();
        private $info = [];
        public function __construct($db){
            $this->conn = $db;
        }
        // NOTE: This is all the functions for admin/facultymembers (page) 
                function getFaculty($d){
                    return $this->executeWithRes("SELECT * from tbl_faculty WHERE fa_department='".$d->data[0]->fa_department."' ORDER BY fa_lname,fa_fname,fa_mname,fa_extname ASC");
                }

                function delFaculty($d){
                    return $this->executeWithoutRes("DELETE from tbl_faculty WHERE fa_empnumber='$d->empNo'");
                }

                function addFaculty($d){
                    return $this->executeWithoutRes("DELETE from tbl_faculty WHERE fa_empnumber='$d->empNo'");
                }



        // NOTE: This is all the functions for admin/subjectprospectus (page)
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

                        // admin/subjectprospectus (filters)
                        function getProspectusCourse($d){
                            return $this->executeWithRes("SELECT DISTINCT co_name from tbl_courses WHERE co_dept = '$d->deptName'");
                        }
                        
                        function getProspectusCy($d){
                            return $this->executeWithRes("SELECT DISTINCT su_cy from tbl_subjects WHERE su_course = '$d->courseName'");
                        }



                // NOTE: This is all the functions for admin/classes (page)
                function getClass($d) {
                    return $this->executeWithRes("SELECT DISTINCT * from tbl_classes as cl INNER JOIN tbl_subjects as su on su.su_code = cl.cl_sucode WHERE (cl.cl_sem = '$d->sem' and cl.cl_schoolyear='$d->SY') and cl.cl_block LIKE '%$d->block%' GROUP BY cl.cl_code");                    
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
                                    VALUES('$a','$b','$c','$d','$e','$f','$g','$h','$i','$j','1')";
                                    
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

                function addClass($d) {
                    return $this->executeWithoutRes("INSERT INTO tbl_classes(cl_code,cl_sucode,cl_room,cl_stime,cl_etime,cl_day,cl_block,cl_facultyid,cl_schoolyear,cl_sem,cl_isnormal)VALUES('$d->clCode','$d->suCode','$d->clRoom','$d->stTime','$d->enTime','$d->clDay','$d->clBlock','$d->clFac','$d->clSY','$d->clSem','1')");
                }

                function delClass($d) {
                    return $this->executeWithoutRes("DELETE from tbl_classes WHERE cl_recno='$d->cl_recno'");
                }

                        // admin/classes (filters)
                        function getSchoolYear() {
                            return $this->executeWithRes("SELECT DISTINCT cl_schoolyear from tbl_classes GROUP BY cl_schoolyear");                    
                        }

                        function getSem() {
                            return $this->executeWithRes("SELECT DISTINCT cl_sem from tbl_classes GROUP BY cl_sem");                    
                        }

                        function getBlocks($d) {
                            return $this->executeWithRes("SELECT DISTINCT cl_block FROM tbl_classes as cl Inner JOIN tbl_courses as co ON cl.cl_block LIKE CONCAT('%', co.co_name , '%') WHERE co.co_dept = '$d->department' AND cl.cl_sem = '$d->sem' AND cl.cl_schoolyear='$d->SY' ORDER BY cl.cl_block"); 
                        }




        // all function for faculty side of the system


        // NOTE: This is all the functions for faculty/myclasses (page)
        function getMyClass($d){
            return $this->executeWithRes("SELECT * from tbl_classes WHERE cl_facultyid='". $d->data[0]->fa_empnumber ."' and cl_schoolyear='$d->clSY' and cl_sem = '$d->clSem'");
        }

        function updateIsNormal($d){
            return $this->executeWithoutRes("UPDATE tbl_classes SET cl_isnormal='$d->b' WHERE cl_code='$d->a'");
        }

        function getClassStudents($d){
            return $this->executeWithRes("SELECT *,CONCAT(si.si_lastname,', ',si.si_firstname,' ',si.si_midname,' ',si.si_extname) as si_fullname from tbl_studentinfo as si INNER JOIN tbl_enrolledsubjects as es on es.es_idnumber = si.si_idnumber INNER JOIN tbl_classes as cl on cl.cl_code = es.es_clcode WHERE cl.cl_code=$d->classId ORDER BY si.si_lastname ASC");
        }

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

        // NOTE: This is all the functions for faculty/mystudents (page)
        function getFacStudents($d){
            return $this->executeWithRes("SELECT DISTINCT *, CONCAT(stud.si_lastname,', ',stud.si_firstname,' ',stud.si_midname,' ',stud.si_extname)  as si_fullname from tbl_studentinfo as stud INNER JOIN tbl_enrolledsubjects as es on stud.si_idnumber = es.es_idnumber INNER JOIN tbl_classes as cl on es.es_clcode = cl.cl_code WHERE cl.cl_facultyid='$d->facId' GROUP BY stud.si_idnumber  ORDER BY stud.si_lastname ASC");
        }

        function getAdminStudents($d){
            return $this->executeWithRes("SELECT si_idnumber,CONCAT(si_lastname,', ',si_firstname,' ',si_extname,', ',si_midname) as si_fullname,si_department,si_course,si_block,si_yrlevel,si_sem from tbl_studentinfo WHERE si_department='$d->department' ORDER BY si_lastname,si_firstname,si_midname,si_extname DESC");
        }

        function getCoordinatorStudents($d){
            return $this->executeWithRes("SELECT si_idnumber,CONCAT(si_lastname,', ',si_firstname,' ',si_extname,', ',si_midname) as si_fullname,si_department,si_course,si_block,si_yrlevel,si_sem from tbl_studentinfo WHERE si_course='$d->program' ORDER BY si_lastname,si_firstname,si_midname,si_extname DESC");
        }





        
        // NOTE: This is all the functions for faculty/mystudents (page)
        function updateDP($d){
            return $this->executeWithoutRes("UPDATE tbl_faculty set fa_picture='$d->image' WHERE fa_recno='$d->recno'");
        }





        // students/schedule
        function getStudentSchedule($d){
            return $this->executeWithRes("SELECT tbl_classes.cl_code, tbl_classes.cl_sucode, tbl_classes.cl_room,tbl_classes.cl_stime,tbl_classes.cl_etime,tbl_classes.cl_day, tbl_faculty.fa_fname, tbl_faculty.fa_mname,tbl_faculty.fa_lname, tbl_faculty.fa_extname from tbl_classes LEFT JOIN tbl_enrolledsubjects ON tbl_classes.cl_code = tbl_enrolledsubjects.es_clcode
            INNER JOIN tbl_faculty ON tbl_classes.cl_facultyid = tbl_faculty.fa_empnumber 
            WHERE tbl_enrolledsubjects.es_idnumber=$d->si_idnumber group by es_clcode");
        }

        // students/prospectus
        function getProspectusCopy($d){
            return $this->executeWithRes("SELECT * FROM tbl_subjects LEFT JOIN tbl_studentinfo ON tbl_subjects.su_cy = tbl_studentinfo.si_cy WHERE su_course='$d->si_course' AND tbl_studentinfo.si_idnumber='$d->si_idnumber' ORDER BY su_yrlevel ASC, su_sem ASC");
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
                return $this->executeWithRes("SELECT * FROM tbl_courses WHERE co_dept = '$d->dept' and co_status = 'AVAILABLE'");
            } else{
                return $this->executeWithRes("SELECT * FROM tbl_courses WHERE co_status = 'AVAILABLE' ORDER BY co_dept, co_name ASC");
            }
            
        }

        // students/DP
        function updateImage($d){
            return $this->executeWithoutRes("UPDATE tbl_studentinfo SET si_picture='$d->image' WHERE si_recno='$d->recno'");
        }

        // function uploadImageStudent(){
        //     if(isset($_FILES['file'])){

        //         $studId = $_POST['studId'];
        //         $file_name = $_FILES['file']['name'];
        //         $file_explodedname = explode('.', $file_name);
        //         $file_ext = strtolower(end($file_explodedname) );
        //         $newfile_name = $studId . '.' . $file_ext;
        //         $target_dir = "../imagesFP/".$newfile_name;

        //         $extensions = array("jpeg", "jpg", "png");

        //         if(in_array($file_ext,$extensions)){ // check if file is valid

        //             if(move_uploaded_file($_FILES['file']['tmp_name'], $target_dir)){
        //                 $query = "UPDATE tbl_studentinfo SET si_picture = 'http://localhost/imagesFP/$newfile_name' WHERE si_idnumber=$studId";
        //                 $this->executeWithoutRes($query);
                        
        //                 return $this->info = array(
        //                     'status'=>array(
        //                         'remarks'=>true,
        //                         'message'=>'Uploading success.'
        //                     ),
        //                     'data' =>$this->data,
        //                     'timestamp'=>date_create(),
        //                     'prepared_by'=>'F-Society'
        //                 );

        //             }else{
        //                 return $this->info = array('status'=>array(
        //                     'remarks'=>false,
        //                     'message'=>'Uploading failed.'),
        //                 'timestamp'=>date_create(),
        //                 'prepared_by'=>'F-Society' );
        //             }

        //         }else{
        //             return $this->info = array('status'=>array(
        //                 'remarks'=>false,
        //                 'message'=>'Invalid file for uploading grades.'),
        //             'timestamp'=>date_create(),
        //             'prepared_by'=>'F-Society' );
        //         }
                
                
        //     }else{
        //         return $this->info = array('status'=>array(
        //             'remarks'=>false,
        //             'message'=>'No file uploaded.'),
        //         'timestamp'=>date_create(),
        //         'prepared_by'=>'F-Society' );
        //     }

        // }



        // profily/enlistment

        function getProvinces($d){
            return $this->executeWithRes("SELECT * FROM provinces");
        }

        function getCities($d){
            return $this->executeWithRes("SELECT * FROM cities WHERE province_id =$d->provinceId");
        }
        
        function getCity($d){
            return $this->executeWithRes("SELECT * FROM cities WHERE id = '$d->cityName'");
        }

        // faculty/profiley
            function getStudent($d) {
                return $this->executeWithRes("SELECT * from tbl_studentinfo WHERE si_idnumber = '$d->idNumber'");
            }

            function getStudents($d) {
                return $this->executeWithRes("SELECT si_idnumber,CONCAT(si_lastname,', ',si_firstname,' ',si_extname,', ',si_midname) as si_fullname, si_block, si_course, si_department from tbl_studentinfo WHERE (si_idnumber LIKE '%$d->searchClass%' or si_lastname LIKE '%$d->searchClass%' or si_firstname LIKE '%$d->searchClass%' or si_midname LIKE '%$d->searchClass%' or si_block LIKE '%$d->searchClass%' or si_department LIKE '%$d->searchClass%' or si_course LIKE '%$d->searchClass%') ORDER BY si_idnumber ASC ");
            }

            function getActiveClasses($d) {
                return $this->executeWithRes("SELECT * from tbl_classes as cl JOIN tbl_subjects as su on su.su_code = cl.cl_sucode WHERE cl.cl_schoolyear = '$d->actSY' and cl.cl_sem = '$d->actSem' and (cl.cl_code LIKE '%$d->searchClass%' or su.su_description LIKE '%$d->searchClass%' or cl.cl_room LIKE '%$d->searchClass%' or su.su_code LIKE '%$d->searchClass%' or cl.cl_block LIKE '%$d->searchClass%') ORDER BY cl.cl_block,su.su_code,cl.cl_day,cl.cl_stime,cl.cl_etime ASC LIMIT 25");
            }

            function getSettings($d) {
                return $this->executeWithRes("SELECT * from tbl_enlistment WHERE en_isactive = 'ACTIVE'");
            }

            function updateSettings($d) {
                return $this->executeWithoutRes("UPDATE tbl_enlistment SET en_cystart = '$d->en_cystart', en_cyend = '$d->en_cyend', en_schoolyear = '$d->en_schoolyear', en_cy = '$d->en_cy', en_sem = '$d->en_sem', en_enstart = '$d->en_enstart', en_enend = '$d->en_enend' where en_recno = '$d->en_recno'");
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
                return $this->executeWithRes("SELECT DISTINCT cl_block from tbl_classes WHERE cl_block LIKE '%$d->si_course%' AND cl_sem = $d->actSem and cl_schoolyear = '$d->actSY'");
            }

            function getDept($d) {
                return $this->executeWithRes("SELECT co_dept from tbl_courses GROUP BY co_dept");
            }


            function enrollByBlock($d) {
                $this->executeWithoutRes("DELETE FROM tbl_enrolledsubjects WHERE es_idnumber = '$d->si_idnumber' and es_added = '0'");
                return $this->executeWithoutRes("INSERT INTO tbl_enrolledsubjects(es_idnumber,es_clcode,es_sucode,es_block) SELECT '$d->si_idnumber',cl_code,cl_sucode,cl_block from tbl_classes WHERE cl_block = '$d->blockSelected'");
            }

            // GCAT INSERT
            function insertNewStudent($d){
                $lname = $this->conn->real_escape_string($d->lname);
                $fname = $this->conn->real_escape_string($d->fname);
                $mname = $this->conn->real_escape_string($d->mname);
                $extname = $this->conn->real_escape_string($d->nameext);
                $address = $this->conn->real_escape_string($d->fulladdress);
                $addressnum = '';
                if(isset($d->addressnum)){
                    $addressnum = $this->conn->real_escape_string($d->addressnum);
                }
                $addressst = '';
                if(isset($d->addressst)){
                    $addressst = $this->conn->real_escape_string($d->addressst);
                }
                $addresscity = '';
                if(isset($d->addresscity)){
                    $addresscity = $this->conn->real_escape_string($d->addresscity);
                }
                $addressprovince = '';
                if(isset($d->addressprovince)){
                    $addressprovince = $this->conn->real_escape_string($d->addressprovince);
                }
                $zipcode = $this->conn->real_escape_string($d->addresszip);
                $gender = $this->conn->real_escape_string($d->gender);
                $bday = $this->conn->real_escape_string($d->dob);
                $email = $this->conn->real_escape_string($d->email);
                $contact = $this->conn->real_escape_string($d->mobile);
                $entrancescore = '';
                $course = $this->conn->real_escape_string($d->course);
                $course2 = '';
                if(isset($d->course2)){
                    $course2 = $this->conn->real_escape_string($d->course2);
                }
                $course3 = '';
                if(isset($d->course3)){
                    $course3 = $this->conn->real_escape_string($d->course3);
                }
                $elem = $this->conn->real_escape_string($d->elem);
                $elemyear = $this->conn->real_escape_string($d->elemyear);
                $highschool = $this->conn->real_escape_string($d->highschool);
                $highschoolyear = $this->conn->real_escape_string($d->highschoolyear);
                $highschoolgpa = $this->conn->real_escape_string($d->highschoolgpa);
                $english = $this->conn->real_escape_string($d->english);
                $math = $this->conn->real_escape_string($d->math);
                $science = $this->conn->real_escape_string($d->science);
                $tertiary = $this->conn->real_escape_string($d->tertiary);
                $tertiaryyear = $this->conn->real_escape_string($d->tertiaryyear);
                $tertiarycourse = $this->conn->real_escape_string($d->tertiarycourse);
                $vocational = $this->conn->real_escape_string($d->vocational);
                $vocationalyear = $this->conn->real_escape_string($d->vocationalyear);
                $vocationalcourse = $this->conn->real_escape_string($d->vocationalcourse);
                $nc = $this->conn->real_escape_string($d->nc);
                $nclvl = $this->conn->real_escape_string($d->nclvl);
                $honors = '';
                if(isset($d->honors)){
                    $honors = $this->conn->real_escape_string($d->honors);
                }
                $lrn = '';
                if(isset($d->lrn)){
                    $lrn = $this->conn->real_escape_string($d->lrn);
                }

                $strand = '';
                if(isset($d->strand)){
                    $strand = $this->conn->real_escape_string($d->strand);
                }
                $brothers = $this->conn->real_escape_string($d->brothers);
                $sisters = $this->conn->real_escape_string($d->sisters);
                $siblings = $this->conn->real_escape_string($d->siblings);
                $motherdead = $this->conn->real_escape_string($d->motherdead);
                $mother = $this->conn->real_escape_string($d->mother);
                $motheroccupation = $this->conn->real_escape_string($d->motheroccupation);
                $mothereducation = $this->conn->real_escape_string($d->mothereducation);
                $momcontact = $this->conn->real_escape_string($d->mothercontact);
                $fatherdead = $this->conn->real_escape_string($d->fatherdead);
                $father = $this->conn->real_escape_string($d->father);
                $fatheroccupation = $this->conn->real_escape_string($d->fatheroccupation);
                $fathereducation = $this->conn->real_escape_string($d->fathereducation);
                $dadcontact = $this->conn->real_escape_string($d->fathercontact);
                $guardname = $this->conn->real_escape_string($d->guardian);
                $guardrel = $this->conn->real_escape_string($d->relationship);
                $guardadd = $this->conn->real_escape_string($d->guardianadd);
                $emergencynumber = $this->conn->real_escape_string($d->emergencynumber);
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
                    $govprojother = $this->conn->real_escape_string($d->govprojother);
                }
                $famincome = $this->conn->real_escape_string($d->famincome);
                $disabled = $this->conn->real_escape_string($d->disabled);
                $disability ='';
                if(isset($d->disability)){
                    $disability = $this->conn->real_escape_string($d->disability);
                }
                $household='';
                if(isset($d->household)){
                    $household = $this->conn->real_escape_string($d->household);
                }
                $pob = $this->conn->real_escape_string($d->pob);
                $civilstatus = $this->conn->real_escape_string($d->civilstatus);
                $citizenship = $this->conn->real_escape_string($d->citizenship);
                $age = $this->conn->real_escape_string($d->age);
                $type = $this->conn->real_escape_string($d->type);
                $cy = $this->conn->real_escape_string($d->cy);
                $department = $this->conn->real_escape_string($d->department);
                $sem = $this->conn->real_escape_string($d->sem);
                $yearenrolled = $this->conn->real_escape_string($d->schoolyear);
                $ipgroup = $this->conn->real_escape_string($d->ipgroup);
                $isshs = $this->conn->real_escape_string($d->isshs);
                $hsclass = $this->conn->real_escape_string($d->hsclass);
                $counten=0;


                // update student
                if(isset($d->id)){
                    $id = $d->id;
                    $updateNewStudent = "UPDATE tbl_studentinfo SET
                    si_lastname = '$lname',
                    si_firstname ='$fname',
                    si_midname='$mname',
                    si_extname='$extname',
                    si_address='$address',
                    si_houseno='$addressnum',
                    si_brgy='$addressst',
                    si_city='$addresscity',
                    si_province='$addressprovince',
                    si_zipcode='$zipcode',
                    si_gender='$gender',
                    si_bday='$bday',
                    si_email='$email',
                    si_mobile='$contact',
                    si_course='$course',
                    si_coursechoice='$course2',
                    si_coursechoice2='$course3',
                    si_lastschool='$highschool',
                    si_highschoolyear='$highschoolyear',
                    si_average='$highschoolgpa',
                    si_english= '$english',
                    si_math='$math',
                    si_science='$science',
                    si_elem='$elem',
                    si_elemyear= '$elemyear',
                    si_tertiary= '$tertiary',
                    si_tertiaryyear='$tertiaryyear',
                    si_tertiarycourse='$tertiarycourse',
                    si_vocational= '$vocational',
                    si_vocationalyear=  '$vocationalyear',
                    si_vocationalcourse='$vocationalcourse',
                    si_nc=  '$nc',
                    si_nclvl= '$nclvl',
                    si_specialaward='$honors',
                    si_lrn= '$lrn',
                    si_strand= '$strand',
                    si_brothers= '$brothers',
                    si_sisters='$sisters',
                    si_siblings= '$siblings',
                    si_momdeceased='$motherdead',
                    si_momname='$mother',
                    si_momoccupation='$motheroccupation',
                    si_educationmom='$mothereducation',
                    si_momcontact='$momcontact',
                    si_daddeceased='$fatherdead',
                    si_dadname= '$father',
                    si_dadoccupation='$fatheroccupation',
                    si_educationdad='$fathereducation',
                    si_dadcontact= '$dadcontact',
                    si_guardname= '$guardname',
                    si_guardrel='$guardrel',
                    si_guardadd='$guardadd',
                    si_emergencycontact='$emergencynumber',
                    si_govproj='$govproj',
                    si_govprojothers='$govprojother',
                    si_famincome='$famincome',
                    si_isdisabled= '$disabled',
                    si_disability='$disability',
                    si_householdno='$household',
                    si_pob='$pob',
                    si_civilstatus='$civilstatus',
                    si_nationality='$citizenship',
                    si_age='$age',
                    si_studenttype='$type',
                    si_cy='$cy',
                    si_department='$department',
                    si_sem='$sem',
                    si_enrolledyear='$yearenrolled',
                    si_ipgroup='$ipgroup',
                    si_isshs= '$isshs',
                    si_hsclass= '$hsclass' 
                    WHERE si_idnumber = '$id'";
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
                        // id encryption
                     $simple_string = $id; 
                     $ciphering = "AES-128-CTR"; 
                     $iv_length = openssl_cipher_iv_length($ciphering); 
                     $options = 0; 
                     $encryption_iv = '1234567891011121'; 
                     $encryption_key = "fsociety"; 
                     $encryption = openssl_encrypt($simple_string, $ciphering, 
                                    $encryption_key, $options, $encryption_iv); 
                     $key = rawurlencode($encryption);
                    if($this->conn->query($updateNewStudent)){
                            $sqlgcat = "UPDATE tbl_gcat SET gc_course = '$course', gc_gpa = '$highschoolgpa', gc_english = '$english', gc_math = '$math', gc_science = '$science', gc_key = '$key' WHERE gc_idnumber = '$id'";
                                        if($this->conn->query($sqlgcat)){
                                            // email
                                            $mail->addAddress($email);
                                            $mail->Subject = "Gordon College Admission Test(GCAT) Registration";
                                            $mail->Body = "Hello <b>{$fname} {$lname}</b>!<br>";
                                            $mail->Body .="Your registration for taking the GCAT exam was successful.<br>";
                                            $mail->Body .="This is your temporary id number: <b>{$id}</b>.<br><br>";
                                            $mail->Body .="Please visit this link for your printable Form SR01(TO BE PRINTED ON A4 SIZE BOND PAPER):<br>";
                                            // $mail->Body .="<b>http://localhost/gordoncollegeweb/print/sis.php?id={$id}&key={$key}</b><br><br>";
                                            $mail->Body .="<b><a href='https://gordoncollegeccs.edu.ph/gc/api/print/sis.php?id={$id}&key={$key}'>https://gordoncollegeccs.edu.ph/gc/api/print/sis.php?id={$id}&key={$key}</a></b><br><br>";
                                            $mail->Body .="If you need to edit your data, you can visit the link below:<br>";
                                            $mail->Body .="<b><a href='https://gordoncollegeccs.edu.ph/gc/home/#/edit/{$id}/{$key}'>https://gordoncollegeccs.edu.ph/gc/home/#/edit/{$id}/{$key}</a></b><br><br>";
                                            $mail->Body .="Please secure <b>3 printed copies</b> of your Form SR01 to be submitted to the <b>Registrar's Office</b>.<br>";
                                            $mail->Body .="Submission of SR01 forms will start on <b>March 02, 2020</b>.<br><br>";
                                            $mail->Body .="Kindly acknowledge receipt of this email by clicking on this link: <br>";
                                            $mail->Body .="<b><a href='https://gordoncollegeccs.edu.ph/gc/api/confirmation/confirmation.php?id={$id}&key={$key}'>https://gordoncollegeccs.edu.ph/gc/api/confirmation/confirmation.php?id={$id}&key={$key}</a></b><br><br>";
                                            $mail->Body .="Thank you.<br><br>";
                                            $mail->Body .="Sincerely,<br>";
                                            $mail->Body .="Gordon College Olongapo";
                                            if ($mail->send()) {
                                                $valid[0]='success';
                                                $valid[1]=$id;
                                                $valid[2]="Please save/take note of this temporary ID number. We have also sent an email to {$email} for your printable Form SR01.";
                                                return $valid;
                                            } else {
                                                echo "Mailer Error : " . $mail->ErrorInfo;
                                            }
                                        } else{
                                            $valid[0]='error';
                                            $valid[1]='ERROR';
                                            $valid[2]=$this->conn->error;
                                            return $valid;
                                        }
                    }else{
                        $valid[0]='error';
                        $valid[1]='ERROR';
                        $valid[2]=$this->conn->error;
                        return $valid;
                    }
                } 
                
                // insertnewstudent 
                else{
                    $insertNewStudent = "INSERT INTO tbl_studentinfo
                    (si_lastname,
                    si_firstname,
                    si_midname,
                    si_extname,
                    si_address,
                    si_houseno,
                    si_brgy,
                    si_city,
                    si_province,
                    si_zipcode,
                    si_gender,
                    si_bday,
                    si_email,
                    si_mobile,
                    si_course,
                    si_coursechoice,
                    si_coursechoice2,
                    si_lastschool,
                    si_highschoolyear,
                    si_average,
                    si_english,
                    si_math,
                    si_science,
                    si_elem,
                    si_elemyear,
                    si_tertiary,
                    si_tertiaryyear,
                    si_tertiarycourse,
                    si_vocational,
                    si_vocationalyear,
                    si_vocationalcourse,
                    si_nc,
                    si_nclvl,
                    si_specialaward,
                    si_lrn,
                    si_strand,
                    si_brothers,
                    si_sisters,
                    si_siblings,
                    si_momdeceased,
                    si_momname,
                    si_momoccupation,
                    si_educationmom,
                    si_momcontact,
                    si_daddeceased,
                    si_dadname,
                    si_dadoccupation,
                    si_educationdad,
                    si_dadcontact,
                    si_guardname,
                    si_guardrel,
                    si_guardadd,
                    si_emergencycontact,
                    si_govproj,
                    si_govprojothers,
                    si_famincome,
                    si_isdisabled,
                    si_disability,
                    si_householdno,
                    si_pob,
                    si_civilstatus,
                    si_nationality,
                    si_age,
                    si_studenttype,
                    si_cy,
                    si_department,
                    si_sem,
                    si_enrolledyear,
                    si_ipgroup,
                    si_isshs,
                    si_hsclass
                    ) 
                    VALUES
                    ('$lname',
                    '$fname',
                    '$mname',
                    '$extname',
                    '$address',
                    '$addressnum',
                    '$addressst',
                    '$addresscity',
                    '$addressprovince',
                    '$zipcode',
                    '$gender',
                    '$bday',
                    '$email',
                    '$contact',
                    '$course',
                    '$course2',
                    '$course3',
                    '$highschool',
                    '$highschoolyear',
                    '$highschoolgpa',
                    '$english',
                    '$math',
                    '$science',
                    '$elem',
                    '$elemyear',
                    '$tertiary',
                    '$tertiaryyear',
                    '$tertiarycourse',
                    '$vocational',
                    '$vocationalyear',
                    '$vocationalcourse',
                    '$nc',
                    '$nclvl',
                    '$honors',
                    '$lrn',
                    '$strand',
                    '$brothers',
                    '$sisters',
                    '$siblings',
                    '$motherdead',
                    '$mother',
                    '$motheroccupation',
                    '$mothereducation',
                    '$momcontact',
                    '$fatherdead',
                    '$father',
                    '$fatheroccupation',
                    '$fathereducation',
                    '$dadcontact',
                    '$guardname',
                    '$guardrel',
                    '$guardadd',
                    '$emergencynumber',
                    '$govproj',
                    '$govprojother',
                    '$famincome',
                    '$disabled',
                    '$disability',
                    '$household',
                    '$pob',
                    '$civilstatus',
                    '$citizenship',
                    '$age',
                    '$type',
                    '$cy',
                    '$department',
                    '$sem',
                    '$yearenrolled',
                    '$ipgroup',
                    '$isshs',
                    '$hsclass')";
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

                    if($this->conn->query($insertNewStudent)){
                        $insertid = $this->conn->insert_id;
                        $tempid = $insertid + 200000;
                        $sqlid = "UPDATE tbl_studentinfo set si_idnumber = '$tempid' where si_recno = '$insertid'";
                        if($this->conn->query($sqlid)){
                            // id encryption
                            $simple_string = $tempid; 
                            $ciphering = "AES-128-CTR"; 
                            $iv_length = openssl_cipher_iv_length($ciphering); 
                            $options = 0; 
                            $encryption_iv = '1234567891011121'; 
                            $encryption_key = "fsociety"; 
                            $encryption = openssl_encrypt($simple_string, $ciphering, 
                                        $encryption_key, $options, $encryption_iv); 
                            $key = rawurlencode($encryption);
                            $sqlgcat = "INSERT INTO tbl_gcat(gc_idnumber, gc_key, gc_course, gc_gpa, gc_english, gc_math, gc_science, gc_regtime) 
                                        VALUES('$tempid', '$key', '$course', '$highschoolgpa', '$english', '$math', '$science', now())";
                                        if($this->conn->query($sqlgcat)){
                                            // email
                                            $mail->addAddress($email);
                                            $mail->Subject = "Gordon College Admission Test(GCAT) Registration";
                                            $mail->Body = "Hello <b>{$fname} {$lname}</b>!<br>";
                                            $mail->Body .="Your registration for taking the GCAT exam was successful.<br>";
                                            $mail->Body .="This is your temporary id number: <b>{$tempid}</b>.<br><br>";
                                            $mail->Body .="Please visit this link for your printable Form SR01(TO BE PRINTED ON A4 SIZE BOND PAPER):<br>";
                                            // $mail->Body .="<b>http://localhost/gordoncollegeweb/print/sis.php?id={$tempid}&key={$key}</b><br><br>";
                                            $mail->Body .="<b><a href='https://gordoncollegeccs.edu.ph/gc/api/print/sis.php?id={$tempid}&key={$key}'>https://gordoncollegeccs.edu.ph/gc/api/print/sis.php?id={$tempid}&key={$key}</a></b><br><br>";
                                            $mail->Body .="If you need to edit your data, you can visit the link below:<br>";
                                            $mail->Body .="<b><a href='https://gordoncollegeccs.edu.ph/gc/home/#/edit/{$tempid}/{$key}'>https://gordoncollegeccs.edu.ph/gc/home/#/edit/{$tempid}/{$key}</a></b><br><br>";
                                            $mail->Body .="Please secure <b>3 printed copies</b> of your Form SR01 to be submitted to the <b>Registrar's Office</b>.<br>";
                                            $mail->Body .="Submission of SR01 forms will start on <b>March 02, 2020</b>.<br><br>";
                                            $mail->Body .="Kindly acknowledge receipt of this email by clicking on this link: <br>";
                                            $mail->Body .="<b><a href='https://gordoncollegeccs.edu.ph/gc/api/confirmation/confirmation.php?id={$tempid}&key={$key}'>https://gordoncollegeccs.edu.ph/gc/api/confirmation/confirmation.php?id={$tempid}&key={$key}</a></b><br><br>";
                                            $mail->Body .="Sincerely,<br>";
                                            $mail->Body .="Gordon College Olongapo";
                                            if ($mail->send()) {
                                                $valid[0]='success';
                                                $valid[1]=$tempid;
                                                $valid[2]="Please save/take note of this temporary ID number. We have also sent an email to {$email} for your printable Form SR01.";
                                                return $valid;
                                            } else {
                                                echo "Mailer Error : " . $mail->ErrorInfo;
                                            }
                                        } else{
                                            $valid[0]='error';
                                            $valid[1]='ERROR';
                                            $valid[2]=$this->conn->error;
                                            return $valid;
                                        }
                        } else{
                            $valid[0]='error';
                            $valid[1]='ERROR';
                            $valid[2]=$this->conn->error;
                            return $valid;
                        }
                    }else{
                        $valid[0]='error';
                        $valid[1]='ERROR';
                        $valid[2]=$this->conn->error;
                        return $valid;
                    }
                }
                
                

            }             // /GCAT INSERT END




            function updateStudentInfo($d){
                $recno = $d->recno;
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
                $type = $d->type;
                if(isset($d->recno)){
                    $updateStudent = "UPDATE tbl_studentinfo SET si_idnumber = '$d->idnumber', si_lastname = '$lname', si_firstname = '$fname', si_midname = '$mname', si_extname = '$extname', si_address = '$address',  si_gender = '$gender', si_bday = '$bday', si_email = '$email', si_mobile = '$contact', si_course = '$course', si_coursechoice = '$course2', si_coursechoice2 = '$course3', si_reason = '$reason', si_siblings = '$siblings', si_momname = '$mother', si_dadname = '$father', si_emergencycontact = '$emergencynumber', si_device = '$devices', si_entranceexam = '$entrancescore', si_lastschool = '$highschool', si_average = '$highschoolgpa', si_istransferee = '$transferee', si_transfercourselevel = '$transferschool', si_reasonstudy = '$reasongc', si_scholartype = '$scholartype', si_support = '$sponsor', si_supportoccupation = '$sponsoroccupation', si_specialaward = '$honors', si_organization = '$orgs', si_competition = '$competitions', si_interest = '$interests', si_talent = '$talents',si_schoolyear = '$yearenrolled',si_isregular = '$regular', si_yrlevel = '$year', si_sem = '$sem', si_enrolledyear = '$yearenrolled', si_isenrolled = 0, si_isenlisted = 1, si_sport = '$sport', si_momoccupation = '$motheroccupation', si_dadoccupation = '$fatheroccupation', si_lrn = '$lrn', si_strand = '$strand', si_guardname = '$guardname', si_guardrel = '$guardrel', si_guardadd = '$guardadd', si_govproj = '$govproj', si_govprojothers = '$govprojother', si_famincome = '$famincome', si_isdisabled = '$disabled', si_disability = '$disability', si_householdno = '$household', si_zipcode = '$zipcode', si_momcontact = '$momcontact', si_dadcontact = '$dadcontact', si_spouse = '$spouse', si_spousecontact = '$spousecontact', si_department = '$department', si_pob = '$pob', si_civilstatus = '$civilstatus', si_age = '$age', si_studenttype = '$type', si_religion = '$religion' WHERE si_recno = '$d->recno'";

                    if($this->conn->query($updateStudent)){
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

                } else{
                $verify = "SELECT * FROM tbl_studentinfo WHERE si_idnumber='$d->idnumber'";
                $updateStudent = "UPDATE tbl_studentinfo SET si_lastname = '$lname', si_firstname = '$fname', si_midname = '$mname', si_extname = '$extname', si_address = '$address',  si_gender = '$gender', si_bday = '$bday', si_email = '$email', si_mobile = '$contact', si_course = '$course', si_coursechoice = '$course2', si_coursechoice2 = '$course3', si_reason = '$reason', si_siblings = '$siblings', si_momname = '$mother', si_dadname = '$father', si_emergencycontact = '$emergencynumber', si_device = '$devices', si_entranceexam = '$entrancescore', si_lastschool = '$highschool', si_average = '$highschoolgpa', si_istransferee = '$transferee', si_transfercourselevel = '$transferschool', si_reasonstudy = '$reasongc', si_scholartype = '$scholartype', si_support = '$sponsor', si_supportoccupation = '$sponsoroccupation', si_specialaward = '$honors', si_organization = '$orgs', si_competition = '$competitions', si_interest = '$interests', si_talent = '$talents',si_schoolyear = '$yearenrolled',si_isregular = '$regular', si_yrlevel = '$year', si_sem = '$sem', si_enrolledyear = '$yearenrolled', si_isenrolled = 0, si_isenlisted = 1, si_sport = '$sport', si_momoccupation = '$motheroccupation', si_dadoccupation = '$fatheroccupation', si_lrn = '$lrn', si_strand = '$strand', si_guardname = '$guardname', si_guardrel = '$guardrel', si_guardadd = '$guardadd', si_govproj = '$govproj', si_govprojothers = '$govprojother', si_famincome = '$famincome', si_isdisabled = '$disabled', si_disability = '$disability', si_householdno = '$household', si_zipcode = '$zipcode', si_momcontact = '$momcontact', si_dadcontact = '$dadcontact', si_spouse = '$spouse', si_spousecontact = '$spousecontact', si_department = '$department', si_pob = '$pob', si_civilstatus = '$civilstatus', si_age = '$age', si_studenttype = '$type', si_religion = '$religion' WHERE si_idnumber = '$d->idnumber'";
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







            // gcat/users
            function getGCATmembers() {
                return $this->executeWithRes("SELECT * from tbl_faculty WHERE fa_department='GCAT-R' || fa_department='GCAT-AO' ORDER BY fa_lname,fa_fname,fa_mname,fa_extname ASC");
            }

            function deleteGCATmember($d) {
                return $this->executeWithoutRes("DELETE from tbl_faculty WHERE fa_recno='$d->empRecno'");
            }

            function printStudentSIS() {
                $idNumber = $_POST['idNumber'];
                return $this->executeWithRes("SELECT * from tbl_studentinfo WHERE si_idnumber = '$idNumber'");
            }

            function getApplicants() {
                return $this->executeWithRes("SELECT gc.*, si.si_email as si_email, si.si_mobile as si_mobile, CONCAT(si.si_lastname,', ',si.si_firstname,', ',si.si_midname,' ',si.si_extname) as si_fullname FROM tbl_gcat as gc INNER JOIN tbl_studentinfo as si on si.si_idnumber = gc.gc_idnumber ORDER BY gc.gc_regtime ASC");
            }


            function validateStudent($d) {
                return $this->executeWithRes("SELECT * from tbl_studentinfo WHERE si_firstname = '$d->firstname' and si_lastname = '$d->lastname' and si_midname = '$d->midname' and si_bday = '$d->bday'");
            }

            function validateUnenrolled($d) {
                return $this->executeWithRes("SELECT * from tbl_gcat WHERE gc_idnumber = '$d->idNumber'");
            }

            function validateEmail($d){
                return $this->executeWithRes("SELECT si_email from tbl_studentinfo WHERE si_email = '$d->email'");
            }

            function validateEdit($d){
                $ciphering = "AES-128-CTR"; 
                $iv_length = openssl_cipher_iv_length($ciphering); 
                $options = 0; 
                $id = $d->id;
                $key = $d->key;
                $decryption_iv = '1234567891011121'; 
                
                // Store the decryption key 
                $decryption_key = "fsociety"; 
                
                // Use openssl_decrypt() function to decrypt the data 
                $decryptedkey=openssl_decrypt ($key, $ciphering,  
                            $decryption_key, $options, $decryption_iv); 
                if($id == $decryptedkey){
                    return $this->executeWithRes("SELECT * from tbl_studentinfo WHERE si_idnumber = '$id'");
                } else{
                    return "error";
                }
                
            }

            function updateEmail($d){
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
                $email = $d->email;
                $id = $d->idNumber;
                $fname = $d->fname;
                $lname = $d->lname;
                $updateEmail = "UPDATE tbl_studentinfo SET si_email = '$email' WHERE si_idnumber = '$id'";
                if($this->conn->query($updateEmail)){
                    // id encryption
                    $simple_string = $id; 
                    $ciphering = "AES-128-CTR"; 
                    $iv_length = openssl_cipher_iv_length($ciphering); 
                    $options = 0; 
                    $encryption_iv = '1234567891011121'; 
                    $encryption_key = "fsociety"; 
                    $encryption = openssl_encrypt($simple_string, $ciphering, 
                                    $encryption_key, $options, $encryption_iv); 
                    $key = rawurlencode($encryption);
                    // email
                    $mail->addAddress($email);
                    $mail->Subject = "Gordon College Admission Test(GCAT) Registration";
                    $mail->Body = "Hello <b>{$fname} {$lname}</b>!<br>";
                    $mail->Body .="Your registration for taking the GCAT exam was successful.<br>";
                    $mail->Body .="This is your temporary id number: <b>{$id}</b>.<br><br>";
                    $mail->Body .="Please visit this link for your printable Form SR01(TO BE PRINTED ON A4 SIZE BOND PAPER):<br>";
                    // $mail->Body .="<b>http://localhost/gordoncollegeweb/print/sis.php?id={$id}&key={$key}</b><br><br>";
                    $mail->Body .="<b><a href='https://gordoncollegeccs.edu.ph/gc/api/print/sis.php?id={$id}&key={$key}'>https://gordoncollegeccs.edu.ph/gc/api/print/sis.php?id={$id}&key={$key}</a></b><br><br>";
                    $mail->Body .="If you need to edit your data, you can visit the link below:<br>";
                    $mail->Body .="<b><a href='https://gordoncollegeccs.edu.ph/gc/home/#/edit/{$id}/{$key}'>https://gordoncollegeccs.edu.ph/gc/home/#/edit/{$id}/{$key}</a></b><br><br>";
                    $mail->Body .="Please secure <b>3 printed copies</b> of your Form SR01 to be submitted to the <b>Registrar's Office</b>.<br>";
                    $mail->Body .="Submission of SR01 forms will start on <b>March 02, 2020</b>.<br><br>";
                    $mail->Body .="Kindly acknowledge receipt of this email by clicking on this link: <br>";
                    $mail->Body .="<b><a href='https://gordoncollegeccs.edu.ph/gc/api/confirmation/confirmation.php?id={$id}&key={$key}'>https://gordoncollegeccs.edu.ph/gc/api/confirmation/confirmation.php?id={$id}&key={$key}</a></b><br><br>";
                    $mail->Body .="Sincerely,<br>";
                    $mail->Body .="Gordon College Olongapo";
                    if ($mail->send()) {
                        $valid[0]='success';
                        $valid[1]=$id;
                        $valid[2]="Please save/take note of this temporary ID number. We have also sent an email to {$email} for your printable Form SR01.";
                        return $valid;
                    } else {
                        return 'Email Failed';
                    }
                }else{
                    $valid[0]='error';
                    $valid[1]='ERROR';
                    $valid[2]=$this->conn->error;
                    return $valid;
                }
            }


            function getMail(){
                return $this->executeWithRes("SELECT gc_idnumber, gc_key, si_email, si_firstname, si_lastname FROM tbl_gcat INNER JOIN tbl_studentinfo on tbl_studentinfo.si_idnumber = tbl_gcat.gc_idnumber");
            }

            function sendMail($d){
                $valid = 0;
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
                $id = $d->gc_idnumber;
                $email = $d->si_email;
                $fname = $d->si_firstname;
                $lname = $d->si_lastname;
                // id encryption
                $key = $d->gc_key;
                    // email
                $mail->addAddress($email);
                $mail->Subject = "Gordon College Admission Test(GCAT) Registration";
                $mail->Body = "Hello <b>{$fname} {$lname}</b>!<br>";
                $mail->Body .="Your registration for taking the GCAT exam was successful.<br>";
                $mail->Body .="This is your temporary id number: <b>{$id}</b>.<br><br>";
                $mail->Body .="<b>Kindly check this link below for final review of your inputted data:<br>";
                $mail->Body .="<b><a href='https://gordoncollegeccs.edu.ph/gc/home/#/edit/{$id}/{$key}'>https://gordoncollegeccs.edu.ph/gc/home/#/edit/{$id}/{$key}</a></b><br><br>";
                $mail->Body .="Sincerely,<br>";
                $mail->Body .="Gordon College Olongapo";
                if ($mail->send()) {
                    return 'Email Success';
                } else {
                    return 'Email Failed';
                }
            }

            function reSendMail($d){
                $valid = 0;
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
                $id = $d->id;
                $email = $d->email;
                $fname = $d->firstname;
                $lname = $d->lastname;
                // id encryption
                $simple_string = $id; 
                $ciphering = "AES-128-CTR"; 
                $iv_length = openssl_cipher_iv_length($ciphering); 
                $options = 0; 
                $encryption_iv = '1234567891011121'; 
                $encryption_key = "fsociety"; 
                $encryption = openssl_encrypt($simple_string, $ciphering, 
                                $encryption_key, $options, $encryption_iv); 
                $key = rawurlencode($encryption);
                // email
                $mail->addAddress($email);
                $mail->Subject = "Gordon College Admission Test(GCAT) Registration";
                $mail->Body = "Hello <b>{$fname} {$lname}</b>!<br>";
                $mail->Body .="Your registration for taking the GCAT exam was successful.<br>";
                $mail->Body .="This is your temporary id number: <b>{$id}</b>.<br><br>";
                $mail->Body .="Please visit this link for your printable Form SR01(TO BE PRINTED ON A4 SIZE BOND PAPER):<br>";
                // $mail->Body .="<b>http://localhost/gordoncollegeweb/print/sis.php?id={$id}&key={$key}</b><br><br>";
                $mail->Body .="<b><a href='https://gordoncollegeccs.edu.ph/gc/api/print/sis.php?id={$id}&key={$key}'>https://gordoncollegeccs.edu.ph/gc/api/print/sis.php?id={$id}&key={$key}</a></b><br><br>";
                $mail->Body .="If you need to edit your data, you can visit the link below:<br>";
                $mail->Body .="<b><a href='https://gordoncollegeccs.edu.ph/gc/home/#/edit/{$id}/{$key}'>https://gordoncollegeccs.edu.ph/gc/home/#/edit/{$id}/{$key}</a></b><br><br>";
                $mail->Body .="Please secure <b>3 printed copies</b> of your Form SR01 to be submitted to the <b>Registrar's Office</b>.<br>";
                $mail->Body .="Submission of SR01 forms will start on <b>March 02, 2020</b>.<br><br>";
                $mail->Body .="Kindly acknowledge receipt of this email by clicking on this link: <br>";
                $mail->Body .="<b><a href='https://gordoncollegeccs.edu.ph/gc/api/confirmation/confirmation.php?id={$id}&key={$key}'>https://gordoncollegeccs.edu.ph/gc/api/confirmation/confirmation.php?id={$id}&key={$key}</a></b><br><br>";
                $mail->Body .="Sincerely,<br>";
                $mail->Body .="Gordon College Olongapo";
                if ($mail->send()) {
                    return 'Email Success';
                } else {
                    return 'Email Failed';
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