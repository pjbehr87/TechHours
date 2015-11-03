$(function () {
	$('body').on('click', '.addRowButton', function () {
		var $this = $(this),
			$thisRow = $this.parents('tr'),
			$thisTable = $this.parents('table');

		$.ajax({
			url: '/admin/application/ajaxGetHoursRow.php',
			dataType: 'html',
			data: {
				noButton: $thisTable.find('.additionalRow').length > 0
			}
		}).done(function (data) {
			var $data = $(data);
			$data.find('input.date').datepicker({
				format: "mm/dd/yy",
				todayBtn: "linked",
				autoclose: true,
				todayHighlight: true,
				endDate: '0d'
			});
			$data.find('select').select2({
				width: '225px',
				placeholder: '-- Select a Job --'
			});
			$thisRow.before($data);
			$thisTable.find('td.saveButtonColumn').attr('rowspan', (parseInt($thisTable.find('td.saveButtonColumn').attr('rowspan'))+1));
		});
	});

	$('.addUserLink').on('click', function () {
		var $this = $(this),
			userID = $this.data('userId'),
			userName = $this.text();
		$this.parents('li').remove();


		$.ajax({
			url: '/admin/application/ajaxAddUser.php',
			dataType: 'html',
			data: {
				user_id: userID,
				name: userName
			}
		}).done(function (data) {
			var $data = $(data);
			$data.find('input.date').datepicker({
				format: "mm/dd/yy",
				todayBtn: "linked",
				autoclose: true,
				todayHighlight: true,
				endDate: '0d'
			});
			$data.find('select').select2({
				width: '225px',
				placeholder: '-- Select a Job --'
			});
			$('#addUserButtonContainer').after($data);
		});
	});

	$('body').on('click', '.submitRow', function () {
		var $this = $(this),
			$thisTable = $this.parents('table'),
			thisUser = $thisTable.parents('.panel').data('userId'),
			hours = {user_id: thisUser, rows: []};

		$this.button('loading');

		$thisTable.find('.additionalRow').each(function (index) {
			var $thisRow = $(this);
			hours['in1_' + (index+1)] = $thisRow.find('.in1').val(),
			hours['out1_' + (index+1)] = $thisRow.find('.out1').val(),
			hours['in2_' + (index+1)] = $thisRow.find('.in2').val(),
			hours['out2_' + (index+1)] = $thisRow.find('.out2').val(),
			hours['date_' + (index+1)] = $thisRow.find('.date').val(),
			hours['job_' + (index+1)] = $thisRow.find('.job').select2('val'),
			hours['comment_' + (index+1)] = $thisRow.find('.comment').val(),

			hours.rows.push((index+1));
		});
		$.ajax({
			url: '/com/class/ajax/hourService.php?function=addHours',
			type: 'GET',
			data: hours,
			dataType: 'json'
		})
		.done(function(data) {
			if (data.error) {
				showAlert('error');
			}
			else if (data.validation) {
				var errorFields = data.fields;
				for(var i = 0; i < errorFields.length; i++) {
					$thisTable.find('.'+errorFields[i].split('-')[0]).parent().addClass('has-error');
				}
				showAlert('warning');
			}
			else {
				if (data.data) {
					window.location = '/admin/index.php';
				}
				else {
					$thisTable.find('.additionalRow').remove();
				}
			}
		});
	});

	$('.jobSelect').select2({
		width: '200px',
		placeholder: '-- Select a Job --' 
	});
});