<?php
// Получаем текст перевода
$translationText = isset($translation['text87']) ? $translation['text87'] : '';

// Выводим JavaScript код для установки переменной
echo "<script>
    var translationText = " . json_encode($translationText) . ";
</script>";
?> 