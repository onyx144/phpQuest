<?php
	defined('GD_ACCESS') or die('You can not access the file directly!');
	/*if ($this->userInfo == false) {
        $this->body_class[] = 'auth_page';
    } else {
    	$this->body_class[] = 'body_after_auth';
    }*/

	function generateRandomStringIntelescape($length = 100) {
        $string = '';

        $chars = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numChars = strlen($chars);

        for ($j = 0; $j < $length; $j++) {
            $string .= substr($chars, rand(1, $numChars) - 1, 1);
        }

        return $string;
    }

	$random = generateRandomStringIntelescape(5);
?>
<!doctype html>
<html lang="<?php echo $this->lang->getParam('lang_abbr'); ?>">
	<head>
		<meta charset="utf-8" />
		<!-- <meta name="viewport" id="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=1" /> -->
		<!-- <meta name="viewport" content="width=1200" /> -->
		<!-- <meta name="viewport" content="width=1920" /> -->
		<meta name="viewport" content="width=1920, minimum-scale=0.1, user-scalable=1" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="google" content="notranslate" />

        <title><?php if (!empty($this->meta_title)) { echo $this->meta_title; } else { echo 'Digital Game'; } ?></title>
        <meta name="description" content="" />
        <meta name="keywords" content="" />

        <meta name="robots" content="noindex" />

        <!-- <link rel="icon" type="image/png" href="/favicon.png" /> -->
        <!-- <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon"> -->
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
		<link rel="manifest" href="/site.webmanifest">

		<link rel="stylesheet" href="/plugins/selectric/selectric.css" />
		<link rel="stylesheet" href="/plugins/jquery-ui-1.12.1.custom/jquery-ui.min.css" />
		<link rel="stylesheet" href="/plugins/slick-1.8.1/slick/slick.css" />
		<link rel="stylesheet" href="/plugins/fancybox/jquery.fancybox.min.css">
		<?php
			if (count($this->styles) > 0) {
				foreach ($this->styles as $style) {
					echo $style;
				}
			}
		?>
		<link rel="stylesheet" href="/view/css/style.css?v=<?php echo $random; ?>" />
		<link rel="stylesheet" href="/view/css/cyber.css?v=<?php echo $random; ?>" />
		<link rel="stylesheet" href="/dist/output.css?v=<?php echo $random; ?>" />

		<link rel="stylesheet" href="/view/css/tailwind.css?v=<?php echo $random; ?>" />
		<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
		<!-- <script src="/plugins/animating-roll-number/jquery.rollNumber.js"></script> -->
		<script src="/plugins/selectric/jquery.selectric.min.js"></script>
		<script src="/plugins/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
		<script src="/plugins/slick-1.8.1/slick/slick.min.js"></script>
		<script src="/plugins/jquery.bubble.text.js"></script>
		<script src="/plugins/fancybox/jquery.fancybox.min.js"></script>
		<!-- <script src="/plugins/gm_gauge.js"></script> -->
		<?php
			if (count($this->scripts) > 0) {
				foreach ($this->scripts as $script) {
					echo $script;
				}
			}
		?>
		<script src="/view/js/main.js?v=<?php echo $random; ?>"></script>
		<script src="/view/js/1_accept_mission.js?v=<?php echo $random; ?>"></script>
		<script src="/view/js/2_databases_car_register.js?v=<?php echo $random; ?>"></script>
		<script src="/view/js/3_databases_personal_files_private_individuals.js?v=<?php echo $random; ?>"></script>
		<script src="/view/js/4_databases_mobile_calls.js?v=<?php echo $random; ?>"></script>
		<script src="/view/js/5_databases_personal_files_ceo_database.js?v=<?php echo $random; ?>"></script>
		<script src="/view/js/6_dashboard_company_investigate.js?v=<?php echo $random; ?>"></script>
		<script src="/view/js/7_dashboard_geo_coordinates.js?v=<?php echo $random; ?>"></script>
		<script src="/view/js/8_dashboard_african_partner.js?v=<?php echo $random; ?>"></script>
		<script src="/view/js/9_databases_bank_transactions.js?v=<?php echo $random; ?>"></script>
		<script src="/view/js/10_dashboard_metting_place.js?v=<?php echo $random; ?>"></script>
		<script src="/view/js/11_tools_scan.js?v=<?php echo $random; ?>"></script>
		<script src="/view/js/12_dashboard_room_name.js?v=<?php echo $random; ?>"></script>
		<script src="/view/js/13_minigame.js?v=<?php echo $random; ?>"></script>
		<script src="/view/js/14_dashboard_password.js?v=<?php echo $random; ?>"></script>
		<script src="/view/js/hint.js?v=<?php echo $random; ?>"></script>
		<script src="/view/js/interpol.js?v=<?php echo $random; ?>"></script>
		<script src="/view/js/score.js?v=<?php echo $random; ?>"></script>
		<script src="/view/js/progress_mission.js?v=<?php echo $random; ?>"></script>
		<script src="/view/js/timer.js?v=<?php echo $random; ?>"></script>
		<script src="/view/js/highscore.js?v=<?php echo $random; ?>"></script>
		<script src="/view/js/music.js?v=<?php echo $random; ?>"></script>
		<script src="/view/js/exit.js?v=<?php echo $random; ?>"></script>
		<script src="/view/js/chatbot.js?v=<?php echo $random; ?>"></script>
		<script src="/view/js/tab.js?v=<?php echo $random; ?>"></script>
		<script src="/view/js/dashboard.js?v=<?php echo $random; ?>"></script>
		<script src="/view/js/animation.js?v=<?php echo $random; ?>"></script>
		<script src="/view/js/calls.js?v=<?php echo $random; ?>"></script>
		<script src="/view/js/files.js?v=<?php echo $random; ?>"></script>
		<script src="/view/js/databases.js?v=<?php echo $random; ?>"></script>
		<script src="/view/js/tools.js?v=<?php echo $random; ?>"></script>
		<script src="/view/js/socket.js?v=<?php echo $random; ?>"></script>
		<!-- <script src="https://static.matterport.com/showcase-sdk/latest.js"></script> -->
		<?php
			if (count($this->scripts_after) > 0) {
				foreach ($this->scripts_after as $script) {
					echo $script;
				}
			}
		?>
		<!--[if lt IE 9]>
           	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>
    <body class="<?php echo implode(' ', $this->body_class); ?>">
    	<?php if ($this->userInfo) { ?>
	    	<div id="preloader">
	    		<img src="/images/preloader_inner_bg.png" class="preloader_inner_bg" alt="">
	            <span><?php if (isset($translation['text31'])) { echo $translation['text31']; } ?></span>
	            <img src="/images/loader.png" class="loader_img" alt="">
	        </div>
	    <?php } ?>
        <div id="main">
<?php
    /*if ($this->userInfo == false) {
        require_once(ROOT . '/view/blocks/form_auth.php');
        require_once(ROOT . '/view/blocks/footer.php');
        exit();
    }*/