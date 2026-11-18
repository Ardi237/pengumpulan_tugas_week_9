# Endpoint Sistem Akademik

Seluruh endpoint internal menggunakan middleware `auth` dan `verified`. Middleware `role` serta Policy menambahkan pembatasan sesuai tabel berikut.

## Autentikasi dan Profil

| Method | Endpoint | Akses | Fungsi |
| --- | --- | --- | --- |
| GET, POST | `/login` | Guest | Login |
| POST | `/logout` | Login | Logout |
| GET, PATCH, DELETE | `/profile` | Login | Kelola profil sendiri |
| GET | `/dashboard` | Semua role | Dashboard sesuai role |

Registrasi publik `/register` dinonaktifkan. Admin membuat akun melalui manajemen pengguna.

## Mata Kuliah

| Method | Endpoint | Akses | Fungsi |
| --- | --- | --- | --- |
| GET | `/courses` | Semua role | Daftar mata kuliah yang boleh dilihat |
| GET | `/courses/{course}` | Pengguna terkait | Detail mata kuliah |
| GET | `/admin/courses` | Admin | Daftar seluruh mata kuliah |
| GET, POST | `/admin/courses/create`, `/admin/courses` | Admin | Membuat mata kuliah |
| GET, PUT/PATCH | `/admin/courses/{course}/edit`, `/admin/courses/{course}` | Admin | Mengubah mata kuliah |
| DELETE | `/admin/courses/{course}` | Admin | Menghapus mata kuliah |

## Pengguna dan Enrollment

| Method | Endpoint | Akses | Fungsi |
| --- | --- | --- | --- |
| GET, POST | `/admin/users`, `/admin/users/create` | Admin | Daftar dan membuat pengguna |
| GET, PUT/PATCH | `/admin/users/{user}/edit`, `/admin/users/{user}` | Admin | Mengubah pengguna |
| DELETE | `/admin/users/{user}` | Admin | Menghapus pengguna |
| GET | `/admin/courses/{course}/students` | Admin | Daftar peserta mata kuliah |
| POST | `/admin/courses/{course}/students` | Admin | Menambahkan mahasiswa |
| DELETE | `/admin/courses/{course}/students/{student}` | Admin | Mengeluarkan mahasiswa |

## Materi

| Method | Endpoint | Akses | Fungsi |
| --- | --- | --- | --- |
| GET | `/courses/{course}/materials` | Pengguna terkait | Daftar materi |
| GET | `/materials/{material}` | Pengguna terkait | Detail materi |
| GET | `/materials/{material}/download` | Pengguna terkait | Unduh file private |
| GET, POST | `/courses/{course}/materials/create`, `/courses/{course}/materials` | Admin/dosen pengampu | Membuat materi |
| GET, PUT/PATCH | `/materials/{material}/edit`, `/materials/{material}` | Admin/dosen pengampu | Mengubah materi |
| DELETE | `/materials/{material}` | Admin/dosen pengampu | Menghapus materi |

## Nilai

| Method | Endpoint | Akses | Fungsi |
| --- | --- | --- | --- |
| GET | `/courses/{course}/grades` | Admin/dosen pengampu | Daftar nilai kelas |
| GET, POST | `/courses/{course}/grades/create`, `/courses/{course}/grades` | Admin/dosen pengampu | Menambahkan nilai |
| GET, PUT/PATCH | `/grades/{grade}/edit`, `/grades/{grade}` | Admin/dosen pengampu | Mengubah nilai |
| DELETE | `/grades/{grade}` | Admin/dosen pengampu | Menghapus nilai |
| GET | `/my-grades` | Mahasiswa | Daftar nilai sendiri |
| GET | `/my-grades/{grade}` | Mahasiswa pemilik | Detail nilai sendiri |

## Respons Keamanan

- Guest yang membuka halaman internal diarahkan ke `/login`.
- Pengguna login dengan role yang salah menerima `403`.
- Resource yang tidak dimiliki atau tidak terkait ditolak oleh Policy.
- File materi yang hilang menerima `404`.
- Input tidak valid dikembalikan ke form dengan pesan validasi.
