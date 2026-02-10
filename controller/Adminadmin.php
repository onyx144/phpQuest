<?php

class Adminadmin
{
	protected $user;
    protected $userInfo;
    protected $db;
    protected $urlPag;
    protected $page;
    protected $limit;
    protected $start; // початкова позиція пагінації
    protected $settings = [];
    protected $pagination = false; // переменная для вывода пагинации
    protected $pagetitle = '';
    protected $body_class = [];

    function __construct()
    {
    	// db
    	$this->db = DataBase::getDB();

    	// user
    	$this->user = Useradmin::getUser();
    	$this->userInfo = $this->user->isAutorized();

	    // link without page param
	    $url_param = $this->getUrlPag();

	    $this->urlPag = '/' . $url_param[0];
	    if (count($url_param[1]) > 0) {
	    	$this->urlPag .= '?';

	    	foreach ($url_param[1] as $get_field => $get_val) {
	    		if ($get_field == 'page') {
	    			continue;
	    		}

	    		$this->urlPag .= $get_field . '=' . $get_val . '&';
	    	}

	    	$this->urlPag = substr($this->urlPag, 0, -1);
	    }

	    // page - get param for pagination
	    $this->page = (isset($_GET['page'])) ? (int) $_GET['page'] : 1;

	    // settings
	    $query = "SELECT `field_variable`, `value` FROM `admin_settings` ORDER BY `id`";
	    $setting_db = $this->db->select($query);
	    if ($setting_db) {
	    	foreach ($setting_db as $setting_item) {
	    		$this->settings[$setting_item['field_variable']] = $setting_item['value'];
	    	}
	    }

	    // start pagination
	    $this->start = ($this->page - 1) * $this->settings['limit'];
    }

    // обробка урла для формування пагінації
    private function getUrlPag()
    {
        $getParams = array();
        $url = trim($_SERVER['REQUEST_URI']);
        if (stristr($url, '?') !== false) {
            $parts = explode("?", $url);
            $url = preg_replace("/\/+/", '/', $parts[0]);

            if (stristr($parts[1], '&') !== false) {
                $parts1 = explode("&", $parts[1]);

                foreach ($parts1 as $value) {
                    if (stristr($value, '=') !== false) {
                        $parts2 = explode("=", $value);
                        $key = $parts2[0];
                        $val = $parts2[1];
                        $getParams[$key] = $val;
                    }
                }
            } else {
                if (stristr($parts[1], '=') !== false) {
                    $parts1 = explode("=", $parts[1]);
                    $key = $parts1[0];
                    $val = $parts1[1];
                    $getParams[$key] = $val;
                } else {
                    $key = $parts[1];
                    $getParams[$key] = '';
                }
            }
        } else {
            $url = preg_replace("/\/+/", '/', $url);
        }

        $url = preg_replace("/^\/(.*)\/?$/U", '\\1', $url);

        return array($url, $getParams);
    }

    protected function startPagination($qt)
    {
        $this->pagination = new Pagination();
        $this->pagination->total = $qt;
        $this->pagination->page = $this->page;
        $this->pagination->limit = $this->settings['limit'];
        $this->pagination->url = $this->urlPag . (stripos($this->urlPag, '?') !== false ? '&' : '?') . 'page={page}';
    }

    protected function generateRandomString($length = 100)
	{
	    $string = "";

	    $chars = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	    $numChars = strlen($chars);

	    for ($j = 0; $j < $length; $j++) {
	        $string .= substr($chars, rand(1, $numChars) - 1, 1);
	    }

	    return $string;
	}




    // страница авторизации
    public function login()
    {
    	$this->pagetitle = 'Sign in';

    	$this->body_class[] = 'd-flex';
    	$this->body_class[] = 'align-items-center';
    	$this->body_class[] = 'bg-auth';
    	$this->body_class[] = 'border-top';
    	$this->body_class[] = 'border-top-2';
    	$this->body_class[] = 'border-primary';

    	require_once(ROOT . '/admin/view/template/login.php');
    }

