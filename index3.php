<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>AreWeAtRisk?</title>
	<meta name="generator" content="TextMate http://macromates.com/">
	<meta name="author" content="Stanford Rosenthal">
	<!-- Date: 2008-07-09 -->
	<link rel="stylesheet" href="style1.css" />
	<script type="text/javascript" src="javascript.js"></script>
</head>
<body>
	<div id="container">
		<img id="logo" src="images/logo.png" />
		
		<?php if(!isset($_GET['zip'])){ ?>
			<h1>Your community may be at risk of levee failure.</h1>
			<img src="images/images.png" style="margin-left:-24px;" />
			<h2>Enter your zip code to find out if you’re safe.</h2>
			<form action="" method="get">
				<input name="zip" type="text" value="zip code" onfocus="this.className='selected';if(this.value=='zip code'){this.value='';}" onblur="if(this.value==''){this.value='zip code';this.className=''}else{this.className='blur';}" />
				<input type="submit" class="submit" value="Go" />
			</form>
		<?php }
		if($_GET[zip]){
			//Locally
			$hostname="localhost:8889";
			$db_username="root";
			$password="root";
			$database="leveestest";

			//Online
			$hostname="mysql.areweatrisk.org";
			$db_username="standaman2009";
			$password="smr1421";
			$database="awar";
			

			

			mysql_connect($hostname,$db_username,$password);
			@mysql_select_db($database) or die( "Unable to select database.  Contact stanford@stanfordrosenthal.com.");
			
				/*  
				   DEMO for using the zipcode PHP class. By: Micah Carrick 
				   Questions?  Comments?  Suggestions?  email@micahcarrick.com
			*/

				error_reporting(0);

				require_once('zipcode.class.php');      // zip code class


				// Open up a connection to the database.  The sql required to create the MySQL
				// tables and populate them with the data is in the /sql subfolder.  You can
				// upload those sql files using phpMyAdmin or a MySQL prompt.  You will have to
				// modify the below information to your database information.  

				function ZipIsInDatabase($zip){
					$query = "SELECT * FROM `zip_code` WHERE `zip_code` = '$zip'";
					$result = mysql_query($query);	
					if(mysql_num_rows($result)==0) return false;
					elseif(mysql_num_rows($result)>0) return true;
				}

				function getCityByZip($zip){
					$query = "SELECT * FROM `zip_code` WHERE `zip_code` = '$zip'";
					$result = mysql_query($query);	
					return mysql_result($result, 0, 'city');
				}

				function getStateByZip($zip){
					$query = "SELECT * FROM `zip_code` WHERE `zip_code` = '$zip'";
					$result = mysql_query($query);	
					return mysql_result($result, 0, 'state_name');
				}
				function getLeveeCountByZip($zip){
					$count = 0;
					$query = "SELECT * FROM leveerisk";
					$result = mysql_query($query);
					while($levee = mysql_fetch_array($result)){

						$z = new zipcode_class;
						$miles = $z->get_distance($zip, $levee[zip]);

						//if($miles === false) echo '<p>Error: '.$z->last_error.'</p>';
						if($miles < 100){
							$count++;
							}
					}
					return $count;	
				}
				function getLeveeCountByState($state){
					$query = "SELECT * FROM `leveerisk` WHERE `state` = '$state'";
					$result = mysql_query($query);	
					return mysql_num_rows($result);	
				}

				function printLeveeDetails($id){
					$query = "SELECT * FROM `leveerisk` WHERE `id` = '$id'";
					$result = mysql_query($query);	
					while($levee = mysql_fetch_array($result)){ ?>
						<li><ul>
							<li><h3><?php echo $levee['segment']; ?></h3></li>
							<li>Project: <?php echo $levee['project']; ?></li>
							<li>District: <?php echo $levee['district']; ?></li>
							<li>Location: <?php echo $levee['city']; ?>, <?php echo $levee['state']; ?></li>

						</ul></li>
						<?php 
					}		
				}

				function printLeveesByZip($zip){
						echo "<ol>";
						$query = "SELECT * FROM leveerisk";
						$result = mysql_query($query);
						while($levee = mysql_fetch_array($result)){

							$z = new zipcode_class;
							$miles = $z->get_distance($zip, $levee[zip]);

							//if($miles === false) echo '<p>Error: '.$z->last_error.'</p>';
							if($miles < 100){
								printLeveeDetails($levee[id]);
								}
						}
						echo "</ol>";		
				}

				function printLeveesByState($state){
						echo "<ol>";
						$query = "SELECT * FROM leveerisk WHERE `state` = '$state'";
						$result = mysql_query($query);
						while($levee = mysql_fetch_array($result)){
							printLeveeDetails($levee[id]);
						}		
						echo "</ol>";	
				}

				function printLeveesByNearbyState($state){
					echo "<ol>";
					$query = "SELECT * FROM `neighbors` WHERE `state` = '$state'";
					$result = mysql_query($query);
					while($neighbor = mysql_fetch_array($result)){
						$query2 = "SELECT * FROM leveerisk WHERE `state` = '$neighbor[neighbor]'";
						$result2 = mysql_query($query2);
						while($levee = mysql_fetch_array($result2)){
							printLeveeDetails($levee[id]);
						}
					}
					echo "</ol>";
				}

				function getLeveeCountByNearbyState($state){
					$query = "SELECT * FROM `neighbors` WHERE `state` = '$state'";
					$result = mysql_query($query);
					while($neighbor = mysql_fetch_array($result)){
						$count = $count+getLeveeCountByState($neighbor[neighbor]);

					}
					return $count;
				}
				function getAllLeveesByState($state){
					$query = "SELECT * FROM `leveesByState` WHERE `state` = '$state'";
					$result = mysql_query($query);
					return mysql_result($result, 0, 'levees');
				}

				?>
				<?php
				 if(ZipIsInDatabase($_GET[zip])==false){ ?>
					<p class="notice"><?php echo $_GET[zip]; ?> is not in our database!  An e-mail has been sent to the webmaster about this error.  <a href="http://www.areweatrisk.org">Try another zip code.</a></p>
					<?php mail('stanford@stanfordrosenthal.com','Missing Zip Code',$_GET['zip']); ?>


					<?php include('includes/footer.inc'); ?>

				<?php }else{ ?>
				<p style="float:right; font-size:12px; margin-top:30px; margin-right:30px;"><a href="http://www.areweatrisk.org"><img src="images/arrow.png" style="border-width:0; margin-right: 3px;" /></a><a href="http://www.areweatrisk.orgweatrisk.com">Try another zip</a>
				<h1 class="city"><?php echo getCityByZip($_GET[zip]); ?>, <?php echo getStateByZip($_GET[zip]); ?> is at risk!</h1>
				
				<?php //See if there are vulnerable levees in the area ?>
				<?php if(getLeveeCountByZip($_GET[zip])>0){ ?>
					<p class="alert">Listed below <?php if(getLeveeCountByZip($_GET[zip])==1) echo "is a "; else echo "are ".getLeveeCountByZip($_GET[zip]); ?> <strong>vulnerable</strong> levee<?php if(getLeveeCountByZip($_GET[zip])==1) echo ""; else echo "s"; ?> near <?php echo getCityByZip($_GET[zip]); ?>.</p>
				<?php } ?>

				<?php //See if there are more vulnerable levees in the state than by the area.  If there are, say how many. ?>
				<?php if(getLeveeCountByZip($_GET[zip]) == 0 && getLeveeCountByState(getStateByZip($_GET[zip])) != 0){ ?>
						<p class="alert">Listed below <?php if(getLeveeCountByState(getStateByZip($_GET[zip]))==1) echo "is a "; else echo "are ".getLeveeCountByState(getStateByZip($_GET[zip])); ?> <strong>vulnerable</strong> levee<?php if(getLeveeCountByState(getStateByZip($_GET[zip]))==1) echo ""; else echo "s"; ?> in <?php echo getStateByZip($_GET[zip]); ?>.</p>
				<?php } ?>

				<?php //Check Nearby States. ?>
				<?php if(getLeveeCountByZip($_GET[zip]) == 0 && getLeveeCountByState(getStateByZip($_GET[zip])) == 0){ ?>
						<?php if(getAllLeveesByState(getStateByZip($_GET[zip])) > 0){ ?><p class="notice">There <?php if(getAllLeveesByState(getStateByZip($_GET[zip]))==1) echo "is a "; else echo "are ".getAllLeveesByState(getStateByZip($_GET[zip])); ?> levee<?php if(getAllLeveesByState(getStateByZip($_GET[zip]))==1) echo ""; else echo "s"; ?> in <?php echo getStateByZip($_GET[zip]); ?>. <?php } ?>
						<?php if(getLeveeCountByNearbyState(getStateByZip($_GET[zip])) > 0){ ?><p class="alert">Listed below <?php if(getLeveeCountByNearbyState(getStateByZip($_GET[zip]))==1) echo "is a "; else echo "are ".getLeveeCountByNearbyState(getStateByZip($_GET[zip])); ?> <strong>vulnerable</strong> levee<?php if(getLeveeCountByNearbyState(getStateByZip($_GET[zip]))==1) echo ""; else echo "s"; ?> in the states neighboring <?php echo getStateByZip($_GET[zip]); ?>.</p><?php } ?>
				<?php } ?>

				<?php if(getLeveeCountByZip($_GET[zip]) == 0 && getLeveeCountByState(getStateByZip($_GET[zip])) == 0 && getLeveeCountByNearbyState(getStateByZip($_GET[zip])) == 0){ ?>
							<p class="alert">An error has occurred!  An e-mail report has been sent to our webmaster.  <a href="http://www.areweatrisk.org">Try another zip.</a></p>
							<?php mail('stanford@stanfordrosenthal.com','Error with Zip Code',$_GET['zip']); ?>
				<?php } ?>
				<a href="http://www.levees.org"><img src="images/leveesad.png" alt="Concerned?  Join Levees.Org!" style="margin-right:23px; float:right; border-width:0;"></a>
					<?php 
					if(getLeveeCountByZip($_GET[zip]) != 0){
						printLeveesByZip($_GET[zip]);
					} 
					elseif(getLeveeCountByState(getStateByZip($_GET[zip])) != 0){
						printLeveesByState(getStateByZip($_GET[zip]));
					}
					else{
						printLeveesByNearbyState(getStateByZip($_GET[zip]));
					}	
					 ?>



				<?php 
					}
				}
				 ?>
		
		<img src="images/background_bottom.png" />
	</div>
	<div id="footer">
		<p style="float:left;">A community project by <a href="http://www.levees.org" title="Demanding safe levees nationwide.">Levees.Org</a></p>
		<p style="float:right;">Developed by <a href="http://www.stanfordrosenthal.com" title="Web Design, Development, and Optimization">Stanford Rosenthal</a></p>
	</div>
	
	<script type="text/javascript">
	var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
	document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
	</script>
	<script type="text/javascript">
	var pageTracker = _gat._getTracker("UA-175112-12");
	pageTracker._initData();
	pageTracker._trackPageview();
	</script>
	
</body>
</html>
