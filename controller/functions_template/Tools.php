<?php

trait Tools
{
public function uploadTypeTabsToolsStep($step, $lang_id, $team_id)
{
    switch ($step) {
        case 'no_access': $return = $this->uploadToolsNoAccess($lang_id, $team_id); break;
        case 'tools_start_four': $return = $this->uploadToolsStartFour($lang_id, $team_id); break;
        case 'advanced_search_engine': $return = $this->uploadToolsAdvancedSearchEngine($lang_id, $team_id); break;
        case 'symbol_decoder': $return = $this->uploadToolsSymbolDecoder($lang_id, $team_id); break;
        case '3d_building_scan': $return = $this->uploadToolsThreeDBuildingScan($lang_id, $team_id); break;
        case 'secret_office': $return = $this->uploadToolsSecretOffice($lang_id, $team_id); break;
        
        default: $return = $this->uploadToolsNoAccess($lang_id, $team_id); break;
    }

    return $return;
}

// tools - нет доступа
private function uploadToolsNoAccess($lang_id, $team_id)
{
    $translation = $this->getWordsByPage('game', $lang_id);

    $return = [];

    // после принятия миссии меняется текст НЕдоступности
    /*$sql = "SELECT `id` FROM `team_history_action` WHERE `team_id` = {?} AND `action_id` = {?}";
    $isset_accept_mission = (int) $this->db->selectCell($sql, [$team_id, 39]);*/
    $sql = "SELECT `view_gem` FROM `teams` WHERE `id` = {?}";
    $isset_accept_mission = (int) $this->db->selectCell($sql, [$team_id]);
    if (!empty($isset_accept_mission)) {
        $tools_no_access_text = $translation['text172'];
    } else {
        $tools_no_access_text = $translation['text40'];
    }

    $return['titles'] = '
    <div class="flex items-center gap-3 mb-8">
        <div class="icon-container p-2 rounded-lg bg-primary/20 border border-primary/30">
            <svg width="22" height="22" viewBox="0 0 18 18" fill="none">
                <path d="M1.75 0H17.75V5L16 7H0V2L1.75 0Z" fill="#00F0FF"/>
                <path d="M1.75 9H17.75V10.5L16 12.5H0V11L1.75 9Z" fill="#00F0FF"/>
                <path d="M1.75 14H17.75V15.5L16 17.5H0V16L1.75 14Z" fill="#00F0FF"/>
            </svg>
        </div>
        <h2 class="text-3xl font-bold neon-text">
            ' . $translation['text14'] . '
        </h2>
    </div>
    ';

    $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_no_access dashboard_tab_content_item_active" data-tab="tab1">
                            <div class="dashboard_tab_content_item_no_access_inner">
                                <img src="/images/tab_no_access_bg.png" class="dashboard_tab_content_item_no_access_bg" alt="">
                                <div class="dashboard_tab_content_item_no_access_skew_line_top"></div>
                                <div class="dashboard_tab_content_item_no_access_skew_line_bottom"></div>
                            </div>
                            <div class="dashboard_tab_content_item_no_access_inner_va">
                                <div class="dashboard_tab_content_item_no_access_title">
                                    <img src="/images/tab_no_access_border_left.png" class="tab_no_access_border_left" alt="">
                                    <img src="/images/tab_no_access_border_left.png" class="tab_no_access_border_right" alt="">
                                    <div class="dashboard_tab_content_item_no_access_title_text">' . $translation['text39'] . '</div>
                                    <img src="/images/dashboard_tab_content_item_no_access_line_left.png" class="dashboard_tab_content_item_no_access_line_left" alt="">
                                    <img src="/images/dashboard_tab_content_item_no_access_line_right.png" class="dashboard_tab_content_item_no_access_line_right" alt="">
                                    <img src="/images/dashboard_tab_content_item_no_access_line_left2.png" class="dashboard_tab_content_item_no_access_line_left2" alt="">
                                    <img src="/images/dashboard_tab_content_item_no_access_line_right2.png" class="dashboard_tab_content_item_no_access_line_right2" alt="">
                                </div>
                                <div class="dashboard_tab_content_item_no_access_subtitle">' . $tools_no_access_text . '</div>
                            </div>
                        </div>';

    return $return;
}

// tools - первый экран - список 4-ех tools
private function uploadToolsStartFour($lang_id, $team_id)
{
$translation = $this->getWordsByPage('game', $lang_id);
$svg = $this->svg; 
$return = [];

// ---------------- TAB TITLE ----------------
$return['titles'] = '
    <div class="flex items-center gap-3 mb-8">
        <div class="icon-container p-2 rounded-lg bg-primary/20 border border-primary/30">
            <svg width="22" height="22" viewBox="0 0 18 18" fill="none">
                <path d="M1.75 0H17.75V5L16 7H0V2L1.75 0Z" fill="#00F0FF"/>
                <path d="M1.75 9H17.75V10.5L16 12.5H0V11L1.75 9Z" fill="#00F0FF"/>
                <path d="M1.75 14H17.75V15.5L16 17.5H0V16L1.75 14Z" fill="#00F0FF"/>
            </svg>
        </div>
        <h2 class="text-3xl font-bold neon-text">
            ' . $translation['text14'] . '
        </h2>
    </div>
';

// ---------------- TOOLS CONTENT ----------------
$return['content'] = '
<div class="flex items-stretch gap-6 w-full p-3">


        <!-- ADVANCED SEARCH ENGINE -->
        <div class="dashboard_tab_content_item_start_four_inner_item_tools cyber-panel
                    border border-blue-500/30 bg-blue-500/20 rounded-xl cursor-pointer 
                    overflow-hidden hover:scale-105 transition-all flex flex-col justify-between flex-1"
             data-tools="advanced_search_engine">

            <div class="p-3 text-center flex-1 flex flex-col">
                <img src="/images/database_personal_files_top_bg.png" 
                     class="w-full h-20 object-cover rounded-md mb-3" alt="">
                <div class="text-xl font-semibold text-blue-400 mb-3 uppercase">
                    ' . $translation['text185'] . '
                </div>
            </div>

            <div class="p-3 flex flex-col items-center">
                <div class="flex justify-center items-center mb-3">
                    ' . $svg['loop'] . '
                </div>
                <button class="w-full border border-current text-blue-400 py-2 hover:bg-blue-500/10 rounded-lg text-center">
                    Открыть
                </button>
            </div>
        </div>

        <!-- GPS COORDINATES -->
        <div class="dashboard_tab_content_item_start_four_inner_item_tools cyber-panel
                    border border-green-500/30 bg-green-500/20 rounded-xl cursor-pointer 
                    overflow-hidden hover:scale-105 transition-all flex flex-col justify-between flex-1"
             data-tools="gps_coordinates">

            <div class="p-3 text-center flex-1 flex flex-col">
                <img src="/images/database_car_register_top_bg.png" 
                     class="w-full h-20 object-cover rounded-md mb-3" alt="">
                <div class="text-xl font-semibold text-green-400 mb-3 uppercase">
                    ' . $translation['text186'] . '
                </div>
            </div>

            <div class="p-3 flex flex-col items-center">
                <div class="flex justify-center items-center mb-3">
                    ' . $svg['gps'] . '
                </div>
                <button class="w-full border border-current text-green-400 py-2 hover:bg-green-500/10 rounded-lg text-center">
                    Открыть
                </button>
            </div>
        </div>

        <!-- SYMBOL DECODER -->
        <div class="dashboard_tab_content_item_start_four_inner_item_tools cyber-panel
                    border border-purple-500/30 bg-purple-500/20 rounded-xl cursor-pointer 
                    overflow-hidden hover:scale-105 transition-all flex flex-col justify-between flex-1"
             data-tools="symbol_decoder">

            <div class="p-3 text-center flex-1 flex flex-col">
                <img src="/images/database_mobile_calls_top_bg.png" 
                     class="w-full h-20 object-cover rounded-md mb-3" alt="">
                <div class="text-xl font-semibold text-purple-400 mb-3 uppercase">
                    ' . $translation['text187'] . '
                </div>
            </div>

            <div class="p-3 flex flex-col items-center">
                <div class="flex justify-center items-center mb-3">
                    ' . $svg['decoder'] . '
                </div>
                <button class="w-full border border-current text-purple-400 py-2 hover:bg-purple-500/10 rounded-lg text-center">
                    Запустить
                </button>
            </div>
        </div>

        <!-- 3D BUILDING SCAN -->
        <div class="dashboard_tab_content_item_start_four_inner_item_tools cyber-panel
                    border border-red-500/30 bg-red-500/20 rounded-xl cursor-pointer 
                    overflow-hidden hover:scale-105 transition-all flex flex-col justify-between flex-1"
             data-tools="3d_building_scan">

            <div class="p-3 text-center flex-1 flex flex-col">
                <img src="/images/database_bank_transactions_top_bg.png" 
                     class="w-full h-20 object-cover rounded-md mb-3" alt="">
                <div class="text-xl font-semibold text-red-400 mb-3 uppercase">
                    ' . $translation['text188'] . '
                </div>
            </div>

            <div class="p-3 flex flex-col items-center">
                <div class="flex justify-center items-center mb-3">
                    ' . $svg['building'] . '
                </div>
                <button class="w-full border border-current text-red-400 py-2 hover:bg-red-500/10 rounded-lg text-center">
                    Запустить
                </button>
            </div>
        </div>

</div>';

return $return;
}




// tools - Advanced Search Engine
private function uploadToolsAdvancedSearchEngine($lang_id, $team_id)
{
    $translation = $this->getWordsByPage('game', $lang_id);

    $team_info = $this->teamInfo($team_id);

    $return = [];

    $return['titles'] = '<div class="dashboard_tab_title dashboard_tab_title_active dashboard_tab_title_can_click_tools" data-tab="tab1" data-step="tools_start_four" data-tools="false">
                            <div class="dashboard_tab_title_active_skew_right"></div>
                            <div class="dashboard_tab_title_inner">
                                <div class="dashboard_tab_title_img_wrapper">
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.75 0H17.75V5L16 7H0V2L1.75 0Z" fill="#00F0FF"/><path d="M1.75 9H17.75V10.5L16 12.5H0V11L1.75 9Z" fill="#00F0FF"/><path d="M1.75 14H17.75V15.5L16 17.5H0V16L1.75 14Z" fill="#00F0FF"/></svg>
                                </div>
                                <div class="dashboard_tab_title_text">' . $translation['text14'] . '</div>
                            </div>
                        </div>';

    if (empty($team_info['tools_advanced_search_engine_access'])) {
        $return['back_btn'] = '<div class="tools_back_btn" data-back="tools_start_four" data-tools="false">
                                    <img src="/images/back_bg.png" class="back_btn_bg" alt="">
                                    <div class="back_btn_text">' . $translation['text22'] . '</div>
                                </div>';

        $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_no_access dashboard_tab_content_item_active" data-tab="tab1">
                                <div class="dashboard_tab_content_item_no_access_inner">
                                    <img src="/images/tab_no_access_bg.png" class="dashboard_tab_content_item_no_access_bg" alt="">
                                    <div class="dashboard_tab_content_item_no_access_skew_line_top"></div>
                                    <div class="dashboard_tab_content_item_no_access_skew_line_bottom"></div>
                                </div>
                                <div class="dashboard_tab_content_item_no_access_inner_va">
                                    <div class="dashboard_tab_content_item_no_access_title">
                                        <img src="/images/tab_no_access_border_left.png" class="tab_no_access_border_left" alt="">
                                        <img src="/images/tab_no_access_border_left.png" class="tab_no_access_border_right" alt="">
                                        <div class="dashboard_tab_content_item_no_access_title_text">' . $translation['text39'] . '</div>
                                        <img src="/images/dashboard_tab_content_item_no_access_line_left.png" class="dashboard_tab_content_item_no_access_line_left" alt="">
                                        <img src="/images/dashboard_tab_content_item_no_access_line_right.png" class="dashboard_tab_content_item_no_access_line_right" alt="">
                                        <img src="/images/dashboard_tab_content_item_no_access_line_left2.png" class="dashboard_tab_content_item_no_access_line_left2" alt="">
                                        <img src="/images/dashboard_tab_content_item_no_access_line_right2.png" class="dashboard_tab_content_item_no_access_line_right2" alt="">
                                    </div>
                                    <div class="dashboard_tab_content_item_no_access_subtitle">' . $translation['text189'] . '</div>
                                </div>
                            </div>';
    }

    return $return;
}

// tools - Symbol Decoder
private function uploadToolsSymbolDecoder($lang_id, $team_id)
{
    $translation = $this->getWordsByPage('game', $lang_id);

    $team_info = $this->teamInfo($team_id);

    $return = [];

    if (empty($team_info['tools_symbol_decoder_access'])) {
        $return['titles'] = '<div class="dashboard_tab_title dashboard_tab_title_active dashboard_tab_title_can_click_tools" data-tab="tab1" data-step="tools_start_four" data-tools="false">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper">
                                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.75 0H17.75V5L16 7H0V2L1.75 0Z" fill="#00F0FF"/><path d="M1.75 9H17.75V10.5L16 12.5H0V11L1.75 9Z" fill="#00F0FF"/><path d="M1.75 14H17.75V15.5L16 17.5H0V16L1.75 14Z" fill="#00F0FF"/></svg>
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text14'] . '</div>
                                </div>
                            </div>';

        $return['back_btn'] = '<div class="tools_back_btn" data-back="tools_start_four" data-tools="false">
                                    <img src="/images/back_bg.png" class="back_btn_bg" alt="">
                                    <div class="back_btn_text">' . $translation['text22'] . '</div>
                                </div>';

        $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_no_access dashboard_tab_content_item_active" data-tab="tab1">
                                <div class="dashboard_tab_content_item_no_access_inner">
                                    <img src="/images/tab_no_access_bg.png" class="dashboard_tab_content_item_no_access_bg" alt="">
                                    <div class="dashboard_tab_content_item_no_access_skew_line_top"></div>
                                    <div class="dashboard_tab_content_item_no_access_skew_line_bottom"></div>
                                </div>
                                <div class="dashboard_tab_content_item_no_access_inner_va">
                                    <div class="dashboard_tab_content_item_no_access_title">
                                        <img src="/images/tab_no_access_border_left.png" class="tab_no_access_border_left" alt="">
                                        <img src="/images/tab_no_access_border_left.png" class="tab_no_access_border_right" alt="">
                                        <div class="dashboard_tab_content_item_no_access_title_text">' . $translation['text39'] . '</div>
                                        <img src="/images/dashboard_tab_content_item_no_access_line_left.png" class="dashboard_tab_content_item_no_access_line_left" alt="">
                                        <img src="/images/dashboard_tab_content_item_no_access_line_right.png" class="dashboard_tab_content_item_no_access_line_right" alt="">
                                        <img src="/images/dashboard_tab_content_item_no_access_line_left2.png" class="dashboard_tab_content_item_no_access_line_left2" alt="">
                                        <img src="/images/dashboard_tab_content_item_no_access_line_right2.png" class="dashboard_tab_content_item_no_access_line_right2" alt="">
                                    </div>
                                    <div class="dashboard_tab_content_item_no_access_subtitle">' . $translation['text189'] . '</div>
                                </div>
                            </div>';
    } else {
        $return['titles'] = '<div class="dashboard_tab_title dashboard_tab_title_can_click_tools" data-tab="tab1" data-step="tools_start_four" data-tools="false">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper">
                                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.75 0H17.75V5L16 7H0V2L1.75 0Z" fill="#00F0FF"/><path d="M1.75 9H17.75V10.5L16 12.5H0V11L1.75 9Z" fill="#00F0FF"/><path d="M1.75 14H17.75V15.5L16 17.5H0V16L1.75 14Z" fill="#00F0FF"/></svg>
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text14'] . '</div>
                                </div>
                            </div>
                            <div class="dashboard_tab_title dashboard_tab_title_active" data-tab="tab2">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper" style="margin: -4px 0 0;">
                                        <svg width="24" height="32" viewBox="0 0 24 32" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M23.4171 13.7969C23.4166 13.3215 23.2275 12.8658 22.8913 12.5297C22.5552 12.1936 22.0995 12.0045 21.6241 12.0039H21.0331V10.1977C21.0331 10.0744 20.9842 9.95614 20.897 9.86896C20.8098 9.78179 20.6916 9.73281 20.5683 9.73281C20.445 9.73281 20.3268 9.78179 20.2396 9.86896C20.1524 9.95614 20.1034 10.0744 20.1034 10.1977V12.0039H18.7278V10.1977C18.7278 10.0744 18.6788 9.95614 18.5916 9.86896C18.5044 9.78179 18.3862 9.73281 18.2629 9.73281C18.1396 9.73281 18.0214 9.78179 17.9342 9.86896C17.8471 9.95614 17.7981 10.0744 17.7981 10.1977V12.0039H16.5085V10.1977C16.5085 10.0744 16.4595 9.95614 16.3723 9.86896C16.2851 9.78179 16.1669 9.73281 16.0436 9.73281C15.9203 9.73281 15.8021 9.78179 15.7149 9.86896C15.6278 9.95614 15.5788 10.0744 15.5788 10.1977V12.0039H14.1595V9.91981C14.1593 9.6561 14.0545 9.40324 13.8681 9.21674C13.6816 9.03023 13.4288 8.92533 13.1651 8.92505H11.9543C11.6906 8.92533 11.4377 9.03023 11.2512 9.21672C11.0647 9.40321 10.9598 9.65607 10.9596 9.91981V12.0039H9.29117V11.5752C9.29117 11.4519 9.2422 11.3337 9.15502 11.2465C9.06785 11.1593 8.94961 11.1103 8.82633 11.1103C8.70305 11.1103 8.58481 11.1593 8.49764 11.2465C8.41046 11.3337 8.36149 11.4519 8.36149 11.5752V12.0039H6.88195V11.4031C6.88195 11.2798 6.83298 11.1615 6.74581 11.0744C6.65863 10.9872 6.54039 10.9382 6.41711 10.9382C6.29383 10.9382 6.17559 10.9872 6.08842 11.0744C6.00124 11.1615 5.95227 11.2798 5.95227 11.4031V12.0039H3.89766V10.1977C3.89766 10.0744 3.84868 9.95614 3.76151 9.86896C3.67433 9.78179 3.5561 9.73281 3.43281 9.73281C3.30953 9.73281 3.19129 9.78179 3.10412 9.86896C3.01694 9.95614 2.96797 10.0744 2.96797 10.1977V12.0039H2.37695C1.9016 12.0045 1.44588 12.1936 1.10975 12.5297C0.773629 12.8658 0.584547 13.3215 0.583984 13.7969V29.2642C0.584547 29.7396 0.773629 30.1953 1.10975 30.5314C1.44588 30.8675 1.9016 31.0566 2.37695 31.0572H17.5614C17.6288 31.0625 17.6966 31.053 17.76 31.0295C17.8233 31.0059 17.8809 30.9689 17.9285 30.9209L23.2809 25.5686C23.4939 25.3555 23.4139 25.9274 23.4171 13.7969ZM11.889 9.91875C11.8892 9.90156 11.8961 9.88512 11.9083 9.87296C11.9204 9.8608 11.9369 9.85388 11.9541 9.85367H13.1648C13.182 9.85388 13.1984 9.86081 13.2105 9.87298C13.2226 9.88515 13.2295 9.90158 13.2296 9.91875V12.0028H11.8893L11.889 9.91875ZM1.51367 29.2642V13.7969C1.51395 13.568 1.605 13.3486 1.76683 13.1868C1.92867 13.0249 2.14808 12.9339 2.37695 12.9336H3.42378C4.78431 12.9376 7.48944 12.9307 8.83589 12.9336H20.5765H21.6233C21.8522 12.9339 22.0716 13.0249 22.2335 13.1868C22.3953 13.3486 22.4863 13.568 22.4866 13.7969V24.7752H18.9174C18.4421 24.7757 17.9864 24.9648 17.6502 25.3009C17.3141 25.6371 17.125 26.0928 17.1245 26.5681L17.1268 30.1275H2.37695C2.14808 30.1272 1.92867 30.0362 1.76683 29.8743C1.605 29.7125 1.51395 29.4931 1.51367 29.2642ZM19.9279 27.6078L18.0547 29.4807V26.5689C18.055 26.34 18.146 26.1206 18.3078 25.9588C18.4697 25.797 18.6891 25.7059 18.918 25.7056H21.8305L19.9279 27.6078Z" fill="#00F0FF"/><path d="M3.43359 3.75708C3.55686 3.75701 3.67505 3.70801 3.76221 3.62085C3.84937 3.53369 3.89837 3.4155 3.89844 3.29224V1.39966C3.89844 1.27637 3.84946 1.15814 3.76229 1.07096C3.67511 0.983789 3.55688 0.934814 3.43359 0.934814C3.31031 0.934814 3.19207 0.983789 3.1049 1.07096C3.01772 1.15814 2.96875 1.27637 2.96875 1.39966V3.29224C2.96875 3.41552 3.01772 3.53376 3.1049 3.62093C3.19207 3.70811 3.31031 3.75708 3.43359 3.75708Z" fill="#00F0FF"/><path d="M3.43359 8.94315C3.55686 8.94308 3.67505 8.89408 3.76221 8.80692C3.84937 8.71976 3.89837 8.60157 3.89844 8.4783V5.1062C3.89844 4.98292 3.84946 4.86468 3.76229 4.77751C3.67511 4.69033 3.55688 4.64136 3.43359 4.64136C3.31031 4.64136 3.19207 4.69033 3.1049 4.77751C3.01772 4.86468 2.96875 4.98292 2.96875 5.1062V8.47963C2.9691 8.60269 3.01823 8.72058 3.10537 8.80747C3.1925 8.89436 3.31054 8.94315 3.43359 8.94315Z" fill="#00F0FF"/><path d="M6.41797 4.16986C6.54125 4.16986 6.65949 4.12089 6.74666 4.03371C6.83384 3.94654 6.88281 3.8283 6.88281 3.70502V1.39966C6.88281 1.27637 6.83384 1.15814 6.74666 1.07096C6.65949 0.983789 6.54125 0.934814 6.41797 0.934814C6.29468 0.934814 6.17645 0.983789 6.08928 1.07096C6.0021 1.15814 5.95312 1.27637 5.95312 1.39966V3.70502C5.95312 3.8283 6.0021 3.94654 6.08928 4.03371C6.17645 4.12089 6.29468 4.16986 6.41797 4.16986Z" fill="#00F0FF"/><path d="M8.9043 4.16986C8.96535 4.16989 9.02581 4.15789 9.08223 4.13455C9.13864 4.1112 9.1899 4.07696 9.23307 4.03379C9.27624 3.99062 9.31048 3.93936 9.33383 3.88294C9.35718 3.82653 9.36918 3.76607 9.36914 3.70502V1.39966C9.36914 1.27637 9.32017 1.15814 9.23299 1.07096C9.14582 0.983789 9.02758 0.934814 8.9043 0.934814C8.78101 0.934814 8.66278 0.983789 8.5756 1.07096C8.48843 1.15814 8.43945 1.27637 8.43945 1.39966V3.70502C8.43945 3.8283 8.48843 3.94654 8.5756 4.03371C8.66278 4.12089 8.78101 4.16986 8.9043 4.16986Z" fill="#00F0FF"/><path d="M11.1016 3.75708C11.2248 3.75701 11.343 3.70801 11.4302 3.62085C11.5173 3.53369 11.5663 3.4155 11.5664 3.29224V1.39966C11.5664 1.27637 11.5174 1.15814 11.4303 1.07096C11.3431 0.983789 11.2248 0.934814 11.1016 0.934814C10.9783 0.934814 10.86 0.983789 10.7729 1.07096C10.6857 1.15814 10.6367 1.27637 10.6367 1.39966V3.29224C10.6367 3.41552 10.6857 3.53376 10.7729 3.62093C10.86 3.70811 10.9783 3.75708 11.1016 3.75708Z" fill="#00F0FF"/><path d="M13.4551 5.06475C13.5783 5.06468 13.6965 5.01568 13.7837 4.92852C13.8709 4.84136 13.9199 4.72317 13.9199 4.59991V1.39966C13.9199 1.27637 13.8709 1.15814 13.7838 1.07096C13.6966 0.983789 13.5784 0.934814 13.4551 0.934814C13.3318 0.934814 13.2136 0.983789 13.1264 1.07096C13.0392 1.15814 12.9902 1.27637 12.9902 1.39966V4.59991C12.9902 4.72319 13.0392 4.84143 13.1264 4.9286C13.2136 5.01578 13.3318 5.06475 13.4551 5.06475Z" fill="#00F0FF"/><path d="M15.957 2.89672C16.0803 2.89672 16.1985 2.84775 16.2857 2.76057C16.3729 2.6734 16.4219 2.55516 16.4219 2.43188V1.39966C16.4219 1.27637 16.3729 1.15814 16.2857 1.07096C16.1985 0.983789 16.0803 0.934814 15.957 0.934814C15.8337 0.934814 15.7155 0.983789 15.6283 1.07096C15.5412 1.15814 15.4922 1.27637 15.4922 1.39966V2.43188C15.4922 2.55516 15.5412 2.6734 15.6283 2.76057C15.7155 2.84775 15.8337 2.89672 15.957 2.89672Z" fill="#00F0FF"/><path d="M18.2637 2.89672C18.387 2.89672 18.5052 2.84775 18.5924 2.76057C18.6795 2.6734 18.7285 2.55516 18.7285 2.43188V1.39966C18.7285 1.27637 18.6795 1.15814 18.5924 1.07096C18.5052 0.983789 18.387 0.934814 18.2637 0.934814C18.1404 0.934814 18.0222 0.983789 17.935 1.07096C17.8478 1.15814 17.7988 1.27637 17.7988 1.39966V2.43188C17.7988 2.55516 17.8478 2.6734 17.935 2.76057C18.0222 2.84775 18.1404 2.89672 18.2637 2.89672Z" fill="#00F0FF"/><path d="M20.5684 2.89672C20.6294 2.89676 20.6899 2.88476 20.7463 2.86141C20.8027 2.83806 20.854 2.80382 20.8971 2.76065C20.9403 2.71748 20.9745 2.66622 20.9979 2.60981C21.0212 2.55339 21.0332 2.49293 21.0332 2.43188V1.39966C21.0332 1.27637 20.9842 1.15814 20.8971 1.07096C20.8099 0.983789 20.6916 0.934814 20.5684 0.934814C20.4451 0.934814 20.3268 0.983789 20.2397 1.07096C20.1525 1.15814 20.1035 1.27637 20.1035 1.39966V2.43188C20.1035 2.55516 20.1525 2.6734 20.2397 2.76057C20.3268 2.84775 20.4451 2.89672 20.5684 2.89672Z" fill="#00F0FF"/><path d="M20.5684 8.94325C20.6916 8.94325 20.8099 8.89427 20.8971 8.8071C20.9842 8.71992 21.0332 8.60169 21.0332 8.4784V4.22681C21.0332 4.10352 20.9842 3.98529 20.8971 3.89811C20.8099 3.81094 20.6916 3.76196 20.5684 3.76196C20.4451 3.76196 20.3268 3.81094 20.2397 3.89811C20.1525 3.98529 20.1035 4.10352 20.1035 4.22681V8.4784C20.1036 8.60167 20.1526 8.71986 20.2397 8.80702C20.3269 8.89418 20.4451 8.94318 20.5684 8.94325Z" fill="#00F0FF"/><path d="M6.84138 10.0787H8.31719C8.55268 10.0784 8.77845 9.98473 8.94497 9.81822C9.11148 9.6517 9.20516 9.42593 9.20544 9.19044V5.87358C9.20516 5.63811 9.11148 5.41237 8.94495 5.2459C8.77842 5.07942 8.55266 4.98581 8.31719 4.9856H6.84138C6.60591 4.98581 6.38014 5.07942 6.21361 5.2459C6.04709 5.41237 5.95341 5.63811 5.95312 5.87358V9.19044C5.95341 9.42593 6.04708 9.6517 6.2136 9.81822C6.38012 9.98473 6.60588 10.0784 6.84138 10.0787ZM6.88281 5.91528H8.27575V9.149H6.88281V5.91528Z" fill="#00F0FF"/><path d="M15.5781 4.73876V7.96584C15.5784 8.23719 15.6863 8.49735 15.8782 8.68922C16.0701 8.8811 16.3302 8.98902 16.6016 8.9893H17.7039C17.9752 8.98894 18.2353 8.881 18.4271 8.68913C18.6189 8.49726 18.7268 8.23715 18.7271 7.96584V4.73876C18.7268 4.46748 18.6189 4.2074 18.4271 4.01557C18.2353 3.82375 17.9752 3.71586 17.7039 3.71558H16.6016C16.3303 3.71586 16.0702 3.82374 15.8783 4.01556C15.6864 4.20737 15.5785 4.46746 15.5781 4.73876ZM16.5078 4.73876C16.5079 4.71394 16.5178 4.69016 16.5354 4.67263C16.5529 4.65511 16.5768 4.64526 16.6016 4.64526H17.7039C17.7287 4.64533 17.7524 4.65521 17.77 4.67273C17.7875 4.69025 17.7974 4.71399 17.7974 4.73876V7.96584C17.7974 7.99067 17.7876 8.01447 17.7701 8.03205C17.7525 8.04963 17.7287 8.05954 17.7039 8.05961H16.6016C16.5767 8.05961 16.5529 8.04973 16.5353 8.03215C16.5177 8.01456 16.5078 7.99071 16.5078 7.96584V4.73876Z" fill="#00F0FF"/><path d="M13.4551 8.28096C13.5783 8.28089 13.6965 8.23189 13.7837 8.14473C13.8709 8.05757 13.9199 7.93938 13.9199 7.81611V6.46143C13.9199 6.33814 13.8709 6.21991 13.7838 6.13273C13.6966 6.04556 13.5784 5.99658 13.4551 5.99658C13.3318 5.99658 13.2136 6.04556 13.1264 6.13273C13.0392 6.21991 12.9902 6.33814 12.9902 6.46143V7.81611C12.9902 7.9394 13.0392 8.05763 13.1264 8.14481C13.2136 8.23198 13.3318 8.28096 13.4551 8.28096Z" fill="#00F0FF"/><path d="M11.1055 4.64136C10.9822 4.64143 10.864 4.69042 10.7769 4.77758C10.6897 4.86474 10.6407 4.98294 10.6406 5.1062V7.81558C10.6406 7.93886 10.6896 8.0571 10.7768 8.14427C10.8639 8.23145 10.9822 8.28042 11.1055 8.28042C11.2288 8.28042 11.347 8.23145 11.4342 8.14427C11.5213 8.0571 11.5703 7.93886 11.5703 7.81558V5.1062C11.5702 4.98294 11.5212 4.86474 11.4341 4.77758C11.3469 4.69042 11.2287 4.64143 11.1055 4.64136Z" fill="#00F0FF"/><path d="M16.3795 15.6296H7.61914C7.49586 15.6296 7.37762 15.6786 7.29045 15.7658C7.20327 15.853 7.1543 15.9712 7.1543 16.0945C7.1543 16.2178 7.20327 16.336 7.29045 16.4232C7.37762 16.5104 7.49586 16.5593 7.61914 16.5593H16.3795C16.5027 16.5593 16.621 16.5104 16.7082 16.4232C16.7953 16.336 16.8443 16.2178 16.8443 16.0945C16.8443 15.9712 16.7953 15.853 16.7082 15.7658C16.621 15.6786 16.5027 15.6296 16.3795 15.6296Z" fill="#00F0FF"/><path d="M18.3555 16.5593H20.5681C20.6914 16.5593 20.8096 16.5104 20.8968 16.4232C20.984 16.336 21.033 16.2178 21.033 16.0945C21.033 15.9712 20.984 15.853 20.8968 15.7658C20.8096 15.6786 20.6914 15.6296 20.5681 15.6296H18.3555C18.2322 15.6296 18.114 15.6786 18.0268 15.7658C17.9396 15.853 17.8906 15.9712 17.8906 16.0945C17.8906 16.2178 17.9396 16.336 18.0268 16.4232C18.114 16.5104 18.2322 16.5593 18.3555 16.5593Z" fill="#00F0FF"/><path d="M3.43359 16.5593H5.64625C5.76953 16.5593 5.88777 16.5104 5.97494 16.4232C6.06212 16.336 6.11109 16.2178 6.11109 16.0945C6.11109 15.9712 6.06212 15.853 5.97494 15.7658C5.88777 15.6786 5.76953 15.6296 5.64625 15.6296H3.43359C3.31031 15.6296 3.19207 15.6786 3.1049 15.7658C3.01772 15.853 2.96875 15.9712 2.96875 16.0945C2.96875 16.2178 3.01772 16.336 3.1049 16.4232C3.19207 16.5104 3.31031 16.5593 3.43359 16.5593Z" fill="#00F0FF"/><path d="M7.61914 20.1853H12.0232C12.1465 20.1853 12.2647 20.1363 12.3519 20.0492C12.4391 19.962 12.488 19.8437 12.488 19.7205C12.488 19.5972 12.4391 19.4789 12.3519 19.3918C12.2647 19.3046 12.1465 19.2556 12.0232 19.2556H7.61914C7.49586 19.2556 7.37762 19.3046 7.29045 19.3918C7.20327 19.4789 7.1543 19.5972 7.1543 19.7205C7.1543 19.8437 7.20327 19.962 7.29045 20.0492C7.37762 20.1363 7.49586 20.1853 7.61914 20.1853Z" fill="#00F0FF"/><path d="M18.3555 20.1853H20.5681C20.6914 20.1853 20.8096 20.1363 20.8968 20.0492C20.984 19.962 21.033 19.8437 21.033 19.7205C21.033 19.5972 20.984 19.4789 20.8968 19.3918C20.8096 19.3046 20.6914 19.2556 20.5681 19.2556H18.3555C18.2322 19.2556 18.114 19.3046 18.0268 19.3918C17.9396 19.4789 17.8906 19.5972 17.8906 19.7205C17.8906 19.8437 17.9396 19.962 18.0268 20.0492C18.114 20.1363 18.2322 20.1853 18.3555 20.1853Z" fill="#00F0FF"/><path d="M16.3807 19.2556H13.998C13.8748 19.2556 13.7565 19.3046 13.6694 19.3918C13.5822 19.4789 13.5332 19.5972 13.5332 19.7205C13.5332 19.8437 13.5822 19.962 13.6694 20.0492C13.7565 20.1363 13.8748 20.1853 13.998 20.1853H16.3807C16.504 20.1853 16.6222 20.1363 16.7094 20.0492C16.7966 19.962 16.8455 19.8437 16.8455 19.7205C16.8455 19.5972 16.7966 19.4789 16.7094 19.3918C16.6222 19.3046 16.504 19.2556 16.3807 19.2556Z" fill="#00F0FF"/><path d="M3.43359 20.1853H5.64625C5.76953 20.1853 5.88777 20.1363 5.97494 20.0492C6.06212 19.962 6.11109 19.8437 6.11109 19.7205C6.11109 19.5972 6.06212 19.4789 5.97494 19.3918C5.88777 19.3046 5.76953 19.2556 5.64625 19.2556H3.43359C3.31031 19.2556 3.19207 19.3046 3.1049 19.3918C3.01772 19.4789 2.96875 19.5972 2.96875 19.7205C2.96875 19.8437 3.01772 19.962 3.1049 20.0492C3.19207 20.1363 3.31031 20.1853 3.43359 20.1853Z" fill="#00F0FF"/><path d="M10.3421 22.884H7.60352C7.48023 22.884 7.362 22.933 7.27482 23.0202C7.18765 23.1074 7.13867 23.2256 7.13867 23.3489C7.13867 23.4722 7.18765 23.5904 7.27482 23.6776C7.362 23.7647 7.48023 23.8137 7.60352 23.8137H10.3421C10.4654 23.8137 10.5836 23.7647 10.6708 23.6776C10.758 23.5904 10.807 23.4722 10.807 23.3489C10.807 23.2256 10.758 23.1074 10.6708 23.0202C10.5836 22.933 10.4654 22.884 10.3421 22.884Z" fill="#00F0FF"/><path d="M14.8424 22.884H12.2871C12.1638 22.884 12.0456 22.933 11.9584 23.0202C11.8712 23.1074 11.8223 23.2256 11.8223 23.3489C11.8223 23.4722 11.8712 23.5904 11.9584 23.6776C12.0456 23.7647 12.1638 23.8137 12.2871 23.8137H14.8424C14.9657 23.8137 15.0839 23.7647 15.1711 23.6776C15.2583 23.5904 15.3073 23.4722 15.3073 23.3489C15.3073 23.2256 15.2583 23.1074 15.1711 23.0202C15.0839 22.933 14.9657 22.884 14.8424 22.884Z" fill="#00F0FF"/><path d="M5.66219 22.884H3.43359C3.31031 22.884 3.19207 22.933 3.1049 23.0202C3.01772 23.1074 2.96875 23.2256 2.96875 23.3489C2.96875 23.4722 3.01772 23.5904 3.1049 23.6776C3.19207 23.7647 3.31031 23.8137 3.43359 23.8137H5.66219C5.78547 23.8137 5.90371 23.7647 5.99088 23.6776C6.07806 23.5904 6.12703 23.4722 6.12703 23.3489C6.12703 23.2256 6.07806 23.1074 5.99088 23.0202C5.90371 22.933 5.78547 22.884 5.66219 22.884Z" fill="#00F0FF"/><path d="M14.8421 26.5098H12.3027C12.1795 26.5098 12.0612 26.5587 11.974 26.6459C11.8869 26.7331 11.8379 26.8513 11.8379 26.9746C11.8379 27.0979 11.8869 27.2161 11.974 27.3033C12.0612 27.3905 12.1795 27.4395 12.3027 27.4395H14.8421C14.9654 27.4395 15.0836 27.3905 15.1708 27.3033C15.258 27.2161 15.307 27.0979 15.307 26.9746C15.307 26.8513 15.258 26.7331 15.1708 26.6459C15.0836 26.5587 14.9654 26.5098 14.8421 26.5098Z" fill="#00F0FF"/><path d="M5.64625 26.5098H3.43359C3.31031 26.5098 3.19207 26.5587 3.1049 26.6459C3.01772 26.7331 2.96875 26.8513 2.96875 26.9746C2.96875 27.0979 3.01772 27.2161 3.1049 27.3033C3.19207 27.3905 3.31031 27.4395 3.43359 27.4395H5.64625C5.76953 27.4395 5.88777 27.3905 5.97494 27.3033C6.06212 27.2161 6.11109 27.0979 6.11109 26.9746C6.11109 26.8513 6.06212 26.7331 5.97494 26.6459C5.88777 26.5587 5.76953 26.5098 5.64625 26.5098Z" fill="#00F0FF"/><path d="M10.3285 26.5098H7.61914C7.49586 26.5098 7.37762 26.5587 7.29045 26.6459C7.20327 26.7331 7.1543 26.8513 7.1543 26.9746C7.1543 27.0979 7.20327 27.2161 7.29045 27.3033C7.37762 27.3905 7.49586 27.4395 7.61914 27.4395H10.3285C10.4518 27.4395 10.57 27.3905 10.6572 27.3033C10.7444 27.2161 10.7934 27.0979 10.7934 26.9746C10.7934 26.8513 10.7444 26.7331 10.6572 26.6459C10.57 26.5587 10.4518 26.5098 10.3285 26.5098Z" fill="#00F0FF"/></svg>
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text227'] . '</div>
                                </div>
                            </div>';

        $return['back_btn'] = '<div class="tools_back_btn" data-back="tools_start_four" data-tools="false">
                                    <img src="/images/back_bg.png" class="back_btn_bg" alt="">
                                    <div class="back_btn_text">' . $translation['text22'] . '</div>
                                </div>';

        $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_tools_symbol_decoder dashboard_tab_content_item_active" data-tab="tools_symbol_decoder2">
                                    <div class="dashboard_tools_symbol_decoder_inner">
                                        <div class="dashboard_tools_symbol_decoder_inner_title">' . $translation['text227'] . '</div>
                                        <div class="dashboard_tools_symbol_decoder_inner_text">' . $translation['text228'] . '</div>
                                        <div class="dashboard_tools_symbol_decoder_inner_top">
                                            <div class="dashboard_tools_symbol_decoder_inner_left">www.scanme.top</div>
                                            <div class="dashboard_tools_symbol_decoder_inner_right">
                                                <img src="/images/tools_symbol_decoder_phone.png" class="tools_symbol_decoder_phone" alt="">
                                            </div>
                                        </div>
                                        <div class="dashboard_tools_symbol_decoder_inner_bottom">
                                            <div class="dashboard_tools_symbol_decoder_inner_left">' . $translation['text229'] . '</div>
                                            <div class="dashboard_tools_symbol_decoder_inner_right">' . $translation['text230'] . '</div>
                                        </div>
                                    </div>
                                </div>';
    }

    return $return;
}

// tools - 3d Building Scan
private function uploadToolsThreeDBuildingScan($lang_id, $team_id)
{
    $translation = $this->getWordsByPage('game', $lang_id);

    $team_info = $this->teamInfo($team_id);

    $return = [];

    $return['back_btn'] = '<div class="tools_back_btn" data-back="tools_start_four" data-tools="false">
                                <img src="/images/back_bg.png" class="back_btn_bg" alt="">
                                <div class="back_btn_text">' . $translation['text22'] . '</div>
                            </div>';

    if (empty($team_info['tools_3d_bulding_scan_access'])) {
        $return['titles'] = '<div class="dashboard_tab_title dashboard_tab_title_active dashboard_tab_title_can_click_tools" data-tab="tab1" data-step="tools_start_four" data-tools="false">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper">
                                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.75 0H17.75V5L16 7H0V2L1.75 0Z" fill="#00F0FF"/><path d="M1.75 9H17.75V10.5L16 12.5H0V11L1.75 9Z" fill="#00F0FF"/><path d="M1.75 14H17.75V15.5L16 17.5H0V16L1.75 14Z" fill="#00F0FF"/></svg>
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text14'] . '</div>
                                </div>
                            </div>';

        $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_no_access dashboard_tab_content_item_active" data-tab="tab1">
                                <div class="dashboard_tab_content_item_no_access_inner">
                                    <img src="/images/tab_no_access_bg.png" class="dashboard_tab_content_item_no_access_bg" alt="">
                                    <div class="dashboard_tab_content_item_no_access_skew_line_top"></div>
                                    <div class="dashboard_tab_content_item_no_access_skew_line_bottom"></div>
                                </div>
                                <div class="dashboard_tab_content_item_no_access_inner_va">
                                    <div class="dashboard_tab_content_item_no_access_title">
                                        <img src="/images/tab_no_access_border_left.png" class="tab_no_access_border_left" alt="">
                                        <img src="/images/tab_no_access_border_left.png" class="tab_no_access_border_right" alt="">
                                        <div class="dashboard_tab_content_item_no_access_title_text">' . $translation['text39'] . '</div>
                                        <img src="/images/dashboard_tab_content_item_no_access_line_left.png" class="dashboard_tab_content_item_no_access_line_left" alt="">
                                        <img src="/images/dashboard_tab_content_item_no_access_line_right.png" class="dashboard_tab_content_item_no_access_line_right" alt="">
                                        <img src="/images/dashboard_tab_content_item_no_access_line_left2.png" class="dashboard_tab_content_item_no_access_line_left2" alt="">
                                        <img src="/images/dashboard_tab_content_item_no_access_line_right2.png" class="dashboard_tab_content_item_no_access_line_right2" alt="">
                                    </div>
                                    <div class="dashboard_tab_content_item_no_access_subtitle">' . $translation['text189'] . '</div>
                                </div>
                            </div>';
    } else {
        $return['titles'] = '<div class="dashboard_tab_title dashboard_tab_title_can_click_tools" data-tab="tab1" data-step="tools_start_four" data-tools="false">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper">
                                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.75 0H17.75V5L16 7H0V2L1.75 0Z" fill="#00F0FF"/><path d="M1.75 9H17.75V10.5L16 12.5H0V11L1.75 9Z" fill="#00F0FF"/><path d="M1.75 14H17.75V15.5L16 17.5H0V16L1.75 14Z" fill="#00F0FF"/></svg>
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text14'] . '</div>
                                </div>
                            </div>
                            <div class="dashboard_tab_title dashboard_tab_title_active" data-tab="tab2">
                                <div class="dashboard_tab_title_active_skew_right"></div>
                                <div class="dashboard_tab_title_inner">
                                    <div class="dashboard_tab_title_img_wrapper" style="margin: -4px 0 0;">
                                        <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_881_922)"><path d="M27.4173 27.9999H23.9173C23.5953 27.9999 23.334 27.7386 23.334 27.4166V23.9166C23.334 23.5946 23.5953 23.3333 23.9173 23.3333H27.4173C27.7393 23.3333 28.0006 23.5946 28.0006 23.9166V27.4166C28.0006 27.7386 27.7393 27.9999 27.4173 27.9999ZM24.5007 26.8333H26.834V24.4999H24.5007V26.8333Z" fill="#00F0FF"/><path d="M4.08333 27.9999H0.583333C0.261333 27.9999 0 27.7386 0 27.4166V23.9166C0 23.5946 0.261333 23.3333 0.583333 23.3333H4.08333C4.40533 23.3333 4.66667 23.5946 4.66667 23.9166V27.4166C4.66667 27.7386 4.40533 27.9999 4.08333 27.9999ZM1.16667 26.8333H3.5V24.4999H1.16667V26.8333Z" fill="#00F0FF"/><path d="M27.4173 4.66667H23.9173C23.5953 4.66667 23.334 4.40533 23.334 4.08333V0.583333C23.334 0.261333 23.5953 0 23.9173 0H27.4173C27.7393 0 28.0006 0.261333 28.0006 0.583333V4.08333C28.0006 4.40533 27.7393 4.66667 27.4173 4.66667ZM24.5007 3.5H26.834V1.16667H24.5007V3.5Z" fill="#00F0FF"/><path d="M4.08333 4.66667H0.583333C0.261333 4.66667 0 4.40533 0 4.08333V0.583333C0 0.261333 0.261333 0 0.583333 0H4.08333C4.40533 0 4.66667 0.261333 4.66667 0.583333V4.08333C4.66667 4.40533 4.40533 4.66667 4.08333 4.66667ZM1.16667 3.5H3.5V1.16667H1.16667V3.5Z" fill="#00F0FF"/><path d="M2.33333 24.5C2.01133 24.5 1.75 24.2387 1.75 23.9167V21.5833C1.75 21.2613 2.01133 21 2.33333 21C2.65533 21 2.91667 21.2613 2.91667 21.5833V23.9167C2.91667 24.2387 2.65533 24.5 2.33333 24.5ZM2.33333 18.6667C2.01133 18.6667 1.75 18.4053 1.75 18.0833V15.75C1.75 15.428 2.01133 15.1667 2.33333 15.1667C2.65533 15.1667 2.91667 15.428 2.91667 15.75V18.0833C2.91667 18.4053 2.65533 18.6667 2.33333 18.6667ZM2.33333 12.8333C2.01133 12.8333 1.75 12.572 1.75 12.25V9.91667C1.75 9.59467 2.01133 9.33333 2.33333 9.33333C2.65533 9.33333 2.91667 9.59467 2.91667 9.91667V12.25C2.91667 12.572 2.65533 12.8333 2.33333 12.8333ZM2.33333 7C2.01133 7 1.75 6.73867 1.75 6.41667V4.08333C1.75 3.76133 2.01133 3.5 2.33333 3.5C2.65533 3.5 2.91667 3.76133 2.91667 4.08333V6.41667C2.91667 6.73867 2.65533 7 2.33333 7Z" fill="#00F0FF"/><path d="M25.6673 24.5C25.3453 24.5 25.084 24.2387 25.084 23.9167V21.5833C25.084 21.2613 25.3453 21 25.6673 21C25.9893 21 26.2506 21.2613 26.2506 21.5833V23.9167C26.2506 24.2387 25.9893 24.5 25.6673 24.5ZM25.6673 18.6667C25.3453 18.6667 25.084 18.4053 25.084 18.0833V15.75C25.084 15.428 25.3453 15.1667 25.6673 15.1667C25.9893 15.1667 26.2506 15.428 26.2506 15.75V18.0833C26.2506 18.4053 25.9893 18.6667 25.6673 18.6667ZM25.6673 12.8333C25.3453 12.8333 25.084 12.572 25.084 12.25V9.91667C25.084 9.59467 25.3453 9.33333 25.6673 9.33333C25.9893 9.33333 26.2506 9.59467 26.2506 9.91667V12.25C26.2506 12.572 25.9893 12.8333 25.6673 12.8333ZM25.6673 7C25.3453 7 25.084 6.73867 25.084 6.41667V4.08333C25.084 3.76133 25.3453 3.5 25.6673 3.5C25.9893 3.5 26.2506 3.76133 26.2506 4.08333V6.41667C26.2506 6.73867 25.9893 7 25.6673 7Z" fill="#00F0FF"/><path d="M23.9167 26.2499H21.5833C21.2613 26.2499 21 25.9886 21 25.6666C21 25.3446 21.2613 25.0833 21.5833 25.0833H23.9167C24.2387 25.0833 24.5 25.3446 24.5 25.6666C24.5 25.9886 24.2387 26.2499 23.9167 26.2499ZM18.0833 26.2499H15.75C15.428 26.2499 15.1667 25.9886 15.1667 25.6666C15.1667 25.3446 15.428 25.0833 15.75 25.0833H18.0833C18.4053 25.0833 18.6667 25.3446 18.6667 25.6666C18.6667 25.9886 18.4053 26.2499 18.0833 26.2499ZM12.25 26.2499H9.91667C9.59467 26.2499 9.33333 25.9886 9.33333 25.6666C9.33333 25.3446 9.59467 25.0833 9.91667 25.0833H12.25C12.572 25.0833 12.8333 25.3446 12.8333 25.6666C12.8333 25.9886 12.572 26.2499 12.25 26.2499ZM6.41667 26.2499H4.08333C3.76133 26.2499 3.5 25.9886 3.5 25.6666C3.5 25.3446 3.76133 25.0833 4.08333 25.0833H6.41667C6.73867 25.0833 7 25.3446 7 25.6666C7 25.9886 6.73867 26.2499 6.41667 26.2499Z" fill="#00F0FF"/><path d="M23.9167 2.91667H21.5833C21.2613 2.91667 21 2.65533 21 2.33333C21 2.01133 21.2613 1.75 21.5833 1.75H23.9167C24.2387 1.75 24.5 2.01133 24.5 2.33333C24.5 2.65533 24.2387 2.91667 23.9167 2.91667ZM18.0833 2.91667H15.75C15.428 2.91667 15.1667 2.65533 15.1667 2.33333C15.1667 2.01133 15.428 1.75 15.75 1.75H18.0833C18.4053 1.75 18.6667 2.01133 18.6667 2.33333C18.6667 2.65533 18.4053 2.91667 18.0833 2.91667ZM12.25 2.91667H9.91667C9.59467 2.91667 9.33333 2.65533 9.33333 2.33333C9.33333 2.01133 9.59467 1.75 9.91667 1.75H12.25C12.572 1.75 12.8333 2.01133 12.8333 2.33333C12.8333 2.65533 12.572 2.91667 12.25 2.91667ZM6.41667 2.91667H4.08333C3.76133 2.91667 3.5 2.65533 3.5 2.33333C3.5 2.01133 3.76133 1.75 4.08333 1.75H6.41667C6.73867 1.75 7 2.01133 7 2.33333C7 2.65533 6.73867 2.91667 6.41667 2.91667Z" fill="#00F0FF"/><path d="M14 13.125C13.939 13.125 13.878 13.1138 13.82 13.0913L7.32 10.5392C7.127 10.4637 7 10.2738 7 10.0625C7 9.85119 7.127 9.66131 7.32 9.58577L13.82 7.03369C13.935 6.98877 14.064 6.98877 14.179 7.03369L20.679 9.58577C20.873 9.66131 21 9.85119 21 10.0625C21 10.2738 20.873 10.4637 20.68 10.5392L14.18 13.0913C14.122 13.1138 14.061 13.125 14 13.125ZM8.893 10.0625L14 12.0674L19.107 10.0625L14 8.05758L8.893 10.0625Z" fill="#00F0FF"/><path d="M14 21C13.939 21 13.878 20.9891 13.82 20.9674L7.32 18.4945C7.127 18.4213 7 18.2374 7 18.0326V10.1196C7 9.84657 7.224 9.625 7.5 9.625C7.776 9.625 8 9.84657 8 10.1196V17.6933L14 19.9763L20 17.6933V10.1196C20 9.84657 20.224 9.625 20.5 9.625C20.776 9.625 21 9.84657 21 10.1196V18.0326C21 18.2374 20.873 18.4213 20.68 18.4945L14.18 20.9674C14.122 20.9891 14.061 21 14 21Z" fill="#00F0FF"/><path d="M14 21C13.7585 21 13.5625 20.7713 13.5625 20.4896V12.3229C13.5625 12.0412 13.7585 11.8125 14 11.8125C14.2415 11.8125 14.4375 12.0412 14.4375 12.3229V20.4896C14.4375 20.7713 14.2415 21 14 21Z" fill="#00F0FF"/></g><defs><clipPath id="clip0_881_922"><rect width="28" height="28" fill="white"/></clipPath></defs></svg>
                                    </div>
                                    <div class="dashboard_tab_title_text">' . $translation['text238'] . '</div>
                                </div>
                            </div>';

        $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_tools_3d_scan dashboard_tab_content_item_active" data-tab="tools_3d_scan2">
                                <div class="dashboard_tools_3d_scan_inner">
                                    <div class="dashboard_tools_3d_scan_inner_title">' . $translation['text188'] . '</div>
                                    <div class="dashboard_tools_3d_scan_inner_text">' . $translation['text239'] . '</div>
                                    <div class="dashboard_tools_3d_scan_inner_main">
                                        <div class="dashboard_tools_3d_scan_inner_main_left">
                                            <div class="dashboard_tools_3d_scan_inner_main_left_gauge_wrapper">
                                                <div class="dashboard_tools_3d_scan_inner_main_left_gauge">
                                                    <div class="dashboard_tools_3d_scan_inner_main_left_value" data-value="' . $team_info['tools_building_scan_degree'] . '">' . $team_info['tools_building_scan_degree'] . 'k</div>
                                                    <div class="dashboard_tools_3d_scan_inner_main_left_gauge_center_circle">
                                                        <svg width="132" height="80" viewBox="0 0 132 80" fill="none" xmlns="http://www.w3.org/2000/svg"><g filter="url(#filter0_d_2127_1167)"><path d="M111.5 12.4999L125.5 7" stroke="white" stroke-width="3"/></g><path d="M79 24.5L64 23L69.5 36L79 24.5Z" fill="#01E0EE"/><g filter="url(#filter1_d_2127_1167)"><path d="M71 41.5C71 60.0015 56.0015 75 37.5 75C18.9985 75 4 60.0015 4 41.5C4 22.9985 18.9985 8 37.5 8C56.0015 8 71 22.9985 71 41.5Z" fill="#204972"/></g><circle cx="57" cy="33" r="3" fill="url(#paint0_radial_2127_1167)"/><defs><filter id="filter0_d_2127_1167" x="105.951" y="0.60376" width="25.0977" height="18.2922" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB"><feFlood flood-opacity="0" result="BackgroundImageFix"/><feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/><feOffset/><feGaussianBlur stdDeviation="2.5"/><feColorMatrix type="matrix" values="0 0 0 0 1 0 0 0 0 1 0 0 0 0 1 0 0 0 1 0"/><feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_2127_1167"/><feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_2127_1167" result="shape"/></filter><filter id="filter1_d_2127_1167" x="0" y="5" width="75" height="75" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB"><feFlood flood-opacity="0" result="BackgroundImageFix"/><feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/><feOffset dy="1"/><feGaussianBlur stdDeviation="2"/><feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0"/><feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_2127_1167"/><feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_2127_1167" result="shape"/></filter><radialGradient id="paint0_radial_2127_1167" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(57 33) rotate(90) scale(3)"><stop stop-color="#04A8B3"/><stop offset="1" stop-color="#00F0FF"/></radialGradient></defs></svg>
                                                    </div>
                                                    <div class="dashboard_tools_3d_scan_inner_main_left_gauge_clickable"></div>
                                                </div>
                                                <div class="dashboard_tools_3d_scan_inner_main_left_gauge_select_value">' . $translation['text240'] . '</div>
                                                <div class="dashboard_tools_3d_scan_inner_main_left_gauge_cur_value">' . $translation['text241'] . ' <span>' . $team_info['tools_building_scan_degree'] . 'k</span></div>
                                            </div>
                                            <div class="dashboard_tools_3d_scan_inner_main_left_inputs_wrapper">
                                                <div class="dashboard_tools_3d_scan_inner_main_left_input_wrapper dashboard_tools_3d_scan_inner_main_left_input_wrapper1">
                                                    <input type="text" autocomplete="off" value="' . (!empty($team_info['tools_building_scan_input1']) ? $team_info['tools_building_scan_input1'] : '') . '" class="tools_building_scan_input1" placeholder="0">
                                                    <div class="tools_3d_scan_input_arrow_up tools_3d_scan_input_arrow_up1"><svg width="27" height="13" viewBox="0 0 27 13" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M25.3365 12.4019L1.39258 12.4019L13.0225 0.999986L25.3365 12.4019Z" fill="#102348" fill-opacity="0.5" stroke="#00F0FF"/></svg></div>
                                                    <div class="tools_3d_scan_input_arrow_down tools_3d_scan_input_arrow_down1"><svg width="27" height="14" viewBox="0 0 27 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.39202 1L25.3359 1L13.706 12.4019L1.39202 1Z" fill="#FF0303" fill-opacity="0.5" stroke="#FF0303"/></svg></div>
                                                </div>
                                                <div class="dashboard_tools_3d_scan_inner_main_left_input_wrapper dashboard_tools_3d_scan_inner_main_left_input_wrapper2">
                                                    <input type="text" autocomplete="off" value="' . (!empty($team_info['tools_building_scan_input2']) ? $team_info['tools_building_scan_input2'] : '') . '" class="tools_building_scan_input2" placeholder="0">
                                                    <div class="tools_3d_scan_input_arrow_up tools_3d_scan_input_arrow_up2"><svg width="27" height="13" viewBox="0 0 27 13" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M25.3365 12.4019L1.39258 12.4019L13.0225 0.999986L25.3365 12.4019Z" fill="#102348" fill-opacity="0.5" stroke="#00F0FF"/></svg></div>
                                                    <div class="tools_3d_scan_input_arrow_down tools_3d_scan_input_arrow_down2"><svg width="27" height="14" viewBox="0 0 27 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.39202 1L25.3359 1L13.706 12.4019L1.39202 1Z" fill="#FF0303" fill-opacity="0.5" stroke="#FF0303"/></svg></div>
                                                </div>
                                                <div class="dashboard_tools_3d_scan_inner_main_left_input_wrapper dashboard_tools_3d_scan_inner_main_left_input_wrapper3">
                                                    <input type="text" autocomplete="off" value="' . (!empty($team_info['tools_building_scan_input3']) ? $team_info['tools_building_scan_input3'] : '') . '" class="tools_building_scan_input3" placeholder="0">
                                                    <div class="tools_3d_scan_input_arrow_up tools_3d_scan_input_arrow_up3"><svg width="27" height="13" viewBox="0 0 27 13" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M25.3365 12.4019L1.39258 12.4019L13.0225 0.999986L25.3365 12.4019Z" fill="#102348" fill-opacity="0.5" stroke="#00F0FF"/></svg></div>
                                                    <div class="tools_3d_scan_input_arrow_down tools_3d_scan_input_arrow_down3"><svg width="27" height="14" viewBox="0 0 27 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.39202 1L25.3359 1L13.706 12.4019L1.39202 1Z" fill="#FF0303" fill-opacity="0.5" stroke="#FF0303"/></svg></div>
                                                </div>
                                                <div class="dashboard_tools_3d_scan_inner_main_left_input_wrapper dashboard_tools_3d_scan_inner_main_left_input_wrapper4">
                                                    <input type="text" autocomplete="off" value="' . (!empty($team_info['tools_building_scan_input4']) ? $team_info['tools_building_scan_input4'] : '') . '" class="tools_building_scan_input4" placeholder="0">
                                                    <div class="tools_3d_scan_input_arrow_up tools_3d_scan_input_arrow_up4"><svg width="27" height="13" viewBox="0 0 27 13" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M25.3365 12.4019L1.39258 12.4019L13.0225 0.999986L25.3365 12.4019Z" fill="#102348" fill-opacity="0.5" stroke="#00F0FF"/></svg></div>
                                                    <div class="tools_3d_scan_input_arrow_down tools_3d_scan_input_arrow_down4"><svg width="27" height="14" viewBox="0 0 27 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.39202 1L25.3359 1L13.706 12.4019L1.39202 1Z" fill="#FF0303" fill-opacity="0.5" stroke="#FF0303"/></svg></div>
                                                </div>
                                                <div class="dashboard_tools_3d_scan_inner_main_left_input_wrapper dashboard_tools_3d_scan_inner_main_left_input_wrapper5">
                                                    <input type="text" autocomplete="off" value="' . (!empty($team_info['tools_building_scan_input5']) ? $team_info['tools_building_scan_input5'] : '') . '" class="tools_building_scan_input5" placeholder="0">
                                                    <div class="tools_3d_scan_input_arrow_up tools_3d_scan_input_arrow_up5"><svg width="27" height="13" viewBox="0 0 27 13" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M25.3365 12.4019L1.39258 12.4019L13.0225 0.999986L25.3365 12.4019Z" fill="#102348" fill-opacity="0.5" stroke="#00F0FF"/></svg></div>
                                                    <div class="tools_3d_scan_input_arrow_down tools_3d_scan_input_arrow_down5"><svg width="27" height="14" viewBox="0 0 27 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.39202 1L25.3359 1L13.706 12.4019L1.39202 1Z" fill="#FF0303" fill-opacity="0.5" stroke="#FF0303"/></svg></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dashboard_tools_3d_scan_inner_main_right">
                                            <div class="dashboard_tools_3d_scan_inner_main_right_parameters">
                                                <div class="dashboard_tools_3d_scan_inner_main_right_parameter_row">
                                                    <div class="dashboard_tools_3d_scan_inner_main_right_parameter_scale">
                                                        <div class="dashboard_tools_3d_scan_inner_main_right_parameter_scale_title">' . $translation['text242'] . '</div>
                                                        <div class="dashboard_tools_3d_scan_inner_main_right_parameter_scale_dots" data-field="tools_building_scan_address_dot_n">';

                                                            for ($i=0; $i < 5; $i++) { 
                                                                $class = '';
                                                                if ($i < $team_info['tools_building_scan_address_dot_n']) {
                                                                    $class = ' dashboard_tools_3d_scan_inner_main_right_parameter_scale_dot_before_active';
                                                                } elseif ($i == $team_info['tools_building_scan_address_dot_n']) {
                                                                    $class = ' dashboard_tools_3d_scan_inner_main_right_parameter_scale_dot_active';
                                                                }

                                                                $return['content'] .= '<div class="dashboard_tools_3d_scan_inner_main_right_parameter_scale_dot' . $class . '"></div>';
                                                            }

                                $return['content'] .= ' </div>
                                                    </div>
                                                    <div class="dashboard_tools_3d_scan_inner_main_right_parameter_checkbox">
                                                        <span>' . $translation['text242'] . '</span>
                                                        <input type="checkbox" name="tools_3d_scan_checkbox1" id="tools_3d_scan_checkbox1" data-field="tools_building_scan_address_checkbox_n"' . (!empty($team_info['tools_building_scan_address_checkbox_n']) ? ' checked="checked"' : '') . '>
                                                        <label for="tools_3d_scan_checkbox1"></label>
                                                    </div>
                                                </div>
                                                <div class="dashboard_tools_3d_scan_inner_main_right_parameter_row">
                                                    <div class="dashboard_tools_3d_scan_inner_main_right_parameter_scale">
                                                        <div class="dashboard_tools_3d_scan_inner_main_right_parameter_scale_title">' . $translation['text243'] . '</div>
                                                        <div class="dashboard_tools_3d_scan_inner_main_right_parameter_scale_dots" data-field="tools_building_scan_address_dot_s">';

                                                            for ($i=0; $i < 5; $i++) { 
                                                                $class = '';
                                                                if ($i < $team_info['tools_building_scan_address_dot_s']) {
                                                                    $class = ' dashboard_tools_3d_scan_inner_main_right_parameter_scale_dot_before_active';
                                                                } elseif ($i == $team_info['tools_building_scan_address_dot_s']) {
                                                                    $class = ' dashboard_tools_3d_scan_inner_main_right_parameter_scale_dot_active';
                                                                }

                                                                $return['content'] .= '<div class="dashboard_tools_3d_scan_inner_main_right_parameter_scale_dot' . $class . '"></div>';
                                                            }

                                $return['content'] .= ' </div>
                                                    </div>
                                                    <div class="dashboard_tools_3d_scan_inner_main_right_parameter_checkbox">
                                                        <span>' . $translation['text243'] . '</span>
                                                        <input type="checkbox" name="tools_3d_scan_checkbox2" id="tools_3d_scan_checkbox2" data-field="tools_building_scan_address_checkbox_s"' . (!empty($team_info['tools_building_scan_address_checkbox_s']) ? ' checked="checked"' : '') . '>
                                                        <label for="tools_3d_scan_checkbox2"></label>
                                                    </div>
                                                </div>
                                                <div class="dashboard_tools_3d_scan_inner_main_right_parameter_row">
                                                    <div class="dashboard_tools_3d_scan_inner_main_right_parameter_scale">
                                                        <div class="dashboard_tools_3d_scan_inner_main_right_parameter_scale_title">' . $translation['text244'] . '</div>
                                                        <div class="dashboard_tools_3d_scan_inner_main_right_parameter_scale_dots" data-field="tools_building_scan_address_dot_e">';

                                                            for ($i=0; $i < 5; $i++) { 
                                                                $class = '';
                                                                if ($i < $team_info['tools_building_scan_address_dot_e']) {
                                                                    $class = ' dashboard_tools_3d_scan_inner_main_right_parameter_scale_dot_before_active';
                                                                } elseif ($i == $team_info['tools_building_scan_address_dot_e']) {
                                                                    $class = ' dashboard_tools_3d_scan_inner_main_right_parameter_scale_dot_active';
                                                                }

                                                                $return['content'] .= '<div class="dashboard_tools_3d_scan_inner_main_right_parameter_scale_dot' . $class . '"></div>';
                                                            }

                                $return['content'] .= ' </div>
                                                    </div>
                                                    <div class="dashboard_tools_3d_scan_inner_main_right_parameter_checkbox">
                                                        <span>' . $translation['text244'] . '</span>
                                                        <input type="checkbox" name="tools_3d_scan_checkbox3" id="tools_3d_scan_checkbox3" data-field="tools_building_scan_address_checkbox_e"' . (!empty($team_info['tools_building_scan_address_checkbox_e']) ? ' checked="checked"' : '') . '>
                                                        <label for="tools_3d_scan_checkbox3"></label>
                                                    </div>
                                                </div>
                                                <div class="dashboard_tools_3d_scan_inner_main_right_parameter_row">
                                                    <div class="dashboard_tools_3d_scan_inner_main_right_parameter_scale">
                                                        <div class="dashboard_tools_3d_scan_inner_main_right_parameter_scale_title">' . $translation['text245'] . '</div>
                                                        <div class="dashboard_tools_3d_scan_inner_main_right_parameter_scale_dots" data-field="tools_building_scan_address_dot_w">';

                                                            for ($i=0; $i < 5; $i++) { 
                                                                $class = '';
                                                                if ($i < $team_info['tools_building_scan_address_dot_w']) {
                                                                    $class = ' dashboard_tools_3d_scan_inner_main_right_parameter_scale_dot_before_active';
                                                                } elseif ($i == $team_info['tools_building_scan_address_dot_w']) {
                                                                    $class = ' dashboard_tools_3d_scan_inner_main_right_parameter_scale_dot_active';
                                                                }

                                                                $return['content'] .= '<div class="dashboard_tools_3d_scan_inner_main_right_parameter_scale_dot' . $class . '"></div>';
                                                            }

                                $return['content'] .= ' </div>
                                                    </div>
                                                    <div class="dashboard_tools_3d_scan_inner_main_right_parameter_checkbox">
                                                        <span>' . $translation['text245'] . '</span>
                                                        <input type="checkbox" name="tools_3d_scan_checkbox4" id="tools_3d_scan_checkbox4" data-field="tools_building_scan_address_checkbox_w"' . (!empty($team_info['tools_building_scan_address_checkbox_w']) ? ' checked="checked"' : '') . '>
                                                        <label for="tools_3d_scan_checkbox4"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="dashboard_tools_3d_scan_inner_main_right_btn_wrapper">
                                                <div class="tools_3d_scan_btn_bg"></div>
                                                <div class="btn_wrapper btn_wrapper_blue tools_3d_scan_btn" data-scan-en="Scanning" data-scan-no="Skanner">
                                                    <div class="btn btn_blue">
                                                        <span>' . $translation['text246'] . '</span>
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
                            <script>$(function() { updateGaugeValueLoadPage(' . $team_info['tools_building_scan_degree'] . '); });</script>';
    }

