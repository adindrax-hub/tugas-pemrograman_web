<?php
$host = "localhost";
// Username database dari hosting kelas Anda
$user = "ifummiid_kelasc"; 
// Masukkan password database dari hosting kelas Anda di bawah ini
$pass = "pemweb_db_c"; 
// Nama database harus persis dengan yang ada di gambar kiri atas
$db   = "ifummiid_kelasc"; 

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}
?>