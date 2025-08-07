/* === ЛОГИКА ПО СОКЕТАМ === */

/* ГЛАВНЫЕ ПЕРЕМЕННЫЕ */
	// var socket = new WebSocket("ws://185.69.152.94:8090/server_game.php"); // "слушаем" сервер
	// var socket = new WebSocket("ws://91.200.43.213:8090/server_game.php"); // "слушаем" сервер
	// var socket = new WebSocket("ws://intelescape.com:8090/server_game.php"); // "слушаем" сервер
	// var socket = new WebSocket("wss://intelescape.com:8080/server_game.php"); // "слушаем" сервер
	// var socket = new WebSocket("wss://intelescape.com:8090/server_game.php"); // "слушаем" сервер
	// var socket = new WebSocket("wss://intelescape.com/server_game.php"); // "слушаем" сервер
	// var socket = new WebSocket("wss://intelescape.com:443/server_game.php"); // "слушаем" сервер
	// var socket = new WebSocket("wss://intelescape.com:44301/server_game.php"); // "слушаем" сервер
	// var socket = new WebSocket("wss://localhost:8090/server_game.php"); // "слушаем" сервер
	var socket = new WebSocket("wss://intelescape.com/websocket"); // "слушаем" сервер

	var questionEndVideo = true;

/* ОБЩИЕ ФУНКЦИИ */
	// Нужно для того, чтобы дождать подключения
	function sendMessageSocket(msg) {
	    waitForSocketConnection(socket, function(){
	        socket.send(msg);
	    });
	}

	// Переподключение, если еще нет соединения
	function waitForSocketConnection(socket, callback){
	    setTimeout(function () {
	        if (socket.readyState === 1) {
	            if (callback != null){
	                callback();
	            }
	        } else {
	            waitForSocketConnection(socket, callback);
	        }
	    }, 5); // каждые 5 миллисекунд
	}

