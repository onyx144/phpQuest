<?php

// defines
define('GD_ACCESS', true); // security
define('ROOT', $_SERVER['DOCUMENT_ROOT']); // root

// include files
require_once(ROOT . '/config.php'); // config
require_once(ROOT . '/core/DataBase.php'); // db
require_once(ROOT . '/controller/Useradmin.php'); // user

// db
$db = DataBase::getDB();

// user
$user = Useradmin::getUser();
$userInfo = $user->isAutorized();

if (isset($_POST['op'])) {
	$return = [];

	switch ($_POST['op']) {
		// добавить продажу из админки. Генерация нового кода
		case 'saveGamecode':
			if ($userInfo['role_id'] == 2) {
				$date = isset($_POST['date']) ? strip_tags(trim($_POST['date'])) : NULL;
				// $gamename = isset($_POST['gamename']) ? strip_tags(trim($_POST['gamename'])) : '';
				$gamename_id = isset($_POST['gamename_id']) ? (int) $_POST['gamename_id'] : 0;
				$price_usd = isset($_POST['price_usd']) ? (float) $_POST['price_usd'] : 0;
				$price_nok = isset($_POST['price_nok']) ? (float) $_POST['price_nok'] : 0;
				$source_id = isset($_POST['source_id']) ? (int) $_POST['source_id'] : 0;
				$code = isset($_POST['code']) ? strip_tags(trim($_POST['code'])) : '';
				$code2 = isset($_POST['code2']) ? strip_tags(trim($_POST['code2'])) : '';
				$code3 = isset($_POST['code3']) ? strip_tags(trim($_POST['code3'])) : '';
				$code4 = isset($_POST['code4']) ? strip_tags(trim($_POST['code4'])) : '';
				$code5 = isset($_POST['code5']) ? strip_tags(trim($_POST['code5'])) : '';
				$code6 = isset($_POST['code6']) ? strip_tags(trim($_POST['code6'])) : '';
				$code7 = isset($_POST['code7']) ? strip_tags(trim($_POST['code7'])) : '';
				$code8 = isset($_POST['code8']) ? strip_tags(trim($_POST['code8'])) : '';
				$code9 = isset($_POST['code9']) ? strip_tags(trim($_POST['code9'])) : '';
				$code10 = isset($_POST['code10']) ? strip_tags(trim($_POST['code10'])) : '';
				$source_code = isset($_POST['source_code']) ? strip_tags(trim($_POST['source_code'])) : '';
				// $source_code = '';
				$client_email = isset($_POST['client_email']) ? strip_tags(trim($_POST['client_email'])) : '';

				if (empty($code) || strlen($code) < 5 || strlen($code) > 30) {
					$return['error'] = 'Code length must be from 5 to 30 symbols';
				} else {
					$sql = "SELECT id FROM admin_sales WHERE BINARY team_code = {?} LIMIT 1";
					$isset_code = $db->selectCell($sql, [$code]);

					if ($isset_code) {
						$return['error'] = 'Code already exist';
					} else {
						if (empty($source_id)) {
							$source_name = '';
						} else {
							$sql = "SELECT source_name FROM admin_source WHERE id = {?} LIMIT 1";
							$isset_source_name = $db->selectCell($sql, [$source_id]);

							if (!empty($isset_source_name)) {
								$source_name = $isset_source_name;
							} else {
								$source_name = '';
							}
						}

						if (empty($gamename_id)) {
							$gamename = '';
						} else {
							$sql = "SELECT game_name FROM admin_game_names WHERE id = {?} LIMIT 1";
							$isset_game_name = $db->selectCell($sql, [$gamename_id]);

							if (!empty($isset_game_name)) {
								$gamename = $isset_game_name;
							} else {
								$gamename = '';
							}
						}

						$codes = [$code];

						if (!empty($code2)) { $codes[] = $code2; }
						if (!empty($code3)) { $codes[] = $code3; }
						if (!empty($code4)) { $codes[] = $code4; }
						if (!empty($code5)) { $codes[] = $code5; }
						if (!empty($code6)) { $codes[] = $code6; }
						if (!empty($code7)) { $codes[] = $code7; }
						if (!empty($code8)) { $codes[] = $code8; }
						if (!empty($code9)) { $codes[] = $code9; }
						if (!empty($code10)) { $codes[] = $code10; }

						foreach ($codes as $code) {
							$sql = "
								INSERT INTO `admin_sales`
								SET `datetime_sale` = {?},
									`gamename_id` = {?},
									`game_name` = {?},
									`price_dollar` = {?},
									`price_norway_crone` = {?},
									`source_id` = {?},
									`source_name` = {?},
									`team_code` = {?},
									`status` = {?},
									`source_code` = {?},
									`client_email` = {?}
							";
							$sale_id = $db->query($sql, [(is_null($date) ? date('Y-m-d H:i:s') : fromRusDatetimeToEng($date)), $gamename_id, $gamename, $price_usd, $price_nok, $source_id, $source_name, $code, 1, $source_code, $client_email]);

							if ($sale_id) {
								$sql = "
									INSERT INTO `teams`
									SET `team_name` = {?},
										`code` = {?},
										`create` = {?},
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
								";
								$team_id = $db->query($sql, ['', $code, (is_null($date) ? date('Y-m-d H:i:s') : fromRusDatetimeToEng($date)), 0, 0, 'dashboard', 0, json_encode([], JSON_UNESCAPED_UNICODE), json_encode([1,2,3], JSON_UNESCAPED_UNICODE), 'text26', 'text27', json_encode([], JSON_UNESCAPED_UNICODE), json_encode([1], JSON_UNESCAPED_UNICODE), json_encode([], JSON_UNESCAPED_UNICODE), json_encode([], JSON_UNESCAPED_UNICODE), 2, 2, json_encode([['id'=>1,'datetime'=>'']], JSON_UNESCAPED_UNICODE), 0, NULL, 0, 0, 0, 0, NULL, 0, NULL, 'no_access', 'accept_new_mission', 'main', 'dashboard', 'no_access', 'no_access', 'no', 0, 0, 0, 0, json_encode([], JSON_UNESCAPED_UNICODE), json_encode([], JSON_UNESCAPED_UNICODE), 0, 0, 0, 0, NULL, 0, NULL, 0, 0, '0', '0', '0', '0', '0', '0', '2', '2', '2', '2', 0, 0, 0, 0, 0, 0, 1, 0, 0, NULL, 0, 1]);

								if ($team_id) {
									$url = 'https://app.escapegames.no/forms.php?f=emm';

									$ch = curl_init();
									curl_setopt($ch, CURLOPT_URL, $url);
									curl_setopt($ch, CURLOPT_HEADER, 0);
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
									curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
									curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
									curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
									curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.116 Safari/537.36');
									// curl_setopt($ch, CURLOPT_TIMEOUT, 5);
									curl_setopt($ch, CURLOPT_FAILONERROR, 1);

									curl_setopt($ch, CURLOPT_POST, true);
									curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

									$data_send = [
										'code' => $code,
										'hash' => md5($code . $client_email . 'vS0qT6lX2c')
									];

									if (!empty($client_email)) {
										$data_send['to'] = $client_email;
									}

									$data_send = json_encode($data_send, JSON_UNESCAPED_UNICODE);

									curl_setopt($ch, CURLOPT_POSTFIELDS, $data_send);
									curl_setopt($ch, CURLOPT_HTTPHEADER, array(
										'Content-Type: application/json',
										'Content-Length: ' . strlen($data_send))
									);

									$answer = curl_exec($ch);

									$answer = json_decode($answer, true);

									if (!array_key_exists('success', $return)) {
										if (array_key_exists('success', $answer)) {
											$return['success'] = $answer['success'];
										} elseif (array_key_exists('error', $answer)) {
											$return['success'] = $answer['error'];
										} else {
											$return['success'] = 'Unexpected answer';
										}
									}
								} else {
									$sql = "DELETE FROM admin_sales WHERE id = {?}";
									$db->query($sql, [$sale_id]);

									$return['error'] = 'Dont save. Unexpected error.';

									break;
								}
							} else {
								$return['error'] = 'Dont save. Unexpected error.';

								break;
							}
						}
					}
				}
			} else {
				$return['error'] = 'Access is denied';
			}

            print_r(json_encode($return));
            break;

        // Отправить пиьмо клиенту из таблицы продаж
        case 'sendClientEmail':
			$code = isset($_POST['code']) ? strip_tags(trim($_POST['code'])) : '';
			$client_email = isset($_POST['client_email']) ? strip_tags(trim($_POST['client_email'])) : '';

			$url = 'https://app.escapegames.no/forms.php?f=emm';

		 	$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.116 Safari/537.36');
			// curl_setopt($ch, CURLOPT_TIMEOUT, 5);
			curl_setopt($ch, CURLOPT_FAILONERROR, 1);

			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

			$data_send = [
	            'code' => $code,
	            'hash' => md5($code . $client_email . 'vS0qT6lX2c')
	        ];

	        if (!empty($client_email)) {
	        	$data_send['to'] = $client_email;
	        }

	        $data_send = json_encode($data_send, JSON_UNESCAPED_UNICODE);

	        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_send);
	        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	            'Content-Type: application/json',
	            'Content-Length: ' . strlen($data_send))
	        );

	        $answer = curl_exec($ch);

	        $answer = json_decode($answer, true);

	        if (array_key_exists('success', $answer)) {
	        	$return['success'] = $answer['success'];
	        } elseif (array_key_exists('error', $answer)) {
	        	$return['error'] = $answer['error'];
	        } else {
	        	$return['error'] = 'Unexpected answer';
	        }

            print_r(json_encode($return));
            break;

		// удалить продажу из админки
		case 'removeSale':
			if ($userInfo['role_id'] == 2) {
				$code = isset($_POST['code']) ? strip_tags(trim($_POST['code'])) : '';

				/*$sql = "DELETE FROM admin_sales WHERE team_code = {?}";
		    	$db->query($sql, [$code]);

		    	$sql = "DELETE FROM teams WHERE code = {?}";
		    	$db->query($sql, [$code]);*/

		    	$sql = "UPDATE `admin_sales` SET `status` = {?} WHERE `team_code` = {?}";
		    	$db->query($sql, [2, $code]);

		    	$sql = "UPDATE `teams` SET `status` = {?} WHERE `code` = {?}";
		    	$db->query($sql, [2, $code]);

		    	$return['success'] = 'ok';
			} else {
				$return['error'] = 'Access is denied';
			}

            print_r(json_encode($return));
            break;

		// добавить нового пользователя
		case 'addUser':
			if ($userInfo['role_id'] == 2) {
				$login = isset($_POST['login']) ? strip_tags(trim($_POST['login'])) : '';
				$password = isset($_POST['password']) ? strip_tags(trim($_POST['password'])) : '';
				$role_id = isset($_POST['role_id']) ? (int) $_POST['role_id'] : 2;
				$source_id = isset($_POST['source_id']) ? (int) $_POST['source_id'] : 0;
				$status = isset($_POST['status']) ? (int) $_POST['status'] : 0;

				if (strlen($login) < 3 || strlen($login) > 30) {
					$return['error'] = 'Login length from 3 to 30 symbols';
				} elseif (strlen($password) < 3 || strlen($password) > 30) {
					$return['error'] = 'Password length from 3 to 30 symbols';
				} else {
					$sql = "SELECT `id` FROM `admin_users` WHERE `login` = {?} LIMIT 1";
					$isset_user = $db->selectCell($sql, [$login]);
					if ($isset_user) {
						$return['error'] = 'Login already exists';
					} else {
						$sql = "INSERT INTO `admin_users` SET `role_id` = {?}, `hash` = {?}, `activity` = {?}, `ip` = {?}, `login` = {?}, `password` = {?}, `status` = {?}, `source_id` = {?}";
						$user_add = $db->query($sql, [$role_id, '', NULL, NULL, $login, $password, $status, $source_id]);
						if (!empty($user_add)) {
							$return['success'] = 'ok';
						} else {
							$return['error'] = 'Dont save. Unexpected error.';
						}
					}
				}
			} else {
				$return['error'] = 'Access is denied';
			}

            print_r(json_encode($return));
            break;

		// редактировать пользователя
		case 'editUser':
			if ($userInfo['role_id'] == 2) {
				$login = isset($_POST['login']) ? strip_tags(trim($_POST['login'])) : '';
				$password = isset($_POST['password']) ? strip_tags(trim($_POST['password'])) : '';
				$role_id = isset($_POST['role_id']) ? (int) $_POST['role_id'] : 2;
				$source_id = isset($_POST['source_id']) ? (int) $_POST['source_id'] : 0;
				$status = isset($_POST['status']) ? (int) $_POST['status'] : 0;
				$userId = isset($_POST['userId']) ? (int) $_POST['userId'] : 0;

				if (empty($userId)) {
					$return['error'] = 'Edited user not found';
				} elseif ($userId == 1) {
					$return['error'] = 'Forbidden for editing';
				} elseif (strlen($login) < 3 || strlen($login) > 30) {
					$return['error'] = 'Login length from 3 to 30 symbols';
				} elseif (strlen($password) < 3 || strlen($password) > 30) {
					$return['error'] = 'Password length from 3 to 30 symbols';
				} else {
					$sql = "SELECT `id` FROM `admin_users` WHERE `login` = {?} AND `id` != {?} LIMIT 1";
					$isset_user = $db->selectCell($sql, [$login, $userId]);
					if ($isset_user) {
						$return['error'] = 'Login already exists';
					} else {
						$sql = "UPDATE `admin_users` SET `role_id` = {?}, `login` = {?}, `password` = {?}, `status` = {?}, `source_id` = {?} WHERE `id` = {?}";
						$db->query($sql, [$role_id, $login, $password, $status, $source_id, $userId]);

						$return['success'] = 'ok';
					}
				}
			} else {
				$return['error'] = 'Access is denied';
			}

            print_r(json_encode($return));
            break;

		// сохранить настройки
		case 'editSettings':
			if ($userInfo['role_id'] == 2) {
				$norway_crone = isset($_POST['norway_crone']) ? strip_tags(trim($_POST['norway_crone'])) : '';

				$sql = "UPDATE `admin_settings` SET `value` = {?} WHERE `id` = {?}";
				$db->query($sql, [$norway_crone, 2]);

				$return['success'] = 'ok';
			} else {
				$return['error'] = 'Access is denied';
			}

            print_r(json_encode($return));
            break;

		// добавить источник
		case 'addSource':
			if ($userInfo['role_id'] == 2) {
				$source_name = isset($_POST['source_name']) ? strip_tags(trim($_POST['source_name'])) : '';

				if (empty($source_name)) {
					$return['error'] = 'Source name required';
				} else {
					$sql = "SELECT `id` FROM `admin_source` WHERE `source_name` = {?} LIMIT 1";
					$isset_source = $db->selectCell($sql, [$source_name]);
					if ($isset_source) {
						$return['error'] = 'Source name already exists';
					} else {
						$sql = "INSERT INTO `admin_source` SET `source_name` = {?}";
						$source_id = $db->query($sql, [$source_name]);
						if ($source_id) {
							$return['success'] = 'ok';
						} else {
							$return['error'] = 'Dont save. Unexpected error.';
						}
					}
				}
			} else {
				$return['error'] = 'Access is denied';
			}

            print_r(json_encode($return));
            break;

		// редактировать источник
		case 'editSource':
			if ($userInfo['role_id'] == 2) {
				$source_name = isset($_POST['source_name']) ? strip_tags(trim($_POST['source_name'])) : '';
				$source_id = isset($_POST['source_id']) ? (int) $_POST['source_id'] : 0;

				if (empty($source_name)) {
					$return['error'] = 'Source name required';
				} else {
					$sql = "SELECT `id` FROM `admin_source` WHERE BINARY `source_name` = {?} AND `id` != {?} LIMIT 1";
					$isset_source = $db->selectCell($sql, [$source_name, $source_id]);
					if ($isset_source) {
						$return['error'] = 'Source name already exists';
					} else {
						$sql = "UPDATE `admin_source` SET `source_name` = {?} WHERE `id` = {?}";
						$db->query($sql, [$source_name, $source_id]);

						$return['success'] = 'ok';
					}
				}
			} else {
				$return['error'] = 'Access is denied';
			}

            print_r(json_encode($return));
            break;

		// удалить источник из админки
		case 'removeSource':
           	if ($userInfo['role_id'] == 2) {
				$source_id = isset($_POST['source_id']) ? (int) $_POST['source_id'] : 0;

				$sql = "SELECT `team_code` FROM `admin_sales` WHERE `source_id` = {?}";
				$codes = $db->select($sql, [$source_id]);
				if ($codes) {
					foreach ($codes as $code) {
						// $sql = "DELETE FROM `teams` WHERE BINARY `code` = {?}";
		    			// $db->query($sql, [$code['team_code']]);
						$sql = "UPDATE `teams` SET `status` = {?} WHERE BINARY `code` = {?}";
		    			$db->query($sql, [2, $code['team_code']]);
					}
				}

				// $sql = "DELETE FROM `admin_sales` WHERE `source_id` = {?}";
		    	// $db->query($sql, [$source_id]);
				$sql = "UPDATE `admin_sales` SET `status` = {?} WHERE `source_id` = {?}";
		    	$db->query($sql, [2, $source_id]);

		    	$sql = "DELETE FROM `admin_source` WHERE `id` = {?}";
		    	$db->query($sql, [$source_id]);

		    	$sql = "UPDATE `admin_users` SET `source_id` = {?} WHERE `source_id` = {?}";
		    	$db->query($sql, [0, $source_id]);

		    	$return['success'] = 'ok';
			} else {
				$return['error'] = 'Access is denied';
			}

            print_r(json_encode($return));
            break;

		// добавить название игры
		case 'addGamename':
			if ($userInfo['role_id'] == 2) {
				$game_name = isset($_POST['game_name']) ? strip_tags(trim($_POST['game_name'])) : '';

				if (empty($game_name)) {
					$return['error'] = 'Game name required';
				} else {
					$sql = "SELECT `id` FROM `admin_game_names` WHERE `game_name` = {?} LIMIT 1";
					$isset_gamename = $db->selectCell($sql, [$game_name]);
					if ($isset_gamename) {
						$return['error'] = 'Game name already exists';
					} else {
						$sql = "INSERT INTO `admin_game_names` SET `game_name` = {?}";
						$gamename_id = $db->query($sql, [$game_name]);
						if ($gamename_id) {
							$return['success'] = 'ok';
						} else {
							$return['error'] = 'Dont save. Unexpected error.';
						}
					}
				}
			} else {
				$return['error'] = 'Access is denied';
			}

            print_r(json_encode($return));
            break;

		// редактировать название игры
		case 'editGamename':
			if ($userInfo['role_id'] == 2) {
				$game_name = isset($_POST['game_name']) ? strip_tags(trim($_POST['game_name'])) : '';
				$gamename_id = isset($_POST['gamename_id']) ? (int) $_POST['gamename_id'] : 0;

				if (empty($game_name)) {
					$return['error'] = 'Game name required';
				} else {
					$sql = "SELECT `id` FROM `admin_game_names` WHERE BINARY `game_name` = {?} AND `id` != {?} LIMIT 1";
					$isset_gamename = $db->selectCell($sql, [$game_name, $gamename_id]);
					if ($isset_gamename) {
						$return['error'] = 'Game name already exists';
					} else {
						$sql = "UPDATE `admin_game_names` SET `game_name` = {?} WHERE `id` = {?}";
						$db->query($sql, [$game_name, $gamename_id]);

						$return['success'] = 'ok';
					}
				}
			} else {
				$return['error'] = 'Access is denied';
			}

            print_r(json_encode($return));
            break;

		// удалить название игры из админки
		case 'removeGamename':
           	if ($userInfo['role_id'] == 2) {
				$gamename_id = isset($_POST['gamename_id']) ? (int) $_POST['gamename_id'] : 0;

				$sql = "SELECT `team_code` FROM `admin_sales` WHERE `gamename_id` = {?}";
				$codes = $db->select($sql, [$gamename_id]);
				if ($codes) {
					foreach ($codes as $code) {
						// $sql = "DELETE FROM `teams` WHERE BINARY `code` = {?}";
		    			// $db->query($sql, [$code['team_code']]);
						$sql = "UPDATE `teams` SET `status` = {?} WHERE BINARY `code` = {?}";
		    			$db->query($sql, [2, $code['team_code']]);
					}
				}

				// $sql = "DELETE FROM `admin_sales` WHERE `gamename_id` = {?}";
	    		// $db->query($sql, [$gamename_id]);
				$sql = "UPDATE `admin_sales` SET `status` = {?} WHERE `gamename_id` = {?}";
	    		$db->query($sql, [2, $gamename_id]);

				$sql = "DELETE FROM `admin_game_names` WHERE `id` = {?}";
		    	$db->query($sql, [$gamename_id]);

		    	$return['success'] = 'ok';
			} else {
				$return['error'] = 'Access is denied';
			}

            print_r(json_encode($return));
            break;

		// удалить пользователя
		case 'removeUser':
           	if ($userInfo['role_id'] == 2) {
				$remove_user_id = isset($_POST['remove_user_id']) ? (int) $_POST['remove_user_id'] : 0;

				if ($remove_user_id == $userInfo['id']) {
					$return['error'] = 'You cannot remove yourself';
				} else {
					if ($remove_user_id == 1) {
						$return['error'] = 'You cannot remove this user';
					} else {
		    			$sql = "DELETE FROM `admin_users` WHERE `id` = {?}";
		    			$db->query($sql, [$remove_user_id]);

		    			$return['success'] = 'ok';
		    		}
		    	}
			} else {
				$return['error'] = 'Access is denied';
			}

            print_r(json_encode($return));
            break;
	}
}

function fromRusDatetimeToEng($datetime) {
    $return = '';

    if (stripos($datetime, ' ') !== false) {
        $parts = explode(' ', $datetime);

        $from_date = $parts[0];
        $from_time = $parts[1];
    } else {
        $from_date = $datetime;
        $from_time = '';
    }

    if (stripos($from_date, '.') !== false) {
        $parts1 = explode('.', $from_date);
        for ($i = count($parts1) - 1; $i >= 0; $i--) { 
            $return .= $parts1[$i] . '-';
        }
        $return = substr($return, 0, -1);
    }

    if (!empty($from_time)) {
        $time_object = new DateTime($from_time);
        $return .= ' ' . $time_object->format('H:i');
    }

    return $return;
}
