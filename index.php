<?php
    header('Access-Control-Allow-Origin: *');
    // header('Access-Control-Allow-Methods: PUT, POST, PATCH, OPTIONS, GET');
    header('Content-Type: application/json');

    include_once './config/database.php';
    include_once './model/post.php';
    include_once './model/auth.php';

    $database = new Database();
    $db = $database->connect();
    $post = new Post($db);
    $auth = new Auth($db);
    $data = array();

    $req = explode('/',rtrim($_REQUEST['request'],'/'));

    switch ($_SERVER['REQUEST_METHOD']) {

        case 'POST':

            switch ($req[0]) {



                    // authentication related cases
                    case 'register':
                        $d = json_decode( base64_decode( file_get_contents('php://input')));
                        echo json_encode($auth->registeruser($d));
                    break;

                    case 'loginAdmin':
                        $d = json_decode( base64_decode( file_get_contents('php://input')));
                        echo json_encode($auth->loginAdmin($d));
                    break;

                    case 'checkAdmin':
                        $d = json_decode( base64_decode( file_get_contents('php://input')));
                        echo json_encode($auth->checkAdmin($d));
                    break;

                    case 'loginFaculty':
                        $d = json_decode( base64_decode( file_get_contents('php://input')));
                        echo json_encode($auth->loginFaculty($d));
                    break;

                    case 'checkFaculty':
                        $d = json_decode( base64_decode( file_get_contents('php://input')));
                        echo json_encode($auth->checkFaculty($d));
                    break;

                    case 'loginStudent':
                        $d = json_decode( base64_decode( file_get_contents('php://input')));
                        echo json_encode($auth->loginStudent($d));
                    break;

                    case 'checkStudent':
                        $d = json_decode( base64_decode( file_get_contents('php://input')));
                        echo json_encode($auth->checkStudent($d));
                    break;
                


                    // admin/facultymembers
                    case 'getFaculty':
                        $d = json_decode( base64_decode( file_get_contents('php://input')));
                        echo json_encode($post->getFaculty($d)); 
                    break;

                    case 'delFaculty':
                        $d = json_decode( base64_decode( file_get_contents('php://input')));
                        echo json_encode($post->delFaculty($d)); 
                    break;

                    case 'addFaculty':
                        $d = json_decode( base64_decode( file_get_contents('php://input')));
                        echo json_encode($auth->addFaculty($d)); 
                    break;

                    case 'uploadFaculty':
                        echo json_encode($auth->uploadFaculty()); 
                    break;

                    
                
                    // admin/subjectprospectus
                    case 'getProspectus':
                        $d = json_decode( base64_decode( file_get_contents('php://input')));
                        echo json_encode($post->getProspectus($d)); 
                    break;
                    
                    case 'uploadProspectus':
                        echo json_encode($post->uploadProspectus()); 
                    break;
                    
                    case 'updateProspectus':
                        $d = json_decode( base64_decode( file_get_contents('php://input')));
                        echo json_encode($post->updateProspectus($d)); 
                    break;

                    case 'addProspectus':
                        $d = json_decode( base64_decode( file_get_contents('php://input')));
                        echo json_encode($post->addProspectus($d)); 
                    break;

                    case 'delProspectus':
                        $d = json_decode( base64_decode( file_get_contents('php://input')));
                        echo json_encode($post->delProspectus($d)); 
                    break;
                    
                            // options in selects in admin/subjectprospectus
                            case 'getProspectusCourse':
                                $d = json_decode( base64_decode( file_get_contents('php://input')));
                                echo json_encode($post->getProspectusCourse($d)); 
                            break;
                            case 'getProspectusCy':
                                $d = json_decode( base64_decode( file_get_contents('php://input')));
                                echo json_encode($post->getProspectusCy($d)); 
                            break;



                    // admin/classes
                    case 'getClass':
                        $d = json_decode( base64_decode( file_get_contents('php://input')));
                        echo json_encode($post->getClass($d)); 
                    break;

                    case 'addClass':
                        $d = json_decode( base64_decode( file_get_contents('php://input')));
                        echo json_encode($post->addClass($d)); 
                    break;

                    case 'delClass':
                        $d = json_decode( base64_decode( file_get_contents('php://input')));
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
                                $d = json_decode( base64_decode( file_get_contents('php://input')));
                                echo json_encode($post->getSem($d)); 
                            break;

                            case 'getBlocks':
                                $d = json_decode( base64_decode( file_get_contents('php://input')));
                                echo json_encode($post->getBlocks($d)); 
                            break;

                            case 'getDept':
                                $d = json_decode( base64_decode( file_get_contents('php://input')));
                                echo json_encode($post->getDept($d)); 
                            break;





                    // all funtions for faculty pages


                    // faculty/myclasses
                    case 'getMyClass':
                        $d = json_decode( base64_decode( file_get_contents('php://input')));
                        echo json_encode($post->getMyClass($d));
                    break;

                    case 'updateIsNormal':
                        $d = json_decode( base64_decode( file_get_contents('php://input')));
                        echo json_encode($post->updateIsNormal($d));
                    break;

                    // faculty/students
                    case 'getFacStudents':
                        $d = json_decode( base64_decode( file_get_contents('php://input')));
                        echo json_encode($post->getFacStudents($d));
                    break;

                    case 'getAdminStudents':
                        $d = json_decode( base64_decode( file_get_contents('php://input')));
                        echo json_encode($post->getAdminStudents($d));
                    break;

                    case 'getCoordinatorStudents':
                        $d = json_decode( base64_decode( file_get_contents('php://input')));
                        echo json_encode($post->getCoordinatorStudents($d));
                    break;

                    // faculty/profile
                    case 'updateDP':
                        $d = json_decode( base64_decode( file_get_contents('php://input')));
                        echo json_encode($post->updateDP($d));
                    break;

                    case 'updatePassword':
                        $d = json_decode( base64_decode( file_get_contents('php://input')));
                        echo json_encode($auth->updatePassword($d));
                    break;









                case 'getclass':
                    $d = json_decode( base64_decode( file_get_contents('php://input')));
                    echo json_encode($post->myclass($d));
                break;

                case 'getclassf':
                    $d = json_decode( base64_decode( file_get_contents('php://input')));
                    echo json_encode($post->myclassf($d));
                break;

                case 'getfiles':
                    $d = json_decode( base64_decode( file_get_contents('php://input')));
                    echo json_encode($post->classFiles($d));
                break;


                case 'coordstudents':
                    $d = json_decode( base64_decode( file_get_contents('php://input')));
                    echo json_encode($post->students2($d));
                break;

                case 'allstudents':
                    $d = json_decode( base64_decode( file_get_contents('php://input')));
                    echo json_encode($post->getStudents($d));
                break;

                case 'reenlist':
                    $d = json_decode( base64_decode( file_get_contents('php://input')));
                    echo json_encode($post->reenlist($d));
                break;

                case 'getClassStudents':
                    $d = json_decode( base64_decode( file_get_contents('php://input')));
                    echo json_encode($post->getClassStudents($d));
                break;

                case 'getProspectusCopy':
                    $d = json_decode( base64_decode( file_get_contents('php://input')));
                    echo json_encode($post->getProspectusCopy($d));
                break;

                case 'getProspectusCopyF':
                    $d = json_decode( base64_decode( file_get_contents('php://input')));
                    echo json_encode($post->getProspectusCopyF($d));
                break;

                case 'getStudentSchedule':
                    $d = json_decode( base64_decode( file_get_contents('php://input')));
                    echo json_encode($post->getStudentSchedule($d));
                break;

                case 'getGrades':
                    $d = json_decode( base64_decode( file_get_contents('php://input')));
                    echo json_encode($post->getGrades($d));
                break;

                // data updating cases
                case 'updateStudentProfile':
                    $d = json_decode( base64_decode( file_get_contents('php://input')));
                    echo json_encode($post->updateStudentProfile($d));
                break;

                case 'getCourses':
                    $d = json_decode( base64_decode( file_get_contents('php://input')));
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
                    $d = json_decode( base64_decode( file_get_contents('php://input')));
                    echo json_encode($post->getProvinces($d));
                break;

                case 'getCities':
                    $d = json_decode( base64_decode( file_get_contents('php://input')));
                    echo json_encode($post->getCities($d));
                break;

                case 'getCity':
                    $d = json_decode( base64_decode( file_get_contents('php://input')));
                    echo json_encode($post->getCity($d));
                break;
                
                // faculty/profiley
                case 'getStudent':
                    $d = json_decode( base64_decode( file_get_contents('php://input')));
                    echo json_encode($post->getStudent($d));
                break;

                case 'getActiveClasses':
                    $d = json_decode( base64_decode( file_get_contents('php://input')));
                    echo json_encode($post->getActiveClasses($d));
                break;

                case 'getSettings':
                    $d = json_decode( base64_decode( file_get_contents('php://input')));
                    echo json_encode($post->getSettings($d));
                break;

                case 'updateSettings':
                    $d = json_decode( base64_decode( file_get_contents('php://input')));
                    echo json_encode($post->updateSettings($d));
                break;

                case 'getEnrolledClasses':
                    $d = json_decode( base64_decode( file_get_contents('php://input')));
                    echo json_encode($post->getEnrolledClasses($d));
                break;

                case 'enrollSingleClass':
                    $d = json_decode( base64_decode( file_get_contents('php://input')));
                    echo json_encode($post->enrollSingleClass($d));
                break;

                case 'removeEnrolledSubject':
                    $d = json_decode( base64_decode( file_get_contents('php://input')));
                    echo json_encode($post->removeEnrolledSubject($d));
                break;

                case 'getCourseBlocks':
                    $d = json_decode( base64_decode( file_get_contents('php://input')));
                    echo json_encode($post->getCourseBlocks($d));
                break;

                case 'enrollByBlock':
                    $d = json_decode( base64_decode( file_get_contents('php://input')));
                    echo json_encode($post->enrollByBlock($d));
                break;

                case 'insertNewStudent':
                    $d = json_decode( base64_decode( file_get_contents('php://input')));
                    echo json_encode($post->insertNewStudent($d));
                break;
                
                case 'updateStudentInfo':
                    $d = json_decode( base64_decode( file_get_contents('php://input')));
                    echo json_encode($post->updateStudentInfo($d));
                break;

                case 'updateId':
                    $d = json_decode( base64_decode( file_get_contents('php://input')));
                    echo json_encode($post->updateId($d));
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