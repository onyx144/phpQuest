<?php

/**
 * Trait для работы с базой данных Personal Files
 */
trait PersonalFilesBlock
{
    /**
     * Personal Files - первый экран (выбор категории)
     * @param int $lang_id
     * @param int $team_id
     * @return array
     */
    private function uploadDatabasesPersonalFiles($lang_id, $team_id)
    {
        $translation = $this->getWordsByPage('game', $lang_id);
        $team_info = $this->teamInfo($team_id);

        $return = [];

        $return['titles'] = '<div class="dashboard_tab_title dashboard_tab_title_can_click" data-tab="tab1" data-step="databases_start_four" data-action-id="28" data-database="false">
                            <div class="dashboard_tab_title_active_skew_right"></div>
                            <div class="dashboard_tab_title_inner">
                                <div class="dashboard_tab_title_img_wrapper">
                                    <svg width="19" height="21" viewBox="0 0 19 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 0H19V3L17.25 5H0V2L1.75 0ZM1.73684 2H3V3.2L2.26316 4H1V2.8L1.73684 2ZM6 2H4.73684L4 2.8V4H5.26316L6 3.2V2ZM7.73684 2H9V3.2L8.26316 4H7V2.8L7.73684 2ZM17 2H10.7368L10 2.8V4H16.2632L17 3.2V2Z" fill="#00F0FF"/><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 8H19V11L17.25 13H0V10L1.75 8ZM1.73684 10H3V11.2L2.26316 12H1V10.8L1.73684 10ZM6 10H4.73684L4 10.8V12H5.26316L6 11.2V10ZM7.73684 10H9V11.2L8.26316 12H7V10.8L7.73684 10ZM17 10H10.7368L10 10.8V12H16.2632L17 11.2V10Z" fill="#00F0FF"/><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 16H19V19L17.25 21H0V18L1.75 16ZM1.73684 18H3V19.2L2.26316 20H1V18.8L1.73684 18ZM6 18H4.73684L4 18.8V20H5.26316L6 19.2V18ZM7.73684 18H9V19.2L8.26316 20H7V18.8L7.73684 18ZM17 18H10.7368L10 18.8V20H16.2632L17 19.2V18Z" fill="#00F0FF"/><rect width="15" height="1" transform="matrix(1 0 0 -1 2 7)" fill="#00F0FF"/><rect width="15" height="1" transform="matrix(1 0 0 -1 2 15)" fill="#00F0FF"/></svg>
                                </div>
                                <div class="dashboard_tab_title_text">' . $translation['text13'] . '</div>
                            </div>
                        </div>
                        <div class="dashboard_tab_title dashboard_tab_title_active" data-tab="personal_files1">
                            <div class="dashboard_tab_title_active_skew_right"></div>
                            <div class="dashboard_tab_title_inner">
                                <div class="dashboard_tab_title_img_wrapper" style="margin: -3px 0 0;">
                                    <img src="/images/icons/icon_tab_personal_files.png" alt="">
                                </div>
                                <div class="dashboard_tab_title_text">' . $translation['text170'] . '</div>
                            </div>
                        </div>';

        $return['back_btn'] = '<div class="dashboard_back_btn" data-back="databases_start_four" data-action-id-back="28" data-database="false">
                                    <img src="/images/back_bg.png" class="back_btn_bg" alt="">
                                    <div class="back_btn_text">' . $translation['text22'] . '</div>
                                </div>';

        $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_start_four" data-tab="tab1"></div>

                            <div class="dashboard_tab_content_item dashboard_tab_content_item_personal_files dashboard_tab_content_item_active" data-tab="personal_files1">
                                <div class="dashboard_personal_files1_inner">
                                    <div class="dashboard_personal_files1_title">' . $translation['text107'] . '</div>
                                    <div class="dashboard_personal_files1_categories">
                                        <div class="dashboard_personal_files1_category dashboard_personal_files1_category_private_individuals">
                                            <div class="dashboard_personal_files1_category_top"></div>
                                            <div class="dashboard_personal_files1_category_bottom">
                                                <div class="dashboard_personal_files1_category_title">' . $translation['text108'] . '</div>
                                                <div class="dashboard_personal_files1_category_img_wrapper">
                                                    <img src="/images/icons/icon_personal_files_private_individual.png" alt="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dashboard_personal_files1_category dashboard_personal_files1_category_ceo_database">
                                            <div class="dashboard_personal_files1_category_top"></div>
                                            <div class="dashboard_personal_files1_category_bottom">
                                                <div class="dashboard_personal_files1_category_title">' . $translation['text109'] . '</div>
                                                <div class="dashboard_personal_files1_category_img_wrapper">
                                                    <img src="/images/icons/icon_personal_files_ceo_database.png" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>';

        return $return;
    }

