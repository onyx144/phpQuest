/* === DASHBOARD - COMPANY INVESTIGATE === */

/* ОБЩИЕ ФУНКЦИИ */
	// нажали на кнопку отправки данных
	function companyInvestigateSubmit(companyName, lang_abbr2) {
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
			    	formData.append('op', 'validateDashboardCompanyInvestigateSearch');
			    	formData.append('company_name', companyName);
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
								$('#popup_success').addClass('popup_success_company_investigate').css('display','block');

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

	// company investigate ввели верно, открываем попап входящего звонка
	function companyInvestigateOpenIncomingCall() {
		// запускаем отображение времени
		updateIncomingTime();
		incomingCallTimer = setInterval(function(){
			updateIncomingTime();
		}, 1000);

		$('#popup_video_phone .popup_video_phone_wifi_icons').html('<img src="/images/wifi_icons.png" alt="">');
		$('#popup_video_phone .popup_video_phone_name').html('Jane Blond');
		$('#popup_video_phone').attr('class','').addClass('popup_video_phone_incoming_company_investigate');

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

	// company investigate ввели верно - просмотрели incoming video либо закрыли попап входящего звонка
	function companyInvestigate() {
		var formData = new FormData();
    	formData.append('op', 'companyInvestigateUpdateHint');
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
						incrementScore(parseInt(teamInfo.score, 10) + 100, 'main', teamInfo.score);
					});

					// обновляем mission progress
					incrementProgressMission(5);

					// обновляем содержимое dashboard
					uploadTypeTabsDashboardStep('geo_coordinates', false);

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
	function companyInvestigateFromSocket() {
		// добавляем очки
		incrementScoreWithoutSaveDb(scoreBeforeDashboardCompanyInvestigate + 100, 'main', scoreBeforeDashboardCompanyInvestigate);

		// обновляем mission progress
		incrementProgressMissionWithoutSaveDb(5);

		// обновляем содержимое dashboard
		uploadTypeTabsDashboardStep('geo_coordinates', false);

		// Обновить к-во непрочитанных файлов
		updateDontOpenFilesQt();

		// Обновить к-во неоткрытых tools
		updateDontOpenToolsQt();
	}

	// company investigate - закрыть попап входящего звонка
	function companyInvestigateCloseIncomingCall() {
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
		$('#popup_success').addClass('popup_success_company_investigate').fadeIn(200);

		// очищаем данные
		setTimeout(function(){
			$('#popup_video_phone .popup_video_phone_wifi_icons').html('');
			$('#popup_video_phone .popup_video_phone_name').html('');
			$('#popup_video_phone').attr('class','');
		}, 210);
	}

$(function() {
	// вводим данные в поля ввода текста
	$('.dashboard_tabs[data-dashboard="dashboard"]').on('keyup', '.dashboard_tab_content_item_company_name_input', function(e){
		if ($('.dashboard_tab_content_item_company_name_investigate').length) {
			if (e.which == 13) {
				$('.dashboard_tab_content_item_company_name_investigate').trigger('click');
			} else {
				// socket
				var message = {
					'op': 'dashboardCompanyNameKeyup',
					'parameters': {
						'company_name': $(this).val(),
						'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
						'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
					}
		        };
		        sendMessageSocket(JSON.stringify(message));
			}
		}
	});

	// нажали на кнопку отправки
	$('body').on('click', '.dashboard_tab_content_item_company_name_investigate', function(e){
		var err = false;
		var companyName = $.trim($('.dashboard_tab_content_item_company_name_input').val());

		if (companyName == '') {
			$('.dashboard_tab_content_item_company_name_error').addClass('error_text_database_car_register_active');
			err = true;
		} else {
			$('.dashboard_tab_content_item_company_name_error').removeClass('error_text_database_car_register_active');
		}

		if (!err) {
			// socket
			var message = {
				'op': 'dashboardCompanyNameNoEmptyFields',
				'parameters': {
					'company_name': companyName,
					'lang_abbr': $('html').attr('lang'),
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));

	        companyInvestigateSubmit(companyName, $('html').attr('lang'));
		} else {
			// socket
			var message = {
				'op': 'dashboardCompanyNameEmptyFields',
				'parameters': {
					'company_name_error': (companyName == '') ? true : false,
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));
		}
	});

	// закрыть попап при правильном вводе данных
	$('body').on('click', '.popup_success_company_investigate .popup_success_close', function(e){
		// socket
		var message = {
			'op': 'dashboardCompanyInvestigateCloseSuccessPopupAndOpenIncomingCall',
			'parameters': {
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
        };
        sendMessageSocket(JSON.stringify(message));

		// закрыть попап с успешным выполнением
		$('#popup_success').removeClass('popup_success_company_investigate').fadeOut(200);

		// открываем попап входящего звонка
		companyInvestigateOpenIncomingCall();
	});

	// company investigate ввели верно - принять входящий звонок
	$('body').on('click', '.popup_video_phone_incoming_company_investigate .popup_video_phone_btn_answer_wrapper', function(e){
		// socket
		var message = {
			'op': 'dashboardCompanyInvestigateCallAnswer',
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
		openFileVideoPopup(0, 'video/' + $('html').attr('lang') + '/video_jane_2.mp4', '', 'company_investigate_answer_incoming_video', 'call');
		playVideo('call');
		// openFileVideoPopupCall(0, 'video/' + $('html').attr('lang') + '/video_jane_2.mp4', '', 'company_investigate_answer_incoming_video', 'call_jane');
		// playVideoCall();

		// сохранить время просмотра видео в списке звонков команды
		var formData = new FormData();
    	formData.append('op', 'updateDatetimeCall');
    	formData.append('lang_abbr', $('html').attr('lang'));
    	formData.append('call_id', 5);

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
	$('body').on('ended', '.company_investigate_answer_incoming_video video', function(e){
		// closePopupVideo();
		closePopupVideoCall();

		// фиксируем к-во очков, которое было у команды перед успешным результатом поиска. Для правильного подсчета очков команды
		$.when(getTeamInfo()).done(function(teamResponse){
			var teamInfo = teamResponse.success;

			scoreBeforeDashboardCompanyInvestigate = parseInt(teamInfo.score, 10);

			// socket
			var message = {
				'op': 'closePopupVideoAndCompanyInvestigateSuccess',
				'parameters': {
					'scoreBeforeDashboardCompanyInvestigate': scoreBeforeDashboardCompanyInvestigate,
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));

	        // запускаем обновление данных
			companyInvestigate();
		});
	});*/

	// company investigate - закрыть попап входящего звонка
	$('body').on('click', '.popup_video_phone_incoming_company_investigate .popup_video_phone_bg, .popup_video_phone_incoming_company_investigate .popup_video_phone_btn_decline_wrapper', function(e){
		// socket
		var message = {
			'op': 'dashboardCompanyInvestigateCloseIncomingCall',
			'parameters': {
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
        };
        sendMessageSocket(JSON.stringify(message));

		companyInvestigateCloseIncomingCall();
	});

	// company investigate - закрыть попап с видео
	$('body').on('click', '.company_investigate_answer_incoming_video .popup_video_phone_video_bg, .company_investigate_answer_incoming_video .popup_video_close', function(e){
		stopVideo();
		closePopupVideo();
		// stopVideoCall();
		// closePopupVideoCall();

		// фиксируем к-во очков, которое было у команды перед успешным результатом поиска. Для правильного подсчета очков команды
		$.when(getTeamInfo()).done(function(teamResponse){
			var teamInfo = teamResponse.success;

			scoreBeforeDashboardCompanyInvestigate = parseInt(teamInfo.score, 10);

			// socket
			var message = {
				'op': 'stopVideoAndClosePopupVideoAndCompanyInvestigateSuccess',
				'parameters': {
					'scoreBeforeDashboardCompanyInvestigate': scoreBeforeDashboardCompanyInvestigate,
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));

	        // запускаем обновление данных
			companyInvestigate();
		});
	});
});