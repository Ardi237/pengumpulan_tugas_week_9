# Sistem Akademik — Tugas Week 9

Proyek Laravel untuk praktikum Middleware, pembatasan route, autentikasi, dan otorisasi berbasis role.

## Status

Tahap 0–16 telah selesai:

- Laravel 12.73
- Laravel Breeze 2.4 dengan Blade
- Login, logout, verifikasi email, reset password, dan profil
- Registrasi publik dinonaktifkan; akun dikelola oleh admin
- Tailwind CSS dan Vite
- Model dan migration pengguna, mata kuliah, enrollment, materi, dan nilai
- Seeder akun admin, dosen, mahasiswa, serta data mata kuliah contoh
- Middleware `CheckRole` dengan alias `role`
- Policy untuk kepemilikan mata kuliah, materi, dan nilai
- Form Request untuk validasi seluruh data akademik
- Controller dan endpoint untuk seluruh alur akademik
- Antarmuka Blade responsif untuk admin, dosen, dan mahasiswa
- Penyimpanan dan unduhan materi melalui storage private
- PHPUnit dengan 35 test dan 137 assertion

Dokumentasi endpoint dan hasil pengujian tersedia pada folder [`docs`](docs).

## Fitur

- Dashboard dan navigasi berbeda untuk setiap role
- CRUD pengguna oleh admin
- CRUD mata kuliah dan penetapan dosen pengampu
- Enrollment mahasiswa ke mata kuliah
- Pengelolaan dan unduhan materi private
- Pengelolaan nilai oleh admin atau dosen pengampu
- Halaman nilai pribadi mahasiswa
- Pencarian dan pagination data
- Halaman khusus untuk akses `403`

## Kebutuhan Sistem

- PHP 8.2 atau lebih baru
- Composer 2
- Node.js 20 atau lebih baru dan npm
- MySQL 8 atau MariaDB
- Git

## Instalasi

```bash
git clone https://github.com/Ardi237/pengumpulan_tugas_week_9.git
cd pengumpulan_tugas_week_9
composer install
Copy-Item .env.example .env
php artisan key:generate
npm install
```

Buat database MySQL bernama `akademik_app`, lalu sesuaikan kredensial database pada `.env`.

```bash
php artisan migrate --seed
npm run build
php artisan serve
```

Aplikasi dapat dibuka melalui `http://127.0.0.1:8000`.

Untuk pengembangan frontend, jalankan perintah berikut pada terminal terpisah:

```bash
npm run dev
```

## Pengujian

```bash
php artisan test
```

Laporan pengujian lengkap terdapat pada [`docs/LAPORAN_PENGUJIAN.md`](docs/LAPORAN_PENGUJIAN.md).

## Akun Pengujian

Semua akun hasil seeder menggunakan password `password`.

| Role | Email |
| --- | --- |
| Admin | `admin@example.com` |
| Dosen | `dosen@example.com` |
| Mahasiswa | `mahasiswa@example.com` |

## Struktur Hak Akses

| Fitur | Admin | Dosen | Mahasiswa |
| --- | --- | --- | --- |
| Dashboard | Ya | Ya | Ya |
| Manajemen pengguna | Ya | Tidak | Tidak |
| Kelola materi | Ya | Ya, untuk kelas yang diampu | Tidak |
| Lihat materi | Ya | Ya | Ya, untuk kelas yang diikuti |
| Kelola nilai | Ya | Ya, untuk kelas yang diampu | Tidak |
| Lihat nilai pribadi | Ya | Tidak | Ya |

## Catatan Keamanan

- File `.env` tidak boleh di-commit.
- Password pengguna disimpan menggunakan hashing Laravel.
- Middleware membatasi route berdasarkan role.
- Policy membatasi akses ke data berdasarkan kepemilikan dan mata kuliah.
- Form web menggunakan perlindungan CSRF dari Laravel.
- File materi disimpan pada disk private dan hanya dikirim melalui controller setelah Policy mengizinkan akses.
- Mahasiswa tidak dapat melihat nilai atau materi kelas lain dengan mengganti ID pada URL.
