/* === DATABASES - CAR REGISTER. SEARCH 1 === */

/* ОБЩИЕ ФУНКЦИИ */
	// анимация поиска и результаты
	function databaseCarRegisterNoEmptyFields(answer, lang_abbr2) {
		// на всякий случай скрываем окно с ошибкой
		$('#popup_search_error').css('display','none');

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
		    	formData.append('op', 'validateCarRegisterSearch');
		    	formData.append('answer', answer);
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
							uploadTypeTabsDatabasesStep('databases_start_four_inner_second_car_register_huilov', 'car_register', false);
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
	// ввод данных в поле ответа
	$('.dashboard_tabs[data-dashboard="databases"]').on('keyup', '.dashboard_car_register1_answer', function(e){
		if ($('.dashboard_car_register1_search').length) {
			if (e.which == 13) {
				$('.dashboard_car_register1_search').trigger('click');
			} else {
				// socket
				var message = {
					'op': 'databaseCarregisterSearchAnswerKeyup',
					'parameters': {
						'answer': $(this).val(),
						'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
						'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
					}
		        };
		        sendMessageSocket(JSON.stringify(message));
			}
		}
	});

	// отправить данные из формы поиска
	$('body').on('click', '.dashboard_car_register1_search', function(e){
		var err = false;
		var answer = $.trim($('.dashboard_car_register1_answer').val());

		if (answer == '') {
			$('.dashboard_car_register1_answer_error').addClass('error_text_database_car_register_active');
			err = true;
		} else {
			$('.dashboard_car_register1_answer_error').removeClass('error_text_database_car_register_active');
		}

		if (!err) {
			// фиксируем к-во очков, которое было у команды перед успешным результатом поиска. Для правильного подсчета очков команды
			$.when(getTeamInfo()).done(function(teamResponse){
				var teamInfo = teamResponse.success;

				scoreBeforeDatabaseCarRegister = parseInt(teamInfo.score, 10);

				// socket
				var message = {
					'op': 'databaseCarRegisterNoEmptyFields',
					'parameters': {
						'answer': answer,
						'lang_abbr': $('html').attr('lang'),
						'scoreBeforeDatabaseCarRegister': scoreBeforeDatabaseCarRegister,
						'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
						'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
					}
		        };
		        sendMessageSocket(JSON.stringify(message));

				databaseCarRegisterNoEmptyFields(answer, $('html').attr('lang'));
			});
		} else {
			// socket
			var message = {
				'op': 'databaseCarRegisterEmptyFields',
				'parameters': {
					'answer_error': true,
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));
		}
	});

	// car register search1 success slider - to next slide
	$('body').on('click', '.dashboard_car_register2_slider_arrow_right', function(e){
		$('.dashboard_car_register2_slider').slick('slickNext');
	});
	// car register search1 success slider - to prev slide
	$('body').on('click', '.dashboard_car_register2_slider_arrow_left', function(e){
		$('.dashboard_car_register2_slider').slick('slickPrev');
	});
});