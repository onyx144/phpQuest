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

				if ($check_company_name == 'green pace' || $check_company_name == 'green pace group') {
					$return['success'] = 'ok';

					// переводы для всех языков. Для синхронизации
					$sql = "SELECT `lang_abbr`, `id` FROM `langs` WHERE `status` = {?}";
					$langs = $db->select($sql, [1]);
					if ($langs) {
						$return['success_lang'] = [];

						foreach ($langs as $lang2) {
							$translation = $lang->getWordsByPage('game', $lang2['id']);

							$return['success_lang'][$lang2['lang_abbr']]['success_input'] = $translation['text163'];
							$return['success_lang'][$lang2['lang_abbr']]['success_text'] = $translation['text164'];
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

							$return['error_lang'][$lang2['lang_abbr']]['error_input'] = $translation['text161'];
							$return['error_lang'][$lang2['lang_abbr']]['error_text'] = $translation['text162'];
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

						$return['error_lang'][$lang2['lang_abbr']]['error_input'] = $translation['text161'];
						$return['error_lang'][$lang2['lang_abbr']]['error_text'] = $translation['text162'];
					}
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
			) {
				if (
					($latitude1 == '2' && $latitude2 == '0' && $latitude3 == '0' && $longitude1 == '2' && $longitude2 == '29' && ($longitude3 == '59.999' || $longitude3 == '59,999')) || 
					($latitude1 == '2' && $latitude2 == '48' && ($latitude3 == '14.883' || $latitude3 == '14,883') && $longitude1 == '3' && $longitude2 == '39' && ($longitude3 == '54.335' || $longitude3 == '54,335')) || 
					($latitude1 == '2' && $latitude2 == '0' && $latitude3 == '0' && $longitude1 == '2' && $longitude2 == '30' && $longitude3 == '0')
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

					// сохраняем обновленный список подсказок + запоминаем новый открытый dashboard
					$sql = "UPDATE `teams` SET `active_hints` = {?}, `list_hints` = {?}, `list_hints_title_lang_var` = {?}, `list_hints_text_lang_var` = {?}, `last_dashboard` = {?}, `tools_advanced_search_engine_access` = {?} WHERE `id` = {?}";
					$db->query($sql, [json_encode($active_hints, JSON_UNESCAPED_UNICODE), json_encode($list_hints, JSON_UNESCAPED_UNICODE), 'text44', 'text45', 'african_partner', 1, $userInfo['team_id']]);

					$return['success'] = 'ok';

					/*// не в тему, но))) обновляем актуальный список доступных файлов
					$list_files = json_decode($team_info['list_files'], true);

					$list_files[] = 6;
					$list_files[] = 7;

					$list_files = array_unique($list_files);

					$sql = "UPDATE `teams` SET `list_files` = {?} WHERE `id` = {?}";
					$db->query($sql, [json_encode($list_files, JSON_UNESCAPED_UNICODE), $userInfo['team_id']]);*/
					$function->updateTeamListFiles($userInfo['team_id'], 6);
					$function->updateTeamListFiles($userInfo['team_id'], 7);

					/*// аналогично обновляем актуальный список доступных tools
					$list_tools = json_decode($team_info['list_tools'], true);

					$list_tools[] = 'advanced_search_engine';
					$list_tools[] = 'gps_coordinates';
					// $list_tools[] = 'symbol_decoder';
					// $list_tools[] = '3d_building_scan';

					$list_tools = array_unique($list_tools);

					$sql = "UPDATE `teams` SET `list_tools` = {?} WHERE `id` = {?}";
					$db->query($sql, [json_encode($list_tools, JSON_UNESCAPED_UNICODE), $userInfo['team_id']]);*/
					$function->updateTeamListTools($userInfo['team_id'], 'advanced_search_engine');
				} else {
					$return['error'] = $translation['text29'];
				}
			}

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

				if (mb_strtolower($company_name, 'UTF-8') == 'gigaconsulting' && $country == 'Nigeria' && ($date == '04.11.1997' || $date == '4.11.1997' || $date == '04.11.97' || $date == '4.11.97')) {
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

		// правильно ввели metting place - обновляем список подсказок
		case 'mettingPlaceUpdateHint':
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
			/*$lang_abbr = isset($_POST['lang_abbr']) ? strip_tags(trim($_POST['lang_abbr'])) : '';

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

					$hints_by_step = $function->getHintsByStep('3d_plan', $lang_id);
					if ($hints_by_step) {
						foreach ($hints_by_step as $hint) {
							$list_hints[] = $hint['id'];
						}
					}

					// сохраняем обновленный список подсказок + запоминаем новый открытый dashboard + теперь всегда доступен новый tools 3d scan
					$sql = "UPDATE `teams` SET `active_hints` = {?}, `list_hints` = {?}, `list_hints_title_lang_var` = {?}, `list_hints_text_lang_var` = {?}, `last_dashboard` = {?}, `tools_3d_bulding_scan_access` = {?} WHERE `id` = {?}";
					$db->query($sql, [json_encode($active_hints, JSON_UNESCAPED_UNICODE), json_encode($list_hints, JSON_UNESCAPED_UNICODE), 'text44', 'text45', 'room_name', 1, $userInfo['team_id']]);

					// добавляем новый файл к списку доступных
					$function->updateTeamListFiles($userInfo['team_id'], 8);

					// обновляем актуальный список доступных tools
					$function->updateTeamListTools($userInfo['team_id'], '3d_building_scan');

					// возвращаем чат к первоначальному состоянию. Ввод данных недоступен
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

					$sql = "UPDATE `teams` SET `chat_send_message_access` = {?} WHERE `id` = {?}";
					$db->query($sql, [0, $userInfo['team_id']]);
				}
			}

			print_r(json_encode($return));
		    break;*/

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
					in_array($street_name, ['voskresenska', 'voskresenskastreet']) && 
					$house_number == '22' && 
					mb_strtolower($city, 'UTF-8') == 'dnipro' &&
					(
						($lang_abbr == 'en' && $country == 'Ukraine') || 
						($lang_abbr == 'no' && $country == 'Ukraine')
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
