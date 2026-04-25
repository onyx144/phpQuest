<?php

require_once(ROOT . '/admin/view/template/component/autocomplete_select.php');

/**
 * Trait для работы с базой данных Mobile Calls
 */
trait MobileCallsBlock
{
    /**
     * Mobile Calls - первый экран (форма поиска по номеру)
     * @param int $lang_id
     * @param int $team_id
     * @return array
     */
    private function uploadDatabasesMobileCalls($lang_id, $team_id)
    {
        $translation = $this->getWordsByPage(null, $lang_id);
        $team_info = $this->teamInfo($team_id);

        $return = [];

        $return['titles'] = $this->getMobileCallsTitles($translation);
        $return['back_btn'] = $this->getMobileCallsBackButton($translation);
        $return['content'] = $this->getMobileCallsSearchForm($translation, $team_info, $lang_id);

        return $return;
    }

    /**
     * Mobile Calls - второй экран (сообщения)
     * @param int $lang_id
     * @param int $team_id
     * @return array
     */
    private function uploadDatabasesMobileCallsMessages($lang_id, $team_id)
    {
        $translation = $this->getWordsByPage(null, $lang_id);
        $team_info = $this->teamInfo($team_id);
        $user_info = $this->getUserMobileCallsInfo($team_id);

        $return = [];

        $return['titles'] = $this->getMobileCallsMessagesTitles($translation);
        $return['back_btn'] = $this->getMobileCallsBackButton($translation);
        $return['content'] = $this->getMobileCallsMessagesContent($translation, $user_info, $team_info);
        $return['popup'] = $this->getMobileCallsMessagesPopup($translation);

        // Обработка первого запуска
        $this->processMobileCallsFirstRun($team_info, $team_id, $lang_id);
        
        // Обновление для текущего пользователя
        $this->updateUserMobileCallsStatus($team_id);

        return $return;
    }

    // ==================== HELPER METHODS ====================

    /**
     * Получение заголовков для Mobile Calls
     */
    private function getMobileCallsTitles($translation)
    {
        return '<div class="dashboard_tab_title dashboard_tab_title_can_click" data-tab="tab1" data-step="databases_start_four" data-action-id="28" data-database="false">
                    <div class="dashboard_tab_title_active_skew_right"></div>
                    <div class="dashboard_tab_title_inner">
                        <div class="dashboard_tab_title_img_wrapper">
                            <svg width="19" height="21" viewBox="0 0 19 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 0H19V3L17.25 5H0V2L1.75 0ZM1.73684 2H3V3.2L2.26316 4H1V2.8L1.73684 2ZM6 2H4.73684L4 2.8V4H5.26316L6 3.2V2ZM7.73684 2H9V3.2L8.26316 4H7V2.8L7.73684 2ZM17 2H10.7368L10 2.8V4H16.2632L17 3.2V2Z" fill="#00F0FF"/><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 8H19V11L17.25 13H0V10L1.75 8ZM1.73684 10H3V11.2L2.26316 12H1V10.8L1.73684 10ZM6 10H4.73684L4 10.8V12H5.26316L6 11.2V10ZM7.73684 10H9V11.2L8.26316 12H7V10.8L7.73684 10ZM17 10H10.7368L10 10.8V12H16.2632L17 11.2V10Z" fill="#00F0FF"/><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 16H19V19L17.25 21H0V18L1.75 16ZM1.73684 18H3V19.2L2.26316 20H1V18.8L1.73684 18ZM6 18H4.73684L4 18.8V20H5.26316L6 19.2V18ZM7.73684 18H9V19.2L8.26316 20H7V18.8L7.73684 18ZM17 18H10.7368L10 18.8V20H16.2632L17 19.2V18Z" fill="#00F0FF"/><rect width="15" height="1" transform="matrix(1 0 0 -1 2 7)" fill="#00F0FF"/><rect width="15" height="1" transform="matrix(1 0 0 -1 2 15)" fill="#00F0FF"/></svg>
                        </div>
                        <div class="dashboard_tab_title_text">' . $translation['text13'] . '</div>
                    </div>
                </div>
                <div class="dashboard_tab_title dashboard_tab_title_active" data-tab="mobile_calls1">
                    <div class="dashboard_tab_title_active_skew_right"></div>
                    <div class="dashboard_tab_title_inner">
                        <div class="dashboard_tab_title_img_wrapper">
                            ' . $this->getMobileCallsIcon() . '
                        </div>
                        <div class="dashboard_tab_title_text">' . $translation['text59'] . '</div>
                    </div>
                </div>';
    }

