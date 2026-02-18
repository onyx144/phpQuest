<?php

class Language
{

    private static $lang = null; // Единственный экземпляр класса
    private $db;
    private $langParam = array(); // все параметры языка
    /*
	$langParam = [
		'lang' =>
		'lang_name' =>
		'lang_abbr' =>
		'id' =>
		'words' => [
			'field' => value
		]
	]
	*/

    private function __construct()
    {
        $this->db = DataBase::getDB();
    }

    public static function getLang()
    {
        if (self::$lang == null) {
            self::$lang = new Language();
        }
        return self::$lang;
    }

    /*public function getDefaultLanguage()
    {
    	$return = false;

    	$query = "
            SELECT `lang`, `lang_name`, `lang_abbr`, `id`
            FROM `langs`
            WHERE `lang_default` = {?}
            AND `status` = {?}
            LIMIT 1
        ";
        $langArray = $this->db->selectRow($query, [1, 1]);
        if ($langArray) {
        	$return = array(
        		'lang' => $langArray['lang'],
        		'lang_name' => $langArray['lang_name'],
        		'lang_abbr' => $langArray['lang_abbr'],
        		'id' => $langArray['id']
        	);
        }

        return $return;
    }*/

    public function getDefaultLanguageId()
    {
        $query = "SELECT `value` FROM `settings` WHERE `field_variable` = 'admin_language' LIMIT 1";
        return $this->db->selectCell($query);
    }

    public function getLanguageByCode($code)
    {
    	$return = false;

    	$query = "
            SELECT `lang`, `lang_name`, `lang_abbr`, `id`
            FROM `langs`
            WHERE `lang` = {?}
            AND `status` = {?}
            LIMIT 1
        ";
        $langArray = $this->db->selectRow($query, [$code, 1]);
        if ($langArray) {
        	$return = array(
        		'lang' => $langArray['lang'],
        		'lang_name' => $langArray['lang_name'],
        		'lang_abbr' => $langArray['lang_abbr'],
        		'id' => $langArray['id']
        	);
        }

        return $return;
    }

    public function getLanguageById($id)
    {
        $return = false;

        $query = "
            SELECT `lang`, `lang_name`, `lang_abbr`, `id`, `company_link`, `flag`, `lang_name_no`
            FROM `langs`
            WHERE `id` = {?}
            AND `status` = {?}
            LIMIT 1
        ";
        $langArray = $this->db->selectRow($query, [$id, 1]);
        if ($langArray) {
            $return = array(
                'lang' => $langArray['lang'],
                'lang_name' => $langArray['lang_name'],
                'lang_abbr' => $langArray['lang_abbr'],
                'id' => $langArray['id'],
                'company_link' => $langArray['company_link'],
                'flag' => $langArray['flag'],
                'lang_name_no' => $langArray['lang_name_no']
            );
        }

        return $return;
    }

    public function setParam($key, $value)
    {
    	$this->langParam[$key] = $value;
    }

    public function getParam($key)
    {
    	$return = false;

    	if (isset($this->langParam[$key])) {
    		$return = $this->langParam[$key];
    	}

    	return $return;
    }

    public function getWordsByLangId($lang_id = false)
    {
        if (!empty($lang_id)) {
            $check_lang_id = $lang_id;
        } else {
            $check_lang_id = $this->getParam('id');
        }

        $query = "
            SELECT `val`, `field`
            FROM `lang_words_admin`
            WHERE `language_id` = {?}
        ";
        return $this->db->select($query, [$check_lang_id]);
    }

    public function setWordsParam($field, $value)
    {
    	if (!isset($this->langParam['words'])) {
    		$this->langParam['words'] = array();
    	}
    	
    	$this->langParam['words'][$field] = $value;
    }

    public function getWordsParam($field)
    {
    	$return = false;

    	if (isset($this->langParam['words'][$field])) {
    		$return = $this->langParam['words'][$field];
    	}

    	return $return;
    }

    public function getWordsByPage($page, $lang_id = false)
    {
        $return = [];

        if (!empty($lang_id)) {
            $check_lang_id = $lang_id;
        } else {
            $check_lang_id = $this->getParam('id');
        }

        // Без привязки к странице (как в manage_languages) — подтягиваем все слова по языку
        if ($page === null || $page === '' || $page === false) {
            $sql = "SELECT `val`, `field` FROM `lang_words_admin` WHERE `language_id` = {?} ORDER BY `id`";
            $words = $this->db->select($sql, [$check_lang_id]);
        } else {
            $sql = "SELECT `val`, `field` FROM `lang_words_admin` WHERE `page` = {?} AND `language_id` = {?} ORDER BY `id`";
            $words = $this->db->select($sql, [$page, $check_lang_id]);
        }
        if ($words) {
            foreach ($words as $word) {
                $return[$word['field']] = $word['val'];
            }
        }

        return $return;
    }

    public function getAllActiveLangs($order = false)
    {
    	$query = "SELECT * FROM `langs` WHERE `status` = 1";

    	if ($order) {
    		$query .= " ORDER BY `" . $order . "`";
    	}

    	return $this->db->select($query);
    }

    // идентификатор языка в зависимости от атрибута языка, который в теге html
    public function getLangIdByHtmlAttr($abbr)
    {
        $sql = "SELECT `id` FROM `langs` WHERE `lang_abbr` = {?}";
        return $this->db->selectCell($sql, [$abbr]);
    }
}
