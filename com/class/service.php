<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/com/initialize.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/com/class/postmark-api.php';
if(!defined('POSTMARKAPP_API_KEY'))	define('POSTMARKAPP_API_KEY', 'postmark-api-key-here');
if(!defined('POSTMARKAPP_MAIL_FROM_ADDRESS')) define('POSTMARKAPP_MAIL_FROM_ADDRESS', 'email@address.here');

class Service {

	public $mysqli;
	private $logRoot;
	private $emailLog	= 'email.php'; // file name for the email log
	private $errorLog	= 'error.php'; // file name for the error log
	const private $salt = ''; // password salt, a random string. do not change once accounts have been created

	public function __construct() {

		$host = "127.0.0.1";
		$dbUser = "";
		$dbUserPass = "";
		$dbName   = "";
		
		$this->mysqli 	= new mysqli($host, $dbUser, $dbUserPass, $dbName);
		$this->logRoot = $_SERVER['DOCUMENT_ROOT'] . '/com/logs/';
		$this->emailLog = $this->logRoot . $this->emailLog;
		$this->errorLog = $this->logRoot . $this->errorLog;
	}

	public function dateFormat ($date) {

		return date('m/d/y', strtotime($date));
	}

	public function dateFormatDB ($date) {

		return date('Y-m-d', strtotime($date));
	}

	public function emailHoursSubmitted ($userEmail, $userName, $total) {

		$subject = 'Tech Hours Hours Submitted';
		$email = new Mail_Postmark();
		$email->subject($subject);
		$email->addTo($userEmail, $userName);
		$body = ""; 
		$body .= "The hours you had given for last week have been submitted.<br />";
		$body .= "Your total earnings comes to: <strong>$" . $total . "</strong><br />";
		$body .= "<hr>";
		$body .= "If you no longer wish to receive these emails you may opt out on the user's page on the TechHours website.";
			
		$email->messageHtml($this->emailWrapper($body));
		$email->send();
		$this->logEmail('HoursSubmitted', array($userEmail, $userName, $total));
	}

	public function emailNewAccount ($newUserEmail, $key) {

		$email = new Mail_Postmark();
		$email->subject('Tech Hours New Account');
		$email->addTo($newUserEmail);
		$body = ""; 
		$body .= "An account has been created for you to be able to log your hours on the Tech Hours site.<br />";
		$body .= "To finish the account creation process please click the link below and fill out the form:<br />";
		$body .= "<a href=\"" . $_SERVER['HTTP_HOST'] . "/newAccount.php?key=" . $key . "\">" . $_SERVER['HTTP_HOST'] . "/newAccount.php?key=" . $key . "</a>";
			
		$email->messageHtml($this->emailWrapper($body));
		$email->send();
		$this->logEmail('NewUser', array($newUserEmail, $key));
	}

	public function emailPasswordReset ($userEmail, $name, $key) {

		$email = new Mail_Postmark();
		$email->subject('Tech Hours Password Reset');
		$email->addTo($userEmail, $name);
		$body = ""; 
		$body .= "A password reset was requested for your account, click the link below to reset it.<br />";
		$body .= "<a href=\"" . $_SERVER['HTTP_HOST'] . "/password.php?key=" . $key . "\">" . $_SERVER['HTTP_HOST'] . "/password.php?key=" . $key . "</a>";
		$body .= "<hr>";
		$body .= "If you did not request a password reset just ignore this email and log in as usual.";
			
		$email->messageHtml($this->emailWrapper($body));
		$email->send();
		$this->logEmail('PasswordReset', array($userEmail, $name, $key));
	}

	private function emailWrapper ($message) {

		$eHeader = "";
		$eFooter = "";
		return $eHeader.$message.$eFooter;
	}

	public function getMonth ($dateString) {

		return date('m', strtotime($dateString));
	}

	public function getMonthName ($dateString) {

		return date('F', strtotime($dateString));
	}

	public function getYear ($dateString) {

		return date('Y', strtotime($dateString));
	}

