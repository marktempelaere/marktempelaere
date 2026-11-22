<?php

/* Namespace alias. */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/* If you installed PHPMailer without Composer: */

require '/PHPMailer/src/Exception.php';
require '/PHPMailer/src/PHPMailer.php';
require '/PHPMailer/src/SMTP.php';

/* Variables you can change: */

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

/* Now create the message */

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
}

/* Create a new PHPMailer object. Passing TRUE to the constructor enables exceptions. */
$mail = new PHPMailer(TRUE);

/* Open the try/catch block. */
try {
	/* Set the mail sender. */
	$mail->setFrom($email, $name);
	
	/* Add a recipient. */
	$mail->addAddress($mailto, 'Mark Tempelaere');
	
	/* Set the subject. */
	$mail->Subject = $subject;
	
	/* Set the mail message body. */
	$mail->Body = $email_message;
	
	/* Finally send the mail. */
	$mail->send();
}
catch (Exception $e)
{
	/* PHPMailer exception.
	echo $e->errorMessage(); */
	echo 'email_error'; /* Replace with code above to debug. */
}
catch (\Exception $e)
{
	/* PHP exception (note the backslash to select the global namespace Exception class).
	echo $e->getMessage(); */
	echo 'email_error'; /* Replace with code above to debug. */
}

?>
