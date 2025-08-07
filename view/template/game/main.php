<?php
	defined('GD_ACCESS') or die('You can not access the file directly!');
	require_once(ROOT . '/view/blocks/header.php');
	require_once(ROOT . '/view/template/bg_header.php');
	require_once(ROOT . '/view/template/bg.php');
?>
<div class="section" id="section_game" data-user-id="<?php echo $this->userInfo['id']; ?>" data-team-id="<?php echo $this->userInfo['team_id']; ?>">
	<div class="content">
		<div class="container_big content_game">
			<div class="game_left_column">
			<?php require_once(ROOT . '/views/template/cyber_left/agent_profile.php'); ?>
			<?php require_once(ROOT . '/view/template/game/dashboard_info.php'); ?>

				<?php
					$percent_class = '';

					if ($team_info['progress_percent'] >= 35 && $team_info['progress_percent'] <= 69) {
						$percent_class = ' mission_progress_percent_yellow';
					} elseif ($team_info['progress_percent'] >= 70 && $team_info['progress_percent'] <= 99) {
						$percent_class = ' mission_progress_percent_green_light';
					} elseif ($team_info['progress_percent'] == 100) {
						$percent_class = ' mission_progress_percent_green_dark mission_progress_percent_full';
					}
				?>
				<div class="mission_progress">
					<div class="mission_progress_title"><?php echo $translation['text9']; ?></div>
					<div class="mission_progress_line">
						<div class="mission_progress_percent<?php echo $percent_class; ?>" style="width: <?php echo $team_info['progress_percent']; ?>%;"></div>
						<div class="mission_progress_text"><?php echo $team_info['progress_percent'] . ' - 100'; ?></div>
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
