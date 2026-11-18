<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <a href="{{ route('admin.courses.index') }}" class="text-sm font-medium text-red-600">← Kembali</a>
                <h1 class="mt-1 text-2xl font-bold text-slate-900">{{ $course->name }}</h1>
                <p class="text-sm text-slate-500">{{ $course->code }}</p>
            </div>
            <a href="{{ route('admin.courses.edit', $course) }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Edit Mata Kuliah</a>
        </div>
    </x-slot>

    <div class="py-8"><div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">Dosen pengampu</p><p class="mt-2 font-bold text-slate-900">{{ $course->lecturer?->name ?? '—' }}</p></div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">Mahasiswa</p><p class="mt-2 text-2xl font-bold text-slate-900">{{ $course->students_count ?? $course->students?->count() ?? 0 }}</p></div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">Materi</p><p class="mt-2 text-2xl font-bold text-slate-900">{{ $course->materials_count ?? $course->materials?->count() ?? 0 }}</p></div>
        </div>

        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="font-semibold text-slate-900">Deskripsi</h2>
            <p class="mt-3 whitespace-pre-line text-sm leading-6 text-slate-600">{{ $course->description ?: 'Belum ada deskripsi.' }}</p>
        </section>

        <div class="grid gap-4 md:grid-cols-3">
            <a href="{{ route('admin.courses.students.index', $course) }}" class="rounded-xl bg-slate-900 p-5 text-white hover:bg-slate-700"><p class="font-semibold">Kelola Mahasiswa</p><p class="mt-1 text-sm text-slate-300">Tambah atau keluarkan peserta kelas</p></a>
            <a href="{{ route('courses.materials.index', $course) }}" class="rounded-xl bg-red-600 p-5 text-white hover:bg-red-700"><p class="font-semibold">Kelola Materi</p><p class="mt-1 text-sm text-red-100">Unggah dan perbarui materi</p></a>
            <a href="{{ route('courses.grades.index', $course) }}" class="rounded-xl bg-white p-5 text-slate-900 ring-1 ring-slate-200 hover:bg-slate-50"><p class="font-semibold">Kelola Nilai</p><p class="mt-1 text-sm text-slate-500">Nilai mahasiswa dalam kelas</p></a>
        </div>
    </div></div>
</x-app-layout>
