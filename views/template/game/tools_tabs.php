<?php defined('GD_ACCESS') or die('You can not access the file directly!'); ?>
<div class="dashboard_tabs" data-dashboard="tools">
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
				<div class="dashboard_tab_title_text"><?php echo $translation['text14']; ?></div>
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
			<?php include 'no_access.php'?>

		</div>
	</div>
</div>