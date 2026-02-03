/* === ГЛАВНОЕ ОКНО ИГРЫ - ЦЕНТРАЛЬНЫЙ БЛОК ИНФОРМАЦИИ - TOOLS === */

/* КЭШИРОВАНИЕ ДАННЫХ */
	var toolsCache = {
		step: null,
		tool: null,
		titles: null,
		content: null
	};

/* ОБЩИЕ ФУНКЦИИ */
	// Открыть тип табов: tools
	function openTypeTabsTools(isSocketSend) {
		$('.dashboard_tabs[data-dashboard="tools"]').addClass('dashboard_tabs_active');
		$('.dashboard_item[data-dashboard="tools"]').addClass('dashboard_item_active');

		$.when(getTeamInfo()).done(function(teamResponse){
			var teamInfo = teamResponse.success;

			if (teamInfo) {
				uploadTypeTabsToolsStep(teamInfo.last_tools, false, isSocketSend);
			}
		});
	}
	// Открыть тип табов: tools - главный экран
	function openTypeTabsToolsMain(isSocketSend) {
		$('.dashboard_tabs[data-dashboard="tools"]').addClass('dashboard_tabs_active');
		$('.dashboard_item[data-dashboard="tools"]').addClass('dashboard_item_active');

		$.when(getTeamInfo()).done(function(teamResponse){
			var teamInfo = teamResponse.success;

			if (teamInfo) {
				if (teamInfo.last_tools == 'no_access') {
					uploadTypeTabsToolsStep('no_access', false, isSocketSend);
				} else {
					uploadTypeTabsToolsStep('tools_start_four', false, isSocketSend);
				}
			}
		});
	}

	// загрузить конкретный экран (с переключателем табов) для tools
	function uploadTypeTabsToolsStep(step, tool, isSocketSend) {
		if (typeof tool === "undefined") {
			tool = false;
		}

		var $dashboardTabs = $('.dashboard_tabs[data-dashboard="tools"]');
		
		// Проверяем, есть ли уже загруженные данные для этой стадии
		if (toolsCache.step === step && toolsCache.tool === tool && toolsCache.titles && toolsCache.content) {
			// Данные уже загружены - показываем их сразу без лоадинга
			$('.dashboard_tabs[data-dashboard="tools"] .dashboard_tab_titles').html(toolsCache.titles);
			$('.dashboard_tabs[data-dashboard="tools"] .dashboard_tab_content_item_wrapper').html(toolsCache.content);
			$dashboardTabs.find('.dashboard_tabs_loading').hide();
			$dashboardTabs.find('.dashboard_tabs_content_wrapper').show();
			return;
		}

		// Показываем лоадинг и скрываем контент
		$dashboardTabs.find('.dashboard_tabs_loading').show();
		$dashboardTabs.find('.dashboard_tabs_content_wrapper').hide();

		var formData = new FormData();
    	formData.append('op', 'uploadTypeTabsToolsStep');
    	formData.append('lang_abbr', $('html').attr('lang'));
    	formData.append('step', step);
    	formData.append('tool', tool);

    	$.ajax({
			url: '/ajax/ajax_tools.php',
	        type: "POST",
	        dataType: "json",
	        cache: false,
	        contentType: false,
	        processData: false,
	        data: formData,
			success: function(json) {
				if (json.titles) {
					$('.dashboard_tabs[data-dashboard="tools"] .dashboard_tab_titles').html(json.titles);
					toolsCache.titles = json.titles;
				}
				if (json.content) {
					$('.dashboard_tabs[data-dashboard="tools"] .dashboard_tab_content_item_wrapper').html(json.content);
					toolsCache.content = json.content;
				}
				toolsCache.step = step;
				toolsCache.tool = tool;

				// Скрываем лоадинг и показываем контент
				var $dashboardTabs = $('.dashboard_tabs[data-dashboard="tools"]');
				$dashboardTabs.find('.dashboard_tabs_loading').hide();
				$dashboardTabs.find('.dashboard_tabs_content_wrapper').show();
				$('.tools_back_btn').remove();
				if (json.back_btn) {
					$('.dashboard_tabs[data-dashboard="tools"]').append(json.back_btn);
				}

				// к-во непрочитанных tools
				$('.dashboard_item[data-dashboard="tools"] .dashboard_item_text_qt').html(json.qt_tools);

				if (json.qt_tools == 0) {
					$('.dashboard_item[data-dashboard="tools"] .dashboard_item_text_qt').css('display','none');
				} else {
					$('.dashboard_item[data-dashboard="tools"] .dashboard_item_text_qt').css('display','inline-block');
				}

				/*// шкала сканирования
				if ($('#tools_3d_scan_gauge').length) {
					var gauge = new Gauge('tools_3d_scan_gauge', {
						'apert': 280,
						// 'radius': 60,
						'insideText': true,
						'lineWidth': 15
					});
					gauge.runDrawGauge();
				}*/

				if (step == 'secret_office') {
					setTimeout(function(){
						(async function connectSdk() {
        					// const sdkKey = '03n54zi8sqy4eybxnutm4upfa' // TODO: replace with your sdk key
        					const sdkKey = 'inbzsdh0qs3br7sffup314f5d' // TODO: replace with your sdk key
        					const iframe = document.getElementById('matterport-iframe');

        					// connect the sdk; log an error and stop if there were any connection issues
        					try {
          						const mpSdk = await window.MP_SDK.connect(
            						iframe, // Obtained earlier
            						sdkKey, // Your SDK key
            						'' // Unused but needs to be a valid string
          						);
          						onShowcaseConnect(mpSdk);
        					} catch (e) {
          						console.error(e);
        					}
      					})();

      					async function onShowcaseConnect(mpSdk) {
        					// insert your sdk code here. See the ref https://matterport.github.io/showcase-sdk//docs/sdk/reference/current/index.html

        					// try retrieving the model data and log the model's sid
        					try {
          						const modelData = await mpSdk.Model.getData();
          						// console.log('Model sid:' + modelData.sid);
        					} catch (e) {
          						console.error(e);
        					}
      					}
					}, 1000);
				}

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
				var $dashboardTabs = $('.dashboard_tabs[data-dashboard="tools"]');
				$dashboardTabs.find('.dashboard_tabs_loading').hide();
				$dashboardTabs.find('.dashboard_tabs_content_wrapper').show();
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});

		// запоминаем открытый тип табов
		setTeamLastTypeTabs('tools');

		// запоминаем открытый tools
		setTeamTabsTextInfo('last_tools', step);
	}

	// Обновить к-во новых НЕоткрытых еще tools
	function updateDontOpenToolsQt() {
		var formData = new FormData();
    	formData.append('op', 'updateDontOpenToolsQt');

    	$.ajax({
			url: '/ajax/ajax_tools.php',
	        type: "POST",
	        dataType: "json",
	        cache: false,
	        contentType: false,
	        processData: false,
	        data: formData,
			success: function(json) {
				$('.dashboard_item[data-dashboard="tools"] .dashboard_item_text_qt').html(json.success);

				if (json.success == 0) {
					$('.dashboard_item[data-dashboard="tools"] .dashboard_item_text_qt').css('display','none');
				} else {
					$('.dashboard_item[data-dashboard="tools"] .dashboard_item_text_qt').css('display','inline-block');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {	
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}

	// добавить tools к списку просмотренных
	function addToolsToViewed(tools) {
		var formData = new FormData();
    	formData.append('op', 'addToolsToActive');
    	formData.append('lang_abbr', $('html').attr('lang'));
    	formData.append('tools', tools);

    	$.ajax({
			url: '/ajax/ajax_tools.php',
	        type: "POST",
	        dataType: "json",
	        cache: false,
	        contentType: false,
	        processData: false,
	        data: formData,
			success: function(json) {
				if (json.success) {
					// Обновить к-во непрочитанных файлов
					updateDontOpenToolsQt();
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {	
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}

$(function() {
	// открыть какую-то конкретную базу данных. Первый основной этап. 4 основные базы
	$('body').on('click', '.dashboard_tab_content_item_start_four_inner_item_tools', function(e){
		if ($(this).attr('data-tools') == 'advanced_search_engine') {
			// в зависимости от того, куда дошли в игре, грузим разные экраны
			$.when(getTeamInfo()).done(function(teamResponse){
				var teamInfo = teamResponse.success;

				if (teamInfo) {
					if (teamInfo.tools_advanced_search_engine_access == 1) {
				        var win = window.open('https://www.google.com/', '_blank');
						if (win) {
							// добавляем tools к списку просмотренных
						    addToolsToViewed('advanced_search_engine');

						    win.focus();
						}
					} else {
						uploadTypeTabsToolsStep('advanced_search_engine', false, true);
					}
				}
			});
		} else if ($(this).attr('data-tools') == 'gps_coordinates') {
			/*$.fancybox.open({
                src: 'https://www.gps-coordinates.net/',
                type: 'iframe',
                opts: {
                    afterShow: function(instance, current) {
                    	addToolsToViewed('gps_coordinates');
                    },
                    beforeShow: function(instance, current) {},
                    beforeClose: function(instance, current) {},
                    iframe : {
                        preload : false
                    }
                }
            });*/

            var win = window.open('https://www.gps-coordinates.net/', '_blank');
			if (win) {
				// добавляем tools к списку просмотренных
			    addToolsToViewed('gps_coordinates');

			    win.focus();
			}
		} else if ($(this).attr('data-tools') == 'symbol_decoder') {
			// в зависимости от того, куда дошли в игре, грузим разные экраны
			$.when(getTeamInfo()).done(function(teamResponse){
				var teamInfo = teamResponse.success;

				if (teamInfo) {
					if (teamInfo.tools_symbol_decoder_access == 1) {
				        // добавляем tools к списку просмотренных
			    		addToolsToViewed('symbol_decoder');

				        uploadTypeTabsToolsStep('symbol_decoder', false, true);
					} else {
						uploadTypeTabsToolsStep('symbol_decoder', false, true);
					}
				}
			});
			// uploadTypeTabsToolsStep('symbol_decoder', false);
		} else if ($(this).attr('data-tools') == '3d_building_scan') {
			// в зависимости от того, куда дошли в игре, грузим разные экраны
			$.when(getTeamInfo()).done(function(teamResponse){
				var teamInfo = teamResponse.success;

				if (teamInfo) {
					if (teamInfo.tools_3d_bulding_scan_access == 1) {
						// добавляем tools к списку просмотренных
    					addToolsToViewed('3d_building_scan');
					}

					if (teamInfo.tools_secret_office_access == 1) {
				        uploadTypeTabsToolsStep('secret_office', '3d_building_scan', true);
					} else {
						uploadTypeTabsToolsStep('3d_building_scan', false, true);
					}
				}
			});
			/*// добавляем tools к списку просмотренных
    		addToolsToViewed('3d_building_scan');

			uploadTypeTabsToolsStep('3d_building_scan', false);*/
		}
	});

	// вернуться назад на предыдущый таб tools
	$('body').on('click', '.tools_back_btn', function(e){
		uploadTypeTabsToolsStep($(this).attr('data-back'), $(this).attr('data-tools'), true);
	});

	// перемещение по табам tools
	$('body').on('click', '.dashboard_tab_title_can_click_tools', function(e){
		uploadTypeTabsToolsStep($(this).attr('data-step'), $(this).attr('data-tools'), true);
	});
});