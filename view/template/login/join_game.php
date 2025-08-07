<?php
	defined('GD_ACCESS') or die('You can not access the file directly!');
	require_once(ROOT . '/view/blocks/header.php');
?>
<div class="section" id="section_join_game">
	<img src="/images/login/join_game_hand2.png" class="bg_hand" alt="">
	<div class="container" style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 60vh;">
		<div class="join_mission_btn_wrapper" style="margin-bottom: 30px;">
			<a href="/control-system" class="join_mission_btn">Join the mission</a>
		</div>
		<div class="join_mission_btn_wrapper join_mission_btn_wrapper2">
			<a href="#" onclick="alert('Here is the transition to purchase the game'); return false;" class="join_mission_btn">Buy the game</a>
		</div>
		<img src="/images/login/list11.png" class="bg_list1" alt="">
		<img src="/images/login/list22.png" class="bg_list2" alt="">
		<img src="/images/login/list3.png" class="bg_list3" alt="">
		<img src="/images/login/list55.png" class="bg_list5" alt="">
		<img src="/images/login/list66.png" class="bg_list6" alt="">
	</div>
	<img src="/images/login/list4.png" class="bg_list4" alt="">
	<img src="/images/login/list77.png" class="bg_list7" alt="">
</div>
<?php
require_once(ROOT . '/view/blocks/footer.php');
