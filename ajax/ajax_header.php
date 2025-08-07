<?php

// defines
define('GD_ACCESS', true); // security
define('ROOT', $_SERVER['DOCUMENT_ROOT']); // root

// include files
require_once(ROOT . '/config.php'); // config
require_once(ROOT . '/core/DataBase.php'); // db

require_once(ROOT . '/core/Language.php'); // language
require_once(ROOT . '/controller/Functions.php'); // functions
require_once(ROOT . '/controller/User.php'); // user

// db
$db = DataBase::getDB();
// lang
$lang = Language::getLang();
// function
$function = Functions::getFunctions();

// user
$user = User::getUser();
$userInfo = $user->isAutorized();
