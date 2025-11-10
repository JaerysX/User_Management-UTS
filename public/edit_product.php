<?php
session_start();
require_once '../config/functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? 0;

// Ambil data produk
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("Produk tidak ditemukan!");
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $stock = (int)$_POST['stock'];
    $price = (float)$_POST['price'];

    if (!empty($name)) {
        $stmt = $conn->prepare("UPDATE products SET name = ?, stock = ?, price = ? WHERE id = ?");
        $stmt->execute([$name, $stock, $price, $id]);
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
    <title>Edit Produk</title>
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
    <h2>Edit Produk</h2>
    <?= $message ?>
    <form method="POST">
        <label>Nama Produk</label>
        <input type="text" name="name" value="<?= htmlspecialchars($product['name']); ?>" required>

        <label>Stok</label>
        <input type="number" name="stock" value="<?= $product['stock']; ?>" required>

        <label>Harga</label>
        <input type="number" name="price" step="0.01" value="<?= $product['price']; ?>" required>

        <button type="submit">Update</button>
    </form>
    <p><a href="dashboard.php">← Kembali ke Dashboard</a></p>
</div>
</body>
</html>