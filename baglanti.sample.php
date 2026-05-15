<?php
// baglanti.sample.php
$host = "localhost";
$dbname = "yemek_sistemi";
$username = "root"; // Kendi kullanıcı adınızı yazın
$password = ""; // Kendi şifrenizi yazın

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
} catch (PDOException $e) {
    echo "Bağlantı hatası: " . $e->getMessage();
}
?>