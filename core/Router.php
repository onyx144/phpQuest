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
    
    public function run()
    {
        $uri = $this->getURI();

        /* LANG */
        // check lang
        $langList = $this->lang->getAllActiveLangs('id');
        if ($langList && count($langList) > 0) {
            foreach ($langList as $langListItem) {
                $checklangPart = $langListItem['lang'];

                if (strripos($uri, '/' . $checklangPart . '/') !== false || substr($uri, 0, 3) == $checklangPart . '/' || (strlen($uri) == 2 && substr($uri, 0, 2) == $checklangPart)) {
                    $admin_lang_id = $langListItem['id'];

                    break;
                }
            }

            if (empty($admin_lang_id)) {
                $admin_lang_id = $this->lang->getDefaultLanguageId();
            }
        }

        $langArray = $this->lang->getLanguageById($admin_lang_id);

        // set active lang param
        foreach ($langArray as $key => $value) {
            $this->lang->setParam($key, $value);
        }

        /*// translation
        $words = $this->lang->getWordsByLangId($admin_lang_id);
        if ($words) {
            foreach ($words as $word) {
                $this->lang->setWordsParam($word['field'], $word['val']);
            }
        }*/
        /* LANG - end */

        if (array_key_exists($uri, $this->routes)) {
            $segments = explode('/', $this->routes[$uri]);
            
            // if not concrete item
            if (strripos($this->routes[$uri], '_id=') === false) {
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
