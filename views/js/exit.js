/* === ВЫХОД === */

/* ОБЩИЕ ФУНКЦИИ */

$(function() {
	// открыть попап выхода
	$('.exit').click(function(){
		$('#popup_exit').css('display','block');
	});

	// закрыть попап выхода
	$('.popup_exit_bg, .popup_exit_btn_no').click(function(){
		$('#popup_exit').fadeOut(200);
	});

	// выход
	$('.popup_exit_btn_yes').click(function(){
		var formData = new FormData();
    	formData.append('op', 'exit');
    	formData.append('lang_abbr', $('html').attr('lang'));

    	$.ajax({
			url: '/ajax/ajax_exit.php',
	        type: "POST",
	        dataType: "json",
	        cache: false,
	        contentType: false,
	        processData: false,
	        data: formData,
			success: function(json) {
				window.location.href = json.success;
			},
			error: function(xhr, ajaxOptions, thrownError) {	
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});
});