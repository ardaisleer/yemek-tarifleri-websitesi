<?php
session_start();


if (!isset($_SESSION['kullanici_id'])) {
    header('Location: giris.php');
    exit;
}


if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];


    require_once 'baglanti.php';

    try {

        $stmt = $pdo->prepare("DELETE FROM tarifler WHERE id = :id AND yazar_id = :yazar_id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':yazar_id', $_SESSION['kullanici_id'], PDO::PARAM_INT);
        $stmt->execute();


        header('Location: profil.php');
        exit;
    } catch (PDOException $e) {

        echo 'Bir hata oluştu: ' . $e->getMessage();
    }
} else {

    header('Location: profil.php');
    exit;
}
?>