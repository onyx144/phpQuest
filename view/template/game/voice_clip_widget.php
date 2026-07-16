<?php
defined('GD_ACCESS') or die('You can not access the file directly!');
$voiceClipId = isset($voiceClipId) ? (int) $voiceClipId : 0;
$voiceClipPos = isset($voiceClipPos) ? $voiceClipPos : '';
if ($voiceClipId < 1 || $voiceClipId > 4) {
	return;
}
?>
<div class="voice_clip_widget voice_clip_pos_<?= htmlspecialchars($voiceClipPos, ENT_QUOTES, 'UTF-8') ?>" data-audio-id="<?= $voiceClipId ?>">
	<button type="button" class="voice_clip_btn" aria-label="Play">
		<span class="voice_clip_icon voice_clip_icon_play">
			<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 3.5V14.5L14.5 9L6 3.5Z" fill="currentColor"/></svg>
		</span>
		<span class="voice_clip_icon voice_clip_icon_pause">
			<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="5" y="3.5" width="2.8" height="11" fill="currentColor"/><rect x="10.2" y="3.5" width="2.8" height="11" fill="currentColor"/></svg>
		</span>
	</button>
	<audio preload="none" src="/music/deshefrator/<?= $voiceClipId ?>_audio.mp3"></audio>
	<button type="button" class="voice_clip_add_btn">Додати до дешефровки</button>
</div>
