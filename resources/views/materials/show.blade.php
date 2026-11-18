<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div><a href="{{ route('courses.materials.index', $material->course) }}" class="text-sm font-medium text-red-600">← Materi {{ $material->course->name }}</a><h1 class="mt-1 text-2xl font-bold text-slate-900">{{ $material->title }}</h1></div>
            <a href="{{ route('materials.download', $material) }}" class="rounded-lg bg-red-600 px-4 py-2.5 text-center text-sm font-semibold text-white hover:bg-red-700">Unduh File</a>
        </div>
    </x-slot>

    <div class="py-8"><div class="mx-auto max-w-4xl space-y-6 px-4 sm:px-6 lg:px-8">
        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <div class="flex flex-wrap gap-x-6 gap-y-2 border-b border-slate-100 pb-5 text-sm text-slate-500">
                <span>Mata kuliah: <strong class="text-slate-700">{{ $material->course->name }}</strong></span>
                <span>Pengunggah: <strong class="text-slate-700">{{ $material->uploader?->name ?? 'Sistem' }}</strong></span>
                <span>{{ $material->created_at->format('d M Y, H:i') }}</span>
            </div>
            <div class="mt-6 whitespace-pre-line text-sm leading-7 text-slate-700">{{ $material->description ?: 'Tidak ada deskripsi untuk materi ini.' }}</div>
        </section>

        @can('update', $material)
            <div class="flex justify-end"><a href="{{ route('materials.edit', $material) }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-white">Edit Materi</a></div>
        @endcan
    </div></div>
</x-app-layout>
