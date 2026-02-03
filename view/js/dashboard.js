/* === ГЛАВНОЕ ОКНО ИГРЫ - ЦЕНТРАЛЬНЫЙ БЛОК ИНФОРМАЦИИ - DASHBOARD === */

/* КЭШИРОВАНИЕ ДАННЫХ */
	var dashboardCache = {
		step: null,
		titles: null,
		content: null
	};

/* ОБЩИЕ ФУНКЦИИ */
	// Открыть тип табов: dashboard
	function openTypeTabsDashboard(isSocketSend) {
		$('.dashboard_tabs[data-dashboard="dashboard"]').addClass('dashboard_tabs_active');
		$('.dashboard_item[data-dashboard="dashboard"]').addClass('dashboard_item_active');

		$.when(getTeamInfo()).done(function(teamResponse){
			var teamInfo = teamResponse.success;

			if (teamInfo) {
				uploadTypeTabsDashboardStep(teamInfo.last_dashboard, isSocketSend);
			}
		});
	}

	// загрузить конкретный экран (с переключателем табов) для dashboard
	function uploadTypeTabsDashboardStep(step, isSocketSend) {
		var $dashboardTabs = $('.dashboard_tabs[data-dashboard="dashboard"]');
		
		// Проверяем, есть ли уже загруженные данные для этой стадии
		if (dashboardCache.step === step && dashboardCache.titles && dashboardCache.content) {
			// Данные уже загружены - показываем их сразу без лоадинга
			$('.dashboard_tabs[data-dashboard="dashboard"] .dashboard_tab_titles').html(dashboardCache.titles);
			$('.dashboard_tabs[data-dashboard="dashboard"] .dashboard_tab_content_item_wrapper').html(dashboardCache.content);
			$dashboardTabs.find('.dashboard_tabs_loading').hide();
			$dashboardTabs.find('.dashboard_tabs_content_wrapper').show();
			return;
		}
		
		// Показываем лоадинг и скрываем контент
		$dashboardTabs.find('.dashboard_tabs_loading').show();
		$dashboardTabs.find('.dashboard_tabs_content_wrapper').hide();
		
		var formData = new FormData();
    	formData.append('op', 'uploadTypeTabsDashboardStep');
    	formData.append('lang_abbr', $('html').attr('lang'));
    	formData.append('step', step);

    	$.ajax({
			url: '/ajax/ajax_dashboard.php',
	        type: "POST",
	        dataType: "json",
	        cache: false,
	        contentType: false,
	        processData: false,
	        data: formData,
			success: function(json) {
				if (json.titles) {
					$('.dashboard_tabs[data-dashboard="dashboard"] .dashboard_tab_titles').html(json.titles);
					dashboardCache.titles = json.titles;
				}
				if (json.content) {
					$('.dashboard_tabs[data-dashboard="dashboard"] .dashboard_tab_content_item_wrapper').html(json.content);
					dashboardCache.content = json.content;
				}
				dashboardCache.step = step;

				// Скрываем лоадинг и показываем контент
				$dashboardTabs.find('.dashboard_tabs_loading').hide();
				$dashboardTabs.find('.dashboard_tabs_content_wrapper').show();

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
		setTeamLastTypeTabs('dashboard');

		// запоминаем открытый dashboard
		setTeamTabsTextInfo('last_dashboard', step);
	}

$(function() {
	// При загрузке страницы, если dashboard_tabs_active уже есть, загружаем контент
	if ($('.dashboard_tabs[data-dashboard="dashboard"]').hasClass('dashboard_tabs_active')) {
		$.when(getTeamInfo()).done(function(teamResponse){
			var teamInfo = teamResponse.success;
			if (teamInfo) {
				uploadTypeTabsDashboardStep(teamInfo.last_dashboard || 'accept_new_mission', false);
			}
		});
	}
	
	// при нажатии на лого открываем dashboard
	$('.main_logo_title, .section_main_bg_header .main_logo').click(function(){
		// если результаты
		if ($('.section_window_results').length) {
			return false;
		}

		// обнуляем таймер текущей команды в результатах на всякий случай
		if (mainTimer3 !== false) {
			clearInterval(mainTimer3);
			mainTimer3 = false;
		}

		if ($('.content_game').css('display') == 'block') { // если главное окно
			if ($('.dashboard_item_active').attr('data-dashboard') == 'dashboard') {
				return false;
			}

	        // function
			viewMainPreloader(defaultLoaderLoadingText);

			hiddenAllTypeTabs();

			$('.dashboard_tabs[data-dashboard="dashboard"]').addClass('dashboard_tabs_active');
			$('.dashboard_item[data-dashboard="dashboard"]').addClass('dashboard_item_active');
			
			$.when(getTeamInfo()).done(function(teamResponse){
				var teamInfo = teamResponse.success;

				if (teamInfo) {
					uploadTypeTabsDashboardStep(teamInfo.last_dashboard, true);

					hiddenMainPreloader();

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
		} else if ($('.content_hints').css('display') == 'block') { // если окно подсказок
			hiddenHintWindow();

			$.when(getTeamInfo()).done(function(teamResponse){
				var teamInfo = teamResponse.success;

				if (teamInfo) {
					if (teamInfo.dashboard_minigame_access == 1) { // если находимся на этапе миниигры
				        openMiniGameWindow();

						loadScoreActualMain(teamInfo.score);

						// socket
						var message = {
							'op': 'loadSocketMain',
							'parameters': {
								user_id: $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
								team_id: $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
							}
				        };
				        sendMessageSocket(JSON.stringify(message));
					} else if (teamInfo.dashboard_interpol_access == 1) { // если находимся на этапе interpol
				        openInterpolWindow();

						loadScoreActualMain(teamInfo.score);

						// socket
						var message = {
							'op': 'loadSocketMain',
							'parameters': {
								user_id: $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
								team_id: $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
							}
				        };
				        sendMessageSocket(JSON.stringify(message));
					} else { // просто открываем главный экран игры
						openMainGameWindow();

						loadScoreActualMain(teamInfo.score);

						if ($('.dashboard_item_active').attr('data-dashboard') == 'dashboard') {
							// socket
							var message = {
								'op': 'loadSocketMain',
								'parameters': {
									user_id: $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
									team_id: $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
								}
					        };
					        sendMessageSocket(JSON.stringify(message));

							return false;
						} else {
							viewMainPreloader(defaultLoaderLoadingText);

							hiddenAllTypeTabs();

							$('.dashboard_tabs[data-dashboard="dashboard"]').addClass('dashboard_tabs_active');
							$('.dashboard_item[data-dashboard="dashboard"]').addClass('dashboard_item_active');
							uploadTypeTabsDashboardStep(teamInfo.last_dashboard, false);

							hiddenMainPreloader();

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
				}
			});
		} else if ($('.content_highscore').css('display') == 'block') { // если окно результатов
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

						if ($('.dashboard_item_active').attr('data-dashboard') == 'dashboard') {
							return false;
						}

						viewMainPreloader(defaultLoaderLoadingText);

						hiddenAllTypeTabs();

						$('.dashboard_tabs[data-dashboard="dashboard"]').addClass('dashboard_tabs_active');
						$('.dashboard_item[data-dashboard="dashboard"]').addClass('dashboard_item_active');
						uploadTypeTabsDashboardStep(teamInfo.last_dashboard, true);

						hiddenMainPreloader();
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
		}
	});
});