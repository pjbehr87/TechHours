<?php
	$hourService = true;
	$pageTitle = 'View Submitted Hours';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/com/printViewHeader.php';
	
	$hoursArray = $hourService->getAllSubmittedHours();
	if (count($hoursArray)):
		viewSubmitted ($hoursArray, true);
	else:
 ?>
	<div class="alert alert-info">There have been no hours submitted in the last 5 days.</div>
<?php
	endif;
	require_once $_SERVER['DOCUMENT_ROOT'] . '/com/printViewFooter.php';
?>