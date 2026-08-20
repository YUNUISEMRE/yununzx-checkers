<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';
oturum_kontrol();

$user = kullanici_bilgi($_SESSION['user_id']);
$isVip = isVip();
$hak = $isVip ? '♾️ Sınırsız' : $user['sorgu_hakki'];

$stmt = $pdo->prepare("SELECT COUNT(*) FROM sorgu_gecmisi WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$toplam = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT * FROM sorgu_gecmisi WHERE user_id = ? ORDER BY sorgu_tarihi DESC LIMIT 10");
$stmt->execute([$_SESSION['user_id']]);
$son_sorgular = $stmt->fetchAll();

$duyurular = duyuru_getir(3);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🌸 Dashboard | Yununzx</title>
    <link rel="stylesheet" href="assets/css/sakura.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="sakura-bg"></div>
    
    <nav class="navbar">
        <button class="hamburger" id="hamburger"><span></span><span></span><span></span></button>
        <a href="dashboard.php" class="navbar-brand">
            <img src="assets/images/logo.jpg" alt="Yununzx"><span>🌸 Yununzx</span>
        </a>
        <ul class="navbar-menu" id="navMenu">
            <li><a href="dashboard.php" class="active">Dashboard</a></li>
            <li><a href="sorgu/tc.php">Sorgu</a></li>
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <li><a href="admin.php">Admin</a></li>
            <?php endif; ?>
            <li><a href="profil.php">Profil</a></li>
            <li><a href="logout.php" style="color:#ef4444;">Çıkış</a></li>
            <li><button id="themeToggle" style="background:none;border:none;color:var(--text-secondary);font-size:1.2rem;cursor:pointer;"><i class="fas fa-moon"></i></button></li>
        </ul>
    </nav>

    <div class="sidebar" id="sidebar">
        <div class="sidebar-title">🌸 Menü</div>
        <a href="dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a>
        <a href="sorgu/tc.php"><i class="fas fa-id-card"></i> TC Sorgu</a>
        <a href="sorgu/gsm.php"><i class="fas fa-phone"></i> GSM Sorgu</a>
        <a href="sorgu/adres.php"><i class="fas fa-home"></i> Adres Sorgu</a>
        <a href="sorgu/aile.php"><i class="fas fa-users"></i> Aile Sorgu</a>
        <a href="sorgu/vesika.php"><i class="fas fa-camera"></i> Vesika Sorgu</a>
        <hr style="border-color:var(--border);margin:12px 0;">
        <a href="profil.php"><i class="fas fa-user"></i> Profil</a>
        <?php if ($_SESSION['role'] === 'admin'): ?>
            <a href="admin.php"><i class="fas fa-crown"></i> Admin</a>
        <?php endif; ?>
        <a href="logout.php" style="color:#ef4444;"><i class="fas fa-sign-out-alt"></i> Çıkış</a>
    </div>

    <div class="main-content">
        <?php foreach ($duyurular as $d): ?>
            <div class="duyuru-item <?= $d['onemli'] ? 'onemli' : '' ?>">
                <div class="baslik">🌸 <?= htmlspecialchars($d['baslik']) ?></div>
                <div style="color:var(--text-secondary);font-size:0.9rem;margin-top:4px;"><?= nl2br(htmlspecialchars($d['icerik'])) ?></div>
                <div class="tarih"><?= $d['created_at'] ?></div>
            </div>
        <?php endforeach; ?>

        <div class="card">
            <div class="card-title"><i class="fas fa-cherry-blossom" style="color:var(--sakura-pink);"></i> Hoş Geldin, <?= htmlspecialchars($user['username']) ?>!</div>
            <p style="color:var(--text-secondary);">🌸 Bugün <?= $isVip ? 'VIP 👑' : 'Free' ?> üyesisin. <?= $isVip ? 'Sınırsız sorgu hakkın var!' : $user['sorgu_hakki'] . ' sorgu hakkın kaldı.' ?></p>
        </div>

        <div class="stats-grid">
            <div class="stat-card"><div class="icon">🌸</div><div class="value"><?= $toplam ?></div><div class="label">Toplam Sorgu</div></div>
            <div class="stat-card"><div class="icon">👑</div><div class="value"><?= $hak ?></div><div class="label">Kalan Sorgu</div></div>
            <div class="stat-card"><div class="icon">⭐</div><div class="value"><?= ucfirst($user['role']) ?></div><div class="label">Rol</div></div>
            <div class="stat-card"><div class="icon">📅</div><div class="value" style="font-size:1rem;"><?= date('d.m.Y', strtotime($user['created_at'])) ?></div><div class="label">Üyelik Tarihi</div></div>
        </div>

        <div class="card">
            <div class="card-title"><i class="fas fa-search"></i> Hızlı Sorgu</div>
            <div style="display:flex;flex-wrap:wrap;gap:10px;">
                <a href="sorgu/tc.php" class="btn btn-sakura"><i class="fas fa-id-card"></i> TC</a>
                <a href="sorgu/gsm.php" class="btn btn-sakura"><i class="fas fa-phone"></i> GSM</a>
                <a href="sorgu/adres.php" class="btn btn-sakura"><i class="fas fa-home"></i> Adres</a>
                <a href="sorgu/aile.php" class="btn btn-sakura"><i class="fas fa-users"></i> Aile</a>
                <a href="sorgu/vesika.php" class="btn btn-sakura"><i class="fas fa-camera"></i> Vesika</a>
            </div>
        </div>

        <?php if (!empty($son_sorgular)): ?>
        <div class="card">
            <div class="card-title"><i class="fas fa-history"></i> Son Sorgular</div>
            <table class="table-sakura">
                <tr><th>Tür</th><th>Parametre</th><th>Tarih</th></tr>
                <?php foreach ($son_sorgular as $s): ?>
                    <tr><td><?= $s['sorgu_turu'] ?></td><td><?= $s['sorgu_parametresi'] ?></td><td><?= $s['sorgu_tarihi'] ?></td></tr>
                <?php endforeach; ?>
            </table>
        </div>
        <?php endif; ?>
    </div>

    <script src="assets/js/sakura.js"></script>
</body>
</html>