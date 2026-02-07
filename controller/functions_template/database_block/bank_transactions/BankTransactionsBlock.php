<?php

/**
 * Trait для работы с базой данных Bank Transactions
 */
trait BankTransactionsBlock
{
    /**
     * Bank Transactions - первый экран (форма поиска)
     * @param int $lang_id
     * @param int $team_id
     * @return array
     */
    private function uploadDatabasesBankTransactions($lang_id, $team_id)
    {
        $translation = $this->getWordsByPage('game', $lang_id);
        $team_info = $this->teamInfo($team_id);

        // Проверяем доступ к базе данных
        $list_databases = json_decode($team_info['list_databases'], true);
        if (!in_array('bank_transactions', $list_databases)) {
            return $this->uploadDatabasesNoAccess($lang_id, 'text61', true);
        }

        $return = [];

        $return['titles'] = $this->getBankTransactionsTitles($translation);
        $return['back_btn'] = $this->getBankTransactionsBackButton($translation);
        $return['content'] = $this->getBankTransactionsSearchForm($translation, $team_info, $lang_id, $team_id);

        return $return;
    }

    /**
     * Bank Transactions - второй экран (результаты)
     * @param int $lang_id
     * @param int $team_id
     * @return array
     */
    private function uploadDatabasesBankTransactionsSuccess($lang_id, $team_id)
    {
        $translation = $this->getWordsByPage('game', $lang_id);
        $team_info = $this->teamInfo($team_id);

        $return = [];

        $return['titles'] = $this->getBankTransactionsSuccessTitles($translation);
        $return['back_btn'] = $this->getBankTransactionsBackButton($translation);
        $return['content'] = $this->getBankTransactionsResultsContent($translation);

        return $return;
    }

    // ==================== HELPER METHODS ====================

    /**
     * Получение заголовков для Bank Transactions
     */
    private function getBankTransactionsTitles($translation)
    {
        return '<div class="dashboard_tab_title dashboard_tab_title_can_click" data-tab="tab1" data-step="databases_start_four" data-database="false">
                    <div class="dashboard_tab_title_active_skew_right"></div>
                    <div class="dashboard_tab_title_inner">
                        <div class="dashboard_tab_title_img_wrapper">
                            <svg width="19" height="21" viewBox="0 0 19 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 0H19V3L17.25 5H0V2L1.75 0ZM1.73684 2H3V3.2L2.26316 4H1V2.8L1.73684 2ZM6 2H4.73684L4 2.8V4H5.26316L6 3.2V2ZM7.73684 2H9V3.2L8.26316 4H7V2.8L7.73684 2ZM17 2H10.7368L10 2.8V4H16.2632L17 3.2V2Z" fill="#00F0FF"/><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 8H19V11L17.25 13H0V10L1.75 8ZM1.73684 10H3V11.2L2.26316 12H1V10.8L1.73684 10ZM6 10H4.73684L4 10.8V12H5.26316L6 11.2V10ZM7.73684 10H9V11.2L8.26316 12H7V10.8L7.73684 10ZM17 10H10.7368L10 10.8V12H16.2632L17 11.2V10Z" fill="#00F0FF"/><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 16H19V19L17.25 21H0V18L1.75 16ZM1.73684 18H3V19.2L2.26316 20H1V18.8L1.73684 18ZM6 18H4.73684L4 18.8V20H5.26316L6 19.2V18ZM7.73684 18H9V19.2L8.26316 20H7V18.8L7.73684 18ZM17 18H10.7368L10 18.8V20H16.2632L17 19.2V18Z" fill="#00F0FF"/><rect width="15" height="1" transform="matrix(1 0 0 -1 2 7)" fill="#00F0FF"/><rect width="15" height="1" transform="matrix(1 0 0 -1 2 15)" fill="#00F0FF"/></svg>
                        </div>
                        <div class="dashboard_tab_title_text">' . $translation['text13'] . '</div>
                    </div>
                </div>
                <div class="dashboard_tab_title dashboard_tab_title_active" data-tab="bank_transactions1">
                    <div class="dashboard_tab_title_active_skew_right"></div>
                    <div class="dashboard_tab_title_inner">
                        <div class="dashboard_tab_title_img_wrapper">
                            ' . $this->getBankTransactionsIcon() . '
                        </div>
                        <div class="dashboard_tab_title_text">' . $translation['text60'] . '</div>
                    </div>
                </div>';
    }

