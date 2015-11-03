$(function() {

	var $updateJobButton = $('#updateJob');
	var $createJobButton = $('#createJob');
	var $deactivateJobButton = $('#deactivateJob');
	var $activateJobButton = $('#activateJob');
	var $jobListSelect = $('#jobList');

	$jobListSelect.select2({
		width: '200px',
		placeholder: '-- Select a Job --'
	}).on('change', getJob);
	$('#newJobButton').on('click', newJob);
	$updateJobButton.on('click', function() {
		hideAlerts();
		$updateJobButton.button('loading');
		setTimeout(updateJob, 500);
	});
	$createJobButton.on('click', function() {
		hideAlerts();
		$createJobButton.button('loading');
		setTimeout(createJob, 500);
	});
	$deactivateJobButton.on('click', function() {
		hideAlerts();
		$deactivateJobButton.button('loading');
		setTimeout(deactivateJob, 500);
	});
	$activateJobButton.on('click', function() {
		hideAlerts();
		$activateJobButton.button('loading');
		setTimeout(activateJob, 500);
	});


	function activateJob() {
		$.ajax({
			service: 'admin',
			sFunction: 'activateJob',
			data: {
				jobID: $('#jobID').val()
			}
		})
		.done(function(out) {
			if (out.error) {
				showAlert('error');
				console.log(out.message);
			}
			else if (out.validation) {
				var errorFields = out.fields;
				console.log(errorFields);
				for(var i = 0; i < errorFields.length; i++) {
					$('#'+errorFields[i]).parent().addClass('has-error');
				}
				showAlert('warning');
			}
			else {
				$('a[data-job-id="' + $('#jobID').val() + '"]').children().removeClass('text-muted');
				$activateJobButton.addClass('hide');
				$deactivateJobButton.removeClass('hide');
				showAlert('success', 'The job has been activated.');
			}
		});
	}

	function createJob() {
		$.ajax({
			service: 'admin',
			sFunction: 'createJob',
			data: $('form#jobInfo').serialize()
		})
		.done(function(out) {
			if (out.error) {
				showAlert('error');
				console.log(out.message);
			}
			else if (out.validation) {
				var errorFields = out.fields;
				for(var i = 0; i < errorFields.length; i++) {
					$('#'+errorFields[i]).parent().addClass('has-error');
				}
				showAlert('warning');
			}
			else {
				showAlert('success', 'The job has been created.');
			}
		});
	}

	function deactivateJob() {
		$.ajax({
			service: 'admin',
			sFunction: 'deactivateJob',
			data: {
				jobID: $('#jobID').val()
			}
		})
		.done(function(out) {
			if (out.error) {
				showAlert('error');
				console.log(out.message);
			}
			else if (out.validation) {
				var errorFields = out.fields;
				console.log(errorFields);
				for(var i = 0; i < errorFields.length; i++) {
					$('#'+errorFields[i]).parent().addClass('has-error');
				}
				showAlert('warning');
			}
			else {
				$('a[data-job-id="' + $('#jobID').val() + '"]').children().addClass('text-muted');
				$deactivateJobButton.addClass('hide');
				$activateJobButton.removeClass('hide');
				showAlert('success', 'The job has been deactivated.');
			}
		});
	}

	function getJob(e) {

		e.preventDefault();
		var $this = $(this);
		$('td.has-error, div.has-error').removeClass('has-error');
		hideAlerts();

		$.ajax({
			service: 'admin',
			sFunction: 'getJob',
			data: {
				jobID: $jobListSelect.val()
			}
		})
		.done(function(out) {
			if (out.error) {
				showAlert('error');
				console.log(out.message);
			}
			else if (out.validation) {

			}
			else {
				var jobData = out.data;

				$('#jobID').val(jobData.job_id);
				$('#location').val(jobData.location);
				$('#job').val(jobData.job);
				$('#account').val(jobData.account);
				$('#rate').val(jobData.rate);
				$('#flatWarning').removeClass('hide');
				$('#flat').prop('checked', jobData.flat).prop('disabled', true);

				if (jobData.active) {
					$activateJobButton.addClass('hide');
					$deactivateJobButton.removeClass('hide');
				}
				else {
					$deactivateJobButton.addClass('hide');
					$activateJobButton.removeClass('hide');
				}

				$updateJobButton.removeClass('hide');
				$createJobButton.addClass('hide');
				$('#jobInfo').siblings('.panel-body').addClass('hide');
				$('#jobInfo').removeClass('hide');
			}
		});
	}

	function newJob() {
		var $this = $(this);

		$('#jobInfo').find('input:not([type="checkbox"])').val('');
		$('#flat').prop('checked', false).prop('disabled', false);
		$('#flatWarning').addClass('hide');


		$activateJobButton.addClass('hide');
		$deactivateJobButton.addClass('hide');

		$updateJobButton.addClass('hide');
		$createJobButton.removeClass('hide');
		$('#jobInfo').siblings('.panel-body').addClass('hide');
		$('#jobInfo').removeClass('hide');
	}

	function updateJob() {
		$.ajax({
			service: 'admin',
			sFunction: 'updateJob',
			data: $('form#jobInfo').serialize()
		})
		.done(function(out) {
			if (out.error) {
				showAlert('error');
				console.log(out.message);
			}
			else if (out.validation) {
				var errorFields = out.fields;
				console.log(errorFields);
				for(var i = 0; i < errorFields.length; i++) {
					$('#'+errorFields[i]).parent().addClass('has-error');
				}
				showAlert('warning');
			}
			else {
				showAlert('success', 'The job\'s info was updated successfully.');
				$('a[data-job-id="' + $('#jobID').val() + '"]').children().text($('#location').val() + '-' + $('#job').val());
			}
		});
	}
});