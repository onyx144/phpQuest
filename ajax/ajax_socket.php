<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/ajax/ajax_header.php');

if (isset($_POST['op'])) {
	$return = [];

	switch ($_POST['op']) {
		// история сокетов
		case 'addSocketHistory':
			$socket_action = isset($_POST['socket_action']) ? strip_tags(trim($_POST['socket_action'])) : '';
			$parameters = isset($_POST['parameters']) ? json_decode($_POST['parameters'], true) : [];

			if (!is_null($socket_action) && $socket_action != 'null' && $socket_action != 'newDisconectedACK' && $socket_action != 'newConnectionACK') {
				$sql = "INSERT INTO `team_socket_action` SET `socket_action` = {?}, `team_id` = {?}, `user_id` = {?}, `parameters` = {?}, `datetime` = NOW()";
				$db->query($sql, [$socket_action, $userInfo['team_id'], $userInfo['id'], json_encode($parameters, JSON_UNESCAPED_UNICODE)]);
				
				// Если это действие связано с входящим звонком, отправляем вебхук для синхронизации всех пользователей команды
				$incoming_call_ops = [
					'missionNumberOpenIncomingCall',
					'missionNumberCloseIncomingCall',
					'acceptMissionIncomingCallAccept',
					'acceptMissionIncomingCallReject',
					'dashboardCompanyInvestigateCloseSuccessPopupAndOpenIncomingCall',
					'dashboardCompanyInvestigateCloseIncomingCall',
					'dashboardCompanyInvestigateAcceptIncomingCall',
					'dashboardCoordinatesOpenIncomingCall',
					'dashboardCoordinatesCloseIncomingCall',
					'dashboardCoordinatesAcceptIncomingCall',
					'dashboardAfricanPartnerOpenIncomingCall',
					'dashboardAfricanPartnerCloseIncomingCall',
					'dashboardAfricanPartnerAcceptIncomingCall',
					'dashboardMettingPlaceOpenIncomingCall',
					'dashboardMettingPlaceCloseIncomingCall',
					'dashboardMettingPlaceAcceptIncomingCall',
					'dashboardRoomNameOpenIncomingCall',
					'dashboardRoomNameCloseIncomingCall',
					'dashboardRoomNameAcceptIncomingCall',
					'minigameOpenIncomingCall',
					'minigameCloseIncomingCall',
					'minigameAcceptIncomingCall'
				];
				
				// Синхронизация звонков происходит через сокеты - вебхук на внешний URL не нужен
				// if (in_array($socket_action, $incoming_call_ops)) {
				// 	// Вебхук можно добавить здесь, если нужен внешний URL для логирования
				// }
			}

			// возвращаем последнее действие команды кроме подключения и отключения
			$sql = "SELECT `socket_action`, `parameters` FROM `team_socket_action` WHERE `team_id` = {?} AND `socket_action` != {?} AND `socket_action` != {?} AND `socket_action` != {?} AND `socket_action` != {?} AND `socket_action` IS NOT NULL ORDER BY `id` DESC LIMIT 1";
			$data = $db->selectRow($sql, [$userInfo['team_id'], 'newConnectionACK', 'newDisconectedACK', '', 'null']);
			if ($data) {
				$return['socket_action'] = $data['socket_action'];
				$return['parameters'] = json_decode($data['parameters'], true);
			}

			$return['success'] = 'ok';

			print_r(json_encode($return));
		    break;

		// логирование действий сокетов
		case 'socketLog':
			$action = isset($_POST['action']) ? strip_tags(trim($_POST['action'])) : '';

			$sql = "INSERT INTO `socket_log` SET `action` = {?}, `datetime` = NOW()";
			$db->query($sql, [$action]);

			$return['success'] = 'ok';

			print_r(json_encode($return));
		    break;

		// Синхронизация входящего звонка для всех пользователей команды
		// Когда один пользователь открывает/принимает/отклоняет звонок, это должно быть видно всем
		case 'syncIncomingCallForTeam':
			$socket_action = isset($_POST['socket_action']) ? strip_tags(trim($_POST['socket_action'])) : '';
			$parameters = isset($_POST['parameters']) ? json_decode($_POST['parameters'], true) : [];
			
			// Список операций, связанных с входящими звонками
			$incoming_call_ops = [
				'missionNumberOpenIncomingCall',
				'missionNumberCloseIncomingCall',
				'acceptMissionIncomingCallAccept',
				'acceptMissionIncomingCallReject',
				'dashboardCompanyInvestigateCloseSuccessPopupAndOpenIncomingCall',
				'dashboardCompanyInvestigateCloseIncomingCall',
				'dashboardCompanyInvestigateAcceptIncomingCall',
				'dashboardCoordinatesOpenIncomingCall',
				'dashboardCoordinatesCloseIncomingCall',
				'dashboardCoordinatesAcceptIncomingCall',
				'dashboardAfricanPartnerOpenIncomingCall',
				'dashboardAfricanPartnerCloseIncomingCall',
				'dashboardAfricanPartnerAcceptIncomingCall',
				'dashboardMettingPlaceOpenIncomingCall',
				'dashboardMettingPlaceCloseIncomingCall',
				'dashboardMettingPlaceAcceptIncomingCall',
				'dashboardRoomNameOpenIncomingCall',
				'dashboardRoomNameCloseIncomingCall',
				'dashboardRoomNameAcceptIncomingCall',
				'minigameOpenIncomingCall',
				'minigameCloseIncomingCall',
				'minigameAcceptIncomingCall'
			];
			
			if (in_array($socket_action, $incoming_call_ops)) {
				// Сохраняем действие в истории сокетов для команды
				$sql = "INSERT INTO `team_socket_action` SET `socket_action` = {?}, `team_id` = {?}, `user_id` = {?}, `parameters` = {?}, `datetime` = NOW()";
				$db->query($sql, [$socket_action, $userInfo['team_id'], $userInfo['id'], json_encode($parameters, JSON_UNESCAPED_UNICODE)]);
				
				// Синхронизация происходит через сокеты - вебхук на внешний URL не нужен
				// Если нужен вебхук для логирования, можно добавить здесь
				
				$return['success'] = 'ok';
				$return['message'] = 'Call action synchronized for all team members';
			} else {
				$return['error'] = 'Not an incoming call action';
			}
			
			print_r(json_encode($return));
			break;
	}
}
