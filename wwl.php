<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Is your home at risk of a levee failure?</title>
</head>

<body style="text-align:center; background-color:#eee; font-family:arial, serif;">
	<div style="margin:0 auto; width:650px; background-color:#fff; border:1px solid #000; text-align:left; padding:10px;">
		
<?php 
		if (isset($_POST['tellsubmit'])) {
			doTell($notice, $adminEmail, ", watch this video at AreWeAtRisk.org!", $websiteName, $defaultMessageClose, $link);
		}elseif (isset($_GET['thankyou'])){
			?><p style="text-align:center;">Thank you for joining Levees.Org!</p> <?php
		}
		?>
<h1 style="text-align:center;">Is your home at risk of a levee failure?</h1>
<h2 style="text-align:center;">You may be surprised by the answer.</h2>
<h3 style="text-align:center;">43% of all Americans live in areas protected by levees.</h3>
<div style="text-align:center; ">
<script src="http://go.webvideoplayer.com/js/jkA2df04lJEtwPp3N85e13175" type="text/javascript"></script>
</div>

<?php 
//PHP Script: Tell A Friend : Copyright Christian-web-masters.com
//You can get free and friendly help with this script at the 
//Christian Web Masters forums: www.christian-web-masters.com/forums

//Copy and paste this code into your PHP page, where you want 
//the Tell a Friend Form to appear.
//Note if you do not know PHP, just make a regular HTML page
//and save it as a .php file.  

//You can also save this as a .php file such as "tell.php"
//and include it in the php file(s) of your choice
//using: include("tell.php");

//Then just link to this page from wherever you want.

//You will need to configure the script.  
//It's easy, just use a text editor.
//Follow the instructions in the "Begin Configure" section below.

//Note: This script contains a link to our site.  
//We'd like it if you left it, but you have permission to remove it. :)

//This script uses the new $_POST and $_SERVER['HTTP_REFERRER']
//If you have an issue with an older version of PHP, you can get help at:

// ************Begin Configure***************

//Put your website name between the quotes below:
$websiteName = "Are We At Risk?";

//Put your website address between the quotes below:
$websiteAddress = "www.AreWeAtRisk.org";


// If you have a privacy policy, put in the link title: 
$privacyPolicyLinkText = "";


// Put in the "" the url to you your privacy policy, if you have one,
$privacyPolicyLinkURL = "";


// Change the 0 to a 1 below, 
//if you want to recieve a notice when people are refered:
$notice = 0;


//Put your email address in the " " if you changed the notice to 1
$adminEmail = "admin@websiteAddress.com";


//Put the subject line text you want the email to read in the  "":
$subject = "Watch this video at AreWeAtRisk.org!";

// Put your default message intro text in the first set of quotes below
//This is what the people who are referred will see
//along with any personal message entered by the referer.
$defaultMessageIntro = "You are invited to watch: " . "\n" ; 


//Put in the "" your default close (this will be at the end of the message):
$defaultMessageClose = "As a public service, Levees.Org has created a searchable data base so citizens anywhere can find out if they're at risk of flooding by going to www.AreWeAtRisk.org and entering their zip code.";


// ************End Configure****************

// Set the link that will be in intro/invite and used to send the referer back
if (isset($_POST['link'])) {
$link = $_POST['link'];
}
else {
	if (empty($_SERVER['HTTP_REFERER'])) { 
     $link = 'http://' . $websiteAddress;
 	} else { 
     $link = $_SERVER['HTTP_REFERER']; 
	 
 	} 
}
// Add the link to the intro
$defaultMessageIntro = $defaultMessageIntro . $link . "\n";

