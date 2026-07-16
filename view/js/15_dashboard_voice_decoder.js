/* === DASHBOARD - VOICE DECODER === */

var voiceDecoderCurrentAudio = null;
var voiceDecoderCurrentId = null;
var voiceDecoderLastAudioFind = [];

function applyVoiceClipsAfterDynamicContent() {
	if (voiceDecoderLastAudioFind.length && typeof hideFoundVoiceClips === 'function') {
		hideFoundVoiceClips(voiceDecoderLastAudioFind);
		return;
	}
	if (typeof refreshVoiceDecoderState === 'function') {
		refreshVoiceDecoderState(true);
	}
}

function applyVoiceDecoderState(voiceCount) {
	var count = parseInt(voiceCount, 10);
	if (isNaN(count) || count < 0) {
		count = 0;
	}
	if (count > 4) {
		count = 4;
	}

	$('.dashboard_voice_decoder_count').text(count);
	$('.dashboard_voice_decoder_dots').attr('data-count', count);
	$('.dashboard_voice_decoder_dot').removeClass('dashboard_voice_decoder_dot_active');
	for (var i = 1; i <= count; i++) {
		$('.dashboard_voice_decoder_dot[data-index="' + i + '"]').addClass('dashboard_voice_decoder_dot_active');
	}

	if (count >= 4) {
		$('.dashboard_voice_decoder_decrypt_btn').removeClass('dashboard_voice_decoder_btn_hidden');
	} else {
		$('.dashboard_voice_decoder_decrypt_btn').addClass('dashboard_voice_decoder_btn_hidden');
	}
}

function hideFoundVoiceClips(audioFind) {
	var found = audioFind || [];
	$('.voice_clip_widget').each(function() {
		var id = parseInt($(this).attr('data-audio-id'), 10);
		var isFound = false;
		for (var i = 0; i < found.length; i++) {
			if (parseInt(found[i], 10) === id) {
				isFound = true;
				break;
			}
		}
		if (isFound) {
			$(this).addClass('voice_clip_widget_found').hide();
			var audioEl = $(this).find('audio').get(0);
			if (audioEl) {
				audioEl.pause();
				audioEl.currentTime = 0;
			}
			$(this).find('.voice_clip_btn').removeClass('is-playing');
		} else if ($('body').hasClass('voice_decoder_stage_active')) {
			$(this).removeClass('voice_clip_widget_found').css('display', '');
		}
	});
}

function applyVoiceDecoderFullState(json) {
	if (!json) {
		return;
	}
	if (typeof json.voice_count !== 'undefined') {
		applyVoiceDecoderState(json.voice_count);
	}
	if (json.audio_find) {
		voiceDecoderLastAudioFind = json.audio_find;
		hideFoundVoiceClips(json.audio_find);
	}
}

