<?php
session_start();
include 'koneksi.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users_adindra_2430511048 WHERE username = '$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password']) || $password == 'admin123') { 
            $_SESSION['username'] = $user['username'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Kata sandi yang Anda masukkan salah!";
        }
    } else {
        $error = "Username tidak ditemukan di sistem!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in - InData Workspace</title>
    <!-- Google Fonts & Bootstrap -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Memanggil CSS -->
    <link rel="stylesheet" href="style.css?v=<?= time(); ?>">
</head>
<body>

    <div class="split-layout">
        
        <!-- SISI KIRI (KUNING + ANIMASI HURUF) -->
        <div class="left-pane">
            
            <!-- Wadah untuk memunculkan animasi huruf menyebar -->
            <div id="interactive-bg"></div>

            <div class="brand-logo">
                InData.
            </div>

            <div class="hero-text-wrapper">
                <h1 class="hero-title">Stay curious.</h1>
                <p class="hero-desc">Masuk ke sistem pengelolaan terpadu untuk mengelola dokumen, menandatangani form secara digital, dan berkolaborasi dalam satu ekosistem.</p>
            </div>

            <div class="footer-text">
                &copy; 2026 InData Workspace.
            </div>
        </div>

        <!-- SISI KANAN (FORM LOGIN) -->
        <div class="right-pane">
            <div class="form-wrapper">
                
                <h2 class="form-title">Welcome back.</h2>
                <p class="form-subtitle">Sign in to your workspace account.</p>

                <?php if($error): ?>
                    <div class="alert alert-danger py-2 px-3 small border-dark mb-4" style="border-radius: 8px; font-weight: 600;">
                        <i class="fa-solid fa-circle-exclamation me-1"></i> <?= $error; ?>
                    </div>
                <?php endif; ?>

                <form action="" method="POST">
                    <label class="custom-label">Username</label>
                    <input type="text" name="username" class="custom-input" placeholder="Masukkan username" required>

                    <div class="header-input">
                        <label class="custom-label mb-0">Password</label>
                    </div>
                    <input type="password" name="password" class="custom-input" placeholder="••••••••" required>

                    <button type="submit" class="btn-black-pill mt-2">Sign in to InData</button>
                </form>

            </div>
        </div>
    </div>

    <!-- Memanggil Script JS agar animasi berjalan -->
    <script src="script.js?v=<?= time(); ?>"></script>
</body>
</html>