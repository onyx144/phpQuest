/* МИНИИГРА */
	var minigameVideoMute = false;

/* ОБЩИЕ ФУНКЦИИ */
	// скрыть окно миниигры
	function hiddenMiniGameWindow() {
		$('.content_minigame').css('display', 'none');

		// убираем звук с видео на странице
		if ($('.content_minigame_video_mute').hasClass('content_minigame_video_mute_active')) {
            $('.content_minigame_video_mute').removeClass('content_minigame_video_mute_active');
            $('.content_minigame_video video').prop('muted', 1);
        }
	}

	// показать окно миниигры
	function openMiniGameWindow() {
		viewMainScore();
		viewMainTimer();
		viewHighscoreBtn();
		viewHintBtn();
		viewChatBtn();

		$('.content_minigame').css('display', 'block');

		// запоминаем открытое окно
		setTeamLastOpenWindow('minigame');

		// стартовые позиции
		uploadMinigamePositions();
	}

	// загрузить активное состояние миниигры
	function uploadMinigamePositions() {
		var formData = new FormData();
    	formData.append('op', 'uploadMinigamePositions');

    	$.ajax({
			url: '/ajax/ajax_minigame.php',
	        type: "POST",
	        dataType: "json",
	        cache: false,
	        contentType: false,
	        processData: false,
	        data: formData,
			success: function(json) {
				if (json.success) {
					updateObjects(json.success);

					// если игра окончена, то сразу отображаем звонок
					if (json.dashboard_minigame_active_step == 12) {
						minigameOpenOutgoingCall();
					}
				}
				/*else {
					alert(json.error_lang[$('html').attr('lang')].error);
				}*/
			},
			error: function(xhr, ajaxOptions, thrownError) {	
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}

	// загрузить первоначальное состояние миниигры
	function uploadMinigamePositionsStart() {
		// возобновляем звук на видео на странице, если был активен до видео с ошибкой
		if (minigameVideoMute) {
			$('.content_minigame_video_mute').addClass('content_minigame_video_mute_active');
            $('.content_minigame_video video').prop('muted', 0);

			minigameVideoMute = false;
		}

		var formData = new FormData();
    	formData.append('op', 'uploadMinigamePositionsStart');

    	$.ajax({
			url: '/ajax/ajax_minigame.php',
	        type: "POST",
	        dataType: "json",
	        cache: false,
	        contentType: false,
	        processData: false,
	        data: formData,
			success: function(json) {
				if (json.success) {
					updateObjects(json.success);
				}
				/*else {
					alert(json.error_lang[$('html').attr('lang')].error);
				}*/
			},
			error: function(xhr, ajaxOptions, thrownError) {	
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}

	// обновляем отображение и расположение объектов
	function updateObjects(objects) {
		$('.minigame_keyboard').css('display','none');
		if (objects['arrows'].left) { $('.minigame_keyboard_left').css('display','block'); }
		if (objects['arrows'].bottom) { $('.minigame_keyboard_bottom').css('display','block'); }
		if (objects['arrows'].up) { $('.minigame_keyboard_up').css('display','block'); }
		if (objects['arrows'].right) { $('.minigame_keyboard_right').css('display','block'); }

		$('.minigame_person_blue').css({
			'left': objects['person_blue'].left,
			'right': objects['person_blue'].right,
			'top': objects['person_blue'].top,
			'bottom': objects['person_blue'].bottom,
			'display': objects['person_blue'].display
		});
		$('.minigame_person_red1').css({
			'left': objects['person_red1'].left,
			'right': objects['person_red1'].right,
			'top': objects['person_red1'].top,
			'bottom': objects['person_red1'].bottom,
			'display': objects['person_red1'].display
		});
		$('.minigame_person_red2').css({
			'left': objects['person_red2'].left,
			'right': objects['person_red2'].right,
			'top': objects['person_red2'].top,
			'bottom': objects['person_red2'].bottom,
			'display': objects['person_red2'].display
		});
		$('.minigame_person_red3').css({
			'left': objects['person_red3'].left,
			'right': objects['person_red3'].right,
			'top': objects['person_red3'].top,
			'bottom': objects['person_red3'].bottom,
			'display': objects['person_red3'].display
		});
		$('.minigame_person_yellow1').css({
			'left': objects['person_yellow1'].left,
			'right': objects['person_yellow1'].right,
			'top': objects['person_yellow1'].top,
			'bottom': objects['person_yellow1'].bottom,
			'display': objects['person_yellow1'].display
		});
		$('.minigame_person_yellow2').css({
			'left': objects['person_yellow2'].left,
			'right': objects['person_yellow2'].right,
			'top': objects['person_yellow2'].top,
			'bottom': objects['person_yellow2'].bottom,
			'display': objects['person_yellow2'].display
		});
		$('.minigame_person_yellow3').css({
			'left': objects['person_yellow3'].left,
			'right': objects['person_yellow3'].right,
			'top': objects['person_yellow3'].top,
			'bottom': objects['person_yellow3'].bottom,
			'display': objects['person_yellow3'].display
		});
		$('.minigame_person_yellow4').css({
			'left': objects['person_yellow4'].left,
			'right': objects['person_yellow4'].right,
			'top': objects['person_yellow4'].top,
			'bottom': objects['person_yellow4'].bottom,
			'display': objects['person_yellow4'].display
		});
		$('.minigame_person_yellow5').css({
			'left': objects['person_yellow5'].left,
			'right': objects['person_yellow5'].right,
			'top': objects['person_yellow5'].top,
			'bottom': objects['person_yellow5'].bottom,
			'display': objects['person_yellow5'].display
		});
	}

	// прошли миниигру, открываем попап исходящего звонка
	function minigameOpenOutgoingCall() {
		// запускаем отображение времени
		updateIncomingTime();
		incomingCallTimer = setInterval(function(){
			updateIncomingTime();
		}, 1000);

		$('#popup_video_phone .popup_video_phone_wifi_icons').html('<img src="/images/wifi_icons.png" alt="">');
		$('#popup_video_phone .popup_video_phone_name').html('Jane Blond');
		$('#popup_video_phone').attr('class','').addClass('popup_video_phone_outgoing_minigame');

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

	// прошли миниигру - просмотрели incoming video либо закрыли попап с видео
	function minigameSuccess() {
		$('#popup_minigame_alarm').fadeIn(200);

		if (!is_touch_device()) {
			var pageSize = getPageSize();
    		var windowWidth = pageSize[2];
    		// var windowWidth = pageSize[0];
    		var windowHeight = pageSize[1];
    		if (windowWidth < 1800) {
    			$('#popup_minigame_alarm').css('height', windowHeight + 'px');
    		}
		}

		// запускаем отображение времени
		updateAlarmTime();
		alarmTimer = setInterval(function(){
			updateAlarmTime();
		}, 1000);

		// music_before = music;
		// if (music) {
			stopMusic();
		// }

		if (!alarmAudio || !isPlaying(alarmAudio)) {
			alarmAudio = new Audio;
			alarmAudio.src = '/music/alarm.mp3';
			// alarmAudio.play();

			// Autoplay
			var promise = alarmAudio.play();

			if (promise !== undefined) {
				promise.then(_ => {
					// console.log('autoplay');
				}).catch(error => {
					// console.log('autoplay ERR');
				});
			}
		}
	}

	// закрыть попап входящего звонка
	function minigameCloseIncomingCall() {
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

		// возвращаем миниигру к стартовым позициям
		uploadMinigamePositionsStart();

		// очищаем данные
		setTimeout(function(){
			$('#popup_video_phone .popup_video_phone_wifi_icons').html('');
			$('#popup_video_phone .popup_video_phone_name').html('');
			$('#popup_video_phone').attr('class','');
		}, 210);
	}

$(function() {
	/*if ($('.minigame').length && $('.content_minigame').css('display') == 'block') {
		uploadMinigamePositions();
	}*/

	// запустить звук на видео
	$('body').on('click', '.content_minigame_video_mute', function(e){
		if ($(this).hasClass('content_minigame_video_mute_active')) {
            $(this).removeClass('content_minigame_video_mute_active');
            $('.content_minigame_video video').prop('muted', 1);

            // запускаем фоновую музыку
			// if (music_before) {
			if ($('.music_on').length && $('.music_on').hasClass('music_active')) {
				playMusic();
			}
			// music_before = false;
        } else {
            $(this).addClass('content_minigame_video_mute_active');
            $('.content_minigame_video video').prop('muted', 0);

            // останавливаем фоновую музыку
			// music_before = music;
			// if (music) {
				stopMusic();
			// }
        }
	});

	// нажимаем на кнопки
	$('body').on('click', '.minigame_keyboard', function(e){
		var formData = new FormData();
    	formData.append('op', 'gotoNextStepMinigame');

    	if ($(this).hasClass('minigame_keyboard_left')) {
    		formData.append('keyboard', 'left');
    	} else if ($(this).hasClass('minigame_keyboard_bottom')) {
    		formData.append('keyboard', 'down');
    	} else if ($(this).hasClass('minigame_keyboard_up')) {
    		formData.append('keyboard', 'up');
    	} else if ($(this).hasClass('minigame_keyboard_right')) {
    		formData.append('keyboard', 'right');
    	}

    	$.ajax({
			url: '/ajax/ajax_minigame.php',
	        type: "POST",
	        dataType: "json",
	        cache: false,
	        contentType: false,
	        processData: false,
	        data: formData,
			success: function(json) {
				if (json.success) {
					updateObjects(json.success);

					// socket
					var message = {
						'op': 'minigameUpdateObjects',
						'parameters': {
							'objects': json.success,
							'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
							'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
						}
			        };
			        sendMessageSocket(JSON.stringify(message));

					// если пришли к ошибке
					if (json.key.indexOf('error') > -1) {
						setTimeout(function(){
							// socket
							var message = {
								'op': 'minigamePositionsError',
								'parameters': {
									'error_lang': json.error_lang,
									'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
									'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
								}
					        };
					        sendMessageSocket(JSON.stringify(message));

							/*// убрать звук с видео на странице, если был активен
							if ($('.content_minigame_video_mute').hasClass('content_minigame_video_mute_active')) {
								$('.content_minigame_video_mute').removeClass('content_minigame_video_mute_active');
            					$('.content_minigame_video video').prop('muted', 1);

            					minigameVideoMute = true;
							}

							// открыть видео и сразу запустить его
							playVideoByNotControls = true; // указываем, что запускалось через кнопку Play, а не через Controls
							openFileVideoPopup(0, 'video/' + $('html').attr('lang') + '/video_jane_6.mp4', '', 'minigame_error_video', 'file');
							playVideo('call');*/

							// отображаем попап ошибки
							$('#popup_search_error').addClass('popup_search_error_minigame');

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
						}, 400);
					} else if (json.key == 12 || json.key == '12') {
						// успешно прошли миниигру
						minigameOpenOutgoingCall();

						// socket
						var message = {
							'op': 'minigameOpenOutgoingCall',
							'parameters': {
								'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
								'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
							}
				        };
				        sendMessageSocket(JSON.stringify(message));
					}
				} else {
					// если пришли к ошибке
					if (json.error_lang) {
						setTimeout(function(){
							// socket
							var message = {
								'op': 'minigamePositionsError',
								'parameters': {
									'error_lang': json.error_lang,
									'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
									'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
								}
					        };
					        sendMessageSocket(JSON.stringify(message));

							/*// убрать звук с видео на странице, если был активен
							if ($('.content_minigame_video_mute').hasClass('content_minigame_video_mute_active')) {
								$('.content_minigame_video_mute').removeClass('content_minigame_video_mute_active');
            					$('.content_minigame_video video').prop('muted', 1);

            					minigameVideoMute = true;
							}

							// открыть видео и сразу запустить его
							playVideoByNotControls = true; // указываем, что запускалось через кнопку Play, а не через Controls
							openFileVideoPopup(0, 'video/' + $('html').attr('lang') + '/video_jane_6.mp4', '', 'minigame_error_video', 'file');
							playVideo('call');*/

							// отображаем попап ошибки
							$('#popup_search_error').addClass('popup_search_error_minigame');

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
						}, 400);
					} else {
						setTimeout(function(){
							// socket
							var message = {
								'op': 'minigamePositionsError',
								'parameters': {
									'error_lang': {
										'en': {
											error_input: "WARNING",
											error_text: "Agent Jane has almost got caught. Be more careful."
										},
										'no': {
											error_input: "ADVARSEL",
											error_text: "Agent Jane har nesten blitt tatt. Vær forsiktig."
										}
									},
									'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
									'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
								}
					        };
					        sendMessageSocket(JSON.stringify(message));

							// отображаем попап ошибки
							$('#popup_search_error').addClass('popup_search_error_minigame');

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
						}, 400);
					}
				}
				/*else {
					alert(json.error_lang[$('html').attr('lang')].error);
				}*/
			},
			error: function(xhr, ajaxOptions, thrownError) {	
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

	// отслеживаем нажатие на кнопки с клавиатуры
	$(document).keydown(function(e) {
		if ($('.content_minigame').is(':visible')) {
			if (e.key === 'ArrowLeft' && $('.minigame_keyboard_left').length && $('.minigame_keyboard_left').is(':visible')) {
				e.preventDefault();

				$('.minigame_keyboard_left').trigger('click');
			} else if (e.key === 'ArrowRight' && $('.minigame_keyboard_right').length && $('.minigame_keyboard_right').is(':visible')) {
				e.preventDefault();

				$('.minigame_keyboard_right').trigger('click');
			} else if (e.key === 'ArrowDown' && $('.minigame_keyboard_bottom').length && $('.minigame_keyboard_bottom').is(':visible')) {
				e.preventDefault();

				$('.minigame_keyboard_bottom').trigger('click');
			} else if (e.key === 'ArrowUp' && $('.minigame_keyboard_up').length && $('.minigame_keyboard_up').is(':visible')) {
				e.preventDefault();

				$('.minigame_keyboard_up').trigger('click');
			}
		}
	});

	/*// когда видео доиграло до конца, то закрываем и производим нужные действия (видео с ошибкой)
	$('body').on('ended', '.minigame_error_video video', function(e){
		// closePopupVideo();
		closePopupVideoCall();

		// socket
		var message = {
			'op': 'closePopupVideoMinigameError',
			'parameters': {
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
        };
        sendMessageSocket(JSON.stringify(message));

        // запускаем обновление данных
		uploadMinigamePositionsStart();
	});*/

	// закрыть попап с видео об ошибке
	$('body').on('click', '.minigame_error_video .popup_video_phone_video_bg, .minigame_error_video .popup_video_close', function(e){
		// function
		// stopVideo();
		// closePopupVideo();
		stopVideoCall();
		closePopupVideoCall();

		// socket
		var message = {
			'op': 'closeAndStopPopupVideoMinigameError',
			'parameters': {
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
        };
        sendMessageSocket(JSON.stringify(message));

		// запускаем обновление данных
		uploadMinigamePositionsStart();
	});

	// ввели верно - принять входящий звонок
	$('body').on('click', '.popup_video_phone_outgoing_minigame .popup_video_phone_btn_answer_wrapper', function(e){
		// socket
		var message = {
			'op': 'minigameCallAnswer',
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
		// openFileVideoPopup(0, 'video/' + $('html').attr('lang') + '/video_jane_7.mp4', '', 'minigame_answer_incoming_video', 'call');
		// playVideo('call');
		openFileVideoPopupCall(0, 'video/' + $('html').attr('lang') + '/video_jane_7.mp4', '', 'minigame_answer_incoming_video', 'call');
		playVideoCall();

		/*// когда видео доиграло до конца, то закрываем и производим нужные действия
		$('.minigame_answer_incoming_video video').on('ended', function() {
			closePopupVideo();

			// запускаем обновление данных
			minigameSuccess();
		});*/
	});

	/*// когда видео доиграло до конца, то закрываем и производим нужные действия
	$('body').on('ended', '.minigame_answer_incoming_video video', function(e){
		closePopupVideo();

		// фиксируем к-во очков, которое было у команды перед успешным результатом поиска. Для правильного подсчета очков команды
		$.when(getTeamInfo()).done(function(teamResponse){
			var teamInfo = teamResponse.success;

			scoreBeforeMinigame = parseInt(teamInfo.score, 10);

			// socket
			var message = {
				'op': 'closePopupVideoAndMinigameSuccess',
				'parameters': {
					'scoreBeforeMinigame': scoreBeforeMinigame,
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));

	        // запускаем обновление данных
			minigameSuccess();
		});
	});*/

	// закрыть попап входящего звонка
	$('body').on('click', '.popup_video_phone_outgoing_minigame .popup_video_phone_bg, .popup_video_phone_outgoing_minigame .popup_video_phone_btn_decline_wrapper', function(e){
		// socket
		var message = {
			'op': 'minigameCloseIncomingCall',
			'parameters': {
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
        };
        sendMessageSocket(JSON.stringify(message));

        minigameCloseIncomingCall();
	});

	/*// закрыть попап с видео
	$('body').on('click', '.minigame_answer_incoming_video .popup_video_bg, .minigame_answer_incoming_video .popup_video_close', function(e){
		// function
		// stopVideo();
		stopVideoCall();
		// closePopupVideo();
		closePopupVideoCall();

		// запускаем обновление данных
		minigameSuccess();

		// socket
		var message = {
			'op': 'stopVideoAndClosePopupVideoAndMinigameSuccess',
			'parameters': {
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
        };
        sendMessageSocket(JSON.stringify(message));

		// // фиксируем к-во очков, которое было у команды перед успешным результатом поиска. Для правильного подсчета очков команды
		// $.when(getTeamInfo()).done(function(teamResponse){
		// 	var teamInfo = teamResponse.success;

		// 	scoreBeforeMinigame = parseInt(teamInfo.score, 10);

		// 	// socket
		// 	var message = {
		// 		'op': 'stopVideoAndClosePopupVideoAndMinigameSuccess',
		// 		'parameters': {
		// 			'scoreBeforeMinigame': scoreBeforeMinigame,
		// 			'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
		// 			'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
		// 		}
	 //        };
	 //        sendMessageSocket(JSON.stringify(message));

	 //        // запускаем обновление данных
		// 	minigameSuccess();
		// });
	});*/

	// нажали на кнопку ОК в попапе Alarm
	$('.popup_minigame_alarm_submit').click(function(){
		// фиксируем к-во очков, которое было у команды перед успешным результатом поиска. Для правильного подсчета очков команды
		$.when(getTeamInfo()).done(function(teamResponse){
			var teamInfo = teamResponse.success;

			scoreBeforeMinigame = parseInt(teamInfo.score, 10);

			var formData = new FormData();
	    	formData.append('op', 'minigameUpdateHint');
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
					} else if (json.success) {
						// socket
						var message = {
							'op': 'minigameAlarmSubmit',
							'parameters': {
								'scoreBeforeMinigame': scoreBeforeMinigame,
								'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
								'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
							}
				        };
				        sendMessageSocket(JSON.stringify(message));

						// останавливаем обновление времени
						clearInterval(alarmTimer);
						alarmTimer = false;

						// останавливаем звук, если он еще активен
						if (alarmAudio && isPlaying(alarmAudio)) {
							alarmAudio.pause();
						}

						// запускаем фоновую музыку
						// if (music_before) {
						if ($('.music_on').length && $('.music_on').hasClass('music_active')) {
							playMusic();
						}
						// music_before = false;

						$.when(getTeamInfo()).done(function(teamResponse){
							var teamInfo = teamResponse.success;

							// добавляем очки
							incrementScore(parseInt(teamInfo.score, 10) + 150, 'main', teamInfo.score);
						});

						// обновляем mission progress
						incrementProgressMission(5);

						// обновляем содержимое dashboard
						uploadTypeTabsDashboardStep('password', false);

						// скрыть попап Alarm
						$('#popup_minigame_alarm').css('display','none');

						// закрываем экран с миниигрой и открываем основной экран игры
						hiddenHintWindow();
						hiddenMiniGameWindow();
						openMainGameWindow();

						// сохранить время просмотра видео в списке звонков команды
						var formData = new FormData();
				    	formData.append('op', 'updateDatetimeCall');
				    	formData.append('lang_abbr', $('html').attr('lang'));
				    	formData.append('call_id', 10);

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
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {	
					console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		});
	});
});