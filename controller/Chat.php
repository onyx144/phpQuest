<?php

class Chat 
{
    // отображение формы чата для тестирования
    public function view()
    {
        require_once($_SERVER['DOCUMENT_ROOT'] . '/view/template/chat.php');
    }

    /*// крон для постоянного запуска сервера
    public function socketServerCron()
    {
        $server_active = false;

        $pidfile = $_SERVER['DOCUMENT_ROOT'] . '/tmp/my_pid_file.pid';

        if (is_file($pidfile)) {
            $pid = file_get_contents($pidfile);

            // получаем статус процесса
            $status = $this->getDaemonStatus($pid);

            if ($status['run']) {
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

                // запускаем повторно
                $output = null;
                exec("cd ../", $output);

                $output = null;
                exec("nohup php server.php &", $output);
            }
        }

        return $server_active;
    }*/

    /*// статус сервера
    private function getDaemonStatus($pid)
    {
        $result = ['run' => false];
        $output = null;
        exec("ps -aux -p " . $pid, $output);

        if (count($output) > 1) { // Если в результате выполнения больше одной строки то процесс есть! т.к. первая строка это заголовок, а вторая уже процесс
            $result['run'] = true;
            $result['info'] = $output[1]; // строка с информацией о процессе
        }

        return $result;
    }*/

    public function sendHeaders($headersText, $newSocket, $host, $port)
    {
        $headers = array();
        $tmpLine = preg_split("/\r\n/",$headersText);

        foreach ($tmpLine as $line) {
            $line = rtrim($line);
            if (preg_match('/\A(\S+): (.*)\z/', $line, $matches)) {
                $headers[$matches[1]] = $matches[2];
            }
        }

        $key = $headers['Sec-WebSocket-Key'];
        $sKey = base64_encode(pack('H*', sha1($key . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));

        $strHeadr = "HTTP/1.1 101 Switching Protocols \r\n" .
            "Upgrade: websocket\r\n" .
            "Connection: Upgrade\r\n" .
            "WebSocket-Origin: $host\r\n" .
            "WebSocket-Location: ws://$host:$port/server.php\r\n".
            "Sec-WebSocket-Accept:$sKey\r\n\r\n"
        ;

        socket_write($newSocket,$strHeadr, strlen($strHeadr));

    }

    public function newConnectionACK($client_ip_address)
    {
        $message = 'New client ' . $client_ip_address . ' connected';
        $messageArray = [
            "message" => $message,
            "type" => "newConnectionACK"
        ];
        $ask = $this->seal(json_encode($messageArray));

        return $ask;
    }
    
    public function seal($socketData)
    {
        $b1 = 0x81;
        $length = strlen($socketData);
        $header = "";

        if ($length <= 125) {
            $header = pack('CC', $b1, $length);
        } else if ($length > 125 && $length < 65536) {
            $header = pack('CCn', $b1, 126, $length);
        } else if ($length > 65536) {
            $header = pack('CCNN', $b1, 127, $length);
        }

        return $header . $socketData;
    }
    
    
    public function send($message, $clientSocketArray)
    {
        $messageLength = strlen($message);

        foreach ($clientSocketArray as $clientSocket) {
            @socket_write($clientSocket, $message, $messageLength);
        }

        return true;
    }

    public function unseal($socketData)
    {
        $length = ord($socketData[1]) & 127;

        if ($length == 126) {
            $mask = substr($socketData, 4, 4);
            $data = substr($socketData, 8);
        } else if ($length == 127) {
            $mask = substr($socketData, 10, 4);
            $data = substr($socketData, 14);
        } else {
            $mask = substr($socketData, 2, 4);
            $data = substr($socketData, 6);
        }

        $socketStr = "";
        
        for ($i = 0; $i < strlen($data); ++$i) {
            $socketStr .= $data[$i] ^ $mask[$i%4];
        }

        return $socketStr;
    }

    public function createChatMessage($username, $messageStr)
    {
        $message = $username . "<div>" . $messageStr . "</div>";
        $messageArray = [
            'type' =>'chat-box',
            'message' => $message
        ];

        return $this->seal(json_encode($messageArray));
    }

    public function newDisconectedACK($client_ip_address)
    {
        $message = 'Client ' . $client_ip_address . ' disconnected';
        $messageArray = [
            "message" => $message,
            "type" => "newConnectionACK"
        ];
        $ask = $this->seal(json_encode($messageArray));

        return $ask;
    }
}
