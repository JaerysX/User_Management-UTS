<?php
// config/functions.php

require_once __DIR__ . '/config.php'; // koneksi database

// 🔐 Generate token unik
function generateToken($length = 32) {
    return bin2hex(random_bytes($length / 2));
}

// 📧 Fungsi simulasi kirim email
function sendMail($to, $subject, $message) {
    echo "<h4>Email ke: $to</h4>";
    echo "<strong>Subject:</strong> $subject<br>";
    echo "<strong>Message:</strong><br>$message<br><hr>";
}
