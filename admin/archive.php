<?php
	$adminService = true;
	$useDatepicker = true;
	$useSelect2 = true;
	$pageTitle = 'Archive';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/com/header.php';

	$errorMessage = '';
	$sql = '';
	$filterGroupBy = '';
	$filterUserArray = array();
	$filterLocationArray = array();
	$filterJobArray = array();
	$filterAccountArray = array();
	$filterStartDate = '';
	$filterEndDate = '';
	if (isset($_GET['filter'])) {
		try {
			require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/application/filter.php';
			$result = $adminService->mysqli->query($sql);
			$hoursArray = array();
			while ($row = $result->fetch_assoc()) {
				$hoursArray[] = $row;
			}
		}
		catch (Exception $error) {
			$errorMessage = $error->getMessage();
		}
	}

	$allUserArray = $adminService->getUsers();
	$userArray = array();
	$adminArray = array();
	foreach ($allUserArray as $user) {
		if ($user['admin']) {
			$adminArray[] = $user;
		}
		else {
			$userArray[] = $user;
		}
	}
	$jobArray = $adminService->getJobs();
	$locationArray = array();
	$accountArray = array();
	foreach ($jobArray as $job) {
		if ($job['location'] != end($locationArray)) {
			$locationArray[] = $job['location'];
		}
		if (!in_array($job['account'], $accountArray)) {
			$accountArray[] = $job['account'];
		}
	}
	sort($accountArray);
?>
<div id="saveWarning" class="alert alert-warning alert-dismissable hide">
	<strong>Warning!</strong> Please check the fields below for incorrect values. 
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
</div>
<div id="saveError" class="alert alert-danger alert-dismissable <?php if (!strlen($errorMessage)) echo 'hide' ?>">
	<strong>Error!</strong> <?php echo $errorMessage ?>
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
</div>

<div id="searchContainer" class="panel panel-default">
	<div class="panel-heading">
		<h1 class="panel-title"><span class="glyphicon glyphicon-list"></span> Archive Filter</h1>
	</div>
	<form id="filterForm" action="" type="get" class="panel-body">
		<div class="col-lg-4">
			<div class="form-group">
				<label for="user">User</label>
				<div>
					<select id="user" name="user[]" multiple placeholder="-- Select a User --">
						<option></option>
						<?php foreach ($userArray as $user): ?>
							<option value="<?php echo $user['user_id'] ?>" <?php if (in_array($user['user_id'], $filterUserArray)) echo 'selected' ?>>
								<span <?php if ($user['active']) echo 'class="text-muted"' ?>>
									<?php echo strlen($user['name']) ? $user['name'] : $user['username'] ?>
								</span>
							</option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="account">Account</label>
				<div>
					<select id="account" name="account[]" multiple placeholder="-- Select a Account --" >
						<option></option>
						<?php foreach ($accountArray as $account): ?>
							<option value="<?php echo $account ?>" <?php if (in_array($account, $filterAccountArray)) echo 'selected' ?>>
								<?php echo $account ?>
							</option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="form-group">
				<label for="job">Job</label>
				<div>
					<select id="job" name="job[]" multiple placeholder="-- Select a Job --">
						<option></option>
						<?php foreach ($jobArray as $job): ?>
							<option value="<?php echo $job['job_id'] ?>" <?php if (in_array($job['job_id'], $filterJobArray)) echo 'selected' ?>>
								<span <?php if ($job['active']) echo 'class="text-muted"' ?>>
									<?php echo $job['location'] . '-' . $job['job'] ?>
								</span>
							</option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="location">Location</label>
				<div>
					<select id="location" name="location[]" multiple placeholder="-- Select a Location --">
						<option></option>
						<?php foreach ($locationArray as $location): ?>
							<option value="<?php echo $location ?>" <?php if (in_array($location, $filterLocationArray)) echo 'selected' ?>>
								<?php echo $location ?>
							</option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="form-group">
				<label for="">Group By</label>
				<div>
					<div id="groupBy" class="btn-group" data-toggle="buttons">
						<label class="btn btn-default btn-sm <?php if ($filterGroupBy === 'hours') echo 'active' ?>">
							<input type="radio" name="groupBy" id="groupByHours" value="hours" <?php if ($filterGroupBy === 'hours') echo 'checked' ?>> <abbr title="Can modify hours in this view">Hours*</abbr>
						</label>
						<label class="btn btn-default btn-sm <?php if ($filterGroupBy === 'user') echo 'active' ?>">
							<input type="radio" name="groupBy" id="groupByUser" value="user" <?php if ($filterGroupBy === 'user') echo 'checked' ?>> User
						</label>
						<label class="btn btn-default btn-sm <?php if ($filterGroupBy === 'job') echo 'active' ?>">
							<input type="radio" name="groupBy" id="groupByJob" value="job" <?php if ($filterGroupBy === 'job') echo 'checked' ?>> Job
						</label>
						<label class="btn btn-default btn-sm <?php if ($filterGroupBy === 'location') echo 'active' ?>">
							<input type="radio" name="groupBy" id="groupByLocation" value="location" <?php if ($filterGroupBy === 'location') echo 'checked' ?>> Location
						</label>
						<label class="btn btn-default btn-sm <?php if ($filterGroupBy === 'account') echo 'active' ?>">
							<input type="radio" name="groupBy" id="groupByLocation" value="account" <?php if ($filterGroupBy === 'account') echo 'checked' ?>> Acct
						</label>
					</div>
					<button type="button" id="clearGroupBy" class="btn btn-default btn-xs">
						<span class="glyphicon glyphicon-remove"></span>
					</button>
				</div>
			</div>
			<div class="form-group">
				<label for="startDate">Date Range</label>
				<div class="datesContainer">
					<div class="dateContainer pull-left">
						<input id="startDate" name="startDate" class="date form-control" placeholder="Start Date" value="<?php echo $filterStartDate ?>">
					</div>
					<div class="dateContainer pull-right">
						<input id="endDate" name="endDate" class="date form-control" placeholder="End Date" value="<?php echo $filterEndDate ?>">
					</div>
				</div>
			</div>
		</div>
		<input type="hidden" name="filter">
	</form>
	<nav class="panel-body">
		<a href="/admin/archive.php" class="btn btn-danger pull-left"><span class="glyphicon glyphicon-ban-circle"></span> Clear</a>
		<button type="button" id="submitFilter" class="pull-right btn btn-success" data-loading-text="<i class='glyphicon glyphicon-search'></i> Saving...">
			<i class="glyphicon glyphicon-search"></i>
			Filter
		</button>
	</nav>
