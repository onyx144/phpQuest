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
                    ' . $translation['open'] . '
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
                    ' . $translation['open'] . '
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
                    ' . $translation['launch'] . '
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
                    ' . $translation['launch'] . '
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

private function getToolsSymbolDecoderTitles($translation)
{
    return renderCyberBreadcrumbs([
        [
            'text' => $translation['text14'],
            'url' => '#',
            'data' => [
                'tab' => 'tab1',
                'step' => 'tools_start_four',
                'tools' => 'false',
            ],
        ],
        [
            'text' => $translation['text227'],
            'data' => [
                'tab' => 'tab2',
            ],
        ],
    ]);
}

// tools - Symbol Decoder
private function uploadToolsSymbolDecoder($lang_id, $team_id)
{
    $translation = $this->getWordsByPage('game', $lang_id);

    $team_info = $this->teamInfo($team_id);

    $return = [];

    if (empty($team_info['tools_symbol_decoder_access'])) {
        $return['titles'] = $this->getToolsSymbolDecoderTitles($translation);

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
        $return['titles'] = $this->getToolsSymbolDecoderTitles($translation);

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

private function getToolsThreeDBuildingScanTitles($translation)
{
    return renderCyberBreadcrumbs([
        [
            'text' => $translation['text14'],
            'url' => '#',
            'data' => [
                'tab' => 'tab1',
                'step' => 'tools_start_four',
                'tools' => 'false',
            ],
        ],
        [
            'text' => $translation['text238'],
            'data' => [
                'tab' => 'tab2',
            ],
        ],
    ]);
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
        $return['titles'] = $this->getToolsThreeDBuildingScanTitles($translation);

        $voiceClipWidget = '';
        if ($this->isVoiceDecoderStage($team_info)) {
            $audio_find = $this->parseTeamAudioFind($team_info);
            if (!in_array(4, $audio_find, true)) {
                $voiceClipId = 4;
                $voiceClipPos = 'tools';
                ob_start();
                require ROOT . '/view/template/game/voice_clip_widget.php';
                $voiceClipWidget = ob_get_clean();
            }
        }

        $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_no_access dashboard_tab_content_item_active" data-tab="tab1">
                                <div class="dashboard_tab_content_item_no_access_inner">
                                    ' . $voiceClipWidget . '
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
        $return['titles'] = $this->getToolsThreeDBuildingScanTitles($translation);

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
