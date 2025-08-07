<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/ajax/ajax_header.php');

if (isset($_POST['op'])) {
	$return = [];

	switch ($_POST['op']) {
		// загрузить актуальные данные по подсказкам
		case 'updateWindowHint':
			$lang_abbr = isset($_POST['lang_abbr']) ? strip_tags(trim($_POST['lang_abbr'])) : '';

			$lang_id = $lang->getLangIdByHtmlAttr($lang_abbr);
			$translation = $lang->getWordsByPage('game', $lang_id);

			if (!$function->isActiveVerifyCode($userInfo['team_id'])) {
				$return['error_verify'] = $translation['text4'];
			} else {
				$team_info = $function->teamInfo($userInfo['team_id']);
				if ($team_info) {
					$return = $function->getHintPageHints($userInfo['team_id'], $lang_id);
				} else {
					$return['error'] = $translation['text29'];
				}
			}

			print_r(json_encode($return));
		    break;

		// открыть подсказку
		case 'activateHint':
			$lang_abbr = isset($_POST['lang_abbr']) ? strip_tags(trim($_POST['lang_abbr'])) : '';
			$hint_id = isset($_POST['hint_id']) ? (int) $_POST['hint_id'] : 0;

			$lang_id = $lang->getLangIdByHtmlAttr($lang_abbr);
			$translation = $lang->getWordsByPage('game', $lang_id);

			if (!$function->isActiveVerifyCode($userInfo['team_id'])) {
				$return['error_verify'] = $translation['text4'];
			} else {
				$team_info = $function->teamInfo($userInfo['team_id']);
				if ($team_info) {
					// текущий список
					$cur_active_hints = json_decode($team_info['active_hints'], true);

					// добавляем подсказку в активные
					if (!in_array($hint_id, $cur_active_hints)) {
						$cur_active_hints[] = $hint_id;
					}

					// списываем очки со счета
					$sql = "SELECT `points` FROM `hints` WHERE `id` = {?}";
					$points = (int) $db->selectCell($sql, [$hint_id]);
					if ($points > 0) {
						$sql = "UPDATE teams SET score = score - " . $points . " WHERE id = '" . $userInfo['team_id'] . "'";
						$db->query($sql);
					}

					// сохраняем обновленный список подсказок
					$sql = "UPDATE `teams` SET `active_hints` = {?}, `hints_open` = {?} WHERE `id` = {?}";
					$db->query($sql, [json_encode($cur_active_hints, JSON_UNESCAPED_UNICODE), ((int) $team_info['hints_open'] + 1), $userInfo['team_id']]);

					// возвращаем обновленное состояние подсказок справа и слева
					$return = $function->getHintPageHints($userInfo['team_id'], $lang_id);

					// списываем очки со счета
					$sql = "SELECT `points` FROM `hints` WHERE `id` = {?}";
					$return['points'] = (int) $db->selectCell($sql, [$hint_id]);
				} else {
					$return['error'] = $translation['text29'];
				}
			}

			print_r(json_encode($return));
		    break;
	}
}
