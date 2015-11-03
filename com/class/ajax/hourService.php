<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

try {

	require_once $_SERVER['DOCUMENT_ROOT'] . '/com/initialize.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/com/class/hourService.php';
	$hourService = new HourService();
	
	$errorFields = array();
	$ajaxReturn = array(
		'validation'	=> false,
		'error'			=> false,
	);

	if (!isset($_GET['function'])) {
		throw new Exception('No function was given.');
	}
	elseif ($_GET['function'] === 'addHours') {

		global $user;

		if (!$hourService->isArrayR($_GET['rows'])) {
			throw new Exception('No row list.');
		}

		$rowsList = $_GET['rows'];
		$hoursArray = array();

		foreach ($rowsList as $rowNum) {

			if (!isset($_GET['in1_' . $rowNum]) || !$hourService->isTime($_GET['in1_' . $rowNum])) {
				$errorFields[] = 'in1_' . $rowNum;
			}
			if (!isset($_GET['out1_' . $rowNum]) || !$hourService->isTime($_GET['out1_' . $rowNum])) {
				$errorFields[] = 'out1_' . $rowNum;
			}
			if (!isset($_GET['in2_' . $rowNum]) || !$hourService->isTime($_GET['in2_' . $rowNum])) {
				$errorFields[] = 'in2_' . $rowNum;
			}
			if (!isset($_GET['out2_' . $rowNum]) || !$hourService->isTime($_GET['out2_' . $rowNum])) {
				$errorFields[] = 'out2_' . $rowNum;
			}
			if (!isset($_GET['date_' . $rowNum]) || !$hourService->isDate($_GET['date_' . $rowNum])) {
				$errorFields[] = 'date_' . $rowNum;
			}
			if ((!isset($_GET['job_' . $rowNum]) || !$hourService->isIntR($_GET['job_' . $rowNum])) && strlen($_GET['date_' . $rowNum])) {
				$errorFields[] = 'job_' . $rowNum;
			}

			if (strlen($_GET['in1_' . $rowNum]) && !strlen($_GET['out1_' . $rowNum])) {
				$errorFields[] = 'out1_' . $rowNum;
			}
			if (!strlen($_GET['in1_' . $rowNum]) && strlen($_GET['out1_' . $rowNum])) {
				$errorFields[] = 'in1_' . $rowNum;
			}
			if (strlen($_GET['in2_' . $rowNum]) && !strlen($_GET['out2_' . $rowNum])) {
				$errorFields[] = 'out2_' . $rowNum;
			}
			if (!strlen($_GET['in2_' . $rowNum]) && strlen($_GET['out2_' . $rowNum])) {
				$errorFields[] = 'in2_' . $rowNum;
			}
			if ((strlen($_GET['in1_' . $rowNum]) || strlen($_GET['in2_' . $rowNum])) && !strlen($_GET['date_' . $rowNum])) {
				$errorFields[] = 'date_' . $rowNum;
			}
			if (strlen($_GET['in1_' . $rowNum]) && strlen($_GET['out1_' . $rowNum]) && strtotime($_GET['in1_' . $rowNum]) > strtotime($_GET['out1_' . $rowNum])) {
				$errorFields[] = 'in1_' . $rowNum;
				$errorFields[] = 'out1_' . $rowNum;
			}
			if (strlen($_GET['in2_' . $rowNum]) && strlen($_GET['out2_' . $rowNum]) && strtotime($_GET['in2_' . $rowNum]) > strtotime($_GET['out2_' . $rowNum])) {
				$errorFields[] = 'in2_' . $rowNum;
				$errorFields[] = 'out2_' . $rowNum;
			}

			if (!count($errorFields) && strlen($_GET['date_' . $rowNum])) {
				$hours = array(
					'in1' => $hourService->timeFormatDB($_GET['in1_' . $rowNum]),
					'out1' => $hourService->timeFormatDB($_GET['out1_' . $rowNum]),
					'in2' => $hourService->timeFormatDB($_GET['in2_' . $rowNum]),
					'out2' => $hourService->timeFormatDB($_GET['out2_' . $rowNum]),
					'date' => $hourService->dateFormatDB($_GET['date_' . $rowNum]),
					'job' => $_GET['job_' . $rowNum],
					'comment' => $_GET['comment_' . $rowNum]
				);
				$hoursArray[] = $hours;
			}
		}
		if (count($errorFields)) {
			throw new Exception('validation');
		}

		if ($user['access'] < 2 && isset($_GET['user_id'])) {
			$userID = $_GET['user_id'];
		}
		else {
			$userID = $user['id'];
		}

		if (count($hours)) {
			$ajaxReturn['data'] = $hourService->addHours($hoursArray, $userID);
		}
		else {
			$ajaxReturn['data'] = false;
		}
	}
	elseif ($_GET['function'] === 'deleteHours') {

		if (!isset($_GET['hourID']) || !$hourService->isIntR($_GET['hourID'])) {
			$errorFields[] = 'hourID';
			throw new Exception('validation');
		}

		$ajaxReturn['data'] = $hourService->deleteHours($_GET['hourID']);
	}
	elseif ($_GET['function'] === 'modifyHours') {

		if (!isset($_GET['hourID']) || !$hourService->isIntR($_GET['hourID'])) {
			$errorFields[] = 'hourID';
		}
		if (!isset($_GET['in1']) || !$hourService->isTime($_GET['in1'])) {
			$errorFields[] = 'in1';
		}
		if (!isset($_GET['out1']) || !$hourService->isTime($_GET['out1'])) {
			$errorFields[] = 'out1';
		}
		if (!isset($_GET['in2']) || !$hourService->isTime($_GET['in2'])) {
			$errorFields[] = 'in2';
		}
		if (!isset($_GET['out2']) || !$hourService->isTime($_GET['out2'])) {
			$errorFields[] = 'out2';
		}
		if (!isset($_GET['date']) || !$hourService->isDate($_GET['date'])) {
			$errorFields[] = 'date';
		}
		if (!isset($_GET['job']) || !$hourService->isIntR($_GET['job'])) {
			$errorFields[] = 'job';
		}

		if (strlen($_GET['in1']) && !strlen($_GET['out1'])) {
			$errorFields[] = 'out1';
		}
		if (!strlen($_GET['in1']) && strlen($_GET['out1'])) {
			$errorFields[] = 'in1';
		}
		if (strlen($_GET['in2']) && !strlen($_GET['out2'])) {
			$errorFields[] = 'out2';
		}
		if (!strlen($_GET['in2']) && strlen($_GET['out2'])) {
			$errorFields[] = 'in2';
		}
		if ((strlen($_GET['in1']) || strlen($_GET['in2'])) && !strlen($_GET['date'])) {
			$errorFields[] = 'date';
		}
		if (strlen($_GET['in1']) && strlen($_GET['out1']) && strtotime($_GET['in1']) > strtotime($_GET['out1'])) {
			$errorFields[] = 'in1';
			$errorFields[] = 'out1';
		}
		if (strlen($_GET['in2']) && strlen($_GET['out2']) && strtotime($_GET['in2']) > strtotime($_GET['out2'])) {
			$errorFields[] = 'in2';
			$errorFields[] = 'out2';
		}

		if (count($errorFields)) {
			throw new Exception('validation');
		}

		$ajaxReturn['data'] = $hourService->modifyHours(
			$_GET['hourID'],
			$hourService->timeFormatDB($_GET['in1']),
			$hourService->timeFormatDB($_GET['out1']),
			$hourService->timeFormatDB($_GET['in2']),
			$hourService->timeFormatDB($_GET['out2']),
			$hourService->dateFormatDB($_GET['date']),
			$_GET['job'],
			$_GET['comment']
		);
	}
	elseif ($_GET['function'] === 'submitHours') {

		if (!isset($_GET['hourIDs']) || !$hourService->isStringR($_GET['hourIDs'])) {
			$errorFields[] = 'hourIDs';
			throw new Exception('validation');
		}

		$ajaxReturn['data'] = $hourService->submitHours($_GET['hourIDs']);
	}
	else {
		throw new Exception('This function does not exist.');
	}

} catch (Exception $e) {

	if ($e->getMessage() === 'validation') {
		$ajaxReturn['validation'] = true;
		$ajaxReturn['fields'] = $errorFields;
	} else {
		$hourService->logError($e->getMessage() . ' : Line(' . $e->getLine() . ')');
		$ajaxReturn['error'] = true;
		$ajaxReturn['message'] = $e->getMessage();		
	}

}

echo json_encode($ajaxReturn);