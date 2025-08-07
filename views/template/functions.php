<?php
require_once 'core/Language.php';

function generate_button($translation_key, $button_class = '') {
    $language = Language::getInstance();
    $translation = $language->getAllTranslations();
   
    $button_text = isset($translation[$translation_key]) ? $translation[$translation_key] : '';
    
    $html = '<div class="btn_wrapper btn_wrapper_blue ' . htmlspecialchars($button_class) . '">
        <div class="btn btn_blue">
            <span>' . htmlspecialchars($button_text) . '</span>
        </div>
        <div class="btn_border_top"></div>
        <div class="btn_border_bottom"></div>
        <div class="btn_border_left"></div>
        <div class="btn_border_left_arcle"></div>
        <div class="btn_border_right"></div>
        <div class="btn_border_right_arcle"></div>
        <div class="btn_bg_top_line"></div>
        <div class="btn_bg_bottom_line"></div>
        <div class="btn_bg_triangle_left"></div>
        <div class="btn_bg_triangle_right"></div>
        <div class="btn_circles_top">
            <div class="btn_circle"></div>
            <div class="btn_circle"></div>
            <div class="btn_circle"></div>
            <div class="btn_circle"></div>
        </div>
        <div class="btn_circles_bottom">
            <div class="btn_circle"></div>
            <div class="btn_circle"></div>
            <div class="btn_circle"></div>
            <div class="btn_circle"></div>
        </div>
    </div>';
    
    return $html;
} 