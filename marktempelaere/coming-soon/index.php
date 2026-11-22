<?php
include("inc/eo.php"); // PHP function for email address obfuscation
if (array_key_exists('subscribe', $_POST)) { // if form has been submitted
	// initialize warning array
	$warning = array();
	$success = array();
	
	require("inc/connection.php");
	require("inc/constants.php");
	require("inc/functions.php");

	// remove backslashes
	if (function_exists('nukeMagicQuotes')) {
	  nukeMagicQuotes();
	}
	// do form validation:
	// list expected fields
	$expected = array('subscriber');
	// set required fields
	$required = array('subscriber');
	// create empty array for any missing fields
	$missing = array();
	
	// process the $_POST variables
	foreach ($_POST as $key => $value) {
		// assign to temporary variable and strip whitespace if not an array
		$temp = is_array($value) ? $value : trim($value);
		// if empty and required, add to $missing array
		if (empty($temp) && in_array($key, $required)) {
			array_push($missing, $key);
		}
		// otherwise, assign to a variable of the same name as $key
		elseif (in_array($key, $expected)) {
			${$key} = $temp;
		}
	}
	// validate the email address
	if (!empty($subscriber)) {
		// regex to ensure no illegal characters in email address 
		$checkEmail = '/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,10})+$/';
		// reject the email address if it doesn't match
		if (!preg_match($checkEmail, $subscriber)) {
			array_push($missing, 'subscriber');
			$warning[] = "Please, enter a valid email address.";
		}
	}
	// check if the email address is already stored
	// initialise $exists variable
	$exists = 0;
	$check_sql = "SELECT COUNT(*) FROM subscribers WHERE email = :email";
	// prepare the statement
	$check_stmt = $conn->prepare($check_sql);
	// bind the parameters and execute the statement
	$check_stmt->bindParam(':email', $subscriber);
	$check_result = $check_stmt->execute();
	$exists = $check_stmt->fetchColumn();
	if ($exists >= 1) {
		$warning[] = "This email address is already registered.";
	}
	// store the data into the database and process the email
	// go ahead only if all required fields are OK and data is not suspect as spam and address does not exist yet
	if (empty($missing) && empty($_POST['url']) && $exists == 0) {
		// store data into the database:
		// create SQL
		$insert = "INSERT INTO subscribers (email) VALUES (:subscriber)";
		// prepare the statement
		$stmt = $conn->prepare($insert);
		// bind the parameters and execute the statement
		$stmt->bindParam(':subscriber', $subscriber);
		$OK = $stmt->execute();
		// get the id from the new record as created by the insert query for use in the e-mail feature
		$lastId = $conn->lastInsertId();
	
		// create the mail
		$to = 'mark@marktempelaere.com';
		$subject = 'New subscription to be updated when the site launches';
		// build the mail message
		$mailmessage = "<html>\n";
		$mailmessage .= "<head>\n";
		$mailmessage .= "<title>\n";
		$mailmessage .= "New subscription to be updated when the site launches\n";
		$mailmessage .= "</title>\n";
		$mailmessage .= "</head>\n";
		$mailmessage .= "<body>\n";
		$mailmessage .= "<p>Dear,</p>\n";
		$mailmessage .= "<p>There is a new subscription to be updated when the site launches by<br />\n<strong>{$subscriber}</strong></p>\n";
		$mailmessage .= "</body>\n";
		$mailmessage .= "</html>\n";
		// limit the mail message line length to 80 characters
		$mailmessage = wordwrap($mailmessage, 80);
		// create additional mail headers
		// To send HTML mail, the Content-type header must be set
		$additionalHeaders  = 'MIME-Version: 1.0' . "\r\n";
		$additionalHeaders .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$additionalHeaders .= 'From: marktempelaere.com<no-reply@marktempelaere.com>' . "\r\n";
		$additionalHeaders .= "Reply-To: $subscriber" . "\r\n";
		
		// redirect and send the mail, or display an error message
		if ($OK) {
			// send the mail (cannot send it before redirecting due to 'headers already sent' error)
			$mailSent = mail($to, $subject, $mailmessage, $additionalHeaders);
			if ($mailSent) {
				$missing = array(); // initialize the missing array.
			}
			$success[] = "Your email address was correctly registered.";
		}
		else {
			$warning[] = "There was a problem storing your email address.\nPlease, try again later.";
		}
	}
	// if data is suspect as spam, then simply return and do nothing
	if (!empty($_POST['url'])) {
		header('Location: https://'.SITE_LOCATION.'index.php');
		exit;
	}
}
?><!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="x-ua-compatible" content="ie=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no, user-scalable=no" />
		<link rel="stylesheet" href="./css/styles.css" />
		<title>Mark Tempelaere&nbsp;&ndash; Web Designer and Graphic Designer</title>
	</head>
	<body>
		<img class="bgimg" src="./img/bg-gradient.jpg" alt="Background image with Ken Burns effect, slowly zooming in and out." />
		<div class="container">
			<h1 class="text-hide logo">Mark Tempelaere&nbsp;&ndash; Web Designer, Web Developer, Graphic Designer
				<img src="./img/logo-quality-white.svg" alt="Mark Tempelaere - Quality Designs, Made With Love" class="bottom" />
				<img src="./img/logo-graphic-white.svg" alt="Mark Tempelaere - Graphic Design, Web Design" class="top" />
			</h1>
			<h2>My personal portfolio website will soon be online!</h2>
			<div class="countdown">
				<div class="timer"></div>
			</div>
			<div class="subscribe">
				<h3>Subscribe here to be updated when the site launches.</h3>
				<?php
				if ($warning) {
					foreach ($warning as $item) {
						echo "<div class=\"alert alert-danger\" role=\"alert\"><span>$item</span></div>";
					}
				}
				if ($success) {
					foreach ($success as $item) {
						echo "<div class=\"alert alert-success\" role=\"alert\"><span>$item</span></div>";
					}
				}
				?>
				<form method="post" action="index.php" id="contactform" class="clearfix">
					<div class="input-group" id="contactformarea">
						<input type="text" maxlength="150" class="form-control" name="subscriber" id="subscriber" placeholder="Your Email Address" aria-label="Your Email Address" />
						<div class="input-group-append">
							<button type="submit" class="btn btn-primary buttoncontact" name="subscribe" id="subscribe"><span>Subscribe Me!</span></button>
						</div>
					</div>
				</form>
			</div>
			<div class="contact">
				<h3>Contact Me</h3>
				<a href="mailto:mark@marktempelaere.com" title="Email" class="email">Email</a>
				<a href="https://www.linkedin.com/in/marktempelaere/" title="LinkedIn" class="linkedin">LinkedIn</a>
				<a href="https://twitter.com/marktempelaere" title="Twitter" class="twitter">Twitter</a>
			</div>
			<div class="resume">
				<p>You can find my personal resume when clicking on the button below.</p>
				<a class="btn btn-primary" href="./doc/cv_mark_tempelaere.pdf"><span>Resume (PDF)</span></a>
			</div>
		</div>
		<footer>
			<p class="copyright">Made with <i>love</i> by Mark Tempelaere, &copy;&nbsp;2019</p>
		</footer>
		<script src="./js/main.min.js"></script>
	</body>
</html>
