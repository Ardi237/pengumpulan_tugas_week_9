<x-app-layout>
    <x-slot name="header"><div><p class="text-sm font-medium text-red-600">Akademik</p><h1 class="text-2xl font-bold text-slate-900">Nilai Saya</h1></div></x-slot>
    <div class="py-8"><div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
        <div class="space-y-4">
            @forelse ($grades as $grade)
                <a href="{{ route('student.grades.show', $grade) }}" class="flex flex-col gap-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-red-200 sm:flex-row sm:items-center sm:justify-between">
                    <div><p class="text-xs font-semibold uppercase tracking-wide text-red-600">{{ $grade->course?->code }}</p><h2 class="mt-1 font-bold text-slate-900">{{ $grade->assessment_name }}</h2><p class="text-sm text-slate-500">{{ $grade->course?->name }}</p></div>
                    <div class="flex items-center gap-4"><p class="text-xs text-slate-400">{{ $grade->updated_at->format('d M Y') }}</p><span class="inline-flex min-w-20 justify-center rounded-xl bg-slate-900 px-4 py-2 text-xl font-bold text-white">{{ number_format((float) $grade->score, 2) }}</span></div>
                </a>
            @empty
                <div class="rounded-xl border border-dashed border-slate-300 bg-white px-6 py-14 text-center"><p class="font-semibold text-slate-700">Belum ada nilai</p><p class="mt-1 text-sm text-slate-500">Nilai yang diberikan dosen akan tampil di sini.</p></div>
            @endforelse
        </div>
        @if (method_exists($grades, 'links'))<div class="mt-6">{{ $grades->links() }}</div>@endif
    </div></div>
</x-app-layout>
