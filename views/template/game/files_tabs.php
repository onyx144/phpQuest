<?php defined('GD_ACCESS') or die('You can not access the file directly!'); ?>
<div class="dashboard_tabs" data-dashboard="files">
	<div class="dashboard_tab_title_bg_dots">
		<div class="dashboard_tab_title_bg_dot"></div>
		<div class="dashboard_tab_title_bg_dot"></div>
		<div class="dashboard_tab_title_bg_dot"></div>
		<div class="dashboard_tab_title_bg_dot"></div>
		<div class="dashboard_tab_title_bg_dot"></div>
		<div class="dashboard_tab_title_bg_dot"></div>
		<div class="dashboard_tab_title_bg_dot"></div>
		<div class="dashboard_tab_title_bg_dot"></div>
	</div>
	<div class="dashboard_tab_content_bg_dots">
		<div class="dashboard_tab_content_bg_dot"></div>
		<div class="dashboard_tab_content_bg_dot"></div>
		<div class="dashboard_tab_content_bg_dot"></div>
		<div class="dashboard_tab_content_bg_dot"></div>
		<div class="dashboard_tab_content_bg_dot"></div>
		<div class="dashboard_tab_content_bg_dot"></div>
		<div class="dashboard_tab_content_bg_dot"></div>
		<div class="dashboard_tab_content_bg_dot"></div>
	</div>

	<div class="dashboard_tab_titles">
		<!-- эта часть динамическая -->
		<div class="dashboard_tab_title dashboard_tab_title_active" data-tab="tab1">
			<div class="dashboard_tab_title_active_skew_right"></div>
			<div class="dashboard_tab_title_inner">
				<div class="dashboard_tab_title_img_wrapper">
					<?php echo $this->svg['dashboard_dashboard']; ?>
				</div>
				<div class="dashboard_tab_title_text"><?php echo $translation['text15']; ?></div>
			</div>
		</div>
		<!-- end - эта часть динамическая -->
	</div>
	<div class="dashboard_tab_content">
		<div class="dashboard_tab_content_bg_right_square">
			<img src="<?= BASE_URI ?>/images/dashboard_content_right_bg.png" alt="">
			<div class="dashboard_tab_content_bg_svg_right">
				<svg width="9" height="224" viewBox="0 0 9 224" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 203.5L1.81198e-05 223.412L8.35416e-06 -4.5383e-05L8.99999 21.3345L9 203.5Z" fill="#00F0FF"/></svg>
			</div>
		</div>
		<div class="dashboard_tab_content_bg_right_square2"></div>
		<div class="dashboard_tab_content_bg_left_square"></div>
		<div class="dashboard_tab_content_bg_svg_left">
			<svg width="9" height="224" viewBox="0 0 9 224" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M-8.70388e-07 19.9122L8.99998 3.93402e-07L8.99997 223.412L-8.83309e-06 202.078L-8.70388e-07 19.9122Z" fill="#00F0FF"/></svg>
		</div>

		<div class="dashboard_tab_content_item_wrapper">
			<!-- эта часть динамическая -->
			<div class="dashboard_tab_content_item dashboard_tab_content_item_mission_briefing dashboard_tab_content_item_active" data-tab="tab1">
				<div class="dashboard_tab_content_file_item" 
					 data-type="video" 
					 data-path="zombie/video/en/1_mission_briefing.mp4" 
					 data-file-with-path="1_mission_briefing.mp4" 
					 data-file-id="1">
					<div class="dashboard_tab_content_file_item_img_wrapper">
						<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M11.1252 0.5H8.93717L5.18701 3.3125H7.37504L11.1252 0.5Z" fill="#00F0FF"></path>
							<path d="M14.9064 0.5H12.6872L8.93701 3.3125H11.125L14.9064 0.5Z" fill="#00F0FF"></path>
							<path d="M12.687 3.31248H15.9998V0.968728C15.9998 0.932571 15.987 0.900634 15.9792 0.866821L12.687 3.31248Z" fill="#00F0FF"></path>
							<path d="M1.40576 3.3125H3.62504L7.37523 0.5H5.18717L1.40576 3.3125Z" fill="#00F0FF"></path>
							<path d="M0 15.0312C0 15.2903 0.209656 15.5 0.46875 15.5H15.5312C15.7903 15.5 16 15.2903 16 15.0312V4.25H0V15.0312ZM5.65625 6.59375C5.65625 6.42484 5.74687 6.26919 5.89384 6.18587C6.04081 6.10256 6.22116 6.10484 6.36625 6.19181L11.0538 9.00431C11.1947 9.089 11.2812 9.24144 11.2812 9.40622C11.2812 9.571 11.1947 9.72344 11.0538 9.80812L6.36625 12.6206C6.22106 12.7076 6.04066 12.7098 5.89384 12.6266C5.74687 12.5433 5.65625 12.3877 5.65625 12.2188V6.59375Z" fill="#00F0FF"></path>
							<path d="M0.46875 0.5C0.209656 0.5 0 0.709656 0 0.96875V3.19553L3.6255 0.5H0.46875Z" fill="#00F0FF"></path>
							<path d="M6.59375 7.42188V11.3907L9.90156 9.40628L6.59375 7.42188Z" fill="#00F0FF"></path>
						</svg>
					</div>
					<div class="dashboard_tab_content_file_item_name">
						1. <?php echo $translation['mision']; ?>
					</div>
				</div>
			</div>
			<!-- end - эта часть динамическая -->
		</div>
	</div>
</div>