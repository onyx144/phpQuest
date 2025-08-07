/* === DASHBOARD - METTING PLACE === */

/* ОБЩИЕ ФУНКЦИИ */
	// ввели верно, открываем попап исходящего звонка
	function mettingPlaceOpenOutgoingCall() {
		// запускаем отображение времени
		updateIncomingTime();
		incomingCallTimer = setInterval(function(){
			updateIncomingTime();
		}, 1000);

		$('#popup_video_phone .popup_video_phone_wifi_icons').html('<img src="/images/wifi_icons.png" alt="">');
		$('#popup_video_phone .popup_video_phone_name').html('Jane Blond');
		$('#popup_video_phone').attr('class','').addClass('popup_video_phone_outgoing_metting_place');

		// звук вызова
		// music_before = music;
		// if (music) {
			stopMusic();
		// }

		if (!incomingAudio || !isPlaying(incomingAudio)) {
			incomingAudio = new Audio;
			incomingAudio.src = '/music/incoming.mp3';
			// incomingAudio.play();

			// Autoplay
			var promise = incomingAudio.play();

			if (promise !== undefined) {
				promise.then(_ => {
					// console.log('autoplay');

					incomingMusicTimer = setInterval(function(){
						incomingAudio = new Audio;
						incomingAudio.src = '/music/incoming.mp3';
						incomingAudio.play();
					}, incomingMusicDuration);
				}).catch(error => {
					// console.log('autoplay ERR');
				});
			}

			/*incomingMusicTimer = setInterval(function(){
				incomingAudio = new Audio;
				incomingAudio.src = '/music/incoming.mp3';
				incomingAudio.play();
			}, incomingMusicDuration);*/
		}

		// отображаем окошко
		$('#popup_video_phone').fadeIn(200);
	}

	// ввели верно - просмотрели incoming video либо закрыли попап с видео
	function mettingPlace() {
		var formData = new FormData();
    	formData.append('op', 'mettingPlaceUpdateHint');
    	formData.append('lang_abbr', $('html').attr('lang'));

    	$.ajax({
			url: '/ajax/ajax_dashboard.php',
	        type: "POST",
	        dataType: "json",
	        cache: false,
	        contentType: false,
	        processData: false,
	        data: formData,
			success: function(json) {
				if (json.error_verify) {
					window.location.href = json.error_verify;
				} else {
					$.when(getTeamInfo()).done(function(teamResponse){
						var teamInfo = teamResponse.success;

						// добавляем очки
						incrementScore(parseInt(teamInfo.score, 10) + 200, 'main', teamInfo.score);
					});

					// снова запрещаем ввод сообщений в чатбот
					// setTeamTabsTextInfo('chat_send_message_access', 0);

					// скрываем поле ввода текста в чат-боте
					viewChatFormMessageHidden();

					// обновляем mission progress
					incrementProgressMission(15);

					// обновляем содержимое dashboard
					uploadTypeTabsDashboardStep('room_name', false);

					// Обновить к-во неоткрытых файлов
					updateDontOpenFilesQt();

					// Обновить к-во неоткрытых баз данных
					// updateDontOpenDatabasesQt();

					// Обновить к-во неоткрытых tools
					updateDontOpenToolsQt();

					// открываем чатбот и пишем первые 2 сообщения
					// chatPrintFirstMessagesFromBot();
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {	
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
	// если запуск из сокета дубляж (не увеличиваем значения в бд)
	function mettingPlaceFromSocket() {
		// добавляем очки
		incrementScoreWithoutSaveDb(scoreBeforeDashboardMettingPlace + 200, 'main', scoreBeforeDashboardMettingPlace);

		// обновляем mission progress
		incrementProgressMissionWithoutSaveDb(15);

		// обновляем содержимое dashboard
		uploadTypeTabsDashboardStep('room_name', false);

		// Обновить к-во неоткрытых файлов
		updateDontOpenFilesQt();

		// Обновить к-во неоткрытых tools
		updateDontOpenToolsQt();
	}

	// нажали на кнопку отправки данных
	function mettingPlaceSubmit(streetName, houseNumber, city, country, lang_abbr2) {
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
			    	formData.append('op', 'validateMettingPlaceSearch');
			    	formData.append('street_name', streetName);
			    	formData.append('house_number', houseNumber);
			    	formData.append('city', city);
			    	formData.append('country', country);
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
								// попап с текстом успеха
								$('#popup_success .popup_success_input').html(json.success_lang[$('html').attr('lang')].success_input);
								$('#popup_success .popup_success_text').html(json.success_lang[$('html').attr('lang')].success_text);
								$('#popup_success .popup_success_close .btn span').html(json.success_lang[$('html').attr('lang')].success_close);
								$('#popup_success').addClass('popup_success_metting_place').css('display','block');

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

	// закрыть попап входящего звонка
	function mettingPlaceCloseIncomingCall() {
		// останавливаем звук звонка и запускаем фоновое
		// if (music_before) {
		if ($('.music_on').length && $('.music_on').hasClass('music_active')) {
			playMusic();
		}
		// music_before = false;

		clearInterval(incomingMusicTimer);
		incomingMusicTimer = false;

		if (incomingAudio && isPlaying(incomingAudio)) {
			incomingAudio.pause();
		}

		// останавливаем обновление времени
		clearInterval(incomingCallTimer);
		incomingCallTimer = false;

		// скрываем блок с телефоном
		$('#popup_video_phone').fadeOut(200);

		// повторно показываем попап, что правильно ввели данные в форме
		$('#popup_success').addClass('popup_success_metting_place').fadeIn(200);

		// очищаем данные
		setTimeout(function(){
			$('#popup_video_phone .popup_video_phone_wifi_icons').html('');
			$('#popup_video_phone .popup_video_phone_name').html('');
			$('#popup_video_phone').attr('class','');
		}, 210);
	}

$(function() {
	// ввод данных в поля формы
	/*$('.dashboard_tabs[data-dashboard="dashboard"]').on('keyup', '.dashboard_metting_place_street_name, .dashboard_metting_place_house_number, .dashboard_metting_place_city', function(e){
		if (e.which == 13) {
			$('.dashboard_metting_place_search').trigger('click');
		} else {
			// socket
		}
	});*/
	$('.dashboard_tabs[data-dashboard="dashboard"]').on('keyup', '.dashboard_metting_place_street_name', function(e){
		if (e.which == 13) {
			$('.dashboard_metting_place_search').trigger('click');
		} else {
			// socket
			var message = {
				'op': 'dashboardMettingPlaceKeyupStreet',
				'parameters': {
					'street': $(this).val(),
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));
		}
	});
	$('.dashboard_tabs[data-dashboard="dashboard"]').on('keyup', '.dashboard_metting_place_house_number', function(e){
		if (e.which == 13) {
			$('.dashboard_metting_place_search').trigger('click');
		} else {
			// socket
			var message = {
				'op': 'dashboardMettingPlaceKeyupHouse',
				'parameters': {
					'house': $(this).val(),
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));
		}
	});
	$('.dashboard_tabs[data-dashboard="dashboard"]').on('keyup', '.dashboard_metting_place_city', function(e){
		if (e.which == 13) {
			$('.dashboard_metting_place_search').trigger('click');
		} else {
			// socket
			var message = {
				'op': 'dashboardMettingPlaceKeyupCity',
				'parameters': {
					'city': $(this).val(),
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));
		}
	});

	// отправить данные из формы поиска
	$('body').on('click', '.dashboard_metting_place_search', function(e){
		var err = false;
		var streetName = $.trim($('.dashboard_metting_place_street_name').val());
		var houseNumber = $.trim($('.dashboard_metting_place_house_number').val());
		var city = $.trim($('.dashboard_metting_place_city').val());
		var country = $('.dashboard_metting_place_country').val();

		if (streetName == '') {
			$('.dashboard_metting_place_street_name_error').addClass('error_text_database_car_register_active');
			err = true;
		} else {
			$('.dashboard_metting_place_street_name_error').removeClass('error_text_database_car_register_active');
		}

		if (houseNumber == '') {
			$('.dashboard_metting_place_house_number_error').addClass('error_text_database_car_register_active');
			err = true;
		} else {
			$('.dashboard_metting_place_house_number_error').removeClass('error_text_database_car_register_active');
		}

		if (city == '') {
			$('.dashboard_metting_place_city_error').addClass('error_text_database_car_register_active');
			err = true;
		} else {
			$('.dashboard_metting_place_city_error').removeClass('error_text_database_car_register_active');
		}

		if ($.type(country) === "null") {
			$('.dashboard_metting_place_country_error').addClass('error_text_database_car_register_active');
			err = true;
		} else {
			$('.dashboard_metting_place_country_error').removeClass('error_text_database_car_register_active');
		}

		if (!err) {
			// socket
			var message = {
				'op': 'dashboardMettingPlaceNoEmptyFields',
				'parameters': {
					'street_name': streetName,
					'house_number': houseNumber,
					'city': city,
					'country': country,
					'lang_abbr': $('html').attr('lang'),
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));

			mettingPlaceSubmit(streetName, houseNumber, city, country, $('html').attr('lang'));
		} else {
			// socket
			var message = {
				'op': 'dashboardMettingPlaceEmptyFields',
				'parameters': {
					'street_name_error': (streetName == '') ? true : false,
					'house_number_error': (houseNumber == '') ? true : false,
					'city_error': (city == '') ? true : false,
					'country_error': (country == '' || $.type(country) === "null") ? true : false,
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));
		}
	});

	// закрыть попап при правильном вводе данных
	$('body').on('click', '.popup_success_metting_place .popup_success_close', function(e){
		$('#popup_success').removeClass('popup_success_metting_place').fadeOut(200);
		// socket
	/*	var message = {
			'op': 'dashboardMettingPlaceCloseSuccessPopupAndOpenOutgoingCall',
			'parameters': {
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
        };
        sendMessageSocket(JSON.stringify(message));

		// закрыть попап с успешным выполнением
		$('#popup_success').removeClass('popup_success_metting_place').fadeOut(200);

		// открываем попап входящего звонка
		mettingPlaceOpenOutgoingCall();*/

	// --- ДОБАВЛЕНО: Завершение игры ---
	// Получаем время и очки через AJAX и отправляем finishGame через сокет
	var formData = new FormData();
	formData.append('op', 'finishGame');
	$.ajax({
		url: '/ajax/ajax_dashboard.php',
		type: 'POST',
		dataType: 'json',
		cache: false,
		contentType: false,
		processData: false,
		data: formData,
		success: function(json) {
			var finishMessage = {
				'op': 'finishGame',
				'parameters': {
					'hours': json.hours,
					'minute': json.minute,
					'second': json.second,
					'score': json.score,
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
			};
			sendMessageSocket(JSON.stringify(finishMessage));
			if (typeof finishGame === 'function') {
				finishGame(json.hours, json.minute, json.second, json.score);
			}
		}
	});
	// --- КОНЕЦ ДОБАВЛЕНИЯ ---
});

	// ввели верно - принять входящий звонок
	$('body').on('click', '.popup_video_phone_outgoing_metting_place .popup_video_phone_btn_answer_wrapper', function(e){
		// socket
		var message = {
			'op': 'dashboardMettingPlaceCallAnswer',
			'parameters': {
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
        };
        sendMessageSocket(JSON.stringify(message));

		// запускаем фоновую музыку, если была
		// if (music_before) {
		if ($('.music_on').length && $('.music_on').hasClass('music_active')) {
			playMusic();
		}
		// music_before = false;

		// останавливаем звук звонка
		clearInterval(incomingMusicTimer);
		incomingMusicTimer = false;

		if (incomingAudio && isPlaying(incomingAudio)) {
			incomingAudio.pause();
		}

		// останавливаем обновление времени
		clearInterval(incomingCallTimer);
		incomingCallTimer = false;

		// скрываем блок с телефоном
		$('#popup_video_phone').fadeOut(200);

		// очищаем данные в блоке с телефоном
		setTimeout(function(){
			$('#popup_video_phone .popup_video_phone_wifi_icons').html('');
			$('#popup_video_phone .popup_video_phone_name').html('');
			$('#popup_video_phone').attr('class','');
		}, 210);

		// открыть видео и сразу запустить его
		playVideoByNotControls = true; // указываем, что запускалось через кнопку Play, а не через Controls
		// openFileVideoPopup(0, 'video/' + $('html').attr('lang') + '/video_jane_5.mp4', '', 'metting_place_answer_incoming_video', 'call');
		// playVideo('call');
		openFileVideoPopupCall(0, 'video/' + $('html').attr('lang') + '/video_jane_5.mp4', '', 'metting_place_answer_incoming_video', 'call_jane');
		playVideoCall();

		/*// когда видео доиграло до конца, то закрываем и производим нужные действия
		$('.metting_place_answer_incoming_video video').on('ended', function() {
			closePopupVideo();

			// запускаем обновление данных
			mettingPlace();
		});*/

		// сохранить время просмотра видео в списке звонков команды
		var formData = new FormData();
    	formData.append('op', 'updateDatetimeCall');
    	formData.append('lang_abbr', $('html').attr('lang'));
    	formData.append('call_id', 8);

    	$.ajax({
			url: '/ajax/ajax_calls.php',
	        type: "POST",
	        dataType: "json",
	        cache: false,
	        contentType: false,
	        processData: false,
	        data: formData,
			success: function(json) {},
			error: function(xhr, ajaxOptions, thrownError) {	
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

	/*// когда видео доиграло до конца, то закрываем и производим нужные действия
	$('body').on('ended', '.metting_place_answer_incoming_video video', function(e){
		closePopupVideo();

		// фиксируем к-во очков, которое было у команды перед успешным результатом поиска. Для правильного подсчета очков команды
		$.when(getTeamInfo()).done(function(teamResponse){
			var teamInfo = teamResponse.success;

			scoreBeforeDashboardMettingPlace = parseInt(teamInfo.score, 10);

			// socket
			var message = {
				'op': 'closePopupVideoAndMettingPlaceSuccess',
				'parameters': {
					'scoreBeforeDashboardMettingPlace': scoreBeforeDashboardMettingPlace,
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));

	        // запускаем обновление данных
			mettingPlace();
		});
	});*/

	// закрыть попап входящего звонка
	$('body').on('click', '.popup_video_phone_outgoing_metting_place .popup_video_phone_bg, .popup_video_phone_outgoing_metting_place .popup_video_phone_btn_decline_wrapper', function(e){
		// socket
		var message = {
			'op': 'dashboardMettingPlaceCloseIncomingCall',
			'parameters': {
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
        };
        sendMessageSocket(JSON.stringify(message));

        mettingPlaceCloseIncomingCall();
	});

	// закрыть попап с видео
	$('body').on('click', '.metting_place_answer_incoming_video .popup_video_phone_video_bg, .metting_place_answer_incoming_video .popup_video_close', function(e){
		// function
		// stopVideo();
		// closePopupVideo();
		stopVideoCall();
		closePopupVideoCall();

		// фиксируем к-во очков, которое было у команды перед успешным результатом поиска. Для правильного подсчета очков команды
		$.when(getTeamInfo()).done(function(teamResponse){
			var teamInfo = teamResponse.success;

			scoreBeforeDashboardMettingPlace = parseInt(teamInfo.score, 10);

			// socket
			var message = {
				'op': 'stopVideoAndClosePopupVideoAndMettingPlaceSuccess',
				'parameters': {
					'scoreBeforeDashboardMettingPlace': scoreBeforeDashboardMettingPlace,
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));

	        // запускаем обновление данных
			mettingPlace();
		});
	});
});