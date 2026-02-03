<?php

trait Files
{
public function uploadFilesActualForView($team_id, $lang_id)
{
$translation = $this->getWordsByPage('game', $lang_id);
$team_info = $this->teamInfo($team_id);
$svg = $this->svg; 
$return = [];

// Заголовок
$return['titles'] = '
    <div class="flex items-center gap-3 mb-6">
        <div class="icon-container p-2 rounded-lg bg-primary/20 border border-primary/30">
            '.$svg['dashboard_files'].'
        </div>
        <h2 class="text-3xl font-bold neon-text">Архив досье</h2>
    </div>
';

// Контент
$return['content'] = '
<div class="mt-6 ">
    <div class="flex items-center gap-2 mb-4">
        <svg class="h-4 w-4 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path d="M4 4h16v16H4z"/>
        </svg>
        <span class="text-sm text-primary">Архив документов, видео и изображений</span>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
';

if ($team_info) {
$list_files = json_decode($team_info['list_files'], true);

foreach ($list_files as $file_id) {
    $sql = "
        SELECT f.type, fd.path, fd.name, fd.file_with_path
        FROM files f
        JOIN files_description fd ON f.id = fd.file_id 
        WHERE f.id = {?}
        AND fd.lang_id = {?}
    ";
    $file_info = $this->db->selectRow($sql, [(int) $file_id, $lang_id]);

    if ($file_info) {

        // Определяем тип файла для кнопки
        $button_icon = $svg['eye'];
        $button_text = 'Посмотреть';

        if (in_array(strtolower($file_info['type']), ['video', 'mp4', 'mov', 'avi'])) {
            $button_icon = $svg['play'];
            $button_text = 'Воспроизвести';
        }

        // Карточка файла
        $return['content'] .= '
<div class="dashboard_tab_content_file_item w-full relative flex flex-col justify-between 
        p-4 border border-cyan-500/50 rounded-lg bg-cyan-950/20 
        transition-all duration-300 animate-pulse-glow group"
 data-type="'.$file_info['type'].'" 
 data-path="'.$file_info['path'].'" 
 data-file-with-path="'.$file_info['file_with_path'].'" 
 data-file-id="'.$file_id.'">

<div>
    <div class="text-sm font-semibold mb-2 text-cyan-100 tracking-wide">
        '.$file_info['name'].'
    </div>
    <div class="text-xs text-cyan-400/70 mb-4 uppercase">
        '.$file_info['type'].'
    </div>
</div>

<div class="mt-auto">
    <a href="'.$file_info['path'].'" target="_blank"
       class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium rounded-md border border-cyan-500/40 
              text-cyan-200 hover:text-cyan-50 hover:bg-cyan-800/40 transition-all duration-300 w-fit">
        '.$button_icon.'
        <span>'.$button_text.'</span>
    </a>
</div>
</div>
';
    }
}
}

$return['content'] .= '
    </div>
</div>
';


 

return $return;
}
}
