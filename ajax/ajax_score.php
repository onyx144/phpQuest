<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/ajax/ajax_header.php');

if (isset($_POST['op'])) {
    $return = [];

    switch ($_POST['op']) {
        // обновить к-во баллов
        case 'updateScore':
            $score = isset($_POST['score']) ? (int) $_POST['score'] : 0;

            $sql = "UPDATE `teams` SET `score` = {?} WHERE `id` = {?}";
            $db->query($sql, [$score, $userInfo['team_id']]);

            $return['success'] = 'ok';

            print_r(json_encode($return));
            break;
    }
}
