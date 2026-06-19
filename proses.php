<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include 'koneksi.php';

$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : '';
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

    $sql = "INSERT INTO manajemen_data_adindra_2430511048 (nama_lengkap, tanda_tangan, status_tanda_tangan) 
            VALUES ('$nama_lengkap', '$tanda_tangan', '$status_ttd')";
    
    if ($conn->query($sql)) {
        $id_induk = $conn->insert_id;

        if (!empty($_FILES['upload_dokumen']['name'][0])) {
            $files = $_FILES['upload_dokumen'];
            foreach ($files['name'] as $key => $nama_file) {
                $tmp_name = $files['tmp_name'][$key];
                $error    = $files['error'][$key];

                if ($error === 0) {
                    $nama_file_baru = time() . '_' . preg_replace("/[^a-zA-Z0-9.\-_]/", "_", basename($nama_file));
                    $target_file    = $target_main_dir . $nama_file_baru;

                    if (move_uploaded_file($tmp_name, $target_file)) {
                        $sql_file = "INSERT INTO dokumen_pendukung_adindra_2430511048 (manajemen_data_id, nama_file) 
                                     VALUES ($id_induk, '$nama_file_baru')";
                        $conn->query($sql_file);
                    }
                }
            }
        }
        header("Location: index.php");
        exit();
    } else {
        die("Gagal menyimpan data Induk: " . $conn->error);
    }

// ==========================================
// PROSES EDIT / UPDATE DATA
// ==========================================
} elseif ($aksi == 'edit') {
    $id           = intval($_POST['id']);
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $tanda_tangan = $_POST['tanda_tangan_base64'];

    $sql_update = "UPDATE manajemen_data_adindra_2430511048 SET nama_lengkap = '$nama_lengkap' WHERE id = $id";
    $conn->query($sql_update);

    if (!empty($tanda_tangan)) {
        $sql_ttd = "UPDATE manajemen_data_adindra_2430511048 SET tanda_tangan = '$tanda_tangan', status_tanda_tangan = 'Signed' WHERE id = $id";
        $conn->query($sql_ttd);
    }

    if (!empty($_FILES['upload_dokumen']['name'][0])) {
        $files = $_FILES['upload_dokumen'];
        foreach ($files['name'] as $key => $nama_file) {
            $tmp_name = $files['tmp_name'][$key];
            $error    = $files['error'][$key];

            if ($error === 0) {
                $nama_file_baru = time() . '_' . preg_replace("/[^a-zA-Z0-9.\-_]/", "_", basename($nama_file));
                $target_file    = $target_main_dir . $nama_file_baru;

                if (move_uploaded_file($tmp_name, $target_file)) {
                    $conn->query("INSERT INTO dokumen_pendukung_adindra_2430511048 (manajemen_data_id, nama_file) 
                                  VALUES ($id, '$nama_file_baru')");
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

    $res = $conn->query("SELECT nama_file FROM dokumen_pendukung_adindra_2430511048 WHERE manajemen_data_id = $id");
    while($file = $res->fetch_assoc()) {
        $path_file = $target_main_dir . $file['nama_file'];
        if(file_exists($path_file)) {
            unlink($path_file); 
        }
    }

    $sql_hapus = "DELETE FROM manajemen_data_adindra_2430511048 WHERE id = $id";
    if ($conn->query($sql_hapus)) {
        header("Location: index.php");
        exit();
    } else {
        die("Gagal menghapus data: " . $conn->error);
    }
}
?> 