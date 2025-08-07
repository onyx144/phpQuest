/* === ACCEPT A NEW MISSION === */

/* ОБЩИЕ ФУНКЦИИ */
	// ввели и отправили название миссии
	function newMissionAcceptClick() {
		/*setTimeout(function(){
			if (!startAudio || !isPlaying(startAudio)) {
				startAudio = new Audio;
				startAudio.src = '/music/robotic_countdown.mp3';
				startAudio.play();
			}
		}, 100);

		setTimeout(function(){
			$('.popup_start_mission_number').css('opacity', 0);
		}, 2750);

		$('#popup_start_mission').css('display','block');*/
		var mission_number = $.trim($('.dashboard_tabs[data-dashboard="dashboard"] .dashboard_tab_content_item_new_mission_input').val());

		var formData = new FormData();
    	formData.append('op', 'dashboardNewMissionNumber');
    	formData.append('lang_abbr', $('html').attr('lang'));
    	formData.append('mission_number', mission_number);

    	// ajax
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
					$('.popup_start_mission_number').css('opacity', 1);
					$('#popup_start_mission').css('display','block');

					$('.dashboard_tab_content_item_new_mission_error').removeClass('dashboard_tab_content_item_new_mission_error_active').html('');

					// socket
					var message = {
						'op': 'missionNumberOpenIncomingCall',
						'parameters': {
							'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
							'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
						}
			        };
			        sendMessageSocket(JSON.stringify(message));

			        // setTimeout(function(){
						if (!startAudio || !isPlaying(startAudio)) {
							startAudio = new Audio;
							startAudio.src = '/music/robotic_countdown.mp3';
							// startAudio.play();

							// Autoplay
							var promise = startAudio.play();

							if (promise !== undefined) {
								promise.then(_ => {
									// console.log('autoplay');
								}).catch(error => {
									// console.log('autoplay ERR');
								});
							}
						}
					// }, 100);

					setTimeout(function(){
						$('.popup_start_mission_number').css('opacity', 0);
						$('#popup_start_mission').css('display','none');
					}, 2750);

					setTimeout(function(){
						// открываем попап входящего звонка
						newMissionOpenIncomingCall();
					}, 3000);
				} else if (json.error_lang) {
					$('.dashboard_tab_content_item_new_mission_error').html(json.error_lang[langAbbr]).addClass('dashboard_tab_content_item_new_mission_error_active');

					// socket
					var message = {
						'op': 'missionNumberError',
						'parameters': {
							'error_lang': json.error_lang,
							'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
							'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
						}
			        };
			        sendMessageSocket(JSON.stringify(message));
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {	
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}

	// название миссии ввели верно, открываем попап входящего звонка
	function newMissionOpenIncomingCall() {
		// запускаем отображение времени
		updateIncomingTime();
		incomingCallTimer = setInterval(function(){
			updateIncomingTime();
		}, 1000);

		$('#popup_video_phone .popup_video_phone_wifi_icons').html('<img src="/images/wifi_icons.png" alt="">');
		$('#popup_video_phone .popup_video_phone_name').html('Jane Blond');
		$('#popup_video_phone').attr('class','').addClass('popup_video_phone_incoming_new_mission');

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

	// игрок принял миссию - просмотрел incoming video либо закрыл попап входящего звонка
	function acceptMission() {
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
					// пишем игроку первые 100 баллов
					incrementScore(100, 'main', 0);

					// запускаем таймер
					updateTimerUploadPage();

					// Обновить к-во непрочитанных файлов
					updateDontOpenFilesQt();

					// Обновить к-во неоткрытых баз данных
					updateDontOpenDatabasesQt();
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				// alert('Error');
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});

    	// отображаем блок Mission name GEM
		$('.dashboard_gem_wrapper').addClass('dashboard_gem_wrapper_active');
		setTeamTabsTextInfo('view_gem', 1);

		// обновляем содержимое dashboard
		uploadTypeTabsDashboardStep('company_name', false);

		// запоминаем открытый calls
		setTeamTabsTextInfo('last_calls', 'call_list');

		// отображаем блок Call Jane
		$('.call_jane').addClass('call_jane_active');
		setTeamTabsTextInfo('view_call_jane_btn', 1);
	}

