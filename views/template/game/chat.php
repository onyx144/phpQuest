<?php
	defined('GD_ACCESS') or die('You can not access the file directly!');

	$bot_face = '<img src="/zombie/images/bot_face.png" alt="">';
?>
<div class="chat">
	<div class="chat_close">
        <img src="<?= BASE_URI ?>/images/popup_close.png" alt="">
    </div>
	<div class="chat_header">
		<?php echo $bot_face; ?>
		<div class="chat_header_text"><?php echo $translation['text20']; ?></div>
	</div>
	<div class="chat_messages">
		<div class="chat_messages_scroll">
			<?php
				
					echo $translation['text35'];
				
			?>
		</div>
	</div>
</div>