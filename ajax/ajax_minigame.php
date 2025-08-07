<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/ajax/ajax_header.php');

if (isset($_POST['op'])) {
	$return = [];

	$steps = [
		'1' => [ // первый шаг
			'person_blue' => ['top' => 50, 'left' => 91, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_red1' => ['bottom' => 50, 'left' => 10, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 150, 'left' => 110, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 'initial', 'right' => 'initial', 'top' => 'initial', 'display' => 'none'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 80, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 60, 'right' => 30, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 20, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => false, 'bottom' => true, 'up' => true, 'right' => true]
		],
		'1_error_down' => [ // первый шаг, ложное нажатие
			'person_blue' => ['top' => 104, 'left' => 91, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_red1' => ['bottom' => 100, 'left' => 90, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 210, 'left' => 90, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 'initial', 'right' => 'initial', 'top' => 'initial', 'display' => 'none'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 160, 'right' => 80, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 130, 'right' => 100, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 20, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => false, 'bottom' => true, 'up' => true, 'right' => true]
		],
		'1_error_up' => [ // первый шаг, ложное нажатие
			'person_blue' => ['top' => 10, 'left' => 91, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_red1' => ['bottom' => 100, 'left' => 90, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 210, 'left' => 90, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 'initial', 'right' => 'initial', 'top' => 'initial', 'display' => 'none'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 160, 'right' => 80, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 130, 'right' => 100, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 20, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => false, 'bottom' => true, 'up' => true, 'right' => true]
		],
		'2' => [ // второй шаг
			// 'person_blue' => ['top' => 50, 'left' => 160, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			// 'person_blue' => ['top' => 50, 'left' => 180, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_blue' => ['top' => 50, 'left' => 190, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_red1' => ['bottom' => 100, 'left' => 90, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 210, 'left' => 90, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 'initial', 'right' => 'initial', 'top' => 'initial', 'display' => 'none'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 160, 'right' => 80, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 130, 'right' => 100, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 20, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => false, 'bottom' => true, 'up' => true, 'right' => true]
		],
		'2_error_right' => [ // второй шаг, ложное нажатие
			/*'person_blue' => ['top' => 50, 'left' => 226, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_red1' => ['bottom' => 100, 'left' => 90, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 210, 'left' => 90, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 'initial', 'right' => 'initial', 'top' => 'initial', 'display' => 'none'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 160, 'right' => 80, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 130, 'right' => 100, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 20, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => false, 'bottom' => true, 'up' => true, 'right' => true]*/
			'person_blue' => ['top' => 50, 'left' => 226, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_red1' => ['bottom' => 100, 'left' => 220, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 300, 'left' => 90, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 'initial', 'right' => 'initial', 'top' => 'initial', 'display' => 'none'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 210, 'right' => 80, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 180, 'right' => 100, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 20, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => false, 'bottom' => true, 'up' => true, 'right' => true]
		],
		'2_error_up' => [ // второй шаг, ложное нажатие
			/*'person_blue' => ['top' => 10, 'left' => 226, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_red1' => ['bottom' => 100, 'left' => 90, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 210, 'left' => 90, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 'initial', 'right' => 'initial', 'top' => 'initial', 'display' => 'none'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 160, 'right' => 80, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 130, 'right' => 100, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 20, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => false, 'bottom' => true, 'up' => true, 'right' => true]*/
			// 'person_blue' => ['top' => 10, 'left' => 180, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_blue' => ['top' => 10, 'left' => 190, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_red1' => ['bottom' => 100, 'left' => 220, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 300, 'left' => 90, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 'initial', 'right' => 'initial', 'top' => 'initial', 'display' => 'none'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 210, 'right' => 80, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 180, 'right' => 100, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 20, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => false, 'bottom' => true, 'up' => true, 'right' => true]
		],
		'3' => [ // третий шаг
			// 'person_blue' => ['top' => 110, 'left' => 226, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			// 'person_blue' => ['top' => 110, 'left' => 180, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_blue' => ['top' => 110, 'left' => 190, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_red1' => ['bottom' => 100, 'left' => 220, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 300, 'left' => 90, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 'initial', 'right' => 'initial', 'top' => 'initial', 'display' => 'none'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 210, 'right' => 80, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 180, 'right' => 100, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 20, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => false, 'bottom' => true, 'up' => true, 'right' => true]
		],
		'3_error_right' => [ // третий шаг, ложное нажатие
			'person_blue' => ['top' => 110, 'left' => 270, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_red1' => ['bottom' => 100, 'left' => 340, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 340, 'left' => 90, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 'initial', 'right' => 'initial', 'top' => 'initial', 'display' => 'none'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 280, 'right' => 110, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 240, 'right' => 150, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 20, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => false, 'bottom' => true, 'up' => true, 'right' => true]
		],
		'3_error_up' => [ // третий шаг, ложное нажатие
			// 'person_blue' => ['top' => 60, 'left' => 226, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			// 'person_blue' => ['top' => 50, 'left' => 180, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_blue' => ['top' => 50, 'left' => 190, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_red1' => ['bottom' => 100, 'left' => 340, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 340, 'left' => 90, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 'initial', 'right' => 'initial', 'top' => 'initial', 'display' => 'none'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 280, 'right' => 110, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 240, 'right' => 150, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 20, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => false, 'bottom' => true, 'up' => true, 'right' => true]
		],
		'4' => [ // четвертый шаг
			// 'person_blue' => ['top' => 230, 'left' => 226, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			// 'person_blue' => ['top' => 230, 'left' => 180, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_blue' => ['top' => 170, 'left' => 190, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_red1' => ['bottom' => 100, 'left' => 340, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 340, 'left' => 90, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 'initial', 'right' => 'initial', 'top' => 'initial', 'display' => 'none'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 280, 'right' => 110, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 240, 'right' => 150, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 20, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => false, 'bottom' => true, 'up' => true, 'right' => true]
		],
		'4_error_right' => [ // четвертый шаг, ложное нажатие
			'person_blue' => ['top' => 250, 'left' => 310, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			/*'person_red1' => ['bottom' => 100, 'left' => 410, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 340, 'left' => 190, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 'initial', 'right' => 'initial', 'top' => 'initial', 'display' => 'none'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 330, 'right' => 20, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 150, 'right' => 150, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 20, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => true, 'bottom' => false, 'up' => true, 'right' => true]*/
			'person_red1' => ['bottom' => 100, 'left' => 410, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 340, 'left' => 190, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 'initial', 'right' => 'initial', 'top' => 'initial', 'display' => 'none'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 330, 'right' => 20, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 150, 'right' => 150, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 20, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => true, 'bottom' => false, 'up' => true, 'right' => true]
		],
		'4_error_up' => [ // четвертый шаг, ложное нажатие
			// 'person_blue' => ['top' => 110, 'left' => 226, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			// 'person_blue' => ['top' => 110, 'left' => 180, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_blue' => ['top' => 110, 'left' => 190, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			/*'person_red1' => ['bottom' => 100, 'left' => 410, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 340, 'left' => 190, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 'initial', 'right' => 'initial', 'top' => 'initial', 'display' => 'none'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 330, 'right' => 20, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 150, 'right' => 150, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 20, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => true, 'bottom' => false, 'up' => true, 'right' => true]*/
			'person_red1' => ['bottom' => 100, 'left' => 410, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 340, 'left' => 190, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 'initial', 'right' => 'initial', 'top' => 'initial', 'display' => 'none'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 330, 'right' => 20, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 150, 'right' => 150, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 20, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => true, 'bottom' => false, 'up' => true, 'right' => true]
		],
		'5' => [ // пятый шаг
			// 'person_blue' => ['top' => 250, 'left' => 226, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			// 'person_blue' => ['top' => 250, 'left' => 180, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_blue' => ['top' => 250, 'left' => 190, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_red1' => ['bottom' => 100, 'left' => 410, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 340, 'left' => 190, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 'initial', 'right' => 'initial', 'top' => 'initial', 'display' => 'none'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 330, 'right' => 20, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 150, 'right' => 150, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 20, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => true, 'bottom' => false, 'up' => true, 'right' => true]
		],
		'5_error_left' => [ // пятый шаг, ложное нажатие
			// 'person_blue' => ['top' => 240, 'left' => 180, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_blue' => ['top' => 240, 'left' => 190, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			/*'person_red1' => ['bottom' => 100, 'left' => 330, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 290, 'left' => 330, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 'initial', 'right' => 'initial', 'top' => 'initial', 'display' => 'none'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 330, 'right' => 20, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 150, 'right' => 210, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 20, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => true, 'bottom' => false, 'up' => true, 'right' => true]*/
			'person_red1' => ['bottom' => 100, 'left' => 330, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 290, 'left' => 330, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 'initial', 'right' => 'initial', 'top' => 'initial', 'display' => 'none'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 330, 'right' => 20, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 150, 'right' => 210, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 20, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => false, 'bottom' => true, 'up' => true, 'right' => true]
		],
		'5_error_right' => [ // пятый шаг, ложное нажатие
			'person_blue' => ['top' => 250, 'left' => 300, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			/*'person_red1' => ['bottom' => 100, 'left' => 330, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 290, 'left' => 330, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 'initial', 'right' => 'initial', 'top' => 'initial', 'display' => 'none'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 330, 'right' => 20, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 150, 'right' => 210, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 20, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => true, 'bottom' => false, 'up' => true, 'right' => true]*/
			'person_red1' => ['bottom' => 100, 'left' => 330, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 290, 'left' => 330, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 'initial', 'right' => 'initial', 'top' => 'initial', 'display' => 'none'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 330, 'right' => 20, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			// 'person_yellow3' => ['bottom' => 150, 'right' => 210, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 180, 'right' => 190, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 20, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => false, 'bottom' => true, 'up' => true, 'right' => true]
		],
		'6' => [ // шестой шаг
			// 'person_blue' => ['top' => 170, 'left' => 226, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			// 'person_blue' => ['top' => 170, 'left' => 180, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_blue' => ['top' => 170, 'left' => 190, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_red1' => ['bottom' => 100, 'left' => 330, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 360, 'left' => 330, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 'initial', 'right' => 'initial', 'top' => 'initial', 'display' => 'none'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 330, 'right' => 20, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 150, 'right' => 210, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 20, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => false, 'bottom' => true, 'up' => true, 'right' => true]
		],
		'6_error_right' => [ // шестой шаг, ложное нажатие
			'person_blue' => ['top' => 170, 'left' => 230, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			/*'person_red1' => ['bottom' => 100, 'left' => 226, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 360, 'left' => 330, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 'initial', 'right' => 'initial', 'top' => 'initial', 'display' => 'none'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 355, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 150, 'right' => 280, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 20, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => false, 'bottom' => true, 'up' => true, 'right' => true]*/
			'person_red1' => ['bottom' => 100, 'left' => 226, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 360, 'left' => 330, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 60, 'right' => 'initial', 'top' => 80, 'display' => 'block'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 355, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 150, 'right' => 280, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 20, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => false, 'bottom' => true, 'up' => true, 'right' => true]
		],
		'6_error_down' => [ // шестой шаг, ложное нажатие
			// 'person_blue' => ['top' => 200, 'left' => 226, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_blue' => ['top' => 200, 'left' => 190, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			/*'person_red1' => ['bottom' => 100, 'left' => 226, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 360, 'left' => 330, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 'initial', 'right' => 'initial', 'top' => 'initial', 'display' => 'none'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 355, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 150, 'right' => 280, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 20, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => false, 'bottom' => true, 'up' => true, 'right' => true]*/
			'person_red1' => ['bottom' => 100, 'left' => 226, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 360, 'left' => 330, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 60, 'right' => 'initial', 'top' => 80, 'display' => 'block'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 355, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 150, 'right' => 280, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 20, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => false, 'bottom' => true, 'up' => true, 'right' => true]
		],
		'7' => [ // седьмой шаг
			'person_blue' => ['top' => 110, 'left' => 226, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_red1' => ['bottom' => 100, 'left' => 226, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 360, 'left' => 330, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 40, 'right' => 'initial', 'top' => 40, 'display' => 'block'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 355, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 150, 'right' => 280, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 20, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => false, 'bottom' => true, 'up' => true, 'right' => true]
		],
		'7_error_down' => [ // седьмой шаг, ложное нажатие
			'person_blue' => ['top' => 180, 'left' => 226, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			/*'person_red1' => ['bottom' => 100, 'left' => 180, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 360, 'left' => 330, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 90, 'right' => 'initial', 'top' => 30, 'display' => 'block'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 355, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 150, 'right' => 280, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 20, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => false, 'bottom' => true, 'up' => true, 'right' => true]*/
			'person_red1' => ['bottom' => 100, 'left' => 180, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 360, 'left' => 330, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 90, 'right' => 'initial', 'top' => 30, 'display' => 'block'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 355, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 150, 'right' => 280, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 45, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => false, 'bottom' => true, 'up' => true, 'right' => true]
		],
		'7_error_up' => [ // седьмой шаг, ложное нажатие
			'person_blue' => ['top' => 50, 'left' => 226, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			/*'person_red1' => ['bottom' => 100, 'left' => 180, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 360, 'left' => 330, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 90, 'right' => 'initial', 'top' => 30, 'display' => 'block'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 355, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 150, 'right' => 280, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 20, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => false, 'bottom' => true, 'up' => true, 'right' => true]*/
			'person_red1' => ['bottom' => 100, 'left' => 180, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 360, 'left' => 330, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 90, 'right' => 'initial', 'top' => 30, 'display' => 'block'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 355, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 150, 'right' => 280, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 45, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => false, 'bottom' => true, 'up' => true, 'right' => true]
		],
		'8' => [ // восьмой шаг
			'person_blue' => ['top' => 110, 'left' => 380, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_red1' => ['bottom' => 100, 'left' => 180, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 360, 'left' => 330, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 90, 'right' => 'initial', 'top' => 30, 'display' => 'block'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 355, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 150, 'right' => 280, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 45, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => false, 'bottom' => true, 'up' => true, 'right' => true]
		],
		'8_error_down' => [ // восьмой шаг, ложное нажатие
			'person_blue' => ['top' => 190, 'left' => 390, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			/*'person_red1' => ['bottom' => 100, 'left' => 90, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 360, 'left' => 280, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 90, 'right' => 'initial', 'top' => 30, 'display' => 'block'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 355, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 150, 'right' => 280, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 100, 'right' => 106, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => false, 'bottom' => true, 'up' => true, 'right' => true]*/
			'person_red1' => ['bottom' => 100, 'left' => 90, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 330, 'left' => 350, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 90, 'right' => 'initial', 'top' => 30, 'display' => 'block'],
			'person_yellow1' => ['top' => 10, 'right' => 73, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 355, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 150, 'right' => 280, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 100, 'right' => 106, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => true, 'bottom' => true, 'up' => false, 'right' => true]
		],
		'8_error_up' => [ // восьмой шаг, ложное нажатие
			'person_blue' => ['top' => 60, 'left' => 397, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			/*'person_red1' => ['bottom' => 100, 'left' => 90, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 360, 'left' => 280, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 90, 'right' => 'initial', 'top' => 30, 'display' => 'block'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 355, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 150, 'right' => 280, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 100, 'right' => 106, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => false, 'bottom' => true, 'up' => true, 'right' => true]*/
			'person_red1' => ['bottom' => 100, 'left' => 90, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 330, 'left' => 350, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 90, 'right' => 'initial', 'top' => 30, 'display' => 'block'],
			'person_yellow1' => ['top' => 10, 'right' => 73, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 355, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 150, 'right' => 280, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 100, 'right' => 106, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => true, 'bottom' => true, 'up' => false, 'right' => true]
		],
		'9' => [ // девятый шаг
			'person_blue' => ['top' => 90, 'left' => 480, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_red1' => ['bottom' => 100, 'left' => 90, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 330, 'left' => 350, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 90, 'right' => 'initial', 'top' => 30, 'display' => 'block'],
			'person_yellow1' => ['top' => 10, 'right' => 73, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 295, 'right' => 90, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 150, 'right' => 280, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 40, 'right' => 85, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => true, 'bottom' => true, 'up' => false, 'right' => true]
		],
		'9_error_left' => [ // девятый шаг, ложное нажатие
			'person_blue' => ['top' => 110, 'left' => 400, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			/*'person_red1' => ['bottom' => 60, 'left' => 60, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 320, 'left' => 347, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 'initial', 'right' => 'initial', 'top' => 'initial', 'display' => 'none'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 345, 'right' => 80, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 240, 'right' => 310, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 55, 'right' => 80, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => true, 'bottom' => true, 'up' => false, 'right' => true]*/
			'person_red1' => ['bottom' => 60, 'left' => 60, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 320, 'left' => 347, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 'initial', 'right' => 'initial', 'top' => 'initial', 'display' => 'none'],
			'person_yellow1' => ['top' => 10, 'right' => 20, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 345, 'right' => 80, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 240, 'right' => 310, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 55, 'right' => 80, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => true, 'bottom' => true, 'up' => false, 'right' => true]
		],
		'9_error_right' => [ // девятый шаг, ложное нажатие
			'person_blue' => ['top' => 90, 'left' => 501, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			/*'person_red1' => ['bottom' => 60, 'left' => 60, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 320, 'left' => 347, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 'initial', 'right' => 'initial', 'top' => 'initial', 'display' => 'none'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 345, 'right' => 80, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 240, 'right' => 310, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 55, 'right' => 80, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => true, 'bottom' => true, 'up' => false, 'right' => true]*/
			'person_red1' => ['bottom' => 60, 'left' => 60, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 320, 'left' => 347, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 'initial', 'right' => 'initial', 'top' => 'initial', 'display' => 'none'],
			'person_yellow1' => ['top' => 10, 'right' => 20, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 345, 'right' => 80, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 240, 'right' => 310, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 55, 'right' => 80, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => true, 'bottom' => true, 'up' => false, 'right' => true]
		],
		'10' => [ // десятый шаг
			// 'person_blue' => ['top' => 300, 'left' => 400, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_blue' => ['top' => 260, 'left' => 480, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_red1' => ['bottom' => 60, 'left' => 60, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 320, 'left' => 347, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 'initial', 'right' => 'initial', 'top' => 'initial', 'display' => 'none'],
			'person_yellow1' => ['top' => 97, 'right' => 66, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 345, 'right' => 80, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 240, 'right' => 310, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 55, 'right' => 80, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => true, 'bottom' => true, 'up' => false, 'right' => true]
		],
		'10_error_down' => [ // десятый шаг, ложное нажатие
			'person_blue' => ['top' => 316, 'left' => 480, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			/*'person_red1' => ['bottom' => 70, 'left' => 70, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 290, 'left' => 270, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 'initial', 'right' => 'initial', 'top' => 'initial', 'display' => 'none'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 290, 'right' => 100, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 240, 'right' => 310, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 55, 'right' => 80, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => true, 'bottom' => true, 'up' => false, 'right' => true]*/
			'person_red1' => ['bottom' => 70, 'left' => 70, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 290, 'left' => 270, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 'initial', 'right' => 'initial', 'top' => 'initial', 'display' => 'none'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 290, 'right' => 100, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 240, 'right' => 310, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 55, 'right' => 80, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => true, 'bottom' => true, 'up' => false, 'right' => true]
		],
		'10_error_right' => [ // десятый шаг, ложное нажатие
			'person_blue' => ['top' => 260, 'left' => 500, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			/*'person_red1' => ['bottom' => 70, 'left' => 70, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 290, 'left' => 270, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 'initial', 'right' => 'initial', 'top' => 'initial', 'display' => 'none'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 290, 'right' => 100, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 240, 'right' => 310, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 55, 'right' => 80, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => true, 'bottom' => true, 'up' => false, 'right' => true]*/
			'person_red1' => ['bottom' => 70, 'left' => 70, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 290, 'left' => 270, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 'initial', 'right' => 'initial', 'top' => 'initial', 'display' => 'none'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 290, 'right' => 100, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 240, 'right' => 310, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 55, 'right' => 80, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => true, 'bottom' => true, 'up' => false, 'right' => true]
		],
		'11' => [ // одинадцатый шаг
			'person_blue' => ['top' => 300, 'left' => 150, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_red1' => ['bottom' => 70, 'left' => 70, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 290, 'left' => 270, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 'initial', 'right' => 'initial', 'top' => 'initial', 'display' => 'none'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 290, 'right' => 100, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 240, 'right' => 310, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 55, 'right' => 80, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => true, 'bottom' => true, 'up' => false, 'right' => true]
		],
		'11_error_right' => [ // одинадцатый шаг, ложное нажатие
			'person_blue' => ['top' => 300, 'left' => 270, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			/*'person_red1' => ['bottom' => 100, 'left' => 90, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 350, 'left' => 160, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 'initial', 'right' => 'initial', 'top' => 'initial', 'display' => 'none'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 210, 'right' => 100, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 290, 'right' => 280, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 55, 'right' => 80, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => true, 'bottom' => true, 'up' => false, 'right' => true]*/
			'person_red1' => ['bottom' => 100, 'left' => 90, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 350, 'left' => 160, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 'initial', 'right' => 'initial', 'top' => 'initial', 'display' => 'none'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 210, 'right' => 100, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 290, 'right' => 280, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 55, 'right' => 80, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => true, 'bottom' => true, 'up' => false, 'right' => true]
		],
		'11_error_left' => [ // одинадцатый шаг, ложное нажатие
			'person_blue' => ['top' => 300, 'left' => 115, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			/*'person_red1' => ['bottom' => 100, 'left' => 90, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 350, 'left' => 160, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 'initial', 'right' => 'initial', 'top' => 'initial', 'display' => 'none'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 210, 'right' => 100, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 290, 'right' => 280, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 55, 'right' => 80, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => true, 'bottom' => true, 'up' => false, 'right' => true]*/
			'person_red1' => ['bottom' => 100, 'left' => 90, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 350, 'left' => 160, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 'initial', 'right' => 'initial', 'top' => 'initial', 'display' => 'none'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 210, 'right' => 100, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 290, 'right' => 280, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 55, 'right' => 80, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => true, 'bottom' => true, 'up' => false, 'right' => true]
		],
		'12' => [ // типа двенадцатый шаг (конец миниигры)
			'person_blue' => ['top' => 370, 'left' => 165, 'right' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_red1' => ['bottom' => 100, 'left' => 90, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red2' => ['bottom' => 350, 'left' => 160, 'right' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_red3' => ['bottom' => 'initial', 'left' => 'initial', 'right' => 'initial', 'top' => 'initial', 'display' => 'none'],
			'person_yellow1' => ['top' => 10, 'right' => 80, 'left' => 'initial', 'bottom' => 'initial', 'display' => 'block'],
			'person_yellow2' => ['bottom' => 210, 'right' => 100, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow3' => ['bottom' => 290, 'right' => 280, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow4' => ['bottom' => 40, 'right' => 10, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'person_yellow5' => ['bottom' => 55, 'right' => 80, 'left' => 'initial', 'top' => 'initial', 'display' => 'block'],
			'arrows' => ['left' => true, 'bottom' => true, 'up' => false, 'right' => true]
		]
	];

	switch ($_POST['op']) {
		// загрузить активное состояние миниигры
		case 'uploadMinigamePositions':
			$team_info = $function->teamInfo($userInfo['team_id']);
			if ($team_info) {
				if (array_key_exists($team_info['dashboard_minigame_active_step'], $steps)) {
					$return['success'] = $steps[$team_info['dashboard_minigame_active_step']];
					$return['dashboard_minigame_active_step'] =$team_info['dashboard_minigame_active_step'];
				} else {
					// переводы для всех языков. Для синхронизации
					$sql = "SELECT `lang_abbr`, `id` FROM `langs` WHERE `status` = {?}";
					$langs = $db->select($sql, [1]);
					if ($langs) {
						$return['error_lang'] = [];

						foreach ($langs as $lang2) {
							$translation = $lang->getWordsByPage('game', $lang2['id']);

							$return['error_lang'][$lang2['lang_abbr']]['error'] = $translation['text258'];
						}
					}
				}
			} else {
				// переводы для всех языков. Для синхронизации
				$sql = "SELECT `lang_abbr`, `id` FROM `langs` WHERE `status` = {?}";
				$langs = $db->select($sql, [1]);
				if ($langs) {
					$return['error_lang'] = [];

					foreach ($langs as $lang2) {
						$translation = $lang->getWordsByPage('game', $lang2['id']);

						$return['error_lang'][$lang2['lang_abbr']]['error'] = $translation['text258'];
					}
				}
			}

			print_r(json_encode($return));
		    break;

		// загрузить первоначальное состояние миниигры
		case 'uploadMinigamePositionsStart':
			$sql = "UPDATE `teams` SET `dashboard_minigame_active_step` = {?} WHERE `id` = {?}";
			$db->query($sql, ['1', $userInfo['team_id']]);

			$team_info = $function->teamInfo($userInfo['team_id']);
			if ($team_info) {
				if (array_key_exists('1', $steps)) {
					$return['success'] = $steps['1'];
				} else {
					// переводы для всех языков. Для синхронизации
					$sql = "SELECT `lang_abbr`, `id` FROM `langs` WHERE `status` = {?}";
					$langs = $db->select($sql, [1]);
					if ($langs) {
						$return['error_lang'] = [];

						foreach ($langs as $lang2) {
							$translation = $lang->getWordsByPage('game', $lang2['id']);

							$return['error_lang'][$lang2['lang_abbr']]['error'] = $translation['text258'];
						}
					}
				}
			} else {
				// переводы для всех языков. Для синхронизации
				$sql = "SELECT `lang_abbr`, `id` FROM `langs` WHERE `status` = {?}";
				$langs = $db->select($sql, [1]);
				if ($langs) {
					$return['error_lang'] = [];

					foreach ($langs as $lang2) {
						$translation = $lang->getWordsByPage('game', $lang2['id']);

						$return['error_lang'][$lang2['lang_abbr']]['error'] = $translation['text258'];
					}
				}
			}

			print_r(json_encode($return));
		    break;

		// нажимаем на кнопки
		case 'gotoNextStepMinigame':
			$keyboard = !empty($_POST['keyboard']) ? strip_tags(trim($_POST['keyboard'])) : 'left';

			$team_info = $function->teamInfo($userInfo['team_id']);
			if ($team_info) {
				// определяем следующий шаг
				$next_key = '999';

				// if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] == '193.194.107.72') {
				// 	var_dump($team_info['dashboard_minigame_active_step']);
				// }

				if ($team_info['dashboard_minigame_active_step'] == '1') {
					if ($keyboard == 'right') {
						$next_key = '2';
					} elseif ($keyboard == 'down') {
						$next_key = '1_error_down';
					} elseif ($keyboard == 'up') {
						$next_key = '1_error_up';
					}
				} elseif ($team_info['dashboard_minigame_active_step'] == '2') {
					if ($keyboard == 'down') {
						$next_key = '3';
					} elseif ($keyboard == 'right') {
						$next_key = '2_error_right';
					} elseif ($keyboard == 'up') {
						$next_key = '2_error_up';
					}
				} elseif ($team_info['dashboard_minigame_active_step'] == '3') {
					if ($keyboard == 'down') {
						$next_key = '4';
					} elseif ($keyboard == 'right') {
						$next_key = '3_error_right';
					} elseif ($keyboard == 'up') {
						$next_key = '3_error_up';
					}
				} elseif ($team_info['dashboard_minigame_active_step'] == '4') {
					if ($keyboard == 'down') {
						$next_key = '5';
					} elseif ($keyboard == 'right') {
						$next_key = '4_error_right';
					} elseif ($keyboard == 'up') {
						$next_key = '4_error_up';
					}
				} elseif ($team_info['dashboard_minigame_active_step'] == '5') {
					if ($keyboard == 'up') {
						$next_key = '6';
					} elseif ($keyboard == 'left') {
						$next_key = '5_error_left';
					} elseif ($keyboard == 'right') {
						$next_key = '5_error_right';
					}
				} elseif ($team_info['dashboard_minigame_active_step'] == '6') {
					if ($keyboard == 'up') {
						$next_key = '7';
					} elseif ($keyboard == 'right') {
						$next_key = '6_error_right';
					} elseif ($keyboard == 'down') {
						$next_key = '6_error_down';
					}
				} elseif ($team_info['dashboard_minigame_active_step'] == '7') {
					if ($keyboard == 'right') {
						$next_key = '8';
					} elseif ($keyboard == 'down') {
						$next_key = '7_error_down';
					} elseif ($keyboard == 'up') {
						$next_key = '7_error_up';
					}
				} elseif ($team_info['dashboard_minigame_active_step'] == '8') {
					if ($keyboard == 'right') {
						$next_key = '9';
					} elseif ($keyboard == 'down') {
						$next_key = '8_error_down';
					} elseif ($keyboard == 'up') {
						$next_key = '8_error_up';
					}
				} elseif ($team_info['dashboard_minigame_active_step'] == '9') {
					if ($keyboard == 'down') {
						$next_key = '10';
					} elseif ($keyboard == 'left') {
						$next_key = '9_error_left';
					} elseif ($keyboard == 'right') {
						$next_key = '9_error_right';
					}
				} elseif ($team_info['dashboard_minigame_active_step'] == '10') {
					if ($keyboard == 'left') {
						$next_key = '11';
					} elseif ($keyboard == 'down') {
						$next_key = '10_error_down';
					} elseif ($keyboard == 'right') {
						$next_key = '10_error_right';
					}
				} elseif ($team_info['dashboard_minigame_active_step'] == '11') {
					if ($keyboard == 'down') {
						$next_key = '12';
					} elseif ($keyboard == 'right') {
						$next_key = '11_error_right';
					} elseif ($keyboard == 'left') {
						$next_key = '11_error_left';
					}
				}

				if (array_key_exists($next_key, $steps)) {
					$return['success'] = $steps[$next_key];
					$return['key'] = $next_key;

					$sql = "UPDATE `teams` SET `dashboard_minigame_active_step` = {?} WHERE `id` = {?}";
					$db->query($sql, [$next_key, $userInfo['team_id']]);

					// переводы текста ошибки для всех языков. Для синхронизации
					if (stripos($next_key, 'error') !== false) {
						$sql = "SELECT `lang_abbr`, `id` FROM `langs` WHERE `status` = {?}";
						$langs = $db->select($sql, [1]);
						if ($langs) {
							$return['error_lang'] = [];

							foreach ($langs as $lang2) {
								$translation = $lang->getWordsByPage('game', $lang2['id']);

								$return['error_lang'][$lang2['lang_abbr']]['error_input'] = $translation['text299'];
								$return['error_lang'][$lang2['lang_abbr']]['error_text'] = $translation['text300'];
							}
						}
					}
				} else {
					// переводы для всех языков. Для синхронизации
					$sql = "SELECT `lang_abbr`, `id` FROM `langs` WHERE `status` = {?}";
					$langs = $db->select($sql, [1]);
					if ($langs) {
						$return['error_lang'] = [];

						foreach ($langs as $lang2) {
							$translation = $lang->getWordsByPage('game', $lang2['id']);

							$return['error_lang'][$lang2['lang_abbr']]['error'] = $translation['text258'];
						}
					}
				}
			} else {
				// переводы для всех языков. Для синхронизации
				$sql = "SELECT `lang_abbr`, `id` FROM `langs` WHERE `status` = {?}";
				$langs = $db->select($sql, [1]);
				if ($langs) {
					$return['error_lang'] = [];

					foreach ($langs as $lang2) {
						$translation = $lang->getWordsByPage('game', $lang2['id']);

						$return['error_lang'][$lang2['lang_abbr']]['error'] = $translation['text258'];
					}
				}
			}

			print_r(json_encode($return));
		    break;
	}
}
