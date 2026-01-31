<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/ajax/ajax_header.php');

if (isset($_POST['op'])) {
	$return = [];

	switch ($_POST['op']) {
		// получить актуальные данные по команде
		case 'getTeamInfo':
			$return['success'] = $function->teamInfo($userInfo['team_id']);

			print_r(json_encode($return));
		    break;

		// new mission - первый экран - ввод названия миссии
		case 'dashboardNewMissionNumber':
			$lang_abbr = isset($_POST['lang_abbr']) ? strip_tags(trim($_POST['lang_abbr'])) : '';
			$mission_number = isset($_POST['mission_number']) ? strip_tags(trim($_POST['mission_number'])) : '';

			$lang_id = $lang->getLangIdByHtmlAttr($lang_abbr);
			$translation = $lang->getWordsByPage('game', $lang_id);

			// оставляем по одному пробелу
			$mission_number = preg_replace("/\s{2,}/",' ',$mission_number);

			// нижний регистр
			$mission_number = mb_strtolower($mission_number, 'UTF-8');

			// $check = ['g.e.m', 'g. e. m', 'gem', 'global eco mission'];
			$check = ['g.e.m.', 'g. e. m.', 'global eco mission'];

			if (in_array($mission_number, $check)) {
				$return['success'] = 'ok';

				// Синхронизация происходит через сокеты - вебхук не нужен
				// $function->addTeamActionHistory($userInfo['team_id'], 16);
			} else {
				// количество совпадений трех слов
				$qt = 0;

				$parts = explode(' ', $mission_number);
				foreach ($parts as $part) {
					if ($part == 'g' || $part == 'g.' || $part == 'global') {
						$qt++;
					} elseif ($part == 'e' || $part == 'e.' || $part == 'eco') {
						$qt++;
					}if ($part == 'm' || $part == 'm.' || $part == 'mission') {
						$qt++;
					}
				}

				if ($qt == 3) {
					$return['success'] = 'ok';

					// $function->addTeamActionHistory($userInfo['team_id'], 16);
				} else {
					// $return['error'] = $translation['text41'];

					// переводы для всех языков. Для синхронизации
					$sql = "SELECT `lang_abbr`, `id` FROM `langs` WHERE `status` = {?}";
					$langs = $db->select($sql, [1]);
					if ($langs) {
						$return['error_lang'] = [];

						foreach ($langs as $lang2) {
							$translation = $lang->getWordsByPage('game', $lang2['id']);

							$return['error_lang'][$lang2['lang_abbr']] = $translation['text41'];
						}
					}
				}
			}

			print_r(json_encode($return));
		    break;

		// игроки приняли миссию - обновляем список подсказок
		case 'acceptMissionUpdateHint':
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

					$hints_by_step = $function->getHintsByStep('accept_mission', $lang_id);
					if ($hints_by_step) {
						foreach ($hints_by_step as $hint) {
							$list_hints[] = $hint['id'];
						}
					}

					// сохраняем обновленный список подсказок + фиксируем время принятии миссии + запоминаем новый открытый database + запоминаем новый открытый dashboard
					$sql = "UPDATE `teams` SET `active_hints` = {?}, `list_hints` = {?}, `list_hints_title_lang_var` = {?}, `list_hints_text_lang_var` = {?}, `mission_accept_datetime` = NOW(), `last_databases` = {?}, `last_dashboard` = {?} WHERE `id` = {?}";
					$db->query($sql, [json_encode($active_hints, JSON_UNESCAPED_UNICODE), json_encode($list_hints, JSON_UNESCAPED_UNICODE), 'text44', 'text45', 'databases_start_four', 'company_name', $userInfo['team_id']]);

					// возвращаем обновленное состояние подсказок справа и слева
					// $return = $function->getHintPageHints($userInfo['team_id'], $lang_id);
					$return['success'] = 'ok';

					/*// не в тему, но))) обновляем актуальный список доступных файлов
					$list_files = json_decode($team_info['list_files'], true);

					$list_files[] = 2;
					$list_files[] = 3;

					$list_files = array_unique($list_files);

					$sql = "UPDATE `teams` SET `list_files` = {?} WHERE `id` = {?}";
					$db->query($sql, [json_encode($list_files, JSON_UNESCAPED_UNICODE), $userInfo['team_id']]);*/
					$function->updateTeamListFiles($userInfo['team_id'], 2);
					$function->updateTeamListFiles($userInfo['team_id'], 3);

					// аналогично обновляем актуальный список доступных баз данных
					/*$list_databases = json_decode($team_info['list_databases'], true);

					$list_databases[] = 'personal_files';
					$list_databases[] = 'car_register';
					$list_databases[] = 'mobile_calls';

					$list_databases = array_unique($list_databases);

					$sql = "UPDATE `teams` SET `list_databases` = {?} WHERE `id` = {?}";
					$db->query($sql, [json_encode($list_databases, JSON_UNESCAPED_UNICODE), $userInfo['team_id']]);*/
					$function->updateTeamListDatabases($userInfo['team_id'], 'personal_files');
					$function->updateTeamListDatabases($userInfo['team_id'], 'car_register');
					$function->updateTeamListDatabases($userInfo['team_id'], 'mobile_calls');
				} else {
					$return['error'] = $translation['text29'];
				}
			}

			print_r(json_encode($return));
		    break;

		// Обновить текстовые данные в информации о команде
		case 'saveTeamTextField':
			$field = isset($_POST['field']) ? strip_tags(trim($_POST['field'])) : '';
			$val = isset($_POST['val']) ? strip_tags(trim($_POST['val'])) : '';

			if (!empty($field)) {
				if ($field == 'car_register_country_id' || $field == 'african_partner_country_id' || $field == 'metting_place_country_id') {
					$sql = "SELECT `country_id` FROM `countries_description` WHERE `name` = {?} LIMIT 1";
					$val = $db->selectCell($sql, [$val]);

					$return['country_id'] = $val;

					// возвращаем значения на разных языках
					$sql = "SELECT `lang_abbr`, `id` FROM `langs` WHERE `status` = {?}";
					$langs = $db->select($sql, [1]);
					if ($langs) {
						$return['country_lang'] = [];

						foreach ($langs as $lang2) {
							$sql = "SELECT `name` FROM `countries_description` WHERE `country_id` = {?} AND `lang_id` = {?}";
							$country_name = $db->selectCell($sql, [$val, $lang2['id']]);
							if (!empty($country_name)) {
								$return['country_lang'][$lang2['lang_abbr']] = $country_name;
							}
						}
					}
				} elseif ($field == 'car_register_date' || $field == 'african_partner_date' || $field == 'bank_transactions_date') {
					$val = $function->fromRusDatetimeToEng($val);
				} elseif ($field == 'mobile_calls_country_id') {
					// возвращаем значения на разных языках
					$sql = "SELECT `lang_abbr`, `id` FROM `langs` WHERE `status` = {?}";
					$langs = $db->select($sql, [1]);
					if ($langs) {
						$return['country_lang'] = [];

						foreach ($langs as $lang2) {
							$sql = "
								SELECT c.code, cd.name
								FROM countries c
								JOIN countries_description cd ON c.id = cd.country_id
								WHERE c.id = {?}
								AND cd.lang_id = {?}
								LIMIT 1
							";
							$country_row = $db->selectCell($sql, [$val, $lang2['id']]);
							if (!empty($country_row)) {
								// $return['country_lang'][$lang2['lang_abbr']] = '+' . $country_row['code'] . ' ' . $country_row['name'];
								$return['country_lang'][$lang2['lang_abbr']] = $val;
							}
						}
					}
				}

				$sql = "UPDATE `teams` SET `" . $field . "` = '" . $val . "' WHERE `id` = {?}";
				$db->query($sql, [$userInfo['team_id']]);

				$return['success'] = 'ok';
			} else {
				$return['error'] = 'ok';
			}

			print_r(json_encode($return));
		    break;

		// обновить progress mission
		case 'updateProgressMission':
			$progress_mission = isset($_POST['progress_mission']) ? (int) $_POST['progress_mission'] : 0;

			$sql = "UPDATE `teams` SET `progress_percent` = {?} WHERE `id` = {?}";
			$db->query($sql, [$progress_mission, $userInfo['team_id']]);

			$return['success'] = 'ok';

			print_r(json_encode($return));
		    break;

		// последнее действие команды
		case 'lastTeamAction':
			$sql = "SELECT `action_id`, `user_id` FROM `team_history_action` WHERE `team_id` = {?} ORDER BY `id` DESC LIMIT 1";
			$action = $db->selectRow($sql, [$userInfo['team_id']]);
			$return['success'] = $action['action_id'];
			$return['success_user'] = $action['user_id'];

			print_r(json_encode($return));
		    break;

		// обнуление результатов
		case 'clearTeamResults':
			// $sql = "TRUNCATE TABLE `chat_messages`";
		 //    $db->query($sql);

			// $sql = "TRUNCATE TABLE `chat_messages_description`";
		 //    $db->query($sql);

		 //    $sql = "DELETE FROM `chat_messages` WHERE `team_id` = {?}";
			// $db->query($sql, [1]);

			$team_id = 105;
			$team_code = 'M1gCJrbXaK';

			$sql = "SELECT `id` FROM `chat_messages` WHERE `team_id` = {?}";
			$messages = $db->select($sql, [$team_id]);
			if ($messages) {
				foreach ($messages as $message) {
					$sql = "DELETE FROM `chat_messages_description` WHERE `chat_message_id` = {?}";
					$db->query($sql, [$message['id']]);
				}
			}

		    $sql = "DELETE FROM `chat_messages` WHERE `team_id` = {?}";
			$db->query($sql, [$team_id]);

		    $sql = "
		        UPDATE `teams`
		        SET `team_name` = {?},
		            `create` = NOW(),
		            `score` = {?},
		            `progress_percent` = {?},
		            `dashboard` = {?},
		            `timer_second` = {?},
		            `active_hints` = {?},
		            `list_hints` = {?},
		            `list_hints_title_lang_var` = {?},
		            `list_hints_text_lang_var` = {?},
		            `active_files` = {?},
		            `list_files` = {?},
		            `active_databases` = {?},
		            `list_databases` = {?},
		            `last_action_id` = {?},
		            `calls_outgoing_id` = {?},
		            `active_calls` = {?},
		            `car_register_country_id` = {?},
		            `car_register_date` = {?},
		            `car_register_print_text_huilov` = {?},
		            `private_individuals_print_text_huilov` = {?},
		            `ceo_database_print_text_rod` = {?},
		            `mobile_calls_country_id` = {?},
		            `mobile_calls_number` = {?},
		            `mobile_calls_print_messages` = {?},
		            `mission_accept_datetime` = {?},
		            `last_databases` = {?},
		            `last_dashboard` = {?},
		            `last_window_open` = {?},
		            `last_type_tabs` = {?},
		            `last_calls` = {?},
		            `last_tools` = {?},
		            `open_chat` = {?},
		            `view_gem` = {?},
		            `view_call_jane_btn` = {?},
		            `view_call_mobile_btn` = {?},
		            `open_call_mobile_btn` = {?},
		            `active_tools` = {?},
		            `list_tools` = {?},
		            `tools_advanced_search_engine_access` = {?},
		            `tools_symbol_decoder_access` = {?},
		            `tools_3d_bulding_scan_access` = {?},
		            `african_partner_country_id` = {?},
		            `african_partner_date` = {?},
		            `metting_place_country_id` = {?},
		            `bank_transactions_date` = {?},
		            `databases_bank_transactions_access` = {?},
		            `chat_send_message_access` = {?},
		            `tools_building_scan_degree` = {?},
		            `tools_building_scan_input1` = {?},
		            `tools_building_scan_input2` = {?},
		            `tools_building_scan_input3` = {?},
		            `tools_building_scan_input4` = {?},
		            `tools_building_scan_input5` = {?},
		            `tools_building_scan_address_dot_n` = {?},
		            `tools_building_scan_address_dot_s` = {?},
		            `tools_building_scan_address_dot_e` = {?},
		            `tools_building_scan_address_dot_w` = {?},
		            `tools_building_scan_address_checkbox_n` = {?},
		            `tools_building_scan_address_checkbox_s` = {?},
		            `tools_building_scan_address_checkbox_e` = {?},
		            `tools_building_scan_address_checkbox_w` = {?},
		            `tools_secret_office_access` = {?},
		            `dashboard_minigame_access` = {?},
		            `dashboard_minigame_active_step` = {?},
		            `dashboard_interpol_access` = {?},
		            `mission_finish_seconds` = {?},
		            `mission_finish_datetime` = {?},
		            `hints_open` = {?},
		            `status` = {?}
		        WHERE `id` = {?}
		    ";
		    $db->query($sql, ['', 0, 0, 'dashboard', 0, json_encode([], JSON_UNESCAPED_UNICODE), json_encode([1,2,3], JSON_UNESCAPED_UNICODE), 'text26', 'text27', json_encode([], JSON_UNESCAPED_UNICODE), json_encode([1], JSON_UNESCAPED_UNICODE), json_encode([], JSON_UNESCAPED_UNICODE), json_encode([], JSON_UNESCAPED_UNICODE), 2, 2, json_encode([['id'=>1,'datetime'=>'']], JSON_UNESCAPED_UNICODE), 0, NULL, 0, 0, 0, 0, NULL, 0, NULL, 'no_access', 'accept_new_mission', 'main', 'dashboard', 'no_access', 'no_access', 'no', 0, 0, 0, 0, json_encode([], JSON_UNESCAPED_UNICODE), json_encode([], JSON_UNESCAPED_UNICODE), 0, 0, 0, 0, NULL, 0, NULL, 0, 0, '0', '0', '0', '0', '0', '0', '2', '2', '2', '2', 0, 0, 0, 0, 0, 0, 1, 0, 0, NULL, 0, 1, $team_id]);

		 //    $sql = "TRUNCATE TABLE `team_history_action`";
			// $db->query($sql);

			$sql = "DELETE FROM `team_socket_action` WHERE `team_id` = {?}";
			$db->query($sql, [$team_id]);

			$sql = "DELETE FROM `users` WHERE `team_id` = {?}";
			$db->query($sql, [$team_id]);

			$sql = "UPDATE `admin_sales` SET `datetime_sale` = NOW() WHERE `team_code` = {?}";
			$db->query($sql, [$team_code]);

			$return['success'] = 'ok';

			print_r(json_encode($return));
		    break;

		/*// обнуление результатов
		case 'clearTeamResults2':
			$sql = "TRUNCATE TABLE `chat_messages`";
		    $db->query($sql);
		    
			$sql = "TRUNCATE TABLE `chat_messages_description`";
		    $db->query($sql);

		    $sql = "
		        UPDATE `teams`
		        SET `team_name` = {?},
		            `create` = NOW(),
		            `score` = {?},
		            `progress_percent` = {?},
		            `dashboard` = {?},
		            `timer_second` = {?},
		            `active_hints` = {?},
		            `list_hints` = {?},
		            `list_hints_title_lang_var` = {?},
		            `list_hints_text_lang_var` = {?},
		            `active_files` = {?},
		            `list_files` = {?},
		            `active_databases` = {?},
		            `list_databases` = {?},
		            `last_action_id` = {?},
		            `calls_outgoing_id` = {?},
		            `active_calls` = {?},
		            `car_register_country_id` = {?},
		            `car_register_date` = {?},
		            `car_register_print_text_huilov` = {?},
		            `private_individuals_print_text_huilov` = {?},
		            `ceo_database_print_text_rod` = {?},
		            `mobile_calls_country_id` = {?},
		            `mobile_calls_number` = {?},
		            `mobile_calls_print_messages` = {?},
		            `mission_accept_datetime` = {?},
		            `last_databases` = {?},
		            `last_dashboard` = {?},
		            `last_window_open` = {?},
		            `last_type_tabs` = {?},
		            `last_calls` = {?},
		            `last_tools` = {?},
		            `open_chat` = {?},
		            `view_gem` = {?},
		            `view_call_jane_btn` = {?},
		            `view_call_mobile_btn` = {?},
		            `open_call_mobile_btn` = {?},
		            `active_tools` = {?},
		            `list_tools` = {?},
		            `tools_advanced_search_engine_access` = {?},
		            `tools_symbol_decoder_access` = {?},
		            `tools_3d_bulding_scan_access` = {?},
		            `african_partner_country_id` = {?},
		            `african_partner_date` = {?},
		            `metting_place_country_id` = {?},
		            `bank_transactions_date` = {?},
		            `databases_bank_transactions_access` = {?},
		            `chat_send_message_access` = {?},
		            `tools_building_scan_degree` = {?},
		            `tools_building_scan_degree` = {?},
		            `tools_building_scan_input1` = {?},
		            `tools_building_scan_input2` = {?},
		            `tools_building_scan_input3` = {?},
		            `tools_building_scan_input4` = {?},
		            `tools_building_scan_input5` = {?},
		            `tools_building_scan_address_dot_n` = {?},
		            `tools_building_scan_address_dot_s` = {?},
		            `tools_building_scan_address_dot_e` = {?},
		            `tools_building_scan_address_dot_w` = {?},
		            `tools_building_scan_address_checkbox_n` = {?},
		            `tools_building_scan_address_checkbox_s` = {?},
		            `tools_building_scan_address_checkbox_e` = {?},
		            `tools_building_scan_address_checkbox_w` = {?},
		            `tools_secret_office_access` = {?},
		            `dashboard_minigame_access` = {?},
		            `dashboard_minigame_active_step` = {?},
		            `dashboard_interpol_access` = {?},
		            `mission_finish_seconds` = {?},
		            `mission_finish_datetime` = {?},
		            `hints_open` = {?}
		        WHERE `id` = {?}
		    ";
		    $db->query($sql, ['', 0, 0, 'dashboard', 0, json_encode([], JSON_UNESCAPED_UNICODE), json_encode([1,2,3], JSON_UNESCAPED_UNICODE), 'text26', 'text27', json_encode([], JSON_UNESCAPED_UNICODE), json_encode([1], JSON_UNESCAPED_UNICODE), json_encode([], JSON_UNESCAPED_UNICODE), json_encode([], JSON_UNESCAPED_UNICODE), 2, 2, json_encode([['id'=>1,'datetime'=>'']], JSON_UNESCAPED_UNICODE), 0, NULL, 0, 0, 0, 0, NULL, 0, NULL, 'no_access', 'accept_new_mission', 'main', 'dashboard', 'no_access', 'no_access', 'no', 0, 0, 0, 0, json_encode([], JSON_UNESCAPED_UNICODE), json_encode([], JSON_UNESCAPED_UNICODE), 0, 0, 0, 0, NULL, 0, NULL, 0, 0, '0', '0', '0', '0', '0', '0', '2', '2', '2', '2', 0, 0, 0, 0, 0, 0, 1, 0, 0, NULL, 0, 2]);

		    $sql = "TRUNCATE TABLE `team_history_action`";
			$db->query($sql);

			$sql = "DELETE FROM `team_socket_action` WHERE `team_id` = {?}";
			$db->query($sql, [2]);

			$sql = "DELETE FROM `users` WHERE `team_id` = {?}";
			$db->query($sql, [2]);

			$return['success'] = 'ok';

			print_r(json_encode($return));
		    break;*/

		/*// перезапуск сервера
		case 'serverRestore':
			// if (!$function->isDaemonActive('/home/admin/web/digital-game2.example.com/public_html/tmp/socket.pid')) {
			//     $output = null;

			//     exec("nohup php /home/admin/web/digital-game2.example.com/public_html/server_game.php &", $output);
			// }



			// $pidfile = '/home/admin/web/digital-game2.example.com/public_html/tmp/socket.pid';

			// if (!$function->isDaemonActive('/home/admin/web/digital-game2.example.com/public_html/tmp/socket.pid')) {
			// 	if (is_file($pidfile)) {
	  //       		$pid = file_get_contents($pidfile);

			// 		exec("kill -9 $pid");

			// 		$output = null;
			// 		exec("nohup php /home/admin/web/digital-game2.example.com/public_html/server_game.php &", $output);
			// 	}
			// }




			// АКТИВНАЯ ВЕРСИЯ НИЖЕ 
			// $pidfile = '/home/admin/web/digital-game2.example.com/public_html/tmp/socket.pid';

			// if (is_file($pidfile)) {
   //      		$pid = file_get_contents($pidfile);

			// 	exec("kill -9 $pid");

			// 	$output = null;
			// 	exec("nohup php /home/admin/web/digital-game2.example.com/public_html/server_game.php &", $output);
			// }
			// $return['success'] = 'ok';

			print_r(json_encode($return));
		    break;*/

		// загрузить актуальные результаты команд
		case 'uploadActualHighscore':
			$type = isset($_POST['type']) ? strip_tags(trim($_POST['type'])) : 'alltime';
			$lang_abbr = isset($_POST['lang_abbr']) ? strip_tags(trim($_POST['lang_abbr'])) : '';

			$lang_id = $lang->getLangIdByHtmlAttr($lang_abbr);
			$translation = $lang->getWordsByPage('game', $lang_id);

			if ($type == 'alltime') {
				$sql = "SELECT `team_name`, `progress_percent`, `mission_accept_datetime`, `mission_finish_datetime`, `hints_open`, `score`, `mission_finish_seconds`, `id` FROM `teams` WHERE `mission_accept_datetime` != {?} AND `mission_accept_datetime` != {?} AND `mission_accept_datetime` != {?} AND `status` = {?} ORDER BY cast(`score` as unsigned) DESC, `mission_finish_seconds` DESC LIMIT 100";
				$results = $db->select($sql, [NULL, '', '0000-00-00 00:00:00', 1]);
			} else {
				$sql = "SELECT `team_name`, `progress_percent`, `mission_accept_datetime`, `mission_finish_datetime`, `hints_open`, `score`, `mission_finish_seconds`, `id` FROM `teams` WHERE  `mission_accept_datetime` != {?} AND `mission_accept_datetime` != {?} AND `mission_accept_datetime` != {?} AND (DATE(`mission_accept_datetime`) = {?} OR DATE(`mission_finish_datetime`) = {?}) AND `status` = {?} ORDER BY cast(`score` as unsigned) DESC, `mission_finish_seconds` DESC LIMIT 100";
				$results = $db->select($sql, [NULL, '', '0000-00-00 00:00:00', date('Y-m-d'), date('Y-m-d'), 1]);
			}

			$return['success'] = '';

			if ($results) {
				$rank = 1;

				foreach ($results as $result_team) {
					$percent = (!empty($result_team['mission_finish_seconds']) || (int) $result_team['progress_percent'] == 100) ? 100 : (int) $result_team['progress_percent'];
					$percent_text = (!empty($result_team['mission_finish_seconds']) || (int) $result_team['progress_percent'] == 100) ? $translation['text301'] : $result_team['progress_percent'] . '%';

					$percent_class = '';

					if ($percent >= 35 && $percent <= 69) {
						$percent_class = ' highscore_table_cell_status_percent_yellow';
					} elseif ($percent >= 70 && $percent <= 99) {
						$percent_class = ' highscore_table_cell_status_percent_green_light';
					} elseif ($percent == 100) {
						$percent_class = ' highscore_table_cell_status_percent_green_dark';
					}

					$old = new DateTime($result_team['mission_accept_datetime']);
					if (!empty($result_team['mission_finish_seconds'])) {
                    	$now = new DateTime($result_team['mission_finish_datetime']);
                    } else {
                    	$now = new DateTime();
                    }

                    $interval = $old->diff($now);

                    $return['second'] = $interval->s;
                    $return['minute'] = $interval->i;
                    $return['hours'] = $interval->h;

                    if ($result_team['id'] == $userInfo['team_id']) {
	                    $second_sum = $interval->days * 24 * 60 * 60;
	                    $second_sum += $interval->h * 60 * 60;
	                    $second_sum += $interval->i * 60;
	                    $second_sum += $interval->s;
	                } else {
	                	$second_sum = 0;
	                }

	                /*if ($interval->days > 0) {
	                	$print_timer = '<span class="highscore_table_cell_time_hours">23</span>:<span class="highscore_table_cell_time_minute">59</span>:<span class="highscore_table_cell_time_second">59</span>';
	                } else {*/
	                	$print_timer = '<span class="highscore_table_cell_time_hours">' . str_pad($interval->h, 2, '0', STR_PAD_LEFT) . '</span>:<span class="highscore_table_cell_time_minute">' . str_pad($interval->i, 2, '0', STR_PAD_LEFT) . '</span>:<span class="highscore_table_cell_time_second">' . str_pad($interval->s, 2, '0', STR_PAD_LEFT) . '</span>';
	                // }

					$return['success'] .= '<div class="highscore_table_row' . ($result_team['id'] == $userInfo['team_id'] ? ' highscore_table_row_cur_team' : '') . '">
												<div class="highscore_table_body_cell highscore_table_cell_rank">' . $rank . '</div>
												<div class="highscore_table_body_cell highscore_table_cell_team" title="' . htmlspecialchars($result_team['team_name'], ENT_QUOTES) . '">' . $result_team['team_name'] . '</div>
												<div class="highscore_table_body_cell highscore_table_cell_status">
													<div class="highscore_table_cell_status_line">
														<div class="highscore_table_cell_status_percent' . $percent_class . '" style="width: ' . $percent . '%;"></div>
														<div class="highscore_table_cell_status_text">' . $percent_text . '</div>
													</div>
												</div>
												<div class="highscore_table_body_cell highscore_table_cell_time" data-timer="' . $second_sum . '">' . $print_timer . '</div>
												<div class="highscore_table_body_cell highscore_table_cell_hints">' . $result_team['hints_open'] . '</div>
												<div class="highscore_table_body_cell highscore_table_cell_score">' . $result_team['score'] . '</div>
											</div>';

					$rank++;
				}
			} else {
				$return['success'] .= '<div class="highscore_table_row">
											<div class="highscore_table_body_cell highscore_table_cell_empty_results">' . $translation['text296'] . '</div>
										</div>';
			}
			
			print_r(json_encode($return));
		    break;

		// Синхронизация входящего звонка для всех пользователей команды
		case 'syncIncomingCall':
			$call_state = isset($_POST['call_state']) ? strip_tags(trim($_POST['call_state'])) : ''; // 'open', 'accept', 'reject', 'close'
			$call_type = isset($_POST['call_type']) ? strip_tags(trim($_POST['call_type'])) : ''; // тип звонка (например, 'new_mission', 'company_investigate', etc.)
			
			if (!empty($call_state) && !empty($call_type)) {
				// Сохраняем состояние звонка в БД для команды
				$sql = "UPDATE `teams` SET `incoming_call_state` = {?}, `incoming_call_type` = {?}, `incoming_call_datetime` = NOW() WHERE `id` = {?}";
				$db->query($sql, [$call_state, $call_type, $userInfo['team_id']]);
				
				// Отправляем вебхук всем пользователям команды через сокет
				// Сообщение будет транслироваться через server_game.php всем подключенным клиентам команды
				$return['success'] = 'ok';
				$return['call_state'] = $call_state;
				$return['call_type'] = $call_type;
			} else {
				$return['error'] = 'Invalid parameters';
			}
			
			print_r(json_encode($return));
			break;

		// Получить текущее состояние входящего звонка для команды
		case 'getIncomingCallState':
			$team_info = $function->teamInfo($userInfo['team_id']);
			if ($team_info) {
				$return['success'] = 'ok';
				$return['call_state'] = !empty($team_info['incoming_call_state']) ? $team_info['incoming_call_state'] : 'none';
				$return['call_type'] = !empty($team_info['incoming_call_type']) ? $team_info['incoming_call_type'] : '';
				$return['call_datetime'] = !empty($team_info['incoming_call_datetime']) ? $team_info['incoming_call_datetime'] : '';
			} else {
				$return['error'] = 'Team not found';
			}
			
			print_r(json_encode($return));
			break;
	}
}
