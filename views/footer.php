</div>
    <footer class="bg-dark text-light py-3 mt-auto">
   
        <?php include 'template/popup/video.php'; ?>
        <?php include 'template/popup/passkey.php'; ?>
        <?php include 'template/popup/load_function.php'; ?>
        <?php include 'template/popup/success.php'; ?>
        <?php include 'template/popup/error.php'; ?>
        <?php require_once 'views/block/bg_footer.php'; ?>
        <?php include 'template/game/chat.php'; ?>
        <?php showLoadingPopup('popup_load_processing' , '/zombie/images/icons/face-id.png', 'verefying'); ?>
        <?php showLoadingPopup('popup_load_transferring' , '/zombie/images/profits1.png', 'transferring' , ); ?>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URI ?>/public/js/gameCode.js"></script>
    <script src="<?= BASE_URI ?>/public/js/scale.js"></script>
    <script src="<?= BASE_URI ?>/plugins/jquery.jplayer.min.js"></script>
    <script src="<?= BASE_URI ?>/public/js/binance.js"></script>

    <script src="<?= BASE_URI ?>/public/js/startBinance.js"></script>

</body>
</html>