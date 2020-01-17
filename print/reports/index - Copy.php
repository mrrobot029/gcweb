<?php 
  require '../../api/connect.php';
  $x = 0;
  $y = 0;
  $blocks =  ["BSIT1-A","BSIT1-B","BSIT1-C","BSIT1-D","BSCS1-A","BSCS1-B","BSEMC1-A","BSEMC1-B","ACT1-A","ACT1-B","BSIT2-A","BSIT2-B","BSIT2-C","BSCS2-A","BSCS2-B","ACT2-A", "BSIT3-A", "BSCS3-A", "BSIT4-A", "BSCS4-A"];
  $countenlist=[];
  $countenrolled=[];
  $totalenlist=0;
  $totalenrolled=0;
  while ($x<sizeof($blocks)) {
    $sql = "SELECT * FROM tbl_studentinfo where si_isenlisted = 1 and si_block = '$blocks[$x]'";
    $query = $conn->query($sql);
    $countenlist[$x] = mysqli_num_rows($query);
    $totalenlist = $totalenlist + $countenlist[$x];
    $x++;
  }

  $x = 0;

  while ($x<sizeof($blocks)) {
    $sql = "SELECT * FROM tbl_studentinfo where si_isenlisted = 1 and si_isenrolled = 1 and si_block = '$blocks[$x]'";
    $query = $conn->query($sql);
    $countenrolled[$x] = mysqli_num_rows($query);
    $totalenrolled = $totalenrolled + $countenrolled[$x];
    $x++;
  }
  $x = 0;

  date_default_timezone_set('ASIA/MANILA');
?>
<html> 
  <head> 
    <link rel="stylesheet" type="text/css" href="style.css">  
    <title>Reports Form</title>
    <style>
      body {
        display: flex;
        min-height: 100vh;
        flex-direction: column;
      }

      main {
        flex: 1 0 auto;
      }

      body {
      background:#ffffff;  
          color:#000000;
      } 
            @media print{
  html, body{
    height: 100%;
    margin: 0 !important;
    padding: 0 !important;
    overflow: hidden;
  }
    </style>
    <script type="text/javascript">
 window.onload = function() { window.print(); window.close(); }
</script>

  </head>
  <body>
	 <table width="200" border="0" style="text-align:center" class="txt"> 
    <tbody>
      <tr>
        <td class="header-td left">Enrollment Report</td>
        <td class="header-td center"><strong>GORDON COLLEGE</strong></td>
        <td class="header-td right">Academic Year: 2019-2020</td>
      </tr>
      <tr>
        <td class="header-td left"><strong>As of : <?php echo date("D M j Y G:i:s");?></strong></td>
        <td class="header-td center">College of Computer Studies</td>
        <td class="header-td right">Semester<strong>1st</strong> / 2nd / Midyear</td>
      </tr>
    </tbody>
  </table>
 
   
<br>

  <table class="txt table-body" border="1">
  <tbody>
    <tr class="border-thin">
      <td class="border-thin darken" width="10%" style="text-align: center">#</td>
      <td class="border-thin darken" width="30%" style="text-align: center">Blocks</td>
      <td class="border-thin darken" width="30%" style="text-align: center">Enlisted</td>  
      <td class="border-thin darken" width="30%" style="text-align: center">Enrolled</td>   
    </tr>

    <!-- Loop for data -->
    <?php 
      while ($x<sizeof($blocks)) {
        $y = $x+1;
        echo "<tr class='border-thin'>
      <td class='border-thin' width='43' style='text-align: center'>".$y."</td>
      <td class='border-thin' width='197'>".$blocks[$x]."</td>
      <td class='border-thin' width='99' style='text-align: center'>".$countenlist[$x]."</td>  
      <td class='border-thin' width='99' style='text-align: center'>".$countenrolled[$x]."</td>   
    </tr>";
    $x++;
      }

    ?>

    <!-- Total here -->
    <tr class="border-thin"> 
      <td colspan="2"class="border-thin right darken" width="197">Total: </td>
      <td class="border-thin center" width="99"><strong><?php echo $totalenlist; ?></strong></td>  
      <td class="border-thin center" width="99"><strong><?php echo $totalenrolled; ?></strong></td>   
    </tr> 

  </tbody>
</table> 

<br>
<br>

<table width="100%" border="0">
  <tbody>
    <tr>
      <td style="text-align: center" width="33.3%">&nbsp;</td>
      <td style="text-align: center" width="33.3%">&nbsp;</td>
      <td style="text-align: center" width="33.3%"><strong><input class="txt-box center"  type="textbox" style="width: 100%"></strong></td>
    </tr>
    <tr>
      <td style="text-align: center" width="33.3%">&nbsp;</td>
      <td style="text-align: center" width="33.3%">&nbsp;</td>
      <td style="text-align: center; margin-top: -5px;" width="33.3%">Facilitator's Signature and Date</td>
    </tr>
  </tbody>
</table>

  </body>
</html>