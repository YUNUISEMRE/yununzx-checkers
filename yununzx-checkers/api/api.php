<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
error_reporting(0);
ini_set('display_errors', 0);

$target = 'https://ruhsuzpanel8.site';
$path = isset($_GET['path']) ? $_GET['path'] : '';

$allowed = ['tc.php', 'aile.php', 'adres2009_2024.php', 'gsm.php', 'vesika.php', 'sulale.php', 'hane.php', 'sokak.php', 'adsoyad.php'];
if (!in_array($path, $allowed)) {
    die(json_encode(['ok' => false, 'error' => 'Geçersiz API yolu.']));
}

$url = $target . '/' . $path;
$postData = $_POST;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
}

curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
    'Accept: application/json, text/plain, */*',
    'Referer: https://ruhsuzpanel8.site/'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($response === false) {
    die(json_encode(['ok' => false, 'error' => 'Hedef siteye bağlanılamadı.']));
}

http_response_code($httpCode);
echo $response;
?>