    // страница продаж
    public function sales()
    {
    	if (!$this->userInfo) {
    		header('Location: /login');
        	exit();
    	}

    	$this->pagetitle = 'Sales';

    	if ($this->userInfo['role_id'] == 2) {
    		// продажи
	    	$sql = "
	    		SELECT s.datetime_sale, s.game_name, s.price_dollar, s.price_norway_crone, s.team_code, s.source_name, s.source_code, s.client_email
	    		FROM admin_sales s
	    		WHERE s.status = {?}
	    		ORDER BY s.datetime_sale DESC, s.id DESC
	    		LIMIT " . $this->start . ", " . $this->settings['limit'];
	    	$sales = $this->db->select($sql, [1]);

	    	// к-во продаж
	    	$sql = "SELECT COUNT(id) FROM admin_sales WHERE status = {?}";
	    	$qt_sales = $this->db->selectCell($sql, [1]);

	    	// Removed
	    	$sql = "
	    		SELECT s.datetime_sale, s.game_name, s.price_dollar, s.price_norway_crone, s.team_code, s.source_name, s.source_code, s.client_email
	    		FROM admin_sales s
	    		WHERE s.status = {?}
	    		ORDER BY s.datetime_sale DESC, s.id DESC
	    		LIMIT " . $this->start . ", " . $this->settings['limit'];
	    	$sales_removed = $this->db->select($sql, [2]);

	    	$sql = "SELECT COUNT(id) FROM admin_sales WHERE status = {?}";
	    	$qt_sales_removed = $this->db->selectCell($sql, [2]);
	    } elseif ($this->userInfo['role_id'] == 3) {
	    	// продажи
	    	$sql = "
	    		SELECT s.datetime_sale, s.game_name, s.price_dollar, s.price_norway_crone, s.team_code, s.source_name, s.source_code, s.client_email
	    		FROM admin_sales s
	    		WHERE s.source_id = {?}
	    		AND s.status = {?}
	    		ORDER BY s.datetime_sale DESC, s.id DESC
	    		LIMIT " . $this->start . ", " . $this->settings['limit'];
	    	$sales = $this->db->select($sql, [$this->userInfo['source_id'], 1]);

	    	// к-во продаж
	    	$sql = "SELECT COUNT(id) FROM admin_sales WHERE source_id = {?} AND status = {?}";
	    	$qt_sales = $this->db->selectCell($sql, [$this->userInfo['source_id'], 1]);

	    	// Removed
	    	$sql = "
	    		SELECT s.datetime_sale, s.game_name, s.price_dollar, s.price_norway_crone, s.team_code, s.source_name, s.source_code, s.client_email
	    		FROM admin_sales s
	    		WHERE s.source_id = {?}
	    		AND s.status = {?}
	    		ORDER BY s.datetime_sale DESC, s.id DESC
	    		LIMIT " . $this->start . ", " . $this->settings['limit'];
	    	$sales_removed = $this->db->select($sql, [$this->userInfo['source_id'], 2]);

	    	$sql = "SELECT COUNT(id) FROM admin_sales WHERE source_id = {?} AND status = {?}";
	    	$qt_sales_removed = $this->db->selectCell($sql, [$this->userInfo['source_id'], 2]);
	    }

    	$this->startPagination($qt_sales);

    	$pagination_removed = new Pagination();
        $pagination_removed->total = $qt_sales_removed;
        $pagination_removed->page = $this->page;
        $pagination_removed->limit = $this->settings['limit'];
        $pagination_removed->url = $this->urlPag . (stripos($this->urlPag, '?') !== false ? '&' : '?') . 'page={page}';

    	require_once(ROOT . '/admin/view/template/sales.php');
    }

    // добавить продажу. Сгенерировать новый код
    public function addSale()
    {
    	if (!$this->userInfo) {
    		header('Location: /login');
        	exit();
    	}

    	if ($this->userInfo['role_id'] != 2) {
    		header('Location: /sales');
        	exit();
    	}

    	$this->pagetitle = 'Generate Game Code';

    	$sql = "SELECT * FROM `admin_source` ORDER BY `source_name`";
    	$sources = $this->db->select($sql);

    	$sql = "SELECT * FROM `admin_game_names` ORDER BY `game_name`";
    	$game_names = $this->db->select($sql);

    	// 10 codes
		$codes = [];

		for ($i=1; $i <= 10; $i++) {
			$code = $this->generateRandomString(10);

			$sql = "SELECT id FROM admin_sales WHERE team_code = {?} LIMIT 1";

			if ($this->db->selectCell($sql, [$code])) {
				$code = $this->generateRandomString(15);

				$sql = "SELECT id FROM admin_sales WHERE team_code = {?} LIMIT 1";

				if ($this->db->selectCell($sql, [$code])) {
					$code = $this->generateRandomString(20);
				}
			}

			$codes[] = $code;
		}

    	require_once(ROOT . '/admin/view/template/add_sales.php');
    }

