<?php
require_once 'core/Language.php';

function showLoadingPopup($id, $imagePath = '/images/gifs/gears.gif', $translatedText = '') {
    $language = Language::getInstance();
    $translation = $language->getAllTranslations();
   
    $button_text = isset($translation[$translatedText]) ? $translation[$translatedText] : '';

    ?>
    <div id="<?php echo htmlspecialchars($id); ?>" class="popup">
        <div class="popup_load_processing_bg">
        </div>
        <div class="popup_load_processing_inner">
            <div class="popup_load_processing_inner2">
                <div class="popup_load_processing_skew_line_top"></div>
                <div class="popup_load_processing_skew_line_bottom"></div>
                <div class="popup_load_processing_img_wrapper">
                    <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="">
                </div>
                <div class="popup_load_processing_input_wrapper">
                    <div class="popup_load_processing_input_border_left"></div>
                    <div class="popup_load_processing_input_border_right"></div>
                    <div class="popup_load_processing_input">
                        <div class="popup_load_processing_input_upload_percent"></div>
                        <div class="popup_load_processing_input_upload_text"><span>0</span>%</div>
                    </div>
                    <div class="popup_load_processing_input_border_bottom">
                        <span><?php echo htmlspecialchars($button_text); ?></span>
                        <svg width="388" height="20" viewBox="0 0 388 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.5 1L6.5 7.5H109L120.5 19.5H262.5L276 7.5H381L387.5 1" stroke="white"/></svg>
                    </div>
                    <div class="popup_load_processing_input_line_left_outer">
                        <svg width="285" height="39" viewBox="0 0 285 39" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M285 0.5H103.5L66 38H0" stroke="white"/></svg>
                    </div>
                    <div class="popup_load_processing_input_line_right_outer">
                        <svg width="283" height="35" viewBox="0 0 283 35" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.5 34H140.5L172.5 1H282.5" stroke="white"/></svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?> 