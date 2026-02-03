<?php

trait Dashboard
{
public function uploadTypeTabsDashboardStep($step, $lang_id, $team_id)
{
    switch ($step) {
        case 'accept_new_mission': $return = $this->uploadDashboardNewMission($lang_id); break;
        case 'company_name': $return = $this->uploadDashboardCompanyName($lang_id); break;
        case 'geo_coordinates': $return = $this->uploadDashboardGeoCoordinates($lang_id); break;
        case 'african_partner': $return = $this->uploadDashboardAfricanPartner($lang_id, $team_id); break;
        case 'metting_place': $return = $this->uploadDashboardMettingPlace($lang_id, $team_id); break;
        case 'room_name': $return = $this->uploadDashboardRoomName($lang_id, $team_id); break;
        case 'password': $return = $this->uploadDashboardPassword($lang_id, $team_id); break;
        
        default: $return = $this->uploadDashboardNewMission($lang_id); break;
    }

    return $return;
}

// dashboard - новая миссия
private function uploadDashboardNewMission($lang_id)
{
    $translation = $this->getWordsByPage('game', $lang_id);

    $return = [];

    $return['titles'] = '
                         <div class="flex items-center gap-3 mb-6">
            <div class="p-2 rounded-lg bg-primary/20 border border-primary/30">
                <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.75 0H21V2L19.25 4H0V2L1.75 0Z" fill="#00F0FF"></path><path d="M1.75 6H7.25V19L5.5 21H0V8L1.75 6Z" fill="#00F0FF"></path><path d="M11.75 10H21V13L19.25 15H10V12L11.75 10Z" fill="#00F0FF"></path><path d="M10.75 6H21V8L20.25 9H10V7L10.75 6Z" fill="#00F0FF"></path><path d="M11.75 16H21V19L19.25 21H10V18L11.75 16Z" fill="#00F0FF"></path></svg>
            </div>
            <h2 class="text-3xl font-bold neon-text">' . $translation['text11'] . '</h2>
        </div>';

    $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_new_mission dashboard_tab_content_item_active" data-tab="tab1">
                                                                <div class="dashboard_tab_content_item_new_mission_title">ACCEPT A NEW MISSION</div>
                                <div class="dashboard_tab_content_item_new_mission_input_wrapper mission_input">
                                    <input type="text" placeholder="Enter mission code name" autocomplete="off" class="dashboard_tab_content_item_new_mission_input">
                                </div>
                                <div class="btn_wrapper btn_wrapper_blue dashboard_tab_content_item_new_mission_accept">
                                    <div class="btn btn_blue">
                                        <span>ACCEPT</span>
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
                                <div class="dashboard_tab_content_item_new_mission_text_bottom">Timer starts after you have accepted a new mission.</div>
                        </div>';

    return $return;
}

// dashboard - company name
private function uploadDashboardCompanyName($lang_id)
{
    $translation = $this->getWordsByPage('game', $lang_id);

    $return = [];

    $return['titles'] = '<div class="flex items-center gap-3 mb-6">
            <div class="p-2 rounded-lg bg-primary/20 border border-primary/30">
                <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.75 0H21V2L19.25 4H0V2L1.75 0Z" fill="#00F0FF"></path><path d="M1.75 6H7.25V19L5.5 21H0V8L1.75 6Z" fill="#00F0FF"></path><path d="M11.75 10H21V13L19.25 15H10V12L11.75 10Z" fill="#00F0FF"></path><path d="M10.75 6H21V8L20.25 9H10V7L10.75 6Z" fill="#00F0FF"></path><path d="M11.75 16H21V19L19.25 21H10V18L11.75 16Z" fill="#00F0FF"></path></svg>
            </div>
            <h2 class="text-3xl font-bold neon-text">' . $translation['text11'] . '</h2>
        </div>';

    $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_company_name dashboard_tab_content_item_active" data-tab="tab1">
                            <div class="dashboard_tab_content_item_company_name_top">
                                <div class="dashboard_tab_content_item_company_name_top_left">
                                    <img src="/images/dashboard_company_name_top_left_bg.png" class="dashboard_company_name_top_left_bg" alt="">
                                    <div class="dashboard_tab_content_item_company_name_top_text">
                                        <span>' . $translation['text48'] . '</span>
                                    </div>
                                </div>
                                <div class="dashboard_tab_content_item_company_name_top_right">
                                    <div class="dashboard_tab_content_item_company_name_top_right_title">' . $translation['text49'] . '</div>
                                    <div class="dashboard_tab_content_item_company_name_top_right_text">' . $translation['text50'] . '</div>
                                </div>
                            </div>
                            <div class="dashboard_tab_content_item_company_name_inner">
                                <img src="/images/dashboard_tab_content_item_company_name_inner_bg.png" class="dashboard_tab_content_item_company_name_inner_bg" alt="">
                                <img src="/images/dashboard_tab_content_item_company_name_inner_bg2.png" class="dashboard_tab_content_item_company_name_inner_bg_left_top" alt="">
                                <img src="/images/dashboard_tab_content_item_company_name_inner_bg2.png" class="dashboard_tab_content_item_company_name_inner_bg_right_bottom" alt="">

                                <div class="dashboard_tab_content_item_company_name_input_wrapper">
                                    <div class="dashboard_tab_content_item_company_name_input_border_left"></div>
                                    <div class="dashboard_tab_content_item_company_name_input_border_right"></div>
                                    <img src="/images/dashboard_new_mission_line.png" class="dashboard_tab_content_item_company_name_input_line_left" alt="">
                                    <img src="/images/dashboard_new_mission_line.png" class="dashboard_tab_content_item_company_name_input_line_right" alt="">
                                    <input type="text" placeholder="' . $translation['text51'] . '" autocomplete="off" class="dashboard_tab_content_item_company_name_input">
                                    <div class="dashboard_tab_content_item_company_name_error">' . $translation['text86'] . '</div>
                                </div>
                                <div class="btn_wrapper btn_wrapper_blue dashboard_tab_content_item_company_name_investigate">
                                    <div class="btn btn_blue">
                                        <span>' . $translation['text52'] . '</span>
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
                        </div>';

    return $return;
}

// dashboard - geo coordinates
private function uploadDashboardGeoCoordinates($lang_id)
{
    $translation = $this->getWordsByPage('game', $lang_id);

    $return = [];

    $return['titles'] = '<div class="flex items-center gap-3 mb-6">
            <div class="p-2 rounded-lg bg-primary/20 border border-primary/30">
                <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.75 0H21V2L19.25 4H0V2L1.75 0Z" fill="#00F0FF"></path><path d="M1.75 6H7.25V19L5.5 21H0V8L1.75 6Z" fill="#00F0FF"></path><path d="M11.75 10H21V13L19.25 15H10V12L11.75 10Z" fill="#00F0FF"></path><path d="M10.75 6H21V8L20.25 9H10V7L10.75 6Z" fill="#00F0FF"></path><path d="M11.75 16H21V19L19.25 21H10V18L11.75 16Z" fill="#00F0FF"></path></svg>
            </div>
            <h2 class="text-3xl font-bold neon-text">' . $translation['text11'] . '</h2>
        </div>';

    $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_geo_coordinates dashboard_tab_content_item_active" data-tab="tab1">
                            <div class="dashboard_tab_content_item_company_name_top">
                                <div class="dashboard_tab_content_item_company_name_top_left">
                                    <img src="/images/dashboard_company_name_top_left_bg.png" class="dashboard_company_name_top_left_bg" alt="">
                                    <div class="dashboard_tab_content_item_company_name_top_text">
                                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20.1235 15.4248C19.8114 15.2534 19.4196 15.3674 19.2483 15.6793C19.0769 15.9913 19.1908 16.3832 19.5028 16.5545C20.2706 16.9763 20.7109 17.4439 20.7109 17.8374C20.7109 18.3187 20.029 19.0646 18.1151 19.7149C16.2242 20.3574 13.6974 20.7111 11 20.7111C8.30264 20.7111 5.77577 20.3574 3.88493 19.7149C1.97098 19.0647 1.28906 18.3187 1.28906 17.8374C1.28906 17.4439 1.72941 16.9763 2.49717 16.5545C2.80917 16.3831 2.92312 15.9913 2.75172 15.6793C2.58032 15.3673 2.18857 15.2533 1.87649 15.4247C1.02046 15.895 0 16.6953 0 17.8374C0 18.7129 0.602078 19.961 3.47024 20.9355C5.49115 21.622 8.16527 22.0002 11 22.0002C13.8347 22.0002 16.5089 21.622 18.5298 20.9355C21.3979 19.961 22 18.7129 22 17.8374C22 16.6953 20.9795 15.895 20.1235 15.4248Z" fill="white"/><path d="M6.13501 18.7827C7.44341 19.1523 9.17157 19.3559 11.0011 19.3559C12.8307 19.3559 14.5589 19.1524 15.8673 18.7827C17.468 18.3306 18.2796 17.676 18.2796 16.8373C18.2796 15.9986 17.468 15.3441 15.8673 14.8919C15.5119 14.7916 15.1254 14.7035 14.7146 14.6284C14.4914 15.0139 14.2576 15.4108 14.0132 15.819C14.468 15.8872 14.895 15.9709 15.2827 16.0697C16.4607 16.3697 16.8911 16.7075 16.9796 16.8374C16.8911 16.9672 16.4608 17.305 15.2828 17.605C14.1678 17.889 12.7332 18.051 11.2223 18.0655C11.1491 18.0709 11.0754 18.074 11.0011 18.074C10.9268 18.074 10.8531 18.0709 10.7799 18.0655C9.26902 18.051 7.83446 17.8891 6.71947 17.605C5.54144 17.305 5.11111 16.9672 5.02263 16.8374C5.11111 16.7075 5.54148 16.3697 6.71951 16.0697C7.10726 15.9709 7.53424 15.8872 7.98907 15.819C7.74462 15.4108 7.51082 15.0139 7.28769 14.6284C6.87682 14.7036 6.49032 14.7916 6.13501 14.8919C4.53429 15.3441 3.72266 15.9986 3.72266 16.8373C3.72266 17.676 4.53429 18.3305 6.13501 18.7827Z" fill="white"/><path d="M10.9994 16.7851C11.5731 16.7851 12.0943 16.4927 12.3936 16.003C14.4909 12.5716 16.9909 8.04934 16.9909 5.99152C16.9909 2.68778 14.3032 0 10.9994 0C7.69559 0 5.00781 2.68778 5.00781 5.99152C5.00781 8.04934 7.50786 12.5716 9.60513 16.003C9.90445 16.4927 10.4257 16.7851 10.9994 16.7851ZM8.59111 5.58014C8.59111 4.25227 9.67147 3.17195 10.9994 3.17195C12.3273 3.17195 13.4076 4.25227 13.4076 5.58014C13.4076 6.90804 12.3273 7.98836 10.9994 7.98836C9.67147 7.98836 8.59111 6.90809 8.59111 5.58014Z" fill="white"/></svg>
                                        <span>' . $translation['text176'] . '</span>
                                    </div>
                                </div>
                                <div class="dashboard_tab_content_item_company_name_top_right">
                                    <div class="dashboard_tab_content_item_company_name_top_right_title">' . $translation['text49'] . '</div>
                                    <div class="dashboard_tab_content_item_company_name_top_right_text">' . $translation['text177'] . '</div>
                                </div>
                            </div>
                            <div class="dashboard_tab_content_item_company_name_inner dashboard_tab_content_item_geo_coordinates_inner">
                                <img src="/images/dashboard_tab_content_item_company_name_inner_bg.png" class="dashboard_tab_content_item_company_name_inner_bg" alt="">
                                <!-- <img src="/images/gifs/dashboard_geo_coordinates.gif" class="dashboard_tab_content_item_geo_coordinates_inner_bg" alt=""> -->
                                <div class="dashboard_tab_content_item_geo_coordinates_inner_bg"></div>

                                <div class="dashboard_tab_content_item_geo_coordinates_input_wrapper_row_titles">
                                    <div class="dashboard_tab_content_item_geo_coordinates_title_text_left">' . $translation['text178'] . '</div>
                                    <div class="dashboard_tab_content_item_geo_coordinates_title_text_right"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="10" cy="10" r="9" fill="#102348" fill-opacity="0.5" stroke="#00F0FF"/><circle cx="10" cy="10" r="5" fill="#00F0FF" stroke="#00F0FF"/></svg>' . $translation['text179'] . '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="10" cy="10" r="9" fill="#102348" fill-opacity="0.5" stroke="#00F0FF"/></svg>' . $translation['text180'] . '</div>
                                </div>
                                <div class="dashboard_tab_content_item_geo_coordinates_input_wrapper_row dashboard_tab_content_item_geo_coordinates_input_wrapper_row_latitude">
                                    <div class="dashboard_tab_content_item_geo_coordinates_input_wrapper">
                                        <div class="dashboard_tab_content_item_company_name_input_border_left"></div>
                                        <input type="text" placeholder="" autocomplete="off" class="dashboard_tab_content_item_geo_coordinates_latitude1_input">
                                        <div class="dashboard_tab_content_item_geo_coordinates_input_svg">
                                            <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="5.5" cy="5.5" r="4.5" stroke="white" stroke-width="2"/></svg>
                                        </div>
                                    </div>
                                    <div class="dashboard_tab_content_item_geo_coordinates_input_wrapper">
                                        <input type="text" placeholder="" autocomplete="off" class="dashboard_tab_content_item_geo_coordinates_latitude2_input">
                                        <div class="dashboard_tab_content_item_geo_coordinates_input_svg">
                                            <svg width="2" height="9" viewBox="0 0 2 9" fill="none" xmlns="http://www.w3.org/2000/svg"><line x1="1" y1="4.37112e-08" x2="1" y2="9" stroke="white" stroke-width="2"/></svg>
                                        </div>
                                    </div>
                                    <div class="dashboard_tab_content_item_geo_coordinates_input_wrapper">
                                        <div class="dashboard_tab_content_item_company_name_input_border_right"></div>
                                        <input type="text" placeholder="" autocomplete="off" class="dashboard_tab_content_item_geo_coordinates_latitude3_input">
                                        <div class="dashboard_tab_content_item_geo_coordinates_input_svg">
                                            <svg width="7" height="9" viewBox="0 0 7 9" fill="none" xmlns="http://www.w3.org/2000/svg"><line x1="6" y1="4.37112e-08" x2="6" y2="9" stroke="white" stroke-width="2"/><line x1="1" y1="4.37112e-08" x2="1" y2="9" stroke="white" stroke-width="2"/></svg>
                                        </div>
                                    </div>
                                    <div class="dashboard_tab_content_item_geo_coordinates_input_error">' . $translation['text190'] . '</div>
                                </div>

                                <div class="dashboard_tab_content_item_geo_coordinates_input_wrapper_row_titles">
                                    <div class="dashboard_tab_content_item_geo_coordinates_title_text_left">' . $translation['text181'] . '</div>
                                    <div class="dashboard_tab_content_item_geo_coordinates_title_text_right"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="10" cy="10" r="9" fill="#102348" fill-opacity="0.5" stroke="#00F0FF"/><circle cx="10" cy="10" r="5" fill="#00F0FF" stroke="#00F0FF"/></svg>' . $translation['text182'] . '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="10" cy="10" r="9" fill="#102348" fill-opacity="0.5" stroke="#00F0FF"/></svg>' . $translation['text183'] . '</div>
                                </div>
                                <div class="dashboard_tab_content_item_geo_coordinates_input_wrapper_row dashboard_tab_content_item_geo_coordinates_input_wrapper_row_longitude">
                                    <div class="dashboard_tab_content_item_geo_coordinates_input_wrapper">
                                        <div class="dashboard_tab_content_item_company_name_input_border_left"></div>
                                        <input type="text" placeholder="" autocomplete="off" class="dashboard_tab_content_item_geo_coordinates_longitude1_input">
                                        <div class="dashboard_tab_content_item_geo_coordinates_input_svg">
                                            <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="5.5" cy="5.5" r="4.5" stroke="white" stroke-width="2"/></svg>
                                        </div>
                                    </div>
                                    <div class="dashboard_tab_content_item_geo_coordinates_input_wrapper">
                                        <input type="text" placeholder="" autocomplete="off" class="dashboard_tab_content_item_geo_coordinates_longitude2_input">
                                        <div class="dashboard_tab_content_item_geo_coordinates_input_svg">
                                            <svg width="2" height="9" viewBox="0 0 2 9" fill="none" xmlns="http://www.w3.org/2000/svg"><line x1="1" y1="4.37112e-08" x2="1" y2="9" stroke="white" stroke-width="2"/></svg>
                                        </div>
                                    </div>
                                    <div class="dashboard_tab_content_item_geo_coordinates_input_wrapper">
                                        <div class="dashboard_tab_content_item_company_name_input_border_right"></div>
                                        <input type="text" placeholder="" autocomplete="off" class="dashboard_tab_content_item_geo_coordinates_longitude3_input">
                                        <div class="dashboard_tab_content_item_geo_coordinates_input_svg">
                                            <svg width="7" height="9" viewBox="0 0 7 9" fill="none" xmlns="http://www.w3.org/2000/svg"><line x1="6" y1="4.37112e-08" x2="6" y2="9" stroke="white" stroke-width="2"/><line x1="1" y1="4.37112e-08" x2="1" y2="9" stroke="white" stroke-width="2"/></svg>
                                        </div>
                                    </div>
                                    <div class="dashboard_tab_content_item_geo_coordinates_input_error">' . $translation['text190'] . '</div>
                                </div>

                                <div class="btn_wrapper btn_wrapper_blue dashboard_tab_content_item_geo_coordinates_btn">
                                    <div class="btn btn_blue">
                                        <span>' . $translation['text184'] . '</span>
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
                        </div>';

    return $return;
}

// dashboard - african partner
private function uploadDashboardAfricanPartner($lang_id, $team_id)
{
    $translation = $this->getWordsByPage('game', $lang_id);

    $team_info = $this->teamInfo($team_id);

    $return = [];

    $return['titles'] = '<div class="flex items-center gap-3 mb-6">
            <div class="p-2 rounded-lg bg-primary/20 border border-primary/30">
                <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.75 0H21V2L19.25 4H0V2L1.75 0Z" fill="#00F0FF"></path><path d="M1.75 6H7.25V19L5.5 21H0V8L1.75 6Z" fill="#00F0FF"></path><path d="M11.75 10H21V13L19.25 15H10V12L11.75 10Z" fill="#00F0FF"></path><path d="M10.75 6H21V8L20.25 9H10V7L10.75 6Z" fill="#00F0FF"></path><path d="M11.75 16H21V19L19.25 21H10V18L11.75 16Z" fill="#00F0FF"></path></svg>
            </div>
            <h2 class="text-3xl font-bold neon-text">' . $translation['text11'] . '</h2>
        </div>';

    $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_african_partner dashboard_tab_content_item_active" data-tab="tab1">
                            <div class="dashboard_tab_content_item_company_name_top">
                                <div class="dashboard_tab_content_item_company_name_top_left">
                                    <img src="/images/dashboard_company_name_top_left_bg.png" class="dashboard_company_name_top_left_bg" alt="">
                                    <div class="dashboard_tab_content_item_company_name_top_text">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M17.9039 5.04333H13.5156V3.1192H18.4402C19.3004 3.1192 20 2.41959 20 1.55975C20 0.699615 19.3004 0 18.4402 0H1.55975C0.699615 0 0 0.699615 0 1.55975C0 2.41959 0.699615 3.1192 1.55975 3.1192H6.48438V5.04333H2.0961C0.940247 5.04333 0 5.98358 0 7.13943V17.9039C0 19.0598 0.940247 20 2.0961 20H17.9039C19.0598 20 20 19.0598 20 17.9039V7.13943C20 5.98358 19.0598 5.04333 17.9039 5.04333ZM7.65625 3.1192H12.3438V5.04333H7.65625V3.1192ZM3.17221 8.71339C3.17221 8.3516 3.46671 8.05711 3.8288 8.05711H11.5C11.8621 8.05711 12.1566 8.3516 12.1566 8.71339V12.3433C12.1566 12.7055 11.8621 13 11.5 13H3.8288C3.46671 13 3.17221 12.7055 3.17221 12.3433V8.71339ZM13.9062 17.6062H5.99915C5.67581 17.6062 5.41321 17.3442 5.41321 17.0203C5.41321 16.6969 5.67581 16.4343 5.99915 16.4343H13.9062C14.2297 16.4343 14.4922 16.6969 14.4922 17.0203C14.4922 17.3442 14.2297 17.6062 13.9062 17.6062ZM16.3446 15.6531H3.6554C3.33206 15.6531 3.06946 15.3911 3.06946 15.0671C3.06946 14.7438 3.33206 14.4812 3.6554 14.4812H16.3446C16.6679 14.4812 16.9305 14.7438 16.9305 15.0671C16.9305 15.3911 16.6679 15.6531 16.3446 15.6531Z" fill="white"/></svg>
                                        <span>' . $translation['text197'] . '</span>
                                    </div>
                                </div>
                                <div class="dashboard_tab_content_item_company_name_top_right">
                                    <div class="dashboard_tab_content_item_company_name_top_right_title">' . $translation['text49'] . '</div>
                                    <div class="dashboard_tab_content_item_company_name_top_right_text">' . $translation['text198'] . '</div>
                                </div>
                            </div>
                            <div class="dashboard_tab_content_item_company_name_inner dashboard_tab_content_item_african_partner_inner">
                                <img src="/images/dashboard_tab_content_item_company_name_inner_bg.png" class="dashboard_tab_content_item_company_name_inner_bg" alt="">
                                <img src="/images/dashboard_africa_partners_bg.png" class="dashboard_africa_partners_bg" alt="">

                                <div class="dashboard_african_partner_fields_top">
                                    <div class="dashboard_african_partner_input_wrapper dashboard_african_partner_input_wrapper_company_name">
                                        <div class="dashboard_african_partner_input_border_left"></div>
                                        <input type="text" placeholder="' . $translation['text199'] . '" autocomplete="off" class="dashboard_african_partner_company_name">
                                        <div class="dashboard_african_partner_company_name_error error_text_database_car_register">' . $translation['text86'] . '</div>
                                    </div>
                                    <div class="dashboard_african_partner_input_wrapper dashboard_african_partner_input_wrapper_country">
                                        <div class="dashboard_african_partner_input_border_right"></div>';

    $sql = "
        SELECT c.code, c.pos, cd.name, c.id
        FROM countries c
        JOIN countries_description cd ON c.id = cd.country_id
        WHERE cd.lang_id = {?}
        ORDER BY cd.name
    ";
    $countries = $this->db->select($sql, [$lang_id]);
    if ($countries) {
        $return['content'] .= '<select class="dashboard_african_partner_country"><option disabled="disabled"' . (empty($team_info['african_partner_country_id']) ? ' selected="selected"' : '') . '>' . $translation['text200'] . '</option>';
        foreach ($countries as $country) {
            $return['content'] .= '<option value="' . htmlspecialchars($country['name'], ENT_QUOTES) . '" data-pos="' . $country['pos'] . '"' . ($team_info['african_partner_country_id'] == $country['id'] ? ' selected="selected"' : '') . '>' . $country['name'] . '</option>';
        }
        $return['content'] .= '</select>
                                <script>
                                    $(function() {
                                        // select country
                                        var scrollbarPositionPixel = 0;
                                        var isScrollOpen = false;

                                        $(".dashboard_african_partner_country").selectric({
                                            optionsItemBuilder: function(itemData, element, index) {
                                                return (!itemData.disabled) ? \'<span class="select_country_flag" style="display:inline-block;width:16px;height:11px;background:url(/images/flags.png) no-repeat;background-position:\' + itemData.element[0].attributes[\'data-pos\'].value + \';margin: 0 15px 0 5px;"></span><span class="select_country_name" style="display:inline-block;max-width: 87%;">\' + itemData.text + \'</span>\' : itemData.text;
                                            },
                                            maxHeight: 236,
                                            preventWindowScroll: false,
                                            onInit: function() {
                                                // стилизация полосы прокрутки
                                                $(".selectric-dashboard_african_partner_country .selectric-scroll").mCustomScrollbar({
                                                    scrollInertia: 700,
                                                    theme: "minimal-dark",
                                                    scrollbarPosition: "inside",
                                                    alwaysShowScrollbar: 2,
                                                    autoHideScrollbar: false,
                                                    mouseWheel:{ deltaFactor: 200 },
                                                    callbacks:{
                                                        onScroll: function(){
                                                        },
                                                        whileScrolling:function() {
                                                            scrollbarPositionPixel = this.mcs.top;
                                                            if (isScrollOpen) {
                                                                $(".dashboard_african_partner_country").selectric("open");
                                                            }
                                                        }
                                                    }
                                                });
                                            },
                                            onOpen: function() {
                                                if (!isScrollOpen) {
                                                    $(".selectric-dashboard_african_partner_country .selectric-scroll").mCustomScrollbar("scrollTo", Math.abs(scrollbarPositionPixel));
                                                    isScrollOpen = true;
                                                }
                                            }
                                        })
                                        .on("change", function() {
                                            // сохраняем выбор
                                            var formData = new FormData();
                                            formData.append("op", "saveTeamTextField");
                                            formData.append("field", "african_partner_country_id");
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
                                                        // socket
                                                        var message = {
                                                            "op": "dashboardAfricanPartnerUpdateCountry",
                                                            "parameters": {
                                                                "country_lang": json.country_lang,
                                                                "user_id": $("#section_game").length ? $("#section_game").attr("data-user-id") : 0,
                                                                "team_id": $("#section_game").length ? $("#section_game").attr("data-team-id") : 0
                                                            }
                                                        };
                                                        sendMessageSocket(JSON.stringify(message));
                                                    }
                                                },
                                                error: function(xhr, ajaxOptions, thrownError) {    
                                                    console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                                }
                                            });

                                            isScrollOpen = false;
                                        });