    /**
     * Personal Files - Private Individual - форма поиска
     * @param int $lang_id
     * @param int $team_id
     * @return array
     */
    private function uploadDatabasesPersonalFilesPrivateIndividual($lang_id, $team_id)
    {
        $translation = $this->getWordsByPage('game', $lang_id);
        $team_info = $this->teamInfo($team_id);

        $return = [];

        $return['titles'] = $this->getPersonalFilesTitles($translation, 'private_individual');
        $return['back_btn'] = $this->getPersonalFilesBackButton($translation);
        $return['content'] = $this->getPrivateIndividualSearchForm($translation);

        return $return;
    }

    /**
     * Personal Files - Private Individual - результаты Huilov
     * @param int $lang_id
     * @param int $team_id
     * @return array
     */
    private function uploadDatabasesPersonalFilesPrivateIndividualHuilov($lang_id, $team_id)
    {
        $translation = $this->getWordsByPage('game', $lang_id);
        $team_info = $this->teamInfo($team_id);
        $user_info = $this->getUserPersonalFilesInfo($team_id, 'private_individuals_print_text_huilov');

        $return = [];

        $return['titles'] = $this->getPersonalFilesTitles($translation, 'private_individual_huilov');
        $return['back_btn'] = $this->getPersonalFilesBackButton($translation);
        $return['content'] = $this->getPrivateIndividualHuilovContent($translation, $user_info, $team_info);

        // Обработка первого запуска
        $this->processPersonalFilesFirstRun($team_info, $team_id, $lang_id, 'private_individuals_print_text_huilov', 'private_individuals_huilov');
        
        // Обновление для текущего пользователя
        $this->updateUserPersonalFilesStatus($team_id, 'private_individuals_print_text_huilov');
        
        // Подготовка языковых данных
        $return['error_lang'] = $this->getPrivateIndividualLanguageData();

        return $return;
    }

    /**
     * Personal Files - CEO Database - форма поиска
     * @param int $lang_id
     * @param int $team_id
     * @return array
     */
    private function uploadDatabasesPersonalFilesCeoDatabase($lang_id, $team_id)
    {
        $translation = $this->getWordsByPage('game', $lang_id);
        $team_info = $this->teamInfo($team_id);

        $return = [];

        $return['titles'] = $this->getPersonalFilesTitles($translation, 'ceo_database');
        $return['back_btn'] = $this->getPersonalFilesBackButton($translation);
        $return['content'] = $this->getCeoDatabaseSearchForm($translation);

        return $return;
    }

