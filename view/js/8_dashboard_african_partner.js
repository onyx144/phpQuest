/* === DASHBOARD - AFRICAN PARTNER === */

/* ОБЩИЕ ФУНКЦИИ */
	var africanPartnerJaneVideoSrc = '';
	var africanPartnerStageSwitched = false;
	var africanPartnerStagePersisted = false;
	var africanPartnerChatStarted = false;
	var africanPartnerShouldStartChat = false;

	function getAfricanPartnerJaneVideoSrc() {
		if (africanPartnerJaneVideoSrc) {
			return africanPartnerJaneVideoSrc;
		}
		return '/video/' + $('html').attr('lang') + '/video_jane_4.mp4';
	}

	function africanPartnerClearSuccessPopup() {
		$('#popup_success')
			.removeClass('popup_success_african_partner')
			.stop(true, true)
			.hide();
	}

	function africanPartnerPrepareDashboardSwitch() {
		if (typeof dashboardCache !== 'undefined') {
			dashboardCache.step = null;
			dashboardCache.titles = null;
			dashboardCache.content = null;
		}
		showDashboardTabsLoading();
	}

	function africanPartnerShowMettingPlaceDashboard() {
		africanPartnerPrepareDashboardSwitch();
		uploadTypeTabsDashboardStep('metting_place', false);
	}

	function africanPartnerApplySideEffects(isSocket) {
		updateDontOpenDatabasesQt();
		updateDontOpenToolsQt();

		$('.dashboard_item[data-dashboard="calls"]').find('.dashboard_item_text_qt').html('1').css('display', 'inline-block');

		if (!isSocket && $('.call_mobile').length > 0) {
			$('.call_mobile .dashboard_item_text_qt').html('1').css('display', 'inline-block');
		}
	}

	// Чат стартует после конца/закрытия видеозвонка (ajax_chatbot), не на Answer
	function africanPartnerStartChat() {
		if (!africanPartnerShouldStartChat || africanPartnerChatStarted) {
			return;
		}
		africanPartnerChatStarted = true;

		if (typeof chatPrintFirstMessagesFromBot === 'function') {
			chatPrintFirstMessagesFromBot();
		}
	}

	function africanPartnerBroadcastVideoFinished() {
		var message = {
			'op': 'closePopupVideoAndAfricanPartnerSuccess',
			'parameters': {
				'scoreBeforeDashboardAfricanPartner': scoreBeforeDashboardAfricanPartner,
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
		};
		sendMessageSocket(JSON.stringify(message));
	}

	// playPopupVideoPhoneInline уже закрыл попап — здесь только синк + чат
	function africanPartnerOnJaneVideoEnded() {
		africanPartnerBroadcastVideoFinished();
		africanPartnerStartChat();
	}

	// Ручное закрытие во время inline-видео
	function africanPartnerCloseJaneVideoEarly() {
		if (typeof stopPopupVideoPhoneInline === 'function') {
			stopPopupVideoPhoneInline('africanPartnerJane');
		}

		$('#popup_video_phone').stop(true, true).fadeOut(200, function() {
			$('#popup_video_phone .popup_video_phone_wifi_icons').html('');
			$('#popup_video_phone .popup_video_phone_name').html('');
			$('#popup_video_phone').attr('class', '');
		});

		africanPartnerBroadcastVideoFinished();
		africanPartnerStartChat();
	}

	function africanPartnerPlayJaneInlineVideo() {
		// call_id=7 — african partner; путь из calls_description (uk: video_elison_4 и т.п.)
		getCallVideoSrc(7, function(videoSrc) {
			if (!videoSrc) {
				videoSrc = getAfricanPartnerJaneVideoSrc();
			} else {
				africanPartnerJaneVideoSrc = videoSrc;
			}

			playPopupVideoPhoneInline(videoSrc, {
				eventNamespace: 'africanPartnerJane',
				onEnded: africanPartnerOnJaneVideoEnded
			});
		});
	}

	function africanPartnerBroadcastStageSwitch() {
		var message = {
			'op': 'dashboardAfricanPartnerSwitchStage',
			'parameters': {
				'scoreBeforeDashboardAfricanPartner': scoreBeforeDashboardAfricanPartner,
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
		};
		sendMessageSocket(JSON.stringify(message));
	}

	function africanPartnerPersistStage() {
		if (africanPartnerStagePersisted) {
			return;
		}
		africanPartnerStagePersisted = true;

		var formData = new FormData();
		formData.append('op', 'africanPartnerUpdateHint');
		formData.append('lang_abbr', $('html').attr('lang'));

		$.ajax({
			url: '/ajax/ajax_dashboard.php',
			type: 'POST',
			dataType: 'json',
			cache: false,
			contentType: false,
			processData: false,
			data: formData,
			success: function(json) {
				if (json.error_verify) {
					window.location.href = json.error_verify;
					return;
				}

				if (!json.success) {
					africanPartnerStagePersisted = false;
					return;
				}

				$.when(getTeamInfo()).done(function(teamResponse){
					var teamInfo = teamResponse && teamResponse.success ? teamResponse.success : null;
					if (teamInfo) {
						incrementScore(parseInt(teamInfo.score, 10) + 200, 'main', teamInfo.score);
					}
				});

				incrementProgressMission(10);
				africanPartnerApplySideEffects(false);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				africanPartnerStagePersisted = false;
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}

	function africanPartnerStartStageWithVideo() {
		africanPartnerClearSuccessPopup();
		africanPartnerShouldStartChat = true;
		africanPartnerPlayJaneInlineVideo();

		if (!africanPartnerStageSwitched) {
			africanPartnerStageSwitched = true;
			africanPartnerShowMettingPlaceDashboard();
		}

		$.when(getTeamInfo()).done(function(teamResponse){
			var teamInfo = teamResponse && teamResponse.success ? teamResponse.success : null;
			scoreBeforeDashboardAfricanPartner = teamInfo ? (parseInt(teamInfo.score, 10) || 0) : 0;

			africanPartnerBroadcastStageSwitch();
			africanPartnerPersistStage();
		}).fail(function(){
			africanPartnerPersistStage();
		});
	}

	function initAfricanPartnerCountryAutocomplete() {
		var $root = $('#dashboard-african-partner-country-select');
		if (!$root.length) {
			return;
		}

		$root.removeData('autocomplete-initialized');

		if (window.initAutocompleteSelectComponent) {
			window.initAutocompleteSelectComponent($root);
		}

		$('.dashboard_african_partner_country').off('change.africanPartnerCountry').on('change.africanPartnerCountry', function() {
			var formData = new FormData();
			formData.append('op', 'saveTeamTextField');
			formData.append('field', 'african_partner_country_id');
			formData.append('val', $(this).val());

			$.ajax({
				url: '/ajax/ajax.php',
				type: 'POST',
				dataType: 'json',
				cache: false,
				contentType: false,
				processData: false,
				data: formData,
				success: function(json) {
					if (json.country_lang) {
						var message = {
							'op': 'dashboardAfricanPartnerUpdateCountry',
							'parameters': {
								'country_lang': json.country_lang,
								'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
								'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
							}
						};
						sendMessageSocket(JSON.stringify(message));
					}
				}
			});
		});
	}

	function setAfricanPartnerCountryValue(countryName) {
		var $hidden = $('.dashboard_african_partner_country');
		if (!$hidden.length) {
			return;
		}

		var $root = $hidden.closest('.autocomplete_select_component');
		$hidden.val(countryName).trigger('change');
		$root.find('.autocomplete_select_input').val(countryName);
	}

	// african partner ввели верно, открываем попап исходящего звонка
	function africanPartnerOpenOutgoingCall() {
		// запускаем отображение времени
		updateIncomingTime();
		incomingCallTimer = setInterval(function(){
			updateIncomingTime();
		}, 1000);

		$('#popup_video_phone .popup_video_phone_wifi_icons').html('<img src="/images/wifi_icons.png" alt="">');
		//$('#popup_video_phone .popup_video_phone_name').html('Jane Blond');
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

	// african partner ввели верно — сразу UI этапа, затем persist в БД
	function africanPartner() {
		africanPartnerClearSuccessPopup();

		if (!africanPartnerStageSwitched) {
			africanPartnerStageSwitched = true;
			africanPartnerShowMettingPlaceDashboard();
		}

		africanPartnerPersistStage();
	}
	// если запуск из сокета дубляж (не увеличиваем значения в бд)
	function africanPartnerFromSocket() {
		africanPartnerClearSuccessPopup();

		if (africanPartnerStageSwitched) {
			return;
		}
		africanPartnerStageSwitched = true;
		africanPartnerShowMettingPlaceDashboard();

		incrementScoreWithoutSaveDb(scoreBeforeDashboardAfricanPartner + 200, 'main', scoreBeforeDashboardAfricanPartner);
		incrementProgressMissionWithoutSaveDb(10);
		africanPartnerApplySideEffects(true);
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
		africanPartnerClearSuccessPopup();

		// socket — видео у остальных участников команды
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

		// сразу metting_place + спиннер, параллельно стартует видео
		africanPartnerStartStageWithVideo();

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

	// african partner - закрыть попап входящего звонка / прервать inline-видео
	$('body').on('click', '.popup_video_phone_outgoing_african_partner .popup_video_phone_bg, .popup_video_phone_outgoing_african_partner .popup_video_phone_btn_decline_wrapper', function(e){
		// во время inline-видео — закрыть звонок и стартовать чат
		if ($('#popup_video_phone .popup_video_phone_inline_video').is(':visible')) {
			africanPartnerCloseJaneVideoEarly();
			return;
		}

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

	// african partner - закрыть попап с видео (legacy fullscreen)
	$('body').on('click', '.african_partner_answer_incoming_video .popup_video_phone_video_bg, .african_partner_answer_incoming_video .popup_video_close', function(e){
		stopVideo();
		closePopupVideo();

		var message = {
			'op': 'stopVideoAndClosePopupVideoAndAfricanPartnerSuccess',
			'parameters': {
				'scoreBeforeDashboardAfricanPartner': scoreBeforeDashboardAfricanPartner,
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
		};
		sendMessageSocket(JSON.stringify(message));

		// fallback, если Answer ещё не успел переключить этап
		africanPartner();
		africanPartnerStartChat();
	});
});