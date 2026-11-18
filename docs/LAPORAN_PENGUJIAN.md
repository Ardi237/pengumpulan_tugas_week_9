# Laporan Pengujian Sistem Akademik

## Lingkungan

| Komponen | Versi |
| --- | --- |
| PHP | 8.2.12 |
| Laravel | 12.73.0 |
| Laravel Breeze | 2.4.2 |
| Composer | 2.8.8 |
| Node.js | 22.14.0 |
| npm | 10.9.2 |
| MySQL | 8.0.45 |

## Hasil Otomatis

```text
php artisan migrate:fresh --seed  BERHASIL
php artisan route:list --except-vendor  52 route aplikasi terdaftar
php artisan test                 35 test, 137 assertion, seluruhnya lulus
npm run build                    BERHASIL
composer audit                   Tidak ada advisory
npm audit --omit=dev             Tidak ada vulnerability produksi
HTTP smoke test                  /login 200, /up 200, guest dashboard 302 ke /login
```

## Matriks Skenario

| No. | Skenario | Hasil yang diharapkan | Hasil |
| ---: | --- | --- | --- |
| 1 | Guest membuka dashboard | Dialihkan ke login | Lulus |
| 2 | Login dengan akun valid | Masuk ke dashboard | Lulus |
| 3 | Login dengan password salah | Autentikasi ditolak | Lulus |
| 4 | Membuka registrasi publik | `404` | Lulus |
| 5 | Admin mengelola pengguna | Diizinkan | Lulus |
| 6 | Dosen membuka manajemen pengguna | `403` | Lulus |
| 7 | Admin membuat mata kuliah dan enrollment | Data tersimpan | Lulus |
| 8 | Enrollment mahasiswa yang sama dua kali | Validasi menolak duplikasi | Lulus |
| 9 | Dosen mengelola materi kelas sendiri | Diizinkan | Lulus |
| 10 | Mahasiswa terdaftar melihat dan mengunduh materi | Diizinkan | Lulus |
| 11 | Mahasiswa luar kelas membuka materi | `403` | Lulus |
| 12 | Dosen memasukkan nilai mahasiswa terdaftar | Data tersimpan | Lulus |
| 13 | Nilai di luar rentang 0–100 | Validasi menolak | Lulus |
| 14 | Mahasiswa melihat nilai sendiri | Diizinkan | Lulus |
| 15 | Mahasiswa melihat nilai mahasiswa lain | `403` | Lulus |
| 16 | Admin mencoba menghapus akun sendiri/admin terakhir | Operasi ditolak | Lulus |
| 17 | Seeder dijalankan berulang kali | Tidak ada data duplikat | Lulus |
| 18 | Seluruh halaman utama tiap role dirender | HTTP `200` tanpa error Blade | Lulus |

## Data Seeder

| Role | Email | Password |
| --- | --- | --- |
| Admin | `admin@example.com` | `password` |
| Dosen | `dosen@example.com` | `password` |
| Mahasiswa | `mahasiswa@example.com` | `password` |

Seeder juga membuat mata kuliah `WEB201` dan `DBS201`, menetapkan akun dosen sebagai pengampu, serta mendaftarkan akun mahasiswa pada kedua mata kuliah.

## Catatan Keamanan

- File materi berada pada disk `local` yang private.
- Download selalu melewati middleware autentikasi dan `MaterialPolicy`.
- `GradePolicy` membatasi mahasiswa pada record nilai miliknya sendiri.
- `CoursePolicy` membatasi dosen pada mata kuliah yang diampu dan mahasiswa pada mata kuliah yang diikuti.
- CSRF aktif pada form web.
- Password memakai cast hashing Laravel.
- Role tidak dapat dipilih melalui registrasi publik karena endpoint registrasi dinonaktifkan.
