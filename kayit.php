<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol</title>
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

        .form-container input {
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
        <h2>Kayıt Ol</h2>
        <form action="" method="POST">
            <input type="text" name="kullanici_adi" placeholder="Kullanıcı Adı" required>
            <input type="password" name="sifre" placeholder="Şifre" required>
            <button type="submit">Kayıt Ol</button>
        </form>
        <?php

        session_start(); // Oturumu başlat
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $kullanici_adi = $_POST['kullanici_adi']; // Kullanıcı adı al
        
            $sifre = $_POST['sifre']; // Şifre al
        
            require_once 'baglanti.php'; // Veritabanı bağlantısını dahil et
        
            $hashed_password = password_hash($sifre, PASSWORD_DEFAULT); // Şifreyi hashle
        
            try {

                // Kullanıcıyı veritabanına ekle
                $stmt = $pdo->prepare("INSERT INTO kullanicilar (kullanici_adi, sifre) VALUES (:kullanici_adi, :sifre)");

                $stmt->bindParam(':kullanici_adi', $kullanici_adi);

                $stmt->bindParam(':sifre', $hashed_password);

                $stmt->execute();

                // Oturum bilgilerini ayarla
                $_SESSION['kullanici_id'] = $pdo->lastInsertId();

                $_SESSION['kullanici_adi'] = $kullanici_adi;

                // Ana sayfaya yönlendir
                header('Location: index.php');

                exit;

            } catch (PDOException $e) {

                // Kullanıcı adı zaten varsa hata mesajı göster
                if ($e->getCode() == 23000) {

                    echo '<p class="error">Bu kullanıcı adı zaten alınmış.</p>';

                } else {

                    echo '<p class="error">Bir hata oluştu: ' . $e->getMessage() . '</p>';

                }

            }

        }

        ?>
    </div>
</body>

</html>