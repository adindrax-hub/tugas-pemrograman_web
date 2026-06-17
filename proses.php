<?php
// 1. Mengaktifkan pelacak error untuk memudahkan debugging jika ada kendala
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include 'koneksi.php';

$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : '';

// Pastikan folder uploads tersedia secara otomatis di server
$target_main_dir = "uploads/";
if (!is_dir($target_main_dir)) {
    mkdir($target_main_dir, 0777, true); 
}

// ==========================================
// PROSES TAMBAH DATA BARU
// ==========================================
if ($aksi == 'tambah') {
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $tanda_tangan = $_POST['tanda_tangan_base64'];
    $status_ttd   = !empty($tanda_tangan) ? 'Signed' : 'Unsigned';

    // Simpan data induk terlebih dahulu
    $sql = "INSERT INTO manajemen_data (nama_lengkap, tanda_tangan, status_tanda_tangan) 
            VALUES ('$nama_lengkap', '$tanda_tangan', '$status_ttd')";
    
    if ($conn->query($sql)) {
        $id_induk = $conn->insert_id; // Mengambil ID dari data yang baru saja masuk

        // Proses Multi-Upload Dokumen
        if (!empty($_FILES['upload_dokumen']['name'][0])) {
            $files = $_FILES['upload_dokumen'];

            // Looping berdasarkan jumlah file yang dipilih user
            foreach ($files['name'] as $key => $nama_file) {
                $tmp_name = $files['tmp_name'][$key];
                $error    = $files['error'][$key];

                if ($error === 0) {
                    // Membuat nama file unik (Contoh: 171829102_dokumen.jpg)
                    $nama_file_baru = time() . '_' . preg_replace("/[^a-zA-Z0-9.\-_]/", "_", basename($nama_file));
                    $target_file    = $target_main_dir . $nama_file_baru;

                    // Pindahkan file dari folder sementara server ke folder proyek
                    if (move_uploaded_file($tmp_name, $target_file)) {
                        // Jika berhasil pindah, catat nama filenya ke database dokumen_pendukung
                        $sql_file = "INSERT INTO dokumen_pendukung (manajemen_data_id, nama_file) VALUES ($id_induk, '$nama_file_baru')";
                        if (!$conn->query($sql_file)) {
                            echo "Gagal mencatat berkas $nama_file ke database: " . $conn->error . "<br>";
                        }
                    } else {
                        echo "Gagal memindahkan file fisik: $nama_file ke folder uploads/. Periksa izin folder Anda.<br>";
                    }
                } else {
                    echo "Terjadi error sistem saat upload file kode: " . $error . "<br>";
                }
            }
        }
        
        // Jika tidak ada error yang terhenti di atas, kembalikan ke dashboard
        header("Location: index.php");
        exit();
    } else {
        // Menampilkan error jika query utama ditolak oleh MariaDB
        die("Gagal menyimpan data induk ke database! Pesan Error: " . $conn->error);
    }

// ==========================================
// PROSES EDIT / UPDATE DATA
// ==========================================
} elseif ($aksi == 'edit') {
    $id           = intval($_POST['id']);
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $tanda_tangan = $_POST['tanda_tangan_base64'];

    // 1. Update nama lengkap
    $sql_update = "UPDATE manajemen_data SET nama_lengkap = '$nama_lengkap' WHERE id = $id";
    if (!$conn->query($sql_update)) {
        die("Gagal memperbarui nama: " . $conn->error);
    }

    // 2. Update tanda tangan jika canvas diisi (tidak kosong)
    if (!empty($tanda_tangan)) {
        $sql_ttd = "UPDATE manajemen_data SET tanda_tangan = '$tanda_tangan', status_tanda_tangan = 'Signed' WHERE id = $id";
        $conn->query($sql_ttd);
    }

    // 3. Tambahkan dokumen baru jika ada yang di-upload saat edit
    if (!empty($_FILES['upload_dokumen']['name'][0])) {
        $files = $_FILES['upload_dokumen'];

        foreach ($files['name'] as $key => $nama_file) {
            $tmp_name = $files['tmp_name'][$key];
            $error    = $files['error'][$key];

            if ($error === 0) {
                $nama_file_baru = time() . '_' . preg_replace("/[^a-zA-Z0-9.\-_]/", "_", basename($nama_file));
                $target_file    = $target_main_dir . $nama_file_baru;

                if (move_uploaded_file($tmp_name, $target_file)) {
                    $conn->query("INSERT INTO dokumen_pendukung (manajemen_data_id, nama_file) VALUES ($id, '$nama_file_baru')");
                }
            }
        }
    }
    header("Location: index.php");
    exit();

// ==========================================
// PROSES HAPUS DATA
// ==========================================
} elseif ($aksi == 'hapus') {
    $id = intval($_GET['id']);

    // Cari semua file terkait di database untuk dihapus dari folder fisik uploads/
    $res = $conn->query("SELECT nama_file FROM dokumen_pendukung WHERE manajemen_data_id = $id");
    while($file = $res->fetch_assoc()) {
        $path_file = $target_main_dir . $file['nama_file'];
        if(file_exists($path_file)) {
            unlink($path_file); // Menghapus file fisik
        }
    }

    // Hapus data dari tabel utama (Data di tabel dokumen otomatis terhapus karena relasi ON DELETE CASCADE)
    $sql_hapus = "DELETE FROM manajemen_data WHERE id = $id";
    if ($conn->query($sql_hapus)) {
        header("Location: index.php");
        exit();
    } else {
        die("Gagal menghapus data dari database: " . $conn->error);
    }
}
?>