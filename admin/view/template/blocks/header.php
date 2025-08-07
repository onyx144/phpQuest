<?php defined('GD_ACCESS') or die('You can not access the file directly!'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <meta name="viewport" content="width=1200" />
	<title><?php echo $this->pagetitle; ?></title>

    <!-- <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/v0.53.0/mapbox-gl.css" />
    <link rel="stylesheet" href="/admin/view/css/libs.bundle.css" />
    <link rel="stylesheet" href="/admin/view/css/theme.bundle.css" id="stylesheetLight" /> -->

    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">

    <link rel="stylesheet" href="/plugins/jquery-ui-1.12.1.custom/jquery-ui.min.css" />
    <link rel="stylesheet" href="/admin/view/css/theme/jquery.datetimepicker.min.css" />
    <link rel="stylesheet" href="/plugins/fontawesome-free-5.9.0-web/css/all.min.css">
    <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700&amp;subset=cyrillic" rel="stylesheet">
    <!-- <link rel="stylesheet" href="/plugins/fancybox/jquery.fancybox.min.css"> -->

    <link rel="stylesheet" href="/admin/view/css/theme/bootstrap.min.css" />
    <link rel="stylesheet" href="/admin/view/css/theme/flatpickr.min.css" />
    <link rel="stylesheet" href="/admin/view/css/theme/quill.core.css" />
    <link rel="stylesheet" href="/admin/view/css/theme/vs2015.css" />
    <link rel="stylesheet" href="/admin/view/css/theme/theme.min.css" id="stylesheetLight">
    <link rel="stylesheet" href="/admin/view/css/theme/theme-dark.min.css" id="stylesheetDark" disabled="">
    <link rel="stylesheet" href="/admin/view/css/styles.css?v=qwert" />

    <script src="/admin/view/js/theme/jquery.min.js"></script>

    <!-- <script src="/plugins/fancybox/jquery.fancybox.min.js"></script> -->
    <script src="/plugins/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
    <script src="/admin/view/js/theme/jquery.datetimepicker.full.min.js"></script>
    <!-- <script src="/admin/view/js/collapse.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

    <script src="/admin/view/js/theme/bootstrap.min.js"></script>
    <script src="/admin/view/js/theme/bootstrap.bundle.min.js"></script>
    <script src="/admin/view/js/theme/draggable.bundle.legacy.js"></script>
    <script src="/admin/view/js/theme/autosize.min.js"></script>
    <script src="/admin/view/js/theme/Chart.min.js"></script>
    <script src="/admin/view/js/theme/dropzone.min.js"></script>
    <script src="/admin/view/js/theme/highlight.pack.min.js"></script>
    <script src="/admin/view/js/theme/jquery.mask.min.js"></script>
    <script src="/admin/view/js/theme/list.min.js"></script>
    <script src="/admin/view/js/theme/quill.min.js"></script>
    <script src="/admin/view/js/theme/Chart.extension.js"></script>
    <script src="/admin/view/js/theme/theme.js"></script>
    <script src="/admin/view/js/theme/dashkit.min.js"></script>
    <script src="/admin/view/js/main.js"></script>
    
    <style>body { display: none; }</style>
</head>
<body class="<?php echo implode(' ', $this->body_class); ?>">
    <div id="preloader">
        <span></span>
        <img src="/images/ajax-loader.gif" alt="">
    </div>