    /**
     * Получение заголовков для страницы с сообщениями
     */
    private function getMobileCallsMessagesTitles($translation)
    {
        return '<div class="dashboard_tab_title dashboard_tab_title_can_click" data-tab="tab1" data-step="databases_start_four" data-action-id="28" data-database="false">
                    <div class="dashboard_tab_title_active_skew_right"></div>
                    <div class="dashboard_tab_title_inner">
                        <div class="dashboard_tab_title_img_wrapper">
                            <svg width="19" height="21" viewBox="0 0 19 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 0H19V3L17.25 5H0V2L1.75 0ZM1.73684 2H3V3.2L2.26316 4H1V2.8L1.73684 2ZM6 2H4.73684L4 2.8V4H5.26316L6 3.2V2ZM7.73684 2H9V3.2L8.26316 4H7V2.8L7.73684 2ZM17 2H10.7368L10 2.8V4H16.2632L17 3.2V2Z" fill="#00F0FF"/><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 8H19V11L17.25 13H0V10L1.75 8ZM1.73684 10H3V11.2L2.26316 12H1V10.8L1.73684 10ZM6 10H4.73684L4 10.8V12H5.26316L6 11.2V10ZM7.73684 10H9V11.2L8.26316 12H7V10.8L7.73684 10ZM17 10H10.7368L10 10.8V12H16.2632L17 11.2V10Z" fill="#00F0FF"/><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 16H19V19L17.25 21H0V18L1.75 16ZM1.73684 18H3V19.2L2.26316 20H1V18.8L1.73684 18ZM6 18H4.73684L4 18.8V20H5.26316L6 19.2V18ZM7.73684 18H9V19.2L8.26316 20H7V18.8L7.73684 18ZM17 18H10.7368L10 18.8V20H16.2632L17 19.2V18Z" fill="#00F0FF"/><rect width="15" height="1" transform="matrix(1 0 0 -1 2 7)" fill="#00F0FF"/><rect width="15" height="1" transform="matrix(1 0 0 -1 2 15)" fill="#00F0FF"/></svg>
                        </div>
                        <div class="dashboard_tab_title_text">' . $translation['text13'] . '</div>
                    </div>
                </div>
                <div class="dashboard_tab_title" data-tab="mobile_calls1">
                    <div class="dashboard_tab_title_active_skew_right"></div>
                    <div class="dashboard_tab_title_inner">
                        <div class="dashboard_tab_title_img_wrapper">
                            ' . $this->getMobileCallsIcon() . '
                        </div>
                        <div class="dashboard_tab_title_text">' . $translation['text59'] . '</div>
                    </div>
                </div>
                <div class="dashboard_tab_title dashboard_tab_title_active" data-tab="mobile_calls2">
                    <div class="dashboard_tab_title_active_skew_right"></div>
                    <div class="dashboard_tab_title_inner">
                        <div class="dashboard_tab_title_text">+7 940 544 21 337</div>
                    </div>
                </div>';
    }

    /**
     * Получение кнопки "Назад" для Mobile Calls
     */
    private function getMobileCallsBackButton($translation)
    {
        return '<div class="dashboard_back_btn" data-back="databases_start_four" data-action-id-back="28" data-database="false">
                    <img src="/images/back_bg.png" class="back_btn_bg" alt="">
                    <div class="back_btn_text">' . $translation['text22'] . '</div>
                </div>';
    }

