<?php 
  require '../../api/connect.php';
	date_default_timezone_set('ASIA/MANILA');
  $block = $_GET['block'];
  $dept = $_GET['dept'];
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
   /*         @media print{
  html, body{
    height: 100%;
    margin: 0 !important;
    padding: 0 !important;
    overflow: hidden;
  }*/
    </style>
    <script type="text/javascript">
 window.onload = function() { window.print(); window.close(); }
</script>

  </head>
  <body>

<div class="header"> 
<!--   <div class="header-left">
    <img src="img/<?php echo $dept;?>.png" width="50px" height="auto">
  </div> -->
  <div class="center" style="font-weight: bold">
    GORDON COLLEGE - COLLEGE OF COMPUTER STUDIES<br>
    PARENTS' MEETING: ORIENTATION AND CONSULTATION ABOUT UAQTEA / UNIFAST<br>
    JULY 8 -11, 2019 // GC FUNCTION ALL
    <p></p>
  </div>
</div>

 
   
<br>

  <table class="txt table-body" border="1">
  	<thead>
  		<tr class="border-thin">
      <td class="border-thin darken" width="2%" style="text-align: center">#</td>
      <td class="border-thin darken" width="28%" style="text-align: center">Name of Student</td>
      <td class="border-thin darken" width="10%" style="text-align: center">Block</td>
      <td class="border-thin darken" width="25%" style="text-align: center">Parents/Guardian</td> 
      <td class="border-thin darken" width="15%" style="text-align: center">Contact Number</td>
      <td class="border-thin darken" width="20%" style="text-align: center">Signature</td>
    </tr>
  	</thead>
  <tbody>
    

    <!-- Loop for data -->
    <?php 
    	$ctr = 0;
    	$sql = "SELECT * FROM tbl_studentinfo WHERE si_block='$block' ORDER BY si_lastname";
    	$res = $conn->query($sql);
    	while($data=mysqli_fetch_assoc($res)){
    		$ctr=$ctr+1;
    		echo "<tr class='border-thin'>
		      <td class='border-thin' style='text-align: center'>".$ctr."</td>
		      <td class='border-thin' style='padding-left:10px'>".$data['si_lastname'].", ".$data['si_firstname']." ".$data['si_extname']." ".substr($data['si_midname'],0,1).".</td>
		      <td class='border-thin' style='text-align: center'>".$data['si_block']."</td>
		      <td class='border-thin' style='text-align: center'></td>  
		      <td class='border-thin' style='text-align: center'></td>   
		      <td class='border-thin' style='text-align: center'></td>   
		    </tr>";
    	}

    ?>

    <!-- Total here -->
    <tr class="border-thin"> 
      <td colspan="5"class="border-thin right darken" width="197">Total Number of Students: </td>
      <td class="border-thin center" width="99"><strong><?php echo $ctr; ?></strong></td>  
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