	protected function getRandomString () {

		$randomString = "";
		$validChars = 'bcdfghjklmnpqrstvwxzBCDFGHJKLMNPQRSTVWXZ1234567890';
		$length = 16;

		$numValidChars = strlen($validChars);

		for ($i = 0; $i < $length; $i++) {
			$randomPick = mt_rand(1, $numValidChars);
			$randomChar = $validChars[$randomPick-1];
			$randomString .= $randomChar;
		}
		return $randomString;
	}

	public function isArrayR ($array = null) {

		if(is_null($array) || !is_array($array) || !count($array)) {
			return false;
		} else {
			return true;
		}
	}

	public function isBool ($bool = null) {

		if(is_null($bool) || !strlen($bool) || !($bool === 'true' || $bool === 'false')) {
			return false;
		} else {
			return true;
		}
	}

	public function isDate ($date = null) {

		if(!is_null($date) && strlen($date)) {
			$dateSplit = explode('/', $date);
			if (count($dateSplit) !== 3 || !checkdate($dateSplit[0], $dateSplit[1], $dateSplit[2])) {
				return false;
			}
			else {
				return true;
			}
		}
		else {
			return true;
		}
	}

	public function isEmail ($email = null) {

		if (!is_null($email) && strlen($email)) {
			if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
				return true;
			}
			else {
				return false;
			}
		}
		else {
			return true;
		}
	}

	public function isEmailR ($email = null) {

		if(!is_null($email) && strlen($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return true;
		}
		else {
			return false;
		}
	}

	public function isInt ($int = null) {

		if (!is_null($int) && strlen($int)) {
			if (!is_numeric($int) || $int < 1 || $int != round($int)) {
				return false;
			}
			else {
				return true;
			}
		}
		else {
			return true;
		}

	}

	public function isIntR ($int = null) {

		if (is_null($int) || is_array($int) || !strlen($int) || !is_numeric($int) || $int < 1 || $int != round($int)) {
			return false;
		} else {
			return true;
		}

	}

	public function isNumberR ($number = null) {

		if (is_null($number) || is_array($number) || !strlen($number) || !is_numeric($number) || $number < 1) {
			return false;
		} else {
			return true;
		}

	}

	public function isStringR ($string = null) {

		if (is_null($string) || !strlen($string)) {
			return false;
		} else {
			return true;
		}
	}

	public function isTime ($time = null) {

		if (!is_null($time) && strlen($time)) {
			if (preg_match('#^ *(0?[1-9]|1[0-2])[.: ]?[0-5][0-9] *[ap]m *$#', $time) || preg_match('#^ *([01]?[0-9]|2[0-3])[.: ]?[0-5][0-9] *$#', $time)) {
				return true;
			}
			else {
				return false;
			}
		} else {
			return true;
		}
	}

	public function logEmail ($type, $variables) {

		exec('tail -n 500 ' . $this->emailLog, $trimmedLog);
		file_put_contents(
			$this->emailLog,
			join($trimmedLog, "\n") . "\n" .
			$this->logTimestamp() . 'Email Type: ' . $type . ' | Variables: ' . join(', ', $variables) . "\n"
		);
	}

	public function logError ($message) {

		exec('tail -n 500 ' . $this->errorLog, $trimmedLog);
		file_put_contents(
			$this->errorLog,
			join($trimmedLog, "\n") . "\n" .
			$this->logTimestamp() . '[' . $_SERVER['REQUEST_URI'] . '] '. 'Error: ' . $message
		);
	}

	private function logTimestamp () {

		global $user;
		return '[' . date('Y-m-d h:i.s A') . (isset($user) && strlen($user['name']) ? ' ' . $user['name'] : ' ' . $user['username']) . '] ';
	}

	public function timeFormat ($time) {

		return $time != 0 ? date('h:i a', strtotime($time)) : '';
	}

	public function timeFormatDB ($time) {

		if (preg_match('#^ *([01]?[0-9]|2[0-3])[.: ]?[0-5][0-9] *$#', $time)) {
			return $time . '00';
		}
		else {
			if (strlen($time)) {
				$time = date("His", strtotime($time));
			}
			return $time;
		}
	}
}