/* === ЧАТ В ИГРЕ === */

/* ОБЩИЕ ФУНКЦИИ */
	// запоминаем открыт ли чат
	function setTeamOpenChatWindow(typeOpen, isSocketSend) {
		// console.log('setTeamOpenChatWindow', typeOpen, isSocketSend);
		var formData = new FormData();
		formData.append('op', 'saveTeamTextField');
		formData.append('field', 'open_chat');
		formData.append('val', typeOpen);

		$.ajax({
			url: '/ajax/ajax.php',
	        type: "POST",
	        dataType: "json",
	        cache: false,
	        contentType: false,
	        processData: false,
	        data: formData,
			success: function(json) {
				// socket
				if (isSocketSend) {
					// console.log('send socket');
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
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}

	// скрыть кнопку перехода на чат
	function hiddenChatBtn() {
		$('.section_main_bg_footer .btn_wrapper_view_chat').css('display', 'none');
	}

	// показать кнопку перехода на чат
	function viewChatBtn() {
		$('.section_main_bg_footer .btn_wrapper_view_chat').css('display', 'block');
	}

	// развернуть чат
	function openChatWindow(isFirstMessagesFromBot) {
		// console.log('openChatWindow');
		/*// socket
		mySocketLastAction = 'openChatWindow';
		var message = {
			'op': 'openChatWindow',
			'parameters': {},
			'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
			'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
        };
        sendMessageSocket(JSON.stringify(message));*/

		/*// function
		updateChatMessages(false);
		$('.chat').animate({height: '674px'},200);
		$('.btn_wrapper_view_chat').addClass('btn_wrapper_view_chat_active');

		// запоминаем открыт ли чат
		setTeamOpenChatWindow('yes', true);

		if (isFirstMessagesFromBot) {
			var sendOtherMessages = new Array();
			sendOtherMessages.push(2);

			printChatMesageFromBot(1, sendOtherMessages);
		}*/
		$.when(updateChatMessagesResponse(false)).done(function(updateChatMessagesResponse1){
			// console.log(updateChatMessagesResponse);
			if (updateChatMessagesResponse1.messages) {
				$('.chat').animate({height: '674px'},200);
				$('.btn_wrapper_view_chat').addClass('btn_wrapper_view_chat_active');

				// Update visual
				$('.chat_message_bot_printed').remove();

				if ($('.chat_messages_scroll .mCSB_container').length > 0) {
					$('.chat_messages_scroll .mCSB_container').html(updateChatMessagesResponse1.messages);
				} else {
					if ($('.chat_messages_scroll').length) {
						$('.chat_messages_scroll').mCustomScrollbar({
							scrollInertia: 700,
							scrollbarPosition: "inside"
						}).mCustomScrollbar("scrollTo","bottom",{scrollInertia:0});

						$('.chat_messages_scroll .mCSB_container').html(updateChatMessagesResponse1.messages);
					}
				}

				$('.chat_messages_scroll').mCustomScrollbar('update');

				setTimeout(function(){
					$('.chat_messages_scroll').mCustomScrollbar("scrollTo","bottom",{scrollInertia:0});
				}, 100);

				/*// класс при отсутствии сообщений
				if (updateChatMessagesResponse1.chat_message_empty) {
					$('.chat_messages_scroll').addClass('chat_message_empty');
				} else {
					$('.chat_messages_scroll').removeClass('chat_message_empty');
				}

				// возможность что-то написать в чат
				if ($('.chat_messages_scroll .mCSB_container .chat_message_section:last').hasClass('chat_message_section_from_bot')) {
					if ($('.chat_messages_scroll .mCSB_container .chat_message_section:last .chat_bot_message_btn').length) {
						viewChatFormMessageHidden();
						$('.chat_form input[type="text"]').prop('disabled', true);
					} else {
						if (updateChatMessagesResponse1.input_disabled) {
							$('.chat_form input[type="text"]').prop('disabled', true);
						} else {
							viewChatFormMessageView();
							$('.chat_form input[type="text"]').prop('disabled', false);
						}
					}
				} else {
					if (updateChatMessagesResponse1.input_disabled) {
						$('.chat_form input[type="text"]').prop('disabled', true);
					} else {
						viewChatFormMessageView();
						$('.chat_form input[type="text"]').prop('disabled', false);
					}
				}*/

				// запоминаем открыт ли чат
				setTeamOpenChatWindow('yes', true);

				if (isFirstMessagesFromBot) {
					var message_id = 1;
					var message_id_second = 2;

					$.when(getDefaultChatMessage(message_id)).done(function(messageResponse){
						if (messageResponse.success) {
							// бот типа печатает
							if (message_id == 1) {
								// убираем вывод по центру по вертикали
								$('.chat_messages_scroll').removeClass('chat_message_empty');

								// выводим надпись, что сегодняшний день
								$('.chat_messages_scroll .mCSB_container').html('<div class="chat_message_day_title">' + ($('html').attr('lang') == 'en' ? 'Today' : 'I dag') + '</div>');
							}

							printChatMessageFromBotDotsMain();

							setTimeout(function(){
								// добавить сообщение в историю переписки
								$.when(addChatMessageToHistory(message_id, 'bot')).done(function(historyResponse){
									// обновляем всю область сообщений
									$.when(updateChatMessagesResponse(true)).done(function(updateChatMessagesResponse2){
										if (updateChatMessagesResponse2.messages) {
											// socket
											var message = {
												'op': 'updateChatMessages',
												'parameters': {
													'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
													'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
												}
									        };
									        sendMessageSocket(JSON.stringify(message));

									        // Update visual
									        $('.chat_message_bot_printed').remove();

									        // убираем скролл для области сообщений
									        $('.chat_messages_scroll .mCSB_container').html(updateChatMessagesResponse2.messages);

									        // добавляем скролл для области сообщений
									        $('.chat_messages_scroll').mCustomScrollbar('update');

									        setTimeout(function(){
												$('.chat_messages_scroll').mCustomScrollbar("scrollTo","bottom",{scrollInertia:0});
											}, 100);

											/*// класс при отсутствии сообщений
											if (updateChatMessagesResponse2.chat_message_empty) {
												$('.chat_messages_scroll').addClass('chat_message_empty');
											} else {
												$('.chat_messages_scroll').removeClass('chat_message_empty');
											}

											// возможность что-то написать в чат
											if ($('.chat_messages_scroll .mCSB_container .chat_message_section:last').hasClass('chat_message_section_from_bot')) {
												if ($('.chat_messages_scroll .mCSB_container .chat_message_section:last .chat_bot_message_btn').length) {
													viewChatFormMessageHidden();
													$('.chat_form input[type="text"]').prop('disabled', true);
												} else {
													if (updateChatMessagesResponse2.input_disabled) {
														$('.chat_form input[type="text"]').prop('disabled', true);
													} else {
														viewChatFormMessageView();
														$('.chat_form input[type="text"]').prop('disabled', false);
													}
												}
											} else {
												if (updateChatMessagesResponse2.input_disabled) {
													$('.chat_form input[type="text"]').prop('disabled', true);
												} else {
													viewChatFormMessageView();
													$('.chat_form input[type="text"]').prop('disabled', false);
												}
											}*/

											setTimeout(function(){
												$.when(addChatMessageToHistory(message_id_second, 'bot')).done(function(historyResponse2){
													// updateChatMessages(true);

													// обновляем всю область сообщений
													$.when(updateChatMessagesResponse(true)).done(function(updateChatMessagesResponse3){
														if (updateChatMessagesResponse3.messages) {
															// socket
															var message = {
																'op': 'updateChatMessages',
																'parameters': {
																	'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
																	'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
																}
													        };
													        sendMessageSocket(JSON.stringify(message));

													        // Update visual
													        $('.chat_message_bot_printed').remove();

													        // убираем скролл для области сообщений
													        $('.chat_messages_scroll .mCSB_container').html(updateChatMessagesResponse3.messages);

													        // добавляем скролл для области сообщений
													        $('.chat_messages_scroll').mCustomScrollbar('update');

													        setTimeout(function(){
																$('.chat_messages_scroll').mCustomScrollbar("scrollTo","bottom",{scrollInertia:0});
															}, 100);

															// класс при отсутствии сообщений
															if (updateChatMessagesResponse3.chat_message_empty) {
																$('.chat_messages_scroll').addClass('chat_message_empty');
															} else {
																$('.chat_messages_scroll').removeClass('chat_message_empty');
															}

															// возможность что-то написать в чат
															viewChatFormMessageView();
															$('.chat_form input[type="text"]').prop('disabled', false);

															setTeamTabsTextInfo('chat_send_message_access', 1);
														}
													});
												});
											}, 900);
										}
									});
								});
							}, 2400);
						}
					});
				}
			}
		});
	}

	// свернуть чат
	function hiddenChatWindow() {
		// console.log('hiddenChatWindow');
		/*// socket
		mySocketLastAction = 'hiddenChatWindow';
		var message = {
			'op': 'hiddenChatWindow',
			'parameters': {},
			'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
			'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
        };
        sendMessageSocket(JSON.stringify(message));*/

		// function
		$('.chat').animate({height: '0px'},200);
		$('.btn_wrapper_view_chat').removeClass('btn_wrapper_view_chat_active');

		// запоминаем открыт ли чат
		setTeamOpenChatWindow('no', true);
	}

	// загрузить актуальное состояние чата
	function updateChatMessages(sendSocket) {
		// console.log('updateChatMessages');
		var formData = new FormData();
    	formData.append('op', 'updateChatMessages');
    	formData.append('lang_abbr', $('html').attr('lang'));

    	$.ajax({
			url: '/ajax/ajax_chatbot.php',
	        type: "POST",
	        dataType: "json",
	        cache: false,
	        contentType: false,
	        processData: false,
	        data: formData,
			success: function(json) {
				// console.log('updateChatMessages json');
				// console.log(json);
				// socket
				if (sendSocket) {
					var message = {
						'op': 'updateChatMessages',
						'parameters': {
							'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
							'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
						}
			        };
			        sendMessageSocket(JSON.stringify(message));
			    }

				$('.chat_message_bot_printed').remove();

				// убираем скролл для области сообщений
				// $('.chat_messages_scroll').mCustomScrollbar('destroy');

				// $('.chat_messages_scroll').html(json.messages);
				$('.chat_messages_scroll .mCSB_container').html(json.messages);

				// добавляем скролл для области сообщений
				// setTimeout(function(){
					// console.log('updateChatMessages json setTimeout');
					// $('.chat_messages_scroll').mCustomScrollbar({
					// 	scrollInertia: 700,
					// 	scrollbarPosition: "inside"
					// });

					// $('.chat_messages_scroll').mCustomScrollbar({
					// 	scrollInertia: 700,
					// 	scrollbarPosition: "inside"
					// }).mCustomScrollbar("scrollTo","bottom",{scrollInertia:0});

					$('.chat_messages_scroll').mCustomScrollbar('update');

					setTimeout(function(){
						$('.chat_messages_scroll').mCustomScrollbar("scrollTo","bottom",{scrollInertia:0});
					}, 100);
				// }, 100);

				// класс при отсутствии сообщений
				if (json.chat_message_empty) {
					$('.chat_messages_scroll').addClass('chat_message_empty');
				} else {
					$('.chat_messages_scroll').removeClass('chat_message_empty');
				}

				// возможность что-то написать в чат
				/*if (json.input_disabled) {
					// viewChatFormMessageHidden();
					$('.chat_form input[type="text"]').prop('disabled', true);
				} else {
					viewChatFormMessageView();
					$('.chat_form input[type="text"]').prop('disabled', false);
				}*/
				if ($('.chat_messages_scroll .mCSB_container .chat_message_section:last').hasClass('chat_message_section_from_bot')) {
					// console.log('1');
					if ($('.chat_messages_scroll .mCSB_container .chat_message_section:last .chat_bot_message_btn').length) {
						viewChatFormMessageHidden();
						$('.chat_form input[type="text"]').prop('disabled', true);
						// console.log('2');
					} else {
						if (json.input_disabled) {
							// console.log('3');
							// viewChatFormMessageHidden();
							$('.chat_form input[type="text"]').prop('disabled', true);
						} else {
							viewChatFormMessageView();
							$('.chat_form input[type="text"]').prop('disabled', false);
							// console.log('4');
						}
					}
				} else {
					if (json.input_disabled) {
						// viewChatFormMessageHidden();
						$('.chat_form input[type="text"]').prop('disabled', true);
						// console.log('5');
					} else {
						viewChatFormMessageView();
						$('.chat_form input[type="text"]').prop('disabled', false);
						// console.log('6');
					}
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {	
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
	function updateChatMessagesResponse(sendSocket) {
		// console.log('updateChatMessagesResponse');
		var formData = new FormData();
    	formData.append('op', 'updateChatMessages');
    	formData.append('lang_abbr', $('html').attr('lang'));

    	return $.ajax({
			url: '/ajax/ajax_chatbot.php',
	        type: "POST",
	        dataType: "json",
	        cache: false,
	        contentType: false,
	        processData: false,
	        data: formData,
			success: function(json) {
			},
			error: function(xhr, ajaxOptions, thrownError) {	
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}

	// поле для ввода сообщения видно
	function viewChatFormMessageView() {
		$('.chat_form').css('display', 'block');
	}

	// поле для ввода сообщения скрыто
	function viewChatFormMessageHidden() {
		$('.chat_form').css('display', 'none');

		// socket
		var message = {
			'op': 'viewChatFormMessageHidden',
			'parameters': {
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
        };
        sendMessageSocket(JSON.stringify(message));
	}

	// получить текст сообщение от бота, которое надо напечатать
	function getDefaultChatMessage(message_id) {
		var formData = new FormData();
		formData.append('op', 'getDefaultChatMessage');
		formData.append('message_id', message_id);

		return $.ajax({
			url: '/ajax/ajax_chatbot.php',
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
	}

	// пишем сообщение от бота
	function printChatMesageFromBot(message_id, sendOtherMessages) {
		// console.log('printChatMesageFromBot');
		$.when(getDefaultChatMessage(message_id)).done(function(messageResponse){
			if (messageResponse.success) {
				// console.log('getDefaultChatMessage success');
				// бот типа печатает
				if (message_id == 1) {
					// убираем вывод по центру по вертикали
					$('.chat_messages_scroll').removeClass('chat_message_empty');

					// выводим надпись, что сегодняшний день
					// $('.chat_messages_scroll').html('<div class="chat_message_day_title">' + ($('html').attr('lang') == 'en' ? 'Today' : 'I dag') + '</div>');
					$('.chat_messages_scroll .mCSB_container').html('<div class="chat_message_day_title">' + ($('html').attr('lang') == 'en' ? 'Today' : 'I dag') + '</div>');
				}

				printChatMessageFromBotDotsMain();

				setTimeout(function(){
					// $('.chat_message_bot_printed').remove();

					// добавить сообщение в историю переписки
					$.when(addChatMessageToHistory(message_id, 'bot')).done(function(historyResponse){
						// обновляем всю область сообщений
						// $('.chat_message_bot_printed').remove();
						// console.log('111111');
						updateChatMessages(true);

						// добавляем сразу и остальные сообщение
						for (let i = 0; i < sendOtherMessages.length; i++) {
							// console.log(sendOtherMessages, i);
							setTimeout(function(){
								$.when(addChatMessageToHistory(sendOtherMessages[i], 'bot')).done(function(historyResponse){
									// console.log('222222');
									updateChatMessages(true);
								});
							}, (i + 1) * 800);
						}

						if (message_id == 1) {
							setTeamTabsTextInfo('chat_send_message_access', 1);
						}
					});
				}, 2400);
			}
		});
	}

	// бот типа печатает2
	function printChatMessageFromBotDotsMain() {
		$('.chat_message_bot_printed').remove();
		printChatMessageFromBotDots(1);

		$('.chat_messages_scroll').mCustomScrollbar("scrollTo","bottom",{scrollInertia:0});

		setTimeout(function(){
			$('.chat_message_bot_printed').remove();
			printChatMessageFromBotDots(2);
		}, 400);

		setTimeout(function(){
			$('.chat_message_bot_printed').remove();
			printChatMessageFromBotDots(3);
		}, 800);

		setTimeout(function(){
			$('.chat_message_bot_printed').remove();
			printChatMessageFromBotDots(1);
		}, 1200);

		setTimeout(function(){
			$('.chat_message_bot_printed').remove();
			printChatMessageFromBotDots(2);
		}, 1600);

		setTimeout(function(){
			$('.chat_message_bot_printed').remove();
			printChatMessageFromBotDots(3);
		}, 2000);
	}

	// бот типа печатает
	function printChatMessageFromBotDots(count_dots) {
		var side = 'bot';
		var botIcon = '<img src="/images/bot_face_small.png" alt="">';

		var botIcon = '';
		/*if (count_dots == 1) {
			botIcon = '<div class="chat_message_icon"><img src="/images/bot_face_small.png" alt=""></div>';
		}*/
		if ($('.chat_message_section').length) {
			if ($('.chat_message_section').last().hasClass('chat_message_section_from_team')) {
				botIcon = '<div class="chat_message_icon"><img src="/images/bot_face_small.png" alt=""></div>';
			}
		} else {
			botIcon = '<div class="chat_message_icon"><img src="/images/bot_face_small.png" alt=""></div>';
		}

		var dots = '';
		for (var i = 0; i < count_dots; i++) {
			dots += '.';
		}

		var message = '<div class="chat_message_section chat_message_section_from_' + side + ' chat_message_bot_printed">' + botIcon + '<div class="chat_message_item" data-message_id="0">' + dots + '</div></div>';

		// $('.chat_messages_scroll').append(message);
		$('.chat_messages_scroll .mCSB_container').append(message);
	}

	// добавить сообщение в историю переписки. Со стороны бота
	function addChatMessageToHistory(message_id, side) {
		var formData = new FormData();
		formData.append('op', 'addChatMessageToHistory');
		formData.append('message_id', message_id);
		formData.append('side', side);

		return $.ajax({
			url: '/ajax/ajax_chatbot.php',
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
	}

	// добавить сообщение в историю переписки. Со стороны пользователя
	function addChatMessageToHistoryByTeam(message_text, bot_last_message_default_id) {
		var formData = new FormData();
		formData.append('op', 'addChatMessageToHistoryByTeam');
		formData.append('message_text', message_text);
		formData.append('bot_last_message_default_id', bot_last_message_default_id);

		return $.ajax({
			url: '/ajax/ajax_chatbot.php',
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
	}

	// открываем чатбот и пишем первые 2 сообщения
	function chatPrintFirstMessagesFromBot() {
		// console.log('chatPrintFirstMessagesFromBot');
		openChatWindow(true);

		/*var sendOtherMessages = new Array();
		sendOtherMessages.push(2);

		printChatMesageFromBot(1, sendOtherMessages);*/
	}

	// получить идентификатор дефолтного последнего сообщение от бота
	function getLastChatMessageDefaultIdFromHistoryByBot() {
		var formData = new FormData();
		formData.append('op', 'getLastChatMessageDefaultIdFromHistoryByBot');

		return $.ajax({
			url: '/ajax/ajax_chatbot.php',
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
	}

	// удалить сообщение по идентификаторам
	function removeMessageById(message_id1, message_id2, message_id3, message_btn_text) {
		var formData = new FormData();
		formData.append('op', 'removeMessageById');
		formData.append('message_id1', message_id1);
		formData.append('message_id2', message_id2);
		formData.append('message_id3', message_id3);
		formData.append('message_btn_text', message_btn_text);

		return $.ajax({
			url: '/ajax/ajax_chatbot.php',
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
	}

$(function() {
	// скролл для области сообщений
	if ($('.chat_messages_scroll').length) {
		$('.chat_messages_scroll').mCustomScrollbar({
			scrollInertia: 700,
			scrollbarPosition: "inside"
		}).mCustomScrollbar("scrollTo","bottom",{scrollInertia:0});
	}

	// открыть чат при нажатии на кнопку в футере
	$('.section_main_bg_footer .btn_wrapper_view_chat').click(function(){
		if ($(this).hasClass('btn_wrapper_view_chat_active')) {
			hiddenChatWindow();
		} else {
			openChatWindow(false);
		}

		/*// socket
		var message = {
			'op': 'loadSocketMain',
			'parameters': {
				user_id: $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				team_id: $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
        };
        sendMessageSocket(JSON.stringify(message));*/
	});

	// закрыть окно чата при нажатии на кнопку закрытия чата
	$('.chat_close').click(function(){
		hiddenChatWindow();

		/*// socket
		var message = {
			'op': 'loadSocketMain',
			'parameters': {
				user_id: $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				team_id: $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
        };
        sendMessageSocket(JSON.stringify(message));*/
	});

	// юзер жмет на Enter в поле ввода сообщения в чате
	$('.chat_form input[type="text"]').keyup(function(e){
		if (e.which == 13) {
			$('.chat_send_btn').trigger('click');
		}
	});

	// юзер нажимает на кнопку отправки сообщения
	$('.chat_send_btn').click(function(){
		var message = $.trim($('.chat_form input[type="text"]').val());
		if (!$('.chat_form input[type="text"]').prop('disabled') && message != '') {
			// определяем идентификатор последнего сообщения от бота. И от этого делаем дальнейшие действия
			$.when(getLastChatMessageDefaultIdFromHistoryByBot()).done(function(historyResponse){
				if (historyResponse && historyResponse.success != 0) {
					if (historyResponse.success == 2) { // БОТ ОТПРАВИЛ ПРИВЕТСТВЕННЫЙ ТЕКСТ И ОЖИДАЕТ ИМЯ ИСКОМОГО ЧЕЛОВЕКА
						$.when(addChatMessageToHistoryByTeam(message, historyResponse.success)).done(function(messageResponse){
							// очищаем поле для ввода данных
							$('.chat_form input[type="text"]').val('');

							// обновляем всю область сообщений
							updateChatMessages(true);

							// реакция бота
							setTimeout(function(){
								// бот типа печатает
								printChatMessageFromBotDotsMain();

								setTimeout(function(){
									// $('.chat_message_bot_printed').remove();

									// добавить сообщение в историю переписки
									if (messageResponse.success_text == 'false') { // ввели неверно
										$.when(addChatMessageToHistory(3, 'bot')).done(function(historyResponseBot){
											// $('.chat_message_bot_printed').remove();
											// обновляем всю область сообщений
											updateChatMessages(true);

											// второе сообщение. Кнопка
											setTimeout(function(){
												$.when(addChatMessageToHistory(4, 'bot')).done(function(historyResponseBot2){
													updateChatMessages(true);
												});
											}, 800);
										});
									} else { // ввели верно
										$.when(addChatMessageToHistory(5, 'bot')).done(function(historyResponseBot){
											updateChatMessages(true);

											setTimeout(function(){
												$.when(addChatMessageToHistory(6, 'bot')).done(function(historyResponseBot2){
													updateChatMessages(true);

													setTimeout(function(){
														$.when(addChatMessageToHistory(7, 'bot')).done(function(historyResponseBot3){
															updateChatMessages(true);

															setTimeout(function(){
																$.when(addChatMessageToHistory(8, 'bot')).done(function(historyResponseBot4){
																	updateChatMessages(true);

																	setTimeout(function(){
																		$.when(addChatMessageToHistory(9, 'bot')).done(function(historyResponseBot5){
																			updateChatMessages(true);

																			setTimeout(function(){
																				$.when(addChatMessageToHistory(10, 'bot')).done(function(historyResponseBot6){
																					updateChatMessages(true);

																					setTimeout(function(){
																						$.when(addChatMessageToHistory(11, 'bot')).done(function(historyResponseBot7){
																							updateChatMessages(true);

																							setTimeout(function(){
																								$.when(addChatMessageToHistory(12, 'bot')).done(function(historyResponseBot8){
																									updateChatMessages(true);

																									setTimeout(function(){
																										$.when(addChatMessageToHistory(13, 'bot')).done(function(historyResponseBot9){
																											updateChatMessages(true);
																										});
																									}, 800);
																								});
																							}, 800);
																						});
																					}, 800);
																				});
																			}, 800);
																		});
																	}, 800);
																});
															}, 800);
														});
													}, 800);
												});
											}, 800);
										});
									}
								}, 2400);
							}, 500);
						});
					} else if (historyResponse.success == 20 || historyResponse.success == 27) { // БОТ СПРОСИЛ, ГОТОВ ЛИ ЮЗЕР ПОМОЧЬ ВО ВЗЛОМЕ КАМЕРЫ
						$.when(addChatMessageToHistoryByTeam(message, historyResponse.success)).done(function(messageResponse){
							// очищаем поле для ввода данных
							$('.chat_form input[type="text"]').val('');

							// обновляем всю область сообщений
							updateChatMessages(true);

							// реакция бота
							setTimeout(function(){
								// бот типа печатает
								printChatMessageFromBotDotsMain();

								setTimeout(function(){
									// добавить сообщение в историю переписки
									if (messageResponse.success_text == 'false_no') { // ввели нет
										$.when(addChatMessageToHistory(21, 'bot')).done(function(historyResponseBot){
											// обновляем всю область сообщений
											updateChatMessages(true);

											// второе сообщение. Кнопка
											setTimeout(function(){
												$.when(addChatMessageToHistory(22, 'bot')).done(function(historyResponseBot2){
													updateChatMessages(true);
												});
											}, 800);
										});
									} else if (messageResponse.success_text == 'false_yes') { // ввели да
										$.when(addChatMessageToHistory(23, 'bot')).done(function(historyResponseBot){
											// обновляем всю область сообщений
											updateChatMessages(true);

											// второе сообщение
											setTimeout(function(){
												$.when(addChatMessageToHistory(24, 'bot')).done(function(historyResponseBot2){
													updateChatMessages(true);

													// третье сообщение
													setTimeout(function(){
														$.when(addChatMessageToHistory(36, 'bot')).done(function(historyResponseBot3){
															updateChatMessages(true);

															// четвертое сообщение
															setTimeout(function(){
																$.when(addChatMessageToHistory(25, 'bot')).done(function(historyResponseBot4){
																	updateChatMessages(true);

																	// пятое сообщение
																	setTimeout(function(){
																		$.when(addChatMessageToHistory(26, 'bot')).done(function(historyResponseBot4){
																			updateChatMessages(true);
																		});
																	}, 800);
																});
															}, 800);
														});
													}, 800);
												});
											}, 800);
										});
									} else { // ввели неверно. Ни нет, ни да
										$.when(addChatMessageToHistory(27, 'bot')).done(function(historyResponseBot){
											// обновляем всю область сообщений
											updateChatMessages(true);
										});
									}
								}, 2400);
							}, 500);
						});
					} else { // НИЧЕГО НЕ ДЕЛАЕМ. ПРОСТО ОЧИЩАЕМ ПОЛЕ ВВОДА ДАННЫХ
						$('.chat_form input[type="text"]').val('');
					}
				} else { // НИЧЕГО НЕ ДЕЛАЕМ. ПРОСТО ОЧИЩАЕМ ПОЛЕ ВВОДА ДАННЫХ
					$('.chat_form input[type="text"]').val('');
				}
			});
		}
	});

	// юзер нажимает на кнопку в переписке
	$('body').on('click', '.chat_bot_message_btn', function(e){
		var _this = $(this);
		var prevDefaultMessageId = _this.attr('data-goto-bot-message-id');
		var nextDefaultMessageId = _this.attr('data-goto-bot-message-id-next');
		var message_id = _this.closest('.chat_message_item').attr('data-message_id'); // в истории переписки

		if (prevDefaultMessageId == 2) { // ВОЗВРАЩЕНИЕ К ТЕКСТУ ПРИВЕТСТВИЯ
			// удаляем сообщение с кнопкой во избежание повторного нажатия
			$.when(removeMessageById(message_id, 0, 0, 'back')).done(function(removeResponse){
				// обновляем всю область сообщений
				updateChatMessages(true);

				// реакция бота
				setTimeout(function(){
					// бот типа печатает
					printChatMessageFromBotDotsMain();

					setTimeout(function(){
						// добавить сообщение в историю переписки
						$.when(addChatMessageToHistory(prevDefaultMessageId, 'bot')).done(function(historyResponseBot){
							// обновляем всю область сообщений
							updateChatMessages(true);
						});
					}, 2400);
				}, 500);
			});
		} else if (nextDefaultMessageId == 14) { // ПРОСЛУШАТЬ СООБЩЕНИЕ ОТ АГЕНТА
			// удаляем сообщение с кнопкой во избежание повторного нажатия
			$.when(removeMessageById(message_id, _this.closest('.chat_message_section').next().find('.chat_message_item').attr('data-message_id'), _this.closest('.chat_message_section').next().next().find('.chat_message_item').attr('data-message_id'), 'wait_him')).done(function(removeResponse){
				// обновляем всю область сообщений
				updateChatMessages(true);

				// реакция бота
				setTimeout(function(){
					// бот типа печатает
					printChatMessageFromBotDotsMain();

					setTimeout(function(){
						// добавить сообщение в историю переписки
						$.when(addChatMessageToHistory(nextDefaultMessageId, 'bot')).done(function(historyResponseBot){
							// обновляем всю область сообщений
							updateChatMessages(true);

							setTimeout(function(){
								$.when(addChatMessageToHistory(15, 'bot')).done(function(historyResponseBot2){
									updateChatMessages(true);

									setTimeout(function(){
										$.when(addChatMessageToHistory(16, 'bot')).done(function(historyResponseBot3){
											updateChatMessages(true);
										});
									}, 800);
								});
							}, 800);
						});
					}, 2400);
				}, 500);
			});
		} else if (prevDefaultMessageId == 10) { // ВОЗВРАЩЕНИЕ К ТЕКСТУ С ВОПРОСОМ О ПРОДОЛЖЕНИИ
			// удаляем сообщение с кнопкой во избежание повторного нажатия
			$.when(removeMessageById(message_id, 0, 0, 'back')).done(function(removeResponse){
				// обновляем всю область сообщений
				updateChatMessages(true);

				// реакция бота
				setTimeout(function(){
					// бот типа печатает
					printChatMessageFromBotDotsMain();

					setTimeout(function(){
						// добавить сообщение в историю переписки
						$.when(addChatMessageToHistory(prevDefaultMessageId, 'bot')).done(function(historyResponseBot){
							// обновляем всю область сообщений
							updateChatMessages(true);

							setTimeout(function(){
								$.when(addChatMessageToHistory(11, 'bot')).done(function(historyResponseBot2){
									updateChatMessages(true);

									setTimeout(function(){
										$.when(addChatMessageToHistory(12, 'bot')).done(function(historyResponseBot3){
											updateChatMessages(true);

											setTimeout(function(){
												$.when(addChatMessageToHistory(13, 'bot')).done(function(historyResponseBot4){
													updateChatMessages(true);
												});
											}, 800);
										});
									}, 800);
								});
							}, 800);
						});
					}, 2400);
				}, 500);
			});
		} else if (nextDefaultMessageId == 17) { // СВЯЗАТЬСЯ ПО МОБ ТЕЛЕФОНУ
			$.when(removeMessageById(message_id, _this.closest('.chat_message_section').prev().find('.chat_message_item').attr('data-message_id'), _this.closest('.chat_message_section').prev().prev().find('.chat_message_item').attr('data-message_id'), 'connect_mobile')).done(function(removeResponse){
				// обновляем всю область сообщений
				updateChatMessages(true);

				// реакция бота
				setTimeout(function(){
					// бот типа печатает
					printChatMessageFromBotDotsMain();

					setTimeout(function(){
						// добавить сообщение в историю переписки
						$.when(addChatMessageToHistory(nextDefaultMessageId, 'bot')).done(function(historyResponseBot){
							// обновляем всю область сообщений
							updateChatMessages(true);

							setTimeout(function(){
								$.when(addChatMessageToHistory(18, 'bot')).done(function(historyResponseBot2){
									updateChatMessages(true);
								});
							}, 800);
						});
					}, 2400);
				}, 500);
			});
		} else if (nextDefaultMessageId == 19) { // ВЗЛОМАТЬ КАМЕРУ
			$.when(removeMessageById(message_id, _this.closest('.chat_message_section').prev().find('.chat_message_item').attr('data-message_id'), _this.closest('.chat_message_section').next().find('.chat_message_item').attr('data-message_id'), 'hack_camera')).done(function(removeResponse){
				// обновляем всю область сообщений
				updateChatMessages(true);

				// реакция бота
				setTimeout(function(){
					// бот типа печатает
					printChatMessageFromBotDotsMain();

					setTimeout(function(){
						// добавить сообщение в историю переписки
						$.when(addChatMessageToHistory(nextDefaultMessageId, 'bot')).done(function(historyResponseBot){
							// обновляем всю область сообщений
							updateChatMessages(true);

							setTimeout(function(){
								$.when(addChatMessageToHistory(20, 'bot')).done(function(historyResponseBot2){
									updateChatMessages(true);
								});
							}, 800);
						});
					}, 2400);
				}, 500);
			});
		} else if (nextDefaultMessageId == 23) { // ОТКАЗАЛИСЬ ОТ ПОМОЩИ БОТУ ПО ВЗЛОМУ И НАЖАЛИ НА КНОПКУ. АНАЛОГИЧНЫЕ ДЕЙСТВИЯ, КАК ЕСЛИ БЫ СРАЗУ ВВЕЛИ ПОЗИТИВНЫЙ ОТВЕТ
			$.when(removeMessageById(message_id, 0, 0, 'hack_camera_ready_now')).done(function(removeResponse){
				// обновляем всю область сообщений
				updateChatMessages(true);

				// реакция бота
				setTimeout(function(){
					// бот типа печатает
					printChatMessageFromBotDotsMain();

					setTimeout(function(){
						// добавить сообщение в историю переписки
						$.when(addChatMessageToHistory(nextDefaultMessageId, 'bot')).done(function(historyResponseBot){
							// обновляем всю область сообщений
							updateChatMessages(true);

							setTimeout(function(){
								$.when(addChatMessageToHistory(24, 'bot')).done(function(historyResponseBot2){
									updateChatMessages(true);

									setTimeout(function(){
										$.when(addChatMessageToHistory(25, 'bot')).done(function(historyResponseBot3){
											updateChatMessages(true);

											setTimeout(function(){
												$.when(addChatMessageToHistory(26, 'bot')).done(function(historyResponseBot4){
													updateChatMessages(true);
												});
											}, 800);
										});
									}, 800);
								});
							}, 800);
						});
					}, 2400);
				}, 500);
			});
		} else if (nextDefaultMessageId == 28) { // БОТ ХАКНУЛ КАМЕРУ
			$.when(removeMessageById(message_id, 0, 0, 'hack_camera_done')).done(function(removeResponse){
				// обновляем всю область сообщений
				updateChatMessages(true);

				// реакция бота
				setTimeout(function(){
					// бот типа печатает
					printChatMessageFromBotDotsMain();

					setTimeout(function(){
						// добавить сообщение в историю переписки
						$.when(addChatMessageToHistory(nextDefaultMessageId, 'bot')).done(function(historyResponseBot){
							// обновляем всю область сообщений
							updateChatMessages(true);

							setTimeout(function(){
								$.when(addChatMessageToHistory(29, 'bot')).done(function(historyResponseBot2){
									updateChatMessages(true);

									setTimeout(function(){
										$.when(addChatMessageToHistory(37, 'bot')).done(function(historyResponseBot3){
											updateChatMessages(true);

											setTimeout(function(){
												$.when(addChatMessageToHistory(30, 'bot')).done(function(historyResponseBot4){
													updateChatMessages(true);

													setTimeout(function(){
														$.when(addChatMessageToHistory(31, 'bot')).done(function(historyResponseBot5){
															updateChatMessages(true);
														});
													}, 800);
												});
											}, 800);
										});
									}, 800);
								});
							}, 800);
						});
					}, 2400);
				}, 500);
			});
		} else if (nextDefaultMessageId == 32) { // БОТ ХАКНУЛ КАМЕРУ
			// доп условие. К последнему шагу переходит двумя путями. Поэтому определяем какую именно кнопку нажимаем
			var btnText = _this.text();

			if (btnText.indexOf('DONE') > -1 || btnText.indexOf('FERDIG') > -1) {
				var btnExplain = 'great_job2';
			} else {
				var btnExplain = 'great_job';
			}

			$.when(removeMessageById(message_id, _this.closest('.chat_message_section').next().find('.chat_message_item').attr('data-message_id'), 0, btnExplain)).done(function(removeResponse){
				// обновляем всю область сообщений
				updateChatMessages(true);

				// реакция бота
				setTimeout(function(){
					// бот типа печатает
					printChatMessageFromBotDotsMain();

					setTimeout(function(){
						// добавить сообщение в историю переписки
						$.when(addChatMessageToHistory(nextDefaultMessageId, 'bot')).done(function(historyResponseBot){
							// обновляем всю область сообщений
							updateChatMessages(true);

							setTimeout(function(){
								$.when(addChatMessageToHistory(33, 'bot')).done(function(historyResponseBot2){
									setTeamTabsTextInfo('chat_send_message_access', 0);
									viewChatFormMessageHidden();

									updateChatMessages(true);
								});
							}, 800);
						});
					}, 2400);
				}, 500);
			});
		} else if (nextDefaultMessageId == 34) { // БОТ НЕ НАШЕЛ СЛЕДОВ
			$.when(removeMessageById(message_id, _this.closest('.chat_message_section').prev().find('.chat_message_item').attr('data-message_id'), 0, 'no_trace')).done(function(removeResponse){
				// обновляем всю область сообщений
				updateChatMessages(true);

				// реакция бота
				setTimeout(function(){
					// бот типа печатает
					printChatMessageFromBotDotsMain();

					setTimeout(function(){
						// добавить сообщение в историю переписки
						$.when(addChatMessageToHistory(nextDefaultMessageId, 'bot')).done(function(historyResponseBot){
							// обновляем всю область сообщений
							updateChatMessages(true);

							setTimeout(function(){
								$.when(addChatMessageToHistory(38, 'bot')).done(function(historyResponseBot2){
									updateChatMessages(true);

									setTimeout(function(){
										$.when(addChatMessageToHistory(39, 'bot')).done(function(historyResponseBot3){
											updateChatMessages(true);

											setTimeout(function(){
												$.when(addChatMessageToHistory(40, 'bot')).done(function(historyResponseBot4){
													updateChatMessages(true);

													setTimeout(function(){
														$.when(addChatMessageToHistory(35, 'bot')).done(function(historyResponseBot5){
															updateChatMessages(true);
														});
													}, 800);
												});
											}, 800);
										});
									}, 800);
								});
							}, 800);
						});
					}, 2400);
				}, 500);
			});
		}
	});
});