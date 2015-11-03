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

		<?php if (isset($useDatepicker) && $useDatepicker): ?>
			<link href="/com/bootstrap/datepicker/css/datepicker.css" rel="stylesheet" />
		<?php endif ?>

		<?php if (isset($useSelect2) && $useSelect2): ?>
			<link href="/com/select2/select2.css" rel="stylesheet" />
		<?php endif ?>

		<?php if (strpos($_SERVER['PHP_SELF'], 'user/')): ?>

			<?php if (strpos($_SERVER['PHP_SELF'], 'index.php')): ?>
				<link href="/user/style/index.css" rel="stylesheet" />
			<?php endif ?>

		<?php elseif (strpos($_SERVER['PHP_SELF'], 'admin/')): ?>

			<?php if (strpos($_SERVER['PHP_SELF'], 'archive.php')): ?>
				<link href="/admin/style/archive.css" rel="stylesheet" />
			<?php endif ?>
			<?php if (strpos($_SERVER['PHP_SELF'], 'index.php')): ?>
				<link href="/admin/style/index.css" rel="stylesheet" />
			<?php endif ?>
			<?php if (strpos($_SERVER['PHP_SELF'], 'user.php')): ?>
				<link href="/admin/style/user.css" rel="stylesheet" />
			<?php endif ?>

		<?php endif ?>

	</head>
	<body>

		<div class="container">

			<div class="header">
				<?php if ($user['logged_in'] && (strpos($_SERVER['PHP_SELF'], 'user/') || strpos($_SERVER['PHP_SELF'], 'admin/'))): ?>
					<ul class="nav nav-pills pull-right">
						<?php if (strpos($_SERVER['PHP_SELF'], 'user/')): ?>
							<?php if ($user['access'] === 0): ?>
								<li><a href="/admin/index.php"><span class="glyphicon glyphicon-link"></span> ADMIN</a></li>
							<?php endif ?>
							<li<?php if (strpos($_SERVER['PHP_SELF'], 'index.php')): ?> class="active"<?php endif ?>><a href="/user/index.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
							<li<?php if (strpos($_SERVER['PHP_SELF'], 'archive.php')): ?> class="active"<?php endif ?>><a href="/user/archive.php"><span class="glyphicon glyphicon-tasks"></span> Archive</a></li>
							<li<?php if (strpos($_SERVER['PHP_SELF'], 'user.php')): ?> class="active"<?php endif ?>><a href="/user/user.php"><span class="glyphicon glyphicon-user"></span> User</a></li>
						<?php else: ?>
							<?php if ($user['access'] === 0): ?>
								<li><a href="/user/index.php"><span class="glyphicon glyphicon-link"></span> USER</a></li>
							<?php endif ?>
							<li<?php if (strpos($_SERVER['PHP_SELF'], 'index.php')): ?> class="active"<?php endif ?>><a href="/admin/index.php"><span class="glyphicon glyphicon-home"></span> Home</a></li>
							<li class="dropdown<?php if (strpos($_SERVER['PHP_SELF'], 'viewSubmitted.php') || strpos($_SERVER['PHP_SELF'], 'submit.php')): ?> active<?php endif ?>">
								<a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-check"></span> Submit Hours<span class="caret"></span></a>
								<ul class="dropdown-menu">
									<li><a href="/admin/submit.php">Submit Hours</a></li>
									<li><a href="/admin/viewSubmitted.php">View Submitted Hours</a></li>
									<li class="divider"></li>
									<li><a target="_blank" href="/admin/viewSubmittedPrintView.php">View Submitted Hours Print View</a></li>
								</ul>
							</li>
							<li <?php if (strpos($_SERVER['PHP_SELF'], 'archive.php')): ?>class="active"<?php endif ?>><a href="/admin/archive.php"><span class="glyphicon glyphicon-tasks"></span> Archive</a></li>
							<li <?php if (strpos($_SERVER['PHP_SELF'], 'jobs.php')): ?>class="active"<?php endif ?>><a href="/admin/jobs.php"><span class="glyphicon glyphicon-briefcase"></span> Jobs Admin</a></li>
							<li<?php if (strpos($_SERVER['PHP_SELF'], 'user.php')): ?> class="active"<?php endif ?>><a href="/admin/user.php"><span class="glyphicon glyphicon-user"></span> User Admin</a></li>
							
						<?php endif ?>
						<li><a href="/logout.php"><span class="glyphicon glyphicon-off"></span> Sign out [ <?php echo $_SESSION['user']['username'] ?> ]</a></li>
					</ul>
				<?php endif ?>
				<div style="height: 60px">
					<!-- <span class="glyphicon glyphicon-cloud logo"></span> -->
					<h3><a href="/">Tech Hours</a></h3>
				</div>
			</div>