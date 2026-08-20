<?php
require_once 'includes/db.php';

// Admin hesabı oluştur (şifre: admin123)
$username = 'admin';
$password = password_hash('admin123', PASSWORD_DEFAULT);
$email = 'admin@yununz.com';
$role = 'admin';
$sorgu_hakki = 999;

try {
    // Önce var mı kontrol et
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $var = $stmt->fetch();
    
    if ($var) {
        // Varsa güncelle
        $stmt = $pdo->prepare("UPDATE users SET password = ?, email = ?, role = ?, sorgu_hakki = ? WHERE username = ?");
        $stmt->execute([$password, $email, $role, $sorgu_hakki, $username]);
        echo "🌸 Admin hesabı GÜNCELLENDI!<br>";
    } else {
        // Yoksa ekle
        $stmt = $pdo->prepare("INSERT INTO users (username, password, email, role, sorgu_hakki) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$username, $password, $email, $role, $sorgu_hakki]);
        echo "🌸 Admin hesabı OLUŞTURULDU!<br>";
    }
    
    echo "<br>✅ Kullanıcı adı: <strong>admin</strong><br>";
    echo "✅ Şifre: <strong>admin123</strong><br>";
    echo "<br><a href='login.php'>Giriş Yap</a>";
    
} catch(PDOException $e) {
    echo "❌ Hata: " . $e->getMessage();
}
?>