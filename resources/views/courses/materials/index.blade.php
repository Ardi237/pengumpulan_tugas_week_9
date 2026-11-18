<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <a href="{{ route('courses.show', $course) }}" class="text-sm font-medium text-red-600">← {{ $course->name }}</a>
                <h1 class="mt-1 text-2xl font-bold text-slate-900">Materi Pembelajaran</h1>
                <p class="text-sm text-slate-500">{{ $course->code }}</p>
            </div>
            @can('create', [App\Models\Material::class, $course])
                <a href="{{ route('courses.materials.create', $course) }}" class="rounded-lg bg-red-600 px-4 py-2.5 text-center text-sm font-semibold text-white hover:bg-red-700">Unggah Materi</a>
            @endcan
        </div>
    </x-slot>

    <div class="py-8"><div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
        <div class="space-y-4">
            @forelse ($materials as $material)
                <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:flex sm:items-center sm:justify-between sm:gap-6">
                    <div class="min-w-0">
                        <a href="{{ route('materials.show', $material) }}" class="font-bold text-slate-900 hover:text-red-600">{{ $material->title }}</a>
                        <p class="mt-1 line-clamp-2 text-sm text-slate-500">{{ $material->description ?: 'Tidak ada deskripsi.' }}</p>
                        <p class="mt-2 text-xs text-slate-400">Diunggah oleh {{ $material->uploader?->name ?? 'Sistem' }} · {{ $material->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="mt-4 flex shrink-0 flex-wrap items-center gap-3 sm:mt-0">
                        <a href="{{ route('materials.download', $material) }}" class="rounded-lg border border-slate-300 px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Unduh</a>
                        @can('update', $material)
                            <a href="{{ route('materials.edit', $material) }}" class="text-sm font-semibold text-blue-600">Edit</a>
                        @endcan
                        @can('delete', $material)
                            <form method="POST" action="{{ route('materials.destroy', $material) }}" onsubmit="return confirm('Hapus materi dan file ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm font-semibold text-red-600">Hapus</button>
                            </form>
                        @endcan
                    </div>
                </article>
            @empty
                <div class="rounded-xl border border-dashed border-slate-300 bg-white px-6 py-14 text-center">
                    <p class="font-semibold text-slate-700">Belum ada materi</p>
                    <p class="mt-1 text-sm text-slate-500">Materi yang diunggah akan tampil di halaman ini.</p>
                </div>
            @endforelse
        </div>
        @if (method_exists($materials, 'links'))<div class="mt-6">{{ $materials->links() }}</div>@endif
    </div></div>
</x-app-layout>