    /**
     * Personal Files - CEO Database - результаты Rod
     * @param int $lang_id
     * @param int $team_id
     * @return array
     */
    private function uploadDatabasesPersonalFilesCeoDatabaseRod($lang_id, $team_id)
    {
        $translation = $this->getWordsByPage('game', $lang_id);
        $team_info = $this->teamInfo($team_id);
        $user_info = $this->getUserPersonalFilesInfo($team_id, 'ceo_database_print_text_rod');

        $return = [];

        $return['titles'] = $this->getPersonalFilesTitles($translation, 'ceo_database_rod');
        $return['back_btn'] = $this->getPersonalFilesBackButton($translation);
        $return['content'] = $this->getCeoDatabaseRodContent($translation, $user_info, $team_info);

        // Обработка первого запуска
        $this->processPersonalFilesFirstRun($team_info, $team_id, $lang_id, 'ceo_database_print_text_rod', 'ceo_database_rod');
        
        // Обновление для текущего пользователя
        $this->updateUserPersonalFilesStatus($team_id, 'ceo_database_print_text_rod');
        
        // Подготовка языковых данных
        $return['error_lang'] = $this->getCeoDatabaseLanguageData();

        return $return;
    }

    // ==================== HELPER METHODS ====================

    /**
     * Получение заголовков для Personal Files
     */
    private function getPersonalFilesTitles($translation, $type)
    {
        $baseTitle = '<div class="dashboard_tab_title dashboard_tab_title_can_click" data-tab="tab1" data-step="databases_start_four" data-action-id="28" data-database="false">
                            <div class="dashboard_tab_title_active_skew_right"></div>
                            <div class="dashboard_tab_title_inner">
                                <div class="dashboard_tab_title_img_wrapper">
                                    <svg width="19" height="21" viewBox="0 0 19 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 0H19V3L17.25 5H0V2L1.75 0ZM1.73684 2H3V3.2L2.26316 4H1V2.8L1.73684 2ZM6 2H4.73684L4 2.8V4H5.26316L6 3.2V2ZM7.73684 2H9V3.2L8.26316 4H7V2.8L7.73684 2ZM17 2H10.7368L10 2.8V4H16.2632L17 3.2V2Z" fill="#00F0FF"/><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 8H19V11L17.25 13H0V10L1.75 8ZM1.73684 10H3V11.2L2.26316 12H1V10.8L1.73684 10ZM6 10H4.73684L4 10.8V12H5.26316L6 11.2V10ZM7.73684 10H9V11.2L8.26316 12H7V10.8L7.73684 10ZM17 10H10.7368L10 10.8V12H16.2632L17 11.2V10Z" fill="#00F0FF"/><path fill-rule="evenodd" clip-rule="evenodd" d="M1.75 16H19V19L17.25 21H0V18L1.75 16ZM1.73684 18H3V19.2L2.26316 20H1V18.8L1.73684 18ZM6 18H4.73684L4 18.8V20H5.26316L6 19.2V18ZM7.73684 18H9V19.2L8.26316 20H7V18.8L7.73684 18ZM17 18H10.7368L10 18.8V20H16.2632L17 19.2V18Z" fill="#00F0FF"/><rect width="15" height="1" transform="matrix(1 0 0 -1 2 7)" fill="#00F0FF"/><rect width="15" height="1" transform="matrix(1 0 0 -1 2 15)" fill="#00F0FF"/></svg>
                                </div>
                                <div class="dashboard_tab_title_text">' . $translation['text13'] . '</div>
                            </div>
                        </div>';

        $menuTitle = '<div class="dashboard_tab_title ' . (in_array($type, ['private_individual', 'ceo_database']) ? 'dashboard_tab_title_can_click' : '') . '" data-tab="personal_files1" data-step="databases_start_four_inner_first_personal_files" data-action-id="32" data-database="personal_files">
                            <div class="dashboard_tab_title_active_skew_right"></div>
                            <div class="dashboard_tab_title_inner">
                                <div class="dashboard_tab_title_img_wrapper" style="margin: -3px 0 0;">
                                    <img src="/images/icons/icon_tab_personal_files.png" alt="">
                                </div>
                                <div class="dashboard_tab_title_text">' . $translation['text170'] . '</div>
                            </div>
                        </div>';

        $specificTitle = match($type) {
            'private_individual' => $this->getPrivateIndividualTitle($translation),
            'private_individual_huilov' => $this->getPrivateIndividualHuilovTitle($translation),
            'ceo_database' => $this->getCeoDatabaseTitle($translation),
            'ceo_database_rod' => $this->getCeoDatabaseRodTitle($translation),
            default => ''
        };

        return $baseTitle . $menuTitle . $specificTitle;
    }

