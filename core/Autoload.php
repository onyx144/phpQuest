<?php

spl_autoload_register(function($class_name) {
    # List all the class directories in the array.
    $array_paths = array(
        '/model/',
        '/core/'
    );

    foreach ($array_paths as $path) {
        $path = ROOT . $path . $class_name . '.php';
        if (is_file($path)) {
            require_once($path);
        }
    }

    require_once(ROOT . '/controller/User.php');
    require_once(ROOT . '/controller/Functions.php');
    require_once(ROOT . '/controller/Admin.php');

    require_once(ROOT . '/controller/Useradmin.php');
    require_once(ROOT . '/controller/Adminadmin.php');
});
