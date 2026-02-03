<?php

trait Databases
{
public function uploadTypeTabsDatabasesStep($step, $lang_id, $team_id)
{
    switch ($step) {
        case 'no_access': $return = $this->uploadDatabasesNoAccess($lang_id); break;
        case 'databases_start_four': $return = $this->uploadDatabasesStartFour($lang_id); break;
        case 'databases_start_four_inner_first_car_register': $return = $this->uploadDatabasesCarRegister($lang_id, $team_id); break;
        case 'databases_start_four_inner_second_car_register_huilov': $return = $this->uploadDatabasesCarRegisterHuilov($lang_id, $team_id); break;
        case 'databases_start_four_inner_first_personal_files': $return = $this->uploadDatabasesPersonalFiles($lang_id, $team_id); break;
        case 'databases_start_four_inner_second_personal_files_private_individual': $return = $this->uploadDatabasesPersonalFilesPrivateIndividual($lang_id, $team_id); break;
        case 'databases_start_four_inner_second_personal_files_private_individual_huilov': $return = $this->uploadDatabasesPersonalFilesPrivateIndividualHuilov($lang_id, $team_id); break;
        case 'databases_start_four_inner_second_personal_files_ceo_database': $return = $this->uploadDatabasesPersonalFilesCeoDatabase($lang_id, $team_id); break;
        case 'databases_start_four_inner_second_personal_files_ceo_database_rod': $return = $this->uploadDatabasesPersonalFilesCeoDatabaseRod($lang_id, $team_id); break;
        case 'databases_start_four_inner_first_mobile_calls': $return = $this->uploadDatabasesMobileCalls($lang_id, $team_id); break;
        case 'databases_start_four_inner_first_mobile_calls_messages': $return = $this->uploadDatabasesMobileCallsMessages($lang_id, $team_id); break;
        case 'databases_start_four_inner_first_bank_transactions': $return = $this->uploadDatabasesBankTransactions($lang_id, $team_id); break;
        case 'databases_bank_transactions_success': $return = $this->uploadDatabasesBankTransactionsSuccess($lang_id, $team_id); break;
        
        default: $return = $this->uploadDatabasesNoAccess($lang_id); break;
    }

    return $return;
}

// databases - нет доступа
private function uploadDatabasesNoAccess($lang_id, $no_access_text = false, $back_btn = false)
{
    $translation = $this->getWordsByPage('game', $lang_id);

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

// databases - первый экран после принятии миссии - список 4-ех баз данных
private function uploadDatabasesStartFour($lang_id)
{
$svg = $this->svg; 
$translation = $this->getWordsByPage('game', $lang_id);

$return = [];

$return['titles'] = '
    <div class="flex items-center gap-3 mb-8">
 <div class="icon-container p-2 rounded-lg bg-primary/20 border border-primary/30">' .  $svg['database_icon'] . '</div>
<h2 class="text-3xl font-bold neon-text">' . $translation['text13'] . '</h2>
  </div>';

$return['content'] = '
<div class="flex justify-center items-stretch gap-6 w-full p-2">

  <!-- Personal Files -->
  <div class="cyber-panel cursor-pointer transition-all hover:scale-105 group border-cyan-500 bg-cyan-900/20 flex-1 flex flex-col">
<div class="text-center pb-3 flex-1">
  <div class="mx-auto mb-4 relative">
    <div class="w-16 h-16 rounded-full bg-cyan-900/30 flex items-center justify-center group-hover:scale-110 transition-transform">
     ' .  $svg['database_document'] . ' 
    </div>
    <div class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-cyan-400 border-2 border-background animate-pulse"></div>
  </div>
  <h3 class="text-xl mb-2">' . $translation['text57']. '</h3>
  <p class="text-sm text-muted-foreground mb-3">Доступ к личным досье</p>
</div>
<div class="text-center space-y-3 px-4 pb-4">
  <div class="flex justify-between text-sm">
    <span class="text-muted-foreground">Records:</span>
    <span class="text-cyan-400">12,345</span>
  </div>
  <span class="badge w-full justify-center bg-green-400">ACTIVE</span>
  <button data-database="personal_files" class="dashboard_tab_content_item_start_four_inner_item w-full text-cyan-400 border-current hover:bg-current/10 group-hover:glow-effect border rounded px-2 py-1 flex items-center justify-center text-sm">
    <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
      <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
    </svg>
    Access Database
  </button>
</div>
  </div>

  <!-- Car Register -->
  <div class="cyber-panel cursor-pointer transition-all hover:scale-105 group border-purple-500 bg-purple-900/20 flex-1 flex flex-col">
<div class="text-center pb-3 flex-1">
  <div class="mx-auto mb-4 relative">
    <div class="w-16 h-16 rounded-full bg-purple-900/30 flex items-center justify-center group-hover:scale-110 transition-transform">
      ' .  $svg['database_car'] . '         
    </div>
    <div class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-purple-400 border-2 border-background"></div>
  </div>
  <h3 class="text-xl mb-2">' . $translation['text58'] . '</h3>
  <p class="text-sm text-muted-foreground mb-3">Реестр автомобилей</p>
</div>
<div class="text-center space-y-3 px-4 pb-4">
  <div class="flex justify-between text-sm">
    <span class="text-muted-foreground">Records:</span>
    <span class="text-purple-400">8,764</span>
  </div>
  <span class="badge w-full justify-center bg-yellow-400">LIMITED</span>
  <button data-database="car_register" class="dashboard_tab_content_item_start_four_inner_item w-full text-purple-400 border-current hover:bg-current/10 group-hover:glow-effect border rounded px-2 py-1 flex items-center justify-center text-sm">
    <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
      <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
    </svg>
    Access Database
  </button>
</div>
  </div>

  <!-- Mobile Calls -->
  <div class="cyber-panel cursor-pointer transition-all hover:scale-105 group border-blue-500 bg-blue-900/20 flex-1 flex flex-col">
<div class="text-center pb-3 flex-1">
  <div class="mx-auto mb-4 relative">
    <div class="w-16 h-16 rounded-full bg-blue-900/30 flex items-center justify-center group-hover:scale-110 transition-transform">
      ' .  $svg['database_call'] . '         
    </div>
    <div class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-blue-400 border-2 border-background"></div>
  </div>
  <h3 class="text-xl mb-2">' . $translation['text59'] . '</h3>
  <p class="text-sm text-muted-foreground mb-3">История мобильных звонков</p>
</div>
<div class="text-center space-y-3 px-4 pb-4">
  <div class="flex justify-between text-sm">
    <span class="text-muted-foreground">Records:</span>
    <span class="text-blue-400">23,109</span>
  </div>
  <span class="badge w-full justify-center bg-red-400">RESTRICTED</span>
  <button data-database="mobile_calls" class="dashboard_tab_content_item_start_four_inner_item w-full text-blue-400 border-current hover:bg-current/10 group-hover:glow-effect border rounded px-2 py-1 flex items-center justify-center text-sm">
    <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
      <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
    </svg>
    Access Database
  </button>
</div>
  </div>

  <!-- Bank Transactions -->
  <div class="cyber-panel cursor-pointer transition-all hover:scale-105 group border-green-500 bg-green-900/20 flex-1 flex flex-col">
<div class="text-center pb-3 flex-1">
  <div class="mx-auto mb-4 relative">
    <div class="w-16 h-16 rounded-full bg-green-900/30 flex items-center justify-center group-hover:scale-110 transition-transform">
      ' .  $svg['database_card'] . '         
    </div>
    <div class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-green-400 border-2 border-background"></div>
  </div>
  <h3 class="text-xl mb-2">' . $translation['text60'] . '</h3>
  <p class="text-sm text-muted-foreground mb-3">Финансовые транзакции</p>
</div>
<div class="text-center space-y-3 px-4 pb-4">
  <div class="flex justify-between text-sm">
    <span class="text-muted-foreground">Records:</span>
    <span class="text-green-400">45,876</span>
  </div>
  <span class="badge w-full justify-center bg-green-400">ACTIVE</span>
  <button data-database="bank_transactions" class="dashboard_tab_content_item_start_four_inner_item w-full text-green-400 border-current hover:bg-current/10 group-hover:glow-effect border rounded px-2 py-1 flex items-center justify-center text-sm">
    <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
      <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
    </svg>
    Access Database
  </button>
</div>
  </div>

</div>

    ';

return $return;
}

// databases - загрузить Car Register. Первый экран
private function uploadDatabasesCarRegister($lang_id, $team_id)
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
                        <div class="dashboard_tab_title dashboard_tab_title_active" data-tab="car_register1">
                            <div class="dashboard_tab_title_active_skew_right"></div>
                            <div class="dashboard_tab_title_inner">
                                <div class="dashboard_tab_title_img_wrapper" style="margin: -10px 0 0;">
                                    <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11.4595 24.9998H8.54054C8.24324 24.9998 8 25.3155 8 25.7015V28.298C8 28.684 8.24324 28.9998 8.54054 28.9998H11.4595C11.7568 28.9998 12 28.684 12 28.298V25.7015C12 25.3155 11.7568 24.9998 11.4595 24.9998ZM10.9189 27.5962H9.08108V26.4033H10.9189V27.5962Z" fill="#00F0FF"/><path d="M25.3243 24.9998H21.6757C21.3041 24.9998 21 25.3155 21 25.7015V28.298C21 28.684 21.3041 28.9998 21.6757 28.9998H25.3243C25.6959 28.9998 26 28.684 26 28.298V25.7015C26 25.3155 25.6959 24.9998 25.3243 24.9998ZM24.6486 27.5962H22.3514V26.4033H24.6486V27.5962Z" fill="#00F0FF"/><path d="M19.3304 26.9998H14.6696C14.3013 26.9998 14 27.4498 14 27.9998C14 28.5498 14.2946 28.9998 14.6696 28.9998H19.3304C19.6987 28.9998 20 28.5498 20 27.9998C20 27.4498 19.6987 26.9998 19.3304 26.9998Z" fill="#00F0FF"/><path d="M19.3304 25H14.6696C14.3013 25 14 25.45 14 26C14 26.55 14.2946 27 14.6696 27H19.3304C19.6987 27 20 26.55 20 26C20 25.45 19.6987 25 19.3304 25Z" fill="#00F0FF"/><path d="M30.6644 9.20212L24.6262 6.07844C24.4219 5.97278 24.1837 5.97278 23.9794 6.08504L18.091 9.20872C17.8731 9.32099 17.7302 9.54552 17.7302 9.78987V14.2145C17.737 15.2183 17.9208 16.2155 18.2816 17.1599H10.4802C9.57478 17.1599 8.75107 17.6882 8.39708 18.5005L6.71563 22.2978L6.03488 22.9252C5.37455 23.4799 4.99333 24.2922 5.00014 25.1441V31.8273C4.98653 33.016 5.96681 33.9868 7.19216 34C7.19896 34 7.19896 34 7.20577 34H8.4992C9.73135 33.9934 10.7252 33.0226 10.7184 31.8273V31.0216H22.4954V31.8207C22.509 33.0226 23.5097 33.9934 24.7487 33.9934H26.0421C27.2743 33.9868 28.275 33.0226 28.2818 31.8207V24.9658C28.2818 24.1667 27.9414 23.4072 27.3492 22.8591L26.8726 22.3704L26.5595 21.7034C29.3165 20.1185 31.0048 17.2391 30.998 14.1287V9.78987C31.0184 9.53892 30.8823 9.31438 30.6644 9.20212ZM9.64966 19.0222C9.79262 18.692 10.1194 18.4807 10.4802 18.4741H18.9079C19.1053 18.8175 19.3231 19.1477 19.5614 19.4647C18.4177 19.8939 17.5804 20.8647 17.3489 22.0402H8.3222L9.64966 19.0222ZM20.5757 20.5873C21.202 21.1619 21.9032 21.6506 22.6656 22.0402H18.7513C19.01 21.2477 19.7248 20.6798 20.5757 20.5873ZM9.36375 31.8207C9.37056 32.283 8.98934 32.666 8.506 32.6726H7.21258C6.72925 32.6726 6.36845 32.2896 6.36845 31.8207V30.8763C6.63394 30.982 6.92666 31.0282 7.21258 31.0216H9.36375V31.8207ZM26.9271 31.8207C26.9271 32.2896 26.5323 32.6726 26.0489 32.6726H24.7555C24.2722 32.6726 23.8705 32.2962 23.8637 31.8207V31.0216H26.0489C26.3485 31.0282 26.648 30.982 26.9271 30.8763V31.8207ZM26.9271 24.9592V28.8819C26.9271 29.3508 26.5323 29.7008 26.0489 29.7008H7.21258C6.75647 29.714 6.38206 29.364 6.36845 28.9216C6.36845 28.9083 6.36845 28.8951 6.36845 28.8819V25.1375C6.36164 24.6686 6.57267 24.2261 6.93347 23.9157C6.94028 23.9091 6.94028 23.9091 6.94709 23.9025L7.55295 23.361H25.9604L26.3757 23.7771C26.3825 23.7837 26.3961 23.7903 26.4097 23.8035C26.7365 24.1073 26.9271 24.5233 26.9271 24.9592ZM29.6501 14.1221C29.6569 17.1335 27.7985 19.8543 24.9325 21.0232L24.2858 21.294L23.7344 21.0628C20.9229 19.8741 19.0985 17.1797 19.0917 14.2013V10.1729L24.2858 7.40584L29.6501 10.1795V14.1221Z" fill="#00F0FF"/><path d="M27.8373 12.2862C27.5867 11.9511 27.1357 11.9044 26.8279 12.1771L23.4774 15.1456L22.2604 13.5951C22.0027 13.2679 21.5517 13.2289 21.251 13.5094C20.9503 13.7899 20.9145 14.2808 21.1722 14.608L22.8403 16.7351C22.8474 16.7429 22.8546 16.7507 22.8618 16.7585C22.8689 16.7663 22.8761 16.7818 22.8904 16.7896C22.9047 16.7974 22.9119 16.813 22.919 16.8208C22.9262 16.8286 22.9405 16.8364 22.9477 16.8442C22.9548 16.852 22.9691 16.8598 22.9835 16.8675C22.9906 16.8753 23.0049 16.8831 23.0121 16.8909C23.0264 16.8987 23.0336 16.9065 23.0479 16.9143C23.055 16.9221 23.0694 16.9299 23.0765 16.9299C23.0908 16.9377 23.1052 16.9455 23.1195 16.9455C23.1266 16.9533 23.141 16.9533 23.1481 16.961C23.1624 16.9688 23.1768 16.9688 23.1911 16.9766C23.1982 16.9766 23.2125 16.9844 23.2197 16.9844C23.234 16.9844 23.2483 16.9922 23.2627 16.9922C23.2698 16.9922 23.2841 17 23.2913 17C23.3128 17 23.3271 17 23.3486 17C23.3557 17 23.3629 17 23.37 17C23.3915 17 23.413 17 23.4345 17C23.4416 17 23.4416 17 23.4488 17C23.4631 17 23.4846 16.9922 23.4989 16.9922C23.5061 16.9922 23.5132 16.9922 23.5204 16.9844C23.5347 16.9844 23.549 16.9766 23.5633 16.9766C23.5705 16.9766 23.5777 16.9688 23.5848 16.9688C23.5991 16.9688 23.6063 16.961 23.6206 16.9533C23.6278 16.9533 23.6349 16.9455 23.6492 16.9455C23.6564 16.9377 23.6707 16.9377 23.6779 16.9299C23.685 16.9221 23.6922 16.9221 23.7065 16.9143C23.7208 16.9065 23.728 16.9065 23.7352 16.8987C23.7423 16.8909 23.7495 16.8831 23.7638 16.8831C23.771 16.8753 23.7853 16.8675 23.7924 16.8675C23.7996 16.8675 23.8067 16.852 23.8211 16.8442C23.8282 16.8364 23.8354 16.8364 23.8425 16.8286L27.7371 13.3848C28.0449 13.1121 28.0878 12.6212 27.8373 12.2862Z" fill="#00F0FF"/></svg>
                                </div>
                                <div class="dashboard_tab_title_text">' . $translation['text171'] . '</div>
                            </div>
                        </div>';

    $return['back_btn'] = '<div class="dashboard_back_btn" data-back="databases_start_four" data-action-id-back="28" data-database="false">
                                <img src="/images/back_bg.png" class="back_btn_bg" alt="">
                                <div class="back_btn_text">' . $translation['text22'] . '</div>
                            </div>';

    $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_start_four" data-tab="tab1"></div>

                        <div class="dashboard_tab_content_item dashboard_tab_content_item_car_register dashboard_tab_content_item_active" data-tab="car_register1">
                            <div class="dashboard_car_register1_inner">
                                <div class="dashboard_car_register1_inner_image_wrapper">
                                    <svg width="59" height="59" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11.4595 24.9998H8.54054C8.24324 24.9998 8 25.3155 8 25.7015V28.298C8 28.684 8.24324 28.9998 8.54054 28.9998H11.4595C11.7568 28.9998 12 28.684 12 28.298V25.7015C12 25.3155 11.7568 24.9998 11.4595 24.9998ZM10.9189 27.5962H9.08108V26.4033H10.9189V27.5962Z" fill="#00F0FF"/><path d="M25.3243 24.9998H21.6757C21.3041 24.9998 21 25.3155 21 25.7015V28.298C21 28.684 21.3041 28.9998 21.6757 28.9998H25.3243C25.6959 28.9998 26 28.684 26 28.298V25.7015C26 25.3155 25.6959 24.9998 25.3243 24.9998ZM24.6486 27.5962H22.3514V26.4033H24.6486V27.5962Z" fill="#00F0FF"/><path d="M19.3304 26.9998H14.6696C14.3013 26.9998 14 27.4498 14 27.9998C14 28.5498 14.2946 28.9998 14.6696 28.9998H19.3304C19.6987 28.9998 20 28.5498 20 27.9998C20 27.4498 19.6987 26.9998 19.3304 26.9998Z" fill="#00F0FF"/><path d="M19.3304 25H14.6696C14.3013 25 14 25.45 14 26C14 26.55 14.2946 27 14.6696 27H19.3304C19.6987 27 20 26.55 20 26C20 25.45 19.6987 25 19.3304 25Z" fill="#00F0FF"/><path d="M30.6644 9.20212L24.6262 6.07844C24.4219 5.97278 24.1837 5.97278 23.9794 6.08504L18.091 9.20872C17.8731 9.32099 17.7302 9.54552 17.7302 9.78987V14.2145C17.737 15.2183 17.9208 16.2155 18.2816 17.1599H10.4802C9.57478 17.1599 8.75107 17.6882 8.39708 18.5005L6.71563 22.2978L6.03488 22.9252C5.37455 23.4799 4.99333 24.2922 5.00014 25.1441V31.8273C4.98653 33.016 5.96681 33.9868 7.19216 34C7.19896 34 7.19896 34 7.20577 34H8.4992C9.73135 33.9934 10.7252 33.0226 10.7184 31.8273V31.0216H22.4954V31.8207C22.509 33.0226 23.5097 33.9934 24.7487 33.9934H26.0421C27.2743 33.9868 28.275 33.0226 28.2818 31.8207V24.9658C28.2818 24.1667 27.9414 23.4072 27.3492 22.8591L26.8726 22.3704L26.5595 21.7034C29.3165 20.1185 31.0048 17.2391 30.998 14.1287V9.78987C31.0184 9.53892 30.8823 9.31438 30.6644 9.20212ZM9.64966 19.0222C9.79262 18.692 10.1194 18.4807 10.4802 18.4741H18.9079C19.1053 18.8175 19.3231 19.1477 19.5614 19.4647C18.4177 19.8939 17.5804 20.8647 17.3489 22.0402H8.3222L9.64966 19.0222ZM20.5757 20.5873C21.202 21.1619 21.9032 21.6506 22.6656 22.0402H18.7513C19.01 21.2477 19.7248 20.6798 20.5757 20.5873ZM9.36375 31.8207C9.37056 32.283 8.98934 32.666 8.506 32.6726H7.21258C6.72925 32.6726 6.36845 32.2896 6.36845 31.8207V30.8763C6.63394 30.982 6.92666 31.0282 7.21258 31.0216H9.36375V31.8207ZM26.9271 31.8207C26.9271 32.2896 26.5323 32.6726 26.0489 32.6726H24.7555C24.2722 32.6726 23.8705 32.2962 23.8637 31.8207V31.0216H26.0489C26.3485 31.0282 26.648 30.982 26.9271 30.8763V31.8207ZM26.9271 24.9592V28.8819C26.9271 29.3508 26.5323 29.7008 26.0489 29.7008H7.21258C6.75647 29.714 6.38206 29.364 6.36845 28.9216C6.36845 28.9083 6.36845 28.8951 6.36845 28.8819V25.1375C6.36164 24.6686 6.57267 24.2261 6.93347 23.9157C6.94028 23.9091 6.94028 23.9091 6.94709 23.9025L7.55295 23.361H25.9604L26.3757 23.7771C26.3825 23.7837 26.3961 23.7903 26.4097 23.8035C26.7365 24.1073 26.9271 24.5233 26.9271 24.9592ZM29.6501 14.1221C29.6569 17.1335 27.7985 19.8543 24.9325 21.0232L24.2858 21.294L23.7344 21.0628C20.9229 19.8741 19.0985 17.1797 19.0917 14.2013V10.1729L24.2858 7.40584L29.6501 10.1795V14.1221Z" fill="#00F0FF"/><path d="M27.8373 12.2862C27.5867 11.9511 27.1357 11.9044 26.8279 12.1771L23.4774 15.1456L22.2604 13.5951C22.0027 13.2679 21.5517 13.2289 21.251 13.5094C20.9503 13.7899 20.9145 14.2808 21.1722 14.608L22.8403 16.7351C22.8474 16.7429 22.8546 16.7507 22.8618 16.7585C22.8689 16.7663 22.8761 16.7818 22.8904 16.7896C22.9047 16.7974 22.9119 16.813 22.919 16.8208C22.9262 16.8286 22.9405 16.8364 22.9477 16.8442C22.9548 16.852 22.9691 16.8598 22.9835 16.8675C22.9906 16.8753 23.0049 16.8831 23.0121 16.8909C23.0264 16.8987 23.0336 16.9065 23.0479 16.9143C23.055 16.9221 23.0694 16.9299 23.0765 16.9299C23.0908 16.9377 23.1052 16.9455 23.1195 16.9455C23.1266 16.9533 23.141 16.9533 23.1481 16.961C23.1624 16.9688 23.1768 16.9688 23.1911 16.9766C23.1982 16.9766 23.2125 16.9844 23.2197 16.9844C23.234 16.9844 23.2483 16.9922 23.2627 16.9922C23.2698 16.9922 23.2841 17 23.2913 17C23.3128 17 23.3271 17 23.3486 17C23.3557 17 23.3629 17 23.37 17C23.3915 17 23.413 17 23.4345 17C23.4416 17 23.4416 17 23.4488 17C23.4631 17 23.4846 16.9922 23.4989 16.9922C23.5061 16.9922 23.5132 16.9922 23.5204 16.9844C23.5347 16.9844 23.549 16.9766 23.5633 16.9766C23.5705 16.9766 23.5777 16.9688 23.5848 16.9688C23.5991 16.9688 23.6063 16.961 23.6206 16.9533C23.6278 16.9533 23.6349 16.9455 23.6492 16.9455C23.6564 16.9377 23.6707 16.9377 23.6779 16.9299C23.685 16.9221 23.6922 16.9221 23.7065 16.9143C23.7208 16.9065 23.728 16.9065 23.7352 16.8987C23.7423 16.8909 23.7495 16.8831 23.7638 16.8831C23.771 16.8753 23.7853 16.8675 23.7924 16.8675C23.7996 16.8675 23.8067 16.852 23.8211 16.8442C23.8282 16.8364 23.8354 16.8364 23.8425 16.8286L27.7371 13.3848C28.0449 13.1121 28.0878 12.6212 27.8373 12.2862Z" fill="#00F0FF"/></svg>
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
                                        <div class="dashboard_car_register1_input_border_right"></div>';
                                        // <input type="text" placeholder="' . $translation['text64'] . '" autocomplete="off" class="dashboard_car_register1_country">

    $sql = "
        SELECT c.code, c.pos, cd.name, c.id
        FROM countries c
        JOIN countries_description cd ON c.id = cd.country_id
        WHERE cd.lang_id = {?}
        ORDER BY cd.name
    ";
    $countries = $this->db->select($sql, [$lang_id]);
    if ($countries) {
        $return['content'] .= '<select class="dashboard_car_register1_country"><option disabled="disabled"' . (empty($team_info['car_register_country_id']) ? ' selected="selected"' : '') . '>' . $translation['text64'] . '</option>';
        foreach ($countries as $country) {
            $return['content'] .= '<option value="' . htmlspecialchars($country['name'], ENT_QUOTES) . '" data-pos="' . $country['pos'] . '"' . ($team_info['car_register_country_id'] == $country['id'] ? ' selected="selected"' : '') . '>' . $country['name'] . '</option>';
        }
        $return['content'] .= '</select>
                                <script>
                                    $(function() {
                                        // select country
                                        var scrollbarPositionPixel = 0;
                                        var isScrollOpen = false;

                                        $(".dashboard_car_register1_country").selectric({
                                            optionsItemBuilder: function(itemData, element, index) {
                                                return (!itemData.disabled) ? \'<span class="select_country_flag" style="display:inline-block;width:16px;height:11px;background:url(/images/flags.png) no-repeat;background-position:\' + itemData.element[0].attributes[\'data-pos\'].value + \';margin: 0 15px 0 5px;"></span><span class="select_country_name" style="display:inline-block;max-width: 87%;">\' + itemData.text + \'</span>\' : itemData.text;
                                            },
                                            maxHeight: 236,
                                            preventWindowScroll: false,
                                            onInit: function() {
                                                // стилизация полосы прокрутки
                                                $(".selectric-dashboard_car_register1_country .selectric-scroll").mCustomScrollbar({
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
                                            // сохраняем выбор
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
                                                        // socket
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
                                                },
                                                error: function(xhr, ajaxOptions, thrownError) {    
                                                    console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                                }
                                            });

                                            isScrollOpen = false;
                                        });

                                        $(".dashboard_tabs[data-dashboard=\'databases\']").on("click", ".dashboard_car_register1_input_wrapper_country .mCSB_scrollTools_vertical", function(e){
                                            if (isScrollOpen) {
                                                $(".dashboard_car_register1_country").selectric("open");
                                            }
                                        });

                                        // datepicker
                                        $(".dashboard_car_register1_date").datepicker({
                                            dateFormat: "dd.mm.yy",
                                            dayNamesShort: ["' . $translation['text67'] . '", "' . $translation['text68'] . '", "' . $translation['text69'] . '", "' . $translation['text70'] . '", "' . $translation['text71'] . '", "' . $translation['text72'] . '", "' . $translation['text73'] . '"],
                                            dayNamesMin: ["' . $translation['text67'] . '", "' . $translation['text68'] . '", "' . $translation['text69'] . '", "' . $translation['text70'] . '", "' . $translation['text71'] . '", "' . $translation['text72'] . '", "' . $translation['text73'] . '"],
                                            monthNames: ["' . $translation['text74'] . '", "' . $translation['text75'] . '", "' . $translation['text76'] . '", "' . $translation['text77'] . '", "' . $translation['text78'] . '", "' . $translation['text79'] . '", "' . $translation['text80'] . '", "' . $translation['text81'] . '", "' . $translation['text82'] . '", "' . $translation['text83'] . '", "' . $translation['text84'] . '", "' . $translation['text85'] . '"],
                                            changeMonth: false,
                                            //showAnim: "clip",
                                            showAnim: "",
                                            onSelect: function(dateText) {
                                                // сохраняем выбор
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
                                                        // socket
                                                        var message = {
                                                            "op": "databaseCarRegisterUpdateDate",
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

    $return['content'] .= '             <div class="dashboard_car_register1_country_error error_text_database_car_register">' . $translation['text86'] . '</div>
                                    </div>
                                </div>
                                <div class="dashboard_car_register1_fields_bottom">
                                    <div class="dashboard_car_register1_input_wrapper dashboard_car_register1_input_wrapper_date">
                                        <div class="dashboard_car_register1_input_border_left"></div>
                                        <div class="dashboard_car_register1_input_border_right"></div>
                                        <input type="text" placeholder="' . $translation['text65'] . '" autocomplete="off" class="dashboard_car_register1_date" value="' . ((!empty($team_info['car_register_date']) && $team_info['car_register_date'] != '0000-00-00' && !is_null($team_info['car_register_date'])) ? $this->fromEngDatetimeToRus($team_info['car_register_date']) : '') . '">
                                        <div class="dashboard_car_register1_date_error error_text_database_car_register">' . $translation['text86'] . '</div>
                                    </div>
                                </div>
                                <div class="btn_wrapper btn_wrapper_blue dashboard_car_register1_search">
                                    <div class="btn btn_blue">
                                        <span>' . $translation['text66'] . '</span>
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

// databases - загрузить Car Register. Второй экран. Успешно нашли Huilov
private function uploadDatabasesCarRegisterHuilov($lang_id, $team_id)
{
    $translation = $this->getWordsByPage('game', $lang_id);

    $team_info = $this->teamInfo($team_id);

    if (isset($_COOKIE['hash'])) {
        $sql = "SELECT `car_register_print_text_huilov` FROM `users` WHERE `team_id` = {?} AND `hash` = {?} LIMIT 1";
        $user_info = $this->db->selectRow($sql, [$team_id, $_COOKIE['hash']]);
    } else {
        $sql = "SELECT `car_register_print_text_huilov` FROM `users` WHERE `team_id` = {?} AND `ip` = {?} LIMIT 1";
        $user_info = $this->db->selectRow($sql, [$team_id, $this->getIp()]);
    }

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
                        <div class="dashboard_tab_title" data-tab="car_register1">
                            <div class="dashboard_tab_title_active_skew_right"></div>
                            <div class="dashboard_tab_title_inner">
                                <div class="dashboard_tab_title_img_wrapper" style="margin: -10px 0 0;">
                                    <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11.4595 24.9998H8.54054C8.24324 24.9998 8 25.3155 8 25.7015V28.298C8 28.684 8.24324 28.9998 8.54054 28.9998H11.4595C11.7568 28.9998 12 28.684 12 28.298V25.7015C12 25.3155 11.7568 24.9998 11.4595 24.9998ZM10.9189 27.5962H9.08108V26.4033H10.9189V27.5962Z" fill="#00F0FF"/><path d="M25.3243 24.9998H21.6757C21.3041 24.9998 21 25.3155 21 25.7015V28.298C21 28.684 21.3041 28.9998 21.6757 28.9998H25.3243C25.6959 28.9998 26 28.684 26 28.298V25.7015C26 25.3155 25.6959 24.9998 25.3243 24.9998ZM24.6486 27.5962H22.3514V26.4033H24.6486V27.5962Z" fill="#00F0FF"/><path d="M19.3304 26.9998H14.6696C14.3013 26.9998 14 27.4498 14 27.9998C14 28.5498 14.2946 28.9998 14.6696 28.9998H19.3304C19.6987 28.9998 20 28.5498 20 27.9998C20 27.4498 19.6987 26.9998 19.3304 26.9998Z" fill="#00F0FF"/><path d="M19.3304 25H14.6696C14.3013 25 14 25.45 14 26C14 26.55 14.2946 27 14.6696 27H19.3304C19.6987 27 20 26.55 20 26C20 25.45 19.6987 25 19.3304 25Z" fill="#00F0FF"/><path d="M30.6644 9.20212L24.6262 6.07844C24.4219 5.97278 24.1837 5.97278 23.9794 6.08504L18.091 9.20872C17.8731 9.32099 17.7302 9.54552 17.7302 9.78987V14.2145C17.737 15.2183 17.9208 16.2155 18.2816 17.1599H10.4802C9.57478 17.1599 8.75107 17.6882 8.39708 18.5005L6.71563 22.2978L6.03488 22.9252C5.37455 23.4799 4.99333 24.2922 5.00014 25.1441V31.8273C4.98653 33.016 5.96681 33.9868 7.19216 34C7.19896 34 7.19896 34 7.20577 34H8.4992C9.73135 33.9934 10.7252 33.0226 10.7184 31.8273V31.0216H22.4954V31.8207C22.509 33.0226 23.5097 33.9934 24.7487 33.9934H26.0421C27.2743 33.9868 28.275 33.0226 28.2818 31.8207V24.9658C28.2818 24.1667 27.9414 23.4072 27.3492 22.8591L26.8726 22.3704L26.5595 21.7034C29.3165 20.1185 31.0048 17.2391 30.998 14.1287V9.78987C31.0184 9.53892 30.8823 9.31438 30.6644 9.20212ZM9.64966 19.0222C9.79262 18.692 10.1194 18.4807 10.4802 18.4741H18.9079C19.1053 18.8175 19.3231 19.1477 19.5614 19.4647C18.4177 19.8939 17.5804 20.8647 17.3489 22.0402H8.3222L9.64966 19.0222ZM20.5757 20.5873C21.202 21.1619 21.9032 21.6506 22.6656 22.0402H18.7513C19.01 21.2477 19.7248 20.6798 20.5757 20.5873ZM9.36375 31.8207C9.37056 32.283 8.98934 32.666 8.506 32.6726H7.21258C6.72925 32.6726 6.36845 32.2896 6.36845 31.8207V30.8763C6.63394 30.982 6.92666 31.0282 7.21258 31.0216H9.36375V31.8207ZM26.9271 31.8207C26.9271 32.2896 26.5323 32.6726 26.0489 32.6726H24.7555C24.2722 32.6726 23.8705 32.2962 23.8637 31.8207V31.0216H26.0489C26.3485 31.0282 26.648 30.982 26.9271 30.8763V31.8207ZM26.9271 24.9592V28.8819C26.9271 29.3508 26.5323 29.7008 26.0489 29.7008H7.21258C6.75647 29.714 6.38206 29.364 6.36845 28.9216C6.36845 28.9083 6.36845 28.8951 6.36845 28.8819V25.1375C6.36164 24.6686 6.57267 24.2261 6.93347 23.9157C6.94028 23.9091 6.94028 23.9091 6.94709 23.9025L7.55295 23.361H25.9604L26.3757 23.7771C26.3825 23.7837 26.3961 23.7903 26.4097 23.8035C26.7365 24.1073 26.9271 24.5233 26.9271 24.9592ZM29.6501 14.1221C29.6569 17.1335 27.7985 19.8543 24.9325 21.0232L24.2858 21.294L23.7344 21.0628C20.9229 19.8741 19.0985 17.1797 19.0917 14.2013V10.1729L24.2858 7.40584L29.6501 10.1795V14.1221Z" fill="#00F0FF"/><path d="M27.8373 12.2862C27.5867 11.9511 27.1357 11.9044 26.8279 12.1771L23.4774 15.1456L22.2604 13.5951C22.0027 13.2679 21.5517 13.2289 21.251 13.5094C20.9503 13.7899 20.9145 14.2808 21.1722 14.608L22.8403 16.7351C22.8474 16.7429 22.8546 16.7507 22.8618 16.7585C22.8689 16.7663 22.8761 16.7818 22.8904 16.7896C22.9047 16.7974 22.9119 16.813 22.919 16.8208C22.9262 16.8286 22.9405 16.8364 22.9477 16.8442C22.9548 16.852 22.9691 16.8598 22.9835 16.8675C22.9906 16.8753 23.0049 16.8831 23.0121 16.8909C23.0264 16.8987 23.0336 16.9065 23.0479 16.9143C23.055 16.9221 23.0694 16.9299 23.0765 16.9299C23.0908 16.9377 23.1052 16.9455 23.1195 16.9455C23.1266 16.9533 23.141 16.9533 23.1481 16.961C23.1624 16.9688 23.1768 16.9688 23.1911 16.9766C23.1982 16.9766 23.2125 16.9844 23.2197 16.9844C23.234 16.9844 23.2483 16.9922 23.2627 16.9922C23.2698 16.9922 23.2841 17 23.2913 17C23.3128 17 23.3271 17 23.3486 17C23.3557 17 23.3629 17 23.37 17C23.3915 17 23.413 17 23.4345 17C23.4416 17 23.4416 17 23.4488 17C23.4631 17 23.4846 16.9922 23.4989 16.9922C23.5061 16.9922 23.5132 16.9922 23.5204 16.9844C23.5347 16.9844 23.549 16.9766 23.5633 16.9766C23.5705 16.9766 23.5777 16.9688 23.5848 16.9688C23.5991 16.9688 23.6063 16.961 23.6206 16.9533C23.6278 16.9533 23.6349 16.9455 23.6492 16.9455C23.6564 16.9377 23.6707 16.9377 23.6779 16.9299C23.685 16.9221 23.6922 16.9221 23.7065 16.9143C23.7208 16.9065 23.728 16.9065 23.7352 16.8987C23.7423 16.8909 23.7495 16.8831 23.7638 16.8831C23.771 16.8753 23.7853 16.8675 23.7924 16.8675C23.7996 16.8675 23.8067 16.852 23.8211 16.8442C23.8282 16.8364 23.8354 16.8364 23.8425 16.8286L27.7371 13.3848C28.0449 13.1121 28.0878 12.6212 27.8373 12.2862Z" fill="#00F0FF"/></svg>
                                </div>
                                <div class="dashboard_tab_title_text">' . $translation['text171'] . '</div>
                            </div>
                        </div>
                        <div class="dashboard_tab_title dashboard_tab_title_active" data-tab="car_register2">
                            <div class="dashboard_tab_title_active_skew_right"></div>
                            <div class="dashboard_tab_title_inner">
                                <div class="dashboard_tab_title_img_wrapper" style="margin: -10px 0 0;">
                                    <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11.4595 24.9998H8.54054C8.24324 24.9998 8 25.3155 8 25.7015V28.298C8 28.684 8.24324 28.9998 8.54054 28.9998H11.4595C11.7568 28.9998 12 28.684 12 28.298V25.7015C12 25.3155 11.7568 24.9998 11.4595 24.9998ZM10.9189 27.5962H9.08108V26.4033H10.9189V27.5962Z" fill="#00F0FF"/><path d="M25.3243 24.9998H21.6757C21.3041 24.9998 21 25.3155 21 25.7015V28.298C21 28.684 21.3041 28.9998 21.6757 28.9998H25.3243C25.6959 28.9998 26 28.684 26 28.298V25.7015C26 25.3155 25.6959 24.9998 25.3243 24.9998ZM24.6486 27.5962H22.3514V26.4033H24.6486V27.5962Z" fill="#00F0FF"/><path d="M19.3304 26.9998H14.6696C14.3013 26.9998 14 27.4498 14 27.9998C14 28.5498 14.2946 28.9998 14.6696 28.9998H19.3304C19.6987 28.9998 20 28.5498 20 27.9998C20 27.4498 19.6987 26.9998 19.3304 26.9998Z" fill="#00F0FF"/><path d="M19.3304 25H14.6696C14.3013 25 14 25.45 14 26C14 26.55 14.2946 27 14.6696 27H19.3304C19.6987 27 20 26.55 20 26C20 25.45 19.6987 25 19.3304 25Z" fill="#00F0FF"/><path d="M30.6644 9.20212L24.6262 6.07844C24.4219 5.97278 24.1837 5.97278 23.9794 6.08504L18.091 9.20872C17.8731 9.32099 17.7302 9.54552 17.7302 9.78987V14.2145C17.737 15.2183 17.9208 16.2155 18.2816 17.1599H10.4802C9.57478 17.1599 8.75107 17.6882 8.39708 18.5005L6.71563 22.2978L6.03488 22.9252C5.37455 23.4799 4.99333 24.2922 5.00014 25.1441V31.8273C4.98653 33.016 5.96681 33.9868 7.19216 34C7.19896 34 7.19896 34 7.20577 34H8.4992C9.73135 33.9934 10.7252 33.0226 10.7184 31.8273V31.0216H22.4954V31.8207C22.509 33.0226 23.5097 33.9934 24.7487 33.9934H26.0421C27.2743 33.9868 28.275 33.0226 28.2818 31.8207V24.9658C28.2818 24.1667 27.9414 23.4072 27.3492 22.8591L26.8726 22.3704L26.5595 21.7034C29.3165 20.1185 31.0048 17.2391 30.998 14.1287V9.78987C31.0184 9.53892 30.8823 9.31438 30.6644 9.20212ZM9.64966 19.0222C9.79262 18.692 10.1194 18.4807 10.4802 18.4741H18.9079C19.1053 18.8175 19.3231 19.1477 19.5614 19.4647C18.4177 19.8939 17.5804 20.8647 17.3489 22.0402H8.3222L9.64966 19.0222ZM20.5757 20.5873C21.202 21.1619 21.9032 21.6506 22.6656 22.0402H18.7513C19.01 21.2477 19.7248 20.6798 20.5757 20.5873ZM9.36375 31.8207C9.37056 32.283 8.98934 32.666 8.506 32.6726H7.21258C6.72925 32.6726 6.36845 32.2896 6.36845 31.8207V30.8763C6.63394 30.982 6.92666 31.0282 7.21258 31.0216H9.36375V31.8207ZM26.9271 31.8207C26.9271 32.2896 26.5323 32.6726 26.0489 32.6726H24.7555C24.2722 32.6726 23.8705 32.2962 23.8637 31.8207V31.0216H26.0489C26.3485 31.0282 26.648 30.982 26.9271 30.8763V31.8207ZM26.9271 24.9592V28.8819C26.9271 29.3508 26.5323 29.7008 26.0489 29.7008H7.21258C6.75647 29.714 6.38206 29.364 6.36845 28.9216C6.36845 28.9083 6.36845 28.8951 6.36845 28.8819V25.1375C6.36164 24.6686 6.57267 24.2261 6.93347 23.9157C6.94028 23.9091 6.94028 23.9091 6.94709 23.9025L7.55295 23.361H25.9604L26.3757 23.7771C26.3825 23.7837 26.3961 23.7903 26.4097 23.8035C26.7365 24.1073 26.9271 24.5233 26.9271 24.9592ZM29.6501 14.1221C29.6569 17.1335 27.7985 19.8543 24.9325 21.0232L24.2858 21.294L23.7344 21.0628C20.9229 19.8741 19.0985 17.1797 19.0917 14.2013V10.1729L24.2858 7.40584L29.6501 10.1795V14.1221Z" fill="#00F0FF"/><path d="M27.8373 12.2862C27.5867 11.9511 27.1357 11.9044 26.8279 12.1771L23.4774 15.1456L22.2604 13.5951C22.0027 13.2679 21.5517 13.2289 21.251 13.5094C20.9503 13.7899 20.9145 14.2808 21.1722 14.608L22.8403 16.7351C22.8474 16.7429 22.8546 16.7507 22.8618 16.7585C22.8689 16.7663 22.8761 16.7818 22.8904 16.7896C22.9047 16.7974 22.9119 16.813 22.919 16.8208C22.9262 16.8286 22.9405 16.8364 22.9477 16.8442C22.9548 16.852 22.9691 16.8598 22.9835 16.8675C22.9906 16.8753 23.0049 16.8831 23.0121 16.8909C23.0264 16.8987 23.0336 16.9065 23.0479 16.9143C23.055 16.9221 23.0694 16.9299 23.0765 16.9299C23.0908 16.9377 23.1052 16.9455 23.1195 16.9455C23.1266 16.9533 23.141 16.9533 23.1481 16.961C23.1624 16.9688 23.1768 16.9688 23.1911 16.9766C23.1982 16.9766 23.2125 16.9844 23.2197 16.9844C23.234 16.9844 23.2483 16.9922 23.2627 16.9922C23.2698 16.9922 23.2841 17 23.2913 17C23.3128 17 23.3271 17 23.3486 17C23.3557 17 23.3629 17 23.37 17C23.3915 17 23.413 17 23.4345 17C23.4416 17 23.4416 17 23.4488 17C23.4631 17 23.4846 16.9922 23.4989 16.9922C23.5061 16.9922 23.5132 16.9922 23.5204 16.9844C23.5347 16.9844 23.549 16.9766 23.5633 16.9766C23.5705 16.9766 23.5777 16.9688 23.5848 16.9688C23.5991 16.9688 23.6063 16.961 23.6206 16.9533C23.6278 16.9533 23.6349 16.9455 23.6492 16.9455C23.6564 16.9377 23.6707 16.9377 23.6779 16.9299C23.685 16.9221 23.6922 16.9221 23.7065 16.9143C23.7208 16.9065 23.728 16.9065 23.7352 16.8987C23.7423 16.8909 23.7495 16.8831 23.7638 16.8831C23.771 16.8753 23.7853 16.8675 23.7924 16.8675C23.7996 16.8675 23.8067 16.852 23.8211 16.8442C23.8282 16.8364 23.8354 16.8364 23.8425 16.8286L27.7371 13.3848C28.0449 13.1121 28.0878 12.6212 27.8373 12.2862Z" fill="#00F0FF"/></svg>
                                </div>
                                <div class="dashboard_tab_title_text">' . $translation['text90'] . '</div>
                            </div>
                        </div>';

    $return['back_btn'] = '<div class="dashboard_back_btn" data-back="databases_start_four" data-action-id-back="28" data-database="false">
                                <img src="/images/back_bg.png" class="back_btn_bg" alt="">
                                <div class="back_btn_text">' . $translation['text22'] . '</div>
                            </div>';

    $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_start_four" data-tab="tab1"></div>

                        <div class="dashboard_tab_content_item dashboard_tab_content_item_car_register" data-tab="car_register1"></div>

                        <div class="dashboard_tab_content_item dashboard_tab_content_item_car_register_huilov dashboard_tab_content_item_active" data-tab="car_register2">
                            <div class="dashboard_car_register2_inner' . (empty($user_info['car_register_print_text_huilov']) ? ' dashboard_car_register2_inner_bubble' : '') . (empty($team_info['car_register_print_text_huilov']) ? ' dashboard_car_register2_inner_bubble_team' : '') . '">
                                <div class="dashboard_car_register2_left">
                                    <div class="dashboard_car_register2_title">' . $translation['text91'] . '</div>
                                    <div class="dashboard_car_register2_text_wrapper dashboard_car_register2_text_wrapper1">
                                        <div class="dashboard_car_register2_text_row">
                                            <span class="dashboard_car_register2_text_title dashboard_car_register2_bubble" data-bubble="0">' . (empty($user_info['car_register_print_text_huilov']) ? '' : $translation['text92']) . '</span>
                                            <span class="dashboard_car_register2_text dashboard_car_register2_bubble" data-bubble="1">' . (empty($user_info['car_register_print_text_huilov']) ? '' : $translation['text93']) . '</span>
                                        </div>
                                        <div class="dashboard_car_register2_text_row">
                                            <span class="dashboard_car_register2_text_title dashboard_car_register2_bubble" data-bubble="2">' . (empty($user_info['car_register_print_text_huilov']) ? '' : $translation['text94']) . '</span>
                                            <span class="dashboard_car_register2_text dashboard_car_register2_bubble" data-bubble="3">' . (empty($user_info['car_register_print_text_huilov']) ? '' : $translation['text95']) . '</span>
                                        </div>
                                    </div>

                                    <div class="dashboard_car_register2_title">' . $translation['text96'] . '</div>
                                    <div class="dashboard_car_register2_text_wrapper dashboard_car_register2_text_wrapper2">
                                        <div class="dashboard_car_register2_text_row">
                                            <span class="dashboard_car_register2_text_title dashboard_car_register2_bubble" data-bubble="4">' . (empty($user_info['car_register_print_text_huilov']) ? '' : $translation['text97']) . '</span>
                                            <span class="dashboard_car_register2_text dashboard_car_register2_bubble" data-bubble="5">' . (empty($user_info['car_register_print_text_huilov']) ? '' : $translation['text98']) . '</span>
                                        </div>
                                        <div class="dashboard_car_register2_text_row">
                                            <span class="dashboard_car_register2_text_title dashboard_car_register2_bubble" data-bubble="6">' . (empty($user_info['car_register_print_text_huilov']) ? '' : $translation['text99']) . '</span>
                                            <span class="dashboard_car_register2_text dashboard_car_register2_bubble" data-bubble="7">' . (empty($user_info['car_register_print_text_huilov']) ? '' : $translation['text100']) . '</span>
                                        </div>
                                        <div class="dashboard_car_register2_text_row">
                                            <span class="dashboard_car_register2_text_title dashboard_car_register2_bubble" data-bubble="8">' . (empty($user_info['car_register_print_text_huilov']) ? '' : $translation['text101']) . '</span>
                                            <span class="dashboard_car_register2_text dashboard_car_register2_bubble" data-bubble="9">' . (empty($user_info['car_register_print_text_huilov']) ? '' : $translation['text102']) . '</span>
                                        </div>
                                    </div>

                                    <div class="dashboard_car_register2_text_wrapper dashboard_car_register2_text_wrapper3">
                                        <div class="dashboard_car_register2_text_row">
                                            <span class="dashboard_car_register2_text_title dashboard_car_register2_bubble" data-bubble="10">' . (empty($user_info['car_register_print_text_huilov']) ? '' : $translation['text103']) . '</span>
                                            <span class="dashboard_car_register2_text dashboard_car_register2_bubble" data-bubble="11">' . (empty($user_info['car_register_print_text_huilov']) ? '' : $translation['text104']) . '</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="dashboard_car_register2_right">
                                    <div class="dashboard_car_register2_title">' . $translation['text105'] . '</div>
                                    <div class="dashboard_car_register2_slider_wrapper">
                                        <div class="dashboard_car_register2_slider">
                                            <div><img src="/images/slider_stalin_car/stalin_car1.png" alt=""></div>
                                            <div><img src="/images/slider_stalin_car/stalin_car2.png" alt=""></div>
                                            <div><img src="/images/slider_stalin_car/stalin_car3.png" alt=""></div>
                                        </div>
                                        <div class="dashboard_car_register2_slider_arrows">
                                            <div class="dashboard_car_register2_slider_arrow_left"><svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.62094 13.8289C7.84901 13.6009 7.84901 13.2322 7.62094 13.0041L1.61682 6.99998L7.62094 0.995859C7.84901 0.767789 7.84901 0.399121 7.62094 0.171052C7.39287 -0.0570174 7.0242 -0.0570174 6.79613 0.171052L0.379595 6.58759C0.265847 6.70134 0.208673 6.85066 0.208673 7.00001C0.208673 7.14936 0.265847 7.29868 0.379595 7.41242L6.79613 13.829C7.0242 14.057 7.39287 14.057 7.62094 13.8289Z" fill="white"/></svg></div>
                                            <div class="dashboard_car_register2_slider_arrow_number">1</div>
                                            <div class="dashboard_car_register2_slider_arrow_right"><svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.37906 0.171063C0.150991 0.399133 0.150991 0.7678 0.37906 0.99587L6.38318 7.00002L0.37906 13.0041C0.15099 13.2322 0.15099 13.6009 0.37906 13.8289C0.607129 14.057 0.975797 14.057 1.20387 13.8289L7.6204 7.41241C7.73415 7.29866 7.79133 7.14934 7.79133 6.99999C7.79133 6.85064 7.73415 6.70132 7.6204 6.58758L1.20387 0.171036C0.975797 -0.0570068 0.607129 -0.0570059 0.37906 0.171063Z" fill="white"/></svg></div>
                                        </div>
                                        <div class="dashboard_car_register2_slider_border_right_bottom">
                                            <svg width="57" height="84" viewBox="0 0 57 84" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M47.9043 8.02548L56.3936 0.535032L57 0V67.949L38.8085 84H0L8.48936 76.5096H35.1702L47.9043 65.2739V8.02548Z" fill="#00F0FF"/></svg>
                                        </div>
                                        <div class="dashboard_car_register2_slider_picture_counter">
                                            <div class="dashboard_car_register2_slider_picture_counter_bg">
                                                <svg width="107" height="57" viewBox="0 0 107 57" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.5 25.5L0.5 0L107 -8.40556e-07L107 18.1915L107 57L106.51 32.5L8.5 25.5Z" fill="#00F0FF"/></svg>
                                            </div>
                                            <div class="dashboard_car_register2_slider_picture_text">
                                                ' . $translation['text106'] . ': <span>1</span>/3
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>';

    // при первом запуске
    if (empty($team_info['car_register_print_text_huilov'])) {
        // обновляем значение, что текст напечатан. Повторно скрипт НЕ закускается
        $sql = "UPDATE `teams` SET `car_register_print_text_huilov` = {?} WHERE `id` = {?}";
        $this->db->query($sql, [1, $team_id]);

        // обновляем подсказки
        // список открытых
        $active_hints = [];

        // список доступных
        $list_hints = [];

        $hints_by_step = $this->getHintsByStep('car_register_huilov', $lang_id);
        if ($hints_by_step) {
            foreach ($hints_by_step as $hint) {
                $list_hints[] = $hint['id'];
            }
        }

        // сохраняем обновленный список подсказок
        $sql = "UPDATE `teams` SET `active_hints` = {?}, `list_hints` = {?}, `list_hints_title_lang_var` = {?}, `list_hints_text_lang_var` = {?} WHERE `id` = {?}";
        $this->db->query($sql, [json_encode($active_hints, JSON_UNESCAPED_UNICODE), json_encode($list_hints, JSON_UNESCAPED_UNICODE), 'text44', 'text45', $team_id]);
    }

    // обновляем, что текст напечатан для этого юзера
    if (isset($_COOKIE['hash'])) {
        $sql = "UPDATE `users` SET `car_register_print_text_huilov` = {?} WHERE `team_id` = {?} AND `hash` = {?}";
        $this->db->query($sql, [1, $team_id, $_COOKIE['hash']]);
    } else {
        $sql = "UPDATE `users` SET `car_register_print_text_huilov` = {?} WHERE `team_id` = {?} AND `ip` = {?}";
        $this->db->query($sql, [1, $team_id, $this->getIp()]);
    }

    // возвращаем также массив для отпечатки текста на разных языках
    $return['error_lang'] = [];

    $sql = "SELECT `id`, `lang_abbr` FROM `langs` WHERE `status` = {?}";
    $langs = $this->db->select($sql, [1]);
    if ($langs) {
        foreach ($langs as $lang_item) {
            $translation = $this->getWordsByPage('game', $lang_item['id']);

            $return['error_lang'][$lang_item['lang_abbr']] = [
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

    return $return;
}

// databases - загрузить Personal Files. Первый экран
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

// databases - загрузить Personal Files. Второй экран. Private Individual
private function uploadDatabasesPersonalFilesPrivateIndividual($lang_id, $team_id)
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
                        <div class="dashboard_tab_title dashboard_tab_title_can_click" data-tab="personal_files1" data-step="databases_start_four_inner_first_personal_files" data-action-id="32" data-database="personal_files">
                            <div class="dashboard_tab_title_active_skew_right"></div>
                            <div class="dashboard_tab_title_inner">
                                <div class="dashboard_tab_title_img_wrapper" style="margin: -3px 0 0;">
                                    <img src="/images/icons/icon_tab_personal_files.png" alt="">
                                </div>
                                <div class="dashboard_tab_title_text">' . $translation['text170'] . '</div>
                            </div>
                        </div>

                        <div class="dashboard_tab_title dashboard_tab_title_active" data-tab="personal_files2">
                            <div class="dashboard_tab_title_active_skew_right"></div>
                            <div class="dashboard_tab_title_inner">
                                <div class="dashboard_tab_title_img_wrapper" style="margin: -1px 0 0;">
                                    <img src="/images/icons/icon_tab_personal_files_private_individual.png" alt="">
                                </div>
                                <div class="dashboard_tab_title_text">' . $translation['text108'] . '</div>
                            </div>
                        </div>';

    $return['back_btn'] = '<div class="dashboard_back_btn" data-back="databases_start_four_inner_first_personal_files" data-action-id-back="32" data-database="personal_files">
                                <img src="/images/back_bg.png" class="back_btn_bg" alt="">
                                <div class="back_btn_text">' . $translation['text22'] . '</div>
                            </div>';

    $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_start_four" data-tab="tab1"></div>

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
                                    <div class="btn btn_blue">
                                        <span>' . $translation['text66'] . '</span>
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

// databases - загрузить Personal Files. Второй экран. Private Individual - Huilov
private function uploadDatabasesPersonalFilesPrivateIndividualHuilov($lang_id, $team_id)
{
    $translation = $this->getWordsByPage('game', $lang_id);

    $team_info = $this->teamInfo($team_id);

    if (isset($_COOKIE['hash'])) {
        $sql = "SELECT `private_individuals_print_text_huilov` FROM `users` WHERE `team_id` = {?} AND `hash` = {?} LIMIT 1";
        $user_info = $this->db->selectRow($sql, [$team_id, $_COOKIE['hash']]);
    } else {
        $sql = "SELECT `private_individuals_print_text_huilov` FROM `users` WHERE `team_id` = {?} AND `ip` = {?} LIMIT 1";
        $user_info = $this->db->selectRow($sql, [$team_id, $this->getIp()]);
    }

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
                        <div class="dashboard_tab_title dashboard_tab_title_can_click" data-tab="personal_files1" data-step="databases_start_four_inner_first_personal_files" data-action-id="32" data-database="personal_files">
                            <div class="dashboard_tab_title_active_skew_right"></div>
                            <div class="dashboard_tab_title_inner">
                                <div class="dashboard_tab_title_img_wrapper" style="margin: -3px 0 0;">
                                    <img src="/images/icons/icon_tab_personal_files.png" alt="">
                                </div>
                                <div class="dashboard_tab_title_text">' . $translation['text170'] . '</div>
                            </div>
                        </div>

                        <div class="dashboard_tab_title dashboard_tab_title_active" data-tab="personal_files2">
                            <div class="dashboard_tab_title_active_skew_right"></div>
                            <div class="dashboard_tab_title_inner">
                                <div class="dashboard_tab_title_img_wrapper" style="margin: -1px 0 0;">
                                    <img src="/images/icons/icon_tab_personal_files_private_individual.png" alt="">
                                </div>
                                <div class="dashboard_tab_title_text">' . $translation['text104'] . '</div>
                            </div>
                        </div>';

    $return['back_btn'] = '<div class="dashboard_back_btn" data-back="databases_start_four_inner_first_personal_files" data-action-id-back="32" data-database="personal_files">
                                <img src="/images/back_bg.png" class="back_btn_bg" alt="">
                                <div class="back_btn_text">' . $translation['text22'] . '</div>
                            </div>';

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
                                $rowsHtml .= '
                                    <div class="dashboard_personal_files2_private_individuals_huilov_data_row ' . ($row['class'] ?? '') . '">
                                        <div class="dashboard_personal_files2_private_individuals_huilov_label">
                                            ' . $translation[$row['label']] . '
                                        </div>
                                        <div class="dashboard_personal_files2_private_individuals_huilov_input">
                                            <span class="dots_top"></span>
                                            <span class="dots_bottom_left"></span>
                                            <span class="dots_bottom_right"></span>
                                            <span class="private_individuals_huilov_text" data-bubble="' . $row['id'] . '">
                                                <span>' . (empty($user_info['private_individuals_print_text_huilov']) ? '' : $translation[$row['text']]) . '</span>
                                            </span>
                                        </div>
                                    </div>
                                ';
                            }
                            
                            $return['content'] = '
                            <div class="dashboard_tab_content_item dashboard_tab_content_item_start_four dashboard_tab_content_item_active" data-tab="personal_files2">
                                <div class="dashboard_personal_files2_private_individuals_huilov_inner' 
                                    . (empty($user_info['private_individuals_print_text_huilov']) ? ' dashboard_personal_files2_private_individuals_huilov_inner_bubble' : '') 
                                    . (empty($team_info['private_individuals_print_text_huilov']) ? ' dashboard_personal_files2_private_individuals_huilov_inner_bubble_team' : '') . '">
                            
                                    <!-- левая часть без изменений -->
                            
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
                            
    // при первом запуске
    if (empty($team_info['private_individuals_print_text_huilov'])) {
        // обновляем значение, что текст напечатан. Повторно скрипт НЕ закускается
        $sql = "UPDATE `teams` SET `private_individuals_print_text_huilov` = {?} WHERE `id` = {?}";
        $this->db->query($sql, [1, $team_id]);

        // обновляем подсказки
        // список открытых
        $active_hints = [];

        // список доступных
        $list_hints = [];

        $hints_by_step = $this->getHintsByStep('private_individuals_huilov', $lang_id);
        if ($hints_by_step) {
            foreach ($hints_by_step as $hint) {
                $list_hints[] = $hint['id'];
            }
        }

        // сохраняем обновленный список подсказок
        $sql = "UPDATE `teams` SET `active_hints` = {?}, `list_hints` = {?}, `list_hints_title_lang_var` = {?}, `list_hints_text_lang_var` = {?} WHERE `id` = {?}";
        $this->db->query($sql, [json_encode($active_hints, JSON_UNESCAPED_UNICODE), json_encode($list_hints, JSON_UNESCAPED_UNICODE), 'text44', 'text45', $team_id]);
    }

    // обновляем, что текст напечатан для этого юзера
    if (isset($_COOKIE['hash'])) {
        $sql = "UPDATE `users` SET `private_individuals_print_text_huilov` = {?} WHERE `team_id` = {?} AND `hash` = {?}";
        $this->db->query($sql, [1, $team_id, $_COOKIE['hash']]);
    } else {
        $sql = "UPDATE `users` SET `private_individuals_print_text_huilov` = {?} WHERE `team_id` = {?} AND `ip` = {?}";
        $this->db->query($sql, [1, $team_id, $this->getIp()]);
    }

    // возвращаем также массив для отпечатки текста на разных языках
    $return['error_lang'] = [];

    $sql = "SELECT `id`, `lang_abbr` FROM `langs` WHERE `status` = {?}";
    $langs = $this->db->select($sql, [1]);
    if ($langs) {
        foreach ($langs as $lang_item) {
            $translation = $this->getWordsByPage('game', $lang_item['id']);

            $return['error_lang'][$lang_item['lang_abbr']] = [
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

    return $return;
}

// databases - загрузить Personal Files. Второй экран. Ceo Database
private function uploadDatabasesPersonalFilesCeoDatabase($lang_id, $team_id)
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
                        <div class="dashboard_tab_title dashboard_tab_title_can_click" data-tab="personal_files1" data-step="databases_start_four_inner_first_personal_files" data-action-id="32" data-database="personal_files">
                            <div class="dashboard_tab_title_active_skew_right"></div>
                            <div class="dashboard_tab_title_inner">
                                <div class="dashboard_tab_title_img_wrapper" style="margin: -3px 0 0;">
                                    <img src="/images/icons/icon_tab_personal_files.png" alt="">
                                </div>
                                <div class="dashboard_tab_title_text">' . $translation['text170'] . '</div>
                            </div>
                        </div>

                        <div class="dashboard_tab_title dashboard_tab_title_active" data-tab="personal_files2">
                            <div class="dashboard_tab_title_active_skew_right"></div>
                            <div class="dashboard_tab_title_inner">
                                <div class="dashboard_tab_title_img_wrapper" style="margin: -2px 0 0;">
                                    <img src="/images/icons/icon_tab_ceo_database.png" alt="">
                                </div>
                                <div class="dashboard_tab_title_text">' . $translation['text109'] . '</div>
                            </div>
                        </div>';

    $return['back_btn'] = '<div class="dashboard_back_btn" data-back="databases_start_four_inner_first_personal_files" data-action-id-back="32" data-database="personal_files">
                                <img src="/images/back_bg.png" class="back_btn_bg" alt="">
                                <div class="back_btn_text">' . $translation['text22'] . '</div>
                            </div>';

    $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_start_four" data-tab="tab1"></div>

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
                                    <div class="btn btn_blue">
                                        <span>' . $translation['text66'] . '</span>
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

// databases - загрузить Personal Files. Второй экран. Ceo Database - Rod
private function uploadDatabasesPersonalFilesCeoDatabaseRod($lang_id, $team_id)
{
    $translation = $this->getWordsByPage('game', $lang_id);

    $team_info = $this->teamInfo($team_id);

    if (isset($_COOKIE['hash'])) {
        $sql = "SELECT `ceo_database_print_text_rod` FROM `users` WHERE `team_id` = {?} AND `hash` = {?} LIMIT 1";
        $user_info = $this->db->selectRow($sql, [$team_id, $_COOKIE['hash']]);
    } else {
        $sql = "SELECT `ceo_database_print_text_rod` FROM `users` WHERE `team_id` = {?} AND `ip` = {?} LIMIT 1";
        $user_info = $this->db->selectRow($sql, [$team_id, $this->getIp()]);
    }

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
                        <div class="dashboard_tab_title dashboard_tab_title_can_click" data-tab="personal_files1" data-step="databases_start_four_inner_first_personal_files" data-action-id="32" data-database="personal_files">
                            <div class="dashboard_tab_title_active_skew_right"></div>
                            <div class="dashboard_tab_title_inner">
                                <div class="dashboard_tab_title_img_wrapper" style="margin: -3px 0 0;">
                                    <img src="/images/icons/icon_tab_personal_files.png" alt="">
                                </div>
                                <div class="dashboard_tab_title_text">' . $translation['text170'] . '</div>
                            </div>
                        </div>

                        <div class="dashboard_tab_title dashboard_tab_title_active" data-tab="personal_files2">
                            <div class="dashboard_tab_title_active_skew_right"></div>
                            <div class="dashboard_tab_title_inner">
                                <div class="dashboard_tab_title_img_wrapper" style="margin: -2px 0 0;">
                                    <img src="/images/icons/icon_tab_ceo_database.png" alt="">
                                </div>
                                <div class="dashboard_tab_title_text">' . $translation['text132'] . '</div>
                            </div>
                        </div>';

    $return['back_btn'] = '<div class="dashboard_back_btn" data-back="databases_start_four_inner_first_personal_files" data-action-id-back="32" data-database="personal_files">
                                <img src="/images/back_bg.png" class="back_btn_bg" alt="">
                                <div class="back_btn_text">' . $translation['text22'] . '</div>
                            </div>';

    $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_start_four" data-tab="tab1"></div>

                        <div class="dashboard_tab_content_item dashboard_tab_content_item_start_four" data-tab="personal_files1"></div>
    
                        <div class="dashboard_tab_content_item dashboard_tab_content_item_start_four dashboard_tab_content_item_active" data-tab="personal_files2">
                            <div class="dashboard_personal_files2_private_individuals_huilov_inner' . (empty($user_info['ceo_database_print_text_rod']) ? ' dashboard_personal_files2_ceo_database_rod_inner_bubble' : '') . (empty($team_info['ceo_database_print_text_rod']) ? ' dashboard_personal_files2_ceo_database_rod_inner_bubble_team' : '') . '"">
                                <div class="dashboard_personal_files2_private_individuals_huilov_right_bg_line">
                                    <svg width="868" height="418" viewBox="0 0 868 418" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.5 1H790L809.5 20.5V405L822 417.5H867.5" stroke="#FF004E"/></svg>
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
                                        <div class="dashboard_personal_files2_private_individuals_huilov_images_bg_right"><svg width="9" height="396" viewBox="0 0 9 396" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 1H8.5V395.5H3H0" stroke="#FF004E"/></svg></div>
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
                                    <div class="dashboard_personal_files2_private_individuals_huilov_data_row">
                                        <div class="dashboard_personal_files2_private_individuals_huilov_label">' . $translation['text115'] . '</div>
                                        <div class="dashboard_personal_files2_private_individuals_huilov_input">
                                            <span class="dots_top"></span><span class="dots_bottom_left"></span><span class="dots_bottom_right"></span>
                                            <span class="private_individuals_huilov_text" data-bubble="0"><span>' . (empty($user_info['ceo_database_print_text_rod']) ? '' : $translation['text133']) . '</span></span>
                                        </div>
                                    </div>
                                    <div class="dashboard_personal_files2_private_individuals_huilov_data_row dashboard_personal_files2_private_individuals_huilov_data_row_lastname">
                                        <div class="dashboard_personal_files2_private_individuals_huilov_label">' . $translation['text117'] . '</div>
                                        <div class="dashboard_personal_files2_private_individuals_huilov_input">
                                            <span class="dots_top"></span><span class="dots_bottom_left"></span><span class="dots_bottom_right"></span>
                                            <span class="private_individuals_huilov_text" data-bubble="1"><span>' . (empty($user_info['ceo_database_print_text_rod']) ? '' : $translation['text134']) . '</span></span>
                                        </div>
                                    </div>
                                    <div class="dashboard_personal_files2_private_individuals_huilov_data_row">
                                        <div class="dashboard_personal_files2_private_individuals_huilov_label">' . $translation['text119'] . '</div>
                                        <div class="dashboard_personal_files2_private_individuals_huilov_input">
                                            <span class="dots_top"></span><span class="dots_bottom_left"></span><span class="dots_bottom_right"></span>
                                            <span class="private_individuals_huilov_text" data-bubble="2"><span>' . (empty($user_info['ceo_database_print_text_rod']) ? '' : $translation['text135']) . '</span></span>
                                        </div>
                                    </div>
                                    <div class="dashboard_personal_files2_private_individuals_huilov_data_row">
                                        <div class="dashboard_personal_files2_private_individuals_huilov_label">' . $translation['text136'] . '</div>
                                        <div class="dashboard_personal_files2_private_individuals_huilov_input">
                                            <span class="dots_top"></span><span class="dots_bottom_left"></span><span class="dots_bottom_right"></span>
                                            <span class="private_individuals_huilov_text" data-bubble="3"><span>' . (empty($user_info['ceo_database_print_text_rod']) ? '' : $translation['text137']) . '</span></span>
                                        </div>
                                    </div>
                                    <div class="dashboard_personal_files2_private_individuals_huilov_data_row">
                                        <div class="dashboard_personal_files2_private_individuals_huilov_label">' . $translation['text138'] . '</div>
                                        <div class="dashboard_personal_files2_private_individuals_huilov_input">
                                            <span class="dots_top"></span><span class="dots_bottom_left"></span><span class="dots_bottom_right"></span>
                                            <span class="private_individuals_huilov_text" data-bubble="4"><span>' . (empty($user_info['ceo_database_print_text_rod']) ? '' : $translation['text139']) . '</span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>';

    // при первом запуске
    if (empty($team_info['ceo_database_print_text_rod'])) {
        // обновляем значение, что текст напечатан. Повторно скрипт НЕ закускается
        $sql = "UPDATE `teams` SET `ceo_database_print_text_rod` = {?} WHERE `id` = {?}";
        $this->db->query($sql, [1, $team_id]);

        // обновляем подсказки
        // список открытых
        $active_hints = [];

        // список доступных
        $list_hints = [];

        $hints_by_step = $this->getHintsByStep('ceo_database_rod', $lang_id);
        if ($hints_by_step) {
            foreach ($hints_by_step as $hint) {
                $list_hints[] = $hint['id'];
            }
        }

        // сохраняем обновленный список подсказок
        $sql = "UPDATE `teams` SET `active_hints` = {?}, `list_hints` = {?}, `list_hints_title_lang_var` = {?}, `list_hints_text_lang_var` = {?} WHERE `id` = {?}";
        $this->db->query($sql, [json_encode($active_hints, JSON_UNESCAPED_UNICODE), json_encode($list_hints, JSON_UNESCAPED_UNICODE), 'text44', 'text45', $team_id]);
    }

    // обновляем, что текст напечатан для этого юзера
    if (isset($_COOKIE['hash'])) {
        $sql = "UPDATE `users` SET `ceo_database_print_text_rod` = {?} WHERE `team_id` = {?} AND `hash` = {?}";
        $this->db->query($sql, [1, $team_id, $_COOKIE['hash']]);
    } else {
        $sql = "UPDATE `users` SET `ceo_database_print_text_rod` = {?} WHERE `team_id` = {?} AND `ip` = {?}";
        $this->db->query($sql, [1, $team_id, $this->getIp()]);
    }

    // возвращаем также массив для отпечатки текста на разных языках
    $return['error_lang'] = [];

    $sql = "SELECT `id`, `lang_abbr` FROM `langs` WHERE `status` = {?}";
    $langs = $this->db->select($sql, [1]);
    if ($langs) {
        foreach ($langs as $lang_item) {
            $translation = $this->getWordsByPage('game', $lang_item['id']);

            $return['error_lang'][$lang_item['lang_abbr']] = [
                'text133' => $translation['text133'],
                'text134' => $translation['text134'],
                'text135' => $translation['text135'],
                'text137' => $translation['text137'],
                'text139' => $translation['text139']
            ];
        }
    }

    return $return;
}

// databases - загрузить Mobile Calls. Первый экран
private function uploadDatabasesMobileCalls($lang_id, $team_id)
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
                        <div class="dashboard_tab_title dashboard_tab_title_active" data-tab="mobile_calls1">
                            <div class="dashboard_tab_title_active_skew_right"></div>
                            <div class="dashboard_tab_title_inner">
                                <div class="dashboard_tab_title_img_wrapper">
                                    <svg width="22" height="25" viewBox="0 0 22 25" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4.04297 13.0766C3.95284 13.0766 3.8664 13.0408 3.80266 12.9771C3.73893 12.9133 3.70312 12.8269 3.70312 12.7368V2.41797C3.70312 1.90722 3.90602 1.41739 4.26717 1.05624C4.62833 0.695082 5.11816 0.492188 5.62891 0.492188H15.3711C15.8818 0.492188 16.3717 0.695082 16.7328 1.05624C17.094 1.41739 17.2969 1.90722 17.2969 2.41797V6.14945C17.2969 6.23959 17.2611 6.32603 17.1973 6.38976C17.1336 6.45349 17.0472 6.4893 16.957 6.4893C16.8669 6.4893 16.7805 6.45349 16.7167 6.38976C16.653 6.32603 16.6172 6.23959 16.6172 6.14945V2.41797C16.6172 2.08748 16.4859 1.77054 16.2522 1.53685C16.0185 1.30316 15.7016 1.17188 15.3711 1.17188H5.62891C5.29842 1.17188 4.98147 1.30316 4.74778 1.53685C4.5141 1.77054 4.38281 2.08748 4.38281 2.41797V12.7368C4.38281 12.8269 4.34701 12.9133 4.28327 12.9771C4.21954 13.0408 4.1331 13.0766 4.04297 13.0766Z" fill="#00F0FF"/><path d="M15.3711 24.5078H5.62891C5.11816 24.5078 4.62833 24.3049 4.26717 23.9437C3.90602 23.5826 3.70312 23.0927 3.70313 22.582V17.7211C3.70313 17.631 3.73893 17.5445 3.80266 17.4808C3.8664 17.4171 3.95284 17.3812 4.04297 17.3812C4.1331 17.3812 4.21954 17.4171 4.28327 17.4808C4.34701 17.5445 4.38281 17.631 4.38281 17.7211V22.582C4.38281 22.9125 4.5141 23.2294 4.74778 23.4631C4.98147 23.6968 5.29842 23.8281 5.62891 23.8281H15.3711C15.7016 23.8281 16.0185 23.6968 16.2522 23.4631C16.4859 23.2294 16.6172 22.9125 16.6172 22.582V14.0791C16.6172 13.989 16.653 13.9025 16.7167 13.8388C16.7805 13.7751 16.8669 13.7393 16.957 13.7393C17.0472 13.7393 17.1336 13.7751 17.1973 13.8388C17.2611 13.9025 17.2969 13.989 17.2969 14.0791V22.582C17.2969 23.0927 17.094 23.5826 16.7328 23.9437C16.3717 24.3049 15.8818 24.5078 15.3711 24.5078Z" fill="#00F0FF"/><path d="M12.5162 2.32851H8.48343C8.17602 2.32793 7.88124 2.20617 7.66302 1.98965C7.4448 1.77313 7.32075 1.4793 7.31777 1.17191C7.22763 1.1707 7.14167 1.13375 7.07879 1.06916C7.0159 1.00458 6.98125 0.917663 6.98245 0.82753C6.98365 0.737398 7.02061 0.651435 7.08519 0.588552C7.14978 0.525668 7.2367 0.491016 7.32683 0.492218H13.6706C13.7607 0.491016 13.8476 0.525668 13.9122 0.588552C13.9768 0.651435 14.0138 0.737398 14.015 0.82753C14.0162 0.917663 13.9815 1.00458 13.9186 1.06916C13.8557 1.13375 13.7698 1.1707 13.6796 1.17191C13.6767 1.47891 13.5529 1.7724 13.3352 1.98885C13.1175 2.20531 12.8233 2.32733 12.5162 2.32851ZM7.99745 1.17191C8.00012 1.29912 8.05245 1.42025 8.14327 1.50938C8.23409 1.5985 8.35618 1.64855 8.48343 1.64882H12.5162C12.6435 1.64855 12.7656 1.5985 12.8564 1.50938C12.9472 1.42025 12.9996 1.29912 13.0022 1.17191H7.99745Z" fill="#00F0FF"/><path d="M12.6079 22.8926H8.3916C8.30147 22.8926 8.21503 22.8568 8.1513 22.793C8.08756 22.7293 8.05176 22.6429 8.05176 22.5527C8.05176 22.4626 8.08756 22.3762 8.1513 22.3124C8.21503 22.2487 8.30147 22.2129 8.3916 22.2129H12.6079C12.6981 22.2129 12.7845 22.2487 12.8482 22.3124C12.912 22.3762 12.9478 22.4626 12.9478 22.5527C12.9478 22.6429 12.912 22.7293 12.8482 22.793C12.7845 22.8568 12.6981 22.8926 12.6079 22.8926Z" fill="#00F0FF"/><path d="M12.3177 16.492C12.2734 16.4917 12.2295 16.4828 12.1886 16.4659C12.1263 16.4404 12.0731 16.3969 12.0356 16.341C11.998 16.2852 11.978 16.2194 11.9779 16.1521V14.3929C11.4652 14.3112 10.9983 14.0498 10.6607 13.6555C10.323 13.2612 10.1366 12.7597 10.1348 12.2405V7.9925C10.1357 7.41383 10.3659 6.85911 10.7751 6.44993C11.1843 6.04074 11.739 5.81047 12.3177 5.80957H19.5065C20.0853 5.81017 20.6402 6.04035 21.0494 6.4496C21.4587 6.85885 21.6889 7.41373 21.6895 7.9925V12.236C21.6889 12.8148 21.4587 13.3697 21.0494 13.7789C20.6402 14.1882 20.0853 14.4183 19.5065 14.4189H14.5323L12.559 16.3923C12.5273 16.424 12.4897 16.4491 12.4483 16.4662C12.4069 16.4833 12.3625 16.4921 12.3177 16.492ZM12.3177 6.48926C11.9192 6.48986 11.5372 6.64843 11.2554 6.93021C10.9736 7.21199 10.8151 7.594 10.8145 7.9925V12.236C10.8151 12.6345 10.9736 13.0165 11.2554 13.2983C11.5372 13.5801 11.9192 13.7387 12.3177 13.7393C12.4078 13.7393 12.4943 13.7751 12.558 13.8388C12.6217 13.9025 12.6575 13.989 12.6575 14.0791V15.332L14.1506 13.8389C14.2143 13.7752 14.3006 13.7393 14.3907 13.7393H19.5065C19.9051 13.739 20.2873 13.5805 20.5691 13.2986C20.851 13.0168 21.0095 12.6346 21.0098 12.236V7.9925C21.0095 7.59391 20.851 7.21173 20.5691 6.92988C20.2873 6.64803 19.9051 6.48956 19.5065 6.48926H12.3177Z" fill="#00F0FF"/><path d="M7.17191 19.9075C7.08551 19.9072 7.00247 19.874 6.93969 19.8146L5.07848 18.061H1.97344C1.59648 18.0607 1.23504 17.9109 0.968489 17.6443C0.701938 17.3778 0.552058 17.0163 0.551758 16.6394V13.8187C0.552058 13.4417 0.701938 13.0803 0.968489 12.8137C1.23504 12.5472 1.59648 12.3973 1.97344 12.397H7.17191C7.54898 12.3973 7.91053 12.5471 8.17726 12.8136C8.44399 13.0802 8.59413 13.4416 8.59473 13.8187V16.6394C8.59397 16.9573 8.48699 17.2659 8.29077 17.5161C8.09456 17.7663 7.82037 17.9437 7.51176 18.0203V19.5677C7.51167 19.6339 7.49222 19.6987 7.45579 19.7541C7.41936 19.8094 7.36754 19.8529 7.30672 19.8792C7.2643 19.8981 7.21835 19.9077 7.17191 19.9075ZM1.97344 13.0767C1.77674 13.077 1.58819 13.1552 1.4491 13.2943C1.31002 13.4334 1.23174 13.622 1.23145 13.8187V16.6394C1.23174 16.8361 1.31002 17.0246 1.4491 17.1637C1.58819 17.3028 1.77674 17.381 1.97344 17.3813H5.21328C5.29772 17.3831 5.37849 17.4162 5.43984 17.4742L6.82641 18.7804V17.7212C6.82641 17.6311 6.86221 17.5446 6.92594 17.4809C6.98968 17.4172 7.07612 17.3813 7.16625 17.3813C7.36314 17.3813 7.55199 17.3032 7.69132 17.1641C7.83065 17.025 7.90907 16.8362 7.90937 16.6394V13.8187C7.90907 13.6218 7.83065 13.433 7.69132 13.2939C7.55199 13.1548 7.36314 13.0767 7.16625 13.0767H1.97344Z" fill="#00F0FF"/><path d="M6.04935 15.8347C5.83562 15.8347 5.62874 15.8003 5.43647 15.7368C5.37724 15.7179 5.30943 15.7317 5.26222 15.7789L4.88454 16.1574C4.39784 15.9102 4.0004 15.5128 3.75319 15.0269L4.13087 14.6475C4.17809 14.6003 4.19182 14.5325 4.17294 14.4733C4.10942 14.281 4.07508 14.0741 4.07508 13.8604C4.07507 13.7651 3.99868 13.6887 3.9034 13.6887H3.30253C3.20811 13.6887 3.13086 13.7651 3.13086 13.8604C3.13086 15.4724 4.43732 16.7789 6.04935 16.7789C6.14463 16.7789 6.22103 16.7025 6.22103 16.6072V16.0063C6.22103 15.9111 6.14463 15.8347 6.04935 15.8347Z" fill="#00F0FF"/><path d="M18.8047 8.47974H13.4023C13.3122 8.47974 13.2258 8.44393 13.162 8.3802C13.0983 8.31646 13.0625 8.23002 13.0625 8.13989C13.0625 8.04976 13.0983 7.96332 13.162 7.89959C13.2258 7.83585 13.3122 7.80005 13.4023 7.80005H18.8047C18.8949 7.80005 18.9813 7.83585 19.045 7.89959C19.1088 7.96332 19.1446 8.04976 19.1446 8.13989C19.1446 8.23002 19.1088 8.31646 19.045 8.3802C18.9813 8.44393 18.8949 8.47974 18.8047 8.47974Z" fill="#00F0FF"/><path d="M18.8039 10.4768H14.2285C14.1384 10.4768 14.0519 10.441 13.9882 10.3773C13.9245 10.3135 13.8887 10.2271 13.8887 10.137C13.8887 10.0468 13.9245 9.96039 13.9882 9.89666C14.0519 9.83292 14.1384 9.79712 14.2285 9.79712H18.8039C18.8941 9.79712 18.9805 9.83292 19.0443 9.89666C19.108 9.96039 19.1438 10.0468 19.1438 10.137C19.1438 10.2271 19.108 10.3135 19.0443 10.3773C18.9805 10.441 18.8941 10.4768 18.8039 10.4768Z" fill="#00F0FF"/><path d="M18.8047 12.4739H13.4023C13.3122 12.4739 13.2258 12.4381 13.162 12.3743C13.0983 12.3106 13.0625 12.2242 13.0625 12.134C13.0625 12.0439 13.0983 11.9575 13.162 11.8937C13.2258 11.83 13.3122 11.7942 13.4023 11.7942H18.8047C18.8949 11.7942 18.9813 11.83 19.045 11.8937C19.1088 11.9575 19.1446 12.0439 19.1446 12.134C19.1446 12.2242 19.1088 12.3106 19.045 12.3743C18.9813 12.4381 18.8949 12.4739 18.8047 12.4739Z" fill="#00F0FF"/></svg>

                                </div>
                                <div class="dashboard_tab_title_text">' . $translation['text59'] . '</div>
                            </div>
                        </div>';

    $return['back_btn'] = '<div class="dashboard_back_btn" data-back="databases_start_four" data-action-id-back="28" data-database="false">
                                <img src="/images/back_bg.png" class="back_btn_bg" alt="">
                                <div class="back_btn_text">' . $translation['text22'] . '</div>
                            </div>';

    $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_start_four" data-tab="tab1"></div>

                        <div class="dashboard_tab_content_item dashboard_tab_content_item_car_register dashboard_tab_content_item_active" data-tab="mobile_calls1">
                            <div class="dashboard_car_register1_inner">
                                <div class="dashboard_car_register1_inner_image_wrapper">
                                    <img src="/images/database_mobile_calls_icon.png" alt="">
                                </div>
                                <div class="dashboard_car_register1_inner_title" style="margin: 10px 0 0;">' . $translation['text59'] . '</div>
                                <div class="dashboard_car_register1_inner_text">' . $translation['text140'] . '</div>
                                <div class="dashboard_car_register1_fields_top">
                                    <div class="dashboard_car_register1_input_wrapper dashboard_car_register1_input_wrapper_license_plate">
                                        <div class="dashboard_car_register1_input_border_left"></div>';

    $sql = "
        SELECT c.code, cd.name, c.id
        FROM countries c
        JOIN countries_description cd ON c.id = cd.country_id
        WHERE cd.lang_id = {?}
        ORDER BY cd.name
    ";
    $countries = $this->db->select($sql, [$lang_id]);
    if ($countries) {
        $return['content'] .= '<select class="dashboard_mobile_calls1_country_code"><option disabled="disabled"' . (empty($team_info['mobile_calls_country_id']) ? ' selected="selected"' : '') . '>' . $translation['text141'] . '</option>';
        foreach ($countries as $country) {
            $return['content'] .= '<option value="' . $country['id'] . '"' . ($team_info['mobile_calls_country_id'] == $country['id'] ? ' selected="selected"' : '') . '>+' . $country['code'] . ' ' . $country['name'] . '</option>';
        }
        $return['content'] .= '</select>
                                <script>
                                    $(function() {
                                        // select country code
                                        var scrollbarPositionPixel = 0;
                                        var isScrollOpen = false;

                                        $(".dashboard_mobile_calls1_country_code").selectric({
                                            maxHeight: 236,
                                            onInit: function() {
                                                // стилизация полосы прокрутки
                                                $(".selectric-dashboard_mobile_calls1_country_code .selectric-scroll").mCustomScrollbar({
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
                                                                $(".dashboard_mobile_calls1_country_code").selectric("open");
                                                            }
                                                        }
                                                    }
                                                });
                                            },
                                            onOpen: function() {
                                                if (!isScrollOpen) {
                                                    $(".selectric-dashboard_mobile_calls1_country_code .selectric-scroll").mCustomScrollbar("scrollTo", Math.abs(scrollbarPositionPixel));
                                                    isScrollOpen = true;
                                                }
                                            }
                                        })
                                        .on("change", function() {
                                            // сохраняем выбор
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
                                                        // socket
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
                                                },
                                                error: function(xhr, ajaxOptions, thrownError) {    
                                                    console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                                }
                                            });

                                            isScrollOpen = false;
                                        });

                                        $(".dashboard_tabs[data-dashboard=\'databases\']").on("click", ".dashboard_car_register1_input_wrapper_license_plate .mCSB_scrollTools_vertical", function(e){
                                            if (isScrollOpen) {
                                                $(".dashboard_mobile_calls1_country_code").selectric("open");
                                            }
                                        });
                                    });
                                </script>';
    }

    $return['content'] .= '             
                                        <div class="dashboard_mobile_calls1_country_code_error error_text_database_car_register">' . $translation['text86'] . '</div>
                                    </div>
                                    <div class="dashboard_car_register1_input_wrapper dashboard_car_register1_input_wrapper_country">
                                        <div class="dashboard_car_register1_input_border_right"></div>
                                        <input type="text" placeholder="' . $translation['text142'] . '" autocomplete="off" class="dashboard_mobile_calls1_number" value="' . ((!empty($team_info['mobile_calls_number']) && !is_null($team_info['mobile_calls_number']) && $team_info['mobile_calls_number'] != 'NULL') ? htmlspecialchars($team_info['mobile_calls_number'], ENT_QUOTES) : '') . '">
                                        <div class="dashboard_mobile_calls1_number_error error_text_database_car_register">' . $translation['text86'] . '</div>
                                    </div>
                                </div>
                                <div class="btn_wrapper btn_wrapper_blue dashboard_mobile_calls1_search">
                                    <div class="btn btn_blue">
                                        <span>' . $translation['text66'] . '</span>
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

// databases - загрузить Mobile Calls. Второй экран. Успешно ввели номер
private function uploadDatabasesMobileCallsMessages($lang_id, $team_id)
{
    $translation = $this->getWordsByPage('game', $lang_id);

    $team_info = $this->teamInfo($team_id);

    if (isset($_COOKIE['hash'])) {
        $sql = "SELECT `mobile_calls_print_messages` FROM `users` WHERE `team_id` = {?} AND `hash` = {?} LIMIT 1";
        $user_info = $this->db->selectRow($sql, [$team_id, $_COOKIE['hash']]);
    } else {
        $sql = "SELECT `mobile_calls_print_messages` FROM `users` WHERE `team_id` = {?} AND `ip` = {?} LIMIT 1";
        $user_info = $this->db->selectRow($sql, [$team_id, $this->getIp()]);
    }

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
                        <div class="dashboard_tab_title" data-tab="mobile_calls1">
                            <div class="dashboard_tab_title_active_skew_right"></div>
                            <div class="dashboard_tab_title_inner">
                                <div class="dashboard_tab_title_img_wrapper">
                                    <svg width="22" height="25" viewBox="0 0 22 25" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4.04297 13.0766C3.95284 13.0766 3.8664 13.0408 3.80266 12.9771C3.73893 12.9133 3.70312 12.8269 3.70312 12.7368V2.41797C3.70312 1.90722 3.90602 1.41739 4.26717 1.05624C4.62833 0.695082 5.11816 0.492188 5.62891 0.492188H15.3711C15.8818 0.492188 16.3717 0.695082 16.7328 1.05624C17.094 1.41739 17.2969 1.90722 17.2969 2.41797V6.14945C17.2969 6.23959 17.2611 6.32603 17.1973 6.38976C17.1336 6.45349 17.0472 6.4893 16.957 6.4893C16.8669 6.4893 16.7805 6.45349 16.7167 6.38976C16.653 6.32603 16.6172 6.23959 16.6172 6.14945V2.41797C16.6172 2.08748 16.4859 1.77054 16.2522 1.53685C16.0185 1.30316 15.7016 1.17188 15.3711 1.17188H5.62891C5.29842 1.17188 4.98147 1.30316 4.74778 1.53685C4.5141 1.77054 4.38281 2.08748 4.38281 2.41797V12.7368C4.38281 12.8269 4.34701 12.9133 4.28327 12.9771C4.21954 13.0408 4.1331 13.0766 4.04297 13.0766Z" fill="#00F0FF"/><path d="M15.3711 24.5078H5.62891C5.11816 24.5078 4.62833 24.3049 4.26717 23.9437C3.90602 23.5826 3.70312 23.0927 3.70313 22.582V17.7211C3.70313 17.631 3.73893 17.5445 3.80266 17.4808C3.8664 17.4171 3.95284 17.3812 4.04297 17.3812C4.1331 17.3812 4.21954 17.4171 4.28327 17.4808C4.34701 17.5445 4.38281 17.631 4.38281 17.7211V22.582C4.38281 22.9125 4.5141 23.2294 4.74778 23.4631C4.98147 23.6968 5.29842 23.8281 5.62891 23.8281H15.3711C15.7016 23.8281 16.0185 23.6968 16.2522 23.4631C16.4859 23.2294 16.6172 22.9125 16.6172 22.582V14.0791C16.6172 13.989 16.653 13.9025 16.7167 13.8388C16.7805 13.7751 16.8669 13.7393 16.957 13.7393C17.0472 13.7393 17.1336 13.7751 17.1973 13.8388C17.2611 13.9025 17.2969 13.989 17.2969 14.0791V22.582C17.2969 23.0927 17.094 23.5826 16.7328 23.9437C16.3717 24.3049 15.8818 24.5078 15.3711 24.5078Z" fill="#00F0FF"/><path d="M12.5162 2.32851H8.48343C8.17602 2.32793 7.88124 2.20617 7.66302 1.98965C7.4448 1.77313 7.32075 1.4793 7.31777 1.17191C7.22763 1.1707 7.14167 1.13375 7.07879 1.06916C7.0159 1.00458 6.98125 0.917663 6.98245 0.82753C6.98365 0.737398 7.02061 0.651435 7.08519 0.588552C7.14978 0.525668 7.2367 0.491016 7.32683 0.492218H13.6706C13.7607 0.491016 13.8476 0.525668 13.9122 0.588552C13.9768 0.651435 14.0138 0.737398 14.015 0.82753C14.0162 0.917663 13.9815 1.00458 13.9186 1.06916C13.8557 1.13375 13.7698 1.1707 13.6796 1.17191C13.6767 1.47891 13.5529 1.7724 13.3352 1.98885C13.1175 2.20531 12.8233 2.32733 12.5162 2.32851ZM7.99745 1.17191C8.00012 1.29912 8.05245 1.42025 8.14327 1.50938C8.23409 1.5985 8.35618 1.64855 8.48343 1.64882H12.5162C12.6435 1.64855 12.7656 1.5985 12.8564 1.50938C12.9472 1.42025 12.9996 1.29912 13.0022 1.17191H7.99745Z" fill="#00F0FF"/><path d="M12.6079 22.8926H8.3916C8.30147 22.8926 8.21503 22.8568 8.1513 22.793C8.08756 22.7293 8.05176 22.6429 8.05176 22.5527C8.05176 22.4626 8.08756 22.3762 8.1513 22.3124C8.21503 22.2487 8.30147 22.2129 8.3916 22.2129H12.6079C12.6981 22.2129 12.7845 22.2487 12.8482 22.3124C12.912 22.3762 12.9478 22.4626 12.9478 22.5527C12.9478 22.6429 12.912 22.7293 12.8482 22.793C12.7845 22.8568 12.6981 22.8926 12.6079 22.8926Z" fill="#00F0FF"/><path d="M12.3177 16.492C12.2734 16.4917 12.2295 16.4828 12.1886 16.4659C12.1263 16.4404 12.0731 16.3969 12.0356 16.341C11.998 16.2852 11.978 16.2194 11.9779 16.1521V14.3929C11.4652 14.3112 10.9983 14.0498 10.6607 13.6555C10.323 13.2612 10.1366 12.7597 10.1348 12.2405V7.9925C10.1357 7.41383 10.3659 6.85911 10.7751 6.44993C11.1843 6.04074 11.739 5.81047 12.3177 5.80957H19.5065C20.0853 5.81017 20.6402 6.04035 21.0494 6.4496C21.4587 6.85885 21.6889 7.41373 21.6895 7.9925V12.236C21.6889 12.8148 21.4587 13.3697 21.0494 13.7789C20.6402 14.1882 20.0853 14.4183 19.5065 14.4189H14.5323L12.559 16.3923C12.5273 16.424 12.4897 16.4491 12.4483 16.4662C12.4069 16.4833 12.3625 16.4921 12.3177 16.492ZM12.3177 6.48926C11.9192 6.48986 11.5372 6.64843 11.2554 6.93021C10.9736 7.21199 10.8151 7.594 10.8145 7.9925V12.236C10.8151 12.6345 10.9736 13.0165 11.2554 13.2983C11.5372 13.5801 11.9192 13.7387 12.3177 13.7393C12.4078 13.7393 12.4943 13.7751 12.558 13.8388C12.6217 13.9025 12.6575 13.989 12.6575 14.0791V15.332L14.1506 13.8389C14.2143 13.7752 14.3006 13.7393 14.3907 13.7393H19.5065C19.9051 13.739 20.2873 13.5805 20.5691 13.2986C20.851 13.0168 21.0095 12.6346 21.0098 12.236V7.9925C21.0095 7.59391 20.851 7.21173 20.5691 6.92988C20.2873 6.64803 19.9051 6.48956 19.5065 6.48926H12.3177Z" fill="#00F0FF"/><path d="M7.17191 19.9075C7.08551 19.9072 7.00247 19.874 6.93969 19.8146L5.07848 18.061H1.97344C1.59648 18.0607 1.23504 17.9109 0.968489 17.6443C0.701938 17.3778 0.552058 17.0163 0.551758 16.6394V13.8187C0.552058 13.4417 0.701938 13.0803 0.968489 12.8137C1.23504 12.5472 1.59648 12.3973 1.97344 12.397H7.17191C7.54898 12.3973 7.91053 12.5471 8.17726 12.8136C8.44399 13.0802 8.59413 13.4416 8.59473 13.8187V16.6394C8.59397 16.9573 8.48699 17.2659 8.29077 17.5161C8.09456 17.7663 7.82037 17.9437 7.51176 18.0203V19.5677C7.51167 19.6339 7.49222 19.6987 7.45579 19.7541C7.41936 19.8094 7.36754 19.8529 7.30672 19.8792C7.2643 19.8981 7.21835 19.9077 7.17191 19.9075ZM1.97344 13.0767C1.77674 13.077 1.58819 13.1552 1.4491 13.2943C1.31002 13.4334 1.23174 13.622 1.23145 13.8187V16.6394C1.23174 16.8361 1.31002 17.0246 1.4491 17.1637C1.58819 17.3028 1.77674 17.381 1.97344 17.3813H5.21328C5.29772 17.3831 5.37849 17.4162 5.43984 17.4742L6.82641 18.7804V17.7212C6.82641 17.6311 6.86221 17.5446 6.92594 17.4809C6.98968 17.4172 7.07612 17.3813 7.16625 17.3813C7.36314 17.3813 7.55199 17.3032 7.69132 17.1641C7.83065 17.025 7.90907 16.8362 7.90937 16.6394V13.8187C7.90907 13.6218 7.83065 13.433 7.69132 13.2939C7.55199 13.1548 7.36314 13.0767 7.16625 13.0767H1.97344Z" fill="#00F0FF"/><path d="M6.04935 15.8347C5.83562 15.8347 5.62874 15.8003 5.43647 15.7368C5.37724 15.7179 5.30943 15.7317 5.26222 15.7789L4.88454 16.1574C4.39784 15.9102 4.0004 15.5128 3.75319 15.0269L4.13087 14.6475C4.17809 14.6003 4.19182 14.5325 4.17294 14.4733C4.10942 14.281 4.07508 14.0741 4.07508 13.8604C4.07507 13.7651 3.99868 13.6887 3.9034 13.6887H3.30253C3.20811 13.6887 3.13086 13.7651 3.13086 13.8604C3.13086 15.4724 4.43732 16.7789 6.04935 16.7789C6.14463 16.7789 6.22103 16.7025 6.22103 16.6072V16.0063C6.22103 15.9111 6.14463 15.8347 6.04935 15.8347Z" fill="#00F0FF"/><path d="M18.8047 8.47974H13.4023C13.3122 8.47974 13.2258 8.44393 13.162 8.3802C13.0983 8.31646 13.0625 8.23002 13.0625 8.13989C13.0625 8.04976 13.0983 7.96332 13.162 7.89959C13.2258 7.83585 13.3122 7.80005 13.4023 7.80005H18.8047C18.8949 7.80005 18.9813 7.83585 19.045 7.89959C19.1088 7.96332 19.1446 8.04976 19.1446 8.13989C19.1446 8.23002 19.1088 8.31646 19.045 8.3802C18.9813 8.44393 18.8949 8.47974 18.8047 8.47974Z" fill="#00F0FF"/><path d="M18.8039 10.4768H14.2285C14.1384 10.4768 14.0519 10.441 13.9882 10.3773C13.9245 10.3135 13.8887 10.2271 13.8887 10.137C13.8887 10.0468 13.9245 9.96039 13.9882 9.89666C14.0519 9.83292 14.1384 9.79712 14.2285 9.79712H18.8039C18.8941 9.79712 18.9805 9.83292 19.0443 9.89666C19.108 9.96039 19.1438 10.0468 19.1438 10.137C19.1438 10.2271 19.108 10.3135 19.0443 10.3773C18.9805 10.441 18.8941 10.4768 18.8039 10.4768Z" fill="#00F0FF"/><path d="M18.8047 12.4739H13.4023C13.3122 12.4739 13.2258 12.4381 13.162 12.3743C13.0983 12.3106 13.0625 12.2242 13.0625 12.134C13.0625 12.0439 13.0983 11.9575 13.162 11.8937C13.2258 11.83 13.3122 11.7942 13.4023 11.7942H18.8047C18.8949 11.7942 18.9813 11.83 19.045 11.8937C19.1088 11.9575 19.1446 12.0439 19.1446 12.134C19.1446 12.2242 19.1088 12.3106 19.045 12.3743C18.9813 12.4381 18.8949 12.4739 18.8047 12.4739Z" fill="#00F0FF"/></svg>
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

    $return['back_btn'] = '<div class="dashboard_back_btn" data-back="databases_start_four" data-action-id-back="28" data-database="false">
                                <img src="/images/back_bg.png" class="back_btn_bg" alt="">
                                <div class="back_btn_text">' . $translation['text22'] . '</div>
                            </div>';

    $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_start_four" data-tab="tab1"></div>

                        <div class="dashboard_tab_content_item dashboard_tab_content_item_car_register" data-tab="mobile_calls1"></div>

                        <div class="dashboard_tab_content_item dashboard_tab_content_item_car_register dashboard_tab_content_item_active" data-tab="mobile_calls2">
                            <div class="dashboard_mobile_calls2_inner' . (empty($user_info['mobile_calls_print_messages']) ? ' dashboard_mobile_calls2_inner_first' : '') . (empty($team_info['mobile_calls_print_messages']) ? ' dashboard_mobile_calls2_inner_first_team' : '') . '">
                                <div class="dashboard_mobile_calls2_top">
                                    <div class="dashboard_mobile_calls2_title">' . $translation['text145'] . '</div>
                                    <div class="dashboard_mobile_calls2_text">' . $translation['text146'] . '</div>
                                </div>
                                <div class="dashboard_mobile_calls2_messages">
                                    <div class="dashboard_mobile_calls2_message_item dashboard_mobile_calls2_message_item1">
                                        <div class="dashboard_mobile_calls2_message_inner">
                                            <div class="dashboard_mobile_calls2_message_inner_top"><img src="/images/icons/icon_mobile_calls2_face_from.png" alt=""><span>' . $translation['text160'] . '</span></div>
                                            <div class="dashboard_mobile_calls2_message_inner_bottom">
                                                <div class="dashboard_mobile_calls2_message_inner_bottom_time">' . $translation['text69'] . ' 30.08.22 16:46</div>
                                                <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_from"><span>' . $translation['text147'] . ' 🤙</span></div>
                                                <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_from dashboard_mobile_calls2_message_from_has_icon"><span>' . $translation['text148'] . ' 🤔</span></div>
                                                <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_to dashboard_mobile_calls2_message_to_has_icon"><span>' . $translation['text149'] . '☠️</span></div>
                                                <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_from dashboard_mobile_calls2_message_from_has_icon"><span>' . $translation['text150'] . ' 🤟</span></div>
                                            </div>
                                        </div>
                                        <div class="dashboard_mobile_calls2_message_item_border_right_bottom_bg">
                                            <svg width="83" height="10" viewBox="0 0 83 10" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M75.9846 9.49998L82.501 0L9.86363 1.3677e-05L0.000442505 9.49999L75.9846 9.49998Z" fill="#00F0FF"/></svg>
                                        </div>
                                    </div>

                                    <div class="dashboard_mobile_calls2_message_item dashboard_mobile_calls2_message_item2">
                                        <div class="dashboard_mobile_calls2_message_inner">
                                            <div class="dashboard_mobile_calls2_message_inner_top"><img src="/images/icons/icon_mobile_calls2_face_from.png" alt=""><span>' . $translation['text160'] . '</span></div>
                                            <div class="dashboard_mobile_calls2_message_inner_bottom">
                                                <div class="dashboard_mobile_calls2_message_inner_bottom_time">' . $translation['text69'] . ' 30.08.22 16:47</div>
                                                <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_from dashboard_mobile_calls2_message_from_has_icon"><span>' . $translation['text151'] . '</span></div>
                                                <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_to dashboard_mobile_calls2_message_to_has_icon"><span>' . $translation['text152'] . '</span></div>
                                                <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_from dashboard_mobile_calls2_message_from_has_icon"><span>' . $translation['text153'] . '</span></div>
                                                <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_to dashboard_mobile_calls2_message_to_has_icon"><span>' . $translation['text154'] . ' 👊</span></div>
                                            </div>
                                        </div>
                                        <div class="dashboard_mobile_calls2_message_item_border_right_bottom_bg">
                                            <svg width="83" height="10" viewBox="0 0 83 10" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M75.9846 9.49998L82.501 0L9.86363 1.3677e-05L0.000442505 9.49999L75.9846 9.49998Z" fill="#00F0FF"/></svg>
                                        </div>
                                    </div>

                                    <div class="dashboard_mobile_calls2_message_item dashboard_mobile_calls2_message_item3">
                                        <div class="dashboard_mobile_calls2_message_inner">
                                            <div class="dashboard_mobile_calls2_message_inner_top"><img src="/images/icons/icon_mobile_calls2_face_from.png" alt=""><span>' . $translation['text160'] . '</span></div>
                                            <div class="dashboard_mobile_calls2_message_inner_bottom">
                                                <div class="dashboard_mobile_calls2_message_inner_bottom_time">' . $translation['text69'] . ' 30.08.22 23:01</div>
                                                <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_from"><span>' . $translation['text155'] . ' 🙌</span></div>
                                                <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_from"><span>' . $translation['text156'] . '</span></div>
                                                <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_from dashboard_mobile_calls2_message_from_has_icon">' . $translation['text157'] . ' <span class="as_link">' . $translation['text158'] . '</span></span></div>
                                                <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_to dashboard_mobile_calls2_message_to_has_icon"><span>' . $translation['text159'] . ' ✊</span></div>
                                            </div>
                                        </div>
                                        <div class="dashboard_mobile_calls2_message_item_border_right_bottom_bg">
                                            <svg width="83" height="10" viewBox="0 0 83 10" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M75.9846 9.49998L82.501 0L9.86363 1.3677e-05L0.000442505 9.49999L75.9846 9.49998Z" fill="#00F0FF"/></svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>';

    $return['popup'] = '<div id="popup_mobile_calls_messages">
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
                                            <div class="dashboard_mobile_calls2_message_item dashboard_mobile_calls2_message_item1">
                                                <div class="dashboard_mobile_calls2_message_inner">
                                                    <div class="dashboard_mobile_calls2_message_inner_top"><img src="/images/icons/icon_mobile_calls2_face_from_big.png" alt=""><span>' . $translation['text160'] . '</span></div>
                                                    <div class="dashboard_mobile_calls2_message_inner_bottom">
                                                        <div class="dashboard_mobile_calls2_message_inner_bottom_time">' . $translation['text69'] . ' 30.08.22 16:46</div>
                                                        <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_from"><span>' . $translation['text147'] . ' 🤙</span></div>
                                                        <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_from dashboard_mobile_calls2_message_from_has_icon"><span>' . $translation['text148'] . ' 🤔</span></div>
                                                        <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_to dashboard_mobile_calls2_message_to_has_icon"><span>' . $translation['text149'] . '☠️</span></div>
                                                        <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_from dashboard_mobile_calls2_message_from_has_icon"><span>' . $translation['text150'] . ' 🤟</span></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="dashboard_mobile_calls2_message_item dashboard_mobile_calls2_message_item2">
                                                <div class="dashboard_mobile_calls2_message_inner">
                                                    <div class="dashboard_mobile_calls2_message_inner_top"><img src="/images/icons/icon_mobile_calls2_face_from_big.png" alt=""><span>' . $translation['text160'] . '</span></div>
                                                    <div class="dashboard_mobile_calls2_message_inner_bottom">
                                                        <div class="dashboard_mobile_calls2_message_inner_bottom_time">' . $translation['text69'] . ' 30.08.22 16:47</div>
                                                        <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_from dashboard_mobile_calls2_message_from_has_icon"><span>' . $translation['text151'] . '</span></div>
                                                        <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_to dashboard_mobile_calls2_message_to_has_icon"><span>' . $translation['text152'] . '</span></div>
                                                        <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_from dashboard_mobile_calls2_message_from_has_icon"><span>' . $translation['text153'] . '</span></div>
                                                        <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_to dashboard_mobile_calls2_message_to_has_icon"><span>' . $translation['text154'] . ' 👊</span></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="dashboard_mobile_calls2_message_item dashboard_mobile_calls2_message_item3">
                                                <div class="dashboard_mobile_calls2_message_inner">
                                                    <div class="dashboard_mobile_calls2_message_inner_top"><img src="/images/icons/icon_mobile_calls2_face_from_big.png" alt=""><span>' . $translation['text160'] . '</span></div>
                                                    <div class="dashboard_mobile_calls2_message_inner_bottom">
                                                        <div class="dashboard_mobile_calls2_message_inner_bottom_time">' . $translation['text69'] . ' 30.08.22 23:01</div>
                                                        <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_from"><span>' . $translation['text155'] . ' 🙌</span></div>
                                                        <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_from"><span>' . $translation['text156'] . '</span></div>
                                                        <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_from dashboard_mobile_calls2_message_from_has_icon">' . $translation['text157'] . ' <span class="as_link">' . $translation['text158'] . '</span></span></div>
                                                        <div class="dashboard_mobile_calls2_message dashboard_mobile_calls2_message_to dashboard_mobile_calls2_message_to_has_icon"><span>' . $translation['text159'] . ' ✊</span></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>';

    // при первом запуске
    if (empty($team_info['mobile_calls_print_messages'])) {
        // обновляем значение, что блок выведен. Повторно скрипт НЕ закускается
        $sql = "UPDATE `teams` SET `mobile_calls_print_messages` = {?} WHERE `id` = {?}";
        $this->db->query($sql, [1, $team_id]);

        // обновляем подсказки
        // список открытых
        $active_hints = [];

        // список доступных
        $list_hints = [];

        $hints_by_step = $this->getHintsByStep('mobile_calls', $lang_id);
        if ($hints_by_step) {
            foreach ($hints_by_step as $hint) {
                $list_hints[] = $hint['id'];
            }
        }

        // сохраняем обновленный список подсказок
        $sql = "UPDATE `teams` SET `active_hints` = {?}, `list_hints` = {?}, `list_hints_title_lang_var` = {?}, `list_hints_text_lang_var` = {?} WHERE `id` = {?}";
        $this->db->query($sql, [json_encode($active_hints, JSON_UNESCAPED_UNICODE), json_encode($list_hints, JSON_UNESCAPED_UNICODE), 'text44', 'text45', $team_id]);
    }

    // обновляем, что текст напечатан для этого юзера
    if (isset($_COOKIE['hash'])) {
        $sql = "UPDATE `users` SET `mobile_calls_print_messages` = {?} WHERE `team_id` = {?} AND `hash` = {?}";
        $this->db->query($sql, [1, $team_id, $_COOKIE['hash']]);
    } else {
        $sql = "UPDATE `users` SET `mobile_calls_print_messages` = {?} WHERE `team_id` = {?} AND `ip` = {?}";
        $this->db->query($sql, [1, $team_id, $this->getIp()]);
    }

    return $return;
}

// databases - загрузить Bank Transactions. Первый экран
private function uploadDatabasesBankTransactions($lang_id, $team_id)
{
    $translation = $this->getWordsByPage('game', $lang_id);

    $team_info = $this->teamInfo($team_id);

    // если базы еще нет в списке доступных, то выводим, что еще недоступно
    $list_databases = json_decode($team_info['list_databases'], true);
    if (!in_array('bank_transactions', $list_databases)) {
        return $this->uploadDatabasesNoAccess($lang_id, 'text61', true);
    }

    // в противном случае возвращаем форму для ввода данных
    $return = [];

    $return['titles'] = '<div class="dashboard_tab_title dashboard_tab_title_can_click" data-tab="tab1" data-step="databases_start_four" data-database="false">
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
                                    <svg width="21" height="25" viewBox="0 0 21 25" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.9362 10.4167H7.89453C7.7564 10.4167 7.62392 10.3619 7.52625 10.2642C7.42857 10.1665 7.3737 10.034 7.3737 9.89591C7.3737 9.75778 7.31882 9.6253 7.22115 9.52763C7.12347 9.42995 6.991 9.37508 6.85286 9.37508C6.71473 9.37508 6.58225 9.42995 6.48458 9.52763C6.3869 9.6253 6.33203 9.75778 6.33203 9.89591C6.33203 10.3103 6.49665 10.7077 6.78968 11.0008C7.0827 11.2938 7.48013 11.4584 7.89453 11.4584V11.9792C7.89453 12.1174 7.9494 12.2499 8.04708 12.3475C8.14475 12.4452 8.27723 12.5001 8.41536 12.5001C8.5535 12.5001 8.68597 12.4452 8.78365 12.3475C8.88132 12.2499 8.9362 12.1174 8.9362 11.9792V11.4584C9.3506 11.4584 9.74803 11.2938 10.0411 11.0008C10.3341 10.7077 10.4987 10.3103 10.4987 9.89591V9.37508C10.4987 8.96068 10.3341 8.56325 10.0411 8.27022C9.74803 7.9772 9.3506 7.81258 8.9362 7.81258H7.89453C7.7564 7.81258 7.62392 7.75771 7.52625 7.66003C7.42857 7.56236 7.3737 7.42988 7.3737 7.29175V6.77091C7.3737 6.63278 7.42857 6.5003 7.52625 6.40263C7.62392 6.30495 7.7564 6.25008 7.89453 6.25008H8.9362C9.07433 6.25008 9.20681 6.30495 9.30448 6.40263C9.40216 6.5003 9.45703 6.63278 9.45703 6.77091C9.45703 6.90905 9.5119 7.04152 9.60958 7.1392C9.70725 7.23687 9.83973 7.29175 9.97786 7.29175C10.116 7.29175 10.2485 7.23687 10.3461 7.1392C10.4438 7.04152 10.4987 6.90905 10.4987 6.77091C10.4987 6.35651 10.3341 5.95908 10.0411 5.66606C9.74803 5.37303 9.3506 5.20841 8.9362 5.20841V4.68758C8.9362 4.54945 8.88132 4.41697 8.78365 4.3193C8.68597 4.22162 8.5535 4.16675 8.41536 4.16675C8.27723 4.16675 8.14475 4.22162 8.04708 4.3193C7.9494 4.41697 7.89453 4.54945 7.89453 4.68758V5.20841C7.48013 5.20841 7.0827 5.37303 6.78968 5.66606C6.49665 5.95908 6.33203 6.35651 6.33203 6.77091V7.29175C6.33203 7.70615 6.49665 8.10357 6.78968 8.3966C7.0827 8.68963 7.48013 8.85425 7.89453 8.85425H8.9362C9.07433 8.85425 9.20681 8.90912 9.30448 9.00679C9.40216 9.10447 9.45703 9.23694 9.45703 9.37508V9.89591C9.45703 10.034 9.40216 10.1665 9.30448 10.2642C9.20681 10.3619 9.07433 10.4167 8.9362 10.4167Z" fill="#00F0FF"/><path d="M13.1029 16.6667H3.72786C3.58973 16.6667 3.45726 16.7216 3.35958 16.8193C3.2619 16.917 3.20703 17.0494 3.20703 17.1876C3.20703 17.3257 3.2619 17.4582 3.35958 17.5559C3.45726 17.6535 3.58973 17.7084 3.72786 17.7084H13.1029C13.241 17.7084 13.3735 17.6535 13.4712 17.5559C13.5688 17.4582 13.6237 17.3257 13.6237 17.1876C13.6237 17.0494 13.5688 16.917 13.4712 16.8193C13.3735 16.7216 13.241 16.6667 13.1029 16.6667V16.6667Z" fill="#00F0FF"/><path d="M13.1029 19.2708H3.72786C3.58973 19.2708 3.45726 19.3256 3.35958 19.4233C3.2619 19.521 3.20703 19.6535 3.20703 19.7916C3.20703 19.9297 3.2619 20.0622 3.35958 20.1599C3.45726 20.2575 3.58973 20.3124 3.72786 20.3124H13.1029C13.241 20.3124 13.3735 20.2575 13.4712 20.1599C13.5688 20.0622 13.6237 19.9297 13.6237 19.7916C13.6237 19.6535 13.5688 19.521 13.4712 19.4233C13.3735 19.3256 13.241 19.2708 13.1029 19.2708Z" fill="#00F0FF"/><path d="M13.1029 14.0625H3.72786C3.58973 14.0625 3.45726 14.1174 3.35958 14.215C3.2619 14.3127 3.20703 14.4452 3.20703 14.5833C3.20703 14.7215 3.2619 14.8539 3.35958 14.9516C3.45726 15.0493 3.58973 15.1042 3.72786 15.1042H13.1029C13.241 15.1042 13.3735 15.0493 13.4712 14.9516C13.5688 14.8539 13.6237 14.7215 13.6237 14.5833C13.6237 14.4452 13.5688 14.3127 13.4712 14.215C13.3735 14.1174 13.241 14.0625 13.1029 14.0625Z" fill="#00F0FF"/><path d="M18.311 5.20881e-06H2.68598C1.99531 5.20881e-06 1.33293 0.274372 0.844558 0.762748C0.356182 1.25112 0.0818155 1.9135 0.0818155 2.60417C0.0818155 26.4115 -0.0223511 24.6875 0.310982 24.9115C0.644315 25.1354 0.602649 25.0365 3.20682 24C5.83702 25.0417 5.74327 25.0677 6.00369 24.9635L8.41515 24L10.8266 24.9635C11.0922 25.0677 10.9985 25.0469 13.6287 24C16.2329 25.0417 16.087 25 16.2329 25C16.371 25 16.5035 24.9451 16.6011 24.8475C16.6988 24.7498 16.7537 24.6173 16.7537 24.4792V5.20834H20.3995C20.5377 5.20834 20.6701 5.15347 20.7678 5.05579C20.8655 4.95811 20.9204 4.82564 20.9204 4.68751V2.60417C20.9204 2.26175 20.8528 1.92269 20.7216 1.6064C20.5904 1.2901 20.3981 1.00279 20.1558 0.760904C19.9134 0.519017 19.6257 0.327304 19.3092 0.196739C18.9926 0.0661738 18.6534 -0.000679637 18.311 5.20881e-06V5.20881e-06ZM15.7068 23.7083C13.7016 22.9063 13.6912 22.849 13.4308 22.9531L11.0193 23.9167C8.49848 22.9115 8.49327 22.8438 8.22244 22.9531L5.81098 23.9167L3.39952 22.9531C3.13911 22.849 3.18077 22.8854 1.12348 23.7083V2.60417C1.12348 2.18977 1.2881 1.79234 1.58113 1.49932C1.87415 1.20629 2.27158 1.04167 2.68598 1.04167C17.2693 1.04167 16.2589 1.00521 16.1808 1.11459C15.5297 2.04688 15.7068 0.916674 15.7068 23.7083ZM19.8735 4.16667H16.7485V2.60417C16.7485 2.18977 16.9131 1.79234 17.2061 1.49932C17.4992 1.20629 17.8966 1.04167 18.311 1.04167C18.7254 1.04167 19.1228 1.20629 19.4158 1.49932C19.7089 1.79234 19.8735 2.18977 19.8735 2.60417V4.16667Z" fill="#00F0FF"/></svg>
                                </div>
                                <div class="dashboard_tab_title_text">' . $translation['text60'] . '</div>
                            </div>
                        </div>';

    $return['back_btn'] = '<div class="dashboard_back_btn" data-back="databases_start_four" data-database="false">
                                <img src="/images/back_bg.png" class="back_btn_bg" alt="">
                                <div class="back_btn_text">' . $translation['text22'] . '</div>
                            </div>';

    $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_start_four" data-tab="tab1"></div>

                        <div class="dashboard_tab_content_item dashboard_tab_content_item_bank_transactions dashboard_tab_content_item_active" data-tab="bank_transactions1">
                            <div class="dashboard_bank_transactions1_inner">
                                <div class="dashboard_bank_transactions1_inner_image_wrapper">
                                    <svg width="58" height="68" viewBox="0 0 58 68" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M24.7493 28.3332H21.916C21.5403 28.3332 21.18 28.184 20.9143 27.9183C20.6486 27.6526 20.4993 27.2923 20.4993 26.9166C20.4993 26.5409 20.3501 26.1805 20.0844 25.9148C19.8187 25.6492 19.4584 25.4999 19.0827 25.4999C18.707 25.4999 18.3466 25.6492 18.0809 25.9148C17.8153 26.1805 17.666 26.5409 17.666 26.9166C17.666 28.0437 18.1138 29.1247 18.9108 29.9218C19.7078 30.7188 20.7888 31.1666 21.916 31.1666V32.5832C21.916 32.959 22.0653 33.3193 22.3309 33.585C22.5966 33.8507 22.957 33.9999 23.3327 33.9999C23.7084 33.9999 24.0687 33.8507 24.3344 33.585C24.6001 33.3193 24.7493 32.959 24.7493 32.5832V31.1666C25.8765 31.1666 26.9575 30.7188 27.7545 29.9218C28.5516 29.1247 28.9993 28.0437 28.9993 26.9166V25.4999C28.9993 24.3727 28.5516 23.2917 27.7545 22.4947C26.9575 21.6977 25.8765 21.2499 24.7493 21.2499H21.916C21.5403 21.2499 21.18 21.1007 20.9143 20.835C20.6486 20.5693 20.4993 20.209 20.4993 19.8332V18.4166C20.4993 18.0409 20.6486 17.6805 20.9143 17.4148C21.18 17.1492 21.5403 16.9999 21.916 16.9999H24.7493C25.1251 16.9999 25.4854 17.1492 25.7511 17.4148C26.0168 17.6805 26.166 18.0409 26.166 18.4166C26.166 18.7923 26.3153 19.1526 26.5809 19.4183C26.8466 19.684 27.207 19.8332 27.5827 19.8332C27.9584 19.8332 28.3187 19.684 28.5844 19.4183C28.8501 19.1526 28.9993 18.7923 28.9993 18.4166C28.9993 17.2894 28.5516 16.2084 27.7545 15.4114C26.9575 14.6143 25.8765 14.1666 24.7493 14.1666V12.7499C24.7493 12.3742 24.6001 12.0139 24.3344 11.7482C24.0687 11.4825 23.7084 11.3333 23.3327 11.3333C22.957 11.3333 22.5966 11.4825 22.3309 11.7482C22.0653 12.0139 21.916 12.3742 21.916 12.7499V14.1666C20.7888 14.1666 19.7078 14.6143 18.9108 15.4114C18.1138 16.2084 17.666 17.2894 17.666 18.4166V19.8332C17.666 20.9604 18.1138 22.0414 18.9108 22.8384C19.7078 23.6355 20.7888 24.0832 21.916 24.0832H24.7493C25.1251 24.0832 25.4854 24.2325 25.7511 24.4982C26.0168 24.7639 26.166 25.1242 26.166 25.4999V26.9166C26.166 27.2923 26.0168 27.6526 25.7511 27.9183C25.4854 28.184 25.1251 28.3332 24.7493 28.3332Z" fill="#00F0FF"/><path d="M36.0827 45.3333H10.5827C10.207 45.3333 9.84662 45.4825 9.58095 45.7482C9.31527 46.0139 9.16602 46.3742 9.16602 46.7499C9.16602 47.1256 9.31527 47.486 9.58095 47.7516C9.84662 48.0173 10.207 48.1666 10.5827 48.1666H36.0827C36.4584 48.1666 36.8187 48.0173 37.0844 47.7516C37.3501 47.486 37.4994 47.1256 37.4994 46.7499C37.4994 46.3742 37.3501 46.0139 37.0844 45.7482C36.8187 45.4825 36.4584 45.3333 36.0827 45.3333V45.3333Z" fill="#00F0FF"/><path d="M36.0827 52.4167H10.5827C10.207 52.4167 9.84662 52.566 9.58095 52.8317C9.31527 53.0974 9.16602 53.4577 9.16602 53.8334C9.16602 54.2091 9.31527 54.5695 9.58095 54.8351C9.84662 55.1008 10.207 55.2501 10.5827 55.2501H36.0827C36.4584 55.2501 36.8187 55.1008 37.0844 54.8351C37.3501 54.5695 37.4994 54.2091 37.4994 53.8334C37.4994 53.4577 37.3501 53.0974 37.0844 52.8317C36.8187 52.566 36.4584 52.4167 36.0827 52.4167Z" fill="#00F0FF"/><path d="M36.0827 38.25H10.5827C10.207 38.25 9.84662 38.3993 9.58095 38.6649C9.31527 38.9306 9.16602 39.2909 9.16602 39.6667C9.16602 40.0424 9.31527 40.4027 9.58095 40.6684C9.84662 40.9341 10.207 41.0833 10.5827 41.0833H36.0827C36.4584 41.0833 36.8187 40.9341 37.0844 40.6684C37.3501 40.4027 37.4994 40.0424 37.4994 39.6667C37.4994 39.2909 37.3501 38.9306 37.0844 38.6649C36.8187 38.3993 36.4584 38.25 36.0827 38.25Z" fill="#00F0FF"/><path d="M50.2502 1.4168e-05H7.75025C5.87163 1.4168e-05 4.06996 0.746292 2.74157 2.07467C1.41319 3.40306 0.666913 5.20473 0.666913 7.08335C0.666913 71.8392 0.38358 67.15 1.29025 67.7592C2.19691 68.3684 2.08358 68.0992 9.16691 65.28C16.3211 68.1134 16.0661 68.1842 16.7744 67.9009L23.3336 65.28L29.8927 67.9009C30.6152 68.1842 30.3602 68.1275 37.5144 65.28C44.5977 68.1134 44.2011 68 44.5977 68C44.9735 68 45.3338 67.8508 45.5995 67.5851C45.8652 67.3194 46.0144 66.9591 46.0144 66.5834V14.1667H55.9311C56.3068 14.1667 56.6671 14.0174 56.9328 13.7517C57.1985 13.4861 57.3477 13.1257 57.3477 12.75V7.08335C57.3477 6.15196 57.1641 5.22971 56.8072 4.3694C56.4503 3.50908 55.9273 2.72759 55.2681 2.06966C54.6088 1.41173 53.8263 0.890266 52.9653 0.535129C52.1042 0.179993 51.1816 -0.00184861 50.2502 1.4168e-05V1.4168e-05ZM43.1669 64.4867C37.7127 62.305 37.6844 62.1492 36.9761 62.4325L30.4169 65.0534C23.5602 62.3192 23.5461 62.135 22.8094 62.4325L16.2502 65.0534L9.69108 62.4325C8.98275 62.1492 9.09608 62.2484 3.50025 64.4867V7.08335C3.50025 5.95618 3.94801 4.87517 4.74504 4.07814C5.54207 3.28111 6.62308 2.83335 7.75025 2.83335C47.4169 2.83335 44.6686 2.73418 44.4561 3.03168C42.6852 5.56751 43.1669 2.49335 43.1669 64.4867ZM54.5002 11.3333H46.0002V7.08335C46.0002 5.95618 46.448 4.87517 47.245 4.07814C48.0421 3.28111 49.1231 2.83335 50.2502 2.83335C51.3774 2.83335 52.4584 3.28111 53.2554 4.07814C54.0525 4.87517 54.5002 5.95618 54.5002 7.08335V11.3333Z" fill="#00F0FF"/></svg>
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
                                        <input type="text" placeholder="' . $translation['text213'] . '" autocomplete="off" class="dashboard_bank_transactions1_date" value="' . ((!empty($team_info['bank_transactions_date']) && $team_info['bank_transactions_date'] != '0000-00-00' && !is_null($team_info['bank_transactions_date'])) ? $this->fromEngDatetimeToRus($team_info['bank_transactions_date']) : '') . '">
                                        <div class="dashboard_bank_transactions1_date_error error_text_database_car_register">' . $translation['text86'] . '</div>
                                    </div>
                                    <script>
                                        $(function() {
                                            // datepicker
                                            $(".dashboard_bank_transactions1_date").datepicker({
                                                dateFormat: "dd.mm.yy",
                                                dayNamesShort: ["' . $translation['text67'] . '", "' . $translation['text68'] . '", "' . $translation['text69'] . '", "' . $translation['text70'] . '", "' . $translation['text71'] . '", "' . $translation['text72'] . '", "' . $translation['text73'] . '"],
                                                dayNamesMin: ["' . $translation['text67'] . '", "' . $translation['text68'] . '", "' . $translation['text69'] . '", "' . $translation['text70'] . '", "' . $translation['text71'] . '", "' . $translation['text72'] . '", "' . $translation['text73'] . '"],
                                                monthNames: ["' . $translation['text74'] . '", "' . $translation['text75'] . '", "' . $translation['text76'] . '", "' . $translation['text77'] . '", "' . $translation['text78'] . '", "' . $translation['text79'] . '", "' . $translation['text80'] . '", "' . $translation['text81'] . '", "' . $translation['text82'] . '", "' . $translation['text83'] . '", "' . $translation['text84'] . '", "' . $translation['text85'] . '"],
                                                changeMonth: false,
                                                //showAnim: "clip",
                                                showAnim: "",
                                                onSelect: function(dateText) {
                                                    // сохраняем выбор
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
                                                            // socket
                                                            var message = {
                                                                "op": "databasesBankTransactionsUpdateDate",
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
                                    </script>
                                </div>
                                <div class="btn_wrapper btn_wrapper_blue dashboard_bank_transactions1_search">
                                    <div class="btn btn_blue">
                                        <span>' . $translation['text66'] . '</span>
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

// databases - загрузить Bank Transactions. Результаты
private function uploadDatabasesBankTransactionsSuccess($lang_id, $team_id)
{
    $translation = $this->getWordsByPage('game', $lang_id);

    $team_info = $this->teamInfo($team_id);

    $return = [];

    $return['titles'] = '<div class="dashboard_tab_title dashboard_tab_title_can_click" data-tab="tab1" data-step="databases_start_four" data-database="false">
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
                                    <svg width="21" height="25" viewBox="0 0 21 25" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.9362 10.4167H7.89453C7.7564 10.4167 7.62392 10.3619 7.52625 10.2642C7.42857 10.1665 7.3737 10.034 7.3737 9.89591C7.3737 9.75778 7.31882 9.6253 7.22115 9.52763C7.12347 9.42995 6.991 9.37508 6.85286 9.37508C6.71473 9.37508 6.58225 9.42995 6.48458 9.52763C6.3869 9.6253 6.33203 9.75778 6.33203 9.89591C6.33203 10.3103 6.49665 10.7077 6.78968 11.0008C7.0827 11.2938 7.48013 11.4584 7.89453 11.4584V11.9792C7.89453 12.1174 7.9494 12.2499 8.04708 12.3475C8.14475 12.4452 8.27723 12.5001 8.41536 12.5001C8.5535 12.5001 8.68597 12.4452 8.78365 12.3475C8.88132 12.2499 8.9362 12.1174 8.9362 11.9792V11.4584C9.3506 11.4584 9.74803 11.2938 10.0411 11.0008C10.3341 10.7077 10.4987 10.3103 10.4987 9.89591V9.37508C10.4987 8.96068 10.3341 8.56325 10.0411 8.27022C9.74803 7.9772 9.3506 7.81258 8.9362 7.81258H7.89453C7.7564 7.81258 7.62392 7.75771 7.52625 7.66003C7.42857 7.56236 7.3737 7.42988 7.3737 7.29175V6.77091C7.3737 6.63278 7.42857 6.5003 7.52625 6.40263C7.62392 6.30495 7.7564 6.25008 7.89453 6.25008H8.9362C9.07433 6.25008 9.20681 6.30495 9.30448 6.40263C9.40216 6.5003 9.45703 6.63278 9.45703 6.77091C9.45703 6.90905 9.5119 7.04152 9.60958 7.1392C9.70725 7.23687 9.83973 7.29175 9.97786 7.29175C10.116 7.29175 10.2485 7.23687 10.3461 7.1392C10.4438 7.04152 10.4987 6.90905 10.4987 6.77091C10.4987 6.35651 10.3341 5.95908 10.0411 5.66606C9.74803 5.37303 9.3506 5.20841 8.9362 5.20841V4.68758C8.9362 4.54945 8.88132 4.41697 8.78365 4.3193C8.68597 4.22162 8.5535 4.16675 8.41536 4.16675C8.27723 4.16675 8.14475 4.22162 8.04708 4.3193C7.9494 4.41697 7.89453 4.54945 7.89453 4.68758V5.20841C7.48013 5.20841 7.0827 5.37303 6.78968 5.66606C6.49665 5.95908 6.33203 6.35651 6.33203 6.77091V7.29175C6.33203 7.70615 6.49665 8.10357 6.78968 8.3966C7.0827 8.68963 7.48013 8.85425 7.89453 8.85425H8.9362C9.07433 8.85425 9.20681 8.90912 9.30448 9.00679C9.40216 9.10447 9.45703 9.23694 9.45703 9.37508V9.89591C9.45703 10.034 9.40216 10.1665 9.30448 10.2642C9.20681 10.3619 9.07433 10.4167 8.9362 10.4167Z" fill="#00F0FF"/><path d="M13.1029 16.6667H3.72786C3.58973 16.6667 3.45726 16.7216 3.35958 16.8193C3.2619 16.917 3.20703 17.0494 3.20703 17.1876C3.20703 17.3257 3.2619 17.4582 3.35958 17.5559C3.45726 17.6535 3.58973 17.7084 3.72786 17.7084H13.1029C13.241 17.7084 13.3735 17.6535 13.4712 17.5559C13.5688 17.4582 13.6237 17.3257 13.6237 17.1876C13.6237 17.0494 13.5688 16.917 13.4712 16.8193C13.3735 16.7216 13.241 16.6667 13.1029 16.6667V16.6667Z" fill="#00F0FF"/><path d="M13.1029 19.2708H3.72786C3.58973 19.2708 3.45726 19.3256 3.35958 19.4233C3.2619 19.521 3.20703 19.6535 3.20703 19.7916C3.20703 19.9297 3.2619 20.0622 3.35958 20.1599C3.45726 20.2575 3.58973 20.3124 3.72786 20.3124H13.1029C13.241 20.3124 13.3735 20.2575 13.4712 20.1599C13.5688 20.0622 13.6237 19.9297 13.6237 19.7916C13.6237 19.6535 13.5688 19.521 13.4712 19.4233C13.3735 19.3256 13.241 19.2708 13.1029 19.2708Z" fill="#00F0FF"/><path d="M13.1029 14.0625H3.72786C3.58973 14.0625 3.45726 14.1174 3.35958 14.215C3.2619 14.3127 3.20703 14.4452 3.20703 14.5833C3.20703 14.7215 3.2619 14.8539 3.35958 14.9516C3.45726 15.0493 3.58973 15.1042 3.72786 15.1042H13.1029C13.241 15.1042 13.3735 15.0493 13.4712 14.9516C13.5688 14.8539 13.6237 14.7215 13.6237 14.5833C13.6237 14.4452 13.5688 14.3127 13.4712 14.215C13.3735 14.1174 13.241 14.0625 13.1029 14.0625Z" fill="#00F0FF"/><path d="M18.311 5.20881e-06H2.68598C1.99531 5.20881e-06 1.33293 0.274372 0.844558 0.762748C0.356182 1.25112 0.0818155 1.9135 0.0818155 2.60417C0.0818155 26.4115 -0.0223511 24.6875 0.310982 24.9115C0.644315 25.1354 0.602649 25.0365 3.20682 24C5.83702 25.0417 5.74327 25.0677 6.00369 24.9635L8.41515 24L10.8266 24.9635C11.0922 25.0677 10.9985 25.0469 13.6287 24C16.2329 25.0417 16.087 25 16.2329 25C16.371 25 16.5035 24.9451 16.6011 24.8475C16.6988 24.7498 16.7537 24.6173 16.7537 24.4792V5.20834H20.3995C20.5377 5.20834 20.6701 5.15347 20.7678 5.05579C20.8655 4.95811 20.9204 4.82564 20.9204 4.68751V2.60417C20.9204 2.26175 20.8528 1.92269 20.7216 1.6064C20.5904 1.2901 20.3981 1.00279 20.1558 0.760904C19.9134 0.519017 19.6257 0.327304 19.3092 0.196739C18.9926 0.0661738 18.6534 -0.000679637 18.311 5.20881e-06V5.20881e-06ZM15.7068 23.7083C13.7016 22.9063 13.6912 22.849 13.4308 22.9531L11.0193 23.9167C8.49848 22.9115 8.49327 22.8438 8.22244 22.9531L5.81098 23.9167L3.39952 22.9531C3.13911 22.849 3.18077 22.8854 1.12348 23.7083V2.60417C1.12348 2.18977 1.2881 1.79234 1.58113 1.49932C1.87415 1.20629 2.27158 1.04167 2.68598 1.04167C17.2693 1.04167 16.2589 1.00521 16.1808 1.11459C15.5297 2.04688 15.7068 0.916674 15.7068 23.7083ZM19.8735 4.16667H16.7485V2.60417C16.7485 2.18977 16.9131 1.79234 17.2061 1.49932C17.4992 1.20629 17.8966 1.04167 18.311 1.04167C18.7254 1.04167 19.1228 1.20629 19.4158 1.49932C19.7089 1.79234 19.8735 2.18977 19.8735 2.60417V4.16667Z" fill="#00F0FF"/></svg>
                                </div>
                                <div class="dashboard_tab_title_text">' . $translation['text60'] . '</div>
                            </div>
                        </div>
                        <div class="dashboard_tab_title dashboard_tab_title_active" data-tab="bank_transactions2">
                            <div class="dashboard_tab_title_active_skew_right"></div>
                            <div class="dashboard_tab_title_inner">
                                <div class="dashboard_tab_title_img_wrapper">
                                    <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M19.6429 3.14282H2.35714C1.05533 3.14282 0 4.19815 0 5.49996V16.4999C0 17.8018 1.05533 18.8571 2.35714 18.8571H19.6429C20.9447 18.8571 22 17.8018 22 16.4999V5.49996C22 4.19815 20.9447 3.14282 19.6429 3.14282ZM20.4285 16.4999C20.4285 16.9339 20.0768 17.2857 19.6428 17.2857H2.35714C1.92319 17.2857 1.57141 16.9339 1.57141 16.4999V11.7857H20.4285V16.4999ZM20.4285 10.2142H1.57141V8.64283H20.4285V10.2142ZM20.4285 7.07138H1.57141V5.49996C1.57141 5.06601 1.92319 4.71423 2.35714 4.71423H19.6429C20.0768 4.71423 20.4286 5.06601 20.4286 5.49996V7.07138H20.4285Z" fill="#00F0FF"/><path d="M18.0712 13.3572H14.9283C14.4944 13.3572 14.1426 13.709 14.1426 14.1429C14.1426 14.5769 14.4944 14.9286 14.9283 14.9286H18.0712C18.5051 14.9286 18.8569 14.5769 18.8569 14.1429C18.8569 13.709 18.5051 13.3572 18.0712 13.3572Z" fill="#00F0FF"/></svg>
                                </div>
                                <div class="dashboard_tab_title_text">Visa *5684</div>
                            </div>
                        </div>';

    $return['back_btn'] = '<div class="dashboard_back_btn" data-back="databases_start_four" data-database="false">
                                <img src="/images/back_bg.png" class="back_btn_bg" alt="">
                                <div class="back_btn_text">' . $translation['text22'] . '</div>
                            </div>';

    $return['content'] = '<div class="dashboard_tab_content_item dashboard_tab_content_item_bank_transactions dashboard_tab_content_item_active" data-tab="bank_transactions2">
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
                                            <div class="dashboard_bank_transactions2_table_tr">
                                                <div class="dashboard_bank_transactions2_table_td">' . $translation['text220'] . '</div>
                                                <div class="dashboard_bank_transactions2_table_td">95,00</div>
                                                <div class="dashboard_bank_transactions2_table_td"></div>
                                            </div>
                                            <div class="dashboard_bank_transactions2_table_tr">
                                                <div class="dashboard_bank_transactions2_table_td">' . $translation['text221'] . '</div>
                                                <div class="dashboard_bank_transactions2_table_td"></div>
                                                <div class="dashboard_bank_transactions2_table_td">500,00</div>
                                            </div>
                                            <div class="dashboard_bank_transactions2_table_tr">
                                                <div class="dashboard_bank_transactions2_table_td">' . $translation['text222'] . '</div>
                                                <div class="dashboard_bank_transactions2_table_td">2 402 000,00</div>
                                                <div class="dashboard_bank_transactions2_table_td"></div>
                                            </div>
                                            <div class="dashboard_bank_transactions2_table_tr">
                                                <div class="dashboard_bank_transactions2_table_td">' . $translation['text223'] . '</div>
                                                <div class="dashboard_bank_transactions2_table_td">100 000,00</div>
                                                <div class="dashboard_bank_transactions2_table_td"></div>
                                            </div>
                                            <div class="dashboard_bank_transactions2_table_tr">
                                                <div class="dashboard_bank_transactions2_table_td">' . $translation['text224'] . '</div>
                                                <div class="dashboard_bank_transactions2_table_td">410,00</div>
                                                <div class="dashboard_bank_transactions2_table_td"></div>
                                            </div>
                                            <div class="dashboard_bank_transactions2_table_tr">
                                                <div class="dashboard_bank_transactions2_table_td">' . $translation['text225'] . '</div>
                                                <div class="dashboard_bank_transactions2_table_td">154,70</div>
                                                <div class="dashboard_bank_transactions2_table_td"></div>
                                            </div>
                                            <div class="dashboard_bank_transactions2_table_tr">
                                                <div class="dashboard_bank_transactions2_table_td">' . $translation['text226'] . '</div>
                                                <div class="dashboard_bank_transactions2_table_td">52,00</div>
                                                <div class="dashboard_bank_transactions2_table_td"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>';

    return $return;
}
}
