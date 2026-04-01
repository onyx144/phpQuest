<?php

class I18n
{
    private static $instance = null;
    private $translations = [];
    private $currentLang = 'ua'; // Ukrainian by default
    private $availableLangs = ['ua', 'uk', 'en'];

    private function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // GET → cookie lang (см. view/js/app_lang.js + setAppLang) → session
        if (isset($_GET['lang']) && in_array($_GET['lang'], $this->availableLangs)) {
            $this->currentLang = $this->normalizeLang($_GET['lang']);
            $_SESSION['lang'] = $this->currentLang;
            setcookie('lang', $this->currentLang, time() + (86400 * 30), '/'); // 30 days
        } elseif (isset($_COOKIE['lang']) && in_array($_COOKIE['lang'], $this->availableLangs)) {
            $this->currentLang = $this->normalizeLang($_COOKIE['lang']);
            $_SESSION['lang'] = $this->currentLang;
        } elseif (isset($_SESSION['lang']) && in_array($_SESSION['lang'], $this->availableLangs)) {
            $this->currentLang = $this->normalizeLang($_SESSION['lang']);
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
            $langFile = ROOT . '/languages/ua.php';
            if (file_exists($langFile)) {
                $this->translations = require $langFile;
            } else {
                $langFile = ROOT . '/languages/uk.php';
                if (file_exists($langFile)) {
                    $this->translations = require $langFile;
                }
            }
        }
    }

    public function setLanguage($lang)
    {
        if (in_array($lang, $this->availableLangs)) {
            $normalizedLang = $this->normalizeLang($lang);
            $this->currentLang = $normalizedLang;
            $_SESSION['lang'] = $normalizedLang;
            setcookie('lang', $normalizedLang, time() + (86400 * 30), '/');
            $this->loadLanguage();
            return true;
        }
        return false;
    }

    private function normalizeLang($lang)
    {
        return $lang === 'uk' ? 'ua' : $lang;
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

