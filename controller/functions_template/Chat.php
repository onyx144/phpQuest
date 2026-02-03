<?php

trait Chat
{
public function getChatMessages($team_id, $lang_id)
{
    $sql = "
        SELECT c.id, c.team_id, c.side, c.datetime, c.message_default_id, cd.lang_id, cd.message
        FROM chat_messages c
        JOIN chat_messages_description cd ON c.id = cd.chat_message_id
        WHERE c.team_id = {?}
        AND cd.lang_id = {?}
        ORDER BY c.datetime
    ";
    return $this->db->select($sql, [(int) $team_id, (int) $lang_id]);
}

// выводим сообщения в чате
public function printChatMessages($messages, $lang_id)
{
    $return = '';

    if (count($messages) > 0) {
        $translation = $this->getWordsByPage('game', $lang_id);

        $today = new DateTime();
        $yesterday = new DateTime();
        $yesterday->sub(new DateInterval('P1D'));

        // выводим текст о том, вчерашние или сегодняшние сообщения
        $date_message_prev = new DateTime($messages[0]['datetime']);

        if ($yesterday->format('Y-m-d') == $date_message_prev->format('Y-m-d')) {
            $return .= '<div class="chat_message_day_title">' . $translation['text33'] . '</div>'; // вчера
        } else {
            $return .= '<div class="chat_message_day_title">' . $translation['text32'] . '</div>'; // сегодня
        }

        // от кого было предыдущее сообщение
        $prev_message_side = $messages[0]['side'];
        // меняем на противоположное, чтобы всегда выводить первую иконку
        if ($prev_message_side == 'team') {
            $prev_message_side = 'bot';
        } else {
            $prev_message_side = 'team';
        }

        // непосредственно выводим сообщения
        foreach ($messages as $message) {
            // если были вчерашние сообщения, то выводим надпись о сегодняшнем дне
            $date_message_cur = new DateTime($message['datetime']);
            if ($date_message_cur->format('Y-m-d') != $date_message_prev->format('Y-m-d')) {
                $return .= '<div class="chat_message_day_title">' . $translation['text32'] . '</div>';
            }
            $date_message_prev = new DateTime($message['datetime']);

            // сами сообщения
            $return .= $this->printOneMessageChat($message['id'], $message['side'], $message['message'], $prev_message_side);

            $prev_message_side = $message['side'];
        }
    }

    return $return;
}

// вывод одного сообщения в чате
public function printOneMessageChat($message_id, $side, $message, $prev_message_side)
{
    $return = '<div class="chat_message_section chat_message_section_from_' . $side . '">';

        // если предыдущее сообщение было не от этой стороны, то выводим иконку
        $icon = '';

        if ($side != $prev_message_side) {
            if ($side == 'bot') {
                $icon = '<img src="/images/bot_face_small.png" alt="">';
            } elseif ($side == 'team') {
                $icon = '<img src="/images/chat_face_player.png" alt="">';
            }
        }

        if (!empty($icon)) {
            $return .= '<div class="chat_message_icon">' . $icon . '</div>';
        }

        // само сообщение
        $return .= '<div class="chat_message_item" data-message_id="' . $message_id . '">' . $message . '</div>';

    $return .= '</div>';

    return $return;
}
}
