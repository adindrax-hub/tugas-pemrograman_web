# Panduan & Prompt Pembuatan Website Profesional (Aesthetic & Responsive)

Dokumen ini dirancang sebagai acuan komprehensif untuk merencanakan, mendesain, dan mengode *landing page* atau website profesional yang memiliki estetika tinggi, responsif, dan berorientasi pada konversi.

---

## 1. Filosofi Desain Web Modern & Aesthetic

Website yang profesional wajib menyeimbangkan antara keindahan visual (*form*) dan fungsi (*function*). Berikut prinsip utamanya:
* **Less is More (Minimalisme):** Manfaatkan *white space* (ruang kosong) secara optimal. Jangan menumpuk elemen agar mata pengunjung bisa fokus pada informasi penting.
* **Hierarki Tipografi yang Jelas:** Gunakan maksimal 2 rumpun font (*font family*). Satu font berkarakter kuat untuk *Heading* (misal: Serif untuk kesan premium, atau Display Sans untuk kesan modern), dan satu font yang sangat mudah dibaca untuk *Body Text* (misal: Inter, Roboto, atau Plus Jakarta Sans).
* **Aturan Harmoni Warna (60-30-10):**
    * **60% Warna Dominan:** Biasanya untuk latar belakang (misal: putih bersih, *off-white*, atau abu-abu gelap).
    * **30% Warna Sekunder:** Untuk elemen struktural seperti kartu, navbar, dan teks sekunder.
    * **10% Warna Aksen (Pop Color):** Khusus digunakan untuk tombol *Call to Action* (CTA) atau elemen penting yang wajib mencuri perhatian.

---

## 2. Struktur Anatomi Landing Page Profesional

Untuk konversi maksimal, susun komponen website dengan urutan logis berikut:

### A. Hero Section (First Impression)
* **Headline:** Kalimat utama yang tebal, ringkas, dan langsung menjelaskan solusi yang ditawarkan.
* **Sub-headline:** Penjelasan tambahan 1-2 kalimat untuk mendukung headline.
* **Primary CTA Button:** Tombol interaktif (misal: "Mulai Sekarang", "Konsultasi Gratis") dengan kontras warna yang tinggi.
* **Visual Asset:** Gambar produk berkualitas tinggi, ilustrasi 3D abstrak, atau mockup interaktif.

### B. Social Proof / Trust Signals
* Barisan logo perusahaan yang pernah bekerja sama, atau statistik singkat (misal: "10,000+ Pengguna Aktif").

### C. Feature & Benefit Grid
* Penjelasan layanan atau fitur utama menggunakan susunan *grid* (3 atau 4 kolom).
* Gunakan ikon minimalis yang seragam atau ilustrasi mikro yang estetik.

### D. Detailed Showcase / Product Deep-Dive
* Tata letak selang-seling (*zigzag layout*): Gambar di kiri - Teks di kanan, lalu Gambar di kanan - Teks di kiri. Ini menjaga ritme membaca pengunjung agar tidak bosan.

### E. Testimonial / Social Validation
* Kumpulan ulasan dari klien nyata dalam bentuk *bento grid* atau *card slider* yang rapi, lengkap dengan nama, jabatan, dan foto avatar mini.

### F. FAQ (Frequently Asked Questions)
* Menggunakan komponen *accordion* (bisa diklik untuk buka-tutup) untuk menjawab keraguan utama calon pelanggan tanpa memenuhi layar.

### G. Footer & Final Call-to-Action
* Spasi besar dengan ajakan terakhir, diikuti oleh tautan navigasi sekunder, hak cipta, dan ikon media sosial.

---

## 3. Kumpulan Prompt AI Siap Pakai (Premium UI)

### Prompt UI/UX Visual (Figma / Midjourney / DALL-E)
> **Prompt:**
> "UI/UX design of a premium, highly aesthetic landing page for a [Sebutkan Bisnis, misal: Arsitektur Modern / Agen Kreatif Digital]. Clean desktop interface, minimalist layout, elegant serif typography combined with neat sans-serif, sophisticated color palette [Sebutkan Warna, misal: deep charcoal, warm cream, and muted gold accents], smooth organic shapes, high-end photography placeholders, 8k resolution, web design trends 2026, clear visual hierarchy, photorealistic style --ar 16:9"

### Prompt Coding Frontend (Claude / ChatGPT / v0.dev)
> **Prompt:**
> "Tolong buatkan struktur kode lengkap untuk satu halaman *landing page* yang sangat profesional dan memiliki estetika tinggi. 
> 
> **Tech Stack:** Gunakan HTML5 murni dan Tailwind CSS versi terbaru via CDN. Pastikan kodenya semantik dan responsif (mobile-friendly).
> 
> **Tema Visual:**
> * Gaya: Minimalis mewah dengan *white space* yang luas.
> * Palet Warna: Latar belakang memakai warna off-white (#FAFAFA), teks utama charcoal gelap (#1A1A1A), dan aksen tombol CTA menggunakan warna sage green (#4F6F52) yang memiliki efek hover halus.
> * Font: Gunakan font 'Playfair Display' untuk Heading dan 'Inter' untuk body text melalui Google Fonts.
> 
> **Komponen Wajib:**
> 1. Navigation Bar (Sticky, dengan efek backdrop-blur).
> 2. Hero Section (Headline tebal, sub-headline, 2 CTA button, dan hero image placeholder berbentuk rounded-2xl).
> 3. Features Section (Grid 3 kolom dengan border tipis elegan dan efek hover lift-up).
> 4. Testimonial Section (Gaya bento box minimalis).
> 5. Footer rapi dengan tautan navigasi dan media sosial.
> 
> Berikan kode utuh di dalam satu file agar saya bisa langsung menyalin dan mengujinya di browser."

---

## 4. Checklist Teknis & Optimasi Profesional

Sebelum website diluncurkan (*publish/deploy*), pastikan Anda mencentang poin-poin optimasi berikut:

- [ ] **Responsive Design:** Uji tampilan di resolusi Desktop (1920px), Tablet (768px), dan Mobile (375px).
- [ ] **Image Optimization:** Kompres semua gambar ke format modern seperti `.webp` atau `.avif` untuk mempercepat pemuatan halaman.
- [ ] **Core Web Vitals:** Pastikan skor performa di Google PageSpeed Insights berada di atas angka 90 (perhatikan LCP dan CLS).
- [ ] **SEO On-Page Dasar:** Isi tag `<title>`, `<meta description>`, dan pastikan hierarki heading runtut (`<h1>` hanya ada satu di Hero, diikuti `<h2>`, dan `<h3>`).
- [ ] **Accessibility (A11y):** Pastikan kontras warna teks dan latar belakang memenuhi standar WCAG, serta tambahkan atribut `alt` pada semua gambar.
