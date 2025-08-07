/* === DASHBOARD - AFRICAN PARTNER === */

/* ОБЩИЕ ФУНКЦИИ */
	// african partner ввели верно, открываем попап исходящего звонка
	function africanPartnerOpenOutgoingCall() {
		// запускаем отображение времени
		updateIncomingTime();
		incomingCallTimer = setInterval(function(){
			updateIncomingTime();
		}, 1000);

		$('#popup_video_phone .popup_video_phone_wifi_icons').html('<img src="/images/wifi_icons.png" alt="">');
		$('#popup_video_phone .popup_video_phone_name').html('Jane Blond');
		$('#popup_video_phone').attr('class','').addClass('popup_video_phone_outgoing_african_partner');

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

	// african partner ввели верно - просмотрели incoming video либо закрыли попап с видео
	function africanPartner() {
		var formData = new FormData();
    	formData.append('op', 'africanPartnerUpdateHint');
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
					uploadTypeTabsDashboardStep('metting_place', false);

					// Обновить к-во неоткрытых баз данных
					updateDontOpenDatabasesQt();

					// Обновить к-во неоткрытых tools
					updateDontOpenToolsQt();

					// открываем чатбот и пишем первые 2 сообщения
					chatPrintFirstMessagesFromBot();

					// Добавляем к Calls единичку как индикатор того, что доступна кнопка Call mobile
					$('.dashboard_item[data-dashboard="calls"]').find('.dashboard_item_text_qt').html('1').css('display', 'inline-block');

					if ($('.call_mobile').length > 0) {
						$('.call_mobile .dashboard_item_text_qt').html('1').css('display', 'inline-block');
					}
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {	
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
	// если запуск из сокета дубляж (не увеличиваем значения в бд)
	function africanPartnerFromSocket() {
		// добавляем очки
		incrementScoreWithoutSaveDb(scoreBeforeDashboardAfricanPartner + 200, 'main', scoreBeforeDashboardAfricanPartner);

		// обновляем mission progress
		incrementProgressMissionWithoutSaveDb(10);

		// обновляем содержимое dashboard
		uploadTypeTabsDashboardStep('metting_place', false);

		// Обновить к-во неоткрытых баз данных
		updateDontOpenDatabasesQt();

		// Обновить к-во неоткрытых tools
		updateDontOpenToolsQt();

		// открываем чатбот и пишем первые 2 сообщения
		// chatPrintFirstMessagesFromBot();

		// Добавляем к Calls единичку как индикатор того, что доступна кнопка Call mobile
		$('.dashboard_item[data-dashboard="calls"]').find('.dashboard_item_text_qt').html('1').css('display', 'inline-block');
	}

	// нажали на кнопку отправки данных
	function africanPartnerSubmit(companyName, country, date, lang_abbr2) {
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
			    	formData.append('op', 'validateAfricanPartnerSearch');
			    	formData.append('company_name', companyName);
			    	formData.append('country', country);
			    	formData.append('date', date);
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
								$('#popup_success').addClass('popup_success_african_partner').css('display','block');

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
	function africanPartnerCloseIncomingCall() {
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
		$('#popup_success').addClass('popup_success_african_partner').fadeIn(200);

		// очищаем данные
		setTimeout(function(){
			$('#popup_video_phone .popup_video_phone_wifi_icons').html('');
			$('#popup_video_phone .popup_video_phone_name').html('');
			$('#popup_video_phone').attr('class','');
		}, 210);
	}

$(function() {
	// ввод данных в поле Company Name
	$('.dashboard_tabs[data-dashboard="dashboard"]').on('keyup', '.dashboard_african_partner_company_name', function(e){
		if (e.which == 13) {
			$('.dashboard_african_partner_search').trigger('click');
		} else {
			// socket
			var message = {
				'op': 'dashboardAfricanPartnerKeyupCompanyName',
				'parameters': {
					'company_name': $(this).val(),
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));
		}
	});

	// отправить данные из формы поиска
	$('body').on('click', '.dashboard_african_partner_search', function(e){
		var err = false;
		var companyName = $.trim($('.dashboard_african_partner_company_name').val());
		var country = $.trim($('.dashboard_african_partner_country').val());
		var date = $.trim($('.dashboard_african_partner_date').val());

		if (companyName == '') {
			$('.dashboard_african_partner_company_name_error').addClass('error_text_database_car_register_active');
			err = true;
		} else {
			$('.dashboard_african_partner_company_name_error').removeClass('error_text_database_car_register_active');
		}

		if (country == '' || $.type(country) === "null") {
			$('.dashboard_african_partner_country_error').addClass('error_text_database_car_register_active');
			err = true;
		} else {
			$('.dashboard_african_partner_country_error').removeClass('error_text_database_car_register_active');
		}

		if (date == '') {
			$('.dashboard_african_partner_date_error').addClass('error_text_database_car_register_active');
			err = true;
		} else {
			$('.dashboard_african_partner_date_error').removeClass('error_text_database_car_register_active');
		}

		if (!err) {
			// socket
			var message = {
				'op': 'dashboardAfricanPartnerNoEmptyFields',
				'parameters': {
					'company_name': companyName,
					'country': country,
					'date': date,
					'lang_abbr': $('html').attr('lang'),
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));

			africanPartnerSubmit(companyName, country, date, $('html').attr('lang'));
		} else {
			// socket
			var message = {
				'op': 'dashboardAfricanPartnerEmptyFields',
				'parameters': {
					'company_name_error': (companyName == '') ? true : false,
					'country_error': (country == '' || $.type(country) === "null") ? true : false,
					'date_error': (date == '') ? true : false,
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));
		}
	});

	// закрыть попап при правильном вводе данных
	$('body').on('click', '.popup_success_african_partner .popup_success_close', function(e){
		// socket
		var message = {
			'op': 'dashboardAfricanPartnerCloseSuccessPopupAndOpenOutgoingCall',
			'parameters': {
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
        };
        sendMessageSocket(JSON.stringify(message));

		// закрыть попап с успешным выполнением
		$('#popup_success').removeClass('popup_success_african_partner').fadeOut(200);

		// открываем попап входящего звонка
		africanPartnerOpenOutgoingCall();
	});

	// african partner ввели верно - принять входящий звонок
	$('body').on('click', '.popup_video_phone_outgoing_african_partner .popup_video_phone_btn_answer_wrapper', function(e){
		// socket
		var message = {
			'op': 'dashboardAfricanPartnerCallAnswer',
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
		openFileVideoPopup(0, 'video/' + $('html').attr('lang') + '/video_jane_4.mp4', '', 'african_partner_answer_incoming_video', 'call');
		playVideo('call');
		// openFileVideoPopupCall(0, 'video/' + $('html').attr('lang') + '/video_jane_4.mp4', '', 'african_partner_answer_incoming_video', 'call_jane');
		// playVideoCall();

		/*// когда видео доиграло до конца, то закрываем и производим нужные действия
		$('.african_partner_answer_incoming_video video').on('ended', function() {
			closePopupVideo();

			// запускаем обновление данных
			africanPartner();
		});*/

		// сохранить время просмотра видео в списке звонков команды
		var formData = new FormData();
    	formData.append('op', 'updateDatetimeCall');
    	formData.append('lang_abbr', $('html').attr('lang'));
    	formData.append('call_id', 7);

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
	$('body').on('ended', '.african_partner_answer_incoming_video video', function(e){
		closePopupVideo();

		// фиксируем к-во очков, которое было у команды перед успешным результатом поиска. Для правильного подсчета очков команды
		$.when(getTeamInfo()).done(function(teamResponse){
			var teamInfo = teamResponse.success;

			scoreBeforeDashboardAfricanPartner = parseInt(teamInfo.score, 10);

			// socket
			var message = {
				'op': 'closePopupVideoAndAfricanPartnerSuccess',
				'parameters': {
					'scoreBeforeDashboardAfricanPartner': scoreBeforeDashboardAfricanPartner,
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));

	        // запускаем обновление данных
			africanPartner();
		});
	});*/

	// african partner - закрыть попап входящего звонка
	$('body').on('click', '.popup_video_phone_outgoing_african_partner .popup_video_phone_bg, .popup_video_phone_outgoing_african_partner .popup_video_phone_btn_decline_wrapper', function(e){
		// socket
		var message = {
			'op': 'dashboardAfricanPartnerCloseIncomingCall',
			'parameters': {
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
        };
        sendMessageSocket(JSON.stringify(message));

        africanPartnerCloseIncomingCall();
	});

	// african partner - закрыть попап с видео
	$('body').on('click', '.african_partner_answer_incoming_video .popup_video_phone_video_bg, .african_partner_answer_incoming_video .popup_video_close', function(e){
		// function
		stopVideo();
		closePopupVideo();
		// stopVideoCall();
		// closePopupVideoCall();

		// фиксируем к-во очков, которое было у команды перед успешным результатом поиска. Для правильного подсчета очков команды
		$.when(getTeamInfo()).done(function(teamResponse){
			var teamInfo = teamResponse.success;

			scoreBeforeDashboardAfricanPartner = parseInt(teamInfo.score, 10);

			// socket
			var message = {
				'op': 'stopVideoAndClosePopupVideoAndAfricanPartnerSuccess',
				'parameters': {
					'scoreBeforeDashboardAfricanPartner': scoreBeforeDashboardAfricanPartner,
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));

	        // запускаем обновление данных
			africanPartner();
		});
	});
});