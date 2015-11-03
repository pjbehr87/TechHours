<?php
	$adminService = true;
	$useDatepicker = true;
	$useSelect2 = true;
	$pageTitle = 'Jobs';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/com/header.php';
	
	$jobArray = $adminService->getJobs();
?>

<div id="saveSuccess" class="alert alert-success alert-dismissable hide">
	<strong>Success!</strong> <span class="message"></span>
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

<div class="col-lg-3">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h1 class="panel-title"><span class="glyphicon glyphicon-list"></span> Job Select</h1>
		</div>
		<div class="panel-body">
			<div class="form-group">
				<label for="jobSelect">Select Current Job</label>
				<div>
					<select id="jobList">
						<option></option>
						<?php foreach($jobArray as $job): ?>
							<option value="<?php echo $job['job_id'] ?>">
								<span <?php if (!$job['active']) echo 'class="text-muted"'?>>
									<?php echo $job['location'] . '-' . $job['job'] ?>
								</span>
							</option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label for="newJobButton">Create New Job</label>
				<div>
					<button type="button" id="newJobButton" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> New Job</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="col-lg-4">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h1 class="panel-title"><span class="glyphicon glyphicon-briefcase"></span> Job Info</h1>
		</div>
		<div class="panel-body">
			<h4 class="text-muted">Select or Create a Job</h1>
		</div>
		<form id="jobInfo" class="panel-body hide">
			<div class="form-group">
				<label for="username">Location</label>
				<input id="location" name="location" class="form-control" placeholder="Location" value="" />
			</div>
			<div class="form-group">
				<label for="name">Job</label>
				<input id="job" name="job" class="form-control" placeholder="Job" value="" />
			</div>
			<div class="form-group">
				<label for="account">Account Number</label>
				<input id="account" name="account" class="form-control" placeholder="Account Number" value="" />
			</div>
			<div class="form-group">
				<label for="rate">Rate</label>
				<input id="rate" name="rate" class="form-control" placeholder="$00.00" value="" />
			</div>			
			<div class="form-group">
				<label for="flat">Flat Rate</label>
				<span id="flatWarning" class="pull-right text-muted">cannot change for existing jobs</span>
				<input type="checkbox" id="flat" name="flat" class="form-control" value="1" />
			</div>
			<nav>
				<button type="button" id="deactivateJob" class="btn btn-danger" data-loading-text="<i class='glyphicon glyphicon-remove'></i> Saving...">
					<i class="glyphicon glyphicon-remove"></i>
					Deactivate
				</button>
				<button type="button" id="activateJob" class="btn btn-success hide" data-loading-text="<i class='glyphicon glyphicon-remove'></i> Saving...">
					<i class="glyphicon glyphicon-plus"></i>
					Activate
				</button>
				<button type="button" id="updateJob" class="pull-right btn btn-primary" data-loading-text="<i class='glyphicon glyphicon-plus'></i> Saving...">
					<i class="glyphicon glyphicon-ok"></i>
					Update
				</button>
				<button type="button" id="createJob" class="pull-right btn btn-primary hide" data-loading-text="<i class='glyphicon glyphicon-plus'></i> Saving...">
					<i class="glyphicon glyphicon-ok"></i>
					Create
				</button>
				<input type="hidden" id="jobID" name="jobID" value="" />
			</nav>
		</form>
	</div>
</div>

<?php
	require_once $_SERVER['DOCUMENT_ROOT'] . '/com/footer.php';
?>