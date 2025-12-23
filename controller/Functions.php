<?php
require_once __DIR__ . '/../views/template/svg.php';
class Functions
{
    public $svg;
    public $db;
    private static $functions = null;
    
    function __construct()
    {
        global $svg;
        $this->svg = $svg;
        $this->db = DataBase::getDB();
    }

    // получаем единственный экземпляр класса
    public static function getFunctions()
    {
        if (self::$functions == null) {
            self::$functions = new Functions();
        }
        return self::$functions;
    }

    // активен ли сервер
    public function isDaemonActive($pidfile) {
        $server_active = false;

        if (is_file($pidfile)) {
            $pid = file_get_contents($pidfile);

            if (posix_kill($pid, 0)) {
                // демон уже запущен
                $server_active = true;
            } else {
                // pid-файл есть, но процесса нет
                if (!unlink($pidfile)) {
                    // не могу уничтожить pid-файл. ошибка
                }
                $server_active = false;
            }
        }

        return $server_active;
    }

    // конвертуємо дату з російського формату у англійський (Y-m-d)
    public function fromRusDatetimeToEng($datetime)
    {
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

    // конвертуємо дату з англійського формату у російський (d.m.Y)
    public function fromEngDatetimeToRus($datetime)
    {
        $return = '';

        if (stripos($datetime, ' ') !== false) {
            $parts = explode(' ', $datetime);

            $from_date = $parts[0];
            $from_time = $parts[1];
        } else {
            $from_date = $datetime;
            $from_time = '';
        }

        if (stripos($from_date, '-') !== false) {
            $parts1 = explode('-', $from_date);
            for ($i = count($parts1) - 1; $i >= 0; $i--) { 
                $return .= $parts1[$i] . '.';
            }
            $return = substr($return, 0, -1);
        }

        if (!empty($from_time)) {
            $time_object = new DateTime($from_time);
            $return .= ' ' . $time_object->format('H:i');
        }

        return $return;
    }

    // ip юзера
    public function getIp() {
        $ipaddress = '';

        if (getenv('HTTP_CLIENT_IP')) {
            $ipaddress = getenv('HTTP_CLIENT_IP');
        } else if (getenv('HTTP_X_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        } else if (getenv('HTTP_X_FORWARDED')) {
            $ipaddress = getenv('HTTP_X_FORWARDED');
        } else if (getenv('HTTP_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        } else if (getenv('HTTP_FORWARDED')) {
            $ipaddress = getenv('HTTP_FORWARDED');
        } else if (getenv('REMOTE_ADDR')) {
            $ipaddress = getenv('REMOTE_ADDR');
        } else {
            $ipaddress = 'UNKNOWN';
        }

        return $ipaddress;
    }

    // аналог языковой функции из класса Language
    private function getWordsByPage($page, $lang_id)
    {
        $return = [];

        $sql = "SELECT `val`, `field` FROM `lang_words_admin` WHERE `page` = {?} AND `language_id` = {?} ORDER BY `id`";
        $words = $this->db->select($sql, [$page, $lang_id]);
        if ($words) {
            foreach ($words as $word) {
                $return[$word['field']] = $word['val'];
            }
        }

        return $return;
    }

    // не вышло ли еще время доступа к игре
    public function isActiveVerifyCode($team_id)
    {
        // $sql = "SELECT `id` FROM `teams` WHERE `id` = {?} AND `create` >= NOW() - INTERVAL 1 DAY AND `status` = {?}";
        $sql = "SELECT `id` FROM `teams` WHERE `id` = {?} AND `status` = {?}";
        $active_code = $this->db->selectCell($sql, [(int) $team_id, 1]);
        return $active_code ? true : false;
    }

    // инфа о команде
    public function teamInfo($team_id)
    {
        $sql = "SELECT * FROM `teams` WHERE `id` = {?} LIMIT 1";
        return $this->db->selectRow($sql, [$team_id]);
    }

    // пишем действие в историю действий команды
    public function addTeamActionHistory($team_id, $action_id, $user_id)
    {
        $sql = "INSERT INTO `team_history_action` SET `team_id` = {?}, `action_id` = {?}, `datetime` = NOW(), `user_id` = {?}";
        $this->db->query($sql, [(int) $team_id, (int) $action_id, (int) $user_id]);

        $sql = "UPDATE `teams` SET `last_action_id` = {?} WHERE `id` = {?}";
        $this->db->query($sql, [(int) $action_id, (int) $team_id]);
    }

    // пишем действие в историю действий команды по названию действия
    public function addActionToHistoryByActionName($team_id, $action_name)
    {
        if (!empty($action_name)) {
            $sql = "SELECT `id` FROM `actions` WHERE `action` = {?} LIMIT 1";
            $action_id = $this->db->selectCell($sql, [$action_name]);
            if ($action_id) {
                $sql = "INSERT INTO `team_history_action` SET `team_id` = {?}, `action_id` = {?}, `datetime` = NOW()";
                $this->db->query($sql, [(int) $team_id, (int) $action_id]);

                $sql = "UPDATE `teams` SET `last_action_id` = {?} WHERE `id` = {?}";
                $this->db->query($sql, [(int) $action_id, (int) $team_id]);
            }
        }
    }

    // получаем историю действий команды
    public function getTeamActionHistory($team_id, $order = 'DESC')
    {
        $sql = "SELECT * FROM `team_history_action` WHERE `team_id` = {?} ORDER BY `datetime` " . $order;
        return $this->db->select($sql, [(int) $team_id]);
    }

    // список подсказок в зависимости от шага игры
    public function getHintsByStep($step, $lang_id)
    {
        $sql = "
            SELECT h.points, h.number, h.type, hd.text, h.id
            FROM hints h
            JOIN hints_description hd ON h.id = hd.hint_id
            WHERE h.step = {?}
            AND hd.lang_id = {?}
            ORDER BY h.sort, h.number
        ";
        return $this->db->select($sql, [$step, $lang_id]);
    }

    // актуальное состояние подсказок. Вывод страницы подсказок справа и слева
    public function getHintPageHints($team_id, $lang_id)
    {
        $return = [
            'success_hint_left' => '',
            'success_hint_right' => '',
            'success_hint_right_title' => '', // заголовок блока со списком подсказок
            'success_hint_right_text' => '' // текст блока со списком подсказок
        ];

        $translation = $this->getWordsByPage('game', $lang_id);

        $team_info = $this->teamInfo($team_id);

        // активный текст подсказок. Слева
        $active_hint_ids = []; // доп массив для проверки активных

        $active_hints = json_decode($team_info['active_hints'], true);
        if (count($active_hints) == 0) {
            $return['success_hint_left'] = '<span>' . $translation['text25'] . '</span>';
        } else {
            foreach ($active_hints as $hint_id) {
                /*$sql = "
                    SELECT h.id, h.points, hd.text, h.type, h.number, hd.text_btn
                    FROM hints h
                    JOIN hints_description hd ON h.id = hd.hint_id
                    WHERE hd.lang_id = {?}
                    AND h.id = {?}
                    ORDER BY h.type DESC, h.id ASC
                ";*/
                $sql = "
                    SELECT h.id, h.points, hd.text, h.type, h.number, hd.text_btn
                    FROM hints h
                    JOIN hints_description hd ON h.id = hd.hint_id
                    WHERE hd.lang_id = {?}
                    AND h.id = {?}
                    ORDER BY h.sort DESC, h.number ASC
                ";
                $hint_info = $this->db->selectRow($sql, [$lang_id, (int) $hint_id]);
                if ($hint_info) {
                    $return['success_hint_left'] .= '<span><p>' . $hint_info['text_btn'] . ':</p> ' . $hint_info['text'] . '</span>';

                    $active_hint_ids[] = $hint_info['id'];
                }
            }
        }

        // доступный список подсказок. Справа
        $list_hints = json_decode($team_info['list_hints'], true);
        if (count($list_hints) > 0) {
            foreach ($list_hints as $hint_id) {
                /*$sql = "
                    SELECT h.id, h.points, hd.text, h.type, h.number, hd.text_btn
                    FROM hints h
                    JOIN hints_description hd ON h.id = hd.hint_id
                    WHERE hd.lang_id = {?}
                    AND h.id = {?}
                    ORDER BY h.type DESC, h.id ASC
                ";*/
                $sql = "
                    SELECT h.id, h.points, hd.text, h.type, h.number, hd.text_btn, h.answer_open_ids, h.answer_open_id_count
                    FROM hints h
                    JOIN hints_description hd ON h.id = hd.hint_id
                    WHERE hd.lang_id = {?}
                    AND h.id = {?}
                    ORDER BY h.sort DESC, h.number ASC
                ";
                $hint_info = $this->db->selectRow($sql, [$lang_id, (int) $hint_id]);
                if ($hint_info) {
                    // можно ли открыть ответ
                    $answer_can_open = '';
                    if ($hint_info['type'] == 'answer') {
                        // $answer_can_open = ' list_hint_item_answer_dont_open';

                        // Список идентификаторов подсказок, которые должны быть открыты для доступности ответа
                        $answer_open_ids = json_decode($hint_info['answer_open_ids'], true);

                        if (count($answer_open_ids) > 0) {
                            // Сколько из списка подсказок должно быть открыто. 0 - все
                            if (empty($hint_info['answer_open_id_count'])) {
                                // var_dump($answer_open_ids);
                                foreach ($answer_open_ids as $answer_open_id) {
                                    if (!in_array((int) $answer_open_id, $active_hint_ids)) {
                                        $answer_can_open = ' list_hint_item_answer_dont_open';

                                        break;
                                    }
                                }
                            } else {
                                $count_opened_with_list = 0;

                                foreach ($answer_open_ids as $answer_open_id) {
                                    if (in_array($answer_open_id, $active_hint_ids)) {
                                        $count_opened_with_list++;
                                    }
                                }

                                if ($count_opened_with_list < $hint_info['answer_open_id_count']) {
                                    $answer_can_open = ' list_hint_item_answer_dont_open';
                                }
                            }
                        }

                        // if (count($list_hints) - 1 == count($active_hint_ids) && !in_array($hint_id, $active_hint_ids)) {
                        //     $answer_can_open = '';
                        // }
                    }

                    $return['success_hint_right'] .= '<div class="btn_wrapper btn_wrapper_blue_light list_hint_item list_hint_item_points_' . $hint_info['points'] . '_wrapper' . $answer_can_open . (in_array($hint_id, $active_hint_ids) ? ' list_hint_item_opened' : '') . '" data-hint-id="' . $hint_id . '">
                                                        <div class="btn btn_blue_light">
                                                            <span>' . $hint_info['text_btn'] . '</span>
                                                            <p class="list_hint_item_points list_hint_item_points_' . $hint_info['points'] . '">
                                                                <svg width="42" height="34" viewBox="0 0 42 34" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.18426 1H40.6843V27.24L35.6636 33H1.16357V6.76L6.18426 1Z" fill="#FF0303" fill-opacity="0.5" stroke="#FF0303"/></svg>
                                                                <span>-' . $hint_info['points'] . '</span>
                                                            </p>
                                                        </div>
                                                    </div>';
                }
            }
        }

        // заголовок
        if (!empty($team_info['list_hints_title_lang_var'])) {
            $sql = "SELECT `val` FROM `lang_words_admin` WHERE `field` = {?} AND `page` = {?} AND `language_id` = {?}";
            $list_hints_title_lang_var = $this->db->selectCell($sql, [$team_info['list_hints_title_lang_var'], 'game', $lang_id]);
            if (!empty($list_hints_title_lang_var)) {
                $return['success_hint_right_title'] = '<span>' . $list_hints_title_lang_var . '</span>';

                if ($team_info['list_hints_title_lang_var'] == 'text26') {
                    $return['success_hint_right_title'] .= '<svg width="27" height="29" viewBox="0 0 27 29" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.72127 0H26.4713V2.19232L23.9136 5.1154H0.163574V2.92308L2.72127 0Z" fill="#00F0FF"/><path d="M8.56746 10.2307H26.4713V12.423L23.9136 15.3461H6.00977V13.1538L8.56746 10.2307Z" fill="#00F0FF"/><path d="M7.47121 24.1155L11.4904 24.1155V26.3078L9.66352 28.5001L5.64429 28.5001V26.3078L7.47121 24.1155Z" fill="#00F0FF"/><path d="M11.125 14.6153L11.125 22.2884L8.93268 22.2884L6.0096 19.7307L6.0096 13.1538L8.20192 13.1538L11.125 14.6153Z" fill="#00F0FF"/><path d="M20.625 10.596L20.625 0.730659L22.8173 0.730659L26.4711 1.46143L26.4711 10.9614L23.5481 10.9614L20.625 10.596Z" fill="#00F0FF"/></svg>';
                }
            }
        }

        // текст
        if (!empty($team_info['list_hints_text_lang_var'])) {
            $sql = "SELECT `val` FROM `lang_words_admin` WHERE `field` = {?} AND `page` = {?} AND `language_id` = {?}";
            $list_hints_text_lang_var = $this->db->selectCell($sql, [$team_info['list_hints_text_lang_var'], 'game', $lang_id]);
            if (!empty($list_hints_text_lang_var)) {
                $return['success_hint_right_text'] = $list_hints_text_lang_var;
            }
        }

        return $return;
    }

    // список сообщений в чате
    public function getChatMessages($team_id, $lang_id)
    {
        $sql = "
            SELECT c.id, c.team_id, c.side, c.datetime, c.message_default_id, cd.lang_id, cd.message
            FROM chat_messages c
            JOIN chat_messages_description cd ON c.id = cd.chat_message_id
            WHERE c.team_id = {?}
            AND cd.lang_id = {?}
            ORDER BY c.datetime
        ";
        return $this->db->select($sql, [(int) $team_id, (int) $lang_id]);
    }

    // выводим сообщения в чате
    public function printChatMessages($messages, $lang_id)
    {
        $return = '';

        if (count($messages) > 0) {
            $translation = $this->getWordsByPage('game', $lang_id);

            $today = new DateTime();
            $yesterday = new DateTime();
            $yesterday->sub(new DateInterval('P1D'));

            // выводим текст о том, вчерашние или сегодняшние сообщения
            $date_message_prev = new DateTime($messages[0]['datetime']);

            if ($yesterday->format('Y-m-d') == $date_message_prev->format('Y-m-d')) {
                $return .= '<div class="chat_message_day_title">' . $translation['text33'] . '</div>'; // вчера
            } else {
                $return .= '<div class="chat_message_day_title">' . $translation['text32'] . '</div>'; // сегодня
            }

            // от кого было предыдущее сообщение
            $prev_message_side = $messages[0]['side'];
            // меняем на противоположное, чтобы всегда выводить первую иконку
            if ($prev_message_side == 'team') {
                $prev_message_side = 'bot';
            } else {
                $prev_message_side = 'team';
            }

            // непосредственно выводим сообщения
            foreach ($messages as $message) {
                // если были вчерашние сообщения, то выводим надпись о сегодняшнем дне
                $date_message_cur = new DateTime($message['datetime']);
                if ($date_message_cur->format('Y-m-d') != $date_message_prev->format('Y-m-d')) {
                    $return .= '<div class="chat_message_day_title">' . $translation['text32'] . '</div>';
                }
                $date_message_prev = new DateTime($message['datetime']);

                // сами сообщения
                $return .= $this->printOneMessageChat($message['id'], $message['side'], $message['message'], $prev_message_side);

                $prev_message_side = $message['side'];
            }
        }

        return $return;
    }

    // вывод одного сообщения в чате
    public function printOneMessageChat($message_id, $side, $message, $prev_message_side)
    {
        $return = '<div class="chat_message_section chat_message_section_from_' . $side . '">';

            // если предыдущее сообщение было не от этой стороны, то выводим иконку
            $icon = '';

            if ($side != $prev_message_side) {
                if ($side == 'bot') {
                    $icon = '<img src="/images/bot_face_small.png" alt="">';
                } elseif ($side == 'team') {
                    $icon = '<img src="/images/chat_face_player.png" alt="">';
                }
            }

            if (!empty($icon)) {
                $return .= '<div class="chat_message_icon">' . $icon . '</div>';
            }

            // само сообщение
            $return .= '<div class="chat_message_item" data-message_id="' . $message_id . '">' . $message . '</div>';

        $return .= '</div>';

        return $return;
    }

    // добавить новый tools к списку доступных в одной ячейке таблицы teams
    public function updateTeamListTools($team_id, $new_tool)
    {
        $team_info = $this->teamInfo($team_id);

        $list_tools = json_decode($team_info['list_tools'], true);

        $list_tools[] = $new_tool;

        $list_tools = array_unique($list_tools);

        // после array_unique могут появится индексы. Убираем их
        $save_array = [];

        foreach ($list_tools as $tool) {
            $save_array[] = $tool;
        }

        // сохраняем
        $sql = "UPDATE `teams` SET `list_tools` = {?} WHERE `id` = {?}";
        $this->db->query($sql, [json_encode($save_array, JSON_UNESCAPED_UNICODE), $team_id]);
    }

    // добавить новый tools к списку активных (уже открытых) в одной ячейке таблицы teams
    public function updateTeamActiveTools($team_id, $new_tool)
    {
        $team_info = $this->teamInfo($team_id);

        $active_tools = json_decode($team_info['active_tools'], true);

        $active_tools[] = $new_tool;

        $active_tools = array_unique($active_tools);

        // после array_unique могут появится индексы. Убираем их
        $save_array = [];

        foreach ($active_tools as $tool) {
            $save_array[] = $tool;
        }

        // сохраняем
        $sql = "UPDATE `teams` SET `active_tools` = {?} WHERE `id` = {?}";
        $this->db->query($sql, [json_encode($save_array, JSON_UNESCAPED_UNICODE), $team_id]);
    }

    // добавить новый database к списку доступных в одной ячейке таблицы teams
    public function updateTeamListDatabases($team_id, $new_database)
    {
        $team_info = $this->teamInfo($team_id);

        $list_databases = json_decode($team_info['list_databases'], true);

        $list_databases[] = $new_database;

        $list_databases = array_unique($list_databases);

        // после array_unique могут появится индексы. Убираем их
        $save_array = [];

        foreach ($list_databases as $database) {
            $save_array[] = $database;
        }

        // сохраняем
        $sql = "UPDATE `teams` SET `list_databases` = {?} WHERE `id` = {?}";
        $this->db->query($sql, [json_encode($save_array, JSON_UNESCAPED_UNICODE), $team_id]);
    }

    // добавить новый файл к списку доступных в одной ячейке таблицы teams
    public function updateTeamListFiles($team_id, $new_file_id)
    {
        $team_info = $this->teamInfo($team_id);

        $list_files = json_decode($team_info['list_files'], true);

        $list_files[] = $new_file_id;

        $list_files = array_unique($list_files);

        // после array_unique могут появится индексы. Убираем их
        $save_array = [];

        foreach ($list_files as $file) {
            $save_array[] = $file;
        }

        // сохраняем
        $sql = "UPDATE `teams` SET `list_files` = {?} WHERE `id` = {?}";
        $this->db->query($sql, [json_encode($save_array, JSON_UNESCAPED_UNICODE), $team_id]);
    }






/* DASHBOARD */
    // загрузить конкретный экран (с переключателем табов) для dashboard
    public function uploadTypeTabsDashboardStep($step, $lang_id, $team_id)
    {
        switch ($step) {
            case 'accept_new_mission': $return = $this->uploadDashboardNewMission($lang_id); break;
            case 'company_name': $return = $this->uploadDashboardCompanyName($lang_id); break;
            case 'geo_coordinates': $return = $this->uploadDashboardGeoCoordinates($lang_id); break;
            case 'african_partner': $return = $this->uploadDashboardAfricanPartner($lang_id, $team_id); break;
            case 'metting_place': $return = $this->uploadDashboardMettingPlace($lang_id, $team_id); break;
            case 'room_name': $return = $this->uploadDashboardRoomName($lang_id, $team_id); break;
            case 'password': $return = $this->uploadDashboardPassword($lang_id, $team_id); break;
            
            default: $return = $this->uploadDashboardNewMission($lang_id); break;
        }

        return $return;
    }

    // dashboard - новая миссия
    private function uploadDashboardNewMission($lang_id)
    {
        $translation = $this->getWordsByPage('game', $lang_id);

        $return = [];

        $return['titles'] = '<div class="dashboard_tab_title dashboard_tab_title_active" data-tab="tab1">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper">
                                        <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.75 0H21V2L19.25 4H0V2L1.75 0Z" fill="#00F0FF"/><path d="M1.75 6H7.25V19L5.5 21H0V8L1.75 6Z" fill="#00F0FF"/><path d="M11.75 10H21V13L19.25 15H10V12L11.75 10Z" fill="#00F0FF"/><path d="M10.75 6H21V8L20.25 9H10V7L10.75 6Z" fill="#00F0FF"/><path d="M11.75 16H21V19L19.25 21H10V18L11.75 16Z" fill="#00F0FF"/></svg>
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text11'] . '</div>
                                </div>
                            </div>';

        $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_new_mission dashboard_tab_content_item_active" data-tab="tab1">
                                 
                            </div>';

        return $return;
    }

    // dashboard - company name
    private function uploadDashboardCompanyName($lang_id)
    {
        $translation = $this->getWordsByPage('game', $lang_id);

        $return = [];

        $return['titles'] = '<div class="dashboard_tab_title dashboard_tab_title_active" data-tab="tab1">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper">
                                        <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.75 0H21V2L19.25 4H0V2L1.75 0Z" fill="#00F0FF"/><path d="M1.75 6H7.25V19L5.5 21H0V8L1.75 6Z" fill="#00F0FF"/><path d="M11.75 10H21V13L19.25 15H10V12L11.75 10Z" fill="#00F0FF"/><path d="M10.75 6H21V8L20.25 9H10V7L10.75 6Z" fill="#00F0FF"/><path d="M11.75 16H21V19L19.25 21H10V18L11.75 16Z" fill="#00F0FF"/></svg>
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text11'] . '</div>
                                </div>
                            </div>';

        $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_company_name dashboard_tab_content_item_active" data-tab="tab1">
                                <div class="dashboard_tab_content_item_company_name_top">
                                    <div class="dashboard_tab_content_item_company_name_top_left">
                                        <img src="/images/dashboard_company_name_top_left_bg.png" class="dashboard_company_name_top_left_bg" alt="">
                                        <div class="dashboard_tab_content_item_company_name_top_text">
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.67969 8.60889H13.3203V11.7339H6.67969V8.60889Z" fill="white"/><path d="M17.9039 5.04333H13.5156V3.1192H18.4402C19.3004 3.1192 20 2.41959 20 1.55975C20 0.699615 19.3004 0 18.4402 0H1.55975C0.699615 0 0 0.699615 0 1.55975C0 2.41959 0.699615 3.1192 1.55975 3.1192H6.48438V5.04333H2.0961C0.940247 5.04333 0 5.98358 0 7.13943V17.9039C0 19.0598 0.940247 20 2.0961 20H17.9039C19.0598 20 20 19.0598 20 17.9039V7.13943C20 5.98358 19.0598 5.04333 17.9039 5.04333ZM7.65625 3.1192H12.3438V5.04333H7.65625V3.1192ZM5.50781 8.09341C5.50781 7.73163 5.80231 7.43713 6.1644 7.43713H13.8356C14.1977 7.43713 14.4922 7.73163 14.4922 8.09341V12.2491C14.4922 12.6114 14.1977 12.9059 13.8356 12.9059H6.1644C5.80231 12.9059 5.50781 12.6114 5.50781 12.2491V8.09341ZM13.9062 17.6062H5.99915C5.67581 17.6062 5.41321 17.3442 5.41321 17.0203C5.41321 16.6969 5.67581 16.4343 5.99915 16.4343H13.9062C14.2297 16.4343 14.4922 16.6969 14.4922 17.0203C14.4922 17.3442 14.2297 17.6062 13.9062 17.6062ZM16.3446 15.6531H3.6554C3.33206 15.6531 3.06946 15.3911 3.06946 15.0671C3.06946 14.7438 3.33206 14.4812 3.6554 14.4812H16.3446C16.6679 14.4812 16.9305 14.7438 16.9305 15.0671C16.9305 15.3911 16.6679 15.6531 16.3446 15.6531Z" fill="white"/></svg>
                                            <span>' . $translation['text48'] . '</span>
                                        </div>
                                    </div>
                                    <div class="dashboard_tab_content_item_company_name_top_right">
                                        <div class="dashboard_tab_content_item_company_name_top_right_title">' . $translation['text49'] . '</div>
                                        <div class="dashboard_tab_content_item_company_name_top_right_text">' . $translation['text50'] . '</div>
                                    </div>
                                </div>
                                <div class="dashboard_tab_content_item_company_name_inner">
                                    <img src="/images/dashboard_tab_content_item_company_name_inner_bg.png" class="dashboard_tab_content_item_company_name_inner_bg" alt="">
                                    <img src="/images/dashboard_tab_content_item_company_name_inner_bg2.png" class="dashboard_tab_content_item_company_name_inner_bg_left_top" alt="">
                                    <img src="/images/dashboard_tab_content_item_company_name_inner_bg2.png" class="dashboard_tab_content_item_company_name_inner_bg_right_bottom" alt="">

                                    <div class="dashboard_tab_content_item_company_name_input_wrapper">
                                        <div class="dashboard_tab_content_item_company_name_input_border_left"></div>
                                        <div class="dashboard_tab_content_item_company_name_input_border_right"></div>
                                        <img src="/images/dashboard_new_mission_line.png" class="dashboard_tab_content_item_company_name_input_line_left" alt="">
                                        <img src="/images/dashboard_new_mission_line.png" class="dashboard_tab_content_item_company_name_input_line_right" alt="">
                                        <input type="text" placeholder="' . $translation['text51'] . '" autocomplete="off" class="dashboard_tab_content_item_company_name_input">
                                        <div class="dashboard_tab_content_item_company_name_error">' . $translation['text86'] . '</div>
                                    </div>
                                    <div class="btn_wrapper btn_wrapper_blue dashboard_tab_content_item_company_name_investigate">
                                        <div class="btn btn_blue">
                                            <span>' . $translation['text52'] . '</span>
                                        </div>
                                        <div class="btn_border_top"></div>
                                        <div class="btn_border_bottom"></div>
                                        <div class="btn_border_left"></div>
                                        <div class="btn_border_left_arcle"></div>
                                        <div class="btn_border_right"></div>
                                        <div class="btn_border_right_arcle"></div>
                                        <div class="btn_bg_top_line"></div>
                                        <div class="btn_bg_bottom_line"></div>
                                        <div class="btn_bg_triangle_left"></div>
                                        <div class="btn_bg_triangle_right"></div>
                                        <div class="btn_circles_top">
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                        </div>
                                        <div class="btn_circles_bottom">
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>';

        return $return;
    }

    // dashboard - geo coordinates
    private function uploadDashboardGeoCoordinates($lang_id)
    {
        $translation = $this->getWordsByPage('game', $lang_id);

        $return = [];

        $return['titles'] = '<div class="dashboard_tab_title dashboard_tab_title_active" data-tab="tab1">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper">
                                        <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.75 0H21V2L19.25 4H0V2L1.75 0Z" fill="#00F0FF"/><path d="M1.75 6H7.25V19L5.5 21H0V8L1.75 6Z" fill="#00F0FF"/><path d="M11.75 10H21V13L19.25 15H10V12L11.75 10Z" fill="#00F0FF"/><path d="M10.75 6H21V8L20.25 9H10V7L10.75 6Z" fill="#00F0FF"/><path d="M11.75 16H21V19L19.25 21H10V18L11.75 16Z" fill="#00F0FF"/></svg>
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text11'] . '</div>
                                </div>
                            </div>';

        $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_geo_coordinates dashboard_tab_content_item_active" data-tab="tab1">
                                <div class="dashboard_tab_content_item_company_name_top">
                                    <div class="dashboard_tab_content_item_company_name_top_left">
                                        <img src="/images/dashboard_company_name_top_left_bg.png" class="dashboard_company_name_top_left_bg" alt="">
                                        <div class="dashboard_tab_content_item_company_name_top_text">
                                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20.1235 15.4248C19.8114 15.2534 19.4196 15.3674 19.2483 15.6793C19.0769 15.9913 19.1908 16.3832 19.5028 16.5545C20.2706 16.9763 20.7109 17.4439 20.7109 17.8374C20.7109 18.3187 20.029 19.0646 18.1151 19.7149C16.2242 20.3574 13.6974 20.7111 11 20.7111C8.30264 20.7111 5.77577 20.3574 3.88493 19.7149C1.97098 19.0647 1.28906 18.3187 1.28906 17.8374C1.28906 17.4439 1.72941 16.9763 2.49717 16.5545C2.80917 16.3831 2.92312 15.9913 2.75172 15.6793C2.58032 15.3673 2.18857 15.2533 1.87649 15.4247C1.02046 15.895 0 16.6953 0 17.8374C0 18.7129 0.602078 19.961 3.47024 20.9355C5.49115 21.622 8.16527 22.0002 11 22.0002C13.8347 22.0002 16.5089 21.622 18.5298 20.9355C21.3979 19.961 22 18.7129 22 17.8374C22 16.6953 20.9795 15.895 20.1235 15.4248Z" fill="white"/><path d="M6.13501 18.7827C7.44341 19.1523 9.17157 19.3559 11.0011 19.3559C12.8307 19.3559 14.5589 19.1524 15.8673 18.7827C17.468 18.3306 18.2796 17.676 18.2796 16.8373C18.2796 15.9986 17.468 15.3441 15.8673 14.8919C15.5119 14.7916 15.1254 14.7035 14.7146 14.6284C14.4914 15.0139 14.2576 15.4108 14.0132 15.819C14.468 15.8872 14.895 15.9709 15.2827 16.0697C16.4607 16.3697 16.8911 16.7075 16.9796 16.8374C16.8911 16.9672 16.4608 17.305 15.2828 17.605C14.1678 17.889 12.7332 18.051 11.2223 18.0655C11.1491 18.0709 11.0754 18.074 11.0011 18.074C10.9268 18.074 10.8531 18.0709 10.7799 18.0655C9.26902 18.051 7.83446 17.8891 6.71947 17.605C5.54144 17.305 5.11111 16.9672 5.02263 16.8374C5.11111 16.7075 5.54148 16.3697 6.71951 16.0697C7.10726 15.9709 7.53424 15.8872 7.98907 15.819C7.74462 15.4108 7.51082 15.0139 7.28769 14.6284C6.87682 14.7036 6.49032 14.7916 6.13501 14.8919C4.53429 15.3441 3.72266 15.9986 3.72266 16.8373C3.72266 17.676 4.53429 18.3305 6.13501 18.7827Z" fill="white"/><path d="M10.9994 16.7851C11.5731 16.7851 12.0943 16.4927 12.3936 16.003C14.4909 12.5716 16.9909 8.04934 16.9909 5.99152C16.9909 2.68778 14.3032 0 10.9994 0C7.69559 0 5.00781 2.68778 5.00781 5.99152C5.00781 8.04934 7.50786 12.5716 9.60513 16.003C9.90445 16.4927 10.4257 16.7851 10.9994 16.7851ZM8.59111 5.58014C8.59111 4.25227 9.67147 3.17195 10.9994 3.17195C12.3273 3.17195 13.4076 4.25227 13.4076 5.58014C13.4076 6.90804 12.3273 7.98836 10.9994 7.98836C9.67147 7.98836 8.59111 6.90809 8.59111 5.58014Z" fill="white"/></svg>
                                            <span>' . $translation['text176'] . '</span>
                                        </div>
                                    </div>
                                    <div class="dashboard_tab_content_item_company_name_top_right">
                                        <div class="dashboard_tab_content_item_company_name_top_right_title">' . $translation['text49'] . '</div>
                                        <div class="dashboard_tab_content_item_company_name_top_right_text">' . $translation['text177'] . '</div>
                                    </div>
                                </div>
                                <div class="dashboard_tab_content_item_company_name_inner dashboard_tab_content_item_geo_coordinates_inner">
                                    <img src="/images/dashboard_tab_content_item_company_name_inner_bg.png" class="dashboard_tab_content_item_company_name_inner_bg" alt="">
                                    <!-- <img src="/images/gifs/dashboard_geo_coordinates.gif" class="dashboard_tab_content_item_geo_coordinates_inner_bg" alt=""> -->
                                    <div class="dashboard_tab_content_item_geo_coordinates_inner_bg"></div>

                                    <div class="dashboard_tab_content_item_geo_coordinates_input_wrapper_row_titles">
                                        <div class="dashboard_tab_content_item_geo_coordinates_title_text_left">' . $translation['text178'] . '</div>
                                        <div class="dashboard_tab_content_item_geo_coordinates_title_text_right"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="10" cy="10" r="9" fill="#102348" fill-opacity="0.5" stroke="#00F0FF"/><circle cx="10" cy="10" r="5" fill="#00F0FF" stroke="#00F0FF"/></svg>' . $translation['text179'] . '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="10" cy="10" r="9" fill="#102348" fill-opacity="0.5" stroke="#00F0FF"/></svg>' . $translation['text180'] . '</div>
                                    </div>
                                    <div class="dashboard_tab_content_item_geo_coordinates_input_wrapper_row dashboard_tab_content_item_geo_coordinates_input_wrapper_row_latitude">
                                        <div class="dashboard_tab_content_item_geo_coordinates_input_wrapper">
                                            <div class="dashboard_tab_content_item_company_name_input_border_left"></div>
                                            <input type="text" placeholder="" autocomplete="off" class="dashboard_tab_content_item_geo_coordinates_latitude1_input">
                                            <div class="dashboard_tab_content_item_geo_coordinates_input_svg">
                                                <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="5.5" cy="5.5" r="4.5" stroke="white" stroke-width="2"/></svg>
                                            </div>
                                        </div>
                                        <div class="dashboard_tab_content_item_geo_coordinates_input_wrapper">
                                            <input type="text" placeholder="" autocomplete="off" class="dashboard_tab_content_item_geo_coordinates_latitude2_input">
                                            <div class="dashboard_tab_content_item_geo_coordinates_input_svg">
                                                <svg width="2" height="9" viewBox="0 0 2 9" fill="none" xmlns="http://www.w3.org/2000/svg"><line x1="1" y1="4.37112e-08" x2="1" y2="9" stroke="white" stroke-width="2"/></svg>
                                            </div>
                                        </div>
                                        <div class="dashboard_tab_content_item_geo_coordinates_input_wrapper">
                                            <div class="dashboard_tab_content_item_company_name_input_border_right"></div>
                                            <input type="text" placeholder="" autocomplete="off" class="dashboard_tab_content_item_geo_coordinates_latitude3_input">
                                            <div class="dashboard_tab_content_item_geo_coordinates_input_svg">
                                                <svg width="7" height="9" viewBox="0 0 7 9" fill="none" xmlns="http://www.w3.org/2000/svg"><line x1="6" y1="4.37112e-08" x2="6" y2="9" stroke="white" stroke-width="2"/><line x1="1" y1="4.37112e-08" x2="1" y2="9" stroke="white" stroke-width="2"/></svg>
                                            </div>
                                        </div>
                                        <div class="dashboard_tab_content_item_geo_coordinates_input_error">' . $translation['text190'] . '</div>
                                    </div>

                                    <div class="dashboard_tab_content_item_geo_coordinates_input_wrapper_row_titles">
                                        <div class="dashboard_tab_content_item_geo_coordinates_title_text_left">' . $translation['text181'] . '</div>
                                        <div class="dashboard_tab_content_item_geo_coordinates_title_text_right"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="10" cy="10" r="9" fill="#102348" fill-opacity="0.5" stroke="#00F0FF"/><circle cx="10" cy="10" r="5" fill="#00F0FF" stroke="#00F0FF"/></svg>' . $translation['text182'] . '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="10" cy="10" r="9" fill="#102348" fill-opacity="0.5" stroke="#00F0FF"/></svg>' . $translation['text183'] . '</div>
                                    </div>
                                    <div class="dashboard_tab_content_item_geo_coordinates_input_wrapper_row dashboard_tab_content_item_geo_coordinates_input_wrapper_row_longitude">
                                        <div class="dashboard_tab_content_item_geo_coordinates_input_wrapper">
                                            <div class="dashboard_tab_content_item_company_name_input_border_left"></div>
                                            <input type="text" placeholder="" autocomplete="off" class="dashboard_tab_content_item_geo_coordinates_longitude1_input">
                                            <div class="dashboard_tab_content_item_geo_coordinates_input_svg">
                                                <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="5.5" cy="5.5" r="4.5" stroke="white" stroke-width="2"/></svg>
                                            </div>
                                        </div>
                                        <div class="dashboard_tab_content_item_geo_coordinates_input_wrapper">
                                            <input type="text" placeholder="" autocomplete="off" class="dashboard_tab_content_item_geo_coordinates_longitude2_input">
                                            <div class="dashboard_tab_content_item_geo_coordinates_input_svg">
                                                <svg width="2" height="9" viewBox="0 0 2 9" fill="none" xmlns="http://www.w3.org/2000/svg"><line x1="1" y1="4.37112e-08" x2="1" y2="9" stroke="white" stroke-width="2"/></svg>
                                            </div>
                                        </div>
                                        <div class="dashboard_tab_content_item_geo_coordinates_input_wrapper">
                                            <div class="dashboard_tab_content_item_company_name_input_border_right"></div>
                                            <input type="text" placeholder="" autocomplete="off" class="dashboard_tab_content_item_geo_coordinates_longitude3_input">
                                            <div class="dashboard_tab_content_item_geo_coordinates_input_svg">
                                                <svg width="7" height="9" viewBox="0 0 7 9" fill="none" xmlns="http://www.w3.org/2000/svg"><line x1="6" y1="4.37112e-08" x2="6" y2="9" stroke="white" stroke-width="2"/><line x1="1" y1="4.37112e-08" x2="1" y2="9" stroke="white" stroke-width="2"/></svg>
                                            </div>
                                        </div>
                                        <div class="dashboard_tab_content_item_geo_coordinates_input_error">' . $translation['text190'] . '</div>
                                    </div>

                                    <div class="btn_wrapper btn_wrapper_blue dashboard_tab_content_item_geo_coordinates_btn">
                                        <div class="btn btn_blue">
                                            <span>' . $translation['text184'] . '</span>
                                        </div>
                                        <div class="btn_border_top"></div>
                                        <div class="btn_border_bottom"></div>
                                        <div class="btn_border_left"></div>
                                        <div class="btn_border_left_arcle"></div>
                                        <div class="btn_border_right"></div>
                                        <div class="btn_border_right_arcle"></div>
                                        <div class="btn_bg_top_line"></div>
                                        <div class="btn_bg_bottom_line"></div>
                                        <div class="btn_bg_triangle_left"></div>
                                        <div class="btn_bg_triangle_right"></div>
                                        <div class="btn_circles_top">
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                        </div>
                                        <div class="btn_circles_bottom">
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>';

        return $return;
    }

    // dashboard - african partner
    private function uploadDashboardAfricanPartner($lang_id, $team_id)
    {
        $translation = $this->getWordsByPage('game', $lang_id);

        $team_info = $this->teamInfo($team_id);

        $return = [];

        $return['titles'] = '<div class="dashboard_tab_title dashboard_tab_title_active" data-tab="tab1">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper">
                                        <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.75 0H21V2L19.25 4H0V2L1.75 0Z" fill="#00F0FF"/><path d="M1.75 6H7.25V19L5.5 21H0V8L1.75 6Z" fill="#00F0FF"/><path d="M11.75 10H21V13L19.25 15H10V12L11.75 10Z" fill="#00F0FF"/><path d="M10.75 6H21V8L20.25 9H10V7L10.75 6Z" fill="#00F0FF"/><path d="M11.75 16H21V19L19.25 21H10V18L11.75 16Z" fill="#00F0FF"/></svg>
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text11'] . '</div>
                                </div>
                            </div>';

        $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_african_partner dashboard_tab_content_item_active" data-tab="tab1">
                                <div class="dashboard_tab_content_item_company_name_top">
                                    <div class="dashboard_tab_content_item_company_name_top_left">
                                        <img src="/images/dashboard_company_name_top_left_bg.png" class="dashboard_company_name_top_left_bg" alt="">
                                        <div class="dashboard_tab_content_item_company_name_top_text">
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M17.9039 5.04333H13.5156V3.1192H18.4402C19.3004 3.1192 20 2.41959 20 1.55975C20 0.699615 19.3004 0 18.4402 0H1.55975C0.699615 0 0 0.699615 0 1.55975C0 2.41959 0.699615 3.1192 1.55975 3.1192H6.48438V5.04333H2.0961C0.940247 5.04333 0 5.98358 0 7.13943V17.9039C0 19.0598 0.940247 20 2.0961 20H17.9039C19.0598 20 20 19.0598 20 17.9039V7.13943C20 5.98358 19.0598 5.04333 17.9039 5.04333ZM7.65625 3.1192H12.3438V5.04333H7.65625V3.1192ZM3.17221 8.71339C3.17221 8.3516 3.46671 8.05711 3.8288 8.05711H11.5C11.8621 8.05711 12.1566 8.3516 12.1566 8.71339V12.3433C12.1566 12.7055 11.8621 13 11.5 13H3.8288C3.46671 13 3.17221 12.7055 3.17221 12.3433V8.71339ZM13.9062 17.6062H5.99915C5.67581 17.6062 5.41321 17.3442 5.41321 17.0203C5.41321 16.6969 5.67581 16.4343 5.99915 16.4343H13.9062C14.2297 16.4343 14.4922 16.6969 14.4922 17.0203C14.4922 17.3442 14.2297 17.6062 13.9062 17.6062ZM16.3446 15.6531H3.6554C3.33206 15.6531 3.06946 15.3911 3.06946 15.0671C3.06946 14.7438 3.33206 14.4812 3.6554 14.4812H16.3446C16.6679 14.4812 16.9305 14.7438 16.9305 15.0671C16.9305 15.3911 16.6679 15.6531 16.3446 15.6531Z" fill="white"/></svg>
                                            <span>' . $translation['text197'] . '</span>
                                        </div>
                                    </div>
                                    <div class="dashboard_tab_content_item_company_name_top_right">
                                        <div class="dashboard_tab_content_item_company_name_top_right_title">' . $translation['text49'] . '</div>
                                        <div class="dashboard_tab_content_item_company_name_top_right_text">' . $translation['text198'] . '</div>
                                    </div>
                                </div>
                                <div class="dashboard_tab_content_item_company_name_inner dashboard_tab_content_item_african_partner_inner">
                                    <img src="/images/dashboard_tab_content_item_company_name_inner_bg.png" class="dashboard_tab_content_item_company_name_inner_bg" alt="">
                                    <img src="/images/dashboard_africa_partners_bg.png" class="dashboard_africa_partners_bg" alt="">

                                    <div class="dashboard_african_partner_fields_top">
                                        <div class="dashboard_african_partner_input_wrapper dashboard_african_partner_input_wrapper_company_name">
                                            <div class="dashboard_african_partner_input_border_left"></div>
                                            <input type="text" placeholder="' . $translation['text199'] . '" autocomplete="off" class="dashboard_african_partner_company_name">
                                            <div class="dashboard_african_partner_company_name_error error_text_database_car_register">' . $translation['text86'] . '</div>
                                        </div>
                                        <div class="dashboard_african_partner_input_wrapper dashboard_african_partner_input_wrapper_country">
                                            <div class="dashboard_african_partner_input_border_right"></div>';

        $sql = "
            SELECT c.code, c.pos, cd.name, c.id
            FROM countries c
            JOIN countries_description cd ON c.id = cd.country_id
            WHERE cd.lang_id = {?}
            ORDER BY cd.name
        ";
        $countries = $this->db->select($sql, [$lang_id]);
        if ($countries) {
            $return['content'] .= '<select class="dashboard_african_partner_country"><option disabled="disabled"' . (empty($team_info['african_partner_country_id']) ? ' selected="selected"' : '') . '>' . $translation['text200'] . '</option>';
            foreach ($countries as $country) {
                $return['content'] .= '<option value="' . htmlspecialchars($country['name'], ENT_QUOTES) . '" data-pos="' . $country['pos'] . '"' . ($team_info['african_partner_country_id'] == $country['id'] ? ' selected="selected"' : '') . '>' . $country['name'] . '</option>';
            }
            $return['content'] .= '</select>
                                    <script>
                                        $(function() {
                                            // select country
                                            var scrollbarPositionPixel = 0;
                                            var isScrollOpen = false;

                                            $(".dashboard_african_partner_country").selectric({
                                                optionsItemBuilder: function(itemData, element, index) {
                                                    return (!itemData.disabled) ? \'<span class="select_country_flag" style="display:inline-block;width:16px;height:11px;background:url(/images/flags.png) no-repeat;background-position:\' + itemData.element[0].attributes[\'data-pos\'].value + \';margin: 0 15px 0 5px;"></span><span class="select_country_name" style="display:inline-block;max-width: 87%;">\' + itemData.text + \'</span>\' : itemData.text;
                                                },
                                                maxHeight: 236,
                                                preventWindowScroll: false,
                                                onInit: function() {
                                                    // стилизация полосы прокрутки
                                                    $(".selectric-dashboard_african_partner_country .selectric-scroll").mCustomScrollbar({
                                                        scrollInertia: 700,
                                                        theme: "minimal-dark",
                                                        scrollbarPosition: "inside",
                                                        alwaysShowScrollbar: 2,
                                                        autoHideScrollbar: false,
                                                        mouseWheel:{ deltaFactor: 200 },
                                                        callbacks:{
                                                            onScroll: function(){
                                                            },
                                                            whileScrolling:function() {
                                                                scrollbarPositionPixel = this.mcs.top;
                                                                if (isScrollOpen) {
                                                                    $(".dashboard_african_partner_country").selectric("open");
                                                                }
                                                            }
                                                        }
                                                    });
                                                },
                                                onOpen: function() {
                                                    if (!isScrollOpen) {
                                                        $(".selectric-dashboard_african_partner_country .selectric-scroll").mCustomScrollbar("scrollTo", Math.abs(scrollbarPositionPixel));
                                                        isScrollOpen = true;
                                                    }
                                                }
                                            })
                                            .on("change", function() {
                                                // сохраняем выбор
                                                var formData = new FormData();
                                                formData.append("op", "saveTeamTextField");
                                                formData.append("field", "african_partner_country_id");
                                                formData.append("val", $(this).val());

                                                $.ajax({
                                                    url: "/ajax/ajax.php",
                                                    type: "POST",
                                                    dataType: "json",
                                                    cache: false,
                                                    contentType: false,
                                                    processData: false,
                                                    data: formData,
                                                    success: function(json) {
                                                        if (json.country_lang) {
                                                            // socket
                                                            var message = {
                                                                "op": "dashboardAfricanPartnerUpdateCountry",
                                                                "parameters": {
                                                                    "country_lang": json.country_lang,
                                                                    "user_id": $("#section_game").length ? $("#section_game").attr("data-user-id") : 0,
                                                                    "team_id": $("#section_game").length ? $("#section_game").attr("data-team-id") : 0
                                                                }
                                                            };
                                                            sendMessageSocket(JSON.stringify(message));
                                                        }
                                                    },
                                                    error: function(xhr, ajaxOptions, thrownError) {    
                                                        console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                                    }
                                                });

                                                isScrollOpen = false;
                                            });

                                            $(".dashboard_tabs[data-dashboard=\'databases\']").on("click", ".dashboard_african_partner_input_wrapper_country .mCSB_scrollTools_vertical", function(e){
                                                if (isScrollOpen) {
                                                    $(".dashboard_african_partner_country").selectric("open");
                                                }
                                            });

                                            // datepicker
                                            $(".dashboard_african_partner_date").datepicker({
                                                dateFormat: "dd.mm.yy",
                                                dayNamesShort: ["' . $translation['text67'] . '", "' . $translation['text68'] . '", "' . $translation['text69'] . '", "' . $translation['text70'] . '", "' . $translation['text71'] . '", "' . $translation['text72'] . '", "' . $translation['text73'] . '"],
                                                dayNamesMin: ["' . $translation['text67'] . '", "' . $translation['text68'] . '", "' . $translation['text69'] . '", "' . $translation['text70'] . '", "' . $translation['text71'] . '", "' . $translation['text72'] . '", "' . $translation['text73'] . '"],
                                                monthNames: ["' . $translation['text74'] . '", "' . $translation['text75'] . '", "' . $translation['text76'] . '", "' . $translation['text77'] . '", "' . $translation['text78'] . '", "' . $translation['text79'] . '", "' . $translation['text80'] . '", "' . $translation['text81'] . '", "' . $translation['text82'] . '", "' . $translation['text83'] . '", "' . $translation['text84'] . '", "' . $translation['text85'] . '"],
                                                changeMonth: false,
                                                changeYear: false,
                                                //showAnim: "clip",
                                                showAnim: "",
                                                onSelect: function(dateText) {
                                                    // сохраняем выбор
                                                    var formData = new FormData();
                                                    formData.append("op", "saveTeamTextField");
                                                    formData.append("field", "african_partner_date");
                                                    formData.append("val", dateText);

                                                    $.ajax({
                                                        url: "/ajax/ajax.php",
                                                        type: "POST",
                                                        dataType: "json",
                                                        cache: false,
                                                        contentType: false,
                                                        processData: false,
                                                        data: formData,
                                                        success: function(json) {
                                                            // socket
                                                            var message = {
                                                                "op": "dashboardAfricanPartnerUpdateDate",
                                                                "parameters": {
                                                                    "date": dateText,
                                                                    "user_id": $("#section_game").length ? $("#section_game").attr("data-user-id") : 0,
                                                                    "team_id": $("#section_game").length ? $("#section_game").attr("data-team-id") : 0
                                                                }
                                                            };
                                                            sendMessageSocket(JSON.stringify(message));
                                                        },
                                                        error: function(xhr, ajaxOptions, thrownError) {    
                                                            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                                        }
                                                    });
                                                },
                                                beforeShow: function() {
                                                    if (!is_touch_device()) {
                                                        var pageSize = getPageSize();
                                                        var windowWidth = pageSize[2];
                                                        if (windowWidth < 1800) {
                                                            $("body").removeClass("body_desktop_scale").css("transform", "scale(1)");

                                                            setTimeout(function() {
                                                                var pageSize = getPageSize();
                                                                var windowWidth = pageSize[0];

                                                                var koef = parseFloat((windowWidth / 1920).toFixed(2)) + 0.01;

                                                                $("body").addClass("body_desktop_scale").css("transform", "scale(" + koef + ")");
                                                                //$("body").css("transform", "scale(" + koef + ")");

                                                                var curDatepickerPosition = parseFloat($(".ui-datepicker").css("left"));
                                                                var differentDatepickerPosition = (1920 - windowWidth) / 2;
                                                                $(".ui-datepicker").css("left", (curDatepickerPosition + differentDatepickerPosition + 7) + "px");
                                                            }, 1);
                                                        }
                                                    }
                                                }
                                            });
                                        });
                                    </script>';
        }

        $return['content'] .= '             <div class="dashboard_african_partner_country_error error_text_database_car_register">' . $translation['text86'] . '</div>
                                        </div>
                                    </div>
                                    <div class="dashboard_african_partner_fields_bottom">
                                        <div class="dashboard_african_partner_input_wrapper dashboard_african_partner_input_wrapper_date">
                                            <div class="dashboard_african_partner_input_border_left"></div>
                                            <div class="dashboard_african_partner_input_border_right"></div>
                                            <input type="text" placeholder="' . $translation['text201'] . '" autocomplete="off" class="dashboard_african_partner_date" value="' . ((!empty($team_info['african_partner_date']) && $team_info['african_partner_date'] != '0000-00-00' && !is_null($team_info['african_partner_date'])) ? $this->fromEngDatetimeToRus($team_info['african_partner_date']) : '') . '">
                                            <div class="dashboard_african_partner_date_error error_text_database_car_register">' . $translation['text86'] . '</div>
                                        </div>
                                    </div>
                                    <div class="btn_wrapper btn_wrapper_blue dashboard_african_partner_search">
                                        <div class="btn btn_blue">
                                            <span>' . $translation['text184'] . '</span>
                                        </div>
                                        <div class="btn_border_top"></div>
                                        <div class="btn_border_bottom"></div>
                                        <div class="btn_border_left"></div>
                                        <div class="btn_border_left_arcle"></div>
                                        <div class="btn_border_right"></div>
                                        <div class="btn_border_right_arcle"></div>
                                        <div class="btn_bg_top_line"></div>
                                        <div class="btn_bg_bottom_line"></div>
                                        <div class="btn_bg_triangle_left"></div>
                                        <div class="btn_bg_triangle_right"></div>
                                        <div class="btn_circles_top">
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                        </div>
                                        <div class="btn_circles_bottom">
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>';

        return $return;
    }

    // dashboard - Metting Place
    private function uploadDashboardMettingPlace($lang_id, $team_id)
    {
        $translation = $this->getWordsByPage('game', $lang_id);

        $team_info = $this->teamInfo($team_id);

        $return = [];

        $return['titles'] = '<div class="dashboard_tab_title dashboard_tab_title_active" data-tab="tab1">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper">
                                        <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.75 0H21V2L19.25 4H0V2L1.75 0Z" fill="#00F0FF"/><path d="M1.75 6H7.25V19L5.5 21H0V8L1.75 6Z" fill="#00F0FF"/><path d="M11.75 10H21V13L19.25 15H10V12L11.75 10Z" fill="#00F0FF"/><path d="M10.75 6H21V8L20.25 9H10V7L10.75 6Z" fill="#00F0FF"/><path d="M11.75 16H21V19L19.25 21H10V18L11.75 16Z" fill="#00F0FF"/></svg>
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text11'] . '</div>
                                </div>
                            </div>';

        $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_metting_place dashboard_tab_content_item_active" data-tab="tab1">
                                
                                <div class="dashboard_tab_content_item_company_name_inner dashboard_tab_content_item_metting_place_inner">
                                    <img src="/images/dashboard_tab_content_item_company_name_inner_bg.png" class="dashboard_tab_content_item_company_name_inner_bg" alt="">

                                    <div class="dashboard_metting_place_fields_top">
                                        <div class="dashboard_metting_place_input_wrapper dashboard_metting_place_input_wrapper_street_name">
                                            <div class="dashboard_metting_place_input_border_left"></div>
                                            <input type="text" placeholder="' . $translation['text207'] . '" autocomplete="off" class="dashboard_metting_place_street_name">
                                            <div class="dashboard_metting_place_street_name_error error_text_database_car_register">' . $translation['text86'] . '</div>
                                            <div class="dashboard_metting_place_input_border_right"></div>
                                        </div>
                                        <div class="dashboard_metting_place_input_wrapper dashboard_metting_place_input_wrapper_house_number">
                                            <div class="dashboard_metting_place_input_border_left"></div>
                                            <input type="text" placeholder="#" autocomplete="off" class="dashboard_metting_place_house_number">
                                            <div class="dashboard_metting_place_house_number_error error_text_database_car_register">' . $translation['text86'] . '</div>
                                            <div class="dashboard_metting_place_input_border_right"></div>
                                        </div>
                                    </div>
                                    <div class="dashboard_metting_place_fields_bottom">
                                        <div class="dashboard_metting_place_input_wrapper dashboard_metting_place_input_wrapper_city">
                                            <div class="dashboard_metting_place_input_border_left"></div>
                                            <input type="text" placeholder="' . $translation['text208'] . '" autocomplete="off" class="dashboard_metting_place_city">
                                            <div class="dashboard_metting_place_city_error error_text_database_car_register">' . $translation['text86'] . '</div>
                                            <div class="dashboard_metting_place_input_border_right"></div>
                                        </div>
                                        <div class="dashboard_metting_place_input_wrapper dashboard_metting_place_input_wrapper_country">
                                            <div class="dashboard_metting_place_input_border_left"></div>
                                            <div class="dashboard_metting_place_input_border_right"></div>';

        $sql = "
            SELECT c.code, c.pos, cd.name, c.id
            FROM countries c
            JOIN countries_description cd ON c.id = cd.country_id
            WHERE cd.lang_id = {?}
            ORDER BY cd.name
        ";
        $countries = $this->db->select($sql, [$lang_id]);
        if ($countries) {
            $return['content'] .= '<select class="dashboard_metting_place_country"><option disabled="disabled"' . (empty($team_info['metting_place_country_id']) ? ' selected="selected"' : '') . '>' . $translation['text64'] . '</option>';
            foreach ($countries as $country) {
                $return['content'] .= '<option value="' . htmlspecialchars($country['name'], ENT_QUOTES) . '" data-pos="' . $country['pos'] . '"' . ($team_info['metting_place_country_id'] == $country['id'] ? ' selected="selected"' : '') . '>' . $country['name'] . '</option>';
            }
            $return['content'] .= '</select>
                                    <script>
                                        $(function() {
                                            // select country
                                            var scrollbarPositionPixel = 0;
                                            var isScrollOpen = false;

                                            $(".dashboard_metting_place_country").selectric({
                                                optionsItemBuilder: function(itemData, element, index) {
                                                    return (!itemData.disabled) ? \'<span class="select_country_flag" style="display:inline-block;width:16px;height:11px;background:url(/images/flags.png) no-repeat;background-position:\' + itemData.element[0].attributes[\'data-pos\'].value + \';margin: 0 15px 0 5px;"></span><span class="select_country_name" style="display:inline-block;max-width: 87%;">\' + itemData.text + \'</span>\' : itemData.text;
                                                },
                                                maxHeight: 236,
                                                preventWindowScroll: false,
                                                onInit: function() {
                                                    // стилизация полосы прокрутки
                                                    $(".selectric-dashboard_metting_place_country .selectric-scroll").mCustomScrollbar({
                                                        scrollInertia: 700,
                                                        theme: "minimal-dark",
                                                        scrollbarPosition: "inside",
                                                        alwaysShowScrollbar: 2,
                                                        autoHideScrollbar: false,
                                                        mouseWheel:{ deltaFactor: 200 },
                                                        callbacks:{
                                                            onScroll: function(){
                                                            },
                                                            whileScrolling:function() {
                                                                scrollbarPositionPixel = this.mcs.top;
                                                                if (isScrollOpen) {
                                                                    $(".dashboard_metting_place_country").selectric("open");
                                                                }
                                                            }
                                                        }
                                                    });
                                                },
                                                onOpen: function() {
                                                    if (!isScrollOpen) {
                                                        $(".selectric-dashboard_metting_place_country .selectric-scroll").mCustomScrollbar("scrollTo", Math.abs(scrollbarPositionPixel));
                                                        isScrollOpen = true;
                                                    }
                                                }
                                            })
                                            .on("change", function() {
                                                // сохраняем выбор
                                                var formData = new FormData();
                                                formData.append("op", "saveTeamTextField");
                                                formData.append("field", "metting_place_country_id");
                                                formData.append("val", $(this).val());

                                                $.ajax({
                                                    url: "/ajax/ajax.php",
                                                    type: "POST",
                                                    dataType: "json",
                                                    cache: false,
                                                    contentType: false,
                                                    processData: false,
                                                    data: formData,
                                                    success: function(json) {
                                                        if (json.country_lang) {
                                                            // socket
                                                            var message = {
                                                                "op": "dashboardMettingPlaceUpdateCountry",
                                                                "parameters": {
                                                                    "country_lang": json.country_lang,
                                                                    "user_id": $("#section_game").length ? $("#section_game").attr("data-user-id") : 0,
                                                                    "team_id": $("#section_game").length ? $("#section_game").attr("data-team-id") : 0
                                                                }
                                                            };
                                                            sendMessageSocket(JSON.stringify(message));
                                                        }
                                                    },
                                                    error: function(xhr, ajaxOptions, thrownError) {    
                                                        console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                                    }
                                                });

                                                isScrollOpen = false;
                                            });

                                            $(".dashboard_tabs[data-dashboard=\'databases\']").on("click", ".dashboard_metting_place_input_wrapper_country .mCSB_scrollTools_vertical", function(e){
                                                if (isScrollOpen) {
                                                    $(".dashboard_metting_place_country").selectric("open");
                                                }
                                            });
                                        });
                                    </script>';
        }

        $return['content'] .= '             <div class="dashboard_metting_place_country_error error_text_database_car_register">' . $translation['text86'] . '</div>
                                        </div>
                                    </div>
                                    <div class="btn_wrapper btn_wrapper_blue dashboard_metting_place_search">
                                        <div class="btn btn_blue">
                                            <span>' . $translation['text184'] . '</span>
                                        </div>
                                        <div class="btn_border_top"></div>
                                        <div class="btn_border_bottom"></div>
                                        <div class="btn_border_left"></div>
                                        <div class="btn_border_left_arcle"></div>
                                        <div class="btn_border_right"></div>
                                        <div class="btn_border_right_arcle"></div>
                                        <div class="btn_bg_top_line"></div>
                                        <div class="btn_bg_bottom_line"></div>
                                        <div class="btn_bg_triangle_left"></div>
                                        <div class="btn_bg_triangle_right"></div>
                                        <div class="btn_circles_top">
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                        </div>
                                        <div class="btn_circles_bottom">
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>';

        return $return;
    }

    // dashboard - Room Name
    private function uploadDashboardRoomName($lang_id, $team_id)
    {
        $translation = $this->getWordsByPage('game', $lang_id);

        $team_info = $this->teamInfo($team_id);

        $return = [];

        $return['titles'] = '<div class="dashboard_tab_title dashboard_tab_title_active" data-tab="tab1">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper">
                                        <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.75 0H21V2L19.25 4H0V2L1.75 0Z" fill="#00F0FF"/><path d="M1.75 6H7.25V19L5.5 21H0V8L1.75 6Z" fill="#00F0FF"/><path d="M11.75 10H21V13L19.25 15H10V12L11.75 10Z" fill="#00F0FF"/><path d="M10.75 6H21V8L20.25 9H10V7L10.75 6Z" fill="#00F0FF"/><path d="M11.75 16H21V19L19.25 21H10V18L11.75 16Z" fill="#00F0FF"/></svg>
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text11'] . '</div>
                                </div>
                            </div>';

        $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_room_name dashboard_tab_content_item_active" data-tab="tab1">
                                <div class="dashboard_tab_content_item_company_name_top">
                                    <div class="dashboard_tab_content_item_company_name_top_left">
                                        <img src="/images/dashboard_company_name_top_left_bg.png" class="dashboard_company_name_top_left_bg" alt="">
                                        <div class="dashboard_tab_content_item_company_name_top_text">
                                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_886_1055)"><path d="M7.13281 16.8008C8.20071 16.8008 9.06641 15.9351 9.06641 14.8672C9.06641 13.7993 8.20071 12.9336 7.13281 12.9336C6.06492 12.9336 5.19922 13.7993 5.19922 14.8672C5.19922 15.9351 6.06492 16.8008 7.13281 16.8008Z" fill="white"/><path d="M20.2303 9.06641L12.8506 3.79521C12.8992 3.62583 12.9336 3.45052 12.9336 3.26562C12.9336 2.1994 12.0662 1.33203 11 1.33203C9.93377 1.33203 9.06641 2.1994 9.06641 3.26562C9.06641 3.45052 9.10078 3.62583 9.14942 3.79521L1.76971 9.06641H0V20.668H22V9.06641H20.2303ZM11 2.62109C11.355 2.62109 11.6445 2.91002 11.6445 3.26562C11.6445 3.62123 11.355 3.91016 11 3.91016C10.645 3.91016 10.3555 3.62123 10.3555 3.26562C10.3555 2.91002 10.645 2.62109 11 2.62109ZM9.89433 4.84761C10.2082 5.06765 10.5884 5.19922 11 5.19922C11.4116 5.19922 11.7918 5.06765 12.1057 4.84761L18.0119 9.06641H3.98806L9.89433 4.84761Z" fill="white"/><path d="M2.6545 14.6086V17.4296H3.4595V14.8046L3.8445 14.3636H4.7335L4.9505 14.5806V17.4296H5.7555V14.2936L5.0835 13.6286H3.6275L3.2215 14.0976L3.0885 13.5586L2.4375 13.7196L2.6545 14.6086Z" fill="#041627"/><path d="M7.40903 13.6286L6.51303 13.9086L6.73003 14.6086L7.51403 14.3636H8.53603L8.75303 14.5806V15.1616H7.12903L6.45703 15.8266V16.7646L7.12903 17.4296H8.58503L8.99103 16.9606L9.12403 17.4996L9.77503 17.3386L9.55803 16.4496V14.2936L8.88603 13.6286H7.40903ZM8.75303 16.2536L8.36803 16.6946H7.47903L7.26203 16.4776V16.1136L7.47903 15.8966H8.75303V16.2536Z" fill="#041627"/><path d="M12.7602 13.6286H11.5982L11.1922 14.0976L11.0592 13.5586L10.4082 13.7196L10.6252 14.6086V17.4296H11.4302V14.8046L11.8152 14.3636H12.4592L12.7182 14.6436V17.4296H13.5232V14.6436L13.7822 14.3636H14.5942L14.8112 14.5806V17.4296H15.6162V14.2936L14.9442 13.6286H13.4882L13.1242 14.0276L12.7602 13.6286Z" fill="#041627"/><path d="M18.4985 16.6946H17.4065L17.1895 16.4776V15.9596H18.8765L19.4785 15.3646V14.2936L18.8065 13.6286H17.0565L16.3845 14.2936V16.7646L17.0565 17.4296H18.6035L19.4995 17.1496L19.2825 16.4496L18.4985 16.6946ZM17.4065 14.3636H18.4565L18.6735 14.5806V15.0426L18.4915 15.2246H17.1895V14.5806L17.4065 14.3636Z" fill="#041627"/></g><defs><clipPath id="clip0_886_1055"><rect width="22" height="22" fill="white"/></clipPath></defs></svg>
                                            <span>' . $translation['text235'] . '</span>
                                        </div>
                                    </div>
                                    <div class="dashboard_tab_content_item_company_name_top_right">
                                        <div class="dashboard_tab_content_item_company_name_top_right_title">' . $translation['text49'] . '</div>
                                        <div class="dashboard_tab_content_item_company_name_top_right_text">' . $translation['text236'] . '</div>
                                    </div>
                                </div>
                                <div class="dashboard_tab_content_item_company_name_inner dashboard_tab_content_item_room_name_inner">
                                    <img src="/images/dashboard_tab_content_item_company_name_inner_bg.png" class="dashboard_tab_content_item_company_name_inner_bg" alt="">

                                    <img src="/images/gifs/pc.gif" class="dashboard_tab_content_item_room_name_inner_pc" alt="">
                                    <img src="/images/gifs/server.gif" class="dashboard_tab_content_item_room_name_inner_server" alt="">
                                    <div class="dashboard_tab_content_item_room_name_inner_pc_to_server">
                                        <svg width="767" height="198" viewBox="0 0 767 198" fill="none" xmlns="http://www.w3.org/2000/svg"><path opacity="0.3" d="M767 196.5H38L1 159.5V0" stroke="#00F0FF" stroke-width="2" stroke-dasharray="10 10"/></svg>
                                    </div>
                                    <div class="dashboard_room_name_fields">
                                        <div class="dashboard_tab_content_item_room_name_inner_title">' . $translation['text237'] . '</div>
                                        <div class="dashboard_room_name_input_wrapper dashboard_room_name_input_wrapper_room_name">
                                            <div class="dashboard_room_name_input_border_left"></div>
                                            <input type="text" placeholder="' . $translation['text235'] . '" autocomplete="off" class="dashboard_room_name_room_name">
                                            <div class="dashboard_room_name_room_name_error error_text_database_car_register">' . $translation['text86'] . '</div>
                                            <div class="dashboard_room_name_input_border_right"></div>
                                            <div class="dashboard_room_name_input_border_left_line">
                                                <svg width="320" height="41" viewBox="0 0 320 41" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 1H199.697L238.433 40H319.5" stroke="#FF004E"/></svg>
                                            </div>
                                            <div class="dashboard_room_name_input_border_right_line">
                                                <svg width="327" height="41" viewBox="0 0 327 41" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 1H68.6967L107.433 40H326.5" stroke="#FF004E"/></svg>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="btn_wrapper btn_wrapper_blue dashboard_room_name_search">
                                        <div class="btn btn_blue">
                                            <span>' . $translation['text184'] . '</span>
                                        </div>
                                        <div class="btn_border_top"></div>
                                        <div class="btn_border_bottom"></div>
                                        <div class="btn_border_left"></div>
                                        <div class="btn_border_left_arcle"></div>
                                        <div class="btn_border_right"></div>
                                        <div class="btn_border_right_arcle"></div>
                                        <div class="btn_bg_top_line"></div>
                                        <div class="btn_bg_bottom_line"></div>
                                        <div class="btn_bg_triangle_left"></div>
                                        <div class="btn_bg_triangle_right"></div>
                                        <div class="btn_circles_top">
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                        </div>
                                        <div class="btn_circles_bottom">
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>';

        return $return;
    }

    // dashboard - Password
    private function uploadDashboardPassword($lang_id, $team_id)
    {
        $translation = $this->getWordsByPage('game', $lang_id);

        $team_info = $this->teamInfo($team_id);

        $return = [];

        $return['titles'] = '<div class="dashboard_tab_title dashboard_tab_title_active" data-tab="tab1">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper">
                                        <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.75 0H21V2L19.25 4H0V2L1.75 0Z" fill="#00F0FF"/><path d="M1.75 6H7.25V19L5.5 21H0V8L1.75 6Z" fill="#00F0FF"/><path d="M11.75 10H21V13L19.25 15H10V12L11.75 10Z" fill="#00F0FF"/><path d="M10.75 6H21V8L20.25 9H10V7L10.75 6Z" fill="#00F0FF"/><path d="M11.75 16H21V19L19.25 21H10V18L11.75 16Z" fill="#00F0FF"/></svg>
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text11'] . '</div>
                                </div>
                            </div>';

        $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_room_name dashboard_tab_content_item_active" data-tab="tab1">
                                <div class="dashboard_tab_content_item_company_name_top">
                                    <div class="dashboard_tab_content_item_company_name_top_left">
                                        <img src="/images/dashboard_company_name_top_left_bg.png" class="dashboard_company_name_top_left_bg" alt="">
                                        <div class="dashboard_tab_content_item_company_name_top_text">
                                            <svg width="22" height="12" viewBox="0 0 22 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14.1766 2.24984C13.9227 1.81075 13.3599 1.66042 12.9245 1.91434L11.918 2.4955V1.33317C11.918 0.82717 11.5082 0.416504 11.0013 0.416504C10.4944 0.416504 10.0846 0.82717 10.0846 1.33317V2.4955L9.07814 1.91434C8.64089 1.66042 8.07898 1.81075 7.82598 2.24984C7.57298 2.688 7.72331 3.249 8.16148 3.502L9.16798 4.08317L8.16148 4.66434C7.72331 4.91734 7.57298 5.47834 7.82598 5.9165C7.99648 6.21075 8.30356 6.37484 8.62073 6.37484C8.77656 6.37484 8.93423 6.33542 9.07814 6.252L10.0846 5.67084V6.83317C10.0846 7.33917 10.4944 7.74984 11.0013 7.74984C11.5082 7.74984 11.918 7.33917 11.918 6.83317V5.67084L12.9245 6.252C13.0684 6.33542 13.2261 6.37484 13.3819 6.37484C13.6991 6.37484 14.0071 6.21075 14.1766 5.9165C14.4296 5.47834 14.2793 4.91734 13.8411 4.66434L12.8346 4.08317L13.8411 3.502C14.2793 3.249 14.4296 2.688 14.1766 2.24984V2.24984Z" fill="white"/><path d="M6.47352 2.24984C6.2196 1.81075 5.65769 1.66042 5.22135 1.91434L4.21485 2.4955V1.33317C4.21485 0.82717 3.8051 0.416504 3.29819 0.416504C2.79127 0.416504 2.38152 0.82717 2.38152 1.33317V2.4955L1.37502 1.91434C0.936852 1.66042 0.376769 1.81075 0.122853 2.24984C-0.130147 2.688 0.0201859 3.249 0.458353 3.502L1.46485 4.08317L0.458353 4.66434C0.0201859 4.91734 -0.130147 5.47834 0.122853 5.9165C0.293353 6.21075 0.600436 6.37484 0.917603 6.37484C1.07344 6.37484 1.2311 6.33542 1.37502 6.252L2.38152 5.67084V6.83317C2.38152 7.33917 2.79127 7.74984 3.29819 7.74984C3.8051 7.74984 4.21485 7.33917 4.21485 6.83317V5.67084L5.22135 6.252C5.36527 6.33542 5.52294 6.37484 5.67877 6.37484C5.99594 6.37484 6.30394 6.21075 6.47352 5.9165C6.72652 5.47834 6.57619 4.91734 6.13802 4.66434L5.13152 4.08317L6.13802 3.502C6.57619 3.249 6.72652 2.688 6.47352 2.24984V2.24984Z" fill="white"/><path d="M16.3225 6.37484C16.4783 6.37484 16.636 6.33542 16.7799 6.252L17.7864 5.67084V6.83317C17.7864 7.33917 18.1962 7.74984 18.7031 7.74984C19.21 7.74984 19.6198 7.33917 19.6198 6.83317V5.67084L20.6263 6.252C20.7702 6.33542 20.9279 6.37484 21.0837 6.37484C21.4009 6.37484 21.7089 6.21075 21.8784 5.9165C22.1314 5.47834 21.9811 4.91734 21.5429 4.66434L20.5364 4.08317L21.5429 3.502C21.9811 3.249 22.1314 2.688 21.8784 2.24984C21.6245 1.81075 21.0626 1.66042 20.6263 1.91434L19.6198 2.4955V1.33317C19.6198 0.82717 19.21 0.416504 18.7031 0.416504C18.1962 0.416504 17.7864 0.82717 17.7864 1.33317V2.4955L16.7799 1.91434C16.3427 1.66042 15.7808 1.81075 15.5278 2.24984C15.2748 2.688 15.4251 3.249 15.8633 3.502L16.8698 4.08317L15.8633 4.66434C15.4242 4.91734 15.2738 5.47834 15.5278 5.9165C15.6983 6.21075 16.0063 6.37484 16.3225 6.37484Z" fill="white"/><path d="M5.5 9.58301H0.916667C0.40975 9.58301 0 9.99368 0 10.4997C0 11.0057 0.40975 11.4163 0.916667 11.4163H5.5C6.00692 11.4163 6.41667 11.0057 6.41667 10.4997C6.41667 9.99368 6.00692 9.58301 5.5 9.58301Z" fill="white"/><path d="M13.293 9.58301H8.70964C8.20272 9.58301 7.79297 9.99368 7.79297 10.4997C7.79297 11.0057 8.20272 11.4163 8.70964 11.4163H13.293C13.7999 11.4163 14.2096 11.0057 14.2096 10.4997C14.2096 9.99368 13.7999 9.58301 13.293 9.58301Z" fill="white"/><path d="M21.084 9.58301H16.5006C15.9937 9.58301 15.584 9.99368 15.584 10.4997C15.584 11.0057 15.9937 11.4163 16.5006 11.4163H21.084C21.5909 11.4163 22.0006 11.0057 22.0006 10.4997C22.0006 9.99368 21.5909 9.58301 21.084 9.58301Z" fill="white"/></svg>
                                            <span>' . $translation['text263'] . '</span>
                                        </div>
                                    </div>
                                    <div class="dashboard_tab_content_item_company_name_top_right">
                                        <div class="dashboard_tab_content_item_company_name_top_right_title">' . $translation['text306'] . '</div>
                                        <div class="dashboard_tab_content_item_company_name_top_right_text">' . $translation['text307'] . '</div>
                                    </div>
                                </div>
                                <div class="dashboard_tab_content_item_company_name_inner dashboard_tab_content_item_password_inner">
                                    <img src="/images/dashboard_tab_content_item_company_name_inner_bg.png" class="dashboard_tab_content_item_company_name_inner_bg" alt="">

                                    <div class="dashboard_password_fields">
                                        <div class="dashboard_password_input_wrapper dashboard_password_input_wrapper_password">
                                            <div class="dashboard_password_input_border_left"></div>
                                            <input type="text" placeholder="' . $translation['text263'] . '" autocomplete="off" class="dashboard_password_password">
                                            <div class="dashboard_password_password_error error_text_database_car_register">' . $translation['text86'] . '</div>
                                            <div class="dashboard_password_input_border_right"></div>
                                        </div>

                                        <img src="/images/dashboard_password_inner_bg_right.png" alt="" class="dashboard_password_inner_bg_right">
                                        <img src="/images/gifs/hdd3.gif" alt="" class="dashboard_password_inner_bg_gif3">
                                        <img src="/images/gifs/hdd4.gif" alt="" class="dashboard_password_inner_bg_gif4">
                                        <img src="/images/dashboard_password_inner_bg_left.png" alt="" class="dashboard_password_inner_bg_left">
                                    </div>

                                    <div class="dashboard_password_inner_bg">
                                        <img src="/images/dashboard_password_inner_bg.png" alt="" class="dashboard_password_inner_bg_main_img">
                                        <img src="/images/gifs/hdd1.gif" alt="" class="dashboard_password_inner_bg_gif1">
                                    </div>

                                    <div class="btn_wrapper btn_wrapper_blue dashboard_password_search">
                                        <div class="btn btn_blue">
                                            <span>' . $translation['text184'] . '</span>
                                        </div>
                                        <div class="btn_border_top"></div>
                                        <div class="btn_border_bottom"></div>
                                        <div class="btn_border_left"></div>
                                        <div class="btn_border_left_arcle"></div>
                                        <div class="btn_border_right"></div>
                                        <div class="btn_border_right_arcle"></div>
                                        <div class="btn_bg_top_line"></div>
                                        <div class="btn_bg_bottom_line"></div>
                                        <div class="btn_bg_triangle_left"></div>
                                        <div class="btn_bg_triangle_right"></div>
                                        <div class="btn_circles_top">
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                        </div>
                                        <div class="btn_circles_bottom">
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>';

        return $return;
    }






/* CALLS */
    // загрузить конкретный экран (с переключателем табов) для calls
    public function uploadTypeTabsCallsStep($step, $lang_id, $team_id)
    {
        switch ($step) {
            case 'no_access': $return = $this->uploadCallsNoAccess($lang_id); break;
            case 'call_list': $return = $this->uploadCallsList($team_id, $lang_id); break;
            
            default: $return = $this->uploadCallsNoAccess($lang_id); break;
        }

        return $return;
    }

    // calls - нет доступа
    private function uploadCallsNoAccess($lang_id)
    {
        $translation = $this->getWordsByPage('game', $lang_id);

        $return = [];

        $return['titles'] = '<div class="dashboard_tab_title dashboard_tab_title_active" data-tab="tab1">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18.8889 13.8889C17.5056 13.8889 16.1667 13.6667 14.9222 13.2556C14.5389 13.1333 14.1 13.2222 13.7945 13.5278L11.3501 15.9778C8.20005 14.3778 5.62781 11.8056 4.02781 8.66115L6.47224 6.20557C6.77781 5.9 6.86667 5.46115 6.74448 5.07781C6.33339 3.83339 6.11115 2.49448 6.11115 1.11115C6.11109 0.494427 5.61667 0 5 0H1.11109C0.5 0 0 0.494427 0 1.11109C0 11.5444 8.45557 20 18.8889 20C19.5056 20 20 19.5056 20 18.8889V15C20 14.3833 19.5056 13.8889 18.8889 13.8889Z" fill="#00F0FF"/></svg>
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text12'] . '</div>
                                </div>
                            </div>';

        $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_no_access dashboard_tab_content_item_active" data-tab="tab1">
                                <div class="dashboard_tab_content_item_no_access_inner">
                                    <img src="/images/tab_no_access_bg.png" class="dashboard_tab_content_item_no_access_bg" alt="">
                                    <div class="dashboard_tab_content_item_no_access_skew_line_top"></div>
                                    <div class="dashboard_tab_content_item_no_access_skew_line_bottom"></div>
                                </div>
                                <div class="dashboard_tab_content_item_no_access_inner_va">
                                    <div class="dashboard_tab_content_item_no_access_title">
                                        <img src="/images/tab_no_access_border_left.png" class="tab_no_access_border_left" alt="">
                                        <img src="/images/tab_no_access_border_left.png" class="tab_no_access_border_right" alt="">
                                        <div class="dashboard_tab_content_item_no_access_title_text">' . $translation['text39'] . '</div>
                                        <img src="/images/dashboard_tab_content_item_no_access_line_left.png" class="dashboard_tab_content_item_no_access_line_left" alt="">
                                        <img src="/images/dashboard_tab_content_item_no_access_line_right.png" class="dashboard_tab_content_item_no_access_line_right" alt="">
                                        <img src="/images/dashboard_tab_content_item_no_access_line_left2.png" class="dashboard_tab_content_item_no_access_line_left2" alt="">
                                        <img src="/images/dashboard_tab_content_item_no_access_line_right2.png" class="dashboard_tab_content_item_no_access_line_right2" alt="">
                                    </div>
                                    <div class="dashboard_tab_content_item_no_access_subtitle">' . $translation['text40'] . '</div>
                                </div>
                            </div>';

        return $return;
    }

    // calls - список звонков
    private function uploadCallsList($team_id, $lang_id)
{
    $translation = $this->getWordsByPage('game', $lang_id);
    $team_info   = $this->teamInfo($team_id);

    $return = [];

    $return['titles'] = '
             <div class="flex items-center gap-3 mb-6">
                <div class="p-2 rounded-lg bg-primary/20 border border-primary/30">
                    <svg width="24" height="24" fill="currentColor" class="text-primary">
                        <path d="M6.62 10.79a15.053 15.053 0 006.59 6.59l2.2-2.2a1 1 0 011.11-.21 11.72 11.72 0 003.64.59 1 1 0 011 1V20a1 1 0 01-1 1C9.28 21 3 14.72 3 7a1 1 0 011-1h3.5a1 1 0 011 1c0 1.27.2 2.52.59 3.64a1 1 0 01-.21 1.11l-2.26 2.24z"/>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold neon-text">' . $translation['text12'] . '</h2>
            </div>
    ';

    $return['content'] = '<div class="space-y-3">';

    if ($team_info) {
        $active_calls = json_decode($team_info['active_calls'], true);

        foreach ($active_calls as $call) {
            $sql = "
                SELECT c.type, cd.video, cd.name, cd.video_with_path
                FROM calls c
                JOIN calls_description cd ON c.id = cd.call_id
                WHERE c.id = {?} AND cd.lang_id = {?}
            ";
            $call_info = $this->db->selectRow($sql, [$call['id'], $lang_id]);

            // Иконка звонка
            $icon = '';
            if ($call_info) {
                if ($call_info['type'] == 'incoming') {
                    $icon = '<img src="/images/incoming_icon.png" alt="Входящий">';
                } elseif ($call_info['type'] == 'outgoing') {
                    $icon = '<img src="/images/outgoing_icon.png" alt="Исходящий">';
                } else {
                    $icon = '<img src="/images/missed_icon.png" alt="Пропущенный">';
                }
            }

            // Дата и время
            $datetime = '';
            if (!empty($call['datetime'])) {
                $dt_object = new DateTime($call['datetime']);
                $datetime  = $dt_object->format('d.m.Y H:i:s');
            }

            // Длительность
            $duration = !empty($call['duration']) ? $call['duration'] : '00:00';

            // Кнопка прослушивания
            $listen_btn = '';
            if (!empty($call_info['video']) || !empty($call_info['video_with_path'])) {
                $listen_btn = '
                    <button 
                        class="px-3 py-1 rounded-lg border border-primary/30 text-primary hover:bg-primary/20 transition-all duration-300 flex items-center gap-2"
                        data-path="' . (!empty($call_info['video']) ? $call_info['video'] : '') . '"
                        data-video-with-path="' . (!empty($call_info['video_with_path']) ? $call_info['video_with_path'] : '') . '">
                        ▶ ' . $translation['text55'] . '
                    </button>';
            }

            $return['content'] .= '
                <div class="bg-muted/20 border-border/50 hover:bg-muted/30 transition-all duration-300 p-4 rounded-lg flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            ' . $icon . '
                            <div class="p-2 rounded-full bg-muted/50">
                                <img src="/images/user_icon.png" class="h-4 w-4 opacity-70" alt="User">
                            </div>
                        </div>
                        <div>
                            <h3 class="font-semibold text-foreground text-lg">' . (!empty($call_info['name']) ? $call_info['name'] : $translation['text175']) . '</h3>
                            <div class="flex items-center gap-3 text-muted-foreground text-base mt-1">
                                <span>' . $datetime . '</span>
                                ' . ($duration !== '00:00' ? '<span>' . $translation['text176'] . ': ' . $duration . '</span>' : '') . '
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        ' . $listen_btn . '
                    </div>
                </div>';
        }
    }

    $return['content'] .= ' </div>';

    return $return;
}






/* DATABASES */
    // загрузить конкретный экран (с переключателем табов) для databases
    public function uploadTypeTabsDatabasesStep($step, $lang_id, $team_id)
    {
        switch ($step) {
            case 'no_access': $return = $this->uploadDatabasesNoAccess($lang_id); break;
            case 'databases_start_four': $return = $this->uploadDatabasesStartFour($lang_id); break;
            case 'databases_start_four_inner_first_car_register': $return = $this->uploadDatabasesCarRegister($lang_id, $team_id); break;
            case 'databases_start_four_inner_second_car_register_huilov': $return = $this->uploadDatabasesCarRegisterHuilov($lang_id, $team_id); break;
            case 'databases_start_four_inner_first_personal_files': $return = $this->uploadDatabasesPersonalFiles($lang_id, $team_id); break;
            case 'databases_start_four_inner_second_personal_files_private_individual': $return = $this->uploadDatabasesPersonalFilesPrivateIndividual($lang_id, $team_id); break;
            case 'databases_start_four_inner_second_personal_files_private_individual_huilov': $return = $this->uploadDatabasesPersonalFilesPrivateIndividualHuilov($lang_id, $team_id); break;
            case 'databases_start_four_inner_second_personal_files_ceo_database': $return = $this->uploadDatabasesPersonalFilesCeoDatabase($lang_id, $team_id); break;
            case 'databases_start_four_inner_second_personal_files_ceo_database_rod': $return = $this->uploadDatabasesPersonalFilesCeoDatabaseRod($lang_id, $team_id); break;
            case 'databases_start_four_inner_first_mobile_calls': $return = $this->uploadDatabasesMobileCalls($lang_id, $team_id); break;
            case 'databases_start_four_inner_first_mobile_calls_messages': $return = $this->uploadDatabasesMobileCallsMessages($lang_id, $team_id); break;
            case 'databases_start_four_inner_first_bank_transactions': $return = $this->uploadDatabasesBankTransactions($lang_id, $team_id); break;
            case 'databases_bank_transactions_success': $return = $this->uploadDatabasesBankTransactionsSuccess($lang_id, $team_id); break;
            
            default: $return = $this->uploadDatabasesNoAccess($lang_id); break;
        }

        return $return;
    }

    // databases - нет доступа
    private function uploadDatabasesNoAccess($lang_id, $no_access_text = false, $back_btn = false)
    {
        $translation = $this->getWordsByPage('game', $lang_id);

        $return = [];

        $return['titles'] = '<div class="dashboard_tab_title dashboard_tab_title_active dashboard_tab_title_can_click" data-tab="tab1" data-step="databases_start_four" data-action-id="28" data-database="false">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper">
                                        <svg width="19" height="21" viewBox="0 0 19 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 0H19V3L17.25 5H0V2L1.75 0ZM1.73684 2H3V3.2L2.26316 4H1V2.8L1.73684 2ZM6 2H4.73684L4 2.8V4H5.26316L6 3.2V2ZM7.73684 2H9V3.2L8.26316 4H7V2.8L7.73684 2ZM17 2H10.7368L10 2.8V4H16.2632L17 3.2V2Z" fill="#00F0FF"/><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 8H19V11L17.25 13H0V10L1.75 8ZM1.73684 10H3V11.2L2.26316 12H1V10.8L1.73684 10ZM6 10H4.73684L4 10.8V12H5.26316L6 11.2V10ZM7.73684 10H9V11.2L8.26316 12H7V10.8L7.73684 10ZM17 10H10.7368L10 10.8V12H16.2632L17 11.2V10Z" fill="#00F0FF"/><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 16H19V19L17.25 21H0V18L1.75 16ZM1.73684 18H3V19.2L2.26316 20H1V18.8L1.73684 18ZM6 18H4.73684L4 18.8V20H5.26316L6 19.2V18ZM7.73684 18H9V19.2L8.26316 20H7V18.8L7.73684 18ZM17 18H10.7368L10 18.8V20H16.2632L17 19.2V18Z" fill="#00F0FF"/><rect width="15" height="1" transform="matrix(1 0 0 -1 2 7)" fill="#00F0FF"/><rect width="15" height="1" transform="matrix(1 0 0 -1 2 15)" fill="#00F0FF"/></svg>
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text13'] . '</div>
                                </div>
                            </div>';

        if (!$no_access_text) {
            $no_access_text = $translation['text40'];
        } else {
            $no_access_text = $translation[$no_access_text];
        }

        if ($back_btn) {
            $return['back_btn'] = '<div class="dashboard_back_btn" data-back="databases_start_four" data-action-id-back="28" data-database="false">
                                        <img src="/images/back_bg.png" class="back_btn_bg" alt="">
                                        <div class="back_btn_text">' . $translation['text22'] . '</div>
                                    </div>';
        }

        $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_no_access dashboard_tab_content_item_active" data-tab="tab1">
                                <div class="dashboard_tab_content_item_no_access_inner">
                                    <img src="/images/tab_no_access_bg.png" class="dashboard_tab_content_item_no_access_bg" alt="">
                                    <div class="dashboard_tab_content_item_no_access_skew_line_top"></div>
                                    <div class="dashboard_tab_content_item_no_access_skew_line_bottom"></div>
                                </div>
                                <div class="dashboard_tab_content_item_no_access_inner_va">
                                    <div class="dashboard_tab_content_item_no_access_title">
                                        <img src="/images/tab_no_access_border_left.png" class="tab_no_access_border_left" alt="">
                                        <img src="/images/tab_no_access_border_left.png" class="tab_no_access_border_right" alt="">
                                        <div class="dashboard_tab_content_item_no_access_title_text">' . $translation['text39'] . '</div>
                                        <img src="/images/dashboard_tab_content_item_no_access_line_left.png" class="dashboard_tab_content_item_no_access_line_left" alt="">
                                        <img src="/images/dashboard_tab_content_item_no_access_line_right.png" class="dashboard_tab_content_item_no_access_line_right" alt="">
                                        <img src="/images/dashboard_tab_content_item_no_access_line_left2.png" class="dashboard_tab_content_item_no_access_line_left2" alt="">
                                        <img src="/images/dashboard_tab_content_item_no_access_line_right2.png" class="dashboard_tab_content_item_no_access_line_right2" alt="">
                                    </div>
                                    <div class="dashboard_tab_content_item_no_access_subtitle">' . $no_access_text . '</div>
                                </div>
                            </div>';

        return $return;
    }

    // databases - первый экран после принятии миссии - список 4-ех баз данных
    private function uploadDatabasesStartFour($lang_id)
{
    $svg = $this->svg; 
    $translation = $this->getWordsByPage('game', $lang_id);

    $return = [];

    $return['titles'] = '
        <div class="flex items-center gap-3 mb-8">
    <svg class="h-8 w-8 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path d="M12 3l9 4.5v9L12 21l-9-4.5v-9z"/>
    </svg>
    <h2 class="text-3xl font-bold neon-text">' . $translation['text13'] . '</h2>
  </div>';

    $return['content'] = '
    <div class="flex justify-center items-center gap-6 w-full">

  <!-- Personal Files -->
  <div class="cyber-panel cursor-pointer transition-all hover:scale-105 group border-cyan-500 bg-cyan-900/20">
    <div class="text-center pb-3">
      <div class="mx-auto mb-4 relative">
        <div class="w-16 h-16 rounded-full bg-cyan-900/30 flex items-center justify-center group-hover:scale-110 transition-transform">
         ' .  $svg['database_document'] . ' 
        </div>
        <div class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-green-400 border-2 border-background animate-pulse"></div>
      </div>
      <h3 class="text-xl mb-2">' . $translation['text57']. '</h3>
      <p class="text-sm text-muted-foreground mb-3">Доступ к личным досье</p>
    </div>
    <div class="text-center space-y-3 px-4 pb-4">
      <div class="flex justify-between text-sm">
        <span class="text-muted-foreground">Records:</span>
        <span class="text-cyan-400">12,345</span>
      </div>
      <span class="badge w-full justify-center bg-green-400">ACTIVE</span>
      <button data-database="personal_files" class="dashboard_tab_content_item_start_four_inner_item w-full text-cyan-400 border-current hover:bg-current/10 group-hover:glow-effect border rounded px-2 py-1 flex items-center justify-center text-sm">
        <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
          <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
        </svg>
        Access Database
      </button>
    </div>
  </div>

  <!-- Car Register -->
  <div class="cyber-panel cursor-pointer transition-all hover:scale-105 group border-purple-500 bg-purple-900/20">
    <div class="text-center pb-3">
      <div class="mx-auto mb-4 relative">
        <div class="w-16 h-16 rounded-full bg-purple-900/30 flex items-center justify-center group-hover:scale-110 transition-transform">
          ' .  $svg['database_car'] . '         
        </div>
        <div class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-yellow-400 border-2 border-background"></div>
      </div>
      <h3 class="text-xl mb-2">' . $translation['text58'] . '</h3>
      <p class="text-sm text-muted-foreground mb-3">Реестр автомобилей</p>
    </div>
    <div class="text-center space-y-3 px-4 pb-4">
      <div class="flex justify-between text-sm">
        <span class="text-muted-foreground">Records:</span>
        <span class="text-purple-400">8,764</span>
      </div>
      <span class="badge w-full justify-center bg-yellow-400">LIMITED</span>
      <button data-database="car_register" class="dashboard_tab_content_item_start_four_inner_item w-full text-purple-400 border-current hover:bg-current/10 group-hover:glow-effect border rounded px-2 py-1 flex items-center justify-center text-sm">
        <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
          <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
        </svg>
        Access Database
      </button>
    </div>
  </div>

  <!-- Mobile Calls -->
  <div class="cyber-panel cursor-pointer transition-all hover:scale-105 group border-blue-500 bg-blue-900/20">
    <div class="text-center pb-3">
      <div class="mx-auto mb-4 relative">
        <div class="w-16 h-16 rounded-full bg-blue-900/30 flex items-center justify-center group-hover:scale-110 transition-transform">
          ' .  $svg['database_call'] . '         
        </div>
        <div class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-red-400 border-2 border-background"></div>
      </div>
      <h3 class="text-xl mb-2">' . $translation['text59'] . '</h3>
      <p class="text-sm text-muted-foreground mb-3">История мобильных звонков</p>
    </div>
    <div class="text-center space-y-3 px-4 pb-4">
      <div class="flex justify-between text-sm">
        <span class="text-muted-foreground">Records:</span>
        <span class="text-blue-400">23,109</span>
      </div>
      <span class="badge w-full justify-center bg-red-400">RESTRICTED</span>
      <button data-database="mobile_calls" class="dashboard_tab_content_item_start_four_inner_item w-full text-blue-400 border-current hover:bg-current/10 group-hover:glow-effect border rounded px-2 py-1 flex items-center justify-center text-sm">
        <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
          <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
        </svg>
        Access Database
      </button>
    </div>
  </div>

  <!-- Bank Transactions -->
  <div class="cyber-panel cursor-pointer transition-all hover:scale-105 group border-green-500 bg-green-900/20">
    <div class="text-center pb-3">
      <div class="mx-auto mb-4 relative">
        <div class="w-16 h-16 rounded-full bg-green-900/30 flex items-center justify-center group-hover:scale-110 transition-transform">
          ' .  $svg['database_card'] . '         
        </div>
        <div class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-green-400 border-2 border-background"></div>
      </div>
      <h3 class="text-xl mb-2">' . $translation['text60'] . '</h3>
      <p class="text-sm text-muted-foreground mb-3">Финансовые транзакции</p>
    </div>
    <div class="text-center space-y-3 px-4 pb-4">
      <div class="flex justify-between text-sm">
        <span class="text-muted-foreground">Records:</span>
        <span class="text-green-400">45,876</span>
      </div>
      <span class="badge w-full justify-center bg-green-400">ACTIVE</span>
      <button data-database="bank_transactions" class="dashboard_tab_content_item_start_four_inner_item w-full text-green-400 border-current hover:bg-current/10 group-hover:glow-effect border rounded px-2 py-1 flex items-center justify-center text-sm">
        <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
          <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
        </svg>
        Access Database
      </button>
    </div>
  </div>

</div>

        ';

    return $return;
}

    // databases - загрузить Car Register. Первый экран
    private function uploadDatabasesCarRegister($lang_id, $team_id)
    {
        $translation = $this->getWordsByPage('game', $lang_id);

        $team_info = $this->teamInfo($team_id);

        $return = [];

        $return['titles'] = '<div class="dashboard_tab_title dashboard_tab_title_can_click" data-tab="tab1" data-step="databases_start_four" data-action-id="28" data-database="false">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper">
                                        <svg width="19" height="21" viewBox="0 0 19 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 0H19V3L17.25 5H0V2L1.75 0ZM1.73684 2H3V3.2L2.26316 4H1V2.8L1.73684 2ZM6 2H4.73684L4 2.8V4H5.26316L6 3.2V2ZM7.73684 2H9V3.2L8.26316 4H7V2.8L7.73684 2ZM17 2H10.7368L10 2.8V4H16.2632L17 3.2V2Z" fill="#00F0FF"/><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 8H19V11L17.25 13H0V10L1.75 8ZM1.73684 10H3V11.2L2.26316 12H1V10.8L1.73684 10ZM6 10H4.73684L4 10.8V12H5.26316L6 11.2V10ZM7.73684 10H9V11.2L8.26316 12H7V10.8L7.73684 10ZM17 10H10.7368L10 10.8V12H16.2632L17 11.2V10Z" fill="#00F0FF"/><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 16H19V19L17.25 21H0V18L1.75 16ZM1.73684 18H3V19.2L2.26316 20H1V18.8L1.73684 18ZM6 18H4.73684L4 18.8V20H5.26316L6 19.2V18ZM7.73684 18H9V19.2L8.26316 20H7V18.8L7.73684 18ZM17 18H10.7368L10 18.8V20H16.2632L17 19.2V18Z" fill="#00F0FF"/><rect width="15" height="1" transform="matrix(1 0 0 -1 2 7)" fill="#00F0FF"/><rect width="15" height="1" transform="matrix(1 0 0 -1 2 15)" fill="#00F0FF"/></svg>
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text13'] . '</div>
                                </div>
                            </div>
                            <div class="dashboard_tab_title dashboard_tab_title_active" data-tab="car_register1">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper" style="margin: -10px 0 0;">
                                        <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11.4595 24.9998H8.54054C8.24324 24.9998 8 25.3155 8 25.7015V28.298C8 28.684 8.24324 28.9998 8.54054 28.9998H11.4595C11.7568 28.9998 12 28.684 12 28.298V25.7015C12 25.3155 11.7568 24.9998 11.4595 24.9998ZM10.9189 27.5962H9.08108V26.4033H10.9189V27.5962Z" fill="#00F0FF"/><path d="M25.3243 24.9998H21.6757C21.3041 24.9998 21 25.3155 21 25.7015V28.298C21 28.684 21.3041 28.9998 21.6757 28.9998H25.3243C25.6959 28.9998 26 28.684 26 28.298V25.7015C26 25.3155 25.6959 24.9998 25.3243 24.9998ZM24.6486 27.5962H22.3514V26.4033H24.6486V27.5962Z" fill="#00F0FF"/><path d="M19.3304 26.9998H14.6696C14.3013 26.9998 14 27.4498 14 27.9998C14 28.5498 14.2946 28.9998 14.6696 28.9998H19.3304C19.6987 28.9998 20 28.5498 20 27.9998C20 27.4498 19.6987 26.9998 19.3304 26.9998Z" fill="#00F0FF"/><path d="M19.3304 25H14.6696C14.3013 25 14 25.45 14 26C14 26.55 14.2946 27 14.6696 27H19.3304C19.6987 27 20 26.55 20 26C20 25.45 19.6987 25 19.3304 25Z" fill="#00F0FF"/><path d="M30.6644 9.20212L24.6262 6.07844C24.4219 5.97278 24.1837 5.97278 23.9794 6.08504L18.091 9.20872C17.8731 9.32099 17.7302 9.54552 17.7302 9.78987V14.2145C17.737 15.2183 17.9208 16.2155 18.2816 17.1599H10.4802C9.57478 17.1599 8.75107 17.6882 8.39708 18.5005L6.71563 22.2978L6.03488 22.9252C5.37455 23.4799 4.99333 24.2922 5.00014 25.1441V31.8273C4.98653 33.016 5.96681 33.9868 7.19216 34C7.19896 34 7.19896 34 7.20577 34H8.4992C9.73135 33.9934 10.7252 33.0226 10.7184 31.8273V31.0216H22.4954V31.8207C22.509 33.0226 23.5097 33.9934 24.7487 33.9934H26.0421C27.2743 33.9868 28.275 33.0226 28.2818 31.8207V24.9658C28.2818 24.1667 27.9414 23.4072 27.3492 22.8591L26.8726 22.3704L26.5595 21.7034C29.3165 20.1185 31.0048 17.2391 30.998 14.1287V9.78987C31.0184 9.53892 30.8823 9.31438 30.6644 9.20212ZM9.64966 19.0222C9.79262 18.692 10.1194 18.4807 10.4802 18.4741H18.9079C19.1053 18.8175 19.3231 19.1477 19.5614 19.4647C18.4177 19.8939 17.5804 20.8647 17.3489 22.0402H8.3222L9.64966 19.0222ZM20.5757 20.5873C21.202 21.1619 21.9032 21.6506 22.6656 22.0402H18.7513C19.01 21.2477 19.7248 20.6798 20.5757 20.5873ZM9.36375 31.8207C9.37056 32.283 8.98934 32.666 8.506 32.6726H7.21258C6.72925 32.6726 6.36845 32.2896 6.36845 31.8207V30.8763C6.63394 30.982 6.92666 31.0282 7.21258 31.0216H9.36375V31.8207ZM26.9271 31.8207C26.9271 32.2896 26.5323 32.6726 26.0489 32.6726H24.7555C24.2722 32.6726 23.8705 32.2962 23.8637 31.8207V31.0216H26.0489C26.3485 31.0282 26.648 30.982 26.9271 30.8763V31.8207ZM26.9271 24.9592V28.8819C26.9271 29.3508 26.5323 29.7008 26.0489 29.7008H7.21258C6.75647 29.714 6.38206 29.364 6.36845 28.9216C6.36845 28.9083 6.36845 28.8951 6.36845 28.8819V25.1375C6.36164 24.6686 6.57267 24.2261 6.93347 23.9157C6.94028 23.9091 6.94028 23.9091 6.94709 23.9025L7.55295 23.361H25.9604L26.3757 23.7771C26.3825 23.7837 26.3961 23.7903 26.4097 23.8035C26.7365 24.1073 26.9271 24.5233 26.9271 24.9592ZM29.6501 14.1221C29.6569 17.1335 27.7985 19.8543 24.9325 21.0232L24.2858 21.294L23.7344 21.0628C20.9229 19.8741 19.0985 17.1797 19.0917 14.2013V10.1729L24.2858 7.40584L29.6501 10.1795V14.1221Z" fill="#00F0FF"/><path d="M27.8373 12.2862C27.5867 11.9511 27.1357 11.9044 26.8279 12.1771L23.4774 15.1456L22.2604 13.5951C22.0027 13.2679 21.5517 13.2289 21.251 13.5094C20.9503 13.7899 20.9145 14.2808 21.1722 14.608L22.8403 16.7351C22.8474 16.7429 22.8546 16.7507 22.8618 16.7585C22.8689 16.7663 22.8761 16.7818 22.8904 16.7896C22.9047 16.7974 22.9119 16.813 22.919 16.8208C22.9262 16.8286 22.9405 16.8364 22.9477 16.8442C22.9548 16.852 22.9691 16.8598 22.9835 16.8675C22.9906 16.8753 23.0049 16.8831 23.0121 16.8909C23.0264 16.8987 23.0336 16.9065 23.0479 16.9143C23.055 16.9221 23.0694 16.9299 23.0765 16.9299C23.0908 16.9377 23.1052 16.9455 23.1195 16.9455C23.1266 16.9533 23.141 16.9533 23.1481 16.961C23.1624 16.9688 23.1768 16.9688 23.1911 16.9766C23.1982 16.9766 23.2125 16.9844 23.2197 16.9844C23.234 16.9844 23.2483 16.9922 23.2627 16.9922C23.2698 16.9922 23.2841 17 23.2913 17C23.3128 17 23.3271 17 23.3486 17C23.3557 17 23.3629 17 23.37 17C23.3915 17 23.413 17 23.4345 17C23.4416 17 23.4416 17 23.4488 17C23.4631 17 23.4846 16.9922 23.4989 16.9922C23.5061 16.9922 23.5132 16.9922 23.5204 16.9844C23.5347 16.9844 23.549 16.9766 23.5633 16.9766C23.5705 16.9766 23.5777 16.9688 23.5848 16.9688C23.5991 16.9688 23.6063 16.961 23.6206 16.9533C23.6278 16.9533 23.6349 16.9455 23.6492 16.9455C23.6564 16.9377 23.6707 16.9377 23.6779 16.9299C23.685 16.9221 23.6922 16.9221 23.7065 16.9143C23.7208 16.9065 23.728 16.9065 23.7352 16.8987C23.7423 16.8909 23.7495 16.8831 23.7638 16.8831C23.771 16.8753 23.7853 16.8675 23.7924 16.8675C23.7996 16.8675 23.8067 16.852 23.8211 16.8442C23.8282 16.8364 23.8354 16.8364 23.8425 16.8286L27.7371 13.3848C28.0449 13.1121 28.0878 12.6212 27.8373 12.2862Z" fill="#00F0FF"/></svg>
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text171'] . '</div>
                                </div>
                            </div>';

        $return['back_btn'] = '<div class="dashboard_back_btn" data-back="databases_start_four" data-action-id-back="28" data-database="false">
                                    <img src="/images/back_bg.png" class="back_btn_bg" alt="">
                                    <div class="back_btn_text">' . $translation['text22'] . '</div>
                                </div>';

        $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_start_four" data-tab="tab1"></div>

                            <div class="dashboard_tab_content_item dashboard_tab_content_item_car_register dashboard_tab_content_item_active" data-tab="car_register1">
                                <div class="dashboard_car_register1_inner">
                                    <div class="dashboard_car_register1_inner_image_wrapper">
                                        <svg width="59" height="59" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11.4595 24.9998H8.54054C8.24324 24.9998 8 25.3155 8 25.7015V28.298C8 28.684 8.24324 28.9998 8.54054 28.9998H11.4595C11.7568 28.9998 12 28.684 12 28.298V25.7015C12 25.3155 11.7568 24.9998 11.4595 24.9998ZM10.9189 27.5962H9.08108V26.4033H10.9189V27.5962Z" fill="#00F0FF"/><path d="M25.3243 24.9998H21.6757C21.3041 24.9998 21 25.3155 21 25.7015V28.298C21 28.684 21.3041 28.9998 21.6757 28.9998H25.3243C25.6959 28.9998 26 28.684 26 28.298V25.7015C26 25.3155 25.6959 24.9998 25.3243 24.9998ZM24.6486 27.5962H22.3514V26.4033H24.6486V27.5962Z" fill="#00F0FF"/><path d="M19.3304 26.9998H14.6696C14.3013 26.9998 14 27.4498 14 27.9998C14 28.5498 14.2946 28.9998 14.6696 28.9998H19.3304C19.6987 28.9998 20 28.5498 20 27.9998C20 27.4498 19.6987 26.9998 19.3304 26.9998Z" fill="#00F0FF"/><path d="M19.3304 25H14.6696C14.3013 25 14 25.45 14 26C14 26.55 14.2946 27 14.6696 27H19.3304C19.6987 27 20 26.55 20 26C20 25.45 19.6987 25 19.3304 25Z" fill="#00F0FF"/><path d="M30.6644 9.20212L24.6262 6.07844C24.4219 5.97278 24.1837 5.97278 23.9794 6.08504L18.091 9.20872C17.8731 9.32099 17.7302 9.54552 17.7302 9.78987V14.2145C17.737 15.2183 17.9208 16.2155 18.2816 17.1599H10.4802C9.57478 17.1599 8.75107 17.6882 8.39708 18.5005L6.71563 22.2978L6.03488 22.9252C5.37455 23.4799 4.99333 24.2922 5.00014 25.1441V31.8273C4.98653 33.016 5.96681 33.9868 7.19216 34C7.19896 34 7.19896 34 7.20577 34H8.4992C9.73135 33.9934 10.7252 33.0226 10.7184 31.8273V31.0216H22.4954V31.8207C22.509 33.0226 23.5097 33.9934 24.7487 33.9934H26.0421C27.2743 33.9868 28.275 33.0226 28.2818 31.8207V24.9658C28.2818 24.1667 27.9414 23.4072 27.3492 22.8591L26.8726 22.3704L26.5595 21.7034C29.3165 20.1185 31.0048 17.2391 30.998 14.1287V9.78987C31.0184 9.53892 30.8823 9.31438 30.6644 9.20212ZM9.64966 19.0222C9.79262 18.692 10.1194 18.4807 10.4802 18.4741H18.9079C19.1053 18.8175 19.3231 19.1477 19.5614 19.4647C18.4177 19.8939 17.5804 20.8647 17.3489 22.0402H8.3222L9.64966 19.0222ZM20.5757 20.5873C21.202 21.1619 21.9032 21.6506 22.6656 22.0402H18.7513C19.01 21.2477 19.7248 20.6798 20.5757 20.5873ZM9.36375 31.8207C9.37056 32.283 8.98934 32.666 8.506 32.6726H7.21258C6.72925 32.6726 6.36845 32.2896 6.36845 31.8207V30.8763C6.63394 30.982 6.92666 31.0282 7.21258 31.0216H9.36375V31.8207ZM26.9271 31.8207C26.9271 32.2896 26.5323 32.6726 26.0489 32.6726H24.7555C24.2722 32.6726 23.8705 32.2962 23.8637 31.8207V31.0216H26.0489C26.3485 31.0282 26.648 30.982 26.9271 30.8763V31.8207ZM26.9271 24.9592V28.8819C26.9271 29.3508 26.5323 29.7008 26.0489 29.7008H7.21258C6.75647 29.714 6.38206 29.364 6.36845 28.9216C6.36845 28.9083 6.36845 28.8951 6.36845 28.8819V25.1375C6.36164 24.6686 6.57267 24.2261 6.93347 23.9157C6.94028 23.9091 6.94028 23.9091 6.94709 23.9025L7.55295 23.361H25.9604L26.3757 23.7771C26.3825 23.7837 26.3961 23.7903 26.4097 23.8035C26.7365 24.1073 26.9271 24.5233 26.9271 24.9592ZM29.6501 14.1221C29.6569 17.1335 27.7985 19.8543 24.9325 21.0232L24.2858 21.294L23.7344 21.0628C20.9229 19.8741 19.0985 17.1797 19.0917 14.2013V10.1729L24.2858 7.40584L29.6501 10.1795V14.1221Z" fill="#00F0FF"/><path d="M27.8373 12.2862C27.5867 11.9511 27.1357 11.9044 26.8279 12.1771L23.4774 15.1456L22.2604 13.5951C22.0027 13.2679 21.5517 13.2289 21.251 13.5094C20.9503 13.7899 20.9145 14.2808 21.1722 14.608L22.8403 16.7351C22.8474 16.7429 22.8546 16.7507 22.8618 16.7585C22.8689 16.7663 22.8761 16.7818 22.8904 16.7896C22.9047 16.7974 22.9119 16.813 22.919 16.8208C22.9262 16.8286 22.9405 16.8364 22.9477 16.8442C22.9548 16.852 22.9691 16.8598 22.9835 16.8675C22.9906 16.8753 23.0049 16.8831 23.0121 16.8909C23.0264 16.8987 23.0336 16.9065 23.0479 16.9143C23.055 16.9221 23.0694 16.9299 23.0765 16.9299C23.0908 16.9377 23.1052 16.9455 23.1195 16.9455C23.1266 16.9533 23.141 16.9533 23.1481 16.961C23.1624 16.9688 23.1768 16.9688 23.1911 16.9766C23.1982 16.9766 23.2125 16.9844 23.2197 16.9844C23.234 16.9844 23.2483 16.9922 23.2627 16.9922C23.2698 16.9922 23.2841 17 23.2913 17C23.3128 17 23.3271 17 23.3486 17C23.3557 17 23.3629 17 23.37 17C23.3915 17 23.413 17 23.4345 17C23.4416 17 23.4416 17 23.4488 17C23.4631 17 23.4846 16.9922 23.4989 16.9922C23.5061 16.9922 23.5132 16.9922 23.5204 16.9844C23.5347 16.9844 23.549 16.9766 23.5633 16.9766C23.5705 16.9766 23.5777 16.9688 23.5848 16.9688C23.5991 16.9688 23.6063 16.961 23.6206 16.9533C23.6278 16.9533 23.6349 16.9455 23.6492 16.9455C23.6564 16.9377 23.6707 16.9377 23.6779 16.9299C23.685 16.9221 23.6922 16.9221 23.7065 16.9143C23.7208 16.9065 23.728 16.9065 23.7352 16.8987C23.7423 16.8909 23.7495 16.8831 23.7638 16.8831C23.771 16.8753 23.7853 16.8675 23.7924 16.8675C23.7996 16.8675 23.8067 16.852 23.8211 16.8442C23.8282 16.8364 23.8354 16.8364 23.8425 16.8286L27.7371 13.3848C28.0449 13.1121 28.0878 12.6212 27.8373 12.2862Z" fill="#00F0FF"/></svg>
                                    </div>
                                    <div class="dashboard_car_register1_inner_title">' . $translation['text171'] . '</div>
                                    <div class="dashboard_car_register1_inner_text">' . $translation['text62'] . '</div>
                                    <div class="dashboard_car_register1_fields_top">
                                        <div class="dashboard_car_register1_input_wrapper dashboard_car_register1_input_wrapper_license_plate">
                                            <div class="dashboard_car_register1_input_border_left"></div>
                                            <input type="text" placeholder="' . $translation['text63'] . '" autocomplete="off" class="dashboard_car_register1_license_plate">
                                            <div class="dashboard_car_register1_license_plate_error error_text_database_car_register">' . $translation['text86'] . '</div>
                                        </div>
                                        <div class="dashboard_car_register1_input_wrapper dashboard_car_register1_input_wrapper_country">
                                            <div class="dashboard_car_register1_input_border_right"></div>';
                                            // <input type="text" placeholder="' . $translation['text64'] . '" autocomplete="off" class="dashboard_car_register1_country">

        $sql = "
            SELECT c.code, c.pos, cd.name, c.id
            FROM countries c
            JOIN countries_description cd ON c.id = cd.country_id
            WHERE cd.lang_id = {?}
            ORDER BY cd.name
        ";
        $countries = $this->db->select($sql, [$lang_id]);
        if ($countries) {
            $return['content'] .= '<select class="dashboard_car_register1_country"><option disabled="disabled"' . (empty($team_info['car_register_country_id']) ? ' selected="selected"' : '') . '>' . $translation['text64'] . '</option>';
            foreach ($countries as $country) {
                $return['content'] .= '<option value="' . htmlspecialchars($country['name'], ENT_QUOTES) . '" data-pos="' . $country['pos'] . '"' . ($team_info['car_register_country_id'] == $country['id'] ? ' selected="selected"' : '') . '>' . $country['name'] . '</option>';
            }
            $return['content'] .= '</select>
                                    <script>
                                        $(function() {
                                            // select country
                                            var scrollbarPositionPixel = 0;
                                            var isScrollOpen = false;

                                            $(".dashboard_car_register1_country").selectric({
                                                optionsItemBuilder: function(itemData, element, index) {
                                                    return (!itemData.disabled) ? \'<span class="select_country_flag" style="display:inline-block;width:16px;height:11px;background:url(/images/flags.png) no-repeat;background-position:\' + itemData.element[0].attributes[\'data-pos\'].value + \';margin: 0 15px 0 5px;"></span><span class="select_country_name" style="display:inline-block;max-width: 87%;">\' + itemData.text + \'</span>\' : itemData.text;
                                                },
                                                maxHeight: 236,
                                                preventWindowScroll: false,
                                                onInit: function() {
                                                    // стилизация полосы прокрутки
                                                    $(".selectric-dashboard_car_register1_country .selectric-scroll").mCustomScrollbar({
                                                        scrollInertia: 700,
                                                        theme: "minimal-dark",
                                                        scrollbarPosition: "inside",
                                                        alwaysShowScrollbar: 2,
                                                        autoHideScrollbar: false,
                                                        mouseWheel:{ deltaFactor: 200 },
                                                        callbacks:{
                                                            onScroll: function(){
                                                            },
                                                            whileScrolling:function() {
                                                                scrollbarPositionPixel = this.mcs.top;
                                                                if (isScrollOpen) {
                                                                    $(".dashboard_car_register1_country").selectric("open");
                                                                }
                                                            }
                                                        }
                                                    });
                                                },
                                                onOpen: function() {
                                                    if (!isScrollOpen) {
                                                        $(".selectric-dashboard_car_register1_country .selectric-scroll").mCustomScrollbar("scrollTo", Math.abs(scrollbarPositionPixel));
                                                        isScrollOpen = true;
                                                    }
                                                }
                                            })
                                            .on("change", function() {
                                                // сохраняем выбор
                                                var formData = new FormData();
                                                formData.append("op", "saveTeamTextField");
                                                formData.append("field", "car_register_country_id");
                                                formData.append("val", $(this).val());

                                                $.ajax({
                                                    url: "/ajax/ajax.php",
                                                    type: "POST",
                                                    dataType: "json",
                                                    cache: false,
                                                    contentType: false,
                                                    processData: false,
                                                    data: formData,
                                                    success: function(json) {
                                                        if (json.country_lang) {
                                                            // socket
                                                            var message = {
                                                                "op": "databaseCarRegisterUpdateCountry",
                                                                "parameters": {
                                                                    "country_lang": json.country_lang,
                                                                    "user_id": $("#section_game").length ? $("#section_game").attr("data-user-id") : 0,
                                                                    "team_id": $("#section_game").length ? $("#section_game").attr("data-team-id") : 0
                                                                }
                                                            };
                                                            sendMessageSocket(JSON.stringify(message));
                                                        }
                                                    },
                                                    error: function(xhr, ajaxOptions, thrownError) {    
                                                        console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                                    }
                                                });

                                                isScrollOpen = false;
                                            });

                                            $(".dashboard_tabs[data-dashboard=\'databases\']").on("click", ".dashboard_car_register1_input_wrapper_country .mCSB_scrollTools_vertical", function(e){
                                                if (isScrollOpen) {
                                                    $(".dashboard_car_register1_country").selectric("open");
                                                }
                                            });

                                            // datepicker
                                            $(".dashboard_car_register1_date").datepicker({
                                                dateFormat: "dd.mm.yy",
                                                dayNamesShort: ["' . $translation['text67'] . '", "' . $translation['text68'] . '", "' . $translation['text69'] . '", "' . $translation['text70'] . '", "' . $translation['text71'] . '", "' . $translation['text72'] . '", "' . $translation['text73'] . '"],
                                                dayNamesMin: ["' . $translation['text67'] . '", "' . $translation['text68'] . '", "' . $translation['text69'] . '", "' . $translation['text70'] . '", "' . $translation['text71'] . '", "' . $translation['text72'] . '", "' . $translation['text73'] . '"],
                                                monthNames: ["' . $translation['text74'] . '", "' . $translation['text75'] . '", "' . $translation['text76'] . '", "' . $translation['text77'] . '", "' . $translation['text78'] . '", "' . $translation['text79'] . '", "' . $translation['text80'] . '", "' . $translation['text81'] . '", "' . $translation['text82'] . '", "' . $translation['text83'] . '", "' . $translation['text84'] . '", "' . $translation['text85'] . '"],
                                                changeMonth: false,
                                                //showAnim: "clip",
                                                showAnim: "",
                                                onSelect: function(dateText) {
                                                    // сохраняем выбор
                                                    var formData = new FormData();
                                                    formData.append("op", "saveTeamTextField");
                                                    formData.append("field", "car_register_date");
                                                    formData.append("val", dateText);

                                                    $.ajax({
                                                        url: "/ajax/ajax.php",
                                                        type: "POST",
                                                        dataType: "json",
                                                        cache: false,
                                                        contentType: false,
                                                        processData: false,
                                                        data: formData,
                                                        success: function(json) {
                                                            // socket
                                                            var message = {
                                                                "op": "databaseCarRegisterUpdateDate",
                                                                "parameters": {
                                                                    "date": dateText,
                                                                    "user_id": $("#section_game").length ? $("#section_game").attr("data-user-id") : 0,
                                                                    "team_id": $("#section_game").length ? $("#section_game").attr("data-team-id") : 0
                                                                }
                                                            };
                                                            sendMessageSocket(JSON.stringify(message));
                                                        },
                                                        error: function(xhr, ajaxOptions, thrownError) {    
                                                            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                                        }
                                                    });
                                                },
                                                beforeShow: function() {
                                                    if (!is_touch_device()) {
                                                        var pageSize = getPageSize();
                                                        var windowWidth = pageSize[2];
                                                        if (windowWidth < 1800) {
                                                            $("body").removeClass("body_desktop_scale").css("transform", "scale(1)");

                                                            setTimeout(function() {
                                                                var pageSize = getPageSize();
                                                                var windowWidth = pageSize[0];

                                                                var koef = parseFloat((windowWidth / 1920).toFixed(2)) + 0.01;

                                                                $("body").addClass("body_desktop_scale").css("transform", "scale(" + koef + ")");
                                                                //$("body").css("transform", "scale(" + koef + ")");

                                                                var curDatepickerPosition = parseFloat($(".ui-datepicker").css("left"));
                                                                var differentDatepickerPosition = (1920 - windowWidth) / 2;
                                                                $(".ui-datepicker").css("left", (curDatepickerPosition + differentDatepickerPosition + 7) + "px");
                                                            }, 1);
                                                        }
                                                    }
                                                }
                                            });
                                        });
                                    </script>';
        }

        $return['content'] .= '             <div class="dashboard_car_register1_country_error error_text_database_car_register">' . $translation['text86'] . '</div>
                                        </div>
                                    </div>
                                    <div class="dashboard_car_register1_fields_bottom">
                                        <div class="dashboard_car_register1_input_wrapper dashboard_car_register1_input_wrapper_date">
                                            <div class="dashboard_car_register1_input_border_left"></div>
                                            <div class="dashboard_car_register1_input_border_right"></div>
                                            <input type="text" placeholder="' . $translation['text65'] . '" autocomplete="off" class="dashboard_car_register1_date" value="' . ((!empty($team_info['car_register_date']) && $team_info['car_register_date'] != '0000-00-00' && !is_null($team_info['car_register_date'])) ? $this->fromEngDatetimeToRus($team_info['car_register_date']) : '') . '">
                                            <div class="dashboard_car_register1_date_error error_text_database_car_register">' . $translation['text86'] . '</div>
                                        </div>
                                    </div>
                                    <div class="btn_wrapper btn_wrapper_blue dashboard_car_register1_search">
                                        <div class="btn btn_blue">
                                            <span>' . $translation['text66'] . '</span>
                                        </div>
                                        <div class="btn_border_top"></div>
                                        <div class="btn_border_bottom"></div>
                                        <div class="btn_border_left"></div>
                                        <div class="btn_border_left_arcle"></div>
                                        <div class="btn_border_right"></div>
                                        <div class="btn_border_right_arcle"></div>
                                        <div class="btn_bg_top_line"></div>
                                        <div class="btn_bg_bottom_line"></div>
                                        <div class="btn_bg_triangle_left"></div>
                                        <div class="btn_bg_triangle_right"></div>
                                        <div class="btn_circles_top">
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                        </div>
                                        <div class="btn_circles_bottom">
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>';

        return $return;
    }

    // databases - загрузить Car Register. Второй экран. Успешно нашли Huilov
    private function uploadDatabasesCarRegisterHuilov($lang_id, $team_id)
    {
        $translation = $this->getWordsByPage('game', $lang_id);

        $team_info = $this->teamInfo($team_id);

        if (isset($_COOKIE['hash'])) {
            $sql = "SELECT `car_register_print_text_huilov` FROM `users` WHERE `team_id` = {?} AND `hash` = {?} LIMIT 1";
            $user_info = $this->db->selectRow($sql, [$team_id, $_COOKIE['hash']]);
        } else {
            $sql = "SELECT `car_register_print_text_huilov` FROM `users` WHERE `team_id` = {?} AND `ip` = {?} LIMIT 1";
            $user_info = $this->db->selectRow($sql, [$team_id, $this->getIp()]);
        }

        $return = [];

        $return['titles'] = '<div class="dashboard_tab_title dashboard_tab_title_can_click" data-tab="tab1" data-step="databases_start_four" data-action-id="28" data-database="false">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper">
                                        <svg width="19" height="21" viewBox="0 0 19 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 0H19V3L17.25 5H0V2L1.75 0ZM1.73684 2H3V3.2L2.26316 4H1V2.8L1.73684 2ZM6 2H4.73684L4 2.8V4H5.26316L6 3.2V2ZM7.73684 2H9V3.2L8.26316 4H7V2.8L7.73684 2ZM17 2H10.7368L10 2.8V4H16.2632L17 3.2V2Z" fill="#00F0FF"/><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 8H19V11L17.25 13H0V10L1.75 8ZM1.73684 10H3V11.2L2.26316 12H1V10.8L1.73684 10ZM6 10H4.73684L4 10.8V12H5.26316L6 11.2V10ZM7.73684 10H9V11.2L8.26316 12H7V10.8L7.73684 10ZM17 10H10.7368L10 10.8V12H16.2632L17 11.2V10Z" fill="#00F0FF"/><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 16H19V19L17.25 21H0V18L1.75 16ZM1.73684 18H3V19.2L2.26316 20H1V18.8L1.73684 18ZM6 18H4.73684L4 18.8V20H5.26316L6 19.2V18ZM7.73684 18H9V19.2L8.26316 20H7V18.8L7.73684 18ZM17 18H10.7368L10 18.8V20H16.2632L17 19.2V18Z" fill="#00F0FF"/><rect width="15" height="1" transform="matrix(1 0 0 -1 2 7)" fill="#00F0FF"/><rect width="15" height="1" transform="matrix(1 0 0 -1 2 15)" fill="#00F0FF"/></svg>
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text13'] . '</div>
                                </div>
                            </div>
                            <div class="dashboard_tab_title" data-tab="car_register1">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper" style="margin: -10px 0 0;">
                                        <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11.4595 24.9998H8.54054C8.24324 24.9998 8 25.3155 8 25.7015V28.298C8 28.684 8.24324 28.9998 8.54054 28.9998H11.4595C11.7568 28.9998 12 28.684 12 28.298V25.7015C12 25.3155 11.7568 24.9998 11.4595 24.9998ZM10.9189 27.5962H9.08108V26.4033H10.9189V27.5962Z" fill="#00F0FF"/><path d="M25.3243 24.9998H21.6757C21.3041 24.9998 21 25.3155 21 25.7015V28.298C21 28.684 21.3041 28.9998 21.6757 28.9998H25.3243C25.6959 28.9998 26 28.684 26 28.298V25.7015C26 25.3155 25.6959 24.9998 25.3243 24.9998ZM24.6486 27.5962H22.3514V26.4033H24.6486V27.5962Z" fill="#00F0FF"/><path d="M19.3304 26.9998H14.6696C14.3013 26.9998 14 27.4498 14 27.9998C14 28.5498 14.2946 28.9998 14.6696 28.9998H19.3304C19.6987 28.9998 20 28.5498 20 27.9998C20 27.4498 19.6987 26.9998 19.3304 26.9998Z" fill="#00F0FF"/><path d="M19.3304 25H14.6696C14.3013 25 14 25.45 14 26C14 26.55 14.2946 27 14.6696 27H19.3304C19.6987 27 20 26.55 20 26C20 25.45 19.6987 25 19.3304 25Z" fill="#00F0FF"/><path d="M30.6644 9.20212L24.6262 6.07844C24.4219 5.97278 24.1837 5.97278 23.9794 6.08504L18.091 9.20872C17.8731 9.32099 17.7302 9.54552 17.7302 9.78987V14.2145C17.737 15.2183 17.9208 16.2155 18.2816 17.1599H10.4802C9.57478 17.1599 8.75107 17.6882 8.39708 18.5005L6.71563 22.2978L6.03488 22.9252C5.37455 23.4799 4.99333 24.2922 5.00014 25.1441V31.8273C4.98653 33.016 5.96681 33.9868 7.19216 34C7.19896 34 7.19896 34 7.20577 34H8.4992C9.73135 33.9934 10.7252 33.0226 10.7184 31.8273V31.0216H22.4954V31.8207C22.509 33.0226 23.5097 33.9934 24.7487 33.9934H26.0421C27.2743 33.9868 28.275 33.0226 28.2818 31.8207V24.9658C28.2818 24.1667 27.9414 23.4072 27.3492 22.8591L26.8726 22.3704L26.5595 21.7034C29.3165 20.1185 31.0048 17.2391 30.998 14.1287V9.78987C31.0184 9.53892 30.8823 9.31438 30.6644 9.20212ZM9.64966 19.0222C9.79262 18.692 10.1194 18.4807 10.4802 18.4741H18.9079C19.1053 18.8175 19.3231 19.1477 19.5614 19.4647C18.4177 19.8939 17.5804 20.8647 17.3489 22.0402H8.3222L9.64966 19.0222ZM20.5757 20.5873C21.202 21.1619 21.9032 21.6506 22.6656 22.0402H18.7513C19.01 21.2477 19.7248 20.6798 20.5757 20.5873ZM9.36375 31.8207C9.37056 32.283 8.98934 32.666 8.506 32.6726H7.21258C6.72925 32.6726 6.36845 32.2896 6.36845 31.8207V30.8763C6.63394 30.982 6.92666 31.0282 7.21258 31.0216H9.36375V31.8207ZM26.9271 31.8207C26.9271 32.2896 26.5323 32.6726 26.0489 32.6726H24.7555C24.2722 32.6726 23.8705 32.2962 23.8637 31.8207V31.0216H26.0489C26.3485 31.0282 26.648 30.982 26.9271 30.8763V31.8207ZM26.9271 24.9592V28.8819C26.9271 29.3508 26.5323 29.7008 26.0489 29.7008H7.21258C6.75647 29.714 6.38206 29.364 6.36845 28.9216C6.36845 28.9083 6.36845 28.8951 6.36845 28.8819V25.1375C6.36164 24.6686 6.57267 24.2261 6.93347 23.9157C6.94028 23.9091 6.94028 23.9091 6.94709 23.9025L7.55295 23.361H25.9604L26.3757 23.7771C26.3825 23.7837 26.3961 23.7903 26.4097 23.8035C26.7365 24.1073 26.9271 24.5233 26.9271 24.9592ZM29.6501 14.1221C29.6569 17.1335 27.7985 19.8543 24.9325 21.0232L24.2858 21.294L23.7344 21.0628C20.9229 19.8741 19.0985 17.1797 19.0917 14.2013V10.1729L24.2858 7.40584L29.6501 10.1795V14.1221Z" fill="#00F0FF"/><path d="M27.8373 12.2862C27.5867 11.9511 27.1357 11.9044 26.8279 12.1771L23.4774 15.1456L22.2604 13.5951C22.0027 13.2679 21.5517 13.2289 21.251 13.5094C20.9503 13.7899 20.9145 14.2808 21.1722 14.608L22.8403 16.7351C22.8474 16.7429 22.8546 16.7507 22.8618 16.7585C22.8689 16.7663 22.8761 16.7818 22.8904 16.7896C22.9047 16.7974 22.9119 16.813 22.919 16.8208C22.9262 16.8286 22.9405 16.8364 22.9477 16.8442C22.9548 16.852 22.9691 16.8598 22.9835 16.8675C22.9906 16.8753 23.0049 16.8831 23.0121 16.8909C23.0264 16.8987 23.0336 16.9065 23.0479 16.9143C23.055 16.9221 23.0694 16.9299 23.0765 16.9299C23.0908 16.9377 23.1052 16.9455 23.1195 16.9455C23.1266 16.9533 23.141 16.9533 23.1481 16.961C23.1624 16.9688 23.1768 16.9688 23.1911 16.9766C23.1982 16.9766 23.2125 16.9844 23.2197 16.9844C23.234 16.9844 23.2483 16.9922 23.2627 16.9922C23.2698 16.9922 23.2841 17 23.2913 17C23.3128 17 23.3271 17 23.3486 17C23.3557 17 23.3629 17 23.37 17C23.3915 17 23.413 17 23.4345 17C23.4416 17 23.4416 17 23.4488 17C23.4631 17 23.4846 16.9922 23.4989 16.9922C23.5061 16.9922 23.5132 16.9922 23.5204 16.9844C23.5347 16.9844 23.549 16.9766 23.5633 16.9766C23.5705 16.9766 23.5777 16.9688 23.5848 16.9688C23.5991 16.9688 23.6063 16.961 23.6206 16.9533C23.6278 16.9533 23.6349 16.9455 23.6492 16.9455C23.6564 16.9377 23.6707 16.9377 23.6779 16.9299C23.685 16.9221 23.6922 16.9221 23.7065 16.9143C23.7208 16.9065 23.728 16.9065 23.7352 16.8987C23.7423 16.8909 23.7495 16.8831 23.7638 16.8831C23.771 16.8753 23.7853 16.8675 23.7924 16.8675C23.7996 16.8675 23.8067 16.852 23.8211 16.8442C23.8282 16.8364 23.8354 16.8364 23.8425 16.8286L27.7371 13.3848C28.0449 13.1121 28.0878 12.6212 27.8373 12.2862Z" fill="#00F0FF"/></svg>
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text171'] . '</div>
                                </div>
                            </div>
                            <div class="dashboard_tab_title dashboard_tab_title_active" data-tab="car_register2">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper" style="margin: -10px 0 0;">
                                        <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11.4595 24.9998H8.54054C8.24324 24.9998 8 25.3155 8 25.7015V28.298C8 28.684 8.24324 28.9998 8.54054 28.9998H11.4595C11.7568 28.9998 12 28.684 12 28.298V25.7015C12 25.3155 11.7568 24.9998 11.4595 24.9998ZM10.9189 27.5962H9.08108V26.4033H10.9189V27.5962Z" fill="#00F0FF"/><path d="M25.3243 24.9998H21.6757C21.3041 24.9998 21 25.3155 21 25.7015V28.298C21 28.684 21.3041 28.9998 21.6757 28.9998H25.3243C25.6959 28.9998 26 28.684 26 28.298V25.7015C26 25.3155 25.6959 24.9998 25.3243 24.9998ZM24.6486 27.5962H22.3514V26.4033H24.6486V27.5962Z" fill="#00F0FF"/><path d="M19.3304 26.9998H14.6696C14.3013 26.9998 14 27.4498 14 27.9998C14 28.5498 14.2946 28.9998 14.6696 28.9998H19.3304C19.6987 28.9998 20 28.5498 20 27.9998C20 27.4498 19.6987 26.9998 19.3304 26.9998Z" fill="#00F0FF"/><path d="M19.3304 25H14.6696C14.3013 25 14 25.45 14 26C14 26.55 14.2946 27 14.6696 27H19.3304C19.6987 27 20 26.55 20 26C20 25.45 19.6987 25 19.3304 25Z" fill="#00F0FF"/><path d="M30.6644 9.20212L24.6262 6.07844C24.4219 5.97278 24.1837 5.97278 23.9794 6.08504L18.091 9.20872C17.8731 9.32099 17.7302 9.54552 17.7302 9.78987V14.2145C17.737 15.2183 17.9208 16.2155 18.2816 17.1599H10.4802C9.57478 17.1599 8.75107 17.6882 8.39708 18.5005L6.71563 22.2978L6.03488 22.9252C5.37455 23.4799 4.99333 24.2922 5.00014 25.1441V31.8273C4.98653 33.016 5.96681 33.9868 7.19216 34C7.19896 34 7.19896 34 7.20577 34H8.4992C9.73135 33.9934 10.7252 33.0226 10.7184 31.8273V31.0216H22.4954V31.8207C22.509 33.0226 23.5097 33.9934 24.7487 33.9934H26.0421C27.2743 33.9868 28.275 33.0226 28.2818 31.8207V24.9658C28.2818 24.1667 27.9414 23.4072 27.3492 22.8591L26.8726 22.3704L26.5595 21.7034C29.3165 20.1185 31.0048 17.2391 30.998 14.1287V9.78987C31.0184 9.53892 30.8823 9.31438 30.6644 9.20212ZM9.64966 19.0222C9.79262 18.692 10.1194 18.4807 10.4802 18.4741H18.9079C19.1053 18.8175 19.3231 19.1477 19.5614 19.4647C18.4177 19.8939 17.5804 20.8647 17.3489 22.0402H8.3222L9.64966 19.0222ZM20.5757 20.5873C21.202 21.1619 21.9032 21.6506 22.6656 22.0402H18.7513C19.01 21.2477 19.7248 20.6798 20.5757 20.5873ZM9.36375 31.8207C9.37056 32.283 8.98934 32.666 8.506 32.6726H7.21258C6.72925 32.6726 6.36845 32.2896 6.36845 31.8207V30.8763C6.63394 30.982 6.92666 31.0282 7.21258 31.0216H9.36375V31.8207ZM26.9271 31.8207C26.9271 32.2896 26.5323 32.6726 26.0489 32.6726H24.7555C24.2722 32.6726 23.8705 32.2962 23.8637 31.8207V31.0216H26.0489C26.3485 31.0282 26.648 30.982 26.9271 30.8763V31.8207ZM26.9271 24.9592V28.8819C26.9271 29.3508 26.5323 29.7008 26.0489 29.7008H7.21258C6.75647 29.714 6.38206 29.364 6.36845 28.9216C6.36845 28.9083 6.36845 28.8951 6.36845 28.8819V25.1375C6.36164 24.6686 6.57267 24.2261 6.93347 23.9157C6.94028 23.9091 6.94028 23.9091 6.94709 23.9025L7.55295 23.361H25.9604L26.3757 23.7771C26.3825 23.7837 26.3961 23.7903 26.4097 23.8035C26.7365 24.1073 26.9271 24.5233 26.9271 24.9592ZM29.6501 14.1221C29.6569 17.1335 27.7985 19.8543 24.9325 21.0232L24.2858 21.294L23.7344 21.0628C20.9229 19.8741 19.0985 17.1797 19.0917 14.2013V10.1729L24.2858 7.40584L29.6501 10.1795V14.1221Z" fill="#00F0FF"/><path d="M27.8373 12.2862C27.5867 11.9511 27.1357 11.9044 26.8279 12.1771L23.4774 15.1456L22.2604 13.5951C22.0027 13.2679 21.5517 13.2289 21.251 13.5094C20.9503 13.7899 20.9145 14.2808 21.1722 14.608L22.8403 16.7351C22.8474 16.7429 22.8546 16.7507 22.8618 16.7585C22.8689 16.7663 22.8761 16.7818 22.8904 16.7896C22.9047 16.7974 22.9119 16.813 22.919 16.8208C22.9262 16.8286 22.9405 16.8364 22.9477 16.8442C22.9548 16.852 22.9691 16.8598 22.9835 16.8675C22.9906 16.8753 23.0049 16.8831 23.0121 16.8909C23.0264 16.8987 23.0336 16.9065 23.0479 16.9143C23.055 16.9221 23.0694 16.9299 23.0765 16.9299C23.0908 16.9377 23.1052 16.9455 23.1195 16.9455C23.1266 16.9533 23.141 16.9533 23.1481 16.961C23.1624 16.9688 23.1768 16.9688 23.1911 16.9766C23.1982 16.9766 23.2125 16.9844 23.2197 16.9844C23.234 16.9844 23.2483 16.9922 23.2627 16.9922C23.2698 16.9922 23.2841 17 23.2913 17C23.3128 17 23.3271 17 23.3486 17C23.3557 17 23.3629 17 23.37 17C23.3915 17 23.413 17 23.4345 17C23.4416 17 23.4416 17 23.4488 17C23.4631 17 23.4846 16.9922 23.4989 16.9922C23.5061 16.9922 23.5132 16.9922 23.5204 16.9844C23.5347 16.9844 23.549 16.9766 23.5633 16.9766C23.5705 16.9766 23.5777 16.9688 23.5848 16.9688C23.5991 16.9688 23.6063 16.961 23.6206 16.9533C23.6278 16.9533 23.6349 16.9455 23.6492 16.9455C23.6564 16.9377 23.6707 16.9377 23.6779 16.9299C23.685 16.9221 23.6922 16.9221 23.7065 16.9143C23.7208 16.9065 23.728 16.9065 23.7352 16.8987C23.7423 16.8909 23.7495 16.8831 23.7638 16.8831C23.771 16.8753 23.7853 16.8675 23.7924 16.8675C23.7996 16.8675 23.8067 16.852 23.8211 16.8442C23.8282 16.8364 23.8354 16.8364 23.8425 16.8286L27.7371 13.3848C28.0449 13.1121 28.0878 12.6212 27.8373 12.2862Z" fill="#00F0FF"/></svg>
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text90'] . '</div>
                                </div>
                            </div>';

        $return['back_btn'] = '<div class="dashboard_back_btn" data-back="databases_start_four" data-action-id-back="28" data-database="false">
                                    <img src="/images/back_bg.png" class="back_btn_bg" alt="">
                                    <div class="back_btn_text">' . $translation['text22'] . '</div>
                                </div>';

        $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_start_four" data-tab="tab1"></div>

                            <div class="dashboard_tab_content_item dashboard_tab_content_item_car_register" data-tab="car_register1"></div>

                            <div class="dashboard_tab_content_item dashboard_tab_content_item_car_register_huilov dashboard_tab_content_item_active" data-tab="car_register2">
                                <div class="dashboard_car_register2_inner' . (empty($user_info['car_register_print_text_huilov']) ? ' dashboard_car_register2_inner_bubble' : '') . (empty($team_info['car_register_print_text_huilov']) ? ' dashboard_car_register2_inner_bubble_team' : '') . '">
                                    <div class="dashboard_car_register2_left">
                                        <div class="dashboard_car_register2_title">' . $translation['text91'] . '</div>
                                        <div class="dashboard_car_register2_text_wrapper dashboard_car_register2_text_wrapper1">
                                            <div class="dashboard_car_register2_text_row">
                                                <span class="dashboard_car_register2_text_title dashboard_car_register2_bubble" data-bubble="0">' . (empty($user_info['car_register_print_text_huilov']) ? '' : $translation['text92']) . '</span>
                                                <span class="dashboard_car_register2_text dashboard_car_register2_bubble" data-bubble="1">' . (empty($user_info['car_register_print_text_huilov']) ? '' : $translation['text93']) . '</span>
                                            </div>
                                            <div class="dashboard_car_register2_text_row">
                                                <span class="dashboard_car_register2_text_title dashboard_car_register2_bubble" data-bubble="2">' . (empty($user_info['car_register_print_text_huilov']) ? '' : $translation['text94']) . '</span>
                                                <span class="dashboard_car_register2_text dashboard_car_register2_bubble" data-bubble="3">' . (empty($user_info['car_register_print_text_huilov']) ? '' : $translation['text95']) . '</span>
                                            </div>
                                        </div>

                                        <div class="dashboard_car_register2_title">' . $translation['text96'] . '</div>
                                        <div class="dashboard_car_register2_text_wrapper dashboard_car_register2_text_wrapper2">
                                            <div class="dashboard_car_register2_text_row">
                                                <span class="dashboard_car_register2_text_title dashboard_car_register2_bubble" data-bubble="4">' . (empty($user_info['car_register_print_text_huilov']) ? '' : $translation['text97']) . '</span>
                                                <span class="dashboard_car_register2_text dashboard_car_register2_bubble" data-bubble="5">' . (empty($user_info['car_register_print_text_huilov']) ? '' : $translation['text98']) . '</span>
                                            </div>
                                            <div class="dashboard_car_register2_text_row">
                                                <span class="dashboard_car_register2_text_title dashboard_car_register2_bubble" data-bubble="6">' . (empty($user_info['car_register_print_text_huilov']) ? '' : $translation['text99']) . '</span>
                                                <span class="dashboard_car_register2_text dashboard_car_register2_bubble" data-bubble="7">' . (empty($user_info['car_register_print_text_huilov']) ? '' : $translation['text100']) . '</span>
                                            </div>
                                            <div class="dashboard_car_register2_text_row">
                                                <span class="dashboard_car_register2_text_title dashboard_car_register2_bubble" data-bubble="8">' . (empty($user_info['car_register_print_text_huilov']) ? '' : $translation['text101']) . '</span>
                                                <span class="dashboard_car_register2_text dashboard_car_register2_bubble" data-bubble="9">' . (empty($user_info['car_register_print_text_huilov']) ? '' : $translation['text102']) . '</span>
                                            </div>
                                        </div>

                                        <div class="dashboard_car_register2_text_wrapper dashboard_car_register2_text_wrapper3">
                                            <div class="dashboard_car_register2_text_row">
                                                <span class="dashboard_car_register2_text_title dashboard_car_register2_bubble" data-bubble="10">' . (empty($user_info['car_register_print_text_huilov']) ? '' : $translation['text103']) . '</span>
                                                <span class="dashboard_car_register2_text dashboard_car_register2_bubble" data-bubble="11">' . (empty($user_info['car_register_print_text_huilov']) ? '' : $translation['text104']) . '</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dashboard_car_register2_right">
                                        <div class="dashboard_car_register2_title">' . $translation['text105'] . '</div>
                                        <div class="dashboard_car_register2_slider_wrapper">
                                            <div class="dashboard_car_register2_slider">
                                                <div><img src="/images/slider_stalin_car/stalin_car1.png" alt=""></div>
                                                <div><img src="/images/slider_stalin_car/stalin_car2.png" alt=""></div>
                                                <div><img src="/images/slider_stalin_car/stalin_car3.png" alt=""></div>
                                            </div>
                                            <div class="dashboard_car_register2_slider_arrows">
                                                <div class="dashboard_car_register2_slider_arrow_left"><svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.62094 13.8289C7.84901 13.6009 7.84901 13.2322 7.62094 13.0041L1.61682 6.99998L7.62094 0.995859C7.84901 0.767789 7.84901 0.399121 7.62094 0.171052C7.39287 -0.0570174 7.0242 -0.0570174 6.79613 0.171052L0.379595 6.58759C0.265847 6.70134 0.208673 6.85066 0.208673 7.00001C0.208673 7.14936 0.265847 7.29868 0.379595 7.41242L6.79613 13.829C7.0242 14.057 7.39287 14.057 7.62094 13.8289Z" fill="white"/></svg></div>
                                                <div class="dashboard_car_register2_slider_arrow_number">1</div>
                                                <div class="dashboard_car_register2_slider_arrow_right"><svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.37906 0.171063C0.150991 0.399133 0.150991 0.7678 0.37906 0.99587L6.38318 7.00002L0.37906 13.0041C0.15099 13.2322 0.15099 13.6009 0.37906 13.8289C0.607129 14.057 0.975797 14.057 1.20387 13.8289L7.6204 7.41241C7.73415 7.29866 7.79133 7.14934 7.79133 6.99999C7.79133 6.85064 7.73415 6.70132 7.6204 6.58758L1.20387 0.171036C0.975797 -0.0570068 0.607129 -0.0570059 0.37906 0.171063Z" fill="white"/></svg></div>
                                            </div>
                                            <div class="dashboard_car_register2_slider_border_right_bottom">
                                                <svg width="57" height="84" viewBox="0 0 57 84" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M47.9043 8.02548L56.3936 0.535032L57 0V67.949L38.8085 84H0L8.48936 76.5096H35.1702L47.9043 65.2739V8.02548Z" fill="#00F0FF"/></svg>
                                            </div>
                                            <div class="dashboard_car_register2_slider_picture_counter">
                                                <div class="dashboard_car_register2_slider_picture_counter_bg">
                                                    <svg width="107" height="57" viewBox="0 0 107 57" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.5 25.5L0.5 0L107 -8.40556e-07L107 18.1915L107 57L106.51 32.5L8.5 25.5Z" fill="#00F0FF"/></svg>
                                                </div>
                                                <div class="dashboard_car_register2_slider_picture_text">
                                                    ' . $translation['text106'] . ': <span>1</span>/3
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>';

        // при первом запуске
        if (empty($team_info['car_register_print_text_huilov'])) {
            // обновляем значение, что текст напечатан. Повторно скрипт НЕ закускается
            $sql = "UPDATE `teams` SET `car_register_print_text_huilov` = {?} WHERE `id` = {?}";
            $this->db->query($sql, [1, $team_id]);

            // обновляем подсказки
            // список открытых
            $active_hints = [];

            // список доступных
            $list_hints = [];

            $hints_by_step = $this->getHintsByStep('car_register_huilov', $lang_id);
            if ($hints_by_step) {
                foreach ($hints_by_step as $hint) {
                    $list_hints[] = $hint['id'];
                }
            }

            // сохраняем обновленный список подсказок
            $sql = "UPDATE `teams` SET `active_hints` = {?}, `list_hints` = {?}, `list_hints_title_lang_var` = {?}, `list_hints_text_lang_var` = {?} WHERE `id` = {?}";
            $this->db->query($sql, [json_encode($active_hints, JSON_UNESCAPED_UNICODE), json_encode($list_hints, JSON_UNESCAPED_UNICODE), 'text44', 'text45', $team_id]);
        }

        // обновляем, что текст напечатан для этого юзера
        if (isset($_COOKIE['hash'])) {
            $sql = "UPDATE `users` SET `car_register_print_text_huilov` = {?} WHERE `team_id` = {?} AND `hash` = {?}";
            $this->db->query($sql, [1, $team_id, $_COOKIE['hash']]);
        } else {
            $sql = "UPDATE `users` SET `car_register_print_text_huilov` = {?} WHERE `team_id` = {?} AND `ip` = {?}";
            $this->db->query($sql, [1, $team_id, $this->getIp()]);
        }

        // возвращаем также массив для отпечатки текста на разных языках
        $return['error_lang'] = [];

        $sql = "SELECT `id`, `lang_abbr` FROM `langs` WHERE `status` = {?}";
        $langs = $this->db->select($sql, [1]);
        if ($langs) {
            foreach ($langs as $lang_item) {
                $translation = $this->getWordsByPage('game', $lang_item['id']);

                $return['error_lang'][$lang_item['lang_abbr']] = [
                    'text92' => $translation['text92'],
                    'text93' => $translation['text93'],
                    'text94' => $translation['text94'],
                    'text95' => $translation['text95'],
                    'text97' => $translation['text97'],
                    'text98' => $translation['text98'],
                    'text99' => $translation['text99'],
                    'text100' => $translation['text100'],
                    'text101' => $translation['text101'],
                    'text102' => $translation['text102'],
                    'text103' => $translation['text103'],
                    'text104' => $translation['text104']
                ];
            }
        }

        return $return;
    }

    // databases - загрузить Personal Files. Первый экран
    private function uploadDatabasesPersonalFiles($lang_id, $team_id)
    {
        $translation = $this->getWordsByPage('game', $lang_id);

        $team_info = $this->teamInfo($team_id);

        $return = [];

        $return['titles'] = '<div class="dashboard_tab_title dashboard_tab_title_can_click" data-tab="tab1" data-step="databases_start_four" data-action-id="28" data-database="false">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper">
                                        <svg width="19" height="21" viewBox="0 0 19 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 0H19V3L17.25 5H0V2L1.75 0ZM1.73684 2H3V3.2L2.26316 4H1V2.8L1.73684 2ZM6 2H4.73684L4 2.8V4H5.26316L6 3.2V2ZM7.73684 2H9V3.2L8.26316 4H7V2.8L7.73684 2ZM17 2H10.7368L10 2.8V4H16.2632L17 3.2V2Z" fill="#00F0FF"/><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 8H19V11L17.25 13H0V10L1.75 8ZM1.73684 10H3V11.2L2.26316 12H1V10.8L1.73684 10ZM6 10H4.73684L4 10.8V12H5.26316L6 11.2V10ZM7.73684 10H9V11.2L8.26316 12H7V10.8L7.73684 10ZM17 10H10.7368L10 10.8V12H16.2632L17 11.2V10Z" fill="#00F0FF"/><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 16H19V19L17.25 21H0V18L1.75 16ZM1.73684 18H3V19.2L2.26316 20H1V18.8L1.73684 18ZM6 18H4.73684L4 18.8V20H5.26316L6 19.2V18ZM7.73684 18H9V19.2L8.26316 20H7V18.8L7.73684 18ZM17 18H10.7368L10 18.8V20H16.2632L17 19.2V18Z" fill="#00F0FF"/><rect width="15" height="1" transform="matrix(1 0 0 -1 2 7)" fill="#00F0FF"/><rect width="15" height="1" transform="matrix(1 0 0 -1 2 15)" fill="#00F0FF"/></svg>
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text13'] . '</div>
                                </div>
                            </div>
                            <div class="dashboard_tab_title dashboard_tab_title_active" data-tab="personal_files1">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper" style="margin: -3px 0 0;">
                                        <img src="/images/icons/icon_tab_personal_files.png" alt="">
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text170'] . '</div>
                                </div>
                            </div>';

        $return['back_btn'] = '<div class="dashboard_back_btn" data-back="databases_start_four" data-action-id-back="28" data-database="false">
                                    <img src="/images/back_bg.png" class="back_btn_bg" alt="">
                                    <div class="back_btn_text">' . $translation['text22'] . '</div>
                                </div>';

        $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_start_four" data-tab="tab1"></div>

                            <div class="dashboard_tab_content_item dashboard_tab_content_item_personal_files dashboard_tab_content_item_active" data-tab="personal_files1">
                                <div class="dashboard_personal_files1_inner">
                                    <div class="dashboard_personal_files1_title">' . $translation['text107'] . '</div>
                                    <div class="dashboard_personal_files1_categories">
                                        <div class="dashboard_personal_files1_category dashboard_personal_files1_category_private_individuals">
                                            <div class="dashboard_personal_files1_category_top"></div>
                                            <div class="dashboard_personal_files1_category_bottom">
                                                <div class="dashboard_personal_files1_category_title">' . $translation['text108'] . '</div>
                                                <div class="dashboard_personal_files1_category_img_wrapper">
                                                    <img src="/images/icons/icon_personal_files_private_individual.png" alt="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dashboard_personal_files1_category dashboard_personal_files1_category_ceo_database">
                                            <div class="dashboard_personal_files1_category_top"></div>
                                            <div class="dashboard_personal_files1_category_bottom">
                                                <div class="dashboard_personal_files1_category_title">' . $translation['text109'] . '</div>
                                                <div class="dashboard_personal_files1_category_img_wrapper">
                                                    <img src="/images/icons/icon_personal_files_ceo_database.png" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>';

        return $return;
    }

    // databases - загрузить Personal Files. Второй экран. Private Individual
    private function uploadDatabasesPersonalFilesPrivateIndividual($lang_id, $team_id)
    {
        $translation = $this->getWordsByPage('game', $lang_id);

        $team_info = $this->teamInfo($team_id);

        $return = [];

        $return['titles'] = '<div class="dashboard_tab_title dashboard_tab_title_can_click" data-tab="tab1" data-step="databases_start_four" data-action-id="28" data-database="false">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper">
                                        <svg width="19" height="21" viewBox="0 0 19 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 0H19V3L17.25 5H0V2L1.75 0ZM1.73684 2H3V3.2L2.26316 4H1V2.8L1.73684 2ZM6 2H4.73684L4 2.8V4H5.26316L6 3.2V2ZM7.73684 2H9V3.2L8.26316 4H7V2.8L7.73684 2ZM17 2H10.7368L10 2.8V4H16.2632L17 3.2V2Z" fill="#00F0FF"/><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 8H19V11L17.25 13H0V10L1.75 8ZM1.73684 10H3V11.2L2.26316 12H1V10.8L1.73684 10ZM6 10H4.73684L4 10.8V12H5.26316L6 11.2V10ZM7.73684 10H9V11.2L8.26316 12H7V10.8L7.73684 10ZM17 10H10.7368L10 10.8V12H16.2632L17 11.2V10Z" fill="#00F0FF"/><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 16H19V19L17.25 21H0V18L1.75 16ZM1.73684 18H3V19.2L2.26316 20H1V18.8L1.73684 18ZM6 18H4.73684L4 18.8V20H5.26316L6 19.2V18ZM7.73684 18H9V19.2L8.26316 20H7V18.8L7.73684 18ZM17 18H10.7368L10 18.8V20H16.2632L17 19.2V18Z" fill="#00F0FF"/><rect width="15" height="1" transform="matrix(1 0 0 -1 2 7)" fill="#00F0FF"/><rect width="15" height="1" transform="matrix(1 0 0 -1 2 15)" fill="#00F0FF"/></svg>
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text13'] . '</div>
                                </div>
                            </div>
                            <div class="dashboard_tab_title dashboard_tab_title_can_click" data-tab="personal_files1" data-step="databases_start_four_inner_first_personal_files" data-action-id="32" data-database="personal_files">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper" style="margin: -3px 0 0;">
                                        <img src="/images/icons/icon_tab_personal_files.png" alt="">
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text170'] . '</div>
                                </div>
                            </div>

                            <div class="dashboard_tab_title dashboard_tab_title_active" data-tab="personal_files2">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper" style="margin: -1px 0 0;">
                                        <img src="/images/icons/icon_tab_personal_files_private_individual.png" alt="">
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text108'] . '</div>
                                </div>
                            </div>';

        $return['back_btn'] = '<div class="dashboard_back_btn" data-back="databases_start_four_inner_first_personal_files" data-action-id-back="32" data-database="personal_files">
                                    <img src="/images/back_bg.png" class="back_btn_bg" alt="">
                                    <div class="back_btn_text">' . $translation['text22'] . '</div>
                                </div>';

        $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_start_four" data-tab="tab1"></div>

                            <div class="dashboard_tab_content_item dashboard_tab_content_item_start_four" data-tab="personal_files1"></div>
        
                            <div class="dashboard_tab_content_item dashboard_tab_content_item_start_four dashboard_tab_content_item_active" data-tab="personal_files2">
                                <div class="dashboard_personal_files2_private_individuals_inner">
                                    <div class="dashboard_personal_files2_private_individuals_img_wrapper">
                                        <img src="/images/icons/icon_personal_files_private_individual.png" alt="">
                                    </div>
                                    <div class="dashboard_personal_files2_private_individuals_title">' . $translation['text108'] . '</div>
                                    <div class="dashboard_personal_files2_private_individuals_text">' . $translation['text110'] . '</div>
                                    <div class="dashboard_personal_files2_private_individuals_inputs">
                                        <div class="dashboard_personal_files2_private_individuals_input_wrapper dashboard_personal_files2_private_individuals_input_wrapper_firstname">
                                            <div class="dashboard_personal_files2_private_individuals_input_border_left"></div>
                                            <input type="text" placeholder="' . $translation['text111'] . '" value="" autocomplete="off">
                                            <div class="dashboard_personal_files2_private_individuals_firstname_error error_text_database_car_register">' . $translation['text86'] . '</div>
                                        </div>
                                        <div class="dashboard_personal_files2_private_individuals_input_wrapper dashboard_personal_files2_private_individuals_input_wrapper_lastname">
                                            <div class="dashboard_personal_files2_private_individuals_input_border_right"></div>
                                            <input type="text" placeholder="' . $translation['text112'] . '" value="" autocomplete="off">
                                            <div class="dashboard_personal_files2_private_individuals_lastname_error error_text_database_car_register">' . $translation['text86'] . '</div>
                                        </div>
                                    </div>
                                    <div class="btn_wrapper btn_wrapper_blue dashboard_personal_files2_private_individuals_search">
                                        <div class="btn btn_blue">
                                            <span>' . $translation['text66'] . '</span>
                                        </div>
                                        <div class="btn_border_top"></div>
                                        <div class="btn_border_bottom"></div>
                                        <div class="btn_border_left"></div>
                                        <div class="btn_border_left_arcle"></div>
                                        <div class="btn_border_right"></div>
                                        <div class="btn_border_right_arcle"></div>
                                        <div class="btn_bg_top_line"></div>
                                        <div class="btn_bg_bottom_line"></div>
                                        <div class="btn_bg_triangle_left"></div>
                                        <div class="btn_bg_triangle_right"></div>
                                        <div class="btn_circles_top">
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                        </div>
                                        <div class="btn_circles_bottom">
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>';

        return $return;
    }

    // databases - загрузить Personal Files. Второй экран. Private Individual - Huilov
    private function uploadDatabasesPersonalFilesPrivateIndividualHuilov($lang_id, $team_id)
    {
        $translation = $this->getWordsByPage('game', $lang_id);

        $team_info = $this->teamInfo($team_id);

        if (isset($_COOKIE['hash'])) {
            $sql = "SELECT `private_individuals_print_text_huilov` FROM `users` WHERE `team_id` = {?} AND `hash` = {?} LIMIT 1";
            $user_info = $this->db->selectRow($sql, [$team_id, $_COOKIE['hash']]);
        } else {
            $sql = "SELECT `private_individuals_print_text_huilov` FROM `users` WHERE `team_id` = {?} AND `ip` = {?} LIMIT 1";
            $user_info = $this->db->selectRow($sql, [$team_id, $this->getIp()]);
        }

        $return = [];

        $return['titles'] = '<div class="dashboard_tab_title dashboard_tab_title_can_click" data-tab="tab1" data-step="databases_start_four" data-action-id="28" data-database="false">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper">
                                        <svg width="19" height="21" viewBox="0 0 19 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 0H19V3L17.25 5H0V2L1.75 0ZM1.73684 2H3V3.2L2.26316 4H1V2.8L1.73684 2ZM6 2H4.73684L4 2.8V4H5.26316L6 3.2V2ZM7.73684 2H9V3.2L8.26316 4H7V2.8L7.73684 2ZM17 2H10.7368L10 2.8V4H16.2632L17 3.2V2Z" fill="#00F0FF"/><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 8H19V11L17.25 13H0V10L1.75 8ZM1.73684 10H3V11.2L2.26316 12H1V10.8L1.73684 10ZM6 10H4.73684L4 10.8V12H5.26316L6 11.2V10ZM7.73684 10H9V11.2L8.26316 12H7V10.8L7.73684 10ZM17 10H10.7368L10 10.8V12H16.2632L17 11.2V10Z" fill="#00F0FF"/><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 16H19V19L17.25 21H0V18L1.75 16ZM1.73684 18H3V19.2L2.26316 20H1V18.8L1.73684 18ZM6 18H4.73684L4 18.8V20H5.26316L6 19.2V18ZM7.73684 18H9V19.2L8.26316 20H7V18.8L7.73684 18ZM17 18H10.7368L10 18.8V20H16.2632L17 19.2V18Z" fill="#00F0FF"/><rect width="15" height="1" transform="matrix(1 0 0 -1 2 7)" fill="#00F0FF"/><rect width="15" height="1" transform="matrix(1 0 0 -1 2 15)" fill="#00F0FF"/></svg>
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text13'] . '</div>
                                </div>
                            </div>
                            <div class="dashboard_tab_title dashboard_tab_title_can_click" data-tab="personal_files1" data-step="databases_start_four_inner_first_personal_files" data-action-id="32" data-database="personal_files">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper" style="margin: -3px 0 0;">
                                        <img src="/images/icons/icon_tab_personal_files.png" alt="">
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text170'] . '</div>
                                </div>
                            </div>

                            <div class="dashboard_tab_title dashboard_tab_title_active" data-tab="personal_files2">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper" style="margin: -1px 0 0;">
                                        <img src="/images/icons/icon_tab_personal_files_private_individual.png" alt="">
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text104'] . '</div>
                                </div>
                            </div>';

        $return['back_btn'] = '<div class="dashboard_back_btn" data-back="databases_start_four_inner_first_personal_files" data-action-id-back="32" data-database="personal_files">
                                    <img src="/images/back_bg.png" class="back_btn_bg" alt="">
                                    <div class="back_btn_text">' . $translation['text22'] . '</div>
                                </div>';

        $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_start_four" data-tab="tab1"></div>

                            <div class="dashboard_tab_content_item dashboard_tab_content_item_start_four" data-tab="personal_files1"></div>
        
                            <div class="dashboard_tab_content_item dashboard_tab_content_item_start_four dashboard_tab_content_item_active" data-tab="personal_files2">
                                <div class="dashboard_personal_files2_private_individuals_huilov_inner' . (empty($user_info['private_individuals_print_text_huilov']) ? ' dashboard_personal_files2_private_individuals_huilov_inner_bubble' : '') . (empty($team_info['private_individuals_print_text_huilov']) ? ' dashboard_personal_files2_private_individuals_huilov_inner_bubble_team' : '') . '"">
                                    <div class="dashboard_personal_files2_private_individuals_huilov_right_bg_line">
                                        <svg width="868" height="418" viewBox="0 0 868 418" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.5 1H790L809.5 20.5V405L822 417.5H867.5" stroke="#FF004E"/></svg>
                                    </div>
                                    <div class="dashboard_personal_files2_private_individuals_huilov_left">
                                        <div class="dashboard_personal_files2_private_individuals_huilov_images">
                                            <div class="dashboard_personal_files2_private_individuals_huilov_images_inner">
                                                <img src="/images/icons/icon_huilov_hand.png" alt="">
                                                <img src="/images/icons/icon_huilov_img2.png" alt="">
                                                <img src="/images/icons/icon_huilov_diagram.png" alt="">
                                                <div class="dashboard_personal_files2_private_individuals_huilov_images_text">
                                                    <span>' . $translation['text113'] . '</span>
                                                    <span>' . $translation['text114'] . '</span>
                                                </div>
                                            </div>
                                            <div class="dashboard_personal_files2_private_individuals_huilov_images_bg_right"><svg width="9" height="396" viewBox="0 0 9 396" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 1H8.5V395.5H3H0" stroke="#FF004E"/></svg></div>
                                            <div class="dashboard_personal_files2_private_individuals_huilov_images_bg_bottom"></div>
                                        </div>
                                        <div class="dashboard_personal_files2_private_individuals_huilov_main_image">
                                            <img src="/images/huilov_photo.jpg" class="huilov_main_image" alt="">
                                            <div class="dashboard_personal_files2_private_individuals_huilov_main_image_diagram">
                                                <img src="/images/icons/icon_huilov_main_image_diagram.png" alt="">
                                            </div>
                                            <img src="/images/gifs/face_anim.gif" class="huilov_face_anim" alt="">
                                        </div>
                                    </div>
                                    <div class="dashboard_personal_files2_private_individuals_huilov_right">
                                        <div class="dashboard_personal_files2_private_individuals_huilov_data_row">
                                            <div class="dashboard_personal_files2_private_individuals_huilov_label">' . $translation['text115'] . '</div>
                                            <div class="dashboard_personal_files2_private_individuals_huilov_input">
                                                <span class="dots_top"></span><span class="dots_bottom_left"></span><span class="dots_bottom_right"></span>
                                                <span class="private_individuals_huilov_text" data-bubble="0"><span>' . (empty($user_info['private_individuals_print_text_huilov']) ? '' : $translation['text116']) . '</span></span>
                                            </div>
                                        </div>
                                        <div class="dashboard_personal_files2_private_individuals_huilov_data_row dashboard_personal_files2_private_individuals_huilov_data_row_lastname">
                                            <div class="dashboard_personal_files2_private_individuals_huilov_label">' . $translation['text117'] . '</div>
                                            <div class="dashboard_personal_files2_private_individuals_huilov_input">
                                                <span class="dots_top"></span><span class="dots_bottom_left"></span><span class="dots_bottom_right"></span>
                                                <span class="private_individuals_huilov_text" data-bubble="1"><span>' . (empty($user_info['private_individuals_print_text_huilov']) ? '' : $translation['text118']) . '</span></span>
                                            </div>
                                        </div>
                                        <div class="dashboard_personal_files2_private_individuals_huilov_data_row">
                                            <div class="dashboard_personal_files2_private_individuals_huilov_label">' . $translation['text119'] . '</div>
                                            <div class="dashboard_personal_files2_private_individuals_huilov_input">
                                                <span class="dots_top"></span><span class="dots_bottom_left"></span><span class="dots_bottom_right"></span>
                                                <span class="private_individuals_huilov_text" data-bubble="2"><span>' . (empty($user_info['private_individuals_print_text_huilov']) ? '' : $translation['text120']) . '</span></span>
                                            </div>
                                        </div>
                                        <div class="dashboard_personal_files2_private_individuals_huilov_data_row">
                                            <div class="dashboard_personal_files2_private_individuals_huilov_label">' . $translation['text121'] . '</div>
                                            <div class="dashboard_personal_files2_private_individuals_huilov_input">
                                                <span class="dots_top"></span><span class="dots_bottom_left"></span><span class="dots_bottom_right"></span>
                                                <span class="private_individuals_huilov_text" data-bubble="3"><span>' . (empty($user_info['private_individuals_print_text_huilov']) ? '' : $translation['text122']) . '</span></span>
                                            </div>
                                        </div>
                                        <div class="dashboard_personal_files2_private_individuals_huilov_data_row">
                                            <div class="dashboard_personal_files2_private_individuals_huilov_label">' . $translation['text123'] . '</div>
                                            <div class="dashboard_personal_files2_private_individuals_huilov_input">
                                                <span class="dots_top"></span><span class="dots_bottom_left"></span><span class="dots_bottom_right"></span>
                                                <span class="private_individuals_huilov_text" data-bubble="4"><span>' . (empty($user_info['private_individuals_print_text_huilov']) ? '' : $translation['text124']) . '</span></span>
                                            </div>
                                        </div>
                                        <div class="dashboard_personal_files2_private_individuals_huilov_data_row">
                                            <div class="dashboard_personal_files2_private_individuals_huilov_label">' . $translation['text125'] . '</div>
                                            <div class="dashboard_personal_files2_private_individuals_huilov_input">
                                                <span class="dots_top"></span><span class="dots_bottom_left"></span><span class="dots_bottom_right"></span>
                                                <span class="private_individuals_huilov_text" data-bubble="5"><span>' . (empty($user_info['private_individuals_print_text_huilov']) ? '' : $translation['text126']) . '</span></span>
                                            </div>
                                        </div>
                                        <div class="dashboard_personal_files2_private_individuals_huilov_data_row">
                                            <div class="dashboard_personal_files2_private_individuals_huilov_label">' . $translation['text127'] . '</div>
                                            <div class="dashboard_personal_files2_private_individuals_huilov_input">
                                                <span class="dots_top"></span><span class="dots_bottom_left"></span><span class="dots_bottom_right"></span>
                                                <span class="private_individuals_huilov_text" data-bubble="6"><span>' . (empty($user_info['private_individuals_print_text_huilov']) ? '' : $translation['text128']) . '</span></span>
                                            </div>
                                        </div>
                                        <div class="dashboard_personal_files2_private_individuals_huilov_data_row">
                                            <div class="dashboard_personal_files2_private_individuals_huilov_label">' . $translation['text129'] . '</div>
                                            <div class="dashboard_personal_files2_private_individuals_huilov_input" style="font-size: 18px; line-height: 22px; padding: 18px 0 0;">
                                                <span class="dots_top"></span><span class="dots_bottom_left"></span><span class="dots_bottom_right"></span>
                                                <span class="private_individuals_huilov_text" data-bubble="7"><span>' . (empty($user_info['private_individuals_print_text_huilov']) ? '' : $translation['text130']) . '</span></span>
                                            </div>
                                        </div>
                                        <div class="dashboard_personal_files2_private_individuals_huilov_data_row">
                                            <div class="dashboard_personal_files2_private_individuals_huilov_label" style="opacity: 0; visibility: hidden;">' . $translation['text129'] . '</div>
                                            <div class="dashboard_personal_files2_private_individuals_huilov_input" style="font-size: 18px; line-height: 22px; padding: 8px 0 0;">
                                                <span class="dots_top"></span><span class="dots_bottom_left"></span><span class="dots_bottom_right"></span>
                                                <span class="private_individuals_huilov_text" data-bubble="8"><span>' . (empty($user_info['private_individuals_print_text_huilov']) ? '' : $translation['text131']) . '</span></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>';

        // при первом запуске
        if (empty($team_info['private_individuals_print_text_huilov'])) {
            // обновляем значение, что текст напечатан. Повторно скрипт НЕ закускается
            $sql = "UPDATE `teams` SET `private_individuals_print_text_huilov` = {?} WHERE `id` = {?}";
            $this->db->query($sql, [1, $team_id]);

            // обновляем подсказки
            // список открытых
            $active_hints = [];

            // список доступных
            $list_hints = [];

            $hints_by_step = $this->getHintsByStep('private_individuals_huilov', $lang_id);
            if ($hints_by_step) {
                foreach ($hints_by_step as $hint) {
                    $list_hints[] = $hint['id'];
                }
            }

            // сохраняем обновленный список подсказок
            $sql = "UPDATE `teams` SET `active_hints` = {?}, `list_hints` = {?}, `list_hints_title_lang_var` = {?}, `list_hints_text_lang_var` = {?} WHERE `id` = {?}";
            $this->db->query($sql, [json_encode($active_hints, JSON_UNESCAPED_UNICODE), json_encode($list_hints, JSON_UNESCAPED_UNICODE), 'text44', 'text45', $team_id]);
        }

        // обновляем, что текст напечатан для этого юзера
        if (isset($_COOKIE['hash'])) {
            $sql = "UPDATE `users` SET `private_individuals_print_text_huilov` = {?} WHERE `team_id` = {?} AND `hash` = {?}";
            $this->db->query($sql, [1, $team_id, $_COOKIE['hash']]);
        } else {
            $sql = "UPDATE `users` SET `private_individuals_print_text_huilov` = {?} WHERE `team_id` = {?} AND `ip` = {?}";
            $this->db->query($sql, [1, $team_id, $this->getIp()]);
        }

        // возвращаем также массив для отпечатки текста на разных языках
        $return['error_lang'] = [];

        $sql = "SELECT `id`, `lang_abbr` FROM `langs` WHERE `status` = {?}";
        $langs = $this->db->select($sql, [1]);
        if ($langs) {
            foreach ($langs as $lang_item) {
                $translation = $this->getWordsByPage('game', $lang_item['id']);

                $return['error_lang'][$lang_item['lang_abbr']] = [
                    'text116' => $translation['text116'],
                    'text118' => $translation['text118'],
                    'text120' => $translation['text120'],
                    'text122' => $translation['text122'],
                    'text124' => $translation['text124'],
                    'text126' => $translation['text126'],
                    'text128' => $translation['text128'],
                    'text130' => $translation['text130'],
                    'text131' => $translation['text131']
                ];
            }
        }

        return $return;
    }

    // databases - загрузить Personal Files. Второй экран. Ceo Database
    private function uploadDatabasesPersonalFilesCeoDatabase($lang_id, $team_id)
    {
        $translation = $this->getWordsByPage('game', $lang_id);

        $team_info = $this->teamInfo($team_id);

        $return = [];

        $return['titles'] = '<div class="dashboard_tab_title dashboard_tab_title_can_click" data-tab="tab1" data-step="databases_start_four" data-action-id="28" data-database="false">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper">
                                        <svg width="19" height="21" viewBox="0 0 19 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 0H19V3L17.25 5H0V2L1.75 0ZM1.73684 2H3V3.2L2.26316 4H1V2.8L1.73684 2ZM6 2H4.73684L4 2.8V4H5.26316L6 3.2V2ZM7.73684 2H9V3.2L8.26316 4H7V2.8L7.73684 2ZM17 2H10.7368L10 2.8V4H16.2632L17 3.2V2Z" fill="#00F0FF"/><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 8H19V11L17.25 13H0V10L1.75 8ZM1.73684 10H3V11.2L2.26316 12H1V10.8L1.73684 10ZM6 10H4.73684L4 10.8V12H5.26316L6 11.2V10ZM7.73684 10H9V11.2L8.26316 12H7V10.8L7.73684 10ZM17 10H10.7368L10 10.8V12H16.2632L17 11.2V10Z" fill="#00F0FF"/><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 16H19V19L17.25 21H0V18L1.75 16ZM1.73684 18H3V19.2L2.26316 20H1V18.8L1.73684 18ZM6 18H4.73684L4 18.8V20H5.26316L6 19.2V18ZM7.73684 18H9V19.2L8.26316 20H7V18.8L7.73684 18ZM17 18H10.7368L10 18.8V20H16.2632L17 19.2V18Z" fill="#00F0FF"/><rect width="15" height="1" transform="matrix(1 0 0 -1 2 7)" fill="#00F0FF"/><rect width="15" height="1" transform="matrix(1 0 0 -1 2 15)" fill="#00F0FF"/></svg>
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text13'] . '</div>
                                </div>
                            </div>
                            <div class="dashboard_tab_title dashboard_tab_title_can_click" data-tab="personal_files1" data-step="databases_start_four_inner_first_personal_files" data-action-id="32" data-database="personal_files">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper" style="margin: -3px 0 0;">
                                        <img src="/images/icons/icon_tab_personal_files.png" alt="">
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text170'] . '</div>
                                </div>
                            </div>

                            <div class="dashboard_tab_title dashboard_tab_title_active" data-tab="personal_files2">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper" style="margin: -2px 0 0;">
                                        <img src="/images/icons/icon_tab_ceo_database.png" alt="">
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text109'] . '</div>
                                </div>
                            </div>';

        $return['back_btn'] = '<div class="dashboard_back_btn" data-back="databases_start_four_inner_first_personal_files" data-action-id-back="32" data-database="personal_files">
                                    <img src="/images/back_bg.png" class="back_btn_bg" alt="">
                                    <div class="back_btn_text">' . $translation['text22'] . '</div>
                                </div>';

        $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_start_four" data-tab="tab1"></div>

                            <div class="dashboard_tab_content_item dashboard_tab_content_item_start_four" data-tab="personal_files1"></div>
        
                            <div class="dashboard_tab_content_item dashboard_tab_content_item_start_four dashboard_tab_content_item_active" data-tab="personal_files2">
                                <div class="dashboard_personal_files2_private_individuals_inner">
                                    <div class="dashboard_personal_files2_private_individuals_img_wrapper">
                                        <img src="/images/icons/icon_personal_files_ceo_database.png" alt="">
                                    </div>
                                    <div class="dashboard_personal_files2_private_individuals_title">' . $translation['text109'] . '</div>
                                    <div class="dashboard_personal_files2_private_individuals_text">' . $translation['text110'] . '</div>
                                    <div class="dashboard_personal_files2_private_individuals_inputs">
                                        <div class="dashboard_personal_files2_private_individuals_input_wrapper dashboard_personal_files2_private_individuals_input_wrapper_firstname">
                                            <div class="dashboard_personal_files2_private_individuals_input_border_left"></div>
                                            <input type="text" placeholder="' . $translation['text111'] . '" value="" autocomplete="off">
                                            <div class="dashboard_personal_files2_private_individuals_firstname_error error_text_database_car_register">' . $translation['text86'] . '</div>
                                        </div>
                                        <div class="dashboard_personal_files2_private_individuals_input_wrapper dashboard_personal_files2_private_individuals_input_wrapper_lastname">
                                            <div class="dashboard_personal_files2_private_individuals_input_border_right"></div>
                                            <input type="text" placeholder="' . $translation['text112'] . '" value="" autocomplete="off">
                                            <div class="dashboard_personal_files2_private_individuals_lastname_error error_text_database_car_register">' . $translation['text86'] . '</div>
                                        </div>
                                    </div>
                                    <div class="btn_wrapper btn_wrapper_blue dashboard_personal_files2_ceo_database_search">
                                        <div class="btn btn_blue">
                                            <span>' . $translation['text66'] . '</span>
                                        </div>
                                        <div class="btn_border_top"></div>
                                        <div class="btn_border_bottom"></div>
                                        <div class="btn_border_left"></div>
                                        <div class="btn_border_left_arcle"></div>
                                        <div class="btn_border_right"></div>
                                        <div class="btn_border_right_arcle"></div>
                                        <div class="btn_bg_top_line"></div>
                                        <div class="btn_bg_bottom_line"></div>
                                        <div class="btn_bg_triangle_left"></div>
                                        <div class="btn_bg_triangle_right"></div>
                                        <div class="btn_circles_top">
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                        </div>
                                        <div class="btn_circles_bottom">
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>';

        return $return;
    }

    // databases - загрузить Personal Files. Второй экран. Ceo Database - Rod
    private function uploadDatabasesPersonalFilesCeoDatabaseRod($lang_id, $team_id)
    {
        $translation = $this->getWordsByPage('game', $lang_id);

        $team_info = $this->teamInfo($team_id);

        if (isset($_COOKIE['hash'])) {
            $sql = "SELECT `ceo_database_print_text_rod` FROM `users` WHERE `team_id` = {?} AND `hash` = {?} LIMIT 1";
            $user_info = $this->db->selectRow($sql, [$team_id, $_COOKIE['hash']]);
        } else {
            $sql = "SELECT `ceo_database_print_text_rod` FROM `users` WHERE `team_id` = {?} AND `ip` = {?} LIMIT 1";
            $user_info = $this->db->selectRow($sql, [$team_id, $this->getIp()]);
        }

        $return = [];

        $return['titles'] = '<div class="dashboard_tab_title dashboard_tab_title_can_click" data-tab="tab1" data-step="databases_start_four" data-action-id="28" data-database="false">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper">
                                        <svg width="19" height="21" viewBox="0 0 19 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 0H19V3L17.25 5H0V2L1.75 0ZM1.73684 2H3V3.2L2.26316 4H1V2.8L1.73684 2ZM6 2H4.73684L4 2.8V4H5.26316L6 3.2V2ZM7.73684 2H9V3.2L8.26316 4H7V2.8L7.73684 2ZM17 2H10.7368L10 2.8V4H16.2632L17 3.2V2Z" fill="#00F0FF"/><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 8H19V11L17.25 13H0V10L1.75 8ZM1.73684 10H3V11.2L2.26316 12H1V10.8L1.73684 10ZM6 10H4.73684L4 10.8V12H5.26316L6 11.2V10ZM7.73684 10H9V11.2L8.26316 12H7V10.8L7.73684 10ZM17 10H10.7368L10 10.8V12H16.2632L17 11.2V10Z" fill="#00F0FF"/><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 16H19V19L17.25 21H0V18L1.75 16ZM1.73684 18H3V19.2L2.26316 20H1V18.8L1.73684 18ZM6 18H4.73684L4 18.8V20H5.26316L6 19.2V18ZM7.73684 18H9V19.2L8.26316 20H7V18.8L7.73684 18ZM17 18H10.7368L10 18.8V20H16.2632L17 19.2V18Z" fill="#00F0FF"/><rect width="15" height="1" transform="matrix(1 0 0 -1 2 7)" fill="#00F0FF"/><rect width="15" height="1" transform="matrix(1 0 0 -1 2 15)" fill="#00F0FF"/></svg>
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text13'] . '</div>
                                </div>
                            </div>
                            <div class="dashboard_tab_title dashboard_tab_title_can_click" data-tab="personal_files1" data-step="databases_start_four_inner_first_personal_files" data-action-id="32" data-database="personal_files">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper" style="margin: -3px 0 0;">
                                        <img src="/images/icons/icon_tab_personal_files.png" alt="">
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text170'] . '</div>
                                </div>
                            </div>

                            <div class="dashboard_tab_title dashboard_tab_title_active" data-tab="personal_files2">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper" style="margin: -2px 0 0;">
                                        <img src="/images/icons/icon_tab_ceo_database.png" alt="">
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text132'] . '</div>
                                </div>
                            </div>';

        $return['back_btn'] = '<div class="dashboard_back_btn" data-back="databases_start_four_inner_first_personal_files" data-action-id-back="32" data-database="personal_files">
                                    <img src="/images/back_bg.png" class="back_btn_bg" alt="">
                                    <div class="back_btn_text">' . $translation['text22'] . '</div>
                                </div>';

        $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_start_four" data-tab="tab1"></div>

                            <div class="dashboard_tab_content_item dashboard_tab_content_item_start_four" data-tab="personal_files1"></div>
        
                            <div class="dashboard_tab_content_item dashboard_tab_content_item_start_four dashboard_tab_content_item_active" data-tab="personal_files2">
                                <div class="dashboard_personal_files2_private_individuals_huilov_inner' . (empty($user_info['ceo_database_print_text_rod']) ? ' dashboard_personal_files2_ceo_database_rod_inner_bubble' : '') . (empty($team_info['ceo_database_print_text_rod']) ? ' dashboard_personal_files2_ceo_database_rod_inner_bubble_team' : '') . '"">
                                    <div class="dashboard_personal_files2_private_individuals_huilov_right_bg_line">
                                        <svg width="868" height="418" viewBox="0 0 868 418" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.5 1H790L809.5 20.5V405L822 417.5H867.5" stroke="#FF004E"/></svg>
                                    </div>
                                    <div class="dashboard_personal_files2_private_individuals_huilov_left">
                                        <div class="dashboard_personal_files2_private_individuals_huilov_images">
                                            <div class="dashboard_personal_files2_private_individuals_huilov_images_inner">
                                                <img src="/images/icons/icon_huilov_hand.png" alt="">
                                                <img src="/images/icons/icon_huilov_img2.png" alt="">
                                                <img src="/images/icons/icon_huilov_diagram.png" alt="">
                                                <div class="dashboard_personal_files2_private_individuals_huilov_images_text">
                                                    <span>' . $translation['text113'] . '</span>
                                                    <span>' . $translation['text114'] . '</span>
                                                </div>
                                            </div>
                                            <div class="dashboard_personal_files2_private_individuals_huilov_images_bg_right"><svg width="9" height="396" viewBox="0 0 9 396" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 1H8.5V395.5H3H0" stroke="#FF004E"/></svg></div>
                                            <div class="dashboard_personal_files2_private_individuals_huilov_images_bg_bottom"></div>
                                        </div>
                                        <div class="dashboard_personal_files2_private_individuals_huilov_main_image">
                                            <img src="/images/rod_photo2.jpg" class="huilov_main_image" alt="">
                                            <div class="dashboard_personal_files2_private_individuals_huilov_main_image_diagram">
                                                <img src="/images/icons/icon_huilov_main_image_diagram.png" alt="">
                                            </div>
                                            <img src="/images/gifs/face_anim.gif" class="rod_face_anim" alt="">
                                        </div>
                                    </div>
                                    <div class="dashboard_personal_files2_private_individuals_huilov_right">
                                        <div class="dashboard_personal_files2_private_individuals_huilov_data_row">
                                            <div class="dashboard_personal_files2_private_individuals_huilov_label">' . $translation['text115'] . '</div>
                                            <div class="dashboard_personal_files2_private_individuals_huilov_input">
                                                <span class="dots_top"></span><span class="dots_bottom_left"></span><span class="dots_bottom_right"></span>
                                                <span class="private_individuals_huilov_text" data-bubble="0"><span>' . (empty($user_info['ceo_database_print_text_rod']) ? '' : $translation['text133']) . '</span></span>
                                            </div>
                                        </div>
                                        <div class="dashboard_personal_files2_private_individuals_huilov_data_row dashboard_personal_files2_private_individuals_huilov_data_row_lastname">
                                            <div class="dashboard_personal_files2_private_individuals_huilov_label">' . $translation['text117'] . '</div>
                                            <div class="dashboard_personal_files2_private_individuals_huilov_input">
                                                <span class="dots_top"></span><span class="dots_bottom_left"></span><span class="dots_bottom_right"></span>
                                                <span class="private_individuals_huilov_text" data-bubble="1"><span>' . (empty($user_info['ceo_database_print_text_rod']) ? '' : $translation['text134']) . '</span></span>
                                            </div>
                                        </div>
                                        <div class="dashboard_personal_files2_private_individuals_huilov_data_row">
                                            <div class="dashboard_personal_files2_private_individuals_huilov_label">' . $translation['text119'] . '</div>
                                            <div class="dashboard_personal_files2_private_individuals_huilov_input">
                                                <span class="dots_top"></span><span class="dots_bottom_left"></span><span class="dots_bottom_right"></span>
                                                <span class="private_individuals_huilov_text" data-bubble="2"><span>' . (empty($user_info['ceo_database_print_text_rod']) ? '' : $translation['text135']) . '</span></span>
                                            </div>
                                        </div>
                                        <div class="dashboard_personal_files2_private_individuals_huilov_data_row">
                                            <div class="dashboard_personal_files2_private_individuals_huilov_label">' . $translation['text136'] . '</div>
                                            <div class="dashboard_personal_files2_private_individuals_huilov_input">
                                                <span class="dots_top"></span><span class="dots_bottom_left"></span><span class="dots_bottom_right"></span>
                                                <span class="private_individuals_huilov_text" data-bubble="3"><span>' . (empty($user_info['ceo_database_print_text_rod']) ? '' : $translation['text137']) . '</span></span>
                                            </div>
                                        </div>
                                        <div class="dashboard_personal_files2_private_individuals_huilov_data_row">
                                            <div class="dashboard_personal_files2_private_individuals_huilov_label">' . $translation['text138'] . '</div>
                                            <div class="dashboard_personal_files2_private_individuals_huilov_input">
                                                <span class="dots_top"></span><span class="dots_bottom_left"></span><span class="dots_bottom_right"></span>
                                                <span class="private_individuals_huilov_text" data-bubble="4"><span>' . (empty($user_info['ceo_database_print_text_rod']) ? '' : $translation['text139']) . '</span></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>';

        // при первом запуске
        if (empty($team_info['ceo_database_print_text_rod'])) {
            // обновляем значение, что текст напечатан. Повторно скрипт НЕ закускается
            $sql = "UPDATE `teams` SET `ceo_database_print_text_rod` = {?} WHERE `id` = {?}";
            $this->db->query($sql, [1, $team_id]);

            // обновляем подсказки
            // список открытых
            $active_hints = [];

            // список доступных
            $list_hints = [];

            $hints_by_step = $this->getHintsByStep('ceo_database_rod', $lang_id);
            if ($hints_by_step) {
                foreach ($hints_by_step as $hint) {
                    $list_hints[] = $hint['id'];
                }
            }

            // сохраняем обновленный список подсказок
            $sql = "UPDATE `teams` SET `active_hints` = {?}, `list_hints` = {?}, `list_hints_title_lang_var` = {?}, `list_hints_text_lang_var` = {?} WHERE `id` = {?}";
            $this->db->query($sql, [json_encode($active_hints, JSON_UNESCAPED_UNICODE), json_encode($list_hints, JSON_UNESCAPED_UNICODE), 'text44', 'text45', $team_id]);
        }

        // обновляем, что текст напечатан для этого юзера
        if (isset($_COOKIE['hash'])) {
            $sql = "UPDATE `users` SET `ceo_database_print_text_rod` = {?} WHERE `team_id` = {?} AND `hash` = {?}";
            $this->db->query($sql, [1, $team_id, $_COOKIE['hash']]);
        } else {
            $sql = "UPDATE `users` SET `ceo_database_print_text_rod` = {?} WHERE `team_id` = {?} AND `ip` = {?}";
            $this->db->query($sql, [1, $team_id, $this->getIp()]);
        }

        // возвращаем также массив для отпечатки текста на разных языках
        $return['error_lang'] = [];

        $sql = "SELECT `id`, `lang_abbr` FROM `langs` WHERE `status` = {?}";
        $langs = $this->db->select($sql, [1]);
        if ($langs) {
            foreach ($langs as $lang_item) {
                $translation = $this->getWordsByPage('game', $lang_item['id']);

                $return['error_lang'][$lang_item['lang_abbr']] = [
                    'text133' => $translation['text133'],
                    'text134' => $translation['text134'],
                    'text135' => $translation['text135'],
                    'text137' => $translation['text137'],
                    'text139' => $translation['text139']
                ];
            }
        }

        return $return;
    }

    // databases - загрузить Mobile Calls. Первый экран
    private function uploadDatabasesMobileCalls($lang_id, $team_id)
    {
        $translation = $this->getWordsByPage('game', $lang_id);

        $team_info = $this->teamInfo($team_id);

        $return = [];

        $return['titles'] = '<div class="dashboard_tab_title dashboard_tab_title_can_click" data-tab="tab1" data-step="databases_start_four" data-action-id="28" data-database="false">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper">
                                        <svg width="19" height="21" viewBox="0 0 19 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 0H19V3L17.25 5H0V2L1.75 0ZM1.73684 2H3V3.2L2.26316 4H1V2.8L1.73684 2ZM6 2H4.73684L4 2.8V4H5.26316L6 3.2V2ZM7.73684 2H9V3.2L8.26316 4H7V2.8L7.73684 2ZM17 2H10.7368L10 2.8V4H16.2632L17 3.2V2Z" fill="#00F0FF"/><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 8H19V11L17.25 13H0V10L1.75 8ZM1.73684 10H3V11.2L2.26316 12H1V10.8L1.73684 10ZM6 10H4.73684L4 10.8V12H5.26316L6 11.2V10ZM7.73684 10H9V11.2L8.26316 12H7V10.8L7.73684 10ZM17 10H10.7368L10 10.8V12H16.2632L17 11.2V10Z" fill="#00F0FF"/><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 16H19V19L17.25 21H0V18L1.75 16ZM1.73684 18H3V19.2L2.26316 20H1V18.8L1.73684 18ZM6 18H4.73684L4 18.8V20H5.26316L6 19.2V18ZM7.73684 18H9V19.2L8.26316 20H7V18.8L7.73684 18ZM17 18H10.7368L10 18.8V20H16.2632L17 19.2V18Z" fill="#00F0FF"/><rect width="15" height="1" transform="matrix(1 0 0 -1 2 7)" fill="#00F0FF"/><rect width="15" height="1" transform="matrix(1 0 0 -1 2 15)" fill="#00F0FF"/></svg>
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text13'] . '</div>
                                </div>
                            </div>
                            <div class="dashboard_tab_title dashboard_tab_title_active" data-tab="mobile_calls1">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper">
                                        <svg width="22" height="25" viewBox="0 0 22 25" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4.04297 13.0766C3.95284 13.0766 3.8664 13.0408 3.80266 12.9771C3.73893 12.9133 3.70312 12.8269 3.70312 12.7368V2.41797C3.70312 1.90722 3.90602 1.41739 4.26717 1.05624C4.62833 0.695082 5.11816 0.492188 5.62891 0.492188H15.3711C15.8818 0.492188 16.3717 0.695082 16.7328 1.05624C17.094 1.41739 17.2969 1.90722 17.2969 2.41797V6.14945C17.2969 6.23959 17.2611 6.32603 17.1973 6.38976C17.1336 6.45349 17.0472 6.4893 16.957 6.4893C16.8669 6.4893 16.7805 6.45349 16.7167 6.38976C16.653 6.32603 16.6172 6.23959 16.6172 6.14945V2.41797C16.6172 2.08748 16.4859 1.77054 16.2522 1.53685C16.0185 1.30316 15.7016 1.17188 15.3711 1.17188H5.62891C5.29842 1.17188 4.98147 1.30316 4.74778 1.53685C4.5141 1.77054 4.38281 2.08748 4.38281 2.41797V12.7368C4.38281 12.8269 4.34701 12.9133 4.28327 12.9771C4.21954 13.0408 4.1331 13.0766 4.04297 13.0766Z" fill="#00F0FF"/><path d="M15.3711 24.5078H5.62891C5.11816 24.5078 4.62833 24.3049 4.26717 23.9437C3.90602 23.5826 3.70312 23.0927 3.70313 22.582V17.7211C3.70313 17.631 3.73893 17.5445 3.80266 17.4808C3.8664 17.4171 3.95284 17.3812 4.04297 17.3812C4.1331 17.3812 4.21954 17.4171 4.28327 17.4808C4.34701 17.5445 4.38281 17.631 4.38281 17.7211V22.582C4.38281 22.9125 4.5141 23.2294 4.74778 23.4631C4.98147 23.6968 5.29842 23.8281 5.62891 23.8281H15.3711C15.7016 23.8281 16.0185 23.6968 16.2522 23.4631C16.4859 23.2294 16.6172 22.9125 16.6172 22.582V14.0791C16.6172 13.989 16.653 13.9025 16.7167 13.8388C16.7805 13.7751 16.8669 13.7393 16.957 13.7393C17.0472 13.7393 17.1336 13.7751 17.1973 13.8388C17.2611 13.9025 17.2969 13.989 17.2969 14.0791V22.582C17.2969 23.0927 17.094 23.5826 16.7328 23.9437C16.3717 24.3049 15.8818 24.5078 15.3711 24.5078Z" fill="#00F0FF"/><path d="M12.5162 2.32851H8.48343C8.17602 2.32793 7.88124 2.20617 7.66302 1.98965C7.4448 1.77313 7.32075 1.4793 7.31777 1.17191C7.22763 1.1707 7.14167 1.13375 7.07879 1.06916C7.0159 1.00458 6.98125 0.917663 6.98245 0.82753C6.98365 0.737398 7.02061 0.651435 7.08519 0.588552C7.14978 0.525668 7.2367 0.491016 7.32683 0.492218H13.6706C13.7607 0.491016 13.8476 0.525668 13.9122 0.588552C13.9768 0.651435 14.0138 0.737398 14.015 0.82753C14.0162 0.917663 13.9815 1.00458 13.9186 1.06916C13.8557 1.13375 13.7698 1.1707 13.6796 1.17191C13.6767 1.47891 13.5529 1.7724 13.3352 1.98885C13.1175 2.20531 12.8233 2.32733 12.5162 2.32851ZM7.99745 1.17191C8.00012 1.29912 8.05245 1.42025 8.14327 1.50938C8.23409 1.5985 8.35618 1.64855 8.48343 1.64882H12.5162C12.6435 1.64855 12.7656 1.5985 12.8564 1.50938C12.9472 1.42025 12.9996 1.29912 13.0022 1.17191H7.99745Z" fill="#00F0FF"/><path d="M12.6079 22.8926H8.3916C8.30147 22.8926 8.21503 22.8568 8.1513 22.793C8.08756 22.7293 8.05176 22.6429 8.05176 22.5527C8.05176 22.4626 8.08756 22.3762 8.1513 22.3124C8.21503 22.2487 8.30147 22.2129 8.3916 22.2129H12.6079C12.6981 22.2129 12.7845 22.2487 12.8482 22.3124C12.912 22.3762 12.9478 22.4626 12.9478 22.5527C12.9478 22.6429 12.912 22.7293 12.8482 22.793C12.7845 22.8568 12.6981 22.8926 12.6079 22.8926Z" fill="#00F0FF"/><path d="M12.3177 16.492C12.2734 16.4917 12.2295 16.4828 12.1886 16.4659C12.1263 16.4404 12.0731 16.3969 12.0356 16.341C11.998 16.2852 11.978 16.2194 11.9779 16.1521V14.3929C11.4652 14.3112 10.9983 14.0498 10.6607 13.6555C10.323 13.2612 10.1366 12.7597 10.1348 12.2405V7.9925C10.1357 7.41383 10.3659 6.85911 10.7751 6.44993C11.1843 6.04074 11.739 5.81047 12.3177 5.80957H19.5065C20.0853 5.81017 20.6402 6.04035 21.0494 6.4496C21.4587 6.85885 21.6889 7.41373 21.6895 7.9925V12.236C21.6889 12.8148 21.4587 13.3697 21.0494 13.7789C20.6402 14.1882 20.0853 14.4183 19.5065 14.4189H14.5323L12.559 16.3923C12.5273 16.424 12.4897 16.4491 12.4483 16.4662C12.4069 16.4833 12.3625 16.4921 12.3177 16.492ZM12.3177 6.48926C11.9192 6.48986 11.5372 6.64843 11.2554 6.93021C10.9736 7.21199 10.8151 7.594 10.8145 7.9925V12.236C10.8151 12.6345 10.9736 13.0165 11.2554 13.2983C11.5372 13.5801 11.9192 13.7387 12.3177 13.7393C12.4078 13.7393 12.4943 13.7751 12.558 13.8388C12.6217 13.9025 12.6575 13.989 12.6575 14.0791V15.332L14.1506 13.8389C14.2143 13.7752 14.3006 13.7393 14.3907 13.7393H19.5065C19.9051 13.739 20.2873 13.5805 20.5691 13.2986C20.851 13.0168 21.0095 12.6346 21.0098 12.236V7.9925C21.0095 7.59391 20.851 7.21173 20.5691 6.92988C20.2873 6.64803 19.9051 6.48956 19.5065 6.48926H12.3177Z" fill="#00F0FF"/><path d="M7.17191 19.9075C7.08551 19.9072 7.00247 19.874 6.93969 19.8146L5.07848 18.061H1.97344C1.59648 18.0607 1.23504 17.9109 0.968489 17.6443C0.701938 17.3778 0.552058 17.0163 0.551758 16.6394V13.8187C0.552058 13.4417 0.701938 13.0803 0.968489 12.8137C1.23504 12.5472 1.59648 12.3973 1.97344 12.397H7.17191C7.54898 12.3973 7.91053 12.5471 8.17726 12.8136C8.44399 13.0802 8.59413 13.4416 8.59473 13.8187V16.6394C8.59397 16.9573 8.48699 17.2659 8.29077 17.5161C8.09456 17.7663 7.82037 17.9437 7.51176 18.0203V19.5677C7.51167 19.6339 7.49222 19.6987 7.45579 19.7541C7.41936 19.8094 7.36754 19.8529 7.30672 19.8792C7.2643 19.8981 7.21835 19.9077 7.17191 19.9075ZM1.97344 13.0767C1.77674 13.077 1.58819 13.1552 1.4491 13.2943C1.31002 13.4334 1.23174 13.622 1.23145 13.8187V16.6394C1.23174 16.8361 1.31002 17.0246 1.4491 17.1637C1.58819 17.3028 1.77674 17.381 1.97344 17.3813H5.21328C5.29772 17.3831 5.37849 17.4162 5.43984 17.4742L6.82641 18.7804V17.7212C6.82641 17.6311 6.86221 17.5446 6.92594 17.4809C6.98968 17.4172 7.07612 17.3813 7.16625 17.3813C7.36314 17.3813 7.55199 17.3032 7.69132 17.1641C7.83065 17.025 7.90907 16.8362 7.90937 16.6394V13.8187C7.90907 13.6218 7.83065 13.433 7.69132 13.2939C7.55199 13.1548 7.36314 13.0767 7.16625 13.0767H1.97344Z" fill="#00F0FF"/><path d="M6.04935 15.8347C5.83562 15.8347 5.62874 15.8003 5.43647 15.7368C5.37724 15.7179 5.30943 15.7317 5.26222 15.7789L4.88454 16.1574C4.39784 15.9102 4.0004 15.5128 3.75319 15.0269L4.13087 14.6475C4.17809 14.6003 4.19182 14.5325 4.17294 14.4733C4.10942 14.281 4.07508 14.0741 4.07508 13.8604C4.07507 13.7651 3.99868 13.6887 3.9034 13.6887H3.30253C3.20811 13.6887 3.13086 13.7651 3.13086 13.8604C3.13086 15.4724 4.43732 16.7789 6.04935 16.7789C6.14463 16.7789 6.22103 16.7025 6.22103 16.6072V16.0063C6.22103 15.9111 6.14463 15.8347 6.04935 15.8347Z" fill="#00F0FF"/><path d="M18.8047 8.47974H13.4023C13.3122 8.47974 13.2258 8.44393 13.162 8.3802C13.0983 8.31646 13.0625 8.23002 13.0625 8.13989C13.0625 8.04976 13.0983 7.96332 13.162 7.89959C13.2258 7.83585 13.3122 7.80005 13.4023 7.80005H18.8047C18.8949 7.80005 18.9813 7.83585 19.045 7.89959C19.1088 7.96332 19.1446 8.04976 19.1446 8.13989C19.1446 8.23002 19.1088 8.31646 19.045 8.3802C18.9813 8.44393 18.8949 8.47974 18.8047 8.47974Z" fill="#00F0FF"/><path d="M18.8039 10.4768H14.2285C14.1384 10.4768 14.0519 10.441 13.9882 10.3773C13.9245 10.3135 13.8887 10.2271 13.8887 10.137C13.8887 10.0468 13.9245 9.96039 13.9882 9.89666C14.0519 9.83292 14.1384 9.79712 14.2285 9.79712H18.8039C18.8941 9.79712 18.9805 9.83292 19.0443 9.89666C19.108 9.96039 19.1438 10.0468 19.1438 10.137C19.1438 10.2271 19.108 10.3135 19.0443 10.3773C18.9805 10.441 18.8941 10.4768 18.8039 10.4768Z" fill="#00F0FF"/><path d="M18.8047 12.4739H13.4023C13.3122 12.4739 13.2258 12.4381 13.162 12.3743C13.0983 12.3106 13.0625 12.2242 13.0625 12.134C13.0625 12.0439 13.0983 11.9575 13.162 11.8937C13.2258 11.83 13.3122 11.7942 13.4023 11.7942H18.8047C18.8949 11.7942 18.9813 11.83 19.045 11.8937C19.1088 11.9575 19.1446 12.0439 19.1446 12.134C19.1446 12.2242 19.1088 12.3106 19.045 12.3743C18.9813 12.4381 18.8949 12.4739 18.8047 12.4739Z" fill="#00F0FF"/></svg>

                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text59'] . '</div>
                                </div>
                            </div>';

        $return['back_btn'] = '<div class="dashboard_back_btn" data-back="databases_start_four" data-action-id-back="28" data-database="false">
                                    <img src="/images/back_bg.png" class="back_btn_bg" alt="">
                                    <div class="back_btn_text">' . $translation['text22'] . '</div>
                                </div>';

        $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_start_four" data-tab="tab1"></div>

                            <div class="dashboard_tab_content_item dashboard_tab_content_item_car_register dashboard_tab_content_item_active" data-tab="mobile_calls1">
                                <div class="dashboard_car_register1_inner">
                                    <div class="dashboard_car_register1_inner_image_wrapper">
                                        <img src="/images/database_mobile_calls_icon.png" alt="">
                                    </div>
                                    <div class="dashboard_car_register1_inner_title" style="margin: 10px 0 0;">' . $translation['text59'] . '</div>
                                    <div class="dashboard_car_register1_inner_text">' . $translation['text140'] . '</div>
                                    <div class="dashboard_car_register1_fields_top">
                                        <div class="dashboard_car_register1_input_wrapper dashboard_car_register1_input_wrapper_license_plate">
                                            <div class="dashboard_car_register1_input_border_left"></div>';

        $sql = "
            SELECT c.code, cd.name, c.id
            FROM countries c
            JOIN countries_description cd ON c.id = cd.country_id
            WHERE cd.lang_id = {?}
            ORDER BY cd.name
        ";
        $countries = $this->db->select($sql, [$lang_id]);
        if ($countries) {
            $return['content'] .= '<select class="dashboard_mobile_calls1_country_code"><option disabled="disabled"' . (empty($team_info['mobile_calls_country_id']) ? ' selected="selected"' : '') . '>' . $translation['text141'] . '</option>';
            foreach ($countries as $country) {
                $return['content'] .= '<option value="' . $country['id'] . '"' . ($team_info['mobile_calls_country_id'] == $country['id'] ? ' selected="selected"' : '') . '>+' . $country['code'] . ' ' . $country['name'] . '</option>';
            }
            $return['content'] .= '</select>
                                    <script>
                                        $(function() {
                                            // select country code
                                            var scrollbarPositionPixel = 0;
                                            var isScrollOpen = false;

                                            $(".dashboard_mobile_calls1_country_code").selectric({
                                                maxHeight: 236,
                                                onInit: function() {
                                                    // стилизация полосы прокрутки
                                                    $(".selectric-dashboard_mobile_calls1_country_code .selectric-scroll").mCustomScrollbar({
                                                        scrollInertia: 700,
                                                        theme: "minimal-dark",
                                                        scrollbarPosition: "inside",
                                                        alwaysShowScrollbar: 2,
                                                        autoHideScrollbar: false,
                                                        mouseWheel:{ deltaFactor: 200 },
                                                        callbacks:{
                                                            whileScrolling:function() {
                                                                scrollbarPositionPixel = this.mcs.top;
                                                                if (isScrollOpen) {
                                                                    $(".dashboard_mobile_calls1_country_code").selectric("open");
                                                                }
                                                            }
                                                        }
                                                    });
                                                },
                                                onOpen: function() {
                                                    if (!isScrollOpen) {
                                                        $(".selectric-dashboard_mobile_calls1_country_code .selectric-scroll").mCustomScrollbar("scrollTo", Math.abs(scrollbarPositionPixel));
                                                        isScrollOpen = true;
                                                    }
                                                }
                                            })
                                            .on("change", function() {
                                                // сохраняем выбор
                                                var formData = new FormData();
                                                formData.append("op", "saveTeamTextField");
                                                formData.append("field", "mobile_calls_country_id");
                                                formData.append("val", $(this).val());

                                                $.ajax({
                                                    url: "/ajax/ajax.php",
                                                    type: "POST",
                                                    dataType: "json",
                                                    cache: false,
                                                    contentType: false,
                                                    processData: false,
                                                    data: formData,
                                                    success: function(json) {
                                                        if (json.country_lang) {
                                                            // socket
                                                            var message = {
                                                                "op": "databaseMobileCallsUpdateCountryCode",
                                                                "parameters": {
                                                                    "country_lang": json.country_lang,
                                                                    "user_id": $("#section_game").length ? $("#section_game").attr("data-user-id") : 0,
                                                                    "team_id": $("#section_game").length ? $("#section_game").attr("data-team-id") : 0
                                                                }
                                                            };
                                                            sendMessageSocket(JSON.stringify(message));
                                                        }
                                                    },
                                                    error: function(xhr, ajaxOptions, thrownError) {    
                                                        console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                                    }
                                                });

                                                isScrollOpen = false;
                                            });

                                            $(".dashboard_tabs[data-dashboard=\'databases\']").on("click", ".dashboard_car_register1_input_wrapper_license_plate .mCSB_scrollTools_vertical", function(e){
                                                if (isScrollOpen) {
                                                    $(".dashboard_mobile_calls1_country_code").selectric("open");
                                                }
                                            });
                                        });
                                    </script>';
        }

        $return['content'] .= '             
                                            <div class="dashboard_mobile_calls1_country_code_error error_text_database_car_register">' . $translation['text86'] . '</div>
                                        </div>
                                        <div class="dashboard_car_register1_input_wrapper dashboard_car_register1_input_wrapper_country">
                                            <div class="dashboard_car_register1_input_border_right"></div>
                                            <input type="text" placeholder="' . $translation['text142'] . '" autocomplete="off" class="dashboard_mobile_calls1_number" value="' . ((!empty($team_info['mobile_calls_number']) && !is_null($team_info['mobile_calls_number']) && $team_info['mobile_calls_number'] != 'NULL') ? htmlspecialchars($team_info['mobile_calls_number'], ENT_QUOTES) : '') . '">
                                            <div class="dashboard_mobile_calls1_number_error error_text_database_car_register">' . $translation['text86'] . '</div>
                                        </div>
                                    </div>
                                    <div class="btn_wrapper btn_wrapper_blue dashboard_mobile_calls1_search">
                                        <div class="btn btn_blue">
                                            <span>' . $translation['text66'] . '</span>
                                        </div>
                                        <div class="btn_border_top"></div>
                                        <div class="btn_border_bottom"></div>
                                        <div class="btn_border_left"></div>
                                        <div class="btn_border_left_arcle"></div>
                                        <div class="btn_border_right"></div>
                                        <div class="btn_border_right_arcle"></div>
                                        <div class="btn_bg_top_line"></div>
                                        <div class="btn_bg_bottom_line"></div>
                                        <div class="btn_bg_triangle_left"></div>
                                        <div class="btn_bg_triangle_right"></div>
                                        <div class="btn_circles_top">
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                        </div>
                                        <div class="btn_circles_bottom">
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>';

        return $return;
    }

    // databases - загрузить Mobile Calls. Второй экран. Успешно ввели номер
    private function uploadDatabasesMobileCallsMessages($lang_id, $team_id)
    {
        $translation = $this->getWordsByPage('game', $lang_id);

        $team_info = $this->teamInfo($team_id);

        if (isset($_COOKIE['hash'])) {
            $sql = "SELECT `mobile_calls_print_messages` FROM `users` WHERE `team_id` = {?} AND `hash` = {?} LIMIT 1";
            $user_info = $this->db->selectRow($sql, [$team_id, $_COOKIE['hash']]);
        } else {
            $sql = "SELECT `mobile_calls_print_messages` FROM `users` WHERE `team_id` = {?} AND `ip` = {?} LIMIT 1";
            $user_info = $this->db->selectRow($sql, [$team_id, $this->getIp()]);
        }

        $return = [];

        $return['titles'] = '<div class="dashboard_tab_title dashboard_tab_title_can_click" data-tab="tab1" data-step="databases_start_four" data-action-id="28" data-database="false">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper">
                                        <svg width="19" height="21" viewBox="0 0 19 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 0H19V3L17.25 5H0V2L1.75 0ZM1.73684 2H3V3.2L2.26316 4H1V2.8L1.73684 2ZM6 2H4.73684L4 2.8V4H5.26316L6 3.2V2ZM7.73684 2H9V3.2L8.26316 4H7V2.8L7.73684 2ZM17 2H10.7368L10 2.8V4H16.2632L17 3.2V2Z" fill="#00F0FF"/><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 8H19V11L17.25 13H0V10L1.75 8ZM1.73684 10H3V11.2L2.26316 12H1V10.8L1.73684 10ZM6 10H4.73684L4 10.8V12H5.26316L6 11.2V10ZM7.73684 10H9V11.2L8.26316 12H7V10.8L7.73684 10ZM17 10H10.7368L10 10.8V12H16.2632L17 11.2V10Z" fill="#00F0FF"/><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 16H19V19L17.25 21H0V18L1.75 16ZM1.73684 18H3V19.2L2.26316 20H1V18.8L1.73684 18ZM6 18H4.73684L4 18.8V20H5.26316L6 19.2V18ZM7.73684 18H9V19.2L8.26316 20H7V18.8L7.73684 18ZM17 18H10.7368L10 18.8V20H16.2632L17 19.2V18Z" fill="#00F0FF"/><rect width="15" height="1" transform="matrix(1 0 0 -1 2 7)" fill="#00F0FF"/><rect width="15" height="1" transform="matrix(1 0 0 -1 2 15)" fill="#00F0FF"/></svg>
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text13'] . '</div>
                                </div>
                            </div>
                            <div class="dashboard_tab_title" data-tab="mobile_calls1">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper">
                                        <svg width="22" height="25" viewBox="0 0 22 25" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4.04297 13.0766C3.95284 13.0766 3.8664 13.0408 3.80266 12.9771C3.73893 12.9133 3.70312 12.8269 3.70312 12.7368V2.41797C3.70312 1.90722 3.90602 1.41739 4.26717 1.05624C4.62833 0.695082 5.11816 0.492188 5.62891 0.492188H15.3711C15.8818 0.492188 16.3717 0.695082 16.7328 1.05624C17.094 1.41739 17.2969 1.90722 17.2969 2.41797V6.14945C17.2969 6.23959 17.2611 6.32603 17.1973 6.38976C17.1336 6.45349 17.0472 6.4893 16.957 6.4893C16.8669 6.4893 16.7805 6.45349 16.7167 6.38976C16.653 6.32603 16.6172 6.23959 16.6172 6.14945V2.41797C16.6172 2.08748 16.4859 1.77054 16.2522 1.53685C16.0185 1.30316 15.7016 1.17188 15.3711 1.17188H5.62891C5.29842 1.17188 4.98147 1.30316 4.74778 1.53685C4.5141 1.77054 4.38281 2.08748 4.38281 2.41797V12.7368C4.38281 12.8269 4.34701 12.9133 4.28327 12.9771C4.21954 13.0408 4.1331 13.0766 4.04297 13.0766Z" fill="#00F0FF"/><path d="M15.3711 24.5078H5.62891C5.11816 24.5078 4.62833 24.3049 4.26717 23.9437C3.90602 23.5826 3.70312 23.0927 3.70313 22.582V17.7211C3.70313 17.631 3.73893 17.5445 3.80266 17.4808C3.8664 17.4171 3.95284 17.3812 4.04297 17.3812C4.1331 17.3812 4.21954 17.4171 4.28327 17.4808C4.34701 17.5445 4.38281 17.631 4.38281 17.7211V22.582C4.38281 22.9125 4.5141 23.2294 4.74778 23.4631C4.98147 23.6968 5.29842 23.8281 5.62891 23.8281H15.3711C15.7016 23.8281 16.0185 23.6968 16.2522 23.4631C16.4859 23.2294 16.6172 22.9125 16.6172 22.582V14.0791C16.6172 13.989 16.653 13.9025 16.7167 13.8388C16.7805 13.7751 16.8669 13.7393 16.957 13.7393C17.0472 13.7393 17.1336 13.7751 17.1973 13.8388C17.2611 13.9025 17.2969 13.989 17.2969 14.0791V22.582C17.2969 23.0927 17.094 23.5826 16.7328 23.9437C16.3717 24.3049 15.8818 24.5078 15.3711 24.5078Z" fill="#00F0FF"/><path d="M12.5162 2.32851H8.48343C8.17602 2.32793 7.88124 2.20617 7.66302 1.98965C7.4448 1.77313 7.32075 1.4793 7.31777 1.17191C7.22763 1.1707 7.14167 1.13375 7.07879 1.06916C7.0159 1.00458 6.98125 0.917663 6.98245 0.82753C6.98365 0.737398 7.02061 0.651435 7.08519 0.588552C7.14978 0.525668 7.2367 0.491016 7.32683 0.492218H13.6706C13.7607 0.491016 13.8476 0.525668 13.9122 0.588552C13.9768 0.651435 14.0138 0.737398 14.015 0.82753C14.0162 0.917663 13.9815 1.00458 13.9186 1.06916C13.8557 1.13375 13.7698 1.1707 13.6796 1.17191C13.6767 1.47891 13.5529 1.7724 13.3352 1.98885C13.1175 2.20531 12.8233 2.32733 12.5162 2.32851ZM7.99745 1.17191C8.00012 1.29912 8.05245 1.42025 8.14327 1.50938C8.23409 1.5985 8.35618 1.64855 8.48343 1.64882H12.5162C12.6435 1.64855 12.7656 1.5985 12.8564 1.50938C12.9472 1.42025 12.9996 1.29912 13.0022 1.17191H7.99745Z" fill="#00F0FF"/><path d="M12.6079 22.8926H8.3916C8.30147 22.8926 8.21503 22.8568 8.1513 22.793C8.08756 22.7293 8.05176 22.6429 8.05176 22.5527C8.05176 22.4626 8.08756 22.3762 8.1513 22.3124C8.21503 22.2487 8.30147 22.2129 8.3916 22.2129H12.6079C12.6981 22.2129 12.7845 22.2487 12.8482 22.3124C12.912 22.3762 12.9478 22.4626 12.9478 22.5527C12.9478 22.6429 12.912 22.7293 12.8482 22.793C12.7845 22.8568 12.6981 22.8926 12.6079 22.8926Z" fill="#00F0FF"/><path d="M12.3177 16.492C12.2734 16.4917 12.2295 16.4828 12.1886 16.4659C12.1263 16.4404 12.0731 16.3969 12.0356 16.341C11.998 16.2852 11.978 16.2194 11.9779 16.1521V14.3929C11.4652 14.3112 10.9983 14.0498 10.6607 13.6555C10.323 13.2612 10.1366 12.7597 10.1348 12.2405V7.9925C10.1357 7.41383 10.3659 6.85911 10.7751 6.44993C11.1843 6.04074 11.739 5.81047 12.3177 5.80957H19.5065C20.0853 5.81017 20.6402 6.04035 21.0494 6.4496C21.4587 6.85885 21.6889 7.41373 21.6895 7.9925V12.236C21.6889 12.8148 21.4587 13.3697 21.0494 13.7789C20.6402 14.1882 20.0853 14.4183 19.5065 14.4189H14.5323L12.559 16.3923C12.5273 16.424 12.4897 16.4491 12.4483 16.4662C12.4069 16.4833 12.3625 16.4921 12.3177 16.492ZM12.3177 6.48926C11.9192 6.48986 11.5372 6.64843 11.2554 6.93021C10.9736 7.21199 10.8151 7.594 10.8145 7.9925V12.236C10.8151 12.6345 10.9736 13.0165 11.2554 13.2983C11.5372 13.5801 11.9192 13.7387 12.3177 13.7393C12.4078 13.7393 12.4943 13.7751 12.558 13.8388C12.6217 13.9025 12.6575 13.989 12.6575 14.0791V15.332L14.1506 13.8389C14.2143 13.7752 14.3006 13.7393 14.3907 13.7393H19.5065C19.9051 13.739 20.2873 13.5805 20.5691 13.2986C20.851 13.0168 21.0095 12.6346 21.0098 12.236V7.9925C21.0095 7.59391 20.851 7.21173 20.5691 6.92988C20.2873 6.64803 19.9051 6.48956 19.5065 6.48926H12.3177Z" fill="#00F0FF"/><path d="M7.17191 19.9075C7.08551 19.9072 7.00247 19.874 6.93969 19.8146L5.07848 18.061H1.97344C1.59648 18.0607 1.23504 17.9109 0.968489 17.6443C0.701938 17.3778 0.552058 17.0163 0.551758 16.6394V13.8187C0.552058 13.4417 0.701938 13.0803 0.968489 12.8137C1.23504 12.5472 1.59648 12.3973 1.97344 12.397H7.17191C7.54898 12.3973 7.91053 12.5471 8.17726 12.8136C8.44399 13.0802 8.59413 13.4416 8.59473 13.8187V16.6394C8.59397 16.9573 8.48699 17.2659 8.29077 17.5161C8.09456 17.7663 7.82037 17.9437 7.51176 18.0203V19.5677C7.51167 19.6339 7.49222 19.6987 7.45579 19.7541C7.41936 19.8094 7.36754 19.8529 7.30672 19.8792C7.2643 19.8981 7.21835 19.9077 7.17191 19.9075ZM1.97344 13.0767C1.77674 13.077 1.58819 13.1552 1.4491 13.2943C1.31002 13.4334 1.23174 13.622 1.23145 13.8187V16.6394C1.23174 16.8361 1.31002 17.0246 1.4491 17.1637C1.58819 17.3028 1.77674 17.381 1.97344 17.3813H5.21328C5.29772 17.3831 5.37849 17.4162 5.43984 17.4742L6.82641 18.7804V17.7212C6.82641 17.6311 6.86221 17.5446 6.92594 17.4809C6.98968 17.4172 7.07612 17.3813 7.16625 17.3813C7.36314 17.3813 7.55199 17.3032 7.69132 17.1641C7.83065 17.025 7.90907 16.8362 7.90937 16.6394V13.8187C7.90907 13.6218 7.83065 13.433 7.69132 13.2939C7.55199 13.1548 7.36314 13.0767 7.16625 13.0767H1.97344Z" fill="#00F0FF"/><path d="M6.04935 15.8347C5.83562 15.8347 5.62874 15.8003 5.43647 15.7368C5.37724 15.7179 5.30943 15.7317 5.26222 15.7789L4.88454 16.1574C4.39784 15.9102 4.0004 15.5128 3.75319 15.0269L4.13087 14.6475C4.17809 14.6003 4.19182 14.5325 4.17294 14.4733C4.10942 14.281 4.07508 14.0741 4.07508 13.8604C4.07507 13.7651 3.99868 13.6887 3.9034 13.6887H3.30253C3.20811 13.6887 3.13086 13.7651 3.13086 13.8604C3.13086 15.4724 4.43732 16.7789 6.04935 16.7789C6.14463 16.7789 6.22103 16.7025 6.22103 16.6072V16.0063C6.22103 15.9111 6.14463 15.8347 6.04935 15.8347Z" fill="#00F0FF"/><path d="M18.8047 8.47974H13.4023C13.3122 8.47974 13.2258 8.44393 13.162 8.3802C13.0983 8.31646 13.0625 8.23002 13.0625 8.13989C13.0625 8.04976 13.0983 7.96332 13.162 7.89959C13.2258 7.83585 13.3122 7.80005 13.4023 7.80005H18.8047C18.8949 7.80005 18.9813 7.83585 19.045 7.89959C19.1088 7.96332 19.1446 8.04976 19.1446 8.13989C19.1446 8.23002 19.1088 8.31646 19.045 8.3802C18.9813 8.44393 18.8949 8.47974 18.8047 8.47974Z" fill="#00F0FF"/><path d="M18.8039 10.4768H14.2285C14.1384 10.4768 14.0519 10.441 13.9882 10.3773C13.9245 10.3135 13.8887 10.2271 13.8887 10.137C13.8887 10.0468 13.9245 9.96039 13.9882 9.89666C14.0519 9.83292 14.1384 9.79712 14.2285 9.79712H18.8039C18.8941 9.79712 18.9805 9.83292 19.0443 9.89666C19.108 9.96039 19.1438 10.0468 19.1438 10.137C19.1438 10.2271 19.108 10.3135 19.0443 10.3773C18.9805 10.441 18.8941 10.4768 18.8039 10.4768Z" fill="#00F0FF"/><path d="M18.8047 12.4739H13.4023C13.3122 12.4739 13.2258 12.4381 13.162 12.3743C13.0983 12.3106 13.0625 12.2242 13.0625 12.134C13.0625 12.0439 13.0983 11.9575 13.162 11.8937C13.2258 11.83 13.3122 11.7942 13.4023 11.7942H18.8047C18.8949 11.7942 18.9813 11.83 19.045 11.8937C19.1088 11.9575 19.1446 12.0439 19.1446 12.134C19.1446 12.2242 19.1088 12.3106 19.045 12.3743C18.9813 12.4381 18.8949 12.4739 18.8047 12.4739Z" fill="#00F0FF"/></svg>
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text59'] . '</div>
                                </div>
                            </div>
                            <div class="dashboard_tab_title dashboard_tab_title_active" data-tab="mobile_calls2">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_text">+7 940 544 21 337</div>
                                </div>
                            </div>';

        $return['back_btn'] = '<div class="dashboard_back_btn" data-back="databases_start_four" data-action-id-back="28" data-database="false">
                                    <img src="/images/back_bg.png" class="back_btn_bg" alt="">
                                    <div class="back_btn_text">' . $translation['text22'] . '</div>
                                </div>';

        $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_start_four" data-tab="tab1"></div>

                            <div class="dashboard_tab_content_item dashboard_tab_content_item_car_register" data-tab="mobile_calls1"></div>

                            <div class="dashboard_tab_content_item dashboard_tab_content_item_car_register dashboard_tab_content_item_active" data-tab="mobile_calls2">
                                <div class="dashboard_mobile_calls2_inner' . (empty($user_info['mobile_calls_print_messages']) ? ' dashboard_mobile_calls2_inner_first' : '') . (empty($team_info['mobile_calls_print_messages']) ? ' dashboard_mobile_calls2_inner_first_team' : '') . '">
                                    <div class="dashboard_mobile_calls2_top">
                                        <div class="dashboard_mobile_calls2_title">' . $translation['text145'] . '</div>
                                        <div class="dashboard_mobile_calls2_text">' . $translation['text146'] . '</div>
                                    </div>
                                    <div class="dashboard_mobile_calls2_messages">
                                        <div class="dashboard_mobile_calls2_message_item dashboard_mobile_calls2_message_item1">
                                            <div class="dashboard_mobile_calls2_message_inner">
                                                <div class="dashboard_mobile_calls2_message_inner_top"><img src="/images/icons/icon_mobile_calls2_face_from.png" alt=""><span>' . $translation['text160'] . '</span></div>
                                                <div class="dashboard_mobile_calls2_message_inner_bottom">
                                                    <div class="dashboard_mobile_calls2_message_inner_bottom_time">' . $translation['text69'] . ' 30.08.22 16:46</div>
                                                    <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_from"><span>' . $translation['text147'] . ' 🤙</span></div>
                                                    <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_from dashboard_mobile_calls2_message_from_has_icon"><span>' . $translation['text148'] . ' 🤔</span></div>
                                                    <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_to dashboard_mobile_calls2_message_to_has_icon"><span>' . $translation['text149'] . '☠️</span></div>
                                                    <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_from dashboard_mobile_calls2_message_from_has_icon"><span>' . $translation['text150'] . ' 🤟</span></div>
                                                </div>
                                            </div>
                                            <div class="dashboard_mobile_calls2_message_item_border_right_bottom_bg">
                                                <svg width="83" height="10" viewBox="0 0 83 10" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M75.9846 9.49998L82.501 0L9.86363 1.3677e-05L0.000442505 9.49999L75.9846 9.49998Z" fill="#00F0FF"/></svg>
                                            </div>
                                        </div>

                                        <div class="dashboard_mobile_calls2_message_item dashboard_mobile_calls2_message_item2">
                                            <div class="dashboard_mobile_calls2_message_inner">
                                                <div class="dashboard_mobile_calls2_message_inner_top"><img src="/images/icons/icon_mobile_calls2_face_from.png" alt=""><span>' . $translation['text160'] . '</span></div>
                                                <div class="dashboard_mobile_calls2_message_inner_bottom">
                                                    <div class="dashboard_mobile_calls2_message_inner_bottom_time">' . $translation['text69'] . ' 30.08.22 16:47</div>
                                                    <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_from dashboard_mobile_calls2_message_from_has_icon"><span>' . $translation['text151'] . '</span></div>
                                                    <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_to dashboard_mobile_calls2_message_to_has_icon"><span>' . $translation['text152'] . '</span></div>
                                                    <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_from dashboard_mobile_calls2_message_from_has_icon"><span>' . $translation['text153'] . '</span></div>
                                                    <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_to dashboard_mobile_calls2_message_to_has_icon"><span>' . $translation['text154'] . ' 👊</span></div>
                                                </div>
                                            </div>
                                            <div class="dashboard_mobile_calls2_message_item_border_right_bottom_bg">
                                                <svg width="83" height="10" viewBox="0 0 83 10" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M75.9846 9.49998L82.501 0L9.86363 1.3677e-05L0.000442505 9.49999L75.9846 9.49998Z" fill="#00F0FF"/></svg>
                                            </div>
                                        </div>

                                        <div class="dashboard_mobile_calls2_message_item dashboard_mobile_calls2_message_item3">
                                            <div class="dashboard_mobile_calls2_message_inner">
                                                <div class="dashboard_mobile_calls2_message_inner_top"><img src="/images/icons/icon_mobile_calls2_face_from.png" alt=""><span>' . $translation['text160'] . '</span></div>
                                                <div class="dashboard_mobile_calls2_message_inner_bottom">
                                                    <div class="dashboard_mobile_calls2_message_inner_bottom_time">' . $translation['text69'] . ' 30.08.22 23:01</div>
                                                    <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_from"><span>' . $translation['text155'] . ' 🙌</span></div>
                                                    <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_from"><span>' . $translation['text156'] . '</span></div>
                                                    <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_from dashboard_mobile_calls2_message_from_has_icon">' . $translation['text157'] . ' <span class="as_link">' . $translation['text158'] . '</span></span></div>
                                                    <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_to dashboard_mobile_calls2_message_to_has_icon"><span>' . $translation['text159'] . ' ✊</span></div>
                                                </div>
                                            </div>
                                            <div class="dashboard_mobile_calls2_message_item_border_right_bottom_bg">
                                                <svg width="83" height="10" viewBox="0 0 83 10" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M75.9846 9.49998L82.501 0L9.86363 1.3677e-05L0.000442505 9.49999L75.9846 9.49998Z" fill="#00F0FF"/></svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>';

        $return['popup'] = '<div id="popup_mobile_calls_messages">
                                <div class="popup_mobile_calls_messages_bg"></div>
                                <div class="popup_mobile_calls_messages_bg_inner">
                                    <div class="popup_mobile_calls_messages_container">
                                        <div class="popup_mobile_calls_close">
                                            <img src="/images/popup_close.png" alt="">
                                        </div>
                                        <div class="popup_mobile_calls_dots">
                                            <div class="popup_mobile_calls_dot"></div>
                                            <div class="popup_mobile_calls_dot"></div>
                                            <div class="popup_mobile_calls_dot"></div>
                                            <div class="popup_mobile_calls_dot"></div>
                                            <div class="popup_mobile_calls_dot"></div>
                                            <div class="popup_mobile_calls_dot"></div>
                                            <div class="popup_mobile_calls_dot"></div>
                                            <div class="popup_mobile_calls_dot"></div>
                                        </div>
                                        <div class="popup_mobile_calls_inner">
                                            <div class="dashboard_mobile_calls2_messages">
                                                <div class="dashboard_mobile_calls2_message_item dashboard_mobile_calls2_message_item1">
                                                    <div class="dashboard_mobile_calls2_message_inner">
                                                        <div class="dashboard_mobile_calls2_message_inner_top"><img src="/images/icons/icon_mobile_calls2_face_from_big.png" alt=""><span>' . $translation['text160'] . '</span></div>
                                                        <div class="dashboard_mobile_calls2_message_inner_bottom">
                                                            <div class="dashboard_mobile_calls2_message_inner_bottom_time">' . $translation['text69'] . ' 30.08.22 16:46</div>
                                                            <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_from"><span>' . $translation['text147'] . ' 🤙</span></div>
                                                            <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_from dashboard_mobile_calls2_message_from_has_icon"><span>' . $translation['text148'] . ' 🤔</span></div>
                                                            <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_to dashboard_mobile_calls2_message_to_has_icon"><span>' . $translation['text149'] . '☠️</span></div>
                                                            <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_from dashboard_mobile_calls2_message_from_has_icon"><span>' . $translation['text150'] . ' 🤟</span></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="dashboard_mobile_calls2_message_item dashboard_mobile_calls2_message_item2">
                                                    <div class="dashboard_mobile_calls2_message_inner">
                                                        <div class="dashboard_mobile_calls2_message_inner_top"><img src="/images/icons/icon_mobile_calls2_face_from_big.png" alt=""><span>' . $translation['text160'] . '</span></div>
                                                        <div class="dashboard_mobile_calls2_message_inner_bottom">
                                                            <div class="dashboard_mobile_calls2_message_inner_bottom_time">' . $translation['text69'] . ' 30.08.22 16:47</div>
                                                            <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_from dashboard_mobile_calls2_message_from_has_icon"><span>' . $translation['text151'] . '</span></div>
                                                            <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_to dashboard_mobile_calls2_message_to_has_icon"><span>' . $translation['text152'] . '</span></div>
                                                            <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_from dashboard_mobile_calls2_message_from_has_icon"><span>' . $translation['text153'] . '</span></div>
                                                            <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_to dashboard_mobile_calls2_message_to_has_icon"><span>' . $translation['text154'] . ' 👊</span></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="dashboard_mobile_calls2_message_item dashboard_mobile_calls2_message_item3">
                                                    <div class="dashboard_mobile_calls2_message_inner">
                                                        <div class="dashboard_mobile_calls2_message_inner_top"><img src="/images/icons/icon_mobile_calls2_face_from_big.png" alt=""><span>' . $translation['text160'] . '</span></div>
                                                        <div class="dashboard_mobile_calls2_message_inner_bottom">
                                                            <div class="dashboard_mobile_calls2_message_inner_bottom_time">' . $translation['text69'] . ' 30.08.22 23:01</div>
                                                            <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_from"><span>' . $translation['text155'] . ' 🙌</span></div>
                                                            <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_from"><span>' . $translation['text156'] . '</span></div>
                                                            <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_from dashboard_mobile_calls2_message_from_has_icon">' . $translation['text157'] . ' <span class="as_link">' . $translation['text158'] . '</span></span></div>
                                                            <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_to dashboard_mobile_calls2_message_to_has_icon"><span>' . $translation['text159'] . ' ✊</span></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>';

        // при первом запуске
        if (empty($team_info['mobile_calls_print_messages'])) {
            // обновляем значение, что блок выведен. Повторно скрипт НЕ закускается
            $sql = "UPDATE `teams` SET `mobile_calls_print_messages` = {?} WHERE `id` = {?}";
            $this->db->query($sql, [1, $team_id]);

            // обновляем подсказки
            // список открытых
            $active_hints = [];

            // список доступных
            $list_hints = [];

            $hints_by_step = $this->getHintsByStep('mobile_calls', $lang_id);
            if ($hints_by_step) {
                foreach ($hints_by_step as $hint) {
                    $list_hints[] = $hint['id'];
                }
            }

            // сохраняем обновленный список подсказок
            $sql = "UPDATE `teams` SET `active_hints` = {?}, `list_hints` = {?}, `list_hints_title_lang_var` = {?}, `list_hints_text_lang_var` = {?} WHERE `id` = {?}";
            $this->db->query($sql, [json_encode($active_hints, JSON_UNESCAPED_UNICODE), json_encode($list_hints, JSON_UNESCAPED_UNICODE), 'text44', 'text45', $team_id]);
        }

        // обновляем, что текст напечатан для этого юзера
        if (isset($_COOKIE['hash'])) {
            $sql = "UPDATE `users` SET `mobile_calls_print_messages` = {?} WHERE `team_id` = {?} AND `hash` = {?}";
            $this->db->query($sql, [1, $team_id, $_COOKIE['hash']]);
        } else {
            $sql = "UPDATE `users` SET `mobile_calls_print_messages` = {?} WHERE `team_id` = {?} AND `ip` = {?}";
            $this->db->query($sql, [1, $team_id, $this->getIp()]);
        }

        return $return;
    }

    // databases - загрузить Bank Transactions. Первый экран
    private function uploadDatabasesBankTransactions($lang_id, $team_id)
    {
        $translation = $this->getWordsByPage('game', $lang_id);

        $team_info = $this->teamInfo($team_id);

        // если базы еще нет в списке доступных, то выводим, что еще недоступно
        $list_databases = json_decode($team_info['list_databases'], true);
        if (!in_array('bank_transactions', $list_databases)) {
            return $this->uploadDatabasesNoAccess($lang_id, 'text61', true);
        }

        // в противном случае возвращаем форму для ввода данных
        $return = [];

        $return['titles'] = '<div class="dashboard_tab_title dashboard_tab_title_can_click" data-tab="tab1" data-step="databases_start_four" data-database="false">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper">
                                        <svg width="19" height="21" viewBox="0 0 19 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 0H19V3L17.25 5H0V2L1.75 0ZM1.73684 2H3V3.2L2.26316 4H1V2.8L1.73684 2ZM6 2H4.73684L4 2.8V4H5.26316L6 3.2V2ZM7.73684 2H9V3.2L8.26316 4H7V2.8L7.73684 2ZM17 2H10.7368L10 2.8V4H16.2632L17 3.2V2Z" fill="#00F0FF"/><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 8H19V11L17.25 13H0V10L1.75 8ZM1.73684 10H3V11.2L2.26316 12H1V10.8L1.73684 10ZM6 10H4.73684L4 10.8V12H5.26316L6 11.2V10ZM7.73684 10H9V11.2L8.26316 12H7V10.8L7.73684 10ZM17 10H10.7368L10 10.8V12H16.2632L17 11.2V10Z" fill="#00F0FF"/><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 16H19V19L17.25 21H0V18L1.75 16ZM1.73684 18H3V19.2L2.26316 20H1V18.8L1.73684 18ZM6 18H4.73684L4 18.8V20H5.26316L6 19.2V18ZM7.73684 18H9V19.2L8.26316 20H7V18.8L7.73684 18ZM17 18H10.7368L10 18.8V20H16.2632L17 19.2V18Z" fill="#00F0FF"/><rect width="15" height="1" transform="matrix(1 0 0 -1 2 7)" fill="#00F0FF"/><rect width="15" height="1" transform="matrix(1 0 0 -1 2 15)" fill="#00F0FF"/></svg>
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text13'] . '</div>
                                </div>
                            </div>
                            <div class="dashboard_tab_title dashboard_tab_title_active" data-tab="bank_transactions1">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper">
                                        <svg width="21" height="25" viewBox="0 0 21 25" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.9362 10.4167H7.89453C7.7564 10.4167 7.62392 10.3619 7.52625 10.2642C7.42857 10.1665 7.3737 10.034 7.3737 9.89591C7.3737 9.75778 7.31882 9.6253 7.22115 9.52763C7.12347 9.42995 6.991 9.37508 6.85286 9.37508C6.71473 9.37508 6.58225 9.42995 6.48458 9.52763C6.3869 9.6253 6.33203 9.75778 6.33203 9.89591C6.33203 10.3103 6.49665 10.7077 6.78968 11.0008C7.0827 11.2938 7.48013 11.4584 7.89453 11.4584V11.9792C7.89453 12.1174 7.9494 12.2499 8.04708 12.3475C8.14475 12.4452 8.27723 12.5001 8.41536 12.5001C8.5535 12.5001 8.68597 12.4452 8.78365 12.3475C8.88132 12.2499 8.9362 12.1174 8.9362 11.9792V11.4584C9.3506 11.4584 9.74803 11.2938 10.0411 11.0008C10.3341 10.7077 10.4987 10.3103 10.4987 9.89591V9.37508C10.4987 8.96068 10.3341 8.56325 10.0411 8.27022C9.74803 7.9772 9.3506 7.81258 8.9362 7.81258H7.89453C7.7564 7.81258 7.62392 7.75771 7.52625 7.66003C7.42857 7.56236 7.3737 7.42988 7.3737 7.29175V6.77091C7.3737 6.63278 7.42857 6.5003 7.52625 6.40263C7.62392 6.30495 7.7564 6.25008 7.89453 6.25008H8.9362C9.07433 6.25008 9.20681 6.30495 9.30448 6.40263C9.40216 6.5003 9.45703 6.63278 9.45703 6.77091C9.45703 6.90905 9.5119 7.04152 9.60958 7.1392C9.70725 7.23687 9.83973 7.29175 9.97786 7.29175C10.116 7.29175 10.2485 7.23687 10.3461 7.1392C10.4438 7.04152 10.4987 6.90905 10.4987 6.77091C10.4987 6.35651 10.3341 5.95908 10.0411 5.66606C9.74803 5.37303 9.3506 5.20841 8.9362 5.20841V4.68758C8.9362 4.54945 8.88132 4.41697 8.78365 4.3193C8.68597 4.22162 8.5535 4.16675 8.41536 4.16675C8.27723 4.16675 8.14475 4.22162 8.04708 4.3193C7.9494 4.41697 7.89453 4.54945 7.89453 4.68758V5.20841C7.48013 5.20841 7.0827 5.37303 6.78968 5.66606C6.49665 5.95908 6.33203 6.35651 6.33203 6.77091V7.29175C6.33203 7.70615 6.49665 8.10357 6.78968 8.3966C7.0827 8.68963 7.48013 8.85425 7.89453 8.85425H8.9362C9.07433 8.85425 9.20681 8.90912 9.30448 9.00679C9.40216 9.10447 9.45703 9.23694 9.45703 9.37508V9.89591C9.45703 10.034 9.40216 10.1665 9.30448 10.2642C9.20681 10.3619 9.07433 10.4167 8.9362 10.4167Z" fill="#00F0FF"/><path d="M13.1029 16.6667H3.72786C3.58973 16.6667 3.45726 16.7216 3.35958 16.8193C3.2619 16.917 3.20703 17.0494 3.20703 17.1876C3.20703 17.3257 3.2619 17.4582 3.35958 17.5559C3.45726 17.6535 3.58973 17.7084 3.72786 17.7084H13.1029C13.241 17.7084 13.3735 17.6535 13.4712 17.5559C13.5688 17.4582 13.6237 17.3257 13.6237 17.1876C13.6237 17.0494 13.5688 16.917 13.4712 16.8193C13.3735 16.7216 13.241 16.6667 13.1029 16.6667V16.6667Z" fill="#00F0FF"/><path d="M13.1029 19.2708H3.72786C3.58973 19.2708 3.45726 19.3256 3.35958 19.4233C3.2619 19.521 3.20703 19.6535 3.20703 19.7916C3.20703 19.9297 3.2619 20.0622 3.35958 20.1599C3.45726 20.2575 3.58973 20.3124 3.72786 20.3124H13.1029C13.241 20.3124 13.3735 20.2575 13.4712 20.1599C13.5688 20.0622 13.6237 19.9297 13.6237 19.7916C13.6237 19.6535 13.5688 19.521 13.4712 19.4233C13.3735 19.3256 13.241 19.2708 13.1029 19.2708Z" fill="#00F0FF"/><path d="M13.1029 14.0625H3.72786C3.58973 14.0625 3.45726 14.1174 3.35958 14.215C3.2619 14.3127 3.20703 14.4452 3.20703 14.5833C3.20703 14.7215 3.2619 14.8539 3.35958 14.9516C3.45726 15.0493 3.58973 15.1042 3.72786 15.1042H13.1029C13.241 15.1042 13.3735 15.0493 13.4712 14.9516C13.5688 14.8539 13.6237 14.7215 13.6237 14.5833C13.6237 14.4452 13.5688 14.3127 13.4712 14.215C13.3735 14.1174 13.241 14.0625 13.1029 14.0625Z" fill="#00F0FF"/><path d="M18.311 5.20881e-06H2.68598C1.99531 5.20881e-06 1.33293 0.274372 0.844558 0.762748C0.356182 1.25112 0.0818155 1.9135 0.0818155 2.60417C0.0818155 26.4115 -0.0223511 24.6875 0.310982 24.9115C0.644315 25.1354 0.602649 25.0365 3.20682 24C5.83702 25.0417 5.74327 25.0677 6.00369 24.9635L8.41515 24L10.8266 24.9635C11.0922 25.0677 10.9985 25.0469 13.6287 24C16.2329 25.0417 16.087 25 16.2329 25C16.371 25 16.5035 24.9451 16.6011 24.8475C16.6988 24.7498 16.7537 24.6173 16.7537 24.4792V5.20834H20.3995C20.5377 5.20834 20.6701 5.15347 20.7678 5.05579C20.8655 4.95811 20.9204 4.82564 20.9204 4.68751V2.60417C20.9204 2.26175 20.8528 1.92269 20.7216 1.6064C20.5904 1.2901 20.3981 1.00279 20.1558 0.760904C19.9134 0.519017 19.6257 0.327304 19.3092 0.196739C18.9926 0.0661738 18.6534 -0.000679637 18.311 5.20881e-06V5.20881e-06ZM15.7068 23.7083C13.7016 22.9063 13.6912 22.849 13.4308 22.9531L11.0193 23.9167C8.49848 22.9115 8.49327 22.8438 8.22244 22.9531L5.81098 23.9167L3.39952 22.9531C3.13911 22.849 3.18077 22.8854 1.12348 23.7083V2.60417C1.12348 2.18977 1.2881 1.79234 1.58113 1.49932C1.87415 1.20629 2.27158 1.04167 2.68598 1.04167C17.2693 1.04167 16.2589 1.00521 16.1808 1.11459C15.5297 2.04688 15.7068 0.916674 15.7068 23.7083ZM19.8735 4.16667H16.7485V2.60417C16.7485 2.18977 16.9131 1.79234 17.2061 1.49932C17.4992 1.20629 17.8966 1.04167 18.311 1.04167C18.7254 1.04167 19.1228 1.20629 19.4158 1.49932C19.7089 1.79234 19.8735 2.18977 19.8735 2.60417V4.16667Z" fill="#00F0FF"/></svg>
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text60'] . '</div>
                                </div>
                            </div>';

        $return['back_btn'] = '<div class="dashboard_back_btn" data-back="databases_start_four" data-database="false">
                                    <img src="/images/back_bg.png" class="back_btn_bg" alt="">
                                    <div class="back_btn_text">' . $translation['text22'] . '</div>
                                </div>';

        $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_start_four" data-tab="tab1"></div>

                            <div class="dashboard_tab_content_item dashboard_tab_content_item_bank_transactions dashboard_tab_content_item_active" data-tab="bank_transactions1">
                                <div class="dashboard_bank_transactions1_inner">
                                    <div class="dashboard_bank_transactions1_inner_image_wrapper">
                                        <svg width="58" height="68" viewBox="0 0 58 68" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M24.7493 28.3332H21.916C21.5403 28.3332 21.18 28.184 20.9143 27.9183C20.6486 27.6526 20.4993 27.2923 20.4993 26.9166C20.4993 26.5409 20.3501 26.1805 20.0844 25.9148C19.8187 25.6492 19.4584 25.4999 19.0827 25.4999C18.707 25.4999 18.3466 25.6492 18.0809 25.9148C17.8153 26.1805 17.666 26.5409 17.666 26.9166C17.666 28.0437 18.1138 29.1247 18.9108 29.9218C19.7078 30.7188 20.7888 31.1666 21.916 31.1666V32.5832C21.916 32.959 22.0653 33.3193 22.3309 33.585C22.5966 33.8507 22.957 33.9999 23.3327 33.9999C23.7084 33.9999 24.0687 33.8507 24.3344 33.585C24.6001 33.3193 24.7493 32.959 24.7493 32.5832V31.1666C25.8765 31.1666 26.9575 30.7188 27.7545 29.9218C28.5516 29.1247 28.9993 28.0437 28.9993 26.9166V25.4999C28.9993 24.3727 28.5516 23.2917 27.7545 22.4947C26.9575 21.6977 25.8765 21.2499 24.7493 21.2499H21.916C21.5403 21.2499 21.18 21.1007 20.9143 20.835C20.6486 20.5693 20.4993 20.209 20.4993 19.8332V18.4166C20.4993 18.0409 20.6486 17.6805 20.9143 17.4148C21.18 17.1492 21.5403 16.9999 21.916 16.9999H24.7493C25.1251 16.9999 25.4854 17.1492 25.7511 17.4148C26.0168 17.6805 26.166 18.0409 26.166 18.4166C26.166 18.7923 26.3153 19.1526 26.5809 19.4183C26.8466 19.684 27.207 19.8332 27.5827 19.8332C27.9584 19.8332 28.3187 19.684 28.5844 19.4183C28.8501 19.1526 28.9993 18.7923 28.9993 18.4166C28.9993 17.2894 28.5516 16.2084 27.7545 15.4114C26.9575 14.6143 25.8765 14.1666 24.7493 14.1666V12.7499C24.7493 12.3742 24.6001 12.0139 24.3344 11.7482C24.0687 11.4825 23.7084 11.3333 23.3327 11.3333C22.957 11.3333 22.5966 11.4825 22.3309 11.7482C22.0653 12.0139 21.916 12.3742 21.916 12.7499V14.1666C20.7888 14.1666 19.7078 14.6143 18.9108 15.4114C18.1138 16.2084 17.666 17.2894 17.666 18.4166V19.8332C17.666 20.9604 18.1138 22.0414 18.9108 22.8384C19.7078 23.6355 20.7888 24.0832 21.916 24.0832H24.7493C25.1251 24.0832 25.4854 24.2325 25.7511 24.4982C26.0168 24.7639 26.166 25.1242 26.166 25.4999V26.9166C26.166 27.2923 26.0168 27.6526 25.7511 27.9183C25.4854 28.184 25.1251 28.3332 24.7493 28.3332Z" fill="#00F0FF"/><path d="M36.0827 45.3333H10.5827C10.207 45.3333 9.84662 45.4825 9.58095 45.7482C9.31527 46.0139 9.16602 46.3742 9.16602 46.7499C9.16602 47.1256 9.31527 47.486 9.58095 47.7516C9.84662 48.0173 10.207 48.1666 10.5827 48.1666H36.0827C36.4584 48.1666 36.8187 48.0173 37.0844 47.7516C37.3501 47.486 37.4994 47.1256 37.4994 46.7499C37.4994 46.3742 37.3501 46.0139 37.0844 45.7482C36.8187 45.4825 36.4584 45.3333 36.0827 45.3333V45.3333Z" fill="#00F0FF"/><path d="M36.0827 52.4167H10.5827C10.207 52.4167 9.84662 52.566 9.58095 52.8317C9.31527 53.0974 9.16602 53.4577 9.16602 53.8334C9.16602 54.2091 9.31527 54.5695 9.58095 54.8351C9.84662 55.1008 10.207 55.2501 10.5827 55.2501H36.0827C36.4584 55.2501 36.8187 55.1008 37.0844 54.8351C37.3501 54.5695 37.4994 54.2091 37.4994 53.8334C37.4994 53.4577 37.3501 53.0974 37.0844 52.8317C36.8187 52.566 36.4584 52.4167 36.0827 52.4167Z" fill="#00F0FF"/><path d="M36.0827 38.25H10.5827C10.207 38.25 9.84662 38.3993 9.58095 38.6649C9.31527 38.9306 9.16602 39.2909 9.16602 39.6667C9.16602 40.0424 9.31527 40.4027 9.58095 40.6684C9.84662 40.9341 10.207 41.0833 10.5827 41.0833H36.0827C36.4584 41.0833 36.8187 40.9341 37.0844 40.6684C37.3501 40.4027 37.4994 40.0424 37.4994 39.6667C37.4994 39.2909 37.3501 38.9306 37.0844 38.6649C36.8187 38.3993 36.4584 38.25 36.0827 38.25Z" fill="#00F0FF"/><path d="M50.2502 1.4168e-05H7.75025C5.87163 1.4168e-05 4.06996 0.746292 2.74157 2.07467C1.41319 3.40306 0.666913 5.20473 0.666913 7.08335C0.666913 71.8392 0.38358 67.15 1.29025 67.7592C2.19691 68.3684 2.08358 68.0992 9.16691 65.28C16.3211 68.1134 16.0661 68.1842 16.7744 67.9009L23.3336 65.28L29.8927 67.9009C30.6152 68.1842 30.3602 68.1275 37.5144 65.28C44.5977 68.1134 44.2011 68 44.5977 68C44.9735 68 45.3338 67.8508 45.5995 67.5851C45.8652 67.3194 46.0144 66.9591 46.0144 66.5834V14.1667H55.9311C56.3068 14.1667 56.6671 14.0174 56.9328 13.7517C57.1985 13.4861 57.3477 13.1257 57.3477 12.75V7.08335C57.3477 6.15196 57.1641 5.22971 56.8072 4.3694C56.4503 3.50908 55.9273 2.72759 55.2681 2.06966C54.6088 1.41173 53.8263 0.890266 52.9653 0.535129C52.1042 0.179993 51.1816 -0.00184861 50.2502 1.4168e-05V1.4168e-05ZM43.1669 64.4867C37.7127 62.305 37.6844 62.1492 36.9761 62.4325L30.4169 65.0534C23.5602 62.3192 23.5461 62.135 22.8094 62.4325L16.2502 65.0534L9.69108 62.4325C8.98275 62.1492 9.09608 62.2484 3.50025 64.4867V7.08335C3.50025 5.95618 3.94801 4.87517 4.74504 4.07814C5.54207 3.28111 6.62308 2.83335 7.75025 2.83335C47.4169 2.83335 44.6686 2.73418 44.4561 3.03168C42.6852 5.56751 43.1669 2.49335 43.1669 64.4867ZM54.5002 11.3333H46.0002V7.08335C46.0002 5.95618 46.448 4.87517 47.245 4.07814C48.0421 3.28111 49.1231 2.83335 50.2502 2.83335C51.3774 2.83335 52.4584 3.28111 53.2554 4.07814C54.0525 4.87517 54.5002 5.95618 54.5002 7.08335V11.3333Z" fill="#00F0FF"/></svg>
                                    </div>
                                    <div class="dashboard_car_register1_inner_title">' . $translation['text209'] . '</div>
                                    <div class="dashboard_car_register1_inner_text">' . $translation['text210'] . '</div>
                                    <div class="dashboard_car_register1_fields_top dashboard_dashboard_bank_transactions1_fields_top">
                                        <div class="dashboard_car_register1_input_wrapper dashboard_bank_transactions1_input_wrapper_digits">
                                            <div class="dashboard_car_register1_input_border_left"></div>
                                            <input type="text" placeholder="' . $translation['text211'] . '" autocomplete="off" class="dashboard_bank_transactions1_digits">
                                            <div class="dashboard_bank_transactions1_digits_error error_text_database_car_register">' . $translation['text86'] . '</div>
                                        </div>
                                        <div class="dashboard_car_register1_input_wrapper dashboard_bank_transactions1_input_wrapper_amount">
                                            <div class="dashboard_car_register1_input_border_right"></div>
                                            <input type="text" placeholder="' . $translation['text212'] . '" autocomplete="off" class="dashboard_bank_transactions1_amount">
                                            <div class="dashboard_bank_transactions1_amount_error error_text_database_car_register">' . $translation['text86'] . '</div>
                                        </div>
                                    </div>
                                    <div class="dashboard_car_register1_fields_bottom">
                                        <div class="dashboard_car_register1_input_wrapper dashboard_bank_transactions1_input_wrapper_date">
                                            <div class="dashboard_car_register1_input_border_left"></div>
                                            <div class="dashboard_car_register1_input_border_right"></div>
                                            <input type="text" placeholder="' . $translation['text213'] . '" autocomplete="off" class="dashboard_bank_transactions1_date" value="' . ((!empty($team_info['bank_transactions_date']) && $team_info['bank_transactions_date'] != '0000-00-00' && !is_null($team_info['bank_transactions_date'])) ? $this->fromEngDatetimeToRus($team_info['bank_transactions_date']) : '') . '">
                                            <div class="dashboard_bank_transactions1_date_error error_text_database_car_register">' . $translation['text86'] . '</div>
                                        </div>
                                        <script>
                                            $(function() {
                                                // datepicker
                                                $(".dashboard_bank_transactions1_date").datepicker({
                                                    dateFormat: "dd.mm.yy",
                                                    dayNamesShort: ["' . $translation['text67'] . '", "' . $translation['text68'] . '", "' . $translation['text69'] . '", "' . $translation['text70'] . '", "' . $translation['text71'] . '", "' . $translation['text72'] . '", "' . $translation['text73'] . '"],
                                                    dayNamesMin: ["' . $translation['text67'] . '", "' . $translation['text68'] . '", "' . $translation['text69'] . '", "' . $translation['text70'] . '", "' . $translation['text71'] . '", "' . $translation['text72'] . '", "' . $translation['text73'] . '"],
                                                    monthNames: ["' . $translation['text74'] . '", "' . $translation['text75'] . '", "' . $translation['text76'] . '", "' . $translation['text77'] . '", "' . $translation['text78'] . '", "' . $translation['text79'] . '", "' . $translation['text80'] . '", "' . $translation['text81'] . '", "' . $translation['text82'] . '", "' . $translation['text83'] . '", "' . $translation['text84'] . '", "' . $translation['text85'] . '"],
                                                    changeMonth: false,
                                                    //showAnim: "clip",
                                                    showAnim: "",
                                                    onSelect: function(dateText) {
                                                        // сохраняем выбор
                                                        var formData = new FormData();
                                                        formData.append("op", "saveTeamTextField");
                                                        formData.append("field", "bank_transactions_date");
                                                        formData.append("val", dateText);

                                                        $.ajax({
                                                            url: "/ajax/ajax.php",
                                                            type: "POST",
                                                            dataType: "json",
                                                            cache: false,
                                                            contentType: false,
                                                            processData: false,
                                                            data: formData,
                                                            success: function(json) {
                                                                // socket
                                                                var message = {
                                                                    "op": "databasesBankTransactionsUpdateDate",
                                                                    "parameters": {
                                                                        "date": dateText,
                                                                        "user_id": $("#section_game").length ? $("#section_game").attr("data-user-id") : 0,
                                                                        "team_id": $("#section_game").length ? $("#section_game").attr("data-team-id") : 0
                                                                    }
                                                                };
                                                                sendMessageSocket(JSON.stringify(message)); 
                                                            },
                                                            error: function(xhr, ajaxOptions, thrownError) {    
                                                                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                                            }
                                                        });
                                                    },
                                                    beforeShow: function() {
                                                        if (!is_touch_device()) {
                                                            var pageSize = getPageSize();
                                                            var windowWidth = pageSize[2];
                                                            if (windowWidth < 1800) {
                                                                $("body").removeClass("body_desktop_scale").css("transform", "scale(1)");

                                                                setTimeout(function() {
                                                                    var pageSize = getPageSize();
                                                                    var windowWidth = pageSize[0];

                                                                    var koef = parseFloat((windowWidth / 1920).toFixed(2)) + 0.01;

                                                                    $("body").addClass("body_desktop_scale").css("transform", "scale(" + koef + ")");
                                                                    //$("body").css("transform", "scale(" + koef + ")");

                                                                    var curDatepickerPosition = parseFloat($(".ui-datepicker").css("left"));
                                                                    var differentDatepickerPosition = (1920 - windowWidth) / 2;
                                                                    $(".ui-datepicker").css("left", (curDatepickerPosition + differentDatepickerPosition + 7) + "px");
                                                                }, 1);
                                                            }
                                                        }
                                                    }
                                                });
                                            });
                                        </script>
                                    </div>
                                    <div class="btn_wrapper btn_wrapper_blue dashboard_bank_transactions1_search">
                                        <div class="btn btn_blue">
                                            <span>' . $translation['text66'] . '</span>
                                        </div>
                                        <div class="btn_border_top"></div>
                                        <div class="btn_border_bottom"></div>
                                        <div class="btn_border_left"></div>
                                        <div class="btn_border_left_arcle"></div>
                                        <div class="btn_border_right"></div>
                                        <div class="btn_border_right_arcle"></div>
                                        <div class="btn_bg_top_line"></div>
                                        <div class="btn_bg_bottom_line"></div>
                                        <div class="btn_bg_triangle_left"></div>
                                        <div class="btn_bg_triangle_right"></div>
                                        <div class="btn_circles_top">
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                        </div>
                                        <div class="btn_circles_bottom">
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                            <div class="btn_circle"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>';

        return $return;
    }

    // databases - загрузить Bank Transactions. Результаты
    private function uploadDatabasesBankTransactionsSuccess($lang_id, $team_id)
    {
        $translation = $this->getWordsByPage('game', $lang_id);

        $team_info = $this->teamInfo($team_id);

        $return = [];

        $return['titles'] = '<div class="dashboard_tab_title dashboard_tab_title_can_click" data-tab="tab1" data-step="databases_start_four" data-database="false">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper">
                                        <svg width="19" height="21" viewBox="0 0 19 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 0H19V3L17.25 5H0V2L1.75 0ZM1.73684 2H3V3.2L2.26316 4H1V2.8L1.73684 2ZM6 2H4.73684L4 2.8V4H5.26316L6 3.2V2ZM7.73684 2H9V3.2L8.26316 4H7V2.8L7.73684 2ZM17 2H10.7368L10 2.8V4H16.2632L17 3.2V2Z" fill="#00F0FF"/><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 8H19V11L17.25 13H0V10L1.75 8ZM1.73684 10H3V11.2L2.26316 12H1V10.8L1.73684 10ZM6 10H4.73684L4 10.8V12H5.26316L6 11.2V10ZM7.73684 10H9V11.2L8.26316 12H7V10.8L7.73684 10ZM17 10H10.7368L10 10.8V12H16.2632L17 11.2V10Z" fill="#00F0FF"/><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 16H19V19L17.25 21H0V18L1.75 16ZM1.73684 18H3V19.2L2.26316 20H1V18.8L1.73684 18ZM6 18H4.73684L4 18.8V20H5.26316L6 19.2V18ZM7.73684 18H9V19.2L8.26316 20H7V18.8L7.73684 18ZM17 18H10.7368L10 18.8V20H16.2632L17 19.2V18Z" fill="#00F0FF"/><rect width="15" height="1" transform="matrix(1 0 0 -1 2 7)" fill="#00F0FF"/><rect width="15" height="1" transform="matrix(1 0 0 -1 2 15)" fill="#00F0FF"/></svg>
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text13'] . '</div>
                                </div>
                            </div>
                            <div class="dashboard_tab_title" data-tab="bank_transactions1">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper">
                                        <svg width="21" height="25" viewBox="0 0 21 25" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.9362 10.4167H7.89453C7.7564 10.4167 7.62392 10.3619 7.52625 10.2642C7.42857 10.1665 7.3737 10.034 7.3737 9.89591C7.3737 9.75778 7.31882 9.6253 7.22115 9.52763C7.12347 9.42995 6.991 9.37508 6.85286 9.37508C6.71473 9.37508 6.58225 9.42995 6.48458 9.52763C6.3869 9.6253 6.33203 9.75778 6.33203 9.89591C6.33203 10.3103 6.49665 10.7077 6.78968 11.0008C7.0827 11.2938 7.48013 11.4584 7.89453 11.4584V11.9792C7.89453 12.1174 7.9494 12.2499 8.04708 12.3475C8.14475 12.4452 8.27723 12.5001 8.41536 12.5001C8.5535 12.5001 8.68597 12.4452 8.78365 12.3475C8.88132 12.2499 8.9362 12.1174 8.9362 11.9792V11.4584C9.3506 11.4584 9.74803 11.2938 10.0411 11.0008C10.3341 10.7077 10.4987 10.3103 10.4987 9.89591V9.37508C10.4987 8.96068 10.3341 8.56325 10.0411 8.27022C9.74803 7.9772 9.3506 7.81258 8.9362 7.81258H7.89453C7.7564 7.81258 7.62392 7.75771 7.52625 7.66003C7.42857 7.56236 7.3737 7.42988 7.3737 7.29175V6.77091C7.3737 6.63278 7.42857 6.5003 7.52625 6.40263C7.62392 6.30495 7.7564 6.25008 7.89453 6.25008H8.9362C9.07433 6.25008 9.20681 6.30495 9.30448 6.40263C9.40216 6.5003 9.45703 6.63278 9.45703 6.77091C9.45703 6.90905 9.5119 7.04152 9.60958 7.1392C9.70725 7.23687 9.83973 7.29175 9.97786 7.29175C10.116 7.29175 10.2485 7.23687 10.3461 7.1392C10.4438 7.04152 10.4987 6.90905 10.4987 6.77091C10.4987 6.35651 10.3341 5.95908 10.0411 5.66606C9.74803 5.37303 9.3506 5.20841 8.9362 5.20841V4.68758C8.9362 4.54945 8.88132 4.41697 8.78365 4.3193C8.68597 4.22162 8.5535 4.16675 8.41536 4.16675C8.27723 4.16675 8.14475 4.22162 8.04708 4.3193C7.9494 4.41697 7.89453 4.54945 7.89453 4.68758V5.20841C7.48013 5.20841 7.0827 5.37303 6.78968 5.66606C6.49665 5.95908 6.33203 6.35651 6.33203 6.77091V7.29175C6.33203 7.70615 6.49665 8.10357 6.78968 8.3966C7.0827 8.68963 7.48013 8.85425 7.89453 8.85425H8.9362C9.07433 8.85425 9.20681 8.90912 9.30448 9.00679C9.40216 9.10447 9.45703 9.23694 9.45703 9.37508V9.89591C9.45703 10.034 9.40216 10.1665 9.30448 10.2642C9.20681 10.3619 9.07433 10.4167 8.9362 10.4167Z" fill="#00F0FF"/><path d="M13.1029 16.6667H3.72786C3.58973 16.6667 3.45726 16.7216 3.35958 16.8193C3.2619 16.917 3.20703 17.0494 3.20703 17.1876C3.20703 17.3257 3.2619 17.4582 3.35958 17.5559C3.45726 17.6535 3.58973 17.7084 3.72786 17.7084H13.1029C13.241 17.7084 13.3735 17.6535 13.4712 17.5559C13.5688 17.4582 13.6237 17.3257 13.6237 17.1876C13.6237 17.0494 13.5688 16.917 13.4712 16.8193C13.3735 16.7216 13.241 16.6667 13.1029 16.6667V16.6667Z" fill="#00F0FF"/><path d="M13.1029 19.2708H3.72786C3.58973 19.2708 3.45726 19.3256 3.35958 19.4233C3.2619 19.521 3.20703 19.6535 3.20703 19.7916C3.20703 19.9297 3.2619 20.0622 3.35958 20.1599C3.45726 20.2575 3.58973 20.3124 3.72786 20.3124H13.1029C13.241 20.3124 13.3735 20.2575 13.4712 20.1599C13.5688 20.0622 13.6237 19.9297 13.6237 19.7916C13.6237 19.6535 13.5688 19.521 13.4712 19.4233C13.3735 19.3256 13.241 19.2708 13.1029 19.2708Z" fill="#00F0FF"/><path d="M13.1029 14.0625H3.72786C3.58973 14.0625 3.45726 14.1174 3.35958 14.215C3.2619 14.3127 3.20703 14.4452 3.20703 14.5833C3.20703 14.7215 3.2619 14.8539 3.35958 14.9516C3.45726 15.0493 3.58973 15.1042 3.72786 15.1042H13.1029C13.241 15.1042 13.3735 15.0493 13.4712 14.9516C13.5688 14.8539 13.6237 14.7215 13.6237 14.5833C13.6237 14.4452 13.5688 14.3127 13.4712 14.215C13.3735 14.1174 13.241 14.0625 13.1029 14.0625Z" fill="#00F0FF"/><path d="M18.311 5.20881e-06H2.68598C1.99531 5.20881e-06 1.33293 0.274372 0.844558 0.762748C0.356182 1.25112 0.0818155 1.9135 0.0818155 2.60417C0.0818155 26.4115 -0.0223511 24.6875 0.310982 24.9115C0.644315 25.1354 0.602649 25.0365 3.20682 24C5.83702 25.0417 5.74327 25.0677 6.00369 24.9635L8.41515 24L10.8266 24.9635C11.0922 25.0677 10.9985 25.0469 13.6287 24C16.2329 25.0417 16.087 25 16.2329 25C16.371 25 16.5035 24.9451 16.6011 24.8475C16.6988 24.7498 16.7537 24.6173 16.7537 24.4792V5.20834H20.3995C20.5377 5.20834 20.6701 5.15347 20.7678 5.05579C20.8655 4.95811 20.9204 4.82564 20.9204 4.68751V2.60417C20.9204 2.26175 20.8528 1.92269 20.7216 1.6064C20.5904 1.2901 20.3981 1.00279 20.1558 0.760904C19.9134 0.519017 19.6257 0.327304 19.3092 0.196739C18.9926 0.0661738 18.6534 -0.000679637 18.311 5.20881e-06V5.20881e-06ZM15.7068 23.7083C13.7016 22.9063 13.6912 22.849 13.4308 22.9531L11.0193 23.9167C8.49848 22.9115 8.49327 22.8438 8.22244 22.9531L5.81098 23.9167L3.39952 22.9531C3.13911 22.849 3.18077 22.8854 1.12348 23.7083V2.60417C1.12348 2.18977 1.2881 1.79234 1.58113 1.49932C1.87415 1.20629 2.27158 1.04167 2.68598 1.04167C17.2693 1.04167 16.2589 1.00521 16.1808 1.11459C15.5297 2.04688 15.7068 0.916674 15.7068 23.7083ZM19.8735 4.16667H16.7485V2.60417C16.7485 2.18977 16.9131 1.79234 17.2061 1.49932C17.4992 1.20629 17.8966 1.04167 18.311 1.04167C18.7254 1.04167 19.1228 1.20629 19.4158 1.49932C19.7089 1.79234 19.8735 2.18977 19.8735 2.60417V4.16667Z" fill="#00F0FF"/></svg>
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text60'] . '</div>
                                </div>
                            </div>
                            <div class="dashboard_tab_title dashboard_tab_title_active" data-tab="bank_transactions2">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper">
                                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M19.6429 3.14282H2.35714C1.05533 3.14282 0 4.19815 0 5.49996V16.4999C0 17.8018 1.05533 18.8571 2.35714 18.8571H19.6429C20.9447 18.8571 22 17.8018 22 16.4999V5.49996C22 4.19815 20.9447 3.14282 19.6429 3.14282ZM20.4285 16.4999C20.4285 16.9339 20.0768 17.2857 19.6428 17.2857H2.35714C1.92319 17.2857 1.57141 16.9339 1.57141 16.4999V11.7857H20.4285V16.4999ZM20.4285 10.2142H1.57141V8.64283H20.4285V10.2142ZM20.4285 7.07138H1.57141V5.49996C1.57141 5.06601 1.92319 4.71423 2.35714 4.71423H19.6429C20.0768 4.71423 20.4286 5.06601 20.4286 5.49996V7.07138H20.4285Z" fill="#00F0FF"/><path d="M18.0712 13.3572H14.9283C14.4944 13.3572 14.1426 13.709 14.1426 14.1429C14.1426 14.5769 14.4944 14.9286 14.9283 14.9286H18.0712C18.5051 14.9286 18.8569 14.5769 18.8569 14.1429C18.8569 13.709 18.5051 13.3572 18.0712 13.3572Z" fill="#00F0FF"/></svg>
                                    </div>
                                    <div class="dashboard_tab_title_text">Visa *5684</div>
                                </div>
                            </div>';

        $return['back_btn'] = '<div class="dashboard_back_btn" data-back="databases_start_four" data-database="false">
                                    <img src="/images/back_bg.png" class="back_btn_bg" alt="">
                                    <div class="back_btn_text">' . $translation['text22'] . '</div>
                                </div>';

        $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_bank_transactions dashboard_tab_content_item_active" data-tab="bank_transactions2">
                                <div class="dashboard_bank_transactions2_inner">
                                    <div class="dashboard_bank_transactions2_inner_title">' . $translation['text60'] . '</div>
                                    <div class="dashboard_bank_transactions2_inner_text">' . $translation['text215'] . '</div>
                                    <div class="dashboard_bank_transactions2_table_wrapper">
                                        <div class="dashboard_bank_transactions2_table_title">' . $translation['text216'] . ' ' . date('d.m.Y') . '</div>
                                        <div class="dashboard_bank_transactions2_table_thead">
                                            <div class="dashboard_bank_transactions2_table_tr">
                                                <div class="dashboard_bank_transactions2_table_td">' . $translation['text217'] . '</div>
                                                <div class="dashboard_bank_transactions2_table_td">' . $translation['text218'] . '</div>
                                                <div class="dashboard_bank_transactions2_table_td">' . $translation['text219'] . '</div>
                                            </div>
                                        </div>
                                        <div class="dashboard_bank_transactions2_table">
                                            <div class="dashboard_bank_transactions2_table_tbody">
                                                <div class="dashboard_bank_transactions2_table_tr">
                                                    <div class="dashboard_bank_transactions2_table_td">' . $translation['text220'] . '</div>
                                                    <div class="dashboard_bank_transactions2_table_td">95,00</div>
                                                    <div class="dashboard_bank_transactions2_table_td"></div>
                                                </div>
                                                <div class="dashboard_bank_transactions2_table_tr">
                                                    <div class="dashboard_bank_transactions2_table_td">' . $translation['text221'] . '</div>
                                                    <div class="dashboard_bank_transactions2_table_td"></div>
                                                    <div class="dashboard_bank_transactions2_table_td">500,00</div>
                                                </div>
                                                <div class="dashboard_bank_transactions2_table_tr">
                                                    <div class="dashboard_bank_transactions2_table_td">' . $translation['text222'] . '</div>
                                                    <div class="dashboard_bank_transactions2_table_td">2 402 000,00</div>
                                                    <div class="dashboard_bank_transactions2_table_td"></div>
                                                </div>
                                                <div class="dashboard_bank_transactions2_table_tr">
                                                    <div class="dashboard_bank_transactions2_table_td">' . $translation['text223'] . '</div>
                                                    <div class="dashboard_bank_transactions2_table_td">100 000,00</div>
                                                    <div class="dashboard_bank_transactions2_table_td"></div>
                                                </div>
                                                <div class="dashboard_bank_transactions2_table_tr">
                                                    <div class="dashboard_bank_transactions2_table_td">' . $translation['text224'] . '</div>
                                                    <div class="dashboard_bank_transactions2_table_td">410,00</div>
                                                    <div class="dashboard_bank_transactions2_table_td"></div>
                                                </div>
                                                <div class="dashboard_bank_transactions2_table_tr">
                                                    <div class="dashboard_bank_transactions2_table_td">' . $translation['text225'] . '</div>
                                                    <div class="dashboard_bank_transactions2_table_td">154,70</div>
                                                    <div class="dashboard_bank_transactions2_table_td"></div>
                                                </div>
                                                <div class="dashboard_bank_transactions2_table_tr">
                                                    <div class="dashboard_bank_transactions2_table_td">' . $translation['text226'] . '</div>
                                                    <div class="dashboard_bank_transactions2_table_td">52,00</div>
                                                    <div class="dashboard_bank_transactions2_table_td"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>';

        return $return;
    }





/* TOOLS */
    // загрузить конкретный экран (с переключателем табов) для tools
    public function uploadTypeTabsToolsStep($step, $lang_id, $team_id)
    {
        switch ($step) {
            case 'no_access': $return = $this->uploadToolsNoAccess($lang_id, $team_id); break;
            case 'tools_start_four': $return = $this->uploadToolsStartFour($lang_id, $team_id); break;
            case 'advanced_search_engine': $return = $this->uploadToolsAdvancedSearchEngine($lang_id, $team_id); break;
            case 'symbol_decoder': $return = $this->uploadToolsSymbolDecoder($lang_id, $team_id); break;
            case '3d_building_scan': $return = $this->uploadToolsThreeDBuildingScan($lang_id, $team_id); break;
            case 'secret_office': $return = $this->uploadToolsSecretOffice($lang_id, $team_id); break;
            
            default: $return = $this->uploadToolsNoAccess($lang_id, $team_id); break;
        }

        return $return;
    }

    // tools - нет доступа
    private function uploadToolsNoAccess($lang_id, $team_id)
    {
        $translation = $this->getWordsByPage('game', $lang_id);

        $return = [];

        // после принятия миссии меняется текст НЕдоступности
        /*$sql = "SELECT `id` FROM `team_history_action` WHERE `team_id` = {?} AND `action_id` = {?}";
        $isset_accept_mission = (int) $this->db->selectCell($sql, [$team_id, 39]);*/
        $sql = "SELECT `view_gem` FROM `teams` WHERE `id` = {?}";
        $isset_accept_mission = (int) $this->db->selectCell($sql, [$team_id]);
        if (!empty($isset_accept_mission)) {
            $tools_no_access_text = $translation['text172'];
        } else {
            $tools_no_access_text = $translation['text40'];
        }

        $return['titles'] = '<div class="dashboard_tab_title dashboard_tab_title_active" data-tab="tab1">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper">
                                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.75 0H17.75V5L16 7H0V2L1.75 0Z" fill="#00F0FF"/><path d="M1.75 9H17.75V10.5L16 12.5H0V11L1.75 9Z" fill="#00F0FF"/><path d="M1.75 14H17.75V15.5L16 17.5H0V16L1.75 14Z" fill="#00F0FF"/></svg>
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text14'] . '</div>
                                </div>
                            </div>';

        $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_no_access dashboard_tab_content_item_active" data-tab="tab1">
                                <div class="dashboard_tab_content_item_no_access_inner">
                                    <img src="/images/tab_no_access_bg.png" class="dashboard_tab_content_item_no_access_bg" alt="">
                                    <div class="dashboard_tab_content_item_no_access_skew_line_top"></div>
                                    <div class="dashboard_tab_content_item_no_access_skew_line_bottom"></div>
                                </div>
                                <div class="dashboard_tab_content_item_no_access_inner_va">
                                    <div class="dashboard_tab_content_item_no_access_title">
                                        <img src="/images/tab_no_access_border_left.png" class="tab_no_access_border_left" alt="">
                                        <img src="/images/tab_no_access_border_left.png" class="tab_no_access_border_right" alt="">
                                        <div class="dashboard_tab_content_item_no_access_title_text">' . $translation['text39'] . '</div>
                                        <img src="/images/dashboard_tab_content_item_no_access_line_left.png" class="dashboard_tab_content_item_no_access_line_left" alt="">
                                        <img src="/images/dashboard_tab_content_item_no_access_line_right.png" class="dashboard_tab_content_item_no_access_line_right" alt="">
                                        <img src="/images/dashboard_tab_content_item_no_access_line_left2.png" class="dashboard_tab_content_item_no_access_line_left2" alt="">
                                        <img src="/images/dashboard_tab_content_item_no_access_line_right2.png" class="dashboard_tab_content_item_no_access_line_right2" alt="">
                                    </div>
                                    <div class="dashboard_tab_content_item_no_access_subtitle">' . $tools_no_access_text . '</div>
                                </div>
                            </div>';

        return $return;
    }

    // tools - первый экран - список 4-ех tools
    private function uploadToolsStartFour($lang_id, $team_id)
{
    $translation = $this->getWordsByPage('game', $lang_id);

    $return = [];

    // ---------------- TAB TITLE ----------------
    $return['titles'] = '
    <div class="dashboard_tab_title dashboard_tab_title_active cyber-panel
                flex items-center gap-4 p-4 rounded-xl bg-cyan-500/10 border border-cyan-500/30 
                cursor-pointer hover:scale-105 transition-all"
         data-tab="tab1">
        <div class="dashboard_tab_title_inner flex items-center gap-3">
            <div class="dashboard_tab_title_img_wrapper">
                <svg width="22" height="22" viewBox="0 0 18 18" fill="none">
                    <path d="M1.75 0H17.75V5L16 7H0V2L1.75 0Z" fill="#00F0FF"/>
                    <path d="M1.75 9H17.75V10.5L16 12.5H0V11L1.75 9Z" fill="#00F0FF"/>
                    <path d="M1.75 14H17.75V15.5L16 17.5H0V16L1.75 14Z" fill="#00F0FF"/>
                </svg>
            </div>
            <div class="dashboard_tab_title_text text-2xl font-bold neon-text">
                ' . $translation['text14'] . '
            </div>
        </div>
    </div>';

    // ---------------- TOOLS CONTENT ----------------
    $return['content'] = '
    <div class="dashboard_tab_content_item dashboard_tab_content_item_start_four_tools 
                dashboard_tab_content_item_active p-4"
         data-tab="tab1">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            <!-- ADVANCED SEARCH ENGINE -->
            <div class="dashboard_tab_content_item_start_four_inner_item_tools cyber-panel
                        border border-blue-500/30 bg-blue-500/20 rounded-xl cursor-pointer 
                        overflow-hidden hover:scale-105 transition-all flex flex-col justify-between"
                 data-tools="advanced_search_engine">

                <div class="p-4 text-center flex-1">
                    <img src="/images/database_personal_files_top_bg.png" 
                         class="w-full h-24 object-cover rounded-md mb-4" alt="">
                    <div class="text-xl font-semibold text-blue-400 mb-2 uppercase">
                        ' . $translation['text185'] . '
                    </div>
                    <img src="/images/tools_advanced_search_engine_icon.png" 
                         class="h-20 mx-auto" alt="">
                </div>

                <button class="w-full border border-current text-blue-400 py-2 mt-3 hover:bg-blue-500/10 rounded-lg">
                    Открыть
                </button>
            </div>

            <!-- GPS COORDINATES -->
            <div class="dashboard_tab_content_item_start_four_inner_item_tools cyber-panel
                        border border-green-500/30 bg-green-500/20 rounded-xl cursor-pointer 
                        overflow-hidden hover:scale-105 transition-all flex flex-col justify-between"
                 data-tools="gps_coordinates">

                <div class="p-4 text-center flex-1">
                    <img src="/images/database_car_register_top_bg.png" 
                         class="w-full h-24 object-cover rounded-md mb-4" alt="">
                    <div class="text-xl font-semibold text-green-400 mb-2 uppercase">
                        ' . $translation['text186'] . '
                    </div>
                    <img src="/images/tools_gps_coordinates_icon.png" 
                         class="h-20 mx-auto" alt="">
                </div>

                <button class="w-full border border-current text-green-400 py-2 mt-3 hover:bg-green-500/10 rounded-lg">
                    Открыть
                </button>
            </div>

            <!-- SYMBOL DECODER -->
            <div class="dashboard_tab_content_item_start_four_inner_item_tools cyber-panel
                        border border-purple-500/30 bg-purple-500/20 rounded-xl cursor-pointer 
                        overflow-hidden hover:scale-105 transition-all flex flex-col justify-between"
                 data-tools="symbol_decoder">

                <div class="p-4 text-center flex-1">
                    <img src="/images/database_mobile_calls_top_bg.png" 
                         class="w-full h-24 object-cover rounded-md mb-4" alt="">
                    <div class="text-xl font-semibold text-purple-400 mb-2 uppercase">
                        ' . $translation['text187'] . '
                    </div>
                    <img src="/images/tools_symbol_decoder_icon.png" 
                         class="h-20 mx-auto" alt="">
                </div>

                <button class="w-full border border-current text-purple-400 py-2 mt-3 hover:bg-purple-500/10 rounded-lg">
                    Запустить
                </button>
            </div>

            <!-- 3D BUILDING SCAN -->
            <div class="dashboard_tab_content_item_start_four_inner_item_tools cyber-panel
                        border border-red-500/30 bg-red-500/20 rounded-xl cursor-pointer 
                        overflow-hidden hover:scale-105 transition-all flex flex-col justify-between"
                 data-tools="3d_building_scan">

                <div class="p-4 text-center flex-1">
                    <img src="/images/database_bank_transactions_top_bg.png" 
                         class="w-full h-24 object-cover rounded-md mb-4" alt="">
                    <div class="text-xl font-semibold text-red-400 mb-2 uppercase">
                        ' . $translation['text188'] . '
                    </div>
                    <img src="/images/tools_3d_building_scan_icon.png" 
                         class="h-20 mx-auto" alt="">
                </div>

                <button class="w-full border border-current text-red-400 py-2 mt-3 hover:bg-red-500/10 rounded-lg">
                    Запустить
                </button>
            </div>

        </div>
    </div>';

    return $return;
}

    


    // tools - Advanced Search Engine
    private function uploadToolsAdvancedSearchEngine($lang_id, $team_id)
    {
        $translation = $this->getWordsByPage('game', $lang_id);

        $team_info = $this->teamInfo($team_id);

        $return = [];

        $return['titles'] = '<div class="dashboard_tab_title dashboard_tab_title_active dashboard_tab_title_can_click_tools" data-tab="tab1" data-step="tools_start_four" data-tools="false">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper">
                                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.75 0H17.75V5L16 7H0V2L1.75 0Z" fill="#00F0FF"/><path d="M1.75 9H17.75V10.5L16 12.5H0V11L1.75 9Z" fill="#00F0FF"/><path d="M1.75 14H17.75V15.5L16 17.5H0V16L1.75 14Z" fill="#00F0FF"/></svg>
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text14'] . '</div>
                                </div>
                            </div>';

        if (empty($team_info['tools_advanced_search_engine_access'])) {
            $return['back_btn'] = '<div class="tools_back_btn" data-back="tools_start_four" data-tools="false">
                                        <img src="/images/back_bg.png" class="back_btn_bg" alt="">
                                        <div class="back_btn_text">' . $translation['text22'] . '</div>
                                    </div>';

            $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_no_access dashboard_tab_content_item_active" data-tab="tab1">
                                    <div class="dashboard_tab_content_item_no_access_inner">
                                        <img src="/images/tab_no_access_bg.png" class="dashboard_tab_content_item_no_access_bg" alt="">
                                        <div class="dashboard_tab_content_item_no_access_skew_line_top"></div>
                                        <div class="dashboard_tab_content_item_no_access_skew_line_bottom"></div>
                                    </div>
                                    <div class="dashboard_tab_content_item_no_access_inner_va">
                                        <div class="dashboard_tab_content_item_no_access_title">
                                            <img src="/images/tab_no_access_border_left.png" class="tab_no_access_border_left" alt="">
                                            <img src="/images/tab_no_access_border_left.png" class="tab_no_access_border_right" alt="">
                                            <div class="dashboard_tab_content_item_no_access_title_text">' . $translation['text39'] . '</div>
                                            <img src="/images/dashboard_tab_content_item_no_access_line_left.png" class="dashboard_tab_content_item_no_access_line_left" alt="">
                                            <img src="/images/dashboard_tab_content_item_no_access_line_right.png" class="dashboard_tab_content_item_no_access_line_right" alt="">
                                            <img src="/images/dashboard_tab_content_item_no_access_line_left2.png" class="dashboard_tab_content_item_no_access_line_left2" alt="">
                                            <img src="/images/dashboard_tab_content_item_no_access_line_right2.png" class="dashboard_tab_content_item_no_access_line_right2" alt="">
                                        </div>
                                        <div class="dashboard_tab_content_item_no_access_subtitle">' . $translation['text189'] . '</div>
                                    </div>
                                </div>';
        }

        return $return;
    }

    // tools - Symbol Decoder
    private function uploadToolsSymbolDecoder($lang_id, $team_id)
    {
        $translation = $this->getWordsByPage('game', $lang_id);

        $team_info = $this->teamInfo($team_id);

        $return = [];

        if (empty($team_info['tools_symbol_decoder_access'])) {
            $return['titles'] = '<div class="dashboard_tab_title dashboard_tab_title_active dashboard_tab_title_can_click_tools" data-tab="tab1" data-step="tools_start_four" data-tools="false">
                                    <div class="dashboard_tab_title_active_skew_right"></div>
                                    <div class="dashboard_tab_title_inner">
                                        <div class="dashboard_tab_title_img_wrapper">
                                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.75 0H17.75V5L16 7H0V2L1.75 0Z" fill="#00F0FF"/><path d="M1.75 9H17.75V10.5L16 12.5H0V11L1.75 9Z" fill="#00F0FF"/><path d="M1.75 14H17.75V15.5L16 17.5H0V16L1.75 14Z" fill="#00F0FF"/></svg>
                                        </div>
                                        <div class="dashboard_tab_title_text">' . $translation['text14'] . '</div>
                                    </div>
                                </div>';

            $return['back_btn'] = '<div class="tools_back_btn" data-back="tools_start_four" data-tools="false">
                                        <img src="/images/back_bg.png" class="back_btn_bg" alt="">
                                        <div class="back_btn_text">' . $translation['text22'] . '</div>
                                    </div>';

            $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_no_access dashboard_tab_content_item_active" data-tab="tab1">
                                    <div class="dashboard_tab_content_item_no_access_inner">
                                        <img src="/images/tab_no_access_bg.png" class="dashboard_tab_content_item_no_access_bg" alt="">
                                        <div class="dashboard_tab_content_item_no_access_skew_line_top"></div>
                                        <div class="dashboard_tab_content_item_no_access_skew_line_bottom"></div>
                                    </div>
                                    <div class="dashboard_tab_content_item_no_access_inner_va">
                                        <div class="dashboard_tab_content_item_no_access_title">
                                            <img src="/images/tab_no_access_border_left.png" class="tab_no_access_border_left" alt="">
                                            <img src="/images/tab_no_access_border_left.png" class="tab_no_access_border_right" alt="">
                                            <div class="dashboard_tab_content_item_no_access_title_text">' . $translation['text39'] . '</div>
                                            <img src="/images/dashboard_tab_content_item_no_access_line_left.png" class="dashboard_tab_content_item_no_access_line_left" alt="">
                                            <img src="/images/dashboard_tab_content_item_no_access_line_right.png" class="dashboard_tab_content_item_no_access_line_right" alt="">
                                            <img src="/images/dashboard_tab_content_item_no_access_line_left2.png" class="dashboard_tab_content_item_no_access_line_left2" alt="">
                                            <img src="/images/dashboard_tab_content_item_no_access_line_right2.png" class="dashboard_tab_content_item_no_access_line_right2" alt="">
                                        </div>
                                        <div class="dashboard_tab_content_item_no_access_subtitle">' . $translation['text189'] . '</div>
                                    </div>
                                </div>';
        } else {
            $return['titles'] = '<div class="dashboard_tab_title dashboard_tab_title_can_click_tools" data-tab="tab1" data-step="tools_start_four" data-tools="false">
                                    <div class="dashboard_tab_title_active_skew_right"></div>
                                    <div class="dashboard_tab_title_inner">
                                        <div class="dashboard_tab_title_img_wrapper">
                                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.75 0H17.75V5L16 7H0V2L1.75 0Z" fill="#00F0FF"/><path d="M1.75 9H17.75V10.5L16 12.5H0V11L1.75 9Z" fill="#00F0FF"/><path d="M1.75 14H17.75V15.5L16 17.5H0V16L1.75 14Z" fill="#00F0FF"/></svg>
                                        </div>
                                        <div class="dashboard_tab_title_text">' . $translation['text14'] . '</div>
                                    </div>
                                </div>
                                <div class="dashboard_tab_title dashboard_tab_title_active" data-tab="tab2">
                                    <div class="dashboard_tab_title_active_skew_right"></div>
                                    <div class="dashboard_tab_title_inner">
                                        <div class="dashboard_tab_title_img_wrapper" style="margin: -4px 0 0;">
                                            <svg width="24" height="32" viewBox="0 0 24 32" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M23.4171 13.7969C23.4166 13.3215 23.2275 12.8658 22.8913 12.5297C22.5552 12.1936 22.0995 12.0045 21.6241 12.0039H21.0331V10.1977C21.0331 10.0744 20.9842 9.95614 20.897 9.86896C20.8098 9.78179 20.6916 9.73281 20.5683 9.73281C20.445 9.73281 20.3268 9.78179 20.2396 9.86896C20.1524 9.95614 20.1034 10.0744 20.1034 10.1977V12.0039H18.7278V10.1977C18.7278 10.0744 18.6788 9.95614 18.5916 9.86896C18.5044 9.78179 18.3862 9.73281 18.2629 9.73281C18.1396 9.73281 18.0214 9.78179 17.9342 9.86896C17.8471 9.95614 17.7981 10.0744 17.7981 10.1977V12.0039H16.5085V10.1977C16.5085 10.0744 16.4595 9.95614 16.3723 9.86896C16.2851 9.78179 16.1669 9.73281 16.0436 9.73281C15.9203 9.73281 15.8021 9.78179 15.7149 9.86896C15.6278 9.95614 15.5788 10.0744 15.5788 10.1977V12.0039H14.1595V9.91981C14.1593 9.6561 14.0545 9.40324 13.8681 9.21674C13.6816 9.03023 13.4288 8.92533 13.1651 8.92505H11.9543C11.6906 8.92533 11.4377 9.03023 11.2512 9.21672C11.0647 9.40321 10.9598 9.65607 10.9596 9.91981V12.0039H9.29117V11.5752C9.29117 11.4519 9.2422 11.3337 9.15502 11.2465C9.06785 11.1593 8.94961 11.1103 8.82633 11.1103C8.70305 11.1103 8.58481 11.1593 8.49764 11.2465C8.41046 11.3337 8.36149 11.4519 8.36149 11.5752V12.0039H6.88195V11.4031C6.88195 11.2798 6.83298 11.1615 6.74581 11.0744C6.65863 10.9872 6.54039 10.9382 6.41711 10.9382C6.29383 10.9382 6.17559 10.9872 6.08842 11.0744C6.00124 11.1615 5.95227 11.2798 5.95227 11.4031V12.0039H3.89766V10.1977C3.89766 10.0744 3.84868 9.95614 3.76151 9.86896C3.67433 9.78179 3.5561 9.73281 3.43281 9.73281C3.30953 9.73281 3.19129 9.78179 3.10412 9.86896C3.01694 9.95614 2.96797 10.0744 2.96797 10.1977V12.0039H2.37695C1.9016 12.0045 1.44588 12.1936 1.10975 12.5297C0.773629 12.8658 0.584547 13.3215 0.583984 13.7969V29.2642C0.584547 29.7396 0.773629 30.1953 1.10975 30.5314C1.44588 30.8675 1.9016 31.0566 2.37695 31.0572H17.5614C17.6288 31.0625 17.6966 31.053 17.76 31.0295C17.8233 31.0059 17.8809 30.9689 17.9285 30.9209L23.2809 25.5686C23.4939 25.3555 23.4139 25.9274 23.4171 13.7969ZM11.889 9.91875C11.8892 9.90156 11.8961 9.88512 11.9083 9.87296C11.9204 9.8608 11.9369 9.85388 11.9541 9.85367H13.1648C13.182 9.85388 13.1984 9.86081 13.2105 9.87298C13.2226 9.88515 13.2295 9.90158 13.2296 9.91875V12.0028H11.8893L11.889 9.91875ZM1.51367 29.2642V13.7969C1.51395 13.568 1.605 13.3486 1.76683 13.1868C1.92867 13.0249 2.14808 12.9339 2.37695 12.9336H3.42378C4.78431 12.9376 7.48944 12.9307 8.83589 12.9336H20.5765H21.6233C21.8522 12.9339 22.0716 13.0249 22.2335 13.1868C22.3953 13.3486 22.4863 13.568 22.4866 13.7969V24.7752H18.9174C18.4421 24.7757 17.9864 24.9648 17.6502 25.3009C17.3141 25.6371 17.125 26.0928 17.1245 26.5681L17.1268 30.1275H2.37695C2.14808 30.1272 1.92867 30.0362 1.76683 29.8743C1.605 29.7125 1.51395 29.4931 1.51367 29.2642ZM19.9279 27.6078L18.0547 29.4807V26.5689C18.055 26.34 18.146 26.1206 18.3078 25.9588C18.4697 25.797 18.6891 25.7059 18.918 25.7056H21.8305L19.9279 27.6078Z" fill="#00F0FF"/><path d="M3.43359 3.75708C3.55686 3.75701 3.67505 3.70801 3.76221 3.62085C3.84937 3.53369 3.89837 3.4155 3.89844 3.29224V1.39966C3.89844 1.27637 3.84946 1.15814 3.76229 1.07096C3.67511 0.983789 3.55688 0.934814 3.43359 0.934814C3.31031 0.934814 3.19207 0.983789 3.1049 1.07096C3.01772 1.15814 2.96875 1.27637 2.96875 1.39966V3.29224C2.96875 3.41552 3.01772 3.53376 3.1049 3.62093C3.19207 3.70811 3.31031 3.75708 3.43359 3.75708Z" fill="#00F0FF"/><path d="M3.43359 8.94315C3.55686 8.94308 3.67505 8.89408 3.76221 8.80692C3.84937 8.71976 3.89837 8.60157 3.89844 8.4783V5.1062C3.89844 4.98292 3.84946 4.86468 3.76229 4.77751C3.67511 4.69033 3.55688 4.64136 3.43359 4.64136C3.31031 4.64136 3.19207 4.69033 3.1049 4.77751C3.01772 4.86468 2.96875 4.98292 2.96875 5.1062V8.47963C2.9691 8.60269 3.01823 8.72058 3.10537 8.80747C3.1925 8.89436 3.31054 8.94315 3.43359 8.94315Z" fill="#00F0FF"/><path d="M6.41797 4.16986C6.54125 4.16986 6.65949 4.12089 6.74666 4.03371C6.83384 3.94654 6.88281 3.8283 6.88281 3.70502V1.39966C6.88281 1.27637 6.83384 1.15814 6.74666 1.07096C6.65949 0.983789 6.54125 0.934814 6.41797 0.934814C6.29468 0.934814 6.17645 0.983789 6.08928 1.07096C6.0021 1.15814 5.95312 1.27637 5.95312 1.39966V3.70502C5.95312 3.8283 6.0021 3.94654 6.08928 4.03371C6.17645 4.12089 6.29468 4.16986 6.41797 4.16986Z" fill="#00F0FF"/><path d="M8.9043 4.16986C8.96535 4.16989 9.02581 4.15789 9.08223 4.13455C9.13864 4.1112 9.1899 4.07696 9.23307 4.03379C9.27624 3.99062 9.31048 3.93936 9.33383 3.88294C9.35718 3.82653 9.36918 3.76607 9.36914 3.70502V1.39966C9.36914 1.27637 9.32017 1.15814 9.23299 1.07096C9.14582 0.983789 9.02758 0.934814 8.9043 0.934814C8.78101 0.934814 8.66278 0.983789 8.5756 1.07096C8.48843 1.15814 8.43945 1.27637 8.43945 1.39966V3.70502C8.43945 3.8283 8.48843 3.94654 8.5756 4.03371C8.66278 4.12089 8.78101 4.16986 8.9043 4.16986Z" fill="#00F0FF"/><path d="M11.1016 3.75708C11.2248 3.75701 11.343 3.70801 11.4302 3.62085C11.5173 3.53369 11.5663 3.4155 11.5664 3.29224V1.39966C11.5664 1.27637 11.5174 1.15814 11.4303 1.07096C11.3431 0.983789 11.2248 0.934814 11.1016 0.934814C10.9783 0.934814 10.86 0.983789 10.7729 1.07096C10.6857 1.15814 10.6367 1.27637 10.6367 1.39966V3.29224C10.6367 3.41552 10.6857 3.53376 10.7729 3.62093C10.86 3.70811 10.9783 3.75708 11.1016 3.75708Z" fill="#00F0FF"/><path d="M13.4551 5.06475C13.5783 5.06468 13.6965 5.01568 13.7837 4.92852C13.8709 4.84136 13.9199 4.72317 13.9199 4.59991V1.39966C13.9199 1.27637 13.8709 1.15814 13.7838 1.07096C13.6966 0.983789 13.5784 0.934814 13.4551 0.934814C13.3318 0.934814 13.2136 0.983789 13.1264 1.07096C13.0392 1.15814 12.9902 1.27637 12.9902 1.39966V4.59991C12.9902 4.72319 13.0392 4.84143 13.1264 4.9286C13.2136 5.01578 13.3318 5.06475 13.4551 5.06475Z" fill="#00F0FF"/><path d="M15.957 2.89672C16.0803 2.89672 16.1985 2.84775 16.2857 2.76057C16.3729 2.6734 16.4219 2.55516 16.4219 2.43188V1.39966C16.4219 1.27637 16.3729 1.15814 16.2857 1.07096C16.1985 0.983789 16.0803 0.934814 15.957 0.934814C15.8337 0.934814 15.7155 0.983789 15.6283 1.07096C15.5412 1.15814 15.4922 1.27637 15.4922 1.39966V2.43188C15.4922 2.55516 15.5412 2.6734 15.6283 2.76057C15.7155 2.84775 15.8337 2.89672 15.957 2.89672Z" fill="#00F0FF"/><path d="M18.2637 2.89672C18.387 2.89672 18.5052 2.84775 18.5924 2.76057C18.6795 2.6734 18.7285 2.55516 18.7285 2.43188V1.39966C18.7285 1.27637 18.6795 1.15814 18.5924 1.07096C18.5052 0.983789 18.387 0.934814 18.2637 0.934814C18.1404 0.934814 18.0222 0.983789 17.935 1.07096C17.8478 1.15814 17.7988 1.27637 17.7988 1.39966V2.43188C17.7988 2.55516 17.8478 2.6734 17.935 2.76057C18.0222 2.84775 18.1404 2.89672 18.2637 2.89672Z" fill="#00F0FF"/><path d="M20.5684 2.89672C20.6294 2.89676 20.6899 2.88476 20.7463 2.86141C20.8027 2.83806 20.854 2.80382 20.8971 2.76065C20.9403 2.71748 20.9745 2.66622 20.9979 2.60981C21.0212 2.55339 21.0332 2.49293 21.0332 2.43188V1.39966C21.0332 1.27637 20.9842 1.15814 20.8971 1.07096C20.8099 0.983789 20.6916 0.934814 20.5684 0.934814C20.4451 0.934814 20.3268 0.983789 20.2397 1.07096C20.1525 1.15814 20.1035 1.27637 20.1035 1.39966V2.43188C20.1035 2.55516 20.1525 2.6734 20.2397 2.76057C20.3268 2.84775 20.4451 2.89672 20.5684 2.89672Z" fill="#00F0FF"/><path d="M20.5684 8.94325C20.6916 8.94325 20.8099 8.89427 20.8971 8.8071C20.9842 8.71992 21.0332 8.60169 21.0332 8.4784V4.22681C21.0332 4.10352 20.9842 3.98529 20.8971 3.89811C20.8099 3.81094 20.6916 3.76196 20.5684 3.76196C20.4451 3.76196 20.3268 3.81094 20.2397 3.89811C20.1525 3.98529 20.1035 4.10352 20.1035 4.22681V8.4784C20.1036 8.60167 20.1526 8.71986 20.2397 8.80702C20.3269 8.89418 20.4451 8.94318 20.5684 8.94325Z" fill="#00F0FF"/><path d="M6.84138 10.0787H8.31719C8.55268 10.0784 8.77845 9.98473 8.94497 9.81822C9.11148 9.6517 9.20516 9.42593 9.20544 9.19044V5.87358C9.20516 5.63811 9.11148 5.41237 8.94495 5.2459C8.77842 5.07942 8.55266 4.98581 8.31719 4.9856H6.84138C6.60591 4.98581 6.38014 5.07942 6.21361 5.2459C6.04709 5.41237 5.95341 5.63811 5.95312 5.87358V9.19044C5.95341 9.42593 6.04708 9.6517 6.2136 9.81822C6.38012 9.98473 6.60588 10.0784 6.84138 10.0787ZM6.88281 5.91528H8.27575V9.149H6.88281V5.91528Z" fill="#00F0FF"/><path d="M15.5781 4.73876V7.96584C15.5784 8.23719 15.6863 8.49735 15.8782 8.68922C16.0701 8.8811 16.3302 8.98902 16.6016 8.9893H17.7039C17.9752 8.98894 18.2353 8.881 18.4271 8.68913C18.6189 8.49726 18.7268 8.23715 18.7271 7.96584V4.73876C18.7268 4.46748 18.6189 4.2074 18.4271 4.01557C18.2353 3.82375 17.9752 3.71586 17.7039 3.71558H16.6016C16.3303 3.71586 16.0702 3.82374 15.8783 4.01556C15.6864 4.20737 15.5785 4.46746 15.5781 4.73876ZM16.5078 4.73876C16.5079 4.71394 16.5178 4.69016 16.5354 4.67263C16.5529 4.65511 16.5768 4.64526 16.6016 4.64526H17.7039C17.7287 4.64533 17.7524 4.65521 17.77 4.67273C17.7875 4.69025 17.7974 4.71399 17.7974 4.73876V7.96584C17.7974 7.99067 17.7876 8.01447 17.7701 8.03205C17.7525 8.04963 17.7287 8.05954 17.7039 8.05961H16.6016C16.5767 8.05961 16.5529 8.04973 16.5353 8.03215C16.5177 8.01456 16.5078 7.99071 16.5078 7.96584V4.73876Z" fill="#00F0FF"/><path d="M13.4551 8.28096C13.5783 8.28089 13.6965 8.23189 13.7837 8.14473C13.8709 8.05757 13.9199 7.93938 13.9199 7.81611V6.46143C13.9199 6.33814 13.8709 6.21991 13.7838 6.13273C13.6966 6.04556 13.5784 5.99658 13.4551 5.99658C13.3318 5.99658 13.2136 6.04556 13.1264 6.13273C13.0392 6.21991 12.9902 6.33814 12.9902 6.46143V7.81611C12.9902 7.9394 13.0392 8.05763 13.1264 8.14481C13.2136 8.23198 13.3318 8.28096 13.4551 8.28096Z" fill="#00F0FF"/><path d="M11.1055 4.64136C10.9822 4.64143 10.864 4.69042 10.7769 4.77758C10.6897 4.86474 10.6407 4.98294 10.6406 5.1062V7.81558C10.6406 7.93886 10.6896 8.0571 10.7768 8.14427C10.8639 8.23145 10.9822 8.28042 11.1055 8.28042C11.2288 8.28042 11.347 8.23145 11.4342 8.14427C11.5213 8.0571 11.5703 7.93886 11.5703 7.81558V5.1062C11.5702 4.98294 11.5212 4.86474 11.4341 4.77758C11.3469 4.69042 11.2287 4.64143 11.1055 4.64136Z" fill="#00F0FF"/><path d="M16.3795 15.6296H7.61914C7.49586 15.6296 7.37762 15.6786 7.29045 15.7658C7.20327 15.853 7.1543 15.9712 7.1543 16.0945C7.1543 16.2178 7.20327 16.336 7.29045 16.4232C7.37762 16.5104 7.49586 16.5593 7.61914 16.5593H16.3795C16.5027 16.5593 16.621 16.5104 16.7082 16.4232C16.7953 16.336 16.8443 16.2178 16.8443 16.0945C16.8443 15.9712 16.7953 15.853 16.7082 15.7658C16.621 15.6786 16.5027 15.6296 16.3795 15.6296Z" fill="#00F0FF"/><path d="M18.3555 16.5593H20.5681C20.6914 16.5593 20.8096 16.5104 20.8968 16.4232C20.984 16.336 21.033 16.2178 21.033 16.0945C21.033 15.9712 20.984 15.853 20.8968 15.7658C20.8096 15.6786 20.6914 15.6296 20.5681 15.6296H18.3555C18.2322 15.6296 18.114 15.6786 18.0268 15.7658C17.9396 15.853 17.8906 15.9712 17.8906 16.0945C17.8906 16.2178 17.9396 16.336 18.0268 16.4232C18.114 16.5104 18.2322 16.5593 18.3555 16.5593Z" fill="#00F0FF"/><path d="M3.43359 16.5593H5.64625C5.76953 16.5593 5.88777 16.5104 5.97494 16.4232C6.06212 16.336 6.11109 16.2178 6.11109 16.0945C6.11109 15.9712 6.06212 15.853 5.97494 15.7658C5.88777 15.6786 5.76953 15.6296 5.64625 15.6296H3.43359C3.31031 15.6296 3.19207 15.6786 3.1049 15.7658C3.01772 15.853 2.96875 15.9712 2.96875 16.0945C2.96875 16.2178 3.01772 16.336 3.1049 16.4232C3.19207 16.5104 3.31031 16.5593 3.43359 16.5593Z" fill="#00F0FF"/><path d="M7.61914 20.1853H12.0232C12.1465 20.1853 12.2647 20.1363 12.3519 20.0492C12.4391 19.962 12.488 19.8437 12.488 19.7205C12.488 19.5972 12.4391 19.4789 12.3519 19.3918C12.2647 19.3046 12.1465 19.2556 12.0232 19.2556H7.61914C7.49586 19.2556 7.37762 19.3046 7.29045 19.3918C7.20327 19.4789 7.1543 19.5972 7.1543 19.7205C7.1543 19.8437 7.20327 19.962 7.29045 20.0492C7.37762 20.1363 7.49586 20.1853 7.61914 20.1853Z" fill="#00F0FF"/><path d="M18.3555 20.1853H20.5681C20.6914 20.1853 20.8096 20.1363 20.8968 20.0492C20.984 19.962 21.033 19.8437 21.033 19.7205C21.033 19.5972 20.984 19.4789 20.8968 19.3918C20.8096 19.3046 20.6914 19.2556 20.5681 19.2556H18.3555C18.2322 19.2556 18.114 19.3046 18.0268 19.3918C17.9396 19.4789 17.8906 19.5972 17.8906 19.7205C17.8906 19.8437 17.9396 19.962 18.0268 20.0492C18.114 20.1363 18.2322 20.1853 18.3555 20.1853Z" fill="#00F0FF"/><path d="M16.3807 19.2556H13.998C13.8748 19.2556 13.7565 19.3046 13.6694 19.3918C13.5822 19.4789 13.5332 19.5972 13.5332 19.7205C13.5332 19.8437 13.5822 19.962 13.6694 20.0492C13.7565 20.1363 13.8748 20.1853 13.998 20.1853H16.3807C16.504 20.1853 16.6222 20.1363 16.7094 20.0492C16.7966 19.962 16.8455 19.8437 16.8455 19.7205C16.8455 19.5972 16.7966 19.4789 16.7094 19.3918C16.6222 19.3046 16.504 19.2556 16.3807 19.2556Z" fill="#00F0FF"/><path d="M3.43359 20.1853H5.64625C5.76953 20.1853 5.88777 20.1363 5.97494 20.0492C6.06212 19.962 6.11109 19.8437 6.11109 19.7205C6.11109 19.5972 6.06212 19.4789 5.97494 19.3918C5.88777 19.3046 5.76953 19.2556 5.64625 19.2556H3.43359C3.31031 19.2556 3.19207 19.3046 3.1049 19.3918C3.01772 19.4789 2.96875 19.5972 2.96875 19.7205C2.96875 19.8437 3.01772 19.962 3.1049 20.0492C3.19207 20.1363 3.31031 20.1853 3.43359 20.1853Z" fill="#00F0FF"/><path d="M10.3421 22.884H7.60352C7.48023 22.884 7.362 22.933 7.27482 23.0202C7.18765 23.1074 7.13867 23.2256 7.13867 23.3489C7.13867 23.4722 7.18765 23.5904 7.27482 23.6776C7.362 23.7647 7.48023 23.8137 7.60352 23.8137H10.3421C10.4654 23.8137 10.5836 23.7647 10.6708 23.6776C10.758 23.5904 10.807 23.4722 10.807 23.3489C10.807 23.2256 10.758 23.1074 10.6708 23.0202C10.5836 22.933 10.4654 22.884 10.3421 22.884Z" fill="#00F0FF"/><path d="M14.8424 22.884H12.2871C12.1638 22.884 12.0456 22.933 11.9584 23.0202C11.8712 23.1074 11.8223 23.2256 11.8223 23.3489C11.8223 23.4722 11.8712 23.5904 11.9584 23.6776C12.0456 23.7647 12.1638 23.8137 12.2871 23.8137H14.8424C14.9657 23.8137 15.0839 23.7647 15.1711 23.6776C15.2583 23.5904 15.3073 23.4722 15.3073 23.3489C15.3073 23.2256 15.2583 23.1074 15.1711 23.0202C15.0839 22.933 14.9657 22.884 14.8424 22.884Z" fill="#00F0FF"/><path d="M5.66219 22.884H3.43359C3.31031 22.884 3.19207 22.933 3.1049 23.0202C3.01772 23.1074 2.96875 23.2256 2.96875 23.3489C2.96875 23.4722 3.01772 23.5904 3.1049 23.6776C3.19207 23.7647 3.31031 23.8137 3.43359 23.8137H5.66219C5.78547 23.8137 5.90371 23.7647 5.99088 23.6776C6.07806 23.5904 6.12703 23.4722 6.12703 23.3489C6.12703 23.2256 6.07806 23.1074 5.99088 23.0202C5.90371 22.933 5.78547 22.884 5.66219 22.884Z" fill="#00F0FF"/><path d="M14.8421 26.5098H12.3027C12.1795 26.5098 12.0612 26.5587 11.974 26.6459C11.8869 26.7331 11.8379 26.8513 11.8379 26.9746C11.8379 27.0979 11.8869 27.2161 11.974 27.3033C12.0612 27.3905 12.1795 27.4395 12.3027 27.4395H14.8421C14.9654 27.4395 15.0836 27.3905 15.1708 27.3033C15.258 27.2161 15.307 27.0979 15.307 26.9746C15.307 26.8513 15.258 26.7331 15.1708 26.6459C15.0836 26.5587 14.9654 26.5098 14.8421 26.5098Z" fill="#00F0FF"/><path d="M5.64625 26.5098H3.43359C3.31031 26.5098 3.19207 26.5587 3.1049 26.6459C3.01772 26.7331 2.96875 26.8513 2.96875 26.9746C2.96875 27.0979 3.01772 27.2161 3.1049 27.3033C3.19207 27.3905 3.31031 27.4395 3.43359 27.4395H5.64625C5.76953 27.4395 5.88777 27.3905 5.97494 27.3033C6.06212 27.2161 6.11109 27.0979 6.11109 26.9746C6.11109 26.8513 6.06212 26.7331 5.97494 26.6459C5.88777 26.5587 5.76953 26.5098 5.64625 26.5098Z" fill="#00F0FF"/><path d="M10.3285 26.5098H7.61914C7.49586 26.5098 7.37762 26.5587 7.29045 26.6459C7.20327 26.7331 7.1543 26.8513 7.1543 26.9746C7.1543 27.0979 7.20327 27.2161 7.29045 27.3033C7.37762 27.3905 7.49586 27.4395 7.61914 27.4395H10.3285C10.4518 27.4395 10.57 27.3905 10.6572 27.3033C10.7444 27.2161 10.7934 27.0979 10.7934 26.9746C10.7934 26.8513 10.7444 26.7331 10.6572 26.6459C10.57 26.5587 10.4518 26.5098 10.3285 26.5098Z" fill="#00F0FF"/></svg>
                                        </div>
                                        <div class="dashboard_tab_title_text">' . $translation['text227'] . '</div>
                                    </div>
                                </div>';

            $return['back_btn'] = '<div class="tools_back_btn" data-back="tools_start_four" data-tools="false">
                                        <img src="/images/back_bg.png" class="back_btn_bg" alt="">
                                        <div class="back_btn_text">' . $translation['text22'] . '</div>
                                    </div>';

            $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_tools_symbol_decoder dashboard_tab_content_item_active" data-tab="tools_symbol_decoder2">
                                        <div class="dashboard_tools_symbol_decoder_inner">
                                            <div class="dashboard_tools_symbol_decoder_inner_title">' . $translation['text227'] . '</div>
                                            <div class="dashboard_tools_symbol_decoder_inner_text">' . $translation['text228'] . '</div>
                                            <div class="dashboard_tools_symbol_decoder_inner_top">
                                                <div class="dashboard_tools_symbol_decoder_inner_left">www.scanme.top</div>
                                                <div class="dashboard_tools_symbol_decoder_inner_right">
                                                    <img src="/images/tools_symbol_decoder_phone.png" class="tools_symbol_decoder_phone" alt="">
                                                </div>
                                            </div>
                                            <div class="dashboard_tools_symbol_decoder_inner_bottom">
                                                <div class="dashboard_tools_symbol_decoder_inner_left">' . $translation['text229'] . '</div>
                                                <div class="dashboard_tools_symbol_decoder_inner_right">' . $translation['text230'] . '</div>
                                            </div>
                                        </div>
                                    </div>';
        }

        return $return;
    }

    // tools - 3d Building Scan
    private function uploadToolsThreeDBuildingScan($lang_id, $team_id)
    {
        $translation = $this->getWordsByPage('game', $lang_id);

        $team_info = $this->teamInfo($team_id);

        $return = [];

        $return['back_btn'] = '<div class="tools_back_btn" data-back="tools_start_four" data-tools="false">
                                    <img src="/images/back_bg.png" class="back_btn_bg" alt="">
                                    <div class="back_btn_text">' . $translation['text22'] . '</div>
                                </div>';

        if (empty($team_info['tools_3d_bulding_scan_access'])) {
            $return['titles'] = '<div class="dashboard_tab_title dashboard_tab_title_active dashboard_tab_title_can_click_tools" data-tab="tab1" data-step="tools_start_four" data-tools="false">
                                    <div class="dashboard_tab_title_active_skew_right"></div>
                                    <div class="dashboard_tab_title_inner">
                                        <div class="dashboard_tab_title_img_wrapper">
                                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.75 0H17.75V5L16 7H0V2L1.75 0Z" fill="#00F0FF"/><path d="M1.75 9H17.75V10.5L16 12.5H0V11L1.75 9Z" fill="#00F0FF"/><path d="M1.75 14H17.75V15.5L16 17.5H0V16L1.75 14Z" fill="#00F0FF"/></svg>
                                        </div>
                                        <div class="dashboard_tab_title_text">' . $translation['text14'] . '</div>
                                    </div>
                                </div>';

            $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_no_access dashboard_tab_content_item_active" data-tab="tab1">
                                    <div class="dashboard_tab_content_item_no_access_inner">
                                        <img src="/images/tab_no_access_bg.png" class="dashboard_tab_content_item_no_access_bg" alt="">
                                        <div class="dashboard_tab_content_item_no_access_skew_line_top"></div>
                                        <div class="dashboard_tab_content_item_no_access_skew_line_bottom"></div>
                                    </div>
                                    <div class="dashboard_tab_content_item_no_access_inner_va">
                                        <div class="dashboard_tab_content_item_no_access_title">
                                            <img src="/images/tab_no_access_border_left.png" class="tab_no_access_border_left" alt="">
                                            <img src="/images/tab_no_access_border_left.png" class="tab_no_access_border_right" alt="">
                                            <div class="dashboard_tab_content_item_no_access_title_text">' . $translation['text39'] . '</div>
                                            <img src="/images/dashboard_tab_content_item_no_access_line_left.png" class="dashboard_tab_content_item_no_access_line_left" alt="">
                                            <img src="/images/dashboard_tab_content_item_no_access_line_right.png" class="dashboard_tab_content_item_no_access_line_right" alt="">
                                            <img src="/images/dashboard_tab_content_item_no_access_line_left2.png" class="dashboard_tab_content_item_no_access_line_left2" alt="">
                                            <img src="/images/dashboard_tab_content_item_no_access_line_right2.png" class="dashboard_tab_content_item_no_access_line_right2" alt="">
                                        </div>
                                        <div class="dashboard_tab_content_item_no_access_subtitle">' . $translation['text189'] . '</div>
                                    </div>
                                </div>';
        } else {
            $return['titles'] = '<div class="dashboard_tab_title dashboard_tab_title_can_click_tools" data-tab="tab1" data-step="tools_start_four" data-tools="false">
                                    <div class="dashboard_tab_title_active_skew_right"></div>
                                    <div class="dashboard_tab_title_inner">
                                        <div class="dashboard_tab_title_img_wrapper">
                                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.75 0H17.75V5L16 7H0V2L1.75 0Z" fill="#00F0FF"/><path d="M1.75 9H17.75V10.5L16 12.5H0V11L1.75 9Z" fill="#00F0FF"/><path d="M1.75 14H17.75V15.5L16 17.5H0V16L1.75 14Z" fill="#00F0FF"/></svg>
                                        </div>
                                        <div class="dashboard_tab_title_text">' . $translation['text14'] . '</div>
                                    </div>
                                </div>
                                <div class="dashboard_tab_title dashboard_tab_title_active" data-tab="tab2">
                                    <div class="dashboard_tab_title_active_skew_right"></div>
                                    <div class="dashboard_tab_title_inner">
                                        <div class="dashboard_tab_title_img_wrapper" style="margin: -4px 0 0;">
                                            <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_881_922)"><path d="M27.4173 27.9999H23.9173C23.5953 27.9999 23.334 27.7386 23.334 27.4166V23.9166C23.334 23.5946 23.5953 23.3333 23.9173 23.3333H27.4173C27.7393 23.3333 28.0006 23.5946 28.0006 23.9166V27.4166C28.0006 27.7386 27.7393 27.9999 27.4173 27.9999ZM24.5007 26.8333H26.834V24.4999H24.5007V26.8333Z" fill="#00F0FF"/><path d="M4.08333 27.9999H0.583333C0.261333 27.9999 0 27.7386 0 27.4166V23.9166C0 23.5946 0.261333 23.3333 0.583333 23.3333H4.08333C4.40533 23.3333 4.66667 23.5946 4.66667 23.9166V27.4166C4.66667 27.7386 4.40533 27.9999 4.08333 27.9999ZM1.16667 26.8333H3.5V24.4999H1.16667V26.8333Z" fill="#00F0FF"/><path d="M27.4173 4.66667H23.9173C23.5953 4.66667 23.334 4.40533 23.334 4.08333V0.583333C23.334 0.261333 23.5953 0 23.9173 0H27.4173C27.7393 0 28.0006 0.261333 28.0006 0.583333V4.08333C28.0006 4.40533 27.7393 4.66667 27.4173 4.66667ZM24.5007 3.5H26.834V1.16667H24.5007V3.5Z" fill="#00F0FF"/><path d="M4.08333 4.66667H0.583333C0.261333 4.66667 0 4.40533 0 4.08333V0.583333C0 0.261333 0.261333 0 0.583333 0H4.08333C4.40533 0 4.66667 0.261333 4.66667 0.583333V4.08333C4.66667 4.40533 4.40533 4.66667 4.08333 4.66667ZM1.16667 3.5H3.5V1.16667H1.16667V3.5Z" fill="#00F0FF"/><path d="M2.33333 24.5C2.01133 24.5 1.75 24.2387 1.75 23.9167V21.5833C1.75 21.2613 2.01133 21 2.33333 21C2.65533 21 2.91667 21.2613 2.91667 21.5833V23.9167C2.91667 24.2387 2.65533 24.5 2.33333 24.5ZM2.33333 18.6667C2.01133 18.6667 1.75 18.4053 1.75 18.0833V15.75C1.75 15.428 2.01133 15.1667 2.33333 15.1667C2.65533 15.1667 2.91667 15.428 2.91667 15.75V18.0833C2.91667 18.4053 2.65533 18.6667 2.33333 18.6667ZM2.33333 12.8333C2.01133 12.8333 1.75 12.572 1.75 12.25V9.91667C1.75 9.59467 2.01133 9.33333 2.33333 9.33333C2.65533 9.33333 2.91667 9.59467 2.91667 9.91667V12.25C2.91667 12.572 2.65533 12.8333 2.33333 12.8333ZM2.33333 7C2.01133 7 1.75 6.73867 1.75 6.41667V4.08333C1.75 3.76133 2.01133 3.5 2.33333 3.5C2.65533 3.5 2.91667 3.76133 2.91667 4.08333V6.41667C2.91667 6.73867 2.65533 7 2.33333 7Z" fill="#00F0FF"/><path d="M25.6673 24.5C25.3453 24.5 25.084 24.2387 25.084 23.9167V21.5833C25.084 21.2613 25.3453 21 25.6673 21C25.9893 21 26.2506 21.2613 26.2506 21.5833V23.9167C26.2506 24.2387 25.9893 24.5 25.6673 24.5ZM25.6673 18.6667C25.3453 18.6667 25.084 18.4053 25.084 18.0833V15.75C25.084 15.428 25.3453 15.1667 25.6673 15.1667C25.9893 15.1667 26.2506 15.428 26.2506 15.75V18.0833C26.2506 18.4053 25.9893 18.6667 25.6673 18.6667ZM25.6673 12.8333C25.3453 12.8333 25.084 12.572 25.084 12.25V9.91667C25.084 9.59467 25.3453 9.33333 25.6673 9.33333C25.9893 9.33333 26.2506 9.59467 26.2506 9.91667V12.25C26.2506 12.572 25.9893 12.8333 25.6673 12.8333ZM25.6673 7C25.3453 7 25.084 6.73867 25.084 6.41667V4.08333C25.084 3.76133 25.3453 3.5 25.6673 3.5C25.9893 3.5 26.2506 3.76133 26.2506 4.08333V6.41667C26.2506 6.73867 25.9893 7 25.6673 7Z" fill="#00F0FF"/><path d="M23.9167 26.2499H21.5833C21.2613 26.2499 21 25.9886 21 25.6666C21 25.3446 21.2613 25.0833 21.5833 25.0833H23.9167C24.2387 25.0833 24.5 25.3446 24.5 25.6666C24.5 25.9886 24.2387 26.2499 23.9167 26.2499ZM18.0833 26.2499H15.75C15.428 26.2499 15.1667 25.9886 15.1667 25.6666C15.1667 25.3446 15.428 25.0833 15.75 25.0833H18.0833C18.4053 25.0833 18.6667 25.3446 18.6667 25.6666C18.6667 25.9886 18.4053 26.2499 18.0833 26.2499ZM12.25 26.2499H9.91667C9.59467 26.2499 9.33333 25.9886 9.33333 25.6666C9.33333 25.3446 9.59467 25.0833 9.91667 25.0833H12.25C12.572 25.0833 12.8333 25.3446 12.8333 25.6666C12.8333 25.9886 12.572 26.2499 12.25 26.2499ZM6.41667 26.2499H4.08333C3.76133 26.2499 3.5 25.9886 3.5 25.6666C3.5 25.3446 3.76133 25.0833 4.08333 25.0833H6.41667C6.73867 25.0833 7 25.3446 7 25.6666C7 25.9886 6.73867 26.2499 6.41667 26.2499Z" fill="#00F0FF"/><path d="M23.9167 2.91667H21.5833C21.2613 2.91667 21 2.65533 21 2.33333C21 2.01133 21.2613 1.75 21.5833 1.75H23.9167C24.2387 1.75 24.5 2.01133 24.5 2.33333C24.5 2.65533 24.2387 2.91667 23.9167 2.91667ZM18.0833 2.91667H15.75C15.428 2.91667 15.1667 2.65533 15.1667 2.33333C15.1667 2.01133 15.428 1.75 15.75 1.75H18.0833C18.4053 1.75 18.6667 2.01133 18.6667 2.33333C18.6667 2.65533 18.4053 2.91667 18.0833 2.91667ZM12.25 2.91667H9.91667C9.59467 2.91667 9.33333 2.65533 9.33333 2.33333C9.33333 2.01133 9.59467 1.75 9.91667 1.75H12.25C12.572 1.75 12.8333 2.01133 12.8333 2.33333C12.8333 2.65533 12.572 2.91667 12.25 2.91667ZM6.41667 2.91667H4.08333C3.76133 2.91667 3.5 2.65533 3.5 2.33333C3.5 2.01133 3.76133 1.75 4.08333 1.75H6.41667C6.73867 1.75 7 2.01133 7 2.33333C7 2.65533 6.73867 2.91667 6.41667 2.91667Z" fill="#00F0FF"/><path d="M14 13.125C13.939 13.125 13.878 13.1138 13.82 13.0913L7.32 10.5392C7.127 10.4637 7 10.2738 7 10.0625C7 9.85119 7.127 9.66131 7.32 9.58577L13.82 7.03369C13.935 6.98877 14.064 6.98877 14.179 7.03369L20.679 9.58577C20.873 9.66131 21 9.85119 21 10.0625C21 10.2738 20.873 10.4637 20.68 10.5392L14.18 13.0913C14.122 13.1138 14.061 13.125 14 13.125ZM8.893 10.0625L14 12.0674L19.107 10.0625L14 8.05758L8.893 10.0625Z" fill="#00F0FF"/><path d="M14 21C13.939 21 13.878 20.9891 13.82 20.9674L7.32 18.4945C7.127 18.4213 7 18.2374 7 18.0326V10.1196C7 9.84657 7.224 9.625 7.5 9.625C7.776 9.625 8 9.84657 8 10.1196V17.6933L14 19.9763L20 17.6933V10.1196C20 9.84657 20.224 9.625 20.5 9.625C20.776 9.625 21 9.84657 21 10.1196V18.0326C21 18.2374 20.873 18.4213 20.68 18.4945L14.18 20.9674C14.122 20.9891 14.061 21 14 21Z" fill="#00F0FF"/><path d="M14 21C13.7585 21 13.5625 20.7713 13.5625 20.4896V12.3229C13.5625 12.0412 13.7585 11.8125 14 11.8125C14.2415 11.8125 14.4375 12.0412 14.4375 12.3229V20.4896C14.4375 20.7713 14.2415 21 14 21Z" fill="#00F0FF"/></g><defs><clipPath id="clip0_881_922"><rect width="28" height="28" fill="white"/></clipPath></defs></svg>
                                        </div>
                                        <div class="dashboard_tab_title_text">' . $translation['text238'] . '</div>
                                    </div>
                                </div>';

            $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_tools_3d_scan dashboard_tab_content_item_active" data-tab="tools_3d_scan2">
                                    <div class="dashboard_tools_3d_scan_inner">
                                        <div class="dashboard_tools_3d_scan_inner_title">' . $translation['text188'] . '</div>
                                        <div class="dashboard_tools_3d_scan_inner_text">' . $translation['text239'] . '</div>
                                        <div class="dashboard_tools_3d_scan_inner_main">
                                            <div class="dashboard_tools_3d_scan_inner_main_left">
                                                <div class="dashboard_tools_3d_scan_inner_main_left_gauge_wrapper">
                                                    <div class="dashboard_tools_3d_scan_inner_main_left_gauge">
                                                        <div class="dashboard_tools_3d_scan_inner_main_left_value" data-value="' . $team_info['tools_building_scan_degree'] . '">' . $team_info['tools_building_scan_degree'] . 'k</div>
                                                        <div class="dashboard_tools_3d_scan_inner_main_left_gauge_center_circle">
                                                            <svg width="132" height="80" viewBox="0 0 132 80" fill="none" xmlns="http://www.w3.org/2000/svg"><g filter="url(#filter0_d_2127_1167)"><path d="M111.5 12.4999L125.5 7" stroke="white" stroke-width="3"/></g><path d="M79 24.5L64 23L69.5 36L79 24.5Z" fill="#01E0EE"/><g filter="url(#filter1_d_2127_1167)"><path d="M71 41.5C71 60.0015 56.0015 75 37.5 75C18.9985 75 4 60.0015 4 41.5C4 22.9985 18.9985 8 37.5 8C56.0015 8 71 22.9985 71 41.5Z" fill="#204972"/></g><circle cx="57" cy="33" r="3" fill="url(#paint0_radial_2127_1167)"/><defs><filter id="filter0_d_2127_1167" x="105.951" y="0.60376" width="25.0977" height="18.2922" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB"><feFlood flood-opacity="0" result="BackgroundImageFix"/><feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/><feOffset/><feGaussianBlur stdDeviation="2.5"/><feColorMatrix type="matrix" values="0 0 0 0 1 0 0 0 0 1 0 0 0 0 1 0 0 0 1 0"/><feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_2127_1167"/><feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_2127_1167" result="shape"/></filter><filter id="filter1_d_2127_1167" x="0" y="5" width="75" height="75" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB"><feFlood flood-opacity="0" result="BackgroundImageFix"/><feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/><feOffset dy="1"/><feGaussianBlur stdDeviation="2"/><feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0"/><feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_2127_1167"/><feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_2127_1167" result="shape"/></filter><radialGradient id="paint0_radial_2127_1167" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(57 33) rotate(90) scale(3)"><stop stop-color="#04A8B3"/><stop offset="1" stop-color="#00F0FF"/></radialGradient></defs></svg>
                                                        </div>
                                                        <div class="dashboard_tools_3d_scan_inner_main_left_gauge_clickable"></div>
                                                    </div>
                                                    <div class="dashboard_tools_3d_scan_inner_main_left_gauge_select_value">' . $translation['text240'] . '</div>
                                                    <div class="dashboard_tools_3d_scan_inner_main_left_gauge_cur_value">' . $translation['text241'] . ' <span>' . $team_info['tools_building_scan_degree'] . 'k</span></div>
                                                </div>
                                                <div class="dashboard_tools_3d_scan_inner_main_left_inputs_wrapper">
                                                    <div class="dashboard_tools_3d_scan_inner_main_left_input_wrapper dashboard_tools_3d_scan_inner_main_left_input_wrapper1">
                                                        <input type="text" autocomplete="off" value="' . (!empty($team_info['tools_building_scan_input1']) ? $team_info['tools_building_scan_input1'] : '') . '" class="tools_building_scan_input1" placeholder="0">
                                                        <div class="tools_3d_scan_input_arrow_up tools_3d_scan_input_arrow_up1"><svg width="27" height="13" viewBox="0 0 27 13" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M25.3365 12.4019L1.39258 12.4019L13.0225 0.999986L25.3365 12.4019Z" fill="#102348" fill-opacity="0.5" stroke="#00F0FF"/></svg></div>
                                                        <div class="tools_3d_scan_input_arrow_down tools_3d_scan_input_arrow_down1"><svg width="27" height="14" viewBox="0 0 27 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.39202 1L25.3359 1L13.706 12.4019L1.39202 1Z" fill="#FF0303" fill-opacity="0.5" stroke="#FF0303"/></svg></div>
                                                    </div>
                                                    <div class="dashboard_tools_3d_scan_inner_main_left_input_wrapper dashboard_tools_3d_scan_inner_main_left_input_wrapper2">
                                                        <input type="text" autocomplete="off" value="' . (!empty($team_info['tools_building_scan_input2']) ? $team_info['tools_building_scan_input2'] : '') . '" class="tools_building_scan_input2" placeholder="0">
                                                        <div class="tools_3d_scan_input_arrow_up tools_3d_scan_input_arrow_up2"><svg width="27" height="13" viewBox="0 0 27 13" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M25.3365 12.4019L1.39258 12.4019L13.0225 0.999986L25.3365 12.4019Z" fill="#102348" fill-opacity="0.5" stroke="#00F0FF"/></svg></div>
                                                        <div class="tools_3d_scan_input_arrow_down tools_3d_scan_input_arrow_down2"><svg width="27" height="14" viewBox="0 0 27 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.39202 1L25.3359 1L13.706 12.4019L1.39202 1Z" fill="#FF0303" fill-opacity="0.5" stroke="#FF0303"/></svg></div>
                                                    </div>
                                                    <div class="dashboard_tools_3d_scan_inner_main_left_input_wrapper dashboard_tools_3d_scan_inner_main_left_input_wrapper3">
                                                        <input type="text" autocomplete="off" value="' . (!empty($team_info['tools_building_scan_input3']) ? $team_info['tools_building_scan_input3'] : '') . '" class="tools_building_scan_input3" placeholder="0">
                                                        <div class="tools_3d_scan_input_arrow_up tools_3d_scan_input_arrow_up3"><svg width="27" height="13" viewBox="0 0 27 13" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M25.3365 12.4019L1.39258 12.4019L13.0225 0.999986L25.3365 12.4019Z" fill="#102348" fill-opacity="0.5" stroke="#00F0FF"/></svg></div>
                                                        <div class="tools_3d_scan_input_arrow_down tools_3d_scan_input_arrow_down3"><svg width="27" height="14" viewBox="0 0 27 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.39202 1L25.3359 1L13.706 12.4019L1.39202 1Z" fill="#FF0303" fill-opacity="0.5" stroke="#FF0303"/></svg></div>
                                                    </div>
                                                    <div class="dashboard_tools_3d_scan_inner_main_left_input_wrapper dashboard_tools_3d_scan_inner_main_left_input_wrapper4">
                                                        <input type="text" autocomplete="off" value="' . (!empty($team_info['tools_building_scan_input4']) ? $team_info['tools_building_scan_input4'] : '') . '" class="tools_building_scan_input4" placeholder="0">
                                                        <div class="tools_3d_scan_input_arrow_up tools_3d_scan_input_arrow_up4"><svg width="27" height="13" viewBox="0 0 27 13" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M25.3365 12.4019L1.39258 12.4019L13.0225 0.999986L25.3365 12.4019Z" fill="#102348" fill-opacity="0.5" stroke="#00F0FF"/></svg></div>
                                                        <div class="tools_3d_scan_input_arrow_down tools_3d_scan_input_arrow_down4"><svg width="27" height="14" viewBox="0 0 27 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.39202 1L25.3359 1L13.706 12.4019L1.39202 1Z" fill="#FF0303" fill-opacity="0.5" stroke="#FF0303"/></svg></div>
                                                    </div>
                                                    <div class="dashboard_tools_3d_scan_inner_main_left_input_wrapper dashboard_tools_3d_scan_inner_main_left_input_wrapper5">
                                                        <input type="text" autocomplete="off" value="' . (!empty($team_info['tools_building_scan_input5']) ? $team_info['tools_building_scan_input5'] : '') . '" class="tools_building_scan_input5" placeholder="0">
                                                        <div class="tools_3d_scan_input_arrow_up tools_3d_scan_input_arrow_up5"><svg width="27" height="13" viewBox="0 0 27 13" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M25.3365 12.4019L1.39258 12.4019L13.0225 0.999986L25.3365 12.4019Z" fill="#102348" fill-opacity="0.5" stroke="#00F0FF"/></svg></div>
                                                        <div class="tools_3d_scan_input_arrow_down tools_3d_scan_input_arrow_down5"><svg width="27" height="14" viewBox="0 0 27 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.39202 1L25.3359 1L13.706 12.4019L1.39202 1Z" fill="#FF0303" fill-opacity="0.5" stroke="#FF0303"/></svg></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="dashboard_tools_3d_scan_inner_main_right">
                                                <div class="dashboard_tools_3d_scan_inner_main_right_parameters">
                                                    <div class="dashboard_tools_3d_scan_inner_main_right_parameter_row">
                                                        <div class="dashboard_tools_3d_scan_inner_main_right_parameter_scale">
                                                            <div class="dashboard_tools_3d_scan_inner_main_right_parameter_scale_title">' . $translation['text242'] . '</div>
                                                            <div class="dashboard_tools_3d_scan_inner_main_right_parameter_scale_dots" data-field="tools_building_scan_address_dot_n">';

                                                                for ($i=0; $i < 5; $i++) { 
                                                                    $class = '';
                                                                    if ($i < $team_info['tools_building_scan_address_dot_n']) {
                                                                        $class = ' dashboard_tools_3d_scan_inner_main_right_parameter_scale_dot_before_active';
                                                                    } elseif ($i == $team_info['tools_building_scan_address_dot_n']) {
                                                                        $class = ' dashboard_tools_3d_scan_inner_main_right_parameter_scale_dot_active';
                                                                    }

                                                                    $return['content'] .= '<div class="dashboard_tools_3d_scan_inner_main_right_parameter_scale_dot' . $class . '"></div>';
                                                                }

                                    $return['content'] .= ' </div>
                                                        </div>
                                                        <div class="dashboard_tools_3d_scan_inner_main_right_parameter_checkbox">
                                                            <span>' . $translation['text242'] . '</span>
                                                            <input type="checkbox" name="tools_3d_scan_checkbox1" id="tools_3d_scan_checkbox1" data-field="tools_building_scan_address_checkbox_n"' . (!empty($team_info['tools_building_scan_address_checkbox_n']) ? ' checked="checked"' : '') . '>
                                                            <label for="tools_3d_scan_checkbox1"></label>
                                                        </div>
                                                    </div>
                                                    <div class="dashboard_tools_3d_scan_inner_main_right_parameter_row">
                                                        <div class="dashboard_tools_3d_scan_inner_main_right_parameter_scale">
                                                            <div class="dashboard_tools_3d_scan_inner_main_right_parameter_scale_title">' . $translation['text243'] . '</div>
                                                            <div class="dashboard_tools_3d_scan_inner_main_right_parameter_scale_dots" data-field="tools_building_scan_address_dot_s">';

                                                                for ($i=0; $i < 5; $i++) { 
                                                                    $class = '';
                                                                    if ($i < $team_info['tools_building_scan_address_dot_s']) {
                                                                        $class = ' dashboard_tools_3d_scan_inner_main_right_parameter_scale_dot_before_active';
                                                                    } elseif ($i == $team_info['tools_building_scan_address_dot_s']) {
                                                                        $class = ' dashboard_tools_3d_scan_inner_main_right_parameter_scale_dot_active';
                                                                    }

                                                                    $return['content'] .= '<div class="dashboard_tools_3d_scan_inner_main_right_parameter_scale_dot' . $class . '"></div>';
                                                                }

                                    $return['content'] .= ' </div>
                                                        </div>
                                                        <div class="dashboard_tools_3d_scan_inner_main_right_parameter_checkbox">
                                                            <span>' . $translation['text243'] . '</span>
                                                            <input type="checkbox" name="tools_3d_scan_checkbox2" id="tools_3d_scan_checkbox2" data-field="tools_building_scan_address_checkbox_s"' . (!empty($team_info['tools_building_scan_address_checkbox_s']) ? ' checked="checked"' : '') . '>
                                                            <label for="tools_3d_scan_checkbox2"></label>
                                                        </div>
                                                    </div>
                                                    <div class="dashboard_tools_3d_scan_inner_main_right_parameter_row">
                                                        <div class="dashboard_tools_3d_scan_inner_main_right_parameter_scale">
                                                            <div class="dashboard_tools_3d_scan_inner_main_right_parameter_scale_title">' . $translation['text244'] . '</div>
                                                            <div class="dashboard_tools_3d_scan_inner_main_right_parameter_scale_dots" data-field="tools_building_scan_address_dot_e">';

                                                                for ($i=0; $i < 5; $i++) { 
                                                                    $class = '';
                                                                    if ($i < $team_info['tools_building_scan_address_dot_e']) {
                                                                        $class = ' dashboard_tools_3d_scan_inner_main_right_parameter_scale_dot_before_active';
                                                                    } elseif ($i == $team_info['tools_building_scan_address_dot_e']) {
                                                                        $class = ' dashboard_tools_3d_scan_inner_main_right_parameter_scale_dot_active';
                                                                    }

                                                                    $return['content'] .= '<div class="dashboard_tools_3d_scan_inner_main_right_parameter_scale_dot' . $class . '"></div>';
                                                                }

                                    $return['content'] .= ' </div>
                                                        </div>
                                                        <div class="dashboard_tools_3d_scan_inner_main_right_parameter_checkbox">
                                                            <span>' . $translation['text244'] . '</span>
                                                            <input type="checkbox" name="tools_3d_scan_checkbox3" id="tools_3d_scan_checkbox3" data-field="tools_building_scan_address_checkbox_e"' . (!empty($team_info['tools_building_scan_address_checkbox_e']) ? ' checked="checked"' : '') . '>
                                                            <label for="tools_3d_scan_checkbox3"></label>
                                                        </div>
                                                    </div>
                                                    <div class="dashboard_tools_3d_scan_inner_main_right_parameter_row">
                                                        <div class="dashboard_tools_3d_scan_inner_main_right_parameter_scale">
                                                            <div class="dashboard_tools_3d_scan_inner_main_right_parameter_scale_title">' . $translation['text245'] . '</div>
                                                            <div class="dashboard_tools_3d_scan_inner_main_right_parameter_scale_dots" data-field="tools_building_scan_address_dot_w">';

                                                                for ($i=0; $i < 5; $i++) { 
                                                                    $class = '';
                                                                    if ($i < $team_info['tools_building_scan_address_dot_w']) {
                                                                        $class = ' dashboard_tools_3d_scan_inner_main_right_parameter_scale_dot_before_active';
                                                                    } elseif ($i == $team_info['tools_building_scan_address_dot_w']) {
                                                                        $class = ' dashboard_tools_3d_scan_inner_main_right_parameter_scale_dot_active';
                                                                    }

                                                                    $return['content'] .= '<div class="dashboard_tools_3d_scan_inner_main_right_parameter_scale_dot' . $class . '"></div>';
                                                                }

                                    $return['content'] .= ' </div>
                                                        </div>
                                                        <div class="dashboard_tools_3d_scan_inner_main_right_parameter_checkbox">
                                                            <span>' . $translation['text245'] . '</span>
                                                            <input type="checkbox" name="tools_3d_scan_checkbox4" id="tools_3d_scan_checkbox4" data-field="tools_building_scan_address_checkbox_w"' . (!empty($team_info['tools_building_scan_address_checkbox_w']) ? ' checked="checked"' : '') . '>
                                                            <label for="tools_3d_scan_checkbox4"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="dashboard_tools_3d_scan_inner_main_right_btn_wrapper">
                                                    <div class="tools_3d_scan_btn_bg"></div>
                                                    <div class="btn_wrapper btn_wrapper_blue tools_3d_scan_btn" data-scan-en="Scanning" data-scan-no="Skanner">
                                                        <div class="btn btn_blue">
                                                            <span>' . $translation['text246'] . '</span>
                                                        </div>
                                                        <div class="btn_border_top"></div>
                                                        <div class="btn_border_bottom"></div>
                                                        <div class="btn_border_left"></div>
                                                        <div class="btn_border_left_arcle"></div>
                                                        <div class="btn_border_right"></div>
                                                        <div class="btn_border_right_arcle"></div>
                                                        <div class="btn_bg_top_line"></div>
                                                        <div class="btn_bg_bottom_line"></div>
                                                        <div class="btn_bg_triangle_left"></div>
                                                        <div class="btn_bg_triangle_right"></div>
                                                        <div class="btn_circles_top">
                                                            <div class="btn_circle"></div>
                                                            <div class="btn_circle"></div>
                                                            <div class="btn_circle"></div>
                                                            <div class="btn_circle"></div>
                                                        </div>
                                                        <div class="btn_circles_bottom">
                                                            <div class="btn_circle"></div>
                                                            <div class="btn_circle"></div>
                                                            <div class="btn_circle"></div>
                                                            <div class="btn_circle"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <script>$(function() { updateGaugeValueLoadPage(' . $team_info['tools_building_scan_degree'] . '); });</script>';
        }

        return $return;
    }

    // tools - Green Pace Group’s secret office
    private function uploadToolsSecretOffice($lang_id, $team_id)
    {
        $translation = $this->getWordsByPage('game', $lang_id);

        $team_info = $this->teamInfo($team_id);

        $return = [];

        $return['back_btn'] = '<div class="tools_back_btn" data-back="tools_start_four" data-tools="false">
                                    <img src="/images/back_bg.png" class="back_btn_bg" alt="">
                                    <div class="back_btn_text">' . $translation['text22'] . '</div>
                                </div>';

        $return['titles'] = '<div class="dashboard_tab_title dashboard_tab_title_can_click_tools" data-tab="tab1" data-step="tools_start_four" data-tools="false">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper">
                                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.75 0H17.75V5L16 7H0V2L1.75 0Z" fill="#00F0FF"/><path d="M1.75 9H17.75V10.5L16 12.5H0V11L1.75 9Z" fill="#00F0FF"/><path d="M1.75 14H17.75V15.5L16 17.5H0V16L1.75 14Z" fill="#00F0FF"/></svg>
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text14'] . '</div>
                                </div>
                            </div>
                            <div class="dashboard_tab_title" data-tab="tab2">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper" style="margin: -4px 0 0;">
                                        <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_881_922)"><path d="M27.4173 27.9999H23.9173C23.5953 27.9999 23.334 27.7386 23.334 27.4166V23.9166C23.334 23.5946 23.5953 23.3333 23.9173 23.3333H27.4173C27.7393 23.3333 28.0006 23.5946 28.0006 23.9166V27.4166C28.0006 27.7386 27.7393 27.9999 27.4173 27.9999ZM24.5007 26.8333H26.834V24.4999H24.5007V26.8333Z" fill="#00F0FF"/><path d="M4.08333 27.9999H0.583333C0.261333 27.9999 0 27.7386 0 27.4166V23.9166C0 23.5946 0.261333 23.3333 0.583333 23.3333H4.08333C4.40533 23.3333 4.66667 23.5946 4.66667 23.9166V27.4166C4.66667 27.7386 4.40533 27.9999 4.08333 27.9999ZM1.16667 26.8333H3.5V24.4999H1.16667V26.8333Z" fill="#00F0FF"/><path d="M27.4173 4.66667H23.9173C23.5953 4.66667 23.334 4.40533 23.334 4.08333V0.583333C23.334 0.261333 23.5953 0 23.9173 0H27.4173C27.7393 0 28.0006 0.261333 28.0006 0.583333V4.08333C28.0006 4.40533 27.7393 4.66667 27.4173 4.66667ZM24.5007 3.5H26.834V1.16667H24.5007V3.5Z" fill="#00F0FF"/><path d="M4.08333 4.66667H0.583333C0.261333 4.66667 0 4.40533 0 4.08333V0.583333C0 0.261333 0.261333 0 0.583333 0H4.08333C4.40533 0 4.66667 0.261333 4.66667 0.583333V4.08333C4.66667 4.40533 4.40533 4.66667 4.08333 4.66667ZM1.16667 3.5H3.5V1.16667H1.16667V3.5Z" fill="#00F0FF"/><path d="M2.33333 24.5C2.01133 24.5 1.75 24.2387 1.75 23.9167V21.5833C1.75 21.2613 2.01133 21 2.33333 21C2.65533 21 2.91667 21.2613 2.91667 21.5833V23.9167C2.91667 24.2387 2.65533 24.5 2.33333 24.5ZM2.33333 18.6667C2.01133 18.6667 1.75 18.4053 1.75 18.0833V15.75C1.75 15.428 2.01133 15.1667 2.33333 15.1667C2.65533 15.1667 2.91667 15.428 2.91667 15.75V18.0833C2.91667 18.4053 2.65533 18.6667 2.33333 18.6667ZM2.33333 12.8333C2.01133 12.8333 1.75 12.572 1.75 12.25V9.91667C1.75 9.59467 2.01133 9.33333 2.33333 9.33333C2.65533 9.33333 2.91667 9.59467 2.91667 9.91667V12.25C2.91667 12.572 2.65533 12.8333 2.33333 12.8333ZM2.33333 7C2.01133 7 1.75 6.73867 1.75 6.41667V4.08333C1.75 3.76133 2.01133 3.5 2.33333 3.5C2.65533 3.5 2.91667 3.76133 2.91667 4.08333V6.41667C2.91667 6.73867 2.65533 7 2.33333 7Z" fill="#00F0FF"/><path d="M25.6673 24.5C25.3453 24.5 25.084 24.2387 25.084 23.9167V21.5833C25.084 21.2613 25.3453 21 25.6673 21C25.9893 21 26.2506 21.2613 26.2506 21.5833V23.9167C26.2506 24.2387 25.9893 24.5 25.6673 24.5ZM25.6673 18.6667C25.3453 18.6667 25.084 18.4053 25.084 18.0833V15.75C25.084 15.428 25.3453 15.1667 25.6673 15.1667C25.9893 15.1667 26.2506 15.428 26.2506 15.75V18.0833C26.2506 18.4053 25.9893 18.6667 25.6673 18.6667ZM25.6673 12.8333C25.3453 12.8333 25.084 12.572 25.084 12.25V9.91667C25.084 9.59467 25.3453 9.33333 25.6673 9.33333C25.9893 9.33333 26.2506 9.59467 26.2506 9.91667V12.25C26.2506 12.572 25.9893 12.8333 25.6673 12.8333ZM25.6673 7C25.3453 7 25.084 6.73867 25.084 6.41667V4.08333C25.084 3.76133 25.3453 3.5 25.6673 3.5C25.9893 3.5 26.2506 3.76133 26.2506 4.08333V6.41667C26.2506 6.73867 25.9893 7 25.6673 7Z" fill="#00F0FF"/><path d="M23.9167 26.2499H21.5833C21.2613 26.2499 21 25.9886 21 25.6666C21 25.3446 21.2613 25.0833 21.5833 25.0833H23.9167C24.2387 25.0833 24.5 25.3446 24.5 25.6666C24.5 25.9886 24.2387 26.2499 23.9167 26.2499ZM18.0833 26.2499H15.75C15.428 26.2499 15.1667 25.9886 15.1667 25.6666C15.1667 25.3446 15.428 25.0833 15.75 25.0833H18.0833C18.4053 25.0833 18.6667 25.3446 18.6667 25.6666C18.6667 25.9886 18.4053 26.2499 18.0833 26.2499ZM12.25 26.2499H9.91667C9.59467 26.2499 9.33333 25.9886 9.33333 25.6666C9.33333 25.3446 9.59467 25.0833 9.91667 25.0833H12.25C12.572 25.0833 12.8333 25.3446 12.8333 25.6666C12.8333 25.9886 12.572 26.2499 12.25 26.2499ZM6.41667 26.2499H4.08333C3.76133 26.2499 3.5 25.9886 3.5 25.6666C3.5 25.3446 3.76133 25.0833 4.08333 25.0833H6.41667C6.73867 25.0833 7 25.3446 7 25.6666C7 25.9886 6.73867 26.2499 6.41667 26.2499Z" fill="#00F0FF"/><path d="M23.9167 2.91667H21.5833C21.2613 2.91667 21 2.65533 21 2.33333C21 2.01133 21.2613 1.75 21.5833 1.75H23.9167C24.2387 1.75 24.5 2.01133 24.5 2.33333C24.5 2.65533 24.2387 2.91667 23.9167 2.91667ZM18.0833 2.91667H15.75C15.428 2.91667 15.1667 2.65533 15.1667 2.33333C15.1667 2.01133 15.428 1.75 15.75 1.75H18.0833C18.4053 1.75 18.6667 2.01133 18.6667 2.33333C18.6667 2.65533 18.4053 2.91667 18.0833 2.91667ZM12.25 2.91667H9.91667C9.59467 2.91667 9.33333 2.65533 9.33333 2.33333C9.33333 2.01133 9.59467 1.75 9.91667 1.75H12.25C12.572 1.75 12.8333 2.01133 12.8333 2.33333C12.8333 2.65533 12.572 2.91667 12.25 2.91667ZM6.41667 2.91667H4.08333C3.76133 2.91667 3.5 2.65533 3.5 2.33333C3.5 2.01133 3.76133 1.75 4.08333 1.75H6.41667C6.73867 1.75 7 2.01133 7 2.33333C7 2.65533 6.73867 2.91667 6.41667 2.91667Z" fill="#00F0FF"/><path d="M14 13.125C13.939 13.125 13.878 13.1138 13.82 13.0913L7.32 10.5392C7.127 10.4637 7 10.2738 7 10.0625C7 9.85119 7.127 9.66131 7.32 9.58577L13.82 7.03369C13.935 6.98877 14.064 6.98877 14.179 7.03369L20.679 9.58577C20.873 9.66131 21 9.85119 21 10.0625C21 10.2738 20.873 10.4637 20.68 10.5392L14.18 13.0913C14.122 13.1138 14.061 13.125 14 13.125ZM8.893 10.0625L14 12.0674L19.107 10.0625L14 8.05758L8.893 10.0625Z" fill="#00F0FF"/><path d="M14 21C13.939 21 13.878 20.9891 13.82 20.9674L7.32 18.4945C7.127 18.4213 7 18.2374 7 18.0326V10.1196C7 9.84657 7.224 9.625 7.5 9.625C7.776 9.625 8 9.84657 8 10.1196V17.6933L14 19.9763L20 17.6933V10.1196C20 9.84657 20.224 9.625 20.5 9.625C20.776 9.625 21 9.84657 21 10.1196V18.0326C21 18.2374 20.873 18.4213 20.68 18.4945L14.18 20.9674C14.122 20.9891 14.061 21 14 21Z" fill="#00F0FF"/><path d="M14 21C13.7585 21 13.5625 20.7713 13.5625 20.4896V12.3229C13.5625 12.0412 13.7585 11.8125 14 11.8125C14.2415 11.8125 14.4375 12.0412 14.4375 12.3229V20.4896C14.4375 20.7713 14.2415 21 14 21Z" fill="#00F0FF"/></g><defs><clipPath id="clip0_881_922"><rect width="28" height="28" fill="white"/></clipPath></defs></svg>
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text238'] . '</div>
                                </div>
                            </div>
                            <div class="dashboard_tab_title dashboard_tab_title_active" data-tab="tools_3d_scan3">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper" style="margin: -4px 0 0;">
                                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20.0355 4.32767H11.9637C11.849 4.32767 11.739 4.28211 11.6579 4.20101C11.5768 4.11991 11.5312 4.00992 11.5312 3.89523V1.29718C11.5312 1.18249 11.5768 1.0725 11.6579 0.991403C11.739 0.910306 11.849 0.864746 11.9637 0.864746H20.0355C20.1502 0.864746 20.2601 0.910306 20.3412 0.991403C20.4223 1.0725 20.4679 1.18249 20.4679 1.29718V3.89523C20.4679 4.00992 20.4223 4.11991 20.3412 4.20101C20.2601 4.28211 20.1502 4.32767 20.0355 4.32767ZM12.3961 3.4628H19.603V1.72961H12.3961V3.4628Z" fill="#00F0FF"/><path d="M15.2509 31.1351H13.9969C13.8822 31.1351 13.7722 31.0896 13.6911 31.0085C13.61 30.9274 13.5645 30.8174 13.5645 30.7027C13.5645 30.588 13.61 30.478 13.6911 30.3969C13.7722 30.3158 13.8822 30.2703 13.9969 30.2703H15.2509C15.3656 30.2703 15.4756 30.3158 15.5567 30.3969C15.6378 30.478 15.6834 30.588 15.6834 30.7027C15.6834 30.8174 15.6378 30.9274 15.5567 31.0085C15.4756 31.0896 15.3656 31.1351 15.2509 31.1351Z" fill="#00F0FF"/><path d="M30.7031 31.1353H22.5906C22.476 31.1353 22.366 31.0897 22.2849 31.0086C22.2038 30.9275 22.1582 30.8175 22.1582 30.7028C22.1582 30.5881 22.2038 30.4781 22.2849 30.397C22.366 30.3159 22.476 30.2704 22.5906 30.2704H30.2706V28.9774H1.7301V30.2704H10.9712C11.0859 30.2704 11.1959 30.3159 11.277 30.397C11.3581 30.4781 11.4036 30.5881 11.4036 30.7028C11.4036 30.8175 11.3581 30.9275 11.277 31.0086C11.1959 31.0897 11.0859 31.1353 10.9712 31.1353H1.29767C1.18298 31.1353 1.07299 31.0897 0.991891 31.0086C0.910794 30.9275 0.865234 30.8175 0.865234 30.7028V28.545C0.865234 28.4303 0.910794 28.3203 0.991891 28.2392C1.07299 28.1581 1.18298 28.1125 1.29767 28.1125H30.7031C30.8178 28.1125 30.9278 28.1581 31.0089 28.2392C31.0899 28.3203 31.1355 28.4303 31.1355 28.545V30.7028C31.1355 30.8175 31.0899 30.9275 31.0089 31.0086C30.9278 31.0897 30.8178 31.1353 30.7031 31.1353Z" fill="#00F0FF"/><path d="M19.5644 31.1351H18.2801C18.1654 31.1351 18.0554 31.0896 17.9743 31.0085C17.8932 30.9274 17.8477 30.8174 17.8477 30.7027C17.8477 30.588 17.8932 30.478 17.9743 30.3969C18.0554 30.3158 18.1654 30.2703 18.2801 30.2703H19.5644C19.6791 30.2703 19.7891 30.3158 19.8702 30.3969C19.9513 30.478 19.9969 30.588 19.9969 30.7027C19.9969 30.8174 19.9513 30.9274 19.8702 31.0085C19.7891 31.0896 19.6791 31.1351 19.5644 31.1351Z" fill="#00F0FF"/><path d="M22.4874 28.9764H9.51446C9.39978 28.9764 9.28978 28.9308 9.20869 28.8497C9.12759 28.7686 9.08203 28.6587 9.08203 28.544V3.89532C9.08203 3.78063 9.12759 3.67064 9.20869 3.58955C9.28978 3.50845 9.39978 3.46289 9.51446 3.46289H22.4874C22.6021 3.46289 22.7121 3.50845 22.7932 3.58955C22.8743 3.67064 22.9199 3.78063 22.9199 3.89532V28.544C22.9199 28.6587 22.8743 28.7686 22.7932 28.8497C22.7121 28.9308 22.6021 28.9764 22.4874 28.9764ZM9.9469 28.1115H22.055V4.32776H9.9469V28.1115Z" fill="#00F0FF"/><path d="M28.9736 28.9764H22.4871C22.3724 28.9764 22.2624 28.9308 22.1813 28.8497C22.1002 28.7686 22.0547 28.6586 22.0547 28.5439V12.9764C22.0547 12.8617 22.1002 12.7517 22.1813 12.6706C22.2624 12.5895 22.3724 12.5439 22.4871 12.5439H28.9736C29.0883 12.5439 29.1983 12.5895 29.2794 12.6706C29.3605 12.7517 29.406 12.8617 29.406 12.9764V28.5439C29.406 28.6586 29.3605 28.7686 29.2794 28.8497C29.1983 28.9308 29.0883 28.9764 28.9736 28.9764ZM22.9196 28.1115H28.5412V13.4088H22.9196V28.1115Z" fill="#00F0FF"/><path d="M9.51267 28.9765H3.02618C2.91149 28.9765 2.8015 28.9309 2.72041 28.8498C2.63931 28.7687 2.59375 28.6588 2.59375 28.5441V13.8414C2.59375 13.7267 2.63931 13.6167 2.72041 13.5356C2.8015 13.4545 2.91149 13.4089 3.02618 13.4089H9.51267C9.62736 13.4089 9.73735 13.4545 9.81844 13.5356C9.89954 13.6167 9.9451 13.7267 9.9451 13.8414V28.5441C9.9451 28.6588 9.89954 28.7687 9.81844 28.8498C9.73735 28.9309 9.62736 28.9765 9.51267 28.9765ZM3.45861 28.1116H9.08024V14.2738H3.45861V28.1116Z" fill="#00F0FF"/><path d="M9.51409 14.2738H4.64923C4.53454 14.2738 4.42455 14.2283 4.34345 14.1472C4.26236 14.0661 4.2168 13.9561 4.2168 13.8414V11.4864C4.2168 11.3717 4.26236 11.2617 4.34345 11.1806C4.42455 11.0995 4.53454 11.054 4.64923 11.054H9.51409C9.62878 11.054 9.73877 11.0995 9.81987 11.1806C9.90097 11.2617 9.94653 11.3717 9.94653 11.4864V13.8414C9.94653 13.9561 9.90097 14.0661 9.81987 14.1472C9.73877 14.2283 9.62878 14.2738 9.51409 14.2738ZM5.08166 13.409H9.08166V11.9188H5.08166V13.409Z" fill="#00F0FF"/><path d="M9.51436 23.3444H5.83868C5.72399 23.3444 5.614 23.2988 5.53291 23.2177C5.45181 23.1366 5.40625 23.0266 5.40625 22.9119C5.40625 22.7972 5.45181 22.6872 5.53291 22.6061C5.614 22.5251 5.72399 22.4795 5.83868 22.4795H9.51436C9.62905 22.4795 9.73904 22.5251 9.82013 22.6061C9.90123 22.6872 9.94679 22.7972 9.94679 22.9119C9.94679 23.0266 9.90123 23.1366 9.82013 23.2177C9.73904 23.2988 9.62905 23.3444 9.51436 23.3444Z" fill="#00F0FF"/><path d="M9.51436 17.2972H5.83868C5.72399 17.2972 5.614 17.2517 5.53291 17.1706C5.45181 17.0895 5.40625 16.9795 5.40625 16.8648C5.40625 16.7501 5.45181 16.6401 5.53291 16.559C5.614 16.4779 5.72399 16.4324 5.83868 16.4324H9.51436C9.62905 16.4324 9.73904 16.4779 9.82013 16.559C9.90123 16.6401 9.94679 16.7501 9.94679 16.8648C9.94679 16.9795 9.90123 17.0895 9.82013 17.1706C9.73904 17.2517 9.62905 17.2972 9.51436 17.2972Z" fill="#00F0FF"/><path d="M9.51436 20.3209H5.83868C5.72399 20.3209 5.614 20.2754 5.53291 20.1943C5.45181 20.1132 5.40625 20.0032 5.40625 19.8885C5.40625 19.7738 5.45181 19.6638 5.53291 19.5827C5.614 19.5016 5.72399 19.4561 5.83868 19.4561H9.51436C9.62905 19.4561 9.73904 19.5016 9.82013 19.5827C9.90123 19.6638 9.94679 19.7738 9.94679 19.8885C9.94679 20.0032 9.90123 20.1132 9.82013 20.1943C9.73904 20.2754 9.62905 20.3209 9.51436 20.3209Z" fill="#00F0FF"/><path d="M9.51436 26.3683H5.83868C5.72399 26.3683 5.614 26.3227 5.53291 26.2416C5.45181 26.1605 5.40625 26.0505 5.40625 25.9359C5.40625 25.8212 5.45181 25.7112 5.53291 25.6301C5.614 25.549 5.72399 25.5034 5.83868 25.5034H9.51436C9.62905 25.5034 9.73904 25.549 9.82013 25.6301C9.90123 25.7112 9.94679 25.8212 9.94679 25.9359C9.94679 26.0505 9.90123 26.1605 9.82013 26.2416C9.73904 26.3227 9.62905 26.3683 9.51436 26.3683Z" fill="#00F0FF"/><path d="M25.2979 17.3007H22.4871C22.3724 17.3007 22.2624 17.2551 22.1813 17.174C22.1002 17.0929 22.0547 16.9829 22.0547 16.8682C22.0547 16.7535 22.1002 16.6435 22.1813 16.5624C22.2624 16.4814 22.3724 16.4358 22.4871 16.4358H25.2979C25.4126 16.4358 25.5226 16.4814 25.6037 16.5624C25.6848 16.6435 25.7304 16.7535 25.7304 16.8682C25.7304 16.9829 25.6848 17.0929 25.6037 17.174C25.5226 17.2551 25.4126 17.3007 25.2979 17.3007Z" fill="#00F0FF"/><path d="M25.2979 21.1925H22.4871C22.3724 21.1925 22.2624 21.1469 22.1813 21.0659C22.1002 20.9848 22.0547 20.8748 22.0547 20.7601C22.0547 20.6454 22.1002 20.5354 22.1813 20.4543C22.2624 20.3732 22.3724 20.3276 22.4871 20.3276H25.2979C25.4126 20.3276 25.5226 20.3732 25.6037 20.4543C25.6848 20.5354 25.7304 20.6454 25.7304 20.7601C25.7304 20.8748 25.6848 20.9848 25.6037 21.0659C25.5226 21.1469 25.4126 21.1925 25.2979 21.1925Z" fill="#00F0FF"/><path d="M25.2979 25.0846H22.4871C22.3724 25.0846 22.2624 25.039 22.1813 24.9579C22.1002 24.8768 22.0547 24.7668 22.0547 24.6522C22.0547 24.5375 22.1002 24.4275 22.1813 24.3464C22.2624 24.2653 22.3724 24.2197 22.4871 24.2197H25.2979C25.4126 24.2197 25.5226 24.2653 25.6037 24.3464C25.6848 24.4275 25.7304 24.5375 25.7304 24.6522C25.7304 24.7668 25.6848 24.8768 25.6037 24.9579C25.5226 25.039 25.4126 25.0846 25.2979 25.0846Z" fill="#00F0FF"/><path d="M19.711 8.32775H12.2879C12.1732 8.32775 12.0632 8.28219 11.9821 8.2011C11.901 8.12 11.8555 8.01001 11.8555 7.89532C11.8555 7.78063 11.901 7.67064 11.9821 7.58955C12.0632 7.50845 12.1732 7.46289 12.2879 7.46289H19.711C19.8257 7.46289 19.9357 7.50845 20.0168 7.58955C20.0979 7.67064 20.1435 7.78063 20.1435 7.89532C20.1435 8.01001 20.0979 8.12 20.0168 8.2011C19.9357 8.28219 19.8257 8.32775 19.711 8.32775Z" fill="#00F0FF"/><path d="M19.711 12.2379H12.2879C12.1732 12.2379 12.0632 12.1923 11.9821 12.1113C11.901 12.0302 11.8555 11.9202 11.8555 11.8055C11.8555 11.6908 11.901 11.5808 11.9821 11.4997C12.0632 11.4186 12.1732 11.373 12.2879 11.373H19.711C19.8257 11.373 19.9357 11.4186 20.0168 11.4997C20.0979 11.5808 20.1435 11.6908 20.1435 11.8055C20.1435 11.9202 20.0979 12.0302 20.0168 12.1113C19.9357 12.1923 19.8257 12.2379 19.711 12.2379Z" fill="#00F0FF"/><path d="M19.711 16.1442H12.2879C12.1732 16.1442 12.0632 16.0986 11.9821 16.0175C11.901 15.9364 11.8555 15.8264 11.8555 15.7117C11.8555 15.597 11.901 15.4871 11.9821 15.406C12.0632 15.3249 12.1732 15.2793 12.2879 15.2793H19.711C19.8257 15.2793 19.9357 15.3249 20.0168 15.406C20.0979 15.4871 20.1435 15.597 20.1435 15.7117C20.1435 15.8264 20.0979 15.9364 20.0168 16.0175C19.9357 16.0986 19.8257 16.1442 19.711 16.1442Z" fill="#00F0FF"/><path d="M19.171 28.9766H12.8289C12.7142 28.9766 12.6042 28.931 12.5231 28.8499C12.442 28.7688 12.3965 28.6588 12.3965 28.5441V21.1859C12.3965 21.0712 12.442 20.9612 12.5231 20.8801C12.6042 20.799 12.7142 20.7534 12.8289 20.7534H19.171C19.2857 20.7534 19.3957 20.799 19.4768 20.8801C19.5578 20.9612 19.6034 21.0712 19.6034 21.1859V28.5441C19.6034 28.6588 19.5578 28.7688 19.4768 28.8499C19.3957 28.931 19.2857 28.9766 19.171 28.9766ZM13.2613 28.1117H18.7385V21.6183H13.2613V28.1117Z" fill="#00F0FF"/></svg>
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text249'] . '</div>
                                </div>
                            </div>';

        /*$return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_tools_secret_office dashboard_tab_content_item_active" data-tab="tools_3d_scan3">
                                <div class="dashboard_tools_secret_office_inner">
                                    <!-- <img src="/images/tools_secrete_office_example.png" alt=""> -->
                                    <script src="https://static.matterport.com/showcase-sdk/latest.js"></script>
                                    <iframe
                                        width="853"
                                        height="480"
                                        src="https://my.matterport.com/show?m=XqyBcKuG8hd&play=1"
                                        frameborder="0"
                                        allow="fullscreen; vr"
                                        id="matterport-iframe">
                                    </iframe>
                                </div>
                            </div>';*/
        $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_tools_secret_office dashboard_tab_content_item_active" data-tab="tools_3d_scan3">
                                <div class="dashboard_tools_secret_office_inner">
                                    <!-- <img src="/images/tools_secrete_office_example.png" alt=""> -->
                                    <script src="https://static.matterport.com/showcase-sdk/latest.js"></script>
                                    <iframe
                                        width="853"
                                        height="480"
                                        src="https://my.matterport.com/show/?m=ikn8tusTdmG&play=1"
                                        frameborder="0"
                                        allow="fullscreen; vr"
                                        id="matterport-iframe">
                                    </iframe>
                                </div>
                            </div>';

        return $return;
    }






/* FILES */
    // загрузить конкретный экран (с переключателем табов) для files
    /*public function uploadTypeTabsFilesStep($step, $lang_id, $team_id)
    {
        switch ($step) {
            case 'mission_briefing': $return = $this->uploadFilesMissionBriefing($team_id, $lang_id); break;
            
            default: $return = $this->uploadFilesMissionBriefing($lang_id); break;
        }

        return $return;
    }*/

    // files - список файлов доступный для просмотра
    public function uploadFilesActualForView($team_id, $lang_id)
{
    $translation = $this->getWordsByPage('game', $lang_id);
    $team_info = $this->teamInfo($team_id);
    $svg = $this->svg; 
    $return = [];

    // Заголовок
    $return['titles'] = '
        <div class="flex items-center gap-3 mb-6">
            <div class="icon-container p-2 rounded-lg bg-primary/20 border border-primary/30 mt-5">
                '.$svg['dashboard_files'].'
            </div>
            <h2 class="text-3xl font-bold neon-text">Архив досье</h2>
        </div>
    ';

    // Контент
    $return['content'] = '
    <div class="mt-6 ">
        <div class="flex items-center gap-2 mb-4">
            <svg class="h-4 w-4 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path d="M4 4h16v16H4z"/>
            </svg>
            <span class="text-sm text-primary">Архив документов, видео и изображений</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
';

if ($team_info) {
    $list_files = json_decode($team_info['list_files'], true);

    foreach ($list_files as $file_id) {
        $sql = "
            SELECT f.type, fd.path, fd.name, fd.file_with_path
            FROM files f
            JOIN files_description fd ON f.id = fd.file_id 
            WHERE f.id = {?}
            AND fd.lang_id = {?}
        ";
        $file_info = $this->db->selectRow($sql, [(int) $file_id, $lang_id]);

        if ($file_info) {

            // Определяем тип файла для кнопки
            $button_icon = $svg['eye'];
            $button_text = 'Посмотреть';

            if (in_array(strtolower($file_info['type']), ['video', 'mp4', 'mov', 'avi'])) {
                $button_icon = $svg['play'];
                $button_text = 'Воспроизвести';
            }

            // Карточка файла
            $return['content'] .= '
<div class="dashboard_tab_content_file_item w-full relative flex flex-col justify-between 
            p-4 border border-cyan-500/50 rounded-lg bg-cyan-950/20 
            transition-all duration-300 animate-pulse-glow group"
     data-type="'.$file_info['type'].'" 
     data-path="'.$file_info['path'].'" 
     data-file-with-path="'.$file_info['file_with_path'].'" 
     data-file-id="'.$file_id.'">

    <div>
        <div class="text-sm font-semibold mb-2 text-cyan-100 tracking-wide">
            '.$file_info['name'].'
        </div>
        <div class="text-xs text-cyan-400/70 mb-4 uppercase">
            '.$file_info['type'].'
        </div>
    </div>

    <div class="mt-auto">
        <a href="'.$file_info['path'].'" target="_blank"
           class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium rounded-md border border-cyan-500/40 
                  text-cyan-200 hover:text-cyan-50 hover:bg-cyan-800/40 transition-all duration-300 w-fit">
            '.$button_icon.'
            <span>'.$button_text.'</span>
        </a>
    </div>
</div>
';
        }
    }
}

$return['content'] .= '
        </div>
    </div>
';


     

    return $return;
}


}
