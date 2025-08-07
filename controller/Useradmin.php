<?php

class Useradmin
{

    public $db;
    private static $user = null;

    function __construct()
    {
        $this->db = DataBase::getDB();
    }

    public static function getUser()
    {
        if (self::$user == null) {
            self::$user = new Useradmin();
        }
        return self::$user;
    }

    // ip юзера
    private function getIp() {
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

    /**
     * check if user autorized
     * @return boolean
     */
    public function isAutorized()
    {
        if (isset($_COOKIE['hash_admin'])) {
            $query = "
                SELECT *
                FROM `admin_users`
                WHERE `hash` = {?}
                AND `status` = {?}
            ";
            $isAuth = $this->db->selectRow($query, [$_COOKIE['hash_admin'], 1]);
            
            if ($isAuth) {
                setcookie('hash_admin', $isAuth['hash'], time()+(60*60*24*1), '/');

                // обновляем время последней активности юзера
                $query = "UPDATE `admin_users` SET `activity` = NOW() WHERE `hash` = {?}";
                $this->db->query($query, [$_COOKIE['hash_admin']]);
            } else {
                setcookie('hash_admin', '', time()-(60*60*24*1), '/');
            }
        } else {
            $isAuth = false;
        }

        return $isAuth;
    }
    
    /**
     * try autorized. Action when autorized form submit
     */
    public function getAuth()
    {
        foreach ($_POST as $key => $value) {
            $$key = trim($value);
        }

        if (isset($login) && isset($password)) {
            $err_login = false;
            $err_password = false;

            if ($login == '') {
                $err_login = 'Login required';
            } elseif (strlen($login) < 3 || strlen($login) > 30) {
                $err_login = 'Login length from 3 to 30 symbols';
            } else {
                $sql = "SELECT `id` FROM `admin_users` WHERE `login` = {?} LIMIT 1";
                $isset_login = $this->db->selectCell($sql, [$login]);
                if (!$isset_login) {
                    $err_login = 'Login not register';
                }
            }

            if ($password == '') {
                $err_password = 'Password required';
            } elseif (strlen($password) < 3 || strlen($password) > 30) {
                $err_password = 'Password length from 3 to 30 symbols';
            }

            setcookie('admin-login', $login, time() + (60 * 60 * 24 * 1), '/');
            setcookie('admin-password', $password, time() + (60 * 60 * 24 * 1), '/');

            if ($err_login || $err_password) {
                if ($err_login) {
                    setcookie('admin-err-login', $err_login, time() + (60 * 60 * 24 * 1), '/');
                } else {
                    setcookie('admin-err-login', '', time() + (60 * 60 * 24 * 1), '/');
                }

                if ($err_password) {
                    setcookie('admin-err-password', $err_password, time() + (60 * 60 * 24 * 1), '/');
                } else {
                    setcookie('admin-err-password', '', time() + (60 * 60 * 24 * 1), '/');
                }
            } else {
                $sql = "
                    SELECT u.id, ur.url_alias_admin_first_id
                    FROM admin_users u
                    JOIN users_role ur ON u.role_id = ur.id
                    WHERE u.login = {?}
                    AND u.password = {?}
                    AND u.status = {?}
                    AND (u.role_id = {?} OR u.role_id = {?})
                    LIMIT 1
                ";
                $userDb = $this->db->selectRow($sql, [$login, $password, 1, 2, 3]);
                if (!$userDb) {
                    setcookie('admin-err-password', 'Wrong password', time() + (60 * 60 * 24 * 1), '/');
                } else {
                    $sql = "SELECT `url` FROM `url_alias_admin` WHERE `id` = {?}";
                    $first_admin_url = $this->db->selectCell($sql, [$userDb['url_alias_admin_first_id']]);
                    if ($first_admin_url) {
                        setcookie('admin-login', '', time() + (60 * 60 * 24 * 1), '/');
                        setcookie('admin-password', '', time() + (60 * 60 * 24 * 1), '/');

                        setcookie('admin-err-login', '', time() + (60 * 60 * 24 * 1), '/');
                        setcookie('admin-err-password', '', time() + (60 * 60 * 24 * 1), '/');

                        $hash = openssl_random_pseudo_bytes(16);
                        $hash = bin2hex($hash);

                        $sql = "UPDATE `admin_users` SET `hash` = {?} WHERE `id` = {?}";
                        $this->db->query($sql, [$hash, $userDb['id']]);

                        setcookie('hash_admin', $hash, time()+(60*60*24*1), '/');

                        header('Location: /' . $first_admin_url);
                        exit();
                    }
                }
            }
        }
        
        header('Location: /login');
        exit();
    }
    
    /**
     * logout user
     */
    public function logout()
    {
        $curUserInfo = $this->isAutorized();

        if (isset($_COOKIE['hash_admin'])) {
            $query = "
                UPDATE `admin_users`
                SET `hash` = ''
                WHERE `hash` = {?}
            ";
            if ($this->db->query($query, [$_COOKIE['hash_admin']])) {
                setcookie('hash_admin', '', time()+(60*60*24*1), '/');
            }
        }

        header('Location: /login');
        
        exit();
    }
}
