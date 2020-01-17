<?php 
  require '../../api/connect.php';
  $dept = $_GET['department'];
  $x = 0;
  $y = 0;
  $blocks = [];
  $sqlblocks = "SELECT cl_block FROM tbl_classes WHERE cl_department = '$dept' GROUP BY cl_block ORDER BY cl_recno ASC";
  $queryblocks = mysqli_query($conn,$sqlblocks);
while($resblocks = mysqli_fetch_assoc($queryblocks)){
  array_push($blocks, $resblocks['cl_block']);
}
  // $blocks =  ["BSIT1-A","BSIT1-B","BSIT1-C","BSIT1-D","BSCS1-A","BSCS1-B","BSEMC1-A","BSEMC1-B","ACT1-A","ACT1-B","BSIT2-A","BSIT2-B","BSIT2-C","BSCS2-A","BSCS2-B","ACT2-A", "BSIT3-A", "BSCS3-A", "BSIT4-A", "BSCS4-A"];
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
    <link rel="stylesheet" type="text/css" href="newcss.css">  
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
 window.onload = function() { window.print(); }
</script>

  </head>
  <body>
<div class="left"> 
  <img src="img/<?php echo $dept; ?>.png" style="float: left !important" width="50px" height="auto">
  <h3 class="margin-2" style="margin-top: 3px;">Gordon College</h3>
  <h5 class="margin-1"><?php 
  		switch($dept){
          case 'CCS': echo "College of Computer Studies";
                      break;
          case 'CBA': echo "College of Business and Accountancy";
                      break;
          case 'CEAS': echo "College of Education, Arts and Sciences";
                      break;
          case 'CAHS': echo "College of Allied Health Studies";
                      break;
          case 'CHTM': echo "College of Hospitality and Tourism Management";
                      break;
          default: echo "";
        } ?></h5>
</div> 

<div>
  <p><strong>Enlistment Report as of:</strong> <?php echo date("D M j Y G:i:s");?></p>
  <p class="margin-1"><strong>Academic Year</strong>: 2019-2020</p>
  <p class="margin-1"><strong>Semester</strong>: 1st / 2nd / Midyear</p>
</div>


  <table class="txt table-body" border="1">
  	<thead>
	  	<tr class="border-thin">
	      <th class="border-thin darken" width="10%" style="text-align: center">#</th>
	      <th class="border-thin darken" width="30%" style="text-align: center">Blocks</th>
	      <th class="border-thin darken" width="30%" style="text-align: center">Enlisted</th>  
	      <th class="border-thin darken" width="30%" style="text-align: center">Enrolled</th>   
	    </tr>
  	</thead>
  <tbody>
    

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