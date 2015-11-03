$(function() {

	var $updateUserButton = $('#updateUser');
	var $createLimitedUserButton = $('#createLimitedUser');
	var $createUserButton = $('#createUser');
	var $updatePasswordButton = $('#updatePassword');
	var $deactivateUserButton = $('#deactivateUser');
	var $activateUserButton = $('#activateUser');
	var $userListSelect = $('#userList');

	$userListSelect.select2({
		width: '200px',
		placeholder: '-- Select a User --'
	}).on('change', getUser);

	$('#newUserButton').on('click', newUser);
	$('#newLimitedUserButton').on('click', newLimitedUser);

	$updateUserButton.on('click', function() {
		hideAlerts();
		$updateUserButton.button('loading');
		setTimeout(updateUserInfo, 500);
	});
	$createLimitedUserButton.on('click', function() {
		hideAlerts();
		$createLimitedUserButton.button('loading');
		setTimeout(createLimitedUser, 500);
	});
	$createUserButton.on('click', function() {
		hideAlerts();
		$createUserButton.button('loading');
		setTimeout(createUser, 500);
	});
	$updatePasswordButton.on('click', function() {
		hideAlerts();
		$updatePasswordButton.button('loading');
		setTimeout(updatePassword, 500);
	});
	$deactivateUserButton.on('click', function() {
		hideAlerts();
		$deactivateUserButton.button('loading');
		setTimeout(deactivateUser, 500);
	});
	$activateUserButton.on('click', function() {
		hideAlerts();
		$activateUserButton.button('loading');
		setTimeout(activateUser, 500);
	});


	function activateUser() {
		$.ajax({
			url: '/com/class/ajax/adminService.php?function=activateUser',
			data: {
				userID: $('#infoUserID').val()
			}
		})
		.done(function(data) {
			if (data.error) {
				showAlert('error');
				console.log(data.message);
			}
			else if (data.validation) {
				var errorFields = data.fields;
				console.log(errorFields);
				for(var i = 0; i < errorFields.length; i++) {
					$('#'+errorFields[i]).parent().addClass('has-error');
				}
				showAlert('warning');
			}
			else {
				$('a[data-user-id="' + $('#infoUserID').val() + '"]').children().removeClass('text-muted');
				$activateUserButton.addClass('hide');
				$deactivateUserButton.removeClass('hide');
				showAlert('success', 'The user\'s account has been activated.');
			}
		});
	}

	function createLimitedUser() {
		$.ajax({
			url: '/com/class/ajax/adminService.php?function=createLimitedUser',
			data: $('form#newLimitedUser').serialize()
		})
		.done(function(data) {
			if (data.error) {
				showAlert('error');
				console.log(data.message);
			}
			else if (data.validation) {
				var errorFields = data.fields;
				for(var i = 0; i < errorFields.length; i++) {
					$('#'+errorFields[i]).parent().addClass('has-error');
				}
				showAlert('warning');
			}
			else {
				showAlert('success', 'The limited account has been created, you can now add hours to this account from the admin home page.');
			}
		});
	}

	function createUser() {
		$.ajax({
			url: '/com/class/ajax/adminService.php?function=createUser',
			data: $('form#newUser').serialize()
		})
		.done(function(data) {
			if (data.error) {
				showAlert('error');
				console.log(data.message);
			}
			else if (data.validation) {
				var errorFields = data.fields;
				for(var i = 0; i < errorFields.length; i++) {
					$('#'+errorFields[i]).parent().addClass('has-error');
				}
				showAlert('warning');
			}
			else {
				showAlert('success', 'An email has been sent to the email address provided with instructions on how to create an account.');
			}
		});
	}

	function deactivateUser() {
		$.ajax({
			url: '/com/class/ajax/adminService.php?function=deactivateUser',
			data: {
				userID: $('#infoUserID').val()
			}
		})
		.done(function(data) {
			if (data.error) {
				showAlert('error');
				console.log(data.message);
			}
			else if (data.validation) {
				var errorFields = data.fields;
				console.log(errorFields);
				for(var i = 0; i < errorFields.length; i++) {
					$('#'+errorFields[i]).parent().addClass('has-error');
				}
				showAlert('warning');
			}
			else {
				$('a[data-user-id="' + $('#infoUserID').val() + '"]').children().addClass('text-muted');
				$deactivateUserButton.addClass('hide');
				$activateUserButton.removeClass('hide');
				showAlert('success', 'The user\s account has been deactivated.');
			}
		});
	}

	function getUser(e) {
		e.preventDefault();
		var $this = $(this);
		$('td.has-error, div.has-error').removeClass('has-error');
		hideAlerts();

		$.ajax({
			url: '/com/class/ajax/adminService.php?function=getUser',
			type: 'GET',
			data: {
				userID: $userListSelect.val()
			},
			dataType: 'json'
		})
		.done(function(data) {
			if (data.error) {
				showAlert('error');
			}
			else if (data.validation) {

			}
			else {
				var userData = data.data;

				$('#infoUserID').val(userData.user_id);
				$('#username').val(userData.username);
				$('#name').val(userData.name);
				$('#admin').val(userData.admin ? 1 : 2);
				if (!userData.admin) {
					$('#email, #street, #city, #state, #zip').prop('disabled', false);

					$('#email').val(userData.email);
					$('#street').val(userData.street);
					$('#city').val(userData.city);
					$('#state').val(userData.state);
					$('#zip').val(userData.zip);
				}
				else {
					$('#email, #street, #city, #state, #zip').val('').prop('disabled', true);
				}

				if (userData.active == 1) {
					$('#activateUser').addClass('hide');
					$('#deactivateUser').removeClass('hide');
				}
				else {
					$('#deactivateUser').addClass('hide');
					$('#activateUser').removeClass('hide');
				}

				$('#userPassword, #newUser').find('input:not([type="checkbox"])').val('');
				$('#passwordUserID').val(userData.user_id);

				$('#userInfo').siblings('.panel-body').addClass('hide');
				$('#userInfo').removeClass('hide');
				$('#userPassword').siblings('.panel-body').addClass('hide');
				$('#userPassword').removeClass('hide');
			}
		});
	}

	function newLimitedUser() {
		var $this = $(this);

		$('#userInfo, #userPassword, #newUser').find('input:not([type="checkbox"])').val('');

		$('#newLimitedUser').siblings('.panel-body').addClass('hide');
		$('#newLimitedUser').removeClass('hide');
		$('#userPassword').addClass('hide');
		$('#userPassword').siblings('.panel-body').removeClass('hide');
	}

	function newUser() {
		var $this = $(this);

		$('#userInfo, #userPassword, #newUser').find('input:not([type="checkbox"])').val('');

		$('#newUser').siblings('.panel-body').addClass('hide');
		$('#newUser').removeClass('hide');
		$('#userPassword').addClass('hide');
		$('#userPassword').siblings('.panel-body').removeClass('hide');
	}

	function updateUserInfo() {
		$.ajax({
			url: '/com/class/ajax/adminService.php?function=updateUserInfo',
			data: $('form#userInfo').serialize()
		})
		.done(function(data) {
			if (data.error) {
				showAlert('error');
				console.log(data.message);
			}
			else if (data.validation) {
				var errorFields = data.fields;
				console.log(errorFields);
				for(var i = 0; i < errorFields.length; i++) {
					$('#'+errorFields[i]).parent().addClass('has-error');
				}
				showAlert('warning');
			}
			else {
				showAlert('success', 'The user\'s info was updated successfully.');
				$('a[data-user-id="' + $('#infoUserID').val() + '"]').children().text($('#name').val());
			}
		});
	}

	function updatePassword() {
		$.ajax({
			url: '/com/class/ajax/adminService.php?function=updateUserPassword',
			data: $('form#userPassword').serialize()
		})
		.done(function(data) {
			if (data.error) {
				showAlert('error');
				console.log(data.message);
			}
			else if (data.validation) {
				var errorFields = data.fields;
				for(var i = 0; i < errorFields.length; i++) {
					$('#'+errorFields[i]).parent().addClass('has-error');
				}
				showAlert('warning');
			}
			else {
				$('form#password').find('input').val('');
				showAlert('success', 'The user\'s password was updated successfully.');
			}
		});
	}
});