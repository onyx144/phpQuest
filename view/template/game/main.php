<?php
	defined('GD_ACCESS') or die('You can not access the file directly!');
	require_once(ROOT . '/view/blocks/header.php');
	require_once(ROOT . '/view/template/bg_header.php');
	require_once(ROOT . '/view/template/bg.php');
	require_once(ROOT . '/view/template/error_popup.php');
?>
 
<div class="section" id="section_game" data-user-id="<?php echo $this->userInfo['id']; ?>" data-team-id="<?php echo $this->userInfo['team_id']; ?>">
	<div class="content">
		<div class="container_big content_game">
			<div class="game_left_column">
			<?php require_once(ROOT . '/views/template/cyber_left/acgent_container.php'); ?>				 
			</div>
			<div class="game_right_column">
				<?php require_once(ROOT . '/views/template/cyber_right/dasboard_button.php'); ?>
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
