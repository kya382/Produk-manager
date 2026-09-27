<?php
require_once '../config/db.php';

$errors = [];
$name = '';
$category = '';
$price = '';
$stock = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Normalisasi Input
    $name = trim($_POST['name'] ?? '');
    $category = trim($_POST['category'] ?? 'Umum');
    $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
    $stock = filter_input(INPUT_POST, 'stock', FILTER_VALIDATE_INT);

    // Validasi Aturan Bisnis Server-Side
    if (mb_strlen($name) < 3) {
        $errors[] = "Nama barang minimal 3 karakter.";
    }
    if ($price === false || $price <= 0) {
        $errors[] = "Harga harus berupa angka lebih dari 0.";
    }
    if ($stock === false || $stock < 0) {
        $errors[] = "Stok tidak boleh bernilai negatif.";
    }

    // Validasi Cek Nama Unik
    if ($name !== '') {
        $stmtCek = $pdo->prepare("SELECT COUNT(*) FROM products WHERE name = :name");
        $stmtCek->execute(['name' => $name]);
        if ($stmtCek->fetchColumn() > 0) {
            $errors[] = "Nama barang antik ini sudah terdaftar!";
        }
    }

    // Jika lolos validasi -> simpan data & Redirect PRG
    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO products (name, category, price, stock) VALUES (:name, :category, :price, :stock)");
        $stmt->execute([
            'name' => $name,
            'category' => $category,
            'price' => $price,
            'stock' => $stock
        ]);

        header("Location: index.php?status=created");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Koleksi Antik</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container" style="max-width: 500px;">
        <h2>+ Tambah Barang Antik</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $e): ?>
                    <p style="margin: 3px 0;"><?= htmlspecialchars($e) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="create.php">
            <div style="margin-bottom:10px;">
                <label>Nama Barang:</label><br>
                <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" style="width:100%; padding:8px;" required>
            </div>
            
            <div style="margin-bottom:10px;">
                <label>Kategori:</label><br>
                <input type="text" name="category" value="<?= htmlspecialchars($category) ?>" style="width:100%; padding:8px;" required>
            </div>

            <div style="margin-bottom:10px;">
                <label>Harga (Rp):</label><br>
                <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($price) ?>" style="width:100%; padding:8px;" required>
            </div>

            <div style="margin-bottom:15px;">
                <label>Stok:</label><br>
                <input type="number" name="stock" value="<?= htmlspecialchars($stock) ?>" style="width:100%; padding:8px;" required>
            </div>

            <button type="submit" class="btn">Simpan Barang</button>
            <a href="index.php" class="btn btn-danger">Batal</a>
        </form>
    </div>
</body>
</html>