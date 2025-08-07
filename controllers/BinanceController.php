<?php

class BinanceController extends Controller {
    public function index() {
        echo '<script>
            if (!localStorage.getItem("user")) {
                window.location.href = "/zombie";
            }
        </script>';

        // Fetch Bitcoin price data
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.binance.com/api/v3/ticker/price?symbol=BTCUSDT');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        $btcPrice = isset($data['price']) ? floatval($data['price']) : 0;
        $fiveBtcValue = number_format($btcPrice * 5, 2, '.', '');

        // Pass the data to the view
        $this->render('binance', ['btcPrice' => $btcPrice, 'fiveBtcValue' => $fiveBtcValue]);
    }
} 