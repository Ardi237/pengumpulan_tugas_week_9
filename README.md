# Sistem Akademik — Tugas Week 9

Proyek Laravel untuk praktikum Middleware, pembatasan route, autentikasi, dan otorisasi berbasis role.

## Status

Tahap 0 dan bootstrap proyek telah selesai:

- Laravel 12.73
- Laravel Breeze 2.4 dengan Blade
- Login, registrasi, logout, verifikasi email, reset password, dan profil
- Tailwind CSS dan Vite
- PHPUnit dengan 25 test bawaan Breeze

Fitur role `admin`, `dosen`, dan `mahasiswa` akan ditambahkan pada tahap berikutnya.

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
copy .env.example .env
php artisan key:generate
npm install
```

Buat database MySQL bernama `akademik_app`, lalu sesuaikan kredensial database pada `.env`.

```bash
php artisan migrate
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

## Struktur Hak Akses yang Direncanakan

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
- Middleware akan membatasi route berdasarkan role.
- Policy akan membatasi akses ke data berdasarkan kepemilikan dan mata kuliah.
- Form web menggunakan perlindungan CSRF dari Laravel.
