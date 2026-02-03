<?php defined('GD_ACCESS') or die('You can not access the file directly!'); ?>
<div class="dashboard_tabs cyber-panel animate-fade-in-up" data-dashboard="calls">
	<!-- Loading component -->
	<div class="dashboard_tabs_loading" style="display: flex; align-items: center; justify-content: center; min-height: 400px; flex-direction: column;">
		<div class="dashboard_loading_spinner" style="width: 60px; height: 60px; border: 4px solid rgba(0, 116, 176, 0.2); border-top-color: #0074b0; border-radius: 50%; animation: spin 1s linear infinite; margin-bottom: 20px;"></div>
		<div class="dashboard_loading_text" style="color: #0074b0; font-weight: bold; font-size: 16px;">Loading</div>
	</div>
	
	<!-- Dashboard content (hidden by default, shown after loading) -->
	<div class="dashboard_tabs_content_wrapper" style="display: none;">
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
</div>