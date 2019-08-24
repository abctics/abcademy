<?php

if( isset($_POST['email']) ){
	//SMTP needs accurate times, and the PHP time zone MUST be set
	//This should be done in your php.ini, but this is how to do it if you don't have access to that
	date_default_timezone_set('Etc/UTC');

	require '../PHPMailerAutoload.php';

	//Create a new PHPMailer instance
	$mail = new PHPMailer;

	//Tell PHPMailer to use SMTP
	$mail->isSMTP();

	//Enable SMTP debugging
	// 0 = off (for production use)
	// 1 = client messages
	// 2 = client and server messages
	$mail->SMTPDebug = 0;

	//Ask for HTML-friendly debug output
	$mail->Debugoutput = 'html';

	//Set the hostname of the mail server
	$mail->Host = 'smtp.gmail.com';
	// use
	// $mail->Host = gethostbyname('smtp.gmail.com');
	// if your network does not support SMTP over IPv6

	//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
	$mail->Port = 587;

	//Set the encryption system to use - ssl (deprecated) or tls
	$mail->SMTPSecure = 'tls';

	//Whether to use SMTP authentication
	$mail->SMTPAuth = true;

	//Username to use for SMTP authentication - use full email address for gmail
	$mail->Username = "hola@checklatam.com";

	//Password to use for SMTP authentication
	$mail->Password = "Markhamperu";

	//Set who the message is to be sent from
	$mail->setFrom('hola@check.pe', 'Check Home');

	//Set an alternative reply-to address
	$mail->addReplyTo('hola@check.pe', 'Check Home');

	//Set who the message is to be sent to
	$mail->addAddress($_POST('username'), '');

	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body

	$message = '';
	$name = htmlentities($_POST['name'], ENT_QUOTES, "UTF-8");

	if(isset($_POST['people'])) {

		//Set the subject line
		$mail->Subject = 'Nueva solicitud de reserva: ' . $name;

		$message = "Hola,<br/><br/>Alguien ha pedido una reserva para Gioconda. Estos son los datos del cliente:<br/><br/>Nombre: " . $name . "<br/>Email: " . $_POST['email'] . "<br/>Fecha: " . $_POST['day'] . " " . $_POST['time'] . "<br/>Nro. de personas: " . $_POST['people'] . "<br/><br/>Por favor <b>responde al cliente</b> a la brevedad posible confirmando / rechazando su reserva.<br/><br/>Saludos,<br/>GiocondaBot";

		//Replace the plain text body with one created manually
		$mail->AltBody = 'Alguien ha hecho una nueva reserva en Gioconda.';

	} else {
		//Set the subject line
		$mail->Subject = $name . " te ha mandado un mensaje";

		$message = $_POST['message'] . "<br/><br/>De: " . $name . " (" . $_POST['email'] . ")";

		//Replace the plain text body with one created manually
		$mail->AltBody = 'Alguien te ha contactado a traves de la página web.';
	}

	$mail->msgHTML($message);

	//$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));

	//Attach an image file
	//$mail->addAttachment('images/phpmailer_mini.png');

	//send the message, check for errors
	if (!$mail->send()) {
	    echo json_encode("error");
	} else {
	    echo json_encode("success");
	}
} else {
	header("Location: /");
}
