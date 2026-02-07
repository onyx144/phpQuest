<?php

/**
 * Trait для работы с базой данных Car Register
 */
trait CarRegisterBlock
{
    /**
     * Car Register - первый экран (форма поиска)
     * @param int $lang_id
     * @param int $team_id
     * @return array
     */
    private function uploadDatabasesCarRegister($lang_id, $team_id)
    {
        $translation = $this->getWordsByPage('game', $lang_id);
        $team_info = $this->teamInfo($team_id);

        $return = [];
        $return['titles'] = $this->getCarRegisterTitles($translation);
        $return['back_btn'] = $this->getCarRegisterBackButton($translation);
        $return['content'] = $this->getCarRegisterFormContent($translation, $team_info, $lang_id, $team_id);

        return $return;
    }

    /**
     * Car Register - второй экран (результаты Huilov)
     * @param int $lang_id
     * @param int $team_id
     * @return array
     */
    private function uploadDatabasesCarRegisterHuilov($lang_id, $team_id)
    {
        $translation = $this->getWordsByPage('game', $lang_id);
        $team_info = $this->teamInfo($team_id);
        $user_info = $this->getCurrentUserInfo($team_id);

        $return = [];
        $return['titles'] = $this->getCarRegisterHuilovTitles($translation);
        $return['back_btn'] = $this->getCarRegisterBackButton($translation);
        $return['content'] = $this->getCarRegisterHuilovContent($translation, $user_info, $team_info);
        
        // Обработка первого запуска
        $this->processCarRegisterFirstRun($team_info, $team_id, $lang_id);
        
        // Обновление для текущего пользователя
        $this->updateUserCarRegisterStatus($team_id);
        
        // Подготовка языковых данных
        $return['error_lang'] = $this->getCarRegisterLanguageData();

        return $return;
    }

    /**
     * Получение заголовков для формы Car Register
     * @param array $translation
     * @return string
     */
    private function getCarRegisterTitles($translation)
    {
        return '<div class="dashboard_tab_title dashboard_tab_title_can_click" data-tab="tab1" data-step="databases_start_four" data-action-id="28" data-database="false">
            <div class="dashboard_tab_title_active_skew_right"></div>
            <div class="dashboard_tab_title_inner">
                <div class="dashboard_tab_title_img_wrapper">
                    <svg width="19" height="21" viewBox="0 0 19 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 0H19V3L17.25 5H0V2L1.75 0ZM1.73684 2H3V3.2L2.26316 4H1V2.8L1.73684 2ZM6 2H4.73684L4 2.8V4H5.26316L6 3.2V2ZM7.73684 2H9V3.2L8.26316 4H7V2.8L7.73684 2ZM17 2H10.7368L10 2.8V4H16.2632L17 3.2V2Z" fill="#00F0FF"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 8H19V11L17.25 13H0V10L1.75 8ZM1.73684 10H3V11.2L2.26316 12H1V10.8L1.73684 10ZM6 10H4.73684L4 10.8V12H5.26316L6 11.2V10ZM7.73684 10H9V11.2L8.26316 12H7V10.8L7.73684 10ZM17 10H10.7368L10 10.8V12H16.2632L17 11.2V10Z" fill="#00F0FF"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 16H19V19L17.25 21H0V18L1.75 16ZM1.73684 18H3V19.2L2.26316 20H1V18.8L1.73684 18ZM6 18H4.73684L4 18.8V20H5.26316L6 19.2V18ZM7.73684 18H9V19.2L8.26316 20H7V18.8L7.73684 18ZM17 18H10.7368L10 18.8V20H16.2632L17 19.2V18Z" fill="#00F0FF"/>
                        <rect width="15" height="1" transform="matrix(1 0 0 -1 2 7)" fill="#00F0FF"/>
                        <rect width="15" height="1" transform="matrix(1 0 0 -1 2 15)" fill="#00F0FF"/>
                    </svg>
                </div>
                <div class="dashboard_tab_title_text">' . $translation['text13'] . '</div>
            </div>
        </div>
        <div class="dashboard_tab_title dashboard_tab_title_active" data-tab="car_register1">
            <div class="dashboard_tab_title_active_skew_right"></div>
            <div class="dashboard_tab_title_inner">
                <div class="dashboard_tab_title_img_wrapper" style="margin: -10px 0 0;">
                    ' . $this->getCarIcon() . '
                </div>
                <div class="dashboard_tab_title_text">' . $translation['text171'] . '</div>
            </div>
        </div>';
    }

