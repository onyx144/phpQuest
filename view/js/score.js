/* === ОЧКИ КОМАНДЫ === */

/* ОБЩИЕ ФУНКЦИИ */
	// скрыть основное поле с очками
	function hiddenMainScore() {
		$('.score_wrapper').css('display', 'none');
	}

	// показать основное поле с очками
	function viewMainScore() {
		$('.score_wrapper').css('display', 'block');
	}

	// проскроллить блок с очками к нужному значению при загрузке странице
	function loadScoreActual(curScore) {
		// главный экран
		loadScoreActualMain(curScore);

		// экран с подсказками
		loadScoreActualHints(curScore);
	}

	// проскроллить блок с очками к нужному значению при загрузке странице - главный экран
	function loadScoreActualMain(curScore) {
		// var curScoreMain = parseInt($('.score .score_active').html(), 10);

		// // обновляем только, если значения не совпадают
		// if (
		// 	curScore != curScoreMain || 
		// 	($('.content_game').css('display') == 'block' && !updatedScoreMain) || 
		// 	($('.content_minigame').css('display') == 'block' && !updatedScoreMain) || 
		// 	($('.content_interpol').css('display') == 'block' && !updatedScoreMain)
		// ) {
			if ($('.score .score_' + curScore).length) {
				$('.score .score_active').removeClass('score_active');
				$('.score .score_' + curScore).addClass('score_active');

				// при scale position().top считает неверно. Поэтому доп манипуляции
				if (!is_touch_device()) {
					var pageSize = getPageSize();
	        		var windowWidth = pageSize[2];
	        		if (windowWidth < 1800) {
	        			$('body').removeClass('body_desktop_scale').css('transform', 'scale(1)');

	        			var scrollPos = $('.score .score_active').position().top - 22;
						$('.score').scrollTop($('.score').scrollTop() + scrollPos);

						var pageSize = getPageSize();
	        			var windowWidth = pageSize[0];

	        			var koef = parseFloat((windowWidth / 1920).toFixed(2)) + 0.01;

	        			$('body').addClass('body_desktop_scale').css('transform', 'scale(' + koef + ')');
	        		} else {
	        			var scrollPos = $('.score .score_active').position().top - 22;
						$('.score').scrollTop($('.score').scrollTop() + scrollPos);
	        		}
				} else {
					var scrollPos = $('.score .score_active').position().top - 22;
					$('.score').scrollTop($('.score').scrollTop() + scrollPos);
				}
			}

			// updatedScoreMain = true;
		// }
	}

	// проскроллить блок с очками к нужному значению при загрузке странице - экран с подсказками
	function loadScoreActualHints(curScore) {
		// var curScoreHints = parseInt($('.active_hints_score_value .score_active').html(), 10);

		// // обновляем только, если значения не совпадают
		// if (curScore != curScoreHints || ($('.content_hints').css('display') == 'block' && !updatedScoreHints)) {
			if ($('.active_hints_score_value .score_' + curScore).length) {
				$('.active_hints_score_value .score_active').removeClass('score_active');
				$('.active_hints_score_value .score_' + curScore).addClass('score_active');

				// при scale position().top считает неверно. Поэтому доп манипуляции
				if (!is_touch_device()) {
					var pageSize = getPageSize();
	        		var windowWidth = pageSize[2];
	        		if (windowWidth < 1800) {
	        			$('body').removeClass('body_desktop_scale').css('transform', 'scale(1)');

	        			var scrollPos = $('.active_hints_score_value .score_active').position().top;
						$('.active_hints_score_value').scrollTop($('.active_hints_score_value').scrollTop() + scrollPos);

						var pageSize = getPageSize();
	        			var windowWidth = pageSize[0];

	        			var koef = parseFloat((windowWidth / 1920).toFixed(2)) + 0.01;

	        			$('body').addClass('body_desktop_scale').css('transform', 'scale(' + koef + ')');
	        		} else {
	        			var scrollPos = $('.active_hints_score_value .score_active').position().top;
						$('.active_hints_score_value').scrollTop($('.active_hints_score_value').scrollTop() + scrollPos);
	        		}
				} else {
					var scrollPos = $('.active_hints_score_value .score_active').position().top;
					$('.active_hints_score_value').scrollTop($('.active_hints_score_value').scrollTop() + scrollPos);
				}
			}

		// 	updatedScoreHints = true;
		// }
	}

	// увеличить к-во очков
	function incrementScore(newScore, page, curScore) {
		var newScoreAnimation = curScore; // доп переменная для анимации
		if (newScore > curScore) {
			if (page == 'hints') {
				// экран с подсказками
				$('.active_hints_score_value .score_active').removeClass('score_active');
				$('.active_hints_score_value .score_' + newScore).addClass('score_active');

				// при scale position().top считает неверно. Поэтому доп манипуляции
				if (!is_touch_device()) {
					var pageSize = getPageSize();
	        		var windowWidth = pageSize[2];
	        		if (windowWidth < 1800) {
	        			$('body').removeClass('body_desktop_scale').css('transform', 'scale(1)');

	        			var scrollPos = $('.active_hints_score_value .score_' + newScore).position().top;
						$('.active_hints_score_value').animate({
							scrollTop: $('.active_hints_score_value').scrollTop() + scrollPos
						}, incrementScoreDuration);

						var pageSize = getPageSize();
	        			var windowWidth = pageSize[0];

	        			var koef = parseFloat((windowWidth / 1920).toFixed(2)) + 0.01;

	        			$('body').addClass('body_desktop_scale').css('transform', 'scale(' + koef + ')');
	        		} else {
	        			var scrollPos = $('.active_hints_score_value .score_' + newScore).position().top;
						$('.active_hints_score_value').animate({
							scrollTop: $('.active_hints_score_value').scrollTop() + scrollPos
						}, incrementScoreDuration);
	        		}
				} else {
					var scrollPos = $('.active_hints_score_value .score_' + newScore).position().top;
					$('.active_hints_score_value').animate({
						scrollTop: $('.active_hints_score_value').scrollTop() + scrollPos
					}, incrementScoreDuration);
				}
			} else {
				// основной экран
				$('.score .score_active').removeClass('score_active');
				$('.score .score_' + newScore).addClass('score_active');

				// при scale position().top считает неверно. Поэтому доп манипуляции
				if (!is_touch_device()) {
					var pageSize = getPageSize();
	        		var windowWidth = pageSize[2];
	        		if (windowWidth < 1800) {
	        			$('body').removeClass('body_desktop_scale').css('transform', 'scale(1)');

	        			var scrollPos = $('.score .score_' + newScore).position().top - 22;

	        			$('.score').animate({
							scrollTop: $('.score').scrollTop() + scrollPos
						}, incrementScoreDuration);

						var pageSize = getPageSize();
	        			var windowWidth = pageSize[0];

	        			var koef = parseFloat((windowWidth / 1920).toFixed(2)) + 0.01;

	        			$('body').addClass('body_desktop_scale').css('transform', 'scale(' + koef + ')');
	        		} else {
	        			var scrollPos = $('.score .score_' + newScore).position().top - 22;
						$('.score').animate({
							scrollTop: $('.score').scrollTop() + scrollPos
						}, incrementScoreDuration);
	        		}
				} else {
					var scrollPos = $('.score .score_' + newScore).position().top - 22;
					$('.score').animate({
						scrollTop: $('.score').scrollTop() + scrollPos
					}, incrementScoreDuration);
				}
			}

			updateScoreDb(newScore);

			// звук начисления очков
			scoreAudio = new Audio;
			scoreAudio.src = '/music/score.mp3';
			// scoreAudio.play();

			// Autoplay
			var promise = scoreAudio.play();

			if (promise !== undefined) {
				promise.then(_ => {
					// console.log('autoplay');
					setTimeout(function(){
						scoreAudio.pause();

						// music_before = false;
					}, incrementScoreDuration);
				}).catch(error => {
					// console.log('autoplay ERR');
				});
			}

			/*setTimeout(function(){
				scoreAudio.pause();

				// music_before = false;
			}, incrementScoreDuration);*/
		}
	}

	// увеличить к-во очков. Без записи в бд
	function incrementScoreWithoutSaveDb(newScore, page, curScore) {
		var newScoreAnimation = curScore; // доп переменная для анимации
		if (newScore > curScore) {
			if (page == 'hints') {
				// экран с подсказками
				$('.active_hints_score_value .score_active').removeClass('score_active');
				$('.active_hints_score_value .score_' + newScore).addClass('score_active');

				// при scale position().top считает неверно. Поэтому доп манипуляции
				if (!is_touch_device()) {
					var pageSize = getPageSize();
	        		var windowWidth = pageSize[2];
	        		if (windowWidth < 1800) {
	        			$('body').removeClass('body_desktop_scale').css('transform', 'scale(1)');

	        			var scrollPos = $('.active_hints_score_value .score_' + newScore).position().top;

						$('.active_hints_score_value').animate({
							scrollTop: $('.active_hints_score_value').scrollTop() + scrollPos
						}, incrementScoreDuration);

						var pageSize = getPageSize();
	        			var windowWidth = pageSize[0];

	        			var koef = parseFloat((windowWidth / 1920).toFixed(2)) + 0.01;

	        			$('body').addClass('body_desktop_scale').css('transform', 'scale(' + koef + ')');
	        		} else {
	        			var scrollPos = $('.active_hints_score_value .score_' + newScore).position().top;
						$('.active_hints_score_value').animate({
							scrollTop: $('.active_hints_score_value').scrollTop() + scrollPos
						}, incrementScoreDuration);
	        		}
				} else {
					var scrollPos = $('.active_hints_score_value .score_' + newScore).position().top;
					$('.active_hints_score_value').animate({
						scrollTop: $('.active_hints_score_value').scrollTop() + scrollPos
					}, incrementScoreDuration);
				}
			} else {
				// основной экран
				$('.score .score_active').removeClass('score_active');
				$('.score .score_' + newScore).addClass('score_active');

				// при scale position().top считает неверно. Поэтому доп манипуляции
				if (!is_touch_device()) {
					var pageSize = getPageSize();
	        		var windowWidth = pageSize[2];
	        		if (windowWidth < 1800) {
	        			$('body').removeClass('body_desktop_scale').css('transform', 'scale(1)');

	        			var scrollPos = $('.score .score_' + newScore).position().top - 22;

						$('.score').animate({
							scrollTop: $('.score').scrollTop() + scrollPos
						}, incrementScoreDuration);

						var pageSize = getPageSize();
	        			var windowWidth = pageSize[0];

	        			var koef = parseFloat((windowWidth / 1920).toFixed(2)) + 0.01;

	        			$('body').addClass('body_desktop_scale').css('transform', 'scale(' + koef + ')');
	        		} else {
	        			var scrollPos = $('.score .score_' + newScore).position().top - 22;
						$('.score').animate({
							scrollTop: $('.score').scrollTop() + scrollPos
						}, incrementScoreDuration);
	        		}
				} else {
					var scrollPos = $('.score .score_' + newScore).position().top - 22;
					$('.score').animate({
						scrollTop: $('.score').scrollTop() + scrollPos
					}, incrementScoreDuration);
				}
			}

			// звук начисления очков
			scoreAudio = new Audio;
			scoreAudio.src = '/music/score.mp3';
			// scoreAudio.play();

			// Autoplay
			var promise = scoreAudio.play();

			if (promise !== undefined) {
				promise.then(_ => {
					// console.log('autoplay');
					setTimeout(function(){
						scoreAudio.pause();

						// music_before = false;
					}, incrementScoreDuration);
				}).catch(error => {
					// console.log('autoplay ERR');
				});
			}

			/*setTimeout(function(){
				scoreAudio.pause();

				// music_before = false;
			}, incrementScoreDuration);*/
		}
	}

	// уменьшить к-во очков
	function decrementScore(newScore, page, curScore) {
		if (newScore < curScore) {
			if (page == 'hints') {
				// экран с подсказками
				$('.active_hints_score_value .score_active').removeClass('score_active');
				$('.active_hints_score_value .score_' + newScore).addClass('score_active');

				// при scale position().top считает неверно. Поэтому доп манипуляции
				if (!is_touch_device()) {
					var pageSize = getPageSize();
	        		var windowWidth = pageSize[2];
	        		if (windowWidth < 1800) {
	        			$('body').removeClass('body_desktop_scale').css('transform', 'scale(1)');

	        			var scrollPos = $('.active_hints_score_value .score_' + newScore).position().top;

						$('.active_hints_score_value').animate({
							scrollTop: $('.active_hints_score_value').scrollTop() + scrollPos
						}, incrementScoreDuration / 2);

						var pageSize = getPageSize();
	        			var windowWidth = pageSize[0];

	        			var koef = parseFloat((windowWidth / 1920).toFixed(2)) + 0.01;

	        			$('body').addClass('body_desktop_scale').css('transform', 'scale(' + koef + ')');
	        		} else {
	        			var scrollPos = $('.active_hints_score_value .score_' + newScore).position().top;
						$('.active_hints_score_value').animate({
							scrollTop: $('.active_hints_score_value').scrollTop() + scrollPos
						}, incrementScoreDuration / 2);
	        		}
				} else {
					var scrollPos = $('.active_hints_score_value .score_' + newScore).position().top;
					$('.active_hints_score_value').animate({
						scrollTop: $('.active_hints_score_value').scrollTop() + scrollPos
					}, incrementScoreDuration / 2);
				}
			} else {
				// основной экран
				$('.score .score_active').removeClass('score_active');
				$('.score .score_' + newScore).addClass('score_active');

				// при scale position().top считает неверно. Поэтому доп манипуляции
				if (!is_touch_device()) {
					var pageSize = getPageSize();
	        		var windowWidth = pageSize[2];
	        		if (windowWidth < 1800) {
	        			$('body').removeClass('body_desktop_scale').css('transform', 'scale(1)');

	        			var scrollPos = $('.score .score_' + newScore).position().top - 22;

						$('.score').animate({
							scrollTop: $('.score').scrollTop() + scrollPos
						}, incrementScoreDuration / 2);

						var pageSize = getPageSize();
	        			var windowWidth = pageSize[0];

	        			var koef = parseFloat((windowWidth / 1920).toFixed(2)) + 0.01;

	        			$('body').addClass('body_desktop_scale').css('transform', 'scale(' + koef + ')');
	        		} else {
	        			var scrollPos = $('.score .score_' + newScore).position().top - 22;
						$('.score').animate({
							scrollTop: $('.score').scrollTop() + scrollPos
						}, incrementScoreDuration / 2);
	        		}
				} else {
					var scrollPos = $('.score .score_' + newScore).position().top - 22;
					$('.score').animate({
						scrollTop: $('.score').scrollTop() + scrollPos
					}, incrementScoreDuration / 2);
				}
			}

			updateScoreDb(newScore);
		}
	}

	// уменьшить к-во очков. Без записи в бд
	function decrementScoreWithoutSaveDb(newScore, page, curScore) {
		if (newScore < curScore) {
			if (page == 'hints') {
				// экран с подсказками
				$('.active_hints_score_value .score_active').removeClass('score_active');
				$('.active_hints_score_value .score_' + newScore).addClass('score_active');

				// при scale position().top считает неверно. Поэтому доп манипуляции
				if (!is_touch_device()) {
					var pageSize = getPageSize();
	        		var windowWidth = pageSize[2];
	        		if (windowWidth < 1800) {
	        			$('body').removeClass('body_desktop_scale').css('transform', 'scale(1)');

	        			var scrollPos = $('.active_hints_score_value .score_' + newScore).position().top;

						$('.active_hints_score_value').animate({
							scrollTop: $('.active_hints_score_value').scrollTop() + scrollPos
						}, incrementScoreDuration / 2);

						var pageSize = getPageSize();
	        			var windowWidth = pageSize[0];

	        			var koef = parseFloat((windowWidth / 1920).toFixed(2)) + 0.01;

	        			$('body').addClass('body_desktop_scale').css('transform', 'scale(' + koef + ')');
	        		} else {
	        			var scrollPos = $('.active_hints_score_value .score_' + newScore).position().top;
						$('.active_hints_score_value').animate({
							scrollTop: $('.active_hints_score_value').scrollTop() + scrollPos
						}, incrementScoreDuration / 2);
	        		}
				} else {
					var scrollPos = $('.active_hints_score_value .score_' + newScore).position().top;
					$('.active_hints_score_value').animate({
						scrollTop: $('.active_hints_score_value').scrollTop() + scrollPos
					}, incrementScoreDuration / 2);
				}
			} else {
				// основной экран
				$('.score .score_active').removeClass('score_active');
				$('.score .score_' + newScore).addClass('score_active');

				// при scale position().top считает неверно. Поэтому доп манипуляции
				if (!is_touch_device()) {
					var pageSize = getPageSize();
	        		var windowWidth = pageSize[2];
	        		if (windowWidth < 1800) {
	        			$('body').removeClass('body_desktop_scale').css('transform', 'scale(1)');

	        			var scrollPos = $('.score .score_' + newScore).position().top - 22;

						$('.score').animate({
							scrollTop: $('.score').scrollTop() + scrollPos
						}, incrementScoreDuration / 2);

						var pageSize = getPageSize();
	        			var windowWidth = pageSize[0];

	        			var koef = parseFloat((windowWidth / 1920).toFixed(2)) + 0.01;

	        			$('body').addClass('body_desktop_scale').css('transform', 'scale(' + koef + ')');
	        		} else {
	        			var scrollPos = $('.score .score_' + newScore).position().top - 22;
						$('.score').animate({
							scrollTop: $('.score').scrollTop() + scrollPos
						}, incrementScoreDuration / 2);
	        		}
				} else {
					var scrollPos = $('.score .score_' + newScore).position().top - 22;
					$('.score').animate({
						scrollTop: $('.score').scrollTop() + scrollPos
					}, incrementScoreDuration / 2);
				}
			}
		}
	}

	// сохранить изменение очков в бд
	function updateScoreDb(score) {
		var formData = new FormData();
    	formData.append('op', 'updateScore');
    	formData.append('score', score);

    	$.ajax({
			url: '/ajax/ajax_score.php',
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
});