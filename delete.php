<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $token = $_POST['csrf'] ?? '';

    // Verifikasi Token CSRF
    if (!hash_equals($_SESSION['csrf'] ?? '', $token)) {
        http_response_code(403);
        exit("403 Forbidden: Token CSRF tidak cocok!");
    }

    if ($id) {
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}

header("Location: index.php?status=deleted");
exit;