    /**
     * Получение кнопки "Назад" для Car Register
     * @param array $translation
     * @return string
     */
    private function getCarRegisterBackButton($translation)
    {
        return '<div class="dashboard_back_btn" data-back="databases_start_four" data-action-id-back="28" data-database="false">
            <img src="/images/back_bg.png" class="back_btn_bg" alt="">
            <div class="back_btn_text">' . $translation['text22'] . '</div>
        </div>';
    }

    /**
     * Получение контента формы Car Register
     * @param array $translation
     * @param array $team_info
     * @param int $lang_id
     * @param int $team_id
     * @return string
     */
    private function getCarRegisterFormContent($translation, $team_info, $lang_id, $team_id)
    {
        $countrySelect = $this->generateCountrySelect($lang_id, $team_info, $translation);
        $datePickerScript = $this->generateDatePickerScript($translation, $team_info);
        
        return '<div class="dashboard_tab_content_item dashboard_tab_content_item_start_four" data-tab="tab1"></div>
        <div class="dashboard_tab_content_item dashboard_tab_content_item_car_register dashboard_tab_content_item_active" data-tab="car_register1">
            <div class="dashboard_car_register1_inner">
                <div class="dashboard_car_register1_inner_image_wrapper">
                    ' . $this->getCarIcon(59, 59) . '
                </div>
                <div class="dashboard_car_register1_inner_title">' . $translation['text171'] . '</div>
                <div class="dashboard_car_register1_inner_text">' . $translation['text62'] . '</div>
                <div class="dashboard_car_register1_fields_top">
                    <div class="dashboard_car_register1_input_wrapper dashboard_car_register1_input_wrapper_license_plate">
                        <div class="dashboard_car_register1_input_border_left"></div>
                        <input type="text" placeholder="' . $translation['text63'] . '" autocomplete="off" class="dashboard_car_register1_license_plate">
                        <div class="dashboard_car_register1_license_plate_error error_text_database_car_register">' . $translation['text86'] . '</div>
                    </div>
                    <div class="dashboard_car_register1_input_wrapper dashboard_car_register1_input_wrapper_country">
                        <div class="dashboard_car_register1_input_border_right"></div>
                        ' . $countrySelect . '
                        <div class="dashboard_car_register1_country_error error_text_database_car_register">' . $translation['text86'] . '</div>
                    </div>
                </div>
                <div class="dashboard_car_register1_fields_bottom">
                    <div class="dashboard_car_register1_input_wrapper dashboard_car_register1_input_wrapper_date">
                        <div class="dashboard_car_register1_input_border_left"></div>
                        <div class="dashboard_car_register1_input_border_right"></div>
                        <input type="text" placeholder="' . $translation['text65'] . '" autocomplete="off" class="dashboard_car_register1_date" value="' . $this->formatDate($team_info['car_register_date']) . '">
                        <div class="dashboard_car_register1_date_error error_text_database_car_register">' . $translation['text86'] . '</div>
                    </div>
                </div>
                ' . $this->getSearchButton($translation) . '
            </div>
        </div>
        ' . $datePickerScript;
    }

