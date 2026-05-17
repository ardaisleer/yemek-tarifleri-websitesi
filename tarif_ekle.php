<?php
session_start(); // Oturumu başlat

// Kullanıcı giriş yapmamışsa giriş sayfasına yönlendir
if (!isset($_SESSION['kullanici_id'])) {
    header('Location: giris.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarif Ekle</title>
    <style>
        /* Sayfa düzeni ve stil */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background: #ffffff;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .form-container h2 {
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

        .form-container input,
        .form-container textarea,
        .form-container select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-container button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .form-container button:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }

        .success {
            color: green;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Tarif Ekle</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="text" name="baslik" placeholder="Yemek Başlığı" required>
            <textarea name="icerik" placeholder="Tarif İçeriği" rows="5" required></textarea>
            <select name="kategori" required>
                <option value="">Kategori Seçin</option>
                <option value="Ana Yemekler">Ana Yemekler</option>
                <option value="Tatlılar">Tatlılar</option>
                <option value="Hamur İşleri">Hamur İşleri</option>
                <option value="İçecekler">İçecekler</option>
                <option value="Kahvaltılıklar">Kahvaltılıklar</option>
            </select>
            <input type="file" name="gorsel" accept="image/*" required>
            <button type="submit">Tarif Ekle</button>
        </form>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $baslik = $_POST['baslik'];
            $icerik = $_POST['icerik'];
            $kategori = $_POST['kategori'];
            $yazar_id = $_SESSION['kullanici_id'];


            if (isset($_FILES['gorsel']) && $_FILES['gorsel']['error'] === UPLOAD_ERR_OK) {
                $uploads_dir = 'uploads/';
                $tmp_name = $_FILES['gorsel']['tmp_name'];
                $name = time() . '_' . basename($_FILES['gorsel']['name']);
                $upload_path = $uploads_dir . $name;


                if (!is_dir($uploads_dir)) {
                    mkdir($uploads_dir, 0777, true);
                }

                if (move_uploaded_file($tmp_name, $upload_path)) {

                    require_once 'baglanti.php';

                    try {

                        $stmt = $pdo->prepare("INSERT INTO tarifler (baslik, icerik, kategori, gorsel_url, yazar_id) VALUES (:baslik, :icerik, :kategori, :gorsel_url, :yazar_id)");
                        $stmt->bindParam(':baslik', $baslik);
                        $stmt->bindParam(':icerik', $icerik);
                        $stmt->bindParam(':kategori', $kategori);
                        $stmt->bindParam(':gorsel_url', $upload_path);
                        $stmt->bindParam(':yazar_id', $yazar_id);
                        $stmt->execute();


                        header('Location: index.php');
                        exit;
                    } catch (PDOException $e) {
                        echo '<p class="error">Bir hata oluştu: ' . $e->getMessage() . '</p>';
                    }
                } else {
                    echo '<p class="error">Görsel yüklenirken bir hata oluştu.</p>';
                }
            } else {
                echo '<p class="error">Lütfen bir görsel seçin.</p>';
            }
        }
        ?>
    </div>
</body>

</html>