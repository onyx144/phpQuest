<?php

// display error
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// const for control access
define('GD_ACCESS', true);

// include files
define('ROOT', dirname(__FILE__));
require_once(ROOT . '/config.php');
require_once(ROOT . '/core/Autoload.php');

// run router
$router = new Router();
$router->run();
