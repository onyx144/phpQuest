/* === DATABASES - BANK TRANSACTIONS SEARCH === */

/* ОБЩИЕ ФУНКЦИИ */
	function bankTransactionsSubmit(digits, amount, date, lang_abbr2, saveScore) {
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
		    	formData.append('op', 'validateBankTransactionsSearch');
		    	formData.append('digits', digits);
		    	formData.append('amount', amount);
		    	formData.append('date', date);
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
							uploadTypeTabsDatabasesStep('databases_bank_transactions_success', 'bank_transactions', false);

							if (saveScore || scoreBeforeDatabasesBankTransactions == 0) {
								$.when(getTeamInfo()).done(function(teamResponse){
									var teamInfo = teamResponse.success;

									// добавляем очки
									incrementScore(parseInt(teamInfo.score, 10) + 250, 'main', teamInfo.score);

									// обновляем mission progress
									incrementProgressMission(5);
								});
							} else {
								// добавляем очки
								incrementScoreWithoutSaveDb(scoreBeforeDatabasesBankTransactions + 250, 'main', scoreBeforeDatabasesBankTransactions);

								// обновляем mission progress
								incrementProgressMissionWithoutSaveDb(5);
							}
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
	// ввод данных в поле Digits
	$('.dashboard_tabs[data-dashboard="databases"]').on('keyup', '.dashboard_bank_transactions1_digits', function(e){
		if (e.which == 13) {
			$('.dashboard_bank_transactions1_search').trigger('click');
		} else {
			// socket
			var message = {
				'op': 'databasesBankTransactionsKeyupDigits',
				'parameters': {
					'digits': $(this).val(),
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));
		}
	});

	// ввод данных в поле Amount
	$('.dashboard_tabs[data-dashboard="databases"]').on('keyup', '.dashboard_bank_transactions1_amount', function(e){
		if (e.which == 13) {
			$('.dashboard_bank_transactions1_search').trigger('click');
		} else {
			// socket
			var message = {
				'op': 'databasesBankTransactionsKeyupAmount',
				'parameters': {
					'amount': $(this).val(),
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));
		}
	});

	// отправить данные из формы поиска
	$('body').on('click', '.dashboard_bank_transactions1_search', function(e){
		var err = false;
		var digits = $.trim($('.dashboard_bank_transactions1_digits').val());
		var amount = $.trim($('.dashboard_bank_transactions1_amount').val());
		var date = $.trim($('.dashboard_bank_transactions1_date').val());

		if (digits == '') {
			$('.dashboard_bank_transactions1_digits_error').addClass('error_text_database_car_register_active');
			err = true;
		} else {
			$('.dashboard_bank_transactions1_digits_error').removeClass('error_text_database_car_register_active');
		}

		if (amount == '') {
			$('.dashboard_bank_transactions1_amount_error').addClass('error_text_database_car_register_active');
			err = true;
		} else {
			$('.dashboard_bank_transactions1_amount_error').removeClass('error_text_database_car_register_active');
		}

		if (date == '') {
			$('.dashboard_bank_transactions1_date_error').addClass('error_text_database_car_register_active');
			err = true;
		} else {
			$('.dashboard_bank_transactions1_date_error').removeClass('error_text_database_car_register_active');
		}

		if (!err) {
			$.when(getTeamInfo()).done(function(teamResponse){
				var teamInfo = teamResponse.success;

				scoreBeforeDatabasesBankTransactions = parseInt(teamInfo.score, 10);

				// socket
				var message = {
					'op': 'databasesBankTransactionsNoEmptyFields',
					'parameters': {
						'digits': digits,
						'amount': amount,
						'date': date,
						'scoreBeforeDatabasesBankTransactions': scoreBeforeDatabasesBankTransactions,
						'lang_abbr': $('html').attr('lang'),
						'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
						'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
					}
		        };
		        sendMessageSocket(JSON.stringify(message));

				bankTransactionsSubmit(digits, amount, date, $('html').attr('lang'), true);
			});
		} else {
			// socket
			var message = {
				'op': 'databasesBankTransactionsEmptyFields',
				'parameters': {
					'digits_error': (digits == '') ? true : false,
					'amount_error': (amount == '') ? true : false,
					'date_error': (date == '') ? true : false,
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));
		}
	});
});