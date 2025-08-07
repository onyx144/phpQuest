<div class="left-binance-container">
    <div class="card-section">
        <span class="level-4"><?php echo $translation['card']; ?></span>
    </div>
    
    <div class="balance-section">
        <span><?php echo $translation['balance']; ?></span>
        <?php echo $svg['eye']; ?>
    </div>
    
    <div class="btc-amount">
        5.00 BTC
    </div>
    
    <div class="equivalent">
        = <?php echo $fiveBtcValue; ?> USDT
    </div>
    
    <div class="stats-container">
        <div class="stat-block">
            <div class="stat-row">
                <img src="<?= BASE_URI ?>/images/circle-1.png" alt="Circle 1">
                <div class="stat-text">
                    <div class="stat-line">
                        <span class="text_gray"><?php echo $translation['today']; ?></span>
                        <span>349.45 eur</span>
                    </div>
                    <div class="stat-line">
                    <span class="text_gray"><?php echo $translation['daily']; ?></span>
                    <span>349.45 eur</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="stat-block">
            <div class="stat-row">
                <img src="<?= BASE_URI ?>/images/circle-2.png" alt="Circle 2">
                <div class="stat-text">
                    <div class="stat-line">
                        <span class="text_gray"><?php echo $translation['today2']; ?></span>
                        <span>349.45 eur</span>
                    </div>
                    <div class="stat-line">
                    <span class="text_gray"><?php echo $translation['daily2']; ?></span>
                    <span>349.45 eur</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="request-text">
        <?php echo $translation['jane_requested']; ?>
    </div>
</div>
