/* === ТАЙМЕР === */

/* ОБЩИЕ ФУНКЦИИ */
	// скрыть основное поле с таймером
	function hiddenMainTimer() {
		$('.timer_wrapper').css('display', 'none');
	}

	// показать основное поле с таймером
	function viewMainTimer() {
		$('.timer_wrapper').css('display', 'block');
	}

	// запустить обновление таймера
	function updateTimerUploadPage() {
		if ($('.timer').length) {
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

							var days = Math.floor(timerSeconds / 86400);
							timerSeconds = timerSeconds - days * 86400;

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

					updateTimer();
				},
				error: function(xhr, ajaxOptions, thrownError) {	
					console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}

	// обновить значение таймера
	function updateTimer() {
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
				url: '/ajax/ajax_timer.php',
		        type: "POST",
		        dataType: "json",
		        cache: false,
		        contentType: false,
		        processData: false,
		        data: formData,
				success: function(json) {
					if (json.second) {
						if (json.reload == 1) {
							location.reload();
						}

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

							var days = Math.floor(timerSeconds / 86400);
							timerSeconds = timerSeconds - days * 86400;

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
	}

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

	// обновить время в попапе Alarm
	function updateAlarmTime() {
		var d = new Date();
		var hours = d.getHours();
		var minutes = d.getMinutes();

		if (hours <= 9) {
			hours = "0" + hours;
		}
		if (minutes <= 9) {
			minutes = "0" + minutes;
		}

		$('#popup_minigame_alarm .popup_minigame_alarm_center_block_face_time_hours').html(hours);
		$('#popup_minigame_alarm .popup_minigame_alarm_center_block_face_time_minutes').html(minutes);
	}

$(function() {
	/*// запустить обновление таймера
	updateTimerUploadPage();*/
});