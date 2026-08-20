<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';
admin_kontrol();

$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_logs = $pdo->query("SELECT COUNT(*) FROM sorgu_gecmisi")->fetchColumn();
$last_logs = $pdo->query("SELECT * FROM sorgu_gecmisi ORDER BY sorgu_tarihi DESC LIMIT 15")->fetchAll();
$users = $pdo->query("SELECT * FROM users ORDER BY id DESC LIMIT 10")->fetchAll();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🌸 Admin Panel | Yununzx</title>
    <link rel="stylesheet" href="../assets/css/sakura.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="sakura-bg"></div>
    <nav class="navbar">
        <a href="../dashboard.php" class="navbar-brand">
            <img src="../assets/images/logo.jpg" alt="Yununzx"><span>🌸 Admin Panel</span>
        </a>
        <ul class="navbar-menu">
            <li><a href="index.php" class="active">Anasayfa</a></li>
            <li><a href="users.php">Kullanıcılar</a></li>
            <li><a href="logs.php">Loglar</a></li>
            <li><a href="duyuru_ekle.php">Duyuru</a></li>
            <li><a href="settings.php">Ayarlar</a></li>
            <li><a href="backup.php">Yedek</a></li>
            <li><a href="../logout.php" style="color:#ef4444;">Çıkış</a></li>
        </ul>
    </nav>
    <div class="main-content">
        <div class="card">
            <div class="card-title"><i class="fas fa-crown" style="color:var(--gold);"></i> Admin Paneli</div>
            <div class="stats-grid">
                <div class="stat-card"><div class="icon">👤</div><div class="value"><?= $total_users ?></div><div class="label">Toplam Kullanıcı</div></div>
                <div class="stat-card"><div class="icon">📊</div><div class="value"><?= $total_logs ?></div><div class="label">Toplam Sorgu</div></div>
            </div>
        </div>
        <div class="card">
            <div class="card-title"><i class="fas fa-users"></i> Son 10 Kullanıcı</div>
            <table class="table-sakura">
                <tr><th>ID</th><th>Kullanıcı</th><th>Rol</th><th>Kayıt</th></tr>
                <?php foreach ($users as $u): ?>
                    <tr><td><?= $u['id'] ?></td><td><?= htmlspecialchars($u['username']) ?></td><td><?= ucfirst($u['role']) ?></td><td><?= date('d.m.Y', strtotime($u['created_at'])) ?></td></tr>
                <?php endforeach; ?>
            </table>
        </div>
        <div class="card">
            <div class="card-title"><i class="fas fa-history"></i> Son 15 Sorgu</div>
            <table class="table-sakura">
                <tr><th>ID</th><th>Tür</th><th>Parametre</th><th>Tarih</th></tr>
                <?php foreach ($last_logs as $l): ?>
                    <tr><td><?= $l['id'] ?></td><td><?= $l['sorgu_turu'] ?></td><td><?= $l['sorgu_parametresi'] ?></td><td><?= $l['sorgu_tarihi'] ?></td></tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
    <script src="../assets/js/sakura.js"></script>
</body>
</html>