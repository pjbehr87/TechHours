<?php

try {
	$adminOnly = true;
	require_once $_SERVER['DOCUMENT_ROOT'] . '/com/initialize.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/com/class/adminService.php';
	$adminService = new AdminService();
	
	$errorFields = array();
	$ajaxReturn = array(
		'validation'	=> false,
		'error'			=> false,
	);

	if (!isset($_GET['function'])) {
		throw new Exception('No function was given.');
	}
	elseif ($_GET['function'] === 'activateJob') {

		if (!isset($_GET['jobID']) || !$adminService->isIntR($_GET['jobID'])) {
			$errorFields[] = 'jobID';
			throw new Exception('validation');
		}

		$ajaxReturn['data'] = $adminService->activateJob($_GET['jobID']);
	}
	elseif ($_GET['function'] === 'activateUser') {

		if (!isset($_GET['userID']) || !$adminService->isIntR($_GET['userID'])) {
			$errorFields[] = 'userID';
			throw new Exception('validation');
		}
		$ajaxReturn['data'] = $adminService->activateUser($_GET['userID']);
	}
	elseif ($_GET['function'] === 'createJob') {

		if (!isset($_GET['location']) || !$adminService->isStringR($_GET['location'])) {
			$errorFields[] = 'location';
		}
		if (!isset($_GET['job']) || !$adminService->isStringR($_GET['job'])) {
			$errorFields[] = 'job';
		}
		if (!isset($_GET['account']) || !$adminService->isStringR($_GET['account'])) {
			$errorFields[] = 'account';
		}
		if (!isset($_GET['rate']) || !$adminService->isNumberR($_GET['rate'])) {
			$errorFields[] = 'rate';
		}
		$flat = isset($_GET['flat']) ? 1 : 0;

		if (count($errorFields)) {
			throw new Exception('validation');
		}

		$ajaxReturn['data'] = $adminService->createJob($_GET['location'], $_GET['job'], $_GET['account'], $_GET['rate'], $flat);
	}
	elseif ($_GET['function'] === 'createLimitedUser') {

		if (!isset($_GET['name']) || !$adminService->isStringR($_GET['name'])) {
			$errorFields[] = 'limitedName';
		}
		if (!isset($_GET['email']) || !$adminService->isEmail($_GET['email'])) {
			$errorFields[] = 'limitedEmail';
		}
		if (!isset($_GET['street']) || !$adminService->isStringR($_GET['street'])) {
			$errorFields[] = 'limitedStreet';
		}
		if (!isset($_GET['city']) || !$adminService->isStringR($_GET['city'])) {
			$errorFields[] = 'limitedCity';
		}
		if (!isset($_GET['state']) || !$adminService->isStringR($_GET['state'])  || strlen($_GET['state']) > 2) {
			$errorFields[] = 'limitedState';
		}
		if (!isset($_GET['zip']) || !$adminService->isIntR($_GET['zip'])) {
			$errorFields[] = 'limitedZip';
		}

		if (count($errorFields)) {
			throw new Exception('validation');
		}

		$ajaxReturn['data'] = $adminService->createLimitedUser(
			$_GET['name'],
			$_GET['email'],
			$_GET['street'],
			$_GET['city'],
			$_GET['state'],
			$_GET['zip']
		);
	}
	elseif ($_GET['function'] === 'createUser') {

		if (!isset($_GET['email']) || !$adminService->isEmailR($_GET['email'])) {
			$errorFields[] = 'newEmail';
			throw new Exception('validation');
		}
		$access = isset($_GET['admin']) ? 1 : 2;

		$ajaxReturn['data'] = $adminService->createUser($_GET['email'], $access);
	}
	elseif ($_GET['function'] === 'deactivateJob') {

		if (!isset($_GET['jobID']) || !$adminService->isIntR($_GET['jobID'])) {
			$errorFields[] = 'jobID';
			throw new Exception('validation');
		}

		$ajaxReturn['data'] = $adminService->deactivateJob($_GET['jobID']);
	}
	elseif ($_GET['function'] === 'deactivateUser') {

		if (!isset($_GET['userID']) || !$adminService->isIntR($_GET['userID'])) {
			$errorFields[] = 'userID';
			throw new Exception('validation');
		}

		$ajaxReturn['data'] = $adminService->deactivateUser($_GET['userID']);
	}
	elseif ($_GET['function'] === 'getJob') {

		if (!isset($_GET['jobID']) || !$adminService->isIntR($_GET['jobID'])) {
			$errorFields[] = 'jobID';
			throw new Exception('validation');
		}

		$ajaxReturn['data'] = $adminService->getJob($_GET['jobID']);
	}
	elseif ($_GET['function'] === 'getUser') {

		if (!isset($_GET['userID']) || !$adminService->isIntR($_GET['userID'])) {
			$errorFields[] = 'userID';
			throw new Exception('validation');
		}

		$ajaxReturn['data'] = $adminService->getUser($_GET['userID']);
	}
	elseif ($_GET['function'] === 'updateJob') {
		
		if (!isset($_GET['jobID']) || !$adminService->isIntR($_GET['jobID'])) {
			$errorFields[] = 'jobID';
		}
		if (!isset($_GET['location']) || !$adminService->isStringR($_GET['location'])) {
			$errorFields[] = 'location';
		}
		if (!isset($_GET['job']) || !$adminService->isStringR($_GET['job'])) {
			$errorFields[] = 'job';
		}
		if (!isset($_GET['account']) || !$adminService->isStringR($_GET['account'])) {
			$errorFields[] = 'account';
		}
		if (!isset($_GET['rate']) || !$adminService->isNumberR($_GET['rate'])) {
			$errorFields[] = 'rate';
		}
		$flat = isset($_GET['flat']) ? 1 : 0;

		if (count($errorFields)) {
			throw new Exception('validation');
		}

		$ajaxReturn['data'] = $adminService->updateJob(
			$_GET['jobID'],
			$_GET['location'],
			$_GET['job'],
			$_GET['account'],
			$_GET['rate'],
			$flat
		);
	}
	elseif ($_GET['function'] === 'updateUserInfo') {

		if (!isset($_GET['admin']) || !$adminService->isIntR($_GET['admin'])) {
			throw new Exception('Admin flag is required');
		}

		if (!isset($_GET['userID']) || !$adminService->isIntR($_GET['userID'])) {
			$errorFields[] = 'userID';
		}
		if (!isset($_GET['username']) || !$adminService->isStringR($_GET['username'])) {
			$errorFields[] = 'username';
		}
		if (!isset($_GET['name']) || !$adminService->isStringR($_GET['name'])) {
			$errorFields[] = 'name';
		}
		if (intval($_GET['admin']) !== 1) {
			if (!isset($_GET['email']) || !$adminService->isEmail($_GET['email'])) {
				$errorFields[] = 'email';
			}
			if (!isset($_GET['street']) || !$adminService->isStringR($_GET['street'])) {
				$errorFields[] = 'street';
			}
			if (!isset($_GET['city']) || !$adminService->isStringR($_GET['city'])) {
				$errorFields[] = 'city';
			}
			if (!isset($_GET['state']) || !$adminService->isStringR($_GET['state']) || strlen($_GET['state']) > 2) {
				$errorFields[] = 'state';
			}
			if (!isset($_GET['zip']) || !$adminService->isIntR($_GET['zip'])) {
				$errorFields[] = 'zip';
			}
		}

		if (count($errorFields)) {
			throw new Exception('validation');
		}

		if (intval($_GET['admin']) !== 1) {
			$adminService->updateUserInfo(
				$_GET['userID'],
				$_GET['username'],
				$_GET['name'],
				$_GET['email'],
				$_GET['street'],
				$_GET['city'],
				$_GET['state'],
				$_GET['zip']
			);
		}
		else {
			$adminService->updateAdminInfo(
				$_GET['userID'],
				$_GET['username'],
				$_GET['name']
			);
		}
	}
	elseif ($_GET['function'] === 'updateUserPassword') {

		
		if (!isset($_GET['userID']) || !$adminService->isIntR($_GET['userID'])) {
			$errorFields[] = 'userID';
		}
		if (!isset($_GET['password']) || !$adminService->isStringR($_GET['password'])) {
			$errorFields[] = 'password';
		}
		if (!isset($_GET['passwordConfirm']) || !$adminService->isStringR($_GET['passwordConfirm'])) {
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

		$adminService->updateUserPassword($_GET['userID'], $_GET['password']);
	}
	else {
		throw new Exception('This function does not exist.');
	}

} catch (Exception $e) {

	if ($e->getMessage() === 'validation') {
		$ajaxReturn['validation'] = true;
		$ajaxReturn['fields'] = $errorFields;
	} else {
		$adminService->logError($e->getMessage());
		$ajaxReturn['error'] = true;
		$ajaxReturn['message'] = $e->getMessage();		
	}

}

echo json_encode($ajaxReturn);