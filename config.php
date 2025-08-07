<?php
defined('GD_ACCESS') or die('You can not access the file directly!');

// timezone Kiev
date_default_timezone_set('Europe/Kiev');

// db const
define('DB_HOST', 'macan.cityhost.com.ua');
define('DB_USER', 'ch464938ac_user');
define('DB_PASS', 'pfCDI75BKq');
define('DB_NAME', 'ch464938ac_quest');

// lang const
define('LANG_DEFAULT', 'en');
define('LANG_ABBR_DEFAULT', 'en');

// domen
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
    $protocol = "https";
} else {
    $protocol = "http";
}


if (isset($_SERVER['HTTP_HOST'])) {
    define('DOMEN', $protocol . '://' . $_SERVER['HTTP_HOST']);
}