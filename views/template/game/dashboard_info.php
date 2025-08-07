<?php defined('GD_ACCESS') or die('You can not access the file directly!'); ?>
<div class="dashboard_info">
	<div class="dashboard_bg">
		<img src="<?= BASE_URI ?>/images/dashboard_bg.png" alt="">
	</div>
	<div class="dashboard_bg_triangle">
		<svg width="23" height="26" viewBox="0 0 23 26" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.78873e-07 13L22.5 0.00961903L22.5 25.9904L1.78873e-07 13Z" fill="#00F0FF"/></svg>
	</div>
	<div class="dashboard_bg_square_right"></div>
	<div class="dashboard_bg_square_left"></div>
	<div class="dashboard_list">
		<div class="dashboard_item" data-dashboard="dashboard">
			<div class="img_wrapper"><?php echo $svg['dashboard_dashboard']; ?></div>
			<div class="dashboard_item_text"><?php echo $translation['text11']; ?></div>
		</div>
		<div class="dashboard_item" data-dashboard="calls">
			<div class="img_wrapper"><?php echo $svg['dashboard_calls']; ?></div>
			<div class="dashboard_item_text">
				<?php echo $translation['text12']; ?>
				<span class="dashboard_item_text_qt">0</span>
			</div>
		</div>
		<div class="dashboard_item" data-dashboard="files">
			<div class="img_wrapper"><?php echo $svg['dashboard_files']; ?></div>
			<div class="dashboard_item_text">
				<?php echo $translation['text15']; ?>
				<span class="dashboard_item_text_qt">0</span>
			</div>
		</div>
		<div class="dashboard_item" data-dashboard="databases">
			<div class="img_wrapper"><?php echo $svg['dashboard_databases']; ?></div>
			<div class="dashboard_item_text">
				<?php echo $translation['text13']; ?>
				<span class="dashboard_item_text_qt">0</span>
			</div>
		</div>
		<div class="dashboard_item" data-dashboard="tools">
			<div class="img_wrapper"><?php echo $svg['dashboard_tools']; ?></div>
			<div class="dashboard_item_text">
				<?php echo $translation['text14']; ?>
				<span class="dashboard_item_text_qt">0</span>
			</div>
		</div>
	</div>
</div>