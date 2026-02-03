/* === ГЛАВНОЕ ОКНО ИГРЫ - ЦЕНТРАЛЬНЫЙ БЛОК ИНФОРМАЦИИ - CALLS === */

/* КЭШИРОВАНИЕ ДАННЫХ */
	var callsCache = {
		step: null,
		titles: null,
		content: null
	};

/* ОБЩИЕ ФУНКЦИИ */
	// Открыть тип табов: calls
	function openTypeTabsCalls(isSocketSend) {
		$('.dashboard_tabs[data-dashboard="calls"]').addClass('dashboard_tabs_active');
		$('.dashboard_item[data-dashboard="calls"]').addClass('dashboard_item_active');

		$.when(getTeamInfo()).done(function(teamResponse){
			var teamInfo = teamResponse.success;

			if (teamInfo) {
				uploadTypeTabsCallsStep(teamInfo.last_calls, isSocketSend, teamInfo.view_call_mobile_btn, teamInfo.open_call_mobile_btn);
			}
		});
	}

	// загрузить конкретный экран (с переключателем табов) для calls
	function uploadTypeTabsCallsStep(step, isSocketSend, teamInfoViewCallMobileBtn, teamInfoOpenCallMobileBtn) {
		var $dashboardTabs = $('.dashboard_tabs[data-dashboard="calls"]');
		
		// Проверяем, есть ли уже загруженные данные для этой стадии
		if (callsCache.step === step && callsCache.titles && callsCache.content) {
			// Данные уже загружены - показываем их сразу без лоадинга
			$('.dashboard_tabs[data-dashboard="calls"] .dashboard_tab_titles').html(callsCache.titles);
			$('.dashboard_tabs[data-dashboard="calls"] .dashboard_tab_content_item_wrapper').html(callsCache.content);
			$dashboardTabs.find('.dashboard_tabs_loading').hide();
			$dashboardTabs.find('.dashboard_tabs_content_wrapper').show();
			
			// Восстанавливаем состояние кнопок
			if (parseInt(teamInfoViewCallMobileBtn, 10) == 1) {
				if ($('.call_mobile').length > 0) {
					$('.call_mobile').css('display', 'block');
					if (parseInt(teamInfoOpenCallMobileBtn, 10) == 1) {
						$('.call_mobile').removeClass('btn_wrapper_blue_light').addClass('btn_wrapper_blue');
					}
				}
			}
			
			if (step == 'call_list') {
				$('.dashboard_tab_content_item_calls_list_tbody').mCustomScrollbar({
					scrollInertia: 700,
					scrollbarPosition: "inside"
				});
			}
			return;
		}
		
		// Показываем лоадинг и скрываем контент
		$dashboardTabs.find('.dashboard_tabs_loading').show();
		$dashboardTabs.find('.dashboard_tabs_content_wrapper').hide();
		
		var formData = new FormData();
    	formData.append('op', 'uploadTypeTabsCallsStep');
    	formData.append('lang_abbr', $('html').attr('lang'));
    	formData.append('step', step);

    	$.ajax({
			url: '/ajax/ajax_calls.php',
	        type: "POST",
	        dataType: "json",
	        cache: false,
	        contentType: false,
	        processData: false,
	        data: formData,
			success: function(json) {
				if (step == 'call_list') {
					$('.dashboard_tab_content_item_calls_list_tbody').mCustomScrollbar('destroy');
				}

				if (json.titles) {
					$('.dashboard_tabs[data-dashboard="calls"] .dashboard_tab_titles').html(json.titles);
					callsCache.titles = json.titles;
				}
				if (json.content) {
					$('.dashboard_tabs[data-dashboard="calls"] .dashboard_tab_content_item_wrapper').html(json.content);
					callsCache.content = json.content;
				}
				callsCache.step = step;

				// Скрываем лоадинг и показываем контент
				$dashboardTabs.find('.dashboard_tabs_loading').hide();
				$dashboardTabs.find('.dashboard_tabs_content_wrapper').show();

				if (parseInt(teamInfoViewCallMobileBtn, 10) == 1) {
					if ($('.call_mobile').length > 0) {
						$('.call_mobile').css('display', 'block');

						if (parseInt(teamInfoOpenCallMobileBtn, 10) == 1) {
							$('.call_mobile').removeClass('btn_wrapper_blue_light').addClass('btn_wrapper_blue');
						}
					}
				}

				if (step == 'call_list') {
					$('.dashboard_tab_content_item_calls_list_tbody').mCustomScrollbar({
						scrollInertia: 700,
						scrollbarPosition: "inside"
					});
				}

				// socket
				if (isSocketSend) {
					var message = {
						'op': 'loadSocketMain',
						'parameters': {
							user_id: $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
							team_id: $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
						}
			        };
			        sendMessageSocket(JSON.stringify(message));
			    }
			},
			error: function(xhr, ajaxOptions, thrownError) {
				// Скрываем лоадинг даже при ошибке
				$dashboardTabs.find('.dashboard_tabs_loading').hide();
				$dashboardTabs.find('.dashboard_tabs_content_wrapper').show();
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});

		// запоминаем открытый тип табов
		setTeamLastTypeTabs('calls');

		// запоминаем открытый calls
		setTeamTabsTextInfo('last_calls', step);
	}

$(function() {
	// повторно просмотреть видео звонка
	$('.dashboard_tabs[data-dashboard="calls"]').on('click', '.dashboard_tab_content_item_calls_list_td_again_btn', function(e){
		// socket
		var message = {
			'op': 'openFileVideoPopupAndPlayVideoCall',
			'parameters': {
				'fileId': 0,
				// 'path': $(this).attr('data-path'),
				'video_with_path': $(this).attr('data-video-with-path'),
				'name': '',
				'classVideo': 'popup_video_type_calls_again',
				// 'type': 'call',
				'type': 'call_again',
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
        };
        sendMessageSocket(JSON.stringify(message));

		// function
		playVideoByNotControls = true; // указываем, что запускалось через кнопку Play, а не через Controls

		// openFileVideoPopup(0, $(this).attr('data-path'), '', 'popup_video_type_calls_again', 'call');
		// playVideo('call');

		// openFileVideoPopupCall(0, $(this).attr('data-path'), '', 'popup_video_type_calls_again', 'call_again');
		// playVideoCall();

		if ($(this).attr('data-video-with-path') == 'video_jane_1.mp4' || $(this).attr('data-video-with-path') == 'video_jane_2.mp4' || $(this).attr('data-video-with-path') == 'video_jane_3.mp4' || $(this).attr('data-video-with-path') == 'video_jane_4.mp4') {
			openFileVideoPopup(0, $(this).attr('data-path'), '', 'popup_video_type_calls_again', 'call_again');
			playVideo('call');
		} else {
			openFileVideoPopupCall(0, $(this).attr('data-path'), '', 'popup_video_type_calls_again', 'call_again');
			playVideoCall();
		}
	});

	// call jane
	$('.dashboard_tabs[data-dashboard="calls"]').on('click', '.call_jane', function(e){
		var formData = new FormData();
    	formData.append('op', 'callJane');
    	formData.append('lang_abbr', $('html').attr('lang'));

    	$.ajax({
			url: '/ajax/ajax_calls.php',
	        type: "POST",
	        dataType: "json",
	        cache: false,
	        contentType: false,
	        processData: false,
	        data: formData,
			success: function(json) {
				if (json.error_verify) {
					window.location.href = json.error_verify;
				} else if (json.error) {
					alert(json.error);
				} else {
					// if (json.error_lang) { // если больше недоступно
					if (json.available) { // если больше недоступно
						// json.error_lang[langAbbr].available

						// socket
						var message = {
							'op': 'callJaneNotAvailable',
							'parameters': {
								// 'availableText': json.error_lang,
								'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
								'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
							}
				        };
				        sendMessageSocket(JSON.stringify(message));

						// запускаем отображение времени
						updateOutgoingTime();
						outgoingCallTimer = setInterval(function(){
							updateOutgoingTime();
						}, 1000);

						stopMusic();

						outgoingAudio = new Audio;
						outgoingAudio.src = '/music/ended_call.mp3';

						// Autoplay
						var promise = outgoingAudio.play();

						if (promise !== undefined) {
							promise.then(_ => {
								// console.log('autoplay');
							}).catch(error => {
								// console.log('autoplay ERR');
							});
						}

						callsNotAvailableJaneOpenPopup = true;

						// отображаем надпись про недоступность
						$('.popup_video_phone_outgoing_not_available_text').css('display','block');

						// отображаем попап звонка
						$('#popup_video_phone_outgoing').fadeIn(200);

						setTimeout(function(){
							if (callsNotAvailableJaneOpenPopup) {
								callsNotAvailableJaneOpenPopup = false;

								$('.popup_video_phone_outgoing_bg').trigger('click');
							}
						}, 38000);
					} else if (json.path) { // первые два звонка
						// socket
						var message = {
							'op': 'callJane',
							'parameters': {
								'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
								'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
							}
				        };
				        sendMessageSocket(JSON.stringify(message));

						// запускаем отображение времени
						updateOutgoingTime();
						outgoingCallTimer = setInterval(function(){
							updateOutgoingTime();
						}, 1000);

						// звук вызова
						/*music_before = music;
						if (music) {
							stopMusic();
						}*/
						stopMusic();

						outgoingAudio = new Audio;
						outgoingAudio.src = '/music/outgoing.mp3';
						// outgoingAudio.play();

						// Autoplay
						var promise = outgoingAudio.play();

						if (promise !== undefined) {
							promise.then(_ => {
								// console.log('autoplay');
							}).catch(error => {
								// console.log('autoplay ERR');
							});
						}

						// скрываем надпись про недоступность
						$('.popup_video_phone_outgoing_not_available_text').css('display','none');

						$('#popup_video_phone_outgoing').fadeIn(200);

						// и сразу же через пару секунд запускаем видео. Типа ответила на звонок
						setTimeout(function(){
							if (isPlaying(outgoingAudio)) {
								// socket
								var message = {
									'op': 'callJaneOutgoingAccept',
									'parameters': {
										'fileId': 0,
										'video_with_path': json.video_with_path,
										'name': '',
										'classVideo': 'popup_video_type_calls_to_jane',
										// 'type': 'call',
										'type': 'call_jane',
										'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
										'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
									}
						        };
						        sendMessageSocket(JSON.stringify(message));

								// останавливаем звук звонка
								if (outgoingAudio && isPlaying(outgoingAudio)) {
									outgoingAudio.pause();
								}

								// фикс для звучания музыки позже
								/*if (music_before) {
									playMusic();
								}
								music_before = false;*/
								if ($('.music_on').length && $('.music_on').hasClass('music_active')) {
									playMusic();
								}

								// останавливаем обновление времени
								clearInterval(outgoingCallTimer);
								outgoingCallTimer = false;

								// скрываем блок с телефоном
								$('#popup_video_phone_outgoing').fadeOut(200);

								// открыть видео и сразу запустить его
								playVideoByNotControls = true; // указываем, что запускалось через кнопку Play, а не через Controls
								// openFileVideoPopup(0, json.path, '', 'popup_video_type_calls_to_jane', 'call');
								// playVideo('call');
								openFileVideoPopupCall(0, json.path, '', 'popup_video_type_calls_to_jane', 'call_jane');
								playVideoCall();

								// обновляем содержимое вкладки call с учетом совершенного звонка
								/*uploadTypeTabsCallsStep($('#section_game').attr('data-last-open-calls'));*/
								$.when(getTeamInfo()).done(function(teamResponse){
									var teamInfo = teamResponse.success;

									if (teamInfo) {
										uploadTypeTabsCallsStep(teamInfo.last_calls, false, teamInfo.view_call_mobile_btn, teamInfo.open_call_mobile_btn);
									}
								});
							}
						}, selfRandom(5000, 9000));
					}
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {	
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

	// close call Jane. Close outgoing call
	$('body').on('click', '.popup_video_phone_outgoing_bg, .popup_video_phone_outgoing_btn_decline_wrapper', function(e){
		// socket
		var message = {
			'op': 'callJaneOutgoingDecline',
			'parameters': {
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
        };
        sendMessageSocket(JSON.stringify(message));

		// останавливаем звук звонка и запускаем фоновое
		if (outgoingAudio && isPlaying(outgoingAudio)) {
			outgoingAudio.pause();
		}

		// if (music_before) {
		if ($('.music_on').length && $('.music_on').hasClass('music_active')) {
			playMusic();
		}
		// music_before = false;

		// останавливаем обновление времени
		clearInterval(outgoingCallTimer);
		outgoingCallTimer = false;

		// скрываем блок с телефоном
		$('#popup_video_phone_outgoing').fadeOut(200);

		callsNotAvailableJaneOpenPopup = false;
	});

	// Open Call mobile
	$('body').on('click', '.dashboard_tabs[data-dashboard="calls"] .call_mobile', function(e){
		$('#popup_call_mobile').fadeIn(200);

		// Saved to db that Call mobile is open
		setTeamTabsTextInfo('open_call_mobile_btn', 1);

		// Remove one-number from tab title
		$('.dashboard_item[data-dashboard="calls"] .dashboard_item_text_qt').css('display', 'none').html('0');
		$('.call_mobile .dashboard_item_text_qt').css('display', 'none').html('0');

		$(this).removeClass('btn_wrapper_blue_light').addClass('btn_wrapper_blue');

		// socket
		var message = {
			'op': 'callMobileOpen',
			'parameters': {
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
        };
        sendMessageSocket(JSON.stringify(message));
	});
});