                                        $(".dashboard_tabs[data-dashboard=\'databases\']").on("click", ".dashboard_african_partner_input_wrapper_country .mCSB_scrollTools_vertical", function(e){
                                            if (isScrollOpen) {
                                                $(".dashboard_african_partner_country").selectric("open");
                                            }
                                        });

                                        // datepicker
                                        $(".dashboard_african_partner_date").datepicker({
                                            dateFormat: "dd.mm.yy",
                                            dayNamesShort: ["' . $translation['text67'] . '", "' . $translation['text68'] . '", "' . $translation['text69'] . '", "' . $translation['text70'] . '", "' . $translation['text71'] . '", "' . $translation['text72'] . '", "' . $translation['text73'] . '"],
                                            dayNamesMin: ["' . $translation['text67'] . '", "' . $translation['text68'] . '", "' . $translation['text69'] . '", "' . $translation['text70'] . '", "' . $translation['text71'] . '", "' . $translation['text72'] . '", "' . $translation['text73'] . '"],
                                            monthNames: ["' . $translation['text74'] . '", "' . $translation['text75'] . '", "' . $translation['text76'] . '", "' . $translation['text77'] . '", "' . $translation['text78'] . '", "' . $translation['text79'] . '", "' . $translation['text80'] . '", "' . $translation['text81'] . '", "' . $translation['text82'] . '", "' . $translation['text83'] . '", "' . $translation['text84'] . '", "' . $translation['text85'] . '"],
                                            changeMonth: false,
                                            changeYear: false,
                                            //showAnim: "clip",
                                            showAnim: "",
                                            onSelect: function(dateText) {
                                                // сохраняем выбор
                                                var formData = new FormData();
                                                formData.append("op", "saveTeamTextField");
                                                formData.append("field", "african_partner_date");
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
                                                        // socket
                                                        var message = {
                                                            "op": "dashboardAfricanPartnerUpdateDate",
                                                            "parameters": {
                                                                "date": dateText,
                                                                "user_id": $("#section_game").length ? $("#section_game").attr("data-user-id") : 0,
                                                                "team_id": $("#section_game").length ? $("#section_game").attr("data-team-id") : 0
                                                            }
                                                        };
                                                        sendMessageSocket(JSON.stringify(message));
                                                    },
                                                    error: function(xhr, ajaxOptions, thrownError) {    
                                                        console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
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
                                                            //$("body").css("transform", "scale(" + koef + ")");

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

    $return['content'] .= '             <div class="dashboard_african_partner_country_error error_text_database_car_register">' . $translation['text86'] . '</div>
                                    </div>
                                </div>
                                <div class="dashboard_african_partner_fields_bottom">
                                    <div class="dashboard_african_partner_input_wrapper dashboard_african_partner_input_wrapper_date">
                                        <div class="dashboard_african_partner_input_border_left"></div>
                                        <div class="dashboard_african_partner_input_border_right"></div>
                                        <input type="text" placeholder="' . $translation['text201'] . '" autocomplete="off" class="dashboard_african_partner_date" value="' . ((!empty($team_info['african_partner_date']) && $team_info['african_partner_date'] != '0000-00-00' && !is_null($team_info['african_partner_date'])) ? $this->fromEngDatetimeToRus($team_info['african_partner_date']) : '') . '">
                                        <div class="dashboard_african_partner_date_error error_text_database_car_register">' . $translation['text86'] . '</div>
                                    </div>
                                </div>
                                <div class="btn_wrapper btn_wrapper_blue dashboard_african_partner_search">
                                    <div class="btn btn_blue">
                                        <span>' . $translation['text184'] . '</span>
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
                        </div>';

    return $return;
}

// dashboard - Metting Place
private function uploadDashboardMettingPlace($lang_id, $team_id)
{
    $translation = $this->getWordsByPage('game', $lang_id);

    $team_info = $this->teamInfo($team_id);

    $return = [];

    $return['titles'] = '<div class="flex items-center gap-3 mb-6">
            <div class="p-2 rounded-lg bg-primary/20 border border-primary/30">
                <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.75 0H21V2L19.25 4H0V2L1.75 0Z" fill="#00F0FF"></path><path d="M1.75 6H7.25V19L5.5 21H0V8L1.75 6Z" fill="#00F0FF"></path><path d="M11.75 10H21V13L19.25 15H10V12L11.75 10Z" fill="#00F0FF"></path><path d="M10.75 6H21V8L20.25 9H10V7L10.75 6Z" fill="#00F0FF"></path><path d="M11.75 16H21V19L19.25 21H10V18L11.75 16Z" fill="#00F0FF"></path></svg>
            </div>
            <h2 class="text-3xl font-bold neon-text">' . $translation['text11'] . '</h2>
        </div>';

    $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_metting_place dashboard_tab_content_item_active" data-tab="tab1">
                            
                            <div class="dashboard_tab_content_item_company_name_inner dashboard_tab_content_item_metting_place_inner">
                                <img src="/images/dashboard_tab_content_item_company_name_inner_bg.png" class="dashboard_tab_content_item_company_name_inner_bg" alt="">

                                <div class="dashboard_metting_place_fields_top">
                                    <div class="dashboard_metting_place_input_wrapper dashboard_metting_place_input_wrapper_street_name">
                                        <div class="dashboard_metting_place_input_border_left"></div>
                                        <input type="text" placeholder="' . $translation['text207'] . '" autocomplete="off" class="dashboard_metting_place_street_name">
                                        <div class="dashboard_metting_place_street_name_error error_text_database_car_register">' . $translation['text86'] . '</div>
                                        <div class="dashboard_metting_place_input_border_right"></div>
                                    </div>
                                    <div class="dashboard_metting_place_input_wrapper dashboard_metting_place_input_wrapper_house_number">
                                        <div class="dashboard_metting_place_input_border_left"></div>
                                        <input type="text" placeholder="#" autocomplete="off" class="dashboard_metting_place_house_number">
                                        <div class="dashboard_metting_place_house_number_error error_text_database_car_register">' . $translation['text86'] . '</div>
                                        <div class="dashboard_metting_place_input_border_right"></div>
                                    </div>
                                </div>
                                <div class="dashboard_metting_place_fields_bottom">
                                    <div class="dashboard_metting_place_input_wrapper dashboard_metting_place_input_wrapper_city">
                                        <div class="dashboard_metting_place_input_border_left"></div>
                                        <input type="text" placeholder="' . $translation['text208'] . '" autocomplete="off" class="dashboard_metting_place_city">
                                        <div class="dashboard_metting_place_city_error error_text_database_car_register">' . $translation['text86'] . '</div>
                                        <div class="dashboard_metting_place_input_border_right"></div>
                                    </div>
                                    <div class="dashboard_metting_place_input_wrapper dashboard_metting_place_input_wrapper_country">
                                        <div class="dashboard_metting_place_input_border_left"></div>
                                        <div class="dashboard_metting_place_input_border_right"></div>';

    $sql = "
        SELECT c.code, c.pos, cd.name, c.id
        FROM countries c
        JOIN countries_description cd ON c.id = cd.country_id
        WHERE cd.lang_id = {?}
        ORDER BY cd.name
    ";
    $countries = $this->db->select($sql, [$lang_id]);
    if ($countries) {
        $return['content'] .= '<select class="dashboard_metting_place_country"><option disabled="disabled"' . (empty($team_info['metting_place_country_id']) ? ' selected="selected"' : '') . '>' . $translation['text64'] . '</option>';
        foreach ($countries as $country) {
            $return['content'] .= '<option value="' . htmlspecialchars($country['name'], ENT_QUOTES) . '" data-pos="' . $country['pos'] . '"' . ($team_info['metting_place_country_id'] == $country['id'] ? ' selected="selected"' : '') . '>' . $country['name'] . '</option>';
        }
        $return['content'] .= '</select>
                                <script>
                                    $(function() {
                                        // select country
                                        var scrollbarPositionPixel = 0;
                                        var isScrollOpen = false;

                                        $(".dashboard_metting_place_country").selectric({
                                            optionsItemBuilder: function(itemData, element, index) {
                                                return (!itemData.disabled) ? \'<span class="select_country_flag" style="display:inline-block;width:16px;height:11px;background:url(/images/flags.png) no-repeat;background-position:\' + itemData.element[0].attributes[\'data-pos\'].value + \';margin: 0 15px 0 5px;"></span><span class="select_country_name" style="display:inline-block;max-width: 87%;">\' + itemData.text + \'</span>\' : itemData.text;
                                            },
                                            maxHeight: 236,
                                            preventWindowScroll: false,
                                            onInit: function() {
                                                // стилизация полосы прокрутки
                                                $(".selectric-dashboard_metting_place_country .selectric-scroll").mCustomScrollbar({
                                                    scrollInertia: 700,
                                                    theme: "minimal-dark",
                                                    scrollbarPosition: "inside",
                                                    alwaysShowScrollbar: 2,
                                                    autoHideScrollbar: false,
                                                    mouseWheel:{ deltaFactor: 200 },
                                                    callbacks:{
                                                        onScroll: function(){
                                                        },
                                                        whileScrolling:function() {
                                                            scrollbarPositionPixel = this.mcs.top;
                                                            if (isScrollOpen) {
                                                                $(".dashboard_metting_place_country").selectric("open");
                                                            }
                                                        }
                                                    }
                                                });
                                            },
                                            onOpen: function() {
                                                if (!isScrollOpen) {
                                                    $(".selectric-dashboard_metting_place_country .selectric-scroll").mCustomScrollbar("scrollTo", Math.abs(scrollbarPositionPixel));
                                                    isScrollOpen = true;
                                                }
                                            }
                                        })
                                        .on("change", function() {
                                            // сохраняем выбор
                                            var formData = new FormData();
                                            formData.append("op", "saveTeamTextField");
                                            formData.append("field", "metting_place_country_id");
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
                                                        // socket
                                                        var message = {
                                                            "op": "dashboardMettingPlaceUpdateCountry",
                                                            "parameters": {
                                                                "country_lang": json.country_lang,
                                                                "user_id": $("#section_game").length ? $("#section_game").attr("data-user-id") : 0,
                                                                "team_id": $("#section_game").length ? $("#section_game").attr("data-team-id") : 0
                                                            }
                                                        };
                                                        sendMessageSocket(JSON.stringify(message));
                                                    }
                                                },
                                                error: function(xhr, ajaxOptions, thrownError) {    
                                                    console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                                }
                                            });

                                            isScrollOpen = false;
                                        });

                                        $(".dashboard_tabs[data-dashboard=\'databases\']").on("click", ".dashboard_metting_place_input_wrapper_country .mCSB_scrollTools_vertical", function(e){
                                            if (isScrollOpen) {
                                                $(".dashboard_metting_place_country").selectric("open");
                                            }
                                        });
                                    });
                                </script>';
    }

    $return['content'] .= '             <div class="dashboard_metting_place_country_error error_text_database_car_register">' . $translation['text86'] . '</div>
                                    </div>
                                </div>
                                <div class="btn_wrapper btn_wrapper_blue dashboard_metting_place_search">
                                    <div class="btn btn_blue">
                                        <span>' . $translation['text184'] . '</span>
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
                        </div>';

    return $return;
}