    // страница результатов
    public function games()
    {
    	if (!$this->userInfo) {
    		header('Location: /login');
        	exit();
    	}

    	$this->pagetitle = 'Games';

    	$order = !empty($_GET['order']) ? trim(strip_tags($_GET['order'])) : 'date';
    	$sort = !empty($_GET['sort']) ? trim(strip_tags($_GET['sort'])) : 'desc';

    	if ($this->userInfo['role_id'] == 2) {
	    	// игры
			$sql = "
	    		SELECT t.team_name, t.progress_percent, t.mission_accept_datetime, t.mission_finish_datetime, t.hints_open, t.score, t.mission_finish_seconds, t.id, t.create, t.code, s.source_code
	    		FROM teams t
	    		JOIN admin_sales s ON t.code = s.team_code
	    		WHERE s.status = {?}";

	    	if ($order == 'date') {
	    		$sql .= " ORDER BY t.create";
	    	} elseif ($order == 'score') {
	    		$sql .= " ORDER BY t.score+0";
	    	} else {
	    		$sql .= " ORDER BY t.create";
	    	}

	    	if ($sort == 'desc') {
	    		$sql .= " DESC";
	    	}

	    	$sql .= " LIMIT " . $this->start . ", " . $this->settings['limit'];

			$games = $this->db->select($sql, [1]);

	    	// к-во игр
	    	$sql = "SELECT COUNT(id) FROM admin_sales WHERE status = {?}";
	    	$qt_games = $this->db->selectCell($sql, [1]);
	    } elseif ($this->userInfo['role_id'] == 3) {
	    	// игры
	    	$sql = "
	    		SELECT t.team_name, t.progress_percent, t.mission_accept_datetime, t.mission_finish_datetime, t.hints_open, t.score, t.mission_finish_seconds, t.id, t.create, t.code, s.source_code
	    		FROM teams t
	    		JOIN admin_sales s ON t.code = s.team_code
	    		WHERE s.source_id = {?}
	    		AND s.status = {?}";

	    	if ($order == 'date') {
	    		$sql .= " ORDER BY t.create";
	    	} elseif ($order == 'score') {
	    		$sql .= " ORDER BY t.score";
	    	} else {
	    		$sql .= " ORDER BY t.create";
	    	}

	    	if ($sort == 'desc') {
	    		$sql .= " DESC";
	    	}

	    	$sql .= " LIMIT " . $this->start . ", " . $this->settings['limit'];
	    	
			$games = $this->db->select($sql, [$this->userInfo['source_id'], 1]);

	    	// к-во игр
	    	$sql = "SELECT COUNT(id) FROM admin_sales WHERE source_id = {?} AND status = {?}";
	    	$qt_games = $this->db->selectCell($sql, [$this->userInfo['source_id'], 1]);
	    }

    	$this->startPagination($qt_games);

    	require_once(ROOT . '/admin/view/template/games.php');
    }

    // графики по к-ву продаж
    public function totalSales()
    {
    	if (!$this->userInfo) {
    		header('Location: /login');
        	exit();
    	}

    	$this->pagetitle = 'Total Sales';

    	// года, за которые были продажи
    	$years = [];

    	// for ($i = 2022; $i <= date('Y'); $i++) {
    	for ($i = 2021; $i <= date('Y'); $i++) {
    		$years[] = $i;
    	}

    	$cur_year = isset($_GET['year']) ? (int) $_GET['year'] : date('Y');

    	/* СО ВСЕХ ИСТОЧНИКОВ */
	    	if ($this->userInfo['role_id'] == 2) {
	    		$sales_all = [];

		    	// количество продаж по месяцам по выбранному году
		    	for ($i = 1; $i <= 12; $i++) { 
		    		$sql = "SELECT COUNT(`id`) FROM `admin_sales` WHERE MONTH(`datetime_sale`) = {?} AND YEAR(`datetime_sale`) = {?} AND `status` = {?}";
		    		$sales_all[$i] = (int) $this->db->selectCell($sql, [$i, $cur_year, 1]);
		    	}
		    }

	    /* ОТДЕЛЬНО ПО КАЖДОМУ ИСТОЧНИКУ */
	    	$sales_sources = [];

	    	if ($this->userInfo['role_id'] == 2) {
		    	$sql = "SELECT DISTINCT(`source_name`) FROM `admin_sales` WHERE `status` = {?} ORDER BY `source_name`";
		    	$sources = $this->db->select($sql, [1]);
		    } elseif ($this->userInfo['role_id'] == 3) {
		    	$sql = "SELECT `source_name` FROM `admin_source` WHERE `id` = {?} AND `status` = {?}";
		    	$sources = $this->db->select($sql, [$this->userInfo['source_id'], 1]);
		    }

	    	if (isset($sources) && $sources) {
	    		foreach ($sources as $source) {
	    			$source_array = [];

	    			// количество продаж по месяцам по выбранному году
			    	for ($i = 1; $i <= 12; $i++) { 
			    		$sql = "SELECT COUNT(`id`) FROM `admin_sales` WHERE MONTH(`datetime_sale`) = {?} AND YEAR(`datetime_sale`) = {?} AND `source_name` = {?} AND `status` = {?}";
			    		$source_array[$i] = (int) $this->db->selectCell($sql, [$i, $cur_year, $source['source_name'], 1]);
			    	}

			    	$sales_sources[$source['source_name']] = $source_array;
	    		}
	    	}

    	require_once(ROOT . '/admin/view/template/total_sales.php');
    }

