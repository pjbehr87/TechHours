$(function() {
	var $resetPasswordButton = $('button#resetPassword');

	$resetPasswordButton.on('click', function() {
		hideAlerts();
		$resetPasswordButton.button('loading');
		setTimeout(resetPassword, 500);
	});


	$('input').on('keydown', function(e) {
		if (e.which === 13) {
			e.preventDefault();
			$resetPasswordButton.trigger('click');
		}
	});

	function resetPassword () {
		
		$.ajax({
			url: '/com/class/ajax/userService.php?function=updatePassword',
			data: $('form#password').serialize()
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
				window.location = '/';
			}
		});
	}
});