$(function() {
	// new mission - первый экран - ввели названия миссии, нажали отправить
	$('.dashboard_tabs[data-dashboard="dashboard"]').on('click', '.dashboard_tab_content_item_new_mission_accept', function(e){
		newMissionAcceptClick();
	});

	// new mission - первый экран - ввод названия миссии. Отправка также срабатывает при нажатии на Enter
	$('.dashboard_tabs[data-dashboard="dashboard"]').on('keyup', '.dashboard_tab_content_item_new_mission_input', function(e){
		if (e.which == 13) {
			newMissionAcceptClick();
		} else {
			// socket
			var message = {
				'op': 'acceptMissionKeyup',
				'parameters': {
					'mission_name': $(this).val(),
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));
		}
	});

	// new mission - закрыть попап входящего звонка
	$('body').on('click', '.popup_video_phone_incoming_new_mission .popup_video_phone_bg, .popup_video_phone_incoming_new_mission .popup_video_phone_btn_decline_wrapper', function(e){
		// socket
		var message = {
			'op': 'missionNumberCloseIncomingCall',
			'parameters': {
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
        };
        sendMessageSocket(JSON.stringify(message));

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

		// очищаем данные
		setTimeout(function(){
			$('#popup_video_phone .popup_video_phone_wifi_icons').html('');
			$('#popup_video_phone .popup_video_phone_name').html('');
			$('#popup_video_phone').attr('class','');
		}, 210);
	});

	// new mission - принять входящий звонок
	$('body').on('click', '.popup_video_phone_incoming_new_mission .popup_video_phone_btn_answer_wrapper', function(e){
		// socket
		var message = {
			'op': 'acceptMissionIncomingCallAccept',
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
		openFileVideoPopup(0, 'video/' + $('html').attr('lang') + '/video_jane_1.mp4', '', 'new_mission_answer_incoming_video', 'call');
		playVideo('call');
		// openFileVideoPopupCall(0, 'video/' + $('html').attr('lang') + '/video_jane_1.mp4', '', 'new_mission_answer_incoming_video', 'call_jane');
		// playVideoCall();

		/*// когда видео доиграло до конца, то закрываем и производим нужные действия
		$('.new_mission_answer_incoming_video video').on('ended', function() {
			// closePopupVideo();
			closePopupVideoCall();

			var timerSeconds = parseInt($('.timer').attr('data-timer'), 10);
			if (timerSeconds == 0) {
				// socket
				var message = {
					'op': 'closePopupVideoAndAcceptMission',
					'parameters': {
						'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
						'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
					}
		        };
		        sendMessageSocket(JSON.stringify(message));

		        acceptMission();
		    }
		});*/

		// сохранить время просмотра видео в списке звонков команды
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

		// запоминаем последнюю databases
		setTeamTabsTextInfo('last_databases', 'databases_start_four');
	});

	// new mission - закрыть попап с видео - игрок принял миссию
	$('body').on('click', '.new_mission_answer_incoming_video .popup_video_phone_video_bg, .new_mission_answer_incoming_video .popup_video_close', function(e){
		stopVideo();
		closePopupVideo();
		// stopVideoCall();
		// closePopupVideoCall();

		// принятие миссии запускаем единожды
		var timerSeconds = parseInt($('.timer').attr('data-timer'), 10);
		if (timerSeconds == 0) {
			playVideoSeeking = true;

			// socket
			var message = {
				'op': 'stopVideoAndClosePopupVideoAndAcceptMission',
				'parameters': {
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));

	        acceptMission();
	    } else {
	    	// socket
			var message = {
				'op': 'stopVideoAndClosePopupVideo',
				'parameters': {
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));
	    }
	});
});