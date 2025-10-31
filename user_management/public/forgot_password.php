<?php
require_once '../config/functions.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    // Cek apakah email terdaftar dan aktif
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND is_active = 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Buat token reset
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Simpan ke database
        $update = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
        $update->execute([$token, $expires, $email]);

        // Simulasi link reset (seharusnya dikirim via email)
        $reset_link = "http://localhost/user_management/public/reset_password.php?token=$token";
        $message = "<p style='color:green;'>Tautan reset password telah dikirim ke email Anda.</p>
                    <p><strong>Simulasi link:</strong><br><a href='$reset_link'>$reset_link</a></p>";
    } else {
        $message = "<p style='color:red;'>Email tidak ditemukan atau akun belum aktif.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lupa Password</title>
    <style>
        body { font-family: Arial, sans-serif; background: #FBF8F3; padding: 40px; }
        .box { max-width: 450px; margin: auto; background: #fff; padding: 30px;
               border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input, button { width: 100%; padding: 10px; margin: 8px 0; }
        button { background: #2e7d32; color: #fff; border: none; border-radius: 5px; }
        button:hover { background: #1b5e20; }
        a { text-decoration: none; color: #2e7d32; }
    </style>
</head>
<body>
<div class="box">
    <h2>Lupa Password</h2>
    <?php echo $message; ?>
    <form method="POST">
        <label>Email</label>
        <input type="email" name="email" required>
        <button type="submit">Kirim Tautan Reset</button>
    </form>
    <p><a href="login.php">Kembali ke Login</a></p>
</div>
</body>
</html>
