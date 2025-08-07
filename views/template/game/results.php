<?php
	defined('GD_ACCESS') or die('You can not access the file directly!');
	require_once(ROOT . '/view/blocks/header.php');
	require_once(ROOT . '/view/template/bg_header.php');
	require_once(ROOT . '/view/template/bg.php');
?>
<div class="section section_window_results" id="section_game">
	<div class="content">
		<div class="container_big content_game"></div>
		<?php require_once(ROOT . '/view/template/game/highscore.php'); ?>
	</div>
</div>
<?php
require_once(ROOT . '/view/template/bg_footer.php');
require_once(ROOT . '/view/blocks/footer.php');
