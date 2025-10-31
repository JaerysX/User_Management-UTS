<?php
session_start();
require_once '../config/functions.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $stock = (int)$_POST['stock'];
    $price = (float)$_POST['price'];

    if (!empty($name)) {
        $stmt = $conn->prepare("INSERT INTO products (name, stock, price) VALUES (?, ?, ?)");
        $stmt->execute([$name, $stock, $price]);
        header("Location: dashboard.php");
        exit;
    } else {
        $message = "<p style='color:red;'>Nama produk wajib diisi!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Produk</title>
    <style>
        body { background: #FBF8F3; font-family: Arial; padding: 40px; }
        .container { max-width: 400px; background: #fff; padding: 20px; border-radius: 10px; margin: auto; }
        input { width: 100%; padding: 8px; margin: 5px 0; border: 1px solid #ccc; border-radius: 6px; }
        button { background: #1b5e20; color: white; border: none; padding: 10px; width: 100%; border-radius: 6px; }
        button:hover { background: #2e7d32; }
        a { text-decoration: none; color: #1b5e20; }
    </style>
</head>
<body>
<div class="container">
    <h2>Tambah Produk</h2>
    <?= $message ?>
    <form method="POST">
        <label>Nama Produk</label>
        <input type="text" name="name" required>

        <label>Stok</label>
        <input type="number" name="stock" value="0" required>

        <label>Harga</label>
        <input type="number" name="price" step="0.01" value="0" required>

        <button type="submit">Simpan</button>
    </form>
    <p><a href="dashboard.php">← Kembali ke Dashboard</a></p>
</div>
</body>
</html>