<?php

// активен ли сервер
function isDaemonActive($pidfile) {
	$server_active = false;

    if (is_file($pidfile)) {
        $pid = file_get_contents($pidfile);
    	// var_dump($pid);

        // получаем статус процесса
        // $status = getDaemonStatus($pid);
    	// var_dump($status);

        // if ($status['run']) {
    	if (posix_kill($pid, 0)) {
            // демон уже запущен
            // consolemsg("daemon already running info=".$status['info']);
            $server_active = true;
        } else {
            // pid-файл есть, но процесса нет
            // consolemsg("there is no process with PID = ".$pid.", last termination was abnormal...");
            // consolemsg("try to unlink PID file...");
            if (!unlink($pidfile)) {
                // consolemsg("ERROR");
                // не могу уничтожить pid-файл. ошибка
                // exit(-1);
            }
            // consolemsg("OK");
            $server_active = false;
        }
    }
    // var_dump($server_active);

    return $server_active;
}

// статус сервера
function getDaemonStatus($pid)
{
    $result = ['run' => false];
    $output = null;
    exec("ps -aux -p " . $pid, $output);

    if (count($output) > 1) { // Если в результате выполнения больше одной строки то процесс есть! т.к. первая строка это заголовок, а вторая уже процесс
        $result['run'] = true;
        $result['info'] = $output[1]; // строка с информацией о процессе
    }

    return $result;
}

// запускаем повторно, если нужно
// if (!isDaemonActive('tmp/my_pid_file.pid')) {
if (!isDaemonActive('/home/admin/web/digital-game2.example.com/public_html/tmp/my_pid_file.pid')) {
	// var_dump('+');
    /*$output = null;
    exec("cd ../", $output);*/

    $output = null;
    // exec("nohup php server.php &", $output);
    exec("nohup php /home/admin/web/digital-game2.example.com/public_html/server.php &", $output);

    exit();
}
