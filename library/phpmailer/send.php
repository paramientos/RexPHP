
<?php


function sendMail($to,$subject,$body) {
	error_reporting(0);

	date_default_timezone_set('Europe/Istanbul');

	require_once('system/phpmailer/class.phpmailer.php');
	include("system/phpmailer/class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

	$mail             = new PHPMailer();
	$mail->CharSet = 'UTF-8';

	$body             =$body;
	$body             = eregi_replace("[\]",'',$body);

	$mail->IsSMTP(); // telling the class to use SMTP
	$mail->Host       = "mail.hmbilgisayar.com.tr"; // SMTP server
	$mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
											   // 1 = errors and messages
											   // 2 = messages only
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	$mail->Host       = "mail.hmbilgisayar.com.tr"; // sets the SMTP server
	$mail->Port       = 587;                    // set the SMTP port for the GMAIL server
	$mail->Username   = "siparis@hmbilgisayar.com.tr"; // SMTP account username
	$mail->Password   = "FIqk90Y1";        // SMTP account password

	$mail->SetFrom('siparis@hmbilgisayar.com.tr', 'HM Siparis');

	$mail->AddReplyTo("siparis@hmbilgisayar.com.tr","HM Siparis");

	$mail->Subject    =$subject;

	$mail->AltBody    = ""; // optional, comment out and test

	$mail->MsgHTML($body);

	$address = $to;
	$mail->AddAddress($address, $address);

	//$mail->AddAttachment("images/phpmailer.gif");      // attachment
	//$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment

	if(!$mail->Send()) {
	  //echo "Mailer Error: " . $mail->ErrorInfo;
	  return false;
	} else {
	  return true;
	}

}

?>

