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

// Автоматически создаем маршруты для админ-панели, если их нет
try {
    $db = DataBase::getDB();
    
    // Маршрут для manage-stages
    $query = "SELECT `id` FROM `url_alias_admin` WHERE `url` = {?} LIMIT 1";
    $route_id = $db->selectCell($query, ['manage-stages']);
    
    if (!$route_id) {
        $query = "INSERT INTO `url_alias_admin` SET `url` = {?}, `method` = {?}, `status` = {?}, `sort` = {?}";
        $route_id = $db->query($query, ['manage-stages', 'Adminadmin/manageStages', 1, 999]);
        
        if ($route_id) {
            $query = "INSERT INTO `url_alias_admin_access_role` SET `url_alias_id` = {?}, `role_id` = {?}, `access_view` = {?}, `access_edit` = {?}";
            $db->query($query, [$route_id, 2, 1, 1]);
        }
    }
    
    // Маршрут для language
    $query = "SELECT `id` FROM `url_alias_admin` WHERE `url` = {?} LIMIT 1";
    $route_id = $db->selectCell($query, ['language']);
    
    if (!$route_id) {
        $query = "INSERT INTO `url_alias_admin` SET `url` = {?}, `method` = {?}, `status` = {?}, `sort` = {?}";
        $route_id = $db->query($query, ['language', 'Adminadmin/manageLanguages', 1, 998]);
        
        if ($route_id) {
            $query = "INSERT INTO `url_alias_admin_access_role` SET `url_alias_id` = {?}, `role_id` = {?}, `access_view` = {?}, `access_edit` = {?}";
            $db->query($query, [$route_id, 2, 1, 1]);
        }
    }
} catch (Exception $e) {
    // Игнорируем ошибки при создании маршрута (возможно БД еще не инициализирована)
}

// run router
$router = new Router();
$router->run();
