<?php
require_once '../config/functions.php'; // memanggil koneksi + fungsi

$message = ''; // untuk menampung pesan sukses/gagal

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validasi sederhana
    if (empty($name) || empty($email) || empty($password)) {
        $message = "<p style='color:red;'>Semua field wajib diisi.</p>";
    } else {
        // Cek apakah email sudah terdaftar
        $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $check->execute([$email]);

        if ($check->rowCount() > 0) {
            $message = "<p style='color:red;'>Email sudah terdaftar!</p>";
        } else {
            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $token = generateToken(32);

            // Simpan ke database
            $insert = $conn->prepare("INSERT INTO users (name, email, password, activation_token, is_active) VALUES (?, ?, ?, ?, 0)");
            $insert->execute([$name, $email, $hashedPassword, $token]);

            // Buat tautan aktivasi (simulasi email)
            $activationLink = "http://localhost/user_management/public/activate.php?token=$token";
            $subject = "Aktivasi Akun Admin Gudang";
            $body = "
                <h3>Halo, $name!</h3>
                <p>Terima kasih telah mendaftar sebagai <strong>Admin Gudang</strong>.</p>
                <p>Klik tautan berikut untuk mengaktifkan akunmu:</p>
                <a href='$activationLink'>$activationLink</a>
            ";

            sendMail($email, $subject, $body);

            $message = "<p style='color:green;'>Registrasi berhasil! Periksa email Anda untuk aktivasi akun (ditampilkan di bawah ini sebagai simulasi).</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Pengguna - Admin Gudang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #FBF8F3;
            padding: 40px;
        }
        .container {
            max-width: 400px;
            background: #fff;
            border-radius: 10px;
            padding: 25px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        button {
            background: #1b5e20;
            color: #fff;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 8px;
            cursor: pointer;
        }
        button:hover {
            background: #2e7d32;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Registrasi Admin Gudang</h2>

    <?php if (!empty($message)) echo $message; ?>

    <form method="POST" action="">
        <label>Nama Lengkap:</label>
        <input type="text" name="name" required>

        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <button type="submit">Daftar</button>
    </form>

    <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
</div>
</body>
</html>
