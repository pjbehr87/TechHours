<?php
	$hourService = true;
	$useDatepicker = true;
	$useSelect2 = true;
	$pageTitle = 'Home';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/com/header.php';

	$hoursArray = $hourService->getUnsubmittedHours();
	$jobsArray = $hourService->getJobs();
?>
	<div id="saveSuccess" class="alert alert-success alert-dismissable <?php if (!isset($_GET['success'])) echo 'hide'; ?>">
		<strong>Success!</strong> Your hours have been saved successfully. 
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	</div>
	<div id="saveWarning" class="alert alert-warning alert-dismissable hide">
		<strong>Warning!</strong> Please check the fields below for incorrect values. 
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	</div>
	<div id="saveError" class="alert alert-danger alert-dismissable hide">
		<strong>Error!</strong> There was an error while trying to save to the database, pleare refresh the page and try again.
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading">
			<h1 class="panel-title"><span class="glyphicon glyphicon-list"></span> Current Hours</h1>
		</div>
		<div class="panel-body<?php if (count($hoursArray)) echo ' hide' ?>">
			<h4 class="text-muted">No current hours</h1>
		</div>
		<table class="table<?php if (!count($hoursArray)) echo ' hide' ?>">
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
				$totalPaid = 0;
				foreach ($hoursArray as $hours) {
					$totalPaid += $hours['paid'];
					editableRow($hours, $jobsArray);
				}
			?>
			<tr class="bottom">
				<th colspan="5" class="text-right">
					Total: 
				</th>
				<th id="totalPaid" class="paid">
					$<?php echo number_format($totalPaid, 2) ?>
				</td>
				<th colspan="3">
					&nbsp;
				</th>
			</td>
		</table>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading">
			<h1 class="panel-title"><span class="glyphicon glyphicon-time"></span> Enter Hours</h1>
		</div>
		<form id="hoursForm">
			<table class="table panel-body">
				<tr>
					<th>Time In</th>
					<th>Time Out</th>
					<th>Time In</th>
					<th>Time Out</th>
					<th>Date</th>
					<th>Job</th>
					<th>Comment</th>
					<th>&nbsp;</th>
				</tr>
				<tr class="inputRow" data-row="1">
					<td class="timeInput"><input class="form-control" name="in1_1" id="in1_1" placeholder="hh:mm am" value="" /></td>
					<td class="timeInput"><input class="form-control" name="out1_1" id="out1_1" placeholder="hh:mm am" value="" /></td>
					<td class="timeInput"><input class="form-control" name="in2_1" id="in2_1" placeholder="hh:mm am" value="" /></td>
					<td class="timeInput"><input class="form-control" name="out2_1" id="out2_1" placeholder="hh:mm am" value="" /></td>
					<td class="dateInput">
						<input type="text" class="form-control date" name="date_1" placeholder="mm/dd/yy" />
					</td>
					<td class="jobInput">
						<select class="jobSelect" name="job_1" id="job_1">
							<option></option>
							<?php foreach($jobsArray as $job): ?>		
								<option value="<?php echo $job['job_id'] ?>">
									<?php echo $job['location'] . "-" . $job['job'] ?>
								</option>
							<?php endforeach; ?>
						</select>
					</td>
					<td class="commentInput"><textarea class="form-control" name="comment_1" id="comment_1"></textarea></td>
					<td>
						<button type="button" class="btn btn-danger btn-xs removeRow pull-right"><i class="glyphicon glyphicon-minus"></i></button>
						<input type="hidden" name="rows[]" value="1" />
					</td>
				</tr>
			</table>
			<nav class="panel-body">
				<button type="button" id="addRow" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i> Add Row</button>
				<button type="button" id="submitHours" class="pull-right btn btn-primary" data-loading-text="<i class='glyphicon glyphicon-ok'></i> Saving...">
					<i class="glyphicon glyphicon-ok"></i>
					Submit
				</button>
			</nav>
		</form>
		
	</div>

<?php
	require_once $_SERVER['DOCUMENT_ROOT'] . '/com/footer.php';
?>