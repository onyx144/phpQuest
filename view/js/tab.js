/* === ГЛАВНОЕ ОКНО ИГРЫ - ЦЕНТРАЛЬНЫЙ БЛОК ИНФОРМАЦИИ - ПЕРЕКЛЮЧЕНИЕ ТАБОВ === */

/* ОБЩИЕ ФУНКЦИИ */
	// запоминаем открытый тип табов
	function setTeamLastTypeTabs(tab) {
		var formData = new FormData();
		formData.append('op', 'saveTeamTextField');
		formData.append('field', 'last_type_tabs');
		formData.append('val', tab);

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

	// запоминаем какие-то значение доп значения со всех табов
	function setTeamTabsTextInfo(field, val) {
		var formData = new FormData();
		formData.append('op', 'saveTeamTextField');
		formData.append('field', field);
		formData.append('val', val);

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
	function setTeamTabsTextInfoCallback(field, val) {
		var formData = new FormData();
		formData.append('op', 'saveTeamTextField');
		formData.append('field', field);
		formData.append('val', val);

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

	// скрыть все типы табов
	function hiddenAllTypeTabs() {
		$('.dashboard_tabs').removeClass('dashboard_tabs_active');
		$('.dashboard_item').removeClass('dashboard_item_active');
	}

$(function() {
	// переключение типов табов
	$('.dashboard_list .dashboard_item').click(function(){
		/*if ($(this).hasClass('dashboard_item_active')) {
			return false;
		}

		viewMainPreloader(defaultLoaderLoadingText);

		hiddenAllTypeTabs();

		var newTypeDashboard = $(this).attr('data-dashboard');
		if (newTypeDashboard == 'dashboard') {
			openTypeTabsDashboard(true);
		} else if (newTypeDashboard == 'calls') {
			openTypeTabsCalls(true);
		} else if (newTypeDashboard == 'files') {
			openTypeTabsFiles(true);
		} else if (newTypeDashboard == 'databases') {
			openTypeTabsDatabases(true);
		} else if (newTypeDashboard == 'tools') {
			openTypeTabsTools(true);
		}

		hiddenMainPreloader();*/

		if ($(this).hasClass('dashboard_item_active')) { // возвращаемся на первоначальную позицию таба
			var newTypeDashboard = $(this).attr('data-dashboard');

			if (newTypeDashboard == 'databases') {
				openTypeTabsDatabasesMain(false); // Не синхронизируем переключение табов
			} else if (newTypeDashboard == 'tools') {
				openTypeTabsToolsMain(false); // Не синхронизируем переключение табов
			} else {
				return false;
			}
		} else {
			viewMainPreloader(defaultLoaderLoadingText);

			hiddenAllTypeTabs();

			var newTypeDashboard = $(this).attr('data-dashboard');
			if (newTypeDashboard == 'dashboard') {
				openTypeTabsDashboard(false); // Не синхронизируем переключение табов
			} else if (newTypeDashboard == 'calls') {
				openTypeTabsCalls(false); // Не синхронизируем переключение табов
			} else if (newTypeDashboard == 'files') {
				openTypeTabsFiles(false); // Не синхронизируем переключение табов
			} else if (newTypeDashboard == 'databases') {
				// openTypeTabsDatabases(true);
				openTypeTabsDatabasesMain(false); // Не синхронизируем переключение табов
			} else if (newTypeDashboard == 'tools') {
				// openTypeTabsTools(true);
				openTypeTabsToolsMain(false); // Не синхронизируем переключение табов
			}

			hiddenMainPreloader();
		}
	});
});