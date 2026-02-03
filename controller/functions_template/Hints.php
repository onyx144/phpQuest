<?php

trait Hints
{
public function getHintsByStep($step, $lang_id)
{
    $sql = "
        SELECT h.points, h.number, h.type, hd.text, h.id
        FROM hints h
        JOIN hints_description hd ON h.id = hd.hint_id
        WHERE h.step = {?}
        AND hd.lang_id = {?}
        ORDER BY h.sort, h.number
    ";
    return $this->db->select($sql, [$step, $lang_id]);
}

// актуальное состояние подсказок. Вывод страницы подсказок справа и слева
public function getHintPageHints($team_id, $lang_id)
{
    $return = [
        'success_hint_left' => '',
        'success_hint_right' => '',
        'success_hint_right_title' => '', // заголовок блока со списком подсказок
        'success_hint_right_text' => '' // текст блока со списком подсказок
    ];

    $translation = $this->getWordsByPage('game', $lang_id);

    $team_info = $this->teamInfo($team_id);

    // активный текст подсказок. Слева
    $active_hint_ids = []; // доп массив для проверки активных

    $active_hints = json_decode($team_info['active_hints'], true);
    if (count($active_hints) == 0) {
        $return['success_hint_left'] = '<span>' . $translation['text25'] . '</span>';
    } else {
        foreach ($active_hints as $hint_id) {
            /*$sql = "
                SELECT h.id, h.points, hd.text, h.type, h.number, hd.text_btn
                FROM hints h
                JOIN hints_description hd ON h.id = hd.hint_id
                WHERE hd.lang_id = {?}
                AND h.id = {?}
                ORDER BY h.type DESC, h.id ASC
            ";*/
            $sql = "
                SELECT h.id, h.points, hd.text, h.type, h.number, hd.text_btn
                FROM hints h
                JOIN hints_description hd ON h.id = hd.hint_id
                WHERE hd.lang_id = {?}
                AND h.id = {?}
                ORDER BY h.sort DESC, h.number ASC
            ";
            $hint_info = $this->db->selectRow($sql, [$lang_id, (int) $hint_id]);
            if ($hint_info) {
                $return['success_hint_left'] .= '<span><p>' . $hint_info['text_btn'] . ':</p> ' . $hint_info['text'] . '</span>';

                $active_hint_ids[] = $hint_info['id'];
            }
        }
    }

    // доступный список подсказок. Справа
    $list_hints = json_decode($team_info['list_hints'], true);
    if (count($list_hints) > 0) {
        foreach ($list_hints as $hint_id) {
            /*$sql = "
                SELECT h.id, h.points, hd.text, h.type, h.number, hd.text_btn
                FROM hints h
                JOIN hints_description hd ON h.id = hd.hint_id
                WHERE hd.lang_id = {?}
                AND h.id = {?}
                ORDER BY h.type DESC, h.id ASC
            ";*/
            $sql = "
                SELECT h.id, h.points, hd.text, h.type, h.number, hd.text_btn, h.answer_open_ids, h.answer_open_id_count
                FROM hints h
                JOIN hints_description hd ON h.id = hd.hint_id
                WHERE hd.lang_id = {?}
                AND h.id = {?}
                ORDER BY h.sort DESC, h.number ASC
            ";
            $hint_info = $this->db->selectRow($sql, [$lang_id, (int) $hint_id]);
            if ($hint_info) {
                // можно ли открыть ответ
                $answer_can_open = '';
                if ($hint_info['type'] == 'answer') {
                    // $answer_can_open = ' list_hint_item_answer_dont_open';

                    // Список идентификаторов подсказок, которые должны быть открыты для доступности ответа
                    $answer_open_ids = json_decode($hint_info['answer_open_ids'], true);

                    if (count($answer_open_ids) > 0) {
                        // Сколько из списка подсказок должно быть открыто. 0 - все
                        if (empty($hint_info['answer_open_id_count'])) {
                            // var_dump($answer_open_ids);
                            foreach ($answer_open_ids as $answer_open_id) {
                                if (!in_array((int) $answer_open_id, $active_hint_ids)) {
                                    $answer_can_open = ' list_hint_item_answer_dont_open';

                                    break;
                                }
                            }
                        } else {
                            $count_opened_with_list = 0;

                            foreach ($answer_open_ids as $answer_open_id) {
                                if (in_array($answer_open_id, $active_hint_ids)) {
                                    $count_opened_with_list++;
                                }
                            }

                            if ($count_opened_with_list < $hint_info['answer_open_id_count']) {
                                $answer_can_open = ' list_hint_item_answer_dont_open';
                            }
                        }
                    }

                    // if (count($list_hints) - 1 == count($active_hint_ids) && !in_array($hint_id, $active_hint_ids)) {
                    //     $answer_can_open = '';
                    // }
                }

                $return['success_hint_right'] .= '<div class="btn_wrapper btn_wrapper_blue_light list_hint_item list_hint_item_points_' . $hint_info['points'] . '_wrapper' . $answer_can_open . (in_array($hint_id, $active_hint_ids) ? ' list_hint_item_opened' : '') . '" data-hint-id="' . $hint_id . '">
                                                    <div class="btn btn_blue_light">
                                                        <span>' . $hint_info['text_btn'] . '</span>
                                                        <p class="list_hint_item_points list_hint_item_points_' . $hint_info['points'] . '">
                                                            <svg width="42" height="34" viewBox="0 0 42 34" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.18426 1H40.6843V27.24L35.6636 33H1.16357V6.76L6.18426 1Z" fill="#FF0303" fill-opacity="0.5" stroke="#FF0303"/></svg>
                                                            <span>-' . $hint_info['points'] . '</span>
                                                        </p>
                                                    </div>
                                                </div>';
            }
        }
    }

    // заголовок
    if (!empty($team_info['list_hints_title_lang_var'])) {
        $sql = "SELECT `val` FROM `lang_words_admin` WHERE `field` = {?} AND `page` = {?} AND `language_id` = {?}";
        $list_hints_title_lang_var = $this->db->selectCell($sql, [$team_info['list_hints_title_lang_var'], 'game', $lang_id]);
        if (!empty($list_hints_title_lang_var)) {
            $return['success_hint_right_title'] = '<span>' . $list_hints_title_lang_var . '</span>';

            if ($team_info['list_hints_title_lang_var'] == 'text26') {
                $return['success_hint_right_title'] .= '<svg width="27" height="29" viewBox="0 0 27 29" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.72127 0H26.4713V2.19232L23.9136 5.1154H0.163574V2.92308L2.72127 0Z" fill="#00F0FF"/><path d="M8.56746 10.2307H26.4713V12.423L23.9136 15.3461H6.00977V13.1538L8.56746 10.2307Z" fill="#00F0FF"/><path d="M7.47121 24.1155L11.4904 24.1155V26.3078L9.66352 28.5001L5.64429 28.5001V26.3078L7.47121 24.1155Z" fill="#00F0FF"/><path d="M11.125 14.6153L11.125 22.2884L8.93268 22.2884L6.0096 19.7307L6.0096 13.1538L8.20192 13.1538L11.125 14.6153Z" fill="#00F0FF"/><path d="M20.625 10.596L20.625 0.730659L22.8173 0.730659L26.4711 1.46143L26.4711 10.9614L23.5481 10.9614L20.625 10.596Z" fill="#00F0FF"/></svg>';
            }
        }
    }

    // текст
    if (!empty($team_info['list_hints_text_lang_var'])) {
        $sql = "SELECT `val` FROM `lang_words_admin` WHERE `field` = {?} AND `page` = {?} AND `language_id` = {?}";
        $list_hints_text_lang_var = $this->db->selectCell($sql, [$team_info['list_hints_text_lang_var'], 'game', $lang_id]);
        if (!empty($list_hints_text_lang_var)) {
            $return['success_hint_right_text'] = $list_hints_text_lang_var;
        }
    }

    return $return;
}
}