//Adds a space infront of the subject line (to add a name latter
$subject = ' ' . $subject;
?>
<?php
function doTellForm ($privacyPolicyLinkText, $privacyPolicyLinkURL, $defaultMessageIntro, $link) 
{
// If you understand HTML, you can make changes to the form layout below.
// I just copy the HTML from the "<form" opening to the "</form>"
// Put it into Dreamweaver (WYSIWYG Editor), work on it and put it back.
$theForm = <<<EOD
<form name="tellForm" method="post" action="">
  <table width="500px" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr> 
      <td width="50%"><div align="center">Your name:</div></td>
      <td width="50%"><div align="center">Your email:</div></td>
    </tr>
    <tr> 
      <td> <div align="center"> 
          <input name="your_name" type="text" id="your_name">
        </div></td>
      <td> <div align="center"> 
          <input name="your_email" type="text" id="your_email">
        </div></td>
    </tr>
    <tr> 
      <td><div align="center"></div></td>
      <td><div align="center"></div></td>
    </tr>
    <tr> 
      <td><div align="center" style="padding-top:10px;">Friends' Names:</div></td>
      <td><div align="center" style="padding-top:10px;">Friends' E-mails:</div></td>
    </tr>
    <tr> 
      <td> <div align="center"> 
          <input name="friend_name1" type="text" id="friend_name1">
        </div></td>
      <td width="50%"> <div align="center"> 
          <input name="friend_email1" type="text" id="friend_email1">
        </div></td>
    </tr>
    <tr> 
      <td> <div align="center"> 
          <input name="friend_name2" type="text" id="friend_name2">
        </div></td>
      <td> <div align="center"> 
          <input name="friend_email2" type="text" id="friend_email2">
        </div></td>
    </tr>
    <tr> 
      <td> <div align="center"> 
          <input name="friend_name3" type="text" id="friend_name3">
        </div></td>
      <td> <div align="center"> 
          <input name="friend_email3" type="text" id="friend_email3">
        </div></td>
    </tr>
    <tr> 
      <td colspan="2"><div align="center"> 
          <p>Your Message (optional): 
            <input name="tellsubmit" type="hidden" id="tellsubmit4" value="doTheSend">
            <input name="link" type="hidden" id="link" value="$link">
            <br>
            <textarea name="message" cols="50" rows="5" id="textarea">$defaultMessageIntro</textarea>
          </p>
          </div></td>
    </tr>
    <tr> 
      <td colspan="2"><p align="center"> 
          <input type="submit" name="Submit" value="Send It">
        </p>
      </td>
    </tr>
  </table>
  <div align="center"> 
    </div>
</form>
EOD;
echo ($theForm);
}
?>
<?php 

function spamcheck($array) {
  # returns true if data is ok, otherwise false if it is spam-looking
  return (!preg_match("/(MIME-Version:|Content-Type:|\n|\r)/i", join('',array_values($array)) ));
}


function myMailFunction($mailto, $subject, $message, $headers, $defaultMessageClose, $adminEmail, $notice) {
	$message = $message . "\n\n" . $defaultMessageClose;
  
  // Check for suspected spam content
  if (!spamcheck(array($mailto,$subject,$headers))) {
    die('no spam please');
  }
  
	if (@mail($mailto, $subject, $message, $headers)) {
  		echo ('<p style="align:center">Your message was successfully sent to ' . $mailto . '</p>');
			if ($notice == 1) {
			$message = "From email " . $headers . "\n\n" . "To email " . "\n\n" . $mailto . "\n\n" . $message;
			@mail($adminEmail, "Referal notice", $message);
			}
		} 
	else {
  // This echo's the error message if the email did not send.  
  // You could change  the text in between the <p> tags.
  echo('<p>Mail could not be sent to ' . $mailto .  ' Please use your back button to try them again.</p>');
	}
}
?>
<?php 

