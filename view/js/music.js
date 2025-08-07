/* === МУЗЫКА === */

/* ОБЩИЕ ФУНКЦИИ */
	// начать воспроизведение фоновой музыки и звуков
    function playMusic() {
    	$('.jp-play').trigger('click');
    	music = true;
    }

    // остановить воспроизведение фоновой музыки и звуков
    function stopMusic() {
    	$('.jp-pause').trigger('click');
    	music = false;
    }

$(function() {
	// музыкальный проигрыватель. Фоновая музыка
	if ($("#jquery_jplayer_1").length && $('#section_join_game').length == 0) {
	    $("#jquery_jplayer_1").jPlayer({
	        ready: function () {
	            $(this).jPlayer("setMedia", {
	                mp3: "/music/fon_music.mp3"
	            });
	        },
	        swfPath: "/plugins",
	        supplied: "mp3",
	        loop: true,
	        volume: 0.3
	    });
	}

    // вкл/выкл музыку
    $('.music_on').click(function(){
    	if ($(this).hasClass('music_active')) {
    		return false;
    	}

    	if (!music) {
    		playMusic();
    		$('.music_off').removeClass('music_active');
    		$(this).addClass('music_active');
    	}
    });
    $('.music_off').click(function(){
    	if ($(this).hasClass('music_active')) {
    		return false;
    	}

    	if (music) {
    		stopMusic();
    		$('.music_on').removeClass('music_active');
    		$(this).addClass('music_active');
    	}
    });
});