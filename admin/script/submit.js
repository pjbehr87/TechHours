$(function() {

	$('.submitHours').on('click', submitHours);

	function submitHours() {
		var hourIDArray = [];
		var hourIDList;
		var $thisButton = $(this);
		$thisButton.button('loading');

		$thisButton.parents('table').find('.outputRow').each(function() {
			hourIDArray.push($(this).data('hourId'));
		});
		hourIDList = hourIDArray.join();
		console.log(hourIDList);
		$thisButton.button('reset');

		$.ajax({
			url: '/com/class/ajax/hourService.php?function=submitHours',
			type: 'GET',
			data: {
				hourIDs: hourIDList
			},
			dataType: 'json'
		})
		.done(function(data) {
			console.log(data);
			if (data.error) {

			}
			else if (data.validation) {

			}
			else {
				$thisButton.parents('div.panel').remove();
			}
		})
		.fail(function(error1, error2, error3) {
			$thisButton.button('reset');
			console.log(error1 + ' : ' + error2 + ' : ' + error3);
		});
	}
});