    /**
     * Форма поиска по номеру телефона
     */
    private function getMobileCallsSearchForm($translation, $team_info, $lang_id)
    {
        $countryCodeSelect = $this->generateCountryCodeSelect($lang_id, $team_info, $translation);

        return '<div class="dashboard_tab_content_item dashboard_tab_content_item_start_four" data-tab="tab1"></div>
                <div class="dashboard_tab_content_item dashboard_tab_content_item_car_register dashboard_tab_content_item_active" data-tab="mobile_calls1">
                    <div class="dashboard_car_register1_inner">
                        <div class="dashboard_car_register1_inner_image_wrapper">
                            <img src="/images/database_mobile_calls_icon.png" alt="">
                        </div>
                        <div class="dashboard_car_register1_inner_title" style="margin: 10px 0 0;">' . $translation['text59'] . '</div>
                        <div class="dashboard_car_register1_inner_text">' . $translation['text140'] . '</div>
                        <div class="dashboard_car_register1_fields_top">
                            <div class="dashboard_car_register1_input_wrapper dashboard_car_register1_input_wrapper_license_plate">
                                <div class="dashboard_car_register1_input_border_left"></div>
                                ' . $countryCodeSelect . '
                                <div class="dashboard_mobile_calls1_country_code_error error_text_database_car_register">' . $translation['text86'] . '</div>
                            </div>
                            <div class="dashboard_car_register1_input_wrapper dashboard_car_register1_input_wrapper_country">
                                <div class="dashboard_car_register1_input_border_right"></div>
                                <input type="text" placeholder="' . $translation['text142'] . '" autocomplete="off" class="dashboard_mobile_calls1_number" value="' . $this->formatPhoneNumber($team_info['mobile_calls_number']) . '">
                                <div class="dashboard_mobile_calls1_number_error error_text_database_car_register">' . $translation['text86'] . '</div>
                            </div>
                        </div>
                        <div class="btn_wrapper btn_wrapper_blue dashboard_mobile_calls1_search">
                            <div class="btn btn_blue"><span>' . $translation['text66'] . '</span></div>
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
                </div>';
    }

    /**
     * Контент со списком сообщений
     */
    private function getMobileCallsMessagesContent($translation, $user_info, $team_info)
    {
        $bubbleClass = empty($user_info['mobile_calls_print_messages']) ? ' dashboard_mobile_calls2_inner_first' : '';
        $bubbleTeamClass = empty($team_info['mobile_calls_print_messages']) ? ' dashboard_mobile_calls2_inner_first_team' : '';

        return '<div class="dashboard_tab_content_item dashboard_tab_content_item_start_four" data-tab="tab1"></div>
                <div class="dashboard_tab_content_item dashboard_tab_content_item_car_register" data-tab="mobile_calls1"></div>
                <div class="dashboard_tab_content_item dashboard_tab_content_item_car_register dashboard_tab_content_item_active" data-tab="mobile_calls2">
                    <div class="dashboard_mobile_calls2_inner' . $bubbleClass . $bubbleTeamClass . '">
                        <div class="dashboard_mobile_calls2_top">
                            <div class="dashboard_mobile_calls2_title">' . $translation['text145'] . '</div>
                            <div class="dashboard_mobile_calls2_text">' . $translation['text146'] . '</div>
                        </div>
                        <div class="dashboard_mobile_calls2_messages">
                            ' . $this->generateMessageBlocks($translation) . '
                        </div>
                    </div>
                </div>';
    }

