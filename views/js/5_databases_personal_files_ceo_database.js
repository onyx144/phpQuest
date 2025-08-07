/* === DATABASES - PERSONAL FILES - CEO DATABASE === */

/* ОБЩИЕ ФУНКЦИИ */
	function databasePersonalFilesCeoDatabaseNoEmptyFields(firstname, lastname, lang_abbr2) {
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
		// searchAudio.play();

		// Autoplay
		var promise = searchAudio.play();

		if (promise !== undefined) {
			promise.then(_ => {
				// console.log('autoplay');
			}).catch(error => {
				// console.log('autoplay ERR');
			});
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

				// правильные ли данные введены и действие дальше
				var formData = new FormData();
		    	formData.append('op', 'validatePersonalFilesCeoDatabaseSearch');
		    	formData.append('firstname', firstname);
		    	formData.append('lastname', lastname);
		    	formData.append('lang_abbr', lang_abbr2);

		    	$.ajax({
					url: '/ajax/ajax_databases.php',
			        type: "POST",
			        dataType: "json",
			        cache: false,
			        contentType: false,
			        processData: false,
			        data: formData,
					success: function(json) {
						// скрываем попап поиска
						$('#popup_search_processing').css('display','none');

						if (json.success) {
							// звук успешного выполнения
							if (searchAudio && isPlaying(searchAudio)) {
								searchAudio.pause();
							}

							successAudio = new Audio;
							successAudio.src = '/music/done.mp3';
							// successAudio.play();

							// Autoplay
							var promise = successAudio.play();

							if (promise !== undefined) {
								promise.then(_ => {
									// console.log('autoplay');
								}).catch(error => {
									// console.log('autoplay ERR');
								});
							}

							// грузим новый таб
							uploadTypeTabsDatabasesStep('databases_start_four_inner_second_personal_files_ceo_database_rod', 'personal_files', false);
						} else {
							// отображаем попап ошибки
							$('#popup_search_error .popup_search_error_input').html(json.error_lang[$('html').attr('lang')].error_input);
							$('#popup_search_error .popup_search_error_text').html(json.error_lang[$('html').attr('lang')].error_text);
							$('#popup_search_error').css('display','block');

							// звук ошибки
							if (searchAudio && isPlaying(searchAudio)) {
								searchAudio.pause();
							}

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
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {	
						console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			}
		}, (databaseSearchDuration / databaseSearchIteration));
	}

$(function() {
	// открыть personal files - ceo database
	$('body').on('click', '.dashboard_personal_files1_category_ceo_database', function(e){
		$.when(getTeamInfo()).done(function(teamResponse){
			var teamInfo = teamResponse.success;

			if (teamInfo) {
				if (teamInfo.ceo_database_print_text_rod == 1) { // если нашли уже
					uploadTypeTabsDatabasesStep('databases_start_four_inner_second_personal_files_ceo_database_rod', 'personal_files', true);
				} else {
					uploadTypeTabsDatabasesStep('databases_start_four_inner_second_personal_files_ceo_database', 'personal_files', true);
				}
			}
		});
	});

	// нажали на кнопку поиска
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
			// фиксируем к-во очков, которое было у команды перед успешным результатом поиска. Для правильного подсчета очков команды
			$.when(getTeamInfo()).done(function(teamResponse){
				var teamInfo = teamResponse.success;

				scoreBeforeDatabasePersonalfilesCeodatabase = parseInt(teamInfo.score, 10);

				// socket
				var message = {
					'op': 'databasePersonalFilesCeoDatabaseNoEmptyFields',
					'parameters': {
						'firstname': firstname,
						'lastname': lastname,
						'lang_abbr': $('html').attr('lang'),
						'scoreBeforeDatabasePersonalfilesCeodatabase': scoreBeforeDatabasePersonalfilesCeodatabase,
						'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
						'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
					}
		        };
		        sendMessageSocket(JSON.stringify(message));

				databasePersonalFilesCeoDatabaseNoEmptyFields(firstname, lastname, $('html').attr('lang'));
			});
		} else {
			// socket
			var message = {
				'op': 'databasePersonalFilesCeoDatabaseEmptyFields',
				'parameters': {
					'firstname_error': (firstname == '') ? true : false,
					'lastname_error': (lastname == '') ? true : false,
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));
		}
	});
});