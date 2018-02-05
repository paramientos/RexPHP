<?php


/**
 *  extends to Rex this is because use some features of RexPHP
 */
class Mailer extends Rex {

	public $isSMTP=true;
	public $charSet='UTF-8';
	public $body="";
	public $smtpDebug=0;
	public $smtpAuth=true;
	public $host="mail.ibd.com.tr";
	public $port=587;
	public $username="ik@ibd.com.tr";
	public $password="";
	public $from="ik@ibd.com.tr";
	public $addReplyTo="ik@ibd.com.tr";
	public $subject="";
	public $altBody="";
	public $to="";
	public $files;
	
    
public function send() {
	
	
	date_default_timezone_set('Europe/Istanbul');
	$phpmailerClass = realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'phpmailer' . DIRECTORY_SEPARATOR . 'class.phpmailer.php';
	$smtpClass = realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'phpmailer' . DIRECTORY_SEPARATOR . 'class.smtp.php';
	require_once $phpmailerClass;
	require_once $smtpClass;
	

	date_default_timezone_set('Europe/Istanbul');

	$mail             = new PHPMailer();
	$mail->CharSet = $this->charSet;

	$body             =$this->body;
	
	if ($this->isSMTP) {
			$mail->IsSMTP(); 
	}
	
	$mail->Host       = $this->host; 
	$mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
											   // 1 = errors and messages
											   // 2 = messages only
	$mail->SMTPAuth   = $this->smtpAuth;                  // enable SMTP authentication
	//$mail->Host       = $this->host; // sets the SMTP server
	$mail->Port       = $this->port;                    // set the SMTP port for the GMAIL server
	$mail->Username   = $this->username; // SMTP account username
	$mail->Password   = $this->password;        // SMTP account password

	$mail->SetFrom($this->from, 'Website Kariyer');

	$mail->AddReplyTo($this->addReplyTo,"Website Kariyer");

	$mail->Subject    =$this->subject;

	$mail->AltBody    = $this->altBody; // optional, comment out and test

	$mail->MsgHTML($this->body);

	$mail->AddAddress($this->to, $this->to);
	
	if (!is_null($this->files)) {
		if ($this->files['type']=="application/pdf") {
			$mail->AddAttachment( $this->files['tmp_name'], $this->files['name'] );
		} else {
			$this->url->redirect('common/404');
			exit();
		}
	}
	
	//$mail->AddAttachment("images/phpmailer.gif");      // attachment
	//$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment

	if(!$mail->Send()) {
	  //echo "Mailer Error: " . $mail->ErrorInfo;
	  return false;
	} else {
	  return true;
	}

}



}

?>