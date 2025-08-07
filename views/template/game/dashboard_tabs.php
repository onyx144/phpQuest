<?php defined('GD_ACCESS') or die('You can not access the file directly!'); ?>
<div class="dashboard_tabs" data-dashboard="dashboard">
	<?php 
	if ($this->userInfo['secondStep'] == true) {
		include 'second_dashboard.php';
	} else {
		include 'first_dashboard.php';
	}
	?>
</div>