    /**
     * Popup с увеличенными сообщениями
     */
    private function getMobileCallsMessagesPopup($translation)
    {
        return '<div id="popup_mobile_calls_messages">
                    <div class="popup_mobile_calls_messages_bg"></div>
                    <div class="popup_mobile_calls_messages_bg_inner">
                        <div class="popup_mobile_calls_messages_container">
                            <div class="popup_mobile_calls_close">
                                <img src="/images/popup_close.png" alt="">
                            </div>
                            <div class="popup_mobile_calls_dots">
                                <div class="popup_mobile_calls_dot"></div>
                                <div class="popup_mobile_calls_dot"></div>
                                <div class="popup_mobile_calls_dot"></div>
                                <div class="popup_mobile_calls_dot"></div>
                                <div class="popup_mobile_calls_dot"></div>
                                <div class="popup_mobile_calls_dot"></div>
                                <div class="popup_mobile_calls_dot"></div>
                                <div class="popup_mobile_calls_dot"></div>
                            </div>
                            <div class="popup_mobile_calls_inner">
                                <div class="dashboard_mobile_calls2_messages">
                                    ' . $this->generateMessageBlocks($translation, true) . '
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
    }

    /**
     * Генерация блоков сообщений
     */
    private function generateMessageBlocks($translation, $isPopup = false)
    {
        /*
         * Legacy card-based mobile-calls renderer kept for reference.
         * Replaced with a single chat stream in temp.html style.
         */

        $messages = [
            ['role' => 'boss', 'text' => $translation['text147'] . ' 🤙 <br> ' . $translation['text148'] . ' 🤔'],
            ['role' => 'subordinate', 'text' => $translation['text149'] . '☠️ <br> ' . $translation['text150'] . ' 🤟'],
            ['role' => 'boss', 'text' => $translation['text151']],
            ['role' => 'subordinate', 'text' => $translation['text152']],
            ['role' => 'boss', 'text' => $translation['text153']],
            ['role' => 'subordinate', 'text' => $translation['text154'] . ' 👊'],
            ['role' => 'subordinate', 'text' => $translation['text155'] . ' 🙌 <br> ' . $translation['text156'] . ' <br> ' . $translation['text157'] . ' <span class="highlight-blue">' . $translation['text158'] . '</span>'],
            ['role' => 'boss', 'text' => $translation['text159'] . ' ✊']
        ];

        $streamClass = $isPopup
            ? 'dashboard_mobile_calls2_chat_stream dashboard_mobile_calls2_chat_stream_popup'
            : 'dashboard_mobile_calls2_chat_stream';

        $html = '<div class="' . $streamClass . '">';

        foreach ($messages as $msg) {
            $isBoss = ($msg['role'] === 'boss');
            $roleClass = $isBoss ? 'boss' : 'subordinate';

            $html .= '<div class="message ' . $roleClass . '">';
            if ($isBoss) {
                $html .= '<div class="sender-label">' . $translation['text160'] . '</div>';
            }
            $html .= '   <div class="bubble">
                            <div class="text">' . $msg['text'] . '</div>
                        </div>
                    </div>';
        }

        $html .= '</div>';

        return $html;
    }

    /**
     * Генерация select для кода страны
     */
    private function generateCountryCodeSelect($lang_id, $team_info, $translation)
    {
        $sql = "SELECT c.code, COALESCE(cd_lang.name, cd_default.name) AS name, c.id
                FROM countries c
                LEFT JOIN countries_description cd_lang
                    ON c.id = cd_lang.country_id AND cd_lang.lang_id = {?}
                LEFT JOIN countries_description cd_default
                    ON c.id = cd_default.country_id AND cd_default.lang_id = 3
                WHERE cd_lang.name IS NOT NULL OR cd_default.name IS NOT NULL
                ORDER BY COALESCE(cd_lang.name, cd_default.name)";
        $countries = $this->db->select($sql, [$lang_id]);

        if (!$countries) {
            return '';
        }

        $componentData = [];
        $selectedCountryId = '';

        foreach ($countries as $country) {
            $componentData[] = [
                'value' => (string) $country['id'],
                'label' => '+' . $country['code'] . ' ' . $country['name']
            ];
        }

        return renderAutocompleteSelectComponent([
            'id' => 'dashboard-mobile-calls-country-code-select',
            'hidden_class' => 'dashboard_mobile_calls1_country_code',
            'wrapper_class' => 'dashboard_mobile_calls1_country_code_autocomplete',
            'placeholder' => $translation['text141'],
            'selected_value' => $selectedCountryId,
            'options' => $componentData
        ]) . $this->getCountryCodeSelectScript();
    }