    // графики по стоимости продаж
    public function totalSalesCost()
    {
		if (!$this->userInfo) {
    		header('Location: /login');
        	exit();
    	}

    	$this->pagetitle = 'Total Sales Sum';

    	// года, за которые были продажи
    	$years = [];

    	// for ($i = 2022; $i <= date('Y'); $i++) {
    	for ($i = 2021; $i <= date('Y'); $i++) {
    		$years[] = $i;
    	}

    	$cur_year = isset($_GET['year']) ? (int) $_GET['year'] : date('Y');

    	// валюта
    	$currency = isset($_GET['currency']) ? $_GET['currency'] : 'usd';

    	// $currency_symbol = '$';

    	/* СО ВСЕХ ИСТОЧНИКОВ */
	    	if ($this->userInfo['role_id'] == 2) {
	    		$sales_all = [];

		    	// количество продаж по месяцам по выбранному году
		    	for ($i = 1; $i <= 12; $i++) {
		    		if ($currency == 'usd') {
		    			$sql = "SELECT SUM(`price_dollar`) FROM `admin_sales` WHERE MONTH(`datetime_sale`) = {?} AND YEAR(`datetime_sale`) = {?} AND `status` = {?}";
		    		} elseif ($currency == 'nok') {
		    			$sql = "SELECT SUM(`price_norway_crone`) FROM `admin_sales` WHERE MONTH(`datetime_sale`) = {?} AND YEAR(`datetime_sale`) = {?} AND `status` = {?}";
		    		}
		    		$sales_all[$i] = (int) $this->db->selectCell($sql, [$i, $cur_year, 1]);
		    	}
		    }

	    /* ОТДЕЛЬНО ПО КАЖДОМУ ИСТОЧНИКУ */
	    	$sales_sources = [];

	    	if ($this->userInfo['role_id'] == 2) {
	    		$sql = "SELECT DISTINCT(`source_name`) FROM `admin_sales` WHERE `status` = {?} ORDER BY `source_name`";
	    		$sources = $this->db->select($sql, [1]);
		    } elseif ($this->userInfo['role_id'] == 3) {
		    	$sql = "SELECT `source_name` FROM `admin_source` WHERE `id` = {?} AND `status` = {?}";
		    	$sources = $this->db->select($sql, [$this->userInfo['source_id'], 1]);
		    }

	    	if (isset($sources) && $sources) {
	    		foreach ($sources as $source) {
	    			$source_array = [];

	    			// количество продаж по месяцам по выбранному году
			    	for ($i = 1; $i <= 12; $i++) {
			    		if ($currency == 'usd') {
			    			$sql = "SELECT SUM(`price_dollar`) FROM `admin_sales` WHERE MONTH(`datetime_sale`) = {?} AND YEAR(`datetime_sale`) = {?} AND `source_name` = {?} AND `status` = {?}";
			    		} elseif ($currency == 'nok') {
			    			$sql = "SELECT SUM(`price_norway_crone`) FROM `admin_sales` WHERE MONTH(`datetime_sale`) = {?} AND YEAR(`datetime_sale`) = {?} AND `source_name` = {?} AND `status` = {?}";
			    		}
			    		$source_array[$i] = (int) $this->db->selectCell($sql, [$i, $cur_year, $source['source_name'], 1]);
			    	}

			    	$sales_sources[$source['source_name']] = $source_array;
	    		}
	    	}

    	require_once(ROOT . '/admin/view/template/total_sales_sum.php');
    }

    // пользователи
    public function users()
    {
    	if (!$this->userInfo) {
    		header('Location: /login');
        	exit();
    	}

    	if ($this->userInfo['role_id'] != 2) {
    		header('Location: /sales');
        	exit();
    	}

    	$this->pagetitle = 'Users';

    	// пользователи
    	$sql = "SELECT * FROM `admin_users` ORDER BY `role_id`, `login`";
    	$users = $this->db->select($sql);

    	// к-во пользователей
    	$sql = "SELECT COUNT(id) FROM admin_users";
    	$qt_users = $this->db->selectCell($sql);

    	$this->startPagination($qt_users);

    	require_once(ROOT . '/admin/view/template/users.php');
    }

    // добавить пользователя
    public function addUser()
    {
    	if (!$this->userInfo) {
    		header('Location: /login');
        	exit();
    	}

    	if ($this->userInfo['role_id'] != 2) {
    		header('Location: /sales');
        	exit();
    	}

    	$this->pagetitle = 'Add User';

    	$sql = "SELECT * FROM `admin_source` ORDER BY `source_name`";
    	$sources = $this->db->select($sql);

    	require_once(ROOT . '/admin/view/template/add_user.php');
    }

    // редактировать пользователя
    public function editUser()
    {
    	if (!$this->userInfo) {
    		header('Location: /login');
        	exit();
    	}

    	if ($this->userInfo['role_id'] != 2) {
    		header('Location: /sales');
        	exit();
    	}

    	$this->pagetitle = 'Edit User';

    	$sql = "SELECT * FROM `admin_source` ORDER BY `source_name`";
    	$sources = $this->db->select($sql);

    	$get_user_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

    	if (empty($get_user_id) || $get_user_id == 1) {
    		header('Location: /users');
        	exit();
    	} else {
	    	$sql = "SELECT * FROM `admin_users` WHERE `id` = {?}";
	    	$get_user_data = $this->db->selectRow($sql, [$get_user_id]);
	    	if (!$get_user_data) {
	    		header('Location: /users');
        		exit();
	    	} else {
	    		require_once(ROOT . '/admin/view/template/edit_user.php');
	    	}
	    }
    }