    return $return;
}

// tools - Green Pace Group’s secret office
private function uploadToolsSecretOffice($lang_id, $team_id)
{
    $translation = $this->getWordsByPage('game', $lang_id);

    $team_info = $this->teamInfo($team_id);

    $return = [];

    $return['back_btn'] = '<div class="tools_back_btn" data-back="tools_start_four" data-tools="false">
                                <img src="/images/back_bg.png" class="back_btn_bg" alt="">
                                <div class="back_btn_text">' . $translation['text22'] . '</div>
                            </div>';

    $return['titles'] = '<div class="dashboard_tab_title dashboard_tab_title_can_click_tools" data-tab="tab1" data-step="tools_start_four" data-tools="false">
                            <div class="dashboard_tab_title_active_skew_right"></div>
                            <div class="dashboard_tab_title_inner">
                                <div class="dashboard_tab_title_img_wrapper">
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.75 0H17.75V5L16 7H0V2L1.75 0Z" fill="#00F0FF"/><path d="M1.75 9H17.75V10.5L16 12.5H0V11L1.75 9Z" fill="#00F0FF"/><path d="M1.75 14H17.75V15.5L16 17.5H0V16L1.75 14Z" fill="#00F0FF"/></svg>
                                </div>
                                <div class="dashboard_tab_title_text">' . $translation['text14'] . '</div>
                            </div>
                        </div>
                        <div class="dashboard_tab_title" data-tab="tab2">
                            <div class="dashboard_tab_title_active_skew_right"></div>
                            <div class="dashboard_tab_title_inner">
                                <div class="dashboard_tab_title_img_wrapper" style="margin: -4px 0 0;">
                                    <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_881_922)"><path d="M27.4173 27.9999H23.9173C23.5953 27.9999 23.334 27.7386 23.334 27.4166V23.9166C23.334 23.5946 23.5953 23.3333 23.9173 23.3333H27.4173C27.7393 23.3333 28.0006 23.5946 28.0006 23.9166V27.4166C28.0006 27.7386 27.7393 27.9999 27.4173 27.9999ZM24.5007 26.8333H26.834V24.4999H24.5007V26.8333Z" fill="#00F0FF"/><path d="M4.08333 27.9999H0.583333C0.261333 27.9999 0 27.7386 0 27.4166V23.9166C0 23.5946 0.261333 23.3333 0.583333 23.3333H4.08333C4.40533 23.3333 4.66667 23.5946 4.66667 23.9166V27.4166C4.66667 27.7386 4.40533 27.9999 4.08333 27.9999ZM1.16667 26.8333H3.5V24.4999H1.16667V26.8333Z" fill="#00F0FF"/><path d="M27.4173 4.66667H23.9173C23.5953 4.66667 23.334 4.40533 23.334 4.08333V0.583333C23.334 0.261333 23.5953 0 23.9173 0H27.4173C27.7393 0 28.0006 0.261333 28.0006 0.583333V4.08333C28.0006 4.40533 27.7393 4.66667 27.4173 4.66667ZM24.5007 3.5H26.834V1.16667H24.5007V3.5Z" fill="#00F0FF"/><path d="M4.08333 4.66667H0.583333C0.261333 4.66667 0 4.40533 0 4.08333V0.583333C0 0.261333 0.261333 0 0.583333 0H4.08333C4.40533 0 4.66667 0.261333 4.66667 0.583333V4.08333C4.66667 4.40533 4.40533 4.66667 4.08333 4.66667ZM1.16667 3.5H3.5V1.16667H1.16667V3.5Z" fill="#00F0FF"/><path d="M2.33333 24.5C2.01133 24.5 1.75 24.2387 1.75 23.9167V21.5833C1.75 21.2613 2.01133 21 2.33333 21C2.65533 21 2.91667 21.2613 2.91667 21.5833V23.9167C2.91667 24.2387 2.65533 24.5 2.33333 24.5ZM2.33333 18.6667C2.01133 18.6667 1.75 18.4053 1.75 18.0833V15.75C1.75 15.428 2.01133 15.1667 2.33333 15.1667C2.65533 15.1667 2.91667 15.428 2.91667 15.75V18.0833C2.91667 18.4053 2.65533 18.6667 2.33333 18.6667ZM2.33333 12.8333C2.01133 12.8333 1.75 12.572 1.75 12.25V9.91667C1.75 9.59467 2.01133 9.33333 2.33333 9.33333C2.65533 9.33333 2.91667 9.59467 2.91667 9.91667V12.25C2.91667 12.572 2.65533 12.8333 2.33333 12.8333ZM2.33333 7C2.01133 7 1.75 6.73867 1.75 6.41667V4.08333C1.75 3.76133 2.01133 3.5 2.33333 3.5C2.65533 3.5 2.91667 3.76133 2.91667 4.08333V6.41667C2.91667 6.73867 2.65533 7 2.33333 7Z" fill="#00F0FF"/><path d="M25.6673 24.5C25.3453 24.5 25.084 24.2387 25.084 23.9167V21.5833C25.084 21.2613 25.3453 21 25.6673 21C25.9893 21 26.2506 21.2613 26.2506 21.5833V23.9167C26.2506 24.2387 25.9893 24.5 25.6673 24.5ZM25.6673 18.6667C25.3453 18.6667 25.084 18.4053 25.084 18.0833V15.75C25.084 15.428 25.3453 15.1667 25.6673 15.1667C25.9893 15.1667 26.2506 15.428 26.2506 15.75V18.0833C26.2506 18.4053 25.9893 18.6667 25.6673 18.6667ZM25.6673 12.8333C25.3453 12.8333 25.084 12.572 25.084 12.25V9.91667C25.084 9.59467 25.3453 9.33333 25.6673 9.33333C25.9893 9.33333 26.2506 9.59467 26.2506 9.91667V12.25C26.2506 12.572 25.9893 12.8333 25.6673 12.8333ZM25.6673 7C25.3453 7 25.084 6.73867 25.084 6.41667V4.08333C25.084 3.76133 25.3453 3.5 25.6673 3.5C25.9893 3.5 26.2506 3.76133 26.2506 4.08333V6.41667C26.2506 6.73867 25.9893 7 25.6673 7Z" fill="#00F0FF"/><path d="M23.9167 26.2499H21.5833C21.2613 26.2499 21 25.9886 21 25.6666C21 25.3446 21.2613 25.0833 21.5833 25.0833H23.9167C24.2387 25.0833 24.5 25.3446 24.5 25.6666C24.5 25.9886 24.2387 26.2499 23.9167 26.2499ZM18.0833 26.2499H15.75C15.428 26.2499 15.1667 25.9886 15.1667 25.6666C15.1667 25.3446 15.428 25.0833 15.75 25.0833H18.0833C18.4053 25.0833 18.6667 25.3446 18.6667 25.6666C18.6667 25.9886 18.4053 26.2499 18.0833 26.2499ZM12.25 26.2499H9.91667C9.59467 26.2499 9.33333 25.9886 9.33333 25.6666C9.33333 25.3446 9.59467 25.0833 9.91667 25.0833H12.25C12.572 25.0833 12.8333 25.3446 12.8333 25.6666C12.8333 25.9886 12.572 26.2499 12.25 26.2499ZM6.41667 26.2499H4.08333C3.76133 26.2499 3.5 25.9886 3.5 25.6666C3.5 25.3446 3.76133 25.0833 4.08333 25.0833H6.41667C6.73867 25.0833 7 25.3446 7 25.6666C7 25.9886 6.73867 26.2499 6.41667 26.2499Z" fill="#00F0FF"/><path d="M23.9167 2.91667H21.5833C21.2613 2.91667 21 2.65533 21 2.33333C21 2.01133 21.2613 1.75 21.5833 1.75H23.9167C24.2387 1.75 24.5 2.01133 24.5 2.33333C24.5 2.65533 24.2387 2.91667 23.9167 2.91667ZM18.0833 2.91667H15.75C15.428 2.91667 15.1667 2.65533 15.1667 2.33333C15.1667 2.01133 15.428 1.75 15.75 1.75H18.0833C18.4053 1.75 18.6667 2.01133 18.6667 2.33333C18.6667 2.65533 18.4053 2.91667 18.0833 2.91667ZM12.25 2.91667H9.91667C9.59467 2.91667 9.33333 2.65533 9.33333 2.33333C9.33333 2.01133 9.59467 1.75 9.91667 1.75H12.25C12.572 1.75 12.8333 2.01133 12.8333 2.33333C12.8333 2.65533 12.572 2.91667 12.25 2.91667ZM6.41667 2.91667H4.08333C3.76133 2.91667 3.5 2.65533 3.5 2.33333C3.5 2.01133 3.76133 1.75 4.08333 1.75H6.41667C6.73867 1.75 7 2.01133 7 2.33333C7 2.65533 6.73867 2.91667 6.41667 2.91667Z" fill="#00F0FF"/><path d="M14 13.125C13.939 13.125 13.878 13.1138 13.82 13.0913L7.32 10.5392C7.127 10.4637 7 10.2738 7 10.0625C7 9.85119 7.127 9.66131 7.32 9.58577L13.82 7.03369C13.935 6.98877 14.064 6.98877 14.179 7.03369L20.679 9.58577C20.873 9.66131 21 9.85119 21 10.0625C21 10.2738 20.873 10.4637 20.68 10.5392L14.18 13.0913C14.122 13.1138 14.061 13.125 14 13.125ZM8.893 10.0625L14 12.0674L19.107 10.0625L14 8.05758L8.893 10.0625Z" fill="#00F0FF"/><path d="M14 21C13.939 21 13.878 20.9891 13.82 20.9674L7.32 18.4945C7.127 18.4213 7 18.2374 7 18.0326V10.1196C7 9.84657 7.224 9.625 7.5 9.625C7.776 9.625 8 9.84657 8 10.1196V17.6933L14 19.9763L20 17.6933V10.1196C20 9.84657 20.224 9.625 20.5 9.625C20.776 9.625 21 9.84657 21 10.1196V18.0326C21 18.2374 20.873 18.4213 20.68 18.4945L14.18 20.9674C14.122 20.9891 14.061 21 14 21Z" fill="#00F0FF"/><path d="M14 21C13.7585 21 13.5625 20.7713 13.5625 20.4896V12.3229C13.5625 12.0412 13.7585 11.8125 14 11.8125C14.2415 11.8125 14.4375 12.0412 14.4375 12.3229V20.4896C14.4375 20.7713 14.2415 21 14 21Z" fill="#00F0FF"/></g><defs><clipPath id="clip0_881_922"><rect width="28" height="28" fill="white"/></clipPath></defs></svg>
                                </div>
                                <div class="dashboard_tab_title_text">' . $translation['text238'] . '</div>
                            </div>
                        </div>
                        <div class="dashboard_tab_title dashboard_tab_title_active" data-tab="tools_3d_scan3">
                            <div class="dashboard_tab_title_active_skew_right"></div>
                            <div class="dashboard_tab_title_inner">
                                <div class="dashboard_tab_title_img_wrapper" style="margin: -4px 0 0;">
                                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20.0355 4.32767H11.9637C11.849 4.32767 11.739 4.28211 11.6579 4.20101C11.5768 4.11991 11.5312 4.00992 11.5312 3.89523V1.29718C11.5312 1.18249 11.5768 1.0725 11.6579 0.991403C11.739 0.910306 11.849 0.864746 11.9637 0.864746H20.0355C20.1502 0.864746 20.2601 0.910306 20.3412 0.991403C20.4223 1.0725 20.4679 1.18249 20.4679 1.29718V3.89523C20.4679 4.00992 20.4223 4.11991 20.3412 4.20101C20.2601 4.28211 20.1502 4.32767 20.0355 4.32767ZM12.3961 3.4628H19.603V1.72961H12.3961V3.4628Z" fill="#00F0FF"/><path d="M15.2509 31.1351H13.9969C13.8822 31.1351 13.7722 31.0896 13.6911 31.0085C13.61 30.9274 13.5645 30.8174 13.5645 30.7027C13.5645 30.588 13.61 30.478 13.6911 30.3969C13.7722 30.3158 13.8822 30.2703 13.9969 30.2703H15.2509C15.3656 30.2703 15.4756 30.3158 15.5567 30.3969C15.6378 30.478 15.6834 30.588 15.6834 30.7027C15.6834 30.8174 15.6378 30.9274 15.5567 31.0085C15.4756 31.0896 15.3656 31.1351 15.2509 31.1351Z" fill="#00F0FF"/><path d="M30.7031 31.1353H22.5906C22.476 31.1353 22.366 31.0897 22.2849 31.0086C22.2038 30.9275 22.1582 30.8175 22.1582 30.7028C22.1582 30.5881 22.2038 30.4781 22.2849 30.397C22.366 30.3159 22.476 30.2704 22.5906 30.2704H30.2706V28.9774H1.7301V30.2704H10.9712C11.0859 30.2704 11.1959 30.3159 11.277 30.397C11.3581 30.4781 11.4036 30.5881 11.4036 30.7028C11.4036 30.8175 11.3581 30.9275 11.277 31.0086C11.1959 31.0897 11.0859 31.1353 10.9712 31.1353H1.29767C1.18298 31.1353 1.07299 31.0897 0.991891 31.0086C0.910794 30.9275 0.865234 30.8175 0.865234 30.7028V28.545C0.865234 28.4303 0.910794 28.3203 0.991891 28.2392C1.07299 28.1581 1.18298 28.1125 1.29767 28.1125H30.7031C30.8178 28.1125 30.9278 28.1581 31.0089 28.2392C31.0899 28.3203 31.1355 28.4303 31.1355 28.545V30.7028C31.1355 30.8175 31.0899 30.9275 31.0089 31.0086C30.9278 31.0897 30.8178 31.1353 30.7031 31.1353Z" fill="#00F0FF"/><path d="M19.5644 31.1351H18.2801C18.1654 31.1351 18.0554 31.0896 17.9743 31.0085C17.8932 30.9274 17.8477 30.8174 17.8477 30.7027C17.8477 30.588 17.8932 30.478 17.9743 30.3969C18.0554 30.3158 18.1654 30.2703 18.2801 30.2703H19.5644C19.6791 30.2703 19.7891 30.3158 19.8702 30.3969C19.9513 30.478 19.9969 30.588 19.9969 30.7027C19.9969 30.8174 19.9513 30.9274 19.8702 31.0085C19.7891 31.0896 19.6791 31.1351 19.5644 31.1351Z" fill="#00F0FF"/><path d="M22.4874 28.9764H9.51446C9.39978 28.9764 9.28978 28.9308 9.20869 28.8497C9.12759 28.7686 9.08203 28.6587 9.08203 28.544V3.89532C9.08203 3.78063 9.12759 3.67064 9.20869 3.58955C9.28978 3.50845 9.39978 3.46289 9.51446 3.46289H22.4874C22.6021 3.46289 22.7121 3.50845 22.7932 3.58955C22.8743 3.67064 22.9199 3.78063 22.9199 3.89532V28.544C22.9199 28.6587 22.8743 28.7686 22.7932 28.8497C22.7121 28.9308 22.6021 28.9764 22.4874 28.9764ZM9.9469 28.1115H22.055V4.32776H9.9469V28.1115Z" fill="#00F0FF"/><path d="M28.9736 28.9764H22.4871C22.3724 28.9764 22.2624 28.9308 22.1813 28.8497C22.1002 28.7686 22.0547 28.6586 22.0547 28.5439V12.9764C22.0547 12.8617 22.1002 12.7517 22.1813 12.6706C22.2624 12.5895 22.3724 12.5439 22.4871 12.5439H28.9736C29.0883 12.5439 29.1983 12.5895 29.2794 12.6706C29.3605 12.7517 29.406 12.8617 29.406 12.9764V28.5439C29.406 28.6586 29.3605 28.7686 29.2794 28.8497C29.1983 28.9308 29.0883 28.9764 28.9736 28.9764ZM22.9196 28.1115H28.5412V13.4088H22.9196V28.1115Z" fill="#00F0FF"/><path d="M9.51267 28.9765H3.02618C2.91149 28.9765 2.8015 28.9309 2.72041 28.8498C2.63931 28.7687 2.59375 28.6588 2.59375 28.5441V13.8414C2.59375 13.7267 2.63931 13.6167 2.72041 13.5356C2.8015 13.4545 2.91149 13.4089 3.02618 13.4089H9.51267C9.62736 13.4089 9.73735 13.4545 9.81844 13.5356C9.89954 13.6167 9.9451 13.7267 9.9451 13.8414V28.5441C9.9451 28.6588 9.89954 28.7687 9.81844 28.8498C9.73735 28.9309 9.62736 28.9765 9.51267 28.9765ZM3.45861 28.1116H9.08024V14.2738H3.45861V28.1116Z" fill="#00F0FF"/><path d="M9.51409 14.2738H4.64923C4.53454 14.2738 4.42455 14.2283 4.34345 14.1472C4.26236 14.0661 4.2168 13.9561 4.2168 13.8414V11.4864C4.2168 11.3717 4.26236 11.2617 4.34345 11.1806C4.42455 11.0995 4.53454 11.054 4.64923 11.054H9.51409C9.62878 11.054 9.73877 11.0995 9.81987 11.1806C9.90097 11.2617 9.94653 11.3717 9.94653 11.4864V13.8414C9.94653 13.9561 9.90097 14.0661 9.81987 14.1472C9.73877 14.2283 9.62878 14.2738 9.51409 14.2738ZM5.08166 13.409H9.08166V11.9188H5.08166V13.409Z" fill="#00F0FF"/><path d="M9.51436 23.3444H5.83868C5.72399 23.3444 5.614 23.2988 5.53291 23.2177C5.45181 23.1366 5.40625 23.0266 5.40625 22.9119C5.40625 22.7972 5.45181 22.6872 5.53291 22.6061C5.614 22.5251 5.72399 22.4795 5.83868 22.4795H9.51436C9.62905 22.4795 9.73904 22.5251 9.82013 22.6061C9.90123 22.6872 9.94679 22.7972 9.94679 22.9119C9.94679 23.0266 9.90123 23.1366 9.82013 23.2177C9.73904 23.2988 9.62905 23.3444 9.51436 23.3444Z" fill="#00F0FF"/><path d="M9.51436 17.2972H5.83868C5.72399 17.2972 5.614 17.2517 5.53291 17.1706C5.45181 17.0895 5.40625 16.9795 5.40625 16.8648C5.40625 16.7501 5.45181 16.6401 5.53291 16.559C5.614 16.4779 5.72399 16.4324 5.83868 16.4324H9.51436C9.62905 16.4324 9.73904 16.4779 9.82013 16.559C9.90123 16.6401 9.94679 16.7501 9.94679 16.8648C9.94679 16.9795 9.90123 17.0895 9.82013 17.1706C9.73904 17.2517 9.62905 17.2972 9.51436 17.2972Z" fill="#00F0FF"/><path d="M9.51436 20.3209H5.83868C5.72399 20.3209 5.614 20.2754 5.53291 20.1943C5.45181 20.1132 5.40625 20.0032 5.40625 19.8885C5.40625 19.7738 5.45181 19.6638 5.53291 19.5827C5.614 19.5016 5.72399 19.4561 5.83868 19.4561H9.51436C9.62905 19.4561 9.73904 19.5016 9.82013 19.5827C9.90123 19.6638 9.94679 19.7738 9.94679 19.8885C9.94679 20.0032 9.90123 20.1132 9.82013 20.1943C9.73904 20.2754 9.62905 20.3209 9.51436 20.3209Z" fill="#00F0FF"/><path d="M9.51436 26.3683H5.83868C5.72399 26.3683 5.614 26.3227 5.53291 26.2416C5.45181 26.1605 5.40625 26.0505 5.40625 25.9359C5.40625 25.8212 5.45181 25.7112 5.53291 25.6301C5.614 25.549 5.72399 25.5034 5.83868 25.5034H9.51436C9.62905 25.5034 9.73904 25.549 9.82013 25.6301C9.90123 25.7112 9.94679 25.8212 9.94679 25.9359C9.94679 26.0505 9.90123 26.1605 9.82013 26.2416C9.73904 26.3227 9.62905 26.3683 9.51436 26.3683Z" fill="#00F0FF"/><path d="M25.2979 17.3007H22.4871C22.3724 17.3007 22.2624 17.2551 22.1813 17.174C22.1002 17.0929 22.0547 16.9829 22.0547 16.8682C22.0547 16.7535 22.1002 16.6435 22.1813 16.5624C22.2624 16.4814 22.3724 16.4358 22.4871 16.4358H25.2979C25.4126 16.4358 25.5226 16.4814 25.6037 16.5624C25.6848 16.6435 25.7304 16.7535 25.7304 16.8682C25.7304 16.9829 25.6848 17.0929 25.6037 17.174C25.5226 17.2551 25.4126 17.3007 25.2979 17.3007Z" fill="#00F0FF"/><path d="M25.2979 21.1925H22.4871C22.3724 21.1925 22.2624 21.1469 22.1813 21.0659C22.1002 20.9848 22.0547 20.8748 22.0547 20.7601C22.0547 20.6454 22.1002 20.5354 22.1813 20.4543C22.2624 20.3732 22.3724 20.3276 22.4871 20.3276H25.2979C25.4126 20.3276 25.5226 20.3732 25.6037 20.4543C25.6848 20.5354 25.7304 20.6454 25.7304 20.7601C25.7304 20.8748 25.6848 20.9848 25.6037 21.0659C25.5226 21.1469 25.4126 21.1925 25.2979 21.1925Z" fill="#00F0FF"/><path d="M25.2979 25.0846H22.4871C22.3724 25.0846 22.2624 25.039 22.1813 24.9579C22.1002 24.8768 22.0547 24.7668 22.0547 24.6522C22.0547 24.5375 22.1002 24.4275 22.1813 24.3464C22.2624 24.2653 22.3724 24.2197 22.4871 24.2197H25.2979C25.4126 24.2197 25.5226 24.2653 25.6037 24.3464C25.6848 24.4275 25.7304 24.5375 25.7304 24.6522C25.7304 24.7668 25.6848 24.8768 25.6037 24.9579C25.5226 25.039 25.4126 25.0846 25.2979 25.0846Z" fill="#00F0FF"/><path d="M19.711 8.32775H12.2879C12.1732 8.32775 12.0632 8.28219 11.9821 8.2011C11.901 8.12 11.8555 8.01001 11.8555 7.89532C11.8555 7.78063 11.901 7.67064 11.9821 7.58955C12.0632 7.50845 12.1732 7.46289 12.2879 7.46289H19.711C19.8257 7.46289 19.9357 7.50845 20.0168 7.58955C20.0979 7.67064 20.1435 7.78063 20.1435 7.89532C20.1435 8.01001 20.0979 8.12 20.0168 8.2011C19.9357 8.28219 19.8257 8.32775 19.711 8.32775Z" fill="#00F0FF"/><path d="M19.711 12.2379H12.2879C12.1732 12.2379 12.0632 12.1923 11.9821 12.1113C11.901 12.0302 11.8555 11.9202 11.8555 11.8055C11.8555 11.6908 11.901 11.5808 11.9821 11.4997C12.0632 11.4186 12.1732 11.373 12.2879 11.373H19.711C19.8257 11.373 19.9357 11.4186 20.0168 11.4997C20.0979 11.5808 20.1435 11.6908 20.1435 11.8055C20.1435 11.9202 20.0979 12.0302 20.0168 12.1113C19.9357 12.1923 19.8257 12.2379 19.711 12.2379Z" fill="#00F0FF"/><path d="M19.711 16.1442H12.2879C12.1732 16.1442 12.0632 16.0986 11.9821 16.0175C11.901 15.9364 11.8555 15.8264 11.8555 15.7117C11.8555 15.597 11.901 15.4871 11.9821 15.406C12.0632 15.3249 12.1732 15.2793 12.2879 15.2793H19.711C19.8257 15.2793 19.9357 15.3249 20.0168 15.406C20.0979 15.4871 20.1435 15.597 20.1435 15.7117C20.1435 15.8264 20.0979 15.9364 20.0168 16.0175C19.9357 16.0986 19.8257 16.1442 19.711 16.1442Z" fill="#00F0FF"/><path d="M19.171 28.9766H12.8289C12.7142 28.9766 12.6042 28.931 12.5231 28.8499C12.442 28.7688 12.3965 28.6588 12.3965 28.5441V21.1859C12.3965 21.0712 12.442 20.9612 12.5231 20.8801C12.6042 20.799 12.7142 20.7534 12.8289 20.7534H19.171C19.2857 20.7534 19.3957 20.799 19.4768 20.8801C19.5578 20.9612 19.6034 21.0712 19.6034 21.1859V28.5441C19.6034 28.6588 19.5578 28.7688 19.4768 28.8499C19.3957 28.931 19.2857 28.9766 19.171 28.9766ZM13.2613 28.1117H18.7385V21.6183H13.2613V28.1117Z" fill="#00F0FF"/></svg>
                                </div>
                                <div class="dashboard_tab_title_text">' . $translation['text249'] . '</div>
                            </div>
                        </div>';

    /*$return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_tools_secret_office dashboard_tab_content_item_active" data-tab="tools_3d_scan3">
                            <div class="dashboard_tools_secret_office_inner">
                                <!-- <img src="/images/tools_secrete_office_example.png" alt=""> -->
                                <script src="https://static.matterport.com/showcase-sdk/latest.js"></script>
                                <iframe
                                    width="853"
                                    height="480"
                                    src="https://my.matterport.com/show?m=XqyBcKuG8hd&play=1"
                                    frameborder="0"
                                    allow="fullscreen; vr"
                                    id="matterport-iframe">
                                </iframe>
                            </div>
                        </div>';*/
    $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_tools_secret_office dashboard_tab_content_item_active" data-tab="tools_3d_scan3">
                            <div class="dashboard_tools_secret_office_inner">
                                <!-- <img src="/images/tools_secrete_office_example.png" alt=""> -->
                                <script src="https://static.matterport.com/showcase-sdk/latest.js"></script>
                                <iframe
                                    width="853"
                                    height="480"
                                    src="https://my.matterport.com/show/?m=ikn8tusTdmG&play=1"
                                    frameborder="0"
                                    allow="fullscreen; vr"
                                    id="matterport-iframe">
                                </iframe>
                            </div>
                        </div>';

    return $return;
}
}
