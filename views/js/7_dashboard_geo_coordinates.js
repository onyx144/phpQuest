/* === DASHBOARD - GEO COORDINATES === */

/* ОБЩИЕ ФУНКЦИИ */
	// geo coordinates ввели верно, открываем попап исходящего звонка
	function geoCoordinatesOpenOutgoingCall() {
		// запускаем отображение времени
		updateIncomingTime();
		incomingCallTimer = setInterval(function(){
			updateIncomingTime();
		}, 1000);

		$('#popup_video_phone .popup_video_phone_wifi_icons').html('<img src="/zombie/images/wifi_icons.png" alt="">');
		$('#popup_video_phone .popup_video_phone_name').html('Jane Blond');
		$('#popup_video_phone').attr('class','').addClass('popup_video_phone_outgoing_geo_coordinates');

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

	// geo coordinates ввели верно - просмотрели incoming video либо закрыли попап входящего звонка
	function geoCoordinates() {
		var formData = new FormData();
    	formData.append('op', 'geoCoordinatesUpdateHint');
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

					// обновляем mission progress
					incrementProgressMission(10);

					// обновляем содержимое dashboard
					uploadTypeTabsDashboardStep('african_partner', false);

					// Обновить к-во непрочитанных файлов
					updateDontOpenFilesQt();

					// Обновить к-во неоткрытых tools
					updateDontOpenToolsQt();
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {	
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
	// если запуск из сокета дубляж (не увеличиваем значения в бд)
	function geoCoordinatesFromSocket() {
		// добавляем очки
		incrementScoreWithoutSaveDb(scoreBeforeDashboardCoordinates + 200, 'main', scoreBeforeDashboardCoordinates);

		// обновляем mission progress
		incrementProgressMissionWithoutSaveDb(10);

		// обновляем содержимое dashboard
		uploadTypeTabsDashboardStep('african_partner', false);

		// Обновить к-во непрочитанных файлов
		updateDontOpenFilesQt();

		// Обновить к-во неоткрытых tools
		updateDontOpenToolsQt();
	}

	// нажали на кнопку отправки данных
	function dashboardCoordinatesSubmit(latitude1, latitude2, latitude3, longitude1, longitude2, longitude3, lang_abbr2) {
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
			    	formData.append('op', 'validateDashboardGeoCoordinatesSearch');
			    	formData.append('latitude1', latitude1);
			    	formData.append('latitude2', latitude2);
			    	formData.append('latitude3', latitude3);
			    	formData.append('longitude1', longitude1);
			    	formData.append('longitude2', longitude2);
			    	formData.append('longitude3', longitude3);
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
								// звук тревоги
								successAudio = new Audio;
								successAudio.src = '/music/warning.mp3';
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

								// попап с текстом успеха
								$('#popup_success_pollution').addClass('popup_success_pollution_geo_coordinates').css('display','block');

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
						    			$('#popup_success_pollution').css('height', windowHeight + 'px');
						    		}
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
	function dashboardCoordinatesCloseIncomingCall() {
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
		$('#popup_success_pollution').addClass('popup_success_pollution_geo_coordinates').fadeIn(200);

		// очищаем данные
		setTimeout(function(){
			$('#popup_video_phone .popup_video_phone_wifi_icons').html('');
			$('#popup_video_phone .popup_video_phone_name').html('');
			$('#popup_video_phone').attr('class','');
		}, 210);
	}

$(function() {
	// вводим данные в поля ввода текста
	/*$('.dashboard_tabs[data-dashboard="dashboard"]').on('keyup', '.dashboard_tab_content_item_geo_coordinates_latitude1_input, .dashboard_tab_content_item_geo_coordinates_latitude2_input, .dashboard_tab_content_item_geo_coordinates_latitude3_input, .dashboard_tab_content_item_geo_coordinates_longitude1_input, .dashboard_tab_content_item_geo_coordinates_longitude2_input, .dashboard_tab_content_item_geo_coordinates_longitude3_input', function(e){
		if (e.which == 13) {
			$('.dashboard_tab_content_item_geo_coordinates_btn').trigger('click');
		} else {
			// socket
		}
	});*/
	$('.dashboard_tabs[data-dashboard="dashboard"]').on('keyup', '.dashboard_tab_content_item_geo_coordinates_latitude1_input', function(e){
		if (e.which == 13) {
			$('.dashboard_tab_content_item_geo_coordinates_btn').trigger('click');
		} else {
			// socket
			var message = {
				'op': 'dashboardCoordinatesLatitude1Keyup',
				'parameters': {
					'value': $(this).val(),
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message))
		}
	});
	$('.dashboard_tabs[data-dashboard="dashboard"]').on('keyup', '.dashboard_tab_content_item_geo_coordinates_latitude2_input', function(e){
		if (e.which == 13) {
			$('.dashboard_tab_content_item_geo_coordinates_btn').trigger('click');
		} else {
			// socket
			var message = {
				'op': 'dashboardCoordinatesLatitude2Keyup',
				'parameters': {
					'value': $(this).val(),
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message))
		}
	});
	$('.dashboard_tabs[data-dashboard="dashboard"]').on('keyup', '.dashboard_tab_content_item_geo_coordinates_latitude3_input', function(e){
		if (e.which == 13) {
			$('.dashboard_tab_content_item_geo_coordinates_btn').trigger('click');
		} else {
			// socket
			var message = {
				'op': 'dashboardCoordinatesLatitude3Keyup',
				'parameters': {
					'value': $(this).val(),
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message))
		}
	});
	$('.dashboard_tabs[data-dashboard="dashboard"]').on('keyup', '.dashboard_tab_content_item_geo_coordinates_longitude1_input', function(e){
		if (e.which == 13) {
			$('.dashboard_tab_content_item_geo_coordinates_btn').trigger('click');
		} else {
			// socket
			var message = {
				'op': 'dashboardCoordinatesLongitude1Keyup',
				'parameters': {
					'value': $(this).val(),
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message))
		}
	});
	$('.dashboard_tabs[data-dashboard="dashboard"]').on('keyup', '.dashboard_tab_content_item_geo_coordinates_longitude2_input', function(e){
		if (e.which == 13) {
			$('.dashboard_tab_content_item_geo_coordinates_btn').trigger('click');
		} else {
			// socket
			var message = {
				'op': 'dashboardCoordinatesLongitude2Keyup',
				'parameters': {
					'value': $(this).val(),
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message))
		}
	});
	$('.dashboard_tabs[data-dashboard="dashboard"]').on('keyup', '.dashboard_tab_content_item_geo_coordinates_longitude3_input', function(e){
		if (e.which == 13) {
			$('.dashboard_tab_content_item_geo_coordinates_btn').trigger('click');
		} else {
			// socket
			var message = {
				'op': 'dashboardCoordinatesLongitude3Keyup',
				'parameters': {
					'value': $(this).val(),
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message))
		}
	});

	// нажали на кнопку отправки
	$('body').on('click', '.dashboard_tab_content_item_geo_coordinates_btn', function(e){
		var err = false;
		var latitude1 = $.trim($('.dashboard_tab_content_item_geo_coordinates_latitude1_input').val());
		var latitude2 = $.trim($('.dashboard_tab_content_item_geo_coordinates_latitude2_input').val());
		var latitude3 = $.trim($('.dashboard_tab_content_item_geo_coordinates_latitude3_input').val());
		var longitude1 = $.trim($('.dashboard_tab_content_item_geo_coordinates_longitude1_input').val());
		var longitude2 = $.trim($('.dashboard_tab_content_item_geo_coordinates_longitude2_input').val());
		var longitude3 = $.trim($('.dashboard_tab_content_item_geo_coordinates_longitude3_input').val());

		if (latitude1 == '' || latitude2 == '' || latitude3 == '') {
			$('.dashboard_tab_content_item_geo_coordinates_input_wrapper_row_latitude').addClass('dashboard_tab_content_item_geo_coordinates_input_wrapper_row_has_error');
			err = true;
		} else {
			$('.dashboard_tab_content_item_geo_coordinates_input_wrapper_row_latitude').removeClass('dashboard_tab_content_item_geo_coordinates_input_wrapper_row_has_error');
		}

		if (longitude1 == '' || longitude2 == '' || longitude3 == '') {
			$('.dashboard_tab_content_item_geo_coordinates_input_wrapper_row_longitude').addClass('dashboard_tab_content_item_geo_coordinates_input_wrapper_row_has_error');
			err = true;
		} else {
			$('.dashboard_tab_content_item_geo_coordinates_input_wrapper_row_longitude').removeClass('dashboard_tab_content_item_geo_coordinates_input_wrapper_row_has_error');
		}

		if (!err) {
			// socket
			var message = {
				'op': 'dashboardCoordinatesNoEmptyFields',
				'parameters': {
					'latitude1': latitude1,
					'latitude2': latitude2,
					'latitude3': latitude3,
					'longitude1': longitude1,
					'longitude2': longitude2,
					'longitude3': longitude3,
					'lang_abbr': $('html').attr('lang'),
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));

	        dashboardCoordinatesSubmit(latitude1, latitude2, latitude3, longitude1, longitude2, longitude3, $('html').attr('lang'));
		} else {
			// socket
			var message = {
				'op': 'dashboardCoordinatesEmptyFields',
				'parameters': {
					'latitude1_error': (latitude1 == '') ? true : false,
					'latitude2_error': (latitude2 == '') ? true : false,
					'latitude3_error': (latitude3 == '') ? true : false,
					'longitude1_error': (longitude1 == '') ? true : false,
					'longitude2_error': (longitude2 == '') ? true : false,
					'longitude3_error': (longitude3 == '') ? true : false,
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));
		}
	});

	// закрыть попап при правильном вводе данных
	$('body').on('click', '.popup_success_pollution_geo_coordinates .popup_success_pollution_close', function(e){
		// socket
		var message = {
			'op': 'dashboardCoordinatesCloseSuccessPopupAndOpenOutgoingCall',
			'parameters': {
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
        };
        sendMessageSocket(JSON.stringify(message));

		// закрыть попап с успешным выполнением
		$('#popup_success_pollution').removeClass('popup_success_pollution_geo_coordinates').fadeOut(200);

		if (successAudio && isPlaying(successAudio)) {
			successAudio.pause();
		}

		// открываем попап исходящего звонка
		geoCoordinatesOpenOutgoingCall();
	});

	// geo coordinates ввели верно - принять входящий звонок
	$('body').on('click', '.popup_video_phone_outgoing_geo_coordinates .popup_video_phone_btn_answer_wrapper', function(e){
		// socket
		var message = {
			'op': 'dashboardCoordinatesCallAnswer',
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
		openFileVideoPopup(0, 'video/' + $('html').attr('lang') + '/zombie/video_jane_3.mp4', '', 'geo_coordinates_answer_incoming_video', 'call');
		playVideo('call');
		// openFileVideoPopupCall(0, 'video/' + $('html').attr('lang') + '/zombie/video_jane_3.mp4', '', 'geo_coordinates_answer_incoming_video', 'call_jane');
		// playVideoCall();

		/*// когда видео доиграло до конца, то закрываем и производим нужные действия
		$('.geo_coordinates_answer_incoming_video video').on('ended', function() {
			closePopupVideo();

			// запускаем обновление данных
			geoCoordinates();
		});*/

		// сохранить время просмотра видео в списке звонков команды
		var formData = new FormData();
    	formData.append('op', 'updateDatetimeCall');
    	formData.append('lang_abbr', $('html').attr('lang'));
    	formData.append('call_id', 6);

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
	$('body').on('ended', '.geo_coordinates_answer_incoming_video video', function(e){
		closePopupVideo();

		// фиксируем к-во очков, которое было у команды перед успешным результатом поиска. Для правильного подсчета очков команды
		$.when(getTeamInfo()).done(function(teamResponse){
			var teamInfo = teamResponse.success;

			scoreBeforeDashboardCoordinates = parseInt(teamInfo.score, 10);

			// socket
			var message = {
				'op': 'closePopupVideoAndCoordinatesSuccess',
				'parameters': {
					'scoreBeforeDashboardCoordinates': scoreBeforeDashboardCoordinates,
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));

	        // запускаем обновление данных
			geoCoordinates();
		});
	});*/

	// geo coordinates - закрыть попап входящего звонка
	$('body').on('click', '.popup_video_phone_outgoing_geo_coordinates .popup_video_phone_bg, .popup_video_phone_outgoing_geo_coordinates .popup_video_phone_btn_decline_wrapper', function(e){
		// socket
		var message = {
			'op': 'dashboardCoordinatesCloseIncomingCall',
			'parameters': {
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
        };
        sendMessageSocket(JSON.stringify(message));

		dashboardCoordinatesCloseIncomingCall();
	});

	// geo coordinates - закрыть попап с видео
	$('body').on('click', '.geo_coordinates_answer_incoming_video .popup_video_phone_video_bg, .geo_coordinates_answer_incoming_video .popup_video_close', function(e){
		// function
		stopVideo();
		closePopupVideo();
		// stopVideoCall();
		// closePopupVideoCall();

		// фиксируем к-во очков, которое было у команды перед успешным результатом поиска. Для правильного подсчета очков команды
		$.when(getTeamInfo()).done(function(teamResponse){
			var teamInfo = teamResponse.success;

			scoreBeforeDashboardCoordinates = parseInt(teamInfo.score, 10);

			// socket
			var message = {
				'op': 'stopVideoAndClosePopupVideoAndCoordinatesSuccess',
				'parameters': {
					'scoreBeforeDashboardCoordinates': scoreBeforeDashboardCoordinates,
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));

	        // запускаем обновление данных
			geoCoordinates();
		});
	});
});