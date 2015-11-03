<?php
	session_start();

	$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
	$host = $_SERVER['HTTP_HOST'];

	// possible switches
	$noLogin = isset($noLogin) ? $noLogin : false; // Page does not require user to be logged in
	$adminOnly = isset($adminOnly) ? $adminOnly : false; // Page requires user to be an admin
	$useDatePicker = isset($useDatePicker) ? $useDatePicker : false; // Page uses datepicker jquery
	$newAccount = isset($newAccount) ? $newAccount : false; // new account page

	// services switches
	$adminService = isset($adminService) ? $adminService : false; // include adminService
	$userService = isset($userService) ? $userService : false; // include userService
	$hourService = isset($hourService) ? $hourService : false; // include hourService

	require_once $_SERVER['DOCUMENT_ROOT'] . '/com/dBug.php';

	if ($adminService === true) {
		require_once $_SERVER['DOCUMENT_ROOT'] . '/com/class/adminService.php';
		$adminService = new AdminService();
	}
	if ($userService === true) {
		require_once $_SERVER['DOCUMENT_ROOT'] . '/com/class/userService.php';
		$userService = new UserService();
	}
	if ($hourService === true) {
		require_once $_SERVER['DOCUMENT_ROOT'] . '/com/class/hourService.php';
		$hourService = new HourService();
	}

	if ($newAccount) {
		$noLogin = true;
		if (!$userService->getNewAccount($_GET['key'])) {
			header("Location: /index.php");
		}
	}

	if(!isset($_SESSION['user'])) {
		$user = array();
		$user['logged_in'] = false;
	}
	else {
		$user = $_SESSION['user'];
	}
	
	if (!$user['logged_in'] && !$noLogin) {
		header("Location: /index.php");
	}
	elseif ($user['logged_in']) {
		if ((strpos($url, '/user/') && $user['access'] === 1) || (!strpos($url,'/user/') && !strpos($url,'/admin/') && !strpos($url,'/ajax/') && $user['access'] === 2)) {
			header("Location: /admin/");
		}
		elseif ((strpos($url, '/admin/') && $user['access'] === 2) || (!strpos($url,'/user/') && !strpos($url,'/admin/') && !strpos($url,'/ajax/'))) {
			header("Location: /user/");
		}
		elseif (!strpos($url, '/user/user.php') && strpos($url, '/user/') && !strlen($user['email'])) {
			header("Location: /user/user.php");
		}
	}

	if ($user['logged_in'] && $adminOnly && $user['access'] > 1) {
		header('Location: /index.php');
	}
	
?>