    // настройки
    public function settings()
    {
    	if (!$this->userInfo) {
    		header('Location: /login');
        	exit();
    	}

    	if ($this->userInfo['role_id'] != 2) {
    		header('Location: /sales');
        	exit();
    	}

    	$this->pagetitle = 'Settings';

    	require_once(ROOT . '/admin/view/template/settings.php');
    }

    // добавить источник
    public function addSource()
    {
    	if (!$this->userInfo) {
    		header('Location: /login');
        	exit();
    	}

    	if ($this->userInfo['role_id'] != 2) {
    		header('Location: /sales');
        	exit();
    	}

    	$this->pagetitle = 'Add Source';

    	require_once(ROOT . '/admin/view/template/add_source.php');
    }

    // редактировать источник
    public function editSource()
    {
    	if (!$this->userInfo) {
    		header('Location: /login');
        	exit();
    	}

    	if ($this->userInfo['role_id'] != 2) {
    		header('Location: /sales');
        	exit();
    	}

    	$this->pagetitle = 'Edit Source';

    	$get_source_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

    	if (empty($get_source_id)) {
    		header('Location: /settings');
        	exit();
    	} else {
	    	$sql = "SELECT * FROM `admin_source` WHERE `id` = {?}";
	    	$get_source_data = $this->db->selectRow($sql, [$get_source_id]);
	    	if (!$get_source_data) {
	    		header('Location: /settings');
        		exit();
	    	} else {
	    		require_once(ROOT . '/admin/view/template/edit_source.php');
	    	}
	    }
    }

    // добавить название игры
    public function addGamename()
    {
    	if (!$this->userInfo) {
    		header('Location: /login');
        	exit();
    	}

    	if ($this->userInfo['role_id'] != 2) {
    		header('Location: /sales');
        	exit();
    	}

    	$this->pagetitle = 'Add game name';

    	require_once(ROOT . '/admin/view/template/add_gamename.php');
    }

    // редактировать название игры
    public function editGamename()
    {
    	if (!$this->userInfo) {
    		header('Location: /login');
        	exit();
    	}

    	if ($this->userInfo['role_id'] != 2) {
    		header('Location: /sales');
        	exit();
    	}

    	$this->pagetitle = 'Edit game name';

    	$get_gamename_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

    	if (empty($get_gamename_id)) {
    		header('Location: /settings');
        	exit();
    	} else {
	    	$sql = "SELECT * FROM `admin_game_names` WHERE `id` = {?}";
	    	$get_gamename_data = $this->db->selectRow($sql, [$get_gamename_id]);
	    	if (!$get_gamename_data) {
	    		header('Location: /settings');
        		exit();
	    	} else {
	    		require_once(ROOT . '/admin/view/template/edit_gamename.php');
	    	}
	    }
    }

