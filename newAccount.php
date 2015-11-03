<?php
	$newAccount = true;
	$useDatepicker = true;
	$userService = true;
	$pageTitle = 'New Account';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/com/header.php';

	if ($newAccount):
?>
	<div id="saveSuccess" class="alert alert-info noHide">
		<strong>Welcome!</strong> Please fill in your user info and update your password.
		<div>You can only use this link from the email up until you log in the first time.</div>
	</div>
<?php endif; ?>
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

	<form id="user" class="col-lg-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h1 class="panel-title">User Info</h1>
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
				<hr>
				<div class="form-group">
					<label for="username">Username</label>
					<input id="username" name="username" class="form-control" placeholder="Username" value="<?php echo $user['username'] ?>" />
				</div>
				<div class="form-group">
					<label for="name">Name</label>
					<input id="name" name="name" class="form-control" placeholder="Firstname Lastname" value="<?php echo $user['name'] ?>" />
				</div>
				<div class="form-group">
					<label for="email">Email</label>
					<input id="email" name="email" class="form-control" placeholder="Email" value="<?php echo $user['email'] ?>" />
				</div>
				<?php if ($user['access'] === 2): ?>
					<div class="form-group">
						<label for="street">Street</label>
						<input id="street" name="street" class="form-control" placeholder="Street" value="<?php echo $user['street'] ?>" />
					</div>
					<div class="form-group">
						<label for="city">City</label>
						<input id="city" name="city" class="form-control" placeholder="City" value="<?php echo $user['city'] ?>" />
					</div>
					<div class="form-group">
						<label for="state">State</label>
						<input id="state" name="state" class="form-control" placeholder="State" value="<?php echo $user['state'] ?>" />
					</div>
					<div class="form-group">
						<label for="zip">Zip</label>
						<input id="zip" name="zip" class="form-control" placeholder="Zip" value="<?php echo $user['zip'] ?>" />
					</div>
				<?php endif; ?>
				<nav>
					<button type="button" id="createAccount" class="pull-right btn btn-primary" data-loading-text="<i class='glyphicon glyphicon-ok'></i> Saving...">
						<i class="glyphicon glyphicon-ok"></i>
						Create Account and Log In
					</button>
					<input type="hidden" id="access" name="access" value="<?php echo $user['access'] ?>" />
				</nav>
			</div>
		</div>
	</form>
<?php
	require_once $_SERVER['DOCUMENT_ROOT'] . '/com/footer.php';
?>