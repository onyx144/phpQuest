<?php

trait Calls
{
public function uploadTypeTabsCallsStep($step, $lang_id, $team_id)
{
    switch ($step) {
        case 'no_access': $return = $this->uploadCallsNoAccess($lang_id); break;
        case 'call_list': $return = $this->uploadCallsList($team_id, $lang_id); break;
        
        default: $return = $this->uploadCallsNoAccess($lang_id); break;
    }

    return $return;
}

// calls - нет доступа
private function uploadCallsNoAccess($lang_id)
{
    $translation = $this->getWordsByPage('game', $lang_id);

    $return = [];

    $return['titles'] = '<div class="flex items-center gap-3 mb-6">
            <div class="p-2 rounded-lg bg-primary/20 border border-primary/30">
                <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.75 0H21V2L19.25 4H0V2L1.75 0Z" fill="#00F0FF"></path><path d="M1.75 6H7.25V19L5.5 21H0V8L1.75 6Z" fill="#00F0FF"></path><path d="M11.75 10H21V13L19.25 15H10V12L11.75 10Z" fill="#00F0FF"></path><path d="M10.75 6H21V8L20.25 9H10V7L10.75 6Z" fill="#00F0FF"></path><path d="M11.75 16H21V19L19.25 21H10V18L11.75 16Z" fill="#00F0FF"></path></svg>
            </div>
            <h2 class="text-3xl font-bold neon-text">' . $translation['text11'] . '</h2>
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
                                <div class="dashboard_tab_content_item_no_access_subtitle">' . $translation['text40'] . '</div>
                            </div>
                        </div>';

    return $return;
}

// calls - список звонков
private function uploadCallsList($team_id, $lang_id)
{
$translation = $this->getWordsByPage('game', $lang_id);
$team_info   = $this->teamInfo($team_id);

$return = [];

$return['titles'] = '
         <div class="flex items-center gap-3 mb-6">
            <div class="p-2 rounded-lg bg-primary/20 border border-primary/30">
                <svg width="24" height="24" fill="currentColor" class="text-primary">
                    <path d="M6.62 10.79a15.053 15.053 0 006.59 6.59l2.2-2.2a1 1 0 011.11-.21 11.72 11.72 0 003.64.59 1 1 0 011 1V20a1 1 0 01-1 1C9.28 21 3 14.72 3 7a1 1 0 011-1h3.5a1 1 0 011 1c0 1.27.2 2.52.59 3.64a1 1 0 01-.21 1.11l-2.26 2.24z"/>
                </svg>
            </div>
            <h2 class="text-3xl font-bold neon-text">' . $translation['text12'] . '</h2>
        </div>
';

$return['content'] = '<div class="space-y-3">';

if ($team_info) {
    $active_calls = json_decode($team_info['active_calls'], true);

    foreach ($active_calls as $call) {
        $sql = "
            SELECT c.type, cd.video, cd.name, cd.video_with_path
            FROM calls c
            JOIN calls_description cd ON c.id = cd.call_id
            WHERE c.id = {?} AND cd.lang_id = {?}
        ";
        $call_info = $this->db->selectRow($sql, [$call['id'], $lang_id]);

        // Иконка звонка
        $icon = '';
        if ($call_info) {
            if ($call_info['type'] == 'incoming') {
                $icon = '<img src="/images/incoming_icon.png" alt="Входящий">';
            } elseif ($call_info['type'] == 'outgoing') {
                $icon = '<img src="/images/outgoing_icon.png" alt="Исходящий">';
            } else {
                $icon = '<img src="/images/missed_icon.png" alt="Пропущенный">';
            }
        }

        // Дата и время
        $datetime = '';
        if (!empty($call['datetime'])) {
            $dt_object = new DateTime($call['datetime']);
            $datetime  = $dt_object->format('d.m.Y H:i:s');
        }

        // Длительность
        $duration = !empty($call['duration']) ? $call['duration'] : '00:00';

        // Кнопка прослушивания
        $listen_btn = '';
        if (!empty($call_info['video']) || !empty($call_info['video_with_path'])) {
            $listen_btn = '
                <button 
                    class="px-3 py-1 rounded-lg border border-primary/30 text-primary hover:bg-primary/20 transition-all duration-300 flex items-center gap-2"
                    data-path="' . (!empty($call_info['video']) ? $call_info['video'] : '') . '"
                    data-video-with-path="' . (!empty($call_info['video_with_path']) ? $call_info['video_with_path'] : '') . '">
                    ▶ ' . $translation['text55'] . '
                </button>';
        }

        $return['content'] .= '
            <div class="bg-muted/20 border-border/50 hover:bg-muted/30 transition-all duration-300 p-4 rounded-lg flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        ' . $icon . '
                        <div class="p-2 rounded-full bg-muted/50">
                            <img src="/images/user_icon.png" class="h-4 w-4 opacity-70" alt="User">
                        </div>
                    </div>
                    <div>
                        <h3 class="font-semibold text-foreground text-lg">' . (!empty($call_info['name']) ? $call_info['name'] : $translation['text175']) . '</h3>
                        <div class="flex items-center gap-3 text-muted-foreground text-base mt-1">
                            <span>' . $datetime . '</span>
                            ' . ($duration !== '00:00' ? '<span>' . $translation['text176'] . ': ' . $duration . '</span>' : '') . '
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    ' . $listen_btn . '
                </div>
            </div>';
    }
}

$return['content'] .= ' </div>';

return $return;
}
}
