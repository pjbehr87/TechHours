<?php
	$adminService = true;
	$useDatepicker = true;
	$useSelect2 = true;
	$pageTitle = 'User Admin';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/com/header.php';
	
	$userArray = $adminService->getUsers();
?>

<div id="saveSuccess" class="alert alert-success alert-dismissable hide">
	<strong>Success!</strong> <span class="message"></span>
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

<div class="col-lg-3">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h1 class="panel-title"><span class="glyphicon glyphicon-list"></span> User Select</h1>
		</div>
		<div class="panel-body">
			<div class="form-group">
				<label for="userSelect">Select Current User</label>
				<div>
					<select id="userList">
						<option></option>
						<optgroup label="Admins">
						<?php
							$accessBreak = true;
							$inactiveBreak = true;
							foreach($userArray as $user):
								if (!$user['admin'] && $accessBreak):
									$accessBreak = false;
						?>
								<optgroup label="Users">
							<?php 
								elseif (!$user['active'] && $inactiveBreak):
									$inactiveBreak = false;
							?>
								</optgroup>
								<optgroup label="Inactive">
							<?php endif ?>
							<option value="<?php echo $user['user_id'] ?>">
								<span <?php if (!$user['active']) echo 'class="text-muted"' ?>>
									<?php echo strlen($user['name']) ? $user['name'] : $user['username'] ?>
								</span>
							</option>
						<?php endforeach ?>
						<?php if ($inactiveBreak): ?></optgroup><?php endif ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="newUserButton">Create New User</label>
				<button type="button" id="newUserButton" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> New User</button>
			</div>
			<div class="form-group">
				<label for="newLimitedUserButton">Create New Limited User</label>
				<button type="button" id="newLimitedUserButton" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> New Limited User</button>
			</div>
		</div>
	</div>
</div>
<div class="col-lg-4">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h1 class="panel-title"><span class="glyphicon glyphicon-user"></span> User Info</h1>
		</div>
		<div class="panel-body">
			<h4 class="text-muted">Select or Create a user</h1>
		</div>
		<form id="userInfo" class="panel-body hide">
			<div class="form-group">
				<label for="username">Username</label>
				<input id="username" name="username" class="form-control" placeholder="Username" value="" />
			</div>
			<div class="form-group">
				<label for="name">Name</label>
				<input id="name" name="name" class="form-control" placeholder="Firstname Lastname" value="" />
			</div>
			<div class="form-group">
				<label for="email">Email</label>
				<input id="email" name="email" class="form-control" placeholder="Email" value="" />
			</div>
			<div class="form-group">
				<label for="street">Street</label>
				<input id="street" name="street" class="form-control" placeholder="Street" value="" />
			</div>
			<div class="form-group">
				<label for="city">City</label>
				<input id="city" name="city" class="form-control" placeholder="City" value="" />
			</div>
			<div class="form-group">
				<label for="state">State</label>
				<input id="state" name="state" class="form-control" placeholder="State" value="" />
			</div>
			<div class="form-group">
				<label for="zip">Zip</label>
				<input id="zip" name="zip" class="form-control" placeholder="Zip" value="" />
			</div>
			<nav>
				<button type="button" id="deactivateUser" class="btn btn-danger" data-loading-text="<i class='glyphicon glyphicon-remove'></i> Saving...">
					<i class="glyphicon glyphicon-remove"></i>
					Deactivate
				</button>
				<button type="button" id="activateUser" class="btn btn-success hide" data-loading-text="<i class='glyphicon glyphicon-remove'></i> Saving...">
					<i class="glyphicon glyphicon-plus"></i>
					Activate
				</button>
				<button type="button" id="updateUser" class="pull-right btn btn-primary" data-loading-text="<i class='glyphicon glyphicon-plus'></i> Saving...">
					<i class="glyphicon glyphicon-ok"></i>
					Update
				</button>
				<input type="hidden" id="infoUserID" name="userID" value="" />
				<input type="hidden" id="admin" name="admin" value="" />
			</nav>
		</form>
		<form id="newUser" class="panel-body hide">
			<div class="form-group">
				<label for="newEmail">Email</label>
				<input id="newEmail" name="email" class="form-control" placeholder="Email" value="" />
			</div>
			<div class="form-group">
				<label for="newAdmin">Admin</label>
				<input type="checkbox" id="newAdmin" name="admin" class="form-control" value="1" />
			</div>
			<nav>
				<button type="button" id="createUser" class="pull-right btn btn-primary" data-loading-text="<i class='glyphicon glyphicon-ok'></i> Saving...">
					<i class="glyphicon glyphicon-ok"></i>
					Create Account
				</button>
			</nav>
		</form>
		<form id="newLimitedUser" class="panel-body hide">
			<div class="text-muted">
				Limited account users cannot log in. The email address is used only to send the hours submitted email notifications.
			</div>
			<div class="form-group">
				<label for="limitedEmail">Name</label>
				<input id="limitedName" name="name" class="form-control" placeholder="Name" value="" />
			</div>
			<div class="form-group">
				<label for="limitedEmail">Email</label>
				<input id="limitedEmail" name="email" class="form-control" placeholder="Email (optional)" value="" />
			</div>
			<div class="form-group">
				<label for="limitedStreet">Street</label>
				<input id="limitedStreet" name="street" class="form-control" placeholder="Street" value="" />
			</div>
			<div class="form-group">
				<label for="limitedCity">City</label>
				<input id="limitedCity" name="city" class="form-control" placeholder="City" value="" />
			</div>
			<div class="form-group">
				<label for="limitedState">State</label>
				<input id="limitedState" name="state" class="form-control" placeholder="State" value="" />
			</div>
			<div class="form-group">
				<label for="limitedZip">Zip</label>
				<input id="limitedZip" name="zip" class="form-control" placeholder="Zip" value="" />
			</div>
			<nav>
				<button type="button" id="createLimitedUser" class="pull-right btn btn-primary" data-loading-text="<i class='glyphicon glyphicon-ok'></i> Saving...">
					<i class="glyphicon glyphicon-ok"></i>
					Create Account
				</button>
			</nav>
		</form>
	</div>
</div>
<div class="col-lg-4">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h1 class="panel-title"><span class="glyphicon glyphicon-lock"></span> User Password</h1>
		</div>
		<div class="panel-body">
			<h4 class="text-muted">Select a user</h1>
		</div>
		<form id="userPassword" class="panel-body hide">
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
				<input type="hidden" id="passwordUserID" name="userID" value="" />
			</nav>
		</form>
	</div>
</div>

<?php
	require_once $_SERVER['DOCUMENT_ROOT'] . '/com/footer.php';
?>