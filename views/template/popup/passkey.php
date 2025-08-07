<?php
require_once 'views/template/functions.php';
?>

<div id="popup_passkey">
            <div class="popup_passkey_bg"></div>
            <div class="popup_passkey_inner_main">
                <div class="popup_passkey_inner">
                    <div class="popup_passkey_inner2">
                        <div class="popup_search_processing_skew_line_top"></div>
                        <div class="popup_search_processing_skew_line_bottom"></div>
                        <div class="popup_passkey_input_wrapper">
                        <div class="user-auth-icon">
                                <img src="<?= BASE_URI ?>/images/user-authentication.png" alt="user-auth-icon">
                            </div>
                        <div class="icon_block">
                        <div class="popup_passkey_input_border_left"></div>
                            <div class="popup_passkey_input_border_right"></div>
</div>
                            <input type="text" class="popup_passkey_input" placeholder="VERIFY WITH PASSKEY">
                            <div class="error_text_database_car_register">Required field</div>
                            <div class="face_id_button">
                            <?php echo generate_button('text310', 'popup_passkey_button'); ?>

                            </div>
                            <div class="popup_passkey_input_line_left_outer">
                            <svg width="317" height="88" viewBox="0 0 317 88" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M9 67.3349L1.90735e-05 87.2471L1.72529e-05 0.000442398L9 21.335L9 67.3349Z" fill="white"/>
<path d="M6.5 43.001H123L149.5 69.501H317" stroke="white"/>
</svg>
                            </div>
                            <div class="popup_passkey_input_line_right_outer">
                                <svg width="303" height="75" viewBox="0 0 303 75" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M294 19.9122L303 3.9341e-07L303 74.3345L294 53L294 19.9122Z" fill="white"/><path d="M0.5 71.7158H99.5L131.5 38.7158H299" stroke="white"/></svg>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>