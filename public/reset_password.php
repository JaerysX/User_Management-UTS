<?php
require_once '../config/functions.php';
$message = '';
$valid = false;

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Cek token di database
    $stmt = $conn->prepare("SELECT * FROM users WHERE reset_token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && strtotime($user['reset_expires']) > time()) {
        $valid = true;

        // Jika form dikirim
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            // Update password & hapus token
            $update = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
            $update->execute([$password, $user['id']]);

            $message = "<p style='color:green;'>Password berhasil diperbarui! Silakan <a href='login.php'>login</a>.</p>";
            $valid = false; // jangan tampilkan form lagi
        }
    } else {
        $message = "<p style='color:red;'>Token tidak valid atau sudah kedaluwarsa.</p>";
    }
} else {
    $message = "<p style='color:red;'>Token tidak ditemukan.</p>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <style>
        body { font-family: Arial, sans-serif; background: #FBF8F3; padding: 40px; }
        .box { max-width: 450px; margin: auto; background: #fff; padding: 30px;
               border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input, button { width: 100%; padding: 10px; margin: 8px 0; }
        button { background: #2e7d32; color: #fff; border: none; border-radius: 5px; }
        button:hover { background: #1b5e20; }
    </style>
</head>
<body>
<div class="box">
    <h2>Reset Password</h2>
    <?php echo $message; ?>

    <?php if ($valid): ?>
    <form method="POST">
        <label>Password Baru</label>
        <input type="password" name="password" required>
        <button type="submit">Perbarui Password</button>
    </form>
    <?php endif; ?>
</div>
</body>
</html>
