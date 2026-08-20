<?php
require_once '../includes/auth.php';
admin_kontrol();

$msg = '';
if (isset($_GET['export'])) {
    header('Content-Type: application/json');
    header('Content-Disposition: attachment; filename="backup_' . date('Y-m-d') . '.json"');
    $data = [
        'users' => $pdo->query("SELECT * FROM users")->fetchAll(),
        'sorgu_gecmisi' => $pdo->query("SELECT * FROM sorgu_gecmisi")->fetchAll(),
        'duyurular' => $pdo->query("SELECT * FROM duyurular")->fetchAll(),
        'backup_tarihi' => date('Y-m-d H:i:s')
    ];
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['backup_file'])) {
    $file = $_FILES['backup_file'];
    if ($file['type'] === 'application/json') {
        $data = json_decode(file_get_contents($file['tmp_name']), true);
        if ($data) {
            admin_log($_SESSION['user_id'], "Backup yüklendi");
            $msg = '🌸 Backup başarıyla yüklendi!';
        } else {
            $msg = '🌸 Geçersiz backup dosyası.';
        }
    } else {
        $msg = '🌸 Lütfen JSON dosyası seçin.';
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🌸 Yedek | Admin</title>
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
            <li><a href="settings.php">Ayarlar</a></li>
            <li><a href="backup.php" class="active">Yedek</a></li>
            <li><a href="../logout.php" style="color:#ef4444;">Çıkış</a></li>
        </ul>
    </nav>
    <div class="main-content" style="max-width:600px;margin:80px auto 0;">
        <div class="card">
            <div class="card-title"><i class="fas fa-download"></i> Yedek Al</div>
            <?php if ($msg): ?>
                <div style="background:rgba(255,183,197,0.1);color:var(--sakura-pink);padding:12px;border-radius:12px;margin-bottom:16px;"><?= $msg ?></div>
            <?php endif; ?>
            <a href="?export=1" class="btn btn-sakura" style="width:100%;justify-content:center;"><i class="fas fa-download"></i> JSON Yedek İndir</a>
        </div>
        <div class="card">
            <div class="card-title"><i class="fas fa-upload"></i> Yedek Yükle</div>
            <form method="POST" enctype="multipart/form-data">
                <input type="file" name="backup_file" class="input-sakura" accept=".json" required>
                <button type="submit" class="btn btn-sakura" style="width:100%;justify-content:center;margin-top:12px;"><i class="fas fa-upload"></i> Yükle</button>
            </form>
        </div>
    </div>
    <script src="../assets/js/sakura.js"></script>
</body>
</html>