<?php
session_start();
// Jika belum login, diarahkan ke halaman login
if (!isset($_SESSION['username'])) { 
    header("Location: login.php"); 
    exit(); 
}
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InData - Stay curious.</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css?v=<?= time(); ?>">
    <style>
        /* Tambahan style khusus Landing Page agar serasi dengan tema Medium Anda */
        .feature-card {
            background: #FFFFFF;
            border: 2px solid #191919;
            border-radius: 12px;
            box-shadow: 6px 6px 0px rgba(0,0,0,1);
            transition: all 0.2s ease;
        }
        .feature-card:hover {
            transform: translateY(-3px);
            box-shadow: 10px 10px 0px rgba(0,0,0,1);
        }
        .section-title {
            font-family: 'Playfair Display', serif;
            font-weight: 800;
            font-size: 2.5rem;
            color: #191919;
        }
    </style>
</head>
<body>

    <div class="app-container">
        
        <div class="navbar-top">
            <a href="#" class="navbar-brand">InData.</a>
            <div class="d-flex align-items-center">
                <span class="me-3 fw-bold d-none d-sm-inline"><i class="fa-solid fa-user-circle me-1"></i> <?= htmlspecialchars($_SESSION['username']); ?></span>
                <a href="#workspace-section" class="btn btn-sm btn-outline-dark rounded-pill fw-bold me-2 px-3 d-none d-md-inline">Dashboard</a>
                <a href="login.php" class="text-dark fw-bold text-decoration-none border-start ps-3"><i class="fa-solid fa-right-from-bracket"></i> Sign out</a>
            </div>
        </div>

        <div class="hero-section">
            <div class="hero-content">
                <h1 class="hero-title">Stay curious.</h1>
                <p class="hero-text">Tempat terbaik untuk mengelola dokumen, menandatangani secara digital, dan merekam data kolaboratif dalam satu ekosistem kerja terpadu.</p>
                <button class="btn-black-pill shadow-lg" data-bs-toggle="modal" data-bs-target="#crudModal">
                    <i class="fa-solid fa-plus-circle me-1"></i> Mulai Kelola Data
                </button>
                <a href="#workspace-section" class="btn btn-link text-dark fw-bold ms-3 text-decoration-none">Lihat Tabel <i class="fa-solid fa-arrow-down-long ms-1"></i></a>
            </div>
            <div id="interactive-bg"></div>
        </div>

        <div class="py-5 px-4 px-md-5" style="background-color: #FFFFFF; border-bottom: 2px solid #191919;">
            <div class="container-fluid text-center mb-5">
                <h2 class="section-title">Designed for modern workflows.</h2>
                <p class="text-muted fs-5">Semua alat yang Anda butuhkan untuk mengatur administrasi digital secara instan.</p>
            </div>
            
            <div class="row g-4 justify-content-center px-2 px-md-4">
                <div class="col-md-4">
                    <div class="feature-card p-4 h-100">
                        <div class="fs-1 text-warning mb-3"><i class="fa-solid fa-cloud-arrow-up"></i></div>
                        <h4 class="fw-bold text-dark">Multiple File Upload</h4>
                        <p class="text-muted mb-0">Unggah banyak dokumen pendukung sekaligus dalam satu kali pengisian form tanpa batasan kaku.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card p-4 h-100">
                        <div class="fs-1 text-primary mb-3"><i class="fa-solid fa-signature"></i></div>
                        <h4 class="fw-bold text-dark">HTML5 Canvas Sign</h4>
                        <p class="text-muted mb-0">Tandatangani berkas Anda secara langsung di layar perangkat menggunakan goresan tangan digital yang responsif.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card p-4 h-100">
                        <div class="fs-1 text-success mb-3"><i class="fa-solid fa-file-export"></i></div>
                        <h4 class="fw-bold text-dark">Advanced Export</h4>
                        <p class="text-muted mb-0">Cetak laporan atau ekspor database instan langsung ke format Microsoft Excel, PDF, atau mode Print Cetak.</p>
                    </div>
                </div>
            </div>
        </div>

        <div id="workspace-section" class="table-section">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
                <div>
                    <h3 class="fw-bold m-0" style="font-family: 'Playfair Display', serif; font-size: 2rem;"><i class="fa-solid fa-folder-open text-warning me-2"></i>Data Workspace</h3>
                    <p class="text-muted m-0 small">Gunakan tombol ekspor untuk menarik data secara keseluruhan.</p>
                </div>
                <button class="btn-black-pill btn-sm mt-3 mt-md-0 fs-6 py-2" data-bs-toggle="modal" data-bs-target="#crudModal">
                    <i class="fa-solid fa-plus me-1"></i> Tambah Dokumen Baru
                </button>
            </div>

            <table id="dataTable" class="table table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pengguna</th>
                        <th>Dokumen Terlampir</th>
                        <th>Status TTD</th>
                        <th>Kelola</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    // Logika relasi database Anda tetap utuh
                    $sql = "SELECT md.*, GROUP_CONCAT(dp.nama_file SEPARATOR '|') as daftar_file 
                            FROM manajemen_data_adindra_2430511048 md 
                            LEFT JOIN dokumen_pendukung_adindra_2430511048 dp ON md.id = dp.manajemen_data_id 
                            GROUP BY md.id ORDER BY md.id DESC";
                    $result = $conn->query($sql);
                    
                    while ($row = $result->fetch_assoc()) :
                    ?>
                    <tr>
                        <td class="fw-bold text-muted"><?= $no++; ?></td>
                        <td class="fw-bold text-dark fs-5"><?= htmlspecialchars($row['nama_lengkap']); ?></td>
                        <td>
                            <?php
                            if (!empty($row['daftar_file'])) {
                                $files = explode('|', $row['daftar_file']);
                                foreach ($files as $file) {
                                    $nama_tampil = preg_replace('/^[0-9]+_/', '', $file);
                                    echo '<a href="uploads/' . htmlspecialchars($file) . '" target="_blank" 
                                            class="badge bg-dark text-white text-decoration-none mb-1 d-block text-truncate p-2" style="max-width:250px;">';
                                    echo '<i class="fa-solid fa-file-lines me-1"></i> ' . htmlspecialchars($nama_tampil);
                                    echo '</a>';
                                }
                            } else {
                                echo '<span class="text-muted small"><i class="fa-solid fa-minus"></i> Kosong</span>';
                            }
                            ?>
                        </td>
                        <td>
                            <?php if ($row['status_tanda_tangan'] == 'Signed' && !empty($row['tangan_tangan'] ?? $row['tanda_tangan'])): ?>
                                <img src="<?= $row['tanda_tangan']; ?>" alt="TTD" style="height: 45px; object-fit: contain;" class="border rounded bg-white p-1">
                            <?php else: ?>
                                <span class="badge bg-warning text-dark border border-dark rounded-pill px-3 py-1">Unsigned</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-dark btn-edit rounded-pill fw-bold px-3" 
                                    data-bs-toggle="modal" data-bs-target="#crudModal"
                                    data-id="<?= $row['id']; ?>" data-nama="<?= htmlspecialchars($row['nama_lengkap']); ?>">
                                <i class="fa-solid fa-marker me-1"></i> Edit
                            </button>
                            <a href="proses.php?aksi=hapus&id=<?= $row['id']; ?>" class="btn btn-sm btn-dark rounded-pill px-2 ms-1" onclick="return confirm('Hapus data ini beserta seluruh filenya?')">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        
        <div class="text-center py-4 bg-white border-top text-muted small">
            &copy; 2026 InData Workspace Framework. Built for academic assignment. All rights reserved.
        </div>
    </div>

    <div class="modal fade" id="crudModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="dataForm" action="proses.php?aksi=tambah" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h4 class="modal-title fw-bold" style="font-family: 'Playfair Display', serif;" id="modalLabel">Kelola Data InData</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <input type="hidden" name="id" id="idData">
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control form-control-lg border-dark" id="namaLengkap" required placeholder="Contoh: Adindra Hakim">
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase">Upload Dokumen Pendukung (Multiple)</label>
                            <input class="form-control border-dark" type="file" name="upload_dokumen[]" id="uploadDokumen" multiple>
                            <small class="text-muted">Anda bisa memilih lebih dari 1 file dengan menahan tombol CTRL.</small>
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-bold small text-uppercase">Goresan Tanda Tangan Canvas</label>
                            <div class="signature-wrapper">
                                <canvas id="signatureCanvas"></canvas>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger mt-2 rounded-pill fw-bold" id="clearSignature">Hapus Goresan</button>
                            <input type="hidden" name="tanda_tangan_base64" id="tandaTanganBase64">
                        </div>
                    </div>
                    <div class="modal-footer border-top border-dark">
                        <button type="button" class="btn btn-outline-dark rounded-pill fw-bold px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-dark rounded-pill fw-bold px-4" id="saveData">Simpan Dokumen</button>
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
    <script src="script.js?v=<?= time(); ?>"></script>
</body>
</html>