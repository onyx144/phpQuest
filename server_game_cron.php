<?php

// restore server
if (!empty($_GET['restore-server'])) {
    // $pidfile = '/home/admin/web/digital-game2.example.com/public_html/tmp/socket.pid';
    $pidfile = '/var/www/www-root/data/www/intelescape.com/tmp/socket.pid';

    if (is_file($pidfile)) {
        $pid = file_get_contents($pidfile);

        exec("kill -9 $pid");

        $output = null;
        $return = null;
        // exec("nohup php /home/admin/web/digital-game2.example.com/public_html/server_game.php &", $output, $return);
        // exec("nohup php /var/www/www-root/data/www/intelescape.com/server_game.php &", $output, $return);
        exec("nohup /opt/php74/bin/php /var/www/www-root/data/www/intelescape.com/server_game.php &", $output, $return);

        // $return = ['success' => 'ok'];
        // print_r(json_encode($return));
        echo $output;
        exit();
    }
}

// активен ли сервер
function isDaemonActive($pidfile) {
	$server_active = false;

    if (is_file($pidfile)) {
        $pid = file_get_contents($pidfile);

    	if (posix_kill($pid, 0)) {
            // демон уже запущен
            $server_active = true;
        } else {
            // pid-файл есть, но процесса нет
            if (!unlink($pidfile)) {
                // не могу уничтожить pid-файл. ошибка
            }
            $server_active = false;
        }
    }

    return $server_active;
}

// запускаем повторно, если нужно
// if (!isDaemonActive('/home/admin/web/digital-game2.example.com/public_html/tmp/socket.pid')) {
if (!isDaemonActive('/var/www/www-root/data/www/intelescape.com/tmp/socket.pid')) {
    $output = null;

    // exec("nohup php /home/admin/web/digital-game2.example.com/public_html/server_game.php &", $output);
    // exec("nohup php /var/www/www-root/data/www/intelescape.com/server_game.php &", $output);
    exec("nohup /opt/php74/bin/php /var/www/www-root/data/www/intelescape.com/server_game.php &", $output);

    exit();
} else {
    /*// дополнительно смотрим дату. Если больше 3 дней, то перезапускаем сервер тоже
    $time_change_file = DateTime::createFromFormat('U', filectime('/var/www/www-root/data/www/intelescape.com/tmp/socket.pid'));
    $time_now = new DateTime();
    $interval = $time_now->diff($time_change_file);

    $days = (int) $interval->d;
    // $minute = (int) $interval->i;

    if ($days >= 3) {
    // if ($minute >= 1) {
        $pidfile = '/var/www/www-root/data/www/intelescape.com/tmp/socket.pid';

        if (is_file($pidfile)) {
            $pid = file_get_contents($pidfile);

            exec("kill -9 $pid");

            $output = null;
            $return = null;

            exec("nohup /opt/php74/bin/php /var/www/www-root/data/www/intelescape.com/server_game.php &", $output, $return);

            exit();
        }
    }*/
}
