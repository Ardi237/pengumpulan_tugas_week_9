<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <a href="{{ route('courses.index') }}" class="text-sm font-medium text-red-600 hover:text-red-700">← Kembali ke mata kuliah</a>
                <h1 class="mt-1 text-2xl font-bold text-slate-900">{{ $course->name }}</h1>
                <p class="text-sm text-slate-500">{{ $course->code }} · {{ $course->lecturer?->name ?? 'Belum ada dosen' }}</p>
            </div>
            <span class="w-fit rounded-full bg-slate-100 px-3 py-1 text-sm font-semibold text-slate-600">{{ $course->students_count ?? $course->students?->count() ?? 0 }} mahasiswa</span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="font-semibold text-slate-900">Tentang Mata Kuliah</h2>
                <p class="mt-3 whitespace-pre-line text-sm leading-6 text-slate-600">{{ $course->description ?: 'Belum ada deskripsi mata kuliah.' }}</p>
            </section>

            <div class="grid gap-6 lg:grid-cols-2">
                <a href="{{ route('courses.materials.index', $course) }}" class="group rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition hover:border-red-200 hover:shadow-md">
                    <p class="text-sm font-semibold text-red-600">Materi Pembelajaran</p>
                    <h2 class="mt-2 text-xl font-bold text-slate-900">Lihat materi mata kuliah</h2>
                    <p class="mt-2 text-sm text-slate-500">Buka dan unduh materi yang tersedia sesuai hak akses Anda.</p>
                    <p class="mt-5 text-sm font-semibold text-slate-900 group-hover:text-red-600">Buka materi →</p>
                </a>

                @if (auth()->user()->isAdmin() || auth()->user()->isDosen())
                    @can('create', [App\Models\Grade::class, $course])
                        <a href="{{ route('courses.grades.index', $course) }}" class="group rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition hover:border-red-200 hover:shadow-md">
                            <p class="text-sm font-semibold text-red-600">Penilaian</p>
                            <h2 class="mt-2 text-xl font-bold text-slate-900">Kelola nilai mahasiswa</h2>
                            <p class="mt-2 text-sm text-slate-500">Masukkan, perbarui, dan periksa nilai mahasiswa pada kelas ini.</p>
                            <p class="mt-5 text-sm font-semibold text-slate-900 group-hover:text-red-600">Buka nilai →</p>
                        </a>
                    @endcan
                @else
                    <a href="{{ route('student.grades.index') }}" class="group rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition hover:border-red-200 hover:shadow-md">
                        <p class="text-sm font-semibold text-red-600">Nilai Saya</p>
                        <h2 class="mt-2 text-xl font-bold text-slate-900">Lihat hasil penilaian</h2>
                        <p class="mt-2 text-sm text-slate-500">Nilai yang ditampilkan hanya milik akun Anda.</p>
                        <p class="mt-5 text-sm font-semibold text-slate-900 group-hover:text-red-600">Buka nilai →</p>
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
