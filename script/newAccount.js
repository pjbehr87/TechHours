$(function() {
	var $createAccountButton = $('button#createAccount');

	$createAccountButton.on('click', function() {
		hideAlerts();
		$createAccountButton.button('loading');
		setTimeout(createAccount, 500);
	});

	function createAccount() {
		
		$.ajax({
			url: '/com/class/ajax/userService.php?function=createAccount',
			type: 'GET',
			data: $('form#user').serialize(),
			dataType: 'json'
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
				var access = data.data.access;

				if (access = 1) {
					window.location = '/admin/';
				}
				else {
					window.location = '/user/';
				}
			}
		});
	}
});