    // покупка игры/генерация кода на стороннем источнике по API
    public function apiGenerateCode()
    {
    	// данные, которые возвращаем
		$return = [];

		// получаем данные
		$json = file_get_contents('php://input');

		$data = json_decode($json, true);

		$create = isset($data['date']) ? strip_tags(trim($data['date'])) : date('Y-m-d H:i:s');
		// $gamename = isset($data['gamename']) ? strip_tags(trim($data['gamename'])) : 'GEM';
		$gamename = isset($data['gamename']) ? strip_tags(trim($data['gamename'])) : false;
		$price_usd = isset($data['price_usd']) ? (float) $data['price_usd'] : 0;
		$price_nok = isset($data['price_nok']) ? (float) $data['price_nok'] : 0;
		$source = isset($data['source']) ? strip_tags(trim($data['source'])) : false;
		$code = isset($data['code']) ? strip_tags(trim($data['code'])) : '';
		$game_number = isset($data['game_number']) ? strip_tags(trim($data['game_number'])) : '';
        $client_email = isset($data['email']) ? strip_tags(trim($data['email'])) : '';

		if (stripos($create, ' ') === false || stripos($create, '-') === false || stripos($create, ':') === false) {
			$return['error'] = 'Wrong date format. Need format Y-m-d H:i:s.';
		}

		if (!isset($return['error'])) {
			if (empty($price_usd) && empty($price_nok)) {
				$return['error'] = 'Wrong cost. Need "price_usd" or/and "price_nok".';
			}
		}

		if (!isset($return['error'])) {
			if (empty($source)) {
				$return['error'] = 'Wrong source. Value is empty.';
			} else {
				$sql = "SELECT `id` FROM `admin_source` WHERE `source_name` = {?} LIMIT 1";
				$source_id = $this->db->selectCell($sql, [$source]);
				if (empty($source_id)) {
					$return['error'] = 'Wrong source. Value not find in database.';
				}
			}
		}

		if (!isset($return['error'])) {
			if (empty($gamename)) {
				$return['error'] = 'Wrong gamename. Value is empty.';
			} else {
				$sql = "SELECT `id` FROM `admin_game_names` WHERE `game_name` = {?} LIMIT 1";
				$gamename_id = $this->db->selectCell($sql, [$gamename]);
				if (empty($gamename_id)) {
					$return['error'] = 'Wrong gamename. Value not find in database.';
				}
			}
		}

		if (!isset($return['error'])) {
			if (empty($code)) {
				$return['error'] = 'Wrong code. Value is empty.';
			} elseif (strlen($code) < 5 || strlen($code) > 30) {
				$return['error'] = 'Wrong code. Need length from 5 to 30 symbols.';
			} else {
				$sql = "SELECT id FROM admin_sales WHERE BINARY team_code = {?} LIMIT 1";
				$isset_code = $this->db->selectCell($sql, [$code]);
				if (!empty($isset_code)) {
					$return['error'] = 'Wrong code. Code already exists.';
				}
			}
		}

		if (!isset($return['error'])) {
			if (!empty($game_number)) {
				$sql = "SELECT `id` FROM `admin_sales` WHERE `source_code` = {?} LIMIT 1";
				$isset_game_number = $this->db->selectCell($sql, [$game_number]);

				if (!empty($isset_game_number)) {
					$return['error'] = 'Wrong game number. Game number already exists.';
				}
			}
		}

		if (!isset($return['error'])) {
			// если передана только одна валюта, то вторую валюту конвертируем согласно курса в админке
			if (empty($price_usd) && !empty($price_nok)) {
				$sql = "SELECT `value` FROM `admin_settings` WHERE `field_variable` = {?} LIMIT 1";
				$curse = $this->db->selectCell($sql, ['norway_crone']);
				if (!empty($curse)) {
					$price_usd = round($price_nok / $curse, 2);
				}
			} elseif (!empty($price_usd) && empty($price_nok)) {
				$sql = "SELECT `value` FROM `admin_settings` WHERE `field_variable` = {?} LIMIT 1";
				$curse = $this->db->selectCell($sql, ['norway_crone']);
				if (!empty($curse)) {
					$price_nok = round($price_usd * $curse, 2);
				}
			}

			$sql = "
				INSERT INTO `admin_sales`
				SET `datetime_sale` = {?},
					`gamename_id` = {?},
					`game_name` = {?},
					`price_dollar` = {?},
					`price_norway_crone` = {?},
					`source_id` = {?},
					`source_name` = {?},
					`team_code` = {?},
					`status` = {?},
					`source_code` = {?},
                    `client_email` = {?}
			";
			$sale_id = $this->db->query($sql, [$create, $gamename_id, $gamename, $price_usd, $price_nok, $source_id, $source, $code, 1, $game_number, $client_email]);
			if ($sale_id) {
				$sql = "
			        INSERT INTO `teams`
			        SET `team_name` = {?},
			            `code` = {?},
			            `create` = {?},
			            `score` = {?},
			            `progress_percent` = {?},
			            `dashboard` = {?},
			            `timer_second` = {?},
			            `active_hints` = {?},
			            `list_hints` = {?},
			            `list_hints_title_lang_var` = {?},
			            `list_hints_text_lang_var` = {?},
			            `active_files` = {?},
			            `list_files` = {?},
			            `active_databases` = {?},
			            `list_databases` = {?},
			            `last_action_id` = {?},
			            `calls_outgoing_id` = {?},
			            `active_calls` = {?},
			            `car_register_country_id` = {?},
			            `car_register_date` = {?},
			            `car_register_print_text_huilov` = {?},
			            `private_individuals_print_text_huilov` = {?},
			            `ceo_database_print_text_rod` = {?},
			            `mobile_calls_country_id` = {?},
			            `mobile_calls_number` = {?},
			            `mobile_calls_print_messages` = {?},
			            `mission_accept_datetime` = {?},
			            `last_databases` = {?},
			            `last_dashboard` = {?},
			            `last_window_open` = {?},
			            `last_type_tabs` = {?},
			            `last_calls` = {?},
			            `last_tools` = {?},
			            `open_chat` = {?},
			            `view_gem` = {?},
			            `view_call_jane_btn` = {?},
			            `view_call_mobile_btn` = {?},
			            `open_call_mobile_btn` = {?},
			            `active_tools` = {?},
			            `list_tools` = {?},
			            `tools_advanced_search_engine_access` = {?},
			            `tools_symbol_decoder_access` = {?},
			            `tools_3d_bulding_scan_access` = {?},
			            `african_partner_country_id` = {?},
			            `african_partner_date` = {?},
			            `metting_place_country_id` = {?},
			            `bank_transactions_date` = {?},
			            `databases_bank_transactions_access` = {?},
			            `chat_send_message_access` = {?},
			            `tools_building_scan_degree` = {?},
			            `tools_building_scan_input1` = {?},
			            `tools_building_scan_input2` = {?},
			            `tools_building_scan_input3` = {?},
			            `tools_building_scan_input4` = {?},
			            `tools_building_scan_input5` = {?},
			            `tools_building_scan_address_dot_n` = {?},
			            `tools_building_scan_address_dot_s` = {?},
			            `tools_building_scan_address_dot_e` = {?},
			            `tools_building_scan_address_dot_w` = {?},
			            `tools_building_scan_address_checkbox_n` = {?},
			            `tools_building_scan_address_checkbox_s` = {?},
			            `tools_building_scan_address_checkbox_e` = {?},
			            `tools_building_scan_address_checkbox_w` = {?},
			            `tools_secret_office_access` = {?},
			            `dashboard_minigame_access` = {?},
			            `dashboard_minigame_active_step` = {?},
			            `dashboard_interpol_access` = {?},
			            `mission_finish_seconds` = {?},
			            `mission_finish_datetime` = {?},
			            `hints_open` = {?},
			            `status` = {?}
			    ";
			    $team_id = $this->db->query($sql, ['', $code, $create, 0, 0, 'dashboard', 0, json_encode([], JSON_UNESCAPED_UNICODE), json_encode([1,2,3], JSON_UNESCAPED_UNICODE), 'text26', 'text27', json_encode([], JSON_UNESCAPED_UNICODE), json_encode([1], JSON_UNESCAPED_UNICODE), json_encode([], JSON_UNESCAPED_UNICODE), json_encode([], JSON_UNESCAPED_UNICODE), 2, 2, json_encode([['id'=>1,'datetime'=>'']], JSON_UNESCAPED_UNICODE), 0, NULL, 0, 0, 0, 0, NULL, 0, NULL, 'no_access', 'accept_new_mission', 'main', 'dashboard', 'no_access', 'no_access', 'no', 0, 0, 0, 0, json_encode([], JSON_UNESCAPED_UNICODE), json_encode([], JSON_UNESCAPED_UNICODE), 0, 0, 0, 0, NULL, 0, NULL, 0, 0, '0', '0', '0', '0', '0', '0', '2', '2', '2', '2', 0, 0, 0, 0, 0, 0, 1, 0, 0, NULL, 0, 1]);
			    if ($team_id) {
			    	$return['success'] = 'Successfully added.';
			    } else {
			    	$sql = "DELETE FROM admin_sales WHERE id = {?}";
			    	$this->db->query($sql, [$sale_id]);

			    	$return['error'] = 'Dont save. Unexpected error.';
			    }
			} else {
				$return['error'] = 'Dont save. Unexpected error.';
			}
		}

		echo json_encode($return, JSON_UNESCAPED_UNICODE);
    }

