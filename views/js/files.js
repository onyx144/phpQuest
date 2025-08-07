/* === ГЛАВНОЕ ОКНО ИГРЫ - ЦЕНТРАЛЬНЫЙ БЛОК ИНФОРМАЦИИ - FILES === */

/* ОБЩИЕ ФУНКЦИИ */
	// Открыть тип табов: files
	function openTypeTabsFiles(isSocketSend) {
		$('.dashboard_tabs[data-dashboard="files"]').addClass('dashboard_tabs_active');
		$('.dashboard_item[data-dashboard="files"]').addClass('dashboard_item_active');

		uploadTypeTabsFilesStep(isSocketSend);
	}

	// загрузить конкретный экран (с переключателем табов) для files
	function uploadTypeTabsFilesStep(isSocketSend) {
		var formData = new FormData();
    	formData.append('op', 'uploadTypeTabsFilesStep');
    	formData.append('lang_abbr', $('html').attr('lang'));

    	$.ajax({
			url: '/ajax/ajax_files.php',
	        type: "POST",
	        dataType: "json",
	        cache: false,
	        contentType: false,
	        processData: false,
	        data: formData,
			success: function(json) {
				if (json.titles) {
					$('.dashboard_tabs[data-dashboard="files"] .dashboard_tab_titles').html(json.titles);
				}
				if (json.content) {
					$('.dashboard_tabs[data-dashboard="files"] .dashboard_tab_content_item_wrapper').html(json.content);
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
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});

		// запоминаем открытый тип табов
		setTeamLastTypeTabs('files');
	}

	// Обновить к-во непрочитанных файлов
	function updateDontOpenFilesQt() {
		var formData = new FormData();
    	formData.append('op', 'updateDontOpenFilesQt');

    	$.ajax({
			url: '/ajax/ajax_files.php',
	        type: "POST",
	        dataType: "json",
	        cache: false,
	        contentType: false,
	        processData: false,
	        data: formData,
			success: function(json) {
				$('.dashboard_item[data-dashboard="files"] .dashboard_item_text_qt').html(json.success);

				if (json.success == 0) {
					$('.dashboard_item[data-dashboard="files"] .dashboard_item_text_qt').css('display','none');
				} else {
					$('.dashboard_item[data-dashboard="files"] .dashboard_item_text_qt').css('display','inline-block');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {	
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}

	// открыть файл типа видео - попап
	function openFileVideoPopup(fileId, path, name, classVideo, type) {
		playVideoSeeking = false;

		if ($('#popup_video').length) {
			$('#popup_video').removeClass().addClass(classVideo);

			$('#popup_video_mp4 source').attr('src', '/' + path);
			$('#popup_video .popup_video_container_inner').attr('data-file-id', fileId).attr('data-type', type);

			if (type == 'file') {
				$('#popup_video_mp4').attr('controls', 'controls');
			} else if (type == 'call') {
				$('#popup_video_mp4').removeAttr('controls');

				name = ''; // обнуляем на всякий случай заголовок для звонков. НЕ выводим заголовок
			}

			$('#popup_video_mp4')[0].load();

			$('#popup_video .popup_video_inner_title').html(name);
			$('#popup_video .popup_video_play').css('display','block');
			$('#popup_video').fadeIn(200);
		}
	}

	// открыть файл типа видео - попап. Если видео звонка
	function openFileVideoPopupCall(fileId, path, name, classVideo, type) {
		playVideoSeeking = false;

		if ($('#popup_video_phone_video').length && $('#popup_video_mp4_call').length) {
			$('#popup_video_phone_video').removeClass().addClass(classVideo);

			$('#popup_video_mp4_call').attr('src', '/' + path);

			$('.popup_video_phone_video_inner').attr('data-type', type);

			if (type == 'call_again') {
				$('#popup_video_mp4_call').attr('controls', 'controls');
			} else {
				$('#popup_video_mp4_call').removeAttr('controls');
			}

			$('#popup_video_mp4_call')[0].load();

			$('#popup_video_phone_video').fadeIn(200);
		}
	}

	// добавить файл к списку просмотренных
	function addFileToViewed(fileId) {
		var formData = new FormData();
    	formData.append('op', 'addFileToActive');
    	formData.append('lang_abbr', $('html').attr('lang'));
    	formData.append('file_id', fileId);

    	$.ajax({
			url: '/ajax/ajax_files.php',
	        type: "POST",
	        dataType: "json",
	        cache: false,
	        contentType: false,
	        processData: false,
	        data: formData,
			success: function(json) {
				if (json.success) {
					// Обновить к-во непрочитанных файлов
					updateDontOpenFilesQt();
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {	
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}

	// остановить проигрывание файла видео
	function stopVideo() {
		playVideoByNotControls = false; // возвращаем к значению по умолчанию

		if ($('#popup_video').length && $('#popup_video_mp4').length) {
			// if (music_before) {
			if ($('.music_on').length && $('.music_on').hasClass('music_active')) {
				playMusic();
			}
			// music_before = false;

			var checkedVideo = $('#popup_video_mp4').get(0);
			if (!checkedVideo.paused) {
				checkedVideo.pause();
				$('#popup_video .popup_video_play').css('display','block');
				$('#popup_video .popup_video_stop').css('display','none');

				$('#popup_video .popup_video_btns').css('display','block');
			}
		}
	}

	// остановить проигрывание файла видео. Если звонок
	function stopVideoCall() {
		playVideoByNotControls = false; // возвращаем к значению по умолчанию

		if ($('#popup_video_phone_video').length && $('#popup_video_mp4_call').length) {
			// if (music_before) {
			if ($('.music_on').length && $('.music_on').hasClass('music_active')) {
				playMusic();
			}
			// music_before = false;

			var checkedVideo = $('#popup_video_mp4_call').get(0);
			if (!checkedVideo.paused) {
				checkedVideo.pause();
			}
		}
	}

	// вспомогательная функция. Запущен ли аудио-файл
	function isPlaying(audelem) {
		return !audelem.paused;
	}

	// закрыть попап с видео
	function closePopupVideo() {
		$('#popup_video').fadeOut(200);

		setTimeout(function(){
			$('#popup_video').removeClass();

			$('#popup_video .popup_video_container_inner').attr('data-file-id', 0);

			$('#popup_video .popup_video_inner_title').html('');
		}, 210);
	}

	// закрыть попап с видео. Если звонок
	function closePopupVideoCall() {
		$('#popup_video_phone_video').fadeOut(200);

		setTimeout(function(){
			$('#popup_video_phone_video').removeClass();
			$('#popup_video_mp4_call source').attr('src', '/zombie/video/one_second.mp4');
		}, 210);
	}

	// запустить проигрывание файла видео
	function playVideo(type) {
		if ($('#popup_video_mp4').length) {
			// music_before = music;
			stopMusic();

			var checkedVideo = $('#popup_video_mp4').get(0);
			if (checkedVideo.paused) {
				// checkedVideo.play();
				$('#popup_video .popup_video_play').css('display','none');
				$('#popup_video .popup_video_stop').css('display','block');

				$('#popup_video .popup_video_btns').css('display','none');

				// Autoplay
				var promise = checkedVideo.play();

				if (promise !== undefined) {
					promise.then(_ => {
						// console.log('autoplay');
					}).catch(error => {
						// console.log('autoplay ERR');
						$('#popup_video_mp4').attr('controls', 'controls');
					});
				}
			}

			if (type == 'file') {
				// добавляем файл к списку просмотренных
				// if ($('#popup_video .popup_video_container_inner').attr('data-file-id') != '0' && $('#popup_video .popup_video_container_inner').attr('data-file-id') != 0) {
					addFileToViewed($('#popup_video .popup_video_container_inner').attr('data-file-id'));
				// }
		    }
		}
	}

	// запустить проигрывание файла видео. Если видео звонка
	function playVideoCall() {
		if ($('#popup_video_mp4_call').length) {
			// music_before = music;
			stopMusic();

			var checkedVideo = $('#popup_video_mp4_call').get(0);
			if (checkedVideo.paused) {
				// checkedVideo.play();

				// Autoplay
				var promise = checkedVideo.play();

				if (promise !== undefined) {
					promise.then(_ => {
						// console.log('autoplay');
					}).catch(error => {
						// console.log('autoplay ERR');
						$('#popup_video_mp4_call').attr('controls', 'controls');
					});
				}
			}
		}
	}

$(function() {
	// открыть файл при нажатии на название в списке файлов в табе справа
	$('.dashboard_tabs[data-dashboard="files"]').on('click', '.dashboard_tab_content_file_item', function(e){
		if ($(this).attr('data-type') == 'video') {
			// socket
			var message = {
				'op': 'openFileVideoPopup',
				'parameters': {
					'fileId': $(this).attr('data-file-id'),
					// 'path': $(this).attr('data-path'),
					'file_with_path': $(this).attr('data-file-with-path'),
					'name': $(this).find('.dashboard_tab_content_file_item_name').html(),
					'classVideo': 'popup_video_type_file',
					'type': 'file',
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));

			// function
			openFileVideoPopup($(this).attr('data-file-id'), $(this).attr('data-path'), $(this).find('.dashboard_tab_content_file_item_name').html(), 'popup_video_type_file', 'file');
		} else if ($(this).attr('data-type') == 'pdf') {
			/*// socket
			var message = {
				'op': 'openFilePdf',
				'parameters': {
					'fileId': $(this).attr('data-file-id'),
					// 'path': $(this).attr('data-path'),
					'file_with_path': $(this).attr('data-file-with-path'),
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));

			if (is_touch_device()) {
		        // function
				$.fancybox.open({
                    src: '/plugins/pdf.js/web/viewer.html?file=' + encodeURIComponent('/' + $(this).attr('data-path')),
                    type: 'iframe',
                    opts: {
                        afterShow: function(instance, current) {},
                        beforeShow: function(instance, current) {},
                        beforeClose: function(instance, current) {
                        	// socket
							var message = {
								'op': 'closeFilePdf',
								'parameters': {
									'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
									'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
								}
					        };
					        sendMessageSocket(JSON.stringify(message));
                        },
                        iframe : {
                            preload : false
                        }
                    }
                });
			} else {
				var pageSize = getPageSize();
		        var windowWidth = pageSize[2];
		        if (windowWidth < 1800) {
		        	var koef = parseFloat((windowWidth / 1920).toFixed(2)) + 0.01;

		        	$.fancybox.open({
	                    src: '/' + $(this).attr('data-path'),
	                    type: 'iframe',
	                    opts: {
	                        afterShow: function(instance, current) {
                        		$('.fancybox-container.fancybox-is-open').css('height', ($('#main').outerHeight() * parseFloat((1920 / windowWidth).toFixed(2))) + 'px');
	                        },
	                        beforeShow: function(instance, current) {},
	                        beforeClose: function(instance, current) {
	                        	// socket
								var message = {
									'op': 'closeFilePdf',
									'parameters': {
										'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
										'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
									}
						        };
						        sendMessageSocket(JSON.stringify(message));
	                        },
	                        iframe : {
	                            preload : false
	                        }
	                    }
	                });
		        } else {
					$.fancybox.open({
	                    src: '/' + $(this).attr('data-path'),
	                    type: 'iframe',
	                    opts: {
	                        afterShow: function(instance, current) {},
	                        beforeShow: function(instance, current) {},
	                        beforeClose: function(instance, current) {
	                        	// socket
								var message = {
									'op': 'closeFilePdf',
									'parameters': {
										'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
										'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
									}
						        };
						        sendMessageSocket(JSON.stringify(message));
	                        },
	                        iframe : {
	                            preload : false
	                        }
	                    }
	                });
				}
			}

			// добавляем файл к списку просмотренных
			addFileToViewed($(this).attr('data-file-id'));*/

			var _this = $(this);
			var win = window.open('/' + _this.attr('data-path'), '_blank');
			if (win) {
				// добавляем файл к списку просмотренных
				addFileToViewed(_this.attr('data-file-id'));

				// socket
				var message = {
					'op': 'openFilePdf',
					'parameters': {
						// 'fileId': $(this).attr('data-file-id'),
						'path': '/' + _this.attr('data-path'),
						// 'file_with_path': $(this).attr('data-file-with-path'),
						'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
						'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
					}
		        };
		        sendMessageSocket(JSON.stringify(message));

				win.focus();
			}
		} else if ($(this).attr('data-type') == 'link') {
			/*$.fancybox.open({
                src: $(this).attr('data-path'),
                type: 'iframe',
                opts: {
                    afterShow: function(instance, current) {},
                    beforeShow: function(instance, current) {},
                    beforeClose: function(instance, current) {},
                    iframe : {
                        preload : false
                    }
                }
            });

			// добавляем файл к списку просмотренных
			addFileToViewed($(this).attr('data-file-id'));*/
			
			var _this = $(this);
			var win = window.open(_this.attr('data-path'), '_blank');
			if (win) {
			    // добавляем файл к списку просмотренных
				addFileToViewed(_this.attr('data-file-id'));

				// socket
				var message = {
					'op': 'openFileLink',
					'parameters': {
						'path': _this.attr('data-file-id'),
						'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
						'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
					}
		        };
		        sendMessageSocket(JSON.stringify(message));

			    win.focus();
			}
		}
	});

	// остановить проигрывание файла видео
	$('#popup_video').on('click', '.popup_video_stop', function(e){
		// socket
		var message = {
			'op': 'stopVideo',
			'parameters': {
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
        };
        sendMessageSocket(JSON.stringify(message));

		stopVideo();
	});

	// остановить проигрывание видео при нажатии на кнопке Pause в стандартных Controls
	$('#popup_video #popup_video_mp4').on('pause', function(e){
		setTimeout(function(){ // задержка для НЕ отправки данных в сокеты при промотке видео
			if (!playVideoSeeking) {
				playVideoByNotControls = false; // возвращаем к значению по умолчанию

				// if (music_before) {
				if ($('.music_on').length && $('.music_on').hasClass('music_active')) {
					playMusic();
				}
				// music_before = false;

				$('#popup_video .popup_video_play').css('display','block');
				$('#popup_video .popup_video_stop').css('display','none');

				$('#popup_video .popup_video_btns').css('display','block');

				// socket
				var message = {
					'op': 'stopVideoByControls',
					'parameters': {
						'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
						'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
					}
		        };
		        sendMessageSocket(JSON.stringify(message));
		    }
	    }, 10);
	});

	// остановить проигрывание видео при нажатии на кнопке Pause в стандартных Controls. Если звонок
	$('#popup_video_phone_video #popup_video_mp4_call').on('pause', function(e){
		setTimeout(function(){ // задержка для НЕ отправки данных в сокеты при промотке видео
			if (!playVideoSeeking) {
				playVideoByNotControls = false; // возвращаем к значению по умолчанию

				// if (music_before) {
				if ($('.music_on').length && $('.music_on').hasClass('music_active')) {
					playMusic();
				}
				// music_before = false;

				// socket
				if ($('#popup_video_phone_video').hasClass('minigame_error_video')) {
					var message = {
						'op': 'closeAndStopPopupVideoMinigameError',
						'parameters': {
							'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
							'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
						}
			        };
			        sendMessageSocket(JSON.stringify(message));
				} else {
					var message = {
						'op': 'stopVideoByControlsCalls',
						'parameters': {
							'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
							'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
						}
			        };
			        sendMessageSocket(JSON.stringify(message));
			    }
		    }
	    }, 10);
	});

	// когда видео доиграло до конца, то закрываем и производим нужные действия
	// $('body').on('ended', '#popup_video #popup_video_mp4', function(e){
	$('#popup_video #popup_video_mp4').on('ended', function(e){
		// console.log('close from files');
		closePopupVideo();
		// socket
		var message = {
			'op': 'stopVideoAndClosePopupVideo',
			'parameters': {
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
        };
        sendMessageSocket(JSON.stringify(message));

        if ($('#popup_video').hasClass('new_mission_answer_incoming_video')) { // если закрытие видео при принятии миссии
			var timerSeconds = parseInt($('.timer').attr('data-timer'), 10);

			if (timerSeconds == 0) {
				// socket
				var message = {
					'op': 'closePopupVideoAndAcceptMission',
					'parameters': {
						'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
						'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
					}
		        };
		        sendMessageSocket(JSON.stringify(message));

		        // запускаем обновление данных
		        acceptMission();
		    }
		} else if ($('#popup_video').hasClass('company_investigate_answer_incoming_video')) { // dashboard, company investigate
			// фиксируем к-во очков, которое было у команды перед успешным результатом поиска. Для правильного подсчета очков команды
			$.when(getTeamInfo()).done(function(teamResponse){
				var teamInfo = teamResponse.success;

				scoreBeforeDashboardCompanyInvestigate = parseInt(teamInfo.score, 10);

				// socket
				var message = {
					'op': 'closePopupVideoAndCompanyInvestigateSuccess',
					'parameters': {
						'scoreBeforeDashboardCompanyInvestigate': scoreBeforeDashboardCompanyInvestigate,
						'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
						'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
					}
		        };
		        sendMessageSocket(JSON.stringify(message));

		        // запускаем обновление данных
				companyInvestigate();
			});
		} else if ($('#popup_video').hasClass('geo_coordinates_answer_incoming_video')) { // dashboard, Geo coordinates
			// фиксируем к-во очков, которое было у команды перед успешным результатом поиска. Для правильного подсчета очков команды
			$.when(getTeamInfo()).done(function(teamResponse){
				var teamInfo = teamResponse.success;

				scoreBeforeDashboardCoordinates = parseInt(teamInfo.score, 10);

				// socket
				var message = {
					'op': 'closePopupVideoAndCoordinatesSuccess',
					'parameters': {
						'scoreBeforeDashboardCoordinates': scoreBeforeDashboardCoordinates,
						'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
						'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
					}
		        };
		        sendMessageSocket(JSON.stringify(message));

		        // запускаем обновление данных
				geoCoordinates();
			});
		} else if ($('#popup_video').hasClass('african_partner_answer_incoming_video')) { // dashboard, African partner
			// фиксируем к-во очков, которое было у команды перед успешным результатом поиска. Для правильного подсчета очков команды
			$.when(getTeamInfo()).done(function(teamResponse){
				var teamInfo = teamResponse.success;

				scoreBeforeDashboardAfricanPartner = parseInt(teamInfo.score, 10);

				// socket
				var message = {
					'op': 'closePopupVideoAndAfricanPartnerSuccess',
					'parameters': {
						'scoreBeforeDashboardAfricanPartner': scoreBeforeDashboardAfricanPartner,
						'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
						'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
					}
		        };
		        sendMessageSocket(JSON.stringify(message));

		        // запускаем обновление данных
				africanPartner();
			});
		} else if ($('#popup_video').hasClass('arrest_video')) {
        	// победная музыка
			finishAudio = new Audio;
			finishAudio.src = '/music/winner.mp3';
			// finishAudio.play();

			// Autoplay
			var promise = finishAudio.play();

			if (promise !== undefined) {
				promise.then(_ => {
					// console.log('autoplay');
				}).catch(error => {
					// console.log('autoplay ERR');
				});
			}

			// отображаем победный попап
			if (!is_touch_device()) {
				var pageSize = getPageSize();
	    		var windowWidth = pageSize[2];
	    		var windowHeight = pageSize[1];
	    		if (windowWidth < 1800) {
	    			$('body').removeClass('body_desktop_scale').css('transform', 'scale(1)');

	    			var pageSize = getPageSize();

	    			var koef = parseFloat((windowWidth / 1920).toFixed(2)) + 0.01;

	    			$('#popup_mission_complete').css({
	    				'display': 'block',
	    				'height': windowHeight + 'px'
	    			});
					// $.scrollLock(true);

					$('body').addClass('body_desktop_scale').css('transform', 'scale(' + koef + ')');
	    		} else {
					$('#popup_mission_complete').css('display','block');
					// $.scrollLock(true);
				}
			} else {
				$('#popup_mission_complete').css('display','block');
				// $.scrollLock(true);
			}

			// socket
			var message = {
				'op': 'ArrestSuccess',
				'parameters': {
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));
        }
	});

	// когда видео доиграло до конца, то закрываем и производим нужные действия. Если звонок
	$('#popup_video_phone_video #popup_video_mp4_call').on('ended', function(e){
		if (
			// $('#popup_video_phone_video').hasClass('new_mission_answer_incoming_video') || 
			$('#popup_video_phone_video').hasClass('company_investigate_answer_incoming_video') || 
			$('#popup_video_phone_video').hasClass('geo_coordinates_answer_incoming_video') || 
			$('#popup_video_phone_video').hasClass('african_partner_answer_incoming_video')
		) {
			closePopupVideo();
		} else {
			closePopupVideoCall();
		}

		/*if ($('#popup_video_phone_video').hasClass('new_mission_answer_incoming_video')) { // если закрытие видео при принятии миссии
			var timerSeconds = parseInt($('.timer').attr('data-timer'), 10);
			if (timerSeconds == 0) {
				// socket
				var message = {
					'op': 'closePopupVideoAndAcceptMission',
					'parameters': {
						'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
						'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
					}
		        };
		        sendMessageSocket(JSON.stringify(message));

		        // запускаем обновление данных
		        acceptMission();
		    }
		} else*/
		/*if ($('#popup_video_phone_video').hasClass('company_investigate_answer_incoming_video')) { // dashboard, company investigate
			// фиксируем к-во очков, которое было у команды перед успешным результатом поиска. Для правильного подсчета очков команды
			$.when(getTeamInfo()).done(function(teamResponse){
				var teamInfo = teamResponse.success;

				scoreBeforeDashboardCompanyInvestigate = parseInt(teamInfo.score, 10);

				// socket
				var message = {
					'op': 'closePopupVideoAndCompanyInvestigateSuccess',
					'parameters': {
						'scoreBeforeDashboardCompanyInvestigate': scoreBeforeDashboardCompanyInvestigate,
						'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
						'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
					}
		        };
		        sendMessageSocket(JSON.stringify(message));

		        // запускаем обновление данных
				companyInvestigate();
			});
		} else*/
		/*if ($('#popup_video_phone_video').hasClass('geo_coordinates_answer_incoming_video')) { // dashboard, Geo coordinates
			// фиксируем к-во очков, которое было у команды перед успешным результатом поиска. Для правильного подсчета очков команды
			$.when(getTeamInfo()).done(function(teamResponse){
				var teamInfo = teamResponse.success;

				scoreBeforeDashboardCoordinates = parseInt(teamInfo.score, 10);

				// socket
				var message = {
					'op': 'closePopupVideoAndCoordinatesSuccess',
					'parameters': {
						'scoreBeforeDashboardCoordinates': scoreBeforeDashboardCoordinates,
						'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
						'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
					}
		        };
		        sendMessageSocket(JSON.stringify(message));

		        // запускаем обновление данных
				geoCoordinates();
			});
		} else*/
		/*if ($('#popup_video_phone_video').hasClass('african_partner_answer_incoming_video')) { // dashboard, African partner
			// фиксируем к-во очков, которое было у команды перед успешным результатом поиска. Для правильного подсчета очков команды
			$.when(getTeamInfo()).done(function(teamResponse){
				var teamInfo = teamResponse.success;

				scoreBeforeDashboardAfricanPartner = parseInt(teamInfo.score, 10);

				// socket
				var message = {
					'op': 'closePopupVideoAndAfricanPartnerSuccess',
					'parameters': {
						'scoreBeforeDashboardAfricanPartner': scoreBeforeDashboardAfricanPartner,
						'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
						'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
					}
		        };
		        sendMessageSocket(JSON.stringify(message));

		        // запускаем обновление данных
				africanPartner();
			});
		} else*/
		if ($('#popup_video_phone_video').hasClass('metting_place_answer_incoming_video')) { // dashboard, Meeting place
			// фиксируем к-во очков, которое было у команды перед успешным результатом поиска. Для правильного подсчета очков команды
			$.when(getTeamInfo()).done(function(teamResponse){
				var teamInfo = teamResponse.success;

				scoreBeforeDashboardMettingPlace = parseInt(teamInfo.score, 10);

				// socket
				var message = {
					'op': 'closePopupVideoAndMettingPlaceSuccess',
					'parameters': {
						'scoreBeforeDashboardMettingPlace': scoreBeforeDashboardMettingPlace,
						'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
						'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
					}
		        };
		        sendMessageSocket(JSON.stringify(message));

		        // запускаем обновление данных
				mettingPlace();
			});
		} else if ($('#popup_video_phone_video').hasClass('room_name_answer_incoming_video')) { // dashboard, Room name
			// фиксируем к-во очков, которое было у команды перед успешным результатом поиска. Для правильного подсчета очков команды
			$.when(getTeamInfo()).done(function(teamResponse){
				var teamInfo = teamResponse.success;

				scoreBeforeDashboardRoomName = parseInt(teamInfo.score, 10);

				// socket
				var message = {
					'op': 'closePopupVideoAndRoomNameSuccess',
					'parameters': {
						'scoreBeforeDashboardRoomName': scoreBeforeDashboardRoomName,
						'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
						'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
					}
		        };
		        sendMessageSocket(JSON.stringify(message));

		        // запускаем обновление данных
				roomName();
			});
		} else if ($('#popup_video_phone_video').hasClass('minigame_answer_incoming_video')) { // minigame. Success
			/*// фиксируем к-во очков, которое было у команды перед успешным результатом поиска. Для правильного подсчета очков команды
			$.when(getTeamInfo()).done(function(teamResponse){
				var teamInfo = teamResponse.success;

				scoreBeforeMinigame = parseInt(teamInfo.score, 10);

				// socket
				var message = {
					'op': 'closePopupVideoAndMinigameSuccess',
					'parameters': {
						'scoreBeforeMinigame': scoreBeforeMinigame,
						'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
						'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
					}
		        };
		        sendMessageSocket(JSON.stringify(message));

		        // запускаем обновление данных
				minigameSuccess();
			});*/
			// socket
			var message = {
				'op': 'closePopupVideoAndMinigameSuccess',
				'parameters': {
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));

			minigameSuccess();
		} else if ($('#popup_video_phone_video').hasClass('minigame_error_video')) { // minigame. Error
			// socket
			var message = {
				'op': 'closePopupVideoMinigameError',
				'parameters': {
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));

	        // запускаем обновление данных
			uploadMinigamePositionsStart();
		} else { // любое другое стандартное закрытие видео
			// socket
			var message = {
				'op': 'stopVideoAndClosePopupVideoCalls',
				'parameters': {
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));
	    }
	});

	// действия при перемотке видео
	if ($('#popup_video #popup_video_mp4').length) {
		document.getElementById("popup_video_mp4").onseeking = function(){
			playVideoSeeking = true;
		};
		document.getElementById("popup_video_mp4").onseeked = function(){
			playVideoSeeking = false;
		};
	}

	// действия при перемотке видео. Если звонок
	if ($('#popup_video_phone_video #popup_video_mp4_call').length) {
		document.getElementById("popup_video_mp4_call").onseeking = function(){
			playVideoSeeking = true;
		};
		document.getElementById("popup_video_mp4_call").onseeked = function(){
			playVideoSeeking = false;
		};
	}

	// закрыть попап с видео
	$('body').on('click', '.popup_video_type_file .popup_video_bg, .popup_video_type_file .popup_video_close, .popup_video_type_calls_again .popup_video_bg, .popup_video_type_calls_again .popup_video_close, .popup_video_type_calls_to_jane .popup_video_bg, .popup_video_type_calls_to_jane .popup_video_close', function(e){
		var videoUrlEn = '';
		var videoUrlNo = '';

		if ($(this).closest('#popup_video').length > 0) {
			var videoUrl = $(this).closest('#popup_video').find('video source').attr('src');

			if (videoUrl.indexOf('/en/') > -1) {
				videoUrlEn = videoUrl;
				videoUrlNo = videoUrl.replace('/en/', '/no/');
			} else if (videoUrl.indexOf('/no/') > -1) {
				videoUrlEn = videoUrl.replace('/no/', '/en/');
				videoUrlNo = videoUrl;
			}
		}

		// socket
		var message = {
			'op': 'stopVideoAndClosePopupVideo',
			'parameters': {
				'video_url_en': videoUrlEn,
				'video_url_no': videoUrlNo,
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
        };
        sendMessageSocket(JSON.stringify(message));

		// function
		stopVideo();
		closePopupVideo();
	});

	// закрыть попап с видео. Если звонок
	$('body').on('click', '.popup_video_phone_video_bg, #popup_video_phone_video .popup_video_close', function(e){
		/*var videoUrlEn = '';
		var videoUrlNo = '';

		if ($(this).closest('#popup_video_phone_video').length > 0) {
			var videoUrl = $(this).closest('#popup_video_phone_video').find('video source').attr('src');

			if (videoUrl.indexOf('/en/') > -1) {
				videoUrlEn = videoUrl;
				videoUrlNo = videoUrl.replace('/en/', '/no/');
			} else if (videoUrl.indexOf('/no/') > -1) {
				videoUrlEn = videoUrl.replace('/no/', '/en/');
				videoUrlNo = videoUrl;
			}
		}*/

		// socket
		var message = {
			'op': 'stopVideoAndClosePopupVideoCalls',
			'parameters': {
				// 'video_url_en': videoUrlEn,
				// 'video_url_no': videoUrlNo,
				'is_minigame_success': $('#popup_video_phone_video').hasClass('minigame_answer_incoming_video') ? true : false,
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
        };
        sendMessageSocket(JSON.stringify(message));

		// function
		stopVideoCall();
		closePopupVideoCall();

		// Minigame success
		if ($('#popup_video_phone_video').hasClass('minigame_answer_incoming_video')) {
			minigameSuccess();
		}
	});

	// запустить проигрывание файла видео
	$('#popup_video').on('click', '.popup_video_play', function(e){
		// console.log('play4');
		// socket
		var message = {
			'op': 'playVideoFileByPlayBtn',
			'parameters': {
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
        };
        sendMessageSocket(JSON.stringify(message));

        // function
		playVideoByNotControls = true; // указываем, что запускалось через кнопку Play, а не через Controls

		playVideo('file');
	});

	// запуск файла при нажатии на кнопку Play в стандартных Controls
	$('#popup_video #popup_video_mp4').on('play', function(e){
		setTimeout(function(){ // задержка для НЕ отправки данных в сокеты при промотке видео
			if (!playVideoByNotControls && !playVideoSeeking) {
				// socket
				var message = {
					'op': 'playVideoFileByControls',
					'parameters': {
						'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
						'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
					}
		        };
		        sendMessageSocket(JSON.stringify(message));

		        // function
				// music_before = music;
				stopMusic();

				$('#popup_video .popup_video_play').css('display','none');
				$('#popup_video .popup_video_stop').css('display','block');

				$('#popup_video .popup_video_btns').css('display','none');

				// добавляем файл к списку просмотренных
				if ($('#popup_video .popup_video_container_inner').attr('data-type') == 'file') {
					addFileToViewed($('#popup_video .popup_video_container_inner').attr('data-file-id'));
				}
			}
	    }, 10);
	});

	// запуск файла при нажатии на кнопку Play в стандартных Controls. Если звонок
	$('#popup_video_phone_video #popup_video_mp4_call').on('play', function(e){
		setTimeout(function(){ // задержка для НЕ отправки данных в сокеты при промотке видео
			if (!playVideoByNotControls && !playVideoSeeking) {
				// socket
				var message = {
					'op': 'playVideoFileByControlsCalls',
					'parameters': {
						'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
						'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
					}
		        };
		        sendMessageSocket(JSON.stringify(message));

		        // function
				// music_before = music;
				stopMusic();
			}
	    }, 10);
	});
});