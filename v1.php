<?php if(!$_GET[zip] & !$_GET[v]){ ?>
	<script>
	function utmx_section(){}function utmx(){}
	(function(){var k='0733038302',d=document,l=d.location,c=d.cookie;function f(n){
	if(c){var i=c.indexOf(n+'=');if(i>-1){var j=c.indexOf(';',i);return c.substring(i+n.
	length+1,j<0?c.length:j)}}}var x=f('__utmx'),xx=f('__utmxx'),h=l.hash;
	d.write('<sc'+'ript src="'+
	'http'+(l.protocol=='https:'?'s://ssl':'://www')+'.google-analytics.com'
	+'/siteopt.js?v=1&utmxkey='+k+'&utmx='+(x?x:'')+'&utmxx='+(xx?xx:'')+'&utmxtime='
	+new Date().valueOf()+(h?'&utmxhash='+escape(h.substr(1)):'')+
	'" type="text/javascript" charset="utf-8"></sc'+'ript>')})();
	</script><script>utmx("url",'A/B');</script>

	
<?php } ?>
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
			<img src="images/images.png" style="margin-left:-24px; position:relative;" />
			<h2>Enter your zip code to find out if you’re safe.</h2>
			<form action="" method="get">
				<input id="zip" name="zip" type="text" value="zip code" onfocus="this.className='selected';if(this.value=='zip code'){this.value=''; this.setAttribute('maxlength','5')}" onblur="if(this.value==''){this.setAttribute('maxlength','8'); this.value='zip code';this.className=''; }else{this.className='blur';}" />
				<input type="submit" class="submit" value="Go" />
			</form>
		<?php }
		if($_GET[zip]){

			
			//Online
			$hostname="mysql.areweatrisk.org";
			$db_username="standaman2009";
			$password="smr1421";
			$database="awar";

			//Locally
			$hostname="localhost:8889";
			$db_username="root";
			$password="root";
			$database="leveestest";



			mysql_connect($hostname,$db_username,$password);
			@mysql_select_db($database) or die( "Unable to select database.  Contact stanford@stanfordrosenthal.com.");
			
			
				/*  
				   Zip Code Auto-Correction
			*/
			
			if($_POST[city]){
				$query = "SELECT * FROM zip_code WHERE city LIKE '$_POST[city]' AND state_prefix = '$_POST[state]' LIMIT 1";
				$result = mysql_query($query);
				$zip = mysql_fetch_array($result);
				
				if(mysql_num_rows($result)>0){				
					$query = "INSERT INTO zip_code VALUES('', '$_GET[zip]','$_POST[city]', '$zip[county]','$zip[state_name]','$zip[state_prefix]','$zip[area_code]','$zip[time_zone]','$zip[lat]','$zip[lon]','1')";
					$result = mysql_query($query);					

				}else{
					$bad_zip=1;
				}
			}
			
			
			
			
			
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
				
				function ZipHasBeenAdded($zip){
					$query = "SELECT * FROM `zip_code` WHERE `zip_code` = '$zip'";
					$result = mysql_query($query);	
					if(mysql_result($result, 0, 'custom')==1) return true;
					else return false;
				}
				
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
					<?php if($bad_zip==1){ ?>
					<p style="margin:50px 0; text-align:center; color:#666;">We cannot find your city in the database.  A report has been sent to our development team.  For now, <a href="http://areweatrisk.org">please try another zip code</a>.</p>
					<?php 
					$text = $_GET['zip']."<br />".$_POST['city']."<br />".$_POST['state'];
					mail('stanford@stanfordrosenthal.com','Missing Zip Code', $text); ?>
										
					<?php }else{ ?>
					<p style="margin:50px 0 0 0; text-align:center;"><?php echo $_GET[zip]; ?> is not in our database.  Please enter your city and state.</p>
					<form method="post" action="" name="custom" style="margin:40px 0 60px 0;">
						<input type="text" name="city" style="font-size:.8em; height:auto; border-width:1px; background-image:none; background-color:#fff; width:150px;" value="Type City Name" onclick="this.value='';" />
						<select name="state"> 
						<option value="" selected="selected">Select a State</option> 
						<option value="AL">Alabama</option> 
						<option value="AK">Alaska</option> 
						<option value="AZ">Arizona</option> 
						<option value="AR">Arkansas</option> 
						<option value="CA">California</option> 
						<option value="CO">Colorado</option> 
						<option value="CT">Connecticut</option> 
						<option value="DE">Delaware</option> 
						<option value="DC">District Of Columbia</option> 
						<option value="FL">Florida</option> 
						<option value="GA">Georgia</option> 
						<option value="HI">Hawaii</option> 
						<option value="ID">Idaho</option> 
						<option value="IL">Illinois</option> 
						<option value="IN">Indiana</option> 
						<option value="IA">Iowa</option> 
						<option value="KS">Kansas</option> 
						<option value="KY">Kentucky</option> 
						<option value="LA">Louisiana</option> 
						<option value="ME">Maine</option> 
						<option value="MD">Maryland</option> 
						<option value="MA">Massachusetts</option> 
						<option value="MI">Michigan</option> 
						<option value="MN">Minnesota</option> 
						<option value="MS">Mississippi</option> 
						<option value="MO">Missouri</option> 
						<option value="MT">Montana</option> 
						<option value="NE">Nebraska</option> 
						<option value="NV">Nevada</option> 
						<option value="NH">New Hampshire</option> 
						<option value="NJ">New Jersey</option> 
						<option value="NM">New Mexico</option> 
						<option value="NY">New York</option> 
						<option value="NC">North Carolina</option> 
						<option value="ND">North Dakota</option> 
						<option value="OH">Ohio</option> 
						<option value="OK">Oklahoma</option> 
						<option value="OR">Oregon</option> 
						<option value="PA">Pennsylvania</option> 
						<option value="RI">Rhode Island</option> 
						<option value="SC">South Carolina</option> 
						<option value="SD">South Dakota</option> 
						<option value="TN">Tennessee</option> 
						<option value="TX">Texas</option> 
						<option value="UT">Utah</option> 
						<option value="VT">Vermont</option> 
						<option value="VA">Virginia</option> 
						<option value="WA">Washington</option> 
						<option value="WV">West Virginia</option> 
						<option value="WI">Wisconsin</option> 
						<option value="WY">Wyoming</option>
						</select>
					<input type="submit" style="font-size:1em; width:50px; height:auto; border-width:1px; margin:5px;" value="Go">
					</form>
						
				<?php }}else{ ?>
				<p style="float:right; font-size:12px; margin-top:30px; margin-right:30px;"><a href="http://www.areweatrisk.org"><img src="images/arrow.png" style="border-width:0; margin-right: 3px;" /></a><a href="http://www.areweatrisk.org">Try another zip</a>
				<h1 class="city"><?php echo getCityByZip($_GET[zip]); ?>, <?php echo getStateByZip($_GET[zip]); ?> is at risk!</h1>
				<?php if(ZipHasBeenAdded($_GET[zip])){ ?>
				<p style="font-size:.7em; margin-left:30px; margin-top:-15px; margin-bottom:15px;">This zip code was added by our users.  If the city is incorrect, please e-mail <a href="mailto:webmaster@levees.org">webmaster@levees.org.</a></p>
				<?php } ?>
				<?php //Louisiana Only ?>
				<?php if(getStateByZip($_GET[zip]) == "Louisiana"){ ?>
					<p class="notice" style=" height:27px; padding:5px 0;">The list of vulnerable levees provided by the Corps includes river levees, not hurricane levees. <br />Thus no vulnerable levees are listed for New Orleans or Louisiana.</p>
				<?php } ?>
								
				<?php //See if there are vulnerable levees in the area ?>
				<?php if(getLeveeCountByZip($_GET[zip])>0){ ?>
					<p class="alert">Listed below <?php if(getLeveeCountByZip($_GET[zip])==1) echo "is a "; else echo "are ".getLeveeCountByZip($_GET[zip]); ?> <strong>vulnerable</strong> federal levee<?php if(getLeveeCountByZip($_GET[zip])==1) echo ""; else echo "s"; ?> near <?php echo getCityByZip($_GET[zip]); ?>.</p>
				<?php } ?>

				<?php //See if there are more vulnerable levees in the state than by the area.  If there are, say how many. ?>
				<?php if(getLeveeCountByZip($_GET[zip]) == 0 && getLeveeCountByState(getStateByZip($_GET[zip])) != 0){ ?>
						<p class="alert">Listed below <?php if(getLeveeCountByState(getStateByZip($_GET[zip]))==1) echo "is a "; else echo "are ".getLeveeCountByState(getStateByZip($_GET[zip])); ?> <strong>vulnerable</strong> federal levee<?php if(getLeveeCountByState(getStateByZip($_GET[zip]))==1) echo ""; else echo "s"; ?> in <?php echo getStateByZip($_GET[zip]); ?>.</p>
				<?php } ?>

				<?php //Check Nearby States. ?>
				<?php if(getLeveeCountByZip($_GET[zip]) == 0 && getLeveeCountByState(getStateByZip($_GET[zip])) == 0){ ?>
						<?php if(getAllLeveesByState(getStateByZip($_GET[zip])) > 0){ ?><p class="notice">There <?php if(getAllLeveesByState(getStateByZip($_GET[zip]))==1) echo "is a "; else echo "are ".getAllLeveesByState(getStateByZip($_GET[zip])); ?> levee<?php if(getAllLeveesByState(getStateByZip($_GET[zip]))==1) echo ""; else echo "s"; ?> in <?php echo getStateByZip($_GET[zip]); ?>, federal <?php if(getAllLeveesByState(getStateByZip($_GET[zip]))==1) echo "or"; else echo "and"; ?> local. <?php } ?>
						<?php if(getLeveeCountByNearbyState(getStateByZip($_GET[zip])) > 0){ ?><p class="alert">Listed below <?php if(getLeveeCountByNearbyState(getStateByZip($_GET[zip]))==1) echo "is a "; else echo "are ".getLeveeCountByNearbyState(getStateByZip($_GET[zip])); ?> <strong>vulnerable</strong> federal levee<?php if(getLeveeCountByNearbyState(getStateByZip($_GET[zip]))==1) echo ""; else echo "s"; ?> in the states neighboring <?php echo getStateByZip($_GET[zip]); ?>.</p><?php } ?>
				<?php } ?>

				<?php if(getLeveeCountByZip($_GET[zip]) == 0 && getLeveeCountByState(getStateByZip($_GET[zip])) == 0 && getLeveeCountByNearbyState(getStateByZip($_GET[zip])) == 0){ ?>
							<p class="alert">An error has occurred!  An e-mail report has been sent to our webmaster.  <a href="http://www.areweatrisk.org">Try another zip.</a></p>
							<?php mail('stanford@stanfordrosenthal.com','Error with Zip Code',$_GET['zip']); ?>
				<?php } ?>
				<a href="http://www.levees.org"><img src="images/leveesad.png" alt="Concerned?  Join Levees.Org!" style="margin-right:23px; float:right; border-width:0; margin-bottom:10px;"></a>
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




				<div class="facts">
							<h4>Where is this data coming from?</h4>
							<ul>
								<li>There are 146 levees that the United States Army Corps of Engineers has deemed "vulnerable."</li>
							  	<li>Under pressure from USA Today, the Corps has released the names and locations of 122 of these levees.</li>
								<li>24 vulnerable levees remain unaccounted for.</li>
								<li>None of these vulnerable levees are located in Louisiana, which suffered catastrophic levee failures in 2005.</li>
							</ul>
				</div>

							<p class="leveesfooter">New Orleans thought it was safe.  Do you?  <a href="http://www.levees.org">Visit Levees.Org</a> now to protect your family.</p>
							
							<?php 
								}
							}
							 ?>
							<?php if($_GET[zip]){ ?>							
							
							
											<?php } ?>
		<img src="images/background_bottom.png" />
	</div>
	<div id="footer">
		<p style="float:left;">A public service by <a href="http://www.levees.org" title="Demanding safe levees nationwide.">Levees.Org</a></p>
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
	
	<?php if(!ereg('zip', curPageURL())){ ?>
		<script>
		if(typeof(urchinTracker)!='function')document.write('<sc'+'ript src="'+
		'http'+(document.location.protocol=='https:'?'s://ssl':'://www')+
		'.google-analytics.com/urchin.js'+'"></sc'+'ript>')
		</script>
		<script>
		_uacct = 'UA-4574581-2';
		urchinTracker("/0733038302/test");
		</script>
	<?php } 
	
	function curPageURL(){
	 $pageURL = 'http';
	 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	 $pageURL .= "://";
	 if ($_SERVER["SERVER_PORT"] != "80") {
	  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	 } else {
	  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	 }
	 return $pageURL;
	}
	
	 if(ereg('zip', curPageURL())){ ?>
		<script>
		if(typeof(urchinTracker)!='function')document.write('<sc'+'ript src="'+
		'http'+(document.location.protocol=='https:'?'s://ssl':'://www')+
		'.google-analytics.com/urchin.js'+'"></sc'+'ript>')
		</script>
		<script>
		_uacct = 'UA-4574581-2';
		urchinTracker("/0733038302/goal");
		</script>
	<?php } ?>
</body>
</html>
