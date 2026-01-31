<?php
set_time_limit(0);
ignore_user_abort(true);

define('GD_ACCESS', true);
define('PORT', '8090');
// define('PORT', '8080');
// define('PORT', '443');
// define('PORT', '44301');

/*require_once('config.php');
require_once('core/DataBase.php');
require_once('core/Language.php');
require_once('controller/User.php');
require_once('controller/Functions.php');
require_once('controller/Admin.php');*/
require_once('controller/Socketgame.php');

$socket_object = new Socketgame();

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
socket_bind($socket, '127.0.0.1', PORT);
// socket_bind($socket, 0);

socket_listen($socket);

$clientSocketArray = array($socket);

// Определяем путь к PID файлу в зависимости от окружения
$pid_file = 'tmp/socket.pid';
if (!is_dir('tmp')) {
	mkdir('tmp', 0777, true);
}
file_put_contents($pid_file, getmypid());

while(true) {
    $newSocketArray = $clientSocketArray;
    $nullA = [];
    socket_select($newSocketArray, $nullA, $nullA, 0, 10);

    if (in_array($socket, $newSocketArray)) {
        $newSocket = socket_accept($socket);
        $clientSocketArray[] = $newSocket;
        
        $header = socket_read($newSocket, 1024);
        // Определяем хост динамически
        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
        $host = preg_replace('/:\d+$/', '', $host); // Убираем порт, если есть
        if ($host === 'localhost' || $host === '127.0.0.1') {
            $host = 'localhost';
        }
        $socket_object->sendHeaders($header, $newSocket, $host, PORT);

        socket_getpeername($newSocket, $client_ip_address);
        $connectionACK = $socket_object->newConnectionACK($client_ip_address);
        $socket_object->send($connectionACK, $clientSocketArray);

        $newSocketArrayIndex = array_search($socket, $newSocketArray);
        unset($newSocketArray[$newSocketArrayIndex]);
    }

    foreach ($newSocketArray as $newSocketArrayResource) {
        //1
        while(socket_recv($newSocketArrayResource, $socketData, 1024, 0) >= 1) {
            $socketMessage = $socket_object->unseal($socketData);
            $messageObj = json_decode($socketMessage);

            $chatMessage = $socket_object->createChatMessage($messageObj->op, $messageObj->parameters);

            $socket_object->send($chatMessage, $clientSocketArray);

            break 2;
        }

        //2
        $socketData = @socket_read($newSocketArrayResource, 1024, PHP_NORMAL_READ);
        if ($socketData === false) {
            socket_getpeername($newSocketArrayResource, $client_ip_address);
            $connectionACK = $socket_object->newDisconectedACK($client_ip_address);
            $socket_object->send($connectionACK, $clientSocketArray);

            $newSocketArrayIndex = array_search($newSocketArrayResource, $clientSocketArray);
            unset($clientSocketArray[$newSocketArrayIndex]);
        }
    }
}

socket_close($socket);
