<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';
admin_kontrol();

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $free_limit = (int)$_POST['free_limit'];
    file_put_contents('../includes/config.php', str_replace("define('FREE_SORGULAMA', " . FREE_SORGULAMA . ")", "define('FREE_SORGULAMA', $free_limit)", file_get_contents('../includes/config.php')));
    admin_log($_SESSION['user_id'], "Sorgu limiti güncellendi: $free_limit");
    $msg = '🌸 Ayarlar güncellendi!';
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🌸 Ayarlar | Admin</title>
    <link rel="stylesheet" href="../assets/css/sakura.css">
</head>
<body>
    <div class="sakura-bg"></div>
    <nav class="navbar">
        <a href="../dashboard.php" class="navbar-brand"><img src="../assets/images/logo.jpg" alt=""><span>🌸 Admin</span></a>
        <ul class="navbar-menu">
            <li><a href="index.php">Anasayfa</a></li>
            <li><a href="users.php">Kullanıcılar</a></li>
            <li><a href="logs.php">Loglar</a></li>
            <li><a href="duyuru_ekle.php">Duyuru</a></li>
            <li><a href="settings.php" class="active">Ayarlar</a></li>
            <li><a href="../logout.php" style="color:#ef4444;">Çıkış</a></li>
        </ul>
    </nav>
    <div class="main-content" style="max-width:600px;margin:80px auto 0;">
        <div class="card">
            <div class="card-title"><i class="fas fa-cog"></i> Site Ayarları</div>
            <?php if ($msg): ?>
                <div style="background:rgba(255,183,197,0.1);color:var(--sakura-pink);padding:12px;border-radius:12px;margin-bottom:16px;"><?= $msg ?></div>
            <?php endif; ?>
            <form method="POST">
                <div style="margin-bottom:16px;">
                    <label style="color:var(--text-secondary);font-weight:600;">🌸 Free Kullanıcı Sorgu Limiti</label>
                    <input type="number" name="free_limit" class="input-sakura" value="<?= FREE_SORGULAMA ?>" min="1" max="100">
                </div>
                <button type="submit" class="btn btn-sakura" style="width:100%;justify-content:center;">🌸 Kaydet</button>
            </form>
        </div>
    </div>
    <script src="../assets/js/sakura.js"></script>
</body>
</html>