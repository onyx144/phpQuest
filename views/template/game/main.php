<?php
	defined('GD_ACCESS') or die('You can not access the file directly!');
	require_once(ROOT . '/view/blocks/header.php');
	require_once(ROOT . '/view/template/bg_header.php');
	require_once(ROOT . '/view/template/bg.php');
?>
<div class="section" id="section_game">
	<div class="content">
		<div class="container_big content_game">
			<div class="game_left_column">
				<?php require_once(ROOT . '/view/template/game/team_info.php'); ?>
				<?php require_once(ROOT . '/view/template/game/dashboard_info.php'); ?>

				<div class="mission_progress">
					<div class="mission_progress_title"><?php echo $translation['text9']; ?></div>
					<div class="mission_progress_line">
						<div class="mission_progress_percent" style="width: 0%;"></div>
						<div class="mission_progress_text">0 - 100</div>
					</div>
				</div>
			</div>

			<div class="game_right_column">
				<?php require_once(ROOT . '/view/template/game/dashboard_tabs.php'); ?>
				<?php require_once(ROOT . '/view/template/game/calls_tabs.php'); ?>
				<?php require_once(ROOT . '/view/template/game/files_tabs.php'); ?>
				<?php require_once(ROOT . '/view/template/game/databases_tabs.php'); ?>
				<?php require_once(ROOT . '/view/template/game/tools_tabs.php'); ?>
			</div>
		</div>
		<?php require_once(ROOT . '/view/template/game/hints.php'); ?>
		<?php require_once(ROOT . '/view/template/game/minigame.php'); ?>
		<?php require_once(ROOT . '/view/template/game/interpol.php'); ?>
		<?php require_once(ROOT . '/view/template/game/highscore.php'); ?>
	</div>
</div>
<?php
require_once(ROOT . '/view/template/bg_footer.php');
require_once(ROOT . '/view/template/game/chat.php');
require_once(ROOT . '/view/blocks/footer.php');
