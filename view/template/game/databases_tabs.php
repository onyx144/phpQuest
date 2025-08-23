<?php defined('GD_ACCESS') or die('You can not access the file directly!'); ?>
<div class="dashboard_tabs cyber-panel animate-fade-in-up" data-dashboard="databases">
	 

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
			<div class="dashboard_tab_content_item dashboard_tab_content_item_active" data-tab="tab1">Databases</div>
			<!-- end - эта часть динамическая -->
		</div>
	</div>
</div>