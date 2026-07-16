<?php

trait Team
{
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

    // voice_decoder: гарантируем наличие колонок voice_message и audio_find
    public function ensureVoiceDecoderColumns()
    {
        static $ready = false;
        if ($ready) {
            return true;
        }

        $voiceMessageCol = $this->db->select("SHOW COLUMNS FROM `teams` LIKE 'voice_message'");
        if (!$voiceMessageCol) {
            $this->db->query("ALTER TABLE `teams` ADD COLUMN `voice_message` INT NOT NULL DEFAULT 0");
        }

        $audioFindCol = $this->db->select("SHOW COLUMNS FROM `teams` LIKE 'audio_find'");
        if (!$audioFindCol) {
            $this->db->query("ALTER TABLE `teams` ADD COLUMN `audio_find` VARCHAR(50) NOT NULL DEFAULT ''");
        }

        $voiceCorrectOrderCol = $this->db->select("SHOW COLUMNS FROM `teams` LIKE 'voice_correct_order'");
        if (!$voiceCorrectOrderCol) {
            $this->db->query("ALTER TABLE `teams` ADD COLUMN `voice_correct_order` VARCHAR(50) NOT NULL DEFAULT '3,1,4,2'");
        }

        $ready = true;
        return true;
    }

    public function parseVoiceCorrectOrder($team_info)
    {
        $default = [3, 1, 4, 2];
        if (!is_array($team_info) || !array_key_exists('voice_correct_order', $team_info)) {
            return $default;
        }

        $raw = trim((string) $team_info['voice_correct_order']);
        if ($raw === '') {
            return $default;
        }

        $order = [];
        foreach (preg_split('/\s*,\s*/', $raw) as $part) {
            $id = (int) $part;
            if ($id >= 1 && $id <= 4 && !in_array($id, $order, true)) {
                $order[] = $id;
            }
        }

        if (count($order) !== 4) {
            return $default;
        }

        return $order;
    }

    public function normalizeVoiceCorrectOrder($order)
    {
        $normalized = [];
        if (!is_array($order)) {
            return [3, 1, 4, 2];
        }

        foreach ($order as $part) {
            $id = (int) $part;
            if ($id >= 1 && $id <= 4 && !in_array($id, $normalized, true)) {
                $normalized[] = $id;
            }
        }

        if (count($normalized) !== 4) {
            return [3, 1, 4, 2];
        }

        return $normalized;
    }

    public function isVoiceDecoderStage($team_info)
    {
        return is_array($team_info) && ($team_info['last_dashboard'] ?? '') === 'voice_decoder';
    }

    public function parseTeamAudioFind($team_info)
    {
        $audio_find = [];
        if (!is_array($team_info) || !array_key_exists('audio_find', $team_info)) {
            return $audio_find;
        }

        $rawFind = trim((string) $team_info['audio_find']);
        if ($rawFind === '') {
            return $audio_find;
        }

        foreach (preg_split('/\s*,\s*/', $rawFind) as $part) {
            $id = (int) $part;
            if ($id >= 1 && $id <= 4 && !in_array($id, $audio_find, true)) {
                $audio_find[] = $id;
            }
        }

        sort($audio_find);
        return $audio_find;
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
}