    /**
     * Получение кнопки "Назад" для Personal Files
     */
    private function getPersonalFilesBackButton($translation)
    {
        return '<div class="dashboard_back_btn" data-back="databases_start_four_inner_first_personal_files" data-action-id-back="32" data-database="personal_files">
                    <img src="/images/back_bg.png" class="back_btn_bg" alt="">
                    <div class="back_btn_text">' . $translation['text22'] . '</div>
                </div>';
    }

    /**
     * Форма поиска Private Individual
     */
    private function getPrivateIndividualSearchForm($translation)
    {
        return '<div class="dashboard_tab_content_item dashboard_tab_content_item_start_four" data-tab="tab1"></div>
                <div class="dashboard_tab_content_item dashboard_tab_content_item_start_four" data-tab="personal_files1"></div>
                <div class="dashboard_tab_content_item dashboard_tab_content_item_start_four dashboard_tab_content_item_active" data-tab="personal_files2">
                    <div class="dashboard_personal_files2_private_individuals_inner">
                        <div class="dashboard_personal_files2_private_individuals_img_wrapper">
                            <img src="/images/icons/icon_personal_files_private_individual.png" alt="">
                        </div>
                        <div class="dashboard_personal_files2_private_individuals_title">' . $translation['text108'] . '</div>
                        <div class="dashboard_personal_files2_private_individuals_text">' . $translation['text110'] . '</div>
                        <div class="dashboard_personal_files2_private_individuals_inputs">
                            <div class="dashboard_personal_files2_private_individuals_input_wrapper dashboard_personal_files2_private_individuals_input_wrapper_firstname">
                                <div class="dashboard_personal_files2_private_individuals_input_border_left"></div>
                                <input type="text" placeholder="' . $translation['text111'] . '" value="" autocomplete="off">
                                <div class="dashboard_personal_files2_private_individuals_firstname_error error_text_database_car_register">' . $translation['text86'] . '</div>
                            </div>
                            <div class="dashboard_personal_files2_private_individuals_input_wrapper dashboard_personal_files2_private_individuals_input_wrapper_lastname">
                                <div class="dashboard_personal_files2_private_individuals_input_border_right"></div>
                                <input type="text" placeholder="' . $translation['text112'] . '" value="" autocomplete="off">
                                <div class="dashboard_personal_files2_private_individuals_lastname_error error_text_database_car_register">' . $translation['text86'] . '</div>
                            </div>
                        </div>
                        <div class="btn_wrapper btn_wrapper_blue dashboard_personal_files2_private_individuals_search">
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
     * Контент с результатами Huilov для Private Individual
     */
    private function getPrivateIndividualHuilovContent($translation, $user_info, $team_info)
    {
        $bubbleClass = empty($user_info['private_individuals_print_text_huilov']) ? ' dashboard_personal_files2_private_individuals_huilov_inner_bubble' : '';
        $bubbleTeamClass = empty($team_info['private_individuals_print_text_huilov']) ? ' dashboard_personal_files2_private_individuals_huilov_inner_bubble_team' : '';

        $rows = [
            ['label' => 'text115', 'text' => 'text116', 'id' => 0],
            ['label' => 'text117', 'text' => 'text118', 'id' => 1, 'class' => 'dashboard_personal_files2_private_individuals_huilov_data_row_lastname'],
            ['label' => 'text119', 'text' => 'text120', 'id' => 2],
            ['label' => 'text121', 'text' => 'text122', 'id' => 3],
            ['label' => 'text123', 'text' => 'text124', 'id' => 4],
            ['label' => 'text125', 'text' => 'text126', 'id' => 5],
            ['label' => 'text127', 'text' => 'text128', 'id' => 6],
        ];

        $rowsHtml = '';
        foreach ($rows as $row) {
            $rowsHtml .= '<div class="dashboard_personal_files2_private_individuals_huilov_data_row ' . ($row['class'] ?? '') . '">
                <div class="dashboard_personal_files2_private_individuals_huilov_label">' . $translation[$row['label']] . '</div>
                <div class="dashboard_personal_files2_private_individuals_huilov_input">
                    <span class="dots_top"></span>
                    <span class="dots_bottom_left"></span>
                    <span class="dots_bottom_right"></span>
                    <span class="private_individuals_huilov_text" data-bubble="' . $row['id'] . '">
                        <span>' . (empty($user_info['private_individuals_print_text_huilov']) ? '' : $translation[$row['text']]) . '</span>
                    </span>
                </div>
            </div>';
        }

        return '<div class="dashboard_tab_content_item dashboard_tab_content_item_active" data-tab="personal_files2">
            <div class="dashboard_personal_files2_private_individuals_huilov_inner' . $bubbleClass . $bubbleTeamClass . '">
                <div class="dashboard_personal_files2_private_individuals_huilov_left">
                    <div class="dashboard_personal_files2_private_individuals_huilov_images">
                        <div class="dashboard_personal_files2_private_individuals_huilov_images_inner">
                            <img src="/images/icons/icon_huilov_hand.png" alt="">
                            <img src="/images/icons/icon_huilov_img2.png" alt="">
                            <img src="/images/icons/icon_huilov_diagram.png" alt="">
                            <div class="dashboard_personal_files2_private_individuals_huilov_images_text">
                                <span>' . $translation['text113'] . '</span>
                                <span>' . $translation['text114'] . '</span>
                            </div>
                        </div>
                        <div class="dashboard_personal_files2_private_individuals_huilov_images_bg_right">
                            <svg width="9" height="396" viewBox="0 0 9 396" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0 1H8.5V395.5H3H0" stroke="#FF004E"/>
                            </svg>
                        </div>
                        <div class="dashboard_personal_files2_private_individuals_huilov_images_bg_bottom"></div>
                    </div>
                    <div class="dashboard_personal_files2_private_individuals_huilov_main_image">
                        <img src="/images/huilov_photo.jpg" class="huilov_main_image" alt="">
                        <div class="dashboard_personal_files2_private_individuals_huilov_main_image_diagram">
                            <img src="/images/icons/icon_huilov_main_image_diagram.png" alt="">
                        </div>
                        <img src="/images/gifs/face_anim.gif" class="huilov_face_anim" alt="">
                    </div>
                </div>
                <div class="dashboard_personal_files2_private_individuals_huilov_right">
                    ' . $rowsHtml . '
                    <div class="dashboard_personal_files2_private_individuals_huilov_data_row">
                        <div class="dashboard_personal_files2_private_individuals_huilov_label">' . $translation['text129'] . '</div>
                        <div class="dashboard_personal_files2_private_individuals_huilov_input" style="font-size:18px;line-height:22px;padding:18px 0 0;">
                            <span class="dots_top"></span>
                            <span class="dots_bottom_left"></span>
                            <span class="dots_bottom_right"></span>
                            <span class="private_individuals_huilov_text" data-bubble="7">
                                <span>' . (empty($user_info['private_individuals_print_text_huilov']) ? '' : $translation['text130']) . '</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
    }

    /**
     * Форма поиска CEO Database
     */
    private function getCeoDatabaseSearchForm($translation)
    {
        return '<div class="dashboard_tab_content_item dashboard_tab_content_item_start_four" data-tab="tab1"></div>
                <div class="dashboard_tab_content_item dashboard_tab_content_item_start_four" data-tab="personal_files1"></div>
                <div class="dashboard_tab_content_item dashboard_tab_content_item_start_four dashboard_tab_content_item_active" data-tab="personal_files2">
                    <div class="dashboard_personal_files2_private_individuals_inner">
                        <div class="dashboard_personal_files2_private_individuals_img_wrapper">
                            <img src="/images/icons/icon_personal_files_ceo_database.png" alt="">
                        </div>
                        <div class="dashboard_personal_files2_private_individuals_title">' . $translation['text109'] . '</div>
                        <div class="dashboard_personal_files2_private_individuals_text">' . $translation['text110'] . '</div>
                        <div class="dashboard_personal_files2_private_individuals_inputs">
                            <div class="dashboard_personal_files2_private_individuals_input_wrapper dashboard_personal_files2_private_individuals_input_wrapper_firstname">
                                <div class="dashboard_personal_files2_private_individuals_input_border_left"></div>
                                <input type="text" placeholder="' . $translation['text111'] . '" value="" autocomplete="off">
                                <div class="dashboard_personal_files2_private_individuals_firstname_error error_text_database_car_register">' . $translation['text86'] . '</div>
                            </div>
                            <div class="dashboard_personal_files2_private_individuals_input_wrapper dashboard_personal_files2_private_individuals_input_wrapper_lastname">
                                <div class="dashboard_personal_files2_private_individuals_input_border_right"></div>
                                <input type="text" placeholder="' . $translation['text112'] . '" value="" autocomplete="off">
                                <div class="dashboard_personal_files2_private_individuals_lastname_error error_text_database_car_register">' . $translation['text86'] . '</div>
                            </div>
                        </div>
                        <div class="btn_wrapper btn_wrapper_blue dashboard_personal_files2_ceo_database_search">
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
     * Контент с результатами Rod для CEO Database
     */
    private function getCeoDatabaseRodContent($translation, $user_info, $team_info)
    {
        $bubbleClass = empty($user_info['ceo_database_print_text_rod']) ? ' dashboard_personal_files2_ceo_database_rod_inner_bubble' : '';
        $bubbleTeamClass = empty($team_info['ceo_database_print_text_rod']) ? ' dashboard_personal_files2_ceo_database_rod_inner_bubble_team' : '';

        $rows = [
            ['label' => 'text115', 'text' => 'text133', 'id' => 0],
            ['label' => 'text117', 'text' => 'text134', 'id' => 1, 'class' => 'dashboard_personal_files2_private_individuals_huilov_data_row_lastname'],
            ['label' => 'text119', 'text' => 'text135', 'id' => 2],
            ['label' => 'text136', 'text' => 'text137', 'id' => 3],
            ['label' => 'text138', 'text' => 'text139', 'id' => 4],
        ];

        $rowsHtml = '';
        foreach ($rows as $row) {
            $rowsHtml .= '<div class="dashboard_personal_files2_private_individuals_huilov_data_row ' . ($row['class'] ?? '') . '">
                <div class="dashboard_personal_files2_private_individuals_huilov_label">' . $translation[$row['label']] . '</div>
                <div class="dashboard_personal_files2_private_individuals_huilov_input">
                    <span class="dots_top"></span>
                    <span class="dots_bottom_left"></span>
                    <span class="dots_bottom_right"></span>
                    <span class="private_individuals_huilov_text" data-bubble="' . $row['id'] . '">
                        <span>' . (empty($user_info['ceo_database_print_text_rod']) ? '' : $translation[$row['text']]) . '</span>
                    </span>
                </div>
            </div>';
        }

        return '<div class="dashboard_tab_content_item dashboard_tab_content_item_start_four dashboard_tab_content_item_active" data-tab="personal_files2">
            <div class="dashboard_personal_files2_private_individuals_huilov_inner' . $bubbleClass . $bubbleTeamClass . '">
                <div class="dashboard_personal_files2_private_individuals_huilov_right_bg_line">
                    <svg width="868" height="418" viewBox="0 0 868 418" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0.5 1H790L809.5 20.5V405L822 417.5H867.5" stroke="#FF004E"/>
                    </svg>
                </div>
                <div class="dashboard_personal_files2_private_individuals_huilov_left">
                    <div class="dashboard_personal_files2_private_individuals_huilov_images">
                        <div class="dashboard_personal_files2_private_individuals_huilov_images_inner">
                            <img src="/images/icons/icon_huilov_hand.png" alt="">
                            <img src="/images/icons/icon_huilov_img2.png" alt="">
                            <img src="/images/icons/icon_huilov_diagram.png" alt="">
                            <div class="dashboard_personal_files2_private_individuals_huilov_images_text">
                                <span>' . $translation['text113'] . '</span>
                                <span>' . $translation['text114'] . '</span>
                            </div>
                        </div>
                        <div class="dashboard_personal_files2_private_individuals_huilov_images_bg_right">
                            <svg width="9" height="396" viewBox="0 0 9 396" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0 1H8.5V395.5H3H0" stroke="#FF004E"/>
                            </svg>
                        </div>
                        <div class="dashboard_personal_files2_private_individuals_huilov_images_bg_bottom"></div>
                    </div>
                    <div class="dashboard_personal_files2_private_individuals_huilov_main_image">
                        <img src="/images/rod_photo2.jpg" class="huilov_main_image" alt="">
                        <div class="dashboard_personal_files2_private_individuals_huilov_main_image_diagram">
                            <img src="/images/icons/icon_huilov_main_image_diagram.png" alt="">
                        </div>
                        <img src="/images/gifs/face_anim.gif" class="rod_face_anim" alt="">
                    </div>
                </div>
                <div class="dashboard_personal_files2_private_individuals_huilov_right">
                    ' . $rowsHtml . '
                </div>
            </div>
        </div>';
    }

    /**
     * Получение информации пользователя для Personal Files
     */
    private function getUserPersonalFilesInfo($team_id, $field)
    {
        if (isset($_COOKIE['hash'])) {
            $sql = "SELECT `{$field}` FROM `users` WHERE `team_id` = {?} AND `hash` = {?} LIMIT 1";
            return $this->db->selectRow($sql, [$team_id, $_COOKIE['hash']]);
        } else {
            $sql = "SELECT `{$field}` FROM `users` WHERE `team_id` = {?} AND `ip` = {?} LIMIT 1";
            return $this->db->selectRow($sql, [$team_id, $this->getIp()]);
        }
    }

    /**
     * Обработка первого запуска Personal Files
     */
    private function processPersonalFilesFirstRun($team_info, $team_id, $lang_id, $field, $hint_step)
    {
        if (empty($team_info[$field])) {
            // Обновляем значение, что текст напечатан
            $sql = "UPDATE `teams` SET `{$field}` = {?} WHERE `id` = {?}";
            $this->db->query($sql, [1, $team_id]);

            // Обновляем подсказки
            $this->updatePersonalFilesHints($team_id, $lang_id, $hint_step);
        }
    }

    /**
     * Обновление подсказок для Personal Files
     */
    private function updatePersonalFilesHints($team_id, $lang_id, $hint_step)
    {
        $active_hints = [];
        $list_hints = [];

        $hints_by_step = $this->getHintsByStep($hint_step, $lang_id);
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
     * Обновление статуса пользователя для Personal Files
     */
    private function updateUserPersonalFilesStatus($team_id, $field)
    {
        if (isset($_COOKIE['hash'])) {
            $sql = "UPDATE `users` SET `{$field}` = {?} WHERE `team_id` = {?} AND `hash` = {?}";
            $this->db->query($sql, [1, $team_id, $_COOKIE['hash']]);
        } else {
            $sql = "UPDATE `users` SET `{$field}` = {?} WHERE `team_id` = {?} AND `ip` = {?}";
            $this->db->query($sql, [1, $team_id, $this->getIp()]);
        }
    }

    /**
     * Получение языковых данных для Private Individual
     */
    private function getPrivateIndividualLanguageData()
    {
        $error_lang = [];
        $sql = "SELECT `id`, `lang_abbr` FROM `langs` WHERE `status` = {?}";
        $langs = $this->db->select($sql, [1]);

        if ($langs) {
            foreach ($langs as $lang_item) {
                $translation = $this->getWordsByPage('game', $lang_item['id']);
                $error_lang[$lang_item['lang_abbr']] = [
                    'text116' => $translation['text116'],
                    'text118' => $translation['text118'],
                    'text120' => $translation['text120'],
                    'text122' => $translation['text122'],
                    'text124' => $translation['text124'],
                    'text126' => $translation['text126'],
                    'text128' => $translation['text128'],
                    'text130' => $translation['text130'],
                    'text131' => $translation['text131']
                ];
            }
        }

        return $error_lang;
    }

    /**
     * Получение языковых данных для CEO Database
     */
    private function getCeoDatabaseLanguageData()
    {
        $error_lang = [];
        $sql = "SELECT `id`, `lang_abbr` FROM `langs` WHERE `status` = {?}";
        $langs = $this->db->select($sql, [1]);

        if ($langs) {
            foreach ($langs as $lang_item) {
                $translation = $this->getWordsByPage('game', $lang_item['id']);
                $error_lang[$lang_item['lang_abbr']] = [
                    'text133' => $translation['text133'],
                    'text134' => $translation['text134'],
                    'text135' => $translation['text135'],
                    'text137' => $translation['text137'],
                    'text139' => $translation['text139']
                ];
            }
        }

        return $error_lang;
    }

    /**
     * Заголовок для Private Individual
     */
    private function getPrivateIndividualTitle($translation)
    {
        return '<div class="dashboard_tab_title dashboard_tab_title_active" data-tab="personal_files2">
                    <div class="dashboard_tab_title_active_skew_right"></div>
                    <div class="dashboard_tab_title_inner">
                        <div class="dashboard_tab_title_img_wrapper" style="margin: -1px 0 0;">
                            <img src="/images/icons/icon_tab_personal_files_private_individual.png" alt="">
                        </div>
                        <div class="dashboard_tab_title_text">' . $translation['text108'] . '</div>
                    </div>
                </div>';
    }

    /**
     * Заголовок для Private Individual Huilov
     */
    private function getPrivateIndividualHuilovTitle($translation)
    {
        return '<div class="dashboard_tab_title dashboard_tab_title_active" data-tab="personal_files2">
                    <div class="dashboard_tab_title_active_skew_right"></div>
                    <div class="dashboard_tab_title_inner">
                        <div class="dashboard_tab_title_img_wrapper" style="margin: -1px 0 0;">
                            <img src="/images/icons/icon_tab_personal_files_private_individual.png" alt="">
                        </div>
                        <div class="dashboard_tab_title_text">' . $translation['text104'] . '</div>
                    </div>
                </div>';
    }

    /**
     * Заголовок для CEO Database
     */
    private function getCeoDatabaseTitle($translation)
    {
        return '<div class="dashboard_tab_title dashboard_tab_title_active" data-tab="personal_files2">
                    <div class="dashboard_tab_title_active_skew_right"></div>
                    <div class="dashboard_tab_title_inner">
                        <div class="dashboard_tab_title_img_wrapper" style="margin: -2px 0 0;">
                            <img src="/images/icons/icon_tab_ceo_database.png" alt="">
                        </div>
                        <div class="dashboard_tab_title_text">' . $translation['text109'] . '</div>
                    </div>
                </div>';
    }

    /**
     * Заголовок для CEO Database Rod
     */
    private function getCeoDatabaseRodTitle($translation)
    {
        return '<div class="dashboard_tab_title dashboard_tab_title_active" data-tab="personal_files2">
                    <div class="dashboard_tab_title_active_skew_right"></div>
                    <div class="dashboard_tab_title_inner">
                        <div class="dashboard_tab_title_img_wrapper" style="margin: -2px 0 0;">
                            <img src="/images/icons/icon_tab_ceo_database.png" alt="">
                        </div>
                        <div class="dashboard_tab_title_text">' . $translation['text132'] . '</div>
                    </div>
                </div>';
    }
}
