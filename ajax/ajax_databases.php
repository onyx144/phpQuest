<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/ajax/ajax_header.php');

if (isset($_POST['op'])) {
	$return = [];

	switch ($_POST['op']) {
		// загрузить конкретный экран (с переключателем табов) для databases
		case 'uploadTypeTabsDatabasesStep':
			$step = isset($_POST['step']) ? strip_tags(trim($_POST['step'])) : 'no_access';
			$database = !empty($_POST['database']) ? strip_tags(trim($_POST['database'])) : false;

			$lang_abbr = isset($_POST['lang_abbr']) ? strip_tags(trim($_POST['lang_abbr'])) : '';
			$lang_id = $lang->getLangIdByHtmlAttr($lang_abbr);

			// запоминаем последнюю открытую database
			$sql = "UPDATE `teams` SET `last_databases` = {?} WHERE `id` = {?}";
			$db->query($sql, [$step, $userInfo['team_id']]);

			$return = $function->uploadTypeTabsDatabasesStep($step, $lang_id, $userInfo['team_id']);

			// обновляем к-во непрочитанных баз данных
			$return['qt_databases'] = 0;

			$team_info = $function->teamInfo($userInfo['team_id']);
			if ($team_info) {
				// названия баз данных, которые уже прочитаны/открыты
				$active_databases = json_decode($team_info['active_databases'], true);

				// названия баз данных, которые доступны для просмотра
				$list_databases = json_decode($team_info['list_databases'], true);

				if ($database && $database != 'false' && $database != 'no_access') {
					$active_databases[] = $database;
					$active_databases = array_unique($active_databases);
				}

				foreach ($list_databases as $database) {
					if (!in_array($database, $active_databases)) {
						$return['qt_databases']++;
					}
				}

				// обновляем значение в бд
				$sql = "UPDATE `teams` SET `active_databases` = {?} WHERE `id` = {?}";
				$db->query($sql, [json_encode($active_databases, JSON_UNESCAPED_UNICODE), $userInfo['team_id']]);
			}

			print_r(json_encode($return));
		    break;

		// Обновить к-во непрочитанных баз данных
		case 'updateDontOpenDatabasesQt':
			$return['success'] = 0;

			$team_info = $function->teamInfo($userInfo['team_id']);
			if ($team_info) {
				// названия баз данных, которые уже прочитаны/открыты
				$active_databases = json_decode($team_info['active_databases'], true);

				// названия баз данных, которые доступны для просмотра
				$list_databases = json_decode($team_info['list_databases'], true);

				foreach ($list_databases as $database) {
					if (!in_array($database, $active_databases)) {
						$return['success']++;
					}
				}
			}

			print_r(json_encode($return));
		    break;

		// databases - car register. search1. Проверка правильности ввода данных
		case 'validateCarRegisterSearch':
			$license_plate = !empty($_POST['license_plate']) ? strip_tags(trim($_POST['license_plate'])) : false;
			$country = !empty($_POST['country']) ? strip_tags(trim($_POST['country'])) : false;
			$date = !empty($_POST['date']) ? strip_tags(trim($_POST['date'])) : false;
			$lang_abbr = isset($_POST['lang_abbr']) ? strip_tags(trim($_POST['lang_abbr'])) : 'en';

			$lang_id = $lang->getLangIdByHtmlAttr($lang_abbr);
			$translation = $lang->getWordsByPage('game', $lang_id);

			if (!empty($license_plate) && !empty($country) && !empty($date)) {
				if (
					mb_strtolower($license_plate) == 'stalin' && 
					(
						($lang_abbr == 'en' && $country == 'Russia') || 
						($lang_abbr == 'no' && $country == 'Russland')
					) &&
					($date == '30.08.2022' || $date == '30.8.2022' || $date == '30.08.22' || $date == '30.8.22')
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

							$return['error_lang'][$lang2['lang_abbr']]['error_input'] = $translation['text89'];
							$return['error_lang'][$lang2['lang_abbr']]['error_text'] = $translation['text88'];
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

						$return['error_lang'][$lang2['lang_abbr']]['error_input'] = $translation['text89'];
						$return['error_lang'][$lang2['lang_abbr']]['error_text'] = $translation['text88'];
					}
				}
			}

			print_r(json_encode($return));
		    break;

		// databases - personal files - private individuals. Проверка правильности ввода данных
		case 'validatePersonalFilesPrivateIndividualsSearch':
			$firstname = !empty($_POST['firstname']) ? strip_tags(trim($_POST['firstname'])) : false;
			$lastname = !empty($_POST['lastname']) ? strip_tags(trim($_POST['lastname'])) : false;
			$lang_abbr = isset($_POST['lang_abbr']) ? strip_tags(trim($_POST['lang_abbr'])) : 'en';

			$lang_id = $lang->getLangIdByHtmlAttr($lang_abbr);
			$translation = $lang->getWordsByPage('game', $lang_id);

			if (!empty($firstname) && !empty($lastname)) {
				if (mb_strtolower($firstname, 'UTF-8') == 'vladimir' && mb_strtolower($lastname, 'UTF-8') == 'pupkin') {
					$return['success'] = 'ok';
				} else {
					// переводы для всех языков. Для синхронизации
					$sql = "SELECT `lang_abbr`, `id` FROM `langs` WHERE `status` = {?}";
					$langs = $db->select($sql, [1]);
					if ($langs) {
						$return['error_lang'] = [];

						foreach ($langs as $lang2) {
							$translation = $lang->getWordsByPage('game', $lang2['id']);

							$return['error_lang'][$lang2['lang_abbr']]['error_input'] = $translation['text89'];
							$return['error_lang'][$lang2['lang_abbr']]['error_text'] = $translation['text88'];
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

						$return['error_lang'][$lang2['lang_abbr']]['error_input'] = $translation['text89'];
						$return['error_lang'][$lang2['lang_abbr']]['error_text'] = $translation['text88'];
					}
				}
			}

			print_r(json_encode($return));
		    break;

		// databases - mobile calls. Проверка правильности ввода данных
		case 'validateMobileCalls':
			$country_code = !empty($_POST['country_code']) ? strip_tags(trim($_POST['country_code'])) : false;
			$number = !empty($_POST['number']) ? strip_tags(trim($_POST['number'])) : false;
			$lang_abbr = isset($_POST['lang_abbr']) ? strip_tags(trim($_POST['lang_abbr'])) : 'en';

			$lang_id = $lang->getLangIdByHtmlAttr($lang_abbr);
			$translation = $lang->getWordsByPage('game', $lang_id);

			if (!empty($country_code) && !empty($number)) {
				if (($country_code == '167' || $country_code == 167 || $country_code == '102' || $country_code == 102) && ($number == '94054421337' || $number == '794054421337' || $number == '+794054421337' || $number == '+94054421337')) {
					$return['success'] = 'ok';
				} else {
					// переводы для всех языков. Для синхронизации
					$sql = "SELECT `lang_abbr`, `id` FROM `langs` WHERE `status` = {?}";
					$langs = $db->select($sql, [1]);
					if ($langs) {
						$return['error_lang'] = [];

						foreach ($langs as $lang2) {
							$translation = $lang->getWordsByPage('game', $lang2['id']);

							$return['error_lang'][$lang2['lang_abbr']]['error_input'] = $translation['text143'];
							$return['error_lang'][$lang2['lang_abbr']]['error_text'] = $translation['text144'];
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

						$return['error_lang'][$lang2['lang_abbr']]['error_input'] = $translation['text143'];
						$return['error_lang'][$lang2['lang_abbr']]['error_text'] = $translation['text144'];
					}
				}
			}

			print_r(json_encode($return));
		    break;

		// databases - personal files - ceo database. Проверка правильности ввода данных
		case 'validatePersonalFilesCeoDatabaseSearch':
			$firstname = !empty($_POST['firstname']) ? strip_tags(trim($_POST['firstname'])) : false;
			$lastname = !empty($_POST['lastname']) ? strip_tags(trim($_POST['lastname'])) : false;
			$lang_abbr = isset($_POST['lang_abbr']) ? strip_tags(trim($_POST['lang_abbr'])) : 'en';

			$lang_id = $lang->getLangIdByHtmlAttr($lang_abbr);
			$translation = $lang->getWordsByPage('game', $lang_id);

			if (!empty($firstname) && !empty($lastname)) {
				if (mb_strtolower($firstname, 'UTF-8') == 'axel' && mb_strtolower($lastname, 'UTF-8') == 'rod') {
					$return['success'] = 'ok';
				} else {
					// переводы для всех языков. Для синхронизации
					$sql = "SELECT `lang_abbr`, `id` FROM `langs` WHERE `status` = {?}";
					$langs = $db->select($sql, [1]);
					if ($langs) {
						$return['error_lang'] = [];

						foreach ($langs as $lang2) {
							$translation = $lang->getWordsByPage('game', $lang2['id']);

							$return['error_lang'][$lang2['lang_abbr']]['error_input'] = $translation['text89'];
							$return['error_lang'][$lang2['lang_abbr']]['error_text'] = $translation['text88'];
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

						$return['error_lang'][$lang2['lang_abbr']]['error_input'] = $translation['text89'];
						$return['error_lang'][$lang2['lang_abbr']]['error_text'] = $translation['text88'];
					}
				}
			}

			print_r(json_encode($return));
		    break;

		// databases - bank transactions. Проверка правильности ввода данных
		case 'validateBankTransactionsSearch':
			$digits = !empty($_POST['digits']) ? strip_tags(trim($_POST['digits'])) : false;
			$amount = !empty($_POST['amount']) ? strip_tags(trim($_POST['amount'])) : false;
			$date = !empty($_POST['date']) ? strip_tags(trim($_POST['date'])) : false;
			$lang_abbr = isset($_POST['lang_abbr']) ? strip_tags(trim($_POST['lang_abbr'])) : 'en';

			$lang_id = $lang->getLangIdByHtmlAttr($lang_abbr);
			$translation = $lang->getWordsByPage('game', $lang_id);

			// последние два символа года
			$year_small = substr(date('Y'), -2);

			if (!empty($digits) && !empty($amount) && !empty($date)) {
				if (
					$digits == '5684' && $amount == '95' && 
					(
						$date == date('d.m.Y') || 
						$date == (int) date('d') . '.' . date('m') . '.' . date('Y') || 
						$date == date('d') . '.' . (int) date('m') . '.' . date('Y') || 
						$date == (int) date('d') . '.' . (int) date('m') . '.' . date('Y') || 
						$date == (int) date('d') . '.' . date('m') . '.' . $year_small || 
						$date == date('d') . '.' . (int) date('m') . '.' . $year_small || 
						$date == (int) date('d') . '.' . (int) date('m') . '.' . $year_small
					)
				) {
					$return['success'] = 'ok';

					// запоминаем, что теперь всегда доступны результаты bank transactions
					$sql = "UPDATE `teams` SET `databases_bank_transactions_access` = {?} WHERE `id` = {?}";
					$db->query($sql, [1, $userInfo['team_id']]);

					/*// обновляем актуальный список доступных tools
					$list_tools = json_decode($team_info['list_tools'], true);

					// $list_tools[] = 'advanced_search_engine';
					// $list_tools[] = 'gps_coordinates';
					$list_tools[] = 'symbol_decoder';
					// $list_tools[] = '3d_building_scan';

					$list_tools = array_unique($list_tools);

					$sql = "UPDATE `teams` SET `list_tools` = {?} WHERE `id` = {?}";
					$db->query($sql, [json_encode($list_tools, JSON_UNESCAPED_UNICODE), $userInfo['team_id']]);*/
					// $function->updateTeamListTools($userInfo['team_id'], 'symbol_decoder');

					// обновляем список подсказок
					$active_hints = []; // список открытых

					// список доступных
					$list_hints = [];

					$hints_by_step = $function->getHintsByStep('metting_place', $lang_id);
					if ($hints_by_step) {
						foreach ($hints_by_step as $hint) {
							$list_hints[] = $hint['id'];
						}
					}

					$sql = "UPDATE `teams` SET `active_hints` = {?}, `list_hints` = {?}, `list_hints_title_lang_var` = {?}, `list_hints_text_lang_var` = {?} WHERE `id` = {?}";
					$db->query($sql, [json_encode($active_hints, JSON_UNESCAPED_UNICODE), json_encode($list_hints, JSON_UNESCAPED_UNICODE), 'text44', 'text45', $userInfo['team_id']]);
				} else {
					// переводы для всех языков. Для синхронизации
					$sql = "SELECT `lang_abbr`, `id` FROM `langs` WHERE `status` = {?}";
					$langs = $db->select($sql, [1]);
					if ($langs) {
						$return['error_lang'] = [];

						foreach ($langs as $lang2) {
							$translation = $lang->getWordsByPage('game', $lang2['id']);

							$return['error_lang'][$lang2['lang_abbr']]['error_input'] = $translation['text89'];
							$return['error_lang'][$lang2['lang_abbr']]['error_text'] = $translation['text214'];
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

						$return['error_lang'][$lang2['lang_abbr']]['error_input'] = $translation['text89'];
						$return['error_lang'][$lang2['lang_abbr']]['error_text'] = $translation['text214'];
					}
				}
			}

			print_r(json_encode($return));
		    break;
	}
}
