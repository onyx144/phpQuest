/* === АВТОРИЗАЦИЯ === */

/* ГЛАВНЫЕ ПЕРЕМЕННЫЕ */
	var teamNameErrLetters = ''; // текст ошибки при вводе названия команды, если мало символов
	var successfullVerifyCode = ''; // успешный код верификации

$(function() {
	// проверка кода игры + название команды
	$('.btn_control_system').click(function(){
		checkGameCode();
	});
	$('.game_code_field_wrapper_inner input[type="text"]').keyup(function(e){
		if (e.which == 13) {
			checkGameCode();
		}
	});

	function checkGameCode() {
		if ($('.btn_control_system').hasClass('btn_control_system_page_send_code')) {
			// проверка кода игры
			var code = $.trim($('.game_code_field_wrapper input[type="text"]').val());

			if (code == '') {
				$('.game_code_error_code').addClass('game_code_error_code_active');
			} else {
				var formData = new FormData();
		    	formData.append('op', 'verifyCode');
		    	formData.append('code', code);
		    	formData.append('lang_abbr', $('html').attr('lang'));

		    	$.ajax({
					url: '/ajax/ajax_login.php',
			        type: "POST",
			        dataType: "json",
			        cache: false,
			        contentType: false,
			        processData: false,
			        data: formData,
					success: function(json) {
						if (json.error) {
							$('.game_code_error_code').addClass('game_code_error_code_active');
						} else if (json.success_link) {
							window.location.href = json.success_link;
						} else if (json.success) {
							$('.game_code_error_code').removeClass('game_code_error_code_active').html(json.btn_error);
							teamNameErrLetters = json.btn_error;

							$('.game_code_field_wrapper input[type="text"]').val('').attr('placeholder', $('.game_code_field_wrapper input[type="text"]').attr('data-placeholder2'));

							$('.btn_control_system').removeClass('btn_control_system_page_send_code').addClass('btn_control_system_page_send_teamname').html(json.btn);

							successfullVerifyCode = code;

							$('.mission_control_title').html(json.success);
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {	
						console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
		    }
		} else if ($('.btn_control_system').hasClass('btn_control_system_page_send_teamname')) {
			// название команды
			var teamname = $.trim($('.game_code_field_wrapper input[type="text"]').val());

			if (teamname.length < 3) {
				$('.game_code_error_code').html(teamNameErrLetters).addClass('game_code_error_code_active');
			} else {
				var formData = new FormData();
		    	formData.append('op', 'teamname');
		    	formData.append('teamname', teamname);
		    	formData.append('lang_abbr', $('html').attr('lang'));
		    	formData.append('code', successfullVerifyCode);

		    	$.ajax({
					url: '/ajax/ajax_login.php',
			        type: "POST",
			        dataType: "json",
			        cache: false,
			        contentType: false,
			        processData: false,
			        data: formData,
					success: function(json) {
						if (json.error) {
							$('.game_code_error_code').html(json.error).addClass('game_code_error_code_active');
						} else if (json.success) {
							window.location.href = '/' + json.success;
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {	
						console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			}
		}
	}
});