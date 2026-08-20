<?php
function guvenli($veri) {
    return htmlspecialchars(strip_tags(trim($veri)), ENT_QUOTES, 'UTF-8');
}

function tc_kontrol($tc) {
    return (strlen($tc) === 11 && is_numeric($tc) && $tc[0] != '0');
}

function sorgu_log($user_id, $tur, $param, $sonuc) {
    global $pdo;
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $stmt = $pdo->prepare("INSERT INTO sorgu_gecmisi (user_id, sorgu_turu, sorgu_parametresi, sonuc, ip_adresi) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $tur, $param, json_encode($sonuc), $ip]);
}

function sorgu_hakki_kontrol($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT sorgu_hakki FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $row = $stmt->fetch();
    return $row['sorgu_hakki'] ?? 0;
}

function sorgu_hakki_azalt($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE users SET sorgu_hakki = sorgu_hakki - 1 WHERE id = ? AND sorgu_hakki > 0");
    $stmt->execute([$user_id]);
}

function duyuru_getir($limit = 5) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM duyurular ORDER BY onemli DESC, created_at DESC LIMIT ?");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

function admin_log($admin_id, $islem) {
    global $pdo;
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $stmt = $pdo->prepare("INSERT INTO admin_logs (admin_id, islem, ip_adresi) VALUES (?, ?, ?)");
    $stmt->execute([$admin_id, $islem, $ip]);
}
?>