</div>
<?php if (isset($_GET['filter'])): ?>
	<div class="panel panel-default">
		<table class="table">
		<?php switch ($filterGroupBy):
				case 'hours': ?>
			<thead>
				<tr>
					<th>Name</th>
					<th>In</th>
					<th>Out</th>
					<th>In</th>
					<th>Out</th>
					<th>Date</th>
					<th>Job</th>
					<th>Comments</th>
				</tr>
			</thead>
			<tbody>
			<?php 
				for ($i = 0; $i < count($hoursArray); $i++) {
					$hours = $hoursArray[$i];
					editableRow($hours, $jobArray, true);
				}
			?>
			</tbody>
			<?php break;
				case 'user': ?>
			<thead>
				<tr>
					<th>Name</th>
					<th>Email</th>
					<th>Total Hours</th>
					<th>Total Paid</th>
				</tr>
			</thead>
			<tbody>
			<?php for ($i = 0; $i < count($hoursArray); $i++): $hours = $hoursArray[$i] ?>
				<tr>
					<td><?php echo $hours['name'] ?></td>
					<td><?php echo $hours['email'] ?></td>
					<td><?php echo $hours['total_hours'] ?></td>
					<td><?php echo $hours['total_paid'] ?></td>
				</tr>
			<?php endfor ?>
			</tbody>
			<?php break;
				case 'job': ?>
			<thead>
				<tr>
					<th>Job</th>
					<th>Account</th>
					<th>Rate</th>
					<th>Total Hours</th>
					<th>Total Paid</th>
				</tr>
			</thead>
			<tbody>
			<?php for ($i = 0; $i < count($hoursArray); $i++): $hours = $hoursArray[$i] ?>
				<tr>
					<td><?php echo $hours['location'] . '-' . $hours['job'] ?></td>
					<td><?php echo $hours['account'] ?></td>
					<td><?php echo $hours['rate'] . ($hours['flat'] == 1 ? ' (flat)' : '') ?></td>
					<td><?php echo $hours['total_hours'] ?></td>
					<td><?php echo $hours['total_paid'] ?></td>
				</tr>
			<?php endfor ?>
			</tbody>
			<?php break;
				case 'location': ?>
			<thead>
				<tr>
					<th>Location</th>
					<th>Total Hours</th>
					<th>Total Paid</th>
				</tr>
			</thead>
			<tbody>
			<?php for ($i = 0; $i < count($hoursArray); $i++): $hours = $hoursArray[$i] ?>
				<tr>
					<td><?php echo $hours['location'] ?></td>
					<td><?php echo $hours['total_hours'] ?></td>
					<td><?php echo $hours['total_paid'] ?></td>
				</tr>
			<?php endfor ?>
			</tbody>
			<?php break;
				case 'account': ?>
			<thead>
				<tr>
					<th>Account</th>
					<th>Total Hours</th>
					<th>Total Paid</th>
				</tr>
			</thead>
			<tbody>
			<?php for ($i = 0; $i < count($hoursArray); $i++): $hours = $hoursArray[$i] ?>
				<tr>
					<td><?php echo $hours['account'] ?></td>
					<td><?php echo $hours['total_hours'] ?></td>
					<td><?php echo $hours['total_paid'] ?></td>
				</tr>
			<?php endfor ?>
			</tbody>
			<?php break;
				default: ?>
			<thead>
				<tr>
					<th>Name</th>
					<th>Hours</th>
					<th>Date</th>
					<th>Job</th>
					<th>Account</th>
					<th>Submit User</th>
					<th>Submit Date</th>
					<th>Paid</th>
				</tr>
			</thead>
			<tbody>
			<?php for ($i = 0; $i < count($hoursArray); $i++): $hours = $hoursArray[$i] ?>
				<tr>
					<td><?php echo $hours['name'] ?></td>
					<td><?php echo $hours['hours'] ?></td>
					<td><?php echo $adminService->dateFormat($hours['date']) ?></td>
					<td><?php echo $hours['location'] . '-' . $hours['job'] ?></td>
					<td><?php echo $hours['account'] ?></td>
					<td><?php echo $hours['submit_username'] ?></td>
					<td><?php echo $adminService->dateFormat($hours['submit_date']) ?></td>
					<td><?php echo $hours['paid'] ?></td>
				</tr>
			<?php endfor ?>
			</tbody>
		<?php break;
		endswitch; ?>
		</table>
	</div>
<?php		
	endif
?>
	
<?php
	require_once $_SERVER['DOCUMENT_ROOT'] . '/com/footer.php';
?>