<?php
require_once '../config/db.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: index.php");
    exit;
}

// Ambil data lama
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
$stmt->execute(['id' => $id]);
$product = $stmt->fetch();

if (!$product) {
    die("Barang antik tidak ditemukan!");
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
    $stock = filter_input(INPUT_POST, 'stock', FILTER_VALIDATE_INT);

    if (mb_strlen($name) < 3) $errors[] = "Nama barang minimal 3 karakter.";
    if ($price === false || $price <= 0) $errors[] = "Harga harus lebih dari 0.";
    if ($stock === false || $stock < 0) $errors[] = "Stok tidak boleh negatif.";

    // Cek Unik Nama (Kecuali milik sendiri)
    if ($name !== '') {
        $stmtCek = $pdo->prepare("SELECT COUNT(*) FROM products WHERE name = :name AND id != :id");
        $stmtCek->execute(['name' => $name, 'id' => $id]);
        if ($stmtCek->fetchColumn() > 0) {
            $errors[] = "Nama barang sudah dipakai oleh koleksi lain!";
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE products SET name=:name, category=:category, price=:price, stock=:stock WHERE id=:id");
        $stmt->execute([
            'name' => $name,
            'category' => $category,
            'price' => $price,
            'stock' => $stock,
            'id' => $id
        ]);

        header("Location: index.php?status=updated");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Barang Antik</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container" style="max-width: 500px;">
        <h2>Edit Data Barang Antik</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $e): ?>
                    <p style="margin: 3px 0;"><?= htmlspecialchars($e) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div style="margin-bottom:10px;">
                <label>Nama Barang:</label><br>
                <input type="text" name="name" value="<?= htmlspecialchars($_POST['name'] ?? $product['name']) ?>" style="width:100%; padding:8px;" required>
            </div>

            <div style="margin-bottom:10px;">
                <label>Kategori:</label><br>
                <input type="text" name="category" value="<?= htmlspecialchars($_POST['category'] ?? $product['category']) ?>" style="width:100%; padding:8px;" required>
            </div>

            <div style="margin-bottom:10px;">
                <label>Harga (Rp):</label><br>
                <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($_POST['price'] ?? $product['price']) ?>" style="width:100%; padding:8px;" required>
            </div>

            <div style="margin-bottom:15px;">
                <label>Stok:</label><br>
                <input type="number" name="stock" value="<?= htmlspecialchars($_POST['stock'] ?? $product['stock']) ?>" style="width:100%; padding:8px;" required>
            </div>

            <button type="submit" class="btn">Update Data</button>
            <a href="index.php" class="btn btn-danger">Batal</a>
        </form>
    </div>
</body>
</html>