    /**
     * Получение заголовков для страницы с результатами
     */
    private function getBankTransactionsSuccessTitles($translation)
    {
        return '<div class="dashboard_tab_title dashboard_tab_title_can_click" data-tab="tab1" data-step="databases_start_four" data-database="false">
                    <div class="dashboard_tab_title_active_skew_right"></div>
                    <div class="dashboard_tab_title_inner">
                        <div class="dashboard_tab_title_img_wrapper">
                            <svg width="19" height="21" viewBox="0 0 19 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 0H19V3L17.25 5H0V2L1.75 0ZM1.73684 2H3V3.2L2.26316 4H1V2.8L1.73684 2ZM6 2H4.73684L4 2.8V4H5.26316L6 3.2V2ZM7.73684 2H9V3.2L8.26316 4H7V2.8L7.73684 2ZM17 2H10.7368L10 2.8V4H16.2632L17 3.2V2Z" fill="#00F0FF"/><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 8H19V11L17.25 13H0V10L1.75 8ZM1.73684 10H3V11.2L2.26316 12H1V10.8L1.73684 10ZM6 10H4.73684L4 10.8V12H5.26316L6 11.2V10ZM7.73684 10H9V11.2L8.26316 12H7V10.8L7.73684 10ZM17 10H10.7368L10 10.8V12H16.2632L17 11.2V10Z" fill="#00F0FF"/><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 16H19V19L17.25 21H0V18L1.75 16ZM1.73684 18H3V19.2L2.26316 20H1V18.8L1.73684 18ZM6 18H4.73684L4 18.8V20H5.26316L6 19.2V18ZM7.73684 18H9V19.2L8.26316 20H7V18.8L7.73684 18ZM17 18H10.7368L10 18.8V20H16.2632L17 19.2V18Z" fill="#00F0FF"/><rect width="15" height="1" transform="matrix(1 0 0 -1 2 7)" fill="#00F0FF"/><rect width="15" height="1" transform="matrix(1 0 0 -1 2 15)" fill="#00F0FF"/></svg>
                        </div>
                        <div class="dashboard_tab_title_text">' . $translation['text13'] . '</div>
                    </div>
                </div>
                <div class="dashboard_tab_title" data-tab="bank_transactions1">
                    <div class="dashboard_tab_title_active_skew_right"></div>
                    <div class="dashboard_tab_title_inner">
                        <div class="dashboard_tab_title_img_wrapper">
                            ' . $this->getBankTransactionsIcon() . '
                        </div>
                        <div class="dashboard_tab_title_text">' . $translation['text60'] . '</div>
                    </div>
                </div>
                <div class="dashboard_tab_title dashboard_tab_title_active" data-tab="bank_transactions2">
                    <div class="dashboard_tab_title_active_skew_right"></div>
                    <div class="dashboard_tab_title_inner">
                        <div class="dashboard_tab_title_img_wrapper">
                            ' . $this->getCardIcon() . '
                        </div>
                        <div class="dashboard_tab_title_text">Visa *5684</div>
                    </div>
                </div>';
    }

    /**
     * Получение кнопки "Назад" для Bank Transactions
     */
    private function getBankTransactionsBackButton($translation)
    {
        return '<div class="dashboard_back_btn" data-back="databases_start_four" data-database="false">
                    <img src="/images/back_bg.png" class="back_btn_bg" alt="">
                    <div class="back_btn_text">' . $translation['text22'] . '</div>
                </div>';
    }

