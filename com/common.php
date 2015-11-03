<?php
	function editableRow ($hours, $jobArray, $archive = false) {
		if ($archive) {
			global $adminService;
			$service = $adminService;
		}
		else {
			global $hourService;
			$service = $hourService;
		}
?>
<tr data-hour-id="<?php echo $hours['hour_id'] ?>" class="outputRow">
	<?php if ($archive): ?>
		<td class="nameInput">
			<span class="nameStatic"><?php echo $hours['name'] ?></span>
		</td>
	<?php endif ?>
	<td class="timeInput">
		<span class="in1Static static"><?php echo $hours['in1'] != 0 ? $service->timeFormat($hours['in1']) : "&nbsp;" ?></span>
		<input placeholder="hh:mm am" class="form-control hide in1" value="<?php echo $hours['in1'] != 0 ? $service->timeFormat($hours['in1']) : '' ?>">
	</td>
	<td class="timeInput">
		<span class="out1Static static"><?php echo $hours['out1'] != 0 ? $service->timeFormat($hours['out1']) : "&nbsp;" ?></span>
		<input placeholder="hh:mm am" class="form-control hide out1" value="<?php echo $hours['out1'] != 0 ? $service->timeFormat($hours['out1']) : '' ?>">
	</td>
	<td class="timeInput">
		<span class="in2Static static"><?php echo $hours['in2'] != 0 ? $service->timeFormat($hours['in2']) : "&nbsp;" ?></span>
		<input placeholder="hh:mm am" class="form-control hide in2" value="<?php echo $hours['in2'] != 0 ? $service->timeFormat($hours['in2']) : '' ?>">
	</td>
	<td class="timeInput">
		<span class="out2Static static"><?php echo $hours['out2'] != 0 ? $service->timeFormat($hours['out2']) : "&nbsp;" ?></span>
		<input placeholder="hh:mm am" class="form-control hide out2" value="<?php echo $hours['out2'] != 0 ? $service->timeFormat($hours['out2']) : '' ?>">
	</td>
	<td class="dateInput">
		<span class="dateStatic static"><?php echo $service->dateFormat($hours['date']) ?></span>
		<input placeholder="mm/dd/yy" class="form-control date hide" value="<?php echo $service->dateFormat($hours['date']) ?>">
	</td>
	<?php if (!$archive): ?>
		<td class="paidInput">
			<span class="paidStatic">$<?php echo number_format($hours['paid'], 2) ?></span>
		</td>
	<?php endif ?>
	<td class="jobInput">
		<span class="jobStatic static"><?php echo $hours['location'] . "-" . $hours['job'] ?></span>
		<select class="hide job jobSelect" name="job">
			<option></option>
			<?php foreach($jobArray as $job): ?>			
				<option value="<?php echo $job['job_id'] ?>" <?php if ($hours['job_id'] == $job['job_id']) echo 'selected' ?>>
					<?php echo $job['location'] . "-" . $job['job'] ?>
				</option>
			<?php endforeach; ?>
		</select>
	</td>
	<td class="commentInput">
		<span class="commentStatic static"><?php echo $hours['comment']."&nbsp;" ?></span>
		<textarea class="form-control hide comment" value=""><?php echo $hours['comment'] ? $hours['comment'] : "" ?></textarea>
	</td>
	<td class="button">
		<button type="button" title="Edit Row" class="editButton btn btn-info btn-xs"><i class="glyphicon glyphicon-edit"></i></button>
		<div class="btn-group-vertical">
			<button type="button" title="Submit Changes" class="editSubmitButton btn btn-success btn-xs hide"><i class="glyphicon glyphicon-ok"></i></button>
			<button type="button" title="Cancel Changes" class="editCancelButton btn btn-warning btn-xs hide"><i class="glyphicon glyphicon-remove"></i></button>
			<button type="button" title="Delete Row" class="editDeleteButton btn btn-danger btn-xs hide"><i class="glyphicon glyphicon-minus"></i></button>
		</div>
	</td>
</tr>
<?php } ?>

<?php
	function staticRow ($hours) {
		global $hourService;
?>
<tr data-hour-id="<?php echo $hours['hour_id'] ?>" class="outputRow">
	<td class="timeOutput">
		<span class="in1Static"><?php echo $hours['in1'] != 0 ? $hourService->timeFormat($hours['in1']) : "&nbsp;" ?></span>
	</td>
	<td class="timeOutput">
		<span class="out1Static"><?php echo $hours['out1'] != 0 ? $hourService->timeFormat($hours['out1']) : "&nbsp;" ?></span>
	</td>
	<td class="timeOutput">
		<span class="in2Static"><?php echo $hours['in2'] != 0 ? $hourService->timeFormat($hours['in2']) : "&nbsp;" ?></span>
	</td>
	<td class="timeOutput">
		<span class="out2Static"><?php echo $hours['out2'] != 0 ? $hourService->timeFormat($hours['out2']) : "&nbsp;" ?></span>
	</td>
	<td class="dateOutput">
		<span class="dateStatic"><?php echo $hourService->dateFormat($hours['date']) ?></span>
	</td>
	<td class="paidOutput">
		<span class="paidStatic">$<?php echo number_format($hours['paid'], 2) ?></span>
	</td>
	<td class="jobOutput">
		<span class="jobStatic"><?php echo $hours['location'] . "-" . $hours['job'] ?></span>
	</td>
	<td class="commentOutput">
		<span class="commentStatic"><?php echo $hours['comment']."&nbsp;" ?></span>
	</td>
</tr>
<?php } ?>

