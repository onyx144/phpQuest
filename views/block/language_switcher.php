<?php
if (!defined('GD_ACCESS')) {
    die('Прямой доступ запрещен');
}
?>
<div class="language">
    <div class="language_bg">
        <svg width="152" height="38" viewBox="0 0 152 38" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M23 37.5H137L151 26V-11H125.5H1V10L23 37.5Z" fill="white" fill-opacity="0.16" stroke="#00F0FF"/>
        </svg>
    </div>
    <div class="language_text">
        <div class="language_flag">
            <img src="<?= BASE_URI ?>/images/<?php echo $language->getLanguageFlag($language->getCurrentLang()); ?>" alt="">
        </div>
        <div class="language_name">
            <?php if (isset($translation['text18'])) { echo $translation['text18']; } ?>
        </div>
    </div>
</div>
<div class="language_hidden">
    <?php
    if ($language->getCurrentLang() == 'en') {
        echo '<div class="language_hidden_item language_hidden_item_active">
                <img src="<?= BASE_URI ?>/images/gb.jpg" alt=""> English
              </div>
              ';
    } /*
    <a href="?lang=no" class="language_hidden_item">
                <img src="<?= BASE_URI ?>/images/no.png" alt=""> Norwegian
              </a>
              else {
        echo '<a href="?lang=en" class="language_hidden_item">
                <img src="<?= BASE_URI ?>/images/gb.jpg" alt=""> Engelsk
              </a>
              <div class="language_hidden_item language_hidden_item_active">
                <img src="<?= BASE_URI ?>/images/no.png" alt=""> Norsk
              </div>';
    }*/
    ?>
</div>


