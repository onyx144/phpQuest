/* === ПОДСКАЗКИ === */

/* ОБЩИЕ ФУНКЦИИ */
	// скрыть кнопку перехода на страницу подсказок
	function hiddenHintBtn() {
		$('.section_main_bg_footer .btn_wrapper_view_hints').css('display', 'none');
	}

	// показать кнопку перехода на страницу подсказок
	function viewHintBtn() {
		$('.section_main_bg_footer .btn_wrapper_view_hints').css('display', 'block');
	}

	// скрыть окно подсказок
	function hiddenHintWindow() {
		$('.content_hints').css('display', 'none');

		// убираем скролл с окна подсказок
		$('.active_hints .active_hints_value_middle_scroll').mCustomScrollbar('destroy');
		$('.list_hints_content_right').mCustomScrollbar('destroy');
	}

	// показать окно подсказок
	function openHintWindow() {
		hiddenMainScore();
		hiddenMainTimer();
		hiddenHighscoreBtn();
		hiddenHintBtn();
		hiddenChatBtn();

		$('.content_hints').css('display', 'block');

		// запоминаем открытое окно
		setTeamLastOpenWindow('hints');
	}

	// загрузить актуальный список подсказок
	function updateHintWindow() {
		var formData = new FormData();
    	formData.append('op', 'updateWindowHint');
    	formData.append('lang_abbr', $('html').attr('lang'));

    	$.ajax({
			url: '/ajax/ajax_hint.php',
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
					$('.list_hints_content_right').mCustomScrollbar('destroy');

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
					}).mCustomScrollbar("scrollTo","bottom",{scrollInertia:0});
					
					$('.list_hints_content_right').mCustomScrollbar({
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

$(function() {
	// открыть окно подсказок при нажатии на кнопку в футере
	$('.section_main_bg_footer .btn_wrapper_view_hints').click(function(){
		viewMainPreloader(defaultLoaderLoadingText);

		updateHintWindow();
		hiddenMainGameWindow();
		hiddenMiniGameWindow();
		openHintWindow();

		$.when(getTeamInfo()).done(function(teamResponse){
			var teamInfo = teamResponse.success;

			if (teamInfo) {
				loadScoreActualHints(teamInfo.score);

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

	// кнопка вернуться назад из окна подсказок на основное окно игры
	$('.hint_back_btn').click(function(){
		hiddenHintWindow();

		$.when(getTeamInfo()).done(function(teamResponse){
			var teamInfo = teamResponse.success;

			if (teamInfo) {
				// loadScoreActualMain(teamInfo.score);
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

	// открыть подсказку
	$('.list_hints_content_right').on('click', '.list_hint_item', function(e){
		if ($(this).hasClass('list_hint_item_opened') || $(this).hasClass('list_hint_item_answer_dont_open')) {
			return false;
		}

		var _this = $(this);

		viewMainPreloader(defaultLoaderLoadingText);

		$.when(getTeamInfo()).done(function(teamResponse){
			var teamInfo = teamResponse.success;

			// убираем скролл с окна подсказок
			$('.active_hints .active_hints_value_middle_scroll').mCustomScrollbar('destroy');
			$('.list_hints_content_right').mCustomScrollbar('destroy');

			var formData = new FormData();
	    	formData.append('op', 'activateHint');
	    	formData.append('lang_abbr', $('html').attr('lang'));
	    	formData.append('hint_id', _this.attr('data-hint-id'));

	    	$.ajax({
				url: '/ajax/ajax_hint.php',
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
						}).mCustomScrollbar("scrollTo","bottom",{scrollInertia:0});

						$('.list_hints_content_right').mCustomScrollbar({
							scrollInertia: 700,
							scrollbarPosition: "inside"
						});

						hiddenMainPreloader();

						// обновляем points
						if (json.points) {
							var points = parseInt(json.points, 10);
							if (points > 0) {
								/*// socket
								mySocketLastAction = 'updateHintWindowAndDecrementScore';
								var message = {
									'op': 'updateHintWindowAndDecrementScore',
									'parameters': {
										new_score: curScore - points
									},
									'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
									'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
						        };
						        sendMessageSocket(JSON.stringify(message));*/

						        decrementScore(parseInt(teamInfo.score, 10) - points, 'hints', teamInfo.score);

						        // socket
								var message = {
									'op': 'loadSocketMain',
									'parameters': {
										user_id: $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
										team_id: $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
									}
						        };
						        sendMessageSocket(JSON.stringify(message));
							} else {
								/*// socket
								mySocketLastAction = 'updateHintWindow';
								var message = {
									'op': 'updateHintWindow',
									'parameters': {},
									'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
									'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
						        };
						        sendMessageSocket(JSON.stringify(message));*/
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
						} else {
							/*// socket
							mySocketLastAction = 'updateHintWindow';
							var message = {
								'op': 'updateHintWindow',
								'parameters': {},
								'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
								'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
					        };
					        sendMessageSocket(JSON.stringify(message));*/
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
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {	
					console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		});
	});
});