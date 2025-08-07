<?php
	defined('GD_ACCESS') or die('You can not access the file directly!');

	$bot_face = '<img src="/images/bot_face.png" alt="">';
	$chat_messages = $this->function->getChatMessages($this->userInfo['team_id'], $this->lang->getParam('id'));
?>
<div class="chat">
	<div class="chat_close">
        <img src="/images/popup_close.png" alt="">
    </div>
	<div class="chat_header">
		<?php echo $bot_face; ?>
		<div class="chat_header_text"><?php echo $translation['text20']; ?></div>
	</div>
	<div class="chat_messages">
		<div class="chat_messages_scroll<?php if (!$chat_messages) { echo ' chat_message_empty'; } ?>">
			<?php
				if ($chat_messages) {
					echo $this->function->printChatMessages($chat_messages, $this->lang->getParam('id'));
				} else {
					echo $translation['text35'];
				}
			?>
		</div>
	</div>
	<div class="chat_form"<?php if (!isset($team_info) || empty($team_info['chat_send_message_access'])) { echo ' style="display: none;"'; } ?>>
		<input type="text" placeholder="<?php echo $translation['text34']; ?>" value="" autocomplete="off"<?php if (!isset($team_info) || empty($team_info['chat_send_message_access'])) { echo ' disabled="disabled"'; } ?>>
		<div class="chat_send_btn">
			<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.8 18L18 0L0 7.2L4.2 11.4L14.4 3.6L6.6 13.8L10.8 18Z" fill="white"/></svg>
		</div>
		<div class="chat_form_border_left"></div>
		<div class="chat_form_border_right"></div>
	</div>
</div>