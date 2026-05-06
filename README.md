<div align="center">

<h1>📚 RYU — Sistem E-Rapor Sekolah Dasar</h1>

<p><strong>Aplikasi manajemen rapor digital berbasis web untuk Sekolah Dasar,<br>terintegrasi langsung dengan data Dapodik.</strong></p>

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Livewire](https://img.shields.io/badge/Livewire-4.x-FB70A9?style=for-the-badge&logo=livewire&logoColor=white)](https://livewire.laravel.com)
[![NativePHP](https://img.shields.io/badge/NativePHP-Electron-4A90D9?style=for-the-badge&logo=electron&logoColor=white)](https://nativephp.com)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg?style=for-the-badge)](LICENSE)

</div>

---

## 🌟 Tentang Proyek

**RYU** adalah aplikasi **E-Rapor** (Elektronik Rapor) modern untuk Sekolah Dasar yang dirancang untuk menyederhanakan proses penilaian dan pelaporan akademik. Aplikasi ini berjalan sebagai **aplikasi desktop** menggunakan NativePHP (Electron), sehingga dapat dioperasikan secara offline tanpa memerlukan koneksi internet setelah instalasi.

Data siswa, kelas, dan guru diambil langsung dari **Dapodik** (Data Pokok Pendidikan) melalui API lokal, sehingga operator tidak perlu memasukkan ulang data yang sudah ada di Dapodik.

---

## ✨ Fitur Utama

### 📊 Penilaian & Rapor
- **Input Nilai Akademik** — Penilaian per mata pelajaran, per rombel, per siswa
- **Tujuan Pembelajaran (TP/CP)** — Manajemen Tujuan Pembelajaran dan Capaian Pembelajaran
- **Penilaian P5** — Projek Penguatan Profil Pelajar Pancasila (Kokurikuler)
- **Pelengkap Rapor** — Ketidakhadiran, catatan wali kelas, ekstrakurikuler
- **Perkembangan Siswa** — Monitoring progres nilai per periode
- **Status Penilaian** — Pantau kelengkapan input nilai per rombel

### 🖨️ Cetak & Ekspor
- **Cetak Rapor** — Cetak rapor per siswa maupun massal per rombel (PDF)
- **Leger Nilai** — Rekap nilai seluruh siswa dalam satu rombel
- **Ekspor Excel** — Unduh leger nilai dalam format Excel (`.xlsx`)
- **Kirim Dapodik** — Sinkronisasi nilai kembali ke Dapodik

### 🏫 Manajemen Data
- **Integrasi Dapodik** — Sinkronisasi otomatis data sekolah, guru, siswa, dan rombel
- **Data Manual** — Tambah guru & pembelajaran manual jika tidak ada di Dapodik
- **Override Data** — Koreksi data siswa, rombel, dan identitas sekolah tanpa mengubah Dapodik
- **Manajemen Pengguna** — Role-based access control (Superadmin / Admin / Guru)

### ⚙️ Pengaturan
- **Identitas Sekolah** — Logo, stempel, tanda tangan kepala sekolah (disimpan sebagai Base64)
- **Konfigurasi Database** — Ganti koneksi database melalui UI
- **Backup & Restore** — Ekspor dan impor seluruh data rapor
- **Admin Log** — Riwayat aktivitas sistem

---

## 🛠️ Teknologi

| Komponen | Teknologi |
|---|---|
| Framework | [Laravel 11](https://laravel.com) |
| Runtime Bahasa | PHP 8.2+ |
| UI Reaktif | [Livewire 4](https://livewire.laravel.com) |
| Routing SPA | [InertiaJS](https://inertiajs.com) |
| Desktop | [NativePHP / Electron](https://nativephp.com) |
| PDF Generator | [DomPDF (barryvdh)](https://github.com/barryvdh/laravel-dompdf) |
| CSS Framework | [Tailwind CSS](https://tailwindcss.com) |
| Build Tool | [Vite](https://vitejs.dev) |
| Database Default | SQLite (embedded) |
| Database Opsional | PostgreSQL / MySQL |

---

## 🚀 Instalasi & Menjalankan

### Prasyarat
- PHP >= 8.2 (dengan ekstensi: `pdo`, `pdo_sqlite`, `gd`, `curl`, `zip`)
- Composer
- Node.js >= 18 & npm
- Dapodik terinstall dan berjalan di komputer yang sama (port `5774`)

### Langkah Instalasi

```bash
# 1. Clone repositori
git clone https://github.com/username/ryu-erapor.git
cd ryu-erapor

# 2. Install dependensi PHP
composer install

# 3. Install dependensi Node.js
npm install

# 4. Salin file konfigurasi lingkungan
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Jalankan migrasi database
php artisan migrate

# 7. Jalankan seeder awal (identitas sekolah placeholder)
php artisan db:seed

# 8. Jalankan aplikasi (mode development)
npm run dev
```

### Menjalankan sebagai Desktop App (NativePHP)

```bash
php artisan native:serve
```

### Menjalankan sebagai Web App Biasa

```bash
# Jalankan semua service sekaligus
composer run dev
```

---

## 🔐 Akun Default & Role

| Role | Keterangan | Hak Akses |
|---|---|---|
| `superadmin` | Super Administrator | Akses penuh termasuk manajemen siswa & override data |
| `admin` | Administrator Sekolah | Input nilai, cetak rapor, pengaturan, sinkronisasi Dapodik |
| `guru` | Guru Mata Pelajaran | Input nilai kelas yang diajar, cetak rapor, TP/CP |

> Akun default dibuat melalui seeder. Lihat `database/seeders/DatabaseSeeder.php` untuk detail.

---

## 📁 Struktur Modul

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php          # Autentikasi
│   │   ├── DashboardController.php     # Dashboard & analitik
│   │   ├── NilaiController.php         # Input nilai akademik
│   │   ├── TPController.php            # Tujuan Pembelajaran
│   │   ├── P5Controller.php            # Projek P5 (Kokurikuler)
│   │   ├── KokurikulerController.php   # Kegiatan kokurikuler
│   │   ├── PelengkapRaporController.php# Data pelengkap rapor
│   │   ├── CetakController.php         # Cetak & ekspor rapor/leger
│   │   ├── SettingsController.php      # Pengaturan & identitas sekolah
│   │   ├── DatabaseController.php      # Manajemen koneksi DB
│   │   ├── StudentController.php       # Manajemen peserta didik
│   │   ├── SyncController.php          # Sinkronisasi Dapodik
│   │   └── PageController.php          # Halaman referensi & utilitas
│   └── Middleware/
├── Models/                             # Eloquent Models
└── Services/
    └── DapodikService.php              # Integrasi API Dapodik
```

---

## 🔗 Integrasi Dapodik

Aplikasi ini membaca data secara langsung dari **API lokal Dapodik** yang berjalan di `http://localhost:5774`. Data yang diambil meliputi:

- Data Sekolah (`getSekolah`)
- Peserta Didik (`getPesertaDidik`)
- Pendidik & Tenaga Kependidikan / PTK (`getPTK`)
- Rombongan Belajar beserta anggota & pembelajaran (`getRombonganBelajar`)

Data Dapodik di-cache secara lokal untuk performa. Admin dapat memperbarui cache melalui menu **Ambil Data Dapodik**.

---

## 📸 Screenshot

> *(Tambahkan screenshot antarmuka aplikasi di sini)*

---

## 🤝 Kontribusi

Kontribusi sangat diterima! Silakan buka **Issue** untuk melaporkan bug atau mengusulkan fitur baru, lalu buat **Pull Request** dengan perubahan yang diinginkan.

1. Fork repositori ini
2. Buat branch fitur: `git checkout -b fitur/nama-fitur`
3. Commit perubahan: `git commit -m 'feat: tambah fitur X'`
4. Push ke branch: `git push origin fitur/nama-fitur`
5. Buat Pull Request

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah **MIT License** — lihat file [LICENSE](LICENSE) untuk detail.

---

<div align="center">

Dibuat dengan ❤️ untuk membantu operator sekolah Indonesia

</div>