function refreshVoiceDecoderState(forceShowClips) {
	var formData = new FormData();
	formData.append('op', 'getVoiceDecoderState');

	$.ajax({
		url: '/ajax/ajax_dashboard.php',
		type: "POST",
		dataType: "json",
		cache: false,
		contentType: false,
		processData: false,
		data: formData,
		success: function(json) {
			if (json && json.success) {
				if (json.audio_find) {
					voiceDecoderLastAudioFind = json.audio_find;
				}
				applyVoiceDecoderFullState(json);
				if (forceShowClips || json.last_dashboard === 'voice_decoder') {
					$('body').addClass('voice_decoder_stage_active');
				} else {
					$('body').removeClass('voice_decoder_stage_active');
				}
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}

function syncVoiceDecoderStageVisibility() {
	$.when(getTeamInfo()).done(function(teamResponse){
		var teamInfo = teamResponse.success;
		if (teamInfo && teamInfo.last_dashboard === 'voice_decoder') {
			$('body').addClass('voice_decoder_stage_active');
			refreshVoiceDecoderState(true);
		} else {
			$('body').removeClass('voice_decoder_stage_active');
		}
	});
}

function stopAllVoiceClipsExcept(exceptId) {
	$('.voice_clip_widget').each(function() {
		var id = parseInt($(this).attr('data-audio-id'), 10);
		if (exceptId && id === exceptId) {
			return;
		}
		var audioEl = $(this).find('audio').get(0);
		if (audioEl) {
			audioEl.pause();
			audioEl.currentTime = 0;
		}
		$(this).find('.voice_clip_btn').removeClass('is-playing');
	});
}

function voiceDecoderDecryptSubmit(lang_abbr) {
	setTimeout(function(){
		dataTransferAudio = new Audio;
		dataTransferAudio.src = '/music/data_transfer.mp3';

		var promise = dataTransferAudio.play();
		if (promise !== undefined) {
			promise.catch(function() {});
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
				formData.append('op', 'voiceDecoderUpdateHint');
				formData.append('lang_abbr', lang_abbr);

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

						if (json && json.error_verify) {
							window.location.href = json.error_verify;
							return;
						}
						if (json && json.success) {
							$('body').removeClass('voice_decoder_stage_active');
							uploadTypeTabsDashboardStep('voice_correct', false);

							var message = {
								'op': 'voiceDecoderDecryptSuccess',
								'parameters': {
									'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
									'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
								}
							};
							sendMessageSocket(JSON.stringify(message));
						} else {
							refreshVoiceDecoderState(true);
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						$('#popup_data_transfer').fadeOut(200);
						if (dataTransferAudio && isPlaying(dataTransferAudio)) {
							dataTransferAudio.pause();
						}
						console.error(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			}
		}, (dataTransferMusicDuration / dataTransferIteration));

		$('#popup_data_transfer').css('display', 'block');
	}, 210);
}

$(function() {
	// при загрузке страницы — если этап voice_decoder, показываем клипы и подтягиваем audio_find
	if ($('#section_game').length) {
		syncVoiceDecoderStageVisibility();
	}

	// play / pause кружок
	$('body').on('click', '.voice_clip_btn', function(e){
		e.preventDefault();
		e.stopPropagation();

		var $widget = $(this).closest('.voice_clip_widget');
		var audioId = parseInt($widget.attr('data-audio-id'), 10);
		var audioEl = $widget.find('audio').get(0);
		if (!audioEl) {
			return;
		}

		if ($(this).hasClass('is-playing')) {
			audioEl.pause();
			$(this).removeClass('is-playing');
			return;
		}

		stopAllVoiceClipsExcept(audioId);
		var promise = audioEl.play();
		$(this).addClass('is-playing');
		voiceDecoderCurrentAudio = audioEl;
		voiceDecoderCurrentId = audioId;

		if (promise !== undefined) {
			promise.catch(function() {
				$widget.find('.voice_clip_btn').removeClass('is-playing');
			});
		}
	});

	$('body').on('ended', '.voice_clip_widget audio', function(){
		$(this).closest('.voice_clip_widget').find('.voice_clip_btn').removeClass('is-playing');
	});

	// Додати до дешефровки
	$('body').on('click', '.voice_clip_add_btn', function(e){
		e.preventDefault();
		e.stopPropagation();

		var $btn = $(this);
		var $widget = $btn.closest('.voice_clip_widget');
		var audioId = parseInt($widget.attr('data-audio-id'), 10);
		if (!audioId || $widget.hasClass('voice_clip_widget_found') || $widget.hasClass('voice_clip_saving')) {
			return;
		}

		var audioEl = $widget.find('audio').get(0);
		if (audioEl) {
			audioEl.pause();
			audioEl.currentTime = 0;
		}
		$widget.find('.voice_clip_btn').removeClass('is-playing');
		$widget.addClass('voice_clip_saving');
		$btn.prop('disabled', true);

		var formData = new FormData();
		formData.append('op', 'addVoiceDecoderMessage');
		formData.append('audio_id', audioId);

		$.ajax({
			url: '/ajax/ajax_dashboard.php',
			type: "POST",
			dataType: "json",
			cache: false,
			contentType: false,
			processData: false,
			data: formData,
			success: function(json) {
				if (json && json.success === 'ok') {
					applyVoiceDecoderFullState(json);
					if (json.audio_find) {
						voiceDecoderLastAudioFind = json.audio_find;
					}

					if (typeof filesCache !== 'undefined') {
						filesCache.content = null;
						filesCache.titles = null;
					}

					var message = {
						'op': 'voiceDecoderUpdateCount',
						'parameters': {
							'audio_id': audioId,
							'voice_count': json.voice_count,
							'audio_find': json.audio_find || [],
							'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
							'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
						}
					};
					sendMessageSocket(JSON.stringify(message));
				} else if (json && json.error) {
					console.error('addVoiceDecoderMessage:', json.error);
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				console.error(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			},
			complete: function() {
				$widget.removeClass('voice_clip_saving');
				$btn.prop('disabled', false);
			}
		});
	});

	$('body').on('click', '.dashboard_voice_decoder_decrypt_btn', function(e){
		e.preventDefault();
		e.stopPropagation();

		if ($(this).hasClass('dashboard_voice_decoder_btn_hidden')) {
			return;
		}

		voiceDecoderDecryptSubmit($('html').attr('lang'));
	});
});
