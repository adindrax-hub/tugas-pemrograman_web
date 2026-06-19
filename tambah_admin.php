<?php
include 'koneksi.php';

$username_baru = "admin1";
$password_baru = "admin456";

$password_hash = password_hash($password_baru, PASSWORD_DEFAULT);

// Nama tabel SUDAH DIPERBAIKI di bawah ini
$sql = "INSERT INTO users_adindra_2430511048 (username, password) VALUES ('$username_baru', '$password_hash')";

if ($conn->query($sql)) {
    echo "<h1>Berhasil!</h1>";
    echo "Akun dengan Username: <b>$username_baru</b> berhasil ditambahkan.<br>";
    echo "Silakan kembali ke halaman <a href='login.php'>Login</a>.";
} else {
    echo "Gagal menambahkan akun: " . $conn->error;
}
?>