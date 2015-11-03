<?php

$hourService = true;
require_once $_SERVER['DOCUMENT_ROOT'] . '/com/initialize.php';

$jobsArray = $hourService->getJobs();
?>

<div class="panel panel-default" data-user-id="<?php echo $_GET['user_id'] ?>">
	<div class="panel-heading">
		<h1 class="panel-title"><?php echo $_GET['name'] ?></h1>
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
		<tr class="additionalRow">
			<td class="timeInput">
				<input placeholder="hh:mm am" class="form-control in1" value="">
			</td>
			<td class="timeInput">
				<input placeholder="hh:mm am" class="form-control out1" value="">
			</td>
			<td class="timeInput">
				<input placeholder="hh:mm am" class="form-control in2" value="">
			</td>
			<td class="timeInput">
				<input placeholder="hh:mm am" class="form-control out2" value="">
			</td>
			<td class="dateInput">
				<input placeholder="mm/dd/yy" class="form-control date" value="">
			</td>
			<td class="paidInput">
				<span class="paidStatic">&nbsp;</span>
			</td>
			<td class="jobInput">
				<select class="job jobSelect" name="job">
					<option></option>
					<?php foreach($jobsArray as $job): ?>			
						<option value="<?php echo $job['job_id'] ?>">
							<?php echo $job['location'] . "-" . $job['job'] ?>
						</option>
					<?php endforeach; ?>
				</select>
			</td>
			<td class="commentInput">
				<textarea class="form-control comment" value=""></textarea>
			</td>
			<?php if ($_GET['noButton'] !== 'true'): ?>
				<td class="button saveButtonColumn" rowspan="1">
					<button type="button" title="Save Rows" class="submitRow btn btn-success btn-xs" data-loading-text="<span class='glyphicon glyphicon-refresh'></span>"><i class="glyphicon glyphicon-ok"></i></button>
				</td>
			<?php endif ?>
		</tr>
		<tr class="bottom">
			<th colspan="5" class="text-right">
				Total: 
			</th>
			<th id="totalPaid" class="paid">
				$0.00
			</td>
			<th colspan="2">&nbsp;</th>
			<td>
				<button type="button" title="Add Hours" class="addRowButton btn btn-primary btn-xs"><i class="glyphicon glyphicon-plus"></i></button>
			</td>
		</td>
	</table>
</div>