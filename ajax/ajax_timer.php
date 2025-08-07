<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/ajax/ajax_header.php');

if (isset($_POST['op'])) {
    $return = [];

    switch ($_POST['op']) {
        // сколько времени прошло с момента принятия миссии
        case 'getActiveTimer':
            if ($userInfo) {
                $sql = "SELECT `mission_accept_datetime`, `mission_finish_seconds`, `mission_finish_datetime` FROM `teams` WHERE `id` = {?} AND `mission_accept_datetime` != {?} AND `mission_accept_datetime` != {?} AND `mission_accept_datetime` != {?} AND `mission_accept_datetime` IS NOT NULL";
                $row = $db->selectRow($sql, [$userInfo['team_id'], '', '0000-00-00 00:00:00', 'null']);
                if ($row) {
                    if ($row['mission_finish_seconds'] > 0) { // прошли игру
                        $old = new DateTime($row['mission_accept_datetime']);
                        $now = new DateTime($row['mission_finish_datetime']);

                        $interval = $old->diff($now);

                        $return['second'] = $interval->s;
                        $return['minute'] = $interval->i;
                        $return['hours'] = $interval->h;
                        $return['days'] = $interval->days;

                        // общее к-во секунд от начала отсчета
                        /*$second_sum = $interval->days * 24 * 60 * 60;
                        $second_sum += $interval->h * 60 * 60;
                        $second_sum += $interval->i * 60;
                        $second_sum += $interval->s;*/
                        $return['second_sum'] = $row['mission_finish_seconds'];
                    } else { // еще в процессе прохождения
                        $old = new DateTime($row['mission_accept_datetime']);
                        $now = new DateTime();

                        $interval = $old->diff($now);

                        $return['second'] = $interval->s;
                        $return['minute'] = $interval->i;
                        $return['hours'] = $interval->h;
                        $return['days'] = $interval->days;

                        // общее к-во секунд от начала отсчета
                        $second_sum = $interval->days * 24 * 60 * 60;
                        $second_sum += $interval->h * 60 * 60;
                        $second_sum += $interval->i * 60;
                        $second_sum += $interval->s;
                        $return['second_sum'] = $second_sum;

                        /*if ($interval->days > 0) {
                            $return['reload'] = 1;
                        } else {
                            $return['reload'] = 0;
                        }*/
                        $return['reload'] = 0;
                    }
                } else {
                    $return['error'] = 'ok';
                }
            } else {
                $return['error'] = 'ok';
            }

            print_r(json_encode($return));
            break;

        // обновить значение таймера
        case 'updateTimer':
            $timer_second = isset($_POST['timer_second']) ? (int) $_POST['timer_second'] : 0;

            $sql = "UPDATE `teams` SET `timer_second` = {?} WHERE `id` = {?}";
            $db->query($sql, [$timer_second, $userInfo['team_id']]);

            $return['success'] = 'ok';

            print_r(json_encode($return));
            break;
    }
}
