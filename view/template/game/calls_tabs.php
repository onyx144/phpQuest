<?php defined('GD_ACCESS') or die('You can not access the file directly!'); ?>
<div class="dashboard_tabs cyber-panel animate-fade-in-up" data-dashboard="calls">
	 
  <!--
	<div class="btn_wrapper btn_wrapper_blue_light call_mobile">
		<div class="btn btn_blue">
			<span><?php if (isset($translation['text308'])) { echo $translation['text308']; } ?></span>
			<span class="dashboard_item_text_qt" style="margin-left: 10px;">0</span>
		</div>
		<div class="btn_border_top"></div>
		<div class="btn_border_bottom"></div>
		<div class="btn_border_left"></div>
		<div class="btn_border_left_arcle"></div>
		<div class="btn_border_right"></div>
		<div class="btn_border_right_arcle"></div>
		<div class="btn_bg_top_line"></div>
		<div class="btn_bg_bottom_line"></div>
		<div class="btn_bg_triangle_left"></div>
		<div class="btn_bg_triangle_right"></div>
		<div class="btn_circles_top">
			<div class="btn_circle"></div>
			<div class="btn_circle"></div>
			<div class="btn_circle"></div>
			<div class="btn_circle"></div>
		</div>
		<div class="btn_circles_bottom">
			<div class="btn_circle"></div>
			<div class="btn_circle"></div>
			<div class="btn_circle"></div>
			<div class="btn_circle"></div>
		</div>
	</div>

	<div class="btn_wrapper btn_wrapper_blue call_jane">
		<div class="btn btn_blue">
			<span><?php if (isset($translation['text56'])) { echo $translation['text56']; } ?></span>
		</div>
		<div class="btn_border_top"></div>
		<div class="btn_border_bottom"></div>
		<div class="btn_border_left"></div>
		<div class="btn_border_left_arcle"></div>
		<div class="btn_border_right"></div>
		<div class="btn_border_right_arcle"></div>
		<div class="btn_bg_top_line"></div>
		<div class="btn_bg_bottom_line"></div>
		<div class="btn_bg_triangle_left"></div>
		<div class="btn_bg_triangle_right"></div>
		<div class="btn_circles_top">
			<div class="btn_circle"></div>
			<div class="btn_circle"></div>
			<div class="btn_circle"></div>
			<div class="btn_circle"></div>
		</div>
		<div class="btn_circles_bottom">
			<div class="btn_circle"></div>
			<div class="btn_circle"></div>
			<div class="btn_circle"></div>
			<div class="btn_circle"></div>
		</div>
	</div>
	-->
	<div class="dashboard_tab_titles">

		<!-- эта часть динамическая -->
		<div class="dashboard_tab_title dashboard_tab_title_active" data-tab="tab1">
			<div class="dashboard_tab_title_active_skew_right"></div>
			<div class="dashboard_tab_title_inner">
				<div class="dashboard_tab_title_img_wrapper">
					<?php echo $this->svg['dashboard_dashboard']; ?>
				</div>
				<div class="dashboard_tab_title_text"><?php echo $translation['text11']; ?></div>
			</div>
		</div>
		<!-- end - эта часть динамическая -->
	</div>
	<div class="dashboard_tab_content">
		 

		<div class="dashboard_tab_content_item_wrapper">
			<!-- эта часть динамическая -->
			<div class="dashboard_tab_content_item dashboard_tab_content_item_active" data-tab="tab1">Calls</div>
			<!-- end - эта часть динамическая -->
		</div>
	</div>
</div>