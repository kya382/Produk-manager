<?php
session_start();
require_once '../config/db.php';

// Inisialisasi CSRF Token untuk hapus data
$_SESSION['csrf'] ??= bin2hex(random_bytes(32));

// Filter Search (GET Method)
$q = trim($_GET['q'] ?? '');
if ($q !== '') {
    // Karena :q dipakai 2 kali di SQL, kirim 2 parameter di execute()
    $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE :q1 OR category LIKE :q2 ORDER BY id DESC");
    $stmt->execute([
        'q1' => "%$q%",
        'q2' => "%$q%"
    ]);
} else {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
}
$products = $stmt->fetchAll();

// Pesan Notifikasi PRG
$status = $_GET['status'] ?? '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Galeri Barang Antik - Management</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>🏛️ Galeri Barang Antik</h1>
            <p>Sistem Kelola Inventaris Koleksi Klasik</p>
        </header>

        <?php if ($status === 'created'): ?>
            <div class="alert">Barang antik berhasil ditambahkan!</div>
        <?php elseif ($status === 'updated'): ?>
            <div class="alert">Data barang berhasil diperbarui!</div>
        <?php elseif ($status === 'deleted'): ?>
            <div class="alert">Barang telah dihapus dari sistem!</div>
        <?php endif; ?>

        <div style="margin-bottom: 15px;">
            <a href="create.php" class="btn">+ Tambah Koleksi Baru</a>
        </div>

        <!-- Form Pencarian (GET) -->
        <form method="GET" action="index.php" class="search-bar">
            <input type="text" name="q" placeholder="Cari nama barang atau kategori..." value="<?= htmlspecialchars($q) ?>">
            <button type="submit" class="btn">Cari</button>
        </form>

        <!-- Display Products Grid (Flexbox) -->
        <div class="products-grid">
            <?php if (empty($products)): ?>
                <p>Tidak ada koleksi barang antik yang ditemukan.</p>
            <?php else: ?>
                <?php foreach ($products as $p): ?>
                    <div class="card">
                        <div>
                            <!-- Keamanan Output dari XSS via htmlspecialchars -->
                            <h3><?= htmlspecialchars($p['name'], ENT_QUOTES, 'UTF-8') ?></h3>
                            <p><strong>Kategori:</strong> <?= htmlspecialchars($p['category'], ENT_QUOTES, 'UTF-8') ?></p>
                            <p><strong>Harga:</strong> Rp <?= number_format($p['price'], 0, ',', '.') ?></p>
                            <p><strong>Stok:</strong> <?= htmlspecialchars($p['stock']) ?> unit</p>
                        </div>
                        
                        <div class="actions">
                            <a href="edit.php?id=<?= $p['id'] ?>" class="btn" style="background:#0288d1;">Edit</a>
                            
                            <!-- Form Delete via POST + Proteksi CSRF -->
                            <form method="POST" action="delete.php" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini?');">
                                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>