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
    <title>Profilim</title>
    <style>
        /* Sayfa düzeni ve stil */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7f6;
        }

        .navbar {
            background-color: #007BFF;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-size: 16px;
            transition: color 0.3s ease;
        }

        .navbar a:hover {
            color: #ffdd57;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .card {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
        }

        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .card-content {
            padding: 15px;
        }

        .card-content h3 {
            margin: 0 0 10px;
            color: #333;
        }

        .card-content h3 a {
            text-decoration: none;
            color: inherit;
        }

        .card-content h3 a:hover {
            color: #007BFF;
        }

        .card-content p {
            margin: 0 0 10px;
            color: #666;
        }

        .card-content .meta {
            font-size: 14px;
            color: #999;
        }

        .card-actions {
            display: flex;
            justify-content: space-between;
            padding: 10px 15px;
            border-top: 1px solid #eee;
            margin-top: auto;
        }

        .card-actions a {
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 5px;
            font-size: 14px;
            color: white;
            transition: background-color 0.3s ease;
        }

        .card-actions a.edit {
            background-color: #007BFF;
        }

        .card-actions a.edit:hover {
            background-color: #0056b3;
        }

        .card-actions a.delete {
            background-color: #FF4136;
        }

        .card-actions a.delete:hover {
            background-color: #c70000;
        }

        .no-tarif {
            text-align: center;
            font-size: 18px;
            color: #666;
            margin-top: 50px;
        }
    </style>
</head>

<body>
    <div class="navbar">
        <div class="logo">
            <a href="index.php">Yemek Tarifleri</a>
        </div>
        <div class="links">
            <a href="index.php">Ana Sayfa</a>
            <a href="profil.php">Profilim</a>
            <a href="tarif_ekle.php">Tarif Ekle</a>
            <a href="cikis.php">Çıkış Yap</a>
        </div>
    </div>

    <div class="container">
        <h1>Tariflerim</h1>
        <?php
        require_once 'baglanti.php';

        // Kullanıcının tariflerini çek
        $stmt = $pdo->prepare("SELECT * FROM tarifler WHERE yazar_id = :yazar_id ORDER BY eklenme_tarihi DESC");
        $stmt->bindParam(':yazar_id', $_SESSION['kullanici_id'], PDO::PARAM_INT);
        $stmt->execute();
        $tarifler = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($tarifler) > 0): ?>
            <div class="cards">
                <?php foreach ($tarifler as $tarif): ?>
                    <div class="card">
                        <a href="tarif_detay.php?id=<?php echo $tarif['id']; ?>">
                            <img src="<?php echo htmlspecialchars($tarif['gorsel_url']); ?>"
                                alt="<?php echo htmlspecialchars($tarif['baslik']); ?>">
                        </a>
                        <div class="card-content">
                            <h3><a
                                    href="tarif_detay.php?id=<?php echo $tarif['id']; ?>"><?php echo htmlspecialchars($tarif['baslik']); ?></a>
                            </h3>
                            <p><?php echo nl2br(htmlspecialchars($tarif['icerik'])); ?></p>
                            <p class="meta">Eklenme Tarihi: <?php echo htmlspecialchars($tarif['eklenme_tarihi']); ?></p>
                        </div>
                        <div class="card-actions">
                            <a href="duzenle.php?id=<?php echo $tarif['id']; ?>" class="edit">Düzenle</a>
                            <a href="sil.php?id=<?php echo $tarif['id']; ?>" class="delete">Sil</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="no-tarif">Henüz hiç tarif paylaşmadınız.</p>
        <?php endif; ?>
    </div>
</body>

</html>