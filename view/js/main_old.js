var socket = new WebSocket("ws://185.69.152.94:8090/server_game.php");
var mySocketLastAction = false;

// Доп функция для отправки сообщений. Нужна для того, чтобы дождать подключения
function sendMessageSocket(msg) {
    waitForSocketConnection(socket, function(){
        socket.send(msg);
    });
}

// Доп функция. Переподключение, если еще нет соединения
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
	var music = false; // активны ли фоновая музыка и звуки
	var music_before = false; // активны ли фоновая музыка и звуки. Доп переменная для проигрывания других звуков
	var defaultLoaderLoadingText = 'Loading';
	if ($('html').attr('lang') == 'no') {
		defaultLoaderLoadingText = 'Laster';
	}
	var incomingCallTimer = false; // таймер реального времени при входящем "звонке"
	var incomingMusicTimer = false; // входящий звонок
	var incomingMusicDuration = 12000; // сколько секунд мелодия входящего звонка
	var incomingAudio = false; // переменная для аудио входящего звонка
	var outgoingAudio; // переменная для аудио исходящего звонка
	var scoreAudio; // переменная для аудио изменения баллов
	var searchAudio; // переменная для аудио поиска
	var dataTransferAudio; // переменная для аудио data transfer
	var errorAudio; // переменная для аудио ошибки
	var printAudio; // переменная для аудио печати
	var successAudio; // переменная для аудио при успешном прохождении чего-либо
	var mainTimer = false; // основной таймер. 10-секундный таймер
	var mainTimer2 = false; // основной таймер. 1-секундный внутренний таймер
	var saveTimerEverySecond = 5; // сохранять в бд таймер каждые N секунд
	var outgoingCallTimer = false; // таймер реального времени при исходящем "звонке"
	var incrementScoreDuration = 4000; // время, на протяжении которого насчитываются баллы
	var updatedScoreMain = false; // обновлялись ли очки. Главный экран
	var updatedScoreHints = false; // обновлялись ли очки. Экран с подсказками
	var databaseSearchDuration = 7000; // сколько времени длится поиск по базе данных
	var databaseSearchIteration = 40; // сколько итераций длится поиск по базе данных
	var myLastAction = 26; // мой последний экшн
	var actionTimer = false;
	var playVideoByNotControls = false; // было ли запущено видео через кнопку Play, а НЕ через Controls
	var playVideoSeeking = false; // доп переменная для отслеживания, когда видео перематывается
	var dataTransferIteration = 40; // сколько итераций длится смена цифр в Data Transfer
	var dataTransferMusicDuration = 4500; // 5000; // сколько секунд длится музыка в Data Transfer

	// обнуление результатов
	$('#section_join_game .company_logo').click(function(){
		var formData = new FormData();
		formData.append('op', 'clearTeamResults');

		$.ajax({
			url: '/ajax/ajax.php',
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
	$('#section_join_game .bg_list1').click(function(){
		var formData = new FormData();
		formData.append('op', 'clearTeamResults2');

		$.ajax({
			url: '/ajax/ajax.php',
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

	// для десктоп-экранов применяем масштабирование и пишем некоторые параметры
	if (!is_touch_device()) {
		var pageSize = getPageSize();
        var windowWidth = pageSize[0];
        var windowHeight = pageSize[3];

        if (windowWidth < 1800) {
        	var koef = parseFloat((windowWidth / 1920).toFixed(2)) + 0.01;

        	$('body').addClass('body_desktop_scale').css('transform', 'scale(' + koef + ')');

        	$('#popup_video, #popup_mobile_calls_messages, #popup_video_phone, #popup_video_phone_outgoing, #popup_search_processing, #popup_search_error, #popup_data_transfer, #popup_success, #popup_exit, .fancybox-container').css('height', ($('#main').outerHeight() * parseFloat((1920 / windowWidth).toFixed(2))) + 'px');
        }
	}

/* СОКЕТЫ */
	// Соединение установлено
	socket.onopen = function() {
		console.log('socket.onopen');
		$('#socket_log').prepend('<div>socket.onopen</div>');
	}

	// Ошибка при соединении
	socket.onerror = function(error) {
		console.log('socket.onerror ' + (error.message ? error.message : ""));
		$('#socket_log').prepend('<div>socket.onerror - ' + (error.message ? error.message : "") + '</div>');
	}

	// Соединение закрыто
	socket.onclose = function() {
		// location.reload();
		console.log('socket.onclose');
		// $('#socket_log').prepend('<div>socket.onclose</div>');
	}

	// Типа сообщение
	socket.onmessage = function(event) {
		var data = JSON.parse(event.data);

		console.log('socket.onmessage - ' + data.op);
		$('#socket_log').prepend('<div>socket.onmessage - ' + data.op + '</div>');
		// console.log(data);

		// отображаем изменения. Если не подключение
		console.log('socket.onmessage - ' + data.op + ' - ' + mySocketLastAction);
		if ($('#section_game').length) {
			if (
				data.op != 'newConnectionACK' && (
					mySocketLastAction != data.op || 
					data.op == 'closeFilePdf' || 
					data.op == 'databaseCarregisterSearchLicensePlateKeyup' || 
					data.op == 'acceptMissionKeyup' || 
					data.op == 'databaseCarRegisterUpdateCountry' || 
					data.op == 'databaseCarRegisterUpdateDate' || 
					data.op == 'databaseCarRegisterEmptyFields' || 
					data.op == 'databasePersonalFilesPrivateIndividualFirstnameKeyup' || 
					data.op == 'databasePersonalFilesPrivateIndividualLastnameKeyup' || 
					data.op == 'databasePersonalFilesPrivateIndividualsEmptyFields' || 
					data.op == 'databaseMobileCallsUpdateCountryCode' || 
					data.op == 'databaseMobileCallsNumberKeyup' || 
					data.op == 'databaseMobileCallsEmptyFields' || 
					data.op == 'databasePersonalFilesCeoDatabaseEmptyFields' || 
					data.op == 'dashboardCompanyNameKeyup' || 
					data.op == 'dashboardCompanyNameEmptyFields'
				)
			) {
				uploadGameByActionName(data.op, data.parameters);
			}
		}

		// запоминаем в бд
		if ($('#section_game').length) {
			var formData = new FormData();
	    	formData.append('op', 'addSocketHistory');
	    	formData.append('socket_action', data.op);
	    	formData.append('parameters', JSON.stringify(data.parameters));

	    	$.ajax({
				url: '/ajax/ajax.php',
		        type: "POST",
		        dataType: "json",
		        cache: false,
		        contentType: false,
		        processData: false,
		        data: formData,
				success: function(json) {
					// если подключение. Отображаем текущее состояние игры
					if (data.op == 'newConnectionACK' && $('#section_game').length) {
						console.log('new connection');

						var lastOpenWindow = $('#section_game').attr('data-last-open-window');
						var isOpenChat = $('#section_game').attr('data-open-chat');
						var lastTypeTabs = $('#section_game').attr('data-last-type-tabs');

						if (lastOpenWindow == 'hints') {
							// если открыты подсказки
							updateHintWindow();
							hiddenMainGameWindow();
							openHintWindow();
						} else if (lastOpenWindow == 'main') {
							// если открыто основное окно игры
							openMainGameWindow();
						}

						hiddenMainPreloader();

						if ($('.timer').length) {
							var timerSeconds = parseInt($('.timer').attr('data-timer'), 10);
							if (timerSeconds > 0) {
								// отображаем блок Mission name GEM
								$('.dashboard_gem_wrapper').addClass('dashboard_gem_wrapper_active');

								// обновляем атрибут содержимого calls
								$('#section_game').attr('data-last-open-calls', 'call_list');

								// отображаем блок Call Jane
								$('.call_jane').addClass('call_jane_active');
							}
						}

						// активный тип табов
						hiddenAllTypeTabs();

						if (lastTypeTabs == 'dashboard') {
							$('.dashboard_tabs[data-dashboard="dashboard"]').addClass('dashboard_tabs_active');
							$('.dashboard_item[data-dashboard="dashboard"]').addClass('dashboard_item_active');

							uploadTypeTabsDashboardStep($('#section_game').attr('data-last-open-dashboard'));
						} else if (lastTypeTabs == 'calls') {
							$('.dashboard_tabs[data-dashboard="calls"]').addClass('dashboard_tabs_active');
							$('.dashboard_item[data-dashboard="calls"]').addClass('dashboard_item_active');

							uploadTypeTabsCallsStep($('#section_game').attr('data-last-open-calls'));
						} else if (lastTypeTabs == 'files') {
							$('.dashboard_tabs[data-dashboard="files"]').addClass('dashboard_tabs_active');
							$('.dashboard_item[data-dashboard="files"]').addClass('dashboard_item_active');

							uploadTypeTabsFilesStep();
						} else if (lastTypeTabs == 'databases') {
							$('.dashboard_tabs[data-dashboard="databases"]').addClass('dashboard_tabs_active');
							$('.dashboard_item[data-dashboard="databases"]').addClass('dashboard_item_active');

							uploadTypeTabsDatabasesStep($('#section_game').attr('data-last-open-databases'), false);
						} else if (lastTypeTabs == 'tools') {
							$('.dashboard_tabs[data-dashboard="tools"]').addClass('dashboard_tabs_active');
							$('.dashboard_item[data-dashboard="tools"]').addClass('dashboard_item_active');

							uploadTypeTabsToolsStep($('#section_game').attr('data-last-open-tools'));
						}

						// Обновить к-во непрочитанных файлов
						updateDontOpenFilesQt();

						// Обновить к-во неоткрытых баз данных
						updateDontOpenDatabasesQt();

						// окно чата
						if (isOpenChat == 'yes') {
							updateChatMessages();
							$('.chat').animate({height: '674px'},200);
							$('.btn_wrapper_view_chat').addClass('btn_wrapper_view_chat_active');
						}

						// актуальное к-во очков
						loadScoreActual();

						// грузим последнее действие команды
						if (json.socket_action && (mySocketLastAction != json.socket_action || json.socket_action == 'closeFilePdf' || json.socket_action == 'databaseCarregisterSearchLicensePlateKeyup' || json.socket_action == 'acceptMissionKeyup' || json.socket_action == 'databaseCarRegisterUpdateCountry' || json.socket_action == 'databaseCarRegisterUpdateDate' || json.socket_action == 'databaseCarRegisterEmptyFields' || json.socket_action == 'databasePersonalFilesPrivateIndividualFirstnameKeyup' || json.socket_action == 'databasePersonalFilesPrivateIndividualLastnameKeyup' || json.socket_action == 'databasePersonalFilesPrivateIndividualsEmptyFields' || json.socket_action == 'databaseMobileCallsUpdateCountryCode' || json.socket_action == 'databaseMobileCallsNumberKeyup' || json.socket_action == 'databaseMobileCallsEmptyFields' || json.socket_action == 'databasePersonalFilesCeoDatabaseEmptyFields' || json.socket_action == 'dashboardCompanyNameKeyup' || json.socket_action == 'dashboardCompanyNameEmptyFields')) {
							uploadGameByActionName(json.socket_action, json.parameters);
						}
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {	
					console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
	    }

	    // для первой страницы убираем прелоадер
	    if ($('#section_join_game').length && data.op == 'newConnectionACK') {
	    	hiddenMainPreloader();
	    	console.log('new connection - section_join_game');
	    }
	}

	// одновременное обновление действий команды
	function uploadGameByActionName(op, parameters) {
		if (op == 'openHintWindow') { // открыть окно подсказок
			viewMainPreloader(defaultLoaderLoadingText);

			updateHintWindow();
			hiddenMainGameWindow();
			openHintWindow();
			loadScoreActualHints();

			hiddenMainPreloader();
		} else if (op == 'hiddenHintWindow-openMainGameWindow') { // скрыть окно подсказок и перейти в главное окно игры
			hiddenHintWindow();

			openMainGameWindow();
			loadScoreActualMain();
		} else if (op == 'openTypeTabsDashboard') { // открыть таб - dashboard
			hiddenAllTypeTabs();

			$('.dashboard_tabs[data-dashboard="dashboard"]').addClass('dashboard_tabs_active');
			$('.dashboard_item[data-dashboard="dashboard"]').addClass('dashboard_item_active');

			uploadTypeTabsDashboardStep($('#section_game').attr('data-last-open-dashboard'));
		} else if (op == 'openTypeTabsCalls') { // открыть таб - calls
			hiddenAllTypeTabs();

			$('.dashboard_tabs[data-dashboard="calls"]').addClass('dashboard_tabs_active');
			$('.dashboard_item[data-dashboard="calls"]').addClass('dashboard_item_active');

			uploadTypeTabsCallsStep($('#section_game').attr('data-last-open-calls'));
		} else if (op == 'openTypeTabsFiles') { // открыть таб - files
			hiddenAllTypeTabs();

			$('.dashboard_tabs[data-dashboard="files"]').addClass('dashboard_tabs_active');
			$('.dashboard_item[data-dashboard="files"]').addClass('dashboard_item_active');

			uploadTypeTabsFilesStep();
		} else if (op == 'openTypeTabsDatabases') { // открыть таб - databases
			hiddenAllTypeTabs();

			$('.dashboard_tabs[data-dashboard="databases"]').addClass('dashboard_tabs_active');
			$('.dashboard_item[data-dashboard="databases"]').addClass('dashboard_item_active');

			uploadTypeTabsDatabasesStep($('#section_game').attr('data-last-open-databases'), false);
		} else if (op == 'openTypeTabsTools') { // открыть таб - tools
			hiddenAllTypeTabs();

			$('.dashboard_tabs[data-dashboard="tools"]').addClass('dashboard_tabs_active');
			$('.dashboard_item[data-dashboard="tools"]').addClass('dashboard_item_active');

			uploadTypeTabsToolsStep($('#section_game').attr('data-last-open-tools'));
		} else if (op == 'hiddenHintWindow-openMainGameWindow-openTypeTabsDashboard') { // скрыть окно подсказок, перейти в главное окно игры и открыть таб - dashboard
			hiddenHintWindow();

			openMainGameWindow();
			loadScoreActualMain();

			hiddenAllTypeTabs();

			$('.dashboard_tabs[data-dashboard="dashboard"]').addClass('dashboard_tabs_active');
			$('.dashboard_item[data-dashboard="dashboard"]').addClass('dashboard_item_active');

			uploadTypeTabsDashboardStep($('#section_game').attr('data-last-open-dashboard'));
		} else if (op == 'updateHintWindow') { // обновить список подсказок
			updateHintWindow();
		} else if (op == 'openChatWindow') { // открыть окно чата
			updateChatMessages();
			$('.chat').animate({height: '674px'},200);
			$('.btn_wrapper_view_chat').addClass('btn_wrapper_view_chat_active');
		} else if (op == 'hiddenChatWindow') { // скрыть окно чата
			$('.chat').animate({height: '0px'},200);
			$('.btn_wrapper_view_chat').removeClass('btn_wrapper_view_chat_active');
		} else if (op == 'openFileVideoPopup') { // открыть попап с видео
			openFileVideoPopup(parameters.fileId, parameters.path, parameters.name, parameters.classVideo, parameters.type);
		} else if (op == 'stopVideo') { // остановить воспроизведение видео
			playVideoByNotControls = false; // возвращаем к значению по умолчанию

			if ($('#popup_video').length && $('#popup_video_mp4').length) {
				if ($('.music_on').length && $('.music_on').hasClass('music_active')) {
					playMusic();
				}
				music_before = false;

				var checkedVideo = $('#popup_video_mp4').get(0);
				if (!checkedVideo.paused) {
					checkedVideo.pause();
					$('#popup_video .popup_video_play').css('display','block');
					$('#popup_video .popup_video_stop').css('display','none');

					$('#popup_video .popup_video_btns').css('display','block');
				}
			}
		} else if (op == 'closePopupVideo') { // закрыть попап с видео
			$('#popup_video').css('display','none');

			setTimeout(function(){
				$('#popup_video').removeClass();

				$('#popup_video .popup_video_container_inner').attr('data-file-id', 0);

				$('#popup_video .popup_video_inner_title').html('');
			}, 10);
		} else if (op == 'playVideoFile') { // запустить видео из файлов в попапе
			if ($('#popup_video_mp4').length) {
				music_before = music;
				if ($('.music_on').length && $('.music_on').hasClass('music_active')) {
					stopMusic();
				}

				var checkedVideo = $('#popup_video_mp4').get(0);
				if (checkedVideo.paused) {
					checkedVideo.play();
					$('#popup_video .popup_video_play').css('display','none');
					$('#popup_video .popup_video_stop').css('display','block');

					$('#popup_video .popup_video_btns').css('display','none');
				}

				// добавляем файл к списку просмотренных
				addFileToViewed($('#popup_video .popup_video_container_inner').attr('data-file-id'));
			}
		} else if (op == 'playVideoCall') { // запустить видео-звонок
			if ($('#popup_video_mp4').length) {
				music_before = music;
				if ($('.music_on').length && $('.music_on').hasClass('music_active')) {
					stopMusic();
				}

				var checkedVideo = $('#popup_video_mp4').get(0);
				if (checkedVideo.paused) {
					checkedVideo.play();
					$('#popup_video .popup_video_play').css('display','none');
					$('#popup_video .popup_video_stop').css('display','block');

					$('#popup_video .popup_video_btns').css('display','none');
				}
			}
		} else if (op == 'stopVideoAndClosePopupVideo') { // остановить видео и закрыть попап. Одним сокетом
			playVideoByNotControls = false; // возвращаем к значению по умолчанию

			if ($('#popup_video').length && $('#popup_video_mp4').length) {
				if ($('.music_on').length && $('.music_on').hasClass('music_active')) {
					playMusic();
				}
				music_before = false;

				var checkedVideo = $('#popup_video_mp4').get(0);
				if (!checkedVideo.paused) {
					checkedVideo.pause();
					$('#popup_video .popup_video_play').css('display','block');
					$('#popup_video .popup_video_stop').css('display','none');

					$('#popup_video .popup_video_btns').css('display','block');
				}
			}

			$('#popup_video').css('display','none');

			setTimeout(function(){
				$('#popup_video').removeClass();

				$('#popup_video .popup_video_container_inner').attr('data-file-id', 0);

				$('#popup_video .popup_video_inner_title').html('');
			}, 10);
		} else if (op == 'acceptMissionKeyup') { // ввод символов при вводе названия миссии
			if ($('.dashboard_tab_content_item_new_mission_input').length) {
				$('.dashboard_tab_content_item_new_mission_input').val(parameters.mission_name);
			}
		} else if (op == 'missionNumberError') { // название миссии. Ввели неверно
			if ($('.dashboard_tab_content_item_new_mission_error').length) {
				if (parameters.error_lang) {
					$('.dashboard_tab_content_item_new_mission_error').html(parameters.error_lang[$('html').attr('lang')]).addClass('dashboard_tab_content_item_new_mission_error_active');
				} else {
					$('.dashboard_tab_content_item_new_mission_error').html(parameters.error).addClass('dashboard_tab_content_item_new_mission_error_active');
				}
			}
		} else if (op == 'missionNumberOpenIncomingCall') { // название миссии ввели верно. Открываем попап со звонком
			if ($('.dashboard_tab_content_item_new_mission_error').length) {
				$('.dashboard_tab_content_item_new_mission_error').removeClass('dashboard_tab_content_item_new_mission_error_active').html('');
			}

			// запускаем отображение времени
			updateIncomingTime();
			incomingCallTimer = setInterval(function(){
				updateIncomingTime();
			}, 1000);

			$('#popup_video_phone .popup_video_phone_wifi_icons').html('<img src="/images/wifi_icons.png" alt="">');
			$('#popup_video_phone .popup_video_phone_name').html('Jane Blond');
			$('#popup_video_phone').attr('class','').addClass('popup_video_phone_incoming_new_mission');

			// звук вызова
			music_before = music;
			if ($('.music_on').length && $('.music_on').hasClass('music_active')) {
				stopMusic();
			}

			if (!incomingAudio || !isPlaying(incomingAudio)) {
				incomingAudio = new Audio;
				incomingAudio.src = '/music/incoming.mp3';
				incomingAudio.play();

				incomingMusicTimer = setInterval(function(){
					incomingAudio = new Audio;
					incomingAudio.src = '/music/incoming.mp3';
					incomingAudio.play();
				}, incomingMusicDuration);
			}

			// отображаем окошко
			$('#popup_video_phone').fadeIn(200);
		} else if (op == 'missionNumberCloseIncomingCall') { // название миссии ввели верно. Закрыть попап со звонком
			// останавливаем звук звонка и запускаем фоновое
			if ($('.music_on').length && $('.music_on').hasClass('music_active')) {
				playMusic();
			}
			music_before = false;

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
			// запускаем фотоновую музыку, если была
			if ($('.music_on').length && $('.music_on').hasClass('music_active')) {
				playMusic();
			}
			music_before = false;

			// останавливаем звук звонка
			clearInterval(incomingMusicTimer);
			incomingMusicTimer = false;
			incomingAudio.pause();

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
			openFileVideoPopup(0, 'video/video_jane_1.mp4', '', 'new_mission_answer_incoming_video', 'call');
			playVideo('call');

			// когда видео доиграло до конца, то закрываем и производим нужные действия
			$('.new_mission_answer_incoming_video video').on('ended', function() {
				closePopupVideo();

				var timerSeconds = parseInt($('.timer').attr('data-timer'), 10);
				if (timerSeconds == 0) {
					// socket
					mySocketLastAction = 'closePopupVideoAndAcceptMission';
					var message = {
						'op': 'closePopupVideoAndAcceptMission',
						'parameters': {},
						'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
						'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			        };
			        sendMessageSocket(JSON.stringify(message));

			        acceptMission();
			    }
			});

			// databases
			$('#section_game').attr('data-last-open-databases', 'databases_start_four');
			uploadTypeTabsDatabasesStep($('#section_game').attr('data-last-open-databases'), false);
		} else if (op == 'stopVideoAndClosePopupVideoAndAcceptMission') { // приняли миссию путем закрытия видео
			playVideoByNotControls = false; // возвращаем к значению по умолчанию

			if ($('#popup_video').length && $('#popup_video_mp4').length) {
				if ($('.music_on').length && $('.music_on').hasClass('music_active')) {
					playMusic();
				}
				music_before = false;

				var checkedVideo = $('#popup_video_mp4').get(0);
				if (!checkedVideo.paused) {
					checkedVideo.pause();
					$('#popup_video .popup_video_play').css('display','block');
					$('#popup_video .popup_video_stop').css('display','none');

					$('#popup_video .popup_video_btns').css('display','block');
				}
			}

			$('#popup_video').css('display','none');

			setTimeout(function(){
				$('#popup_video').removeClass();

				$('#popup_video .popup_video_container_inner').attr('data-file-id', 0);

				$('#popup_video .popup_video_inner_title').html('');
			}, 10);

			// обновляем посдказки
			updateHintWindow();

			// пишем игроку первые 100 баллов
			incrementScoreWithoutSaveDb(100, 'main');

			// запускаем таймер
			updateTimerUploadPage();

			// Обновить к-во непрочитанных файлов
			updateDontOpenFilesQt();

			// Обновить к-во неоткрытых баз данных
			updateDontOpenDatabasesQt();

	    	// отображаем блок Mission name GEM
			$('.dashboard_gem_wrapper').addClass('dashboard_gem_wrapper_active');

			// обновляем содержимое dashboard
			$('#section_game').attr('data-last-open-dashboard', 'company_name');
			uploadTypeTabsDashboardStep('company_name');

			// обновляем атрибут содержимого calls
			$('#section_game').attr('data-last-open-calls', 'call_list');

			// отображаем блок Call Jane
			$('.call_jane').addClass('call_jane_active');
		} else if (op == 'closePopupVideoAndAcceptMission') { // приняли миссию. Видео доиграло до конца
			$('#popup_video').css('display','none');

			setTimeout(function(){
				$('#popup_video').removeClass();

				$('#popup_video .popup_video_container_inner').attr('data-file-id', 0);

				$('#popup_video .popup_video_inner_title').html('');
			}, 10);

			// обновляем посдказки
			updateHintWindow();

			// пишем игроку первые 100 баллов
			incrementScoreWithoutSaveDb(100, 'main');

			// запускаем таймер
			updateTimerUploadPage();

			// Обновить к-во непрочитанных файлов
			updateDontOpenFilesQt();

			// Обновить к-во неоткрытых баз данных
			updateDontOpenDatabasesQt();

	    	// отображаем блок Mission name GEM
			$('.dashboard_gem_wrapper').addClass('dashboard_gem_wrapper_active');

			// обновляем содержимое dashboard
			$('#section_game').attr('data-last-open-dashboard', 'company_name');
			uploadTypeTabsDashboardStep('company_name');

			// обновляем атрибут содержимого calls
			$('#section_game').attr('data-last-open-calls', 'call_list');

			// отображаем блок Call Jane
			$('.call_jane').addClass('call_jane_active');
		} else if (op == 'openFilePdf') { // открыть файл pdf
			if (is_touch_device()) {
		        // function
				$.fancybox.open({
                    src: '/plugins/pdf.js/web/viewer.html?file=' + encodeURIComponent('/' + parameters.path),
                    type: 'iframe',
                    opts: {
                        afterShow: function(instance, current) {},
                        beforeShow: function(instance, current) {},
                        beforeClose: function(instance, current) {
                        	// socket
							mySocketLastAction = 'closeFilePdf';
							var message = {
								'op': 'closeFilePdf',
								'parameters': {},
								'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
								'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
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
	                    src: '/' + parameters.path,
	                    type: 'iframe',
	                    opts: {
	                        afterShow: function(instance, current) {
                        		$('.fancybox-container.fancybox-is-open').css('height', ($('#main').outerHeight() * parseFloat((1920 / windowWidth).toFixed(2))) + 'px');
	                        },
	                        beforeShow: function(instance, current) {},
	                       	beforeClose: function(instance, current) {
	                        	// socket
								mySocketLastAction = 'closeFilePdf';
								var message = {
									'op': 'closeFilePdf',
									'parameters': {},
									'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
									'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
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
	                    src: '/' + parameters.path,
	                    type: 'iframe',
	                    opts: {
	                        afterShow: function(instance, current) {},
	                        beforeShow: function(instance, current) {},
	                       	beforeClose: function(instance, current) {
	                        	// socket
								mySocketLastAction = 'closeFilePdf';
								var message = {
									'op': 'closeFilePdf',
									'parameters': {},
									'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
									'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
						        };
						        sendMessageSocket(JSON.stringify(message));
	                        },
	                        iframe : {
	                            preload : false
	                        }
	                    }
	                });
				}
			}

			// Обновить к-во непрочитанных файлов
			updateDontOpenFilesQt();
		} else if (op == 'closeFilePdf') { // закрыть файл pdf
			$.fancybox.close();
		} else if (op == 'callJane') { // новый исходящий звонок к Jane
			// запускаем отображение времени
			updateOutgoingTime();
			outgoingCallTimer = setInterval(function(){
				updateOutgoingTime();
			}, 1000);

			// звук вызова
			music_before = music;
			if ($('.music_on').length && $('.music_on').hasClass('music_active')) {
				stopMusic();
			}
			outgoingAudio = new Audio;
			outgoingAudio.src = '/music/outgoing.mp3';
			outgoingAudio.play();

			$('#popup_video_phone_outgoing').fadeIn(200);
		} else if (op == 'callJaneOutgoingDecline') { // закрыть попап с исходящим звонком к Jane
			// останавливаем звук звонка и запускаем фоновое
			if (outgoingAudio && isPlaying(outgoingAudio)) {
				outgoingAudio.pause();
			}

			if ($('.music_on').length && $('.music_on').hasClass('music_active')) {
				playMusic();
			}
			music_before = false;

			// останавливаем обновление времени
			if (outgoingCallTimer) {
				clearInterval(outgoingCallTimer);
				outgoingCallTimer = false;
			}

			// скрываем блок с телефоном
			$('#popup_video_phone_outgoing').fadeOut(200);
		} else if (op == 'callJaneOutgoingAccept') { // исходящий звонок к Jane. Она типа ответила
			// останавливаем звук звонка
			if (outgoingAudio && isPlaying(outgoingAudio)) {
				outgoingAudio.pause();
			}

			if ($('.music_on').length && $('.music_on').hasClass('music_active')) {
				playMusic(); // фикс для звучания музыки позже
			}
			music_before = false;

			// останавливаем обновление времени
			if (outgoingCallTimer) {
				clearInterval(outgoingCallTimer);
				outgoingCallTimer = false;
			}

			// скрываем блок с телефоном
			$('#popup_video_phone_outgoing').fadeOut(200);

			// открыть видео и сразу запустить его
			playVideoByNotControls = true; // указываем, что запускалось через кнопку Play, а не через Controls
			openFileVideoPopup(parameters.fileId, parameters.path, parameters.name, parameters.classVideo, parameters.type);
			playVideo('call');

			// обновляем содержимое вкладки call с учетом совершенного звонка
			uploadTypeTabsCallsStep($('#section_game').attr('data-last-open-calls'));
		} else if (op == 'openFileVideoPopupAndPlayVideoCall') { // повторно просмотреть звонок к Jane
			openFileVideoPopup(parameters.fileId, parameters.path, parameters.name, parameters.classVideo, parameters.type);

			if ($('#popup_video_mp4').length) {
				music_before = music;
				if ($('.music_on').length && $('.music_on').hasClass('music_active')) {
					stopMusic();
				}

				var checkedVideo = $('#popup_video_mp4').get(0);
				if (checkedVideo.paused) {
					checkedVideo.play();
					$('#popup_video .popup_video_play').css('display','none');
					$('#popup_video .popup_video_stop').css('display','block');

					$('#popup_video .popup_video_btns').css('display','none');
				}
			}
		} else if (op == 'databaseCarRegister') { // database - открываем окно поиска
			$('#section_game').attr('data-last-open-databases', 'databases_start_four_inner_first_car_register');

			uploadTypeTabsDatabasesStep('databases_start_four_inner_first_car_register', 'car_register');
		} else if (op == 'databaseCarregisterSearchLicensePlateKeyup') { // database - первый экран car register - вводим что-то в поле license plate
			if ($('.dashboard_car_register1_license_plate').length && $('.dashboard_car_register1_license_plate').val() != parameters.license_plate) {
				$('.dashboard_car_register1_license_plate').val(parameters.license_plate);
			}
		} else if (op == 'databaseCarRegisterUpdateCountry') { // database - первый экран car register - выбираем значение в Country
			if ($('.dashboard_car_register1_country').length && $('.dashboard_car_register1_country').val() != parameters.country_lang[$('html').attr('lang')]) {
				$('.dashboard_car_register1_country').val(parameters.country_lang[$('html').attr('lang')]).change().selectric('refresh');
			}
		} else if (op == 'databaseChangeDatabase') { // database - смена таба. Перемещение по табам или кнопка назад
			$('#section_game').attr('data-last-open-databases', parameters.step);

			uploadTypeTabsDatabasesStep(parameters.step, parameters.database);
		} else if (op == 'databaseCarRegisterUpdateDate') { // database - первый экран car register - выбираем дату
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
			// на всякий случай скрываем окно с ошибкой
			$('#popup_search_error').css('display','none');

			// обнуляем значения в попапе перед отображением
			$('.popup_search_processing_input_upload_text span').html('0');
			$('.popup_search_processing_input_upload_percent').css('width', '0%');

			// отображаем окно поиска
			$('#popup_search_processing').css('display','block');
			$('.popup_search_processing_input_upload_percent').css('opacity', 1);

			// звук поиска, если звуки включены
			if (!searchAudio || !isPlaying(searchAudio)) {
				searchAudio = new Audio;
				searchAudio.src = '/music/search_database.mp3';
				searchAudio.play();
			}

			// анимация поиска
			var searchInterval = false; // переменная для интервала поиска
			var databaseSearchPlus = Math.round(100 / databaseSearchIteration); // на столько увеличиваем за итерацию

			searchInterval = setInterval(function(){
				var current = parseInt($('.popup_search_processing_input_upload_text span').html(), 10);
				var next = current + selfRandom(1, databaseSearchPlus);

				if (next >= 100) {
					next = 100;
				}

				$('.popup_search_processing_input_upload_percent').css('width', next + '%');
				$('.popup_search_processing_input_upload_text span').html(next);

				if (next == 100) {
					// останавливаем анимацию поиска
					clearInterval(searchInterval);
					searchInterval = false;

					// скрываем попап поиска
					$('#popup_search_processing').css('display','none');

					// проверяем правильная ли комбинация введена
					if (parameters.license_plate.toLowerCase() == 'stalin' && (parameters.country == 'Russia' || parameters.country == 'Russland') && parameters.date == '30.08.2021') {
						// звук успешного выполнения
						if (searchAudio && isPlaying(searchAudio)) {
							searchAudio.pause();
						}

						if (!successAudio || !isPlaying(successAudio)) {
							successAudio = new Audio;
							successAudio.src = '/music/done.mp3';
							successAudio.play();
						}

						// грузим новый таб
						$('#section_game').attr('data-last-open-databases', 'databases_start_four_inner_second_car_register_huilov');

						uploadTypeTabsDatabasesStep('databases_start_four_inner_second_car_register_huilov', 'car_register');

						// фиксируем, что нашли
						$('#section_game').attr('data-car-register-complete', 1);
					} else {
						// отображаем попап ошибки
						$('#popup_search_error .popup_search_error_input').html(text89);
						$('#popup_search_error .popup_search_error_text').html(text88);
						$('#popup_search_error').css('display','block');

						// звук ошибки
						if (searchAudio && isPlaying(searchAudio)) {
							searchAudio.pause();
						}

						if (!errorAudio || !isPlaying(errorAudio)) {
							errorAudio = new Audio;
							errorAudio.src = '/music/error.mp3';
							errorAudio.play();
						}
					}
				}
			}, (databaseSearchDuration / databaseSearchIteration));
		} else if (op == 'popupSearchErrorClose') { // поиск завершился неудачей. Закрываем попап
			$('#popup_search_error').fadeOut(200);
		} else if (op == 'databaseCarRegisterHuilov') { // database - car register - нашли huilov
			$('#section_game').attr('data-last-open-databases', 'databases_start_four_inner_second_car_register_huilov');

			uploadTypeTabsDatabasesStep('databases_start_four_inner_second_car_register_huilov', 'car_register');
		} else if (op == 'databasePersonalFiles') { // database - открыть personal files
			$('#section_game').attr('data-last-open-databases', 'databases_start_four_inner_first_personal_files');

			uploadTypeTabsDatabasesStep('databases_start_four_inner_first_personal_files', 'personal_files');
		} else if (op == 'databasePersonalFilesPrivateIndividual') { // database - открыть personal files - private individuals
			$('#section_game').attr('data-last-open-databases', 'databases_start_four_inner_second_personal_files_private_individual');

			uploadTypeTabsDatabasesStep('databases_start_four_inner_second_personal_files_private_individual', 'personal_files');
		} else if (op == 'databasePersonalFilesPrivateIndividualFirstnameKeyup') { // database - personal files - private individuals + ceo database - вводим что-то в поле firstname
			if ($('.dashboard_personal_files2_private_individuals_input_wrapper_firstname input').length && $('.dashboard_personal_files2_private_individuals_input_wrapper_firstname input').val() != parameters.firstname) {
				$('.dashboard_personal_files2_private_individuals_input_wrapper_firstname input').val(parameters.firstname);
			}
		} else if (op == 'databasePersonalFilesPrivateIndividualLastnameKeyup') { // database - personal files - private individuals + ceo database - вводим что-то в поле lastname
			if ($('.dashboard_personal_files2_private_individuals_input_wrapper_lastname input').length && $('.dashboard_personal_files2_private_individuals_input_wrapper_lastname input').val() != parameters.lastname) {
				$('.dashboard_personal_files2_private_individuals_input_wrapper_lastname input').val(parameters.lastname);
			}
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
		} else if (op == 'databasePersonalFilesPrivateIndividualsNoEmptyFields') { // database - personal files - private individuals - нет пустых полей, открываем попап поиска
			// на всякий случай скрываем окно с ошибкой
			$('#popup_search_error').css('display','none');

			// обнуляем значения в попапе перед отображением
			$('.popup_search_processing_input_upload_text span').html('0');
			$('.popup_search_processing_input_upload_percent').css('width', '0%');

			// отображаем окно поиска
			$('#popup_search_processing').css('display','block');
			$('.popup_search_processing_input_upload_percent').css('opacity', 1);

			// звук поиска, если звуки включены
			if (!searchAudio || !isPlaying(searchAudio)) {
				searchAudio = new Audio;
				searchAudio.src = '/music/search_database.mp3';
				searchAudio.play();
			}

			// анимация поиска
			var searchInterval = false; // переменная для интервала поиска
			var databaseSearchPlus = Math.round(100 / databaseSearchIteration); // на столько увеличиваем за итерацию

			searchInterval = setInterval(function(){
				var current = parseInt($('.popup_search_processing_input_upload_text span').html(), 10);
				var next = current + selfRandom(1, databaseSearchPlus);

				if (next >= 100) {
					next = 100;
				}

				$('.popup_search_processing_input_upload_percent').css('width', next + '%');
				$('.popup_search_processing_input_upload_text span').html(next);

				if (next == 100) {
					// останавливаем анимацию поиска
					clearInterval(searchInterval);
					searchInterval = false;

					// скрываем попап поиска
					$('#popup_search_processing').css('display','none');

					// проверяем правильная ли комбинация введена
					if (parameters.firstname.toLowerCase() == 'vladimir' && parameters.lastname.toLowerCase() == 'huilov') {
						// звук успешного выполнения
						if (searchAudio && isPlaying(searchAudio)) {
							searchAudio.pause();
						}

						if (!successAudio || !isPlaying(successAudio)) {
							successAudio = new Audio;
							successAudio.src = '/music/done.mp3';
							successAudio.play();
						}

						// грузим новый таб
						$('#section_game').attr('data-last-open-databases', 'databases_start_four_inner_second_personal_files_private_individual_huilov');

						uploadTypeTabsDatabasesStep('databases_start_four_inner_second_personal_files_private_individual_huilov', 'car_register');

						// фиксируем, что нашли
						$('#section_game').attr('data-private-individual-complete', 1);
					} else {
						// отображаем попап ошибки
						$('#popup_search_error .popup_search_error_input').html(text89);
						$('#popup_search_error .popup_search_error_text').html(text88);
						$('#popup_search_error').css('display','block');

						// звук ошибки
						if (searchAudio && isPlaying(searchAudio)) {
							searchAudio.pause();
						}

						if (!errorAudio || !isPlaying(errorAudio)) {
							errorAudio = new Audio;
							errorAudio.src = '/music/error.mp3';
							errorAudio.play();
						}
					}
				}
			}, (databaseSearchDuration / databaseSearchIteration));
		} else if (op == 'databasePersonalFilesPrivateIndividualHuilov') { // database - personal files - private individuals - нашли Huilov
			// если нашли уже private individual - huilov
			$('#section_game').attr('data-last-open-databases', 'databases_start_four_inner_second_personal_files_private_individual_huilov');

			uploadTypeTabsDatabasesStep('databases_start_four_inner_second_personal_files_private_individual_huilov', 'personal_files');
		} else if (op == 'databaseMobileCalls') { // database - mobile calls
			$('#section_game').attr('data-last-open-databases', 'databases_start_four_inner_first_mobile_calls');

			uploadTypeTabsDatabasesStep('databases_start_four_inner_first_mobile_calls', 'mobile_calls');
		} else if (op == 'databaseMobileCallsUpdateCountryCode') { // database - первый экран mobile calls - выбираем значение в Country code
			if ($('.dashboard_mobile_calls1_country_code').length && $('.dashboard_mobile_calls1_country_code').val() != parameters.country_lang[$('html').attr('lang')]) {
				$('.dashboard_mobile_calls1_country_code').val(parameters.country_lang[$('html').attr('lang')]).change().selectric('refresh');
			}
		} else if (op == 'databaseMobileCallsNumberKeyup') {
			if ($('.dashboard_mobile_calls1_number').length && $('.dashboard_mobile_calls1_number').val() != parameters.number) {
				$('.dashboard_mobile_calls1_number').val(parameters.number);
			}
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
		} else if (op == 'databaseMobileCallsNoEmptyFields') {
			// обнуляем значения в попапе перед отображением
			$('.popup_search_processing_input_upload_text span').html('0');
			$('.popup_search_processing_input_upload_percent').css('width', '0%');

			// отображаем окно поиска
			$('#popup_search_processing').css('display','block');
			$('.popup_search_processing_input_upload_percent').css('opacity', 1);

			// звук поиска, если звуки включены
			if (!searchAudio || !isPlaying(searchAudio)) {
				searchAudio = new Audio;
				searchAudio.src = '/music/search_database.mp3';
				searchAudio.play();
			}

			// анимация поиска
			var searchInterval = false; // переменная для интервала поиска
			var databaseSearchPlus = Math.round(100 / databaseSearchIteration); // на столько увеличиваем за итерацию

			searchInterval = setInterval(function(){
				var current = parseInt($('.popup_search_processing_input_upload_text span').html(), 10);
				var next = current + selfRandom(1, databaseSearchPlus);

				if (next >= 100) {
					next = 100;
				}

				$('.popup_search_processing_input_upload_percent').css('width', next + '%');
				$('.popup_search_processing_input_upload_text span').html(next);

				if (next == 100) {
					// останавливаем анимацию поиска
					clearInterval(searchInterval);
					searchInterval = false;

					// скрываем попап поиска
					$('#popup_search_processing').css('display','none');

					// проверяем правильная ли комбинация введена
					if ((parameters.country_code == '167' || parameters.country_code == 167) && (parameters.number == '94054421337' || parameters.number == '794054421337' || parameters.number == '+794054421337' || parameters.number == '+94054421337')) {
						// звук успешного выполнения
						if (searchAudio && isPlaying(searchAudio)) {
							searchAudio.pause();
						}

						if (!successAudio || !isPlaying(successAudio)) {
							successAudio = new Audio;
							successAudio.src = '/music/done.mp3';
							successAudio.play();
						}

						// грузим новый таб
						$('#section_game').attr('data-last-open-databases', 'databases_start_four_inner_first_mobile_calls_messages');

						uploadTypeTabsDatabasesStep('databases_start_four_inner_first_mobile_calls_messages', 'mobile_calls');

						// фиксируем, что нашли
						$('#section_game').attr('data-mobile-calls-complete', 1);
					} else {
						// отображаем попап ошибки
						$('#popup_search_error .popup_search_error_input').html(text143);
						$('#popup_search_error .popup_search_error_text').html(text144);
						$('#popup_search_error').css('display','block');

						// звук ошибки
						if (searchAudio && isPlaying(searchAudio)) {
							searchAudio.pause();
						}

						if (!errorAudio || !isPlaying(errorAudio)) {
							errorAudio = new Audio;
							errorAudio.src = '/music/error.mp3';
							errorAudio.play();
						}
					}
				}
			}, (databaseSearchDuration / databaseSearchIteration));
		} else if (op == 'databaseMobileCallsMessages') { // database - mobile calls - сообщения
			$('#section_game').attr('data-last-open-databases', 'databases_start_four_inner_first_mobile_calls_messages');

			uploadTypeTabsDatabasesStep('databases_start_four_inner_first_mobile_calls_messages', 'mobile_calls');
		} else if (op == 'databaseMobileCallsOpenPopupMesages') { // database - mobile calls - сообщения, открыть попап
			$('#popup_mobile_calls_messages').css('display','block');
		} else if (op == 'databaseMobileCallsClosePopupMesages') { // database - mobile calls - сообщения, скрыть попап
			$('#popup_mobile_calls_messages').fadeOut(200);
		} else if (op == 'databasePersonalFilesCeoDatabase') { // database - private individuals - ceo database
			$('#section_game').attr('data-last-open-databases', 'databases_start_four_inner_second_personal_files_ceo_database');

			uploadTypeTabsDatabasesStep('databases_start_four_inner_second_personal_files_ceo_database', 'personal_files');
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
		} else if (op == 'databasePersonalFilesCeoDatabaseNoEmptyFields') { // database - personal files - ceo database - нет пустых полей, открываем попап поиска
			// на всякий случай скрываем окно с ошибкой
			$('#popup_search_error').css('display','none');

			// обнуляем значения в попапе перед отображением
			$('.popup_search_processing_input_upload_text span').html('0');
			$('.popup_search_processing_input_upload_percent').css('width', '0%');

			// отображаем окно поиска
			$('#popup_search_processing').css('display','block');
			$('.popup_search_processing_input_upload_percent').css('opacity', 1);

			// звук поиска, если звуки включены
			if (!searchAudio || !isPlaying(searchAudio)) {
				searchAudio = new Audio;
				searchAudio.src = '/music/search_database.mp3';
				searchAudio.play();
			}

			// анимация поиска
			var searchInterval = false; // переменная для интервала поиска
			var databaseSearchPlus = Math.round(100 / databaseSearchIteration); // на столько увеличиваем за итерацию

			searchInterval = setInterval(function(){
				var current = parseInt($('.popup_search_processing_input_upload_text span').html(), 10);
				var next = current + selfRandom(1, databaseSearchPlus);

				if (next >= 100) {
					next = 100;
				}

				$('.popup_search_processing_input_upload_percent').css('width', next + '%');
				$('.popup_search_processing_input_upload_text span').html(next);

				if (next == 100) {
					// останавливаем анимацию поиска
					clearInterval(searchInterval);
					searchInterval = false;

					// скрываем попап поиска
					$('#popup_search_processing').css('display','none');

					// проверяем правильная ли комбинация введена
					if (parameters.firstname.toLowerCase() == 'axel' && parameters.lastname.toLowerCase() == 'rod') {
						// звук успешного выполнения
						if (searchAudio && isPlaying(searchAudio)) {
							searchAudio.pause();
						}

						if (!successAudio || !isPlaying(successAudio)) {
							successAudio = new Audio;
							successAudio.src = '/music/done.mp3';
							successAudio.play();
						}

						// грузим новый таб
						$('#section_game').attr('data-last-open-databases', 'databases_start_four_inner_second_personal_files_ceo_database_rod');

						uploadTypeTabsDatabasesStep('databases_start_four_inner_second_personal_files_ceo_database_rod', 'personal_files');

						// фиксируем, что нашли
						$('#section_game').attr('data-ceo-database-complete', 1);
					} else {
						// отображаем попап ошибки
						$('#popup_search_error .popup_search_error_input').html(text89);
						$('#popup_search_error .popup_search_error_text').html(text88);
						$('#popup_search_error').css('display','block');

						// звук ошибки
						if (searchAudio && isPlaying(searchAudio)) {
							searchAudio.pause();
						}

						if (!errorAudio || !isPlaying(errorAudio)) {
							errorAudio = new Audio;
							errorAudio.src = '/music/error.mp3';
							errorAudio.play();
						}
					}
				}
			}, (databaseSearchDuration / databaseSearchIteration));
		} else if (op == 'databasePersonalFilesAxelRod') { // если нашли уже ceo database - rod
			$('#section_game').attr('data-last-open-databases', 'databases_start_four_inner_second_personal_files_ceo_database_rod');

			uploadTypeTabsDatabasesStep('databases_start_four_inner_second_personal_files_ceo_database_rod', 'personal_files');
		} else if (op == 'databaseBankTransactions') { // database - bank transactions
			$('#section_game').attr('data-last-open-databases', 'databases_start_four_inner_first_bank_transactions');

			uploadTypeTabsDatabasesStep('databases_start_four_inner_first_bank_transactions', 'bank_transactions');
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
			// звук поиска
			setTimeout(function(){
				if (!dataTransferAudio || !isPlaying(dataTransferAudio)) {
					dataTransferAudio = new Audio;
					dataTransferAudio.src = '/music/data_transfer.mp3';
					dataTransferAudio.play();
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

						// скрываем попап
						$('#popup_data_transfer').fadeOut(200);

						// правильный ли ответ
						var checkCompanyName = parameters.company_name.toLowerCase();
						checkCompanyName = checkCompanyName.replace('«', '');
						checkCompanyName = checkCompanyName.replace('»', '');
						checkCompanyName = checkCompanyName.replace('"', '');

						if (checkCompanyName == 'green pace' || checkCompanyName == 'green pace group') {
							if (dataTransferAudio && isPlaying(dataTransferAudio)) {
								dataTransferAudio.pause();
							}

							// звук успешного выполнения
							if (!successAudio || !isPlaying(successAudio)) {
								successAudio = new Audio;
								successAudio.src = '/music/done.mp3';
								successAudio.play();
							}

							// попап с текстом успеха
							$('#popup_success .popup_success_input').html(text163);
							$('#popup_success .popup_success_text').html(text164);
							$('#popup_success .popup_success_close .btn span').html(text165);
							$('#popup_success').css('display','block');
						} else {
							// отображаем попап ошибки
							$('#popup_search_error .popup_search_error_input').html(text161);
							$('#popup_search_error .popup_search_error_text').html(text162);
							$('#popup_search_error').css('display','block');

							// звук ошибки
							if (dataTransferAudio && isPlaying(dataTransferAudio)) {
								dataTransferAudio.pause();
							}

							if (!errorAudio || !isPlaying(errorAudio)) {
								errorAudio = new Audio;
								errorAudio.src = '/music/error.mp3';
								errorAudio.play();
							}
						}
					}
				}, (dataTransferMusicDuration / dataTransferIteration));

				// отображаем попап с гифкой
				$('#popup_data_transfer').css('display','block');
			}, 210);

			// по окончанию поиска
			setTimeout(function(){
				// скрываем попап
				$('#popup_data_transfer').fadeOut(200);

				// правильный ли ответ
				var checkCompanyName = parameters.company_name.toLowerCase();
				checkCompanyName = checkCompanyName.replace('«', '');
				checkCompanyName = checkCompanyName.replace('»', '');
				checkCompanyName = checkCompanyName.replace('"', '');

				if (checkCompanyName == 'green pace' || checkCompanyName == 'green pace group') {
					// останавливаем звук поиска
					if (dataTransferAudio && isPlaying(dataTransferAudio)) {
						dataTransferAudio.pause();
					}

					// звук успешного выполнения
					if (!successAudio || !isPlaying(successAudio)) {
						successAudio = new Audio;
						successAudio.src = '/music/done.mp3';
						successAudio.play();
					}

					// попап с текстом успеха
					$('#popup_success .popup_success_input').html(text163);
					$('#popup_success .popup_success_text').html(text164);
					$('#popup_success .popup_success_close .btn span').html(text165);
					$('#popup_success').css('display','block');
				} else {
					// отображаем попап ошибки
					$('#popup_search_error .popup_search_error_input').html(text161);
					$('#popup_search_error .popup_search_error_text').html(text162);
					$('#popup_search_error').css('display','block');

					// звук ошибки
					if (dataTransferAudio && isPlaying(dataTransferAudio)) {
						dataTransferAudio.pause();
					}

					if (!errorAudio || !isPlaying(errorAudio)) {
						errorAudio = new Audio;
						errorAudio.src = '/music/error.mp3';
						errorAudio.play();
					}
				}
			}, 7000);
		} else if (op == 'updateHintWindowAndDecrementScore') { // обновить окно подсказок и уменьшить очки
			updateHintWindow();

			decrementScoreWithoutSaveDb(parameters.new_score, 'hints');

			// обновляем также и соответствующий атрибут
			$('#section_game').attr('data-score', parameters.new_score);
		}
	}

/* ВЫХОД */
	// открыть попап выхода
	$('.exit').click(function(){
		$('#popup_exit').css('display','block');
	});

	// закрыть попап выхода
	$('.popup_exit_bg, .popup_exit_btn_no').click(function(){
		$('#popup_exit').fadeOut(200);
	});

	// выход
	$('.popup_exit_btn_yes').click(function(){
		var formData = new FormData();
    	formData.append('op', 'exit');
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
				window.location.href = json.success;
			},
			error: function(xhr, ajaxOptions, thrownError) {	
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

/* МУЗЫКА */
	// музыкальный проигрыватель. Фоновая музыка
	if ($("#jquery_jplayer_1").length && $('#section_join_game').length == 0) {
	    $("#jquery_jplayer_1").jPlayer({
	        ready: function () {
	            $(this).jPlayer("setMedia", {
	                mp3: "/music/fon_music.mp3"
	            });
	        },
	        swfPath: "/plugins",
	        supplied: "mp3",
	        loop: true
	    });
	}

    // начать воспроизведение фоновой музыки и звуков
    function playMusic() {
    	$('.jp-play').trigger('click');
    	music = true;
    }

    // остановить воспроизведение фоновой музыки и звуков
    function stopMusic() {
    	$('.jp-pause').trigger('click');
    	music = false;
    }

    // вкл/выкл музыку
    $('.music_on').click(function(){
    	if ($(this).hasClass('music_active')) {
    		return false;
    	}

    	if (!music) {
    		playMusic();
    		$('.music_off').removeClass('music_active');
    		$(this).addClass('music_active');
    	}
    });
    $('.music_off').click(function(){
    	if ($(this).hasClass('music_active')) {
    		return false;
    	}

    	if (music) {
    		stopMusic();
    		$('.music_on').removeClass('music_active');
    		$(this).addClass('music_active');
    	}
    });

/* ЯЗЫКИ */
	// показать меню выбора языка
	$('.language').click(function(){
		if ($(this).hasClass('language_open')) {
			$('.language_hidden').animate({height: '0px'},100);
			$(this).removeClass('language_open');
		} else {
			$('.language_hidden').animate({height: '75px'},100);
			$(this).addClass('language_open');
		}
	});

	// закрывать меню языков при клике вне меню
	$(document).mouseup(function(e){
	    var container = $('.language_hidden');
	    var container2 = $('.language');

	    if (!container.is(e.target) && container.has(e.target).length === 0 && !container2.is(e.target) && container2.has(e.target).length === 0) {
	        $('.language_hidden').animate({height: '0px'},100);
			$('.language').removeClass('language_open');
	    }
	});

/* ТАЙМЕР */
	// обновить значение таймера
	updateTimerUploadPage();

	function updateTimerUploadPage() {
		if ($('.timer').length) {
			/*// обнуляем таймер на всякий случай
			if (mainTimer !== false) {
				clearInterval(mainTimer);
				mainTimer = false;
			}*/

			var formData = new FormData();
	    	formData.append('op', 'getActiveTimer');

	    	$.ajax({
				url: '/ajax/ajax.php',
		        type: "POST",
		        dataType: "json",
		        cache: false,
		        contentType: false,
		        processData: false,
		        data: formData,
				success: function(json) {
					// обнуляем таймер2 на всякий случай
					if (mainTimer2 !== false) {
						clearInterval(mainTimer2);
						mainTimer2 = false;
					}

					if (json.second || json.second == '0' || json.second == 0) {
						$('.timer .timer_hour').html(('0' + json.hours).slice(-2));
						$('.timer .timer_minute').html(('0' + json.minute).slice(-2));
						$('.timer .timer_second').html(('0' + json.second).slice(-2));

						$('.timer').attr('data-timer', json.second_sum);

						mainTimer2 = setInterval(function(){
							var timerSeconds = parseInt($('.timer').attr('data-timer'), 10);
							var timerSecondsStart = timerSeconds;

							var hours = Math.floor(timerSeconds / 3600);
							timerSeconds = timerSeconds - hours * 3600;

							var minute = Math.floor(timerSeconds / 60);
							timerSeconds = timerSeconds - minute * 60;

							var second = timerSeconds;

							$('.timer .timer_hour').html(('0' + hours).slice(-2));
							$('.timer .timer_minute').html(('0' + minute).slice(-2));
							$('.timer .timer_second').html(('0' + second).slice(-2));

							$('.timer').attr('data-timer', (timerSecondsStart + 1));
						}, 1000); // 1000 - раз в секунду
					} else {
						$('.timer .timer_hour').html('00');
						$('.timer .timer_minute').html('00');
						$('.timer .timer_second').html('00');
					}

					// setTimeout(function(){
					updateTimer();
				},
				error: function(xhr, ajaxOptions, thrownError) {	
					console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}

	function updateTimer() {
		// if ($('.timer').length) {
			// обнуляем таймер на всякий случай
			if (mainTimer !== false) {
				clearInterval(mainTimer);
				mainTimer = false;
			}

			// запускаем основной таймер
			mainTimer = setInterval(function(){
				var formData = new FormData();
		    	formData.append('op', 'getActiveTimer');

		    	$.ajax({
					url: '/ajax/ajax.php',
			        type: "POST",
			        dataType: "json",
			        cache: false,
			        contentType: false,
			        processData: false,
			        data: formData,
					success: function(json) {
						if (json.second) {
							$('.timer .timer_hour').html(('0' + json.hours).slice(-2));
							$('.timer .timer_minute').html(('0' + json.minute).slice(-2));
							$('.timer .timer_second').html(('0' + json.second).slice(-2));

							$('.timer').attr('data-timer', json.second_sum);

							// обнуляем таймер2 на всякий случай
							if (mainTimer2 !== false) {
								clearInterval(mainTimer2);
								mainTimer2 = false;
							}

							mainTimer2 = setInterval(function(){
								var timerSeconds = parseInt($('.timer').attr('data-timer'), 10);
								var timerSecondsStart = timerSeconds;

								var hours = Math.floor(timerSeconds / 3600);
								timerSeconds = timerSeconds - hours * 3600;

								var minute = Math.floor(timerSeconds / 60);
								timerSeconds = timerSeconds - minute * 60;

								var second = timerSeconds;

								$('.timer .timer_hour').html(('0' + hours).slice(-2));
								$('.timer .timer_minute').html(('0' + minute).slice(-2));
								$('.timer .timer_second').html(('0' + second).slice(-2));

								$('.timer').attr('data-timer', (timerSecondsStart + 1));
							}, 1000);
						} else {
							$('.timer .timer_hour').html('00');
							$('.timer .timer_minute').html('00');
							$('.timer .timer_second').html('00');
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {	
						console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			}, 5 * 60 * 1000); // раз в 5 минут
			// }, 10 * 1000);
		// }
	}

	/*// обновляем значение временного атрибута и пишем в бд
	function updateTimerDb() {
		if ($('.timer').length) {
			var timerSeconds = parseInt($('.timer').attr('data-timer'), 10);
			$('.timer').attr('data-timer', timerSeconds + 1);

			// каждые 10(?) секунд пишем в бд
			if (timerSeconds >= saveTimerEverySecond && timerSeconds % saveTimerEverySecond == 0) {
				var formData = new FormData();
		    	formData.append('op', 'updateTimer');
		    	formData.append('timer_second', timerSeconds);

		    	$.ajax({
					url: '/ajax/ajax.php',
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
		}
	}*/

/* ЗАГРУЗКА ИГРЫ - ОТОБРАЖЕНИЕ/СКРЫТИЕ ОКОН */
	// скрыть основное окно игры
	function hiddenMainGameWindow() {
		$('.content_game').css('display', 'none');
	}

	// показать основное окно игры
	function openMainGameWindow() {
		viewMainScore();
		viewMainTimer();
		viewHighscoreBtn();
		viewHintBtn();
		viewChatBtn();

		$('.content_game').css('display', 'block');
	}

	// скрыть окно подсказок
	function hiddenHintWindow() {
		$('.content_hints').css('display', 'none');

		// убираем скролл с окна подсказок
		$('.active_hints .active_hints_value_middle_scroll').mCustomScrollbar('destroy');
	}

	// показать окно подсказок
	function openHintWindow() {
		hiddenMainScore();
		hiddenMainTimer();
		hiddenHighscoreBtn();
		hiddenHintBtn();
		hiddenChatBtn();

		$('.content_hints').css('display', 'block');
	}

	// скрыть основной прелоадер
	function hiddenMainPreloader() {
		$('#preloader span').html('');
		$('#preloader').fadeOut(200);
	}

	// показать основной прелоадер
	function viewMainPreloader(text) {
		$('#preloader span').html(text);

		$('#preloader').css('display', 'block');
	}

	// скрыть основное поле с очками
	function hiddenMainScore() {
		$('.score_wrapper').css('display', 'none');
	}

	// показать основное поле с очками
	function viewMainScore() {
		$('.score_wrapper').css('display', 'block');
	}

	// скрыть основное поле с таймером
	function hiddenMainTimer() {
		$('.timer_wrapper').css('display', 'none');
	}

	// показать основное поле с таймером
	function viewMainTimer() {
		$('.timer_wrapper').css('display', 'block');
	}

	// скрыть кнопку перехода на страницу результатов
	function hiddenHighscoreBtn() {
		$('.section_main_bg_footer .btn_wrapper_view_highscore').css('display', 'none');
	}

	// показать кнопку перехода на страницу результатов
	function viewHighscoreBtn() {
		$('.section_main_bg_footer .btn_wrapper_view_highscore').css('display', 'block');
	}

	// скрыть кнопку перехода на страницу подсказок
	function hiddenHintBtn() {
		$('.section_main_bg_footer .btn_wrapper_view_hints').css('display', 'none');
	}

	// показать кнопку перехода на страницу подсказок
	function viewHintBtn() {
		$('.section_main_bg_footer .btn_wrapper_view_hints').css('display', 'block');
	}

	// скрыть кнопку перехода на чат
	function hiddenChatBtn() {
		$('.section_main_bg_footer .btn_wrapper_view_chat').css('display', 'none');
	}

	// показать кнопку перехода на чат
	function viewChatBtn() {
		$('.section_main_bg_footer .btn_wrapper_view_chat').css('display', 'block');
	}

	// развернуть чат
	function openChatWindow() {
		// socket
		mySocketLastAction = 'openChatWindow';
		var message = {
			'op': 'openChatWindow',
			'parameters': {},
			'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
			'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
        };
        sendMessageSocket(JSON.stringify(message));

		// function
		updateChatMessages();
		$('.chat').animate({height: '674px'},200);
		$('.btn_wrapper_view_chat').addClass('btn_wrapper_view_chat_active');
	}

	// свернуть чат
	function hiddenChatWindow() {
		// socket
		mySocketLastAction = 'hiddenChatWindow';
		var message = {
			'op': 'hiddenChatWindow',
			'parameters': {},
			'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
			'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
        };
        sendMessageSocket(JSON.stringify(message));

		// function
		$('.chat').animate({height: '0px'},200);
		$('.btn_wrapper_view_chat').removeClass('btn_wrapper_view_chat_active');
	}

/* ПОДСКАЗКИ */
	// загрузить актуальный список подсказок
	function updateHintWindow() {
		var formData = new FormData();
    	formData.append('op', 'updateWindowHint');
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
					hiddenMainPreloader();

					// убираем скролл с окна подсказок
					$('.active_hints .active_hints_value_middle_scroll').mCustomScrollbar('destroy');

					$('.active_hints .active_hints_value_middle_scroll').html('');
					if (json.success_hint_left) {
						$('.active_hints .active_hints_value_middle_scroll').html($.trim(json.success_hint_left));
					}

					$('.list_hints .list_hints_content_right').html('');
					if (json.success_hint_right) {
						$('.list_hints .list_hints_content_right').html(json.success_hint_right);
					}
					
					$('.list_hints .list_hints_content_left_title').html('');
					if (json.success_hint_right_title) {
						$('.list_hints .list_hints_content_left_title').html(json.success_hint_right_title);
					}
					
					$('.list_hints .list_hints_content_left_text').html('');
					if (json.success_hint_right_text) {
						$('.list_hints .list_hints_content_left_text').html(json.success_hint_right_text);
					}

					// скролл для текста подсказок, которые открыты. Текст слева
					$('.active_hints .active_hints_value_middle_scroll').mCustomScrollbar({
						scrollInertia: 700,
						scrollbarPosition: "inside"
					});
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {	
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}

	// открыть окно подсказок при нажатии на кнопку в футере
	$('.section_main_bg_footer .btn_wrapper_view_hints').click(function(){
		// socket
		mySocketLastAction = 'openHintWindow';
		var message = {
			'op': 'openHintWindow',
			'parameters': {},
			'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
			'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
        };
        sendMessageSocket(JSON.stringify(message));

        // function
		viewMainPreloader(defaultLoaderLoadingText);

		updateHintWindow();
		hiddenMainGameWindow();
		openHintWindow();
		loadScoreActualHints();
	});

	// кнопка вернуться назад из окна подсказок на основное окно игры
	$('.hint_back_btn').click(function(){
		// socket
		mySocketLastAction = 'hiddenHintWindow-openMainGameWindow';
		var message = {
			'op': 'hiddenHintWindow-openMainGameWindow',
			'parameters': {},
			'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
			'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
        };
        sendMessageSocket(JSON.stringify(message));

        // function
		hiddenHintWindow();

		openMainGameWindow();
		loadScoreActualMain();
	});

	// открыть подсказку
	$('.list_hints_content_right').on('click', '.list_hint_item', function(e){
		if ($(this).hasClass('list_hint_item_opened') || $(this).hasClass('list_hint_item_answer_dont_open')) {
			return false;
		}

		viewMainPreloader(defaultLoaderLoadingText);

		// убираем скролл с окна подсказок
		$('.active_hints .active_hints_value_middle_scroll').mCustomScrollbar('destroy');

		var formData = new FormData();
    	formData.append('op', 'activateHint');
    	formData.append('lang_abbr', $('html').attr('lang'));
    	formData.append('hint_id', $(this).attr('data-hint-id'));

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
			        // function
					$('.active_hints .active_hints_value_middle_scroll').html('');
					if (json.success_hint_left) {
						$('.active_hints .active_hints_value_middle_scroll').html($.trim(json.success_hint_left));
					}

					$('.list_hints .list_hints_content_right').html('');
					if (json.success_hint_right) {
						$('.list_hints .list_hints_content_right').html(json.success_hint_right);
					}
					
					$('.list_hints .list_hints_content_left_title').html('');
					if (json.success_hint_right_title) {
						$('.list_hints .list_hints_content_left_title').html(json.success_hint_right_title);
					}
					
					$('.list_hints .list_hints_content_left_text').html('');
					if (json.success_hint_right_text) {
						$('.list_hints .list_hints_content_left_text').html(json.success_hint_right_text);
					}

					// скролл для текста подсказок, которые открыты. Текст слева
					$('.active_hints .active_hints_value_middle_scroll').mCustomScrollbar({
						scrollInertia: 700,
						scrollbarPosition: "inside"
					});

					hiddenMainPreloader();

					// обновляем points
					if (json.points) {
						var points = parseInt(json.points, 10);
						if (points > 0) {
							var curScore = parseInt($.trim($('#section_game').attr('data-score')), 10);

							// socket
							mySocketLastAction = 'updateHintWindowAndDecrementScore';
							var message = {
								'op': 'updateHintWindowAndDecrementScore',
								'parameters': {
									new_score: curScore - points
								},
								'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
								'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
					        };
					        sendMessageSocket(JSON.stringify(message));

							// decrementScoreWithoutSaveDb(curScore - points, 'hints');
							decrementScore(curScore - points, 'hints');
						} else {
							// socket
							mySocketLastAction = 'updateHintWindow';
							var message = {
								'op': 'updateHintWindow',
								'parameters': {},
								'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
								'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
					        };
					        sendMessageSocket(JSON.stringify(message));
						}
					} else {
						// socket
						mySocketLastAction = 'updateHintWindow';
						var message = {
							'op': 'updateHintWindow',
							'parameters': {},
							'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
							'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				        };
				        sendMessageSocket(JSON.stringify(message));
					}
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {	
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

/* ЧАТ */
	// скролл для области сообщений
	if ($('.chat_messages_scroll').length) {
		$('.chat_messages_scroll').mCustomScrollbar({
			scrollInertia: 700,
			scrollbarPosition: "inside"
		});
	}

	// загрузить актуальное состояние чата
	function updateChatMessages() {
		var formData = new FormData();
    	formData.append('op', 'updateChatMessages');
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
				// убираем скролл для области сообщений
				$('.chat_messages_scroll').mCustomScrollbar('destroy');

				$('.chat_messages_scroll').html(json.messages);

				// добавляем скролл для области сообщений
				setTimeout(function(){
					$('.chat_messages_scroll').mCustomScrollbar({
						scrollInertia: 700,
						scrollbarPosition: "inside"
					});
				}, 100);

				// класс при отсутствии сообщений
				if (json.chat_message_empty) {
					$('.chat_messages_scroll').addClass('chat_message_empty');
				} else {
					$('.chat_messages_scroll').removeClass('chat_message_empty');
				}

				// возможность что-то написать в чат
				if (json.input_disabled) {
					$('.chat_form input[type="text"]').prop('disabled', true);
				} else {
					$('.chat_form input[type="text"]').prop('disabled', false);
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {	
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}

	// открыть чат при нажатии на кнопку в футере
	$('.section_main_bg_footer .btn_wrapper_view_chat').click(function(){
		if ($(this).hasClass('btn_wrapper_view_chat_active')) {
			hiddenChatWindow();
		} else {
			openChatWindow();
		}
	});

	// закрыть окно чата при нажатии на кнопку закрытия чата
	$('.chat_close').click(function(){
		hiddenChatWindow();
	});

/* DASHBOARD */
	// скрыть все типы табов
	function hiddenAllTypeTabs() {
		$('.dashboard_tabs').removeClass('dashboard_tabs_active');
		$('.dashboard_item').removeClass('dashboard_item_active');
	}

	// переключение типов табов
	$('.dashboard_list .dashboard_item').click(function(){
		if ($(this).hasClass('dashboard_item_active')) {
			return false;
		}

		viewMainPreloader(defaultLoaderLoadingText);

		hiddenAllTypeTabs();

		var newTypeDashboard = $(this).attr('data-dashboard');
		if (newTypeDashboard == 'dashboard') {
			openTypeTabsDashboard();
		} else if (newTypeDashboard == 'calls') {
			openTypeTabsCalls();
		} else if (newTypeDashboard == 'files') {
			openTypeTabsFiles();
		} else if (newTypeDashboard == 'databases') {
			openTypeTabsDatabases();
		} else if (newTypeDashboard == 'tools') {
			openTypeTabsTools();
		}

		hiddenMainPreloader();
	});

	// при нажатии на лого открываем dashboard
	$('.main_logo_title, .section_main_bg_header .main_logo').click(function(){
		if ($('.content_game').css('display') == 'block') { // если главное окно
			if ($('.dashboard_item_active').attr('data-dashboard') == 'dashboard') {
				return false;
			}

			// socket
			mySocketLastAction = 'openTypeTabsDashboard';
			var message = {
				'op': 'openTypeTabsDashboard',
				'parameters': {},
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
	        };
	        sendMessageSocket(JSON.stringify(message));

	        // function
			viewMainPreloader(defaultLoaderLoadingText);

			hiddenAllTypeTabs();

			$('.dashboard_tabs[data-dashboard="dashboard"]').addClass('dashboard_tabs_active');
			$('.dashboard_item[data-dashboard="dashboard"]').addClass('dashboard_item_active');

			uploadTypeTabsDashboardStep($('#section_game').attr('data-last-open-dashboard'));

			hiddenMainPreloader();
		} else if ($('.content_hints').css('display') == 'block') { // если окно подсказок
			// socket
			mySocketLastAction = 'hiddenHintWindow-openMainGameWindow-openTypeTabsDashboard';
			var message = {
				'op': 'hiddenHintWindow-openMainGameWindow-openTypeTabsDashboard',
				'parameters': {},
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
	        };
	        sendMessageSocket(JSON.stringify(message));

			// function
			hiddenHintWindow();

			openMainGameWindow();
			loadScoreActualMain();

			if ($('.dashboard_item_active').attr('data-dashboard') == 'dashboard') {
				return false;
			}

			viewMainPreloader(defaultLoaderLoadingText);

			hiddenAllTypeTabs();

			$('.dashboard_tabs[data-dashboard="dashboard"]').addClass('dashboard_tabs_active');
			$('.dashboard_item[data-dashboard="dashboard"]').addClass('dashboard_item_active');

			uploadTypeTabsDashboardStep($('#section_game').attr('data-last-open-dashboard'));

			hiddenMainPreloader();
		}
	});

	// Открыть тип табов: dashboard
	function openTypeTabsDashboard() {
		// socket
		mySocketLastAction = 'openTypeTabsDashboard';
		var message = {
			'op': 'openTypeTabsDashboard',
			'parameters': {},
			'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
			'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
        };
        sendMessageSocket(JSON.stringify(message));

        // function
		$('.dashboard_tabs[data-dashboard="dashboard"]').addClass('dashboard_tabs_active');
		$('.dashboard_item[data-dashboard="dashboard"]').addClass('dashboard_item_active');

		uploadTypeTabsDashboardStep($('#section_game').attr('data-last-open-dashboard'));
	}

	// загрузить конкретный экран (с переключателем табов) для dashboard
	function uploadTypeTabsDashboardStep(step) {
		var formData = new FormData();
    	formData.append('op', 'uploadTypeTabsDashboardStep');
    	formData.append('lang_abbr', $('html').attr('lang'));
    	formData.append('step', step);

    	$.ajax({
			url: '/ajax/ajax.php',
	        type: "POST",
	        dataType: "json",
	        cache: false,
	        contentType: false,
	        processData: false,
	        data: formData,
			success: function(json) {
				if (json.titles) {
					$('.dashboard_tabs[data-dashboard="dashboard"] .dashboard_tab_titles').html(json.titles);
				}
				if (json.content) {
					$('.dashboard_tabs[data-dashboard="dashboard"] .dashboard_tab_content_item_wrapper').html(json.content);
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {	
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}

	// new mission - первый экран - ввод названия миссии
	$('.dashboard_tabs[data-dashboard="dashboard"]').on('click', '.dashboard_tab_content_item_new_mission_accept', function(e){
		newMissionAcceptClick();
	});

	// new mission - первый экран - ввод названия миссии. Также срабатывает при нажатии на Enter
	$('.dashboard_tabs[data-dashboard="dashboard"]').on('keyup', '.dashboard_tab_content_item_new_mission_input', function(e){
		if (e.which == 13) {
			newMissionAcceptClick();
		} else {
			// socket
			mySocketLastAction = 'acceptMissionKeyup';
			var message = {
				'op': 'acceptMissionKeyup',
				'parameters': {
					'mission_name': $(this).val()
				},
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
	        };
	        sendMessageSocket(JSON.stringify(message));
		}
	});

	function newMissionAcceptClick() {
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
					$('.dashboard_tab_content_item_new_mission_error').removeClass('dashboard_tab_content_item_new_mission_error_active').html('');

					// socket
					mySocketLastAction = 'missionNumberOpenIncomingCall';
					var message = {
						'op': 'missionNumberOpenIncomingCall',
						'parameters': {},
						'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
						'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			        };
			        sendMessageSocket(JSON.stringify(message));

					// открываем попап входящего звонка
					newMissionOpenIncomingCall();
				} else if (json.error) {
					$('.dashboard_tab_content_item_new_mission_error').html(json.error).addClass('dashboard_tab_content_item_new_mission_error_active');

					// socket
					mySocketLastAction = 'missionNumberError';
					if (json.error_lang) {
						var message = {
							'op': 'missionNumberError',
							'parameters': {
								'error_lang': json.error_lang
							},
							'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
							'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				        };
				    } else {
				    	var message = {
							'op': 'missionNumberError',
							'parameters': {
								'error': json.error
							},
							'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
							'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				        };
				    }
			        sendMessageSocket(JSON.stringify(message));
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {	
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}

	// new mission - открыть попап входящего звонка
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
		music_before = music;
		if (music) {
			stopMusic();
		}

		if (!incomingAudio || !isPlaying(incomingAudio)) {
			incomingAudio = new Audio;
			incomingAudio.src = '/music/incoming.mp3';
			incomingAudio.play();

			incomingMusicTimer = setInterval(function(){
				incomingAudio = new Audio;
				incomingAudio.src = '/music/incoming.mp3';
				incomingAudio.play();
			}, incomingMusicDuration);
		}

		// отображаем окошко
		$('#popup_video_phone').fadeIn(200);
	}

	// new mission - закрыть попап входящего звонка
	$('body').on('click', '.popup_video_phone_incoming_new_mission .popup_video_phone_bg, .popup_video_phone_incoming_new_mission .popup_video_phone_btn_decline_wrapper', function(e){
		// socket
		mySocketLastAction = 'missionNumberCloseIncomingCall';
		var message = {
			'op': 'missionNumberCloseIncomingCall',
			'parameters': {},
			'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
			'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
        };
        sendMessageSocket(JSON.stringify(message));

		// останавливаем звук звонка и запускаем фоновое
		if (music_before) {
			playMusic();
		}
		music_before = false;

		clearInterval(incomingMusicTimer);
		incomingMusicTimer = false;
		incomingAudio.pause();

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
		mySocketLastAction = 'acceptMissionIncomingCallAccept';
		var message = {
			'op': 'acceptMissionIncomingCallAccept',
			'parameters': {},
			'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
			'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
        };
        sendMessageSocket(JSON.stringify(message));

		// запускаем фотоновую музыку, если была
		if (music_before) {
			playMusic();
		}
		music_before = false;

		// останавливаем звук звонка
		clearInterval(incomingMusicTimer);
		incomingMusicTimer = false;
		incomingAudio.pause();

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
		openFileVideoPopup(0, 'video/video_jane_1.mp4', '', 'new_mission_answer_incoming_video', 'call');
		playVideo('call');

		// когда видео доиграло до конца, то закрываем и производим нужные действия
		$('.new_mission_answer_incoming_video video').on('ended', function() {
			closePopupVideo();

			var timerSeconds = parseInt($('.timer').attr('data-timer'), 10);
			if (timerSeconds == 0) {
				// socket
				mySocketLastAction = 'closePopupVideoAndAcceptMission';
				var message = {
					'op': 'closePopupVideoAndAcceptMission',
					'parameters': {},
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
		        };
		        sendMessageSocket(JSON.stringify(message));

		        acceptMission();
		    }
		});

		// сохранить время просмотра видео в списке звонков команды
		var formData = new FormData();
    	formData.append('op', 'updateDatetimeCall');
    	formData.append('lang_abbr', $('html').attr('lang'));
    	formData.append('call_id', 1);

    	$.ajax({
			url: '/ajax/ajax.php',
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

		// databases
		$('#section_game').attr('data-last-open-databases', 'databases_start_four');

		var formData = new FormData();
    	formData.append('op', 'updateLastDatabase');
    	formData.append('database', 'databases_start_four');

    	$.ajax({
			url: '/ajax/ajax.php',
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

		uploadTypeTabsDatabasesStep($('#section_game').attr('data-last-open-databases'), false);
	});

	// new mission - закрыть попап с видео - игрок принял миссию
	$('body').on('click', '.new_mission_answer_incoming_video .popup_video_bg, .new_mission_answer_incoming_video .popup_video_close', function(e){
		// function
		stopVideo();
		closePopupVideo();

		// принятие миссии запускаем единожды
		var timerSeconds = parseInt($('.timer').attr('data-timer'), 10);
		if (timerSeconds == 0) {
			playVideoSeeking = true;

			// socket
			mySocketLastAction = 'stopVideoAndClosePopupVideoAndAcceptMission';
			var message = {
				'op': 'stopVideoAndClosePopupVideoAndAcceptMission',
				'parameters': {},
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
	        };
	        sendMessageSocket(JSON.stringify(message));

	        acceptMission();
	    } else {
	    	// socket
	    	mySocketLastAction = 'stopVideoAndClosePopupVideo';
			var message = {
				'op': 'stopVideoAndClosePopupVideo',
				'parameters': {},
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
	        };
	        sendMessageSocket(JSON.stringify(message));
	    }
	});

	// игрок принял миссию - просмотрел incoming video
	function acceptMission() {
		// запускаем единожды
		// var timerSeconds = parseInt($('.timer').attr('data-timer'), 10);
		// if (timerSeconds == 0) {
			/*// socket
			var message = {
				'op': 'acceptMission',
				'parameters': {},
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
	        };
	        sendMessageSocket(JSON.stringify(message));*/

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
						incrementScore(100, 'main');

						updateTimerUploadPage();

						// обновляем окно с подсказками
						$('.active_hints .active_hints_value_middle_scroll').mCustomScrollbar('destroy');

						$('.active_hints .active_hints_value_middle_scroll').html('');
						if (json.success_hint_left) {
							$('.active_hints .active_hints_value_middle_scroll').html($.trim(json.success_hint_left));
						}

						$('.list_hints .list_hints_content_right').html('');
						if (json.success_hint_right) {
							$('.list_hints .list_hints_content_right').html(json.success_hint_right);
						}
						
						$('.list_hints .list_hints_content_left_title').html('');
						if (json.success_hint_right_title) {
							$('.list_hints .list_hints_content_left_title').html(json.success_hint_right_title);
						}
						
						$('.list_hints .list_hints_content_left_text').html('');
						if (json.success_hint_right_text) {
							$('.list_hints .list_hints_content_left_text').html(json.success_hint_right_text);
						}

						// скролл для текста подсказок, которые открыты. Текст слева
						$('.active_hints .active_hints_value_middle_scroll').mCustomScrollbar({
							scrollInertia: 700,
							scrollbarPosition: "inside"
						});

						// Обновить к-во непрочитанных файлов
						updateDontOpenFilesQt();

						// Обновить к-во неоткрытых баз данных
						updateDontOpenDatabasesQt();
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert('Error');
					console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});

	    	// отображаем блок Mission name GEM
			$('.dashboard_gem_wrapper').addClass('dashboard_gem_wrapper_active');

			// обновляем содержимое dashboard
			$('#section_game').attr('data-last-open-dashboard', 'company_name');
			uploadTypeTabsDashboardStep('company_name');

			// обновляем атрибут содержимого calls
			$('#section_game').attr('data-last-open-calls', 'call_list');

			// отображаем блок Call Jane
			$('.call_jane').addClass('call_jane_active');
		// }
	}

	// company investigate
	$('.dashboard_tabs[data-dashboard="dashboard"]').on('keyup', '.dashboard_tab_content_item_company_name_input', function(e){
		if ($('.dashboard_tab_content_item_company_name_investigate').length) {
			if (e.which == 13) {
				$('.dashboard_tab_content_item_company_name_investigate').trigger('click');
			} else {
				// socket
				mySocketLastAction = 'dashboardCompanyNameKeyup';
				var message = {
					'op': 'dashboardCompanyNameKeyup',
					'parameters': {
						'company_name': $(this).val()
					},
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
		        };
		        sendMessageSocket(JSON.stringify(message));
			}
		}
	});

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
			mySocketLastAction = 'dashboardCompanyNameNoEmptyFields';
			var message = {
				'op': 'dashboardCompanyNameNoEmptyFields',
				'parameters': {
					'company_name': companyName
				},
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
	        };
	        sendMessageSocket(JSON.stringify(message));

			// звук поиска
			setTimeout(function(){
				dataTransferAudio = new Audio;
				dataTransferAudio.src = '/music/data_transfer.mp3';
				dataTransferAudio.play();
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

						// скрываем попап
						$('#popup_data_transfer').fadeOut(200);

						// правильный ли ответ
						var checkCompanyName = companyName.toLowerCase();
						checkCompanyName = checkCompanyName.replace('«', '');
						checkCompanyName = checkCompanyName.replace('»', '');
						checkCompanyName = checkCompanyName.replace('"', '');

						if (checkCompanyName == 'green pace' || checkCompanyName == 'green pace group') {
							dataTransferAudio.pause();

							// звук успешного выполнения
							successAudio = new Audio;
							successAudio.src = '/music/done.mp3';
							successAudio.play();

							// попап с текстом успеха
							$('#popup_success .popup_success_input').html(text163);
							$('#popup_success .popup_success_text').html(text164);
							$('#popup_success .popup_success_close .btn span').html(text165);
							$('#popup_success').css('display','block');
						} else {
							// отображаем попап ошибки
							$('#popup_search_error .popup_search_error_input').html(text161);
							$('#popup_search_error .popup_search_error_text').html(text162);
							$('#popup_search_error').css('display','block');

							// звук ошибки
							dataTransferAudio.pause();

							errorAudio = new Audio;
							errorAudio.src = '/music/error.mp3';
							errorAudio.play();
						}
					}
				}, (dataTransferMusicDuration / dataTransferIteration));

				// отображаем попап с гифкой
				$('#popup_data_transfer').css('display','block');
			}, 210);

			// по окончанию поиска
			setTimeout(function(){
				// скрываем попап
				$('#popup_data_transfer').fadeOut(200);

				// правильный ли ответ
				var checkCompanyName = companyName.toLowerCase();
				checkCompanyName = checkCompanyName.replace('«', '');
				checkCompanyName = checkCompanyName.replace('»', '');
				checkCompanyName = checkCompanyName.replace('"', '');

				if (checkCompanyName == 'green pace' || checkCompanyName == 'green pace group') {
					// останавливаем звук поиска
					dataTransferAudio.pause();

					// звук успешного выполнения
					successAudio = new Audio;
					successAudio.src = '/music/done.mp3';
					successAudio.play();

					// попап с текстом успеха
					$('#popup_success .popup_success_input').html(text163);
					$('#popup_success .popup_success_text').html(text164);
					$('#popup_success .popup_success_close .btn span').html(text165);
					$('#popup_success').css('display','block');
				} else {
					// отображаем попап ошибки
					$('#popup_search_error .popup_search_error_input').html(text161);
					$('#popup_search_error .popup_search_error_text').html(text162);
					$('#popup_search_error').css('display','block');

					// звук ошибки
					dataTransferAudio.pause();

					errorAudio = new Audio;
					errorAudio.src = '/music/error.mp3';
					errorAudio.play();
				}
			}, 7000);
		} else {
			// socket
			mySocketLastAction = 'dashboardCompanyNameEmptyFields';
			var message = {
				'op': 'dashboardCompanyNameEmptyFields',
				'parameters': {
					'company_name_error': (companyName == '') ? true : false
				},
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
	        };
	        sendMessageSocket(JSON.stringify(message));
		}
	});

	// company investigate success
	$('body').on('click', '.popup_success_close', function(e){
		alert('More 40 page');
	});

/* CALLS */
	// Открыть тип табов: calls
	function openTypeTabsCalls() {
		// socket
		mySocketLastAction = 'openTypeTabsCalls';
		var message = {
			'op': 'openTypeTabsCalls',
			'parameters': {},
			'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
			'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
        };
        sendMessageSocket(JSON.stringify(message));

        // function
		$('.dashboard_tabs[data-dashboard="calls"]').addClass('dashboard_tabs_active');
		$('.dashboard_item[data-dashboard="calls"]').addClass('dashboard_item_active');

		uploadTypeTabsCallsStep($('#section_game').attr('data-last-open-calls'));
	}

	// загрузить конкретный экран (с переключателем табов) для calls
	function uploadTypeTabsCallsStep(step) {
		var formData = new FormData();
    	formData.append('op', 'uploadTypeTabsCallsStep');
    	formData.append('lang_abbr', $('html').attr('lang'));
    	formData.append('step', step);

    	$.ajax({
			url: '/ajax/ajax.php',
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
				}
				if (json.content) {
					$('.dashboard_tabs[data-dashboard="calls"] .dashboard_tab_content_item_wrapper').html(json.content);
				}

				if (step == 'call_list') {
					$('.dashboard_tab_content_item_calls_list_tbody').mCustomScrollbar({
						scrollInertia: 700,
						scrollbarPosition: "inside"
					});
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {	
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}

	// повторно просмотреть видео звонка
	$('.dashboard_tabs[data-dashboard="calls"]').on('click', '.dashboard_tab_content_item_calls_list_td_again_btn', function(e){
		// socket
		mySocketLastAction = 'openFileVideoPopupAndPlayVideoCall';
		var message = {
			'op': 'openFileVideoPopupAndPlayVideoCall',
			'parameters': {
				'fileId': 0,
				'path': $(this).attr('data-path'),
				'name': '',
				'classVideo': 'popup_video_type_calls_again',
				'type': 'call'
			},
			'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
			'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
        };
        sendMessageSocket(JSON.stringify(message));

        /*// socket
        mySocketLastAction = 'playVideoCall';
		var message = {
			'op': 'playVideoCall',
			'parameters': {},
			'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
			'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
        };
        sendMessageSocket(JSON.stringify(message));*/

		// function
		playVideoByNotControls = true; // указываем, что запускалось через кнопку Play, а не через Controls
		openFileVideoPopup(0, $(this).attr('data-path'), '', 'popup_video_type_calls_again', 'call');
		playVideo('call');
	});

	// call jane
	$('.dashboard_tabs[data-dashboard="calls"]').on('click', '.call_jane', function(e){
		var formData = new FormData();
    	formData.append('op', 'callJane');
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
					if (json.path) {
						// socket
				        mySocketLastAction = 'callJane';
						var message = {
							'op': 'callJane',
							'parameters': {
								path: json.path
							},
							'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
							'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				        };
				        sendMessageSocket(JSON.stringify(message));

						// запускаем отображение времени
						updateOutgoingTime();
						outgoingCallTimer = setInterval(function(){
							updateOutgoingTime();
						}, 1000);

						// звук вызова
						music_before = music;
						if (music) {
							stopMusic();
						}

						outgoingAudio = new Audio;
						outgoingAudio.src = '/music/outgoing.mp3';
						outgoingAudio.play();

						$('#popup_video_phone_outgoing').fadeIn(200);

						// и сразу же через пару секунд запускаем видео. Типа ответила на звонок
						setTimeout(function(){
							if (isPlaying(outgoingAudio)) {
								// socket
						        mySocketLastAction = 'callJaneOutgoingAccept';
								var message = {
									'op': 'callJaneOutgoingAccept',
									'parameters': {
										fileId: 0,
										path: json.path,
										name: '',
										classVideo: 'popup_video_type_calls_to_jane',
										type: 'call'
									},
									'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
									'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
						        };
						        sendMessageSocket(JSON.stringify(message));

								// останавливаем звук звонка
								outgoingAudio.pause();

								if (music_before) {
									playMusic(); // фикс для звучания музыки позже
								}
								music_before = false;

								// останавливаем обновление времени
								clearInterval(outgoingCallTimer);
								outgoingCallTimer = false;

								// скрываем блок с телефоном
								$('#popup_video_phone_outgoing').fadeOut(200);

								// открыть видео и сразу запустить его
								playVideoByNotControls = true; // указываем, что запускалось через кнопку Play, а не через Controls
								openFileVideoPopup(0, json.path, '', 'popup_video_type_calls_to_jane', 'call');
								playVideo('call');

								// обновляем содержимое вкладки call с учетом совершенного звонка
								uploadTypeTabsCallsStep($('#section_game').attr('data-last-open-calls'));
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
		mySocketLastAction = 'callJaneOutgoingDecline';
		var message = {
			'op': 'callJaneOutgoingDecline',
			'parameters': {},
			'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
			'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
        };
        sendMessageSocket(JSON.stringify(message));

		// останавливаем звук звонка и запускаем фоновое
		outgoingAudio.pause();

		if (music_before) {
			playMusic();
		}
		music_before = false;

		// останавливаем обновление времени
		clearInterval(outgoingCallTimer);
		outgoingCallTimer = false;

		// скрываем блок с телефоном
		$('#popup_video_phone_outgoing').fadeOut(200);
	});

/* FILES */
	// Открыть тип табов: files
	function openTypeTabsFiles() {
		// socket
		mySocketLastAction = 'openTypeTabsFiles';
		var message = {
			'op': 'openTypeTabsFiles',
			'parameters': {},
			'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
			'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
        };
        sendMessageSocket(JSON.stringify(message));

        // function
		$('.dashboard_tabs[data-dashboard="files"]').addClass('dashboard_tabs_active');
		$('.dashboard_item[data-dashboard="files"]').addClass('dashboard_item_active');

		uploadTypeTabsFilesStep();
	}

	// загрузить конкретный экран (с переключателем табов) для files
	function uploadTypeTabsFilesStep() {
		var formData = new FormData();
    	formData.append('op', 'uploadTypeTabsFilesStep');
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
				if (json.titles) {
					$('.dashboard_tabs[data-dashboard="files"] .dashboard_tab_titles').html(json.titles);
				}
				if (json.content) {
					$('.dashboard_tabs[data-dashboard="files"] .dashboard_tab_content_item_wrapper').html(json.content);
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {	
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}

	// Обновить к-во непрочитанных файлов
	function updateDontOpenFilesQt() {
		var formData = new FormData();
    	formData.append('op', 'updateDontOpenFilesQt');

    	$.ajax({
			url: '/ajax/ajax.php',
	        type: "POST",
	        dataType: "json",
	        cache: false,
	        contentType: false,
	        processData: false,
	        data: formData,
			success: function(json) {
				$('.dashboard_item[data-dashboard="files"] .dashboard_item_text_qt').html(json.success);

				if (json.success == 0) {
					$('.dashboard_item[data-dashboard="files"] .dashboard_item_text_qt').css('display','none');
				} else {
					$('.dashboard_item[data-dashboard="files"] .dashboard_item_text_qt').css('display','inline-block');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {	
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}

	// открыть файл при нажатии на название в списке файлов в табе справа
	$('.dashboard_tabs[data-dashboard="files"]').on('click', '.dashboard_tab_content_file_item', function(e){
		if ($(this).attr('data-type') == 'video') {
			// socket
			mySocketLastAction = 'openFileVideoPopup';
			var message = {
				'op': 'openFileVideoPopup',
				'parameters': {
					'fileId': $(this).attr('data-file-id'),
					'path': $(this).attr('data-path'),
					'name': $(this).find('.dashboard_tab_content_file_item_name').html(),
					'classVideo': 'popup_video_type_file',
					'type': 'file'
				},
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
	        };
	        sendMessageSocket(JSON.stringify(message));

			// function
			openFileVideoPopup($(this).attr('data-file-id'), $(this).attr('data-path'), $(this).find('.dashboard_tab_content_file_item_name').html(), 'popup_video_type_file', 'file');
		} else if ($(this).attr('data-type') == 'pdf') {
			// socket
			mySocketLastAction = 'openFilePdf';
			var message = {
				'op': 'openFilePdf',
				'parameters': {
					'fileId': $(this).attr('data-file-id'),
					'path': $(this).attr('data-path')
				},
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
	        };
	        sendMessageSocket(JSON.stringify(message));

			if (is_touch_device()) {
		        // function
				$.fancybox.open({
                    src: '/plugins/pdf.js/web/viewer.html?file=' + encodeURIComponent('/' + $(this).attr('data-path')),
                    type: 'iframe',
                    opts: {
                        afterShow: function(instance, current) {},
                        beforeShow: function(instance, current) {},
                        beforeClose: function(instance, current) {
                        	// socket
							mySocketLastAction = 'closeFilePdf';
							var message = {
								'op': 'closeFilePdf',
								'parameters': {},
								'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
								'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
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
	                    src: '/' + $(this).attr('data-path'),
	                    type: 'iframe',
	                    opts: {
	                        afterShow: function(instance, current) {
                        		$('.fancybox-container.fancybox-is-open').css('height', ($('#main').outerHeight() * parseFloat((1920 / windowWidth).toFixed(2))) + 'px');
	                        },
	                        beforeShow: function(instance, current) {},
	                        beforeClose: function(instance, current) {
	                        	// socket
								mySocketLastAction = 'closeFilePdf';
								var message = {
									'op': 'closeFilePdf',
									'parameters': {},
									'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
									'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
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
	                    src: '/' + $(this).attr('data-path'),
	                    type: 'iframe',
	                    opts: {
	                        afterShow: function(instance, current) {},
	                        beforeShow: function(instance, current) {},
	                        beforeClose: function(instance, current) {
	                        	// socket
								mySocketLastAction = 'closeFilePdf';
								var message = {
									'op': 'closeFilePdf',
									'parameters': {},
									'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
									'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
						        };
						        sendMessageSocket(JSON.stringify(message));
	                        },
	                        iframe : {
	                            preload : false
	                        }
	                    }
	                });
				}
			}

			// добавляем файл к списку просмотренных
			addFileToViewed($(this).attr('data-file-id'));
		}
	});

	// открыть файл типа видео - попап
	function openFileVideoPopup(fileId, path, name, classVideo, type) {
		playVideoSeeking = false;

		if ($('#popup_video').length) {
			$('#popup_video').removeClass().addClass(classVideo);

			$('#popup_video_mp4 source').attr('src', '/' + path);
			$('#popup_video .popup_video_container_inner').attr('data-file-id', fileId).attr('data-type', type);

			if (type == 'file') {
				$('#popup_video_mp4').attr('controls', 'controls');
			} else if (type == 'call') {
				$('#popup_video_mp4').removeAttr('controls');

				name = ''; // обнуляем на всякий случай заголовок для звонков. НЕ выводим заголовок
			}

			$('#popup_video_mp4')[0].load();

			$('#popup_video .popup_video_inner_title').html(name);
			$('#popup_video .popup_video_play').css('display','block');
			$('#popup_video').fadeIn(200);
		}
	}

	// закрыть попап с видео
	$('body').on('click', '.popup_video_type_file .popup_video_bg, .popup_video_type_file .popup_video_close, .popup_video_type_calls_again .popup_video_bg, .popup_video_type_calls_again .popup_video_close', function(e){
		// setTimeout(function(){
			// socket
			mySocketLastAction = 'stopVideoAndClosePopupVideo';
			var message = {
				'op': 'stopVideoAndClosePopupVideo',
				'parameters': {},
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
	        };
	        sendMessageSocket(JSON.stringify(message));
	    // }, 100);

		// function
		stopVideo();
		closePopupVideo();
	});
	$('body').on('click', '.popup_video_type_calls_to_jane .popup_video_bg, .popup_video_type_calls_to_jane .popup_video_close', function(e){
		// setTimeout(function(){
			// socket
			mySocketLastAction = 'stopVideoAndClosePopupVideo';
			var message = {
				'op': 'stopVideoAndClosePopupVideo',
				'parameters': {},
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
	        };
	        sendMessageSocket(JSON.stringify(message));
	    // }, 100);

		// function
		stopVideo();
		closePopupVideo();

		/*// запускаем обратно музыку
		if (music_before) {
			playMusic();
		}
		music_before = false;*/
	});

	function closePopupVideo() {
		$('#popup_video').fadeOut(200);

		setTimeout(function(){
			$('#popup_video').removeClass();

			$('#popup_video .popup_video_container_inner').attr('data-file-id', 0);

			$('#popup_video .popup_video_inner_title').html('');
		}, 210);
	}

	// запустить проигрывание файла видео
	$('#popup_video').on('click', '.popup_video_play', function(e){
		// socket
		mySocketLastAction = 'playVideoFile';
		var message = {
			'op': 'playVideoFile',
			'parameters': {},
			'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
			'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
        };
        sendMessageSocket(JSON.stringify(message));

        // function
		playVideoByNotControls = true; // указываем, что запускалось через кнопку Play, а не через Controls

		playVideo('file');
	});

	function playVideo(type) {
		console.log('playVideo');
		if ($('#popup_video_mp4').length) {
			music_before = music;
			stopMusic();

			var checkedVideo = $('#popup_video_mp4').get(0);
			if (checkedVideo.paused) {
				checkedVideo.play();
				$('#popup_video .popup_video_play').css('display','none');
				$('#popup_video .popup_video_stop').css('display','block');

				$('#popup_video .popup_video_btns').css('display','none');
			}

			if (type == 'file') {
				// добавляем файл к списку просмотренных
				addFileToViewed($('#popup_video .popup_video_container_inner').attr('data-file-id'));
		    }
		}
	}

	// запуск файла при нажатии на кнопку Play в стандартных Controls
	$('#popup_video #popup_video_mp4').on('play', function(e){
		setTimeout(function(){ // задержка для НЕ отправки данных в сокеты при промотке видео
			if (!playVideoByNotControls && !playVideoSeeking) {
				// socket
				mySocketLastAction = 'playVideoFile';
				var message = {
					'op': 'playVideoFile',
					'parameters': {},
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
		        };
		        sendMessageSocket(JSON.stringify(message));

		        // function
				music_before = music;
				stopMusic();

				$('#popup_video .popup_video_play').css('display','none');
				$('#popup_video .popup_video_stop').css('display','block');

				$('#popup_video .popup_video_btns').css('display','none');

				// добавляем файл к списку просмотренных
				if ($('#popup_video .popup_video_container_inner').attr('data-type') == 'file') {
					addFileToViewed($('#popup_video .popup_video_container_inner').attr('data-file-id'));
				}
			}
	    }, 10);
	});

	// добавить файл к списку просмотренных
	function addFileToViewed(fileId) {
		var formData = new FormData();
    	formData.append('op', 'addFileToActive');
    	formData.append('lang_abbr', $('html').attr('lang'));
    	formData.append('file_id', fileId);

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
					// Обновить к-во непрочитанных файлов
					updateDontOpenFilesQt();
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {	
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}

	// остановить проигрывание файла видео
	$('#popup_video').on('click', '.popup_video_stop', function(e){
		// setTimeout(function(){
			// socket
			mySocketLastAction = 'stopVideo';
			var message = {
				'op': 'stopVideo',
				'parameters': {},
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
	        };
	        sendMessageSocket(JSON.stringify(message));
	    // }, 100);

		stopVideo();
	});

	function stopVideo() {
		playVideoByNotControls = false; // возвращаем к значению по умолчанию

		if ($('#popup_video').length && $('#popup_video_mp4').length) {
			if (music_before) {
				playMusic();
			}
			music_before = false;

			var checkedVideo = $('#popup_video_mp4').get(0);
			if (!checkedVideo.paused) {
				checkedVideo.pause();
				$('#popup_video .popup_video_play').css('display','block');
				$('#popup_video .popup_video_stop').css('display','none');

				$('#popup_video .popup_video_btns').css('display','block');
			}
		}
	}

	// остановить проигрывание видео при нажатии на кнопке Pause в стандартных Controls
	$('#popup_video #popup_video_mp4').on('pause', function(e){
		setTimeout(function(){ // задержка для НЕ отправки данных в сокеты при промотке видео
			if (!playVideoSeeking) {
				playVideoByNotControls = false; // возвращаем к значению по умолчанию

				if (music_before) {
					playMusic();
				}
				music_before = false;

				$('#popup_video .popup_video_play').css('display','block');
				$('#popup_video .popup_video_stop').css('display','none');

				$('#popup_video .popup_video_btns').css('display','block');

				// socket
				mySocketLastAction = 'stopVideo';
				var message = {
					'op': 'stopVideo',
					'parameters': {},
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
		        };
		        sendMessageSocket(JSON.stringify(message));
		    }
	    }, 10);
	});

	// вспомогательная функция. Запущен ли аудио-файл
	function isPlaying(audelem) {
		return !audelem.paused;
	}

	// добавляем отдельный класс для body для touch-дисплеев
	if (is_touch_device()) {
		$('body').addClass('body_touch');
	}

	// действия при перемотке видео
	if ($('#popup_video #popup_video_mp4').length) {
		document.getElementById("popup_video_mp4").onseeking = function(){
			playVideoSeeking = true;
		};
		document.getElementById("popup_video_mp4").onseeked = function(){
			playVideoSeeking = false;
		};
	}

/* DATABASES */
	// Открыть тип табов: databases
	function openTypeTabsDatabases() {
		// socket
		mySocketLastAction = 'openTypeTabsDatabases';
		var message = {
			'op': 'openTypeTabsDatabases',
			'parameters': {},
			'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
			'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
        };
        sendMessageSocket(JSON.stringify(message));

        // function
		$('.dashboard_tabs[data-dashboard="databases"]').addClass('dashboard_tabs_active');
		$('.dashboard_item[data-dashboard="databases"]').addClass('dashboard_item_active');

		uploadTypeTabsDatabasesStep($('#section_game').attr('data-last-open-databases'), false);
	}

	// загрузить конкретный экран (с переключателем табов) для databases
	function uploadTypeTabsDatabasesStep(step, database) {
		var formData = new FormData();
    	formData.append('op', 'uploadTypeTabsDatabasesStep');
    	formData.append('lang_abbr', $('html').attr('lang'));
    	formData.append('step', step);
    	formData.append('database', database);

    	$.ajax({
			url: '/ajax/ajax.php',
	        type: "POST",
	        dataType: "json",
	        cache: false,
	        contentType: false,
	        processData: false,
	        data: formData,
			success: function(json) {
				if (json.titles) {
					$('.dashboard_tabs[data-dashboard="databases"] .dashboard_tab_titles').html(json.titles);
				}
				if (json.content) {
					$('.dashboard_tabs[data-dashboard="databases"] .dashboard_tab_content_item_wrapper').html(json.content);
				}
				$('.dashboard_back_btn').remove();
				if (json.back_btn) {
					$('.dashboard_tabs[data-dashboard="databases"]').append(json.back_btn);
				}

				// к-во непрочитанных баз данных
				$('.dashboard_item[data-dashboard="databases"] .dashboard_item_text_qt').html(json.qt_databases);

				if (json.qt_databases == 0) {
					$('.dashboard_item[data-dashboard="databases"] .dashboard_item_text_qt').css('display','none');
				} else {
					$('.dashboard_item[data-dashboard="databases"] .dashboard_item_text_qt').css('display','inline-block');
				}

				// если есть попапы
				$('#popup_ajax').html('');
				if (json.popup) {
					$('#popup_ajax').html(json.popup);
				}

				// индивидуальные действия для отдельных блоков
				if (step == 'databases_start_four_inner_second_car_register_huilov') { // если блок с результатами поиска car register - huilov
					// слайдер
					if ($('.dashboard_car_register2_slider').length) {
						$('.dashboard_car_register2_slider').slick({
							autoplay: false,
							infinite: true,
							slidesToShow: 1,
							arrows: false
						})
						.on('afterChange', function(event, slick, currentSlide, nextSlide) {
							var currentSlideNumber = (currentSlide ? currentSlide : 0) + 1;
							$('.dashboard_car_register2_slider_arrow_number, .dashboard_car_register2_slider_picture_text span').html(currentSlideNumber);
						});
					}

					// только при первой загрузке экрана
					if ($('.dashboard_car_register2_inner_bubble').length) {
						// печатаем текст
						setTimeout(function(){
							// звуки
							printAudio = new Audio;
							printAudio.src = '/music/print.mp3';
							printAudio.play();

							var bubbleArrayText = {};
	                        bubbleArrayText[0] = (typeof(text92) != "undefined" && text92 !== null) ? text92 : '';
	                        bubbleArrayText[1] = (typeof(text93) != "undefined" && text93 !== null) ? text93 : '';
	                        bubbleArrayText[2] = (typeof(text94) != "undefined" && text94 !== null) ? text94 : '';
	                        bubbleArrayText[3] = (typeof(text95) != "undefined" && text95 !== null) ? text95 : '';
	                        bubbleArrayText[4] = (typeof(text97) != "undefined" && text97 !== null) ? text97 : '';
	                        bubbleArrayText[5] = (typeof(text98) != "undefined" && text98 !== null) ? text98 : '';
	                        bubbleArrayText[6] = (typeof(text99) != "undefined" && text99 !== null) ? text99 : '';
	                        bubbleArrayText[7] = (typeof(text100) != "undefined" && text100 !== null) ? text100 : '';
	                        bubbleArrayText[8] = (typeof(text101) != "undefined" && text101 !== null) ? text101 : '';
	                        bubbleArrayText[9] = (typeof(text102) != "undefined" && text102 !== null) ? text102 : '';
	                        bubbleArrayText[10] = (typeof(text103) != "undefined" && text103 !== null) ? text103 : '';
	                        bubbleArrayText[11] = (typeof(text104) != "undefined" && text104 !== null) ? text104 : '';

	                        function bubbleByNumber(number) {
	                            if ($('.dashboard_car_register2_bubble[data-bubble="' + number + '"]').length) {
	                                var $element = $('.dashboard_car_register2_bubble[data-bubble="' + number + '"]');
	                                var properties = {
	                                    element: $element,
	                                    newText: bubbleArrayText[number],
	                                    letterSpeed: 70,
	                                    callback: function() {
	                                        bubbleByNumber(number + 1);
	                                    }
	                                };
	                                bubbleText(properties);
	                            } else {
	                            	// звуки
	                            	printAudio.pause();
	                            }
	                        }
	                        bubbleByNumber(0);
						}, 1000);

						// добавляем очки
						incrementScore(parseInt($('.score .score_active').html(), 10) + 150, 'main');

						// обновляем mission progress
						incrementProgressMission(10);
					}
				} else if (step == 'databases_start_four_inner_second_personal_files_private_individual_huilov') { // если грузим personal files - private individual - huilov
					// стилизация полосы прокрутки
                    $(".dashboard_personal_files2_private_individuals_huilov_right").mCustomScrollbar({
                        scrollInertia: 700,
                        scrollbarPosition: "inside"
                    });

					// только при первой загрузке экрана
					if ($('.dashboard_personal_files2_private_individuals_huilov_inner_bubble').length) {
						// печатаем текст
						setTimeout(function(){
							// звуки
							printAudio = new Audio;
							printAudio.src = '/music/print.mp3';
							printAudio.play();

							var bubbleArrayText = {};
	                        bubbleArrayText[0] = (typeof(text116) != "undefined" && text116 !== null) ? text116 : '';
	                        bubbleArrayText[1] = (typeof(text118) != "undefined" && text118 !== null) ? text118 : '';
	                        bubbleArrayText[2] = (typeof(text120) != "undefined" && text120 !== null) ? text120 : '';
	                        bubbleArrayText[3] = (typeof(text122) != "undefined" && text122 !== null) ? text122 : '';
	                        bubbleArrayText[4] = (typeof(text124) != "undefined" && text124 !== null) ? text124 : '';
	                        bubbleArrayText[5] = (typeof(text126) != "undefined" && text126 !== null) ? text126 : '';
	                        bubbleArrayText[6] = (typeof(text128) != "undefined" && text128 !== null) ? text128 : '';
	                        bubbleArrayText[7] = (typeof(text130) != "undefined" && text130 !== null) ? text130 : '';
	                        bubbleArrayText[8] = (typeof(text131) != "undefined" && text131 !== null) ? text131 : '';

	                        function bubbleByNumber(number) {
	                            if ($('.private_individuals_huilov_text[data-bubble="' + number + '"]').length) {
	                                var $element = $('.private_individuals_huilov_text[data-bubble="' + number + '"] span');
	                                var properties = {
	                                    element: $element,
	                                    newText: bubbleArrayText[number],
	                                    letterSpeed: 70,
	                                    callback: function() {
	                                        bubbleByNumber(number + 1);
	                                    }
	                                };
	                                bubbleText(properties);
	                            } else {
	                            	// звуки
	                            	printAudio.pause();
	                            }
	                        }
	                        bubbleByNumber(0);
						}, 1000);

						// автоматический скролл блока с данными вниз при печатании текста
						setTimeout(function(){
							$(".dashboard_personal_files2_private_individuals_huilov_right").mCustomScrollbar("scrollTo","last",{scrollInertia:700});
						}, 4000);

						// добавляем очки
						incrementScore(parseInt($('.score .score_active').html(), 10) + 100, 'main');

						// обновляем mission progress
						incrementProgressMission(5);
					}
				} else if (step == 'databases_start_four_inner_second_personal_files_ceo_database_rod') { // если грузим personal files - ceo database - rod
					// только при первой загрузке экрана
					if ($('.dashboard_personal_files2_ceo_database_rod_inner_bubble').length) {
						// печатаем текст
						setTimeout(function(){
							// звуки
							printAudio = new Audio;
							printAudio.src = '/music/print.mp3';
							printAudio.play();

							var bubbleArrayText = {};
	                        bubbleArrayText[0] = (typeof(text133) != "undefined" && text133 !== null) ? text133 : '';
	                        bubbleArrayText[1] = (typeof(text134) != "undefined" && text134 !== null) ? text134 : '';
	                        bubbleArrayText[2] = (typeof(text135) != "undefined" && text135 !== null) ? text135 : '';
	                        bubbleArrayText[3] = (typeof(text137) != "undefined" && text137 !== null) ? text137 : '';
	                        bubbleArrayText[4] = (typeof(text139) != "undefined" && text139 !== null) ? text139 : '';

	                        function bubbleByNumber(number) {
	                            if ($('.private_individuals_huilov_text[data-bubble="' + number + '"]').length) {
	                                var $element = $('.private_individuals_huilov_text[data-bubble="' + number + '"] span');
	                                var properties = {
	                                    element: $element,
	                                    newText: bubbleArrayText[number],
	                                    letterSpeed: 70,
	                                    callback: function() {
	                                        bubbleByNumber(number + 1);
	                                    }
	                                };
	                                bubbleText(properties);
	                            } else {
	                            	// звуки
	                            	printAudio.pause();
	                            }
	                        }
	                        bubbleByNumber(0);
						}, 1000);

						// добавляем очки
						incrementScore(parseInt($('.score .score_active').html(), 10) + 200, 'main');

						// обновляем mission progress
						incrementProgressMission(10);
					}
				} else if (step == 'databases_start_four_inner_first_mobile_calls_messages') { // просмотрены database mobile calls
					// только при первой загрузке экрана
					if ($('.dashboard_mobile_calls2_inner_first').length) {
						// добавляем очки
						incrementScore(parseInt($('.score .score_active').html(), 10) + 100, 'main');

						// обновляем mission progress
						incrementProgressMission(5);
					}
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {	
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}

	// перемещение по табам databases
	$('body').on('click', '.dashboard_tab_title_can_click', function(e){
		$('#section_game').attr('data-last-open-databases', $(this).attr('data-step'));

		uploadTypeTabsDatabasesStep($(this).attr('data-step'), $(this).attr('data-database'));

		// socket
		mySocketLastAction = 'databaseChangeDatabase';
		var message = {
			'op': 'databaseChangeDatabase',
			'parameters': {
				step: $(this).attr('data-step'),
				database: $(this).attr('data-database')
			},
			'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
			'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
        };
        sendMessageSocket(JSON.stringify(message));

		var formData = new FormData();
    	formData.append('op', 'updateLastDatabase');
    	formData.append('database', $(this).attr('data-step'));

    	$.ajax({
			url: '/ajax/ajax.php',
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

	// Обновить к-во новых НЕоткрытых еще баз данных
	function updateDontOpenDatabasesQt() {
		var formData = new FormData();
    	formData.append('op', 'updateDontOpenDatabasesQt');

    	$.ajax({
			url: '/ajax/ajax.php',
	        type: "POST",
	        dataType: "json",
	        cache: false,
	        contentType: false,
	        processData: false,
	        data: formData,
			success: function(json) {
				$('.dashboard_item[data-dashboard="databases"] .dashboard_item_text_qt').html(json.success);

				if (json.success == 0) {
					$('.dashboard_item[data-dashboard="databases"] .dashboard_item_text_qt').css('display','none');
				} else {
					$('.dashboard_item[data-dashboard="databases"] .dashboard_item_text_qt').css('display','inline-block');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {	
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}

	// открыть какую-то конкретную базу данных. Первый основной этап. 4 основные базы
	$('body').on('click', '.dashboard_tab_content_item_start_four_inner_item', function(e){
		if ($(this).attr('data-database') == 'car_register') {
			// в зависимости от очков грузим разные экраны
			if ($('#section_game').attr('data-car-register-complete') == 1) {
				// если нашли уже car register - huilov
				$('#section_game').attr('data-last-open-databases', 'databases_start_four_inner_second_car_register_huilov');

				mySocketLastAction = 'databaseCarRegisterHuilov';
				var message = {
					'op': 'databaseCarRegisterHuilov',
					'parameters': {},
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
		        };
		        sendMessageSocket(JSON.stringify(message));

				var formData = new FormData();
		    	formData.append('op', 'updateLastDatabase');
		    	formData.append('database', 'databases_start_four_inner_second_car_register_huilov');

		    	$.ajax({
					url: '/ajax/ajax.php',
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

				uploadTypeTabsDatabasesStep('databases_start_four_inner_second_car_register_huilov', 'car_register');
			} else {
				$('#section_game').attr('data-last-open-databases', 'databases_start_four_inner_first_car_register');

				mySocketLastAction = 'databaseCarRegister';
				var message = {
					'op': 'databaseCarRegister',
					'parameters': {},
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
		        };
		        sendMessageSocket(JSON.stringify(message));

		        var formData = new FormData();
		    	formData.append('op', 'updateLastDatabase');
		    	formData.append('database', 'databases_start_four_inner_first_car_register');

		    	$.ajax({
					url: '/ajax/ajax.php',
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

				// экран поиска car register
				uploadTypeTabsDatabasesStep('databases_start_four_inner_first_car_register', 'car_register');
			}
		} else if ($(this).attr('data-database') == 'bank_transactions') {
			$('#section_game').attr('data-last-open-databases', 'databases_start_four_inner_first_bank_transactions');

			mySocketLastAction = 'databaseBankTransactions';
			var message = {
				'op': 'databaseBankTransactions',
				'parameters': {},
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
	        };
	        sendMessageSocket(JSON.stringify(message));

			var formData = new FormData();
	    	formData.append('op', 'updateLastDatabase');
	    	formData.append('database', 'databases_start_four_inner_first_bank_transactions');

	    	$.ajax({
				url: '/ajax/ajax.php',
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

			uploadTypeTabsDatabasesStep('databases_start_four_inner_first_bank_transactions', 'bank_transactions');
		} else if ($(this).attr('data-database') == 'personal_files') {
			$('#section_game').attr('data-last-open-databases', 'databases_start_four_inner_first_personal_files');

			mySocketLastAction = 'databasePersonalFiles';
			var message = {
				'op': 'databasePersonalFiles',
				'parameters': {},
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
	        };
	        sendMessageSocket(JSON.stringify(message));

			var formData = new FormData();
	    	formData.append('op', 'updateLastDatabase');
	    	formData.append('database', 'databases_start_four_inner_first_personal_files');

	    	$.ajax({
				url: '/ajax/ajax.php',
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

			uploadTypeTabsDatabasesStep('databases_start_four_inner_first_personal_files', 'personal_files');
		} else if ($(this).attr('data-database') == 'mobile_calls') {
			// в зависимости от очков грузим разные экраны
			if ($('#section_game').attr('data-mobile-calls-complete') == 1) {
				// если открывали сообщения
				$('#section_game').attr('data-last-open-databases', 'databases_start_four_inner_first_mobile_calls_messages');

				mySocketLastAction = 'databaseMobileCallsMessages';
				var message = {
					'op': 'databaseMobileCallsMessages',
					'parameters': {},
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
		        };
		        sendMessageSocket(JSON.stringify(message));

				var formData = new FormData();
		    	formData.append('op', 'updateLastDatabase');
		    	formData.append('database', 'databases_start_four_inner_first_mobile_calls_messages');

		    	$.ajax({
					url: '/ajax/ajax.php',
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

				uploadTypeTabsDatabasesStep('databases_start_four_inner_first_mobile_calls_messages', 'mobile_calls');
			} else {
				// экран поиска mobile calls
				$('#section_game').attr('data-last-open-databases', 'databases_start_four_inner_first_mobile_calls');

				mySocketLastAction = 'databaseMobileCalls';
				var message = {
					'op': 'databaseMobileCalls',
					'parameters': {},
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
		        };
		        sendMessageSocket(JSON.stringify(message));

				var formData = new FormData();
		    	formData.append('op', 'updateLastDatabase');
		    	formData.append('database', 'databases_start_four_inner_first_mobile_calls');

		    	$.ajax({
					url: '/ajax/ajax.php',
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

				uploadTypeTabsDatabasesStep('databases_start_four_inner_first_mobile_calls', 'mobile_calls');
			}
		}
	});

	// вернуться назад на предыдущый таб базы данных
	$('body').on('click', '.dashboard_back_btn', function(e){
		$('#section_game').attr('data-last-open-databases', $(this).attr('data-back'));

		uploadTypeTabsDatabasesStep($(this).attr('data-back'), $(this).attr('data-database'));

		// socket
		mySocketLastAction = 'databaseChangeDatabase';
		var message = {
			'op': 'databaseChangeDatabase',
			'parameters': {
				step: $(this).attr('data-back'),
				database: $(this).attr('data-database')
			},
			'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
			'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
        };
        sendMessageSocket(JSON.stringify(message));

		var formData = new FormData();
    	formData.append('op', 'updateLastDatabase');
    	formData.append('database', $(this).attr('data-back'));

    	$.ajax({
			url: '/ajax/ajax.php',
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

	// car register search
	$('.dashboard_tabs[data-dashboard="databases"]').on('keyup', '.dashboard_car_register1_license_plate', function(e){
		if ($('.dashboard_car_register1_search').length) {
			if (e.which == 13) {
				$('.dashboard_car_register1_search').trigger('click');
			} else {
				// socket
				mySocketLastAction = 'databaseCarregisterSearchLicensePlateKeyup';
				var message = {
					'op': 'databaseCarregisterSearchLicensePlateKeyup',
					'parameters': {
						'license_plate': $(this).val()
					},
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
		        };
		        sendMessageSocket(JSON.stringify(message));
			}
		}
	});

	$('body').on('click', '.dashboard_car_register1_search', function(e){
		var err = false;
		var licensePlate = $.trim($('.dashboard_car_register1_license_plate').val());
		var country = $.trim($('.dashboard_car_register1_country').val());
		var date = $.trim($('.dashboard_car_register1_date').val());

		if (licensePlate == '') {
			$('.dashboard_car_register1_license_plate_error').addClass('error_text_database_car_register_active');
			err = true;
		} else {
			$('.dashboard_car_register1_license_plate_error').removeClass('error_text_database_car_register_active');
		}

		if (country == '') {
			$('.dashboard_car_register1_country_error').addClass('error_text_database_car_register_active');
			err = true;
		} else {
			$('.dashboard_car_register1_country_error').removeClass('error_text_database_car_register_active');
		}

		if (date == '') {
			$('.dashboard_car_register1_date_error').addClass('error_text_database_car_register_active');
			err = true;
		} else {
			$('.dashboard_car_register1_date_error').removeClass('error_text_database_car_register_active');
		}

		if (!err) {
			// socket
			mySocketLastAction = 'databaseCarRegisterNoEmptyFields';
			var message = {
				'op': 'databaseCarRegisterNoEmptyFields',
				'parameters': {
					'license_plate': licensePlate,
					'country': country,
					'date': date
				},
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
	        };
	        sendMessageSocket(JSON.stringify(message));

			// на всякий случай скрываем окно с ошибкой
			$('#popup_search_error').css('display','none');

			// обнуляем значения в попапе перед отображением
			$('.popup_search_processing_input_upload_text span').html('0');
			$('.popup_search_processing_input_upload_percent').css('width', '0%');

			// отображаем окно поиска
			$('#popup_search_processing').css('display','block');
			$('.popup_search_processing_input_upload_percent').css('opacity', 1);

			// звук поиска, если звуки включены
			searchAudio = new Audio;
			searchAudio.src = '/music/search_database.mp3';
			searchAudio.play();

			// анимация поиска
			var searchInterval = false; // переменная для интервала поиска
			var databaseSearchPlus = Math.round(100 / databaseSearchIteration); // на столько увеличиваем за итерацию

			searchInterval = setInterval(function(){
				var current = parseInt($('.popup_search_processing_input_upload_text span').html(), 10);
				var next = current + selfRandom(1, databaseSearchPlus);

				if (next >= 100) {
					next = 100;
				}

				$('.popup_search_processing_input_upload_percent').css('width', next + '%');
				$('.popup_search_processing_input_upload_text span').html(next);

				if (next == 100) {
					// останавливаем анимацию поиска
					clearInterval(searchInterval);
					searchInterval = false;

					// скрываем попап поиска
					$('#popup_search_processing').css('display','none');

					// проверяем правильная ли комбинация введена
					if (licensePlate.toLowerCase() == 'stalin' && (country == 'Russia' || country == 'Russland') && date == '30.08.2021') {
						// звук успешного выполнения
						searchAudio.pause();

						successAudio = new Audio;
						successAudio.src = '/music/done.mp3';
						successAudio.play();

						// грузим новый таб
						$('#section_game').attr('data-last-open-databases', 'databases_start_four_inner_second_car_register_huilov');

						var formData = new FormData();
				    	formData.append('op', 'updateLastDatabase');
				    	formData.append('database', 'databases_start_four_inner_second_car_register_huilov');

				    	$.ajax({
							url: '/ajax/ajax.php',
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

						uploadTypeTabsDatabasesStep('databases_start_four_inner_second_car_register_huilov', 'car_register');

						// фиксируем, что нашли
						$('#section_game').attr('data-car-register-complete', 1);
					} else {
						// отображаем попап ошибки
						$('#popup_search_error .popup_search_error_input').html(text89);
						$('#popup_search_error .popup_search_error_text').html(text88);
						$('#popup_search_error').css('display','block');

						// звук ошибки
						searchAudio.pause();

						errorAudio = new Audio;
						errorAudio.src = '/music/error.mp3';
						errorAudio.play();
					}
				}
			}, (databaseSearchDuration / databaseSearchIteration));
		} else {
			// socket
			mySocketLastAction = 'databaseCarRegisterEmptyFields';
			var message = {
				'op': 'databaseCarRegisterEmptyFields',
				'parameters': {
					'license_plate_error': (licensePlate == '') ? true : false,
					'country_error': (country == '') ? true : false,
					'date_error': (date == '') ? true : false
				},
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
	        };
	        sendMessageSocket(JSON.stringify(message));
		}
	});

	// закрыть окно с ошибкой поиска
	$('.popup_search_error_bg, .popup_search_error_close').click(function(){
		$('#popup_search_error').fadeOut(200);

		// socket
		mySocketLastAction = 'popupSearchErrorClose';
		var message = {
			'op': 'popupSearchErrorClose',
			'parameters': {},
			'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
			'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
        };
        sendMessageSocket(JSON.stringify(message));
	});

	// huilov slider - to next slide
	$('body').on('click', '.dashboard_car_register2_slider_arrow_right', function(e){
		$('.dashboard_car_register2_slider').slick('slickNext');
	});
	// huilov slider - to prev slide
	$('body').on('click', '.dashboard_car_register2_slider_arrow_left', function(e){
		$('.dashboard_car_register2_slider').slick('slickPrev');
	});

	// открыть personal files - private individual
	$('body').on('click', '.dashboard_personal_files1_category_private_individuals', function(e){
		// в зависимости от очков грузим разные экраны
		if ($('#section_game').attr('data-private-individual-complete') == 1) {
			// если нашли уже private individual - huilov
			$('#section_game').attr('data-last-open-databases', 'databases_start_four_inner_second_personal_files_private_individual_huilov');

			mySocketLastAction = 'databasePersonalFilesPrivateIndividualHuilov';
			var message = {
				'op': 'databasePersonalFilesPrivateIndividualHuilov',
				'parameters': {},
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
	        };
	        sendMessageSocket(JSON.stringify(message));

			var formData = new FormData();
	    	formData.append('op', 'updateLastDatabase');
	    	formData.append('database', 'databases_start_four_inner_second_personal_files_private_individual_huilov');

	    	$.ajax({
				url: '/ajax/ajax.php',
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

			uploadTypeTabsDatabasesStep('databases_start_four_inner_second_personal_files_private_individual_huilov', 'personal_files');
		} else {
			// экран поиска
			$('#section_game').attr('data-last-open-databases', 'databases_start_four_inner_second_personal_files_private_individual');

			mySocketLastAction = 'databasePersonalFilesPrivateIndividual';
			var message = {
				'op': 'databasePersonalFilesPrivateIndividual',
				'parameters': {},
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
	        };
	        sendMessageSocket(JSON.stringify(message));

			var formData = new FormData();
	    	formData.append('op', 'updateLastDatabase');
	    	formData.append('database', 'databases_start_four_inner_second_personal_files_private_individual');

	    	$.ajax({
				url: '/ajax/ajax.php',
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

			uploadTypeTabsDatabasesStep('databases_start_four_inner_second_personal_files_private_individual', 'personal_files');
		}
	});

	// personal files - private individuals search + ceo database search
	$('.dashboard_tabs[data-dashboard="databases"]').on('keyup', '.dashboard_personal_files2_private_individuals_input_wrapper_firstname input[type="text"]', function(e){
		if ($('.dashboard_personal_files2_private_individuals_search').length) {
			if (e.which == 13) {
				$('.dashboard_personal_files2_private_individuals_search').trigger('click');
			} else {
				// socket
				mySocketLastAction = 'databasePersonalFilesPrivateIndividualFirstnameKeyup';
				var message = {
					'op': 'databasePersonalFilesPrivateIndividualFirstnameKeyup',
					'parameters': {
						'firstname': $(this).val()
					},
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
		        };
		        sendMessageSocket(JSON.stringify(message));
			}
		} else if ($('.dashboard_personal_files2_ceo_database_search').length) {
			if (e.which == 13) {
				$('.dashboard_personal_files2_ceo_database_search').trigger('click');
			} else {
				// socket
				mySocketLastAction = 'databasePersonalFilesPrivateIndividualFirstnameKeyup';
				var message = {
					'op': 'databasePersonalFilesPrivateIndividualFirstnameKeyup',
					'parameters': {
						'firstname': $(this).val()
					},
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
		        };
		        sendMessageSocket(JSON.stringify(message));
			}
		}
	});
	$('.dashboard_tabs[data-dashboard="databases"]').on('keyup', '.dashboard_personal_files2_private_individuals_input_wrapper_lastname input[type="text"]', function(e){
		if ($('.dashboard_personal_files2_private_individuals_search').length) {
			if (e.which == 13) {
				$('.dashboard_personal_files2_private_individuals_search').trigger('click');
			} else {
				// socket
				mySocketLastAction = 'databasePersonalFilesPrivateIndividualLastnameKeyup';
				var message = {
					'op': 'databasePersonalFilesPrivateIndividualLastnameKeyup',
					'parameters': {
						'lastname': $(this).val()
					},
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
		        };
		        sendMessageSocket(JSON.stringify(message));
			}
		} else if ($('.dashboard_personal_files2_ceo_database_search').length) {
			if (e.which == 13) {
				$('.dashboard_personal_files2_ceo_database_search').trigger('click');
			} else {
				// socket
				mySocketLastAction = 'databasePersonalFilesPrivateIndividualLastnameKeyup';
				var message = {
					'op': 'databasePersonalFilesPrivateIndividualLastnameKeyup',
					'parameters': {
						'lastname': $(this).val()
					},
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
		        };
		        sendMessageSocket(JSON.stringify(message));
			}
		}
	});

	$('body').on('click', '.dashboard_personal_files2_private_individuals_search', function(e){
		var err = false;
		var firstname = $.trim($('.dashboard_personal_files2_private_individuals_input_wrapper_firstname input').val());
		var lastname = $.trim($('.dashboard_personal_files2_private_individuals_input_wrapper_lastname input').val());

		if (firstname == '') {
			$('.dashboard_personal_files2_private_individuals_firstname_error').addClass('error_text_database_car_register_active');
			err = true;
		} else {
			$('.dashboard_personal_files2_private_individuals_firstname_error').removeClass('error_text_database_car_register_active');
		}

		if (lastname == '') {
			$('.dashboard_personal_files2_private_individuals_lastname_error').addClass('error_text_database_car_register_active');
			err = true;
		} else {
			$('.dashboard_personal_files2_private_individuals_lastname_error').removeClass('error_text_database_car_register_active');
		}

		if (!err) {
			// socket
			mySocketLastAction = 'databasePersonalFilesPrivateIndividualsNoEmptyFields';
			var message = {
				'op': 'databasePersonalFilesPrivateIndividualsNoEmptyFields',
				'parameters': {
					'firstname': firstname,
					'lastname': lastname
				},
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
	        };
	        sendMessageSocket(JSON.stringify(message));

			// на всякий случай скрываем окно с ошибкой
			$('#popup_search_error').css('display','none');

			// обнуляем значения в попапе перед отображением
			$('.popup_search_processing_input_upload_text span').html('0');
			$('.popup_search_processing_input_upload_percent').css('width', '0%');

			// отображаем окно поиска
			$('#popup_search_processing').css('display','block');
			$('.popup_search_processing_input_upload_percent').css('opacity', 1);

			// звук поиска, если звуки включены
			searchAudio = new Audio;
			searchAudio.src = '/music/search_database.mp3';
			searchAudio.play();

			// анимация поиска
			var searchInterval = false; // переменная для интервала поиска
			var databaseSearchPlus = Math.round(100 / databaseSearchIteration); // на столько увеличиваем за итерацию

			searchInterval = setInterval(function(){
				var current = parseInt($('.popup_search_processing_input_upload_text span').html(), 10);
				var next = current + selfRandom(1, databaseSearchPlus);

				if (next >= 100) {
					next = 100;
				}

				$('.popup_search_processing_input_upload_percent').css('width', next + '%');
				$('.popup_search_processing_input_upload_text span').html(next);

				if (next == 100) {
					// останавливаем анимацию поиска
					clearInterval(searchInterval);
					searchInterval = false;

					// скрываем попап поиска
					$('#popup_search_processing').css('display','none');

					// проверяем правильная ли комбинация введена
					if (firstname.toLowerCase() == 'vladimir' && lastname.toLowerCase() == 'huilov') {
						// звук успешного выполнения
						searchAudio.pause();

						successAudio = new Audio;
						successAudio.src = '/music/done.mp3';
						successAudio.play();

						// грузим новый таб
						$('#section_game').attr('data-last-open-databases', 'databases_start_four_inner_second_personal_files_private_individual_huilov');

						var formData = new FormData();
				    	formData.append('op', 'updateLastDatabase');
				    	formData.append('database', 'databases_start_four_inner_second_personal_files_private_individual_huilov');

				    	$.ajax({
							url: '/ajax/ajax.php',
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

						uploadTypeTabsDatabasesStep('databases_start_four_inner_second_personal_files_private_individual_huilov', 'car_register');

						// фиксируем, что нашли
						$('#section_game').attr('data-private-individual-complete', 1);
					} else {
						// отображаем попап ошибки
						$('#popup_search_error .popup_search_error_input').html(text89);
						$('#popup_search_error .popup_search_error_text').html(text88);
						$('#popup_search_error').css('display','block');

						// звук ошибки
						searchAudio.pause();

						errorAudio = new Audio;
						errorAudio.src = '/music/error.mp3';
						errorAudio.play();
					}
				}
			}, (databaseSearchDuration / databaseSearchIteration));
		} else {
			// socket
			mySocketLastAction = 'databasePersonalFilesPrivateIndividualsEmptyFields';
			var message = {
				'op': 'databasePersonalFilesPrivateIndividualsEmptyFields',
				'parameters': {
					'firstname_error': (firstname == '') ? true : false,
					'lastname_error': (lastname == '') ? true : false
				},
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
	        };
	        sendMessageSocket(JSON.stringify(message));
		}
	});

	// открыть personal files - ceo database
	$('body').on('click', '.dashboard_personal_files1_category_ceo_database', function(e){
		// в зависимости от очков грузим разные экраны
		if ($('#section_game').attr('data-ceo-database-complete') == 1) {
			// если нашли уже ceo database - rod
			$('#section_game').attr('data-last-open-databases', 'databases_start_four_inner_second_personal_files_ceo_database_rod');

			mySocketLastAction = 'databasePersonalFilesAxelRod';
			var message = {
				'op': 'databasePersonalFilesAxelRod',
				'parameters': {},
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
	        };
	        sendMessageSocket(JSON.stringify(message));

			var formData = new FormData();
	    	formData.append('op', 'updateLastDatabase');
	    	formData.append('database', 'databases_start_four_inner_second_personal_files_ceo_database_rod');

	    	$.ajax({
				url: '/ajax/ajax.php',
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

			uploadTypeTabsDatabasesStep('databases_start_four_inner_second_personal_files_ceo_database_rod', 'personal_files');
		} else {
			$('#section_game').attr('data-last-open-databases', 'databases_start_four_inner_second_personal_files_ceo_database');

			mySocketLastAction = 'databasePersonalFilesCeoDatabase';
			var message = {
				'op': 'databasePersonalFilesCeoDatabase',
				'parameters': {},
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
	        };
	        sendMessageSocket(JSON.stringify(message));

			// экран поиска
			var formData = new FormData();
	    	formData.append('op', 'updateLastDatabase');
	    	formData.append('database', 'databases_start_four_inner_second_personal_files_ceo_database');

	    	$.ajax({
				url: '/ajax/ajax.php',
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

			uploadTypeTabsDatabasesStep('databases_start_four_inner_second_personal_files_ceo_database', 'personal_files');
		}
	});

	// personal files - ceo database search
	/*$('.dashboard_tabs[data-dashboard="databases"]').on('keyup', '.dashboard_personal_files2_private_individuals_input_wrapper_firstname input[type="text"]', function(e){
		if (e.which == 13 && $('.dashboard_personal_files2_ceo_database_search').length) {
			$('.dashboard_personal_files2_ceo_database_search').trigger('click');
		}
	});
	$('.dashboard_tabs[data-dashboard="databases"]').on('keyup', '.dashboard_personal_files2_private_individuals_input_wrapper_lastname input[type="text"]', function(e){
		if (e.which == 13 && $('.dashboard_personal_files2_ceo_database_search').length) {
			$('.dashboard_personal_files2_ceo_database_search').trigger('click');
		}
	});*/

	$('body').on('click', '.dashboard_personal_files2_ceo_database_search', function(e){
		var err = false;
		var firstname = $.trim($('.dashboard_personal_files2_private_individuals_input_wrapper_firstname input').val());
		var lastname = $.trim($('.dashboard_personal_files2_private_individuals_input_wrapper_lastname input').val());

		if (firstname == '') {
			$('.dashboard_personal_files2_private_individuals_firstname_error').addClass('error_text_database_car_register_active');
			err = true;
		} else {
			$('.dashboard_personal_files2_private_individuals_firstname_error').removeClass('error_text_database_car_register_active');
		}

		if (lastname == '') {
			$('.dashboard_personal_files2_private_individuals_lastname_error').addClass('error_text_database_car_register_active');
			err = true;
		} else {
			$('.dashboard_personal_files2_private_individuals_lastname_error').removeClass('error_text_database_car_register_active');
		}

		if (!err) {
			// socket
			mySocketLastAction = 'databasePersonalFilesCeoDatabaseNoEmptyFields';
			var message = {
				'op': 'databasePersonalFilesCeoDatabaseNoEmptyFields',
				'parameters': {
					'firstname': firstname,
					'lastname': lastname
				},
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
	        };
	        sendMessageSocket(JSON.stringify(message));

			// на всякий случай скрываем окно с ошибкой
			$('#popup_search_error').css('display','none');

			// обнуляем значения в попапе перед отображением
			$('.popup_search_processing_input_upload_text span').html('0');
			$('.popup_search_processing_input_upload_percent').css('width', '0%');

			// отображаем окно поиска
			$('#popup_search_processing').css('display','block');
			$('.popup_search_processing_input_upload_percent').css('opacity', 1);

			// звук поиска, если звуки включены
			searchAudio = new Audio;
			searchAudio.src = '/music/search_database.mp3';
			searchAudio.play();

			// анимация поиска
			var searchInterval = false; // переменная для интервала поиска
			var databaseSearchPlus = Math.round(100 / databaseSearchIteration); // на столько увеличиваем за итерацию

			searchInterval = setInterval(function(){
				var current = parseInt($('.popup_search_processing_input_upload_text span').html(), 10);
				var next = current + selfRandom(1, databaseSearchPlus);

				if (next >= 100) {
					next = 100;
				}

				$('.popup_search_processing_input_upload_percent').css('width', next + '%');
				$('.popup_search_processing_input_upload_text span').html(next);

				if (next == 100) {
					// останавливаем анимацию поиска
					clearInterval(searchInterval);
					searchInterval = false;

					// скрываем попап поиска
					$('#popup_search_processing').css('display','none');

					// проверяем правильная ли комбинация введена
					if (firstname.toLowerCase() == 'axel' && lastname.toLowerCase() == 'rod') {
						// звук успешного выполнения
						searchAudio.pause();

						successAudio = new Audio;
						successAudio.src = '/music/done.mp3';
						successAudio.play();

						// грузим новый таб
						$('#section_game').attr('data-last-open-databases', 'databases_start_four_inner_second_personal_files_ceo_database_rod');

						var formData = new FormData();
				    	formData.append('op', 'updateLastDatabase');
				    	formData.append('database', 'databases_start_four_inner_second_personal_files_ceo_database_rod');

				    	$.ajax({
							url: '/ajax/ajax.php',
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

						uploadTypeTabsDatabasesStep('databases_start_four_inner_second_personal_files_ceo_database_rod', 'personal_files');

						// фиксируем, что нашли
						$('#section_game').attr('data-ceo-database-complete', 1);
					} else {
						// отображаем попап ошибки
						$('#popup_search_error .popup_search_error_input').html(text89);
						$('#popup_search_error .popup_search_error_text').html(text88);
						$('#popup_search_error').css('display','block');

						// звук ошибки
						searchAudio.pause();

						errorAudio = new Audio;
						errorAudio.src = '/music/error.mp3';
						errorAudio.play();
					}
				}
			}, (databaseSearchDuration / databaseSearchIteration));
		} else {
			// socket
			mySocketLastAction = 'databasePersonalFilesCeoDatabaseEmptyFields';
			var message = {
				'op': 'databasePersonalFilesCeoDatabaseEmptyFields',
				'parameters': {
					'firstname_error': (firstname == '') ? true : false,
					'lastname_error': (lastname == '') ? true : false
				},
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
	        };
	        sendMessageSocket(JSON.stringify(message));
		}
	});

	// mobile calls search
	$('.dashboard_tabs[data-dashboard="databases"]').on('keyup', '.dashboard_mobile_calls1_number', function(e){
		if ($('.dashboard_mobile_calls1_search').length) {
			if (e.which == 13) {
				$('.dashboard_mobile_calls1_search').trigger('click');
			} else {
				// socket
				mySocketLastAction = 'databaseMobileCallsNumberKeyup';
				var message = {
					'op': 'databaseMobileCallsNumberKeyup',
					'parameters': {
						'number': $(this).val()
					},
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
		        };
		        sendMessageSocket(JSON.stringify(message));
			}
		}
	});

	$('body').on('click', '.dashboard_mobile_calls1_search', function(e){
		var err = false;
		var countryCode = $.trim($('.dashboard_mobile_calls1_country_code').val());
		var number = $.trim($('.dashboard_mobile_calls1_number').val());

		if (countryCode == '') {
			$('.dashboard_mobile_calls1_country_code_error').addClass('error_text_database_car_register_active');
			err = true;
		} else {
			$('.dashboard_mobile_calls1_country_code_error').removeClass('error_text_database_car_register_active');
		}

		if (number == '') {
			$('.dashboard_mobile_calls1_number_error').addClass('error_text_database_car_register_active');
			err = true;
		} else {
			$('.dashboard_mobile_calls1_number_error').removeClass('error_text_database_car_register_active');
			var number = number.replace(/ /g, "");
		}

		if (!err) {
			// socket
			mySocketLastAction = 'databaseMobileCallsNoEmptyFields';
			var message = {
				'op': 'databaseMobileCallsNoEmptyFields',
				'parameters': {
					'country_code': countryCode,
					'number': number
				},
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
	        };
	        sendMessageSocket(JSON.stringify(message));

			// обнуляем значения в попапе перед отображением
			$('.popup_search_processing_input_upload_text span').html('0');
			$('.popup_search_processing_input_upload_percent').css('width', '0%');

			// отображаем окно поиска
			$('#popup_search_processing').css('display','block');
			$('.popup_search_processing_input_upload_percent').css('opacity', 1);

			// звук поиска, если звуки включены
			searchAudio = new Audio;
			searchAudio.src = '/music/search_database.mp3';
			searchAudio.play();

			// анимация поиска
			var searchInterval = false; // переменная для интервала поиска
			var databaseSearchPlus = Math.round(100 / databaseSearchIteration); // на столько увеличиваем за итерацию

			searchInterval = setInterval(function(){
				var current = parseInt($('.popup_search_processing_input_upload_text span').html(), 10);
				var next = current + selfRandom(1, databaseSearchPlus);

				if (next >= 100) {
					next = 100;
				}

				$('.popup_search_processing_input_upload_percent').css('width', next + '%');
				$('.popup_search_processing_input_upload_text span').html(next);

				if (next == 100) {
					// останавливаем анимацию поиска
					clearInterval(searchInterval);
					searchInterval = false;

					// скрываем попап поиска
					$('#popup_search_processing').css('display','none');

					// проверяем правильная ли комбинация введена
					if ((countryCode == '167' || countryCode == 167) && (number == '94054421337' || number == '794054421337' || number == '+794054421337' || number == '+94054421337')) {
						// звук успешного выполнения
						searchAudio.pause();

						successAudio = new Audio;
						successAudio.src = '/music/done.mp3';
						successAudio.play();

						// грузим новый таб
						$('#section_game').attr('data-last-open-databases', 'databases_start_four_inner_first_mobile_calls_messages');

						var formData = new FormData();
				    	formData.append('op', 'updateLastDatabase');
				    	formData.append('database', 'databases_start_four_inner_first_mobile_calls_messages');

				    	$.ajax({
							url: '/ajax/ajax.php',
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

						uploadTypeTabsDatabasesStep('databases_start_four_inner_first_mobile_calls_messages', 'mobile_calls');

						// фиксируем, что нашли
						$('#section_game').attr('data-mobile-calls-complete', 1);
					} else {
						// отображаем попап ошибки
						$('#popup_search_error .popup_search_error_input').html(text143);
						$('#popup_search_error .popup_search_error_text').html(text144);
						$('#popup_search_error').css('display','block');

						// звук ошибки
						searchAudio.pause();

						errorAudio = new Audio;
						errorAudio.src = '/music/error.mp3';
						errorAudio.play();
					}
				}
			}, (databaseSearchDuration / databaseSearchIteration));
		} else {
			// socket
			mySocketLastAction = 'databaseMobileCallsEmptyFields';
			var message = {
				'op': 'databaseMobileCallsEmptyFields',
				'parameters': {
					'country_code_error': (countryCode == '') ? true : false,
					'number_error': (number == '') ? true : false
				},
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
	        };
	        sendMessageSocket(JSON.stringify(message));
		}
	});

	// opep popup mobile calls messages
	$('body').on('click', '.dashboard_tab_content_item_active .dashboard_mobile_calls2_message_item', function(e){
		// socket
		mySocketLastAction = 'databaseMobileCallsOpenPopupMesages';
		var message = {
			'op': 'databaseMobileCallsOpenPopupMesages',
			'parameters': {},
			'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
			'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
        };
        sendMessageSocket(JSON.stringify(message));

		$('#popup_mobile_calls_messages').css('display','block');

		if (!is_touch_device()) {
			var pageSize = getPageSize();
    		var windowWidth = pageSize[2];
    		if (windowWidth < 1800) {
    			$('body').removeClass('body_desktop_scale').css('transform', 'scale(1)');

    			var pageSize = getPageSize();
    			var windowWidth = pageSize[0];

    			var koef = parseFloat((windowWidth / 1920).toFixed(2)) + 0.01;

    			$('body').addClass('body_desktop_scale').css('transform', 'scale(' + koef + ')');

    			$('#popup_mobile_calls_messages').css('height', ($('#main').outerHeight() * parseFloat((1920 / windowWidth).toFixed(2))) + 'px');
    		}
		}
	});

	// close popup mobile calls messages
	$('body').on('click', '.popup_mobile_calls_close, .popup_mobile_calls_messages_bg', function(e){
		// socket
		mySocketLastAction = 'databaseMobileCallsClosePopupMesages';
		var message = {
			'op': 'databaseMobileCallsClosePopupMesages',
			'parameters': {},
			'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
			'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
        };
        sendMessageSocket(JSON.stringify(message));

		$('#popup_mobile_calls_messages').fadeOut(200);
	});

/* TOOLS */
	// Открыть тип табов: tools
	function openTypeTabsTools() {
		// socket
		mySocketLastAction = 'openTypeTabsTools';
		var message = {
			'op': 'openTypeTabsTools',
			'parameters': {},
			'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
			'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
        };
        sendMessageSocket(JSON.stringify(message));

        // function
		$('.dashboard_tabs[data-dashboard="tools"]').addClass('dashboard_tabs_active');
		$('.dashboard_item[data-dashboard="tools"]').addClass('dashboard_item_active');

		uploadTypeTabsToolsStep($('#section_game').attr('data-last-open-tools'));
	}

	// загрузить конкретный экран (с переключателем табов) для tools
	function uploadTypeTabsToolsStep(step) {
		var formData = new FormData();
    	formData.append('op', 'uploadTypeTabsToolsStep');
    	formData.append('lang_abbr', $('html').attr('lang'));
    	formData.append('step', step);

    	$.ajax({
			url: '/ajax/ajax.php',
	        type: "POST",
	        dataType: "json",
	        cache: false,
	        contentType: false,
	        processData: false,
	        data: formData,
			success: function(json) {
				if (json.titles) {
					$('.dashboard_tabs[data-dashboard="tools"] .dashboard_tab_titles').html(json.titles);
				}
				if (json.content) {
					$('.dashboard_tabs[data-dashboard="tools"] .dashboard_tab_content_item_wrapper').html(json.content);
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {	
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}

/* ВРЕМЯ */
	// обновить время в попапе звонка. Входящего
	function updateIncomingTime() {
		var d = new Date();
		var hours = d.getHours();
		var minutes = d.getMinutes();

		if (hours <= 9) {
			hours = "0" + hours;
		}
		if (minutes <= 9) {
			minutes = "0" + minutes;
		}

		$('#popup_video_phone .popup_video_phone_time_hours').html(hours);
		$('#popup_video_phone .popup_video_phone_time_minutes').html(minutes);
	}

	// обновить время в попапе звонка. Исходящего
	function updateOutgoingTime() {
		var d = new Date();
		var hours = d.getHours();
		var minutes = d.getMinutes();

		if (hours <= 9) {
			hours = "0" + hours;
		}
		if (minutes <= 9) {
			minutes = "0" + minutes;
		}

		$('#popup_video_phone_outgoing .popup_video_phone_outgoing_time_hours').html(hours);
		$('#popup_video_phone_outgoing .popup_video_phone_outgoing_time_minutes').html(minutes);
	}

/* SCORE */
	// проскроллить блок с очками к нужному значению при загрузке странице
	function loadScoreActual() {
		// главный экран
		loadScoreActualMain();

		// экран с подсказками
		loadScoreActualHints();
	}

	// проскроллить блок с очками к нужному значению при загрузке странице - главный экран
	function loadScoreActualMain() {
		var curScore = parseInt($.trim($('#section_game').attr('data-score')), 10);
		var curScoreMain = parseInt($('.score .score_active').html(), 10);

		// обновляем только, если значения не совпадают
		if (curScore != curScoreMain || ($('.content_game').css('display') == 'block' && !updatedScoreMain)) {
			$('.score .score_active').removeClass('score_active');
			$('.score .score_' + curScore).addClass('score_active');

			/*var scrollPos = $('.score .score_active').position().top - 22;
			$('.score').scrollTop($('.score').scrollTop() + scrollPos);*/

			// при scale position().top считает неверно. Поэтому доп манипуляции
			if (!is_touch_device()) {
				var pageSize = getPageSize();
        		var windowWidth = pageSize[2];
        		if (windowWidth < 1800) {
        			$('body').removeClass('body_desktop_scale').css('transform', 'scale(1)');

        			var scrollPos = $('.score .score_active').position().top - 22;
					$('.score').scrollTop($('.score').scrollTop() + scrollPos);

					var pageSize = getPageSize();
        			var windowWidth = pageSize[0];

        			var koef = parseFloat((windowWidth / 1920).toFixed(2)) + 0.01;

        			$('body').addClass('body_desktop_scale').css('transform', 'scale(' + koef + ')');
        		} else {
        			var scrollPos = $('.score .score_active').position().top - 22;
					$('.score').scrollTop($('.score').scrollTop() + scrollPos);
        		}
			} else {
				var scrollPos = $('.score .score_active').position().top - 22;
				$('.score').scrollTop($('.score').scrollTop() + scrollPos);
			}

			updatedScoreMain = true;
		}
	}

	// проскроллить блок с очками к нужному значению при загрузке странице - экран с подсказками
	function loadScoreActualHints() {
		var curScore = parseInt($.trim($('#section_game').attr('data-score')), 10);
		var curScoreHints = parseInt($('.active_hints_score_value .score_active').html(), 10);

		// обновляем только, если значения не совпадают
		if (curScore != curScoreHints || ($('.content_hints').css('display') == 'block' && !updatedScoreHints)) {
			$('.active_hints_score_value .score_active').removeClass('score_active');
			$('.active_hints_score_value .score_' + curScore).addClass('score_active');

			/*var scrollPos = $('.active_hints_score_value .score_active').position().top;
			$('.active_hints_score_value').scrollTop($('.active_hints_score_value').scrollTop() + scrollPos);*/

			// при scale position().top считает неверно. Поэтому доп манипуляции
			if (!is_touch_device()) {
				var pageSize = getPageSize();
        		var windowWidth = pageSize[2];
        		if (windowWidth < 1800) {
        			$('body').removeClass('body_desktop_scale').css('transform', 'scale(1)');

        			var scrollPos = $('.active_hints_score_value .score_active').position().top;
					$('.active_hints_score_value').scrollTop($('.active_hints_score_value').scrollTop() + scrollPos);

					var pageSize = getPageSize();
        			var windowWidth = pageSize[0];

        			var koef = parseFloat((windowWidth / 1920).toFixed(2)) + 0.01;

        			$('body').addClass('body_desktop_scale').css('transform', 'scale(' + koef + ')');
        		} else {
        			var scrollPos = $('.active_hints_score_value .score_active').position().top;
					$('.active_hints_score_value').scrollTop($('.active_hints_score_value').scrollTop() + scrollPos);
        		}
			} else {
				var scrollPos = $('.active_hints_score_value .score_active').position().top;
				$('.active_hints_score_value').scrollTop($('.active_hints_score_value').scrollTop() + scrollPos);
			}

			updatedScoreHints = true;
		}
	}

	// увеличить к-во очков
	function incrementScore(newScore, page) {
		var curScore = parseInt($.trim($('#section_game').attr('data-score')), 10);
		var newScoreAnimation = curScore; // доп переменная для анимации
		if (newScore > curScore) {
			if (page == 'hints') {
				// экран с подсказками
				$('.active_hints_score_value .score_active').removeClass('score_active');
				$('.active_hints_score_value .score_' + newScore).addClass('score_active');

				/*var scrollPos = $('.active_hints_score_value .score_' + newScore).position().top;
				$('.active_hints_score_value').animate({
					scrollTop: $('.active_hints_score_value').scrollTop() + scrollPos
				}, incrementScoreDuration);*/

				// при scale position().top считает неверно. Поэтому доп манипуляции
				if (!is_touch_device()) {
					var pageSize = getPageSize();
	        		var windowWidth = pageSize[2];
	        		if (windowWidth < 1800) {
	        			$('body').removeClass('body_desktop_scale').css('transform', 'scale(1)');

	        			var scrollPos = $('.active_hints_score_value .score_' + newScore).position().top;
						$('.active_hints_score_value').animate({
							scrollTop: $('.active_hints_score_value').scrollTop() + scrollPos
						}, incrementScoreDuration);

						var pageSize = getPageSize();
	        			var windowWidth = pageSize[0];

	        			var koef = parseFloat((windowWidth / 1920).toFixed(2)) + 0.01;

	        			$('body').addClass('body_desktop_scale').css('transform', 'scale(' + koef + ')');
	        		} else {
	        			var scrollPos = $('.active_hints_score_value .score_' + newScore).position().top;
						$('.active_hints_score_value').animate({
							scrollTop: $('.active_hints_score_value').scrollTop() + scrollPos
						}, incrementScoreDuration);
	        		}
				} else {
					var scrollPos = $('.active_hints_score_value .score_' + newScore).position().top;
					$('.active_hints_score_value').animate({
						scrollTop: $('.active_hints_score_value').scrollTop() + scrollPos
					}, incrementScoreDuration);
				}
			} else {
				// основной экран
				$('.score .score_active').removeClass('score_active');
				$('.score .score_' + newScore).addClass('score_active');
				
				/*var scrollPos = $('.score .score_' + newScore).position().top - 22;
				$('.score').animate({
					scrollTop: $('.score').scrollTop() + scrollPos
				}, incrementScoreDuration);*/

				// при scale position().top считает неверно. Поэтому доп манипуляции
				if (!is_touch_device()) {
					var pageSize = getPageSize();
	        		var windowWidth = pageSize[2];
	        		if (windowWidth < 1800) {
	        			$('body').removeClass('body_desktop_scale').css('transform', 'scale(1)');

	        			var scrollPos = $('.score .score_' + newScore).position().top - 22;

	        			$('.score').animate({
							scrollTop: $('.score').scrollTop() + scrollPos
						}, incrementScoreDuration);

						var pageSize = getPageSize();
	        			var windowWidth = pageSize[0];

	        			var koef = parseFloat((windowWidth / 1920).toFixed(2)) + 0.01;

	        			$('body').addClass('body_desktop_scale').css('transform', 'scale(' + koef + ')');
	        		} else {
	        			var scrollPos = $('.score .score_' + newScore).position().top - 22;
						$('.score').animate({
							scrollTop: $('.score').scrollTop() + scrollPos
						}, incrementScoreDuration);
	        		}
				} else {
					var scrollPos = $('.score .score_' + newScore).position().top - 22;
					$('.score').animate({
						scrollTop: $('.score').scrollTop() + scrollPos
					}, incrementScoreDuration);
				}
			}

			updateScoreDb(newScore);

			// звук начисления очков
			scoreAudio = new Audio;
			scoreAudio.src = '/music/score.mp3';
			scoreAudio.play();

			setTimeout(function(){
				scoreAudio.pause();

				music_before = false;
			}, incrementScoreDuration);
		}
	}

	// увеличить к-во очков. Без записи в бд
	function incrementScoreWithoutSaveDb(newScore, page) {
		var curScore = parseInt($.trim($('#section_game').attr('data-score')), 10);
		var newScoreAnimation = curScore; // доп переменная для анимации
		if (newScore > curScore) {
			// обновляем также и соответствующий атрибут
			$('#section_game').attr('data-score', newScore);

			if (page == 'hints') {
				// экран с подсказками
				$('.active_hints_score_value .score_active').removeClass('score_active');
				$('.active_hints_score_value .score_' + newScore).addClass('score_active');

				/*var scrollPos = $('.active_hints_score_value .score_' + newScore).position().top;
				$('.active_hints_score_value').animate({
					scrollTop: $('.active_hints_score_value').scrollTop() + scrollPos
				}, incrementScoreDuration);*/

				// при scale position().top считает неверно. Поэтому доп манипуляции
				if (!is_touch_device()) {
					var pageSize = getPageSize();
	        		var windowWidth = pageSize[2];
	        		if (windowWidth < 1800) {
	        			$('body').removeClass('body_desktop_scale').css('transform', 'scale(1)');

	        			var scrollPos = $('.active_hints_score_value .score_' + newScore).position().top;

						$('.active_hints_score_value').animate({
							scrollTop: $('.active_hints_score_value').scrollTop() + scrollPos
						}, incrementScoreDuration);

						var pageSize = getPageSize();
	        			var windowWidth = pageSize[0];

	        			var koef = parseFloat((windowWidth / 1920).toFixed(2)) + 0.01;

	        			$('body').addClass('body_desktop_scale').css('transform', 'scale(' + koef + ')');
	        		} else {
	        			var scrollPos = $('.active_hints_score_value .score_' + newScore).position().top;
						$('.active_hints_score_value').animate({
							scrollTop: $('.active_hints_score_value').scrollTop() + scrollPos
						}, incrementScoreDuration);
	        		}
				} else {
					var scrollPos = $('.active_hints_score_value .score_' + newScore).position().top;
					$('.active_hints_score_value').animate({
						scrollTop: $('.active_hints_score_value').scrollTop() + scrollPos
					}, incrementScoreDuration);
				}
			} else {
				// основной экран
				$('.score .score_active').removeClass('score_active');
				$('.score .score_' + newScore).addClass('score_active');
				
				/*var scrollPos = $('.score .score_' + newScore).position().top - 22;
				$('.score').animate({
					scrollTop: $('.score').scrollTop() + scrollPos
				}, incrementScoreDuration);*/

				// при scale position().top считает неверно. Поэтому доп манипуляции
				if (!is_touch_device()) {
					var pageSize = getPageSize();
	        		var windowWidth = pageSize[2];
	        		if (windowWidth < 1800) {
	        			$('body').removeClass('body_desktop_scale').css('transform', 'scale(1)');

	        			var scrollPos = $('.score .score_' + newScore).position().top - 22;

						$('.score').animate({
							scrollTop: $('.score').scrollTop() + scrollPos
						}, incrementScoreDuration);

						var pageSize = getPageSize();
	        			var windowWidth = pageSize[0];

	        			var koef = parseFloat((windowWidth / 1920).toFixed(2)) + 0.01;

	        			$('body').addClass('body_desktop_scale').css('transform', 'scale(' + koef + ')');
	        		} else {
	        			var scrollPos = $('.score .score_' + newScore).position().top - 22;
						$('.score').animate({
							scrollTop: $('.score').scrollTop() + scrollPos
						}, incrementScoreDuration);
	        		}
				} else {
					var scrollPos = $('.score .score_' + newScore).position().top - 22;
					$('.score').animate({
						scrollTop: $('.score').scrollTop() + scrollPos
					}, incrementScoreDuration);
				}
			}

			// звук начисления очков
			scoreAudio = new Audio;
			scoreAudio.src = '/music/score.mp3';
			scoreAudio.play();

			setTimeout(function(){
				scoreAudio.pause();

				music_before = false;
			}, incrementScoreDuration);
		}
	}

	// уменьшить к-во очков
	function decrementScore(newScore, page) {
		var curScore = parseInt($.trim($('#section_game').attr('data-score')), 10);
		if (newScore < curScore) {
			if (page == 'hints') {
				// экран с подсказками
				$('.active_hints_score_value .score_active').removeClass('score_active');
				$('.active_hints_score_value .score_' + newScore).addClass('score_active');

				/*var scrollPos = $('.active_hints_score_value .score_' + newScore).position().top;
				$('.active_hints_score_value').animate({
					scrollTop: $('.active_hints_score_value').scrollTop() + scrollPos
				}, incrementScoreDuration / 2);*/

				// при scale position().top считает неверно. Поэтому доп манипуляции
				if (!is_touch_device()) {
					var pageSize = getPageSize();
	        		var windowWidth = pageSize[2];
	        		if (windowWidth < 1800) {
	        			$('body').removeClass('body_desktop_scale').css('transform', 'scale(1)');

	        			var scrollPos = $('.active_hints_score_value .score_' + newScore).position().top;

						$('.active_hints_score_value').animate({
							scrollTop: $('.active_hints_score_value').scrollTop() + scrollPos
						}, incrementScoreDuration / 2);

						var pageSize = getPageSize();
	        			var windowWidth = pageSize[0];

	        			var koef = parseFloat((windowWidth / 1920).toFixed(2)) + 0.01;

	        			$('body').addClass('body_desktop_scale').css('transform', 'scale(' + koef + ')');
	        		} else {
	        			var scrollPos = $('.active_hints_score_value .score_' + newScore).position().top;
						$('.active_hints_score_value').animate({
							scrollTop: $('.active_hints_score_value').scrollTop() + scrollPos
						}, incrementScoreDuration / 2);
	        		}
				} else {
					var scrollPos = $('.active_hints_score_value .score_' + newScore).position().top;
					$('.active_hints_score_value').animate({
						scrollTop: $('.active_hints_score_value').scrollTop() + scrollPos
					}, incrementScoreDuration / 2);
				}
			} else {
				// основной экран
				$('.score .score_active').removeClass('score_active');
				$('.score .score_' + newScore).addClass('score_active');
				
				/*var scrollPos = $('.score .score_' + newScore).position().top - 22;
				$('.score').animate({
					scrollTop: $('.score').scrollTop() + scrollPos
				}, incrementScoreDuration / 2);*/

				// при scale position().top считает неверно. Поэтому доп манипуляции
				if (!is_touch_device()) {
					var pageSize = getPageSize();
	        		var windowWidth = pageSize[2];
	        		if (windowWidth < 1800) {
	        			$('body').removeClass('body_desktop_scale').css('transform', 'scale(1)');

	        			var scrollPos = $('.score .score_' + newScore).position().top - 22;

						$('.score').animate({
							scrollTop: $('.score').scrollTop() + scrollPos
						}, incrementScoreDuration / 2);

						var pageSize = getPageSize();
	        			var windowWidth = pageSize[0];

	        			var koef = parseFloat((windowWidth / 1920).toFixed(2)) + 0.01;

	        			$('body').addClass('body_desktop_scale').css('transform', 'scale(' + koef + ')');
	        		} else {
	        			var scrollPos = $('.score .score_' + newScore).position().top - 22;
						$('.score').animate({
							scrollTop: $('.score').scrollTop() + scrollPos
						}, incrementScoreDuration / 2);
	        		}
				} else {
					var scrollPos = $('.score .score_' + newScore).position().top - 22;
					$('.score').animate({
						scrollTop: $('.score').scrollTop() + scrollPos
					}, incrementScoreDuration / 2);
				}
			}

			updateScoreDb(newScore);
		}
	}

	// уменьшить к-во очков
	function decrementScoreWithoutSaveDb(newScore, page) {
		var curScore = parseInt($.trim($('#section_game').attr('data-score')), 10);
		if (newScore < curScore) {
			if (page == 'hints') {
				// экран с подсказками
				$('.active_hints_score_value .score_active').removeClass('score_active');
				$('.active_hints_score_value .score_' + newScore).addClass('score_active');

				/*var scrollPos = $('.active_hints_score_value .score_' + newScore).position().top;
				$('.active_hints_score_value').animate({
					scrollTop: $('.active_hints_score_value').scrollTop() + scrollPos
				}, incrementScoreDuration / 2);*/

				// при scale position().top считает неверно. Поэтому доп манипуляции
				if (!is_touch_device()) {
					var pageSize = getPageSize();
	        		var windowWidth = pageSize[2];
	        		if (windowWidth < 1800) {
	        			$('body').removeClass('body_desktop_scale').css('transform', 'scale(1)');

	        			var scrollPos = $('.active_hints_score_value .score_' + newScore).position().top;

						$('.active_hints_score_value').animate({
							scrollTop: $('.active_hints_score_value').scrollTop() + scrollPos
						}, incrementScoreDuration / 2);

						var pageSize = getPageSize();
	        			var windowWidth = pageSize[0];

	        			var koef = parseFloat((windowWidth / 1920).toFixed(2)) + 0.01;

	        			$('body').addClass('body_desktop_scale').css('transform', 'scale(' + koef + ')');
	        		} else {
	        			var scrollPos = $('.active_hints_score_value .score_' + newScore).position().top;
						$('.active_hints_score_value').animate({
							scrollTop: $('.active_hints_score_value').scrollTop() + scrollPos
						}, incrementScoreDuration / 2);
	        		}
				} else {
					var scrollPos = $('.active_hints_score_value .score_' + newScore).position().top;
					$('.active_hints_score_value').animate({
						scrollTop: $('.active_hints_score_value').scrollTop() + scrollPos
					}, incrementScoreDuration / 2);
				}
			} else {
				// основной экран
				$('.score .score_active').removeClass('score_active');
				$('.score .score_' + newScore).addClass('score_active');
				
				/*var scrollPos = $('.score .score_' + newScore).position().top - 22;
				$('.score').animate({
					scrollTop: $('.score').scrollTop() + scrollPos
				}, incrementScoreDuration / 2);*/

				// при scale position().top считает неверно. Поэтому доп манипуляции
				if (!is_touch_device()) {
					var pageSize = getPageSize();
	        		var windowWidth = pageSize[2];
	        		if (windowWidth < 1800) {
	        			$('body').removeClass('body_desktop_scale').css('transform', 'scale(1)');

	        			var scrollPos = $('.score .score_' + newScore).position().top - 22;

						$('.score').animate({
							scrollTop: $('.score').scrollTop() + scrollPos
						}, incrementScoreDuration / 2);

						var pageSize = getPageSize();
	        			var windowWidth = pageSize[0];

	        			var koef = parseFloat((windowWidth / 1920).toFixed(2)) + 0.01;

	        			$('body').addClass('body_desktop_scale').css('transform', 'scale(' + koef + ')');
	        		} else {
	        			var scrollPos = $('.score .score_' + newScore).position().top - 22;
						$('.score').animate({
							scrollTop: $('.score').scrollTop() + scrollPos
						}, incrementScoreDuration / 2);
	        		}
				} else {
					var scrollPos = $('.score .score_' + newScore).position().top - 22;
					$('.score').animate({
						scrollTop: $('.score').scrollTop() + scrollPos
					}, incrementScoreDuration / 2);
				}
			}
		}
	}

	// сохранить изменение очков в бд
	function updateScoreDb(score) {
		var formData = new FormData();
    	formData.append('op', 'updateScore');
    	formData.append('score', score);

    	$.ajax({
			url: '/ajax/ajax.php',
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

		// обновляем также и соответствующий атрибут
		$('#section_game').attr('data-score', score);
	}

/* PROGRESS MISSION */
	// увеличить к-во процентов
	function incrementProgressMission(plusProgress) {
		var missionProgressText = $('.mission_progress_text').html();
		var missionProgressArray = missionProgressText.split('-');
		var currentMissionProgress = parseInt(missionProgressArray[0], 10);
		var newProgress = currentMissionProgress + plusProgress;

		$('.mission_progress_text').html(newProgress + ' - 100');
		$('.mission_progress_percent').css('width', newProgress + '%');

		var formData = new FormData();
    	formData.append('op', 'updateProgressMission');
    	formData.append('progress_mission', newProgress);

    	$.ajax({
			url: '/ajax/ajax.php',
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

	// случайное число из промежутка
	function selfRandom(min, max) {
		return Math.floor(Math.random() * (max - min + 1)) + min;
	}




	$('.btn_view_highscore').click(function(){
		alert('Table with the results after the end of the game. Not available yet.');
	});
});

// crossbrowse window size
function getPageSize(){
    var xScroll, yScroll;

    if (window.innerHeight && window.scrollMaxY) {
        xScroll = document.body.scrollWidth;
        yScroll = window.innerHeight + window.scrollMaxY;
    } else if (document.body.scrollHeight > document.body.offsetHeight){ // all but Explorer Mac
        xScroll = document.body.scrollWidth;
        yScroll = document.body.scrollHeight;
    } else if (document.documentElement && document.documentElement.scrollHeight > document.documentElement.offsetHeight){ // Explorer 6 strict mode
        xScroll = document.documentElement.scrollWidth;
        yScroll = document.documentElement.scrollHeight;
    } else { // Explorer Mac...would also work in Mozilla and Safari
        xScroll = document.body.offsetWidth;
        yScroll = document.body.offsetHeight;
    }

    var windowWidth, windowHeight;
    if (self.innerHeight) { // all except Explorer
        windowWidth = self.innerWidth;
        windowHeight = self.innerHeight;
    } else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
        windowWidth = document.documentElement.clientWidth;
        windowHeight = document.documentElement.clientHeight;
    } else if (document.body) { // other Explorers
        windowWidth = document.body.clientWidth;
        windowHeight = document.body.clientHeight;
    }

    // for small pages with total height less then height of the viewport
    if(yScroll < windowHeight){
        pageHeight = windowHeight;
    } else {
        pageHeight = yScroll;
    }

    // for small pages with total width less then width of the viewport
    if(xScroll < windowWidth){
        pageWidth = windowWidth;
    } else {
        pageWidth = xScroll;
    }

    return [pageWidth,pageHeight,windowWidth,windowHeight];
}

// check touch device
function is_touch_device() {
    var prefixes = ' -webkit- -moz- -o- -ms- '.split(' ');
    var mq = function(query) {
        return window.matchMedia(query).matches;
    }

    if (('ontouchstart' in window) || window.DocumentTouch && document instanceof DocumentTouch) {
        return true;
    }

    var query = ['(', prefixes.join('touch-enabled),('), 'heartz', ')'].join('');
    return mq(query);
}