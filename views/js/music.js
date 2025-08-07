/* === МУЗЫКА === */


/* ОБЩИЕ ФУНКЦИИ */
    function updateMusicUI(state) {
        $('.music_on, .music_off').removeClass('music_active');
        $(`.music_${state}`).addClass('music_active');
    }

    // начать воспроизведение фоновой музыки и звуков
    function playMusic() {
        localStorage.setItem('musicState', 'on');
        document.cookie = "musicState=on; path=/";
        updateMusicUI('on');
        $("#jquery_jplayer_1").jPlayer("play");
        // Воспроизводим фоновую музыку
        if (window.bgMusic) {
            window.bgMusic.play().catch(e => console.log('Playback failed:', e));
        } else {
            window.bgMusic = new Audio('/zombie/music/fon_music.mp3');
            window.bgMusic.loop = true;
            window.bgMusic.volume = 0.3;
            window.bgMusic.play().catch(e => console.log('Playback failed:', e));
        }
    }

    // остановить воспроизведение фоновой музыки и звуков
    function stopMusic() {
        localStorage.setItem('musicState', 'off');
        document.cookie = "musicState=off; path=/";
        updateMusicUI('off');
        $("#jquery_jplayer_1").jPlayer("pause");
        // Останавливаем фоновую музыку
        if (window.bgMusic) {
            window.bgMusic.pause();
            window.bgMusic.currentTime = 0;
        }
    }

$(function() {
	// Инициализация состояния музыки при загрузке страницы
	const musicState = localStorage.getItem('musicState') || 'off';
	//console.log('musicState' , musicState);
    //updateMusicUI(musicState); // Только обновляем UI, не воспроизводим

	// музыкальный проигрыватель. Фоновая музыка
	if ($("#jquery_jplayer_1").length && $('#section_join_game').length == 0) {
	    $("#jquery_jplayer_1").jPlayer({
	        ready: function () {
	            $(this).jPlayer("setMedia", {
	                mp3: "/zombie/music/fon_music.mp3"
	            });
	        },
	        swfPath: "/plugins",
	        supplied: "mp3",
	        loop: true,
	        volume: 0.3
	    });
	}

    // Handle music toggle clicks
    $('.music_on, .music_off').on('click', function() {
        const newState = $(this).data('music-state');
        if (newState === 'on') {
            playMusic();
        } else {
            stopMusic();
        }
    });
});