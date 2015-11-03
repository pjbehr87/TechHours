<?php
	$hourService = true;
	$useDatepicker = true;
	$useDataTable = true;
	$pageTitle = 'Archive';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/com/header.php';

	$hoursArray = $hourService->getSubmittedHours();
	if (count($hoursArray)):
		for ($i = 0; $i < count($hoursArray); ):
?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h1 class="panel-title"><?php echo $hourService->getMonthName($hoursArray[$i]['date']) . ' - ' . $hourService->getYear($hoursArray[$i]['date']) ?></h1>
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
			$totalPaid = 0;
			do {
				$hours = $hoursArray[$i];
				$totalPaid += $hours['paid'];

				staticRow($hours);

				$i++;
			} while ($i === 0 || ($i < count($hoursArray) && $hourService->getMonth($hoursArray[$i]['date']) === $hourService->getMonth($hoursArray[$i-1]['date'])));
		?>
		<tr class="bottom">
			<th colspan="5" class="text-right">
				Total: 
			</th>
			<th id="totalPaid" class="paid">
				$<?php echo number_format($totalPaid, 2) ?>
			</td>
			<th colspan="2">&nbsp;</th>
		</td>
	</table>
</div>
<?php 
		endfor;
	else:
 ?>
	<div class="alert alert-info">You currently have no hours that have been submitted.</div>
<?php
	endif;
	require_once $_SERVER['DOCUMENT_ROOT'] . '/com/footer.php';
?>