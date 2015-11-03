<?php
	$userService = true;
	$useDatepicker = true;
	$pageTitle = 'User';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/com/header.php';
?>
	
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
	<?php if (!strlen($user['email'])): ?>
		<div class="alert alert-info">Please add your email address and verify that your other information is correct.</div>
	<?php endif ?>

	<form id="user" class="col-lg-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h1 class="panel-title"><span class="glyphicon glyphicon-user"></span> Update User Info</h1>
			</div>
			<div class="panel-body">
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
				<div class="form-group">
					<label>Email Subscriptions</label>
					<input type="checkbox" name="emailHoursSubmitted" class="form-control" placeholder="Email" value="1" <?php if ($user['email_hours_submitted']) echo 'checked' ?> />
				</div>			
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
				<nav>
					<button type="button" id="updateUser" class="pull-right btn btn-primary" data-loading-text="<i class='glyphicon glyphicon-ok'></i> Saving...">
						<i class="glyphicon glyphicon-ok"></i>
						Update
					</button>
				</nav>
			</div>
		</div>
	</form>

	<form id="password" class="col-lg-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h1 class="panel-title"><span class="glyphicon glyphicon-lock"></span> Update Password</h1>
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label for="password">New Password</label>
					<input type="password" id="password" name="password" class="form-control" placeholder="New Password" />
				</div>
				<div class="form-group">
					<label for="passwordConfirm">Confirm New Password</label>
					<input type="password" id="passwordConfirm" name="passwordConfirm" class="form-control" placeholder="New Password" />
				</div>
				<nav>
					<button type="button" id="updatePassword" class="pull-right btn btn-primary" data-loading-text="<i class='glyphicon glyphicon-ok'></i> Saving...">
						<i class="glyphicon glyphicon-ok"></i>
						Update
					</button>
				</nav>
			</div>
		</div>
	</form>
<?php
	require_once $_SERVER['DOCUMENT_ROOT'] . '/com/footer.php';
?>