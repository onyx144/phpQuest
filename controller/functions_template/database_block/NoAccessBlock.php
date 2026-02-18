<?php

trait NoAccessBlock
{
    /**
     * databases - нет доступа
     * @param int $lang_id
     * @param string|false $no_access_text
     * @param bool $back_btn
     * @return array
     */
    private function uploadDatabasesNoAccess($lang_id, $no_access_text = false, $back_btn = false)
    {
        $translation = $this->getWordsByPage(null, $lang_id);

        $return = [];

        $return['titles'] = '<div class="flex items-center gap-3 mb-6">
            <div class="p-2 rounded-lg bg-primary/20 border border-primary/30">
                <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.75 0H21V2L19.25 4H0V2L1.75 0Z" fill="#00F0FF"></path><path d="M1.75 6H7.25V19L5.5 21H0V8L1.75 6Z" fill="#00F0FF"></path><path d="M11.75 10H21V13L19.25 15H10V12L11.75 10Z" fill="#00F0FF"></path><path d="M10.75 6H21V8L20.25 9H10V7L10.75 6Z" fill="#00F0FF"></path><path d="M11.75 16H21V19L19.25 21H10V18L11.75 16Z" fill="#00F0FF"></path></svg>
            </div>
            <h2 class="text-3xl font-bold neon-text">' . $translation['text11'] . '</h2>
        </div>';

        if (!$no_access_text) {
            $no_access_text = $translation['text40'];
        } else {
            $no_access_text = $translation[$no_access_text];
        }

        if ($back_btn) {
            $return['back_btn'] = '<div class="dashboard_back_btn" data-back="databases_start_four" data-action-id-back="28" data-database="false">
                                    <img src="/images/back_bg.png" class="back_btn_bg" alt="">
                                    <div class="back_btn_text">' . $translation['text22'] . '</div>
                                </div>';
        }

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
                                <div class="dashboard_tab_content_item_no_access_subtitle">' . $no_access_text . '</div>
                            </div>
                        </div>';

        return $return;
    }
}
