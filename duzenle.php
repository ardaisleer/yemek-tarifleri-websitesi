<?php
session_start();


if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: profil.php');
    exit;
}
$id = $_GET['id'];


require_once 'baglanti.php';


$stmt = $pdo->prepare("SELECT * FROM tarifler WHERE id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$tarif = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$tarif) {
    header('Location: profil.php');
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $baslik = $_POST['baslik'];
    $icerik = $_POST['icerik'];
    $kategori = $_POST['kategori'];
    $yeniResim = $_FILES['resim'];


    if ($yeniResim['error'] === UPLOAD_ERR_OK) {
        $dosyaAdi = time() . '_' . basename($yeniResim['name']);
        $hedefYol = 'uploads/' . $dosyaAdi;
        move_uploaded_file($yeniResim['tmp_name'], $hedefYol);


        if (!empty($tarif['gorsel_url']) && file_exists($tarif['gorsel_url'])) {
            unlink($tarif['gorsel_url']);
        }

        $guncelResimYolu = $hedefYol;
    } else {
        $guncelResimYolu = $tarif['gorsel_url'];
    }


    $updateStmt = $pdo->prepare("UPDATE tarifler SET baslik = :baslik, icerik = :icerik, kategori = :kategori, gorsel_url = :gorsel_url WHERE id = :id");
    $updateStmt->bindParam(':baslik', $baslik);
    $updateStmt->bindParam(':icerik', $icerik);
    $updateStmt->bindParam(':kategori', $kategori);
    $updateStmt->bindParam(':gorsel_url', $guncelResimYolu);
    $updateStmt->bindParam(':id', $id, PDO::PARAM_INT);
    $updateStmt->execute();

    header('Location: profil.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarif Düzenle</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-top: 10px;
            font-weight: bold;
        }

        input,
        textarea,
        select {
            margin-top: 5px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        textarea {
            resize: vertical;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .buttons {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn {
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            background-color: #007BFF;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .btn.cancel {
            background-color: #6c757d;
        }

        .btn.cancel:hover {
            background-color: #5a6268;
        }

        .current-image {
            margin-top: 10px;
        }

        .current-image img {
            max-width: 100px;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Tarif Düzenle</h1>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="baslik">Başlık</label>
                <input type="text" id="baslik" name="baslik" value="<?php echo htmlspecialchars($tarif['baslik']); ?>"
                    required>
            </div>
            <div class="form-group">
                <label for="icerik">İçerik</label>
                <textarea id="icerik" name="icerik" rows="5"
                    required><?php echo htmlspecialchars($tarif['icerik']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="kategori">Kategori</label>
                <select id="kategori" name="kategori" required>
                    <option value="Ana Yemekler" <?php echo $tarif['kategori'] === 'Ana Yemekler' ? 'selected' : ''; ?>>
                        Ana Yemekler</option>
                    <option value="Tatlılar" <?php echo $tarif['kategori'] === 'Tatlılar' ? 'selected' : ''; ?>>Tatlılar
                    </option>
                    <option value="Hamur İşleri" <?php echo $tarif['kategori'] === 'Hamur İşleri' ? 'selected' : ''; ?>>
                        Hamur İşleri</option>
                    <option value="İçecekler" <?php echo $tarif['kategori'] === 'İçecekler' ? 'selected' : ''; ?>>
                        İçecekler</option>
                    <option value="Kahvaltılıklar" <?php echo $tarif['kategori'] === 'Kahvaltılıklar' ? 'selected' : ''; ?>>Kahvaltılıklar</option>
                </select>
            </div>
            <div class="form-group">
                <label for="resim">Resim</label>
                <input type="file" id="resim" name="resim">
                <div class="current-image">
                    <p>Mevcut Resim:</p>
                    <img src="<?php echo htmlspecialchars($tarif['gorsel_url']); ?>" alt="Mevcut Resim">
                </div>
            </div>
            <div class="buttons">
                <a href="profil.php" class="btn cancel">İptal / Geri Dön</a>
                <button type="submit" class="btn">Güncelle</button>
            </div>
        </form>
    </div>
</body>

</html>