<?php

require 'PHPMailer-master/PHPMailerAutoload.php';

$fromEmail = 'hannah.elaine.gray@gmail.com'; 
$fromName = 'Website Contact form'; 

$sendToEmail = 'hannah.elaine.gray@gmail.com';
$sendToName = "Website Contact form"
$subject = 'New Message from Contact Form';

$smtpHost = 'smtp.gmail.com'; 
$smtpUsername ="hannah.elaine.gray@gmail.com"; 
$smtpPassword = 'Graygirl1'; 

$fields = array('name' => 'Name', 'email'=> 'Email', 'phone'=> 'Phone', 'web' => 'Web', 'design' => 'Design','message' => 'Message');

$okMessage = 'Thank you! I will be in touch soon!';
$errorMessage = 'There was an error sending your message. Please try again later'; 


error_reporting(0);

try{

	if(count($_POST==0)) throw new \Exception('Form is empty');

	$emailTextHtml = "<h1>You have a new message from your contact form</h1><hr>";
	$emailTextHtml .= "<table>"; 

	foreach($_POST as $key => $value){
		if (isset($fiels[$key])){
			$emailTextHtml .= "<tr><th>$fields[$key]</th><td>$value</td></tr>";
		}
	}
	$emailTextHtml .= "</table><hr>"; 
	$emailTextHtml .="<p>Have a nice day,<br> Best,<br>Hannah</p>"; 

	$mail = new PHPMailer; 

	$mail -> setFrom($fromEmail, $fromName); 
	$mail -> addAddress($sendToEmail, $sendToName); 
	$mail -> addReplyTo($from); 

	$mail -> isHTML(true); 

	$mail -> Subject = $subject; 
	$mail -> Body = $emailTextHtml; 
	$mail-> msgHTML($emailTextHtml); 


	$mail -> isSMTP(); 
	$mail -> SMTPSecure = 'tls'; 
	$mail -> SMTPAuth = true; 
	$mail -> Host = gethostbyname($smtpHost);  
	$mail -> Port = 587; 
	$mail -> Encoding = '7bit';
	$mail -> Username = $smtpUsername;
	$mail -> Password = $smtpPassword;


	if(!$mail->send()){
		throw new \Exception('I could not send the email.') .$mail->ErrorInfo);
	}

	$responseArray = array('type' =>'success', 'message' => $okMessage); 
}
catch(\Exception $e){
	$responseArray  = array('type' => 'danger', 'message' => $e->getMessage());
}

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) =='xmlhrrprequest'){

	$encoded = json_encode($responseArray);

	header('Content-Type: application/json'); 
	echo$encoded; 
}
else{
	echo $responseArray['message'];
}

?>