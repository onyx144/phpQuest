<?php

class Game extends Admin
{
    private $svg = [
        'dashboard_dashboard' => '<svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.75 0H21V2L19.25 4H0V2L1.75 0Z" fill="#00F0FF"/><path d="M1.75 6H7.25V19L5.5 21H0V8L1.75 6Z" fill="#00F0FF"/><path d="M11.75 10H21V13L19.25 15H10V12L11.75 10Z" fill="#00F0FF"/><path d="M10.75 6H21V8L20.25 9H10V7L10.75 6Z" fill="#00F0FF"/><path d="M11.75 16H21V19L19.25 21H10V18L11.75 16Z" fill="#00F0FF"/></svg>',
        'dashboard_calls' => '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18.8889 13.8889C17.5056 13.8889 16.1667 13.6667 14.9222 13.2556C14.5389 13.1333 14.1 13.2222 13.7945 13.5278L11.3501 15.9778C8.20005 14.3778 5.62781 11.8056 4.02781 8.66115L6.47224 6.20557C6.77781 5.9 6.86667 5.46115 6.74448 5.07781C6.33339 3.83339 6.11115 2.49448 6.11115 1.11115C6.11109 0.494427 5.61667 0 5 0H1.11109C0.5 0 0 0.494427 0 1.11109C0 11.5444 8.45557 20 18.8889 20C19.5056 20 20 19.5056 20 18.8889V15C20 14.3833 19.5056 13.8889 18.8889 13.8889Z" fill="#00F0FF"/></svg>',
        'dashboard_files' => '<svg width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M20.25 0H7L0 8V20H18.5L20.25 18V0ZM7 7H3.75L3 8V10H6.25L7 9V7ZM9.75 7H18V9L17.25 10H9V8L9.75 7ZM18 3H9.75L9 4V6H17.25L18 5V3ZM3.75 11H7V13L6.25 14H3V12L3.75 11ZM18 11H9.75L9 12V14H17.25L18 13V11ZM3.75 15H7V17L6.25 18H3V16L3.75 15ZM18 15H9.75L9 16V18H17.25L18 17V15Z" fill="#00F0FF"/></svg>',
        'dashboard_databases' => '<svg width="19" height="21" viewBox="0 0 19 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 0H19V3L17.25 5H0V2L1.75 0ZM1.73684 2H3V3.2L2.26316 4H1V2.8L1.73684 2ZM6 2H4.73684L4 2.8V4H5.26316L6 3.2V2ZM7.73684 2H9V3.2L8.26316 4H7V2.8L7.73684 2ZM17 2H10.7368L10 2.8V4H16.2632L17 3.2V2Z" fill="#00F0FF"/><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 8H19V11L17.25 13H0V10L1.75 8ZM1.73684 10H3V11.2L2.26316 12H1V10.8L1.73684 10ZM6 10H4.73684L4 10.8V12H5.26316L6 11.2V10ZM7.73684 10H9V11.2L8.26316 12H7V10.8L7.73684 10ZM17 10H10.7368L10 10.8V12H16.2632L17 11.2V10Z" fill="#00F0FF"/><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 16H19V19L17.25 21H0V18L1.75 16ZM1.73684 18H3V19.2L2.26316 20H1V18.8L1.73684 18ZM6 18H4.73684L4 18.8V20H5.26316L6 19.2V18ZM7.73684 18H9V19.2L8.26316 20H7V18.8L7.73684 18ZM17 18H10.7368L10 18.8V20H16.2632L17 19.2V18Z" fill="#00F0FF"/><rect width="15" height="1" transform="matrix(1 0 0 -1 2 7)" fill="#00F0FF"/><rect width="15" height="1" transform="matrix(1 0 0 -1 2 15)" fill="#00F0FF"/></svg>',
        'dashboard_tools' => '<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.75 0H17.75V5L16 7H0V2L1.75 0Z" fill="#00F0FF"/><path d="M1.75 9H17.75V10.5L16 12.5H0V11L1.75 9Z" fill="#00F0FF"/><path d="M1.75 14H17.75V15.5L16 17.5H0V16L1.75 14Z" fill="#00F0FF"/></svg>'
    ];

    function __construct()
    {
        parent::__construct();
    }

    // Основная страница игры
    public function main()
    {
        // если истек код игры. В противном случае разлогируем
        if ($this->userInfo) {
            if (!$this->function->isActiveVerifyCode($this->userInfo['team_id'])) {
                $query = "
                    UPDATE `users`
                    SET `hash` = ''
                    WHERE `id` = {?}
                ";
                if ($this->db->query($query, [$this->userInfo['id']])) {
                    setcookie('hash', '', time()+(60*60*24*1), '/');
                }
                
                if ($this->lang->getParam('lang_abbr') == 'en') {
                    header('Location: /');
                } else {
                    header('Location: /no');
                }

                exit();
            }
        } else {
            if ($this->lang->getParam('lang_abbr') == 'en') {
                header('Location: /');
            } else {
                header('Location: /no');
            }

            exit();
        }

        $this->meta_title = 'Mission Control System';

        $this->styles[] = '<link rel="stylesheet" href="/view/css/game.css" />';
        $this->styles[] = '<link rel="stylesheet" href="/plugins/mCustomScrollbar/mCustomScrollbar.css" />';

        $this->scripts[] = '<script src="/plugins/jquery.jplayer.min.js"></script>';
        $this->scripts[] = '<script src="/plugins/mCustomScrollbar/jquery.mCustomScrollbar.js"></script>';

        $translation = $this->lang->getWordsByPage('game');

        $team_info = $this->function->teamInfo($this->userInfo['team_id']);

        require_once(ROOT . '/view/template/game/main.php');
    }

    // Отдельная страница результатов
    public function results()
    {
        $this->meta_title = 'Results';

        $this->styles[] = '<link rel="stylesheet" href="/view/css/game.css" />';
        $this->styles[] = '<link rel="stylesheet" href="/plugins/mCustomScrollbar/mCustomScrollbar.css" />';
        
        $this->scripts[] = '<script src="/plugins/jquery.jplayer.min.js"></script>';
        $this->scripts[] = '<script src="/plugins/mCustomScrollbar/jquery.mCustomScrollbar.js"></script>';

        $translation = $this->lang->getWordsByPage('game');

        $team_info = $this->function->teamInfo($this->userInfo['team_id']);

        require_once(ROOT . '/view/template/game/results.php');
    }

    /*// инфа о команде
    private function teamInfo()
    {
        $sql = "SELECT * FROM `teams` WHERE `id` = {?} LIMIT 1";
        return $this->db->selectRow($sql, [$this->userInfo['team_id']]);
    }*/
}
