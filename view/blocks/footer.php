        </div><!-- end main -->

    <?php if ($this->userInfo) { ?>
        <!-- music -->
        <div id="jquery_jplayer_1"></div>
        <div id="jp_container_1" style="display: none;">
            <a href="#" class="jp-play">Play</a>
            <a href="#" class="jp-pause">Pause</a>
        </div>

        <!-- video -->
        <div id="popup_video">
            <div class="popup_video_bg"></div>
            <div class="popup_video_inner">
                <div class="popup_video_inner_title"></div>
                <div class="popup_video_container">
                    <div class="popup_video_close">
                        <img src="/images/popup_close.png" alt="">
                    </div>
                    <div class="popup_video_dots">
                        <div class="popup_video_dot"></div>
                        <div class="popup_video_dot"></div>
                        <div class="popup_video_dot"></div>
                        <div class="popup_video_dot"></div>
                        <div class="popup_video_dot"></div>
                        <div class="popup_video_dot"></div>
                        <div class="popup_video_dot"></div>
                        <div class="popup_video_dot"></div>
                    </div>
                    <div class="popup_video_container_inner" data-file-id="1" data-type="file">
                        <video id="popup_video_mp4" width="100%" height="100%" controls="controls"><source src="/video/one_second.mp4" type="video/mp4" />Your browser does not support the video tag.</video>
                    </div>
                    <div class="popup_video_btns">
                        <div class="popup_video_play">
                            <img src="/images/play_icon_white.png" alt="">
                        </div>
                        <div class="popup_video_stop">
                            <img src="/images/pause_icon_white.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- popup phone incoming -->
        <div id="popup_video_phone">
            <div class="popup_video_phone_bg"></div>
            <div class="popup_video_phone_inner">
                <div class="popup_video_phone_top">
                    <div class="popup_video_phone_time">
                        <div class="popup_video_phone_time_hours">00</div>
                        <div class="popup_video_phone_time_dots">:</div>
                        <div class="popup_video_phone_time_minutes">00</div>
                    </div>
                    <div class="popup_video_phone_wifi_icons">
                        <img src="/images/wifi_icons.png" alt="">
                    </div>
                </div>
                <div class="popup_video_phone_title"><?php if (isset($translation['text42'])) { echo $translation['text42']; } ?></div>
                <div class="popup_video_phone_photo">
                    <img src="/images/incoming_jane_blond.png" alt="">
                </div>
                <div class="popup_video_phone_name"></div>
                <div class="popup_video_phone_btns">
                    <div class="popup_video_phone_btn_decline_wrapper">
                        <div class="popup_video_phone_btn">
                            <img src="/images/phone_icon.png" alt="">
                            <div class="popup_video_phone_btn_text"><?php if (isset($translation['text43'])) { echo $translation['text43']; } ?></div>
                        </div>
                    </div>
                    <div class="popup_video_phone_btn_answer_wrapper">
                        <div class="popup_video_phone_btn">
                            <img src="/images/phone_icon.png" alt="">
                            <div class="popup_video_phone_btn_text"><?php if (isset($translation['text30'])) { echo $translation['text30']; } ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- popup phone outgoing. Call Jane -->
        <div id="popup_video_phone_outgoing">
            <div class="popup_video_phone_outgoing_bg"></div>
            <div class="popup_video_phone_outgoing_inner">
                <div class="popup_video_phone_outgoing_not_available_text"><?php if (isset($translation['text298'])) { echo $translation['text298']; } ?></div>
                <img src="/images/popup_video_phone_outgoing_jane_img.png" class="popup_video_phone_outgoing_jane_img" alt="">
                <div class="popup_video_phone_outgoing_top">
                    <div class="popup_video_phone_outgoing_time">
                        <div class="popup_video_phone_outgoing_time_hours">00</div>
                        <div class="popup_video_phone_outgoing_time_dots">:</div>
                        <div class="popup_video_phone_outgoing_time_minutes">00</div>
                    </div>
                    <div class="popup_video_phone_outgoing_wifi_icons">
                        <img src="/images/wifi_icons.png" alt="">
                    </div>
                </div>
                <div class="popup_video_phone_outgoing_name">Jane Blond</div>
                <div class="popup_video_phone_outgoing_btns">
                    <div class="popup_video_phone_outgoing_btn_decline_wrapper">
                        <div class="popup_video_phone_outgoing_btn">
                            <img src="/images/phone_icon.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- popup phone video -->
        <div id="popup_video_phone_video">
            <div class="popup_video_phone_video_bg"></div>
            <div class="popup_video_phone_video_inner">
                <div class="popup_video_close">
                    <img src="/images/popup_close.png" alt="">
                </div>
                <video id="popup_video_mp4_call" controls="controls"><source src="/video/one_second.mp4" type="video/mp4" />Your browser does not support the video tag.</video>
            </div>
        </div>

        <!-- popup search processing -->
        <div id="popup_search_processing">
            <div class="popup_search_processing_bg">
                <img src="/images/gifs/search_popup_bg-min.gif" class="popup_search_processing_bg_gif" alt="">
            </div>
            <div class="popup_search_processing_inner">
                <div class="popup_search_processing_inner2">
                    <div class="popup_search_processing_skew_line_top"></div>
                    <div class="popup_search_processing_skew_line_bottom"></div>
                    <div class="popup_search_processing_img_wrapper">
                        <img src="/images/gifs/gears.gif" alt="">
                    </div>
                    <div class="popup_search_processing_input_wrapper">
                        <div class="popup_search_processing_input_border_left"></div>
                        <div class="popup_search_processing_input_border_right"></div>
                        <div class="popup_search_processing_input">
                            <div class="popup_search_processing_input_upload_percent"></div>
                            <div class="popup_search_processing_input_upload_text"><span>0</span>%</div>
                        </div>
                        <div class="popup_search_processing_input_border_bottom">
                            <span><?php if (isset($translation['text87'])) { echo $translation['text87']; } ?></span>
                            <svg width="388" height="20" viewBox="0 0 388 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.5 1L6.5 7.5H109L120.5 19.5H262.5L276 7.5H381L387.5 1" stroke="white"/></svg>
                        </div>
                        <div class="popup_search_processing_input_line_left_outer">
                            <svg width="285" height="39" viewBox="0 0 285 39" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M285 0.5H103.5L66 38H0" stroke="white"/></svg>
                        </div>
                        <div class="popup_search_processing_input_line_right_outer">
                            <svg width="283" height="35" viewBox="0 0 283 35" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.5 34H140.5L172.5 1H282.5" stroke="white"/></svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- popup search error -->
        <div id="popup_search_error">
            <div class="popup_search_error_bg"></div>
            <div class="popup_search_error_inner_main">
                <div class="popup_search_error_inner">
                    <div class="popup_search_error_inner2">
                        <div class="popup_search_processing_skew_line_top"></div>
                        <div class="popup_search_processing_skew_line_bottom"></div>
                        <div class="popup_search_error_input_wrapper">
                            <div class="popup_search_error_input_border_left"></div>
                            <div class="popup_search_error_input_border_right"></div>
                            <div class="popup_search_error_input"><?php if (isset($translation['text89'])) { echo $translation['text89']; } ?></div>
                            <div class="popup_search_error_input_line_left_outer">
                                <svg width="303" height="88" viewBox="0 0 303 88" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 67.3344L1.90735e-05 87.2466L1.72529e-05 -4.58837e-05L9 21.3345L9 67.3344Z" fill="#FF004E"/><path d="M303 6.21582H161.5L124 43.7158H0.5" stroke="#FF004E"/></svg>
                            </div>
                            <div class="popup_search_error_input_line_right_outer">
                                <svg width="303" height="75" viewBox="0 0 303 75" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M294 19.9122L303 3.9341e-07L303 74.3345L294 53L294 19.9122Z" fill="#FF004E"/><path d="M0.5 71.7158H99.5L131.5 38.7158H299" stroke="#FF004E"/></svg>
                            </div>
                        </div>
                        <div class="popup_search_error_text"><?php if (isset($translation['text88'])) { echo $translation['text88']; } ?></div>
                    </div>
                </div>
                <div class="btn_wrapper btn_wrapper_blue popup_search_error_close">
                    <div class="btn btn_blue">
                        <span>OK</span>
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
                </div>
            </div>
        </div>

        <!-- popup Call mobile -->
        <div id="popup_call_mobile">
            <div class="popup_call_mobile_bg"></div>
            <div class="popup_call_mobile_inner_main">
                <div class="popup_call_mobile_inner">
                    <div class="popup_call_mobile_inner2">
                        <div class="popup_search_processing_skew_line_top"></div>
                        <div class="popup_search_processing_skew_line_bottom"></div>
                        <div class="popup_call_mobile_input_wrapper">
                            <div class="popup_call_mobile_input_border_left"></div>
                            <div class="popup_call_mobile_input_border_right"></div>
                            <div class="popup_call_mobile_input"><?php if (isset($translation['text309'])) { echo $translation['text309']; } ?></div>
                            <div class="popup_call_mobile_input_line_left_outer">
                                <svg width="303" height="88" viewBox="0 0 303 88" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 67.3344L1.90735e-05 87.2466L1.72529e-05 -4.58837e-05L9 21.3345L9 67.3344Z" fill="white"/><path d="M303 6.21582H161.5L124 43.7158H0.5" stroke="white"/></svg>
                            </div>
                            <div class="popup_call_mobile_input_line_right_outer">
                                <svg width="303" height="75" viewBox="0 0 303 75" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M294 19.9122L303 3.9341e-07L303 74.3345L294 53L294 19.9122Z" fill="white"/><path d="M0.5 71.7158H99.5L131.5 38.7158H299" stroke="white"/></svg>
                            </div>
                        </div>
                        <div class="popup_call_mobile_text"><?php if (isset($translation['text310'])) { echo $translation['text310']; } ?></div>
                    </div>
                </div>
                <div class="btn_wrapper btn_wrapper_blue popup_call_mobile_close">
                    <div class="btn btn_blue">
                        <span>OK</span>
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
                </div>
            </div>
        </div>

        <!-- popup for ajax -->
        <div id="popup_ajax"></div>

        <!-- popup data transfer -->
        <!-- <div id="popup_data_transfer" style="display: block;"> -->
        <div id="popup_data_transfer">
            <div class="popup_data_transfer_bg"></div>
            <div class="popup_data_transfer_inner">
                <div class="popup_data_transfer_title"><?php if (isset($translation['text173'])) { echo $translation['text173']; } ?></div>
                <div class="popup_data_transfer_percent"><span>0</span>%</div>
                <div class="popup_data_transfer_processed"><?php if (isset($translation['text174'])) { echo $translation['text174']; } ?> _</div>
                <div class="popup_data_transfer_numbers"><span class="popup_data_transfer_numbers_one">2934</span>,<span class="popup_data_transfer_numbers_two">492</span></div>
                <div class="popup_data_transfer_progress"><div class="popup_data_transfer_progress_inner"></div></div>
                <!-- <img src="/images/gifs/data_transfer.gif" alt=""> -->
                <!-- <img src="/images/gifs/data_transfer.gif" alt="" style="margin-top: 200px;"> -->
            </div>
        </div>

        <!-- popup success -->
        <div id="popup_success">
            <div class="popup_success_bg"></div>
            <div class="popup_success_inner_main">
                <div class="popup_success_inner">
                    <div class="popup_success_inner2">
                        <div class="popup_success_input_wrapper">
                            <div class="popup_success_input_border_left"></div>
                            <div class="popup_success_input_border_right"></div>
                            <div class="popup_success_confirmed_star"><img src="/images/icons/icon_confirmed_star.png" alt=""></div>
                            <div class="popup_success_input"></div>
                            <div class="popup_success_input_line_left_outer">
                                <svg width="260" height="88" viewBox="0 0 260 88" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 67.3344L1.90735e-05 87.2466L1.72529e-05 -4.58837e-05L9 21.3345L9 67.3344Z" fill="white"/><path d="M6.5 43H123L149.5 69.5H260" stroke="white"/></svg>
                            </div>
                            <div class="popup_success_input_line_right_outer">
                                <svg width="262" height="75" viewBox="0 0 262 75" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M253.5 35H137L110.5 61.5H0" stroke="white"/><path d="M253 19.9122L262 3.9341e-07L262 74.3345L253 53L253 19.9122Z" fill="white"/></svg>
                            </div>
                        </div>
                        <div class="popup_success_text"></div>
                    </div>
                </div>
                <div class="btn_wrapper btn_wrapper_blue popup_success_close">
                    <div class="btn btn_blue">
                        <span>OK</span>
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
                </div>
            </div>
        </div>

        <!-- popup success pollution -->
        <div id="popup_success_pollution">
            <div class="popup_success_pollution_bg"></div>
            <div class="popup_success_pollution_inner_main">
                <div class="popup_success_pollution_inner">
                    <div class="popup_success_pollution_inner2">
                        <div class="popup_success_pollution_top"><?php if (isset($translation['text193'])) { echo $translation['text193']; } ?></div>
                        <div class="popup_success_pollution_middle">
                            <div class="popup_success_pollution_middle_pollution">
                                <div class="popup_success_pollution_middle_pollution_bottom">
                                    <img src="/images/gifs/pollution1.gif" alt="">
                                </div>
                                <div class="popup_success_pollution_middle_pollution_top"><?php if (isset($translation['text194'])) { echo $translation['text194']; } ?></div>
                                <div class="popup_success_pollution_middle_pollution_middle"><img src="/images/pollution_molots.jpg" alt=""> <?php if (isset($translation['text195'])) { echo $translation['text195']; } ?></div>
                            </div>
                            <div class="popup_success_pollution_middle_diagram">
                                <svg width="180" height="170" viewBox="0 0 180 170" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_803_6702)"><path d="M150.821 67.1995C148.362 65.8115 145.821 64.6683 143.198 63.6885C143.608 60.8307 143.936 57.8095 143.936 54.7884C143.936 27.7615 124.182 5.38879 98.2799 1.22453C97.2963 1.06122 96.3946 1.87774 96.3946 2.85757C96.3946 3.67409 96.9684 4.32731 97.7061 4.49062C114.591 8.24662 127.214 23.2706 127.214 41.2341C127.214 62.7903 108.936 80.1823 86.9684 78.7125C68.4438 77.4877 53.2799 62.627 51.8864 44.1736C50.3291 25.3119 62.87 9.14479 80.0832 4.73557C80.8209 4.57227 81.3946 3.91905 81.3946 3.10253C81.3946 2.04105 80.493 1.22453 79.4274 1.46948C54.0996 6.12366 35.0012 28.2514 35.0012 54.7884C35.0012 57.5646 35.2471 60.3408 35.6569 62.9536C32.7061 64.1784 29.1815 65.6482 26.3946 67.4445C3.44382 81.8969 -4.83487 110.557 5.493 134.563C5.90283 135.461 7.05037 135.787 7.87005 135.298C8.52578 134.889 8.77168 134.073 8.52578 133.338C2.62414 117.089 8.52578 98.4724 23.7717 88.8374C41.3127 77.7327 64.5914 82.8768 75.8209 100.35C75.0832 98.6357 74.7553 96.7577 74.7553 94.798C74.7553 86.5511 81.4766 79.8557 89.7553 79.8557C98.034 79.8557 104.755 86.5511 104.755 94.798C104.755 103.045 98.034 109.74 89.7553 109.74C84.8372 109.74 80.575 107.372 77.7881 103.78C85.8209 119.865 81.2307 139.788 66.7225 150.648C51.5586 161.998 31.1487 160.12 18.1979 148.035C17.6241 147.545 16.8045 147.382 16.1487 147.79C15.2471 148.362 15.0832 149.587 15.8209 150.322C33.3618 169.102 62.3782 173.347 84.8372 159.058C86.3127 158.079 87.7881 157.099 89.1815 156.037C91.6405 158.079 94.6733 160.283 97.5422 161.916C121.149 175.144 150.411 168.938 166.804 148.444C167.46 147.627 167.214 146.484 166.313 145.994C165.657 145.586 164.755 145.749 164.263 146.321C152.706 159.14 133.362 162.814 117.706 153.996C98.7717 143.463 92.5422 119.049 104.591 100.677C114.755 85.163 135.165 79.2841 152.05 87.1227C169.263 95.043 177.296 113.823 172.706 130.97C172.542 131.705 172.788 132.521 173.526 132.848C174.427 133.338 175.575 132.93 175.903 131.95C184.018 107.617 174.018 80.1823 150.821 67.1995Z" fill="white"/><path d="M124.262 170C114.754 170 105.409 167.55 96.9669 162.896C94.016 161.263 91.2291 159.222 89.18 157.507C88.0324 158.405 86.8029 159.222 85.4914 160.038C62.8685 174.409 33.1964 170.653 14.9177 151.138C14.3439 150.485 14.098 149.668 14.18 148.852C14.2619 147.954 14.7537 147.219 15.4914 146.729C16.639 145.994 18.1144 146.157 19.016 147.055C31.9669 159.222 51.721 160.283 65.9832 149.587C80.4095 138.809 84.7537 118.885 76.0652 103.126C71.1472 94.1447 62.9505 87.8575 53.1144 85.3263C43.1964 82.7951 33.0324 84.4281 24.4259 89.8172C10.0816 98.8806 3.8521 117.007 9.67177 132.93C10.1636 134.236 9.67177 135.624 8.52423 136.359C7.78653 136.767 6.96686 136.931 6.14718 136.686C5.32751 136.441 4.67177 135.869 4.42587 135.134C-0.738063 123.131 -1.47577 109.659 2.37669 97.1659C6.31112 84.4281 14.5898 73.4867 25.8193 66.383C28.3603 64.7499 31.3931 63.4435 34.3439 62.2187C34.016 59.7691 33.8521 57.3196 33.8521 54.7884C33.8521 28.0881 52.9505 5.14379 79.2619 0.244651C80.0816 0.0813466 80.9832 0.326303 81.639 0.897869C82.2947 1.46943 82.7046 2.28596 82.7046 3.10248C82.7046 4.40892 81.8029 5.55205 80.4914 5.87866C63.2783 10.2879 51.8029 26.2917 53.1144 44.0103C54.5078 61.8921 69.098 76.2629 87.0488 77.406C97.2947 78.0593 106.967 74.6299 114.426 67.6894C121.885 60.749 125.983 51.2773 125.983 41.1524C125.983 24.2504 113.934 9.30805 97.3767 5.6337C96.0652 5.30709 95.0816 4.16396 95.0816 2.85752C95.0816 2.041 95.4914 1.22448 96.0652 0.652912C96.7209 0.0813466 97.5406 -0.081958 98.3603 -0.00030571C111.229 2.041 123.114 8.73648 131.639 18.6164C140.328 28.6596 145.082 41.479 145.082 54.7067C145.082 57.4012 144.918 60.0958 144.508 62.7903C146.885 63.6885 149.18 64.8316 151.393 66.0564C174.754 79.1207 185.491 106.964 176.885 132.195C176.639 133.011 175.983 133.664 175.164 133.909C174.344 134.236 173.442 134.154 172.705 133.746C171.557 133.093 170.983 131.786 171.311 130.48C175.901 113.415 167.541 95.5328 151.311 88.0208C135 80.4272 115.246 86.1428 105.409 101.167C99.8357 109.74 98.1144 119.865 100.491 129.745C102.95 139.625 109.18 147.79 118.032 152.771C132.868 161.018 151.803 157.915 163.114 145.341C164.016 144.279 165.491 144.034 166.639 144.769C167.377 145.177 167.868 145.912 168.032 146.729C168.196 147.545 167.95 148.362 167.459 149.015C159.344 159.222 147.705 166.244 134.836 168.775C131.557 169.673 127.95 170 124.262 170ZM89.2619 154.404L89.9996 155.057C91.9669 156.69 95.0816 159.058 98.1964 160.773C121.065 173.592 149.59 168.04 165.901 147.627C165.983 147.545 165.983 147.382 165.983 147.3C165.983 147.219 165.901 147.137 165.737 146.974C165.573 146.892 165.327 146.892 165.246 147.055C153.196 160.528 132.95 163.876 117.131 154.976C107.787 149.75 100.901 140.768 98.3603 130.398C95.7373 119.865 97.6226 109.005 103.606 99.9421C114.098 83.8566 135.164 77.8143 152.541 85.8979C169.836 93.8998 178.77 112.925 173.852 131.133C173.77 131.378 173.852 131.541 174.016 131.623C174.18 131.705 174.344 131.705 174.426 131.623C174.508 131.623 174.59 131.541 174.672 131.378C182.868 107.209 172.623 80.6721 150.327 68.0977C147.95 66.7912 145.409 65.5665 142.868 64.6683L141.885 64.3417L142.049 63.3618C142.541 60.504 142.787 57.5645 142.787 54.7067C142.787 28.578 124.016 6.53187 98.1144 2.36761C97.9505 2.36761 97.8685 2.36761 97.7865 2.44926C97.7046 2.44926 97.6226 2.61257 97.6226 2.77587C97.6226 2.93918 97.7865 3.10248 97.9505 3.18413C115.655 7.10344 128.442 23.0256 128.442 41.0708C128.442 51.7672 123.934 62.0554 116.147 69.4041C108.196 76.8345 97.7865 80.5088 86.8849 79.7739C67.7046 78.5492 52.1308 63.1985 50.6554 44.0919C49.2619 25.3119 61.557 8.24657 79.9177 3.51074C80.1636 3.42909 80.2455 3.26578 80.2455 3.10248C80.2455 2.93918 80.1636 2.77587 80.0816 2.77587C79.9996 2.69422 79.9177 2.69422 79.7537 2.69422C54.5898 7.3484 36.3111 29.3129 36.3111 54.87C36.3111 57.5645 36.475 60.2591 36.8849 62.8719L37.0488 63.8518L36.1472 64.1784C33.0324 65.4848 29.7537 66.8729 27.1308 68.5059C4.99964 82.5501 -3.60692 110.149 6.72095 134.154C6.80292 134.318 6.88489 134.318 6.96685 134.399C7.04882 134.399 7.13079 134.481 7.29472 134.318C7.45866 134.236 7.54063 133.991 7.45866 133.828C1.31112 116.844 7.86849 97.5741 23.1964 87.8575C32.2128 82.1419 43.4423 80.3455 53.7701 83.04C64.3439 85.7346 73.0324 92.5117 78.2783 102.065C87.5406 118.885 82.8685 140.197 67.5406 151.71C52.2947 163.059 31.2292 161.916 17.3767 149.015C17.2128 148.852 16.9669 148.852 16.8029 148.933C16.639 149.015 16.639 149.178 16.557 149.26C16.557 149.342 16.557 149.505 16.639 149.587C34.098 168.203 62.4587 171.796 84.098 158.16C85.5734 157.262 86.9669 156.2 88.3603 155.221L89.2619 154.404Z" fill="#4A8B94"/><path d="M90.0829 111.047C81.0665 111.047 73.6895 103.698 73.6895 94.7167C73.6895 85.7349 81.0665 78.3862 90.0829 78.3862C99.0993 78.3862 106.476 85.7349 106.476 94.7167C106.476 103.698 99.1813 111.047 90.0829 111.047ZM90.0829 80.8358C82.378 80.8358 76.1485 87.0414 76.1485 94.7167C76.1485 102.392 82.378 108.598 90.0829 108.598C97.7878 108.598 104.017 102.392 104.017 94.7167C104.017 87.0414 97.7878 80.8358 90.0829 80.8358Z" fill="#4A8B94"/><path d="M75.1638 142.646C74.0983 142.32 73.0327 141.993 71.9671 141.585L72.7868 139.299C73.7704 139.625 74.836 140.033 75.8196 140.278L75.1638 142.646ZM68.8524 140.36C67.8688 139.952 66.8032 139.462 65.8196 138.89L66.9671 136.686C67.9507 137.176 68.8524 137.665 69.836 138.074L68.8524 140.36ZM62.8688 137.257C61.8851 136.686 60.9835 136.114 60.0819 135.461L61.4753 133.42C62.377 133.991 63.2786 134.644 64.1802 135.134L62.8688 137.257ZM57.377 133.42C56.4753 132.766 55.6556 132.031 54.836 131.297L56.4753 129.5C57.295 130.235 58.1147 130.888 58.9343 131.542L57.377 133.42ZM52.377 129.01C51.6393 128.194 50.8196 127.377 50.0819 126.561L51.9671 124.928C52.7048 125.744 53.3606 126.561 54.0983 127.296L52.377 129.01ZM48.0327 123.948C47.377 123.05 46.7212 122.152 46.0655 121.253L48.1147 119.865C48.6884 120.763 49.3442 121.58 49.9999 122.478L48.0327 123.948ZM44.3442 118.314C43.7704 117.334 43.2786 116.354 42.7868 115.374L44.9999 114.313C45.4917 115.293 45.9835 116.191 46.4753 117.089L44.3442 118.314ZM41.3933 112.353C40.9835 111.292 40.5737 110.312 40.2458 109.25L42.5409 108.434C42.8688 109.414 43.2786 110.475 43.6884 111.373L41.3933 112.353ZM39.1802 105.984C38.8524 104.923 38.6065 103.861 38.4425 102.718L40.8196 102.228C41.0655 103.29 41.3114 104.351 41.5573 105.331L39.1802 105.984ZM37.8688 99.4522C37.7048 98.3907 37.6229 97.2476 37.5409 96.1044L39.9999 95.9411C40.0819 97.0026 40.1638 98.0641 40.3278 99.1256L37.8688 99.4522ZM37.377 92.8383V92.3484C37.377 91.3686 37.377 90.4704 37.4589 89.4906L39.9179 89.6539C39.836 90.5521 39.836 91.4503 39.836 92.3484V92.8383H37.377ZM40.1638 86.3878L37.7048 86.1429C37.7868 85.7346 37.7868 85.2447 37.8688 84.8364L40.3278 85.163C40.3278 85.5713 40.2458 85.9796 40.1638 86.3878Z" fill="#4A8B94"/><path d="M107.214 141.83L106.395 139.544C107.132 139.299 107.87 139.054 108.608 138.727L109.509 141.013C108.69 141.34 107.952 141.585 107.214 141.83ZM112.542 139.707L111.476 137.502C112.46 137.012 113.362 136.523 114.345 136.033L115.575 138.156C114.591 138.727 113.526 139.217 112.542 139.707ZM118.444 136.441L117.132 134.4C118.034 133.828 118.936 133.256 119.755 132.603L121.231 134.563C120.247 135.216 119.345 135.869 118.444 136.441ZM123.772 132.44L122.214 130.562C123.034 129.909 123.854 129.174 124.591 128.439L126.313 130.235C125.493 130.97 124.673 131.787 123.772 132.44ZM128.69 127.867L126.886 126.234C127.624 125.499 128.28 124.683 129.017 123.785L130.903 125.336C130.165 126.234 129.427 127.051 128.69 127.867ZM132.87 122.642L130.903 121.254C131.558 120.355 132.132 119.539 132.706 118.559L134.755 119.865C134.181 120.845 133.526 121.743 132.87 122.642ZM136.395 117.008L134.263 115.865C134.755 114.966 135.247 113.987 135.657 113.007L137.87 113.987C137.46 114.966 136.968 116.028 136.395 117.008ZM139.181 110.884L136.886 109.986C137.296 109.006 137.624 107.944 137.952 106.964L140.329 107.699C139.919 108.761 139.591 109.822 139.181 110.884ZM141.149 104.515L138.772 103.943C139.017 102.882 139.263 101.902 139.427 100.841L141.886 101.249C141.64 102.31 141.395 103.453 141.149 104.515ZM142.296 97.901L139.837 97.6561C139.919 96.5946 140.001 95.5331 140.083 94.4716L142.542 94.5533C142.46 95.6964 142.378 96.8396 142.296 97.901ZM140.083 91.2872C140.083 90.2257 140.001 89.1642 139.919 88.1028L142.378 87.9395C142.46 89.0826 142.542 90.1441 142.542 91.2872H140.083Z" fill="#4A8B94"/><path d="M56.6393 55.0334L55 53.2371C55.8197 52.5022 56.7213 51.7673 57.541 51.1141L59.0164 53.0737C58.2787 53.6453 57.459 54.2985 56.6393 55.0334ZM123.279 54.9518C122.705 54.3802 122.049 53.8903 121.393 53.4004L122.951 51.4407C123.607 51.9306 124.262 52.5022 124.918 53.0737L123.279 54.9518ZM118.852 51.4407C117.951 50.8691 117.049 50.2159 116.148 49.726L117.459 47.603C118.443 48.1746 119.344 48.8278 120.246 49.481L118.852 51.4407ZM61.7213 51.1141L60.3279 49.0728C61.2295 48.4196 62.2131 47.848 63.1967 47.2764L64.4262 49.3994C63.5246 49.8893 62.623 50.4609 61.7213 51.1141ZM113.361 48.1746C112.377 47.6847 111.475 47.1948 110.492 46.7865L111.475 44.5819C112.459 45.0718 113.525 45.5617 114.508 46.0517L113.361 48.1746ZM67.2131 47.848L66.0656 45.6434C67.0492 45.1535 68.1148 44.6636 69.0984 44.2553L70.082 46.5416C69.0984 46.8682 68.1148 47.3581 67.2131 47.848ZM107.541 45.5617C106.557 45.1535 105.574 44.8269 104.508 44.5819L105.246 42.214C106.311 42.5406 107.377 42.8672 108.443 43.2755L107.541 45.5617ZM73.0328 45.3168L72.2131 43.0305C73.2787 42.6223 74.3443 42.2957 75.4098 41.969L76.0656 44.337C75.082 44.5819 74.0984 44.9085 73.0328 45.3168ZM101.393 43.6837C100.328 43.4388 99.3443 43.1938 98.2787 43.0305L98.6885 40.581C99.7541 40.7443 100.902 40.9892 101.967 41.2342L101.393 43.6837ZM79.1803 43.5204L78.6885 41.1525C79.7541 40.9076 80.9016 40.7443 81.9672 40.581L82.3771 43.0305C81.3115 43.1122 80.2459 43.2755 79.1803 43.5204ZM95.082 42.6223C94.0164 42.5406 92.9508 42.459 91.8852 42.3773L91.9672 39.9277C93.1148 40.0094 94.1803 40.0094 95.3279 40.1727L95.082 42.6223ZM85.4918 42.5406L85.2459 40.091C86.3115 40.0094 87.459 39.9277 88.6066 39.9277L88.6885 42.3773C87.623 42.459 86.5574 42.459 85.4918 42.5406Z" fill="#4A8B94"/><path d="M90.0821 99.6157C92.7982 99.6157 95.0001 97.4222 95.0001 94.7165C95.0001 92.0108 92.7982 89.8174 90.0821 89.8174C87.3659 89.8174 85.1641 92.0108 85.1641 94.7165C85.1641 97.4222 87.3659 99.6157 90.0821 99.6157Z" fill="white"/></g><defs><clipPath id="clip0_803_6702"><rect width="180" height="170" fill="white"/></clipPath></defs></svg>
                            </div>
                            <div class="popup_success_pollution_middle_numbers">
                                <div class="popup_success_pollution_middle_numbers_top"><?php if (isset($translation['text194'])) { echo $translation['text194']; } ?></div>
                                <div class="popup_success_pollution_middle_numbers_bottom">
                                    <img src="/images/gifs/pollution2.gif" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="popup_success_pollution_bottom"><?php if (isset($translation['text196'])) { echo $translation['text196']; } ?></div>
                    </div>
                </div>
                <div class="btn_wrapper btn_wrapper_blue popup_success_pollution_close">
                    <div class="btn btn_blue">
                        <span><?php if (isset($translation['text165'])) { echo $translation['text165']; } ?></span>
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
                </div>
            </div>
        </div>

        <!-- popup minigame alarm -->
        <div id="popup_minigame_alarm">
            <div class="popup_minigame_alarm_bg"></div>
            <div class="popup_minigame_alarm_inner_main">
                <div class="popup_minigame_alarm_inner">
                    <div class="popup_minigame_alarm_header"><?php if (isset($translation['text259'])) { echo $translation['text259']; } ?></div>
                    <div class="popup_minigame_alarm_text1"><?php if (isset($translation['text260'])) { echo $translation['text260']; } ?></div>
                    <div class="popup_minigame_alarm_center_block">
                        <div class="popup_minigame_alarm_center_block_face">
                            <div class="popup_minigame_alarm_center_block_face_top">
                                <div class="popup_minigame_alarm_center_block_face_time">
                                    <div class="popup_minigame_alarm_center_block_face_time_hours"></div>:<div class="popup_minigame_alarm_center_block_face_time_minutes"></div>
                                </div>
                                <div class="popup_minigame_alarm_center_block_face_wifi">
                                    <img src="/images/popup_alarm_face_wifi.png" alt="">
                                </div>
                            </div>
                            <img src="/images/agent_face.png" alt="" class="popup_minigame_alarm_center_block_face_main_img">
                            <div class="popup_minigame_alarm_center_block_face_agent_name"><?php if (isset($translation['text8'])) { echo $translation['text8']; } ?></div>
                        </div>
                        <div class="popup_minigame_alarm_center_block_messages">
                            <div class="popup_minigame_alarm_text2"><?php if (isset($translation['text261'])) { echo $translation['text261']; } ?></div>
                            <div class="popup_minigame_alarm_center_block_message"><span><?php if (isset($translation['text262'])) { echo $translation['text262']; } ?></span></div>
                            <div class="btn_wrapper btn_wrapper_blue btn_wrapper_red popup_minigame_alarm_submit">
                                <div class="btn btn_blue btn_red">
                                    <span>OK</span>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- popup mission complete -->
        <div id="popup_mission_complete">
            <div class="popup_mission_complete_center_inner_btns">
                <a href="<?php if (isset($translation['text297'])) { echo $translation['text297']; } else { echo '#'; } ?>" class="popup_mission_complete_goto_all_scores" target="_blank"><?php if (isset($translation['text284'])) { echo $translation['text284']; } ?></a>
                <a href="https://questalize.com/" class="popup_mission_complete_leave_feedback" target="_blank"><?php if (isset($translation['text285'])) { echo $translation['text285']; } ?></a>
            </div>

            <div class="popup_mission_complete_inner">
                <div class="popup_mission_complete_try_also">
                    <div class="popup_mission_complete_column_title"><?php if (isset($translation['text276'])) { echo $translation['text276']; } ?></div>
                </div>
                <div class="popup_mission_complete_center">
                    <div class="popup_mission_complete_inner_confetti">
                        <img src="/images/gifs/cinfetti.gif" alt="">
                    </div>

                    <div class="popup_mission_complete_center_title1"><?php if (isset($translation['text279'])) { echo $translation['text279']; } ?></div>
                    <div class="popup_mission_complete_center_title2"><?php if (isset($translation['text280'])) { echo $translation['text280']; } ?></div>
                    <div class="popup_mission_complete_center_circle_dots">
                        <img src="/images/gifs/dots.gif" alt="">
                    </div>
                    <div class="popup_mission_complete_center_inner">
                        <div class="popup_mission_complete_center_inner_text"><?php if (isset($translation['text281'])) { echo $translation['text281']; } ?></div>
                        <div class="popup_mission_complete_center_inner_results">
                            <div class="popup_mission_complete_center_inner_score">
                                <svg width="236" height="264" viewBox="0 0 236 264" fill="none" xmlns="http://www.w3.org/2000/svg"><g filter="url(#filter0_d_411_2632)"><path d="M117.927 103.559C109.069 103.559 101.862 110.766 101.862 119.623C101.862 128.481 109.069 135.688 117.927 135.688C126.784 135.688 133.992 128.481 133.992 119.623C133.992 110.766 126.784 103.559 117.927 103.559Z" fill="#FF0303"/><path d="M114.316 158.409L114.229 158.33L112.183 156.477L107.366 152.119L102.128 152.683L98.9228 153.026L98.1629 153.109L95.7158 153.371L88.0093 169.748L99.9436 169.906L107.668 179L116.451 160.34L114.316 158.409Z" fill="#FF0303"/><path d="M140.133 153.371L137.686 153.109L136.926 153.028L133.721 152.683L128.483 152.119L123.666 156.477L121.619 158.33L121.533 158.409L119.398 160.34L128.18 179L135.905 169.906L147.839 169.748L140.133 153.371Z" fill="#FF0303"/><path d="M146.063 119.622L150.856 108.924L140.69 103.086L138.279 91.6123L126.622 92.8642L117.928 85L109.234 92.8644L97.5769 91.6125L95.1665 103.086L85 108.924L89.7931 119.622L85 130.321L95.1665 136.159L97.5769 147.632L98.4655 147.537L101.673 147.192L104.878 146.848L105.737 146.757H105.739L109.234 146.38L111.863 148.758L114.637 151.267L115.502 152.049L116.772 153.198L117.928 154.245L119.085 153.198L120.355 152.049L121.22 151.267L123.377 149.317L123.379 149.316L126.622 146.38L132.288 146.988H132.289L132.866 147.05L134.184 147.192H134.186L137.391 147.537L138.279 147.632L140.69 136.159L150.856 130.321L146.063 119.622ZM117.928 141.194C106.034 141.194 96.356 131.518 96.356 119.622C96.356 107.728 106.034 98.0502 117.928 98.0502C129.822 98.0502 139.5 107.728 139.5 119.622C139.5 131.518 129.822 141.194 117.928 141.194Z" fill="#FF0303"/></g><defs><filter id="filter0_d_411_2632" x="0" y="0" width="235.856" height="264" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB"><feFlood flood-opacity="0" result="BackgroundImageFix"/><feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/><feOffset/><feGaussianBlur stdDeviation="42.5"/><feColorMatrix type="matrix" values="0 0 0 0 1 0 0 0 0 0.0117647 0 0 0 0 0.0117647 0 0 0 0.8 0"/><feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_411_2632"/><feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_411_2632" result="shape"/></filter></defs></svg>
                                <div class="popup_mission_complete_center_inner_result_text"><?php if (isset($translation['text282'])) { echo $translation['text282']; } ?></div>
                                <div class="popup_mission_complete_center_inner_result_number_wrapper">
                                    <img src="/images/gifs/timer1.gif" alt="">
                                    <div class="popup_mission_complete_center_inner_result_number">0</div>
                                </div>
                            </div>
                            <div class="popup_mission_complete_center_inner_timer">
                                <svg width="261" height="250" viewBox="0 0 261 250" fill="none" xmlns="http://www.w3.org/2000/svg"><g filter="url(#filter0_d_411_2645)"><path d="M150.722 85H136.278C135.081 85 134.111 85.97 134.111 87.1667V91.5C134.111 92.6966 135.081 93.6667 136.278 93.6667H150.722C151.919 93.6667 152.889 92.6966 152.889 91.5V87.1667C152.889 85.97 151.919 85 150.722 85Z" fill="#00F0FF"/><path d="M108.833 116.777H94.3888C93.8142 116.777 93.2631 117.006 92.8568 117.412C92.4504 117.818 92.2222 118.369 92.2222 118.944C92.2222 119.519 92.4504 120.07 92.8568 120.476C93.2631 120.882 93.8142 121.111 94.3888 121.111H108.833C109.408 121.111 109.959 120.882 110.365 120.476C110.772 120.07 111 119.519 111 118.944C111 118.369 110.772 117.818 110.365 117.412C109.959 117.006 109.408 116.777 108.833 116.777Z" fill="#00F0FF"/><path d="M108.111 136.278C108.111 135.703 107.883 135.152 107.477 134.745C107.07 134.339 106.519 134.111 105.945 134.111H91.5002C90.9255 134.111 90.3744 134.339 89.9681 134.745C89.5618 135.152 89.3335 135.703 89.3335 136.278C89.3335 136.852 89.5618 137.403 89.9681 137.81C90.3744 138.216 90.9255 138.444 91.5002 138.444H105.945C106.519 138.444 107.07 138.216 107.477 137.81C107.883 137.403 108.111 136.852 108.111 136.278Z" fill="#00F0FF"/><path d="M107.389 142.778H97.278C96.7034 142.778 96.1523 143.006 95.7459 143.412C95.3396 143.819 95.1113 144.37 95.1113 144.944C95.1113 145.519 95.3396 146.07 95.7459 146.477C96.1523 146.883 96.7034 147.111 97.278 147.111H107.389C107.964 147.111 108.515 146.883 108.921 146.477C109.327 146.07 109.556 145.519 109.556 144.944C109.556 144.37 109.327 143.819 108.921 143.412C108.515 143.006 107.964 142.778 107.389 142.778Z" fill="#00F0FF"/><path d="M108.111 127.611C108.111 127.037 107.883 126.486 107.477 126.079C107.07 125.673 106.519 125.445 105.944 125.445H87.1667C86.592 125.445 86.0409 125.673 85.6346 126.079C85.2283 126.486 85 127.037 85 127.611C85 128.186 85.2283 128.737 85.6346 129.144C86.0409 129.55 86.592 129.778 87.1667 129.778H105.944C106.519 129.778 107.07 129.55 107.477 129.144C107.883 128.737 108.111 128.186 108.111 127.611Z" fill="#00F0FF"/><path d="M143.5 131.944V107.389C129.96 107.389 118.944 118.404 118.944 131.944C118.944 145.484 129.96 156.5 143.5 156.5C157.04 156.5 168.055 145.484 168.055 131.944H143.5Z" fill="#00F0FF"/><path d="M167.264 109.8L169.588 107.477C169.791 107.276 169.954 107.037 170.065 106.773C170.176 106.51 170.234 106.227 170.235 105.941C170.236 105.655 170.18 105.372 170.071 105.107C169.963 104.843 169.802 104.602 169.6 104.4C169.398 104.198 169.158 104.038 168.893 103.929C168.629 103.82 168.345 103.764 168.059 103.765C167.773 103.767 167.49 103.824 167.227 103.935C166.963 104.047 166.724 104.209 166.524 104.413L164.106 106.83C159.449 102.994 153.813 100.536 147.833 99.7335V95.8335H139.167V99.7335C133.187 100.536 127.551 102.994 122.894 106.829L120.476 104.412C120.276 104.208 120.037 104.045 119.773 103.934C119.51 103.823 119.227 103.766 118.941 103.764C118.655 103.763 118.371 103.819 118.107 103.928C117.842 104.037 117.602 104.197 117.4 104.399C117.198 104.601 117.037 104.842 116.929 105.106C116.82 105.37 116.764 105.654 116.765 105.94C116.766 106.226 116.824 106.509 116.935 106.772C117.046 107.036 117.209 107.275 117.412 107.476L119.736 109.799C114.117 115.804 110.994 123.721 111 131.945C111 149.865 125.58 164.445 143.5 164.445C161.421 164.445 176 149.865 176 131.945C176.006 123.722 172.883 115.804 167.264 109.8ZM143.5 158.667C138.215 158.667 133.048 157.1 128.654 154.163C124.259 151.227 120.834 147.054 118.812 142.171C116.789 137.288 116.26 131.915 117.291 126.731C118.322 121.548 120.867 116.786 124.605 113.049C128.342 109.312 133.103 106.767 138.287 105.736C143.47 104.705 148.843 105.234 153.726 107.256C158.609 109.279 162.782 112.704 165.719 117.099C168.655 121.493 170.222 126.659 170.222 131.945C170.222 135.454 169.531 138.929 168.188 142.171C166.845 145.413 164.877 148.359 162.395 150.84C159.914 153.321 156.968 155.29 153.726 156.633C150.484 157.976 147.009 158.667 143.5 158.667Z" fill="#00F0FF"/></g><defs><filter id="filter0_d_411_2645" x="0" y="0" width="261" height="249.445" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB"><feFlood flood-opacity="0" result="BackgroundImageFix"/><feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/><feOffset/><feGaussianBlur stdDeviation="42.5"/><feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0.94 0 0 0 0 1 0 0 0 0.8 0"/><feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_411_2645"/><feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_411_2645" result="shape"/></filter></defs></svg>
                                <div class="popup_mission_complete_center_inner_result_text"><?php if (isset($translation['text283'])) { echo $translation['text283']; } ?></div>
                                <div class="popup_mission_complete_center_inner_result_number_wrapper">
                                    <!-- <img src="/images/gifs/timer2.gif" alt=""> -->
                                    <!-- <img src="/images/gifs/timer1.gif" alt=""> -->
                                    <video autoplay muted loop style="width: 236px;">
                                        <source src="/images/gifs/gif_final_step_right_bg.mp4" type="video/mp4">
                                    </video>
                                    <div class="popup_mission_complete_center_inner_result_number">
                                        <div class="popup_mission_complete_center_inner_result_hours">0</div>
                                        <span>:</span>
                                        <div class="popup_mission_complete_center_inner_result_minutes">0</div>
                                        <span>:</span>
                                        <div class="popup_mission_complete_center_inner_result_seconds">0</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="popup_mission_complete_partners">
                    <div class="popup_mission_complete_column_title"><?php if (isset($translation['text286'])) { echo $translation['text286']; } ?></div>
                </div>
            </div>
        </div>

        <!-- popup start mission -->
        <div id="popup_start_mission">
            <div class="popup_start_mission_bg"></div>
            <div class="popup_start_mission_inner">
                <div class="popup_start_mission_inner2">
                    <div class="popup_success_input_wrapper">
                        <div class="popup_success_input_border_left"></div>
                        <div class="popup_success_input_border_right"></div>
                        <div class="popup_start_mission_number"><img src="/images/gifs/start_mission.gif"></div>
                        <!-- <div class="popup_start_mission_number"></div> -->
                        <div class="popup_success_input"><?php if (isset($translation['text303'])) { echo $translation['text303']; } ?></div>
                        <div class="popup_success_input_line_left_outer">
                            <svg width="260" height="88" viewBox="0 0 260 88" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 67.3344L1.90735e-05 87.2466L1.72529e-05 -4.58837e-05L9 21.3345L9 67.3344Z" fill="white"></path><path d="M6.5 43H123L149.5 69.5H260" stroke="white"></path></svg>
                        </div>
                        <div class="popup_success_input_line_right_outer">
                            <svg width="262" height="75" viewBox="0 0 262 75" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M253.5 35H137L110.5 61.5H0" stroke="white"></path><path d="M253 19.9122L262 3.9341e-07L262 74.3345L253 53L253 19.9122Z" fill="white"></path></svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- popup exit -->
        <div id="popup_exit">
            <div class="popup_exit_bg"></div>
            <div class="popup_exit_inner">
                <div class="popup_exit_input"><span><?php if (isset($translation['text166'])) { echo $translation['text166']; } ?></span></div>
                <div class="popup_exit_btns">
                    <div class="popup_exit_btn_no"><?php if (isset($translation['text167'])) { echo $translation['text167']; } ?></div>
                    <div class="popup_exit_btn_yes"><?php if (isset($translation['text168'])) { echo $translation['text168']; } ?></div>
                </div>
            </div>
        </div>

        <!-- popup end video question -->
        <div id="popup_end_video_question">
            <div class="popup_end_video_question_bg"></div>
            <div class="popup_end_video_question_inner">
                <div class="popup_end_video_question_input"><span><?php if (isset($translation['text305'])) { echo $translation['text305']; } ?></span></div>
                <div class="popup_end_video_question_btns">
                    <div class="popup_end_video_question_btn_no"><?php if (isset($translation['text167'])) { echo $translation['text167']; } ?></div>
                    <div class="popup_end_video_question_btn_yes"><?php if (isset($translation['text168'])) { echo $translation['text168']; } ?></div>
                </div>
            </div>
        </div>

    <?php
            /*// текстовые языковые переменные
            echo '<script>
                    ' . ((isset($translation['text88'])) ? 'var text88 = ' . json_encode($translation['text88']) . ';' : '') . '
                    ' . ((isset($translation['text89'])) ? 'var text89 = ' . json_encode($translation['text89']) . ';' : '') . '
                    ' . ((isset($translation['text92'])) ? 'var text92 = ' . json_encode($translation['text92']) . ';' : '') . '
                    ' . ((isset($translation['text93'])) ? 'var text93 = ' . json_encode($translation['text93']) . ';' : '') . '
                    ' . ((isset($translation['text94'])) ? 'var text94 = ' . json_encode($translation['text94']) . ';' : '') . '
                    ' . ((isset($translation['text95'])) ? 'var text95 = ' . json_encode($translation['text95']) . ';' : '') . '
                    ' . ((isset($translation['text97'])) ? 'var text97 = ' . json_encode($translation['text97']) . ';' : '') . '
                    ' . ((isset($translation['text98'])) ? 'var text98 = ' . json_encode($translation['text98']) . ';' : '') . '
                    ' . ((isset($translation['text99'])) ? 'var text99 = ' . json_encode($translation['text99']) . ';' : '') . '
                    ' . ((isset($translation['text100'])) ? 'var text100 = ' . json_encode($translation['text100']) . ';' : '') . '
                    ' . ((isset($translation['text101'])) ? 'var text101 = ' . json_encode($translation['text101']) . ';' : '') . '
                    ' . ((isset($translation['text102'])) ? 'var text102 = ' . json_encode($translation['text102']) . ';' : '') . '
                    ' . ((isset($translation['text103'])) ? 'var text103 = ' . json_encode($translation['text103']) . ';' : '') . '
                    ' . ((isset($translation['text104'])) ? 'var text104 = ' . json_encode($translation['text104']) . ';' : '') . '
                    ' . ((isset($translation['text116'])) ? 'var text116 = ' . json_encode($translation['text116']) . ';' : '') . '
                    ' . ((isset($translation['text118'])) ? 'var text118 = ' . json_encode($translation['text118']) . ';' : '') . '
                    ' . ((isset($translation['text120'])) ? 'var text120 = ' . json_encode($translation['text120']) . ';' : '') . '
                    ' . ((isset($translation['text122'])) ? 'var text122 = ' . json_encode($translation['text122']) . ';' : '') . '
                    ' . ((isset($translation['text124'])) ? 'var text124 = ' . json_encode($translation['text124']) . ';' : '') . '
                    ' . ((isset($translation['text126'])) ? 'var text126 = ' . json_encode($translation['text126']) . ';' : '') . '
                    ' . ((isset($translation['text128'])) ? 'var text128 = ' . json_encode($translation['text128']) . ';' : '') . '
                    ' . ((isset($translation['text130'])) ? 'var text130 = ' . json_encode($translation['text130']) . ';' : '') . '
                    ' . ((isset($translation['text131'])) ? 'var text131 = ' . json_encode($translation['text131']) . ';' : '') . '
                    ' . ((isset($translation['text133'])) ? 'var text133 = ' . json_encode($translation['text133']) . ';' : '') . '
                    ' . ((isset($translation['text134'])) ? 'var text134 = ' . json_encode($translation['text134']) . ';' : '') . '
                    ' . ((isset($translation['text135'])) ? 'var text135 = ' . json_encode($translation['text135']) . ';' : '') . '
                    ' . ((isset($translation['text137'])) ? 'var text137 = ' . json_encode($translation['text137']) . ';' : '') . '
                    ' . ((isset($translation['text139'])) ? 'var text139 = ' . json_encode($translation['text139']) . ';' : '') . '
                    ' . ((isset($translation['text143'])) ? 'var text143 = ' . json_encode($translation['text143']) . ';' : '') . '
                    ' . ((isset($translation['text144'])) ? 'var text144 = ' . json_encode($translation['text144']) . ';' : '') . '
                    ' . ((isset($translation['text161'])) ? 'var text161 = ' . json_encode($translation['text161']) . ';' : '') . '
                    ' . ((isset($translation['text162'])) ? 'var text162 = ' . json_encode($translation['text162']) . ';' : '') . '
                    ' . ((isset($translation['text163'])) ? 'var text163 = ' . json_encode($translation['text163']) . ';' : '') . '
                    ' . ((isset($translation['text164'])) ? 'var text164 = ' . json_encode($translation['text164']) . ';' : '') . '
                    ' . ((isset($translation['text165'])) ? 'var text165 = ' . json_encode($translation['text165']) . ';' : '') . '
                </script>';*/
        }
    ?>
