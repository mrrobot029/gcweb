<?php
    header('Access-Control-Allow-Origin: *');
    // header('Access-Control-Allow-Methods: PUT, POST, PATCH, OPTIONS, GET');
    header('Content-Type: application/json');
    date_default_timezone_set('Asia/Manila');
    include_once './config/database.php';
    include_once './model/post.php';
    include_once './model/auth.php';
    $database = new Database();
    $db = $database->connect();
    $post = new Post($db);
    $auth = new Auth($db);
    $data = array();

    $req = explode('/',rtrim($_REQUEST['request'],'/'));
    $d = json_decode( base64_decode( file_get_contents('php://input')));

    switch ($_SERVER['REQUEST_METHOD']) {

        case 'POST':

            switch ($req[0]) {

                // authentication related cases
                case 'register':
                    echo json_encode($auth->registeruser($d));
                break;

                case 'loginAdmin':
                    echo json_encode($auth->loginAdmin($d));
                break;

                case 'checkAdmin':
                    echo json_encode($auth->checkAdmin($d));
                break;

                case 'loginFaculty':
                    echo json_encode($auth->loginFaculty($d));
                break;

                case 'checkFaculty':
                    echo json_encode($auth->checkFaculty($d));
                break;

                case 'loginStudent':
                    echo json_encode($auth->loginStudent($d));
                break;

                case 'checkStudent':
                    echo json_encode($auth->checkStudent($d));
                break;
            
                // admin/facultymembers
                case 'getFaculty':
                    echo json_encode($post->getFaculty($d)); 
                break;

                case 'delFaculty':
                    echo json_encode($post->delFaculty($d)); 
                break;

                case 'addFaculty':
                    echo json_encode($auth->addFaculty($d)); 
                break;

                case 'uploadFaculty':
                    echo json_encode($auth->uploadFaculty()); 
                break;

                // admin/subjectprospectus
                case 'getProspectus':
                    echo json_encode($post->getProspectus($d)); 
                break;
                
                case 'uploadProspectus':
                    echo json_encode($post->uploadProspectus()); 
                break;
                
                case 'updateProspectus':
                    echo json_encode($post->updateProspectus($d)); 
                break;

                case 'addProspectus':
                    echo json_encode($post->addProspectus($d)); 
                break;

                case 'delProspectus':
                    echo json_encode($post->delProspectus($d)); 
                break;
                
                        // options in selects in admin/subjectprospectus
                        case 'getProspectusCourse':
                            echo json_encode($post->getProspectusCourse($d)); 
                        break;
                        case 'getProspectusCy':
                            echo json_encode($post->getProspectusCy($d)); 
                        break;

                // admin/classes
                case 'getClass':
                    echo json_encode($post->getClass($d)); 
                break;

                case 'addClass':
                    echo json_encode($post->addClass($d)); 
                break;

                case 'delClass':
                    echo json_encode($post->delClass($d)); 
                break;
                    
                case 'uploadClass':
                    echo json_encode($post->uploadClass()); 
                break;

                // NOTE again: getSettings is share function from faculty/profiley (page)

                        // options in select in admin/classes
                        case 'getSchoolYear':
                            echo json_encode($post->getSchoolYear()); 
                        break;

                        case 'getSem':
                            echo json_encode($post->getSem($d)); 
                        break;

                        case 'getBlocks':
                            echo json_encode($post->getBlocks($d)); 
                        break;

                        case 'getDept':
                            echo json_encode($post->getDept($d)); 
                        break;

                // all funtions for faculty pages
                // faculty/myclasses
                case 'getMyClass':
                    echo json_encode($post->getMyClass($d));
                break;

                case 'updateIsNormal':
                    echo json_encode($post->updateIsNormal($d));
                break;

                // faculty/students
                case 'getFacStudents':
                    echo json_encode($post->getFacStudents($d));
                break;

                case 'getAdminStudents':
                    echo json_encode($post->getAdminStudents($d));
                break;

                case 'getCoordinatorStudents':
                    echo json_encode($post->getCoordinatorStudents($d));
                break;

                // faculty/profile
                case 'updateDP':
                    echo json_encode($post->updateDP($d));
                break;

                case 'updatePassword':
                    echo json_encode($auth->updatePassword($d));
                break;

                case 'getclass':
                    echo json_encode($post->myclass($d));
                break;

                case 'getclassf':
                    echo json_encode($post->myclassf($d));
                break;

                case 'getfiles':
                    echo json_encode($post->classFiles($d));
                break;


                case 'coordstudents':
                    echo json_encode($post->students2($d));
                break;

                case 'allstudents':
                    echo json_encode($post->getStudents($d));
                break;

                case 'reenlist':
                    echo json_encode($post->reenlist($d));
                break;

                case 'getClassStudents':
                    echo json_encode($post->getClassStudents($d));
                break;

                // students
                // students/prospectus
                case 'getProspectusCopy':
                    echo json_encode($post->getProspectusCopy($d));
                break;

                case 'getProspectusCopyF':
                    echo json_encode($post->getProspectusCopyF($d));
                break;

                case 'getProspectusByYr':
                    echo json_encode($post->getProspectusByYr($d));
                break;

                // student/sched
                case 'getStudentSchedule':
                    echo json_encode($post->getStudentSchedule($d));
                break;

                // student/grade
                case 'getGrades':
                    echo json_encode($post->getGrades($d));
                break;

                // student/image
                case 'updateImage':
                    echo json_encode($post->updateImage($d));
                break;

                // data updating cases
                case 'updateStudentProfile':
                    echo json_encode($post->updateStudentProfile($d));
                break;

                case 'getCourses':
                    echo json_encode($post->getCourses($d));
                break;

                // data pushing related cases
                case 'addfile':
                    echo json_encode($post->addfile());
                break;

                case 'uploadGrade':
                    echo json_encode($post->uploadGrade());
                break;

                case 'uploadImageStudent':
                    echo json_encode($post->uploadImageStudent());
                break;

                //profily/enlistment
                case 'getProvinces':
                    echo json_encode($post->getProvinces($d));
                break;

                case 'getCities':
                    echo json_encode($post->getCities($d));
                break;

                case 'getCity':
                    echo json_encode($post->getCity($d));
                break;
                
                // faculty/profiley
                case 'getStudent':
                    echo json_encode($post->getStudent($d));
                break;

                case 'getActiveClasses':
                    echo json_encode($post->getActiveClasses($d));
                break;

                case 'getSettings':
                    echo json_encode($post->getSettings($d));
                break;

                case 'updateSettings':
                    echo json_encode($post->updateSettings($d));
                break;

                case 'getEnrolledClasses':
                    echo json_encode($post->getEnrolledClasses($d));
                break;

                case 'enrollSingleClass':
                    echo json_encode($post->enrollSingleClass($d));
                break;

                case 'removeEnrolledSubject':
                    echo json_encode($post->removeEnrolledSubject($d));
                break;

                case 'getCourseBlocks':
                    echo json_encode($post->getCourseBlocks($d));
                break;

                case 'enrollByBlock':
                    echo json_encode($post->enrollByBlock($d));
                break;

                case 'insertNewStudent':
                    echo json_encode($post->insertNewStudent($d));
                break;
                
                case 'updateStudentInfo':
                    echo json_encode($post->updateStudentInfo($d));
                break;

                case 'updateId':
                    echo json_encode($post->updateId($d));
                break;

                // gcat/users
                case 'addGCATmember':
                    echo json_encode($auth->addGCATmember($d));
                break;

                case 'validateStudent':
                    echo json_encode($post->validateStudent($d));
                break;

                case 'validateUnenrolled':
                    echo json_encode($post->validateUnenrolled($d));
                break;

                case 'validateEmail':
                    echo json_encode($post->validateEmail($d));
                break;

                case 'validateEdit':
                    echo json_encode($post->validateEdit($d));
                break;

                case 'updateEmail':
                    echo json_encode($post->updateEmail($d));
                break;

                case 'getGCATmembers':
                    echo json_encode($post->getGCATmembers());
                break;

                case 'deleteGCATmember':
                    echo json_encode($post->deleteGCATmember($d));
                break;

                case 'loginGCATmember':
                    echo json_encode($auth->loginGCATmember($d));
                break;

                case 'checkGCATmember':
                    echo json_encode($auth->checkGCATmember($d));
                break;

                case 'getApplicants':
                    echo json_encode($post->getApplicants());
                break;

                case 'getApplicantCount':
                    echo json_encode($post->executeWithRes("SELECT COUNT(gc_idnumber) as applicantcount from tbl_gcat"));
                break;

                case 'updateNewStudent':
                    echo json_encode($auth->updateNewStudent($d));
                break;
                
                case 'confirmApplication':
                    echo json_encode($post->executeWithoutRes("UPDATE tbl_gcat SET gc_status = 1 WHERE gc_idnumber = '$d->gc_idnumber'"));
                break;

                case 'unconfirmApplication':
                    echo json_encode($post->executeWithoutRes("UPDATE tbl_gcat SET gc_status = 0 WHERE gc_idnumber = '$d->gc_idnumber'"));
                break;

                case 'getUnconfirmedApplicants':
                    echo json_encode($post->executeWithRes("SELECT gc.*, si.si_email, si.si_mobile, si.si_firstname, si.si_lastname, CONCAT(si.si_lastname,', ',si.si_firstname,', ',si.si_midname,' ',si.si_extname)  as si_fullname FROM tbl_gcat as gc INNER JOIN tbl_studentinfo as si on si.si_idnumber = gc.gc_idnumber WHERE  gc.gc_status = '0' ORDER BY si.si_lastname,si.si_firstname,si.si_midname,si.si_extname,gc.gc_idnumber ASC"));
                break;

                case 'getUnscheduledApplicants':
                    echo json_encode($post->executeWithRes("SELECT gc.*, si.si_email, si.si_mobile, si.si_firstname, si.si_lastname, CONCAT(si.si_lastname,', ',si.si_firstname,', ',si.si_midname,' ',si.si_extname)  as si_fullname FROM tbl_gcat as gc INNER JOIN tbl_studentinfo as si on si.si_idnumber = gc.gc_idnumber WHERE  gc.gc_status = '1' ORDER BY si.si_lastname,si.si_firstname,si.si_midname,si.si_extname,gc.gc_idnumber ASC"));
                break;

                case 'getScheduledApplicants':
                    echo json_encode($post->executeWithRes("SELECT gc.*, si.si_email, si.si_mobile, si.si_firstname, si.si_lastname, CONCAT(si.si_lastname,', ',si.si_firstname,', ',si.si_midname,' ',si.si_extname)  as si_fullname FROM tbl_gcat as gc INNER JOIN tbl_studentinfo as si on si.si_idnumber = gc.gc_idnumber WHERE  gc.gc_status = '2'  ORDER BY si.si_lastname,si.si_firstname,si.si_midname,si.si_extname,gc.gc_idnumber ASC"));
                break;

                // ian codes
                case 'searchUnconfirmedApplicants':
                    echo json_encode($post->executeWithRes("SELECT gc.*, si.si_email, si.si_mobile, si.si_firstname, si.si_lastname, CONCAT(si.si_lastname,', ',si.si_firstname,', ',si.si_midname,' ',si.si_extname)  as si_fullname FROM tbl_gcat as gc INNER JOIN tbl_studentinfo as si on si.si_idnumber = gc.gc_idnumber WHERE (gc.gc_status = '0') AND (si.si_lastname LIKE '%$d->value%' || si.si_midname LIKE '%$d->value%' || si.si_firstname LIKE '%$d->value%' || gc.gc_idnumber LIKE '%$d->value%' || gc.gc_regtime LIKE '%$d->value%' || gc.gc_course LIKE '%$d->value%' || si.si_email LIKE '%$d->value%') ORDER BY si.si_lastname,si.si_firstname,si.si_midname,si.si_extname,gc.gc_idnumber ASC"));
                break;

                case 'searchUnscheduledApplicants':
                    echo json_encode($post->executeWithRes("SELECT gc.*, si.si_email, si.si_mobile, si.si_firstname, si.si_lastname, CONCAT(si.si_lastname,', ',si.si_firstname,', ',si.si_midname,' ',si.si_extname)  as si_fullname FROM tbl_gcat as gc INNER JOIN tbl_studentinfo as si on si.si_idnumber = gc.gc_idnumber WHERE (gc.gc_status = '1') AND (si.si_lastname LIKE '%$d->value%' || si.si_midname LIKE '%$d->value%' || si.si_firstname LIKE '%$d->value%' || gc.gc_idnumber LIKE '%$d->value%' || gc.gc_regtime LIKE '%$d->value%' || gc.gc_course LIKE '%$d->value%' || si.si_email LIKE '%$d->value%') ORDER BY si.si_lastname,si.si_firstname,si.si_midname,si.si_extname,gc.gc_idnumber ASC"));
                break;

                case 'searchScheduledApplicants':
                    echo json_encode($post->executeWithRes("SELECT gc.*, si.si_email, si.si_mobile, si.si_firstname, si.si_lastname, CONCAT(si.si_lastname,', ',si.si_firstname,', ',si.si_midname,' ',si.si_extname)  as si_fullname FROM tbl_gcat as gc INNER JOIN tbl_studentinfo as si on si.si_idnumber = gc.gc_idnumber WHERE (gc.gc_status = '2') AND  (si.si_lastname LIKE '%$d->value%' || si.si_midname LIKE '%$d->value%' || si.si_firstname LIKE '%$d->value%' || gc.gc_idnumber LIKE '%$d->value%' || gc.gc_regtime LIKE '%$d->value%' || gc.gc_course LIKE '%$d->value%' || si.si_email LIKE '%$d->value%') ORDER BY si.si_lastname,si.si_firstname,si.si_midname,si.si_extname,gc.gc_idnumber ASC"));
                break;

                case 'addGCATSchedule':
                    echo json_encode($post->executeWithoutRes("UPDATE tbl_gcat SET gc_examdate='$d->date', gc_examtime='$d->time' WHERE gc_idnumber='$d->idNumber'"));
                break;
                
                
                // print
                case 'printStudentSIS':
                    echo json_encode($post->printStudentSIS());
                break;

                //one time use - send mail to all registered via google forms
                case 'getMail':
                    echo json_encode($post->getMail());
                break;

                case 'sendMail':
                    echo json_encode($post->sendMail($d));
                break;

                case 'reSendMail':
                    echo json_encode($post->reSendMail($d));
                break;

                default:
                    http_response_code(400);
                    echo json_encode(array('status'=>'failed', 'message'=>'Bad request.'));
                break;
            }
        break;

        default:
            http_response_code(400);
            echo json_encode(array('status'=>'failed', 'message'=>'Bad request.'));
        break;
    }

    $db->close();
?>