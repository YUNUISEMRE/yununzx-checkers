<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';
oturum_kontrol();

$sonuc = null;
$hata = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gsm = guvenli($_POST['gsm'] ?? '');
    if (strlen($gsm) < 10 || !is_numeric($gsm)) {
        $hata = '🌸 Geçersiz GSM numarası.';
    } elseif (!isVip() && sorgu_hakki_kontrol($_SESSION['user_id']) <= 0) {
        $hata = '🌸 Sorgu hakkın tükendi.';
    } else {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, API_URL . '?path=gsm.php');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['gsm' => $gsm, 'submit' => 1]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $response = curl_exec($ch);
        curl_close($ch);
        
        $data = json_decode($response, true);
        if ($data && $data['ok'] && !empty($data['data']['results'])) {
            $sonuc = $data['data']['results'][0];
            sorgu_log($_SESSION['user_id'], 'GSM', $gsm, $sonuc);
            if (!isVip()) sorgu_hakki_azalt($_SESSION['user_id']);
        } else {
            $hata = '🌸 Kayıt bulunamadı.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🌸 GSM Sorgu | Yununzx</title>
    <link rel="stylesheet" href="../assets/css/sakura.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="sakura-bg"></div>
    <nav class="navbar">
        <a href="../dashboard.php" class="navbar-brand">
            <img src="../assets/images/logo.jpg" alt="Yununzx"><span>🌸 Yununzx</span>
        </a>
        <ul class="navbar-menu">
            <li><a href="../dashboard.php">Dashboard</a></li>
            <li><a href="tc.php" class="active">Sorgu</a></li>
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <li><a href="../admin.php">Admin</a></li>
            <?php endif; ?>
            <li><a href="../logout.php" style="color:#ef4444;">Çıkış</a></li>
        </ul>
    </nav>
    <div class="main-content" style="max-width:700px;margin:80px auto 0;">
        <div class="card">
            <div class="card-title"><i class="fas fa-phone"></i> GSM Sorgu</div>
            <p style="color:var(--text-secondary);margin-bottom:16px;">🌸 Kalan sorgu: <?= isVip() ? '♾️ Sınırsız' : sorgu_hakki_kontrol($_SESSION['user_id']) ?></p>
            <form method="POST">
                <input type="text" name="gsm" class="input-sakura" placeholder="5xx xxx xx xx" maxlength="11" required>
                <button type="submit" class="btn btn-sakura" style="width:100%;justify-content:center;margin-top:12px;padding:14px;"><i class="fas fa-search"></i> Sorgula</button>
            </form>
        </div>
        <?php if ($hata): ?>
            <div style="background:rgba(239,68,68,0.1);color:#fca5a5;padding:16px;border-radius:12px;"><?= $hata ?></div>
        <?php endif; ?>
        <?php if ($sonuc): ?>
            <div class="card" style="border-color:var(--sakura-pink);">
                <div class="card-title"><i class="fas fa-cherry-blossom"></i> Sonuç</div>
                <div class="sonuc-card">
                    <?php foreach ($sonuc as $key => $val): ?>
                        <div class="row"><span class="key"><?= $key ?></span><span class="val"><?= htmlspecialchars($val ?? '-') ?></span></div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <script src="../assets/js/sakura.js"></script>
</body>
</html>