<?php

class User
{

    public $db;
    private $lang;
    private static $user = null;

    function __construct()
    {
        $this->db = DataBase::getDB();
        $this->lang = Language::getLang();
    }

    public static function getUser()
    {
        if (self::$user == null) {
            self::$user = new User();
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
        if (isset($_COOKIE['hash'])) {
            $query = "
                SELECT *
                FROM `users`
                WHERE `hash` = {?}
                AND `ip` = {?}
            ";
            $isAuth = $this->db->selectRow($query, [$_COOKIE['hash'], $this->getIp()]);
            
            if ($isAuth) {
                setcookie('hash', $isAuth['hash'], time()+(60*60*24*1), '/');

                // обновляем время последней активности юзера
                $query = "UPDATE `users` SET `activity` = NOW() WHERE `hash` = {?}";
                $this->db->query($query, [$_COOKIE['hash']]);
            } else {
                setcookie('hash', '', time()-(60*60*24*1), '/');
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

        if (isset($login) && isset($pass)) {
            $errArray = array();
            $isLogin = $this->validateLogin($login);
            $isPass = $this->validatePass($pass);

            if ($isLogin !== true) {
                $errArray['login'] = $isLogin;
            }
            if ($isPass !== true) {
                $errArray['pass'] = $isPass;
            }

            if (count($errArray) == 0) {
                $query = "
                    SELECT u.id, u.password, ur.url_alias_admin_first_id
                    FROM users u
                    JOIN users_role ur ON u.role_id = ur.id
                    WHERE (u.login = {?} OR u.phone = {?})
                    AND u.status = {?}
                    LIMIT 1
                ";
                $userDb = $this->db->selectRow($query, [$login, $login, 1]);
                if (!$userDb) {
                    $errArray['error'] = !empty($this->lang->getWordsParam('t_error_user_not_register')) ? $this->lang->getWordsParam('t_error_user_not_register') : 'Error';
                } else {
                    // if ($userDb['password'] === md5(md5($pass))) {
                    if ($userDb['password'] === $pass) {
                        $hash = openssl_random_pseudo_bytes(16);
                        $hash = bin2hex($hash);

                        $query = "
                            UPDATE `users`
                            SET `hash` = {?}
                            WHERE `id` = {?}
                        ";
                        if ($this->db->query($query, [$hash, $userDb['id']])) {
                            setcookie('hash', $hash, time() + (60 * 60 * 24 * 1), '/');
                        } else {
                            $errArray['error'] = !empty($this->lang->getWordsParam('t_error_unexpected')) ? $this->lang->getWordsParam('t_error_unexpected') : 'Error';
                        }
                    } else {
                        $errArray['pass'] = !empty($this->lang->getWordsParam('t_error_wrong_pass')) ? $this->lang->getWordsParam('t_error_wrong_pass') : 'Error';
                    }
                }
            }

            if (count($errArray) > 0) {
                $errArray = serialize($errArray);
                setcookie('err', $errArray, time() + (60 * 60 * 24 * 1), '/');
                setcookie('login', $login, time() + (60 * 60 * 24 * 1), '/');
                setcookie('pass', $pass, time() + (60 * 60 * 24 * 1), '/');
            } else {
                setcookie('err', '', time() - (60 * 60 * 24 * 1), '/');
                setcookie('login', '', time() + (60 * 60 * 24 * 1), '/');
                setcookie('pass', '', time() + (60 * 60 * 24 * 1), '/');

                if (!empty($userDb)) {
                    $query = "SELECT `url` FROM `url_alias_admin` WHERE `id` = {?} LIMIT 1";
                    $first_admin_url = $this->db->selectCell($query, [$userDb['url_alias_admin_first_id']]);
                    if ($first_admin_url) {
                        header('Location: /' . $first_admin_url);
                        exit();
                    }
                }
            }
        }
        
        header('Location: /');
        exit();
    }
    
    /**
     * logout user
     */
    public function logout()
    {
        $curUserInfo = $this->isAutorized();

        if (isset($_COOKIE['hash'])) {
            $query = "
                UPDATE `users`
                SET `hash` = ''
                WHERE `hash` = {?}
            ";
            if ($this->db->query($query, [$_COOKIE['hash']])) {
                setcookie('hash', '', time()+(60*60*24*1), '/');
            }
        }

        header('Location: /');
        
        exit();
    }

    private function validateLogin($val)
    {
        if (empty($val)) {
            $err = !empty($this->lang->getWordsParam('t_error_empty_login')) ? $this->lang->getWordsParam('t_error_empty_login') : 'Error';
        } elseif (strlen($val) < 3) {
            $err = !empty($this->lang->getWordsParam('t_error_short_login')) ? $this->lang->getWordsParam('t_error_short_login') : 'Error';
        } else {
            $err = true;
        }
        
        return $err;
    }

    private function validatePass($val) {
        if (empty($val)) {
            $err = !empty($this->lang->getWordsParam('t_error_empty_pass')) ? $this->lang->getWordsParam('t_error_empty_pass') : 'Error';
        } elseif (strlen($val) < 3 || strlen($val) > 30) {
            $err = !empty($this->lang->getWordsParam('t_error_pass_length')) ? $this->lang->getWordsParam('t_error_pass_length') : 'Error';
        } else {
            $err = true;
        }
        
        return $err;
    }
    
    /**
     * return all user list
     */
    /*public function getAllUsers()
    {
        $activeSection = 'allUser';
        echo 'all-user';
        return true;
    }*/
    
    /**
     * 
     */
    /*public function addUsers()
    {
        $activeSection = 'addUser';
        echo 'add-user';
        return true;
    }*/
}
