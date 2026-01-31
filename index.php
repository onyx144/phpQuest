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

// Автоматически создаем маршрут для manage-stages, если его нет
try {
    $db = DataBase::getDB();
    $query = "SELECT `id` FROM `url_alias_admin` WHERE `url` = {?} LIMIT 1";
    $route_id = $db->selectCell($query, ['manage-stages']);
    
    if (!$route_id) {
        // Создаем маршрут для Adminadmin
        $query = "INSERT INTO `url_alias_admin` SET `url` = {?}, `method` = {?}, `status` = {?}, `sort` = {?}";
        $route_id = $db->query($query, ['manage-stages', 'Adminadmin/manageStages', 1, 999]);
        
        if ($route_id) {
            // Даем доступ админам (role_id = 2)
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
