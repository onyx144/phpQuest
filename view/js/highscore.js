/* === ТАБЛИЦА РЕЗУЛЬТАТОВ ИГРЫ === */

/* ОБЩИЕ ФУНКЦИИ */
	// скрыть кнопку перехода на страницу результатов
	function hiddenHighscoreBtn() {
		$('.section_main_bg_footer .btn_wrapper_view_highscore').css('display', 'none');
	}

	// показать кнопку перехода на страницу результатов
	function viewHighscoreBtn() {
		$('.section_main_bg_footer .btn_wrapper_view_highscore').css('display', 'block');
	}

	// скрыть окно результатов
	function hiddenHighscoreWindow() {
		$('.content_highscore').css('display', 'none');

		// убираем скролл
		$('.highscore_table_body').mCustomScrollbar('destroy');
	}

	// показать окно результатов
	function openHighscoreWindow() {
		hiddenMainScore();
		hiddenMainTimer();
		hiddenHighscoreBtn();
		hiddenHintBtn();
		hiddenChatBtn();

		$('.content_highscore').css('display', 'block');
		hiddenMainPreloader();

		// запоминаем открытое окно
		setTeamLastOpenWindow('highscore');
	}

	// загрузить актуальные результаты команд
	function uploadActualHighscore() {
		var formData = new FormData();
    	formData.append('op', 'uploadActualHighscore');
    	formData.append('lang_abbr', $('html').attr('lang'));
    	formData.append('type', $('.highscore_btn_active').hasClass('highscore_btn_alltime') ? 'alltime' : 'today');

    	$.ajax({
			url: '/ajax/ajax.php',
	        type: "POST",
	        dataType: "json",
	        cache: false,
	        contentType: false,
	        processData: false,
	        data: formData,
			success: function(json) {
				$('.highscore_table_body').mCustomScrollbar('destroy');

				$('.highscore_table_body').html(json.success);

				$('.highscore_table_body').mCustomScrollbar({
					scrollInertia: 700,
					scrollbarPosition: "inside"
				});

				hiddenMainPreloader();

				// обнуляем таймер текущей команды в результатах на всякий случай
				if (mainTimer3 !== false) {
					clearInterval(mainTimer3);
					mainTimer3 = false;
				}

				if ($('.highscore_table_row_cur_team').length && $('.highscore_table_row_cur_team .highscore_table_cell_status_text').html() != 'Finished' && $('.highscore_table_row_cur_team .highscore_table_cell_status_text').html() != 'Fullført') {
					mainTimer3 = setInterval(function(){
						var timerSeconds = parseInt($('.highscore_table_row_cur_team .highscore_table_cell_time').attr('data-timer'), 10);
						var timerSecondsStart = timerSeconds;

						var days = Math.floor(timerSeconds / 86400);
						timerSeconds = timerSeconds - days * 86400;

						var hours = Math.floor(timerSeconds / 3600);
						timerSeconds = timerSeconds - hours * 3600;

						var minute = Math.floor(timerSeconds / 60);
						timerSeconds = timerSeconds - minute * 60;

						var second = timerSeconds;

						$('.highscore_table_row_cur_team .highscore_table_cell_time .highscore_table_cell_time_hours').html(('0' + hours).slice(-2));
						$('.highscore_table_row_cur_team .highscore_table_cell_time .highscore_table_cell_time_minute').html(('0' + minute).slice(-2));
						$('.highscore_table_row_cur_team .highscore_table_cell_time .highscore_table_cell_time_second').html(('0' + second).slice(-2));

						$('.highscore_table_row_cur_team .highscore_table_cell_time').attr('data-timer', (timerSecondsStart + 1));
					}, 1000);
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {	
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}

$(function() {
	// открыть окно результатов при нажатии на кнопку в футере
	$('.section_main_bg_footer .btn_wrapper_view_highscore').click(function(){
        // function
		viewMainPreloader(defaultLoaderLoadingText);

		uploadActualHighscore();
		hiddenHintWindow();
		hiddenMainGameWindow();
		hiddenMiniGameWindow();
		openHighscoreWindow();

        // socket
		var message = {
			'op': 'loadSocketMain',
			'parameters': {
				user_id: $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				team_id: $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
        };
        sendMessageSocket(JSON.stringify(message));

		/*$.when(getTeamInfo()).done(function(teamResponse){
			var teamInfo = teamResponse.success;

			if (teamInfo) {
				loadScoreActualHints(teamInfo.score);
			}
		});*/
	});

	// кнопка вернуться назад из окна подсказок на основное окно игры
	$('.highscore_back_btn').click(function(){
		hiddenHighscoreWindow();

		$.when(getTeamInfo()).done(function(teamResponse){
			var teamInfo = teamResponse.success;

			if (teamInfo) {
				if (teamInfo.dashboard_minigame_access == 1) { // если находимся на этапе миниигры
					openMiniGameWindow();

					loadScoreActualMain(teamInfo.score);
				} else if (teamInfo.dashboard_interpol_access == 1) { // если находимся на этапе interpol
					openInterpolWindow();

					loadScoreActualMain(teamInfo.score);
				} else { // просто открываем главный экран игры
					openMainGameWindow();

					loadScoreActualMain(teamInfo.score);
				}

		        // socket
				var message = {
					'op': 'loadSocketMain',
					'parameters': {
						user_id: $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
						team_id: $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
					}
		        };
		        sendMessageSocket(JSON.stringify(message));
			}
		});
	});

	// переключить табы результатов
	$('body').on('click', '.highscore_wrapper .highscore_btn', function(e){
		if ($(this).hasClass('highscore_btn_active')) {
			return false;
		}

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
		$(this).addClass('highscore_btn_active');

		// грузим результаты в новом табе
		uploadActualHighscore();

        // socket
		var message = {
			'op': 'loadSocketHighscoreToday',
			'parameters': {
				user_id: $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				team_id: $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
        };
        sendMessageSocket(JSON.stringify(message));
	});
});