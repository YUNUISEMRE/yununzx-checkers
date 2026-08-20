<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';
oturum_kontrol();

$user = kullanici_bilgi($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $stmt = $pdo->prepare("UPDATE users SET email = ? WHERE id = ?");
    $stmt->execute([$email, $_SESSION['user_id']]);
    $mesaj = '🌸 Profil güncellendi!';
    $user = kullanici_bilgi($_SESSION['user_id']);
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🌸 Profil | Yununzx</title>
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
        <ul class="navbar-menu">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="sorgu/tc.php">Sorgu</a></li>
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <li><a href="admin.php">Admin</a></li>
            <?php endif; ?>
            <li><a href="profil.php" class="active">Profil</a></li>
            <li><a href="logout.php" style="color:#ef4444;">Çıkış</a></li>
        </ul>
    </nav>

    <div class="main-content" style="max-width:600px;margin:80px auto 0;">
        <div class="card">
            <div class="card-title"><i class="fas fa-user"></i> Profil Bilgileri</div>
            
            <?php if (isset($mesaj)): ?>
                <div style="background:rgba(255,183,197,0.1);color:var(--sakura-pink);padding:12px;border-radius:12px;margin-bottom:16px;"><?= $mesaj ?></div>
            <?php endif; ?>
            
            <div style="text-align:center;margin-bottom:20px;">
                <img src="assets/images/logo.jpg" style="height:100px;width:100px;border-radius:50%;border:3px solid var(--sakura-pink);">
                <h2 style="color:var(--sakura-pink);margin-top:8px;"><?= htmlspecialchars($user['username']) ?></h2>
                <span style="background:var(--sakura-pink);color:#1a0f1a;padding:4px 16px;border-radius:20px;font-weight:700;font-size:0.8rem;"><?= ucfirst($user['role']) ?></span>
            </div>
            
            <form method="POST">
                <div style="margin-bottom:16px;">
                    <label style="color:var(--text-secondary);font-weight:600;">📧 E-posta</label>
                    <input type="email" name="email" class="input-sakura" value="<?= htmlspecialchars($user['email']) ?>">
                </div>
                <button type="submit" class="btn btn-sakura" style="width:100%;justify-content:center;">🌸 Güncelle</button>
            </form>
            
            <div style="margin-top:20px;padding-top:20px;border-top:1px solid var(--border);">
                <div style="display:flex;justify-content:space-between;color:var(--text-secondary);font-size:0.9rem;padding:4px 0;">
                    <span>Üyelik Tarihi</span>
                    <span><?= date('d.m.Y H:i', strtotime($user['created_at'])) ?></span>
                </div>
                <div style="display:flex;justify-content:space-between;color:var(--text-secondary);font-size:0.9rem;padding:4px 0;">
                    <span>Son Giriş</span>
                    <span><?= $user['last_login'] ? date('d.m.Y H:i', strtotime($user['last_login'])) : 'İlk giriş' ?></span>
                </div>
                <div style="display:flex;justify-content:space-between;color:var(--text-secondary);font-size:0.9rem;padding:4px 0;">
                    <span>Kalan Sorgu</span>
                    <span><?= $user['sorgu_hakki'] ?></span>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/sakura.js"></script>
</body>
</html>