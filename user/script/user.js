$(function() {
	var $updateUserButton = $('button#updateUser');
	var $updatePasswordButton = $('button#updatePassword');

	$('body').on('click', 'td.has-error >', function() {
		$(this).parent().removeClass('has-error');
		if ($('.has-error').length === 0) {
			$saveWarning.addClass('hide');
		}
	});
	$updateUserButton.on('click', function() {
		hideAlerts();
		$updateUserButton.button('loading');
		setTimeout(updateUserInfo, 500);
	});
	$updatePasswordButton.on('click', function() {
		hideAlerts();
		$updatePasswordButton.button('loading');
		setTimeout(updatePassword, 500);
	});

	function updateUserInfo() {

		$.ajax({
			url: '/com/class/ajax/userService.php?function=updateUserInfo',
			type: 'GET',
			data: $('form#user').serialize(),
			dataType: 'json'
		})
		.done(function(data) {
			if (data.error) {
				showAlert('error');
				$updateUserButton.button('reset');
			}
			else if (data.validation) {
				var errorFields = data.fields;
				for(var i = 0; i < errorFields.length; i++) {
					$('#'+errorFields[i]).parent().addClass('has-error');
				}
				showAlert('warning');
				$updateUserButton.button('reset');
			}
			else {
				showAlert('success');
				$updateUserButton.button('reset');
			}
		})
		.fail(function(error1, error2, error3) {
			showAlert('error');
			$updateUserButton.button('reset');
			console.log(error1 + ' : ' + error2 + ' : ' + error3);
		});
	}

	function updatePassword() {
		
		$.ajax({
			url: '/com/class/ajax/userService.php?function=updatePassword',
			type: 'GET',
			data: $('form#password').serialize(),
			dataType: 'json'
		})
		.done(function(data) {
			if (data.error) {
				showAlert('error');
				$updatePasswordButton.button('reset');
				console.log(data.message);
			}
			else if (data.validation) {
				var errorFields = data.fields;
				for(var i = 0; i < errorFields.length; i++) {
					$('#'+errorFields[i]).parent().addClass('has-error');
				}
				showAlert('warning');
				$updatePasswordButton.button('reset');
			}
			else {
				$('form#password').find('input').val('');
				showAlert('success');
				$updatePasswordButton.button('reset');
			}
		})
		.fail(function(error1, error2, error3) {
			showAlert('error');
			$updatePasswordButton.button('reset');
			console.log(error1 + ' : ' + error2 + ' : ' + error3);
		});
	}

});