<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Akses Ditolak · {{ config('app.name', 'Sistem Akademik') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-100 font-sans antialiased text-slate-900">
        <main class="flex min-h-screen items-center justify-center px-4 py-12">
            <div class="w-full max-w-lg rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-sm sm:p-12">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-50 text-2xl font-black text-red-600">403</div>
                <h1 class="mt-6 text-2xl font-bold">Akses ditolak</h1>
                <p class="mt-3 text-sm leading-6 text-slate-500">{{ $exception->getMessage() ?: 'Akun Anda tidak memiliki izin untuk membuka halaman atau data ini.' }}</p>
                <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="mt-8 inline-flex rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-700">
                    {{ auth()->check() ? 'Kembali ke Dashboard' : 'Masuk ke Aplikasi' }}
                </a>
            </div>
        </main>
    </body>
</html>
