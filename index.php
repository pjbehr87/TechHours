<?php
	$noLogin = true;
	$pageTitle = 'Home';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/com/header.php';
?>

<div id="saveSuccess" class="alert alert-success alert-dismissable hide">
	<strong>Success!</strong> <span class="message"></span>
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
</div>
<div id="saveWarning" class="alert alert-warning alert-dismissable hide">
	<strong>Warning!</strong> Please verify that your username and password are entered correctly. 
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
</div>
<div id="saveError" class="alert alert-danger alert-dismissable hide">
	<strong>Error!</strong> There was an error while trying to save to the database, pleare refresh the page and try again.
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
</div>

<div class="col-md-4">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title"><span class="glyphicon glyphicon-user"></span> Sign In</h3>
		</div>
		<div class="panel-body">

			<div class="form-group">
				<label class="control-label" for="username">Username or Email</label>
				<input id="username" name="username" type="text" class="form-control" placeholder="Username/Email" autofocus />
			</div>
			<div class="form-group">
				<label class="control-label" for="password">Password</label>
				<input id="password" name="password" type="password" class="form-control" placeholder="Password" />
			</div>

			<div>
				<a href="#" id="forgotPassword" class="">
					Forgot Password?
				</a>
				<button id="signinButton" type="button" class="btn btn-primary pull-right" data-loading-text="<span class='glyphicon glyphicon-lock'></span> Signing in...">
					<span class="glyphicon glyphicon-lock"></span> Sign In
				</button>
			</div>

		</div>
	</div>
</div>

<div class="col-md-4">
	<div id="resetPassword" class="panel panel-default hide">
		<div class="panel-heading">
			<h3 class="panel-title"><span class="glyphicon glyphicon-repeat"></span> Reset Password</h3>
		</div>
		<div class="panel-body">

			<div class="form-group">
				<label class="control-label" for="email">Username</label>
				<label class="control-label text-muted" for="email">You must have added your email address to your account in order to reset your password</label>
				<input id="resetUser" name="username" type="text" class="form-control" placeholder="Username" autofocus />
			</div>

			<div>
				<button id="resetButton" type="button" class="btn btn-primary pull-right" data-loading-text="<span class='glyphicon glyphicon-lock'></span> Signing in...">
					<span class="glyphicon glyphicon-repeat"></span> Send Reset Email
				</button>
			</div>

		</div>
	</div>
</div>

<?php
	require_once $_SERVER['DOCUMENT_ROOT'] . '/com/footer.php';
?>