<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/ajax/ajax_header.php');

function buildLangPath($lang_abbr, $path = '')
{
    $langCode = strtolower(trim((string) $lang_abbr));
    $path = ltrim((string) $path, '/');

    if ($langCode === '' || $langCode === 'en') {
        return $path === '' ? '/' : '/' . $path;
    }

    return $path === '' ? '/' . $langCode : '/' . $langCode . '/' . $path;
}

if (isset($_POST['op'])) {
    $return = [];

    switch ($_POST['op']) {
        // выход
        case 'exit':
            $lang_abbr = isset($_POST['lang_abbr']) ? strip_tags(trim($_POST['lang_abbr'])) : '';

            $sql = "UPDATE `users` SET `hash` = {?} WHERE `id` = {?}";
            $db->query($sql, ['', $userInfo['id']]);

            setcookie('hash', '', time() - (60 * 60 * 24 * 1), '/');

            $return['success'] = buildLangPath($lang_abbr);

            print_r(json_encode($return));
            break;
    }
}
