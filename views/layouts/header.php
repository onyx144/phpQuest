<?php
if (!defined('GD_ACCESS')) {
    die('Прямой доступ запрещен');
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewsport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'PHP Game'; ?></title>
    <base href="/zombie/"> 
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URI ?>/public/css/style.css">
	<link rel="stylesheet" href="<?= BASE_URI ?>/public/css/cyber.css">
     <link rel="stylesheet" href="<?= BASE_URI ?>/public/css/login.css">
	<link rel="stylesheet" href="<?= BASE_URI ?>/dist/output.css">

	<link rel="stylesheet" href="<?= BASE_URI ?>/public/css/tailwind.css">

	<link rel="stylesheet" href="<?= BASE_URI ?>/public/css/binance.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    
    <script src="<?= BASE_URI ?>/views/js/main.js?v=1.1"></script>
		<script src="<?= BASE_URI ?>/views/js/1_accept_mission.js?v=1.1"></script>
		<script src="<?= BASE_URI ?>/views/js/2_databases_car_register.js?v=1.1"></script>
		<script src="<?= BASE_URI ?>/views/js/3_databases_personal_files_private_individuals.js?v=1.1"></script>
		<script src="<?= BASE_URI ?>/views/js/4_databases_mobile_calls.js?v=1.1"></script>
		<script src="<?= BASE_URI ?>/views/js/5_databases_personal_files_ceo_database.js?v=1.1"></script>
	    <script src="<?= BASE_URI ?>/views/js/6_dashboard_company_investigate.js?v=1.1"></script>
		<script src="<?= BASE_URI ?>/views/js/7_dashboard_geo_coordinates.js?v=1.1"></script>
		<script src="<?= BASE_URI ?>/views/js/8_dashboard_african_partner.js?v=1.1"></script>
		<script src="<?= BASE_URI ?>/views/js/9_databases_bank_transactions.js?v=1.1"></script>
		<script src="<?= BASE_URI ?>/views/js/10_dashboard_metting_place.js?v=1.1"></script>
		<script src="<?= BASE_URI ?>/views/js/11_tools_scan.js?v=1.1"></script>
		<script src="<?= BASE_URI ?>/views/js/12_dashboard_room_name.js?v=1.1"></script>
		<script src="<?= BASE_URI ?>/views/js/13_minigame.js?v=1.1"></script>
		<script src="<?= BASE_URI ?>/views/js/14_dashboard_password.js?v=1.1"></script>
		<script src="<?= BASE_URI ?>/views/js/hint.js?v=1.1"></script>
		<script src="<?= BASE_URI ?>/views/js/interpol.js?v=1.1"></script>
		<script src="<?= BASE_URI ?>/views/js/score.js?v=1.1"></script>
		<script src="<?= BASE_URI ?>/views/js/progress_mission.js?v=1.1"></script>
		<script src="<?= BASE_URI ?>/views/js/timer.js?v=1.1"></script>
		<script src="<?= BASE_URI ?>/views/js/highscore.js?v=1.1"></script>
		<script src="<?= BASE_URI ?>/views/js/music.js?v=1.1"></script>
		<script src="<?= BASE_URI ?>/views/js/exit.js?v=1.1"></script>
		<script src="<?= BASE_URI ?>/views/js/tab.js?v=1.1"></script>
		<script src="<?= BASE_URI ?>/views/js/dashboard.js?v=1.1"></script>
		<script src="<?= BASE_URI ?>/views/js/animation.js?v=1.1"></script>
		<script src="<?= BASE_URI ?>/views/js/calls.js?v=1.1"></script>
		<script src="<?= BASE_URI ?>/views/js/files.js?v=1.1"></script>
		<script src="<?= BASE_URI ?>/views/js/databases.js?v=1.1"></script>
		<script src="<?= BASE_URI ?>/views/js/tools.js?v=1.1"></script>
		<script src="<?= BASE_URI ?>/views/js/socket.js?v=1.1"></script>

    </head>
<?php
$current_page = basename($_SERVER['PHP_SELF']);
$body_class = "body_game_bg body_desktop_scale";
if ($current_page === 'vaccine-report.php' || $current_page === 'vaksine-rapport.php') {
    $body_class .= " body-report";
}
?>
<body class="<?php echo $body_class; ?>">
<div id="preloader2">
	    		<img src="<?= BASE_URI ?>/images/preloader_inner_bg.png" class="preloader_inner_bg" alt="">
	            <span><?php if (isset($translation['text31'])) { echo $translation['text31']; } ?></span>
	            <img src="<?= BASE_URI ?>/images/loader.png" class="loader_img" alt="">
	        </div>
 <div id="main">
<script src="<?= BASE_URI ?>/js/scale.js"></script>