    /**
     * Форма поиска Bank Transactions
     */
    private function getBankTransactionsSearchForm($translation, $team_info, $lang_id, $team_id)
    {
        $datePickerScript = $this->generateBankTransactionsDatePickerScript($translation, $team_info);

        return '<div class="dashboard_tab_content_item dashboard_tab_content_item_start_four" data-tab="tab1"></div>
                <div class="dashboard_tab_content_item dashboard_tab_content_item_bank_transactions dashboard_tab_content_item_active" data-tab="bank_transactions1">
                    <div class="dashboard_bank_transactions1_inner">
                        <div class="dashboard_bank_transactions1_inner_image_wrapper">
                            ' . $this->getBankTransactionsIcon(58, 68) . '
                        </div>
                        <div class="dashboard_car_register1_inner_title">' . $translation['text209'] . '</div>
                        <div class="dashboard_car_register1_inner_text">' . $translation['text210'] . '</div>
                        <div class="dashboard_car_register1_fields_top dashboard_dashboard_bank_transactions1_fields_top">
                            <div class="dashboard_car_register1_input_wrapper dashboard_bank_transactions1_input_wrapper_digits">
                                <div class="dashboard_car_register1_input_border_left"></div>
                                <input type="text" placeholder="' . $translation['text211'] . '" autocomplete="off" class="dashboard_bank_transactions1_digits">
                                <div class="dashboard_bank_transactions1_digits_error error_text_database_car_register">' . $translation['text86'] . '</div>
                            </div>
                            <div class="dashboard_car_register1_input_wrapper dashboard_bank_transactions1_input_wrapper_amount">
                                <div class="dashboard_car_register1_input_border_right"></div>
                                <input type="text" placeholder="' . $translation['text212'] . '" autocomplete="off" class="dashboard_bank_transactions1_amount">
                                <div class="dashboard_bank_transactions1_amount_error error_text_database_car_register">' . $translation['text86'] . '</div>
                            </div>
                        </div>
                        <div class="dashboard_car_register1_fields_bottom">
                            <div class="dashboard_car_register1_input_wrapper dashboard_bank_transactions1_input_wrapper_date">
                                <div class="dashboard_car_register1_input_border_left"></div>
                                <div class="dashboard_car_register1_input_border_right"></div>
                                <input type="text" placeholder="' . $translation['text213'] . '" autocomplete="off" class="dashboard_bank_transactions1_date" value="' . $this->formatBankTransactionsDate($team_info['bank_transactions_date']) . '">
                                <div class="dashboard_bank_transactions1_date_error error_text_database_car_register">' . $translation['text86'] . '</div>
                            </div>
                        </div>
                        <div class="btn_wrapper btn_wrapper_blue dashboard_bank_transactions1_search">
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
                </div>
                ' . $datePickerScript;
    }

    /**
     * Контент с результатами Bank Transactions
     */
    private function getBankTransactionsResultsContent($translation)
    {
        $transactions = [
            ['desc' => 'text220', 'debit' => '95,00', 'credit' => ''],
            ['desc' => 'text221', 'debit' => '', 'credit' => '500,00'],
            ['desc' => 'text222', 'debit' => '2 402 000,00', 'credit' => ''],
            ['desc' => 'text223', 'debit' => '100 000,00', 'credit' => ''],
            ['desc' => 'text224', 'debit' => '410,00', 'credit' => ''],
            ['desc' => 'text225', 'debit' => '154,70', 'credit' => ''],
            ['desc' => 'text226', 'debit' => '52,00', 'credit' => ''],
        ];

        $transactionRows = '';
        foreach ($transactions as $transaction) {
            $transactionRows .= '<div class="dashboard_bank_transactions2_table_tr">
                <div class="dashboard_bank_transactions2_table_td">' . $translation[$transaction['desc']] . '</div>
                <div class="dashboard_bank_transactions2_table_td">' . $transaction['debit'] . '</div>
                <div class="dashboard_bank_transactions2_table_td">' . $transaction['credit'] . '</div>
            </div>';
        }

        return '<div class="dashboard_tab_content_item dashboard_tab_content_item_bank_transactions dashboard_tab_content_item_active" data-tab="bank_transactions2">
                    <div class="dashboard_bank_transactions2_inner">
                        <div class="dashboard_bank_transactions2_inner_title">' . $translation['text60'] . '</div>
                        <div class="dashboard_bank_transactions2_inner_text">' . $translation['text215'] . '</div>
                        <div class="dashboard_bank_transactions2_table_wrapper">
                            <div class="dashboard_bank_transactions2_table_title">' . $translation['text216'] . ' ' . date('d.m.Y') . '</div>
                            <div class="dashboard_bank_transactions2_table_thead">
                                <div class="dashboard_bank_transactions2_table_tr">
                                    <div class="dashboard_bank_transactions2_table_td">' . $translation['text217'] . '</div>
                                    <div class="dashboard_bank_transactions2_table_td">' . $translation['text218'] . '</div>
                                    <div class="dashboard_bank_transactions2_table_td">' . $translation['text219'] . '</div>
                                </div>
                            </div>
                            <div class="dashboard_bank_transactions2_table">
                                <div class="dashboard_bank_transactions2_table_tbody">
                                    ' . $transactionRows . '
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
    }

