<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/ajax/ajax_header.php');

if (isset($_POST['op'])) {
	$return = [];

	switch ($_POST['op']) {
		// загрузить конкретный экран (с переключателем табов) для calls
		case 'uploadTypeTabsCallsStep':
			$step = isset($_POST['step']) ? strip_tags(trim($_POST['step'])) : 'no_access';
			$lang_abbr = isset($_POST['lang_abbr']) ? strip_tags(trim($_POST['lang_abbr'])) : '';
			$lang_id = $lang->getLangIdByHtmlAttr($lang_abbr);

			$return = $function->uploadTypeTabsCallsStep($step, $lang_id, $userInfo['team_id']);

			print_r(json_encode($return));
		    break;

		// сохранить время просмотра видео в списке звонков команды
		case 'updateDatetimeCall':
			$call_id = isset($_POST['call_id']) ? (int) $_POST['call_id'] : 0;
			$lang_abbr = isset($_POST['lang_abbr']) ? strip_tags(trim($_POST['lang_abbr'])) : '';

			$lang_id = $lang->getLangIdByHtmlAttr($lang_abbr);
			$translation = $lang->getWordsByPage('game', $lang_id);

			if (!$function->isActiveVerifyCode($userInfo['team_id'])) {
				$return['error_verify'] = $translation['text4'];
			} else {
				$team_info = $function->teamInfo($userInfo['team_id']);
				if ($team_info) {
					// текущий список
					$active_calls = json_decode($team_info['active_calls'], true);

					// обновленный список
					$new_calls = [];

					$isset_call = false;
					foreach ($active_calls as $call) {
						if ($call['id'] == $call_id) {
							$new_calls[] = ['id' => $call['id'], 'datetime' => date('Y-m-d H:i:s')];
							$isset_call = true;
						} else {
							$new_calls[] = ['id' => $call['id'], 'datetime' => $call['datetime']];
						}
					}

					if (!$isset_call) {
						$new_calls[] = ['id' => $call_id, 'datetime' => date('Y-m-d H:i:s')];
					}

					// сохраняем обновленный список
					$sql = "UPDATE `teams` SET `active_calls` = {?} WHERE `id` = {?}";
					$db->query($sql, [json_encode($new_calls, JSON_UNESCAPED_UNICODE), $userInfo['team_id']]);

					$return['success'] = 'ok';
				} else {
					$return['error'] = $translation['text29'];
				}
			}

			print_r(json_encode($return));
		    break;

		// действие при звонку Jane
		case 'callJane':
			$lang_abbr = isset($_POST['lang_abbr']) ? strip_tags(trim($_POST['lang_abbr'])) : '';

			$lang_id = $lang->getLangIdByHtmlAttr($lang_abbr);
			$translation = $lang->getWordsByPage('game', $lang_id);

			if (!$function->isActiveVerifyCode($userInfo['team_id'])) {
				$return['error_verify'] = $translation['text4'];
			} else {
				$team_info = $function->teamInfo($userInfo['team_id']);
				if ($team_info) {
					// доступна ли уже кнопка на этом этапе игры
					if (!empty($team_info['view_call_jane_btn'])) {
						if ($team_info['calls_outgoing_id'] == 4) { // если больше недоступно
							$return['available'] = 'not available';

							/*// переводы для всех языков. Для синхронизации
							$sql = "SELECT `lang_abbr`, `id` FROM `langs` WHERE `status` = {?}";
							$langs = $db->select($sql, [1]);
							if ($langs) {
								$return['error_lang'] = [];

								foreach ($langs as $lang2) {
									$translation = $lang->getWordsByPage('game', $lang2['id']);

									$return['error_lang'][$lang2['lang_abbr']]['available'] = $translation['text298'];
								}
							}*/
						} else {
							$sql = "
								SELECT c.type, cd.video, cd.name, c.id, cd.video_with_path
			                    FROM calls c
			                    JOIN calls_description cd ON c.id = cd.call_id
			                    WHERE c.id = {?}
			                    AND cd.lang_id = {?}
							";
							$call_info = $db->selectRow($sql, [$team_info['calls_outgoing_id'], $lang_id]);
							if ($call_info) {
								$return['path'] = $call_info['video'];
								$return['video_with_path'] = $call_info['video_with_path'];
								// $return['type'] = $call_info['type'];
								$return['call_id'] = $call_info['id'];

								// если не Fake Call 3, то пишем в список осуществленных
								if ($call_info['id'] != 4) {
									// пишем звонок в список осуществленных
									$active_calls = json_decode($team_info['active_calls'], true);
									$active_calls[] = ['id' => $call_info['id'], 'datetime' => date('Y-m-d H:i:s')];

									$sql = "UPDATE `teams` SET `active_calls` = {?} WHERE `id` = {?}";
									$db->query($sql, [json_encode($active_calls, JSON_UNESCAPED_UNICODE), $userInfo['team_id']]);
								}

								// обновляем звонок, который будет идти следующим
								$call_id_after = $call_info['id'];

								if ($call_info['id'] == 2) {
									$call_id_after = 3;
								} elseif ($call_info['id'] == 3) {
									$call_id_after = 4;
								}

								$sql = "UPDATE `teams` SET `calls_outgoing_id` = {?} WHERE `id` = {?}";
								$db->query($sql, [$call_id_after, $userInfo['team_id']]);
							} else {
								$return['error'] = $translation['text29'];
							}
						}
					} else {
						$return['error'] = $translation['text29'];
					}
				} else {
					$return['error'] = $translation['text29'];
				}
			}

			print_r(json_encode($return));
		    break;
	}
}
