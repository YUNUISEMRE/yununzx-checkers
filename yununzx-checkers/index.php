<?php
session_start();

// Oturum kontrolü
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
    // Giriş yapmışsa dashboard'a git
    header('Location: dashboard.php');
    exit;
} else {
    // Giriş yapmamışsa login sayfasına git
    header('Location: login.php');
    exit;
}
?>