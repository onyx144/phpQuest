/* INTERPOL */

/* ОБЩИЕ ФУНКЦИИ */
	// скрыть окно interpol
	function hiddenInterpolWindow() {
		$('.content_interpol').css('display', 'none');
	}

	// показать окно interpol
	function openInterpolWindow() {
		viewMainScore();
		viewMainTimer();
		viewHighscoreBtn();
		viewHintBtn();
		viewChatBtn();

		$('.content_interpol').css('display', 'block');

		// запоминаем открытое окно
		setTeamLastOpenWindow('interpol');
	}

	// окончание игры
	function finishGame(hours, minute, second, score) {
		// звук поиска
		setTimeout(function(){
			dataTransferAudio = new Audio;
			dataTransferAudio.src = '/music/data_transfer.mp3';
			dataTransferAudio.play();
		}, 500);

		// обнуляем значение процентов
		$('.popup_data_transfer_percent span').html('0');
		$('.popup_data_transfer_progress_inner').css('width', '0%');

		if ($('html').attr('lang') == 'en') {
			$('.popup_data_transfer_title').html('INTERPOL DISPATCH').css('padding-left', '18px');
			$('.popup_data_transfer_processed').html('COMPLETED _');
		} else {
			$('.popup_data_transfer_title').html('INTERPOL RYKKER UT').css('padding-left', '12px');
			$('.popup_data_transfer_processed').html('GJENNOMFØRT _');
		}

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

					$('#popup_data_transfer').css('display','none');

					// обновляем время на главном экране
					$('.timer .timer_hour').html(('0' + hours).slice(-2));
					$('.timer .timer_minute').html(('0' + minute).slice(-2));
					$('.timer .timer_second').html(('0' + second).slice(-2));

					// останавливаем отсчет таймер на главном экране
					if (mainTimer !== false) {
						clearInterval(mainTimer);
						mainTimer = false;
					}

					if (mainTimer2 !== false) {
						clearInterval(mainTimer2);
						mainTimer2 = false;
					}

					// loadScoreActualMain(score); // обновляем баллы на главном экране
					// var curScore = parseInt($('#main .score .score_active').html(), 10);
					incrementScoreWithoutSaveDb(parseInt(score, 10), 'main', parseInt($('#main .score .score_active').html(), 10));

					// пишем время в победном попапе
					$('.popup_mission_complete_center_inner_result_hours').html(('0' + hours).slice(-2));
					$('.popup_mission_complete_center_inner_result_minutes').html(('0' + minute).slice(-2));
					$('.popup_mission_complete_center_inner_result_seconds').html(('0' + second).slice(-2));

					// пишем баллы в победном попапе
					$('.popup_mission_complete_center_inner_score .popup_mission_complete_center_inner_result_number').html(score);

					// if (music) {
						stopMusic();
					// }

					// Открыть видео ареста и сразу запустить его
					playVideoByNotControls = true; // указываем, что запускалось через кнопку Play, а не через Controls
					openFileVideoPopup(0, 'video/' + $('html').attr('lang') + '/arrest.mp4', '', 'arrest_video', 'call');
					playVideo('call');
				}
			}, (dataTransferMusicDuration / dataTransferIteration));

			// отображаем попап с гифкой
			$('#popup_data_transfer').css('display','block');
		}, 210);

		/*// обновляем время на главном экране
		$('.timer .timer_hour').html(('0' + hours).slice(-2));
		$('.timer .timer_minute').html(('0' + minute).slice(-2));
		$('.timer .timer_second').html(('0' + second).slice(-2));

		// останавливаем отсчет таймер на главном экране
		if (mainTimer !== false) {
			clearInterval(mainTimer);
			mainTimer = false;
		}

		if (mainTimer2 !== false) {
			clearInterval(mainTimer2);
			mainTimer2 = false;
		}

		loadScoreActualMain(score); // обновляем баллы на главном экране

		// пишем время в победном попапе
		$('.popup_mission_complete_center_inner_result_hours').html(('0' + hours).slice(-2));
		$('.popup_mission_complete_center_inner_result_minutes').html(('0' + minute).slice(-2));
		$('.popup_mission_complete_center_inner_result_seconds').html(('0' + second).slice(-2));

		// пишем баллы в победном попапе
		$('.popup_mission_complete_center_inner_score .popup_mission_complete_center_inner_result_number').html(score);

		// if (music) {
			stopMusic();
		// }

		// Открыть видео ареста и сразу запустить его
		playVideoByNotControls = true; // указываем, что запускалось через кнопку Play, а не через Controls
		openFileVideoPopup(0, 'video/' + $('html').attr('lang') + '/arrest.mp4', '', 'arrest_video', 'call');
		playVideo('call');*/
	}

$(function() {
	// нажимаем на кнопку для завершения игры
	$('body').on('click', '.interpol_submit', function(e){
		// Убираем видимость кнопки, чтоб не было повторного нажатия
		$(this).css('opacity', 0);

		var formData = new FormData();
    	formData.append('op', 'finishGame');

    	$.ajax({
			url: '/ajax/ajax_dashboard.php',
	        type: "POST",
	        dataType: "json",
	        cache: false,
	        contentType: false,
	        processData: false,
	        data: formData,
			success: function(json) {
				/*// звук поиска
				setTimeout(function(){
					dataTransferAudio = new Audio;
					dataTransferAudio.src = '/music/data_transfer.mp3';
					dataTransferAudio.play();
				}, 500);

				// обнуляем значение процентов
				$('.popup_data_transfer_percent span').html('0');
				$('.popup_data_transfer_progress_inner').css('width', '0%');

				if ($('html').attr('lang') == 'en') {
					$('.popup_data_transfer_title').html('INTERPOL DISPATCH').css('padding-left', '18px');
					$('.popup_data_transfer_processed').html('COMPLETED _');
				} else {
					$('.popup_data_transfer_title').html('INTERPOL RYKKER UT').css('padding-left', '12px');
					$('.popup_data_transfer_processed').html('GJENNOMFØRT _');
				}

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

							finishGame(json.hours, json.minute, json.second, json.score);
						}
					}, (dataTransferMusicDuration / dataTransferIteration));

					// отображаем попап с гифкой
					$('#popup_data_transfer').css('display','block');
				}, 210);*/

				// socket
				var message = {
					'op': 'finishGame',
					'parameters': {
						'hours': json.hours,
						'minute': json.minute,
						'second': json.second,
						'score': json.score,
						'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
						'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
					}
		        };
		        sendMessageSocket(JSON.stringify(message));

				finishGame(json.hours, json.minute, json.second, json.score);
			},
			error: function(xhr, ajaxOptions, thrownError) {	
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

	/*// когда видео доиграло до конца, то закрываем и производим нужные действия
	$('body').on('ended', '.arrest_video video', function(e){
		// console.log('close from interpol');
		closePopupVideo();

		// победная музыка
		finishAudio = new Audio;
		finishAudio.src = '/music/winner.mp3';
		finishAudio.play();

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

		// socket
		var message = {
			'op': 'ArrestSuccess',
			'parameters': {
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
        };
        sendMessageSocket(JSON.stringify(message));
	});*/

	// закрыть попап с видео
	$('body').on('click', '.arrest_video .popup_video_bg, .arrest_video .popup_video_close', function(e){
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

		// socket
		var message = {
			'op': 'stopVideoAndArrestSuccess',
			'parameters': {
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
        };
        sendMessageSocket(JSON.stringify(message));
	});
});