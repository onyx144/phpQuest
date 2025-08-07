/* === TOOLS - 3D BUILDING SCAN === */
	var activeGuage = false; // меняем ли значения, двигаем ли линию "спидометра"

/* ОБЩИЕ ФУНКЦИИ */
	// конвертируем градус для смещения линии на "спидометре"
	function convertDegreeToDegreeLine(degree) {
		// 160 - смещение
		var degreeLine = degree - 160;
		if (degreeLine >= 80 && degreeLine <= 110) {
			degreeLine = 80;
		} else if (degreeLine >= 110 && degreeLine <= 140) {
			degreeLine = 140;
		}

		return degreeLine;
	}

	// обновляем значение на "спидометре" при загрузке страницы
	function updateGaugeValueLoadPage(guageValue) {
		var degree = 0;

		if (guageValue >= 0 && guageValue <= 19) { // от 300 к 359
			var gaugeKoef = 60 / 20; // коэф - 20 делений на "спидометре" на 60 градусов
			degree = 300 + guageValue * gaugeKoef;
		} else if (guageValue >= 20 && guageValue <= 49) { // от 0 к 89
			var gaugeKoef = 90 / 30; // коэф - 30 делений на "спидометре" на 90 градусов
			degree = (guageValue - 20) * gaugeKoef;
		} else if (guageValue >= 50 && guageValue <= 79) { // от 90 к 179
			var gaugeKoef = 90 / 30; // коэф - 30 делений на "спидометре" на 90 градусов
			degree = (guageValue - 20) * gaugeKoef;
		} else if (guageValue >= 80 && guageValue <= 100) { // от 180 к 269
			var gaugeKoef = 60 / 20; // коэф - 20 делений на "спидометре" на 60 градусов
			degree = (guageValue - 20) * gaugeKoef;
		}

		// поворот круга с линией
		$('.dashboard_tools_3d_scan_inner_main_left_gauge_center_circle').css('transform', 'rotate('+ convertDegreeToDegreeLine(degree) +'deg)');
	}

	// отправить результаты сканирования
	function toolsScanSubmit(degree, input1, input2, input3, input4, input5, dot_n_index, dot_s_index, dot_e_index, dot_w_index, checkbox_n, checkbox_s, checkbox_e, checkbox_w, lang_abbr2, sendFromSocket) {
		/*var attrScanText = $(this).attr('data-scan-' + $('html').attr('lang'));
		if (typeof attrScanText !== 'undefined' && attrScanText !== false) {
			viewMainPreloader(attrScanText);
		} else {
			viewMainPreloader('Scanning');
		}*/

		if (!is_touch_device()) {
			var pageSize = getPageSize();
    		var windowWidth = pageSize[2];
    		var windowHeight = pageSize[1];

    		if (windowWidth < 1800) {
    			$('#preloader2').css('height', windowHeight + 'px');

    			$('body').addClass('body_desktop_scale_view_preloader');

    			var attrScanText = $('.tools_3d_scan_btn').attr('data-scan-' + $('html').attr('lang'));
				if (typeof attrScanText !== 'undefined' && attrScanText !== false) {
					viewMainPreloader(attrScanText);
				} else {
					viewMainPreloader('Scanning');
				}
    		} else {
    			var attrScanText = $('.tools_3d_scan_btn').attr('data-scan-' + $('html').attr('lang'));
				if (typeof attrScanText !== 'undefined' && attrScanText !== false) {
					viewMainPreloader(attrScanText);
				} else {
					viewMainPreloader('Scanning');
				}
    		}
    	} else {
    		var attrScanText = $('.tools_3d_scan_btn').attr('data-scan-' + $('html').attr('lang'));
			if (typeof attrScanText !== 'undefined' && attrScanText !== false) {
				viewMainPreloader(attrScanText);
			} else {
				viewMainPreloader('Scanning');
			}
    	}

		setTimeout(function(){
			// правильные ли данные введены и действие дальше
			var formData = new FormData();
	    	formData.append('op', 'validateBuildingScanParameters');
	    	/*formData.append('degree', $('.dashboard_tools_3d_scan_inner_main_left_value').attr('data-value'));
	    	formData.append('input1', $.trim($('.tools_building_scan_input1').val()));
	    	formData.append('input2', $.trim($('.tools_building_scan_input2').val()));
	    	formData.append('input3', $.trim($('.tools_building_scan_input3').val()));
	    	formData.append('input4', $.trim($('.tools_building_scan_input4').val()));
	    	formData.append('input5', $.trim($('.tools_building_scan_input5').val()));
	    	formData.append('lang_abbr', $('html').attr('lang'));

	    	$('.dashboard_tools_3d_scan_inner_main_right_parameter_row').each(function(index, element) {
	    		var dotWrapper = $(element).find('.dashboard_tools_3d_scan_inner_main_right_parameter_scale_dots');
	    		var indexActive = dotWrapper.find('.dashboard_tools_3d_scan_inner_main_right_parameter_scale_dot').index(dotWrapper.find('.dashboard_tools_3d_scan_inner_main_right_parameter_scale_dot_active'));
	    		formData.append(dotWrapper.attr('data-field'), indexActive);

	    		var checkboxField = $(element).find('input[type="checkbox"]');
	    		formData.append(checkboxField.attr('data-field'), checkboxField.prop('checked') ? 1 : 0);
	    	});*/

	    	formData.append('degree', degree);
	    	formData.append('input1', input1);
	    	formData.append('input2', input2);
	    	formData.append('input3', input3);
	    	formData.append('input4', input4);
	    	formData.append('input5', input5);
	    	formData.append('lang_abbr', lang_abbr2);
	    	formData.append('tools_building_scan_address_dot_n', dot_n_index);
	    	formData.append('tools_building_scan_address_dot_s', dot_s_index);
	    	formData.append('tools_building_scan_address_dot_e', dot_e_index);
	    	formData.append('tools_building_scan_address_dot_w', dot_w_index);
	    	formData.append('tools_building_scan_address_checkbox_n', checkbox_n);
	    	formData.append('tools_building_scan_address_checkbox_s', checkbox_s);
	    	formData.append('tools_building_scan_address_checkbox_e', checkbox_e);
	    	formData.append('tools_building_scan_address_checkbox_w', checkbox_w);

	    	$.ajax({
				url: '/ajax/ajax_tools.php',
		        type: "POST",
		        dataType: "json",
		        cache: false,
		        contentType: false,
		        processData: false,
		        data: formData,
				success: function(json) {
					// скрываем прелоадер
					hiddenMainPreloader();
					$('body').removeClass('body_desktop_scale_view_preloader');
					$('#preloader2').css('height', '100%');

					if (json.success) {
						// звук успешного выполнения
						successAudio = new Audio;
						successAudio.src = '/music/done.mp3';
						// successAudio.play();

						// Autoplay
						var promise = successAudio.play();

						if (promise !== undefined) {
							promise.then(_ => {
								// console.log('autoplay');
							}).catch(error => {
								// console.log('autoplay ERR');
							});
						}

						// грузим новый таб tools
						uploadTypeTabsToolsStep('secret_office', '3d_building_scan', false);

						if (!sendFromSocket) {
							$.when(getTeamInfo()).done(function(teamResponse){
								var teamInfo = teamResponse.success;

								// добавляем очки
								incrementScore(parseInt(teamInfo.score, 10) + 100, 'main', teamInfo.score);
							});

							// обновляем mission progress
							incrementProgressMission(5);
						} else {
							// добавляем очки
							incrementScoreWithoutSaveDb(scoreBeforeToolsScan + 100, 'main', scoreBeforeToolsScan);

							// обновляем mission progress
							incrementProgressMissionWithoutSaveDb(5);
						}
					} else {
						// отображаем попап ошибки
						$('#popup_search_error .popup_search_error_input').html(json.error_lang[$('html').attr('lang')].error_input);
						$('#popup_search_error .popup_search_error_text').html(json.error_lang[$('html').attr('lang')].error_text);
						$('#popup_search_error').css('display','block');

						// звук ошибки
						errorAudio = new Audio;
						errorAudio.src = '/music/error.mp3';
						// errorAudio.play();

						// Autoplay
						var promise = errorAudio.play();

						if (promise !== undefined) {
							promise.then(_ => {
								// console.log('autoplay');
							}).catch(error => {
								// console.log('autoplay ERR');
							});
						}
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {	
					console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}, 4900);
	}

$(function() {
/* ИЗМЕНЕНИЕ ЗНАЧЕНИЯ "СПИДОМЕТРА" */
	// координаты центра "спидометра"
	function getGuageCenterCoords() {
		var circle = $('.dashboard_tools_3d_scan_inner_main_left_gauge_clickable');

		var centerCoordX = circle.outerWidth() / 2;
		var centerCoordY = circle.outerHeight() / 2;

		return {x:centerCoordX, y:centerCoordY};
	}

	// координаты курсора внутри "спидометра"
	function getGuageCursorCoords(offset, elem) {
		// положение элемента
		var elemLeft = offset.left;
		var elemTop = offset.top;

		// положение курсора внутри элемента
		return {x:elem.pageX - elemLeft, y:elem.pageY - elemTop};
	}

	// угол по двум координатам
	function getAngleByCoords(centerCoords, cursorCoords) {
		var difX = cursorCoords.x - centerCoords.x;
		var difY = cursorCoords.y - centerCoords.y;

		var defaultDegree = radians_to_degrees(Math.atan(difY / difX));

		// преобразовываем полученное значение градуса в систему 360 градусов
		// почему 95, а не 90 непонятно))
		if (cursorCoords.x <= 95 && cursorCoords.y <= 90) {
			var degree = defaultDegree;
		} else if (cursorCoords.x >= 95 && cursorCoords.y <= 90) {
			var degree = defaultDegree + 180;
		} else if (cursorCoords.x >= 95 && cursorCoords.y >= 90) {
			var degree = defaultDegree + 180;
		} else if (cursorCoords.x <= 95 && cursorCoords.y >= 90) {
			var degree = defaultDegree + 360;
		}

		return degree;
	}

	// радианы в градусы
	function radians_to_degrees(radians) {
	  	return radians * (180 / Math.PI);
	}

	// конвертируем градус на шкалу "спидометра"
	function convertDegreeToValue(degree) {
		var gaugeKoef = 30 / 90; // коэф - 30 делений на "спидометре" на 90 градусов
		var value = Math.round(gaugeKoef * degree + 20); // значение на шкале, 20 - смещение относительно оси Х
		if (value >= 100 && value <= 110) {
			value = 100;
		} else if (value >= 110 && value <= 120) {
			value = 0;
		} else if (value >= 120 && value <= 140) {
			value = value - 120;
		}

		return value;
	}

	// высчитываем и обновляем значение на "спидометре"
	function updateGaugeValue(circleElem, cursorElem) {
		// координаты центра "спидометра"
		var centerCoords = getGuageCenterCoords();

		// координаты курсора внутри "спидометра"
		var cursorCoords = getGuageCursorCoords(circleElem.offset(), cursorElem);

		// угол по двум координатам
		var degree = getAngleByCoords(centerCoords, cursorCoords);

		// конвертируем градус на шкалу "спидометра"
		var guageValue = convertDegreeToValue(degree);

		// пишем текст
		$('.dashboard_tools_3d_scan_inner_main_left_value').html(guageValue + 'k').attr('data-value', guageValue);
		$('.dashboard_tools_3d_scan_inner_main_left_gauge_cur_value span').html(guageValue + 'k');

		// поворот круга с линией
		$('.dashboard_tools_3d_scan_inner_main_left_gauge_center_circle').css('transform', 'rotate('+ convertDegreeToDegreeLine(degree) +'deg)');
	}

	$('body').on('mousedown touchstart', '.dashboard_tools_3d_scan_inner_main_left_gauge_clickable', function(e){
		activeGuage = true;
		updateGaugeValue($(this), e);
	});

	$('body').on('mousemove touchmove', '.dashboard_tools_3d_scan_inner_main_left_gauge_clickable', function(e){
		if (activeGuage) {
			updateGaugeValue($(this), e);
		}
	});

	/*$('body').on('mouseup touchend', '.dashboard_tools_3d_scan_inner_main_left_gauge_clickable', function(e){
		activeGuage = false;
	});*/

	$('body').on('mouseup touchend', function(e){
		activeGuage = false;

		// пишем в бд
		if ($('.dashboard_tools_3d_scan_inner_main_left_value').length) {
			var degree = $('.dashboard_tools_3d_scan_inner_main_left_value').attr('data-value');
			setTeamTabsTextInfo('tools_building_scan_degree', degree);

			// socket
			var message = {
				'op': 'toolsScanChangeValueDegree',
				'parameters': {
					'value': degree,
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));

			/*$.when(setTeamTabsTextInfoCallback('tools_building_scan_degree', degree)).done(function(ajaxResponse){
				// socket
				var message = {
					'op': 'toolsScanChangeValueDegree',
					'parameters': {
						'value': degree,
						'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
						'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
					}
		        };
		        sendMessageSocket(JSON.stringify(message));
			});*/
		}
	});

/* ИЗМЕНЕНИЕ ЦИФР */
	$('body').on('click', '.tools_3d_scan_input_arrow_up', function(e){
		var input = $(this).closest('.dashboard_tools_3d_scan_inner_main_left_input_wrapper').find('input[type="text"]');
		var number = $.trim(input.val());
		if (isNaN(number) || number == '') {
			number = 0;
		}
		number = parseInt(number, 10);
		var newNumber = number + 1;

		input.val(newNumber);

		// пишем в бд
		setTeamTabsTextInfo(input.attr('class'), newNumber);

		// socket
		var message = {
			'op': 'toolsScanChangeValueNumber',
			'parameters': {
				'fieldClass': input.attr('class'),
				'value': newNumber,
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
        };
        sendMessageSocket(JSON.stringify(message));
	});
	$('body').on('click', '.tools_3d_scan_input_arrow_down', function(e){
		var input = $(this).closest('.dashboard_tools_3d_scan_inner_main_left_input_wrapper').find('input[type="text"]');
		var number = $.trim(input.val());
		if (isNaN(number) || number == '') {
			number = 0;
		}
		number = parseInt(number, 10);

		var newNumber = number - 1;
		if (newNumber < 0) {
			newNumber = 0;
		}

		input.val(newNumber);

		// пишем в бд
		setTeamTabsTextInfo(input.attr('class'), newNumber);

		// socket
		var message = {
			'op': 'toolsScanChangeValueNumber',
			'parameters': {
				'fieldClass': input.attr('class'),
				'value': newNumber,
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
        };
        sendMessageSocket(JSON.stringify(message));
	});
	$('body').on('keyup', '.dashboard_tools_3d_scan_inner_main_left_inputs_wrapper input[type="text"]', function(e){
		var number = $.trim($(this).val());
		/*if (isNaN(number) || number == '') {
			number = 0;
		}
		number = parseInt(number, 10);*/

		// пишем в бд
		setTeamTabsTextInfo($(this).attr('class'), number);

		// socket
		var message = {
			'op': 'toolsScanChangeValueNumber',
			'parameters': {
				'fieldClass': $(this).attr('class'),
				'value': number,
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
        };
        sendMessageSocket(JSON.stringify(message));
	});

/* ИЗМЕНЕНИЕ ЗНАЧЕНИЕ ПАРАМЕТРОВ. ШКАЛА С ТОЧКАМИ */
	$('body').on('click', '.dashboard_tools_3d_scan_inner_main_right_parameter_scale_dot', function(e){
		if ($(this).hasClass('dashboard_tools_3d_scan_inner_main_right_parameter_scale_dot_active')) {
			return false;
		}

		var wrapper = $(this).closest('.dashboard_tools_3d_scan_inner_main_right_parameter_scale_dots');

		wrapper.find('.dashboard_tools_3d_scan_inner_main_right_parameter_scale_dot').removeClass('dashboard_tools_3d_scan_inner_main_right_parameter_scale_dot_active').removeClass('dashboard_tools_3d_scan_inner_main_right_parameter_scale_dot_before_active');

		var indexActive = wrapper.find('.dashboard_tools_3d_scan_inner_main_right_parameter_scale_dot').index($(this));

		wrapper.find('.dashboard_tools_3d_scan_inner_main_right_parameter_scale_dot').each(function(index, element) {
			if (index < indexActive) {
				$(element).addClass('dashboard_tools_3d_scan_inner_main_right_parameter_scale_dot_before_active');
			} else if (index == indexActive) {
				$(element).addClass('dashboard_tools_3d_scan_inner_main_right_parameter_scale_dot_active');
			}
		});

		// пишем в бд
		setTeamTabsTextInfo(wrapper.attr('data-field'), indexActive);

		// socket
		var message = {
			'op': 'toolsScanChangeValueDots',
			'parameters': {
				'field': wrapper.attr('data-field'),
				'value': indexActive,
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
        };
        sendMessageSocket(JSON.stringify(message));
	});

/* ЧЕКБОКСЫ */
	$('body').on('change', '.dashboard_tools_3d_scan_inner_main_right_parameter_checkbox input[type="checkbox"]', function(e){
		var field = $(this).attr('data-field');
		var value = $(this).prop('checked') ? 1 : 0;

		// пишем в бд
		setTeamTabsTextInfo(field, value);

		// socket
		var message = {
			'op': 'toolsScanChangeValueCheckbox',
			'parameters': {
				'field': field,
				'value': value,
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
        };
        sendMessageSocket(JSON.stringify(message));
	});

/* ОТПРАВЛЯЕМ РЕЗУЛЬТАТЫ СКАНИРОВАНИЯ */
	$('body').on('click', '.tools_3d_scan_btn', function(e){
		var degree = $('.dashboard_tools_3d_scan_inner_main_left_value').attr('data-value');
		var input1 = $.trim($('.tools_building_scan_input1').val());
		var input2 = $.trim($('.tools_building_scan_input2').val());
		var input3 = $.trim($('.tools_building_scan_input3').val());
		var input4 = $.trim($('.tools_building_scan_input4').val());
		var input5 = $.trim($('.tools_building_scan_input5').val());

		var dot_n_index = 0;
		var dot_s_index = 0;
		var dot_e_index = 0;
		var dot_w_index = 0;

		var checkbox_n = 0;
		var checkbox_s = 0;
		var checkbox_e = 0;
		var checkbox_w = 0;

		$('.dashboard_tools_3d_scan_inner_main_right_parameter_row').each(function(index, element) {
    		var dotWrapper = $(element).find('.dashboard_tools_3d_scan_inner_main_right_parameter_scale_dots');
    		var indexActive = dotWrapper.find('.dashboard_tools_3d_scan_inner_main_right_parameter_scale_dot').index(dotWrapper.find('.dashboard_tools_3d_scan_inner_main_right_parameter_scale_dot_active'));

    		var checkboxField = $(element).find('input[type="checkbox"]');

    		if (dotWrapper.attr('data-field') == 'tools_building_scan_address_dot_n') {
    			dot_n_index = indexActive;
    			checkbox_n = checkboxField.prop('checked') ? 1 : 0;
    		} else if (dotWrapper.attr('data-field') == 'tools_building_scan_address_dot_s') {
    			dot_s_index = indexActive;
    			checkbox_s = checkboxField.prop('checked') ? 1 : 0;
    		} else if (dotWrapper.attr('data-field') == 'tools_building_scan_address_dot_e') {
    			dot_e_index = indexActive;
    			checkbox_e = checkboxField.prop('checked') ? 1 : 0;
    		} else if (dotWrapper.attr('data-field') == 'tools_building_scan_address_dot_w') {
    			dot_w_index = indexActive;
    			checkbox_w = checkboxField.prop('checked') ? 1 : 0;
    		}
    	});

		$.when(getTeamInfo()).done(function(teamResponse){
			var teamInfo = teamResponse.success;

			scoreBeforeToolsScan = parseInt(teamInfo.score, 10);

			// socket
			var message = {
				'op': 'toolsScanSubmit',
				'parameters': {
					'scoreBeforeToolsScan': scoreBeforeToolsScan,
					'degree': degree,
					'input1': input1,
					'input2': input2,
					'input3': input3,
					'input4': input4,
					'input5': input5,
					'dot_n_index': dot_n_index,
					'dot_s_index': dot_s_index,
					'dot_e_index': dot_e_index,
					'dot_w_index': dot_w_index,
					'checkbox_n': checkbox_n,
					'checkbox_s': checkbox_s,
					'checkbox_e': checkbox_e,
					'checkbox_w': checkbox_w,
					'lang_abbr': $('html').attr('lang'),
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));

			toolsScanSubmit(degree, input1, input2, input3, input4, input5, dot_n_index, dot_s_index, dot_e_index, dot_w_index, checkbox_n, checkbox_s, checkbox_e, checkbox_w, $('html').attr('lang'), false);
		});
	});
});