<?php
session_start();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = $_GET['id'];

require_once 'baglanti.php';

$stmt = $pdo->prepare("SELECT tarifler.*, kullanicilar.kullanici_adi FROM tarifler INNER JOIN kullanicilar ON tarifler.yazar_id = kullanicilar.id WHERE tarifler.id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$tarif = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$tarif) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($tarif['baslik']); ?> - Tarif Detayı</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7f6;
        }

        /* NAVBAR - değiştirilmedi */
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

        /* SAYFA */
        .page {
            max-width: 800px;
            margin: 32px auto 48px;
            padding: 0 16px;
        }

        /* KART */
        .card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.09);
            overflow: hidden;
        }

        /* Resim */
        .card-image {
            width: 100%;
            height: 320px;
            object-fit: cover;
            display: block;
        }

        /* Başlık */
        .card-title-area {
            padding: 20px 24px 16px;
            border-bottom: 1px solid #f0f0f0;
        }

        .badge {
            display: inline-block;
            padding: 3px 12px;
            background: #fff3cd;
            color: #7a5c00;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .card-title-area h1 {
            margin: 10px 0 0;
            font-size: 22px;
            font-weight: 700;
            color: #222;
            line-height: 1.3;
        }

        /* Yazar */
        .card-author {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 24px;
            border-bottom: 1px solid #f0f0f0;
        }

        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #007BFF;
            color: #fff;
            font-weight: 700;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .author-name {
            font-size: 14px;
            font-weight: 600;
            color: #333;
        }

        .post-date {
            font-size: 12px;
            color: #999;
        }

        /* İçerik */
        .card-body {
            padding: 24px 24px 28px;
        }

        .content-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #007BFF;
            margin: 0 0 12px;
        }

        .content-text {
            font-size: 15px;
            line-height: 1.85;
            color: #444;
        }

        /* Footer */
        .card-footer {
            padding: 0 24px 24px;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 15px;
            transition: background-color 0.2s ease;
        }

        .back-button:hover {
            background-color: #0056b3;
        }

        .back-button svg {
            width: 16px;
            height: 16px;
        }
    </style>
</head>

<body>

    <div class="navbar">
        <div class="logo">
            <a href="index.php">Yemek Tarifleri</a>
        </div>
        <div class="links">
            <?php if (isset($_SESSION['kullanici_id'])): ?>
                <a href="index.php">Ana Sayfa</a>
                <a href="profil.php">Profilim</a>
                <a href="tarif_ekle.php">Tarif Ekle</a>
                <a href="cikis.php">Çıkış Yap</a>
            <?php else: ?>
                <a href="index.php">Ana Sayfa</a>
                <a href="giris.php">Giriş Yap</a>
                <a href="kayit.php">Kayıt Ol</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="page">
        <div class="card">

            <img src="<?php echo htmlspecialchars($tarif['gorsel_url']); ?>"
                alt="<?php echo htmlspecialchars($tarif['baslik']); ?>" class="card-image">

            <div class="card-title-area">
                <span class="badge"><?php echo htmlspecialchars($tarif['kategori']); ?></span>
                <h1><?php echo htmlspecialchars($tarif['baslik']); ?></h1>
            </div>

            <div class="card-author">
                <div class="avatar">
                    <?php echo mb_strtoupper(mb_substr($tarif['kullanici_adi'], 0, 1, 'UTF-8'), 'UTF-8'); ?>
                </div>
                <div>
                    <div class="author-name"><?php echo htmlspecialchars($tarif['kullanici_adi']); ?></div>
                    <div class="post-date"><?php echo htmlspecialchars($tarif['eklenme_tarihi']); ?></div>
                </div>
            </div>

            <div class="card-body">
                <p class="content-label">Tarif</p>
                <div class="content-text">
                    <?php echo nl2br(htmlspecialchars($tarif['icerik'])); ?>
                </div>
            </div>

            <div class="card-footer">
                <a href="index.php" class="back-button">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <polyline points="15 18 9 12 15 6" />
                    </svg>
                    Geri Dön
                </a>
            </div>

        </div>
    </div>

</body>

</html>