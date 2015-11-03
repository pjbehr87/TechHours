<?php
	$newAccount = true;
	$useDatepicker = true;
	$userService = true;
	$pageTitle = 'New Account';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/com/header.php';
?>
	<div id="saveSuccess" class="alert alert-info noHide">
		<strong>Welcome!</strong> Please update your password.
		<div>You can only use this link from the email until you log in.</div>
	</div>
	<div id="saveSuccess" class="alert alert-success alert-dismissable hide">
		<strong>Success!</strong> Your user info was saved successfully. 
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	</div>
	<div id="saveWarning" class="alert alert-warning alert-dismissable hide">
		<strong>Warning!</strong> Please check the fields below for incorrect values. 
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	</div>
	<div id="saveError" class="alert alert-danger alert-dismissable hide">
		<strong>Error!</strong> There was an error while trying to save to the database, pleare refresh the page and try again.
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	</div>

	<form id="password" class="col-lg-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h1 class="panel-title">Password</h1>
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label for="password">Password</label>
					<input type="password" id="password" name="password" class="form-control" placeholder="New Password" />
				</div>
				<div class="form-group">
					<label for="passwordConfirm">Confirm Password</label>
					<input type="password" id="passwordConfirm" name="passwordConfirm" class="form-control" placeholder="New Password" />
				</div>
				<nav>
					<button type="button" id="resetPassword" class="pull-right btn btn-primary" data-loading-text="<i class='glyphicon glyphicon-ok'></i> Saving...">
						<i class="glyphicon glyphicon-ok"></i>
						Reset Password
					</button>
				</nav>
			</div>
		</div>
	</form>
<?php
	require_once $_SERVER['DOCUMENT_ROOT'] . '/com/footer.php';
?>