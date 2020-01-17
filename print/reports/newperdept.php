<?php 
  require_once('../../api/connect.php');
	date_default_timezone_set('ASIA/MANILA');

  $info[]=[];
  


  function details($dept, $conn){
    $title = "";
    switch($dept){
      case 'CAHS':
        $title = "College of Allied Health Studies";
      break;
      case 'CBA':
        $title = "College of Business and Accountancy";
      break;
      case 'CCS':
        $title = "College of Computer Studies";
      break;
      case 'CEAS':
        $title = "College of Education, Arts and Sciences";
      break;
      case 'CHTM':
        $title = "College of Hospitality and Tourism Management";
      break;
    }
    $details[] = array(
      array("First Year", 0, 0),
      array("Second Year", 0, 0),
      array("Third Year", 0, 0),
      array("Fourth Year", 0, 0),
    );
    $enrolled=0;
    $enlisted=0;

    $sql = "SELECT * FROM tbl_studentinfo WHERE si_department='$dept'";
    $res = $conn->query($sql);
    while($data=mysqli_fetch_assoc($res)){
      $details[0][intval($data['si_yrlevel'])-1][1]++;
      $enrolled++;
      if($data['si_isenrolled']==1){
        $details[0][intval($data['si_yrlevel'])-1][2]++;
        $enlisted++;
      }
    }
    $str = '<h4 style="text-transform: uppercase; font-size: 12px;">'.$title.'</h4>';
    $str.= '<table class="txt table-body" style="width:100% !important; font-size: 11px" border="1";>';
    $str.= '<thead>';
    $str.= '<th>Year Level</th>';
    $str.= '<th>Enlisted</th>';
    $str.= '<th>Enrolled</th>';
    $str.= '</thead>';
    $str.= '<tbody>';
    
    for($i=0; $i<4; $i++){
      $str.= '<tr class="border-thin">';
      $str.= '<td>'.$details[0][$i][0].'</td>';
      $str.= '<td style="text-align: center">'.$details[0][$i][1].'</td>';
      $str.= '<td style="text-align: center">'.$details[0][$i][2].'</td>';
      $str.= '</tr>';
    }
    $str.= '<tr class="border-thin" style="font-weight: bold">';
    $str.= '<td>Total</td>';
    $str.= '<td style="text-align: center">'.$enrolled.'</td>';
    $str.= '<td style="text-align: center">'.$enlisted.'</td>';
    $str.= '</tr>';
    $str.= '</tbody>';
    $str.= '</table>';

    return $str;
  }
?>

<!DOCTYPE html> 
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="perdeptreport.css"> 
    <title>Per Department Report</title>
  </head>

  <body class="txt">
    <div class="header"> 
      <!-- <div class="header-left">
        <img src="img/logo_gc.png" width="50px" height="auto">
      </div> -->
      <div class="center">
        <strong>Gordon College</strong><br>
        <strong>Enlistment Report as of:</strong> <?php echo date("D M j Y G:i:s");?><br>
        <strong>Academic Year</strong>: 2019-2020<br>
        <strong>Semester</strong>: 1st <br><!-- / 2nd / Midyear -->
       <!-- <h5 class="margin-1">College of Computer Studies</h5> -->
      </div>
    </div><br>

    <?php echo details('CAHS', $conn) ?>
    <?php echo details('CBA', $conn) ?>
    <?php echo details('CCS', $conn) ?>
    <?php echo details('CEAS', $conn) ?>
    <?php echo details('CHTM', $conn) ?>

    <script type="text/javascript">
      window.onload = function() { window.print(); window.close(); }
    </script>
  </body>
</html>