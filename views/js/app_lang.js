/**
 * Язык приложения: localStorage + cookie site_lang (PHP Router).
 * Дубликат view/js/app_lang.js для layouts в /views/.
 */
(function () {
    var SITE_KEY = 'site_lang';
    var COOKIE_MAX_AGE = 86400 * 365;

    function setCookie(name, value) {
        document.cookie = name + '=' + encodeURIComponent(value)
            + ';path=/;max-age=' + COOKIE_MAX_AGE
            + ';SameSite=Lax';
    }

    function getCookie(name) {
        var m = document.cookie.match(new RegExp('(?:^|;\\s*)' + name + '=([^;]*)'));
        return m ? decodeURIComponent(m[1]) : '';
    }

    function i18nCodeFromSiteLang(siteLang) {
        return siteLang === 'en' ? 'en' : 'ua';
    }

    window.APP_LANG_STORAGE_KEY = SITE_KEY;

    window.setAppLang = function (code) {
        if (!code) {
            return;
        }
        code = String(code).toLowerCase().replace(/а/g, 'a');
        if (code === 'uk') {
            code = 'ua';
        }
        try {
            localStorage.setItem(SITE_KEY, code);
        } catch (e) {}
        setCookie(SITE_KEY, code);
        setCookie('lang', i18nCodeFromSiteLang(code));
        location.reload();
    };

    function syncStorageToCookies() {
        try {
            var ls = localStorage.getItem(SITE_KEY);
            if (!ls) {
                return;
            }
            ls = String(ls).toLowerCase().replace(/а/g, 'a');
            if (ls === 'uk') {
                ls = 'ua';
            }
            var cookiesSite = getCookie(SITE_KEY);
            var i18nNeeded = i18nCodeFromSiteLang(ls);
            var i18nCookie = getCookie('lang');
            if (cookiesSite !== ls || i18nCookie !== i18nNeeded) {
                setCookie(SITE_KEY, ls);
                setCookie('lang', i18nNeeded);
                if (sessionStorage.getItem('_app_lang_sync') !== '1') {
                    sessionStorage.setItem('_app_lang_sync', '1');
                    location.reload();
                    return;
                }
            } else {
                sessionStorage.removeItem('_app_lang_sync');
            }
        } catch (e) {}
    }

    syncStorageToCookies();

    document.addEventListener('click', function (e) {
        var t = e.target.closest && e.target.closest('.js-set-app-lang');
        if (!t) {
            return;
        }
        e.preventDefault();
        var code = t.getAttribute('data-lang');
        if (code && typeof window.setAppLang === 'function') {
            window.setAppLang(String(code));
        }
    });
})();
