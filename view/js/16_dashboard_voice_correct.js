/* === DASHBOARD - VOICE CORRECT === */

var voiceCorrectFullAudio = null;
var voiceCorrectFullPlaylist = [];
var voiceCorrectFullIndex = 0;
var voiceCorrectSaving = false;
var voiceCorrectFinishing = false;

function getVoiceCorrectOrderFromDom() {
	var order = [];
	$('.dashboard_voice_correct_item').each(function() {
		order.push(parseInt($(this).attr('data-audio-id'), 10));
	});
	return order;
}

function updateVoiceCorrectArrowStates() {
	var $items = $('.dashboard_voice_correct_item');
	var total = $items.length;
	$items.each(function(index) {
		var $item = $(this);
		$item.attr('data-index', index);
		var $left = $item.find('.dashboard_voice_correct_arrow_left');
		var $right = $item.find('.dashboard_voice_correct_arrow_right');

		if (index === 0) {
			$left.addClass('is-disabled').prop('disabled', true);
		} else {
			$left.removeClass('is-disabled').prop('disabled', false);
		}

		if (index === total - 1) {
			$right.addClass('is-disabled').prop('disabled', true);
		} else {
			$right.removeClass('is-disabled').prop('disabled', false);
		}
	});

	$('.dashboard_voice_correct_list').attr('data-order', getVoiceCorrectOrderFromDom().join(','));
}

function stopAllVoiceCorrectPlayback() {
	$('.dashboard_voice_correct_item').each(function() {
		var audioEl = $(this).find('audio').get(0);
		if (audioEl) {
			audioEl.pause();
			audioEl.currentTime = 0;
		}
		$(this).find('.dashboard_voice_correct_play').removeClass('is-playing');
	});
}

function renderVoiceCorrectOrder(order) {
	var $list = $('.dashboard_voice_correct_list');
	if (!$list.length || !order || !order.length) {
		return;
	}

	var map = {};
	$list.find('.dashboard_voice_correct_item').each(function() {
		map[parseInt($(this).attr('data-audio-id'), 10)] = $(this);
	});

	for (var i = 0; i < order.length; i++) {
		var id = parseInt(order[i], 10);
		if (map[id] && map[id].length) {
			$list.append(map[id]);
		}
	}

	updateVoiceCorrectArrowStates();
}

function saveVoiceCorrectOrder(order, sendSocket) {
	if (voiceCorrectSaving) {
		return;
	}
	voiceCorrectSaving = true;

	var formData = new FormData();
	formData.append('op', 'saveVoiceCorrectOrder');
	formData.append('order', JSON.stringify(order));

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
				renderVoiceCorrectOrder(json.order || order);
				if (sendSocket) {
					var message = {
						'op': 'voiceCorrectUpdateOrder',
						'parameters': {
							'order': json.order || order,
							'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
							'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
						}
					};
					sendMessageSocket(JSON.stringify(message));
				}
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			console.error(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		},
		complete: function() {
			voiceCorrectSaving = false;
		}
	});
}

function moveVoiceCorrectItem($item, direction) {
	if (!$item.length || voiceCorrectSaving) {
		return;
	}

	var $target = direction === 'left' ? $item.prev('.dashboard_voice_correct_item') : $item.next('.dashboard_voice_correct_item');
	if (!$target.length) {
		return;
	}

	if (direction === 'left') {
		$item.insertBefore($target);
	} else {
		$item.insertAfter($target);
	}

	updateVoiceCorrectArrowStates();
	saveVoiceCorrectOrder(getVoiceCorrectOrderFromDom(), true);
}

function showVoiceCorrectErrorPopup() {
	$('#popup_search_error .popup_search_error_input').html('ERROR');
	$('#popup_search_error .popup_search_error_text').html('помилка. Аудіофайл відтворено невірно');
	$('#popup_search_error').css('display', 'block');

	var errorAudio = new Audio;
	errorAudio.src = '/music/error.mp3';
	var promise = errorAudio.play();
	if (promise !== undefined) {
		promise.catch(function() {});
	}
}

function stopVoiceCorrectFullAudio() {
	if (voiceCorrectFullAudio) {
		voiceCorrectFullAudio.pause();
		voiceCorrectFullAudio = null;
	}
	voiceCorrectFullPlaylist = [];
	voiceCorrectFullIndex = 0;
}

