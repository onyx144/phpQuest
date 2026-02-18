<?php

trait Utils
{
    // активен ли сервер
    public function isDaemonActive($pidfile) {
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

    // конвертуємо дату з російського формату у англійський (Y-m-d)
    public function fromRusDatetimeToEng($datetime)
    {
        $return = '';

        if (stripos($datetime, ' ') !== false) {
            $parts = explode(' ', $datetime);

            $from_date = $parts[0];
            $from_time = $parts[1];
        } else {
            $from_date = $datetime;
            $from_time = '';
        }

        if (stripos($from_date, '.') !== false) {
            $parts1 = explode('.', $from_date);
            for ($i = count($parts1) - 1; $i >= 0; $i--) { 
                $return .= $parts1[$i] . '-';
            }
            $return = substr($return, 0, -1);
        }

        if (!empty($from_time)) {
            $time_object = new DateTime($from_time);
            $return .= ' ' . $time_object->format('H:i');
        }

        return $return;
    }

    // конвертуємо дату з англійського формату у російський (d.m.Y)
    public function fromEngDatetimeToRus($datetime)
    {
        $return = '';

        if (stripos($datetime, ' ') !== false) {
            $parts = explode(' ', $datetime);

            $from_date = $parts[0];
            $from_time = $parts[1];
        } else {
            $from_date = $datetime;
            $from_time = '';
        }

        if (stripos($from_date, '-') !== false) {
            $parts1 = explode('-', $from_date);
            for ($i = count($parts1) - 1; $i >= 0; $i--) { 
                $return .= $parts1[$i] . '.';
            }
            $return = substr($return, 0, -1);
        }

        if (!empty($from_time)) {
            $time_object = new DateTime($from_time);
            $return .= ' ' . $time_object->format('H:i');
        }

        return $return;
    }

    // ip юзера
    public function getIp() {
        $ipaddress = '';

        if (getenv('HTTP_CLIENT_IP')) {
            $ipaddress = getenv('HTTP_CLIENT_IP');
        } else if (getenv('HTTP_X_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        } else if (getenv('HTTP_X_FORWARDED')) {
            $ipaddress = getenv('HTTP_X_FORWARDED');
        } else if (getenv('HTTP_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        } else if (getenv('HTTP_FORWARDED')) {
            $ipaddress = getenv('HTTP_FORWARDED');
        } else if (getenv('REMOTE_ADDR')) {
            $ipaddress = getenv('REMOTE_ADDR');
        } else {
            $ipaddress = 'UNKNOWN';
        }

        return $ipaddress;
    }

    // аналог языковой функции из класса Language
    private function getWordsByPage($page, $lang_id)
    {
        $return = [];

        // Без привязки к странице (как в manage_languages) — подтягиваем все слова по языку
        if ($page === null || $page === '' || $page === false) {
            $sql = "SELECT `val`, `field` FROM `lang_words_admin` WHERE `language_id` = {?} ORDER BY `id`";
            $words = $this->db->select($sql, [$lang_id]);
        } else {
            $sql = "SELECT `val`, `field` FROM `lang_words_admin` WHERE `page` = {?} AND `language_id` = {?} ORDER BY `id`";
            $words = $this->db->select($sql, [$page, $lang_id]);
        }
        if ($words) {
            foreach ($words as $word) {
                $return[$word['field']] = $word['val'];
            }
        }

        return $return;
    }
}

