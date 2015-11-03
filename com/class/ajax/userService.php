<?php

try {

	$noLogin = true;
	$userService = true;
	require_once $_SERVER['DOCUMENT_ROOT'] . '/com/initialize.php';
	
	$errorFields = array();
	$ajaxReturn = array(
		'validation'	=> false,
		'error'			=> false,
	);

	if (!isset($_GET['function'])) {
		throw new Exception('No function was given.');
	}
	elseif ($_GET['function'] === 'login') {

		if (!isset($_GET['username']) || !$userService->isStringR($_GET['username'])) {
			$errorFields[] = 'username';
		}
		if (!isset($_GET['password']) || !$userService->isStringR($_GET['password'])) {
			$errorFields[] = 'password';
		}

		if (count($errorFields)) {
			throw new Exception('validation');
		}
		
		$ajaxReturn['data'] = $userService->login($_GET['username'], $_GET['password']);
		if(!is_int($ajaxReturn['data'])) {
			$ajaxReturn['validation'] = true;
		}

	}
	elseif ($_GET['function'] === 'createAccount') {

		if (!isset($_GET['access']) || !$userService->isIntR($_GET['access'])) {
			throw new Exception('Access flag is required');
		}

		if (!isset($_GET['username']) || !$userService->isStringR($_GET['username'])) {
			$errorFields[] = 'username';
		}
		if (!isset($_GET['name']) || !$userService->isStringR($_GET['name'])) {
			$errorFields[] = 'name';
		}
		if (!isset($_GET['email']) || !$userService->isEmail($_GET['email'])) {
			$errorFields[] = 'email';
		}
		if ($_GET['access'] == 2) {
			if (!isset($_GET['street']) || !$userService->isStringR($_GET['street'])) {
				$errorFields[] = 'street';
			}
			if (!isset($_GET['city']) || !$userService->isStringR($_GET['city'])) {
				$errorFields[] = 'city';
			}
			if (!isset($_GET['state']) || !$userService->isStringR($_GET['state'])) {
				$errorFields[] = 'state';
			}
			if (!isset($_GET['zip']) || !$userService->isIntR($_GET['zip'])) {
				$errorFields[] = 'zip';
			}
		}
		if (!isset($_GET['password']) || !$userService->isStringR($_GET['password'])) {
			$errorFields[] = 'password';
		}
		if (!isset($_GET['passwordConfirm']) || !$userService->isStringR($_GET['passwordConfirm'])) {
			$errorFields[] = 'passwordConfirm';
		}

		if (count($errorFields)) {
			throw new Exception('validation');
		}

		if ($_GET['passwordConfirm'] !== $_GET['password']) {
			$errorFields[] = 'password';
			$errorFields[] = 'passwordConfirm';
			throw new Exception('validation');
		}

		if ($_GET['access'] == 2) {
			$ajaxReturn['data'] = $userService->createUserAccount($_GET['username'], $_GET['name'], $_GET['email'], $_GET['street'], $_GET['city'], $_GET['state'], $_GET['zip'], $_GET['password']);
		}
		else {
			$ajaxReturn['data'] = $userService->createAdminAccount($_GET['username'], $_GET['name'], $_GET['email'], $_GET['password']);	
		}

	}
	elseif ($_GET['function'] === 'resetPassword') {

		if (!isset($_GET['username']) || !$userService->isStringR($_GET['username'])) {
			$errorFields[] = 'resetUser';
			throw new Exception('validation');
		}

		$ajaxReturn['data'] = $userService->resetPassword($_GET['username']);
	}
	elseif ($_GET['function'] === 'updateUserInfo') {

		if (!isset($_GET['username']) || !$userService->isStringR($_GET['username'])) {
			$errorFields[] = 'username';
		}
		if (!isset($_GET['email']) || !$userService->isEmailR($_GET['email'])) {
			$errorFields[] = 'email';
		}
		if (!isset($_GET['name']) || !$userService->isStringR($_GET['name'])) {
			$errorFields[] = 'name';
		}
		if (!isset($_GET['street']) || !$userService->isStringR($_GET['street'])) {
			$errorFields[] = 'street';
		}
		if (!isset($_GET['city']) || !$userService->isStringR($_GET['city'])) {
			$errorFields[] = 'city';
		}
		if (!isset($_GET['state']) || !$userService->isStringR($_GET['state'])) {
			$errorFields[] = 'state';
		}
		if (!isset($_GET['zip']) || !$userService->isIntR($_GET['zip'])) {
			$errorFields[] = 'zip';
		}

		if (count($errorFields)) {
			throw new Exception('validation');
		}
		$emailHoursSubmitted = isset($_GET['emailHoursSubmitted']) ? 1 : 0;
		$userService->updateUserInfo($_GET['username'], $_GET['name'], $_GET['email'], $emailHoursSubmitted, $_GET['street'], $_GET['city'], $_GET['state'], $_GET['zip']);
	}
	elseif ($_GET['function'] === 'updatePassword') {

		if (!isset($_GET['password']) || !$userService->isStringR($_GET['password'])) {
			$errorFields[] = 'password';
		}
		if (!isset($_GET['passwordConfirm']) || !$userService->isStringR($_GET['passwordConfirm'])) {
			$errorFields[] = 'passwordConfirm';
		}

		if (count($errorFields)) {
			throw new Exception('validation');
		}

		if ($_GET['passwordConfirm'] !== $_GET['password']) {
			$errorFields[] = 'password';
			$errorFields[] = 'passwordConfirm';
			throw new Exception('validation');
		}

		$userService->updatePassword($_GET['password']);
	}
	else {
		throw new Exception('This function does not exist.');
	}

} catch (Exception $e) {

	if ($e->getMessage() === 'validation') {
		$ajaxReturn['validation'] = true;
		$ajaxReturn['fields'] = $errorFields;
	} else {
		$userService->logError($e->getMessage());
		$ajaxReturn['error'] = true;
		$ajaxReturn['message'] = $e->getMessage();		
	}

}

echo json_encode($ajaxReturn);