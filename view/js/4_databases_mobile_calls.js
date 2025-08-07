/* === DATABASES - PERSONAL FILES - PRIVATE INDIVIDUALS === */

/* ОБЩИЕ ФУНКЦИИ */
	// анимация поиска и результаты
	function databaseMobileCallsNoEmptyFields(countryCode, number, lang_abbr2) {
		// обнуляем значения в попапе перед отображением
		$('.popup_search_processing_input_upload_text span').html('0');
		$('.popup_search_processing_input_upload_percent').css('width', '0%');

		// отображаем окно поиска
		$('#popup_search_processing').css('display','block');
		$('.popup_search_processing_input_upload_percent').css('opacity', 1);

		// звук поиска, если звуки включены
		searchAudio = new Audio;
		searchAudio.src = '/music/search_database.mp3';
		// searchAudio.play();

		// Autoplay
		var promise = searchAudio.play();

		if (promise !== undefined) {
			promise.then(_ => {
				// console.log('autoplay');
			}).catch(error => {
				// console.log('autoplay ERR');
			});
		}

		// анимация поиска
		var searchInterval = false; // переменная для интервала поиска
		var databaseSearchPlus = Math.round(100 / databaseSearchIteration); // на столько увеличиваем за итерацию

		searchInterval = setInterval(function(){
			var current = parseInt($('.popup_search_processing_input_upload_text span').html(), 10);
			var next = current + selfRandom(1, databaseSearchPlus);

			if (next >= 100) {
				next = 100;
			}

			$('.popup_search_processing_input_upload_percent').css('width', next + '%');
			$('.popup_search_processing_input_upload_text span').html(next);

			if (next == 100) {
				// останавливаем анимацию поиска
				clearInterval(searchInterval);
				searchInterval = false;

				// правильные ли данные введены и действие дальше
				var formData = new FormData();
		    	formData.append('op', 'validateMobileCalls');
		    	formData.append('country_code', countryCode);
		    	formData.append('number', number);
		    	formData.append('lang_abbr', lang_abbr2);

		    	$.ajax({
					url: '/ajax/ajax_databases.php',
			        type: "POST",
			        dataType: "json",
			        cache: false,
			        contentType: false,
			        processData: false,
			        data: formData,
					success: function(json) {
						// скрываем попап поиска
						$('#popup_search_processing').css('display','none');

						if (json.success) {
							// звук успешного выполнения
							if (searchAudio && isPlaying(searchAudio)) {
								searchAudio.pause();
							}

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

							// грузим новый таб
							uploadTypeTabsDatabasesStep('databases_start_four_inner_first_mobile_calls_messages', 'mobile_calls', false);
						} else {
							// отображаем попап ошибки
							$('#popup_search_error .popup_search_error_input').html(json.error_lang[$('html').attr('lang')].error_input);
							$('#popup_search_error .popup_search_error_text').html(json.error_lang[$('html').attr('lang')].error_text);
							$('#popup_search_error').css('display','block');

							// звук ошибки
							if (searchAudio && isPlaying(searchAudio)) {
								searchAudio.pause();
							}

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
			}
		}, (databaseSearchDuration / databaseSearchIteration));
	}

$(function() {
	// mobile calls search. Действие при вводе текста номера телефона
	$('.dashboard_tabs[data-dashboard="databases"]').on('keyup', '.dashboard_mobile_calls1_number', function(e){
		if ($('.dashboard_mobile_calls1_search').length) {
			if (e.which == 13) {
				$('.dashboard_mobile_calls1_search').trigger('click');
			} else {
				// socket
				var message = {
					'op': 'databaseMobileCallsNumberKeyup',
					'parameters': {
						'number': $(this).val(),
						'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
						'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
					}
		        };
		        sendMessageSocket(JSON.stringify(message));
			}
		}
	});

	// mobile calls search. Нажали на кнопку отправки данных
	$('body').on('click', '.dashboard_mobile_calls1_search', function(e){
		var err = false;
		var countryCode = $.trim($('.dashboard_mobile_calls1_country_code').val());
		var number = $.trim($('.dashboard_mobile_calls1_number').val());

		if (countryCode == '') {
			$('.dashboard_mobile_calls1_country_code_error').addClass('error_text_database_car_register_active');
			err = true;
		} else {
			$('.dashboard_mobile_calls1_country_code_error').removeClass('error_text_database_car_register_active');
		}

		if (number == '') {
			$('.dashboard_mobile_calls1_number_error').addClass('error_text_database_car_register_active');
			err = true;
		} else {
			$('.dashboard_mobile_calls1_number_error').removeClass('error_text_database_car_register_active');
			var number = number.replace(/ /g, "");
		}

		if (!err) {
			// фиксируем к-во очков, которое было у команды перед успешным результатом поиска. Для правильного подсчета очков команды
			$.when(getTeamInfo()).done(function(teamResponse){
				var teamInfo = teamResponse.success;

				scoreBeforeDatabaseMobileCalls = parseInt(teamInfo.score, 10);

				// socket
				var message = {
					'op': 'databaseMobileCallsNoEmptyFields',
					'parameters': {
						'country_code': countryCode,
						'number': number,
						'lang_abbr': $('html').attr('lang'),
						'scoreBeforeDatabaseMobileCalls': scoreBeforeDatabaseMobileCalls,
						'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
						'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
					}
		        };
		        sendMessageSocket(JSON.stringify(message));

				databaseMobileCallsNoEmptyFields(countryCode, number, $('html').attr('lang'));
			});
		} else {
			// socket
			var message = {
				'op': 'databaseMobileCallsEmptyFields',
				'parameters': {
					'country_code_error': (countryCode == '') ? true : false,
					'number_error': (number == '') ? true : false,
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));
		}
	});

	// opep popup mobile calls messages
	$('body').on('click', '.dashboard_tab_content_item_active .dashboard_mobile_calls2_message_item', function(e){
		// socket
		var message = {
			'op': 'databaseMobileCallsOpenPopupMesages',
			'parameters': {
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
        };
        sendMessageSocket(JSON.stringify(message));

		$('#popup_mobile_calls_messages').css('display','block');

		if (!is_touch_device()) {
			var pageSize = getPageSize();
    		var windowWidth = pageSize[2];
    		// var windowWidth = pageSize[0];
    		var windowHeight = pageSize[1];
    		if (windowWidth < 1800) {
    			$('body').removeClass('body_desktop_scale').css('transform', 'scale(1)');

    			var pageSize = getPageSize();
    			// var windowWidth = pageSize[0];

    			var koef = parseFloat((windowWidth / 1920).toFixed(2)) + 0.01;

    			$('body').addClass('body_desktop_scale').css('transform', 'scale(' + koef + ')');

    			// $('#popup_mobile_calls_messages').css('height', ($('#main').outerHeight() * parseFloat((1920 / windowWidth).toFixed(2))) + 'px');
    			$('#popup_mobile_calls_messages').css('height', windowHeight + 'px');
    		}
		}
	});

	// close popup mobile calls messages
	$('body').on('click', '.popup_mobile_calls_close, .popup_mobile_calls_messages_bg', function(e){
		// socket
		var message = {
			'op': 'databaseMobileCallsClosePopupMesages',
			'parameters': {
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
        };
        sendMessageSocket(JSON.stringify(message));

		$('#popup_mobile_calls_messages').fadeOut(200);
	});
});