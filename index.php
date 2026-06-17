<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Utama</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-dark text-light">

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Data Manajemen</h2>
            <div>
                <button type="button" class="btn btn-success" id="btnTambahBaru" data-bs-toggle="modal" data-bs-target="#crudModal">
                    + Tambah Data Baru
                </button>
                <a href="login.php" class="btn btn-outline-danger ms-2">Logout</a>
            </div>
        </div>

        <div class="card bg-secondary bg-opacity-25 border-secondary mb-5">
            <div class="card-body">
                <table id="dataTable" class="table table-dark table-striped table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Dokumen Terlampir</th>
                            <th>Status Tanda Tangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        // Query mengambil data induk dan menggabungkan nama_file dokumen
                        $sql = "SELECT md.*, GROUP_CONCAT(dp.nama_file SEPARATOR '|') as daftar_file 
                                FROM manajemen_data md 
                                LEFT JOIN dokumen_pendukung dp ON md.id = dp.manajemen_data_id 
                                GROUP BY md.id ORDER BY md.id DESC";
                        $result = $conn->query($sql);
                        
                        while ($row = $result->fetch_assoc()) :
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($row['nama_lengkap']); ?></td>
                            
                            <td>
                                <?php
                                if (!empty($row['daftar_file'])) {
                                    $files = explode('|', $row['daftar_file']);
                                    
                                    foreach ($files as $file) {
                                        // Membersihkan nama file dari angka acak di depannya agar tampil rapi
                                        $nama_tampil = preg_replace('/^[0-9]+_/', '', $file);
                                        
                                        echo '<a href="uploads/' . htmlspecialchars($file) . '" target="_blank" 
                                              class="badge bg-info text-dark text-decoration-none mb-1 d-block text-truncate" 
                                              style="max-width: 250px;" title="' . htmlspecialchars($nama_tampil) . '">';
                                        echo '📄 ' . htmlspecialchars($nama_tampil);
                                        echo '</a>';
                                    }
                                } else {
                                    echo '<span class="text-muted small">Tidak ada dokumen</span>';
                                }
                                ?>
                            </td>

                            <td>
                                <span class="badge <?= $row['status_tanda_tangan'] == 'Signed' ? 'bg-success' : 'bg-warning' ?>">
                                    <?= $row['status_tanda_tangan']; ?>
                                </span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-warning btn-edit" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#crudModal"
                                        data-id="<?= $row['id']; ?>"
                                        data-nama="<?= htmlspecialchars($row['nama_lengkap']); ?>">
                                    Edit
                                </button>
                                <a href="proses.php?aksi=hapus&id=<?= $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data ini beserta seluruh filenya?')">Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="crudModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-dark text-light border-secondary">
                <form id="dataForm" action="proses.php?aksi=tambah" method="POST" enctype="multipart/form-data">
                    <div class="modal-header border-secondary">
                        <h5 class="modal-title" id="modalLabel">Form Input Data</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="idData">

                        <div class="mb-3">
                            <label for="namaLengkap" class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control" id="namaLengkap" placeholder="Masukkan nama lengkap" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="uploadDokumen" class="form-label">Upload Dokumen Pendukung (Multiple)</label>
                            <input class="form-control" type="file" name="upload_dokumen[]" id="uploadDokumen" multiple>
                            <small class="text-muted" id="fileHelp">Tahan tombol CTRL untuk memilih lebih dari satu file.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanda Tangan</label>
                            <div class="signature-wrapper">
                                <canvas id="signatureCanvas"></canvas>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger mt-2" id="clearSignature">Hapus Tanda Tangan</button>
                            <small class="text-muted d-block mt-1" id="sigHelp">*Wajib mengisi tanda tangan.</small>
                            
                            <input type="hidden" name="tanda_tangan_base64" id="tandaTanganBase64">
                        </div>
                    </div>
                    <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary" id="saveData">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>

    <script src="script.js"></script>
</body>
</html>