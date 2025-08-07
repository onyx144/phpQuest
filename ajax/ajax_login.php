<?php

// defines
define('GD_ACCESS', true); // security
define('ROOT', $_SERVER['DOCUMENT_ROOT']); // root

// include files
require_once(ROOT . '/config.php'); // config
require_once(ROOT . '/core/DataBase.php'); // db

require_once(ROOT . '/core/Language.php'); // language
require_once(ROOT . '/controller/Functions.php'); // functions
require_once(ROOT . '/controller/User.php'); // user

// db
$db = DataBase::getDB();
// lang
$lang = Language::getLang();
// lang
$function = Functions::getFunctions();

// user
$user = User::getUser();
$userInfo = $user->isAutorized();

if (isset($_POST['op'])) {
	$return = [];

	switch ($_POST['op']) {
		// проверка кода игры
		case 'verifyCode':
			$code = isset($_POST['code']) ? strip_tags(trim($_POST['code'])) : '';
			$lang_abbr = isset($_POST['lang_abbr']) ? strip_tags(trim($_POST['lang_abbr'])) : '';

			$lang_id = $lang->getLangIdByHtmlAttr($lang_abbr);

			// $sql = "SELECT `id`, `team_name` FROM `teams` WHERE BINARY `code` = {?} AND `create` IS NOT NULL AND `create` != {?} AND `create` != {?} AND `create` >= NOW() - INTERVAL 1 DAY AND `status` = {?}";
			$sql = "SELECT `id`, `team_name` FROM `teams` WHERE BINARY `code` = {?} AND `create` IS NOT NULL AND `create` != {?} AND `create` != {?} AND `status` = {?}";
			$team_info = $db->selectRow($sql, [$code, '', '0000-00-00 00:00:00', 1]);
			if ($team_info) {
				if (empty($team_info['team_name'])) {

					$translation = $lang->getWordsByPage('control_system', $lang_id);
					
					// $return['success'] = 'ok';
					$return['success'] = $translation['text1'];

					$return['placeholder'] = $translation['text5'];
					$return['btn'] = $translation['text6'];
					$return['btn_error'] = $translation['text7'];
				} else {
					// авторизуемся
					/*$hash = openssl_random_pseudo_bytes(16);
	                $hash = bin2hex($hash);

	                $sql = "SELECT `id` FROM `users` WHERE `ip` = {?} AND `team_id` = {?}";
	                $user_id = $db->selectCell($sql, [$function->getIp(), $team_info['id']]);
	                if ($user_id) {
	                	$sql = "UPDATE `users` SET `hash` = {?} WHERE `id` = {?}";
						$db->query($sql, [$hash, $user_id]);
					} else {
						$sql = "INSERT INTO `users` SET `role_id` = {?}, `hash` = {?}, `activity` = NOW(), `ip` = {?}, `team_id` = {?}";
						$db->query($sql, [1, $hash, $function->getIp(), $team_info['id']]);
					}

					setcookie('hash', $hash, time() + (60 * 60 * 24 * 1), '/');

					if ($lang_abbr == 'en') {
						$return['success_link'] = '/game';
					} else {
						$return['success_link'] = '/no/game';
					}*/

					if (isset($_COOKIE['hash']) && $_COOKIE['hash'] != '') {
						$sql = "SELECT `id` FROM `users` WHERE `hash` = {?} AND `team_id` = {?} LIMIT 1";
						$user_id = $db->selectCell($sql, [$_COOKIE['hash'], $team_info['id']]);
						if (!$user_id) {
							$hash = openssl_random_pseudo_bytes(16);
	                		$hash = bin2hex($hash);

							$sql = "INSERT INTO `users` SET `role_id` = {?}, `hash` = {?}, `activity` = NOW(), `ip` = {?}, `team_id` = {?}";
							$db->query($sql, [1, $hash, $function->getIp(), $team_info['id']]);

							setcookie('hash', $hash, time() + (60 * 60 * 24 * 1), '/');
						}
					} else {
						$hash = openssl_random_pseudo_bytes(16);
                		$hash = bin2hex($hash);

						$sql = "INSERT INTO `users` SET `role_id` = {?}, `hash` = {?}, `activity` = NOW(), `ip` = {?}, `team_id` = {?}";
						$db->query($sql, [1, $hash, $function->getIp(), $team_info['id']]);

						setcookie('hash', $hash, time() + (60 * 60 * 24 * 1), '/');
					}

					if ($lang_abbr == 'en') {
						$return['success_link'] = '/game';
					} else {
						$return['success_link'] = '/no/game';
					}
				}
			} else {
				$return['error'] = 'ok';
			}

			print_r(json_encode($return));
            break;

		// ввод названия команды
		case 'teamname':
			$teamname = isset($_POST['teamname']) ? strip_tags(trim($_POST['teamname'])) : '';
			$lang_abbr = isset($_POST['lang_abbr']) ? strip_tags(trim($_POST['lang_abbr'])) : '';
			$code = isset($_POST['code']) ? strip_tags(trim($_POST['code'])) : '';

			$lang_id = $lang->getLangIdByHtmlAttr($lang_abbr);

			$translation = $lang->getWordsByPage('control_system', $lang_id);

			$sql = "SELECT `id` FROM `teams` WHERE `team_name` = {?} AND `status` = {?} LIMIT 1";
			$isset_teamname = $db->selectCell($sql, [$teamname, 1]);
			if ($isset_teamname) {
				$return['error'] = $translation['text8'];
			} else {
				// обновляем название команды и последнее действие для команды
				$sql = "UPDATE `teams` SET `team_name` = {?}, `last_action_id` = {?} WHERE `code` = {?}";
				$db->query($sql, [$teamname, 2, $code]);

				// получаем идентификатор команды
				$sql = "SELECT `id` FROM `teams` WHERE `code` = {?} LIMIT 1";
				$team_id = $db->selectCell($sql, [$code]);

				// запоминаем нового пользователя
				$hash = openssl_random_pseudo_bytes(16);
                $hash = bin2hex($hash);

				$sql = "INSERT INTO `users` SET `role_id` = {?}, `hash` = {?}, `activity` = NOW(), `ip` = {?}, `team_id` = {?}";
				$db->query($sql, [1, $hash, $function->getIp(), $team_id]);

				setcookie('hash', $hash, time() + (60 * 60 * 24 * 1), '/');

				/*// пишем действие в историю действий команды
				$function->addTeamActionHistory($team_id, 2, $userInfo['id']);
				$function->addTeamActionHistory($team_id, 26, $userInfo['id']);*/

				$return['success'] = $translation['text9'];
			}

			print_r(json_encode($return));
            break;
	}
}
