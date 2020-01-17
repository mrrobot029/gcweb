<?php
require_once 'connect.php';
$data= "<select id='printblock' onchange='selectblockprint()'>
                <option value=''>~SELECT BLOCK~</option>"
;

if(isset($_GET['department'])){
    $dept=$_GET['department'];
$sqlblocks = "SELECT cl_block FROM tbl_classes WHERE cl_department = '$dept' GROUP BY cl_block ORDER BY cl_recno ASC";
  $queryblocks = mysqli_query($conn,$sqlblocks);
while($resblocks = mysqli_fetch_assoc($queryblocks)){
  $data.="<option value=".$resblocks['cl_block'].">".$resblocks['cl_block']."</option>";
}
$data.="</select>";
}


echo $data;
?>