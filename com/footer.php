	</div>
	<script src="/com/jquery/jquery.min.js"></script>
	<script src="/com/bootstrap/js/bootstrap.min.js"></script>
	<script src="/com/common.js"></script>

	<?php if (isset($useDatepicker) && $useDatepicker): ?>
		<script src="/com/bootstrap/datepicker/js/datepicker.js"></script>
	<?php endif ?>

	<?php if (isset($useSelect2) && $useSelect2): ?>
		<script src="/com/select2/select2.js"></script>
	<?php endif ?>

	<?php if (strpos($_SERVER['PHP_SELF'], 'user/')): ?>

		<?php if (strpos($_SERVER['PHP_SELF'], 'index.php')): ?>
			<script src="/user/script/index.js"></script>
		<?php endif ?>

		<?php if (strpos($_SERVER['PHP_SELF'], 'user.php')): ?>
			<script src="/user/script/user.js"></script>
		<?php endif ?>

	<?php elseif (strpos($_SERVER['PHP_SELF'], 'admin/')): ?>

		<?php if (strpos($_SERVER['PHP_SELF'], 'archive.php')): ?>
			<script src="/admin/script/archive.js"></script>
		<?php endif ?>
		<?php if (strpos($_SERVER['PHP_SELF'], 'index.php')): ?>
			<script src="/admin/script/index.js"></script>
		<?php endif ?>
		<?php if (strpos($_SERVER['PHP_SELF'], 'jobs.php')): ?>
			<script src="/admin/script/jobs.js"></script>
		<?php endif ?>
		<?php if (strpos($_SERVER['PHP_SELF'], 'submit.php')): ?>
			<script src="/admin/script/submit.js"></script>
		<?php endif ?>
		<?php if (strpos($_SERVER['PHP_SELF'], 'user.php')): ?>
			<script src="/admin/script/user.js"></script>
		<?php endif ?>

	<?php else: ?>

		<?php if (strpos($_SERVER['PHP_SELF'], 'index.php')): ?>
			<script src="/script/index.js"></script>
		<?php endif ?>
		<?php if (strpos($_SERVER['PHP_SELF'], 'newAccount.php')): ?>
			<script src="/script/newAccount.js"></script>
		<?php endif ?>
		<?php if (strpos($_SERVER['PHP_SELF'], 'password.php')): ?>
			<script src="/script/password.js"></script>
		<?php endif ?>

	<?php endif ?>
</body>
</html>