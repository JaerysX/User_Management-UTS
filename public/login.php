<?php
session_start();
require_once '../config/functions.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Cek apakah email ada di database
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if ($user['is_active'] == 0) {
            $message = "<p style='color:red;'>Akun Anda belum aktif. Silakan periksa email untuk aktivasi.</p>";
        } elseif (password_verify($password, $user['password'])) {
            // Simpan session login
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];

            // Arahkan ke dashboard
            header("Location: dashboard.php");
            exit;
        } else {
            $message = "<p style='color:red;'>Password salah!</p>";
        }
    } else {
        $message = "<p style='color:red;'>Email tidak ditemukan!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin Gudang</title>
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
        input[type="email"], input[type="password"] {
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
    <h2>Login Admin Gudang</h2>

    <?php if (!empty($message)) echo $message; ?>

    <form method="POST" action="">
        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
    </form>

    <p>Lupa password? <a href="forgot_password.php">Reset di sini</a></p>
    <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
</div>
</body>
</html>
