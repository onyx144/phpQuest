/* === ОСНОВНАЯ ЛОГИКА ИГРЫ === */

/* ГЛАВНЫЕ ПЕРЕМЕННЫЕ */
	var langAbbr = $('html').attr('lang'); // языковой префикс
	var music = false; // активны ли фоновая музыка и звуки
	// var music_before = false; // активны ли фоновая музыка и звуки. Доп переменная для проигрывания других звуков
	var defaultLoaderLoadingText = 'Loading';
	if ($('html').attr('lang') == 'no') {
		defaultLoaderLoadingText = 'Laster';
	}
	var incomingCallTimer = false; // таймер реального времени при входящем "звонке"
	var alarmTimer = false; // таймер реального времени в попапе Alarm
	var incomingMusicTimer = false; // входящий звонок
	var incomingMusicDuration = 12000; // сколько секунд мелодия входящего звонка
	var incomingAudio = false; // переменная для аудио входящего звонка
	var alarmAudio = false; // переменная для аудио alarm
	var outgoingAudio; // переменная для аудио исходящего звонка
	var scoreAudio; // переменная для аудио изменения баллов
	var searchAudio; // переменная для аудио поиска
	var dataTransferAudio; // переменная для аудио data transfer
	var errorAudio; // переменная для аудио ошибки
	var printAudio; // переменная для аудио печати
	var successAudio; // переменная для аудио при успешном прохождении чего-либо
	var mainTimer = false; // основной таймер. 10-секундный таймер
	var mainTimer2 = false; // основной таймер. 1-секундный внутренний таймер
	var mainTimer3 = false; // таймер текущей команды в результатах. 1-секундный внутренний таймер
	var saveTimerEverySecond = 5; // сохранять в бд таймер каждые N секунд
	var outgoingCallTimer = false; // таймер реального времени при исходящем "звонке"
	var incrementScoreDuration = 4000; // время, на протяжении которого насчитываются баллы
	var updatedScoreMain = false; // обновлялись ли очки. Главный экран
	var updatedScoreHints = false; // обновлялись ли очки. Экран с подсказками
	var databaseSearchDuration = 7000; // сколько времени длится поиск по базе данных
	var databaseSearchIteration = 40; // сколько итераций длится поиск по базе данных
	var myLastAction = 26; // мой последний экшн
	var actionTimer = false;
	var playVideoByNotControls = false; // было ли запущено видео через кнопку Play, а НЕ через Controls
	var playVideoSeeking = false; // доп переменная для отслеживания, когда видео перематывается
	var dataTransferIteration = 40; // сколько итераций длится смена цифр в Data Transfer
	var dataTransferMusicDuration = 4500; // 5000; // сколько секунд длится музыка в Data Transfer
	var finishAudio; // переменная для победного аудио
	var startAudio = false; // переменная для аудио стартового отчета
	var scoreBeforeDatabaseCarRegister = 0; // сколько было очков до того, как нашли авто
	var scoreBeforeDatabasePersonalfilesPrivateindividuals = 0; // сколько было очков до того, как нашли database - personal files - private individuals
	var scoreBeforeDatabaseMobileCalls = 0; // сколько было очков до того, как нашли database - mobile calls
	var scoreBeforeDatabasePersonalfilesCeodatabase = 0; // сколько было очков до того, как нашли database - personal files - ceo database
	var scoreBeforeDashboardCompanyInvestigate = 0; // сколько было очков до того, как нашли dashboard - company investigate
	var scoreBeforeDashboardCoordinates = 0; // сколько было очков до того, как нашли dashboard - coordinates
	var scoreBeforeDashboardAfricanPartner = 0; // сколько было очков до того, как нашли dashboard - african partner
	var scoreBeforeDatabasesBankTransactions = 0; // сколько было очков до того, как нашли databases - bank transactions
	var scoreBeforeDashboardMettingPlace = 0; // сколько было очков до того, как нашли dashboard - metting place
	var scoreBeforeToolsScan = 0; // сколько было очков до того, как нашли tools - scan
	var scoreBeforeDashboardRoomName = 0; // сколько было очков до того, как нашли dashboard - room name
	var scoreBeforeMinigame = 0; // сколько было очков до того, как нашли minigame
	var callsNotAvailableJaneOpenPopup = false;

