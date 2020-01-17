<?php 
  require '../../api/connect.php';
	date_default_timezone_set('ASIA/MANILA');
	$ccsenrolled = 0;
	$ccsenlisted = 0;
	$ceasenrolled = 0;
	$ceasenlisted = 0;
	$cahsenrolled = 0;
	$cahsenlisted = 0;
	$cbaenrolled = 0;
	$cbaenlisted = 0;
	$chtmenrolled = 0;
	$chtmenlisted = 0;
  ?>
<html> 
  <head>   
    <link rel="stylesheet" type="text/css" href="newer.css">  
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

<div class="header"> 
  <div class="header-left">
    <img src="img/logo_gc.png" width="50px" height="auto">
  </div>
  <div class="header-right">
    <h3>Gordon College</h3>
    <!-- <h5 class="margin-1">College of Computer Studies</h5> -->
  </div>
</div><br>
<div class="margin-3">
  <p><strong>Enlistment Report as of:</strong> <?php echo date("D M j Y G:i:s");?></p>
  <p class="margin-2"><strong>Academic Year</strong>: 2019-2020</p>
  <p class="margin-2"><strong>Semester</strong>: 1st <!-- / 2nd / Midyear --></p>
</div>
 
   
<br>

  <table class="txt table-body" style="width:100% !important" border="1">
  <thead>
  	<tr class="border-thin">
      <th class="border-thin darken" style="text-align: center; width: 10%">#</th>
      <th class="border-thin darken" style="text-align: center; width: 40% !important">Department</th>
      <th class="border-thin darken" style="text-align: center; width: 15%">Enlisted</th>  
      <th class="border-thin darken" style="text-align: center; width: 15%">Enrolled</th>
      <th class="border-thin darken" style="text-align: center; width: 10%">Remarks</th>
    </tr>
  </thead>
  <tbody>

    <!-- Loop for data -->
    <?php  
    	$ctr = 0; $ctrenrolled = 0;
    	//CAHS counter
    	$sql = "SELECT * FROM tbl_studentinfo WHERE si_department='CAHS'";
    	$res = $conn->query($sql);
    	while($data=mysqli_fetch_assoc($res)){
    		if($data['si_isenrolled']==1){
    			$cahsenrolled++;
    			$ctrenrolled++;
    		}
    		$cahsenlisted++;
    		$ctr=$ctr+1;
    	}

    	//CBA counter
    	$sql = "SELECT * FROM tbl_studentinfo WHERE si_department='CBA'";
    	$res = $conn->query($sql);
    	while($data=mysqli_fetch_assoc($res)){
    		if($data['si_isenrolled']==1){
    			$cbaenrolled++;
    			$ctrenrolled++;
    		}
    		$cbaenlisted++;
    		$ctr=$ctr+1;
    	}

    	//CAHS counter
    	$sql = "SELECT * FROM tbl_studentinfo WHERE si_department='ccs'";
    	$res = $conn->query($sql);
    	while($data=mysqli_fetch_assoc($res)){
    		if($data['si_isenrolled']==1){
    			$ccsenrolled++;
    			$ctrenrolled++;
    		}
    		$ccsenlisted++;
    		$ctr=$ctr+1;
    	}
    	$sql = "SELECT * FROM tbl_studentinfo WHERE si_department='ceas'";
    	$res = $conn->query($sql);
    	while($data=mysqli_fetch_assoc($res)){
    		if($data['si_isenrolled']==1){
    			$ceasenrolled++;
    			$ctrenrolled++;
    		}
    		$ceasenlisted++;
    		$ctr=$ctr+1;
    	}
    	$sql = "SELECT * FROM tbl_studentinfo WHERE si_department='chtm'";
    	$res = $conn->query($sql);
    	while($data=mysqli_fetch_assoc($res)){
    		if($data['si_isenrolled']==1){
    			$chtmenrolled++;
    			$ctrenrolled++;
    		}
    		$chtmenlisted++;
    		$ctr=$ctr+1;
    	}

  ?>

    <tr class="border-thin">
    	<td style="text-align: center">1</td>
    	<td>College of Allied Health Studies</td>
    	<td style="text-align: center"><?php echo $cahsenlisted ?></td>
    	<td style="text-align: center"><?php echo $cahsenrolled ?></td>
    	<td style="text-align: center"></td>
    </tr>
    
    <tr class="border-thin">
    	<td style="text-align: center">2</td>
    	<td>College of Business and Accountancy</td>
    	<td style="text-align: center"><?php echo $cbaenlisted ?></td>
    	<td style="text-align: center"><?php echo $cbaenrolled ?></td>
    	<td style="text-align: center"></td>
    </tr>
    <tr class="border-thin">
    	<td style="text-align: center">3</td>
    	<td>College of Computer Studies</td>
    	<td style="text-align: center"><?php echo $ccsenlisted ?></td>
    	<td style="text-align: center"><?php echo $ccsenrolled ?></td>
    	<td style="text-align: center"></td>
    </tr>
    <tr class="border-thin">
    	<td style="text-align: center">4</td>
    	<td>College of Education, Arts, and Sciences</td>
    	<td style="text-align: center"><?php echo $ceasenlisted ?></td>
    	<td style="text-align: center"><?php echo $ceasenrolled ?></td>
    	<td style="text-align: center"></td>
    </tr>
    <tr class="border-thin">
    	<td style="text-align: center">5</td>
    	<td>College of Hospitality and Tourism Management</td>
    	<td style="text-align: center"><?php echo $chtmenlisted ?></td>
    	<td style="text-align: center"><?php echo $chtmenrolled ?></td>
    	<td style="text-align: center"></td>
    </tr>

    <!-- Total here -->
    <tr class="border-thin"> 
      <td colspan="2" class="border-thin right darken">Total Number of Students: </td>
      <td class="border-thin center"><strong><?php echo $ctr; ?></strong></td>  
      <td class="border-thin center"><strong><?php echo $ctrenrolled; ?></strong></td> 
      <td></td> 
    </tr> 

  </tbody>
</table> 
<br><br>
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