$(function() {
	if ($('#section_game').length && !$('.section_window_results').length) { // страница игры
		// console.log(socket);

		// Соединение установлено
		socket.onopen = function() {
			// console.log('socket.onopen');
		}

		// Ошибка при соединении
		socket.onerror = function(error) {
			// console.log('socket.onerror ' + (error.message ? error.message : ""));

			// перезапускаем сервер
			if (socket.readyState !== 1) {
				/*var formData = new FormData();
		    	formData.append('op', 'serverRestore');

		    	$.ajax({
					url: '/ajax/ajax.php',
			        type: "POST",
			        dataType: "json",
			        cache: false,
			        contentType: false,
			        processData: false,
			        data: formData,
					success: function(json) {
						location.reload();
						// console.log(json);
					},
					error: function(xhr, ajaxOptions, thrownError) {	
						console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});*/
				// $.get("server_game_cron.php?restore-server=yes", function(data, status) {

		        /*setTimeout(function(){
					location.reload();
				}, 5000);*/

				
				/*$.get("server_game_cron.php?restore-server=yes", function(json) {
		            // console.log("Data: " + data);
		            // console.log("Status: " + status);
		            // console.log(json);
		            location.reload();
		        });*/
		    }
		}

		/*$.get("server_game_cron.php?restore-server=yes", function(json) {
            // console.log("Data: " + data);
            // console.log("Status: " + status);
            console.log(json);
        });*/

		// Соединение закрыто
		socket.onclose = function() {
			// location.reload();
			// console.log('socket.onclose');
		}

		// Типа сообщение
		socket.onmessage = function(event) {
			var data = JSON.parse(event.data);

			// console.log('socket.onmessage - ' + data.op);
			// console.log(data);

			// запоминаем в бд
			if (data.op != 'null' && data.op != 'newConnectionACK' && data.op != 'newDisconectedACK') {
				uploadGameByActionName(data.op, data.parameters);

				var formData = new FormData();
		    	formData.append('op', 'addSocketHistory');
		    	formData.append('socket_action', data.op);
		    	formData.append('parameters', JSON.stringify(data.parameters));

		    	$.ajax({
					url: '/ajax/ajax_socket.php',
			        type: "POST",
			        dataType: "json",
			        cache: false,
			        contentType: false,
			        processData: false,
			        data: formData,
					success: function(json) {
						// console.log('+');
					},
					error: function(xhr, ajaxOptions, thrownError) {	
						console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
		    }
		}

		// отображаем актуальные данные по игре
		uploadGameGlobal();
	} else if ($('.section_window_results').length) {
		uploadActualHighscore();
		openHighscoreWindow();

		$('.exit').remove();

		hiddenMainPreloader();

		// меняем ссылки в переключателе языков
		if ($('.language_hidden_item[href="/no/game"]').length) {
			$('.language_hidden_item[href="/no/game"]').attr('href', '/no/results');
		} else if ($('.language_hidden_item[href="/game"]').length) {
			$('.language_hidden_item[href="/game"]').attr('href', '/results');
		}
	} else if ($('.game_code_field_wrapper_inner').length) { // страница ввода кода и названия команды
		hiddenMainPreloader();

		$('.section_main_bg .main_center_bg').after('<img src="/zombie/images/gifs/main_bg.gif" class="main_center_bg_gif" alt="">');
	} else {
		hiddenMainPreloader();
	}

	// подгружаем глобальное состояние игры
	function uploadGameGlobal() {
		// обнуляем таймер текущей команды в результатах на всякий случай
		if (mainTimer3 !== false) {
			clearInterval(mainTimer3);
			mainTimer3 = false;
		}

		$.when(getTeamInfo()).done(function(teamResponse){
			var teamInfo = teamResponse.success;
			// console.log(teamInfo);

			if (teamInfo) {
				if (teamInfo.last_window_open == 'hints') {
					// если открыты подсказки
					updateHintWindow();
					hiddenMainGameWindow();
					hiddenMiniGameWindow();
					hiddenInterpolWindow();
					hiddenHighscoreWindow();
					openHintWindow();
				} else if (teamInfo.last_window_open == 'main') {
					// если открыто основное окно игры
					hiddenHintWindow();
					hiddenMiniGameWindow();
					hiddenInterpolWindow();
					hiddenHighscoreWindow();
					openMainGameWindow();
				} else if (teamInfo.last_window_open == 'minigame') {
					// если открыто окно миниигры
					hiddenMainGameWindow();
					hiddenHintWindow();
					hiddenInterpolWindow();
					hiddenHighscoreWindow();
					openMiniGameWindow();
				} else if (teamInfo.last_window_open == 'interpol') {
					// если открыто окно interpol
					hiddenMainGameWindow();
					hiddenHintWindow();
					hiddenMiniGameWindow();
					hiddenHighscoreWindow();
					openInterpolWindow();
				} else if (teamInfo.last_window_open == 'highscore') {
					// если открыты результаты
					uploadActualHighscore();
					hiddenHintWindow();
					hiddenMainGameWindow();
					hiddenMiniGameWindow();
					hiddenInterpolWindow();
					openHighscoreWindow();
				}

				hiddenMainPreloader();

				// активный тип табов
				hiddenAllTypeTabs();

				// окно чата
				if (teamInfo.open_chat == 'yes') {
					updateChatMessages();
					$('.chat').animate({height: '674px'},200);
					$('.btn_wrapper_view_chat').addClass('btn_wrapper_view_chat_active');
				} else {
					$('.chat').animate({height: '0px'},200);
					$('.btn_wrapper_view_chat').removeClass('btn_wrapper_view_chat_active');
				}

				// актуальное к-во очков
				// console.log('score = ' + teamInfo.score);
				loadScoreActual(teamInfo.score);

				// грузим последнее действие команды
				// if (json.socket_action) {
				// 	uploadGameByActionName(json.socket_action, json.parameters);
				// }

				// chatPrintFirstMessagesFromBot();

				// если игра завершена
				if (teamInfo.mission_finish_seconds > 0) {
					var formData = new FormData();
			    	formData.append('op', 'getActiveTimer');

			    	$.ajax({
						url: '/ajax/ajax_timer.php',
				        type: "POST",
				        dataType: "json",
				        cache: false,
				        contentType: false,
				        processData: false,
				        data: formData,
						success: function(json) {
							// обновляем время на главном экране
							$('.timer .timer_hour').html(('0' + json.hours).slice(-2));
							$('.timer .timer_minute').html(('0' + json.minute).slice(-2));
							$('.timer .timer_second').html(('0' + json.second).slice(-2));

							// пишем время в победном попапе
							$('.popup_mission_complete_center_inner_result_hours').html(('0' + json.hours).slice(-2));
							$('.popup_mission_complete_center_inner_result_minutes').html(('0' + json.minute).slice(-2));
							$('.popup_mission_complete_center_inner_result_seconds').html(('0' + json.second).slice(-2));

							// пишем баллы в победном попапе
							$('.popup_mission_complete_center_inner_score .popup_mission_complete_center_inner_result_number').html(teamInfo.score);

							// отображаем победный попап
							$('#popup_mission_complete').css('display','block');
							// $.scrollLock(true);

							if (!is_touch_device()) {
								var pageSize = getPageSize();
			    				var windowWidth = pageSize[2];
			    				var windowHeight = pageSize[1];

			    				if (windowWidth < 1800) {
			    					$('#popup_mission_complete').css('height', windowHeight + 'px');


			    				} else {
			    					$.scrollLock(true);
			    				}
							} else {
								$.scrollLock(true);
							}
						},
						error: function(xhr, ajaxOptions, thrownError) {	
							console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});

					// останавливаем отсчет таймер на главном экране
					if (mainTimer !== false) {
						clearInterval(mainTimer);
						mainTimer = false;
					}

					if (mainTimer2 !== false) {
						clearInterval(mainTimer2);
						mainTimer2 = false;
					}
				} else {
					if (teamInfo.last_type_tabs == 'dashboard') {
						$('.dashboard_tabs[data-dashboard="dashboard"]').addClass('dashboard_tabs_active');
						$('.dashboard_item[data-dashboard="dashboard"]').addClass('dashboard_item_active');

						uploadTypeTabsDashboardStep(teamInfo.last_dashboard, false);
					} else if (teamInfo.last_type_tabs == 'calls') {
						$('.dashboard_tabs[data-dashboard="calls"]').addClass('dashboard_tabs_active');
						$('.dashboard_item[data-dashboard="calls"]').addClass('dashboard_item_active');

						uploadTypeTabsCallsStep(teamInfo.last_calls, false, teamInfo.view_call_mobile_btn, teamInfo.open_call_mobile_btn);
					} else if (teamInfo.last_type_tabs == 'files') {
						$('.dashboard_tabs[data-dashboard="files"]').addClass('dashboard_tabs_active');
						$('.dashboard_item[data-dashboard="files"]').addClass('dashboard_item_active');

						uploadTypeTabsFilesStep(false);
					} else if (teamInfo.last_type_tabs == 'databases') {
						$('.dashboard_tabs[data-dashboard="databases"]').addClass('dashboard_tabs_active');
						$('.dashboard_item[data-dashboard="databases"]').addClass('dashboard_item_active');

						uploadTypeTabsDatabasesStep(teamInfo.last_databases, false, false);
					} else if (teamInfo.last_type_tabs == 'tools') {
						$('.dashboard_tabs[data-dashboard="tools"]').addClass('dashboard_tabs_active');
						$('.dashboard_item[data-dashboard="tools"]').addClass('dashboard_item_active');

						uploadTypeTabsToolsStep(teamInfo.last_tools, false, false);
					}

					// Обновить к-во непрочитанных файлов
					updateDontOpenFilesQt();

					// Обновить к-во неоткрытых баз данных
					updateDontOpenDatabasesQt();

					// Обновить к-во неоткрытых tools
					updateDontOpenToolsQt();

					// Нажата ли Call mobile
					if ((teamInfo.open_call_mobile_btn == 0 || teamInfo.open_call_mobile_btn == '0') && (teamInfo.view_call_mobile_btn == 1 || teamInfo.view_call_mobile_btn == '1')) {
						$('.dashboard_item[data-dashboard="calls"] .dashboard_item_text_qt').html(1).css('display', 'inline-block');

						if ($('.call_mobile').length > 0) {
							$('.call_mobile .dashboard_item_text_qt').html(1).css('display', 'inline-block');
						}
					}

					// отображение блока с глобусом Gem
					if (teamInfo.view_gem == 1) {
						$('.dashboard_gem_wrapper').addClass('dashboard_gem_wrapper_active');
					}

					// отображение кнопка Call Jane
					if (teamInfo.view_call_jane_btn == 1) {
						$('.call_jane').addClass('call_jane_active');
					}

					// запустить обновление таймера
					updateTimerUploadPage();
				}
			}
		});
	}

	// одновременное обновление действий команды
	function uploadGameByActionName(op, parameters) {
		if ($.type(op) !== 'null') {
			var checkUserId = $('#section_game').length ? $('#section_game').attr('data-user-id') : 0;
			// console.log('uploadGameByActionName', checkUserId, parameters);
			// console.log('uploadGameByActionName', checkUserId, parameters.user_id);

			if (parameters.user_id != 0 && parameters.user_id != checkUserId) { // если id не совпадают
				if (op == 'loadSocketMain') { // глобально обновляем игру
					// console.log('uploadGameByActionName-1');
					uploadGameGlobal();
				} else if (op == 'loadSocketHighscoreToday') { // отображаем сегодняшние результаты
					viewMainPreloader(defaultLoaderLoadingText);

					// обнуляем таймер текущей команды в результатах на всякий случай
					if (mainTimer3 !== false) {
						clearInterval(mainTimer3);
						mainTimer3 = false;
					}

					// убираем скролл у текущего блока
					$('.highscore_table_body').mCustomScrollbar('destroy');

					// делаем активной кнопку
					$('.highscore_wrapper .highscore_btn').removeClass('highscore_btn_active');
					$('.highscore_wrapper .highscore_btn_today').addClass('highscore_btn_active');

					// грузим результаты в новом табе
					uploadActualHighscore();
				} else if (op == 'openFileVideoPopup') { // открыть видео в попапе
					// openFileVideoPopup(parameters.fileId, parameters.path, parameters.name, parameters.classVideo, parameters.type);
					openFileVideoPopup(parameters.fileId, 'video/' + $('html').attr('lang') + '/' + parameters.file_with_path, parameters.name, parameters.classVideo, parameters.type);
				} else if (op == 'openFilePdf') { // открыть pdf
					/*if (is_touch_device()) {
						$.fancybox.open({
		                    // src: '/plugins/pdf.js/web/viewer.html?file=' + encodeURIComponent('/' + parameters.path),
		                    src: '/plugins/pdf.js/web/viewer.html?file=' + encodeURIComponent('/pdf/' + $('html').attr('lang') + '/' + parameters.file_with_path),
		                    type: 'iframe',
		                    opts: {
		                        afterShow: function(instance, current) {},
		                        beforeShow: function(instance, current) {},
		                        beforeClose: function(instance, current) {
		                        	// socket
									var message = {
										'op': 'closeFilePdf',
										'parameters': {
											'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
											'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
										}
							        };
							        sendMessageSocket(JSON.stringify(message));
		                        },
		                        iframe : {
		                            preload : false
		                        }
		                    }
		                });
					} else {
						var pageSize = getPageSize();
				        var windowWidth = pageSize[2];
				        if (windowWidth < 1800) {
				        	var koef = parseFloat((windowWidth / 1920).toFixed(2)) + 0.01;

				        	$.fancybox.open({
			                    // src: '/' + parameters.path,
			                    src: '/pdf/' + $('html').attr('lang') + '/' + parameters.file_with_path,
			                    type: 'iframe',
			                    opts: {
			                        afterShow: function(instance, current) {
		                        		$('.fancybox-container.fancybox-is-open').css('height', ($('#main').outerHeight() * parseFloat((1920 / windowWidth).toFixed(2))) + 'px');
			                        },
			                        beforeShow: function(instance, current) {},
			                        beforeClose: function(instance, current) {
			                        	// socket
										var message = {
											'op': 'closeFilePdf',
											'parameters': {
												'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
												'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
											}
								        };
								        sendMessageSocket(JSON.stringify(message));
			                        },
			                        iframe : {
			                            preload : false
			                        }
			                    }
			                });
				        } else {
							$.fancybox.open({
			                    // src: '/' + parameters.path,
			                    src: '/pdf/' + $('html').attr('lang') + '/' + parameters.file_with_path,
			                    type: 'iframe',
			                    opts: {
			                        afterShow: function(instance, current) {},
			                        beforeShow: function(instance, current) {},
			                        beforeClose: function(instance, current) {
			                        	// socket
										var message = {
											'op': 'closeFilePdf',
											'parameters': {
												'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
												'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
											}
								        };
								        sendMessageSocket(JSON.stringify(message));
			                        },
			                        iframe : {
			                            preload : false
			                        }
			                    }
			                });
						}
					}*/

					// Обновить к-во непрочитанных файлов
					updateDontOpenFilesQt();

					/*var win = window.open(parameters.path, '_blank');
					if (win) {
						win.focus();
					}*/
				} else if (op == 'closeFilePdf') { // закрыть файл pdf
					$.fancybox.close();
				} else if (op == 'openFileLink') { // открыть линк из списка файлов
					// Обновить к-во непрочитанных файлов
					updateDontOpenFilesQt();

					/*var win = window.open(parameters.path, '_blank');
					if (win) {
						win.focus();
					}*/
				} else if (op == 'playVideoFileByPlayBtn') { // запустить проигрывание файла видео в попапе при нажатию нашей кнопки play
					// console.log('playVideoFileByPlayBtn - socket');
					if ($('#popup_video_mp4').length) {
						// music_before = music;
						stopMusic();
						// if ($('.music_on').length && $('.music_on').hasClass('music_active')) {
						// 	stopMusic();
						// }

						var checkedVideo = $('#popup_video_mp4').get(0);
						if (checkedVideo.paused) {
							// checkedVideo.play();
							$('#popup_video .popup_video_play').css('display','none');
							$('#popup_video .popup_video_stop').css('display','block');

							$('#popup_video .popup_video_btns').css('display','none');

							// Autoplay
							var promise = checkedVideo.play();

							if (promise !== undefined) {
								promise.then(_ => {
									// console.log('autoplay');
								}).catch(error => {
									// console.log('autoplay ERR');
								});
							}
						}
					}
				} else if (op == 'playVideoFileByControls') { // запуск файла при нажатии на кнопку Play в стандартных Controls
					if ($('#popup_video_mp4').length) {
						// music_before = music;
						stopMusic();
						// if ($('.music_on').length && $('.music_on').hasClass('music_active')) {
						// 	stopMusic();
						// }

						var checkedVideo = $('#popup_video_mp4').get(0);
						if (checkedVideo.paused) {
							// checkedVideo.play();
							$('#popup_video .popup_video_play').css('display','none');
							$('#popup_video .popup_video_stop').css('display','block');

							$('#popup_video .popup_video_btns').css('display','none');

							// Autoplay
							var promise = checkedVideo.play();

							if (promise !== undefined) {
								promise.then(_ => {
									// console.log('autoplay');
								}).catch(error => {
									// console.log('autoplay ERR');
								});
							}
						}
					}
				} else if (op == 'playVideoFileByControlsCalls') { // запуск файла при нажатии на кнопку Play в стандартных Controls. Если звонок
					if ($('#popup_video_mp4_call').length) {
						// music_before = music;
						stopMusic();
						// if ($('.music_on').length && $('.music_on').hasClass('music_active')) {
						// 	stopMusic();
						// }

						var checkedVideo = $('#popup_video_mp4_call').get(0);
						if (checkedVideo.paused) {
							// checkedVideo.play();

							// Autoplay
							var promise = checkedVideo.play();

							if (promise !== undefined) {
								promise.then(_ => {
									// console.log('autoplay');
								}).catch(error => {
									// console.log('autoplay ERR');
								});
							}
						}
					}
				} else if (op == 'stopVideo') { // остановить проигрывание файла видео
					stopVideo();
				} else if (op == 'stopVideoByControls') { // остановить проигрывание видео при нажатии на кнопке Pause в стандартных Controls
				    stopVideo();
				} else if (op == 'stopVideoByControlsCalls') { // остановить проигрывание видео при нажатии на кнопке Pause в стандартных Controls. Если звонок
				    stopVideoCall();
				} else if (op == 'stopVideoAndClosePopupVideo') { // остановить проигрывание видео и закрыть попап
					// console.log('socket stopVideoAndClosePopupVideo');
					stopVideo();
					closePopupVideo();

					if (questionEndVideo) {
						if ('video_url_en' in parameters) {
							$('#popup_end_video_question').css('display','block').attr('video-url', parameters['video_url_' + $('html').attr('lang')]);
						}
					}
				} else if (op == 'stopVideoAndClosePopupVideoCalls') { // остановить проигрывание видео и закрыть попап. Если звонок
					stopVideoCall();
					closePopupVideoCall();

					if (questionEndVideo) {
						if ('video_url_en' in parameters) {
							$('#popup_end_video_question').css('display','block').attr('video-url', parameters['video_url_' + $('html').attr('lang')]);
						}
					}

					if ('is_minigame_success' in parameters) {
						if (parameters.is_minigame_success === true) {
							minigameSuccess();
						}
					}
				} else if (op == 'acceptMissionKeyup') { // ввод символов при вводе названия миссии. Dashboard accept mission
					if ($('.dashboard_tab_content_item_new_mission_input').length) {
						$('.dashboard_tab_content_item_new_mission_input').val(parameters.mission_name);
					}
				} else if (op == 'missionNumberError') { // название миссии. Ввели неверно
					if ($('.dashboard_tab_content_item_new_mission_error').length) {
						$('.dashboard_tab_content_item_new_mission_error').html(parameters.error_lang[$('html').attr('lang')]).addClass('dashboard_tab_content_item_new_mission_error_active');
					}
				} else if (op == 'missionNumberOpenIncomingCall') { // название миссии ввели верно. Открываем попап со звонком
					if ($('.dashboard_tab_content_item_new_mission_error').length) {
						$('.dashboard_tab_content_item_new_mission_error').removeClass('dashboard_tab_content_item_new_mission_error_active').html('');
					}

					$('.popup_start_mission_number').css('opacity', 1);
					$('#popup_start_mission').css('display','block');

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

					setTimeout(function(){
						$('.popup_start_mission_number').css('opacity', 0);
						$('#popup_start_mission').css('display','none');
					}, 2750);

					setTimeout(function(){
						// открываем попап входящего звонка
						newMissionOpenIncomingCall();
					}, 3000);

					/*if (!startAudio || !isPlaying(startAudio)) {
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

					setTimeout(function(){
						$('.popup_start_mission_number').css('opacity', 0);
						$('#popup_start_mission').css('display','none');
					}, 2750);

					setTimeout(function(){
						// открываем попап входящего звонка
						newMissionOpenIncomingCall();
					}, 3000);*/
				} else if (op == 'missionNumberCloseIncomingCall') { // название миссии ввели верно. Закрыть попап со звонком
					// останавливаем звук звонка и запускаем фоновое
					/*if (music_before) {
						playMusic();
					}*/
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
				} else if (op == 'acceptMissionIncomingCallAccept') { // название миссии ввели верно. Принять входящий звонок
					// запускаем фоновую музыку, если была
					/*if (music_before) {
						playMusic();
					}*/
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
					openFileVideoPopup(0, 'video/' + $('html').attr('lang') + '/zombie/video_jane_1.mp4', '', 'new_mission_answer_incoming_video', 'call');
					playVideo('call');
					// openFileVideoPopupCall(0, 'video/' + $('html').attr('lang') + '/zombie/video_jane_1.mp4', '', 'new_mission_answer_incoming_video', 'call_jane');
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
				} else if (op == 'acceptMissionIncomingCallAccept') { // приняли миссию. Видео доиграло до конца
					closePopupVideo();

					// пишем игроку первые 100 баллов
					incrementScoreWithoutSaveDb(100, 'main', 0);

					// запускаем таймер
					updateTimerUploadPage();

					// Обновить к-во непрочитанных файлов
					updateDontOpenFilesQt();

					// Обновить к-во неоткрытых баз данных
					updateDontOpenDatabasesQt();

			    	// отображаем блок Mission name GEM
					$('.dashboard_gem_wrapper').addClass('dashboard_gem_wrapper_active');
					setTeamTabsTextInfo('view_gem', 1);

					// обновляем содержимое dashboard
					uploadTypeTabsDashboardStep('company_name', false);

					// отображаем блок Call Jane
					$('.call_jane').addClass('call_jane_active');
				} else if (op == 'stopVideoAndClosePopupVideoAndAcceptMission') { // приняли миссию путем закрытия видео
					stopVideo();
					closePopupVideo();
					// stopVideoCall();
					// closePopupVideoCall();

					if (questionEndVideo) {
						$('#popup_end_video_question').css('display','block').attr('video-url', '/zombie/video/' + $('html').attr('lang') + '/zombie/video_jane_1.mp4');
					}

					// пишем игроку первые 100 баллов
					incrementScoreWithoutSaveDb(100, 'main', 0);

					// запускаем таймер
					updateTimerUploadPage();

					// Обновить к-во непрочитанных файлов
					updateDontOpenFilesQt();

					// Обновить к-во неоткрытых баз данных
					updateDontOpenDatabasesQt();

			    	// отображаем блок Mission name GEM
					$('.dashboard_gem_wrapper').addClass('dashboard_gem_wrapper_active');
					setTeamTabsTextInfo('view_gem', 1);

					// обновляем содержимое dashboard
					uploadTypeTabsDashboardStep('company_name', false);

					// отображаем блок Call Jane
					$('.call_jane').addClass('call_jane_active');
				} else if (op == 'closePopupVideoAndAcceptMission') { // приняли миссию, видео доиграло до конца
					// stopVideo();
					// closePopupVideo();
					stopVideoCall();
					closePopupVideoCall();

					// пишем игроку первые 100 баллов
					incrementScoreWithoutSaveDb(100, 'main', 0);

					// запускаем таймер
					updateTimerUploadPage();

					// Обновить к-во непрочитанных файлов
					updateDontOpenFilesQt();

					// Обновить к-во неоткрытых баз данных
					updateDontOpenDatabasesQt();

			    	// отображаем блок Mission name GEM
					$('.dashboard_gem_wrapper').addClass('dashboard_gem_wrapper_active');
					setTeamTabsTextInfo('view_gem', 1);

					// обновляем содержимое dashboard
					uploadTypeTabsDashboardStep('company_name', false);

					// отображаем блок Call Jane
					$('.call_jane').addClass('call_jane_active');
				} else if (op == 'callJane') { // звоним Jane. Страница Calls
					// запускаем отображение времени
					updateOutgoingTime();
					outgoingCallTimer = setInterval(function(){
						updateOutgoingTime();
					}, 1000);

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
				} else if (op == 'callJaneNotAvailable') { // звоним Jane. Страница Calls. Больше недоступно
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
				} else if (op == 'callJaneOutgoingAccept') { // исходящий звонок к Jane. Она типа ответила
					// останавливаем звук звонка
					if (outgoingAudio && isPlaying(outgoingAudio)) {
						outgoingAudio.pause();
					}

					// фикс для звучания музыки позже
					if ($('.music_on').length && $('.music_on').hasClass('music_active')) {
						playMusic();
					}

					// останавливаем обновление времени
					if (outgoingCallTimer) {
						clearInterval(outgoingCallTimer);
						outgoingCallTimer = false;
					}

					// скрываем блок с телефоном
					$('#popup_video_phone_outgoing').fadeOut(200);

					// открыть видео и сразу запустить его
					playVideoByNotControls = true; // указываем, что запускалось через кнопку Play, а не через Controls
					// openFileVideoPopup(parameters.fileId, 'video/' + $('html').attr('lang') + '/' + parameters.video_with_path, parameters.name, parameters.classVideo, parameters.type);
					// playVideo('call');
					openFileVideoPopupCall(parameters.fileId, 'video/' + $('html').attr('lang') + '/' + parameters.video_with_path, parameters.name, parameters.classVideo, parameters.type);
					playVideoCall();

					// обновляем содержимое вкладки call с учетом совершенного звонка
					$.when(getTeamInfo()).done(function(teamResponse){
						var teamInfo = teamResponse.success;

						if (teamInfo) {
							uploadTypeTabsCallsStep(teamInfo.last_calls, false, teamInfo.view_call_mobile_btn, teamInfo.open_call_mobile_btn);
						}
					});
				} else if (op == 'callJaneOutgoingDecline') { // закрыть попап с исходящим звонком к Jane
					// останавливаем звук звонка и запускаем фоновое
					if (outgoingAudio && isPlaying(outgoingAudio)) {
						outgoingAudio.pause();
					}

					if ($('.music_on').length && $('.music_on').hasClass('music_active')) {
						playMusic();
					}

					// останавливаем обновление времени
					if (outgoingCallTimer) {
						clearInterval(outgoingCallTimer);
						outgoingCallTimer = false;
					}

					// скрываем блок с телефоном
					$('#popup_video_phone_outgoing').fadeOut(200);

					callsNotAvailableJaneOpenPopup = false;
				} else if (op == 'openFileVideoPopupAndPlayVideoCall') { // повторно просмотреть звонок к Jane
					playVideoByNotControls = true; // указываем, что запускалось через кнопку Play, а не через Controls

					// openFileVideoPopup(parameters.fileId, 'video/' + $('html').attr('lang') + '/' + parameters.video_with_path, parameters.name, parameters.classVideo, parameters.type);
					// playVideo('call');

					// openFileVideoPopupCall(parameters.fileId, 'video/' + $('html').attr('lang') + '/' + parameters.video_with_path, parameters.name, parameters.classVideo, parameters.type);
					// playVideoCall();

					if (parameters.video_with_path == 'video_jane_1.mp4' || parameters.video_with_path == 'video_jane_2.mp4' || parameters.video_with_path == 'video_jane_3.mp4' || parameters.video_with_path == 'video_jane_4.mp4') {
						openFileVideoPopup(parameters.fileId, 'video/' + $('html').attr('lang') + '/' + parameters.video_with_path, parameters.name, parameters.classVideo, parameters.type);
						playVideo('call');
					} else {
						openFileVideoPopupCall(parameters.fileId, 'video/' + $('html').attr('lang') + '/' + parameters.video_with_path, parameters.name, parameters.classVideo, parameters.type);
						playVideoCall();
					}
				} else if (op == 'databaseCarregisterSearchLicensePlateKeyup') { // database - первый экран car register - вводим что-то в поле license plate
					if ($('.dashboard_car_register1_license_plate').length && $('.dashboard_car_register1_license_plate').val() != parameters.license_plate) {
						$('.dashboard_car_register1_license_plate').val(parameters.license_plate);
					}
				} else if (op == 'databaseCarRegisterUpdateCountry') { // database - первый экран car register - выбираем значение в Country
					if ($('.dashboard_car_register1_country').length && $('.dashboard_car_register1_country').val() != parameters.country_lang[$('html').attr('lang')]) {
						$('.dashboard_car_register1_country').val(parameters.country_lang[$('html').attr('lang')]).change().selectric('refresh');
					}
				} else if (op == 'databaseCarRegisterUpdateDate') { // database - первый экран car register - выбираем дату
					if ($('.dashboard_car_register1_date').length && $('.dashboard_car_register1_date').val() != parameters.date) {
						$('.dashboard_car_register1_date').val(parameters.date);
					}
				} else if (op == 'databaseCarregisterSearchDateKeyup') { // database - первый экран car register - вводим что-то в поле date
					if ($('.dashboard_car_register1_date').length && $('.dashboard_car_register1_date').val() != parameters.date) {
						$('.dashboard_car_register1_date').val(parameters.date);
					}
				} else if (op == 'databaseCarRegisterEmptyFields') { // database - первый экран car register - есть пустые поля
					if ($('.dashboard_car_register1_license_plate').length) {
						if (parameters.license_plate_error) {
							$('.dashboard_car_register1_license_plate_error').addClass('error_text_database_car_register_active');
						} else {
							$('.dashboard_car_register1_license_plate_error').removeClass('error_text_database_car_register_active');
						}
					}

					if ($('.dashboard_car_register1_country_error').length) {
						if (parameters.country_error) {
							$('.dashboard_car_register1_country_error').addClass('error_text_database_car_register_active');
						} else {
							$('.dashboard_car_register1_country_error').removeClass('error_text_database_car_register_active');
						}
					}

					if ($('.dashboard_car_register1_date_error').length) {
						if (parameters.date_error) {
							$('.dashboard_car_register1_date_error').addClass('error_text_database_car_register_active');
						} else {
							$('.dashboard_car_register1_date_error').removeClass('error_text_database_car_register_active');
						}
					}
				} else if (op == 'databaseCarRegisterNoEmptyFields') { // database - первый экран car register - нет пустых полей, открываем попап поиска и определяем результаты
					scoreBeforeDatabaseCarRegister = parameters.scoreBeforeDatabaseCarRegister;

					databaseCarRegisterNoEmptyFields(parameters.license_plate, parameters.country, parameters.date, parameters.lang_abbr);
				} else if (op == 'popupSearchErrorClose') { // поиск завершился неудачей. Закрываем попап
					$('#popup_search_error').fadeOut(200);
				} else if (op == 'popupCallMobileClose') { // Call mobile. Закрываем попап
					$('#popup_call_mobile').fadeOut(200);
				} else if (op == 'databasePersonalFilesPrivateIndividualFirstnameKeyup') { // database - personal files - private individuals + ceo database - вводим что-то в поле firstname
					if ($('.dashboard_personal_files2_private_individuals_input_wrapper_firstname input').length && $('.dashboard_personal_files2_private_individuals_input_wrapper_firstname input').val() != parameters.firstname) {
						$('.dashboard_personal_files2_private_individuals_input_wrapper_firstname input').val(parameters.firstname);
					}
				} else if (op == 'databasePersonalFilesPrivateIndividualLastnameKeyup') { // database - personal files - private individuals + ceo database - вводим что-то в поле lastname
					if ($('.dashboard_personal_files2_private_individuals_input_wrapper_lastname input').length && $('.dashboard_personal_files2_private_individuals_input_wrapper_lastname input').val() != parameters.lastname) {
						$('.dashboard_personal_files2_private_individuals_input_wrapper_lastname input').val(parameters.lastname);
					}
				} else if (op == 'databasePersonalFilesPrivateIndividualsNoEmptyFields') { // database - personal files - private individuals - нет пустых полей, открываем попап поиска
					scoreBeforeDatabasePersonalfilesPrivateindividuals = parameters.scoreBeforeDatabasePersonalfilesPrivateindividuals;

					databasePersonalFilesPrivateIndividualsNoEmptyFields(parameters.firstname, parameters.lastname, parameters.lang_abbr);
				} else if (op == 'databasePersonalFilesPrivateIndividualsEmptyFields') { // database - personal files - private individuals - есть пустые поля
					if ($('.dashboard_personal_files2_private_individuals_input_wrapper_firstname input').length) {
						if (parameters.firstname_error) {
							$('.dashboard_personal_files2_private_individuals_firstname_error').addClass('error_text_database_car_register_active');
						} else {
							$('.dashboard_personal_files2_private_individuals_firstname_error').removeClass('error_text_database_car_register_active');
						}
					}

					if ($('.dashboard_personal_files2_private_individuals_input_wrapper_lastname input').length) {
						if (parameters.lastname_error) {
							$('.dashboard_personal_files2_private_individuals_lastname_error').addClass('error_text_database_car_register_active');
						} else {
							$('.dashboard_personal_files2_private_individuals_lastname_error').removeClass('error_text_database_car_register_active');
						}
					}
				} else if (op == 'databaseMobileCallsNumberKeyup') {
					if ($('.dashboard_mobile_calls1_number').length && $('.dashboard_mobile_calls1_number').val() != parameters.number) {
						$('.dashboard_mobile_calls1_number').val(parameters.number);
					}
				} else if (op == 'databaseMobileCallsUpdateCountryCode') { // database - первый экран mobile calls - выбираем значение в Country code
					if ($('.dashboard_mobile_calls1_country_code').length && $('.dashboard_mobile_calls1_country_code').val() != parameters.country_lang[$('html').attr('lang')]) {
						$('.dashboard_mobile_calls1_country_code').val(parameters.country_lang[$('html').attr('lang')]).change().selectric('refresh');
					}
				} else if (op == 'databaseMobileCallsNoEmptyFields') { // database - mobile calls - нет пустых полей, открываем попап поиска
					scoreBeforeDatabaseMobileCalls = parameters.scoreBeforeDatabaseMobileCalls;

					databaseMobileCallsNoEmptyFields(parameters.country_code, parameters.number, parameters.lang_abbr);
				} else if (op == 'databaseMobileCallsEmptyFields') { // database - первый экран mobile calls - есть пустые поля
					if ($('.dashboard_mobile_calls1_country_code_error').length) {
						if (parameters.country_code_error) {
							$('.dashboard_mobile_calls1_country_code_error').addClass('error_text_database_car_register_active');
						} else {
							$('.dashboard_mobile_calls1_country_code_error').removeClass('error_text_database_car_register_active');
						}
					}

					if ($('.dashboard_mobile_calls1_number_error').length) {
						if (parameters.number_error) {
							$('.dashboard_mobile_calls1_number_error').addClass('error_text_database_car_register_active');
						} else {
							$('.dashboard_mobile_calls1_number_error').removeClass('error_text_database_car_register_active');
						}
					}
				} else if (op == 'databaseMobileCallsOpenPopupMesages') { // database - mobile calls - открыть попап с сообщениями
					$('#popup_mobile_calls_messages').css('display','block');

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
			    			$('#popup_mobile_calls_messages').css('height', windowHeight + 'px');
			    		}
					}
				} else if (op == 'databaseMobileCallsClosePopupMesages') { // database - mobile calls - сообщения, скрыть попап
					$('#popup_mobile_calls_messages').fadeOut(200);
				} else if (op == 'databasePersonalFilesCeoDatabaseNoEmptyFields') { // database - personal files - ceo database - нет пустых полей, открываем попап поиска
					scoreBeforeDatabasePersonalfilesCeodatabase = parameters.scoreBeforeDatabasePersonalfilesCeodatabase;

					databasePersonalFilesCeoDatabaseNoEmptyFields(parameters.firstname, parameters.lastname, parameters.lang_abbr);
				} else if (op == 'databasePersonalFilesCeoDatabaseEmptyFields') { // database - personal files - ceo database - есть пустые поля
					if ($('.dashboard_personal_files2_private_individuals_input_wrapper_firstname input').length) {
						if (parameters.firstname_error) {
							$('.dashboard_personal_files2_private_individuals_firstname_error').addClass('error_text_database_car_register_active');
						} else {
							$('.dashboard_personal_files2_private_individuals_firstname_error').removeClass('error_text_database_car_register_active');
						}
					}

					if ($('.dashboard_personal_files2_private_individuals_input_wrapper_lastname input').length) {
						if (parameters.lastname_error) {
							$('.dashboard_personal_files2_private_individuals_lastname_error').addClass('error_text_database_car_register_active');
						} else {
							$('.dashboard_personal_files2_private_individuals_lastname_error').removeClass('error_text_database_car_register_active');
						}
					}
				} else if (op == 'dashboardCompanyNameKeyup') { // ввод символов при вводе dashboard - company name (investigation)
					if ($('.dashboard_tab_content_item_company_name_input').length) {
						$('.dashboard_tab_content_item_company_name_input').val(parameters.company_name);
					}
				} else if (op == 'dashboardCompanyNameEmptyFields') { // dashboard - company name (investigation), есть пустые поля
					if ($('.dashboard_tab_content_item_company_name_error').length) {
						if (parameters.company_name_error) {
							$('.dashboard_tab_content_item_company_name_error').addClass('error_text_database_car_register_active');
						} else {
							$('.dashboard_tab_content_item_company_name_error').removeClass('error_text_database_car_register_active');
						}
					}
				} else if (op == 'dashboardCompanyNameNoEmptyFields') { // dashboard - company name (investigation), нет пустых полей, отображаем попап database search
					companyInvestigateSubmit(parameters.company_name, parameters.lang_abbr);
				} else if (op == 'dashboardCompanyInvestigateCloseSuccessPopupAndOpenIncomingCall') { // dashboard - company investigation - закрыть попап при правильном вводе данных и открыть попап входящего звонка
					// закрыть попап с успешным выполнением
					$('#popup_success').removeClass('popup_success_company_investigate').fadeOut(200);

					// открываем попап входящего звонка
					companyInvestigateOpenIncomingCall();
				} else if (op == 'dashboardCompanyInvestigateCloseIncomingCall') { // dashboard - company investigation - закрыть попап входящего звонка
					companyInvestigateCloseIncomingCall();
				} else if (op == 'dashboardCompanyInvestigateCallAnswer') { // dashboard - company investigation - ввели верно, принять входящий звонок
					// запускаем фоновую музыку, если была
					if ($('.music_on').length && $('.music_on').hasClass('music_active')) {
						playMusic();
					}

					// останавливаем звук звонка
					if (incomingMusicTimer) {
						clearInterval(incomingMusicTimer);
						incomingMusicTimer = false;

						if (incomingAudio && isPlaying(incomingAudio)) {
							incomingAudio.pause();
						}
					}

					// останавливаем обновление времени
					if (incomingCallTimer) {
						clearInterval(incomingCallTimer);
						incomingCallTimer = false;
					}

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
					openFileVideoPopup(0, 'video/' + $('html').attr('lang') + '/zombie/video_jane_2.mp4', '', 'company_investigate_answer_incoming_video', 'call');
					playVideo('call');
					// openFileVideoPopupCall(0, 'video/' + $('html').attr('lang') + '/zombie/video_jane_2.mp4', '', 'company_investigate_answer_incoming_video', 'call_jane');
					// playVideoCall();
				} else if (op == 'closePopupVideoAndCompanyInvestigateSuccess') { // dashboard - company investigation. Видео доиграло до конца
					// closePopupVideo();
					closePopupVideoCall();

					scoreBeforeDashboardCompanyInvestigate = parameters.scoreBeforeDashboardCompanyInvestigate;

					// событие уже сработало
					companyInvestigateFromSocket();
				} else if (op == 'stopVideoAndClosePopupVideoAndCompanyInvestigateSuccess') { // dashboard - company investigation. Закрыть попап с видео
					stopVideo();
					closePopupVideo();
					// stopVideoCall();
					// closePopupVideoCall();

					if (questionEndVideo) {
						$('#popup_end_video_question').css('display','block').attr('video-url', '/zombie/video/' + $('html').attr('lang') + '/zombie/video_jane_2.mp4');
					}

					scoreBeforeDashboardCompanyInvestigate = parameters.scoreBeforeDashboardCompanyInvestigate;

					// событие уже сработало
					companyInvestigateFromSocket();
				} else if (op == 'dashboardCoordinatesLatitude1Keyup') { // ввод символов при вводе dashboard - coordinates
					if ($('.dashboard_tab_content_item_geo_coordinates_latitude1_input').length) {
						$('.dashboard_tab_content_item_geo_coordinates_latitude1_input').val(parameters.value);
					}
				} else if (op == 'dashboardCoordinatesLatitude2Keyup') { // ввод символов при вводе dashboard - coordinates
					if ($('.dashboard_tab_content_item_geo_coordinates_latitude2_input').length) {
						$('.dashboard_tab_content_item_geo_coordinates_latitude2_input').val(parameters.value);
					}
				} else if (op == 'dashboardCoordinatesLatitude3Keyup') { // ввод символов при вводе dashboard - coordinates
					if ($('.dashboard_tab_content_item_geo_coordinates_latitude3_input').length) {
						$('.dashboard_tab_content_item_geo_coordinates_latitude3_input').val(parameters.value);
					}
				} else if (op == 'dashboardCoordinatesLongitude1Keyup') { // ввод символов при вводе dashboard - coordinates
					if ($('.dashboard_tab_content_item_geo_coordinates_longitude1_input').length) {
						$('.dashboard_tab_content_item_geo_coordinates_longitude1_input').val(parameters.value);
					}
				} else if (op == 'dashboardCoordinatesLongitude2Keyup') { // ввод символов при вводе dashboard - coordinates
					if ($('.dashboard_tab_content_item_geo_coordinates_longitude2_input').length) {
						$('.dashboard_tab_content_item_geo_coordinates_longitude2_input').val(parameters.value);
					}
				} else if (op == 'dashboardCoordinatesLongitude3Keyup') { // ввод символов при вводе dashboard - coordinates
					if ($('.dashboard_tab_content_item_geo_coordinates_longitude3_input').length) {
						$('.dashboard_tab_content_item_geo_coordinates_longitude3_input').val(parameters.value);
					}
				} else if (op == 'dashboardCoordinatesNoEmptyFields') { // dashboard - coordinates, нет пустых полей, отображаем попап database search
					dashboardCoordinatesSubmit(parameters.latitude1, parameters.latitude2, parameters.latitude3, parameters.longitude1, parameters.longitude2, parameters.longitude3, parameters.lang_abbr);
				} else if (op == 'dashboardCoordinatesEmptyFields') { // dashboard - coordinates, есть пустые поля
					if ($('.dashboard_tab_content_item_geo_coordinates_input_wrapper_row_latitude .dashboard_tab_content_item_geo_coordinates_input_error').length) {
						if (parameters.latitude1_error || parameters.latitude2_error || parameters.latitude3_error) {
							$('.dashboard_tab_content_item_geo_coordinates_input_wrapper_row_latitude .dashboard_tab_content_item_geo_coordinates_input_error').addClass('error_text_database_car_register_active');
						} else {
							$('.dashboard_tab_content_item_geo_coordinates_input_wrapper_row_latitude .dashboard_tab_content_item_geo_coordinates_input_error').removeClass('error_text_database_car_register_active');
						}
					}

					if ($('.dashboard_tab_content_item_geo_coordinates_input_wrapper_row_longitude .dashboard_tab_content_item_geo_coordinates_input_error').length) {
						if (parameters.longitude1_error || parameters.longitude2_error || parameters.longitude3_error) {
							$('.dashboard_tab_content_item_geo_coordinates_input_wrapper_row_longitude .dashboard_tab_content_item_geo_coordinates_input_error').addClass('error_text_database_car_register_active');
						} else {
							$('.dashboard_tab_content_item_geo_coordinates_input_wrapper_row_longitude .dashboard_tab_content_item_geo_coordinates_input_error').removeClass('error_text_database_car_register_active');
						}
					}
				} else if (op == 'dashboardCoordinatesCloseSuccessPopupAndOpenOutgoingCall') { // dashboard - coordinates - закрыть попап при правильном вводе данных и открыть попап входящего звонка
					// закрыть попап с успешным выполнением
					$('#popup_success_pollution').removeClass('popup_success_pollution_geo_coordinates').fadeOut(200);

					if (successAudio && isPlaying(successAudio)) {
						successAudio.pause();
					}

					// открываем попап исходящего звонка
					geoCoordinatesOpenOutgoingCall();
				} else if (op == 'dashboardCoordinatesCallAnswer') { // dashboard - coordinates - ввели верно, принять входящий звонок
					// запускаем фоновую музыку, если была
					if ($('.music_on').length && $('.music_on').hasClass('music_active')) {
						playMusic();
					}

					// останавливаем звук звонка
					if (incomingMusicTimer) {
						clearInterval(incomingMusicTimer);
						incomingMusicTimer = false;

						if (incomingAudio && isPlaying(incomingAudio)) {
							incomingAudio.pause();
						}
					}

					// останавливаем обновление времени
					if (incomingCallTimer) {
						clearInterval(incomingCallTimer);
						incomingCallTimer = false;
					}

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
				} else if (op == 'closePopupVideoAndCoordinatesSuccess') { // dashboard - coordinates. Видео доиграло до конца
					closePopupVideo();

					scoreBeforeDashboardCoordinates = parameters.scoreBeforeDashboardCoordinates;

					// событие уже сработало
					geoCoordinatesFromSocket();
				} else if (op == 'dashboardCoordinatesCloseIncomingCall') { // dashboard - coordinates - закрыть попап входящего звонка
					dashboardCoordinatesCloseIncomingCall();
				} else if (op == 'stopVideoAndClosePopupVideoAndCoordinatesSuccess') { // dashboard - coordinates. Закрыть попап с видео
					stopVideo();
					closePopupVideo();
					// stopVideoCall();
					// closePopupVideoCall();

					if (questionEndVideo) {
						$('#popup_end_video_question').css('display','block').attr('video-url', '/zombie/video/' + $('html').attr('lang') + '/zombie/video_jane_3.mp4');
					}

					scoreBeforeDashboardCoordinates = parameters.scoreBeforeDashboardCoordinates;

					// событие уже сработало
					geoCoordinatesFromSocket();
				} else if (op == 'dashboardAfricanPartnerKeyupCompanyName') { // ввод символов при вводе dashboard - african partner
					if ($('.dashboard_african_partner_company_name').length) {
						$('.dashboard_african_partner_company_name').val(parameters.company_name);
					}
				} else if (op == 'dashboardAfricanPartnerUpdateCountry') { // dashboard - african partner - выбираем значение в Country
					if ($('.dashboard_african_partner_country').length && $('.dashboard_african_partner_country').val() != parameters.country_lang[$('html').attr('lang')]) {
						$('.dashboard_african_partner_country').val(parameters.country_lang[$('html').attr('lang')]).change().selectric('refresh');
					}
				} else if (op == 'dashboardAfricanPartnerUpdateDate') { // dashboard - african partner - выбираем дату
					if ($('.dashboard_african_partner_date').length && $('.dashboard_african_partner_date').val() != parameters.date) {
						$('.dashboard_african_partner_date').val(parameters.date);
					}
				} else if (op == 'dashboardAfricanPartnerNoEmptyFields') { // dashboard - african partner, нет пустых полей, отображаем попап database search
					africanPartnerSubmit(parameters.company_name, parameters.country, parameters.date, parameters.lang_abbr);
				} else if (op == 'dashboardAfricanPartnerEmptyFields') { // dashboard - african partner, есть пустые поля
					if ($('.dashboard_african_partner_company_name_error').length) {
						if (parameters.company_name_error) {
							$('.dashboard_african_partner_company_name_error').addClass('error_text_database_car_register_active');
						} else {
							$('.dashboard_african_partner_company_name_error').removeClass('error_text_database_car_register_active');
						}
					}

					if ($('.dashboard_african_partner_country_error').length) {
						if (parameters.country_error) {
							$('.dashboard_african_partner_country_error').addClass('error_text_database_car_register_active');
						} else {
							$('.dashboard_african_partner_country_error').removeClass('error_text_database_car_register_active');
						}
					}

					if ($('.dashboard_african_partner_date_error').length) {
						if (parameters.date_error) {
							$('.dashboard_african_partner_date_error').addClass('error_text_database_car_register_active');
						} else {
							$('.dashboard_african_partner_date_error').removeClass('error_text_database_car_register_active');
						}
					}
				} else if (op == 'dashboardAfricanPartnerCloseSuccessPopupAndOpenOutgoingCall') { // dashboard - african partner - закрыть попап при правильном вводе данных и открыть попап входящего звонка
					// закрыть попап с успешным выполнением
					$('#popup_success').removeClass('popup_success_african_partner').fadeOut(200);

					// открываем попап входящего звонка
					africanPartnerOpenOutgoingCall();
				} else if (op == 'dashboardAfricanPartnerCallAnswer') { // dashboard - african partner - ввели верно, принять входящий звонок
					// запускаем фоновую музыку, если была
					if ($('.music_on').length && $('.music_on').hasClass('music_active')) {
						playMusic();
					}

					// останавливаем звук звонка
					if (incomingMusicTimer) {
						clearInterval(incomingMusicTimer);
						incomingMusicTimer = false;

						if (incomingAudio && isPlaying(incomingAudio)) {
							incomingAudio.pause();
						}
					}

					// останавливаем обновление времени
					if (incomingCallTimer) {
						clearInterval(incomingCallTimer);
						incomingCallTimer = false;
					}

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
					openFileVideoPopup(0, 'video/' + $('html').attr('lang') + '/zombie/video_jane_4.mp4', '', 'african_partner_answer_incoming_video', 'call');
					playVideo('call');
					// openFileVideoPopupCall(0, 'video/' + $('html').attr('lang') + '/zombie/video_jane_4.mp4', '', 'african_partner_answer_incoming_video', 'call_jane');
					// playVideoCall();
				} else if (op == 'closePopupVideoAndAfricanPartnerSuccess') { // dashboard - african partner. Видео доиграло до конца
					// closePopupVideo();
					closePopupVideoCall();

					scoreBeforeDashboardAfricanPartner = parameters.scoreBeforeDashboardAfricanPartner;

					// событие уже сработало
					africanPartnerFromSocket();
				} else if (op == 'updateChatMessages') { // обновить сообщения в чатботе
					updateChatMessages(false);
				} else if (op == 'dashboardAfricanPartnerCloseIncomingCall') { // dashboard - african partner - закрыть попап входящего звонка
					africanPartnerCloseIncomingCall();
				} else if (op == 'stopVideoAndClosePopupVideoAndAfricanPartnerSuccess') { // dashboard - african partner. Закрыть попап с видео
					stopVideo();
					closePopupVideo();
					// stopVideoCall();
					// closePopupVideoCall();

					if (questionEndVideo) {
						$('#popup_end_video_question').css('display','block').attr('video-url', '/zombie/video/' + $('html').attr('lang') + '/zombie/video_jane_4.mp4');
					}

					scoreBeforeDashboardAfricanPartner = parameters.scoreBeforeDashboardAfricanPartner;

					// событие уже сработало
					africanPartnerFromSocket();
				} else if (op == 'databasesBankTransactionsKeyupDigits') { // ввод символов при вводе databases - Bank Transactions - digits
					if ($('.dashboard_bank_transactions1_digits').length) {
						$('.dashboard_bank_transactions1_digits').val(parameters.digits);
					}
				} else if (op == 'databasesBankTransactionsKeyupAmount') { // ввод символов при вводе databases - Bank Transactions - amount
					if ($('.dashboard_bank_transactions1_amount').length) {
						$('.dashboard_bank_transactions1_amount').val(parameters.amount);
					}
				} else if (op == 'databasesBankTransactionsNoEmptyFields') { // databases - Bank Transactions, нет пустых полей, отображаем попап database search
					scoreBeforeDatabasesBankTransactions = parameters.scoreBeforeDatabasesBankTransactions;

					bankTransactionsSubmit(parameters.digits, parameters.amount, parameters.date, parameters.lang_abbr, false);
				} else if (op == 'databasesBankTransactionsEmptyFields') { // databases - Bank Transactions, есть пустые поля
					if ($('.dashboard_bank_transactions1_digits_error').length) {
						if (parameters.digits_error) {
							$('.dashboard_bank_transactions1_digits_error').addClass('error_text_database_car_register_active');
						} else {
							$('.dashboard_bank_transactions1_digits_error').removeClass('error_text_database_car_register_active');
						}
					}

					if ($('.dashboard_bank_transactions1_amount_error').length) {
						if (parameters.amount_error) {
							$('.dashboard_bank_transactions1_amount_error').addClass('error_text_database_car_register_active');
						} else {
							$('.dashboard_bank_transactions1_amount_error').removeClass('error_text_database_car_register_active');
						}
					}

					if ($('.dashboard_bank_transactions1_date_error').length) {
						if (parameters.date_error) {
							$('.dashboard_bank_transactions1_date_error').addClass('error_text_database_car_register_active');
						} else {
							$('.dashboard_bank_transactions1_date_error').removeClass('error_text_database_car_register_active');
						}
					}
				} else if (op == 'databasesBankTransactionsUpdateDate') { // databases - Bank Transactions - выбираем дату
					if ($('.dashboard_bank_transactions1_date').length && $('.dashboard_bank_transactions1_date').val() != parameters.date) {
						$('.dashboard_bank_transactions1_date').val(parameters.date);
					}
				} else if (op == 'viewChatFormMessageHidden') { // скрыть поле для ввода данных в переписке с ботом
					$('.chat_form').css('display', 'none');
				} else if (op == 'dashboardMettingPlaceUpdateCountry') { // dashhboard - metting place - выбираем значение в Country
					if ($('.dashboard_metting_place_country').length && $('.dashboard_metting_place_country').val() != parameters.country_lang[$('html').attr('lang')]) {
						$('.dashboard_metting_place_country').val(parameters.country_lang[$('html').attr('lang')]).change().selectric('refresh');
					}
				} else if (op == 'dashboardMettingPlaceKeyupStreet') { // ввод символов при вводе dashboard - metting place - street
					if ($('.dashboard_metting_place_street_name').length) {
						$('.dashboard_metting_place_street_name').val(parameters.street);
					}
				} else if (op == 'dashboardMettingPlaceKeyupHouse') { // ввод символов при вводе dashboard - metting place - house
					if ($('.dashboard_metting_place_house_number').length) {
						$('.dashboard_metting_place_house_number').val(parameters.house);
					}
				} else if (op == 'dashboardMettingPlaceKeyupCity') { // ввод символов при вводе dashboard - metting place - city
					if ($('.dashboard_metting_place_city').length) {
						$('.dashboard_metting_place_city').val(parameters.city);
					}
				} else if (op == 'dashboardMettingPlaceNoEmptyFields') { // dashboard - metting place, нет пустых полей, отображаем попап database search
					mettingPlaceSubmit(parameters.street_name, parameters.house_number, parameters.city, parameters.country, parameters.lang_abbr);
				} else if (op == 'dashboardMettingPlaceEmptyFields') { // dashboard - metting place, есть пустые поля
					if ($('.dashboard_metting_place_street_name_error').length) {
						if (parameters.street_name_error) {
							$('.dashboard_metting_place_street_name_error').addClass('error_text_database_car_register_active');
						} else {
							$('.dashboard_metting_place_street_name_error').removeClass('error_text_database_car_register_active');
						}
					}

					if ($('.dashboard_metting_place_house_number_error').length) {
						if (parameters.house_number_error) {
							$('.dashboard_metting_place_house_number_error').addClass('error_text_database_car_register_active');
						} else {
							$('.dashboard_metting_place_house_number_error').removeClass('error_text_database_car_register_active');
						}
					}

					if ($('.dashboard_metting_place_city_error').length) {
						if (parameters.city_error) {
							$('.dashboard_metting_place_city_error').addClass('error_text_database_car_register_active');
						} else {
							$('.dashboard_metting_place_city_error').removeClass('error_text_database_car_register_active');
						}
					}

					if ($('.dashboard_metting_place_country_error').length) {
						if (parameters.country_error) {
							$('.dashboard_metting_place_country_error').addClass('error_text_database_car_register_active');
						} else {
							$('.dashboard_metting_place_country_error').removeClass('error_text_database_car_register_active');
						}
					}
				} else if (op == 'dashboardMettingPlaceCloseSuccessPopupAndOpenOutgoingCall') { // dashboard - metting place - закрыть попап при правильном вводе данных и открыть попап входящего звонка
					// закрыть попап с успешным выполнением
					$('#popup_success').removeClass('popup_success_metting_place').fadeOut(200);

					// открываем попап входящего звонка
					mettingPlaceOpenOutgoingCall();
				} else if (op == 'dashboardMettingPlaceCallAnswer') { // dashboard - metting place - ввели верно, принять входящий звонок
					// запускаем фоновую музыку, если была
					if ($('.music_on').length && $('.music_on').hasClass('music_active')) {
						playMusic();
					}

					// останавливаем звук звонка
					if (incomingMusicTimer) {
						clearInterval(incomingMusicTimer);
						incomingMusicTimer = false;

						if (incomingAudio && isPlaying(incomingAudio)) {
							incomingAudio.pause();
						}
					}

					// останавливаем обновление времени
					if (incomingCallTimer) {
						clearInterval(incomingCallTimer);
						incomingCallTimer = false;
					}

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
					// openFileVideoPopup(0, 'video/' + $('html').attr('lang') + '/zombie/video_jane_5.mp4', '', 'metting_place_answer_incoming_video', 'call');
					// playVideo('call');
					openFileVideoPopupCall(0, 'video/' + $('html').attr('lang') + '/zombie/video_jane_5.mp4', '', 'metting_place_answer_incoming_video', 'call_jane');
					playVideoCall();
				} else if (op == 'closePopupVideoAndMettingPlaceSuccess') { // dashboard - metting place. Видео доиграло до конца
					// closePopupVideo();
					closePopupVideoCall();

					scoreBeforeDashboardMettingPlace = parameters.scoreBeforeDashboardMettingPlace;

					// событие уже сработало
					mettingPlaceFromSocket();
				} else if (op == 'dashboardMettingPlaceCloseIncomingCall') { // dashboard - metting place - закрыть попап входящего звонка
					mettingPlaceCloseIncomingCall();
				} else if (op == 'stopVideoAndClosePopupVideoAndMettingPlaceSuccess') { // dashboard - metting place. Закрыть попап с видео
					// stopVideo();
					// closePopupVideo();
					stopVideoCall();
					closePopupVideoCall();

					if (questionEndVideo) {
						$('#popup_end_video_question').css('display','block').attr('video-url', '/zombie/video/' + $('html').attr('lang') + '/zombie/video_jane_5.mp4');
					}

					scoreBeforeDashboardMettingPlace = parameters.scoreBeforeDashboardMettingPlace;

					// событие уже сработало
					mettingPlaceFromSocket();
				} else if (op == 'toolsScanChangeValueDegree') { // tools scan, градус "спидометра"
					// стрелка
					updateGaugeValueLoadPage(parameters.value);

					// пишем текст
					$('.dashboard_tools_3d_scan_inner_main_left_value').html(parameters.value + 'k').attr('data-value', parameters.value);
					$('.dashboard_tools_3d_scan_inner_main_left_gauge_cur_value span').html(parameters.value + 'k');
				} else if (op == 'toolsScanChangeValueNumber') { // tools scan, цифры
					if ($('.' + parameters.fieldClass).length) {
						$('.' + parameters.fieldClass).val(parameters.value);
					}
				} else if (op == 'toolsScanChangeValueDots') { // tools scan, точки
					if ($('.dashboard_tools_3d_scan_inner_main_right_parameter_scale_dots[data-field="' + parameters.field + '"]').length) {
						var wrapperDots = $('.dashboard_tools_3d_scan_inner_main_right_parameter_scale_dots[data-field="' + parameters.field + '"]');

						wrapperDots.find('.dashboard_tools_3d_scan_inner_main_right_parameter_scale_dot').removeClass('dashboard_tools_3d_scan_inner_main_right_parameter_scale_dot_active').removeClass('dashboard_tools_3d_scan_inner_main_right_parameter_scale_dot_before_active');

						wrapperDots.find('.dashboard_tools_3d_scan_inner_main_right_parameter_scale_dot').each(function(index, element) {
							if (index < parameters.value) {
								$(element).addClass('dashboard_tools_3d_scan_inner_main_right_parameter_scale_dot_before_active');
							} else if (index == parameters.value) {
								$(element).addClass('dashboard_tools_3d_scan_inner_main_right_parameter_scale_dot_active');
							}
						});
					}
				} else if (op == 'toolsScanChangeValueCheckbox') { // tools scan, чекбоксы
					if ($('.dashboard_tools_3d_scan_inner_main_right_parameter_checkbox input[data-field="' + parameters.field + '"]').length) {
						if (parameters.value == 1) {
							$('.dashboard_tools_3d_scan_inner_main_right_parameter_checkbox input[data-field="' + parameters.field + '"]').prop('checked', true);
						} else {
							$('.dashboard_tools_3d_scan_inner_main_right_parameter_checkbox input[data-field="' + parameters.field + '"]').prop('checked', false);
						}
					}
				} else if (op == 'toolsScanSubmit') { // tools scan, сканируем
					scoreBeforeToolsScan = parameters.scoreBeforeToolsScan;

					toolsScanSubmit(parameters.degree, parameters.input1, parameters.input2, parameters.input3, parameters.input4, parameters.input5, parameters.dot_n_index, parameters.dot_s_index, parameters.dot_e_index, parameters.dot_w_index, parameters.checkbox_n, parameters.checkbox_s, parameters.checkbox_e, parameters.checkbox_w, parameters.lang_abbr, true);
				} else if (op == 'dashboardRoomNameKeyup') { // ввод символов при вводе dashboard - room name
					if ($('.dashboard_room_name_room_name').length) {
						$('.dashboard_room_name_room_name').val(parameters.room_name);
					}
				} else if (op == 'dashboardRoomNameNoEmptyFields') { // dashboard - room name, нет пустых полей, отображаем попап database search
					roomNameSubmit(parameters.room_name, parameters.lang_abbr);
				} else if (op == 'dashboardRoomNameEmptyFields') { // dashboard - room name, есть пустые поля
					if ($('.dashboard_room_name_room_name_error').length) {
						if (parameters.room_name_error) {
							$('.dashboard_room_name_room_name_error').addClass('error_text_database_car_register_active');
						} else {
							$('.dashboard_room_name_room_name_error').removeClass('error_text_database_car_register_active');
						}
					}
				} else if (op == 'dashboardRoomNameCallAnswer') { // dashboard - room name - ввели верно, принять входящий звонок
					// запускаем фоновую музыку, если была
					if ($('.music_on').length && $('.music_on').hasClass('music_active')) {
						playMusic();
					}

					// останавливаем звук звонка
					if (incomingMusicTimer) {
						clearInterval(incomingMusicTimer);
						incomingMusicTimer = false;

						if (incomingAudio && isPlaying(incomingAudio)) {
							incomingAudio.pause();
						}
					}

					// останавливаем обновление времени
					if (incomingCallTimer) {
						clearInterval(incomingCallTimer);
						incomingCallTimer = false;
					}

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
					// openFileVideoPopup(0, 'video/' + $('html').attr('lang') + '/zombie/video_jane_6.mp4', '', 'room_name_answer_incoming_video', 'call');
					// playVideo('call');
					openFileVideoPopupCall(0, 'video/' + $('html').attr('lang') + '/zombie/video_jane_6.mp4', '', 'room_name_answer_incoming_video', 'call_jane');
					playVideoCall();
				} else if (op == 'closePopupVideoAndRoomNameSuccess') { // dashboard - room name. Видео доиграло до конца
					// closePopupVideo();
					closePopupVideoCall();

					scoreBeforeDashboardRoomName = parameters.scoreBeforeDashboardRoomName;

					// событие уже сработало
					roomNameFromSocket();
				} else if (op == 'dashboardRoomNameCloseIncomingCall') { // dashboard - room name - закрыть попап входящего звонка
					roomNameCloseIncomingCall();
				} else if (op == 'stopVideoAndClosePopupVideoAndRoomNameSuccess') { // dashboard - room name. Закрыть попап с видео
					// stopVideo();
					// closePopupVideo();
					stopVideoCall();
					closePopupVideoCall();

					if (questionEndVideo) {
						$('#popup_end_video_question').css('display','block').attr('video-url', '/zombie/video/' + $('html').attr('lang') + '/zombie/video_jane_6.mp4');
					}

					scoreBeforeDashboardRoomName = parameters.scoreBeforeDashboardRoomName;

					// событие уже сработало
					roomNameFromSocket();
				} else if (op == 'minigameUpdateObjects') { // minigame. Нажали на стрелку и сдвигаем объекты
					updateObjects(parameters.objects);
				} else if (op == 'minigamePositionsError') { // minigame. пришли в тупик. Открываем попап
					/*// убрать звук с видео на странице, если был активен
					if ($('.content_minigame_video_mute').hasClass('content_minigame_video_mute_active')) {
						$('.content_minigame_video_mute').removeClass('content_minigame_video_mute_active');
    					$('.content_minigame_video video').prop('muted', 1);

    					minigameVideoMute = true;
					}

					// открыть видео и сразу запустить его
					playVideoByNotControls = true; // указываем, что запускалось через кнопку Play, а не через Controls
					openFileVideoPopup(0, 'video/' + $('html').attr('lang') + '/zombie/video_jane_6.mp4', '', 'minigame_error_video', 'file');
					playVideo('call');*/
					// отображаем попап ошибки
					$('#popup_search_error').addClass('popup_search_error_minigame');

					$('#popup_search_error .popup_search_error_input').html(parameters.error_lang[$('html').attr('lang')].error_input);
					$('#popup_search_error .popup_search_error_text').html(parameters.error_lang[$('html').attr('lang')].error_text);
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
				} else if (op == 'minigamePositionsErrorAfterPopupClose') { // minigame. пришли в тупик. Закрыть попап
					$('#popup_search_error').fadeOut(200);
					
					// убрать звук с видео на странице, если был активен
					if ($('.content_minigame_video_mute').hasClass('content_minigame_video_mute_active')) {
						$('.content_minigame_video_mute').removeClass('content_minigame_video_mute_active');
						$('.content_minigame_video video').prop('muted', 1);

						minigameVideoMute = true;
					}

					// открыть видео и сразу запустить его
					playVideoByNotControls = true; // указываем, что запускалось через кнопку Play, а не через Controls
					// openFileVideoPopup(0, 'video/' + $('html').attr('lang') + '/minigame_lose.mp4', '', 'minigame_error_video', 'file');
					// playVideo('call');
					openFileVideoPopupCall(0, 'video/' + $('html').attr('lang') + '/minigame_lose.mp4', '', 'minigame_error_video', 'call_jane');
					playVideoCall();

					// Текущее к-во проиграшей
					var minigameLoseInCookie = WFCookie.get('wf-minigame-lose-team-' + $('#section_game').attr('data-team-id'));

					// Закрыть видео после 3 секунд. Третье видео и далее
					if (minigameLoseInCookie !== null) {
						if (parseInt(minigameLoseInCookie, 10) > 2) {
							setTimeout(function(){
								$('#popup_video_phone_video .popup_video_close').trigger('click');
							}, 3000);
						}
					}

					// Обновляем к-во проиграшей
					if (minigameLoseInCookie === null) {
						minigameLoseInCookie = 1;
					}

					minigameLoseInCookie++;

					var date = new Date(Date.now() + 24 * 365 * 60 * 60 * 1000); // Year
			    	var options = { expires: date };
			    	WFCookie.set('wf-minigame-lose-team-' + $('#section_game').attr('data-team-id'), minigameLoseInCookie, options);
				} else if (op == 'minigameOpenOutgoingCall') { // minigame. Дошли до последнего шага
					minigameOpenOutgoingCall();
				} else if (op == 'closePopupVideoMinigameError') { // minigame. Видео с ошибкой доиграло до конца
					// closePopupVideo();
					closePopupVideoCall();
					uploadMinigamePositionsStart();
				} else if (op == 'closeAndStopPopupVideoMinigameError') { // minigame. закрыть попап с видео об ошибке
					// stopVideo();
					// closePopupVideo();
					stopVideoCall();
					closePopupVideoCall();

					uploadMinigamePositionsStart();
				} else if (op == 'minigameCallAnswer') { // minigame, принять входящий звонок
					// запускаем фоновую музыку, если была
					if ($('.music_on').length && $('.music_on').hasClass('music_active')) {
						playMusic();
					}

					// останавливаем звук звонка
					if (incomingMusicTimer) {
						clearInterval(incomingMusicTimer);
						incomingMusicTimer = false;

						if (incomingAudio && isPlaying(incomingAudio)) {
							incomingAudio.pause();
						}
					}

					// останавливаем обновление времени
					if (incomingCallTimer) {
						clearInterval(incomingCallTimer);
						incomingCallTimer = false;
					}

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
					// openFileVideoPopup(0, 'video/' + $('html').attr('lang') + '/zombie/video_jane_7.mp4', '', 'minigame_answer_incoming_video', 'call');
					openFileVideoPopupCall(0, 'video/' + $('html').attr('lang') + '/zombie/video_jane_7.mp4', '', 'minigame_answer_incoming_video', 'call');
					// playVideo('call');
					playVideoCall();
				} else if (op == 'closePopupVideoAndMinigameSuccess') { // minigame. Видео доиграло до конца
					// closePopupVideo();
					closePopupVideoCall();

					// scoreBeforeMinigame = parameters.scoreBeforeMinigame;

					// открываем попап Alarm
					minigameSuccess();
				} else if (op == 'minigameCloseIncomingCall') { // minigame - закрыть попап входящего звонка
					minigameCloseIncomingCall();
				} else if (op == 'stopVideoAndClosePopupVideoAndMinigameSuccess') { // minigame. Закрыть попап с видео
					// stopVideo();
					// closePopupVideo();
					stopVideoCall();
					closePopupVideoCall();

					if (questionEndVideo) {
						$('#popup_end_video_question').css('display','block').attr('video-url', '/zombie/video/' + $('html').attr('lang') + '/zombie/video_jane_7.mp4');
					}

					// событие уже сработало
					minigameSuccess();
				} else if (op == 'ArrestSuccess') { // Финальное видео - закрыть попап
					stopVideo();
					closePopupVideo();

					// победная музыка
					finishAudio = new Audio;
					finishAudio.src = '/music/winner.mp3';
					// finishAudio.play();

					// Autoplay
					var promise = finishAudio.play();

					if (promise !== undefined) {
						promise.then(_ => {
							// console.log('autoplay');
						}).catch(error => {
							// console.log('autoplay ERR');
						});
					}

					// отображаем победный попап
					if (!is_touch_device()) {
						var pageSize = getPageSize();
			    		var windowWidth = pageSize[2];
			    		var windowHeight = pageSize[1];
			    		if (windowWidth < 1800) {
			    			$('body').removeClass('body_desktop_scale').css('transform', 'scale(1)');

			    			var pageSize = getPageSize();

			    			var koef = parseFloat((windowWidth / 1920).toFixed(2)) + 0.01;

			    			$('#popup_mission_complete').css({
			    				'display': 'block',
			    				'height': windowHeight + 'px'
			    			});
							// $.scrollLock(true);

							$('body').addClass('body_desktop_scale').css('transform', 'scale(' + koef + ')');
			    		} else {
							$('#popup_mission_complete').css('display','block');
							// $.scrollLock(true);
						}
					} else {
						$('#popup_mission_complete').css('display','block');
						// $.scrollLock(true);
					}
				} else if (op == 'stopVideoAndArrestSuccess') { // Финальное видео. Видео доиграло до конца
					closePopupVideo();

					if (questionEndVideo) {
						$('#popup_end_video_question').css('display','block').attr('video-url', '/zombie/video/' + $('html').attr('lang') + '/arrest.mp4');
					}

					// победная музыка
					finishAudio = new Audio;
					finishAudio.src = '/music/winner.mp3';
					// finishAudio.play();

					// Autoplay
					var promise = finishAudio.play();

					if (promise !== undefined) {
						promise.then(_ => {
							// console.log('autoplay');
						}).catch(error => {
							// console.log('autoplay ERR');
						});
					}

					// отображаем победный попап
					if (!is_touch_device()) {
						var pageSize = getPageSize();
			    		var windowWidth = pageSize[2];
			    		var windowHeight = pageSize[1];
			    		if (windowWidth < 1800) {
			    			$('body').removeClass('body_desktop_scale').css('transform', 'scale(1)');

			    			var pageSize = getPageSize();

			    			var koef = parseFloat((windowWidth / 1920).toFixed(2)) + 0.01;

			    			$('#popup_mission_complete').css({
			    				'display': 'block',
			    				'height': windowHeight + 'px'
			    			});
							// $.scrollLock(true);

							$('body').addClass('body_desktop_scale').css('transform', 'scale(' + koef + ')');
			    		} else {
							$('#popup_mission_complete').css('display','block');
							// $.scrollLock(true);
						}
					} else {
						$('#popup_mission_complete').css('display','block');
						// $.scrollLock(true);
					}
				} else if (op == 'minigameAlarmSubmit') { // minigame - нажали на кнопку ОК в попапе Alarm
					scoreBeforeMinigame = parameters.scoreBeforeMinigame;

					// останавливаем обновление времени
					if (alarmTimer) {
						clearInterval(alarmTimer);
						alarmTimer = false;
					}

					// останавливаем звук, если он еще активен
					if (alarmAudio && isPlaying(alarmAudio)) {
						alarmAudio.pause();
					}

					// запускаем фоновую музыку
					if ($('.music_on').length && $('.music_on').hasClass('music_active')) {
						playMusic();
					}

					// добавляем очки
					incrementScoreWithoutSaveDb(scoreBeforeMinigame + 150, 'main', scoreBeforeMinigame);

					// обновляем mission progress
					incrementProgressMissionWithoutSaveDb(5);

					// обновляем содержимое dashboard
					uploadTypeTabsDashboardStep('password', false);

					// скрыть попап Alarm
					$('#popup_minigame_alarm').css('display','none');

					// закрываем экран с миниигрой и открываем основной экран игры
					hiddenHintWindow();
					hiddenMiniGameWindow();
					openMainGameWindow();
				} else if (op == 'dashboardPasswordKeyup') { // ввод символов при вводе dashboard - password
					if ($('.dashboard_password_password').length) {
						$('.dashboard_password_password').val(parameters.password);
					}
				} else if (op == 'dashboardPasswordNoEmptyFields') { // dashboard - password, нет пустых полей, отображаем попап database search
					dashboardPasswordSubmit(parameters.password, parameters.lang_abbr);
				} else if (op == 'dashboardPasswordEmptyFields') { // dashboard - password, есть пустые поля
					if ($('.dashboard_password_password_error').length) {
						if (parameters.password_error) {
							$('.dashboard_password_password_error').addClass('error_text_database_car_register_active');
						} else {
							$('.dashboard_password_password_error').removeClass('error_text_database_car_register_active');
						}
					}
				} else if (op == 'finishGame') { // окончание игры
					finishGame(parameters.hours, parameters.minute, parameters.second, parameters.score);
				} else if (op == 'callMobileOpen') { // Call mobile. Open popup
					$('#popup_call_mobile').fadeIn(200);

					// Remove one-number from tab title
					$('.dashboard_item[data-dashboard="calls"] .dashboard_item_text_qt').css('display', 'none').html('0');

					$('.dashboard_tabs[data-dashboard="calls"] .call_mobile').removeClass('btn_wrapper_blue_light').addClass('btn_wrapper_blue');
				} else {
					// console.log('uploadGameByActionName-2');
				}
			} else {
				// console.log('uploadGameByActionName-3');
			}
		}
	}
	
	/*

	// Типа сообщение
	socket.onmessage = function(event) {
		var data = JSON.parse(event.data);

		console.log('socket.onmessage - ' + data.op);

		// отображаем актуальные данные по игре
		if ($('#section_game').length) { // страница игры
			// запоминаем в бд
			var formData = new FormData();
	    	formData.append('op', 'addSocketHistory');
	    	formData.append('socket_action', data.op);
	    	formData.append('parameters', JSON.stringify(data.parameters));

	    	$.ajax({
				url: '/ajax/ajax_socket.php',
		        type: "POST",
		        dataType: "json",
		        cache: false,
		        contentType: false,
		        processData: false,
		        data: formData,
				success: function(json) {
					// если подключение. Отображаем текущее состояние игры
					if (data.op == 'newConnectionACK') {
						console.log('new connection');

						// var lastOpenWindow = $('#section_game').attr('data-last-open-window');
						// var isOpenChat = $('#section_game').attr('data-open-chat');
						// var lastTypeTabs = $('#section_game').attr('data-last-type-tabs');

						$.when(getTeamInfo()).done(function(teamResponse){
							var teamInfo = teamResponse.success;
							// console.log(teamInfo);

							if (teamInfo) {
								if (teamInfo.last_window_open == 'hints') {
									// если открыты подсказки
									updateHintWindow();
									hiddenMainGameWindow();
									openHintWindow();
								} else if (teamInfo.last_window_open == 'main') {
									// если открыто основное окно игры
									openMainGameWindow();
								}

								hiddenMainPreloader();

								// активный тип табов
								hiddenAllTypeTabs();

								if (teamInfo.last_type_tabs == 'dashboard') {
									$('.dashboard_tabs[data-dashboard="dashboard"]').addClass('dashboard_tabs_active');
									$('.dashboard_item[data-dashboard="dashboard"]').addClass('dashboard_item_active');

									uploadTypeTabsDashboardStep(teamInfo.last_dashboard);
								} else if (teamInfo.last_type_tabs == 'calls') {
									$('.dashboard_tabs[data-dashboard="calls"]').addClass('dashboard_tabs_active');
									$('.dashboard_item[data-dashboard="calls"]').addClass('dashboard_item_active');

									uploadTypeTabsCallsStep(teamInfo.last_calls);
								} else if (teamInfo.last_type_tabs == 'files') {
									$('.dashboard_tabs[data-dashboard="files"]').addClass('dashboard_tabs_active');
									$('.dashboard_item[data-dashboard="files"]').addClass('dashboard_item_active');

									uploadTypeTabsFilesStep();
								} else if (teamInfo.last_type_tabs == 'databases') {
									$('.dashboard_tabs[data-dashboard="databases"]').addClass('dashboard_tabs_active');
									$('.dashboard_item[data-dashboard="databases"]').addClass('dashboard_item_active');

									uploadTypeTabsDatabasesStep(teamInfo.last_databases);
								} else if (teamInfo.last_type_tabs == 'tools') {
									$('.dashboard_tabs[data-dashboard="tools"]').addClass('dashboard_tabs_active');
									$('.dashboard_item[data-dashboard="tools"]').addClass('dashboard_item_active');

									uploadTypeTabsToolsStep(teamInfo.last_tools);
								}

								// Обновить к-во непрочитанных файлов
								updateDontOpenFilesQt();

								// Обновить к-во неоткрытых баз данных
								updateDontOpenDatabasesQt();

								// Обновить к-во неоткрытых tools
								updateDontOpenToolsQt();

								// окно чата
								if (teamInfo.open_chat == 'yes') {
									updateChatMessages();
									$('.chat').animate({height: '674px'},200);
									$('.btn_wrapper_view_chat').addClass('btn_wrapper_view_chat_active');
								}

								// актуальное к-во очков
								loadScoreActual(teamInfo.score);

								// грузим последнее действие команды
								if (json.socket_action) {
									uploadGameByActionName(json.socket_action, json.parameters);
								}

								// отображение блока с глобусом Gem
								if (teamInfo.view_gem == 1) {
									$('.dashboard_gem_wrapper').addClass('dashboard_gem_wrapper_active');
								}

								// отображение кнопка Call Jane
								if (teamInfo.view_call_jane_btn == 1) {
									$('.call_jane').addClass('call_jane_active');
								}

								// chatPrintFirstMessagesFromBot();
							}
						});
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {	
					console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		} else if ($('#section_join_game').length) { // первая страница
			if (data.op == 'newConnectionACK') { // при загрузке страницы убираем прелоадер
				hiddenMainPreloader();
	    		console.log('new connection - section_join_game');
			}
		}
	}*/

					





					/*// отображаем актуальные данные по игре
					if ($('#section_game').length) { // страница игры
						// если результаты
						if ($('.section_window_results').length) {
							uploadActualHighscore();
							openHighscoreWindow();

							$('.exit').remove();

							hiddenMainPreloader();

							// меняем ссылки в переключателе языков
							if ($('.language_hidden_item[href="/no/game"]').length) {
								$('.language_hidden_item[href="/no/game"]').attr('href', '/no/results');
							} else if ($('.language_hidden_item[href="/game"]').length) {
								$('.language_hidden_item[href="/game"]').attr('href', '/results');
							}
						} else {
							// обнуляем таймер текущей команды в результатах на всякий случай
							if (mainTimer3 !== false) {
								clearInterval(mainTimer3);
								mainTimer3 = false;
							}

							$.when(getTeamInfo()).done(function(teamResponse){
								var teamInfo = teamResponse.success;
								// console.log(teamInfo);

								if (teamInfo) {
									if (teamInfo.last_window_open == 'hints') {
										// если открыты подсказки
										updateHintWindow();
										hiddenMainGameWindow();
										hiddenMiniGameWindow();
										hiddenInterpolWindow();
										hiddenHighscoreWindow();
										openHintWindow();
									} else if (teamInfo.last_window_open == 'main') {
										// если открыто основное окно игры
										hiddenHintWindow();
										hiddenMiniGameWindow();
										hiddenInterpolWindow();
										hiddenHighscoreWindow();
										openMainGameWindow();
									} else if (teamInfo.last_window_open == 'minigame') {
										// если открыто окно миниигры
										hiddenMainGameWindow();
										hiddenHintWindow();
										hiddenInterpolWindow();
										hiddenHighscoreWindow();
										openMiniGameWindow();
									} else if (teamInfo.last_window_open == 'interpol') {
										// если открыто окно interpol
										hiddenMainGameWindow();
										hiddenHintWindow();
										hiddenMiniGameWindow();
										hiddenHighscoreWindow();
										openInterpolWindow();
									} else if (teamInfo.last_window_open == 'highscore') {
										// если открыты результаты
										uploadActualHighscore();
										hiddenHintWindow();
										hiddenMainGameWindow();
										hiddenMiniGameWindow();
										hiddenInterpolWindow();
										openHighscoreWindow();
									}

									hiddenMainPreloader();

									// активный тип табов
									hiddenAllTypeTabs();

									// окно чата
									if (teamInfo.open_chat == 'yes') {
										updateChatMessages();
										$('.chat').animate({height: '674px'},200);
										$('.btn_wrapper_view_chat').addClass('btn_wrapper_view_chat_active');
									}

									// актуальное к-во очков
									loadScoreActual(teamInfo.score);

									// грузим последнее действие команды
									// if (json.socket_action) {
									// 	uploadGameByActionName(json.socket_action, json.parameters);
									// }

									// chatPrintFirstMessagesFromBot();

									// если игра завершена
									if (teamInfo.mission_finish_seconds > 0) {
										var formData = new FormData();
								    	formData.append('op', 'getActiveTimer');

								    	$.ajax({
											url: '/ajax/ajax_timer.php',
									        type: "POST",
									        dataType: "json",
									        cache: false,
									        contentType: false,
									        processData: false,
									        data: formData,
											success: function(json) {
												// обновляем время на главном экране
												$('.timer .timer_hour').html(('0' + json.hours).slice(-2));
												$('.timer .timer_minute').html(('0' + json.minute).slice(-2));
												$('.timer .timer_second').html(('0' + json.second).slice(-2));

												// пишем время в победном попапе
												$('.popup_mission_complete_center_inner_result_hours').html(('0' + json.hours).slice(-2));
												$('.popup_mission_complete_center_inner_result_minutes').html(('0' + json.minute).slice(-2));
												$('.popup_mission_complete_center_inner_result_seconds').html(('0' + json.second).slice(-2));

												// пишем баллы в победном попапе
												$('.popup_mission_complete_center_inner_score .popup_mission_complete_center_inner_result_number').html(teamInfo.score);

												// отображаем победный попап
												$('#popup_mission_complete').css('display','block');
												// $.scrollLock(true);

												if (!is_touch_device()) {
													var pageSize = getPageSize();
								    				var windowWidth = pageSize[2];
								    				var windowHeight = pageSize[1];

								    				if (windowWidth < 1800) {
								    					$('#popup_mission_complete').css('height', windowHeight + 'px');


								    				} else {
								    					$.scrollLock(true);
								    				}
												} else {
													$.scrollLock(true);
												}
											},
											error: function(xhr, ajaxOptions, thrownError) {	
												console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
											}
										});

										// останавливаем отсчет таймер на главном экране
										if (mainTimer !== false) {
											clearInterval(mainTimer);
											mainTimer = false;
										}

										if (mainTimer2 !== false) {
											clearInterval(mainTimer2);
											mainTimer2 = false;
										}
									} else {
										if (teamInfo.last_type_tabs == 'dashboard') {
											$('.dashboard_tabs[data-dashboard="dashboard"]').addClass('dashboard_tabs_active');
											$('.dashboard_item[data-dashboard="dashboard"]').addClass('dashboard_item_active');

											uploadTypeTabsDashboardStep(teamInfo.last_dashboard);
										} else if (teamInfo.last_type_tabs == 'calls') {
											$('.dashboard_tabs[data-dashboard="calls"]').addClass('dashboard_tabs_active');
											$('.dashboard_item[data-dashboard="calls"]').addClass('dashboard_item_active');

											uploadTypeTabsCallsStep(teamInfo.last_calls);
										} else if (teamInfo.last_type_tabs == 'files') {
											$('.dashboard_tabs[data-dashboard="files"]').addClass('dashboard_tabs_active');
											$('.dashboard_item[data-dashboard="files"]').addClass('dashboard_item_active');

											uploadTypeTabsFilesStep();
										} else if (teamInfo.last_type_tabs == 'databases') {
											$('.dashboard_tabs[data-dashboard="databases"]').addClass('dashboard_tabs_active');
											$('.dashboard_item[data-dashboard="databases"]').addClass('dashboard_item_active');

											uploadTypeTabsDatabasesStep(teamInfo.last_databases);
										} else if (teamInfo.last_type_tabs == 'tools') {
											$('.dashboard_tabs[data-dashboard="tools"]').addClass('dashboard_tabs_active');
											$('.dashboard_item[data-dashboard="tools"]').addClass('dashboard_item_active');

											uploadTypeTabsToolsStep(teamInfo.last_tools);
										}

										// Обновить к-во непрочитанных файлов
										updateDontOpenFilesQt();

										// Обновить к-во неоткрытых баз данных
										updateDontOpenDatabasesQt();

										// Обновить к-во неоткрытых tools
										updateDontOpenToolsQt();

										// отображение блока с глобусом Gem
										if (teamInfo.view_gem == 1) {
											$('.dashboard_gem_wrapper').addClass('dashboard_gem_wrapper_active');
										}

										// отображение кнопка Call Jane
										if (teamInfo.view_call_jane_btn == 1) {
											$('.call_jane').addClass('call_jane_active');
										}

										// запустить обновление таймера
										updateTimerUploadPage();
									}
								}
							});
						}
					} else if ($('#section_join_game').length) { // первая страница
						// при загрузке страницы убираем прелоадер
						hiddenMainPreloader();
					}*/
});