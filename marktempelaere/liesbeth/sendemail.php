<?php

/************************
* Variables you can change
*************************/

$mailto   = "liesbeth@marktempelaere.com"; // Enter your mail address here.
$email    = $_POST['email'];
$subject  = "Message from " . $email; // Enter the subject here.
$message  = $_POST['message'];


	if (strlen($email) < 1 ) {
		echo 'email_error';
	}

	else if (!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", $email)) {
		echo 'email_error';
	}

	else if (strlen($message) < 1 ) {
		echo 'email_error';
	}

	else {

	// NOW SEND THE ENQUIRY

	$email_message="\n\n" .
		"Email: " .
		$email .
		"\n" .
		"Message: " .
		"\n" .
		$message .
		"\n" .
		"\n\n" ;

		$email_message = trim(stripslashes($email_message));
		mail($mailto, $subject, $email_message, "From: \"$vname\" <".$email.">\nReply-To: \" <".$email.">\nX-Mailer: PHP/" . phpversion() );
	}
?>