    /**
     * Скрипт для country code select
     */
    private function getCountryCodeSelectScript()
    {
        return '<script>
            $(function() {
                if (window.initAutocompleteSelectComponent) {
                    window.initAutocompleteSelectComponent("#dashboard-mobile-calls-country-code-select");
                }

                $(".dashboard_mobile_calls1_country_code").off("change.mobileCallsCountryCode").on("change.mobileCallsCountryCode", function() {
                    var formData = new FormData();
                    formData.append("op", "saveTeamTextField");
                    formData.append("field", "mobile_calls_country_id");
                    formData.append("val", $(this).val());

                    $.ajax({
                        url: "/ajax/ajax.php",
                        type: "POST",
                        dataType: "json",
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        success: function(json) {
                            if (json.country_lang) {
                                var message = {
                                    "op": "databaseMobileCallsUpdateCountryCode",
                                    "parameters": {
                                        "country_lang": json.country_lang,
                                        "user_id": $("#section_game").length ? $("#section_game").attr("data-user-id") : 0,
                                        "team_id": $("#section_game").length ? $("#section_game").attr("data-team-id") : 0
                                    }
                                };
                                sendMessageSocket(JSON.stringify(message));
                            }
                        }
                    });
                });
            });
        </script>';
    }

    /**
     * Получение иконки Mobile Calls
     */
    private function getMobileCallsIcon()
    {
        return '<svg width="22" height="25" viewBox="0 0 22 25" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M4.04297 13.0766C3.95284 13.0766 3.8664 13.0408 3.80266 12.9771C3.73893 12.9133 3.70312 12.8269 3.70312 12.7368V2.41797C3.70312 1.90722 3.90602 1.41739 4.26717 1.05624C4.62833 0.695082 5.11816 0.492188 5.62891 0.492188H15.3711C15.8818 0.492188 16.3717 0.695082 16.7328 1.05624C17.094 1.41739 17.2969 1.90722 17.2969 2.41797V6.14945C17.2969 6.23959 17.2611 6.32603 17.1973 6.38976C17.1336 6.45349 17.0472 6.4893 16.957 6.4893C16.8669 6.4893 16.7805 6.45349 16.7167 6.38976C16.653 6.32603 16.6172 6.23959 16.6172 6.14945V2.41797C16.6172 2.08748 16.4859 1.77054 16.2522 1.53685C16.0185 1.30316 15.7016 1.17188 15.3711 1.17188H5.62891C5.29842 1.17188 4.98147 1.30316 4.74778 1.53685C4.5141 1.77054 4.38281 2.08748 4.38281 2.41797V12.7368C4.38281 12.8269 4.34701 12.9133 4.28327 12.9771C4.21954 13.0408 4.1331 13.0766 4.04297 13.0766Z" fill="#00F0FF"/>
            <path d="M15.3711 24.5078H5.62891C5.11816 24.5078 4.62833 24.3049 4.26717 23.9437C3.90602 23.5826 3.70312 23.0927 3.70313 22.582V17.7211C3.70313 17.631 3.73893 17.5445 3.80266 17.4808C3.8664 17.4171 3.95284 17.3812 4.04297 17.3812C4.1331 17.3812 4.21954 17.4171 4.28327 17.4808C4.34701 17.5445 4.38281 17.631 4.38281 17.7211V22.582C4.38281 22.9125 4.5141 23.2294 4.74778 23.4631C4.98147 23.6968 5.29842 23.8281 5.62891 23.8281H15.3711C15.7016 23.8281 16.0185 23.6968 16.2522 23.4631C16.4859 23.2294 16.6172 22.9125 16.6172 22.582V14.0791C16.6172 13.989 16.653 13.9025 16.7167 13.8388C16.7805 13.7751 16.8669 13.7393 16.957 13.7393C17.0472 13.7393 17.1336 13.7751 17.1973 13.8388C17.2611 13.9025 17.2969 13.989 17.2969 14.0791V22.582C17.2969 23.0927 17.094 23.5826 16.7328 23.9437C16.3717 24.3049 15.8818 24.5078 15.3711 24.5078Z" fill="#00F0FF"/>
        </svg>';
    }

