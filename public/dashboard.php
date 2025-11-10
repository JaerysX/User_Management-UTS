<?php
session_start();
require_once '../config/functions.php';

// Cek apakah sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Ambil data pengguna dari database
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin Gudang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #FBF8F3;
            margin: 0;
            padding: 0;
        }
        header {
            background: #1b5e20;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header h1 { font-size: 22px; }
        nav a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
        }
        .container {
            padding: 30px;
        }
        .card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 25px;
            margin-bottom: 20px;
        }
        button, a.btn {
            background: #1b5e20;
            color: #fff;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 6px;
        }
        a.btn:hover {
            background: #2e7d32;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background: #eee;
        }
    </style>
</head>
<body>

<header>
    <h1>Dashboard Admin Gudang</h1>
    <nav>
        <span>Halo, <strong><?= htmlspecialchars($user['name']); ?></strong></span>
        <a href="profile.php">Profil</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<div class="container">
    <div class="card">
        <h2>Data Produk</h2>
        <a class="btn" href="add_product.php">+ Tambah Produk</a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Produk</th>
                    <th>Stok</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $conn->query("SELECT * FROM products ORDER BY id DESC");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= htmlspecialchars($row['name']); ?></td>
                        <td><?= $row['stock']; ?></td>
                        <td>Rp <?= number_format($row['price'], 0, ',', '.'); ?></td>
                        <td>
                            <a class="btn" href="edit_product.php?id=<?= $row['id']; ?>">Edit</a>
                            <a class="btn" href="delete_product.php?id=<?= $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