/* ОБЩИЕ ФУНКЦИИ */
	// скрыть основной прелоадер
	function hiddenMainPreloader() {
		$('#preloader2 span').html('');
		$('#preloader2').fadeOut(200);
	}

	// показать основной прелоадер
	function viewMainPreloader(text) {
		$('#preloader2 span').html(text);

		$('#preloader2').css('display', 'block');
	}

	// скрыть основное окно игры
	function hiddenMainGameWindow() {
		$('.content_game').css('display', 'none');
	}

	// показать основное окно игры
	function openMainGameWindow() {
		viewMainScore();
		viewMainTimer();
		viewHighscoreBtn();
		viewHintBtn();
		viewChatBtn();

		$('.content_game').css('display', 'block');

		// запоминаем открытое окно
		setTeamLastOpenWindow('main');
	}

	// случайное число из промежутка
	function selfRandom(min, max) {
		return Math.floor(Math.random() * (max - min + 1)) + min;
	}

	// получаем данные по команде
	function getTeamInfo() {
		var formData = new FormData();
		formData.append('op', 'getTeamInfo');

		return $.ajax({
			url: '/ajax/ajax.php',
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

	// запоминаем открытое окно
	function setTeamLastOpenWindow(windowName) {
		var formData = new FormData();
		formData.append('op', 'saveTeamTextField');
		formData.append('field', 'last_window_open');
		formData.append('val', windowName);

		$.ajax({
			url: '/ajax/ajax.php',
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

	// одновременное обновление действий команды
	function uploadGameByActionName(op, parameters) {
		// console.log('uploadGameByActionName');
	}

	// crossbrowse window size
	function getPageSize(){
	    var xScroll, yScroll;

	    if (window.innerHeight && window.scrollMaxY) {
	        xScroll = document.body.scrollWidth;
	        yScroll = window.innerHeight + window.scrollMaxY;
	    } else if (document.body.scrollHeight > document.body.offsetHeight){ // all but Explorer Mac
	        xScroll = document.body.scrollWidth;
	        yScroll = document.body.scrollHeight;
	    } else if (document.documentElement && document.documentElement.scrollHeight > document.documentElement.offsetHeight){ // Explorer 6 strict mode
	        xScroll = document.documentElement.scrollWidth;
	        yScroll = document.documentElement.scrollHeight;
	    } else { // Explorer Mac...would also work in Mozilla and Safari
	        xScroll = document.body.offsetWidth;
	        yScroll = document.body.offsetHeight;
	    }

	    var windowWidth, windowHeight;
	    if (self.innerHeight) { // all except Explorer
	        windowWidth = self.innerWidth;
	        windowHeight = self.innerHeight;
	    } else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
	        windowWidth = document.documentElement.clientWidth;
	        windowHeight = document.documentElement.clientHeight;
	    } else if (document.body) { // other Explorers
	        windowWidth = document.body.clientWidth;
	        windowHeight = document.body.clientHeight;
	    }

	    // for small pages with total height less then height of the viewport
	    if(yScroll < windowHeight){
	        pageHeight = windowHeight;
	    } else {
	        pageHeight = yScroll;
	    }

	    // for small pages with total width less then width of the viewport
	    if(xScroll < windowWidth){
	        pageWidth = windowWidth;
	    } else {
	        pageWidth = xScroll;
	    }

	    return [pageWidth,pageHeight,windowWidth,windowHeight];
	}

	// check touch device
	function is_touch_device() {
	    var prefixes = ' -webkit- -moz- -o- -ms- '.split(' ');
	    var mq = function(query) {
	        return window.matchMedia(query).matches;
	    }

	    if (('ontouchstart' in window) || window.DocumentTouch && document instanceof DocumentTouch) {
	        return true;
	    }

	    var query = ['(', prefixes.join('touch-enabled),('), 'heartz', ')'].join('');
	    return mq(query);
	}

	// Cookie
    var WFCookie = {
        get: function(e) {
            var t = document.cookie.match(new RegExp("(?:^|; )" + e.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, "\\$1") + "=([^;]*)"));
            return t ? decodeURIComponent(t[1]) : null
        },
        set: function(e, t, n) {
            null == n && (n = {}),
            (n = Object.assign({}, {
                path: "/"
            }, n)).expires instanceof Date && (n.expires = n.expires.toUTCString());
            var i = encodeURIComponent(e) + "=" + encodeURIComponent(t);
            for (var r in n)
                if (!1 !== n.hasOwnProperty(r)) {
                    i += "; " + r;
                    var o = n[r];
                    !0 !== o && (i += "=" + o)
                }
            document.cookie = i
        },
        remove: function(e) {
            this.set(e, "", {
                "max-age": -1
            })
        }
    };
    "undefined" != typeof module && void 0 !== module.exports && (module.exports = WFCookie);

	// функция запрета скрола
  	$.scrollLock = ( function scrollLockClosure() {
        'use strict';

        var $html      = $( 'html' ),
            // State: unlocked by default
            locked     = false,
            // State: scroll to revert to
            prevScroll = {
                scrollLeft : $( window ).scrollLeft(),
                scrollTop  : $( window ).scrollTop()
            },
            // State: styles to revert to
            prevStyles = {},
            lockStyles = {
                // 'overflow-y' : 'scroll',
                'overflow-y' : 'hidden',
                'position'   : 'fixed',
                'width'      : '100%'
            };

        // Instantiate cache in case someone tries to unlock before locking
        saveStyles();

        // Save context's inline styles in cache
        function saveStyles() {
            var styleAttr = $html.attr( 'style' ),
                styleStrs = [],
                styleHash = {};

            if( !styleAttr ){
                return;
            }

            styleStrs = styleAttr.split( /;\s/ );

            $.each( styleStrs, function serializeStyleProp( styleString ){
                if( !styleString ) {
                    return;
                }

                var keyValue = styleString.split( /\s:\s/ );

                if( keyValue.length < 2 ) {
                    return;
                }

                styleHash[ keyValue[ 0 ] ] = keyValue[ 1 ];
            } );

            $.extend( prevStyles, styleHash );
        }

        function lock() {
            var appliedLock = {};

            // Duplicate execution will break DOM statefulness
            if( locked ) {
                return;
            }

            // Save scroll state...
            prevScroll = {
                scrollLeft : $( window ).scrollLeft(),
                scrollTop  : $( window ).scrollTop()
            };

            // ...and styles
            saveStyles();

            // Compose our applied CSS
            $.extend( appliedLock, lockStyles, {
                // And apply scroll state as styles
                'left' : - prevScroll.scrollLeft + 'px',
                'top'  : - prevScroll.scrollTop  + 'px'
            } );

            // Then lock styles...
            $html.css( appliedLock );

            // ...and scroll state
            $( window )
                .scrollLeft( 0 )
                .scrollTop( 0 );

            locked = true;
        }

        function unlock() {
            // Duplicate execution will break DOM statefulness
            if( !locked ) {
                return;
            }

            // Revert styles
            $html.attr( 'style', $( '<x>' ).css( prevStyles ).attr( 'style' ) || '' );

            // Revert scroll values
            $( window )
                .scrollLeft( prevScroll.scrollLeft )
                .scrollTop(  prevScroll.scrollTop );

            locked = false;
        }

        return function scrollLock( on ) {
            // If an argument is passed, lock or unlock depending on truthiness
            if( arguments.length ) {
                if( on ) {
                    lock();
                }
                else {
                    unlock();
                }
            }
            // Otherwise, toggle
            else {
                if( locked ){
                    unlock();
                }
                else {
                    lock();
                }
            }
        };
    }() );

/* ФУНКЦИИ ДЛЯ ОСНОВНОЙ ЛОГИКИ ИГРЫ */

$(function() {
/* УДАЛИТЬ ПОСЛЕ ТЕСТИРОВАНИЯ */
	// обнуление результатов. Code test
	$('#section_join_game .company_logo').click(function(){
		var formData = new FormData();
		formData.append('op', 'clearTeamResults');

		$.ajax({
			url: '/ajax/ajax.php',
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
	// обнуление результатов. Code test2
	$('#section_join_game .bg_list1').click(function(){
		var formData = new FormData();
		formData.append('op', 'clearTeamResults2');

		$.ajax({
			url: '/ajax/ajax.php',
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

/* ЯЗЫКИ */
	// показать меню выбора языка
	$('.language').click(function(){
		if ($(this).hasClass('language_open')) {
			$('.language_hidden').animate({height: '0px'},100);
			$(this).removeClass('language_open');
		} else {
			$('.language_hidden').animate({height: '75px'},100);
			$(this).addClass('language_open');
		}
	});

	// закрывать меню языков при клике вне меню
	$(document).mouseup(function(e){
	    var container = $('.language_hidden');
	    var container2 = $('.language');

	    if (!container.is(e.target) && container.has(e.target).length === 0 && !container2.is(e.target) && container2.has(e.target).length === 0) {
	        $('.language_hidden').animate({height: '0px'},100);
			$('.language').removeClass('language_open');
	    }
	});

/* ОСНОВНАЯ ЛОГИКА */
	// для десктоп-экранов применяем масштабирование и пишем некоторые параметры
	if (!is_touch_device()) {
		var pageSize = getPageSize();
        var windowWidth = pageSize[0];
        // var windowHeight = pageSize[3];

        if (windowWidth < 1800) {
        	var koef = parseFloat((windowWidth / 1920).toFixed(2)) + 0.01;

        	$('body').addClass('body_desktop_scale').css('transform', 'scale(' + koef + ')');

        	$('#popup_video, #popup_mobile_calls_messages, #popup_video_phone, #popup_video_phone_outgoing, #popup_search_processing, #popup_search_error, #popup_call_mobile, #popup_data_transfer, #popup_success, #popup_exit, #popup_end_video_question, .fancybox-container, #popup_video_phone_video, #popup_start_mission').css('height', ($('#main').outerHeight() * parseFloat((1920 / windowWidth).toFixed(2))) + 'px');
        }
	}

	// закрыть окно с ошибкой поиска
	$('.popup_search_error_bg, .popup_search_error_close').click(function(){
		$('#popup_search_error').fadeOut(200);

		if ($('#popup_search_error').hasClass('popup_search_error_minigame')) { // если зашли в тупик в миниигре
			$('#popup_search_error').removeClass('popup_search_error_minigame');

			// socket
			var message = {
				'op': 'minigamePositionsErrorAfterPopupClose',
				'parameters': {
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));

			// убрать звук с видео на странице, если был активен
			if ($('.content_minigame_video_mute').hasClass('content_minigame_video_mute_active')) {
				$('.content_minigame_video_mute').removeClass('content_minigame_video_mute_active');
				$('.content_minigame_video video').prop('muted', 1);

				minigameVideoMute = true;
			}

			// открыть видео и сразу запустить его
			playVideoByNotControls = true; // указываем, что запускалось через кнопку Play, а не через Controls
			// openFileVideoPopup(0, 'video/' + $('html').attr('lang') + '/minigame_lose.mp4', '', 'minigame_error_video', 'file');
			// playVideo('call');
			openFileVideoPopupCall(0, 'video/' + $('html').attr('lang') + '/minigame_lose.mp4', '', 'minigame_error_video', 'call_jane');
			playVideoCall();

			// Текущее к-во проиграшей
			var minigameLoseInCookie = WFCookie.get('wf-minigame-lose-team-' + $('#section_game').attr('data-team-id'));

			// Закрыть видео после 3 секунд. Третье видео и далее
			if (minigameLoseInCookie !== null) {
				if (parseInt(minigameLoseInCookie, 10) > 2) {
					setTimeout(function(){
						$('#popup_video_phone_video .popup_video_close').trigger('click');
					}, 3000);
				}
			}

			// Обновляем к-во проиграшей
			if (minigameLoseInCookie === null) {
				minigameLoseInCookie = 1;
			}

			minigameLoseInCookie++;

			var date = new Date(Date.now() + 24 * 365 * 60 * 60 * 1000); // Year
	    	var options = { expires: date };
	    	WFCookie.set('wf-minigame-lose-team-' + $('#section_game').attr('data-team-id'), minigameLoseInCookie, options);
		} else { // любой другой попап с ошибкой
			// socket
			var message = {
				'op': 'popupSearchErrorClose',
				'parameters': {
					'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
					'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
				}
	        };
	        sendMessageSocket(JSON.stringify(message));
	    }
	});

	// Close Call mobile window
	$('.popup_call_mobile_bg, .popup_call_mobile_close').click(function(){
		$('#popup_call_mobile').fadeOut(200);

		// socket
		var message = {
			'op': 'popupCallMobileClose',
			'parameters': {
				'user_id': $('#section_game').length ? $('#section_game').attr('data-user-id') : 0,
				'team_id': $('#section_game').length ? $('#section_game').attr('data-team-id') : 0
			}
        };
        sendMessageSocket(JSON.stringify(message));
	});

	// добавляем отдельный класс для body для touch-дисплеев
	if (is_touch_device()) {
		$('body').addClass('body_touch');
	}

	// Open video in blank tab
	$('.popup_end_video_question_btn_yes').click(function(){
		var win = window.open($('#popup_end_video_question').attr('video-url'), '_blank');

		if (win) {
		    // Browser has allowed it to be opened
		    win.focus();

		    $('#popup_end_video_question').css('display','none');
		} else {
		    // Browser has blocked it
		    alert('Please allow popups for this website');
		}
	});

	// Close popup end video question
	$('.popup_end_video_question_bg, .popup_end_video_question_btn_no').click(function(){
		$('#popup_end_video_question').fadeOut(200);
	});

	// Start page. Language dropdown
	$('div.section_join_game_language_item_active').click(function(){
		$(this).next().toggleClass('section_join_game_language_item_active');
		// $(this).next().slideToggle(200);
	});
});