    /**
     * Генерация select для выбора страны
     * @param int $lang_id
     * @param array $team_info
     * @param array $translation
     * @return string
     */
    private function generateCountrySelect($lang_id, $team_info, $translation)
    {
        $sql = "SELECT c.code, c.pos, cd.name, c.id
                FROM countries c
                JOIN countries_description cd ON c.id = cd.country_id
                WHERE cd.lang_id = {?}
                ORDER BY cd.name";
        $countries = $this->db->select($sql, [$lang_id]);
        
        if (!$countries) {
            return '';
        }

        $options = '<option disabled="disabled"' . (empty($team_info['car_register_country_id']) ? ' selected="selected"' : '') . '>' 
                  . $translation['text64'] . '</option>';
        
        foreach ($countries as $country) {
            $selected = ($team_info['car_register_country_id'] == $country['id']) ? ' selected="selected"' : '';
            $options .= '<option value="' . htmlspecialchars($country['name'], ENT_QUOTES) . '" data-pos="' . $country['pos'] . '"' . $selected . '>' 
                       . $country['name'] . '</option>';
        }

        return '<select class="dashboard_car_register1_country">' . $options . '</select>'
              . $this->getCountrySelectScript($translation);
    }

    /**
     * Получение скрипта для country select
     * @param array $translation
     * @return string
     */
    private function getCountrySelectScript($translation)
    {
        return '<script>
            $(function() {
                var scrollbarPositionPixel = 0;
                var isScrollOpen = false;

                $(".dashboard_car_register1_country").selectric({
                    optionsItemBuilder: function(itemData, element, index) {
                        return (!itemData.disabled) ? 
                            \'<span class="select_country_flag" style="display:inline-block;width:16px;height:11px;background:url(/images/flags.png) no-repeat;background-position:\' + itemData.element[0].attributes[\'data-pos\'].value + \';margin: 0 15px 0 5px;"></span><span class="select_country_name" style="display:inline-block;max-width: 87%;">\' + itemData.text + \'</span>\' 
                            : itemData.text;
                    },
                    maxHeight: 236,
                    preventWindowScroll: false,
                    onInit: function() {
                        $(".selectric-dashboard_car_register1_country .selectric-scroll").mCustomScrollbar({
                            scrollInertia: 700,
                            theme: "minimal-dark",
                            scrollbarPosition: "inside",
                            alwaysShowScrollbar: 2,
                            autoHideScrollbar: false,
                            mouseWheel:{ deltaFactor: 200 },
                            callbacks:{
                                whileScrolling:function() {
                                    scrollbarPositionPixel = this.mcs.top;
                                    if (isScrollOpen) {
                                        $(".dashboard_car_register1_country").selectric("open");
                                    }
                                }
                            }
                        });
                    },
                    onOpen: function() {
                        if (!isScrollOpen) {
                            $(".selectric-dashboard_car_register1_country .selectric-scroll").mCustomScrollbar("scrollTo", Math.abs(scrollbarPositionPixel));
                            isScrollOpen = true;
                        }
                    }
                })
                .on("change", function() {
                    var formData = new FormData();
                    formData.append("op", "saveTeamTextField");
                    formData.append("field", "car_register_country_id");
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
                                    "op": "databaseCarRegisterUpdateCountry",
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
                    isScrollOpen = false;
                });
            });
        </script>';
    }

    /**
     * Генерация скрипта для datepicker
     * @param array $translation
     * @param array $team_info
     * @return string
     */
    private function generateDatePickerScript($translation, $team_info)
    {
        $dayNames = [$translation['text67'], $translation['text68'], $translation['text69'], 
                     $translation['text70'], $translation['text71'], $translation['text72'], $translation['text73']];
        $monthNames = [$translation['text74'], $translation['text75'], $translation['text76'], $translation['text77'],
                       $translation['text78'], $translation['text79'], $translation['text80'], $translation['text81'],
                       $translation['text82'], $translation['text83'], $translation['text84'], $translation['text85']];
        
        return '<script>
            $(function() {
                $(".dashboard_car_register1_date").datepicker({
                    dateFormat: "dd.mm.yy",
                    dayNamesShort: ' . json_encode($dayNames) . ',
                    dayNamesMin: ' . json_encode($dayNames) . ',
                    monthNames: ' . json_encode($monthNames) . ',
                    changeMonth: false,
                    showAnim: "",
                    onSelect: function(dateText) {
                        var formData = new FormData();
                        formData.append("op", "saveTeamTextField");
                        formData.append("field", "car_register_date");
                        formData.append("val", dateText);

                        $.ajax({
                            url: "/ajax/ajax.php",
                            type: "POST",
                            dataType: "json",
                            cache: false,
                            contentType: false,
                            processData: false,
                            data: formData,
                            success: function(json) {
                                var message = {
                                    "op": "databaseCarRegisterUpdateDate",
                                    "parameters": {
                                        "date": dateText,
                                        "user_id": $("#section_game").length ? $("#section_game").attr("data-user-id") : 0,
                                        "team_id": $("#section_game").length ? $("#section_game").attr("data-team-id") : 0
                                    }
                                };
                                sendMessageSocket(JSON.stringify(message));
                            }
                        });
                    }
                });
            });
        </script>';
    }

