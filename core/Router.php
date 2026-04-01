<?php

class Router
{
    private $routes;
    private $lang;
    private $db;

    public function __construct()
    {
        $this->db = DataBase::getDB();
        $this->lang = Language::getLang();

        $query = "SELECT `url`, `method` FROM `url_alias_admin` WHERE `status` = 1 ORDER BY `id`";
        $admin_urls = $this->db->select($query);
        if ($admin_urls && count($admin_urls) > 0) {
            foreach ($admin_urls as $admin_url) {
                $this->routes[$admin_url['url']] = $admin_url['method'];
            }
        }
    }

    /**
     * Return page url
     * @return string
     */
    private function getURI()
    {
        $url = explode("?", trim($_SERVER['REQUEST_URI'])); // del get param
        $url = preg_replace("/\/+/",'/',$url[0]);
        $url = preg_replace("/^\/(.*)\/?$/U",'\\1',$url);

        return $url;
    }
    
    /**
     * Код языка из БД (langs.lang), нормализация латиницы.
     */
    private function normalizeLangCode($code)
    {
        $code = strtolower(trim((string) $code));
        $code = strtr($code, ['а' => 'a', 'А' => 'a']);

        return $code;
    }

    /**
     * Сопоставить ввод (GET/cookie/URL) с кодом langs.lang.
     */
    private function resolveLangCode($raw, array $langByCode)
    {
        $code = $this->normalizeLangCode($raw);
        if ($code === '') {
            return null;
        }
        if (isset($langByCode[$code])) {
            return $code;
        }
        if ($code === 'uk' && isset($langByCode['ua'])) {
            return 'ua';
        }

        return null;
    }

    public function run()
    {
        $uri = $this->getURI();

        /* LANG + маршрут без префикса языка (язык: localStorage → cookie site_lang, см. view/js/app_lang.js) */
        $langList = $this->lang->getAllActiveLangs('id');
        $langByCode = [];
        if ($langList && count($langList) > 0) {
            foreach ($langList as $langListItem) {
                $code = $this->normalizeLangCode($langListItem['lang']);
                $langByCode[$code] = (int) $langListItem['id'];
            }
        }

        $segments = ($uri === '') ? [] : explode('/', $uri);
        $urlLangCode = null;
        $routeSegments = $segments;

        if (count($segments) > 0) {
            $firstCode = $this->normalizeLangCode($segments[0]);
            if ($firstCode !== '' && isset($langByCode[$firstCode])) {
                $urlLangCode = $firstCode;
                array_shift($routeSegments);
            } elseif ($firstCode === 'uk' && isset($langByCode['ua'])) {
                $urlLangCode = 'ua';
                array_shift($routeSegments);
            }
        }

        $routeUri = count($routeSegments) ? implode('/', $routeSegments) : '';

        $resolvedCode = null;
        if (isset($_GET['lang']) && $_GET['lang'] !== '') {
            $resolvedCode = $this->resolveLangCode($_GET['lang'], $langByCode);
        }
        if ($resolvedCode === null && $urlLangCode !== null) {
            $resolvedCode = $urlLangCode;
        }
        if ($resolvedCode === null && !empty($_COOKIE['site_lang'])) {
            $resolvedCode = $this->resolveLangCode($_COOKIE['site_lang'], $langByCode);
        }

        if ($resolvedCode !== null && isset($langByCode[$resolvedCode])) {
            $admin_lang_id = $langByCode[$resolvedCode];
            if (!headers_sent()) {
                setcookie('site_lang', $resolvedCode, time() + 86400 * 365, '/', '', false, false);
            }
        } else {
            $admin_lang_id = $this->lang->getDefaultLanguageId();
        }

        $langArray = $this->lang->getLanguageById($admin_lang_id);
        if (!$langArray) {
            $admin_lang_id = $this->lang->getDefaultLanguageId();
            $langArray = $this->lang->getLanguageById($admin_lang_id);
        }

        // set active lang param
        if (is_array($langArray)) {
            foreach ($langArray as $key => $value) {
                $this->lang->setParam($key, $value);
            }
        }

        /*// translation
        $words = $this->lang->getWordsByLangId($admin_lang_id);
        if ($words) {
            foreach ($words as $word) {
                $this->lang->setWordsParam($word['field'], $word['val']);
            }
        }*/
        /* LANG - end */

        if (array_key_exists($routeUri, $this->routes)) {
            $segments = explode('/', $this->routes[$routeUri]);
            
            // if not concrete item
            if (strripos($this->routes[$routeUri], '_id=') === false) {
                $controllerName = ucfirst(array_shift($segments));
                $actionName = array_shift($segments);
                $idItem = 0;
            } else {
                $controllerNameArray = explode('_id=', array_shift($segments));
                $controllerName = ucfirst($controllerNameArray[0]);
                $actionName = 'info';
                $idItem = $controllerNameArray[1];
            }
            
            // include controller and do action
            $controllerFile = ROOT . '/controller/' . $controllerName . '.php';

            if (file_exists($controllerFile)) {
                include_once($controllerFile);
                
                $controllerObject = new $controllerName;
                $controllerObject->$actionName($idItem);
            }
        } else {
            $adminObject = new Admin();
            $adminObject->notFound();
        }
    }

}
