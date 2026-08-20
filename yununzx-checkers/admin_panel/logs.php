<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';
admin_kontrol();

$logs = $pdo->query("SELECT * FROM sorgu_gecmisi ORDER BY sorgu_tarihi DESC LIMIT 100")->fetchAll();
$admin_logs = $pdo->query("SELECT * FROM admin_logs ORDER BY tarih DESC LIMIT 50")->fetchAll();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🌸 Loglar | Admin</title>
    <link rel="stylesheet" href="../assets/css/sakura.css">
</head>
<body>
    <div class="sakura-bg"></div>
    <nav class="navbar">
        <a href="../dashboard.php" class="navbar-brand"><img src="../assets/images/logo.jpg" alt=""><span>🌸 Admin</span></a>
        <ul class="navbar-menu">
            <li><a href="index.php">Anasayfa</a></li>
            <li><a href="users.php">Kullanıcılar</a></li>
            <li><a href="logs.php" class="active">Loglar</a></li>
            <li><a href="duyuru_ekle.php">Duyuru</a></li>
            <li><a href="settings.php">Ayarlar</a></li>
            <li><a href="../logout.php" style="color:#ef4444;">Çıkış</a></li>
        </ul>
    </nav>
    <div class="main-content">
        <div class="card">
            <div class="card-title"><i class="fas fa-history"></i> Sorgu Logları</div>
            <table class="table-sakura">
                <tr><th>ID</th><th>Kullanıcı</th><th>Tür</th><th>Parametre</th><th>IP</th><th>Tarih</th></tr>
                <?php foreach ($logs as $l): ?>
                    <tr>
                        <td><?= $l['id'] ?></td>
                        <td><?= $l['user_id'] ?></td>
                        <td><?= $l['sorgu_turu'] ?></td>
                        <td><?= $l['sorgu_parametresi'] ?></td>
                        <td><?= $l['ip_adresi'] ?></td>
                        <td><?= $l['sorgu_tarihi'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <div class="card">
            <div class="card-title"><i class="fas fa-shield-alt"></i> Admin Logları</div>
            <table class="table-sakura">
                <tr><th>ID</th><th>Admin</th><th>İşlem</th><th>IP</th><th>Tarih</th></tr>
                <?php foreach ($admin_logs as $l): ?>
                    <tr>
                        <td><?= $l['id'] ?></td>
                        <td><?= $l['admin_id'] ?></td>
                        <td><?= htmlspecialchars($l['islem']) ?></td>
                        <td><?= $l['ip_adresi'] ?></td>
                        <td><?= $l['tarih'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
    <script src="../assets/js/sakura.js"></script>
</body>
</html>