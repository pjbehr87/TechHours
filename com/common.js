

var $saveSuccess = $('#saveSuccess');
var $saveWarning = $('#saveWarning');
var $saveError = $('#saveError');

function hideAlerts() {
	$('.alert:not(.noHide)').addClass('hide');
	$('.has-error').removeClass('has-error');
}

function showAlert(whichAlert, message) {
	switch (whichAlert) {
		case 'success':
			if (message) {
				$saveSuccess.find('.message').html(message);
			}
			$saveSuccess.removeClass('hide');
		break;
		case 'warning':
			$saveWarning.removeClass('hide');
		break;
		case 'error':
			$saveError.removeClass('hide');
		break;
		default:
			console.log('Not a real alert!');
		break;
	}
	setTimeout(hideAlerts, 10000);
}

$(function () {

	$.ajaxSetup({
		type: "GET",
		dataType: "json"
	});
	var serviceList = ',hour,user,admin,';
	$.ajaxPrefilter(function(options, originalOptions, jqXHR) {
		if (options.service && options.sFunction) {
			if (serviceList.indexOf(',' + options.service + ',') >= 0) {
				options.url = '/com/class/ajax/' + options.service + 'Service.php?function=' + options.sFunction;
			}
			else {
				console.log(options.service + ' is not a valid service name.');
			}
		}
	});
	$(document).ajaxError(function (error1, error2, error3) {
		showAlert('error');
		console.log(error1 + ' : ' + error2 + ' : ' + error3)
	})
	.ajaxComplete(function () {
		$('button').button('reset');
	});

	if ($('input.date').length) {
		$('input.date').datepicker({
			format: "mm/dd/yy",
			todayBtn: "linked",
			autoclose: true,
			todayHighlight: true,
			endDate: '0d'
		});
	}
	$('body').on('click', 'td.has-error >, div.has-error >', function() {
		$(this).parent().removeClass('has-error');
	});

	$('body').on('click', 'button.editButton', editRow);
	$('body').on('click', 'button.editSubmitButton', editSubmit);
	$('body').on('click', 'button.editCancelButton', editCancel);
	$('body').on('click', 'button.editDeleteButton', editDelete);

	function editCancel() {
		var $thisButton = $(this),
			$thisRow = $thisButton.parents('tr');

		$thisRow.find('.form-control, .jobSelect, .btn:not(.editButton)').addClass('hide');
		$thisRow.find('span.static, .editButton').removeClass('hide');
	}

	function editDelete() {
		var $thisRow = $(this).parents('tr'),
			$thisTable = $thisRow.parents('table');
			
		$.ajax({
			url: '/com/class/ajax/hourService.php?function=deleteHours',
			type: 'GET',
			data: {
				hourID: $thisRow.data('hourId')
			},
			dataType: 'json'
		})
		.done(function(data) {
			if (data.error) {
				
			}
			else {
				var hours = data.data;

				$('#totalPaid').html('$' + parseFloat(hours.totalPaid).toFixed(2));
				$thisRow.remove();
				if ($thisTable.find('.outputRow').length === 0) {
					$thisTable.addClass('hide');
					$thisTable.siblings('.hide').removeClass('hide');
				}
			}
		})
		.fail(function(error1, error2, error3) {
			console.log(error1 + ' : ' + error2 + ' : ' + error3);
		});
	}

	function editRow() {
		var $thisButton = $(this),
			$thisRow = $thisButton.parents('tr');

		$thisRow.find('span.static, .editButton').addClass('hide');
		$thisRow.find('.form-control, .jobSelect, .btn:not(.editButton)').removeClass('hide');
	}

	function editSubmit() {
		var $thisButton = $(this),
			$thisRow = $thisButton.parents('tr');
		$.ajax({
			url: '/com/class/ajax/hourService.php?function=modifyHours',
			type: 'GET',
			data: {
				hourID:		$thisRow.data('hourId'),
				in1:		$thisRow.find('.in1').val(),
				out1:		$thisRow.find('.out1').val(),
				in2:		$thisRow.find('.in2').val(),
				out2:		$thisRow.find('.out2').val(),
				date:		$thisRow.find('.date').val(),
				job:		$thisRow.find('[name="job"]').val(),
				comment:	$thisRow.find('.comment').val()
			},
			dataType: 'json'
		})
		.done(function(data) {

			if (data.error) {
				$saveError.removeClass('hide');
			}
			else if (data.validation) {

				var errorFields = data.fields;
				for(var i = 0; i < errorFields.length; i++) {
					$thisRow.find('.'+errorFields[i]).parent().addClass('has-error');
				}
				$saveWarning.removeClass('hide');
			}
			else {
				var hours = data.data;

				$thisRow.find('.in1Static').html(hours.in1);
				$thisRow.find('.out1Static').html(hours.out1);
				$thisRow.find('.in2Static').html(hours.in2);
				$thisRow.find('.out2Static').html(hours.out2);
				$thisRow.find('.dateStatic').html(hours.date);
				$thisRow.find('.jobStatic').html(hours.location + '-' + hours.job);
				$thisRow.find('.commentStatic').html(hours.comment);
				$thisRow.find('.paidStatic').html('$' + parseFloat(hours.paid).toFixed(2));
				$thisRow.parents('table').find('.totalPaid').html('$' + parseFloat(hours.totalPaid).toFixed(2));

				$('button.editCancelButton').trigger('click');
			}
		})
		.fail(function(error1, error2, error3) {
			console.log(error1 + ' : ' + error2 + ' : ' + error3);
		});
	}
});