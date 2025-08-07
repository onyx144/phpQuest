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

	<div class="dashboard_gem_wrapper">
		<img src="<?= BASE_URI ?>/images/dashboard_gem_bg.png" class="dashboard_gem_bg" alt="">
		<!-- <img src="<?= BASE_URI ?>/images/globe-3.gif" class="dashboard_gem_globe" alt=""> -->
		<img src="<?= BASE_URI ?>/images/gifs/globe.gif" class="dashboard_gem_globe" alt="">
		<div class="dashboard_gem_wrapper_content">
			<div class="dashboard_gem_wrapper_content_title"><?php echo $translation['text46']; ?>:</div>
			<div class="dashboard_gem_wrapper_content_text"><?php echo $translation['text47']; ?></div>
		</div>
	</div>
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
			<div class="dashboard_tab_content_item dashboard_tab_content_item_active" data-tab="tab1">Dasboard
			 <div class="dashboard top-block">
			 <div class="btn_wrapper btn_wrapper_blue">
						<div class="text-in-svg">
							<img src="<?= BASE_URI ?>/images/binance_logo.png" alt="">
							<span><?php echo $translation['binance']; ?></span>
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
					</div>
					<p class="base_text"><?php echo $translation['transfer']; ?></p>
             </div>		
				<div class="dashboard_tab_content_item_new_mission_inner bg-image-container">
					<div class="bg-image-wrapper">
						<img src="<?= BASE_URI ?>/images/bg_btc.png" alt="Background" class="bg-image">
					</div>
					<div class="mission-content">
					<div class="dashboard_personal_files2_private_individuals_inputs">
					<div class="red_line"><?php echo($svg['red_line']);?></div>

					<div class="dashboard_personal_files2_private_individuals_input_wrapper dashboard_personal_files2_private_individuals_input_wrapper_firstname">
										
										    <div class="dashboard_personal_files2_private_individuals_input_border_left"></div>
                                            <input type="text" placeholder="Enter first name" value="" autocomplete="off">
											<div class="dashboard_personal_files2_private_individuals_input_border_right"></div>
                                            <div class="dashboard_personal_files2_private_individuals_firstname_error error_text_database_car_register">Required field</div>
                                        </div>
                                        <div class="dashboard_personal_files2_private_individuals_input_wrapper dashboard_personal_files2_private_individuals_input_wrapper_lastname">
                                            <div class="dashboard_personal_files2_private_individuals_input_border_right"></div>
                                            <div class="dashboard_personal_files2_private_individuals_input_border_left"></div>
											<input type="text" placeholder="Enter last name" value="" autocomplete="off">
                                            <div class="dashboard_personal_files2_private_individuals_lastname_error error_text_database_car_register">Required field</div>
                                        </div>
                                    </div>
									<div class="btn_wrapper btn_wrapper_blue dashboard_go_to_binance">
                                        <div class="btn btn_blue">
                                            <span><?php echo $translation['login']; ?></span>
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
					</div>
				</div>
			</div>
		</div>
		</div>
	