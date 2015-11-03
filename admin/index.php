<?php
	$hourService = true;
	$useDatepicker = true;
	$useSelect2 = true;
	$pageTitle = 'Home';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/com/header.php';

	$hoursArray = $hourService->getAllUnsubmittedHours();
	$usersArray = $hourService->getNoHoursUsers();
	$jobArray = $hourService->getJobs();
?>
<div id="addUserButtonContainer" class="btn-group">
	<button class="btn btn-success addUserButton dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-plus"></span> Add User Hours <span class="caret"></span></button>
	<ul class="dropdown-menu" role="menu">
		<?php foreach ($usersArray as $user): ?>
			<li><a class="addUserLink" href="#" data-user-id="<?php echo $user['user_id'] ?>"><?php echo $user['name'] ?></a></li>
		<?php endforeach ?>
	</ul>
</div>
<?php
	if (count($hoursArray)):
		for ($i = 0; $i < count($hoursArray); $i++):
?>
	<div class="panel panel-default" data-user-id="<?php echo $hoursArray[$i]['user_id'] ?>">
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
				<th>&nbsp;</th>
			</tr>
			<?php
				for ($x = 0; $x < count($hoursArray[$i]['hours']); $x++) {
					editableRow($hoursArray[$i]['hours'][$x], $jobArray);
				}
			?>
			<tr class="bottom">
				<th colspan="5" class="text-right">
					Total: 
				</th>
				<th class="paid totalPaid">
					$<?php echo number_format($hoursArray[$i]['totalPaid'], 2) ?>
				</td>
				<th colspan="2">&nbsp;</th>
				<td>
					<button type="button" title="Add Hours" class="addRowButton btn btn-primary btn-xs"><i class="glyphicon glyphicon-plus"></i></button>
				</td>
			</td>
		</table>
	</div>
<?php 
		endfor;
	else:
 ?>
	<div class="alert alert-info">There are currently no hours left to be sudmitted.</div>
<?php
	endif;
	require_once $_SERVER['DOCUMENT_ROOT'] . '/com/footer.php';
?>