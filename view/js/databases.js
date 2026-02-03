/* === ГЛАВНОЕ ОКНО ИГРЫ - ЦЕНТРАЛЬНЫЙ БЛОК ИНФОРМАЦИИ - DATABASES === */

/* КЭШИРОВАНИЕ ДАННЫХ */
	var databasesCache = {
		step: null,
		database: null,
		titles: null,
		content: null
	};

/* ОБЩИЕ ФУНКЦИИ */
	// Открыть тип табов: databases
	function openTypeTabsDatabases(isSocketSend) {
		$('.dashboard_tabs[data-dashboard="databases"]').addClass('dashboard_tabs_active');
		$('.dashboard_item[data-dashboard="databases"]').addClass('dashboard_item_active');

		$.when(getTeamInfo()).done(function(teamResponse){
			var teamInfo = teamResponse.success;

			if (teamInfo) {
				uploadTypeTabsDatabasesStep(teamInfo.last_databases, false, isSocketSend);
			}
		});
	}
	// Открыть тип табов: databases - главный экран
	function openTypeTabsDatabasesMain(isSocketSend) {
		$('.dashboard_tabs[data-dashboard="databases"]').addClass('dashboard_tabs_active');
		$('.dashboard_item[data-dashboard="databases"]').addClass('dashboard_item_active');

		// uploadTypeTabsDatabasesStep('databases_start_four', false, isSocketSend);
		$.when(getTeamInfo()).done(function(teamResponse){
			var teamInfo = teamResponse.success;

			if (teamInfo) {
				if (teamInfo.last_databases == 'no_access') {
					uploadTypeTabsDatabasesStep(teamInfo.last_databases, false, isSocketSend);
				} else {
					uploadTypeTabsDatabasesStep('databases_start_four', false, isSocketSend);
				}
			}
		});
	}

	// загрузить конкретный экран (с переключателем табов) для databases
	function uploadTypeTabsDatabasesStep(step, database, isSocketSend) {
		if (typeof database === "undefined") {
			database = false;
		}

		var $dashboardTabs = $('.dashboard_tabs[data-dashboard="databases"]');
		
		// Проверяем, есть ли уже загруженные данные для этой стадии
		if (databasesCache.step === step && databasesCache.database === database && databasesCache.titles && databasesCache.content) {
			// Данные уже загружены - показываем их сразу без лоадинга
			$('.dashboard_tabs[data-dashboard="databases"] .dashboard_tab_titles').html(databasesCache.titles);
			$('.dashboard_tabs[data-dashboard="databases"] .dashboard_tab_content_item_wrapper').html(databasesCache.content);
			$dashboardTabs.find('.dashboard_tabs_loading').hide();
			$dashboardTabs.find('.dashboard_tabs_content_wrapper').show();
			return;
		}

		// Показываем лоадинг и скрываем контент
		$dashboardTabs.find('.dashboard_tabs_loading').show();
		$dashboardTabs.find('.dashboard_tabs_content_wrapper').hide();

		var formData = new FormData();
    	formData.append('op', 'uploadTypeTabsDatabasesStep');
    	formData.append('lang_abbr', $('html').attr('lang'));
    	formData.append('step', step);
    	formData.append('database', database);

    	$.ajax({
			url: '/ajax/ajax_databases.php',
	        type: "POST",
	        dataType: "json",
	        cache: false,
	        contentType: false,
	        processData: false,
	        data: formData,
			success: function(json) {
				if (json.titles) {
					$('.dashboard_tabs[data-dashboard="databases"] .dashboard_tab_titles').html(json.titles);
					databasesCache.titles = json.titles;
				}
				if (json.content) {
					$('.dashboard_tabs[data-dashboard="databases"] .dashboard_tab_content_item_wrapper').html(json.content);
					databasesCache.content = json.content;
				}
				databasesCache.step = step;
				databasesCache.database = database;

				// Скрываем лоадинг и показываем контент
				var $dashboardTabs = $('.dashboard_tabs[data-dashboard="databases"]');
				$dashboardTabs.find('.dashboard_tabs_loading').hide();
				$dashboardTabs.find('.dashboard_tabs_content_wrapper').show();
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
				if (step == 'databases_start_four_inner_second_car_register_huilov') { // если блок с результатами поиска car register1
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
							// printAudio.play();

							// Autoplay
							var promise = printAudio.play();

							if (promise !== undefined) {
								promise.then(_ => {
									// console.log('autoplay');
								}).catch(error => {
									// console.log('autoplay ERR');
								});
							}

							var bubbleArrayText = {};
	                        bubbleArrayText[0] = json.error_lang[langAbbr].text92;
	                        bubbleArrayText[1] = json.error_lang[langAbbr].text93;
	                        bubbleArrayText[2] = json.error_lang[langAbbr].text94;
	                        bubbleArrayText[3] = json.error_lang[langAbbr].text95;
	                        bubbleArrayText[4] = json.error_lang[langAbbr].text97;
	                        bubbleArrayText[5] = json.error_lang[langAbbr].text98;
	                        bubbleArrayText[6] = json.error_lang[langAbbr].text99;
	                        bubbleArrayText[7] = json.error_lang[langAbbr].text100;
	                        bubbleArrayText[8] = json.error_lang[langAbbr].text101;
	                        bubbleArrayText[9] = json.error_lang[langAbbr].text102;
	                        bubbleArrayText[10] = json.error_lang[langAbbr].text103;
	                        bubbleArrayText[11] = json.error_lang[langAbbr].text104;

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
	                            	if (printAudio && isPlaying(printAudio)) {
	                            		printAudio.pause();
	                            	}
	                            }
	                        }
	                        bubbleByNumber(0);
						}, 1000);

						// добавляем очки
						$.when(getTeamInfo()).done(function(teamResponse){
							var teamInfo = teamResponse.success;

							if ($('.dashboard_car_register2_inner_bubble_team').length) {
								incrementScore(parseInt(teamInfo.score, 10) + 150, 'main', teamInfo.score);
							} else {
								incrementScoreWithoutSaveDb(scoreBeforeDatabaseCarRegister + 150, 'main', scoreBeforeDatabaseCarRegister);
							}
						});

						// обновляем mission progress
						if ($('.dashboard_car_register2_inner_bubble_team').length) {
							incrementProgressMission(10);
						} else {
							incrementProgressMissionWithoutSaveDb(10);
						}
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
							// printAudio.play();

							// Autoplay
							var promise = printAudio.play();

							if (promise !== undefined) {
								promise.then(_ => {
									// console.log('autoplay');
								}).catch(error => {
									// console.log('autoplay ERR');
								});
							}

							var bubbleArrayText = {};
	                        bubbleArrayText[0] = json.error_lang[langAbbr].text116;
	                        bubbleArrayText[1] = json.error_lang[langAbbr].text118;
	                        bubbleArrayText[2] = json.error_lang[langAbbr].text120;
	                        bubbleArrayText[3] = json.error_lang[langAbbr].text122;
	                        bubbleArrayText[4] = json.error_lang[langAbbr].text124;
	                        bubbleArrayText[5] = json.error_lang[langAbbr].text126;
	                        bubbleArrayText[6] = json.error_lang[langAbbr].text128;
	                        bubbleArrayText[7] = json.error_lang[langAbbr].text130;
	                        bubbleArrayText[8] = json.error_lang[langAbbr].text131;

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
	                            	if (printAudio && isPlaying(printAudio)) {
	                            		printAudio.pause();
	                            	}

	                            	// убираем анимацию с лица
	                            	if ($('.huilov_face_anim').length) {
	                            		setTimeout(function(){
											$('.huilov_face_anim').remove();
										}, 100);
									}
	                            }
	                        }
	                        bubbleByNumber(0);
						}, 1000);

						// автоматический скролл блока с данными вниз при печатании текста
						setTimeout(function(){
							$(".dashboard_personal_files2_private_individuals_huilov_right").mCustomScrollbar("scrollTo","last",{scrollInertia:700});
						}, 4000);

						// добавляем очки
						$.when(getTeamInfo()).done(function(teamResponse){
							var teamInfo = teamResponse.success;

							if ($('.dashboard_personal_files2_private_individuals_huilov_inner_bubble_team').length) {
								incrementScore(parseInt(teamInfo.score, 10) + 100, 'main', teamInfo.score);
							} else {
								// incrementScoreWithoutSaveDb(parseInt(teamInfo.score, 10) + 100, 'main', teamInfo.score);
								incrementScoreWithoutSaveDb(scoreBeforeDatabasePersonalfilesPrivateindividuals + 100, 'main', scoreBeforeDatabasePersonalfilesPrivateindividuals);
							}
						});

						// обновляем mission progress
						if ($('.dashboard_personal_files2_private_individuals_huilov_inner_bubble_team').length) {
							incrementProgressMission(5);
						} else {
							incrementProgressMissionWithoutSaveDb(5);
						}
					} else {
						// убираем анимацию с лица
						if ($('.huilov_face_anim').length) {
							$('.huilov_face_anim').remove();
						}
					}
				} else if (step == 'databases_start_four_inner_second_personal_files_ceo_database_rod') { // если грузим personal files - ceo database - rod
					// только при первой загрузке экрана
					if ($('.dashboard_personal_files2_ceo_database_rod_inner_bubble').length) {
						// печатаем текст
						setTimeout(function(){
							// звуки
							printAudio = new Audio;
							printAudio.src = '/music/print.mp3';
							// printAudio.play();

							// Autoplay
							var promise = printAudio.play();

							if (promise !== undefined) {
								promise.then(_ => {
									// console.log('autoplay');
								}).catch(error => {
									// console.log('autoplay ERR');
								});
							}

							var bubbleArrayText = {};
	                        bubbleArrayText[0] = json.error_lang[langAbbr].text133;
	                        bubbleArrayText[1] = json.error_lang[langAbbr].text134;
	                        bubbleArrayText[2] = json.error_lang[langAbbr].text135;
	                        bubbleArrayText[3] = json.error_lang[langAbbr].text137;
	                        bubbleArrayText[4] = json.error_lang[langAbbr].text139;

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
	                            	if (printAudio && isPlaying(printAudio)) {
	                            		printAudio.pause();
	                            	}

	                            	// убираем анимацию с лица
	                            	if ($('.rod_face_anim').length) {
	                            		setTimeout(function(){
											$('.rod_face_anim').remove();
										}, 100);
									}
	                            }
	                        }
	                        bubbleByNumber(0);
						}, 1000);

						// добавляем очки
						$.when(getTeamInfo()).done(function(teamResponse){
							var teamInfo = teamResponse.success;

							if ($('.dashboard_personal_files2_ceo_database_rod_inner_bubble_team').length) {
								incrementScore(parseInt(teamInfo.score, 10) + 300, 'main', teamInfo.score);
							} else {
								incrementScoreWithoutSaveDb(scoreBeforeDatabasePersonalfilesCeodatabase + 300, 'main', scoreBeforeDatabasePersonalfilesCeodatabase);
							}
						});

						// обновляем mission progress
						if ($('.dashboard_personal_files2_ceo_database_rod_inner_bubble_team').length) {
							incrementProgressMission(10);
						} else {
							incrementProgressMissionWithoutSaveDb(10);
						}
					} else {
						// убираем анимацию с лица
						if ($('.rod_face_anim').length) {
							$('.rod_face_anim').remove();
						}
					}
				} else if (step == 'databases_start_four_inner_first_mobile_calls_messages') { // просмотрены database mobile calls
					// только при первой загрузке экрана
					if ($('.dashboard_mobile_calls2_inner_first').length) {
						// добавляем очки
						$.when(getTeamInfo()).done(function(teamResponse){
							var teamInfo = teamResponse.success;

							if ($('.dashboard_mobile_calls2_inner_first_team').length) {
								incrementScore(parseInt(teamInfo.score, 10) + 100, 'main', teamInfo.score);
							} else {
								incrementScoreWithoutSaveDb(scoreBeforeDatabaseMobileCalls + 100, 'main', scoreBeforeDatabaseMobileCalls);
							}
						});

						// обновляем mission progress
						if ($('.dashboard_mobile_calls2_inner_first_team').length) {
							incrementProgressMission(5);
						} else {
							incrementProgressMissionWithoutSaveDb(5);
						}
					}
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
				var $dashboardTabs = $('.dashboard_tabs[data-dashboard="databases"]');
				$dashboardTabs.find('.dashboard_tabs_loading').hide();
				$dashboardTabs.find('.dashboard_tabs_content_wrapper').show();
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});

		// запоминаем открытый тип табов
		setTeamLastTypeTabs('databases');

		// запоминаем открытый databases
		setTeamTabsTextInfo('last_databases', step);
	}

	// Обновить к-во новых НЕоткрытых еще баз данных
	function updateDontOpenDatabasesQt() {
		var formData = new FormData();
    	formData.append('op', 'updateDontOpenDatabasesQt');

    	$.ajax({
			url: '/ajax/ajax_databases.php',
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

$(function() {
	// открыть какую-то конкретную базу данных. Первый основной этап. 4 основные базы
	$('body').on('click', '.dashboard_tab_content_item_start_four_inner_item', function(e){
		if ($(this).attr('data-database') == 'car_register') {
			// в зависимости от того, нашли ли уже авто, грузим разные экраны
			$.when(getTeamInfo()).done(function(teamResponse){
				var teamInfo = teamResponse.success;

				if (teamInfo) {
					if (teamInfo.car_register_print_text_huilov == 1) {
						// если нашли уже car register - huilov
						/*mySocketLastAction = 'databaseCarRegisterHuilov';
						var message = {
							'op': 'databaseCarRegisterHuilov',
							'parameters': {},
							'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
							'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				        };
				        sendMessageSocket(JSON.stringify(message));*/

				        uploadTypeTabsDatabasesStep('databases_start_four_inner_second_car_register_huilov', 'car_register', true);
					} else {
						/*mySocketLastAction = 'databaseCarRegister';
						var message = {
							'op': 'databaseCarRegister',
							'parameters': {},
							'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
							'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				        };
				        sendMessageSocket(JSON.stringify(message));*/

				        // экран поиска car register
						uploadTypeTabsDatabasesStep('databases_start_four_inner_first_car_register', 'car_register', true);
					}
				}
			});
		} else if ($(this).attr('data-database') == 'bank_transactions') {
			// $('#section_game').attr('data-last-open-databases', 'databases_start_four_inner_first_bank_transactions');

			/*mySocketLastAction = 'databaseBankTransactions';
			var message = {
				'op': 'databaseBankTransactions',
				'parameters': {},
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
	        };
	        sendMessageSocket(JSON.stringify(message));*/

			// в зависимости от того, куда дошли, грузим разные экраны
			$.when(getTeamInfo()).done(function(teamResponse){
				var teamInfo = teamResponse.success;

				if (teamInfo.databases_bank_transactions_access == 1) {
					uploadTypeTabsDatabasesStep('databases_bank_transactions_success', 'bank_transactions', true);
				} else {
					uploadTypeTabsDatabasesStep('databases_start_four_inner_first_bank_transactions', 'bank_transactions', true);
				}
			});
		} else if ($(this).attr('data-database') == 'personal_files') {
			/*mySocketLastAction = 'databasePersonalFiles';
			var message = {
				'op': 'databasePersonalFiles',
				'parameters': {},
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
	        };
	        sendMessageSocket(JSON.stringify(message));*/

			uploadTypeTabsDatabasesStep('databases_start_four_inner_first_personal_files', 'personal_files', true);
		} else if ($(this).attr('data-database') == 'mobile_calls') {
			// в зависимости от того, ввели ли уже правильный номер телефона, грузим разные экраны
			$.when(getTeamInfo()).done(function(teamResponse){
				var teamInfo = teamResponse.success;

				if (teamInfo) {
					if (teamInfo.mobile_calls_print_messages == 1) {
						/*mySocketLastAction = 'databaseMobileCallsMessages';
						var message = {
							'op': 'databaseMobileCallsMessages',
							'parameters': {},
							'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
							'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				        };
				        sendMessageSocket(JSON.stringify(message));*/

				        uploadTypeTabsDatabasesStep('databases_start_four_inner_first_mobile_calls_messages', 'mobile_calls', true);
					} else {
						/*mySocketLastAction = 'databaseMobileCalls';
						var message = {
							'op': 'databaseMobileCalls',
							'parameters': {},
							'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
							'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				        };
				        sendMessageSocket(JSON.stringify(message));*/

				        uploadTypeTabsDatabasesStep('databases_start_four_inner_first_mobile_calls', 'mobile_calls', true);
					}
				}
			});
		}
	});

	// вернуться назад на предыдущый таб базы данных
	$('body').on('click', '.dashboard_back_btn', function(e){
		uploadTypeTabsDatabasesStep($(this).attr('data-back'), $(this).attr('data-database'), true);
	});

	// перемещение по табам databases
	$('body').on('click', '.dashboard_tab_title_can_click', function(e){
		// $('#section_game').attr('data-last-open-databases', $(this).attr('data-step'));

		uploadTypeTabsDatabasesStep($(this).attr('data-step'), $(this).attr('data-database'), true);
	});
});