/* === DASHBOARD - PASSWORD === */

/* ОБЩИЕ ФУНКЦИИ */
	function dashboardPasswordSubmit(password, lang_abbr2) {
		// звук поиска
		setTimeout(function(){
			dataTransferAudio = new Audio;
			dataTransferAudio.src = '/music/data_transfer.mp3';
			// dataTransferAudio.play();

			// Autoplay
			var promise = dataTransferAudio.play();

			if (promise !== undefined) {
				promise.then(_ => {
					// console.log('autoplay');
				}).catch(error => {
					// console.log('autoplay ERR');
				});
			}
		}, 500);

		// обнуляем значение процентов
		$('.popup_data_transfer_percent span').html('0');
		$('.popup_data_transfer_progress_inner').css('width', '0%');

		setTimeout(function(){
			// запускаем анимацию смены рандомных цифр в Data Transfer
			var dataTransferInterval1 = false; // переменная для интервала
			var dataTransferSecondIteration = 50; // сколько милисекунд длится итерация смены цифры
			var dataTransferSecondTotal = 0; // для прерывания интервала
			
			dataTransferInterval1 = setInterval(function(){
				if (dataTransferSecondTotal >= (dataTransferMusicDuration + 1500)) { // докидываем 1500. Считытает быстрее, чем вторая анимация
					// прерываем интервал
					clearInterval(dataTransferInterval1);
					dataTransferInterval1 = false;
				}

				// увеличиваем общее к-во секунд для отслеживания прерывания
				dataTransferSecondTotal += dataTransferSecondIteration;

				// непосредственно пишем новые числа
				$('.popup_data_transfer_numbers_one').html(selfRandom(100, 9999));
				$('.popup_data_transfer_numbers_two').html(selfRandom(100, 999));
			}, dataTransferSecondIteration);

			// запускаем анимацию смены процентов загрузки (текст и полоса) в Data Transfer
			var dataTransferInterval2 = false; // переменная для интервала
			var dataTransferPlus = Math.round(100 / dataTransferIteration); // на столько увеличиваем за итерацию

			dataTransferInterval2 = setInterval(function(){
				var current = parseInt($('.popup_data_transfer_percent span').html(), 10);
				var next = current + selfRandom(1, dataTransferPlus);

				if (next >= 100) {
					next = 100;
				}

				$('.popup_data_transfer_progress_inner').css('width', next + '%');
				$('.popup_data_transfer_percent span').html(next);

				if (next == 100) {
					// прерываем интервал
					clearInterval(dataTransferInterval2);
					dataTransferInterval2 = false;

					// правильные ли данные введены и действие дальше
					var formData = new FormData();
			    	formData.append('op', 'validatePasswordSearch');
			    	formData.append('password', password);
			    	formData.append('lang_abbr', lang_abbr2);

			    	$.ajax({
						url: '/ajax/ajax_dashboard.php',
				        type: "POST",
				        dataType: "json",
				        cache: false,
				        contentType: false,
				        processData: false,
				        data: formData,
						success: function(json) {
							// скрываем попап
							$('#popup_data_transfer').fadeOut(200);

							if (dataTransferAudio && isPlaying(dataTransferAudio)) {
								dataTransferAudio.pause();
							}

							if (json.success) {
								// открываем экран interpol
								hiddenMainGameWindow();
								openInterpolWindow();
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
				}
			}, (dataTransferMusicDuration / dataTransferIteration));

			// отображаем попап с гифкой
			$('#popup_data_transfer').css('display','block');
		}, 210);
	}

$(function() {
	// ввод данных в поля формы
	$('.dashboard_tabs[data-dashboard="dashboard"]').on('keyup', '.dashboard_password_password', function(e){
		if (e.which == 13) {
			$('.dashboard_password_search').trigger('click');
		} else {
			// socket
			var message = {
				'op': 'dashboardPasswordKeyup',
				'parameters': {
					'password': $(this).val(),
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));
		}
	});

	// отправить данные из формы поиска
	$('body').on('click', '.dashboard_password_search', function(e){
		var err = false;
		var password = $.trim($('.dashboard_password_password').val());

		if (password == '') {
			$('.dashboard_password_password_error').addClass('error_text_database_car_register_active');
			err = true;
		} else {
			$('.dashboard_password_password_error').removeClass('error_text_database_car_register_active');
		}

		if (!err) {
			// socket
			var message = {
				'op': 'dashboardPasswordNoEmptyFields',
				'parameters': {
					'password': password,
					'lang_abbr': $('html').attr('lang'),
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));

			dashboardPasswordSubmit(password, $('html').attr('lang'));
		} else {
			// socket
			var message = {
				'op': 'dashboardPasswordEmptyFields',
				'parameters': {
					'password_error': (password == '') ? true : false,
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));
		}
	});
});