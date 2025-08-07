<div class="left-binance-container">
    <div class="pnl-section">
        <span class="pnl-title"><?php echo $translation['pnl']; ?></span>
        <span class="pnl-value">+$1000.23 (+0.23%)</span>
    </div>

    <div class="stats-circle-container">
        <img src="<?= BASE_URI ?>/images/right-circle-1.png" alt="Circle" class="circle-image">
        
        <div class="stats-grid">
            <div class="stats-column">
                <div class="stat-item">
                    <span class="stat-label spot-label">Spot</span>
                    <span class="stat-value">0.22330000 BTC (60.00%)</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label usdo-label">USDO-M</span>
                    <span class="stat-value">1.22330000 BTC (30.00%)</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label coin-label">COIN-M</span>
                    <span class="stat-value">0.0000000 BTC (10.00%)</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label arbitrage-label">Arbitrage Stra...</span>
                    <span class="stat-value">0.0000000 BTC (10.00%)</span>
                </div>
            </div>
            
            
        </div>
    </div>
    <?php echo generate_button('transfer_money' , 'transfer_money'); ?>
</div>
