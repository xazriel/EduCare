# 🏫 EduCare – Sistem Monitoring Risiko Psikososial & Ketergantungan Gawai Remaja

[![Laravel Version](https://img.shields.io/badge/Laravel-v12.x-FF2D20?style=flat-flat&logo=laravel)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-^8.2-777BB4?style=flat-flat&logo=php)](https://php.net)
[![TailwindCSS](https://img.shields.io/badge/TailwindCSS-v4.0-38BDF8?style=flat-flat&logo=tailwind-css)](https://tailwindcss.com)
[![AlpineJS](https://img.shields.io/badge/AlpineJS-v3.x-8BC0D0?style=flat-flat&logo=alpine.js)](https://alpinejs.dev)

---

## 📌 Tentang EduCare

**EduCare** adalah platform berbasis web interaktif yang dirancang khusus untuk memantau kondisi psikososial serta mendeteksi tingkat ketergantungan gawai (gadget addiction) pada remaja secara mandiri (*self-assessment*). 

Melalui pendekatan preventif terintegrasi, platform ini menggabungkan **tiga instrumen penilaian klinis terpercaya** ke dalam satu sesi kuesioner terpadu, didukung oleh visualisasi data analitis (*Business Intelligence*) untuk Guru Bimbingan Konseling (BK), dan chatbot interaktif berbasis **LLM Gemini** untuk memberikan edukasi kesehatan mental dan manajemen waktu secara langsung kepada siswa.

Tujuan utama sistem ini adalah mendeteksi tanda-tanda gangguan perilaku sejak dini agar Guru BK atau pihak sekolah dapat mengambil tindakan pencegahan (*preventif*) yang tepat sebelum berkembang menjadi gangguan klinis yang lebih serius.

---

## 👥 Arsitektur Peran & Fitur Utama

Sistem EduCare membagi hak akses dan fungsionalitas ke dalam tiga peran utama:

### 1. 🎓 Peran: Siswa
Siswa bertindak sebagai subjek utama yang memantau kondisi kesehatan psikososial mereka secara mandiri (*self-assessment*).
- **Autentikasi Aman:** Login dan logout ke dalam sistem dengan kredensial terdaftar.
- **Kuesioner Terpadu (3-in-1):** Mengisi instrumen penilaian dalam satu sesi interaktif yang menggabungkan:
  - **SDQ (Strengths and Difficulties Questionnaire):** Mengukur aspek kekuatan dan kesulitan perilaku serta emosional siswa.
  - **PSC-17 (Pediatric Symptom Checklist-17):** Screening kondisi psikososial kognitif, emosi, dan atensi.
  - **SAS-SV (Smartphone Addiction Scale - Short Version):** Mengukur tingkat kecenderungan ketergantungan gawai.
- **Klasifikasi Risiko Otomatis:** Setelah pengisian, sistem menghitung skor secara otomatis dan menampilkan tingkat risiko siswa dengan empat kategori: **Risiko Rendah**, **Sedang**, **Tinggi**, dan **Sangat Tinggi**.
- **Chatbot Edukatif (LLM Gemini):** Fitur interaktif untuk berdialog mengenai penggunaan gawai yang sehat, kiat manajemen waktu, dan regulasi emosi.
- **Tampilan Dashboard Siswa:**
  - Skor ringkasan hasil penilaian psikososial terakhir.
  - Indikator tingkat intensitas penggunaan gawai.
  - Grafik riwayat pengisian kuesioner sebelumnya untuk melihat tren personal.

### 2. 👩‍🏫 Peran: Guru BK (Bimbingan Konseling)
Guru BK bertindak sebagai pemantau utama yang menganalisis data visual untuk mendukung keputusan intervensi cepat atau konseling terarah.
- **Visualisasi Data Kondisi Siswa:** Memantau kondisi kesehatan mental dan ketergantungan gawai secara berkala dan *real-time*.
- **Pelacakan Tren Risiko:** Mengamati perkembangan grafik naik-turun tingkat risiko siswa di sekolah secara individual maupun kolektif.
- **Pengambilan Kebijakan Preventif:** Mengunduh dan memanfaatkan ringkasan data kuesioner sebagai dasar rancangan tindakan pencegahan sebelum masalah siswa memburuk.
- **Tampilan Dashboard Guru BK:**
  - Grafik umum kondisi psikososial seluruh siswa sekolah.
  - Ringkasan statistik jumlah siswa di setiap kategori risiko (Rendah/Sedang/Tinggi/Sangat Tinggi).
  - Grafik tren bulanan tingkat ketergantungan gawai.
  - Distribusi status risiko siswa per kelas serta visualisasi skor rata-rata instrumen.

### 3. 🛡️ Peran: Admin
Admin bertindak sebagai pengelola teknis operasional sistem untuk memastikan data master platform berjalan dengan benar.
- **Manajemen Akun Pengguna:** Mengelola data master siswa dan Guru BK (Tambah, Edit, Hapus, Aktivasi).
- **Konfigurasi Soal Kuesioner:** Mengatur butir-butir pertanyaan dari instrumen SDQ, PSC-17, dan SAS-SV di dalam database untuk pemeliharaan berkala.

### 📊 Fitur Pendukung Terintegrasi: Dashboard Business Intelligence (BI)
Dashboard BI makro dirancang khusus bagi manajemen sekolah dan Guru BK untuk melakukan analisis data psikososial mendalam:
- **Filter Kondisi Demografis:** Grafik visualisasi kondisi psikososial berdasarkan kelas dan gender siswa.
- **Tren Perkembangan Bulanan:** Grafik historis perubahan kondisi mental siswa sekolah dari bulan ke bulan.
- **Analisis Korelasi Khusus:** Visualisasi hubungan korelatif antara skor ketergantungan gawai (**SAS-SV**) dengan skor gangguan perilaku perilaku/emosi siswa (**SDQ & PSC-17**).

---

## 🛠️ Stack Teknologi

Platform ini dibangun menggunakan teknologi modern untuk memastikan performa yang cepat, aman, dan responsif:
- **Core Framework:** Laravel 12 (PHP ^8.2)
- **Autentikasi & Otorisasi:** Laravel Breeze & Spatie Laravel Permission
- **Frontend Engine:** Blade Templates, Alpine.js, Tailwind CSS (v4.0)
- **Data Visualization:** Chart.js (untuk dashboard interaktif dan grafik analisis)
- **Integrasi AI:** Google Gemini API (untuk Chatbot Edukasi)
- **Database:** MySQL / SQLite

---

## 🚀 Panduan Instalasi Lokal

Ikuti langkah-langkah di bawah ini untuk menjalankan proyek EduCare di komputer lokal Anda:

### 1. Prasyarat Sistem
Pastikan Anda sudah menginstal perangkat lunak berikut:
- PHP >= 8.2
- Composer
- Node.js & NPM
- Database Server (MySQL/MariaDB atau menggunakan SQLite default)

### 2. Kloning Proyek & Masuk Direktori
```bash
git clone <url-repositori-anda>
cd educare
```

### 3. Instalasi Dependensi PHP & JavaScript
```bash
composer install
npm install
```

### 4. Konfigurasi Environment File
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Buka file `.env` di text editor Anda, lalu sesuaikan konfigurasi database Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=educare
DB_USERNAME=root
DB_PASSWORD=
```
Tambahkan API Key Gemini Anda untuk mengaktifkan fitur Chatbot:
```env
GEMINI_API_KEY=your_gemini_api_key_here
```

### 5. Generate Application Key
```bash
php artisan key:generate
```

### 6. Jalankan Migrasi Database & Seeder
Buat database kosong bernama `educare` di database manager Anda, lalu jalankan perintah:
```bash
php artisan migrate:fresh --seed
```
*Perintah ini akan membuat struktur tabel kuesioner, peran, serta mengisi data instrumen soal (SDQ, PSC-17, SAS-SV) beserta akun dummy secara otomatis.*

### 7. Jalankan Server Pengembangan
Jalankan server Laravel dan kompilasi file aset Vite secara bersamaan menggunakan perintah:
```bash
npm run dev
```
Atau jalankan secara terpisah di terminal yang berbeda:
```bash
# Terminal 1: Menjalankan backend Laravel
php artisan serve

# Terminal 2: Menjalankan Vite untuk frontend
npm run dev
```
Buka browser Anda dan akses aplikasi melalui tautan `http://127.0.0.1:8000`.

---

## 🗂️ Akun Uji Coba Default (Seeder)
Gunakan kredensial berikut untuk menguji sistem setelah melakukan seeding:

| Role | Email / Username | Password |
|------|------------------|----------|
| **Admin** | `admin@educare.com` | `password` |
| **Guru BK** | `gurubk@educare.com` | `password` |
| **Siswa** | `siswa@educare.com` | `password` |

---

## 📤 Panduan Git Push untuk Pengembang

Untuk mengunggah dan memperbarui kode proyek EduCare Anda ke repositori online (GitHub/GitLab), ikuti langkah-langkah di bawah ini.

### 📌 Langkah 1: Inisialisasi Git & Konfigurasi Awal (Hanya untuk Pertama Kali)
Jika folder proyek Anda belum diinisialisasi sebagai repositori Git:
```bash
# Inisialisasi repositori Git lokal
git init

# Tambahkan URL repositori online Anda (Remote)
git remote add origin <URL_REPOSITORI_ANDA>
```
*Contoh URL: `https://github.com/username/educare.git`*

### 📌 Langkah 2: Cek Perubahan File
Sebelum menyimpan pekerjaan, periksa file apa saja yang telah Anda ubah atau tambahkan:
```bash
git status
```

### 📌 Langkah 3: Tambahkan Perubahan ke Staging Area
Gunakan perintah ini untuk menandai file yang siap dikomit:
```bash
# Menambahkan semua file yang berubah
git add .

# Atau menambahkan file spesifik saja (opsional)
git add app/Http/Controllers/Siswa/ChatbotController.php
```

### 📌 Langkah 4: Buat Commit (Simpan Perubahan)
Simpan perubahan Anda dengan menyertakan pesan commit yang deskriptif dan jelas mengenai apa yang telah Anda kerjakan:
```bash
git commit -m "feat: implementasi kuesioner terpadu 3-in-1 dan kalkulasi skor otomatis"
```
*Gunakan konvensi penamaan pesan commit yang baik, contoh:*
- `feat:` untuk fitur baru (contoh: `feat: integrasi chatbot Gemini`)
- `fix:` untuk perbaikan bug (contoh: `fix: perbaikan formula scoring SDQ`)
- `docs:` untuk perubahan dokumentasi (contoh: `docs: update panduan git push di readme`)
- `style:` untuk perubahan tampilan visual tanpa mengubah logika (contoh: `style: desain dashboard siswa`)

### 📌 Langkah 5: Kirim ke Repositori Online (Git Push)
Kirim kode Anda dari repositori lokal ke repositori online Anda:

```bash
# Jika Anda baru pertama kali melakukan push pada branch ini:
git push -u origin main

# Untuk push berikutnya setelah branch dikonfigurasi:
git push
```
*Catatan: Pastikan Anda berada di branch yang tepat (misalnya `main` atau `master`). Anda dapat melihat branch aktif saat ini dengan mengetik perintah `git branch`.*

### ⚠️ Tips Mengatasi Konflik (Merge Conflict) saat Push
Jika ada pengembang lain yang telah melakukan push sebelum Anda, Git akan menolak push Anda. Untuk menyelesaikannya:
1. Ambil perubahan terbaru terlebih dahulu:
   ```bash
   git pull origin main
   ```
2. Jika terjadi konflik, buka file yang ditunjuk, selesaikan konfliknya, lalu lakukan:
   ```bash
   git add .
   git commit -m "resolve: menyelesaikan konflik penggabungan"
   git push origin main
   ```

---
💡 *EduCare dikembangkan untuk mewujudkan lingkungan sekolah yang sehat secara mental dan gawai untuk masa depan yang lebih cerah.*
