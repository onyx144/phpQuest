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
