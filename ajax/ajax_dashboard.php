<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/ajax/ajax_header.php');

if (isset($_POST['op'])) {
	$return = [];

	switch ($_POST['op']) {
		// загрузить конкретный экран (с переключателем табов) для dashboard
		case 'uploadTypeTabsDashboardStep':
			$step = isset($_POST['step']) ? strip_tags(trim($_POST['step'])) : 'accept_new_mission';
			$lang_abbr = isset($_POST['lang_abbr']) ? strip_tags(trim($_POST['lang_abbr'])) : '';
			$lang_id = $lang->getLangIdByHtmlAttr($lang_abbr);

			$return = $function->uploadTypeTabsDashboardStep($step, $lang_id, $userInfo['team_id']);

			print_r(json_encode($return));
		    break;

		// лоадер dashboard при переключении этапа
		case 'getDashboardTabsLoading':
			$lang_abbr = isset($_POST['lang_abbr']) ? strip_tags(trim($_POST['lang_abbr'])) : '';
			$lang_id = $lang->getLangIdByHtmlAttr($lang_abbr);

			$return['success'] = 'ok';
			$return['loading_html'] = $function->getDashboardTabsLoadingHtml($lang_id);

			print_r(json_encode($return));
		    break;

		// dashboard - company investigate. Проверка правильности ввода данных
		case 'validateDashboardCompanyInvestigateSearch':
			$company_name = !empty($_POST['company_name']) ? strip_tags(trim($_POST['company_name'])) : false;
			$lang_abbr = isset($_POST['lang_abbr']) ? strip_tags(trim($_POST['lang_abbr'])) : 'en';

			$lang_id = $lang->getLangIdByHtmlAttr($lang_abbr);
			$translation = $lang->getWordsByPage('game', $lang_id);

			if (!empty($company_name)) {
				$check_company_name = mb_strtolower($company_name, 'UTF-8');
				$check_company_name = str_replace('«', '', $check_company_name);
				$check_company_name = str_replace('»', '', $check_company_name);
				$check_company_name = str_replace('"', '', $check_company_name);
				$check_company_name = preg_replace('/\s+/u', ' ', trim($check_company_name));

				if ($check_company_name == 'peace group') {
					$return['success'] = 'ok';

					// переводы для всех языков. Для синхронизации
					$sql = "SELECT `lang_abbr`, `id` FROM `langs` WHERE `status` = {?}";
					$langs = $db->select($sql, [1]);

					// видео Jane/Elison для inline-попапа телефона (call_id=5, путь из БД)
					$return['video_src'] = [];
					if ($langs) {
						foreach ($langs as $lang2) {
							$call_info = $function->getCallVideoInfo(5, (int) $lang2['id']);
							if ($call_info && !empty($call_info['video'])) {
								$path = (string) $call_info['video'];
								$return['video_src'][$lang2['lang_abbr']] = ($path[0] === '/') ? $path : '/' . $path;
							} else {
								$return['video_src'][$lang2['lang_abbr']] = '/video/' . $lang2['lang_abbr'] . '/video_jane_2.mp4';
							}
						}
						$return['video_src'] = $lang->mirrorUkUaByLang($return['video_src']);
					}
					if (empty($return['video_src'])) {
						$call_info = $function->getCallVideoInfo(5, (int) $lang_id);
						if ($call_info && !empty($call_info['video'])) {
							$path = (string) $call_info['video'];
							$return['video_src'][$lang_abbr] = ($path[0] === '/') ? $path : '/' . $path;
						} else {
							$return['video_src'][$lang_abbr] = '/video/' . $lang_abbr . '/video_jane_2.mp4';
						}
						$return['video_src'] = $lang->mirrorUkUaByLang($return['video_src']);
					}

					if ($langs) {
						$return['success_lang'] = [];
						$return['translation'] = [];
						$return['lang_ids'] = [];

						foreach ($langs as $lang2) {
							$translation = $lang->getWordsByPage('game', $lang2['id']);
							$return['translation'][$lang2['lang_abbr']] = $translation;
							$return['lang_ids'][$lang2['lang_abbr']] = $lang2['id'];

							$return['success_lang'][$lang2['lang_abbr']]['success_input'] = $translation['text163'] ?? '';
							$return['success_lang'][$lang2['lang_abbr']]['success_text'] = $translation['text164'] ?? '';
							$return['success_lang'][$lang2['lang_abbr']]['success_close'] = $translation['text165'] ?? '';
						}
						$return['success_lang'] = $lang->mirrorUkUaByLang($return['success_lang']);
						$return['translation'] = $lang->mirrorUkUaByLang($return['translation']);
						$return['lang_ids'] = $lang->mirrorUkUaByLang($return['lang_ids']);
					}

					if (empty($return['success_lang'])) {
						$translation = $lang->getWordsByPage('game', $lang_id);

						$return['success_lang'] = [];
						$return['success_lang'][$lang_abbr]['success_input'] = isset($translation['text163']) ? $translation['text163'] : '';
						$return['success_lang'][$lang_abbr]['success_text'] = isset($translation['text164']) ? $translation['text164'] : '';
						$return['success_lang'][$lang_abbr]['success_close'] = isset($translation['text165']) ? $translation['text165'] : '';
						$return['success_lang'] = $lang->mirrorUkUaByLang($return['success_lang']);

						$return['translation'] = [];
						$return['translation'][$lang_abbr] = $translation;
						$return['translation'] = $lang->mirrorUkUaByLang($return['translation']);
						$return['lang_ids'] = [];
						$return['lang_ids'][$lang_abbr] = $lang_id;
						$return['lang_ids'] = $lang->mirrorUkUaByLang($return['lang_ids']);
					}
				} else {
					// переводы для всех языков. Для синхронизации
					$sql = "SELECT `lang_abbr`, `id` FROM `langs` WHERE `status` = {?}";
					$langs = $db->select($sql, [1]);
					if ($langs) {
						$return['error_lang'] = [];

						foreach ($langs as $lang2) {
							$translation = $lang->getWordsByPage('game', $lang2['id']);

							$return['error_lang'][$lang2['lang_abbr']]['error_input'] = $translation['text161'] ?? '';
							$return['error_lang'][$lang2['lang_abbr']]['error_text'] = $translation['text162'] ?? '';
						}
						$return['error_lang'] = $lang->mirrorUkUaByLang($return['error_lang']);
					}

					if (empty($return['error_lang'])) {
						$return['error_lang'] = [];
						$return['error_lang'][$lang_abbr]['error_input'] = isset($translation['text161']) ? $translation['text161'] : '';
						$return['error_lang'][$lang_abbr]['error_text'] = isset($translation['text162']) ? $translation['text162'] : '';
						$return['error_lang'] = $lang->mirrorUkUaByLang($return['error_lang']);
					}
				}
			} else {
				// переводы для всех языков. Для синхронизации
				$sql = "SELECT `lang_abbr`, `id` FROM `langs` WHERE `status` = {?}";
				$langs = $db->select($sql, [1]);
				if ($langs) {
					$return['error_lang'] = [];

					foreach ($langs as $lang2) {
						$translation = $lang->getWordsByPage('game', $lang2['id']);

						$return['error_lang'][$lang2['lang_abbr']]['error_input'] = $translation['text161'] ?? '';
						$return['error_lang'][$lang2['lang_abbr']]['error_text'] = $translation['text162'] ?? '';
					}
					$return['error_lang'] = $lang->mirrorUkUaByLang($return['error_lang']);
				}

				if (empty($return['error_lang'])) {
					$return['error_lang'] = [];
					$return['error_lang'][$lang_abbr]['error_input'] = isset($translation['text161']) ? $translation['text161'] : '';
					$return['error_lang'][$lang_abbr]['error_text'] = isset($translation['text162']) ? $translation['text162'] : '';
					$return['error_lang'] = $lang->mirrorUkUaByLang($return['error_lang']);
				}
			}

			print_r(json_encode($return));
		    break;

		// правильно ввели company investigate - обновляем список подсказок
		case 'companyInvestigateUpdateHint':
			$lang_abbr = isset($_POST['lang_abbr']) ? strip_tags(trim($_POST['lang_abbr'])) : '';

			$lang_id = $lang->getLangIdByHtmlAttr($lang_abbr);
			$translation = $lang->getWordsByPage('game', $lang_id);

			if (!$function->isActiveVerifyCode($userInfo['team_id'])) {
				$return['error_verify'] = $translation['text4'];
			} else {
				$team_info = $function->teamInfo($userInfo['team_id']);
				if ($team_info) {
					// список открытых
					$active_hints = [];

					// список доступных
					$list_hints = [];

					$hints_by_step = $function->getHintsByStep('company_investigate', $lang_id);
					if ($hints_by_step) {
						foreach ($hints_by_step as $hint) {
							$list_hints[] = $hint['id'];
						}
					}

					// сохраняем обновленный список подсказок + запоминаем новый открытый tools + запоминаем новый открытый dashboard
					$sql = "UPDATE `teams` SET `active_hints` = {?}, `list_hints` = {?}, `list_hints_title_lang_var` = {?}, `list_hints_text_lang_var` = {?}, `last_dashboard` = {?}, `last_tools` = {?} WHERE `id` = {?}";
					$db->query($sql, [json_encode($active_hints, JSON_UNESCAPED_UNICODE), json_encode($list_hints, JSON_UNESCAPED_UNICODE), 'text44', 'text45', 'geo_coordinates', 'tools_start_four', $userInfo['team_id']]);

					$return['success'] = 'ok';

					/*// не в тему, но))) обновляем актуальный список доступных файлов
					$list_files = json_decode($team_info['list_files'], true);

					$list_files[] = 4;
					$list_files[] = 5;

					$list_files = array_unique($list_files);

					$sql = "UPDATE `teams` SET `list_files` = {?} WHERE `id` = {?}";
					$db->query($sql, [json_encode($list_files, JSON_UNESCAPED_UNICODE), $userInfo['team_id']]);*/
					$function->updateTeamListFiles($userInfo['team_id'], 4);
					$function->updateTeamListFiles($userInfo['team_id'], 5);

					/*// аналогично обновляем актуальный список доступных tools
					$list_tools = json_decode($team_info['list_tools'], true);

					// $list_tools[] = 'advanced_search_engine';
					$list_tools[] = 'gps_coordinates';
					// $list_tools[] = 'symbol_decoder';
					// $list_tools[] = '3d_building_scan';

					$list_tools = array_unique($list_tools);

					$sql = "UPDATE `teams` SET `list_tools` = {?} WHERE `id` = {?}";
					$db->query($sql, [json_encode($list_tools, JSON_UNESCAPED_UNICODE), $userInfo['team_id']]);*/
					$function->updateTeamListTools($userInfo['team_id'], 'gps_coordinates');
				} else {
					$return['error'] = $translation['text29'];
				}
			}

			print_r(json_encode($return));
		    break;

		// dashboard - geo coordinates. Проверка правильности ввода данных
		case 'validateDashboardGeoCoordinatesSearch':
			$latitude1 = (!empty($_POST['latitude1']) || $_POST['latitude1'] == '0') ? strip_tags(trim($_POST['latitude1'])) : false;
			$latitude2 = (!empty($_POST['latitude2']) || $_POST['latitude2'] == '0') ? strip_tags(trim($_POST['latitude2'])) : false;
			$latitude3 = (!empty($_POST['latitude3']) || $_POST['latitude3'] == '0') ? strip_tags(trim($_POST['latitude3'])) : false;
			$longitude1 = (!empty($_POST['longitude1']) || $_POST['longitude1'] == '0') ? strip_tags(trim($_POST['longitude1'])) : false;
			$longitude2 = (!empty($_POST['longitude2']) || $_POST['longitude2'] == '0') ? strip_tags(trim($_POST['longitude2'])) : false;
			$longitude3 = (!empty($_POST['longitude3']) || $_POST['longitude3'] == '0') ? strip_tags(trim($_POST['longitude3'])) : false;
			$lang_abbr = isset($_POST['lang_abbr']) ? strip_tags(trim($_POST['lang_abbr'])) : 'en';

			$lang_id = $lang->getLangIdByHtmlAttr($lang_abbr);
			$translation = $lang->getWordsByPage('game', $lang_id);

			if (
				(!empty($latitude1) || $latitude1 == '0') && 
				(!empty($latitude2) || $latitude2 == '0') && 
				(!empty($latitude3) || $latitude3 == '0') && 
				(!empty($longitude1) || $longitude1 == '0') && 
				(!empty($longitude2) || $longitude2 == '0') && 
				(!empty($longitude3) || $longitude3 == '0')
			) {//answer cordinate
				if (
					$latitude1 == '42' && $latitude2 == '44' && ($latitude3 == '44.394' || $latitude3 == '44,394') &&
					$longitude1 == '124' && $longitude2 == '29' && ($longitude3 == '50.377' || $longitude3 == '50,377')
				) {
					$return['success'] = 'ok';
				} else {
					// переводы для всех языков. Для синхронизации
					$sql = "SELECT `lang_abbr`, `id` FROM `langs` WHERE `status` = {?}";
					$langs = $db->select($sql, [1]);
					if ($langs) {
						$return['error_lang'] = [];

						foreach ($langs as $lang2) {
							$translation = $lang->getWordsByPage('game', $lang2['id']);

							$return['error_lang'][$lang2['lang_abbr']]['error_input'] = $translation['text191'];
							$return['error_lang'][$lang2['lang_abbr']]['error_text'] = $translation['text192'];
						}
					}
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
						$return['error_lang'][$lang2['lang_abbr']]['error_text'] = $translation['text192'];
					}
				}
			}

			print_r(json_encode($return));
		    break;

		// правильно ввели geo coordinates - обновляем список подсказок
		case 'geoCoordinatesUpdateHint':
			$lang_abbr = isset($_POST['lang_abbr']) ? strip_tags(trim($_POST['lang_abbr'])) : '';

			$lang_id = $lang->getLangIdByHtmlAttr($lang_abbr);
			$translation = $lang->getWordsByPage('game', $lang_id);

			if (!$function->isActiveVerifyCode($userInfo['team_id'])) {
				$return['error_verify'] = $translation['text4'];
			} else {
				$team_info = $function->teamInfo($userInfo['team_id']);
				if ($team_info) {
					// список открытых
					$active_hints = [];

					// список доступных
					$list_hints = [];

					$hints_by_step = $function->getHintsByStep('geo_coordinates', $lang_id);
					if ($hints_by_step) {
						foreach ($hints_by_step as $hint) {
							$list_hints[] = $hint['id'];
						}
					}

					// после geo переходим на этап voice_decoder
					$sql = "UPDATE `teams` SET `active_hints` = {?}, `list_hints` = {?}, `list_hints_title_lang_var` = {?}, `list_hints_text_lang_var` = {?}, `last_dashboard` = {?} WHERE `id` = {?}";
					$db->query($sql, [json_encode($active_hints, JSON_UNESCAPED_UNICODE), json_encode($list_hints, JSON_UNESCAPED_UNICODE), 'text44', 'text45', 'voice_decoder', $userInfo['team_id']]);

					$return['success'] = 'ok';
				} else {
					$return['error'] = $translation['text29'];
				}
			}

			print_r(json_encode($return));
		    break;

		// voice decoder - получить текущее количество voice_message + audio_find
		case 'getVoiceDecoderState':
			if (!$userInfo || empty($userInfo['team_id'])) {
				$return['error'] = 'not_authorized';
				print_r(json_encode($return));
				break;
			}
			$function->ensureVoiceDecoderColumns();
			$team_info = $function->teamInfo($userInfo['team_id']);
			$voice_count = 0;
			$audio_find = [];
			if ($team_info) {
				if (isset($team_info['voice_message'])) {
					$voice_count = (int) $team_info['voice_message'];
				}
				$rawFind = isset($team_info['audio_find']) ? trim((string) $team_info['audio_find']) : '';
				if ($rawFind !== '') {
					foreach (preg_split('/\s*,\s*/', $rawFind) as $part) {
						$id = (int) $part;
						if ($id >= 1 && $id <= 4 && !in_array($id, $audio_find, true)) {
							$audio_find[] = $id;
						}
					}
					sort($audio_find);
				}
			}
			if ($voice_count < 0) {
				$voice_count = 0;
			} elseif ($voice_count > 4) {
				$voice_count = 4;
			}

			$return['success'] = 'ok';
			$return['voice_count'] = $voice_count;
			$return['audio_find'] = $audio_find;
			$return['can_decrypt'] = ($voice_count >= 4) ? 1 : 0;
			$return['last_dashboard'] = $team_info ? ($team_info['last_dashboard'] ?? '') : '';
			print_r(json_encode($return));
		    break;

		// voice decoder - добавить конкретный audio (1..4) в audio_find и +1 к voice_message
		case 'addVoiceDecoderMessage':
			if (!$userInfo || empty($userInfo['team_id'])) {
				$return['error'] = 'not_authorized';
				print_r(json_encode($return));
				break;
			}
			$audio_id = isset($_POST['audio_id']) ? (int) $_POST['audio_id'] : 0;
			$function->ensureVoiceDecoderColumns();
			$team_info = $function->teamInfo($userInfo['team_id']);
			if (!$team_info) {
				$return['error'] = 'team_not_found';
				print_r(json_encode($return));
				break;
			}
			if (!$function->isVoiceDecoderStage($team_info)) {
				$return['error'] = 'wrong_stage';
				print_r(json_encode($return));
				break;
			}
			if (!array_key_exists('voice_message', $team_info) || !array_key_exists('audio_find', $team_info)) {
				$return['error'] = 'voice_decoder_columns_missing';
				print_r(json_encode($return));
				break;
			}
			if ($audio_id < 1 || $audio_id > 4) {
				$return['error'] = 'invalid_audio_id';
				print_r(json_encode($return));
				break;
			}

			$audio_find = [];
			$rawFind = isset($team_info['audio_find']) ? trim((string) $team_info['audio_find']) : '';
			if ($rawFind !== '') {
				foreach (preg_split('/\s*,\s*/', $rawFind) as $part) {
					$id = (int) $part;
					if ($id >= 1 && $id <= 4 && !in_array($id, $audio_find, true)) {
						$audio_find[] = $id;
					}
				}
			}
			if (in_array($audio_id, $audio_find, true)) {
				$return['success'] = 'ok';
				$return['already'] = 1;
				$return['voice_count'] = (int) $team_info['voice_message'];
				$return['audio_find'] = $audio_find;
				$return['audio_id'] = $audio_id;
				print_r(json_encode($return));
				break;
			}

			$audio_find[] = $audio_id;
			sort($audio_find);
			$current = (int) $team_info['voice_message'];
			$next = $current + 1;
			if ($next > 4) {
				$next = 4;
			}

			$updated = $db->query(
				"UPDATE `teams` SET `voice_message` = {?}, `audio_find` = {?} WHERE `id` = {?}",
				[$next, implode(',', $audio_find), $userInfo['team_id']]
			);
			if (!$updated) {
				$return['error'] = 'db_update_failed';
				print_r(json_encode($return));
				break;
			}

			$return['success'] = 'ok';
			$return['voice_count'] = $next;
			$return['audio_find'] = $audio_find;
			$return['audio_id'] = $audio_id;
			print_r(json_encode($return));
		    break;

		// voice decoder - расшифровка количества voice_message и переход дальше
		case 'voiceDecoderUpdateHint':
			$lang_abbr = isset($_POST['lang_abbr']) ? strip_tags(trim($_POST['lang_abbr'])) : '';
			$lang_id = $lang->getLangIdByHtmlAttr($lang_abbr);
			$translation = $lang->getWordsByPage('game', $lang_id);
			if (!$function->isActiveVerifyCode($userInfo['team_id'])) {
				$return['error_verify'] = $translation['text4'];
			} else {
				$team_info = $function->teamInfo($userInfo['team_id']);
				if ($team_info && isset($team_info['voice_message']) && (int) $team_info['voice_message'] >= 4) {
					$function->ensureVoiceDecoderColumns();
					$active_hints = [];
					$list_hints = [];
					$hints_by_step = $function->getHintsByStep('geo_coordinates', $lang_id);
					if ($hints_by_step) {
						foreach ($hints_by_step as $hint) {
							$list_hints[] = $hint['id'];
						}
					}

					$sql = "UPDATE `teams` SET `active_hints` = {?}, `list_hints` = {?}, `list_hints_title_lang_var` = {?}, `list_hints_text_lang_var` = {?}, `last_dashboard` = {?}, `voice_correct_order` = {?} WHERE `id` = {?}";
					$db->query($sql, [json_encode($active_hints, JSON_UNESCAPED_UNICODE), json_encode($list_hints, JSON_UNESCAPED_UNICODE), 'text44', 'text45', 'voice_correct', '3,1,4,2', $userInfo['team_id']]);

					$return['success'] = 'ok';
					$return['next_step'] = 'voice_correct';
				} else {
					$return['error'] = 'not_enough_voice';
				}
			}
			print_r(json_encode($return));
		    break;

		// voice_correct - текущий порядок аудио
		case 'getVoiceCorrectState':
			if (!$userInfo || empty($userInfo['team_id'])) {
				$return['error'] = 'not_authorized';
				print_r(json_encode($return));
				break;
			}
			$function->ensureVoiceDecoderColumns();
			$team_info = $function->teamInfo($userInfo['team_id']);
			$order = $function->parseVoiceCorrectOrder($team_info);
			$return['success'] = 'ok';
			$return['order'] = $order;
			$return['last_dashboard'] = $team_info ? ($team_info['last_dashboard'] ?? '') : '';
			print_r(json_encode($return));
		    break;

		// voice_correct - сохранить порядок (сдвиг влево/вправо)
		case 'saveVoiceCorrectOrder':
			if (!$userInfo || empty($userInfo['team_id'])) {
				$return['error'] = 'not_authorized';
				print_r(json_encode($return));
				break;
			}
			$function->ensureVoiceDecoderColumns();
			$orderRaw = isset($_POST['order']) ? $_POST['order'] : '';
			if (is_string($orderRaw)) {
				$orderRaw = json_decode($orderRaw, true);
			}
			$order = $function->normalizeVoiceCorrectOrder($orderRaw);
			$updated = $db->query(
				"UPDATE `teams` SET `voice_correct_order` = {?} WHERE `id` = {?}",
				[implode(',', $order), $userInfo['team_id']]
			);
			if (!$updated) {
				$return['error'] = 'db_update_failed';
				print_r(json_encode($return));
				break;
			}
			$return['success'] = 'ok';
			$return['order'] = $order;
			print_r(json_encode($return));
		    break;

		// voice_correct - проверка порядка и переход на african_partner
		case 'validateVoiceCorrectOrder':
			if (!$userInfo || empty($userInfo['team_id'])) {
				$return['error'] = 'not_authorized';
				print_r(json_encode($return));
				break;
			}
			$lang_abbr = isset($_POST['lang_abbr']) ? strip_tags(trim($_POST['lang_abbr'])) : '';
			$lang_id = $lang->getLangIdByHtmlAttr($lang_abbr);
			$translation = $lang->getWordsByPage('game', $lang_id);
			if (!$function->isActiveVerifyCode($userInfo['team_id'])) {
				$return['error_verify'] = $translation['text4'];
				print_r(json_encode($return));
				break;
			}

			$function->ensureVoiceDecoderColumns();
			$orderRaw = isset($_POST['order']) ? $_POST['order'] : '';
			if (is_string($orderRaw)) {
				$orderRaw = json_decode($orderRaw, true);
			}
			$order = $function->normalizeVoiceCorrectOrder($orderRaw);
			$isCorrect = ($order === [1, 2, 3, 4]);

			$db->query(
				"UPDATE `teams` SET `voice_correct_order` = {?} WHERE `id` = {?}",
				[implode(',', $order), $userInfo['team_id']]
			);

			if (!$isCorrect) {
				$return['error'] = 'wrong_order';
				$return['error_text'] = 'помилка. Аудіофайл відтворено невірно';
				$return['order'] = $order;
				print_r(json_encode($return));
				break;
			}

			$team_info = $function->teamInfo($userInfo['team_id']);
			$active_hints = [];
			$list_hints = [];
			$hints_by_step = $function->getHintsByStep('geo_coordinates', $lang_id);
			if ($hints_by_step) {
				foreach ($hints_by_step as $hint) {
					$list_hints[] = $hint['id'];
				}
			}

			$sql = "UPDATE `teams` SET `active_hints` = {?}, `list_hints` = {?}, `list_hints_title_lang_var` = {?}, `list_hints_text_lang_var` = {?}, `last_dashboard` = {?}, `tools_advanced_search_engine_access` = {?} WHERE `id` = {?}";
			$db->query($sql, [json_encode($active_hints, JSON_UNESCAPED_UNICODE), json_encode($list_hints, JSON_UNESCAPED_UNICODE), 'text44', 'text45', 'african_partner', 1, $userInfo['team_id']]);

			$function->updateTeamListFiles($userInfo['team_id'], 6);
			$function->updateTeamListFiles($userInfo['team_id'], 7);
			$function->updateTeamListTools($userInfo['team_id'], 'advanced_search_engine');

			$return['success'] = 'ok';
			$return['order'] = $order;
			$return['next_step'] = 'african_partner';
			$return['full_audio'] = [
				'/music/deshefrator_correct/vika_3_out_1.mp3',
				'/music/deshefrator_correct/vika_3_out_2.mp3',
				'/music/deshefrator_correct/vika_3_out_3.mp3',
				'/music/deshefrator_correct/vika_3_out_4.mp3',
			];
			print_r(json_encode($return));
		    break;

		// dashboard - african partner. Проверка правильности ввода данных
		case 'validateAfricanPartnerSearch':
			$company_name = !empty($_POST['company_name']) ? strip_tags(trim($_POST['company_name'])) : false;
			$country = !empty($_POST['country']) ? strip_tags(trim($_POST['country'])) : false;
			$date = !empty($_POST['date']) ? strip_tags(trim($_POST['date'])) : false;
			$lang_abbr = isset($_POST['lang_abbr']) ? strip_tags(trim($_POST['lang_abbr'])) : 'en';

			$lang_id = $lang->getLangIdByHtmlAttr($lang_abbr);
			$translation = $lang->getWordsByPage('game', $lang_id);

			if (!empty($company_name) && !empty($country) && !empty($date)) {
				$company_name = str_replace(' ', '', $company_name);
				// ответ African / American partner
				if (mb_strtolower($company_name, 'UTF-8') == 'royalwolf' && $country == 'Egypt' && ($date == '11.12.2016' || $date == '11.12.2016' || $date == '11.12.16' || $date == '11.12.16')) {
					$return['success'] = 'ok';

					// переводы для всех языков. Для синхронизации
					$sql = "SELECT `lang_abbr`, `id` FROM `langs` WHERE `status` = {?}";
					$langs = $db->select($sql, [1]);
					if ($langs) {
						$return['success_lang'] = [];

						foreach ($langs as $lang2) {
							$translation = $lang->getWordsByPage('game', $lang2['id']);

							$return['success_lang'][$lang2['lang_abbr']]['success_input'] = $translation['text203'];
							$return['success_lang'][$lang2['lang_abbr']]['success_text'] = $translation['text204'];
							$return['success_lang'][$lang2['lang_abbr']]['success_close'] = $translation['text165'];
						}
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
							$return['error_lang'][$lang2['lang_abbr']]['error_text'] = $translation['text202'];
						}
					}
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
						$return['error_lang'][$lang2['lang_abbr']]['error_text'] = $translation['text202'];
					}
				}
			}

			print_r(json_encode($return));
		    break;

		// правильно ввели african partner - обновляем список подсказок
		case 'africanPartnerUpdateHint':
			$lang_abbr = isset($_POST['lang_abbr']) ? strip_tags(trim($_POST['lang_abbr'])) : '';

			$lang_id = $lang->getLangIdByHtmlAttr($lang_abbr);
			$translation = $lang->getWordsByPage('game', $lang_id);

			if (!$function->isActiveVerifyCode($userInfo['team_id'])) {
				$return['error_verify'] = $translation['text4'];
			} else {
				$team_info = $function->teamInfo($userInfo['team_id']);
				if ($team_info) {
					// список открытых
					$active_hints = [];

					// список доступных
					$list_hints = [];

					$hints_by_step = $function->getHintsByStep('african_partner', $lang_id);
					if ($hints_by_step) {
						foreach ($hints_by_step as $hint) {
							$list_hints[] = $hint['id'];
						}
					}

					// сохраняем обновленный список подсказок + запоминаем новый открытый dashboard + теперь всегда доступен новый tools Symbol Decoder
					$sql = "UPDATE `teams` SET `active_hints` = {?}, `list_hints` = {?}, `list_hints_title_lang_var` = {?}, `list_hints_text_lang_var` = {?}, `last_dashboard` = {?}, `tools_symbol_decoder_access` = {?} WHERE `id` = {?}";
					$db->query($sql, [json_encode($active_hints, JSON_UNESCAPED_UNICODE), json_encode($list_hints, JSON_UNESCAPED_UNICODE), 'text44', 'text45', 'metting_place', 1, $userInfo['team_id']]);

					$return['success'] = 'ok';

					// обновляем актуальный список доступных баз данных
					/*$list_databases = ['personal_files', 'car_register', 'mobile_calls', 'bank_transactions'];

					$sql = "UPDATE `teams` SET `list_databases` = {?} WHERE `id` = {?}";
					$db->query($sql, [json_encode($list_databases, JSON_UNESCAPED_UNICODE), $userInfo['team_id']]);*/
					$function->updateTeamListDatabases($userInfo['team_id'], 'bank_transactions');

					// обновляем актуальный список доступных tools
					$function->updateTeamListTools($userInfo['team_id'], 'symbol_decoder');

					// Теперь доступна кнопка Call mobile
					$sql = "UPDATE `teams` SET `view_call_mobile_btn` = {?} WHERE `id` = {?}";
					$db->query($sql, [1, $userInfo['team_id']]);
				} else {
					$return['error'] = $translation['text29'];
				}
			}

			print_r(json_encode($return));
		    break;

		// правильно ввели metting place - обновляем список подсказок и переключаем этап
		case 'mettingPlaceUpdateHint':
			$lang_abbr = isset($_POST['lang_abbr']) ? strip_tags(trim($_POST['lang_abbr'])) : '';

			$lang_id = $lang->getLangIdByHtmlAttr($lang_abbr);
			$translation = $lang->getWordsByPage('game', $lang_id);

			if (!$function->isActiveVerifyCode($userInfo['team_id'])) {
				$return['error_verify'] = $translation['text4'];
			} else {
				$team_info = $function->teamInfo($userInfo['team_id']);
				if ($team_info) {
					$active_hints = [];
					$list_hints = [];

					$hints_by_step = $function->getHintsByStep('3d_plan', $lang_id);
					if ($hints_by_step) {
						foreach ($hints_by_step as $hint) {
							$list_hints[] = $hint['id'];
						}
					}

					$sql = "UPDATE `teams` SET `active_hints` = {?}, `list_hints` = {?}, `list_hints_title_lang_var` = {?}, `list_hints_text_lang_var` = {?}, `last_dashboard` = {?}, `tools_3d_bulding_scan_access` = {?}, `chat_send_message_access` = {?} WHERE `id` = {?}";
					$db->query($sql, [json_encode($active_hints, JSON_UNESCAPED_UNICODE), json_encode($list_hints, JSON_UNESCAPED_UNICODE), 'text44', 'text45', 'room_name', 1, 0, $userInfo['team_id']]);

					$function->updateTeamListFiles($userInfo['team_id'], 8);
					$function->updateTeamListTools($userInfo['team_id'], '3d_building_scan');

					$sql = "SELECT `id` FROM `chat_messages` WHERE `team_id` = {?}";
					$messages = $db->select($sql, [$userInfo['team_id']]);
					if ($messages) {
						foreach ($messages as $message) {
							$sql = "DELETE FROM `chat_messages_description` WHERE `chat_message_id` = {?}";
							$db->query($sql, [$message['id']]);
						}
					}

					$sql = "DELETE FROM `chat_messages` WHERE `team_id` = {?}";
					$db->query($sql, [$userInfo['team_id']]);

					$return['success'] = 'ok';
				} else {
					$return['error'] = $translation['text29'];
				}
			}

			print_r(json_encode($return));
		    break;

		// dashboard - metting place. Проверка правильности ввода данных
		case 'validateMettingPlaceSearch':
			$street_name = !empty($_POST['street_name']) ? strip_tags(trim($_POST['street_name'])) : false;
			$house_number = !empty($_POST['house_number']) ? strip_tags(trim($_POST['house_number'])) : false;
			$city = !empty($_POST['city']) ? strip_tags(trim($_POST['city'])) : false;
			$country = !empty($_POST['country']) ? strip_tags(trim($_POST['country'])) : false;
			$lang_abbr = isset($_POST['lang_abbr']) ? strip_tags(trim($_POST['lang_abbr'])) : 'en';

			$lang_id = $lang->getLangIdByHtmlAttr($lang_abbr);
			$translation = $lang->getWordsByPage('game', $lang_id);

			if (!empty($street_name) && !empty($house_number) && !empty($city) && !empty($country)) {
				$street_name = str_replace(' ', '', $street_name);
				$street_name = mb_strtolower($street_name, 'UTF-8');

				if (
					in_array($street_name, ['каштанова', 'каштановавулица']) && 
					$house_number == '15' && 
					mb_strtolower($city, 'UTF-8') == 'дніпро' &&
					(
						($lang_abbr == 'en' && $country == 'Ukraine') || 
						($lang_abbr == 'no' && $country == 'Ukraine') ||
						($lang_abbr == 'uk' && $country == 'Ukraine')
					)
				) {
					$return['success'] = 'ok';

					// переводы для всех языков. Для синхронизации
					$sql = "SELECT `lang_abbr`, `id` FROM `langs` WHERE `status` = {?}";
					$langs = $db->select($sql, [1]);
					if ($langs) {
						$return['success_lang'] = [];

						foreach ($langs as $lang2) {
							$translation = $lang->getWordsByPage('game', $lang2['id']);

							$return['success_lang'][$lang2['lang_abbr']]['success_input'] = $translation['text232'];
							$return['success_lang'][$lang2['lang_abbr']]['success_text'] = $translation['text233'];
							$return['success_lang'][$lang2['lang_abbr']]['success_close'] = $translation['text234'];
						}
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
							$return['error_lang'][$lang2['lang_abbr']]['error_text'] = $translation['text231'];
						}
					}
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
						$return['error_lang'][$lang2['lang_abbr']]['error_text'] = $translation['text231'];
					}
				}
			}

			print_r(json_encode($return));
		    break;

		// dashboard - room name. Проверка правильности ввода данных
		case 'validateRoomNameSearch':
			$room_name = !empty($_POST['room_name']) ? strip_tags(trim($_POST['room_name'])) : false;
			$lang_abbr = isset($_POST['lang_abbr']) ? strip_tags(trim($_POST['lang_abbr'])) : 'en';

			$lang_id = $lang->getLangIdByHtmlAttr($lang_abbr);
			$translation = $lang->getWordsByPage('game', $lang_id);

			if (!empty($room_name)) {
				$room_name = mb_strtolower($room_name, 'UTF-8');

				if (in_array($room_name, ['office green00', 'officegreen00', 'green00', 'office green 00', 'green 00', 'office greenoo', 'officegreenoo', 'greenoo', 'office green oo', 'green oo', 'office green0o', 'office greeno0', 'officegreeno0', 'officegreen0o', 'greeno0', 'green0o', 'office green o0', 'office green 0o', 'green o0', 'green 0o'])) {
					$return['success'] = 'ok';
				} else {
					// переводы для всех языков. Для синхронизации
					$sql = "SELECT `lang_abbr`, `id` FROM `langs` WHERE `status` = {?}";
					$langs = $db->select($sql, [1]);
					if ($langs) {
						$return['error_lang'] = [];

						foreach ($langs as $lang2) {
							$translation = $lang->getWordsByPage('game', $lang2['id']);

							$return['error_lang'][$lang2['lang_abbr']]['error_input'] = $translation['text191'];
							$return['error_lang'][$lang2['lang_abbr']]['error_text'] = $translation['text250'];
						}
					}
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
						$return['error_lang'][$lang2['lang_abbr']]['error_text'] = $translation['text250'];
					}
				}
			}

			print_r(json_encode($return));
		    break;

		// правильно ввели room name - обновляем список подсказок
		case 'roomNameUpdateHint':
			$lang_abbr = isset($_POST['lang_abbr']) ? strip_tags(trim($_POST['lang_abbr'])) : '';

			$lang_id = $lang->getLangIdByHtmlAttr($lang_abbr);
			$translation = $lang->getWordsByPage('game', $lang_id);

			if (!$function->isActiveVerifyCode($userInfo['team_id'])) {
				$return['error_verify'] = $translation['text4'];
			} else {
				$team_info = $function->teamInfo($userInfo['team_id']);
				if ($team_info) {
					// список открытых
					$active_hints = [];

					// список доступных
					$list_hints = [];

					$hints_by_step = $function->getHintsByStep('room_name', $lang_id);
					if ($hints_by_step) {
						foreach ($hints_by_step as $hint) {
							$list_hints[] = $hint['id'];
						}
					}

					// сохраняем обновленный список подсказок + пишем, что находимся теперь на этапе миниигры
					$sql = "UPDATE `teams` SET `active_hints` = {?}, `list_hints` = {?}, `list_hints_title_lang_var` = {?}, `list_hints_text_lang_var` = {?}, `dashboard_minigame_access` = {?}, `dashboard_minigame_active_step` = {?} WHERE `id` = {?}";
					$db->query($sql, [json_encode($active_hints, JSON_UNESCAPED_UNICODE), json_encode($list_hints, JSON_UNESCAPED_UNICODE), 'text44', 'text45', 1, 1, $userInfo['team_id']]);
				}
			}

			print_r(json_encode($return));
		    break;

		// прошли миниигру - обновляем список подсказок
		case 'minigameUpdateHint':
			$lang_abbr = isset($_POST['lang_abbr']) ? strip_tags(trim($_POST['lang_abbr'])) : '';

			$lang_id = $lang->getLangIdByHtmlAttr($lang_abbr);
			$translation = $lang->getWordsByPage('game', $lang_id);

			if (!$function->isActiveVerifyCode($userInfo['team_id'])) {
				$return['error_verify'] = $translation['text4'];
			} else {
				$team_info = $function->teamInfo($userInfo['team_id']);
				if ($team_info) {
					// проверяем, действительно ли пройдена миниигра
					if ($team_info['dashboard_minigame_active_step'] == 12) {
						// список открытых
						$active_hints = [];

						// список доступных
						$list_hints = [];

						$hints_by_step = $function->getHintsByStep('minigame', $lang_id);
						if ($hints_by_step) {
							foreach ($hints_by_step as $hint) {
								$list_hints[] = $hint['id'];
							}
						}

						// сохраняем обновленный список подсказок + запрещаем доступ к экрану миниигры
						$sql = "UPDATE `teams` SET `active_hints` = {?}, `list_hints` = {?}, `list_hints_title_lang_var` = {?}, `list_hints_text_lang_var` = {?}, `dashboard_minigame_access` = {?} WHERE `id` = {?}";
						$db->query($sql, [json_encode($active_hints, JSON_UNESCAPED_UNICODE), json_encode($list_hints, JSON_UNESCAPED_UNICODE), 'text44', 'text45', 0, $userInfo['team_id']]);

						$return['success'] = 'ok';
					}
				}
			}

			print_r(json_encode($return));
		    break;

		// dashboard - password. Проверка правильности ввода данных
		case 'validatePasswordSearch':
			$password = !empty($_POST['password']) ? strip_tags(trim($_POST['password'])) : false;
			$lang_abbr = isset($_POST['lang_abbr']) ? strip_tags(trim($_POST['lang_abbr'])) : 'en';

			$lang_id = $lang->getLangIdByHtmlAttr($lang_abbr);
			$translation = $lang->getWordsByPage('game', $lang_id);

			if (!empty($password)) {
				$password = mb_strtolower($password, 'UTF-8');

				if (in_array($password, ['rpa139169', 'rpa 13 91 69', 'r p a 13 91 69'])) {
					$return['success'] = 'ok';

					// запоминаем, что переходим на экран interpol
					$sql = "UPDATE `teams` SET `dashboard_interpol_access` = {?} WHERE `id` = {?}";
					$db->query($sql, [1, $userInfo['team_id']]);
				} else {
					// переводы для всех языков. Для синхронизации
					$sql = "SELECT `lang_abbr`, `id` FROM `langs` WHERE `status` = {?}";
					$langs = $db->select($sql, [1]);
					if ($langs) {
						$return['error_lang'] = [];

						foreach ($langs as $lang2) {
							$translation = $lang->getWordsByPage('game', $lang2['id']);

							$return['error_lang'][$lang2['lang_abbr']]['error_input'] = $translation['text274'];
							$return['error_lang'][$lang2['lang_abbr']]['error_text'] = $translation['text275'];
						}
					}
				}
			} else {
				// переводы для всех языков. Для синхронизации
				$sql = "SELECT `lang_abbr`, `id` FROM `langs` WHERE `status` = {?}";
				$langs = $db->select($sql, [1]);
				if ($langs) {
					$return['error_lang'] = [];

					foreach ($langs as $lang2) {
						$translation = $lang->getWordsByPage('game', $lang2['id']);

						$return['error_lang'][$lang2['lang_abbr']]['error_input'] = $translation['text274'];
						$return['error_lang'][$lang2['lang_abbr']]['error_text'] = $translation['text275'];
					}
				}
			}

			print_r(json_encode($return));
		    break;

		// окончание игры
		case 'finishGame':
			// фиксируем время прохождения игры
			$sql = "SELECT `mission_accept_datetime`, `score` FROM `teams` WHERE `id` = {?} AND `mission_accept_datetime` != {?} AND `mission_accept_datetime` != {?} AND `mission_accept_datetime` != {?} AND `mission_accept_datetime` IS NOT NULL";
            $row = $db->selectRow($sql, [$userInfo['team_id'], '', '0000-00-00 00:00:00', 'null']);
            if ($row) {
                $old = new DateTime($row['mission_accept_datetime']);
                $now = new DateTime();

                $interval = $old->diff($now);

                $return['second'] = $interval->s;
                $return['minute'] = $interval->i;
                $return['hours'] = $interval->h;

                // общее к-во секунд от начала отсчета
                $second_sum = $interval->days * 24 * 60;
                $second_sum += $interval->h * 60 * 60;
                $second_sum += $interval->i * 60;
                $second_sum += $interval->s;

                $sql = "UPDATE `teams` SET `mission_finish_seconds` = {?}, `mission_finish_datetime` = NOW(), `score` = {?} WHERE `id` = {?}";
                $db->query($sql, [$second_sum, ((int) $row['score'] + 150), $userInfo['team_id']]);
                
                // бонус, если меньше 120 минут. за каждые минус 5 минут бонусные 50 баллов
				$check_minutes = $interval->i + $interval->h * 60;

            	if ($check_minutes <= 120) {
	            	$bonus = 0;

	            	if ($check_minutes >= 115 && $check_minutes <= 120) {
	            		$bonus = 50;
	            	} elseif ($check_minutes >= 110 && $check_minutes < 115) {
	            		$bonus = 100;
	            	} elseif ($check_minutes >= 105 && $check_minutes < 110) {
	            		$bonus = 150;
	            	} elseif ($check_minutes >= 100 && $check_minutes < 105) {
	            		$bonus = 200;
	            	} elseif ($check_minutes >= 95 && $check_minutes < 100) {
	            		$bonus = 250;
	            	} elseif ($check_minutes >= 90 && $check_minutes < 95) {
	            		$bonus = 300;
	            	} elseif ($check_minutes >= 85 && $check_minutes < 90) {
	            		$bonus = 350;
	            	} elseif ($check_minutes >= 80 && $check_minutes < 85) {
	            		$bonus = 400;
	            	} elseif ($check_minutes >= 75 && $check_minutes < 80) {
	            		$bonus = 450;
	            	} elseif ($check_minutes >= 70 && $check_minutes < 75) {
	            		$bonus = 500;
	            	} elseif ($check_minutes >= 65 && $check_minutes < 70) {
	            		$bonus = 550;
	            	} elseif ($check_minutes >= 60 && $check_minutes < 65) {
	            		$bonus = 600;
	            	} elseif ($check_minutes >= 55 && $check_minutes < 60) {
	            		$bonus = 650;
	            	} elseif ($check_minutes >= 50 && $check_minutes < 55) {
	            		$bonus = 700;
	            	} elseif ($check_minutes >= 45 && $check_minutes < 50) {
	            		$bonus = 750;
	            	} elseif ($check_minutes >= 40 && $check_minutes < 45) {
	            		$bonus = 800;
	            	} elseif ($check_minutes >= 35 && $check_minutes < 40) {
	            		$bonus = 850;
	            	} elseif ($check_minutes >= 30 && $check_minutes < 35) {
	            		$bonus = 900;
	            	} elseif ($check_minutes >= 25 && $check_minutes < 30) {
	            		$bonus = 950;
	            	} elseif ($check_minutes >= 20 && $check_minutes < 25) {
	            		$bonus = 1000;
	            	} elseif ($check_minutes >= 15 && $check_minutes < 20) {
	            		$bonus = 1050;
	            	} elseif ($check_minutes >= 10 && $check_minutes < 15) {
	            		$bonus = 1100;
	            	} elseif ($check_minutes >= 5 && $check_minutes < 10) {
	            		$bonus = 1150;
	            	} elseif ($check_minutes < 5) {
	            		$bonus = 1200;
	            	}

	            	if ($bonus > 0) {
	            		$sql = "UPDATE `teams` SET `score` = {?} WHERE `id` = {?}";
	            		$db->query($sql, [((int) $row['score'] + $bonus + 150), $userInfo['team_id']]);
	            	}
	            }

	            // возвращаем также к-во очков
	            $sql = "SELECT `score` FROM `teams` WHERE `id` = {?}";
	            $return['score'] = $db->selectCell($sql, [$userInfo['team_id']]);
            }

			print_r(json_encode($return));
		    break;
	}
}
