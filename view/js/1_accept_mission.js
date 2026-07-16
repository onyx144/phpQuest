/* === ACCEPT A NEW MISSION === */

/* ОБЩИЕ ФУНКЦИИ */
	var acceptMissionVisualReady = false;
	var acceptMissionVisualApplied = false;
	var acceptMissionDashboardSwitched = false;
	var acceptMissionCallAccepted = false;

	// Секунды таймера миссии с data-timer у .timer (0 = миссия ещё не принята / таймер не тикает).
	// Пустой атриут или NaN трактуем как 0 — иначе acceptMissionServerUpdate никогда не вызывался.
	function getMissionTimerElapsedSeconds() {
		var raw = $('.timer').first().attr('data-timer');
		if (raw === undefined || raw === null || String(raw).trim() === '') {
			return 0;
		}
		var n = parseInt(raw, 10);
		return isNaN(n) ? 0 : n;
	}

	function isFirstMissionAcceptByTimer() {
		return getMissionTimerElapsedSeconds() === 0;
	}

	function shouldSwitchToCompanyNameOnAccept() {
		if (acceptMissionDashboardSwitched) {
			return false;
		}

		if (isFirstMissionAcceptByTimer()) {
			return true;
		}

		if (typeof dashboardCache !== 'undefined' && dashboardCache.step === 'accept_new_mission') {
			return true;
		}

		if ($('.dashboard_tab_content_item_new_mission.dashboard_tab_content_item_active').length) {
			return true;
		}

		return false;
	}

	function getAcceptMissionSocketParameters() {
		return {
			'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
			'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
		};
	}

	function acceptMissionServerUpdate() {
		var formData = new FormData();
    	formData.append('op', 'acceptMissionUpdateHint');
    	formData.append('lang_abbr', $('html').attr('lang'));

    	$.ajax({
			url: '/ajax/ajax.php',
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
					acceptMissionVisualReady = true;
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				acceptMissionVisualReady = true;
			}
		});
	}

	// Переключить дашборд на этап company_name (last_dashboard в БД через uploadTypeTabsDashboardStep).
	function acceptMissionShowCompanyNameDashboard() {
		acceptMissionDashboardSwitched = true;

		if (typeof dashboardCache !== 'undefined' && dashboardCache.step !== 'company_name') {
			dashboardCache.step = null;
			dashboardCache.titles = null;
			dashboardCache.content = null;
		}

		showDashboardTabsLoading();
		uploadTypeTabsDashboardStep('company_name', false);
	}

	function acceptMissionBroadcastStageSwitch() {
		var message = {
			'op': 'acceptMissionSwitchStage',
			'parameters': getAcceptMissionSocketParameters()
		};
		sendMessageSocket(JSON.stringify(message));
	}

	function acceptMissionOnCallAccepted() {
		if (!shouldSwitchToCompanyNameOnAccept()) {
			return;
		}

		acceptMissionShowCompanyNameDashboard();
	}

	function acceptMissionCleanupPhonePopup() {
		stopPopupVideoPhoneInline('inlineIncoming');
		$('#popup_video_phone .popup_video_phone_wifi_icons').html('');
		$('#popup_video_phone').attr('class', '');
	}

	function acceptMissionApplyVisualSync() {
		if (acceptMissionVisualApplied) {
			return;
		}

		acceptMissionVisualApplied = true;

		incrementScoreWithoutSaveDb(100, 'main', 0);
		updateTimerUploadPage();
		updateDontOpenFilesQt();
		updateDontOpenDatabasesQt();
		$('.dashboard_gem_wrapper').addClass('dashboard_gem_wrapper_active');
		setTeamTabsTextInfo('view_gem', 1);

		if (!acceptMissionDashboardSwitched) {
			acceptMissionShowCompanyNameDashboard();
		}

		setTeamTabsTextInfo('last_calls', 'call_list');
		$('.call_jane').addClass('call_jane_active');
		setTeamTabsTextInfo('view_call_jane_btn', 1);
	}

	function acceptMissionFinishAfterVideoClose() {
		if (acceptMissionVisualApplied) {
			return;
		}

		var applyVisual = function() {
			if (acceptMissionVisualApplied) {
				return;
			}

			acceptMissionVisualApplied = true;
			acceptMissionVisualUpdate();
			acceptMissionVisualReady = false;

			var message = {
				'op': 'stopVideoAndClosePopupVideoAndAcceptMission',
				'parameters': getAcceptMissionSocketParameters()
			};
			sendMessageSocket(JSON.stringify(message));
		};

		acceptMissionServerUpdate();

		if (acceptMissionVisualReady) {
			applyVisual();
			return;
		}

		var attempts = 0;
		var waitServer = setInterval(function() {
			attempts++;
			if (acceptMissionVisualReady || attempts >= 50) {
				clearInterval(waitServer);
				applyVisual();
			}
		}, 100);
	}

	function acceptMissionCloseVideoPopup() {
		stopPopupVideoPhoneInline('inlineIncoming');
		stopVideo();
		stopVideoCall();

		var $phonePopup = $('#popup_video_phone');
		var finishAfterClose = function() {
			acceptMissionCleanupPhonePopup();

			if (acceptMissionCallAccepted && !acceptMissionVisualApplied) {
				acceptMissionFinishAfterVideoClose();
			}
		};

		if ($phonePopup.is(':visible')) {
			$phonePopup.stop(true, true).fadeOut(200, finishAfterClose);
		} else {
			finishAfterClose();
		}

		closePopupVideo();
		closePopupVideoCall();
	}

	function acceptMissionVisualUpdate() {
		// пишем игроку первые 100 баллов
		incrementScore(100, 'main', 0);

		// запускаем таймер
		updateTimerUploadPage();

		// Обновить к-во непрочитанных файлов
		updateDontOpenFilesQt();

		// Обновить к-во неоткрытых баз данных
		updateDontOpenDatabasesQt();

    	// отображаем блок Mission name GEM
		$('.dashboard_gem_wrapper').addClass('dashboard_gem_wrapper_active');
		setTeamTabsTextInfo('view_gem', 1);

		// обновляем содержимое dashboard, если ещё не переключили при принятии звонка
		if (!acceptMissionDashboardSwitched) {
			acceptMissionShowCompanyNameDashboard();
		}

		// запоминаем открытый calls
		setTeamTabsTextInfo('last_calls', 'call_list');

		// отображаем блок Call Jane
		$('.call_jane').addClass('call_jane_active');
		setTeamTabsTextInfo('view_call_jane_btn', 1);
	}

	// ввели и отправили название миссии
	function newMissionAcceptClick() {
		var mission_number = $.trim($('.dashboard_tabs[data-dashboard="dashboard"] .dashboard_tab_content_item_new_mission_input').val());

		var formData = new FormData();
    	formData.append('op', 'dashboardNewMissionNumber');
    	formData.append('lang_abbr', $('html').attr('lang'));
    	formData.append('mission_number', mission_number);

    	$.ajax({
			url: '/ajax/ajax.php',
	        type: "POST",
	        dataType: "json",
	        cache: false,
	        contentType: false,
	        processData: false,
	        data: formData,
			success: function(json) {
				if (json.success) {
					$('.dashboard_tab_content_item_new_mission_error').removeClass('dashboard_tab_content_item_new_mission_error_active').html('');

					var message = {
						'op': 'missionNumberOpenIncomingCall',
						'parameters': $.extend({
							'mission_number': mission_number
						}, getAcceptMissionSocketParameters())
			        };
			        sendMessageSocket(JSON.stringify(message));
			        
			        $('.popup_start_mission_number').css('opacity', 1);
					$('#popup_start_mission').css('display','block');

					if (!startAudio || !isPlaying(startAudio)) {
						startAudio = new Audio;
						startAudio.src = '/music/robotic_countdown.mp3';
						var promise = startAudio.play();
						if (promise !== undefined) {
							promise.then(_ => {}).catch(error => {});
						}
					}

					setTimeout(function(){
						$('.popup_start_mission_number').css('opacity', 0);
						$('#popup_start_mission').css('display','none');
					}, 2750);

					setTimeout(function(){
						newMissionOpenIncomingCall();
					}, 3000);
				} else if (json.error_lang) {
					$('.dashboard_tab_content_item_new_mission_error').html(json.error_lang[langAbbr]).addClass('dashboard_tab_content_item_new_mission_error_active');

					var message = {
						'op': 'missionNumberError',
						'parameters': $.extend({
							'error_lang': json.error_lang
						}, getAcceptMissionSocketParameters())
			        };
			        sendMessageSocket(JSON.stringify(message));
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {	
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}

	function newMissionOpenIncomingCall() {
		updateIncomingTime();
		incomingCallTimer = setInterval(function(){
			updateIncomingTime();
		}, 1000);

		$('#popup_video_phone .popup_video_phone_wifi_icons').html('<img src="/images/wifi_icons.png" alt="">');
		$('#popup_video_phone').attr('class','').addClass('popup_video_phone_incoming_new_mission');

		stopMusic();

		if (!incomingAudio || !isPlaying(incomingAudio)) {
			incomingAudio = new Audio;
			incomingAudio.src = '/music/incoming.mp3';

			var promise = incomingAudio.play();

			if (promise !== undefined) {
				promise.then(_ => {
					incomingMusicTimer = setInterval(function(){
						incomingAudio = new Audio;
						incomingAudio.src = '/music/incoming.mp3';
						incomingAudio.play();
					}, incomingMusicDuration);
				}).catch(error => {});
			}
		}

		$('#popup_video_phone').fadeIn(200);
	}

	function getCallVideoSrc(callId, callback) {
		var formData = new FormData();
		formData.append('op', 'getCallVideo');
		formData.append('lang_abbr', $('html').attr('lang'));
		formData.append('call_id', callId);

		$.ajax({
			url: '/ajax/ajax_calls.php',
			type: 'POST',
			dataType: 'json',
			cache: false,
			contentType: false,
			processData: false,
			data: formData,
			success: function(json) {
				if (json.path) {
					var path = String(json.path);
					callback(path.charAt(0) === '/' ? path : '/' + path);
				} else {
					callback('');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				callback('');
			}
		});
	}

	function acceptMissionPlayIncomingVideo() {
		getCallVideoSrc(1, function(videoSrc) {
			if (!videoSrc) {
				return;
			}

			playPopupVideoPhoneInline(videoSrc, {
				eventNamespace: 'inlineIncoming',
				onEnded: function() {
					acceptMissionCloseVideoPopup();
				}
			});
		});
	}

	function acceptMissionHandleIncomingCallAccept(isInitiator) {
		acceptMissionCallAccepted = true;

		if ($('.music_on').length && $('.music_on').hasClass('music_active')) {
			playMusic();
		}

		clearInterval(incomingMusicTimer);
		incomingMusicTimer = false;

		if (incomingAudio && isPlaying(incomingAudio)) {
			incomingAudio.pause();
		}

		clearInterval(incomingCallTimer);
		incomingCallTimer = false;

		acceptMissionOnCallAccepted();

		if (isInitiator) {
			acceptMissionPlayIncomingVideo();
		} else {
			getCallVideoSrc(1, function(videoSrc) {
				if (!videoSrc) {
					return;
				}

				$('#popup_video_phone').fadeOut(200, function() {
					acceptMissionCleanupPhonePopup();
				});

				playVideoByNotControls = true;
				openFileVideoPopup(0, videoSrc.replace(/^\//, ''), '', 'new_mission_answer_incoming_video', 'call');
				playVideo('call');
			});
		}
	}

	// игрок принял миссию - просмотрел incoming video либо закрыл попап входящего звонка
	function acceptMission() {
		acceptMissionFinishAfterVideoClose();
	}

$(function() {
	$('.dashboard_tabs[data-dashboard="dashboard"]').on('click', '.dashboard_tab_content_item_new_mission_accept', function(e){
		newMissionAcceptClick();
	});

	$('.dashboard_tabs[data-dashboard="dashboard"]').on('keyup', '.dashboard_tab_content_item_new_mission_input', function(e){
		if (e.which == 13) {
			newMissionAcceptClick();
		} else {
			var message = {
				'op': 'acceptMissionKeyup',
				'parameters': $.extend({
					'mission_name': $(this).val()
				}, getAcceptMissionSocketParameters())
	        };
	        sendMessageSocket(JSON.stringify(message));
		}
	});

	$('body').on('click', '.popup_video_phone_incoming_new_mission .popup_video_phone_btn_decline_wrapper', function(e){
		var message = {
			'op': 'missionNumberCloseIncomingCall',
			'parameters': getAcceptMissionSocketParameters()
        };
        sendMessageSocket(JSON.stringify(message));

		if ($('.music_on').length && $('.music_on').hasClass('music_active')) {
			playMusic();
		}

		clearInterval(incomingMusicTimer);
		incomingMusicTimer = false;

		if (incomingAudio && isPlaying(incomingAudio)) {
			incomingAudio.pause();
		}

		clearInterval(incomingCallTimer);
		incomingCallTimer = false;

		$('#popup_video_phone').fadeOut(200);

		setTimeout(function(){
			acceptMissionCleanupPhonePopup();
		}, 210);
	});

	$('body').on('click', '.popup_video_phone_incoming_new_mission .popup_video_phone_bg', function(e){
		if ($('#popup_video_phone .popup_video_phone_inline_video').is(':visible')) {
			acceptMissionCloseVideoPopup();
			return;
		}

		$('.popup_video_phone_incoming_new_mission .popup_video_phone_btn_decline_wrapper').trigger('click');
	});

	$('body').on('click', '.popup_video_phone_incoming_new_mission .popup_video_phone_btn_answer_wrapper', function(e){
		var socketParams = getAcceptMissionSocketParameters();
		var message = {
			'op': 'acceptMissionIncomingCallAccept',
			'parameters': socketParams
        };
        sendMessageSocket(JSON.stringify(message));

		acceptMissionBroadcastStageSwitch();
		acceptMissionHandleIncomingCallAccept(true);

		var formData = new FormData();
    	formData.append('op', 'updateDatetimeCall');
    	formData.append('lang_abbr', $('html').attr('lang'));
    	formData.append('call_id', 1);

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

		setTeamTabsTextInfo('last_databases', 'databases_start_four');
	});

	$('body').on('click', '.new_mission_answer_incoming_video .popup_video_phone_video_bg, .new_mission_answer_incoming_video .popup_video_close', function(e){
		stopVideo();
		stopVideoCall();
		closePopupVideo();
		closePopupVideoCall();

		if (acceptMissionCallAccepted && !acceptMissionVisualApplied) {
	        acceptMissionFinishAfterVideoClose();
	    } else {
	    	var message = {
				'op': 'stopVideoAndClosePopupVideo',
				'parameters': getAcceptMissionSocketParameters()
	        };
	        sendMessageSocket(JSON.stringify(message));
	    }
	});
});
