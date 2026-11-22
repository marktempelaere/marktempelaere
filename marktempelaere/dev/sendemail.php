<?php
/* IMPORTANT: Do NOT update PHP version to higher than 5.6 on VERSIO hosting,
or emailing will fail giving an Internal Server Error 500! */
/************************
* Variables you can change
*************************/

$mailto   = "mark@marktempelaere.com"; // Enter your mail address here.
$name     = $_GET['name'];
$email    = $_GET['email'];
$subject  = "[marktempelaere] Message from " . $name; // Enter the subject here.
$message  = $_GET['message'];

	if (strlen($name) < 1 ) {
		echo 'email_error';
	}

	else if (strlen($email) < 1 ) {
		echo 'email_error';
	}

	else if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $email)) {
		echo 'email_error';
	}

	else if (strlen($message) < 1 ) {
		echo 'email_error';
	}

	else {

	// NOW SEND THE ENQUIRY

	$email_message="\n\n" .
		"Name: " .
		$name .
		"\n" .
		"Email: " .
		$email .
		"\n" .
		"Message: " .
		"\n" .
		$message .
		"\n" .
		"\n\n" ;

		$email_message = trim(stripslashes($email_message));
		mail($mailto, $subject, $email_message, "From: \"$name\" <".$email.">\nReply-To: \" <".$email.">\nX-Mailer: PHP/" . phpversion() );
	}
?>
