<?php 
defined('GD_ACCESS') or die('You can not access the file directly!');
// Подключаем SVG если еще не подключен
if (!isset($GLOBALS['svg'])) {
	require_once(ROOT . '/views/template/svg.php');
}
// Создаем локальную переменную $svg для совместимости
if (!isset($svg) && isset($GLOBALS['svg'])) {
	$svg = $GLOBALS['svg'];
}
?>
<div class="section_main_bg_header">
	<div class="container">
		<?php if ($this->userInfo) { ?>
			<div class="music_wrapper cyber-music-toggle">
				<div class="music_content">
					<div class="music_icon">🔊</div>
					<div class="music_controls">
						<div class="music_on<?php if ($this->userInfo['music'] == 'on') { echo ' music_active'; } ?>"><?php if (isset($translation['text2'])) { echo $translation['text2']; } ?></div>
						<div class="music_off<?php if ($this->userInfo['music'] == 'off') { echo ' music_active'; } ?>"><?php if (isset($translation['text3'])) { echo $translation['text3']; } ?></div>
					</div>
				</div>
				<?php
					$voiceClipId = 2;
					$voiceClipPos = 'music';
					require ROOT . '/view/template/game/voice_clip_widget.php';
				?>
			</div>

			<div class="score_wrapper">
				<div class="score_bg">
					<?php 
					if (isset($svg) && isset($svg['back_importnat'])) { 
						echo $svg['back_importnat']; 
					} elseif (isset($GLOBALS['svg']['back_importnat'])) { 
						echo $GLOBALS['svg']['back_importnat']; 
					}
					?>
				</div>
				<div class="score_values">
					<div class="score_text"><?php if (isset($translation['text5'])) { echo $translation['text5']; } ?></div>
					<div class="score">
						<?php
							// if (isset($team_info)) { echo $team_info['score']; }
							for ($i=-1000; $i < $this->settings['max_score']; $i++) { 
								echo '<span class="score_' . $i . ($team_info['score'] == $i ? ' score_active' : '') . '">' . $i . '</span>';
							}
						?>
					</div>
				</div>
				<!-- <div id="socket_log"></div> -->
			</div>

			<div class="timer_wrapper">
				<div class="timer_bg">
					<?php 
					if (isset($svg) && isset($svg['back_importnat'])) { 
						echo $svg['back_importnat']; 
					} elseif (isset($GLOBALS['svg']['back_importnat'])) { 
						echo $GLOBALS['svg']['back_importnat']; 
					} 
					?>
				</div>
				<div class="timer_values">
					<div class="timer_text"><?php if (isset($translation['text19'])) { echo $translation['text19']; } ?></div>
					<div class="timer" data-timer="<?php echo $team_info['timer_second']; ?>">
						<div class="timer_hour"></div>
						<div class="timer_two_dots">:</div>
						<div class="timer_minute"></div>
						<div class="timer_two_dots">:</div>
						<div class="timer_second"></div>
					</div>
				</div>
			</div>

			 

		 
			<div class="language cyber-language-toggle">
				<div class="language_content">
					<div class="language_flag"><img src="/<?php echo $this->lang->getParam('flag'); ?>" alt=""></div>
					<div class="language_name"><?php if (isset($translation['text18'])) { echo $translation['text18']; } ?></div>
				</div>
			</div>
			<div class="language_hidden">
				<?php
					$activeLangCode = strtr((string) $this->lang->getParam('lang'), ['а' => 'a', 'А' => 'A']);
					$activeLangs = $this->lang->getAllActiveLangs('id');
					$hasUkrainian = false;

					if ($activeLangs) {
						foreach ($activeLangs as $langItem) {
							$langCode = strtr((string) $langItem['lang'], ['а' => 'a', 'А' => 'A']);
							$langName = $langItem['lang_name'];
							$langFlag = $langItem['flag'];
							$isActive = ($langCode === $activeLangCode);
							if ($langCode === 'ua' || $langCode === 'uk') {
								$hasUkrainian = true;
							}

							if ($isActive) {
								echo '<div class="language_hidden_item language_hidden_item_active"><img src="/' . $langFlag . '" alt=""> ' . $langName . '</div>';
							} else {
								echo '<a href="#" class="language_hidden_item js-set-app-lang" data-lang="' . htmlspecialchars($langCode, ENT_QUOTES, 'UTF-8') . '"><img src="/' . $langFlag . '" alt=""> ' . $langName . '</a>';
							}
						}
					}

					// Fallback: если украинский не пришел из БД, показываем его в переключателе вручную.
					if (!$hasUkrainian) {
						if ($activeLangCode === 'ua' || $activeLangCode === 'uk') {
							echo '<div class="language_hidden_item language_hidden_item_active"><img src="/images/flags/ua.png" alt=""> Ukrainian</div>';
						} else {
							echo '<a href="#" class="language_hidden_item js-set-app-lang" data-lang="ua"><img src="/images/flags/ua.png" alt=""> Ukrainian</a>';
						}
					}
				?>
			</div>

			<div class="exit cyber-exit-btn">
				<span class="exit_text"><?php if (isset($translation['text17'])) { echo $translation['text17']; } ?></span>
			</div>
		<?php } ?>
	</div>
	
	 
	<?php if ($this->userInfo) { ?>
		 
	<?php } ?>
	<div class="main_logo">
		<?php 
		if (isset($svg) && isset($svg['aegis_logo'])) { 
			echo $svg['aegis_logo']; 
		} elseif (isset($GLOBALS['svg']['aegis_logo'])) { 
			echo $GLOBALS['svg']['aegis_logo']; 
		}
		?>
	</div>
</div>