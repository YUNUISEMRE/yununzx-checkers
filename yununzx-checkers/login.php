<?php
session_start();
require_once 'includes/db.php';

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$hata = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header('Location: dashboard.php');
        exit;
    } else {
        $hata = '🌸 Kullanıcı adı veya şifre hatalı!';
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🌸 Giriş | Yununzx</title>
    <link rel="stylesheet" href="assets/css/sakura.css">
</head>
<body>
    <div class="sakura-bg"></div>
    <div class="main-content" style="display:flex;justify-content:center;align-items:center;min-height:100vh;margin:0;">
        <div class="card" style="max-width:420px;width:100%;padding:40px;">
            <div style="text-align:center;margin-bottom:30px;">
                <img src="assets/images/logo.jpg" alt="Yununzx" style="height:80px;border-radius:50%;border:3px solid var(--sakura-pink);">
                <h1 style="color:var(--sakura-pink);font-size:1.8rem;margin-top:12px;">🌸 Yununzx</h1>
                <p style="color:var(--text-secondary);font-weight:600;">Checkers</p>
            </div>
            <?php if ($hata): ?>
                <div style="background:rgba(239,68,68,0.1);color:#fca5a5;padding:12px;border-radius:12px;margin-bottom:16px;text-align:center;"><?= $hata ?></div>
            <?php endif; ?>
            <form method="POST">
                <div style="margin-bottom:16px;">
                    <label style="color:var(--text-secondary);font-weight:600;font-size:0.9rem;">🌸 Kullanıcı Adı</label>
                    <input type="text" name="username" class="input-sakura" placeholder="Kullanıcı adın" required>
                </div>
                <div style="margin-bottom:20px;">
                    <label style="color:var(--text-secondary);font-weight:600;font-size:0.9rem;">🔑 Şifre</label>
                    <input type="password" name="password" class="input-sakura" placeholder="Şifren" required>
                </div>
                <button type="submit" class="btn btn-sakura" style="width:100%;justify-content:center;font-size:1rem;padding:14px;">🌸 Giriş Yap</button>
            </form>
            <div style="text-align:center;margin-top:20px;color:var(--text-secondary);">
                Hesabın yok mu? <a href="register.php" style="color:var(--sakura-pink);text-decoration:none;font-weight:700;">Kayıt Ol</a>
            </div>
            <div style="text-align:center;margin-top:12px;font-size:0.8rem;color:var(--text-secondary);">
                🌸 Free: 5 sorgu/gün · VIP: Sınırsız
            </div>
        </div>
    </div>
    <script src="assets/js/sakura.js"></script>
</body>
</html>