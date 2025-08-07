<?php
require_once 'views/template/functions.php';
?>
<div class="section" id="section_binance">
    <div class="content">
        <div class="content-container">
            <img src="<?= BASE_URI ?>/images/right2-bg.png" alt="Norwegian Flag" class="bg_image">
            
            <div class="header-section">
                    <img src="<?= BASE_URI ?>/images/binance_logo.png" alt="">
                    <span><?php echo $translation['binance_one']; ?></span>
            </div>
        <div class="section_binance_bootom">
            <div class="section_binance_bootom_row">
                <div class="left">
                  <?php require_once(ROOT . '/views/template/binance/left.php'); ?>
                </div>
                <div class="right">
                <?php require_once(ROOT . '/views/template/binance/right.php'); ?>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
