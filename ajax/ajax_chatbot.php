<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/ajax/ajax_header.php');

if (isset($_POST['op'])) {
	$return = [];

	// сообщения, которые может отправить бот
	$default_messages = [
		1 => [
			3 => 'Hi, agents! The artificial mind iNerd will be happy to assist you <img src="/images/icons/chat_icon1.png" alt="">',
			4 => 'Hei, agenter! Den kunstige intelligensen iNerd er her for å hjelpe dere <img src="/images/icons/chat_icon1.png" alt="">'
		],
		2 => [
			3 => 'What is the name of the criminal you are searching for?',
			4 => 'Hva heter forbryteren dere leter etter?'
		],
		3 => [
			3 => '<img src="/images/icons/chat_icon2.png" alt=""> I could not identify the person you have entered. Please, check that the name is correct and try again.',
			4 => '<img src="/images/icons/chat_icon2.png" alt=""> Jeg kunne ikke identifisere personen dere har oppgitt. Vennligst kontroller at navnet er riktig og prøv på nytt.'
		],
		4 => [
			3 => '<span class="chat_bot_message_btn" data-goto-bot-message-id="2" data-goto-bot-message-id-next="0"><img src="/images/icons/chat_icon3.png" alt=""> BACK</span>',
			4 => '<span class="chat_bot_message_btn" data-goto-bot-message-id="2" data-goto-bot-message-id-next="0"><img src="/images/icons/chat_icon3.png" alt=""> TILBAKE</span>'
		],
		5 => [
			3 => '<img src="/images/icons/chat_icon4.png" alt=""> Locating Axel Rod…',
			4 => '<img src="/images/icons/chat_icon4.png" alt=""> Ser etter Axel Rod…'
		],
		/*6 => [
			3 => '<img src="/images/gifs/globe.gif" alt="">',
			4 => '<img src="/images/gifs/globe.gif" alt="">'
		],*/
		/*6 => [
			3 => '<img src="/images/gifs/searching.gif" alt="">',
			4 => '<img src="/images/gifs/searching.gif" alt="">'
		],*/
		6 => [
			3 => '<a href="/images/gifs/searching.gif" target="_blank"><img src="/images/gifs/searching.gif" alt=""></a>',
			4 => '<a href="/images/gifs/searching.gif" target="_blank"><img src="/images/gifs/searching.gif" alt=""></a>'
		],
		7 => [
			3 => '<img src="/images/icons/chat_icon5.png" alt=""> The person is localized.',
			4 => '<img src="/images/icons/chat_icon5.png" alt=""> Personen er lokalisert.'
		],
		/*8 => [
			3 => '<img src="/images/gifs/globe.gif" alt="">',
			4 => '<img src="/images/gifs/globe.gif" alt="">'
		],*/
		/*8 => [
			3 => '<img src="/images/gifs/searching2.gif" alt="">',
			4 => '<img src="/images/gifs/searching2.gif" alt="">'
		],*/
		8 => [
			3 => '<a href="/images/gifs/searching2.gif" target="_blank"><img src="/images/gifs/searching2.gif" alt=""></a>',
			4 => '<a href="/images/gifs/searching2.gif" target="_blank"><img src="/images/gifs/searching2.gif" alt=""></a>'
		],
		9 => [
			3 => '<img src="/images/icons/chat_icon6.png" alt=""> Axel Rod is now at Cafe "NaMomente", Gogol 2, Dnepr, Ukraine.',
			4 => '<img src="/images/icons/chat_icon6.png" alt=""> Axel Rod er nå på The Cafe "NaMomente", Gogol 2, Dnepr, Ukraine.'
		],
		10 => [
			3 => 'How would you prefer to proceed?',
			4 => 'Hva skal vi gjøre videre?'
		],
		11 => [
			3 => '<span class="chat_bot_message_btn" data-goto-bot-message-id="0" data-goto-bot-message-id-next="14">Wait for him to come out</span>',
			4 => '<span class="chat_bot_message_btn" data-goto-bot-message-id="0" data-goto-bot-message-id-next="14">Vente til han kommer ut</span>'
		],
		12 => [
			3 => '<span class="chat_bot_message_btn" data-goto-bot-message-id="0" data-goto-bot-message-id-next="19">Hack the cameras on place</span>',
			4 => '<span class="chat_bot_message_btn" data-goto-bot-message-id="0" data-goto-bot-message-id-next="19">Hacke kameraene på stedet</span>'
		],
		13 => [
			3 => '<span class="chat_bot_message_btn" data-goto-bot-message-id="0" data-goto-bot-message-id-next="17">Connect to his mobile phone</span>',
			4 => '<span class="chat_bot_message_btn" data-goto-bot-message-id="0" data-goto-bot-message-id-next="17">Koble til hans mobiltelefon</span>'
		],
		14 => [
			3 => '<img src="/images/icons/chat_icon7.png" alt=""> Agent Jane Blond sent you a voice message.',
			4 => '<img src="/images/icons/chat_icon7.png" alt=""> Agent Jane Blond sendte dere en talemelding.'
		],
		15 => [
			3 => '<audio src="/music/chat_audio_en.mp3" controls="controls"></audio>',
			4 => '<audio src="/music/chat_audio_no.mp3" controls="controls"></audio>'
		],
		16 => [
			3 => '<span class="chat_bot_message_btn" data-goto-bot-message-id="10" data-goto-bot-message-id-next="0"><img src="/images/icons/chat_icon3.png" alt=""> BACK</span>',
			4 => '<span class="chat_bot_message_btn" data-goto-bot-message-id="10" data-goto-bot-message-id-next="0"><img src="/images/icons/chat_icon3.png" alt=""> TILBAKE</span>'
		],
		17 => [
			3 => '<img src="/images/icons/chat_icon2.png" alt=""> I was not able to connect to his mobile phone. Try a different solution.',
			4 => '<img src="/images/icons/chat_icon2.png" alt=""> Jeg klarte ikke å koble til mobiltelefonen hans. Prøv en annen løsning.'
		],
		18 => [
			3 => '<span class="chat_bot_message_btn" data-goto-bot-message-id="10" data-goto-bot-message-id-next="0"><img src="/images/icons/chat_icon3.png" alt=""> BACK</span>',
			4 => '<span class="chat_bot_message_btn" data-goto-bot-message-id="10" data-goto-bot-message-id-next="0"><img src="/images/icons/chat_icon3.png" alt=""> TILBAKE</span>'
		],
		19 => [
			3 => '<img src="/images/icons/chat_icon5.png" alt=""> Great',
			4 => '<img src="/images/icons/chat_icon5.png" alt=""> Flott'
		],
		20 => [
			3 => 'Are you ready to assist me in hacking their cameras?',
			4 => 'Er dere klare for å hjelpe meg med å hacke kameraene deres?'
		],
		21 => [
			3 => 'Ok, I’ll wait <img src="/images/icons/chat_icon8.png" alt="">',
			4 => 'Den er grei, venter på dere her <img src="/images/icons/chat_icon8.png" alt="">'
		],
		22 => [
			3 => '<span class="chat_bot_message_btn" data-goto-bot-message-id="0" data-goto-bot-message-id-next="23">We are ready now <img src="/images/icons/chat_icon9.png" alt=""></span>',
			4 => '<span class="chat_bot_message_btn" data-goto-bot-message-id="0" data-goto-bot-message-id-next="23">Vi er klare nå <img src="/images/icons/chat_icon9.png" alt=""></span>'
		],
		23 => [
			3 => '<img src="/images/icons/chat_icon12.png" alt=""> I see we can hack their employee portal to access the surveillance cameras at the location.',
			4 => '<img src="/images/icons/chat_icon12.png" alt=""> Ser at vi kan hacke ansattportalen deres og få tilgang til overvåkningskameraene på stedet.'
		],
		24 => [
			3 => 'Here is the employee portal <img src="/images/icons/chat_icon10.png" alt=""><br><a href="https://cafe.questalize.com/" target="_blank">https://cafe.questalize.com/</a>',
			4 => 'Her er ansattportalen <img src="/images/icons/chat_icon10.png" alt=""><br><a href="https://cafe.questalize.com/" target="_blank">https://cafe.questalize.com/</a>'
		],
		25 => [
			3 => '<img src="/images/icons/chat_icon11.png" alt=""> Crack the password to access the cameras. Let me know once you have succeeded with it!',
			4 => '<img src="/images/icons/chat_icon11.png" alt=""> Knekk passordet for å få tilgang til kameraene. Gi meg beskjed når dere har lyktes med det!'
		],
		26 => [
			3 => '<span class="chat_bot_message_btn" data-goto-bot-message-id="0" data-goto-bot-message-id-next="28">DONE <img src="/images/icons/chat_icon9.png" alt=""></span>',
			4 => '<span class="chat_bot_message_btn" data-goto-bot-message-id="0" data-goto-bot-message-id-next="28">FERDIG <img src="/images/icons/chat_icon9.png" alt=""></span>'
		],
		27 => [
			3 => '<img src="/images/icons/chat_icon2.png" alt=""> I do not understand your message. Are you ready to assist me in hacking their cameras?',
			4 => '<img src="/images/icons/chat_icon2.png" alt=""> Jeg forstår ikke ditt svar. Er dere klare for å hjelpe meg med å hacke kameraene deres?'
		],
		28 => [
			3 => '<img src="/images/icons/chat_icon5.png" alt=""> Great',
			4 => '<img src="/images/icons/chat_icon5.png" alt=""> Flott'
		],
		29 => [
			3 => '<img src="/images/icons/chat_icon13.png" alt=""> Review the cameras carefully. Try to find any traces - mobile calls, notes, bank transactions etc.',
			4 => '<img src="/images/icons/chat_icon13.png" alt=""> Gjennomgå kameraene nøye. Prøv å finne noen spor - mobilsamtaler, notater, bank-transaksjoner og lignende.'
		],
		30 => [
			3 => '<span class="chat_bot_message_btn" data-goto-bot-message-id="0" data-goto-bot-message-id-next="32">WE HAVE FOUND A TRACE <img src="/images/icons/chat_icon9.png" alt=""></span>',
			4 => '<span class="chat_bot_message_btn" data-goto-bot-message-id="0" data-goto-bot-message-id-next="32">VI HAR FUNNET ET SPOR <img src="/images/icons/chat_icon9.png" alt=""></span>'
		],
		31 => [
			3 => '<span class="chat_bot_message_btn" data-goto-bot-message-id="0" data-goto-bot-message-id-next="34">WE FIND NO TRACE <img src="/images/icons/chat_icon14.png" alt=""></span>',
			4 => '<span class="chat_bot_message_btn" data-goto-bot-message-id="0" data-goto-bot-message-id-next="34">VI FINNER IKKE NOEN SPOR <img src="/images/icons/chat_icon14.png" alt=""></span>'
		],
		32 => [
			3 => 'Great job, agents! <img src="/images/icons/chat_icon15.png" alt="">',
			4 => 'Bra jobbet, agenter! <img src="/images/icons/chat_icon15.png" alt="">'
		],
		33 => [
			3 => 'I will leave you and let you continue the investigation together with agent Jane.',
			4 => 'Jeg forlater dere, og lar dere fortsetter etterforskningen sammen med agent Jane.'
		],
		/*34 => [
			3 => '<img src="/images/icons/chat_icon16.png" alt=""> Find Axel Rod over cameras. Check the personal files if you need to refresh how he looks. Try to spot his calls, messages or notes. Try also to get information about his bank card – maybe, he left a trace there. Let me know once you have succeeded!',
			4 => '<img src="/images/icons/chat_icon16.png" alt=""> Finn Axel Rod over kameraer. Sjekk Personregisteret om dere trenger å se igjen hvordan han ser ut. Prøv å fange opp hans samtaler, meldinger eller notater. Prøv også å skaffe informasjon om hans bankkort - kanskje, han etterlot et spor der. Gi meg beskjed når dere har lyktes med det!'
		],*/
		34 => [
			3 => '<img src="/images/icons/chat_icon16.png" alt=""> Find Axel Rod over cameras. Check the personal files if you need to refresh how he looks. Try to spot his calls, messages or notes.',
			4 => '<img src="/images/icons/chat_icon16.png" alt=""> Finn Axel Rod over kameraer. Sjekk Personregisteret om dere trenger å se igjen hvordan han ser ut. Prøv å fange opp hans samtaler, meldinger eller notater.'
		],
		35 => [
			3 => '<span class="chat_bot_message_btn" data-goto-bot-message-id="0" data-goto-bot-message-id-next="32">DONE, WE IDENTIFIED THE MEETING PLACE <img src="/images/icons/chat_icon9.png" alt=""></span>',
			4 => '<span class="chat_bot_message_btn" data-goto-bot-message-id="0" data-goto-bot-message-id-next="32">FERDIG, VI IDENTIFISERTE MØTESTEDET <img src="/images/icons/chat_icon9.png" alt=""></span>'
		],
		36 => [
			3 => '<img src="/images/icons/chat_icon17.png" alt=""> I got access to the ID of one of the employees: Liza9944.',
			4 => '<img src="/images/icons/chat_icon17.png" alt=""> Jeg fikk tak i IDen til en av de ansatte: Liza9944.'
		],
		37 => [
			3 => '<img src="/images/icons/chat_icon18.png" alt=""> See also if you can find any more information in the employee portal.',
			4 => '<img src="/images/icons/chat_icon18.png" alt=""> Se også om du kan finne noe mer informasjon i ansattportalen.'
		],
		38 => [
			3 => '<img src="/images/icons/chat_icon19.png" alt=""> Look also through all information in the employee portal.',
			4 => '<img src="/images/icons/chat_icon19.png" alt=""> Se også gjerne gjennom all informasjonen i ansattportalen.'
		],
		39 => [
			3 => '<img src="/images/icons/chat_icon20.png" alt=""> Try to get information about his bank card – maybe, he left a trace some place.',
			4 => '<img src="/images/icons/chat_icon20.png" alt=""> Prøv å skaffe informasjon om hans bankkort - kanskje, han etterlot et spor noe sted.'
		],
		40 => [
			3 => 'Let me know once you have succeeded!',
			4 => 'Gi meg beskjed når dere har lyktes med det!'
		]
	];

	switch ($_POST['op']) {
		// загрузить актуальное состояние чата
		case 'updateChatMessages':
			$lang_abbr = isset($_POST['lang_abbr']) ? strip_tags(trim($_POST['lang_abbr'])) : '';

			$lang_id = $lang->getLangIdByHtmlAttr($lang_abbr);
			$translation = $lang->getWordsByPage('game', $lang_id);

			$chat_messages = $function->getChatMessages($userInfo['team_id'], $lang_id);
			if ($chat_messages) {
				$return['messages'] = $function->printChatMessages($chat_messages, $lang_id);
				$return['chat_message_empty'] = false;
				// $return['input_disabled'] = false;
			} else {
				$return['messages'] = $translation['text35'];
				$return['chat_message_empty'] = true;
				// $return['input_disabled'] = true;
			}

			$team_info = $function->teamInfo($userInfo['team_id']);
			if ($team_info && !empty($team_info['chat_send_message_access'])) {
				$return['input_disabled'] = false;
			} else {
				$return['input_disabled'] = true;
			}

			print_r(json_encode($return));
		    break;

		// получить текст сообщение от бота, которое надо напечатать
		case 'getDefaultChatMessage':
			$message_id = isset($_POST['message_id']) ? (int) $_POST['message_id'] : 0;

			if (!empty($message_id) && array_key_exists($message_id, $default_messages)) {
				$return['success'] = $default_messages[$message_id];
			} else {
				$return['error'] = 'ok';
			}

			print_r(json_encode($return));
		    break;

		// добавить сообщение в историю переписки. Со стороны бота
		case 'addChatMessageToHistory':
			$message_id = isset($_POST['message_id']) ? (int) $_POST['message_id'] : 0;
			$side = isset($_POST['side']) ? strip_tags(trim($_POST['side'])) : 'bot';

			if (!empty($message_id) && array_key_exists($message_id, $default_messages)) {
				$return['success'] = 'ok';

				$sql = "INSERT INTO `chat_messages` SET `team_id` = {?}, `side` = {?}, `datetime` = NOW(), `message_default_id` = {?}";
				$chat_message_id = $db->query($sql, [$userInfo['team_id'], $side, $message_id]);
				if ($chat_message_id) {
					$sql = "INSERT INTO `chat_messages_description` SET `chat_message_id` = {?}, `lang_id` = {?}, `message` = {?}";
					$db->query($sql, [$chat_message_id, 3, $default_messages[$message_id][3]]);

					$sql = "INSERT INTO `chat_messages_description` SET `chat_message_id` = {?}, `lang_id` = {?}, `message` = {?}";
					$db->query($sql, [$chat_message_id, 4, $default_messages[$message_id][4]]);
				}
			} else {
				$return['error'] = 'ok';
			}

			print_r(json_encode($return));
		    break;

		// получить идентификатор дефолтного последнего сообщение от бота
		case 'getLastChatMessageDefaultIdFromHistoryByBot':
			$sql = "SELECT `message_default_id` FROM `chat_messages` WHERE `team_id` = {?} AND `side` = {?} ORDER BY `id` DESC LIMIT 1";
			$return['success'] = (int) $db->selectCell($sql, [$userInfo['team_id'], 'bot']);

			print_r(json_encode($return));
		    break;

		// добавить сообщение в историю переписки. Со стороны пользователя
		case 'addChatMessageToHistoryByTeam':
			$message_text = isset($_POST['message_text']) ? strip_tags(trim($_POST['message_text'])) : '';
			$bot_last_message_default_id = isset($_POST['bot_last_message_default_id']) ? (int) $_POST['bot_last_message_default_id'] : 0;

			if (!empty($message_text)) {
				$sql = "INSERT INTO `chat_messages` SET `team_id` = {?}, `side` = {?}, `datetime` = NOW(), `message_default_id` = {?}";
				$chat_message_id = $db->query($sql, [$userInfo['team_id'], 'team', 0]);
				if ($chat_message_id) {
					$sql = "INSERT INTO `chat_messages_description` SET `chat_message_id` = {?}, `lang_id` = {?}, `message` = {?}";
					$db->query($sql, [$chat_message_id, 3, $message_text]);

					$sql = "INSERT INTO `chat_messages_description` SET `chat_message_id` = {?}, `lang_id` = {?}, `message` = {?}";
					$db->query($sql, [$chat_message_id, 4, $message_text]);
				}
			}

			$return['success'] = 'ok';

			// возвращаем также, првильно ли ввели текст
			$return['success_text'] = 'false';

			if (!empty($message_text)) {
				if ($bot_last_message_default_id == 2) { // вводим имя искомого
					$message_text = mb_strtolower($message_text, 'UTF-8');
					$message_text = str_replace(' ', '', $message_text);

					// if (mb_strtolower($message_text, 'UTF-8') == 'axel rod' || mb_strtolower($message_text, 'UTF-8') == 'axelrod') {
					if ($message_text == 'axelrod') {
						$return['success_text'] = 'true';
					}
				} elseif ($bot_last_message_default_id == 20 || $bot_last_message_default_id == 27) { // будем ли асистировать боту во взломе камеры
					$message_text = mb_strtolower($message_text, 'UTF-8');
					$message_text = str_replace(' ', '', $message_text);

					// $no_array = ['nei', 'no', 'maybe', 'kanskje', 'vet ikke', 'don’t know', 'don\'t know', 'do not know', 'dont know', 'tja'];
					// $yes_array = ['yes', 'ja', 'oh yes', 'hell yeah', 'så klart', 'sa klart', 'absolutt', 'ja da', 'ja vel', 'javel', 'sure', 'of course', 'absolutely', 'yeah', 'jepp', 'yep', 'yup'];
					$no_array = ['nei', 'no', 'maybe', 'kanskje', 'vetikke', 'don’tknow', 'don\'t know', 'donotknow', 'dontknow', 'tja', 'hellno'];
					$yes_array = ['yes', 'ja', 'ohyes', 'hellyeah', 'såklart', 'saklart', 'absolutt', 'jada', 'javel', 'sure', 'ofcourse', 'absolutely', 'yeah', 'jepp', 'yep', 'yup', 'hellyes'];

					if (in_array($message_text, $no_array)) {
						$return['success_text'] = 'false_no';
					} elseif (in_array($message_text, $yes_array)) {
						$return['success_text'] = 'false_yes';
					}
				}
			}

			print_r(json_encode($return));
		    break;

		// удалить сообщение по идентификатору
		case 'removeMessageById':
			$message_id1 = isset($_POST['message_id1']) ? (int) $_POST['message_id1'] : 0;
			$message_id2 = isset($_POST['message_id2']) ? (int) $_POST['message_id2'] : 0;
			$message_id3 = isset($_POST['message_id3']) ? (int) $_POST['message_id3'] : 0;
			$message_btn_text = isset($_POST['message_btn_text']) ? strip_tags(trim($_POST['message_btn_text'])) : '';

			if (!empty($message_id1)) {
				$sql = "DELETE FROM `chat_messages` WHERE `id` = {?}";
				$db->query($sql, [$message_id1]);
				$sql = "DELETE FROM `chat_messages_description` WHERE `id` = {?}";
				$db->query($sql, [$message_id1]);
			}
			if (!empty($message_id2)) {
				$sql = "DELETE FROM `chat_messages` WHERE `id` = {?}";
				$db->query($sql, [$message_id2]);
				$sql = "DELETE FROM `chat_messages_description` WHERE `id` = {?}";
				$db->query($sql, [$message_id2]);
			}
			if (!empty($message_id3)) {
				$sql = "DELETE FROM `chat_messages` WHERE `id` = {?}";
				$db->query($sql, [$message_id3]);
				$sql = "DELETE FROM `chat_messages_description` WHERE `id` = {?}";
				$db->query($sql, [$message_id3]);
			}

			// добавить сообщение в историю переписки. Со стороны пользователя
			if (!empty($message_btn_text)) {
				if ($message_btn_text == 'back') {
					$text_en = 'BACK';
					$text_no = 'TILBAKE';
				} elseif ($message_btn_text == 'wait_him') {
					$text_en = 'Wait for him to come out';
					$text_no = 'Vente til han kommer ut';
				} elseif ($message_btn_text == 'connect_mobile') {
					$text_en = 'Connect to his mobile phone';
					$text_no = 'Koble til hans mobiltelefon';
				} elseif ($message_btn_text == 'hack_camera') {
					$text_en = 'Hack the cameras on place';
					$text_no = 'Hacke kameraene på stedet';
				} elseif ($message_btn_text == 'hack_camera_ready_now') {
					$text_en = 'We are ready now';
					$text_no = 'Vi er klare nå';
				} elseif ($message_btn_text == 'hack_camera_done') {
					$text_en = 'DONE';
					$text_no = 'FERDIG';
				} elseif ($message_btn_text == 'great_job') {
					$text_en = 'WE HAVE FOUND A TRACE';
					$text_no = 'VI HAR FUNNET ET SPOR';
				} elseif ($message_btn_text == 'great_job2') {
					$text_en = 'DONE, WE IDENTIFIED THE MEETING PLACE';
					$text_no = 'FERDIG, VI IDENTIFISERTE MØTESTEDET';
				} elseif ($message_btn_text == 'no_trace') {
					$text_en = 'WE FIND NO TRACE';
					$text_no = 'VI FINNER IKKE NOEN SPOR';
				}

				$sql = "INSERT INTO `chat_messages` SET `team_id` = {?}, `side` = {?}, `datetime` = NOW(), `message_default_id` = {?}";
				$chat_message_id = $db->query($sql, [$userInfo['team_id'], 'team', 0]);
				if ($chat_message_id) {
					$sql = "INSERT INTO `chat_messages_description` SET `chat_message_id` = {?}, `lang_id` = {?}, `message` = {?}";
					$db->query($sql, [$chat_message_id, 3, $text_en]);

					$sql = "INSERT INTO `chat_messages_description` SET `chat_message_id` = {?}, `lang_id` = {?}, `message` = {?}";
					$db->query($sql, [$chat_message_id, 4, $text_no]);
				}
			}

			$return['success'] = 'ok';

			print_r(json_encode($return));
		    break;
	}
}
