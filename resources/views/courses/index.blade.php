<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm font-medium text-red-600">Pembelajaran</p>
            <h1 class="text-2xl font-bold text-slate-900">Mata Kuliah Saya</h1>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($courses as $course)
                    <article class="flex flex-col rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="flex items-start justify-between gap-4">
                            <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-bold text-red-700">{{ $course->code }}</span>
                            <span class="text-xs text-slate-500">{{ $course->students_count ?? $course->students?->count() ?? 0 }} mahasiswa</span>
                        </div>
                        <h2 class="mt-4 text-lg font-bold text-slate-900">{{ $course->name }}</h2>
                        <p class="mt-1 text-sm text-slate-500">Dosen: {{ $course->lecturer?->name ?? 'Belum ditentukan' }}</p>
                        <p class="mt-3 line-clamp-3 flex-1 text-sm leading-6 text-slate-600">{{ $course->description ?: 'Belum ada deskripsi mata kuliah.' }}</p>
                        <a href="{{ route('courses.show', $course) }}" class="mt-5 inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700">
                            Buka Mata Kuliah
                        </a>
                    </article>
                @empty
                    <div class="col-span-full rounded-xl border border-dashed border-slate-300 bg-white px-6 py-14 text-center">
                        <p class="font-semibold text-slate-700">Belum ada mata kuliah</p>
                        <p class="mt-1 text-sm text-slate-500">Mata kuliah yang sesuai dengan role Anda akan tampil di sini.</p>
                    </div>
                @endforelse
            </div>

            @if (method_exists($courses, 'links'))
                <div class="mt-6">{{ $courses->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
