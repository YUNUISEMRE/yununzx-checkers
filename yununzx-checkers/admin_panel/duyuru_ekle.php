<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';
admin_kontrol();

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $baslik = guvenli($_POST['baslik']);
    $icerik = guvenli($_POST['icerik']);
    $onemli = isset($_POST['onemli']) ? 1 : 0;
    $stmt = $pdo->prepare("INSERT INTO duyurular (baslik, icerik, onemli) VALUES (?, ?, ?)");
    $stmt->execute([$baslik, $icerik, $onemli]);
    admin_log($_SESSION['user_id'], "Duyuru eklendi: $baslik");
    $msg = '🌸 Duyuru eklendi!';
}

$duyurular = duyuru_getir(20);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🌸 Duyuru Ekle | Admin</title>
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
            <li><a href="duyuru_ekle.php" class="active">Duyuru</a></li>
            <li><a href="settings.php">Ayarlar</a></li>
            <li><a href="../logout.php" style="color:#ef4444;">Çıkış</a></li>
        </ul>
    </nav>
    <div class="main-content" style="max-width:600px;margin:80px auto 0;">
        <div class="card">
            <div class="card-title"><i class="fas fa-bullhorn"></i> Duyuru Ekle</div>
            <?php if ($msg): ?>
                <div style="background:rgba(255,183,197,0.1);color:var(--sakura-pink);padding:12px;border-radius:12px;margin-bottom:16px;"><?= $msg ?></div>
            <?php endif; ?>
            <form method="POST">
                <div style="margin-bottom:16px;">
                    <label style="color:var(--text-secondary);font-weight:600;">🌸 Başlık</label>
                    <input type="text" name="baslik" class="input-sakura" required>
                </div>
                <div style="margin-bottom:16px;">
                    <label style="color:var(--text-secondary);font-weight:600;">📝 İçerik</label>
                    <textarea name="icerik" class="input-sakura" rows="5" required></textarea>
                </div>
                <div style="margin-bottom:16px;">
                    <label style="color:var(--text-secondary);font-weight:600;">
                        <input type="checkbox" name="onemli" value="1"> ⭐ Önemli Duyuru
                    </label>
                </div>
                <button type="submit" class="btn btn-sakura" style="width:100%;justify-content:center;">🌸 Yayınla</button>
            </form>
        </div>

        <div class="card">
            <div class="card-title"><i class="fas fa-list"></i> Mevcut Duyurular</div>
            <?php foreach ($duyurular as $d): ?>
                <div class="duyuru-item <?= $d['onemli'] ? 'onemli' : '' ?>">
                    <div class="baslik"><?= htmlspecialchars($d['baslik']) ?></div>
                    <div style="color:var(--text-secondary);font-size:0.9rem;"><?= nl2br(htmlspecialchars($d['icerik'])) ?></div>
                    <div class="tarih"><?= $d['created_at'] ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script src="../assets/js/sakura.js"></script>
</body>
</html>