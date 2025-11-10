<?php
require_once '../config/functions.php'; // panggil koneksi + fungsi

$message = '';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Cek apakah token valid dan masih ada di database
    $stmt = $conn->prepare("SELECT * FROM users WHERE activation_token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if ($user['is_active'] == 1) {
            $message = "<p style='color:blue;'>Akun sudah aktif sebelumnya. Silakan login.</p>";
        } else {
            // Update status menjadi aktif
            $update = $conn->prepare("UPDATE users SET is_active = 1, activation_token = NULL WHERE id = ?");
            $update->execute([$user['id']]);
            $message = "<p style='color:green;'>Akun berhasil diaktifkan! Sekarang Anda dapat login.</p>";
        }
    } else {
        $message = "<p style='color:red;'>Token tidak valid atau sudah digunakan.</p>";
    }
} else {
    $message = "<p style='color:red;'>Token tidak ditemukan.</p>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Aktivasi Akun</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #FBF8F3;
            padding: 40px;
            text-align: center;
        }
        .box {
            max-width: 500px;
            margin: auto;
            background: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        a {
            color: #1b5e20;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="box">
    <h2>Aktivasi Akun Admin Gudang</h2>
    <?php echo $message; ?>
    <p><a href="login.php">Ke halaman Login</a></p>
</div>
</body>
</html>