function playVoiceCorrectFullAudioNext() {
	if (!voiceCorrectFullPlaylist.length || voiceCorrectFullIndex >= voiceCorrectFullPlaylist.length) {
		finishVoiceCorrectSuccess();
		return;
	}

	voiceCorrectFullAudio = new Audio;
	voiceCorrectFullAudio.src = voiceCorrectFullPlaylist[voiceCorrectFullIndex];
	voiceCorrectFullAudio.onended = function() {
		voiceCorrectFullIndex += 1;
		playVoiceCorrectFullAudioNext();
	};
	var promise = voiceCorrectFullAudio.play();
	if (promise !== undefined) {
		promise.catch(function() {
			voiceCorrectFullIndex += 1;
			playVoiceCorrectFullAudioNext();
		});
	}
}

function finishVoiceCorrectSuccess() {
	if (voiceCorrectFinishing) {
		return;
	}
	voiceCorrectFinishing = true;
	stopVoiceCorrectFullAudio();
	$('#popup_voice_correct_alison').fadeOut(200);

	uploadTypeTabsDashboardStep('african_partner', false);
	updateDontOpenFilesQt();
	updateDontOpenToolsQt();

	var message = {
		'op': 'voiceCorrectSuccess',
		'parameters': {
			'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
			'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
		}
	};
	sendMessageSocket(JSON.stringify(message));
	setTimeout(function() {
		voiceCorrectFinishing = false;
	}, 1000);
}

function showVoiceCorrectAlisonPopup(fullAudio) {
	voiceCorrectFullPlaylist = fullAudio || [];
	voiceCorrectFullIndex = 0;
	$('#popup_voice_correct_alison').css('display', 'block');
	playVoiceCorrectFullAudioNext();
}

function voiceCorrectBuildSubmit(lang_abbr) {
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
				formData.append('op', 'validateVoiceCorrectOrder');
				formData.append('lang_abbr', lang_abbr);
				formData.append('order', JSON.stringify(getVoiceCorrectOrderFromDom()));

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
						if (dataTransferAudio && typeof isPlaying === 'function' && isPlaying(dataTransferAudio)) {
							dataTransferAudio.pause();
						}

						if (json && json.error_verify) {
							window.location.href = json.error_verify;
							return;
						}

						if (json && json.success === 'ok') {
							showVoiceCorrectAlisonPopup(json.full_audio || []);
						} else {
							showVoiceCorrectErrorPopup();
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						$('#popup_data_transfer').fadeOut(200);
						if (dataTransferAudio && typeof isPlaying === 'function' && isPlaying(dataTransferAudio)) {
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
	$('body').on('click', '.dashboard_voice_correct_play', function(e){
		e.preventDefault();
		e.stopPropagation();

		var $item = $(this).closest('.dashboard_voice_correct_item');
		var audioEl = $item.find('audio').get(0);
		if (!audioEl) {
			return;
		}

		if ($(this).hasClass('is-playing')) {
			audioEl.pause();
			$(this).removeClass('is-playing');
			return;
		}

		stopAllVoiceCorrectPlayback();
		var promise = audioEl.play();
		$(this).addClass('is-playing');
		if (promise !== undefined) {
			promise.catch(function() {
				$item.find('.dashboard_voice_correct_play').removeClass('is-playing');
			});
		}
	});

	$('body').on('ended', '.dashboard_voice_correct_item audio', function(){
		$(this).closest('.dashboard_voice_correct_item').find('.dashboard_voice_correct_play').removeClass('is-playing');
	});

	$('body').on('click', '.dashboard_voice_correct_arrow_left', function(e){
		e.preventDefault();
		e.stopPropagation();
		if ($(this).prop('disabled') || $(this).hasClass('is-disabled')) {
			return;
		}
		moveVoiceCorrectItem($(this).closest('.dashboard_voice_correct_item'), 'left');
	});

	$('body').on('click', '.dashboard_voice_correct_arrow_right', function(e){
		e.preventDefault();
		e.stopPropagation();
		if ($(this).prop('disabled') || $(this).hasClass('is-disabled')) {
			return;
		}
		moveVoiceCorrectItem($(this).closest('.dashboard_voice_correct_item'), 'right');
	});

	$('body').on('click', '.dashboard_voice_correct_build_btn', function(e){
		e.preventDefault();
		e.stopPropagation();
		stopAllVoiceCorrectPlayback();
		voiceCorrectBuildSubmit($('html').attr('lang'));
	});

	$('body').on('click', '#popup_voice_correct_alison .popup_voice_correct_alison_close', function(e){
		e.preventDefault();
		finishVoiceCorrectSuccess();
	});
});
