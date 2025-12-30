<?php

class I18n
{
    private static $instance = null;
    private $translations = [];
    private $currentLang = 'uk'; // Ukrainian by default
    private $availableLangs = ['uk', 'en'];

    private function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Get language from session, cookie, or default to Ukrainian
        if (isset($_GET['lang']) && in_array($_GET['lang'], $this->availableLangs)) {
            $this->currentLang = $_GET['lang'];
            $_SESSION['lang'] = $this->currentLang;
            setcookie('lang', $this->currentLang, time() + (86400 * 30), '/'); // 30 days
        } elseif (isset($_SESSION['lang']) && in_array($_SESSION['lang'], $this->availableLangs)) {
            $this->currentLang = $_SESSION['lang'];
        } elseif (isset($_COOKIE['lang']) && in_array($_COOKIE['lang'], $this->availableLangs)) {
            $this->currentLang = $_COOKIE['lang'];
            $_SESSION['lang'] = $this->currentLang;
        }

        $this->loadLanguage();
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function loadLanguage()
    {
        $langFile = ROOT . '/languages/' . $this->currentLang . '.php';
        if (file_exists($langFile)) {
            $this->translations = require $langFile;
        } else {
            // Fallback to Ukrainian if file doesn't exist
            $langFile = ROOT . '/languages/uk.php';
            if (file_exists($langFile)) {
                $this->translations = require $langFile;
            }
        }
    }

    public function setLanguage($lang)
    {
        if (in_array($lang, $this->availableLangs)) {
            $this->currentLang = $lang;
            $_SESSION['lang'] = $lang;
            setcookie('lang', $lang, time() + (86400 * 30), '/');
            $this->loadLanguage();
            return true;
        }
        return false;
    }

    public function t($key, $default = null)
    {
        return isset($this->translations[$key]) ? $this->translations[$key] : ($default !== null ? $default : $key);
    }

    public function getCurrentLang()
    {
        return $this->currentLang;
    }

    public function getAvailableLangs()
    {
        return $this->availableLangs;
    }

    public function getAllTranslations()
    {
        return $this->translations;
    }
}

