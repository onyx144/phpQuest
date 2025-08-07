<?php
if (!defined('GD_ACCESS')) {
    die('Прямой доступ запрещен');
}

?>
<div class="section" id="section_game" >
	<div class="content">
		<div class="container_big content_game">
			<div class="game_left_column">
			    <?php require_once(ROOT . '/views/template/game/cyber_left/agent_profile.php'); ?>

                <?php require_once(ROOT . '/views/template/game/dashboard_info.php'); ?>
            </div>
			<div class="game_right_column">
				<?php require_once(ROOT . '/views/template/game/dashboard_tabs.php'); ?>
				<?php require_once(ROOT . '/views/template/game/calls_tabs.php'); ?>
             <?php require_once(ROOT . '/views/template/game/files_tabs.php'); ?>
             <?php require_once(ROOT . '/views/template/game/databases_tabs.php'); ?>
             <?php require_once(ROOT . '/views/template/game/tools_tabs.php'); ?>
			</div>
		</div>
	</div>
</div>