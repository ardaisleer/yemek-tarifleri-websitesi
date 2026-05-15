<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ana Sayfa</title>
    <style>
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

        .search-container {
            margin: 20px auto;
            text-align: center;
        }

        .search-container form {
            display: inline-block;
            width: 100%;
            max-width: 600px;
        }

        .search-container input[type="text"] {
            width: 80%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px 0 0 5px;
            font-size: 16px;
        }

        .search-container button {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
            font-size: 16px;
        }

        .search-container button:hover {
            background-color: #0056b3;
        }

        .category-menu {
            display: flex;
            justify-content: center;
            margin: 20px 0;
            gap: 10px;
        }

        .category-menu a {
            text-decoration: none;
            padding: 10px 15px;
            background-color: #007BFF;
            color: white;
            border-radius: 5px;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .category-menu a:hover {
            background-color: #0056b3;
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
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin: 0 0 10px;
            color: #666;
        }

        .card-content .meta {
            font-size: 14px;
            color: #999;
        }

        .card-content .badge {
            display: inline-block;
            padding: 5px 10px;
            background-color: #ffdd57;
            color: #333;
            border-radius: 5px;
            font-size: 12px;
            margin-top: 10px;
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

    <div class="search-container">
        <form action="index.php" method="GET">
            <input type="text" name="search" placeholder="Tariflerde ara..."
                value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit">Ara</button>
        </form>
    </div>

    <div class="category-menu">
        <a href="index.php">Tümü</a>
        <a href="index.php?kategori=Ana Yemekler">Ana Yemekler</a>
        <a href="index.php?kategori=Tatlılar">Tatlılar</a>
        <a href="index.php?kategori=Hamur İşleri">Hamur İşleri</a>
        <a href="index.php?kategori=İçecekler">İçecekler</a>
        <a href="index.php?kategori=Kahvaltılıklar">Kahvaltılıklar</a>
    </div>

    <div class="container">
        <h1>En Yeni Tarifler</h1>
        <?php
        require_once 'baglanti.php';

        $query = "SELECT tarifler.*, kullanicilar.kullanici_adi FROM tarifler INNER JOIN kullanicilar ON tarifler.yazar_id = kullanicilar.id";
        $params = [];

        if (isset($_GET['kategori']) && !empty(trim($_GET['kategori']))) {
            $kategori = trim($_GET['kategori']);
            $query .= " WHERE tarifler.kategori = :kategori";
            $params[':kategori'] = $kategori;
        }

        if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
            $search = '%' . trim($_GET['search']) . '%';
            if (isset($params[':kategori'])) {
                $query .= " AND (tarifler.baslik LIKE :search OR tarifler.icerik LIKE :search)";
            } else {
                $query .= " WHERE tarifler.baslik LIKE :search OR tarifler.icerik LIKE :search";
            }
            $params[':search'] = $search;
        }

        $query .= " ORDER BY tarifler.eklenme_tarihi DESC";
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
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
                            <p class="meta">Yazar: <?php echo htmlspecialchars($tarif['kullanici_adi']); ?></p>
                            <span class="badge">Kategori: <?php echo htmlspecialchars($tarif['kategori']); ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="no-tarif">Aradığınız kriterlere uygun tarif bulunamadı.</p>
        <?php endif; ?>
    </div>
</body>

</html>