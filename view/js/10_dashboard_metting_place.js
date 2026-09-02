/* === DASHBOARD - METTING PLACE === */

/* ОБЩИЕ ФУНКЦИИ */
	var mettingPlaceJaneVideoSrc = '';
	var mettingPlaceStageSwitched = false;
	var mettingPlaceStagePersisted = false;

	function getMettingPlaceJaneVideoSrc() {
		if (mettingPlaceJaneVideoSrc) {
			return mettingPlaceJaneVideoSrc;
		}
		return '/video/' + $('html').attr('lang') + '/video_jane_5.mp4';
	}

	function initMettingPlaceCountryAutocomplete() {
		var $root = $('#dashboard-metting-place-country-select');
		if (!$root.length) {
			return;
		}

		$root.removeData('autocomplete-initialized');

		if (window.initAutocompleteSelectComponent) {
			window.initAutocompleteSelectComponent($root);
		}

		$('.dashboard_metting_place_country').off('change.mettingPlaceCountry').on('change.mettingPlaceCountry', function() {
			var formData = new FormData();
			formData.append('op', 'saveTeamTextField');
			formData.append('field', 'metting_place_country_id');
			formData.append('val', $(this).val());

			$.ajax({
				url: '/ajax/ajax.php',
				type: 'POST',
				dataType: 'json',
				cache: false,
				contentType: false,
				processData: false,
				data: formData,
				success: function(json) {
					if (json.country_lang) {
						var message = {
							'op': 'dashboardMettingPlaceUpdateCountry',
							'parameters': {
								'country_lang': json.country_lang,
								'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
								'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
							}
						};
						sendMessageSocket(JSON.stringify(message));
					}
				}
			});
		});
	}

	function setMettingPlaceCountryValue(countryName) {
		var $hidden = $('.dashboard_metting_place_country');
		if (!$hidden.length) {
			return;
		}

		var $root = $hidden.closest('.autocomplete_select_component');
		$hidden.val(countryName).trigger('change');
		$root.find('.autocomplete_select_input').val(countryName);
	}

	function mettingPlaceClearSuccessPopup() {
		$('#popup_success')
			.removeClass('popup_success_metting_place')
			.stop(true, true)
			.hide();
	}

	function mettingPlacePrepareDashboardSwitch() {
		if (typeof dashboardCache !== 'undefined') {
			dashboardCache.step = null;
			dashboardCache.titles = null;
			dashboardCache.content = null;
		}
		showDashboardTabsLoading();
	}

	function mettingPlaceShowRoomNameDashboard() {
		mettingPlacePrepareDashboardSwitch();
		uploadTypeTabsDashboardStep('room_name', false);
	}

	function mettingPlaceApplySideEffects() {
		if (typeof updateDontOpenFilesQt === 'function') {
			updateDontOpenFilesQt();
		}
		if (typeof updateDontOpenToolsQt === 'function') {
			updateDontOpenToolsQt();
		}
		if (typeof viewChatFormMessageHidden === 'function') {
			viewChatFormMessageHidden();
		}
	}

	function mettingPlaceBroadcastVideoFinished() {
		var message = {
			'op': 'closePopupVideoAndMettingPlaceSuccess',
			'parameters': {
				'scoreBeforeDashboardMettingPlace': scoreBeforeDashboardMettingPlace,
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
		};
		sendMessageSocket(JSON.stringify(message));
	}

	function mettingPlaceOnJaneVideoEnded() {
		mettingPlaceBroadcastVideoFinished();
	}

	function mettingPlaceCloseJaneVideoEarly() {
		if (typeof stopPopupVideoPhoneInline === 'function') {
			stopPopupVideoPhoneInline('mettingPlaceJane');
		}

		$('#popup_video_phone').stop(true, true).fadeOut(200, function() {
			$('#popup_video_phone .popup_video_phone_wifi_icons').html('');
			$('#popup_video_phone .popup_video_phone_name').html('');
			$('#popup_video_phone').attr('class', '');
		});

		mettingPlaceBroadcastVideoFinished();
	}

	function mettingPlacePlayJaneInlineVideo() {
		getCallVideoSrc(8, function(videoSrc) {
			if (!videoSrc) {
				videoSrc = getMettingPlaceJaneVideoSrc();
			} else {
				mettingPlaceJaneVideoSrc = videoSrc;
			}

			playPopupVideoPhoneInline(videoSrc, {
				eventNamespace: 'mettingPlaceJane',
				onEnded: mettingPlaceOnJaneVideoEnded
			});
		});
	}

	function mettingPlaceBroadcastStageSwitch() {
		var message = {
			'op': 'dashboardMettingPlaceSwitchStage',
			'parameters': {
				'scoreBeforeDashboardMettingPlace': scoreBeforeDashboardMettingPlace,
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
		};
		sendMessageSocket(JSON.stringify(message));
	}

	function mettingPlacePersistStage() {
		if (mettingPlaceStagePersisted) {
			return;
		}
		mettingPlaceStagePersisted = true;

		var formData = new FormData();
		formData.append('op', 'mettingPlaceUpdateHint');
		formData.append('lang_abbr', $('html').attr('lang'));

		$.ajax({
			url: '/ajax/ajax_dashboard.php',
			type: 'POST',
			dataType: 'json',
			cache: false,
			contentType: false,
			processData: false,
			data: formData,
			success: function(json) {
				if (json.error_verify) {
					window.location.href = json.error_verify;
					return;
				}

				if (!json.success) {
					mettingPlaceStagePersisted = false;
					return;
				}

				$.when(getTeamInfo()).done(function(teamResponse){
					var teamInfo = teamResponse && teamResponse.success ? teamResponse.success : null;
					if (teamInfo) {
						incrementScore(parseInt(teamInfo.score, 10) + 200, 'main', teamInfo.score);
					}
				});

				incrementProgressMission(15);
				mettingPlaceApplySideEffects();
			},
			error: function(xhr, ajaxOptions, thrownError) {
				mettingPlaceStagePersisted = false;
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}

	function mettingPlaceStartStageWithVideo() {
		mettingPlaceClearSuccessPopup();
		mettingPlacePlayJaneInlineVideo();

		if (!mettingPlaceStageSwitched) {
			mettingPlaceStageSwitched = true;
			mettingPlaceShowRoomNameDashboard();
		}

		$.when(getTeamInfo()).done(function(teamResponse){
			var teamInfo = teamResponse && teamResponse.success ? teamResponse.success : null;
			scoreBeforeDashboardMettingPlace = teamInfo ? (parseInt(teamInfo.score, 10) || 0) : 0;

			mettingPlaceBroadcastStageSwitch();
			mettingPlacePersistStage();
		}).fail(function(){
			mettingPlacePersistStage();
		});
	}

	// ввели верно, открываем попап исходящего звонка
	function mettingPlaceOpenOutgoingCall() {
		updateIncomingTime();
		incomingCallTimer = setInterval(function(){
			updateIncomingTime();
		}, 1000);

		$('#popup_video_phone .popup_video_phone_wifi_icons').html('<img src="/images/wifi_icons.png" alt="">');
		$('#popup_video_phone').attr('class','').addClass('popup_video_phone_outgoing_metting_place');

		stopMusic();

		if (!incomingAudio || !isPlaying(incomingAudio)) {
			incomingAudio = new Audio;
			incomingAudio.src = '/music/incoming.mp3';

			var promise = incomingAudio.play();

			if (promise !== undefined) {
				promise.then(_ => {
					incomingMusicTimer = setInterval(function(){
						incomingAudio = new Audio;
						incomingAudio.src = '/music/incoming.mp3';
						incomingAudio.play();
					}, incomingMusicDuration);
				}).catch(error => {
				});
			}
		}

		$('#popup_video_phone').fadeIn(200);
	}

	function mettingPlace() {
		mettingPlaceClearSuccessPopup();

		if (!mettingPlaceStageSwitched) {
			mettingPlaceStageSwitched = true;
			mettingPlaceShowRoomNameDashboard();
		}

		mettingPlacePersistStage();
	}

	function mettingPlaceFromSocket() {
		mettingPlaceClearSuccessPopup();

		if (mettingPlaceStageSwitched) {
			return;
		}
		mettingPlaceStageSwitched = true;
		mettingPlaceShowRoomNameDashboard();

		incrementScoreWithoutSaveDb(scoreBeforeDashboardMettingPlace + 200, 'main', scoreBeforeDashboardMettingPlace);
		incrementProgressMissionWithoutSaveDb(15);
		mettingPlaceApplySideEffects();
	}

	function mettingPlaceSubmit(streetName, houseNumber, city, country, lang_abbr2) {
		setTimeout(function(){
			dataTransferAudio = new Audio;
			dataTransferAudio.src = '/music/data_transfer.mp3';

			var promise = dataTransferAudio.play();

			if (promise !== undefined) {
				promise.then(_ => {
				}).catch(error => {
				});
			}
		}, 500);

		$('.popup_data_transfer_percent span').html('0');
		$('.popup_data_transfer_progress_inner').css('width', '0%');

		setTimeout(function(){
			var dataTransferInterval1 = false;
			var dataTransferSecondIteration = 50;
			var dataTransferSecondTotal = 0;

			dataTransferInterval1 = setInterval(function(){
				if (dataTransferSecondTotal >= (dataTransferMusicDuration + 1500)) {
					clearInterval(dataTransferInterval1);
					dataTransferInterval1 = false;
				}

				dataTransferSecondTotal += dataTransferSecondIteration;

				$('.popup_data_transfer_numbers_one').html(selfRandom(100, 9999));
				$('.popup_data_transfer_numbers_two').html(selfRandom(100, 999));
			}, dataTransferSecondIteration);

			var dataTransferInterval2 = false;
			var dataTransferPlus = Math.round(100 / dataTransferIteration);

			dataTransferInterval2 = setInterval(function(){
				var current = parseInt($('.popup_data_transfer_percent span').html(), 10);
				var next = current + selfRandom(1, dataTransferPlus);

				if (next >= 100) {
					next = 100;
				}

				$('.popup_data_transfer_progress_inner').css('width', next + '%');
				$('.popup_data_transfer_percent span').html(next);

				if (next == 100) {
					clearInterval(dataTransferInterval2);
					dataTransferInterval2 = false;

					var formData = new FormData();
			    	formData.append('op', 'validateMettingPlaceSearch');
			    	formData.append('street_name', streetName);
			    	formData.append('house_number', houseNumber);
			    	formData.append('city', city);
			    	formData.append('country', country);
			    	formData.append('lang_abbr', lang_abbr2);

			    	$.ajax({
						url: '/ajax/ajax_dashboard.php',
				        type: "POST",
				        dataType: "json",
				        cache: false,
				        contentType: false,
				        processData: false,
				        data: formData,
						success: function(json) {
							$('#popup_data_transfer').fadeOut(200);

							if (dataTransferAudio && isPlaying(dataTransferAudio)) {
								dataTransferAudio.pause();
							}

							if (json.success) {
								$('#popup_success .popup_success_input').html(json.success_lang[$('html').attr('lang')].success_input);
								$('#popup_success .popup_success_text').html(json.success_lang[$('html').attr('lang')].success_text);
								$('#popup_success .popup_success_close .btn span').html(json.success_lang[$('html').attr('lang')].success_close);
								$('#popup_success').addClass('popup_success_metting_place').css('display','block');

								successAudio = new Audio;
								successAudio.src = '/music/done.mp3';

								var promise = successAudio.play();

								if (promise !== undefined) {
									promise.then(_ => {
									}).catch(error => {
									});
								}
							} else {
								$('#popup_search_error .popup_search_error_input').html(json.error_lang[$('html').attr('lang')].error_input);
								$('#popup_search_error .popup_search_error_text').html(json.error_lang[$('html').attr('lang')].error_text);
								$('#popup_search_error').css('display','block');

								errorAudio = new Audio;
								errorAudio.src = '/music/error.mp3';

								var promise = errorAudio.play();

								if (promise !== undefined) {
									promise.then(_ => {
									}).catch(error => {
									});
								}
							}
						},
						error: function(xhr, ajaxOptions, thrownError) {
							console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
				}
			}, (dataTransferMusicDuration / dataTransferIteration));

			$('#popup_data_transfer').css('display','block');
		}, 210);
	}

	function mettingPlaceCloseIncomingCall() {
		if ($('.music_on').length && $('.music_on').hasClass('music_active')) {
			playMusic();
		}

		clearInterval(incomingMusicTimer);
		incomingMusicTimer = false;

		if (incomingAudio && isPlaying(incomingAudio)) {
			incomingAudio.pause();
		}

		clearInterval(incomingCallTimer);
		incomingCallTimer = false;

		$('#popup_video_phone').fadeOut(200);

		$('#popup_success').addClass('popup_success_metting_place').fadeIn(200);

		setTimeout(function(){
			$('#popup_video_phone .popup_video_phone_wifi_icons').html('');
			$('#popup_video_phone .popup_video_phone_name').html('');
			$('#popup_video_phone').attr('class','');
		}, 210);
	}

$(function() {
	$('.dashboard_tabs[data-dashboard="dashboard"]').on('keyup', '.dashboard_metting_place_street_name', function(e){
		if (e.which == 13) {
			$('.dashboard_metting_place_search').trigger('click');
		} else {
			var message = {
				'op': 'dashboardMettingPlaceKeyupStreet',
				'parameters': {
					'street': $(this).val(),
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));
		}
	});
	$('.dashboard_tabs[data-dashboard="dashboard"]').on('keyup', '.dashboard_metting_place_house_number', function(e){
		if (e.which == 13) {
			$('.dashboard_metting_place_search').trigger('click');
		} else {
			var message = {
				'op': 'dashboardMettingPlaceKeyupHouse',
				'parameters': {
					'house': $(this).val(),
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));
		}
	});
	$('.dashboard_tabs[data-dashboard="dashboard"]').on('keyup', '.dashboard_metting_place_city', function(e){
		if (e.which == 13) {
			$('.dashboard_metting_place_search').trigger('click');
		} else {
			var message = {
				'op': 'dashboardMettingPlaceKeyupCity',
				'parameters': {
					'city': $(this).val(),
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));
		}
	});

	$('body').on('click', '.dashboard_metting_place_search', function(e){
		var err = false;
		var streetName = $.trim($('.dashboard_metting_place_street_name').val());
		var houseNumber = $.trim($('.dashboard_metting_place_house_number').val());
		var city = $.trim($('.dashboard_metting_place_city').val());
		var country = $.trim($('.dashboard_metting_place_country').val() || '');

		if (streetName == '') {
			$('.dashboard_metting_place_street_name_error').addClass('error_text_database_car_register_active');
			err = true;
		} else {
			$('.dashboard_metting_place_street_name_error').removeClass('error_text_database_car_register_active');
		}

		if (houseNumber == '') {
			$('.dashboard_metting_place_house_number_error').addClass('error_text_database_car_register_active');
			err = true;
		} else {
			$('.dashboard_metting_place_house_number_error').removeClass('error_text_database_car_register_active');
		}

		if (city == '') {
			$('.dashboard_metting_place_city_error').addClass('error_text_database_car_register_active');
			err = true;
		} else {
			$('.dashboard_metting_place_city_error').removeClass('error_text_database_car_register_active');
		}

		if (country == '') {
			$('.dashboard_metting_place_country_error').addClass('error_text_database_car_register_active');
			err = true;
		} else {
			$('.dashboard_metting_place_country_error').removeClass('error_text_database_car_register_active');
		}

		if (!err) {
			var message = {
				'op': 'dashboardMettingPlaceNoEmptyFields',
				'parameters': {
					'street_name': streetName,
					'house_number': houseNumber,
					'city': city,
					'country': country,
					'lang_abbr': $('html').attr('lang'),
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));

			mettingPlaceSubmit(streetName, houseNumber, city, country, $('html').attr('lang'));
		} else {
			var message = {
				'op': 'dashboardMettingPlaceEmptyFields',
				'parameters': {
					'street_name_error': (streetName == '') ? true : false,
					'house_number_error': (houseNumber == '') ? true : false,
					'city_error': (city == '') ? true : false,
					'country_error': (country == '') ? true : false,
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));
		}
	});

	$('body').on('click', '.popup_success_metting_place .popup_success_close', function(e){
		var message = {
			'op': 'dashboardMettingPlaceCloseSuccessPopupAndOpenOutgoingCall',
			'parameters': {
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
        };
        sendMessageSocket(JSON.stringify(message));

		$('#popup_success').removeClass('popup_success_metting_place').fadeOut(200);

		mettingPlaceOpenOutgoingCall();
	});

	$('body').on('click', '.popup_video_phone_outgoing_metting_place .popup_video_phone_btn_answer_wrapper', function(e){
		mettingPlaceClearSuccessPopup();

		var message = {
			'op': 'dashboardMettingPlaceCallAnswer',
			'parameters': {
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
        };
        sendMessageSocket(JSON.stringify(message));

		if ($('.music_on').length && $('.music_on').hasClass('music_active')) {
			playMusic();
		}

		clearInterval(incomingMusicTimer);
		incomingMusicTimer = false;

		if (incomingAudio && isPlaying(incomingAudio)) {
			incomingAudio.pause();
		}

		clearInterval(incomingCallTimer);
		incomingCallTimer = false;

		mettingPlaceStartStageWithVideo();

		var formData = new FormData();
    	formData.append('op', 'updateDatetimeCall');
    	formData.append('lang_abbr', $('html').attr('lang'));
    	formData.append('call_id', 8);

    	$.ajax({
			url: '/ajax/ajax_calls.php',
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

	$('body').on('click', '.popup_video_phone_outgoing_metting_place .popup_video_phone_bg, .popup_video_phone_outgoing_metting_place .popup_video_phone_btn_decline_wrapper', function(e){
		if ($('#popup_video_phone .popup_video_phone_inline_video').is(':visible')) {
			mettingPlaceCloseJaneVideoEarly();
			return;
		}

		var message = {
			'op': 'dashboardMettingPlaceCloseIncomingCall',
			'parameters': {
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
        };
        sendMessageSocket(JSON.stringify(message));

        mettingPlaceCloseIncomingCall();
	});

	$('body').on('click', '.metting_place_answer_incoming_video .popup_video_phone_video_bg, .metting_place_answer_incoming_video .popup_video_close', function(e){
		stopVideoCall();
		closePopupVideoCall();

		var message = {
			'op': 'stopVideoAndClosePopupVideoAndMettingPlaceSuccess',
			'parameters': {
				'scoreBeforeDashboardMettingPlace': scoreBeforeDashboardMettingPlace,
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
		};
		sendMessageSocket(JSON.stringify(message));

		mettingPlace();
	});
});
