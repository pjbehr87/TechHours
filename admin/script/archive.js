$(function() {

	var $accountSelect = $('#account');
	var $jobSelect = $('#job');
	var $locationSelect = $('#location');

	$('select').each(function () {
		$this = $(this);
		$(this).select2({
			width: '250px',
			placeholder: $this.attr('placeholder'),
			allowClear: true
		});
	});
	$('#clearGroupBy').on('click', function() {
		$('#groupBy').find('label').removeClass('active');
		$('#groupBy').find('input').prop('checked', false);
		$(this).blur();
	});
	$('#submitFilter').on('click', function() {
		$('form#filterForm').submit();
	});
	$accountSelect.on('change', function () {
		if ($accountSelect.val()) {
			$jobSelect.select2('enable', false);
			$locationSelect.select2('enable', false);
		}
		else {
			$jobSelect.select2('enable', true);
			$locationSelect.select2('enable', true);
		}
	});
	$jobSelect.on('change', function () {
		if ($jobSelect.val()) {
			$accountSelect.select2('enable', false);
			$locationSelect.select2('enable', false);
		}
		else {
			$accountSelect.select2('enable', true);
			$locationSelect.select2('enable', true);
		}
	});
	$locationSelect.on('change', function () {
		if ($locationSelect.val()) {
			$jobSelect.select2('enable', false);
			$accountSelect.select2('enable', false);
		}
		else {
			$jobSelect.select2('enable', true);
			$accountSelect.select2('enable', true);
		}
	});
});