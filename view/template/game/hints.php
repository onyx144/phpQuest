<?php defined('GD_ACCESS') or die('You can not access the file directly!'); ?>
<div class="container_big content_hints">
	<div class="hint_back_btn">
		<img src="/images/back_bg.png" class="back_btn_bg" alt="">
		<div class="back_btn_text"><?php echo $translation['text22']; ?></div>
	</div>
	<div class="active_hints">
		<img src="/images/active_hints_bg.png" class="active_hints_bg" alt="">
		<div class="active_hints_content">
			<div class="active_hints_score">
				<img src="/images/active_hints_score_bg.png" class="active_hints_score_bg" alt="">
				<div class="active_hints_score_title"><?php echo $translation['text23']; ?></div>
				<div class="active_hints_score_value">
					<?php
						// if (isset($team_info)) { echo $team_info['score']; }
						for ($i=-1000; $i < $this->settings['max_score']; $i++) { 
							echo '<span class="score_' . $i . ($team_info['score'] == $i ? ' score_active' : '') . '">' . $i . '</span>';
						}
					?>
				</div>
			</div>
			<div class="active_hints_title"><?php echo $translation['text24']; ?></div>
			<div class="active_hints_value">
				<div class="active_hints_value_top">
					<div class="active_hints_value_top_triangle_left"></div>
					<div class="active_hints_value_top_triangle_left_border"></div>
				</div>
				<div class="active_hints_value_middle">
					<div class="active_hints_value_middle_scroll"></div>
				</div>
				<div class="active_hints_value_bottom">
					<div class="active_hints_value_bottom_triangle_right"></div>
					<div class="active_hints_value_bottom_triangle_right_border"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="list_hints">
		<img src="/images/list_hint_bg.png" class="list_hints_bg" alt="">
		<div class="list_hints_content">
			<div class="list_hints_content_left">
				<div class="list_hints_content_left_title">
					<span><?php echo $translation['text26']; ?></span>
					<svg width="27" height="29" viewBox="0 0 27 29" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.72127 0H26.4713V2.19232L23.9136 5.1154H0.163574V2.92308L2.72127 0Z" fill="#00F0FF"/><path d="M8.56746 10.2307H26.4713V12.423L23.9136 15.3461H6.00977V13.1538L8.56746 10.2307Z" fill="#00F0FF"/><path d="M7.47121 24.1155L11.4904 24.1155V26.3078L9.66352 28.5001L5.64429 28.5001V26.3078L7.47121 24.1155Z" fill="#00F0FF"/><path d="M11.125 14.6153L11.125 22.2884L8.93268 22.2884L6.0096 19.7307L6.0096 13.1538L8.20192 13.1538L11.125 14.6153Z" fill="#00F0FF"/><path d="M20.625 10.596L20.625 0.730659L22.8173 0.730659L26.4711 1.46143L26.4711 10.9614L23.5481 10.9614L20.625 10.596Z" fill="#00F0FF"/></svg>
				</div>
				<div class="list_hints_content_left_text"><?php echo $translation['text27']; ?></div>
			</div>
			<div class="list_hints_content_right"></div>
		</div>
	</div>
</div>