<?php
	defined('GD_ACCESS') or die('You can not access the file directly!');

	// пишем данные в стату по заходам
	$bot = 0;
    // $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : NULL;
    $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    $ip = $this->getIp();
    // $domen = isset($_SERVER['REMOTE_ADDR']) ? gethostbyaddr($_SERVER['REMOTE_ADDR']) : NULL;
    $domen = isset($_SERVER['REMOTE_ADDR']) ? gethostbyaddr($_SERVER['REMOTE_ADDR']) : '';

    $query = "
        SELECT *
        FROM `bots`
    ";
    $bots = $this->db->select($query);
    if ($bots && count($bots) > 0) {
    	require_once(ROOT . '/plugins/geo/SxGeo.php');
        $SxGeo = new SxGeo(ROOT . '/plugins/geo/SxGeoCity.dat');

        $Geo = $SxGeo->getCityFull($ip);
        $country = (!empty($Geo['country']['name_ru']) && !is_null($Geo['country']['name_ru']) && $Geo['country']['name_ru'] != 'NULL') ? $Geo['country']['name_ru'] : '';
        $region = (!empty($Geo['region']['name_ru']) && !is_null($Geo['region']['name_ru']) && $Geo['region']['name_ru'] != 'NULL') ? $Geo['region']['name_ru'] : '';
        $city = (!empty($Geo['city']['name_ru']) && !is_null($Geo['city']['name_ru']) && $Geo['city']['name_ru'] != 'NULL') ? $Geo['city']['name_ru'] : '';
        unset($SxGeo);

        // $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : NULL;
        $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
        // $refer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : NULL;
        $refer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

        if (
        	stripos($uri, 'favicon') === false &&
        	stripos($uri, 'js') === false &&
        	stripos($uri, 'css') === false &&
        	stripos($uri, 'img') === false
        ) {
        	$query = "
                INSERT INTO `in`
                SET `ip` = {?},
                    `url` = {?},
                    `user_agent` = {?},
                    `domen` = {?},
                    `refer` = {?},
                    `country` = {?},
                    `region` = {?},
                    `city` = {?},
                    `datetime` = NOW(),
                    `user_id` = {?}
            ";
            $this->db->query($query, [$ip, $uri, $user_agent, $domen, $refer, $country, $region, $city, $this->userInfo ? $this->userInfo['id'] : 0]);
        }
    }
?>
    </body>
</html>