<?php
	function viewSubmitted ($hoursArray, $printView) {
		global $hourService;

		for ($i = 0; $i < count($hoursArray); $i++):
?>
	<div>
		<div class="row">
			<div class="row form-group text-center">
				<h3>Central Services</h3>
			</div>
			<div class="row form-group">
				<label class="col-xs-4 control-label">Make Check Payable To:</label>
				<div class="col-xs-3 underlined"><?php echo $hoursArray[$i]['name'] ?></div>
			</div>
			<div class="row form-group">
				<label class="col-xs-4 control-label">Total Ammount:</label>
				<div class="col-xs-3 underlined">$<?php echo number_format($hoursArray[$i]['totalPaid'], 2) ?></div>
			</div>
			<div class="row form-group">
				<label class="col-xs-4 control-label">Request Made By:</label>
				<div class="col-xs-3 underlined">Brendan May</div>
			</div>
			<table class="table table-condensed table-bordered">
				<thead>
					<th>Account</th>
					<th>Ammount</th>
					<th>Invoice</th>
					<th>Notes</th>
					<th>Approved By</th>
				</thead>
				<tbody>
					<?php 
						for ($x = 0; $x < count($hoursArray[$i]['hours']); $x++):
							$hours = $hoursArray[$i]['hours'][$x];
					?>
						<tr>
							<td class="col-xs-1"><?php echo $hours['account'] ?></td>
							<td class="col-xs-1">$<?php echo number_format($hours['paid'], 2) ?></td>
							<td class="col-xs-1">&nbsp;</td>
							<td class="col-xs-7">
								<small>
									<?php if ($hours['in1'] != 0): ?>
										<?php echo $hourService->timeFormat($hours['in1']) . " - " . $hourService->timeFormat($hours['out1']) . ($hours['in2'] == 0 ? ";" : "") ?>
									<?php 
										endif;
										if ($hours['in1'] != 0 && $hours['in2'] != 0):
									?>;
									<?php
										endif;
										if ($hours['in2'] != 0):
									?>
										<?php echo $hourService->timeFormat($hours['in2']) . " - " . $hourService->timeFormat($hours['out2']) . ";" ?>
									<?php endif ?>
									<?php echo $hourService->dateFormat($hours['date']) ?>; <?php echo $hours['location'] ?> - <?php echo $hours['job'] ?>
								</small>
							</td>
							<td class="col-xs-2">&nbsp;</td>
						</tr>
					<?php endfor ?>
				</tbody>
			</table>
			<div class="row form-group">
				<label class="col-xs-4 control-label">Total Ammount:</label>
				<div class="col-xs-3 underlined">$<?php echo number_format($hoursArray[$i]['totalPaid'], 2) ?></div>
			</div>
			<div class="row form-group">
				<label class="col-xs-4 control-label">Authorizing Signature for Above Accounts:</label>
				<div class="col-xs-3 underlined">&nbsp;</div>
			</div>
		</div>
	</div>
	<?php if ($printView): ?>
		<div class="row" style="page-break-after:always">
			<div class="row form-group">
				<div class="col-xs-12">
					<strong>Important</strong>: You must attach a copy of the receipts, bills, invoices or statements supporting the above expenses. List items separately by account to be charged. Please include completed W-9 for contract labor pay requests. Please have the initials of those responsible for any account listed above.
				</div>
			</div>
			<div class="row form-group">
				<label class="col-xs-3 control-label">Name:</label>
				<div class="col-xs-4 underlined"><?php echo $hoursArray[$i]['name'] ?></div>
			</div>
			<div class="row form-group">
				<label class="col-xs-3 control-label">Street Address:</label>
				<div class="col-xs-4 underlined"><?php echo $hoursArray[$i]['street'] ?></div>
			</div>
			<div class="row form-group">
				<label class="col-xs-3 control-label">City, State, Zip:</label>
				<div class="col-xs-4 underlined"><?php echo $hoursArray[$i]['city'] . ', ' . $hoursArray[$i]['state'] . ' ' . $hoursArray[$i]['zip'] ?></div>
			</div>
		</div>
	<?php else: ?>
		<hr>
	<?php endif ?>
<?php 
		endfor;
	}
?>