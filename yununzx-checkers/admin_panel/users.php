<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';
admin_kontrol();

$users = $pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['sil'])) {
        $id = (int)$_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role != 'admin'");
        $stmt->execute([$id]);
        admin_log($_SESSION['user_id'], "Kullanıcı silindi ID: $id");
        header('Location: users.php?ok=1');
        exit;
    }
    if (isset($_POST['role'])) {
        $id = (int)$_POST['id'];
        $role = $_POST['role'];
        $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->execute([$role, $id]);
        admin_log($_SESSION['user_id'], "Kullanıcı rolü güncellendi ID: $id -> $role");
        header('Location: users.php?ok=1');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🌸 Kullanıcılar | Admin</title>
    <link rel="stylesheet" href="../assets/css/sakura.css">
</head>
<body>
    <div class="sakura-bg"></div>
    <nav class="navbar">
        <a href="../dashboard.php" class="navbar-brand"><img src="../assets/images/logo.jpg" alt=""><span>🌸 Admin</span></a>
        <ul class="navbar-menu">
            <li><a href="index.php">Anasayfa</a></li>
            <li><a href="users.php" class="active">Kullanıcılar</a></li>
            <li><a href="logs.php">Loglar</a></li>
            <li><a href="duyuru_ekle.php">Duyuru</a></li>
            <li><a href="settings.php">Ayarlar</a></li>
            <li><a href="../logout.php" style="color:#ef4444;">Çıkış</a></li>
        </ul>
    </nav>
    <div class="main-content">
        <div class="card">
            <div class="card-title"><i class="fas fa-users"></i> Tüm Kullanıcılar</div>
            <?php if (isset($_GET['ok'])): ?>
                <div style="background:rgba(255,183,197,0.1);color:var(--sakura-pink);padding:12px;border-radius:12px;margin-bottom:16px;">🌸 İşlem başarılı!</div>
            <?php endif; ?>
            <table class="table-sakura">
                <tr><th>ID</th><th>Kullanıcı</th><th>Email</th><th>Rol</th><th>Sorgu Hakkı</th><th>Kayıt</th><th>İşlem</th></tr>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= $u['id'] ?></td>
                        <td><?= htmlspecialchars($u['username']) ?></td>
                        <td><?= htmlspecialchars($u['email'] ?? '-') ?></td>
                        <td><?= ucfirst($u['role']) ?></td>
                        <td><?= $u['sorgu_hakki'] ?></td>
                        <td><?= date('d.m.Y', strtotime($u['created_at'])) ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                <select name="role" onchange="this.form.submit()">
                                    <option value="user" <?= $u['role']=='user'?'selected':'' ?>>User</option>
                                    <option value="vip" <?= $u['role']=='vip'?'selected':'' ?>>VIP</option>
                                    <option value="admin" <?= $u['role']=='admin'?'selected':'' ?>>Admin</option>
                                </select>
                            </form>
                            <?php if ($u['role'] != 'admin'): ?>
                            <form method="POST" style="display:inline;" onsubmit="return confirm('Silinsin mi?')">
                                <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                <button type="submit" name="sil" style="background:#ef4444;border:none;color:#fff;padding:4px 10px;border-radius:6px;cursor:pointer;">🗑️</button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
    <script src="../assets/js/sakura.js"></script>
</body>
</html>