    // Админ панель управления стадиями игры
    public function manageStages()
    {
        // Проверка доступа (только для админов)
        if (!$this->userInfo || $this->userInfo['role_id'] != 2) {
            header('Location: /sales');
            exit();
        }

        $this->pagetitle = 'Manage Game Stages';

        // Получаем выбранную команду из GET
        $selected_team_id = isset($_GET['team_id']) ? (int) $_GET['team_id'] : 0;

        // Получаем список всех команд с пагинацией
        $query_count = "SELECT COUNT(*) FROM `teams`";
        $qt_teams = $this->db->selectCell($query_count);
        
        // Формируем URL для пагинации с учетом team_id
        $urlPag = '/manage-stages';
        $urlParams = [];
        if ($selected_team_id > 0) {
            $urlParams['team_id'] = $selected_team_id;
        }
        
        $urlPagFull = $urlPag;
        if (!empty($urlParams)) {
            $urlPagFull .= '?' . http_build_query($urlParams);
        }
        
        $this->pagination = new Pagination();
        $this->pagination->total = $qt_teams;
        $this->pagination->page = $this->page;
        $this->pagination->limit = $this->settings['limit'];
        $this->pagination->url = $urlPagFull . (stripos($urlPagFull, '?') !== false ? '&' : '?') . 'page={page}';
        
        $query = "SELECT `id`, `code`, `team_name`, `last_dashboard` FROM `teams` ORDER BY `id` DESC LIMIT " . $this->start . ", " . $this->settings['limit'];
        $teams = $this->db->select($query);

        // Получаем информацию о выбранной команде
        $selected_team = null;
        if ($selected_team_id > 0) {
            $query_team = "SELECT `id`, `code`, `team_name`, `last_dashboard` FROM `teams` WHERE `id` = {?} LIMIT 1";
            $selected_team = $this->db->selectRow($query_team, [$selected_team_id]);
        }

        // Определяем все доступные стадии
        $stages = [
            'accept_new_mission' => 'Accept New Mission',
            'company_name' => 'Company Name',
            'geo_coordinates' => 'Geo Coordinates',
            'african_partner' => 'African Partner',
            'metting_place' => 'Meeting Place',
            'room_name' => 'Room Name',
            'password' => 'Password'
        ];

        require_once(ROOT . '/admin/view/template/manage_stages.php');
    }

