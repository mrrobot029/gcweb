<?php
require_once "connect.php";
require_once "Classes/PHPExcel.php";

$valid = array('ssuccess' => false, 'fsuccess' => false , 'scount' => 0 , 'fcount' => 0);

$tmpfname = $_FILES['class']['name'];
$url = './'.$tmpfname;

if(is_uploaded_file($_FILES['class']['tmp_name']) && isset($_POST['department'])) {
    if(move_uploaded_file($_FILES['class']['tmp_name'], $url)) {

        $excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
        $excelObj = $excelReader->load($tmpfname);
        $worksheet = $excelObj->getSheet(0);
        $lastRow = $worksheet->getHighestRow();
         $checker = $worksheet->getCell('BH1')->getValue();
        
        if ($checker=='class') {
            $data = [];
            for ($row = 1; $row <= $lastRow; $row++) {
                if($row != 1){

                    $cl_block = $worksheet->getCell('A'.$row)->getValue();
                    $cl_code = $worksheet->getCell('B'.$row)->getValue();
                    $cl_sucode = $worksheet->getCell('C'.$row)->getValue();
                    $cl_subdesc = $worksheet->getCell('D'.$row)->getValue();
                    $cl_lecunit =  $worksheet->getCell('E'.$row)->getValue();
                    $cl_labunit =  $worksheet->getCell('F'.$row)->getValue();
                    $cl_rleunit =  $worksheet->getCell('G'.$row)->getValue();
                    $cl_day = $worksheet->getCell('H'.$row)->getValue();
                    $cl_stime = PHPExcel_Style_NumberFormat::toFormattedString($worksheet->getCell('I'.$row)->getCalculatedValue(), 'hh:mm AM/PM');
                    $cl_etime = PHPExcel_Style_NumberFormat::toFormattedString($worksheet->getCell('J'.$row)->getCalculatedValue(), 'hh:mm AM/PM');
                    $cl_room = $worksheet->getCell('K'.$row)->getValue();
                    $yearlevel = $worksheet->getCell('L'.$row)->getValue();
                    $course = $worksheet->getCell('M'.$row)->getValue();
                    $cl_department = $_POST['department'];


                    $arrayblock = explode("-", $cl_block);
                    $arrcourse = explode("-", $course);


             
                    $cl_subdesc = str_replace("'", "''", $cl_subdesc);
                    
                    $cl_block = strtoupper($arrcourse[0]) ." ".$yearlevel.$arrayblock[1];


                    $sql = "INSERT INTO tbl_classes(
                    cl_code,
                    cl_sucode,
                    cl_subdesc,
                    cl_lecunit,
                    cl_labunit,
                    cl_rleunit,
                    cl_room,
                    cl_stime,
                    cl_etime,
                    cl_day,
                    cl_block,
                    cl_department,
                    cl_isactive)
                    VALUES(
                    '$cl_code',
                    '$cl_sucode',
                    '$cl_subdesc',
                    '$cl_lecunit',
                    '$cl_labunit',
                    '$cl_rleunit',
                    '$cl_room',
                    '$cl_stime',
                    '$cl_etime',
                    '$cl_day',
                    '$cl_block',
                    '$cl_department',
                    1)";

                    if($cl_code != ""){
                        if(mysqli_query($conn,$sql)){
                          $valid['ssuccess'] = true;
                          $valid['scount'] = $valid['scount'] + 1;
                        }else{
                          $valid['fsuccess'] = false;
                          $valid['fcount'] = $valid['fcount'] + 1;
                        }
                    }
                }
            }
        }else{
            $valid['success'] = false;
            $valid['message'] = "Invalid file";
        }

            
        

        unlink($url);

    }

}
else{
                  $valid['success'] = false;
                  $valid['message'] = "No file upload";
                }


echo json_encode($valid);