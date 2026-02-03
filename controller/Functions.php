<?php
require_once __DIR__ . '/../views/template/svg.php';

// Подключаем все trait'ы
require_once __DIR__ . '/functions_template/Utils.php';
require_once __DIR__ . '/functions_template/Team.php';
require_once __DIR__ . '/functions_template/Hints.php';
require_once __DIR__ . '/functions_template/Chat.php';
require_once __DIR__ . '/functions_template/Dashboard.php';
require_once __DIR__ . '/functions_template/Calls.php';
require_once __DIR__ . '/functions_template/Databases.php';
require_once __DIR__ . '/functions_template/Tools.php';
require_once __DIR__ . '/functions_template/Files.php';

class Functions
{
    use Utils, Team, Hints, Chat, Dashboard, Calls, Databases, Tools, Files;
    
    public $svg;
    public $db;
    private static $functions = null;
    
    function __construct()
    {
        global $svg;
        $this->svg = $svg;
        $this->db = DataBase::getDB();
    }

    // получаем единственный экземпляр класса
    public static function getFunctions()
    {
        if (self::$functions == null) {
            self::$functions = new Functions();
        }
        return self::$functions;
    }
}