    /**
     * Генерация скрипта datepicker для Bank Transactions
     */
    private function generateBankTransactionsDatePickerScript($translation, $team_info)
    {
        $dayNames = [
            $translation['text67'], $translation['text68'], $translation['text69'],
            $translation['text70'], $translation['text71'], $translation['text72'], $translation['text73']
        ];

        $monthNames = [
            $translation['text74'], $translation['text75'], $translation['text76'], $translation['text77'],
            $translation['text78'], $translation['text79'], $translation['text80'], $translation['text81'],
            $translation['text82'], $translation['text83'], $translation['text84'], $translation['text85']
        ];

        return '<script>
            $(function() {
                $(".dashboard_bank_transactions1_date").datepicker({
                    dateFormat: "dd.mm.yy",
                    dayNamesShort: ' . json_encode($dayNames) . ',
                    dayNamesMin: ' . json_encode($dayNames) . ',
                    monthNames: ' . json_encode($monthNames) . ',
                    changeMonth: false,
                    showAnim: "",
                    onSelect: function(dateText) {
                        var formData = new FormData();
                        formData.append("op", "saveTeamTextField");
                        formData.append("field", "bank_transactions_date");
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
                                    "op": "databasesBankTransactionsUpdateDate",
                                    "parameters": {
                                        "date": dateText,
                                        "user_id": $("#section_game").length ? $("#section_game").attr("data-user-id") : 0,
                                        "team_id": $("#section_game").length ? $("#section_game").attr("data-team-id") : 0
                                    }
                                };
                                sendMessageSocket(JSON.stringify(message)); 
                            }
                        });
                    },
                    beforeShow: function() {
                        if (!is_touch_device()) {
                            var pageSize = getPageSize();
                            var windowWidth = pageSize[2];
                            if (windowWidth < 1800) {
                                $("body").removeClass("body_desktop_scale").css("transform", "scale(1)");

                                setTimeout(function() {
                                    var pageSize = getPageSize();
                                    var windowWidth = pageSize[0];
                                    var koef = parseFloat((windowWidth / 1920).toFixed(2)) + 0.01;
                                    $("body").addClass("body_desktop_scale").css("transform", "scale(" + koef + ")");
                                    var curDatepickerPosition = parseFloat($(".ui-datepicker").css("left"));
                                    var differentDatepickerPosition = (1920 - windowWidth) / 2;
                                    $(".ui-datepicker").css("left", (curDatepickerPosition + differentDatepickerPosition + 7) + "px");
                                }, 1);
                            }
                        }
                    }
                });
            });
        </script>';
    }

    /**
     * Получение иконки Bank Transactions
     */
    private function getBankTransactionsIcon($width = 21, $height = 25)
    {
        return '<svg width="' . $width . '" height="' . $height . '" viewBox="0 0 21 25" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M8.9362 10.4167H7.89453C7.7564 10.4167 7.62392 10.3619 7.52625 10.2642C7.42857 10.1665 7.3737 10.034 7.3737 9.89591C7.3737 9.75778 7.31882 9.6253 7.22115 9.52763C7.12347 9.42995 6.991 9.37508 6.85286 9.37508C6.71473 9.37508 6.58225 9.42995 6.48458 9.52763C6.3869 9.6253 6.33203 9.75778 6.33203 9.89591C6.33203 10.3103 6.49665 10.7077 6.78968 11.0008C7.0827 11.2938 7.48013 11.4584 7.89453 11.4584V11.9792C7.89453 12.1174 7.9494 12.2499 8.04708 12.3475C8.14475 12.4452 8.27723 12.5001 8.41536 12.5001C8.5535 12.5001 8.68597 12.4452 8.78365 12.3475C8.88132 12.2499 8.9362 12.1174 8.9362 11.9792V11.4584C9.3506 11.4584 9.74803 11.2938 10.0411 11.0008C10.3341 10.7077 10.4987 10.3103 10.4987 9.89591V9.37508C10.4987 8.96068 10.3341 8.56325 10.0411 8.27022C9.74803 7.9772 9.3506 7.81258 8.9362 7.81258H7.89453C7.7564 7.81258 7.62392 7.75771 7.52625 7.66003C7.42857 7.56236 7.3737 7.42988 7.3737 7.29175V6.77091C7.3737 6.63278 7.42857 6.5003 7.52625 6.40263C7.62392 6.30495 7.7564 6.25008 7.89453 6.25008H8.9362C9.07433 6.25008 9.20681 6.30495 9.30448 6.40263C9.40216 6.5003 9.45703 6.63278 9.45703 6.77091C9.45703 6.90905 9.5119 7.04152 9.60958 7.1392C9.70725 7.23687 9.83973 7.29175 9.97786 7.29175C10.116 7.29175 10.2485 7.23687 10.3461 7.1392C10.4438 7.04152 10.4987 6.90905 10.4987 6.77091C10.4987 6.35651 10.3341 5.95908 10.0411 5.66606C9.74803 5.37303 9.3506 5.20841 8.9362 5.20841V4.68758C8.9362 4.54945 8.88132 4.41697 8.78365 4.3193C8.68597 4.22162 8.5535 4.16675 8.41536 4.16675C8.27723 4.16675 8.14475 4.22162 8.04708 4.3193C7.9494 4.41697 7.89453 4.54945 7.89453 4.68758V5.20841C7.48013 5.20841 7.0827 5.37303 6.78968 5.66606C6.49665 5.95908 6.33203 6.35651 6.33203 6.77091V7.29175C6.33203 7.70615 6.49665 8.10357 6.78968 8.3966C7.0827 8.68963 7.48013 8.85425 7.89453 8.85425H8.9362C9.07433 8.85425 9.20681 8.90912 9.30448 9.00679C9.40216 9.10447 9.45703 9.23694 9.45703 9.37508V9.89591C9.45703 10.034 9.40216 10.1665 9.30448 10.2642C9.20681 10.3619 9.07433 10.4167 8.9362 10.4167Z" fill="#00F0FF"/>
            <path d="M13.1029 16.6667H3.72786C3.58973 16.6667 3.45726 16.7216 3.35958 16.8193C3.2619 16.917 3.20703 17.0494 3.20703 17.1876C3.20703 17.3257 3.2619 17.4582 3.35958 17.5559C3.45726 17.6535 3.58973 17.7084 3.72786 17.7084H13.1029C13.241 17.7084 13.3735 17.6535 13.4712 17.5559C13.5688 17.4582 13.6237 17.3257 13.6237 17.1876C13.6237 17.0494 13.5688 16.917 13.4712 16.8193C13.3735 16.7216 13.241 16.6667 13.1029 16.6667V16.6667Z" fill="#00F0FF"/>
        </svg>';
    }

    /**
     * Получение иконки карты
     */
    private function getCardIcon()
    {
        return '<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M19.6429 3.14282H2.35714C1.05533 3.14282 0 4.19815 0 5.49996V16.4999C0 17.8018 1.05533 18.8571 2.35714 18.8571H19.6429C20.9447 18.8571 22 17.8018 22 16.4999V5.49996C22 4.19815 20.9447 3.14282 19.6429 3.14282ZM20.4285 16.4999C20.4285 16.9339 20.0768 17.2857 19.6428 17.2857H2.35714C1.92319 17.2857 1.57141 16.9339 1.57141 16.4999V11.7857H20.4285V16.4999ZM20.4285 10.2142H1.57141V8.64283H20.4285V10.2142ZM20.4285 7.07138H1.57141V5.49996C1.57141 5.06601 1.92319 4.71423 2.35714 4.71423H19.6429C20.0768 4.71423 20.4286 5.06601 20.4286 5.49996V7.07138H20.4285Z" fill="#00F0FF"/>
            <path d="M18.0712 13.3572H14.9283C14.4944 13.3572 14.1426 13.709 14.1426 14.1429C14.1426 14.5769 14.4944 14.9286 14.9283 14.9286H18.0712C18.5051 14.9286 18.8569 14.5769 18.8569 14.1429C18.8569 13.709 18.5051 13.3572 18.0712 13.3572Z" fill="#00F0FF"/>
        </svg>';
    }

    /**
     * Форматирование даты для Bank Transactions
     */
    private function formatBankTransactionsDate($date)
    {
        if (empty($date) || $date == '0000-00-00' || is_null($date)) {
            return '';
        }
        return $this->fromEngDatetimeToRus($date);
    }
}
