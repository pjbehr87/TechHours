<?php
	if (isset($_GET['user'])) {
		$filterUserArray = $_GET['user'];
	}
	if (isset($_GET['location'])) {
		$filterLocationArray = $_GET['location'];
	}
	if (isset($_GET['job'])) {
		$filterJobArray = $_GET['job'];
	}
	if (isset($_GET['account'])) {
		$filterAccountArray = $_GET['account'];
	}
	if (isset($_GET['startDate'])) {
		if (!$adminService->isDate($_GET['startDate'])) {
			throw new Exception('startdate is not a Date.');
		}
		$filterStartDate = $_GET['startDate'];
	}
	if (isset($_GET['endDate'])) {
		if (!$adminService->isDate($_GET['endDate'])) {
			throw new Exception('endDate is not a Date.');
		}
		$filterEndDate = $_GET['endDate'];
	}
	if (isset($_GET['groupBy'])) {
		$filterGroupBy = $_GET['groupBy'];
	}

	$sql = 	"SELECT ";
	if (!strlen($filterGroupBy)) {
		$sql .= "name, date, job, location, account, submit_username, submit_date, hours, paid ";
	}
	elseif ($filterGroupBy == 'hours') {
		$sql .= "name, date, job_id, job, location, comment, paid, hour_id, in1, out1, in2, out2, date ";
	}
	elseif ($filterGroupBy == 'user') {
		$sql .= "name, email, SUM(hours) total_hours, SUM(paid) total_paid ";
	}
	elseif ($filterGroupBy == 'job') {
		$sql .= "job, location, account, rate, flat, SUM(hours) total_hours, SUM(paid) total_paid ";
	}
	elseif ($filterGroupBy == 'location') {
		$sql .= "location, SUM(hours) total_hours, SUM(paid) total_paid ";
	}
	elseif ($filterGroupBy == 'account') {
		$sql .= "account, SUM(hours) total_hours, SUM(paid) total_paid ";
	}
	else {
		throw new Exception('Group By not Valid: ' . $filterGroupBy);
	}

	$sql .= "FROM hours_complete ";

	$sql .= "WHERE submit_date IS NOT NULL ";
	if (count($filterUserArray)) {
		$sql .= "AND user_id IN (" . implode(',', $filterUserArray) . ") ";
	}
	if (count($filterLocationArray)) {
		$sql .= "AND location IN ('" . implode("','", $filterLocationArray) . "') ";
	}
	if (count($filterJobArray)) {
		$sql .= "AND job_id IN (" . implode(',', $filterJobArray) . ") ";
	}
	if (count($filterAccountArray)) {
		$sql .= "AND account IN ('" . implode("','", $filterAccountArray) . "') ";
	}
	if (strlen($filterStartDate)) {
		$sql .= "AND date >= '" . $adminService->dateFormatDB($filterStartDate) . "' ";
	}
	if (strlen($filterEndDate)) {
		$sql .= "AND date <= '" . $adminService->dateFormatDB($filterEndDate) . "' ";
	}

	if (strlen($filterGroupBy) && $filterGroupBy != 'hours') {
		$sql .= "GROUP BY ";
	}
	if ($filterGroupBy == 'user') {
		$sql .= "user_id, name, email ";
	}
	elseif ($filterGroupBy == 'job') {
		$sql .= "job_id, job, location, account, rate, flat ";
	}
	elseif ($filterGroupBy == 'location') {
		$sql .= "location ";
	}
	elseif ($filterGroupBy == 'account') {
		$sql .= "account ";
	}


	$sql .= 'ORDER BY ';
	if (!strlen($filterGroupBy)) {
		$sql .= "date DESC, name;";
	}
	elseif ($filterGroupBy == 'hours') {
		$sql .= "date DESC, name";
	}
	elseif ($filterGroupBy == 'user') {
		$sql .= "name;";
	}
	elseif ($filterGroupBy == 'job') {
		$sql .= "location, job;";
	}
	elseif ($filterGroupBy == 'location') {
		$sql .= "location;";
	}
	elseif ($filterGroupBy == 'account') {
		$sql .= "account;";
	}
	else {
		throw new Exception('Group By not Valid');
	}