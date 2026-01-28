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
<div class="section_main_bg_footer">
	<div class="main_line_footer">
		<svg width="1920" height="69" viewBox="0 0 1920 69" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M-89 68L-22 1H396.5L463.5 68H1006L1073 1H1860.5L1927.5 68" stroke="#00F0FF"/></svg>
	</div>
	<div class="container">
		 
		<div class="new_game_text">NEW GAME</div>
		 
		<?php if ($this->userInfo) { ?>
			<div class="btn_wrapper btn_wrapper_blue btn_wrapper_view_highscore">
				<div class="btn_bg">
					<?php 
					if (isset($svg) && isset($svg['back_importnat'])) { 
						echo $svg['back_importnat']; 
					} elseif (isset($GLOBALS['svg']['back_importnat'])) { 
						echo $GLOBALS['svg']['back_importnat']; 
					}
					?>
				</div>
				<div class="btn_content">
					<div class="btn btn_blue btn_bottom btn_view_highscore">
						<img src="/images/medal_icon.png" alt="">
						<span><?php if (isset($translation['text10'])) { echo $translation['text10']; } ?></span>
					</div>
				</div>
			</div>

			<div class="btn_wrapper btn_wrapper_blue btn_wrapper_view_hints">
				<div class="btn_bg">
					<?php 
					if (isset($svg) && isset($svg['back_importnat'])) { 
						echo $svg['back_importnat']; 
					} elseif (isset($GLOBALS['svg']['back_importnat'])) { 
						echo $GLOBALS['svg']['back_importnat']; 
					}
					?>
				</div>
				<div class="btn_content">
					<div class="btn btn_blue btn_bottom btn_view_hints">
						<img src="/images/icon_hints.png" alt="">
						<span><?php if (isset($translation['text21'])) { echo $translation['text21']; } ?></span>
					</div>
				</div>
			</div>

			<div class="btn_wrapper btn_wrapper_blue btn_wrapper_view_chat">
				<div class="btn_bg">
					<?php 
					if (isset($svg) && isset($svg['back_importnat'])) { 
						echo $svg['back_importnat']; 
					} elseif (isset($GLOBALS['svg']['back_importnat'])) { 
						echo $GLOBALS['svg']['back_importnat']; 
					}
					?>
				</div>
				<div class="btn_content">
					<div class="btn btn_blue btn_bottom btn_view_chat">
						<img src="/images/chat_icon.png" alt="">
						<span><?php if (isset($translation['text20'])) { echo $translation['text20']; } ?></span>
					</div>
				</div>
			</div>
		<?php } ?>
	</div>
	<!-- <div class="circle_right_gif">
		<img src="/images/footer_right_circle.gif" alt="">
	</div> -->
</div>