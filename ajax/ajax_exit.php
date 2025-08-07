<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/ajax/ajax_header.php');

if (isset($_POST['op'])) {
    $return = [];

    switch ($_POST['op']) {
        // выход
        case 'exit':
            $lang_abbr = isset($_POST['lang_abbr']) ? strip_tags(trim($_POST['lang_abbr'])) : '';

            $sql = "UPDATE `users` SET `hash` = {?} WHERE `id` = {?}";
            $db->query($sql, ['', $userInfo['id']]);

            setcookie('hash', '', time() - (60 * 60 * 24 * 1), '/');

            if ($lang_abbr == 'en') {
                $return['success'] = '/';
            } else {
                $return['success'] = '/no';
            }

            print_r(json_encode($return));
            break;
    }
}
