<?php

trait StartFourBlock
{
    /**
     * databases - первый экран после принятия миссии - список 4-ех баз данных
     * @param int $lang_id
     * @return array
     */
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
}
