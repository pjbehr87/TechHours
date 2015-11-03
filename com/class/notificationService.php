<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/com/class/service.php';

class NotificationService extends Service {

	public function errorEmail($e)
	{
		$email = new Mail_Postmark();
		$email->subject('Inventory Site Error');
		$email->addTo('pjbehr87@gmail.com', 'Phil');
		$body = ""; 
		$body .= "There was an error:";
		$body .= "File: ".$e->getFile()."<br>";
		$body .= "Line: ".$e->getLine()."<br>";
		$body .= "Message: ".$e->getMessage()."<br>";
		$body .= "Code: ".$e->getCode()."<br>";
		$body .= "Trace: ".$e->getTraceAsString()."<br>";
			
		$email->messageHtml($body);
		$email->send();
	}
}