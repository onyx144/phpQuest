<?php
    defined('GD_ACCESS') or die('You can not access the file directly!');
    require_once(ROOT . '/view/blocks/header.php');
?>
    <section class="main_content">
        <div class="main_content_inner">
            <div class="not_found_wrapper crm_shadow_item" style="color:white; text-align: center; margin-top: 100px;">
                <div class="not_found_title">Page not found</div>
                <div class="not_found_text"><?php echo $message; ?></div>
                <a href="/" class="btn">Go homepage</a>
            </div>
        </div>
    </section>
<?php
require_once(ROOT . '/view/blocks/footer.php');