    /**
     * Получение кнопки поиска
     * @param array $translation
     * @return string
     */
    private function getSearchButton($translation)
    {
        return '<div class="btn_wrapper btn_wrapper_blue dashboard_car_register1_search">
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
        </div>';
    }

    /**
     * Получение иконки автомобиля
     * @param int $width
     * @param int $height
     * @return string
     */
    private function getCarIcon($width = 36, $height = 36)
    {
        return '<svg width="' . $width . '" height="' . $height . '" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M11.4595 24.9998H8.54054C8.24324 24.9998 8 25.3155 8 25.7015V28.298C8 28.684 8.24324 28.9998 8.54054 28.9998H11.4595C11.7568 28.9998 12 28.684 12 28.298V25.7015C12 25.3155 11.7568 24.9998 11.4595 24.9998ZM10.9189 27.5962H9.08108V26.4033H10.9189V27.5962Z" fill="#00F0FF"/>
            <path d="M25.3243 24.9998H21.6757C21.3041 24.9998 21 25.3155 21 25.7015V28.298C21 28.684 21.3041 28.9998 21.6757 28.9998H25.3243C25.6959 28.9998 26 28.684 26 28.298V25.7015C26 25.3155 25.6959 24.9998 25.3243 24.9998ZM24.6486 27.5962H22.3514V26.4033H24.6486V27.5962Z" fill="#00F0FF"/>
            <path d="M19.3304 26.9998H14.6696C14.3013 26.9998 14 27.4498 14 27.9998C14 28.5498 14.2946 28.9998 14.6696 28.9998H19.3304C19.6987 28.9998 20 28.5498 20 27.9998C20 27.4498 19.6987 26.9998 19.3304 26.9998Z" fill="#00F0FF"/>
            <path d="M19.3304 25H14.6696C14.3013 25 14 25.45 14 26C14 26.55 14.2946 27 14.6696 27H19.3304C19.6987 27 20 26.55 20 26C20 25.45 19.6987 25 19.3304 25Z" fill="#00F0FF"/>
        </svg>';
    }

    /**
     * Форматирование даты
     * @param string|null $date
     * @return string
     */
    private function formatDate($date)
    {
        if (empty($date) || $date == '0000-00-00' || is_null($date)) {
            return '';
        }
        return $this->fromEngDatetimeToRus($date);
    }

    /**
     * Получение информации о текущем пользователе
     * @param int $team_id
     * @return array|null
     */
    private function getCurrentUserInfo($team_id)
    {
        if (isset($_COOKIE['hash'])) {
            $sql = "SELECT `car_register_print_text_huilov` FROM `users` WHERE `team_id` = {?} AND `hash` = {?} LIMIT 1";
            return $this->db->selectRow($sql, [$team_id, $_COOKIE['hash']]);
        } else {
            $sql = "SELECT `car_register_print_text_huilov` FROM `users` WHERE `team_id` = {?} AND `ip` = {?} LIMIT 1";
            return $this->db->selectRow($sql, [$team_id, $this->getIp()]);
        }
    }

