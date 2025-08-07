<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/ajax/ajax_header.php');

if (isset($_POST['op'])) {
	$return = [];

	switch ($_POST['op']) {
		// загрузить конкретный экран (с переключателем табов) для tools
		case 'uploadTypeTabsToolsStep':
			$step = isset($_POST['step']) ? strip_tags(trim($_POST['step'])) : 'no_access';
			$tool = !empty($_POST['tool']) ? strip_tags(trim($_POST['tool'])) : false;

			$lang_abbr = isset($_POST['lang_abbr']) ? strip_tags(trim($_POST['lang_abbr'])) : '';
			$lang_id = $lang->getLangIdByHtmlAttr($lang_abbr);

			// запоминаем последнюю открытую tools
			$sql = "UPDATE `teams` SET `last_tools` = {?} WHERE `id` = {?}";
			$db->query($sql, [$step, $userInfo['team_id']]);

			$return = $function->uploadTypeTabsToolsStep($step, $lang_id, $userInfo['team_id']);

			// обновляем к-во непрочитанных tools
			$return['qt_tools'] = 0;

			$team_info = $function->teamInfo($userInfo['team_id']);
			if ($team_info) {
				// названия tools, которые уже прочитаны/открыты
				$active_tools = json_decode($team_info['active_tools'], true);

				// названия tools, которые доступны для просмотра
				$list_tools = json_decode($team_info['list_tools'], true);

				if ($tool && $tool != 'false' && $tool != 'no_access') {
					$active_tools[] = $tool;
					$active_tools = array_unique($active_tools);
				}

				foreach ($list_tools as $tool) {
					if (!in_array($tool, $active_tools)) {
						$return['qt_tools']++;
					}
				}

				// обновляем значение в бд
				$sql = "UPDATE `teams` SET `active_tools` = {?} WHERE `id` = {?}";
				$db->query($sql, [json_encode($active_tools, JSON_UNESCAPED_UNICODE), $userInfo['team_id']]);
			}

			print_r(json_encode($return));
		    break;

		// Обновить к-во непрочитанных tools
		case 'updateDontOpenToolsQt':
			$return['success'] = 0;

			$team_info = $function->teamInfo($userInfo['team_id']);
			if ($team_info) {
				// названия tools, которые уже прочитаны/открыты
				$active_tools = json_decode($team_info['active_tools'], true);

				// названия tools, которые доступны для просмотра
				$list_tools = json_decode($team_info['list_tools'], true);

				foreach ($list_tools as $tool) {
					if (!in_array($tool, $active_tools)) {
						$return['success']++;
					}
				}
			}

			print_r(json_encode($return));
		    break;

		// добавляем tools к списку просмотренных
		case 'addToolsToActive':
			$tools = isset($_POST['tools']) ? strip_tags(trim($_POST['tools'])) : '';
			$lang_abbr = isset($_POST['lang_abbr']) ? strip_tags(trim($_POST['lang_abbr'])) : '';

			$lang_id = $lang->getLangIdByHtmlAttr($lang_abbr);
			$translation = $lang->getWordsByPage('game', $lang_id);

			$team_info = $function->teamInfo($userInfo['team_id']);
			if ($team_info && !empty($tools)) {
				// текущий список
				$active_tools = json_decode($team_info['active_tools'], true);

				// добавляем файл в прочитанные
				if (!in_array($tools, $active_tools)) {
					$active_tools[] = $tools;
				}

				/*// на всякий случай удаляем дубликаты
				$active_tools = array_unique($active_tools);*/

				// сохраняем обновленный список прочитанных файлов
				$sql = "UPDATE `teams` SET `active_tools` = {?} WHERE `id` = {?}";
				$db->query($sql, [json_encode($active_tools, JSON_UNESCAPED_UNICODE), $userInfo['team_id']]);

				$return['success'] = 'ok';
			} else {
				$return['error'] = $translation['text29'];
			}

			print_r(json_encode($return));
		    break;

		// tools - 3d building scan. Проверяем параметры сканирования
		case 'validateBuildingScanParameters':
			$degree = !empty($_POST['degree']) ? strip_tags(trim($_POST['degree'])) : false;
			$input1 = !empty($_POST['input1']) ? strip_tags(trim($_POST['input1'])) : 0;
			$input2 = !empty($_POST['input2']) ? strip_tags(trim($_POST['input2'])) : 0;
			$input3 = !empty($_POST['input3']) ? strip_tags(trim($_POST['input3'])) : 0;
			$input4 = !empty($_POST['input4']) ? strip_tags(trim($_POST['input4'])) : 0;
			$input5 = !empty($_POST['input5']) ? strip_tags(trim($_POST['input5'])) : 0;
			$dot_n = isset($_POST['tools_building_scan_address_dot_n']) ? (int) trim($_POST['tools_building_scan_address_dot_n']) : false;
			$dot_s = isset($_POST['tools_building_scan_address_dot_s']) ? (int) trim($_POST['tools_building_scan_address_dot_s']) : false;
			$dot_e = isset($_POST['tools_building_scan_address_dot_e']) ? (int) trim($_POST['tools_building_scan_address_dot_e']) : false;
			$dot_w = isset($_POST['tools_building_scan_address_dot_w']) ? (int) trim($_POST['tools_building_scan_address_dot_w']) : false;
			$checkbox_n = isset($_POST['tools_building_scan_address_checkbox_n']) ? (int) trim($_POST['tools_building_scan_address_checkbox_n']) : false;
			$checkbox_s = isset($_POST['tools_building_scan_address_checkbox_s']) ? (int) trim($_POST['tools_building_scan_address_checkbox_s']) : false;
			$checkbox_e = isset($_POST['tools_building_scan_address_checkbox_e']) ? (int) trim($_POST['tools_building_scan_address_checkbox_e']) : false;
			$checkbox_w = isset($_POST['tools_building_scan_address_checkbox_w']) ? (int) trim($_POST['tools_building_scan_address_checkbox_w']) : false;
			$lang_abbr = isset($_POST['lang_abbr']) ? strip_tags(trim($_POST['lang_abbr'])) : '';

			$lang_id = $lang->getLangIdByHtmlAttr($lang_abbr);

			if (
				($degree && ($degree == 94 || $degree == '94')) && 
				(!empty($input1) && ($input1 == 679 || $input1 == '679')) && 
				(!empty($input2) && ($input2 == 100 || $input2 == '100')) && 
				(!empty($input3) && $input3 == '071') && 
				(!empty($input4) && ($input4 == 533 || $input4 == '533')) && 
				(!empty($input5) && ($input5 == 882 || $input5 == '882')) && 
				($dot_n !== false && $dot_n == 4) && 
				($dot_s !== false && $dot_s == 1) && 
				($dot_e !== false && $dot_e == 0) && 
				($dot_w !== false && $dot_w == 3) && 
				($checkbox_n !== false && $checkbox_n == 1) && 
				($checkbox_s !== false && $checkbox_s == 0) && 
				($checkbox_e !== false && $checkbox_e == 0) && 
				($checkbox_w !== false && $checkbox_w == 1)
			) {
				$return['success'] = 'ok';

				// обновляем список подсказок
				$team_info = $function->teamInfo($userInfo['team_id']);
				if ($team_info) {
					// список открытых
					$active_hints = [];

					// список доступных
					$list_hints = [];

					$hints_by_step = $function->getHintsByStep('secret_office', $lang_id);
					if ($hints_by_step) {
						foreach ($hints_by_step as $hint) {
							$list_hints[] = $hint['id'];
						}
					}

					// сохраняем обновленный список подсказок + запоминаем новый открытый tools + теперь всегда доступен новый tools secret office
					$sql = "UPDATE `teams` SET `active_hints` = {?}, `list_hints` = {?}, `list_hints_title_lang_var` = {?}, `list_hints_text_lang_var` = {?}, `last_tools` = {?}, `tools_secret_office_access` = {?} WHERE `id` = {?}";
					$db->query($sql, [json_encode($active_hints, JSON_UNESCAPED_UNICODE), json_encode($list_hints, JSON_UNESCAPED_UNICODE), 'text44', 'text45', 'secret_office', 1, $userInfo['team_id']]);
				}
			} else {
				// переводы для всех языков. Для синхронизации
				$sql = "SELECT `lang_abbr`, `id` FROM `langs` WHERE `status` = {?}";
				$langs = $db->select($sql, [1]);
				if ($langs) {
					$return['error_lang'] = [];

					foreach ($langs as $lang2) {
						$translation = $lang->getWordsByPage('game', $lang2['id']);

						$return['error_lang'][$lang2['lang_abbr']]['error_input'] = $translation['text191'];
						$return['error_lang'][$lang2['lang_abbr']]['error_text'] = $translation['text247'];
					}
				}
			}

			print_r(json_encode($return));
		    break;
	}
}