    public function manageLanguages()
    {
        if (!$this->userInfo || $this->userInfo['role_id'] != 2) {
            header('Location: /sales');
            exit();
        }

        $this->pagetitle = 'Manage Languages Dictionary';

        // Получаем все активные языки
        $query = "SELECT `id`, `lang`, `lang_name`, `lang_abbr` FROM `langs` WHERE `status` = 1 ORDER BY `id`";
        $languages = $this->db->select($query);

        // Получаем ID английского языка для получения аналогов
        $english_lang_id = $this->db->selectCell("SELECT `id` FROM `langs` WHERE `lang_abbr` = {?} AND `status` = {?} LIMIT 1", ['en', 1]);

        // Выбранный язык из GET параметра
        $selected_lang_id = isset($_GET['lang_id']) ? (int) $_GET['lang_id'] : 0;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $selected_lang = null;
        $words = [];
        $words_with_english = [];

        if ($selected_lang_id > 0) {
            // Получаем информацию о выбранном языке
            $query_lang = "SELECT `id`, `lang`, `lang_name`, `lang_abbr` FROM `langs` WHERE `id` = {?} LIMIT 1";
            $selected_lang = $this->db->selectRow($query_lang, [$selected_lang_id]);

            if ($selected_lang) {
                // Условие поиска: по field (коду) или по val (переводу)
                $search_where = '';
                $search_params = [];
                if ($search !== '') {
                    $search_like = '%' . $search . '%';
                    $lang_ids = [$selected_lang_id];
                    if ($english_lang_id && !in_array($english_lang_id, $lang_ids)) {
                        $lang_ids[] = $english_lang_id;
                    }
                    $placeholders = implode(',', array_fill(0, count($lang_ids), '{?}'));
                    $search_where = " WHERE (`field` LIKE {?} OR (`language_id` IN (" . $placeholders . ") AND `val` LIKE {?}))";
                    $search_params = array_merge([$search_like], $lang_ids, [$search_like]);
                }

                // Получаем количество уникальных field (с учётом поиска)
                $query_count = "SELECT COUNT(*) as total FROM (SELECT DISTINCT `field` FROM `lang_words_admin`" . $search_where . ") AS sub";
                $total_words = $this->db->selectCell($query_count, $search_params);

                // Настройка пагинации
                $urlPag = '/language';
                $urlParams = ['lang_id' => $selected_lang_id];
                if ($search !== '') {
                    $urlParams['search'] = $search;
                }
                $urlPagFull = $urlPag . '?' . http_build_query($urlParams);

                $this->pagination = new Pagination();
                $this->pagination->total = $total_words;
                $this->pagination->page = $this->page;
                $this->pagination->limit = $this->settings['limit'];
                $this->pagination->url = $urlPagFull . '&page={page}';

                // Получаем уникальные field с пагинацией (с учётом поиска)
                $query_fields = "SELECT DISTINCT `field` FROM `lang_words_admin`" . $search_where . " ORDER BY `field` LIMIT " . $this->start . ", " . $this->settings['limit'];
                $fields = $this->db->select($query_fields, $search_params);

                // Для каждого field получаем переводы
                foreach ($fields as $field_row) {
                    $field = $field_row['field'];
                    
                    // Получаем слово для выбранного языка
                    $query_word = "SELECT `id`, `val`, `page` FROM `lang_words_admin` WHERE `field` = {?} AND `language_id` = {?} LIMIT 1";
                    $word = $this->db->selectRow($query_word, [$field, $selected_lang_id]);
                    
                    // Получаем английский аналог
                    $english_word = null;
                    if ($english_lang_id) {
                        if ($selected_lang_id == $english_lang_id) {
                            // Если выбран английский язык, то val и english_val одинаковые
                            $english_word = $word ? $word['val'] : '';
                        } else {
                            // Если выбран другой язык, получаем английский аналог
                            $query_english = "SELECT `val` FROM `lang_words_admin` WHERE `field` = {?} AND `language_id` = {?} LIMIT 1";
                            $english_word = $this->db->selectCell($query_english, [$field, $english_lang_id]);
                        }
                    }
                    
                    $words_with_english[] = [
                        'field' => $field,
                        'id' => $word ? $word['id'] : null,
                        'val' => $word ? $word['val'] : '',
                        'page' => $word ? $word['page'] : '',
                        'english_val' => $english_word ? $english_word : ''
                    ];
                }
            }
        }

        require_once(ROOT . '/admin/view/template/manage_languages.php');
    }

}
