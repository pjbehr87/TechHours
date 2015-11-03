$(function() {
	var $addRowButton = $('button#addRow');
	var $submitButton = $('button#submitHours');

	$('.jobSelect').select2({
		width: '200px',
		placeholder: '-- Select a Job --' 
	});
	$('body').on('click', 'button.removeRow', removeRow);
	$addRowButton.on('click', addRow);
	$submitButton.on('click', function() {
		$submitButton.button('loading');
		setTimeout(submitHours, 500);
	});

	addRow();
	setTimeout(hideAlerts, 10000);

	function addRow() {
		var $bottomRow = $('tr.inputRow').last(),
			rowCount = $bottomRow.data('row');
		rowCount++;
		$newRow = $bottomRow.clone();
		$newRow.data('row', rowCount);
		$newRow.find('.form-control, select').each(function() {
			var $this = $(this);
			var colName = $this.attr('name').substr(0, $this.attr('name').indexOf('_'));
			$this.attr('name', colName + '_' + rowCount).attr('id', colName + '_' + rowCount).val('');
			if(colName === 'date') {
				$this.datepicker({
					format: "mm/dd/yy",
					todayBtn: "linked",
					autoclose: true,
					todayHighlight: true,
					endDate: '0d'
				});
			}
		});
		$newRow.find('.select2-container').remove();
		$newRow.find('.jobSelect').removeClass('select2-offscreen').select2({
			width: '200px',
			placeholder: '-- Select a Job --' 
		});
		$newRow.find('[name="rows[]"]').attr('value', rowCount);
		$bottomRow.after($newRow);

		$('button.removeRow').removeClass('disabled');
	}

	function removeRow() {
		$(this).parents('tr.inputRow').remove();
		if ($('tr.inputRow').length === 1) {
			$('button.removeRow').addClass('disabled');
		}
	}

	function submitHours() {
		$.ajax({
			url: '/com/class/ajax/hourService.php?function=addHours',
			type: 'GET',
			data: $('#hoursForm').serialize(),
			dataType: 'json'
		})
		.done(function(data) {
			if (data.error) {
				showAlert('error');
			}
			else if (data.validation) {
				var errorFields = data.fields;
				for(var i = 0; i < errorFields.length; i++) {
					$('#'+errorFields[i]).parent().addClass('has-error');
				}
				showAlert('warning');
			}
			else {
				window.location = '/user/index.php?success';
			}
		});
	}
});