function doTell ($notice, $adminEmail, $subject, $websiteName, $defaultMessageClose, $link) {
	if ($_POST['your_email'] != "") {
	$headers = 'From: ' . $_POST['your_name'] . '<' . $_POST['your_email'] . '>'; 
		}
	else {
	$headers = 'From: ' . $websiteName . '<' . $adminEmail . '>';
	}
	

	if ($_POST['friend_email1'] != "") {
	
	$mailto1 = $_POST['friend_email1'];
	
	//This tacs the name onto the subject line
	$subject1 = $_POST['friend_name1'] . $subject; 
	//This tacs the name onto the message
	$message1 = $_POST['friend_name1'] . "\r\n" . $_POST['message'];  
	
	
	myMailFunction($mailto1, $subject1, $message1, $headers, $defaultMessageClose, $adminEmail, $notice);
	}
	
	if ($_POST['friend_email2'] != "") {
	
	$mailto2 = $_POST['friend_email2'];
	
	//This tacs the name onto the subject line
	$subject2 = $_POST['friend_name2'] . $subject; 
	//This tacs the name onto the message
	$message2 = $_POST['friend_name2'] . "\r\n" . $_POST['message'];  
	
	myMailFunction($mailto2, $subject2, $message2, $headers, $defaultMessageClose, $adminEmail, $notice);
	}
	if ($_POST['friend_email3'] != "") {
	
	$mailto3 = $_POST['friend_email3'];
	
	//This tacs the name onto the subject line
	$subject3 = $_POST['friend_name3'] . $subject; 
	//This tacs the name onto the message
	$message3 = $_POST['friend_name3'] . "\r\n" . $_POST['message'];  
	
	myMailFunction($mailto3, $subject3, $message3, $headers, $defaultMessageClose, $adminEmail, $notice);
	}
	
	$return = <<<EOD
	<p align="center">Thank you for sharing.</p>
EOD;
	
echo ($return);	
}
?>
<script type="text/javascript">
function openObject(someID) 
{ 
document.getElementById(someID).style.display = 'block'; 
} 
function verify(){
		if (document.getElementById('f11').value.search(/^.+@.+\..+$/)){document.getElementById('f11').className='error'; alert("Please enter your e-mail address to join Levees.Org"); return false;}
		else{document.getElementById('data').submit(); return true;}
	}
</script>


<form style="text-align:center; border:1px solid #333; padding:10px; margin:0px 30px 20px 30px; background-color: #B5FFB5;" id="data" name="data" action="http://www.areweatrisk.org" method="get">
<h3 style="margin-top:0;">You can find out right now if your home is safe or at risk.</h3>
<h4 style="margin-top:0; font-weight:100;">Just enter your zip code in the form below and press the "Tell me" button.</h4>
		<input title="Zip Code" style="width:100px;" name="zip" class="text" value="zip code" onfocus="this.className='selected';if(this.value=='zip code'){this.value='';}" onblur="if(this.value==''){this.value='zip code';this.className=''}else{this.className='blur';}" type="text" />
		<input type="submit" value="Tell Me" />
</form>
<p style="font-size:.9em;">
As a public service, Levees.Org has created a searchable data base so citizens anywhere can find out if they're at risk of flooding by going to <a href="http://www.areweatrisk.org" title="AreWeAtRisk?">AreWeAtRisk.org</a> and entering their zip code.
</p>
<h3 style="text-align:center; cursor:pointer; color:blue; text-decoration:underline;" onclick="openObject('tell');">Tell your friends about this site so they can see if their home is at risk.</h3>	

<div id="tell" style="display:none;">
<?php
	$message = "
http://www.areweatrisk.org/wwl.php
	
As a public service, Levees.Org has created a searchable data base so citizens anywhere can find out if they're at risk of flooding by going to AreWeAtRisk.org and entering their zip code.  Watch this news story for more information!
	
http://www.areweatrisk.org/wwl.php";
	$link = "http://www.areweatrisk.org/wwl";
	doTellForm($privacyPolicyLinkText, $privacyPolicyLinkURL, $message, $link);
?>
</div>

<h3 style="text-align:center;"><a href="http://www.levees.org">Click here for information about how you can help.</a></h3>


</div>
<p style="text-align:center; margin:5px 0; font-size:.7em;"><a href="http://go.webvideoplayer.com/ats/jkA2df04lJEtwPp3N85e13175/">Add this video to your site</a></p>



<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("UA-175112-12");
pageTracker._initData();
pageTracker._trackPageview();
</script>

<script>
if(typeof(urchinTracker)!='function')document.write('<sc'+'ript src="'+
'http'+(document.location.protocol=='https:'?'s://ssl':'://www')+
'.google-analytics.com/urchin.js'+'"></sc'+'ript>')
</script>
<script>
_uacct = 'UA-4574581-2';
urchinTracker("/0733038302/test");
</script>

</body>
</html>