<?php

class Login extends Admin
{
	function __construct()
    {
        parent::__construct();
    }

    // первая страница игры
    public function joinGame()
    {
    	// $this->refresh();

    	$this->meta_title = 'Join the Mission';

    	$this->styles[] = '<link rel="stylesheet" href="/view/css/login.css" />';

    	$translation = $this->lang->getWordsByPage('join_game');

    	require_once(ROOT . '/view/template/login/join_game.php');
    }

    // Ввод полученного кода доступа
    public function controlSystem()
    {
    	// если уже залогинены и код игры активен, то переходим сразу на игру. В противном случае разлогируем
    	if ($this->userInfo) {
    		/*if ($this->function->isActiveVerifyCode($this->userInfo['team_id'])) {
    			if ($this->lang->getParam('lang_abbr') == 'en') {
    				header('Location: /game');
    			} else {
    				header('Location: /no/game');
    			}
    		} else {*/
	            $query = "
	                UPDATE `users`
	                SET `hash` = ''
	                WHERE `id` = {?}
	            ";
	            if ($this->db->query($query, [$this->userInfo['id']])) {
	                setcookie('hash', '', time()+(60*60*24*1), '/');
	            }

	            if ($this->lang->getParam('lang_abbr') == 'en') {
    				header('Location: /control-system');
    			} else {
    				header('Location: /no/control-system');
    			}
    		// }

            exit();
    	}

    	$this->meta_title = 'Control System';

    	$this->body_class[] = 'body_game_bg';

    	$this->styles[] = '<link rel="stylesheet" href="/view/css/login.css" />';
    	$this->scripts_after[] = '<script src="/view/js/login.js"></script>';

    	$translation = $this->lang->getWordsByPage('control_system');

    	require_once(ROOT . '/view/template/login/control_system.php');
    }
}