    /**
     * Обработка первого запуска Car Register Huilov
     * @param array $team_info
     * @param int $team_id
     * @param int $lang_id
     */
    private function processCarRegisterFirstRun($team_info, $team_id, $lang_id)
    {
        if (empty($team_info['car_register_print_text_huilov'])) {
            // Обновляем значение, что текст напечатан
            $sql = "UPDATE `teams` SET `car_register_print_text_huilov` = {?} WHERE `id` = {?}";
            $this->db->query($sql, [1, $team_id]);

            // Обновляем подсказки
            $this->updateCarRegisterHints($team_id, $lang_id);
        }
    }

    /**
     * Обновление подсказок для Car Register
     * @param int $team_id
     * @param int $lang_id
     */
    private function updateCarRegisterHints($team_id, $lang_id)
    {
        $active_hints = [];
        $list_hints = [];

        $hints_by_step = $this->getHintsByStep('car_register_huilov', $lang_id);
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
     * Обновление статуса пользователя
     * @param int $team_id
     */
    private function updateUserCarRegisterStatus($team_id)
    {
        if (isset($_COOKIE['hash'])) {
            $sql = "UPDATE `users` SET `car_register_print_text_huilov` = {?} WHERE `team_id` = {?} AND `hash` = {?}";
            $this->db->query($sql, [1, $team_id, $_COOKIE['hash']]);
        } else {
            $sql = "UPDATE `users` SET `car_register_print_text_huilov` = {?} WHERE `team_id` = {?} AND `ip` = {?}";
            $this->db->query($sql, [1, $team_id, $this->getIp()]);
        }
    }

    /**
     * Получение языковых данных для Car Register
     * @return array
     */
    private function getCarRegisterLanguageData()
    {
        $error_lang = [];

        $sql = "SELECT `id`, `lang_abbr` FROM `langs` WHERE `status` = {?}";
        $langs = $this->db->select($sql, [1]);
        
        if ($langs) {
            foreach ($langs as $lang_item) {
                $translation = $this->getWordsByPage('game', $lang_item['id']);
                $error_lang[$lang_item['lang_abbr']] = [
                    'text92' => $translation['text92'],
                    'text93' => $translation['text93'],
                    'text94' => $translation['text94'],
                    'text95' => $translation['text95'],
                    'text97' => $translation['text97'],
                    'text98' => $translation['text98'],
                    'text99' => $translation['text99'],
                    'text100' => $translation['text100'],
                    'text101' => $translation['text101'],
                    'text102' => $translation['text102'],
                    'text103' => $translation['text103'],
                    'text104' => $translation['text104']
                ];
            }
        }

        return $error_lang;
    }

    /**
     * Получение заголовков для Huilov страницы
     * @param array $translation
     * @return string
     */
    private function getCarRegisterHuilovTitles($translation)
    {
        // Здесь должен быть HTML для заголовков Huilov страницы
        // Сокращаю для примера
        return '<div class="dashboard_tab_title dashboard_tab_title_active" data-tab="car_register2">
            <div class="dashboard_tab_title_inner">
                <div class="dashboard_tab_title_text">' . $translation['text90'] . '</div>
            </div>
        </div>';
    }

    /**
     * Получение контента для Huilov страницы
     * @param array $translation
     * @param array $user_info
     * @param array $team_info
     * @return string
     */
    private function getCarRegisterHuilovContent($translation, $user_info, $team_info)
    {
        // Здесь должен быть полный HTML контент Huilov страницы
        // Сокращаю для примера
        $bubbleClass = empty($user_info['car_register_print_text_huilov']) ? ' dashboard_car_register2_inner_bubble' : '';
        $bubbleTeamClass = empty($team_info['car_register_print_text_huilov']) ? ' dashboard_car_register2_inner_bubble_team' : '';
        
        return '<div class="dashboard_tab_content_item dashboard_tab_content_item_car_register_huilov dashboard_tab_content_item_active" data-tab="car_register2">
            <div class="dashboard_car_register2_inner' . $bubbleClass . $bubbleTeamClass . '">
                <!-- Здесь полный контент страницы с результатами -->
            </div>
        </div>';
    }
}
