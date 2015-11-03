$(function() {

	var $signinButton = $('#signinButton');
	var $resetButton = $('#resetButton');
	var $forgotPasswordLink = $('#forgotPassword');

	$signinButton.on('click', function() {
		hideAlerts();
		$signinButton.button('loading');
		setTimeout(signinWithAjax, 500);
	});
	$resetButton.on('click', function() {
		hideAlerts();
		$resetButton.button('loading');
		setTimeout(sendResetEmail, 500);
	});
	$forgotPasswordLink.on('click', showForgotPassword);

	$('input').on('keydown', function(e) {
		if (e.which === 13) {
			e.preventDefault();
			if ($('#resetUser').is(':focus')) {
				$resetButton.trigger('click');
			}
			else {
				$signinButton.trigger('click');
			}
		}
	});

	$('.alert').alert();

	function getParameterByName (name) {
		name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
		var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
		results = regex.exec(location.search);
		return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
	}

	function sendResetEmail () {

		$.ajax({
			url: '/com/class/ajax/userService.php?function=resetPassword',
			data: {
				username: $('#resetUser').val()
			}
		})
		.done(function(out) {

			if (out.error) {
				showAlert('error');
				console.log(out.message);
			} else if (out.validation) {
				var errorFields = out.fields;
				for(var i = 0; i < errorFields.length; i++) {
					$('#'+errorFields[i]).parent().addClass('has-error');
				}
				showAlert('warning');
			} else {
				showAlert('success', 'If the account provided has an email assigned to it an email will be sent with instructions on how to reset your password.');
			}
		});
	}

	function showForgotPassword () {
		$('#resetPassword').removeClass('hide');
		$('#resetUser').val($('#username').val());
	}

	function signinWithAjax () {

		$.ajax({
			data: {
				username: $('#username').val(),
				password: $('#password').val()
			},
			dataType: 'json',
			type: 'GET',
			url: '/com/class/ajax/userService.php?function=login'
		})
		.done(function(out) {

			if (out.validation) {
				// vaildation error
				showAlert('warning');
				$('#username').parent('.form-group').addClass('has-error');
				$('#password').parent('.form-group').addClass('has-error');

			} else if (out.error) {
				// server error
				showAlert('error');

			} else {
				// log in
				if (out.data === 0 || out.data === 2) {
					window.location = '/user/index.php';
				} else if (out.data === 1) {
					window.location = '/admin/index.php';
				}
			}

		});
	}

});