    /**
     * Форматирование номера телефона
     */
    private function formatPhoneNumber($number)
    {
        if (empty($number) || is_null($number) || $number == 'NULL') {
            return '';
        }
        return htmlspecialchars($number, ENT_QUOTES);
    }

    /**
     * Получение информации пользователя для Mobile Calls
     */
    private function getUserMobileCallsInfo($team_id)
    {
        if (isset($_COOKIE['hash'])) {
            $sql = "SELECT `mobile_calls_print_messages` FROM `users` WHERE `team_id` = {?} AND `hash` = {?} LIMIT 1";
            return $this->db->selectRow($sql, [$team_id, $_COOKIE['hash']]);
        } else {
            $sql = "SELECT `mobile_calls_print_messages` FROM `users` WHERE `team_id` = {?} AND `ip` = {?} LIMIT 1";
            return $this->db->selectRow($sql, [$team_id, $this->getIp()]);
        }
    }

    /**
     * Обработка первого запуска Mobile Calls
     */
    private function processMobileCallsFirstRun($team_info, $team_id, $lang_id)
    {
        if (empty($team_info['mobile_calls_print_messages'])) {
            // Обновляем значение, что блок выведен
            $sql = "UPDATE `teams` SET `mobile_calls_print_messages` = {?} WHERE `id` = {?}";
            $this->db->query($sql, [1, $team_id]);

            // Обновляем подсказки
            $this->updateMobileCallsHints($team_id, $lang_id);
        }
    }

    /**
     * Обновление подсказок для Mobile Calls
     */
    private function updateMobileCallsHints($team_id, $lang_id)
    {
        $active_hints = [];
        $list_hints = [];

        $hints_by_step = $this->getHintsByStep('mobile_calls', $lang_id);
        if ($hints_by_step) {
            foreach ($hints_by_step as $hint) {
                $list_hints[] = $hint['id'];
            }
        }

        $sql = "UPDATE `teams` SET `active_hints` = {?}, `list_hints` = {?}, `list_hints_title_lang_var` = {?}, `list_hints_text_lang_var` = {?} WHERE `id` = {?}";
        $this->db->query($sql, [
            json_encode($active_hints, JSON_UNESCAPED_UNICODE),
            json_encode($list_hints, JSON_UNESCAPED_UNICODE),
            'text44',
            'text45',
            $team_id
        ]);
    }

    /**
     * Обновление статуса пользователя для Mobile Calls
     */
    private function updateUserMobileCallsStatus($team_id)
    {
        if (isset($_COOKIE['hash'])) {
            $sql = "UPDATE `users` SET `mobile_calls_print_messages` = {?} WHERE `team_id` = {?} AND `hash` = {?}";
            $this->db->query($sql, [1, $team_id, $_COOKIE['hash']]);
        } else {
            $sql = "UPDATE `users` SET `mobile_calls_print_messages` = {?} WHERE `team_id` = {?} AND `ip` = {?}";
            $this->db->query($sql, [1, $team_id, $this->getIp()]);
        }
    }
}
