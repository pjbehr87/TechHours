<?php
	$hourService = true;
	$pageTitle = 'Submit Hours';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/com/header.php';

	$hoursArray = $hourService->getAllUnsubmittedHours();
	$jobsArray = $hourService->getJobs();
	if (count($hoursArray)):
		for ($i = 0; $i < count($hoursArray); $i++):
?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h1 class="panel-title"><?php echo $hoursArray[$i]['name'] ?></h1>
	</div>
	<table class="table">
		<tr>
			<th>Time In</th>
			<th>Time Out</th>
			<th>Time In</th>
			<th>Time Out</th>
			<th>Date</th>
			<th>Paid</th>
			<th>Job</th>
			<th>Comment</th>
		</tr>
		<?php
			for ($x = 0; $x < count($hoursArray[$i]['hours']); $x++) {
				$hours = $hoursArray[$i]['hours'][$x];
				staticRow($hours);
			}
		?>
		<tr class="bottom">
			<th colspan="5" class="text-right">
				Total: 
			</th>
			<th id="totalPaid" class="paid">
				$<?php echo number_format($hoursArray[$i]['totalPaid'], 2) ?>
			</td>
			<th colspan="2">
				<button type="button" class="submitHours pull-right btn btn-primary" data-loading-text="<i class='glyphicon glyphicon-ok'></i> Saving...">
					<i class="glyphicon glyphicon-ok"></i>
					Submit
				</button>
			</th>
		</td>
	</table>
</div>
<?php 
		endfor;
	else:
 ?>
	<div class="alert alert-info">There are currently no hours to be sudmitted.</div>
<?php
	endif;
	require_once $_SERVER['DOCUMENT_ROOT'] . '/com/footer.php';
?>