// dashboard - Room Name
private function uploadDashboardRoomName($lang_id, $team_id)
{
    $translation = $this->getWordsByPage('game', $lang_id);

    $team_info = $this->teamInfo($team_id);

    $return = [];

    $return['titles'] = '<div class="flex items-center gap-3 mb-6">
            <div class="p-2 rounded-lg bg-primary/20 border border-primary/30">
                <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.75 0H21V2L19.25 4H0V2L1.75 0Z" fill="#00F0FF"></path><path d="M1.75 6H7.25V19L5.5 21H0V8L1.75 6Z" fill="#00F0FF"></path><path d="M11.75 10H21V13L19.25 15H10V12L11.75 10Z" fill="#00F0FF"></path><path d="M10.75 6H21V8L20.25 9H10V7L10.75 6Z" fill="#00F0FF"></path><path d="M11.75 16H21V19L19.25 21H10V18L11.75 16Z" fill="#00F0FF"></path></svg>
            </div>
            <h2 class="text-3xl font-bold neon-text">' . $translation['text11'] . '</h2>
        </div>';

    $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_room_name dashboard_tab_content_item_active" data-tab="tab1">
                            <div class="dashboard_tab_content_item_company_name_top">
                                <div class="dashboard_tab_content_item_company_name_top_left">
                                    <img src="/images/dashboard_company_name_top_left_bg.png" class="dashboard_company_name_top_left_bg" alt="">
                                    <div class="dashboard_tab_content_item_company_name_top_text">
                                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_886_1055)"><path d="M7.13281 16.8008C8.20071 16.8008 9.06641 15.9351 9.06641 14.8672C9.06641 13.7993 8.20071 12.9336 7.13281 12.9336C6.06492 12.9336 5.19922 13.7993 5.19922 14.8672C5.19922 15.9351 6.06492 16.8008 7.13281 16.8008Z" fill="white"/><path d="M20.2303 9.06641L12.8506 3.79521C12.8992 3.62583 12.9336 3.45052 12.9336 3.26562C12.9336 2.1994 12.0662 1.33203 11 1.33203C9.93377 1.33203 9.06641 2.1994 9.06641 3.26562C9.06641 3.45052 9.10078 3.62583 9.14942 3.79521L1.76971 9.06641H0V20.668H22V9.06641H20.2303ZM11 2.62109C11.355 2.62109 11.6445 2.91002 11.6445 3.26562C11.6445 3.62123 11.355 3.91016 11 3.91016C10.645 3.91016 10.3555 3.62123 10.3555 3.26562C10.3555 2.91002 10.645 2.62109 11 2.62109ZM9.89433 4.84761C10.2082 5.06765 10.5884 5.19922 11 5.19922C11.4116 5.19922 11.7918 5.06765 12.1057 4.84761L18.0119 9.06641H3.98806L9.89433 4.84761Z" fill="white"/><path d="M2.6545 14.6086V17.4296H3.4595V14.8046L3.8445 14.3636H4.7335L4.9505 14.5806V17.4296H5.7555V14.2936L5.0835 13.6286H3.6275L3.2215 14.0976L3.0885 13.5586L2.4375 13.7196L2.6545 14.6086Z" fill="#041627"/><path d="M7.40903 13.6286L6.51303 13.9086L6.73003 14.6086L7.51403 14.3636H8.53603L8.75303 14.5806V15.1616H7.12903L6.45703 15.8266V16.7646L7.12903 17.4296H8.58503L8.99103 16.9606L9.12403 17.4996L9.77503 17.3386L9.55803 16.4496V14.2936L8.88603 13.6286H7.40903ZM8.75303 16.2536L8.36803 16.6946H7.47903L7.26203 16.4776V16.1136L7.47903 15.8966H8.75303V16.2536Z" fill="#041627"/><path d="M12.7602 13.6286H11.5982L11.1922 14.0976L11.0592 13.5586L10.4082 13.7196L10.6252 14.6086V17.4296H11.4302V14.8046L11.8152 14.3636H12.4592L12.7182 14.6436V17.4296H13.5232V14.6436L13.7822 14.3636H14.5942L14.8112 14.5806V17.4296H15.6162V14.2936L14.9442 13.6286H13.4882L13.1242 14.0276L12.7602 13.6286Z" fill="#041627"/><path d="M18.4985 16.6946H17.4065L17.1895 16.4776V15.9596H18.8765L19.4785 15.3646V14.2936L18.8065 13.6286H17.0565L16.3845 14.2936V16.7646L17.0565 17.4296H18.6035L19.4995 17.1496L19.2825 16.4496L18.4985 16.6946ZM17.4065 14.3636H18.4565L18.6735 14.5806V15.0426L18.4915 15.2246H17.1895V14.5806L17.4065 14.3636Z" fill="#041627"/></g><defs><clipPath id="clip0_886_1055"><rect width="22" height="22" fill="white"/></clipPath></defs></svg>
                                        <span>' . $translation['text235'] . '</span>
                                    </div>
                                </div>
                                <div class="dashboard_tab_content_item_company_name_top_right">
                                    <div class="dashboard_tab_content_item_company_name_top_right_title">' . $translation['text49'] . '</div>
                                    <div class="dashboard_tab_content_item_company_name_top_right_text">' . $translation['text236'] . '</div>
                                </div>
                            </div>
                            <div class="dashboard_tab_content_item_company_name_inner dashboard_tab_content_item_room_name_inner">
                                <img src="/images/dashboard_tab_content_item_company_name_inner_bg.png" class="dashboard_tab_content_item_company_name_inner_bg" alt="">

                                <img src="/images/gifs/pc.gif" class="dashboard_tab_content_item_room_name_inner_pc" alt="">
                                <img src="/images/gifs/server.gif" class="dashboard_tab_content_item_room_name_inner_server" alt="">
                                <div class="dashboard_tab_content_item_room_name_inner_pc_to_server">
                                    <svg width="767" height="198" viewBox="0 0 767 198" fill="none" xmlns="http://www.w3.org/2000/svg"><path opacity="0.3" d="M767 196.5H38L1 159.5V0" stroke="#00F0FF" stroke-width="2" stroke-dasharray="10 10"/></svg>
                                </div>
                                <div class="dashboard_room_name_fields">
                                    <div class="dashboard_tab_content_item_room_name_inner_title">' . $translation['text237'] . '</div>
                                    <div class="dashboard_room_name_input_wrapper dashboard_room_name_input_wrapper_room_name">
                                        <div class="dashboard_room_name_input_border_left"></div>
                                        <input type="text" placeholder="' . $translation['text235'] . '" autocomplete="off" class="dashboard_room_name_room_name">
                                        <div class="dashboard_room_name_room_name_error error_text_database_car_register">' . $translation['text86'] . '</div>
                                        <div class="dashboard_room_name_input_border_right"></div>
                                        <div class="dashboard_room_name_input_border_left_line">
                                            <svg width="320" height="41" viewBox="0 0 320 41" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 1H199.697L238.433 40H319.5" stroke="#FF004E"/></svg>
                                        </div>
                                        <div class="dashboard_room_name_input_border_right_line">
                                            <svg width="327" height="41" viewBox="0 0 327 41" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 1H68.6967L107.433 40H326.5" stroke="#FF004E"/></svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="btn_wrapper btn_wrapper_blue dashboard_room_name_search">
                                    <div class="btn btn_blue">
                                        <span>' . $translation['text184'] . '</span>
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
                        </div>';

    return $return;
}

