<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/ajax/ajax_header.php');

if (isset($_POST['op'])) {
	$return = [];

	switch ($_POST['op']) {
		// загрузить конкретный экран (с переключателем табов) для files
		case 'uploadTypeTabsFilesStep':
			// $step = isset($_POST['step']) ? strip_tags(trim($_POST['step'])) : 'mission_briefing';
			$lang_abbr = isset($_POST['lang_abbr']) ? strip_tags(trim($_POST['lang_abbr'])) : '';
			$lang_id = $lang->getLangIdByHtmlAttr($lang_abbr);

			// $return = $function->uploadTypeTabsFilesStep($step, $lang_id, $userInfo['team_id']);
			$return = $function->uploadFilesActualForView($userInfo['team_id'], $lang_id);

			print_r(json_encode($return));
		    break;

		// Обновить к-во непрочитанных файлов
		case 'updateDontOpenFilesQt':
			$return['success'] = 0;

			$team_info = $function->teamInfo($userInfo['team_id']);
			if ($team_info) {
				// идентификаторы файлов, которые уже прочитаны/открыты
				$active_files = json_decode($team_info['active_files'], true);

				// идентификаторы файлов, которые доступны для просмотра
				$list_files = json_decode($team_info['list_files'], true);

				foreach ($list_files as $file_id) {
					if (!in_array($file_id, $active_files)) {
						$return['success']++;
					}
				}
			}

			print_r(json_encode($return));
		    break;

		// добавляем файл к списку просмотренных
		case 'addFileToActive':
			$file_id = isset($_POST['file_id']) ? (int) $_POST['file_id'] : 0;
			$lang_abbr = isset($_POST['lang_abbr']) ? strip_tags(trim($_POST['lang_abbr'])) : '';

			$lang_id = $lang->getLangIdByHtmlAttr($lang_abbr);
			$translation = $lang->getWordsByPage('game', $lang_id);

			$team_info = $function->teamInfo($userInfo['team_id']);
			if ($team_info && !empty($file_id)) {
				// текущий список
				$active_files = json_decode($team_info['active_files'], true);

				// добавляем файл в прочитанные
				if (!in_array($file_id, $active_files)) {
					$active_files[] = $file_id;
				}

				/*// на всякий случай удаляем дубликаты
				$active_files = array_unique($active_files);*/

				// сохраняем обновленный список прочитанных файлов
				$sql = "UPDATE `teams` SET `active_files` = {?} WHERE `id` = {?}";
				$db->query($sql, [json_encode($active_files, JSON_UNESCAPED_UNICODE), $userInfo['team_id']]);

				$return['success'] = 'ok';
			} else {
				$return['error'] = $translation['text29'];
			}

			print_r(json_encode($return));
		    break;
	}
}
