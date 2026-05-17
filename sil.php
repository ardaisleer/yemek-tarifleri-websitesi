<?php
session_start(); // Oturumu başlat

// Kullanıcı giriş yapmamışsa giriş sayfasına yönlendir
if (!isset($_SESSION['kullanici_id'])) {
    header('Location: giris.php');
    exit;
}

// Geçerli bir tarif ID'si kontrolü
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    require_once 'baglanti.php'; // Veritabanı bağlantısını dahil et

    try {
        // Tarif silme işlemi
        $stmt = $pdo->prepare("DELETE FROM tarifler WHERE id = :id AND yazar_id = :yazar_id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':yazar_id', $_SESSION['kullanici_id'], PDO::PARAM_INT);
        $stmt->execute();

        // Profil sayfasına yönlendir
        header('Location: profil.php');
        exit;
    } catch (PDOException $e) {
        // Hata mesajı göster
        echo 'Bir hata oluştu: ' . $e->getMessage();
    }
} else {
    // Geçersiz ID durumunda profil sayfasına yönlendir
    header('Location: profil.php');
    exit;
}
?>