// dashboard - Password
private function uploadDashboardPassword($lang_id, $team_id)
{
    $translation = $this->getWordsByPage('game', $lang_id);

    $team_info = $this->teamInfo($team_id);

    $return = [];

    $return['titles'] = '<div class="flex items-center gap-3 mb-6">
            <div class="p-2 rounded-lg bg-primary/20 border border-primary/30">
                <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.75 0H21V2L19.25 4H0V2L1.75 0Z" fill="#00F0FF"></path><path d="M1.75 6H7.25V19L5.5 21H0V8L1.75 6Z" fill="#00F0FF"></path><path d="M11.75 10H21V13L19.25 15H10V12L11.75 10Z" fill="#00F0FF"></path><path d="M10.75 6H21V8L20.25 9H10V7L10.75 6Z" fill="#00F0FF"></path><path d="M11.75 16H21V19L19.25 21H10V18L11.75 16Z" fill="#00F0FF"></path></svg>
            </div>
            <h2 class="text-3xl font-bold neon-text">' . $translation['text11'] . '</h2>
        </div>';

    $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_room_name dashboard_tab_content_item_active" data-tab="tab1">
                            <div class="dashboard_tab_content_item_company_name_top">
                                <div class="dashboard_tab_content_item_company_name_top_left">
                                    <img src="/images/dashboard_company_name_top_left_bg.png" class="dashboard_company_name_top_left_bg" alt="">
                                    <div class="dashboard_tab_content_item_company_name_top_text">
                                        <svg width="22" height="12" viewBox="0 0 22 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14.1766 2.24984C13.9227 1.81075 13.3599 1.66042 12.9245 1.91434L11.918 2.4955V1.33317C11.918 0.82717 11.5082 0.416504 11.0013 0.416504C10.4944 0.416504 10.0846 0.82717 10.0846 1.33317V2.4955L9.07814 1.91434C8.64089 1.66042 8.07898 1.81075 7.82598 2.24984C7.57298 2.688 7.72331 3.249 8.16148 3.502L9.16798 4.08317L8.16148 4.66434C7.72331 4.91734 7.57298 5.47834 7.82598 5.9165C7.99648 6.21075 8.30356 6.37484 8.62073 6.37484C8.77656 6.37484 8.93423 6.33542 9.07814 6.252L10.0846 5.67084V6.83317C10.0846 7.33917 10.4944 7.74984 11.0013 7.74984C11.5082 7.74984 11.918 7.33917 11.918 6.83317V5.67084L12.9245 6.252C13.0684 6.33542 13.2261 6.37484 13.3819 6.37484C13.6991 6.37484 14.0071 6.21075 14.1766 5.9165C14.4296 5.47834 14.2793 4.91734 13.8411 4.66434L12.8346 4.08317L13.8411 3.502C14.2793 3.249 14.4296 2.688 14.1766 2.24984V2.24984Z" fill="white"/><path d="M6.47352 2.24984C6.2196 1.81075 5.65769 1.66042 5.22135 1.91434L4.21485 2.4955V1.33317C4.21485 0.82717 3.8051 0.416504 3.29819 0.416504C2.79127 0.416504 2.38152 0.82717 2.38152 1.33317V2.4955L1.37502 1.91434C0.936852 1.66042 0.376769 1.81075 0.122853 2.24984C-0.130147 2.688 0.0201859 3.249 0.458353 3.502L1.46485 4.08317L0.458353 4.66434C0.0201859 4.91734 -0.130147 5.47834 0.122853 5.9165C0.293353 6.21075 0.600436 6.37484 0.917603 6.37484C1.07344 6.37484 1.2311 6.33542 1.37502 6.252L2.38152 5.67084V6.83317C2.38152 7.33917 2.79127 7.74984 3.29819 7.74984C3.8051 7.74984 4.21485 7.33917 4.21485 6.83317V5.67084L5.22135 6.252C5.36527 6.33542 5.52294 6.37484 5.67877 6.37484C5.99594 6.37484 6.30394 6.21075 6.47352 5.9165C6.72652 5.47834 6.57619 4.91734 6.13802 4.66434L5.13152 4.08317L6.13802 3.502C6.57619 3.249 6.72652 2.688 6.47352 2.24984V2.24984Z" fill="white"/><path d="M16.3225 6.37484C16.4783 6.37484 16.636 6.33542 16.7799 6.252L17.7864 5.67084V6.83317C17.7864 7.33917 18.1962 7.74984 18.7031 7.74984C19.21 7.74984 19.6198 7.33917 19.6198 6.83317V5.67084L20.6263 6.252C20.7702 6.33542 20.9279 6.37484 21.0837 6.37484C21.4009 6.37484 21.7089 6.21075 21.8784 5.9165C22.1314 5.47834 21.9811 4.91734 21.5429 4.66434L20.5364 4.08317L21.5429 3.502C21.9811 3.249 22.1314 2.688 21.8784 2.24984C21.6245 1.81075 21.0626 1.66042 20.6263 1.91434L19.6198 2.4955V1.33317C19.6198 0.82717 19.21 0.416504 18.7031 0.416504C18.1962 0.416504 17.7864 0.82717 17.7864 1.33317V2.4955L16.7799 1.91434C16.3427 1.66042 15.7808 1.81075 15.5278 2.24984C15.2748 2.688 15.4251 3.249 15.8633 3.502L16.8698 4.08317L15.8633 4.66434C15.4242 4.91734 15.2738 5.47834 15.5278 5.9165C15.6983 6.21075 16.0063 6.37484 16.3225 6.37484Z" fill="white"/><path d="M5.5 9.58301H0.916667C0.40975 9.58301 0 9.99368 0 10.4997C0 11.0057 0.40975 11.4163 0.916667 11.4163H5.5C6.00692 11.4163 6.41667 11.0057 6.41667 10.4997C6.41667 9.99368 6.00692 9.58301 5.5 9.58301Z" fill="white"/><path d="M13.293 9.58301H8.70964C8.20272 9.58301 7.79297 9.99368 7.79297 10.4997C7.79297 11.0057 8.20272 11.4163 8.70964 11.4163H13.293C13.7999 11.4163 14.2096 11.0057 14.2096 10.4997C14.2096 9.99368 13.7999 9.58301 13.293 9.58301Z" fill="white"/><path d="M21.084 9.58301H16.5006C15.9937 9.58301 15.584 9.99368 15.584 10.4997C15.584 11.0057 15.9937 11.4163 16.5006 11.4163H21.084C21.5909 11.4163 22.0006 11.0057 22.0006 10.4997C22.0006 9.99368 21.5909 9.58301 21.084 9.58301Z" fill="white"/></svg>
                                        <span>' . $translation['text263'] . '</span>
                                    </div>
                                </div>
                                <div class="dashboard_tab_content_item_company_name_top_right">
                                    <div class="dashboard_tab_content_item_company_name_top_right_title">' . $translation['text306'] . '</div>
                                    <div class="dashboard_tab_content_item_company_name_top_right_text">' . $translation['text307'] . '</div>
                                </div>
                            </div>
                            <div class="dashboard_tab_content_item_company_name_inner dashboard_tab_content_item_password_inner">
                                <img src="/images/dashboard_tab_content_item_company_name_inner_bg.png" class="dashboard_tab_content_item_company_name_inner_bg" alt="">

                                <div class="dashboard_password_fields">
                                    <div class="dashboard_password_input_wrapper dashboard_password_input_wrapper_password">
                                        <div class="dashboard_password_input_border_left"></div>
                                        <input type="text" placeholder="' . $translation['text263'] . '" autocomplete="off" class="dashboard_password_password">
                                        <div class="dashboard_password_password_error error_text_database_car_register">' . $translation['text86'] . '</div>
                                        <div class="dashboard_password_input_border_right"></div>
                                    </div>

                                    <img src="/images/dashboard_password_inner_bg_right.png" alt="" class="dashboard_password_inner_bg_right">
                                    <img src="/images/gifs/hdd3.gif" alt="" class="dashboard_password_inner_bg_gif3">
                                    <img src="/images/gifs/hdd4.gif" alt="" class="dashboard_password_inner_bg_gif4">
                                    <img src="/images/dashboard_password_inner_bg_left.png" alt="" class="dashboard_password_inner_bg_left">
                                </div>

                                <div class="dashboard_password_inner_bg">
                                    <img src="/images/dashboard_password_inner_bg.png" alt="" class="dashboard_password_inner_bg_main_img">
                                    <img src="/images/gifs/hdd1.gif" alt="" class="dashboard_password_inner_bg_gif1">
                                </div>

                                <div class="btn_wrapper btn_wrapper_blue dashboard_password_search">
                                    <div class="btn btn_blue">
                                        <span>' . $translation['text184'] . '</span>
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
                        </div>';

    return $return;
}
}
