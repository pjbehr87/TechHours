<?php

	// TODO: remove this after development wraps up
	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	// build page title
	if (!isset($pageTitle)) {
		$pageTitle = 'Tech Hours';
	} else {
		$pageTitle = $pageTitle . ' | Tech Hours';
	}

	// initialize services
	require_once $_SERVER['DOCUMENT_ROOT'] . '/com/initialize.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/com/common.php';

?>

<!DOCTYPE html>
<html dir="ltr" lang="en-US">
	<head>
		<meta charset="UTF-8" />
		<title><?php echo $pageTitle; ?></title>
		<meta name="language" content="en_US" />
		<meta http-equiv="content-language" content="en_US" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />

		<link href="/com/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
		<link href="/com/common.css" rel="stylesheet" />
		<?php if (strpos($_SERVER['PHP_SELF'], 'viewSubmittedPrintView.php')): ?>
			<link href="/admin/style/viewSubmitted.css" rel="stylesheet" />
		<?php endif ?>
	</head>
	<body>

		<div class="container">