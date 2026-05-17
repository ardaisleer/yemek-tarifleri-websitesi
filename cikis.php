<?php
session_start(); // Oturumu başlat

// Tüm oturum değişkenlerini temizle
$_SESSION = [];

// Çerezler kullanılıyorsa, oturum çerezini sil
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000, // Çerezi geçmiş bir zamana ayarla
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

session_destroy(); // Oturumu tamamen yok et

// Kullanıcıyı ana sayfaya yönlendir
header('Location: index.php');
exit;
?>