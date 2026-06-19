# 📦 Sistem Manajemen Data Terpadu (Full-Stack)

Sebuah aplikasi sistem manajemen data berbasis web (Full-Stack) yang dirancang untuk mengelola administrasi data secara dinamis. Proyek ini awalnya dibangun sebagai purwarupa antarmuka (Front-End) untuk Tugas Pemrograman Web, yang kemudian dikembangkan menjadi sistem operasional penuh dengan integrasi PHP dan MariaDB (MySQL).

Sistem ini tidak hanya menawarkan fungsi CRUD standar, tetapi juga dilengkapi dengan kapabilitas tingkat lanjut seperti pengunggahan multi-dokumen (*multiple file upload*), penandatanganan digital via kanvas (*digital signature*), pelaporan data otomatis, serta antarmuka *Dark Mode* yang responsif dan interaktif.

---

## 🛠️ Teknologi yang Digunakan

* **Front-End:** HTML5, CSS3, JavaScript (Vanilla & jQuery)
* **Framework UI:** Bootstrap 5 (Dark Mode)
* **Back-End:** PHP (Native)
* **Database:** MariaDB / MySQL
* **Library Pihak Ketiga:**
    * [DataTables](https://datatables.net/) (Tabel interaktif & fitur Ekspor)
    * [Signature Pad](https://github.com/szimek/signature_pad) (Kanvas tanda tangan digital)

---

## ✅ Progres & Fitur yang Sudah Selesai

Berikut adalah daftar fitur fungsional yang telah berhasil diimplementasikan di dalam sistem:

### 🔐 Keamanan & Autentikasi
- [x] **Sistem Login Utama:** Pembatasan akses menggunakan `session` PHP. Pengguna yang belum *login* akan dialihkan kembali ke gerbang autentikasi.

### 📝 Manajemen Data (CRUD)
- [x] **Create & Update Dinamis:** Penggunaan 1 *Modal Bootstrap* yang dapat berubah wujud dan fungsi secara dinamis menjadi mode "Tambah Data" atau "Edit Data" menggunakan JavaScript.
- [x] **Delete Otomatis (Cascade):** Penghapusan data induk yang akan otomatis membersihkan seluruh rekam jejak file di database (relasi basis data).

### 📂 Sistem Berkas & Tanda Tangan
- [x] **Multiple File Upload:** Kapabilitas untuk mengunggah banyak berkas pendukung sekaligus. Berkas otomatis berganti nama dengan stempel waktu (`time()`) untuk menghindari bentrokan, lalu disimpan ke dalam direktori `/uploads`.
- [x] **Canvas Digital Signature:** Papan tanda tangan interaktif. Goresan tangan diubah menjadi string *Base64* dan disimpan ke dalam database secara langsung.
- [x] **Tautan Dokumen Langsung:** Implementasi kueri `GROUP_CONCAT` pada SQL memungkinkan pengguna melihat dan membuka dokumen (Word, PDF, dll.) langsung dari tautan (*hyperlink*) di dalam sel tabel *dashboard*.

### 📊 Pelaporan & Antarmuka
- [x] **Tabel Interaktif:** Fitur pencarian, pengurutan, dan paginasi data secara *real-time*.
- [x] **Ekspor Data Sekali Klik:** Ekstraksi data dari tabel langsung ke format **Excel (.xlsx)**, **PDF**, maupun fitur **Print/Cetak**.
- [x] **Animasi UI/UX (Smooth Transitions):** Transisi halaman yang halus (*fade-in*), efek mengambang pada tombol (*hover scale*), dan efek bercahaya pada kanvas saat berinteraksi.

---

## 🗄️ Struktur Relasi Database

Database dirancang menggunakan pendekatan *One-to-Many* yang dinormalisasi:
1.  `users` : Penyimpanan kredensial admin.
2.  `manajemen_data` : Tabel induk penyimpan informasi profil dan *string Base64* (tanda tangan) berukuran `LONGTEXT`.
3.  `dokumen_pendukung` : Tabel relasi penyimpan nama berkas fisik dengan konstrain *Foreign Key* (`ON DELETE CASCADE`).

---

## 🚀 Cara Menjalankan Proyek di Komputer Lokal (Laragon)

1.  Buka terminal/CMD, lalu arahkan ke dalam folder root Laragon Anda (secara bawaan berada di `C:\laragon\www`).
2.  *Clone* repositori ini langsung dengan nama folder `proyek_pw` menggunakan perintah berikut:
    ```bash
    git clone [https://github.com/adindrax-hub/tugas-pemrograman_web.git](https://github.com/adindrax-hub/tugas-pemrograman_web.git) proyek_pw
    ```
3.  Buka aplikasi **Laragon** dan klik tombol **Start All** untuk menyalakan layanan Apache dan MySQL.
4.  Buka **HeidiSQL** (dengan mengeklik tombol *Database* di Laragon) atau *phpMyAdmin*, lalu buat database baru dengan nama **`db_proyek_pw`**.
5.  Impor *file* skema database (`db_proyek_pw.sql`) ke dalam database tersebut.
6.  Pastikan terdapat folder bernama **`uploads`** di dalam folder proyek Anda (`laragon/www/proyek_pw/uploads`) untuk menampung *file*. Jika belum ada, buat foldernya secara manual.
7.  Buka *browser* dan akses proyek melalui salah satu URL berikut:
    * **URL Standar:** `http://localhost/proyek_pw`
    * **URL Virtual Host Laragon:** `http://proyek_pw.test` (Jika fitur *Auto Virtual Hosts* pada Laragon Anda aktif)
8.  Gunakan kredensial berikut untuk masuk (data bawaan dari *database* dummy):
    * **Username:** `admin`
    * **Password:** `admin123`

---
