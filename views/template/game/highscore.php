<?php defined('GD_ACCESS') or die('You can not access the file directly!'); ?>
<div class="container_big content_highscore">
	<div class="highscore_back_btn">
		<img src="<?= BASE_URI ?>/images/back_bg.png" class="back_btn_bg" alt="">
		<div class="back_btn_text"><?php echo $translation['text22']; ?></div>
	</div>
	<div class="highscore_wrapper">
		<div class="btn_wrapper btn_wrapper_blue highscore_btn highscore_btn_today">
			<div class="btn btn_blue"><?php echo $translation['text287']; ?></div>
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
		<div class="btn_wrapper btn_wrapper_blue highscore_btn highscore_btn_alltime highscore_btn_active">
			<div class="btn btn_blue"><?php echo $translation['text288']; ?></div>
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

		<div class="highscore_title">
			<svg width="29" height="29" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_140_4432)"><path d="M14.5 5.72534C11.7674 5.72534 9.54388 7.94883 9.54388 10.6815C9.54388 13.4141 11.7674 15.6376 14.5 15.6376C17.2326 15.6376 19.4561 13.4141 19.4561 10.6815C19.4561 7.94883 17.2326 5.72534 14.5 5.72534Z" fill="#00F0FF"/><path d="M13.3861 22.6474L13.3595 22.623L12.728 22.0516L11.2419 20.707L9.6261 20.8809L8.63727 20.9868L8.40283 21.0123L7.64787 21.0933L5.27032 26.1457L8.95219 26.1944L11.3353 29.0001L14.0448 23.2432L13.3861 22.6474Z" fill="#00F0FF"/><path d="M21.351 21.0933L20.596 21.0123L20.3616 20.9874L19.3727 20.8809L17.7569 20.707L16.2708 22.0516L15.6393 22.623L15.6127 22.6474L14.954 23.2432L17.6635 29.0001L20.0467 26.1944L23.7285 26.1457L21.351 21.0933Z" fill="#00F0FF"/><path d="M23.1804 10.6814L24.6591 7.38073L21.5227 5.57967L20.7791 2.03997L17.1827 2.4262L14.5005 0L11.8183 2.42626L8.22197 2.04003L7.47833 5.57967L4.34186 7.38067L5.82057 10.6813L4.34186 13.982L7.47833 15.783L8.22197 19.3227L8.49611 19.2933L9.48551 19.1868L10.4743 19.0809L10.7394 19.0525H10.74L11.8183 18.9364L12.6293 19.6699L13.4851 20.4441L13.7519 20.6854L14.1438 21.0399L14.5006 21.3627L14.8574 21.0399L15.2493 20.6854L15.516 20.4441L16.1815 19.8426L16.1821 19.8421L17.1828 18.9365L18.9306 19.124H18.9311L19.109 19.1432L19.5156 19.1868H19.5162L20.505 19.2933L20.7791 19.3228L21.5228 15.7831L24.6592 13.9821L23.1804 10.6814ZM14.5005 17.3366C10.8311 17.3366 7.84531 14.3513 7.84531 10.6813C7.84531 7.01194 10.8311 4.02613 14.5005 4.02613C18.1699 4.02613 21.1557 7.01194 21.1557 10.6813C21.1557 14.3513 18.1699 17.3366 14.5005 17.3366Z" fill="#00F0FF"/></g><defs><clipPath id="clip0_140_4432"><rect width="29" height="29" fill="white"/></clipPath></defs></svg>
			<span><?php echo $translation['text10']; ?></span>
		</div>

		<div class="highscore_table">
			<div class="highscore_table_header">
				<div class="highscore_table_row">
					<div class="highscore_table_header_cell highscore_table_cell_rank"><?php echo $translation['text290']; ?></div>
					<div class="highscore_table_header_cell highscore_table_cell_team"><?php echo $translation['text289']; ?></div>
					<div class="highscore_table_header_cell highscore_table_cell_status"><?php echo $translation['text291']; ?></div>
					<div class="highscore_table_header_cell highscore_table_cell_time"><?php echo $translation['text292']; ?></div>
					<div class="highscore_table_header_cell highscore_table_cell_hints"><?php echo $translation['text293']; ?></div>
					<div class="highscore_table_header_cell highscore_table_cell_score"><?php echo $translation['text294']; ?></div>
				</div>
			</div>
			<div class="highscore_table_body">ajax results</div>
		</div>
	</div>
</div>