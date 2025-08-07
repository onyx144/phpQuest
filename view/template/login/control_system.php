<?php
	defined('GD_ACCESS') or die('You can not access the file directly!');
	require_once(ROOT . '/view/blocks/header.php');
	require_once(ROOT . '/view/template/bg_header.php');
	require_once(ROOT . '/view/template/bg.php');
?>
<div class="section" id="section_control_system">
	<div class="content">
		<div class="mission_control_title"><?php echo $translation['text10']; ?></div>
		<div class="game_code_field_wrapper">
			<div class="game_code_field_wrapper_inner">
				<span class="border_right"></span>
				<span class="border_left"></span>
				<input type="text" placeholder="<?php echo $translation['text2']; ?>" data-placeholder2="<?php echo $translation['text5']; ?>" value="" autocomplete="off">
				<div class="svg_right">
					<svg width="796" height="69" viewBox="0 0 796 69" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M870 1L803 68L384.5 68L317.5 0.999952L5.85733e-06 0.999924" stroke="#FF004E"/></svg>
				</div>
				<div class="svg_left">
					<svg width="795" height="69" viewBox="0 0 795 69" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M-75 68L-8 1H410.5L477.5 68H795" stroke="#FF004E"/></svg>
				</div>
			</div>
			<div class="game_code_error_code"><?php echo $translation['text4']; ?></div>
		</div>
		<div class="btn_wrapper btn_wrapper_blue">
			<div class="btn btn_blue btn_control_system btn_control_system_page_send_code"><?php echo $translation['text3']; ?></div>
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
<?php
require_once(ROOT . '/view/template/bg_footer.php');
require_once(ROOT . '/view/blocks/footer.php');
