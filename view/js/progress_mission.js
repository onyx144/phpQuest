/* === ПРОГРЕСС ПРОХОЖДЕНИЯ ИГРЫ === */

/* ОБЩИЕ ФУНКЦИИ */
	// увеличить к-во процентов
	function incrementProgressMission(plusProgress) {
		var missionProgressText = $('.mission_progress_text').html();
		var missionProgressArray = missionProgressText.split('-');
		var currentMissionProgress = parseInt(missionProgressArray[0], 10);
		var newProgress = currentMissionProgress + plusProgress;

		$('.mission_progress_text').html(newProgress + ' - 100');
		$('.mission_progress_percent').css('width', newProgress + '%');

		$('.mission_progress_percent').removeClass('mission_progress_percent_yellow').removeClass('mission_progress_percent_green_light').removeClass('mission_progress_percent_green_dark').removeClass('mission_progress_percent_full');

		if (newProgress >= 35 && newProgress <= 69) {
			$('.mission_progress_percent').addClass('mission_progress_percent_yellow');
		} else if (newProgress >= 70 && newProgress <= 99) {
			$('.mission_progress_percent').addClass('mission_progress_percent_green_light');
		} else if (newProgress == 100) {
			$('.mission_progress_percent').addClass('mission_progress_percent_green_dark').addClass('mission_progress_percent_full');
		}

		var formData = new FormData();
    	formData.append('op', 'updateProgressMission');
    	formData.append('progress_mission', newProgress);

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

	// увеличить к-во процентов. Без сохранения в бд
	function incrementProgressMissionWithoutSaveDb(plusProgress) {
		var missionProgressText = $('.mission_progress_text').html();
		var missionProgressArray = missionProgressText.split('-');
		var currentMissionProgress = parseInt(missionProgressArray[0], 10);
		var newProgress = currentMissionProgress + plusProgress;

		$('.mission_progress_text').html(newProgress + ' - 100');
		$('.mission_progress_percent').css('width', newProgress + '%');

		$('.mission_progress_percent').removeClass('mission_progress_percent_yellow').removeClass('mission_progress_percent_green_light').removeClass('mission_progress_percent_green_dark').removeClass('mission_progress_percent_full');

		if (newProgress >= 35 && newProgress <= 69) {
			$('.mission_progress_percent').addClass('mission_progress_percent_yellow');
		} else if (newProgress >= 70 && newProgress <= 99) {
			$('.mission_progress_percent').addClass('mission_progress_percent_green_light');
		} else if (newProgress == 100) {
			$('.mission_progress_percent').addClass('mission_progress_percent_green_dark').addClass('mission_progress_percent_full');
		}
	}

$(function() {
});