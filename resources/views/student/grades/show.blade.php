<x-app-layout>
    <x-slot name="header"><div><a href="{{ route('student.grades.index') }}" class="text-sm font-medium text-red-600">← Semua nilai</a><h1 class="mt-1 text-2xl font-bold text-slate-900">Detail Nilai</h1></div></x-slot>
    <div class="py-8"><div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
        <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="bg-slate-900 px-6 py-8 text-white sm:px-10"><p class="text-sm font-semibold text-red-300">{{ $grade->course?->code }} · {{ $grade->course?->name }}</p><h2 class="mt-2 text-2xl font-bold">{{ $grade->assessment_name }}</h2></div>
            <div class="grid gap-6 p-6 sm:grid-cols-[1fr_auto] sm:p-10">
                <div><p class="text-sm font-semibold text-slate-500">Catatan dosen</p><p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-700">{{ $grade->notes ?: 'Tidak ada catatan.' }}</p><p class="mt-5 text-xs text-slate-400">Diperbarui {{ $grade->updated_at->format('d M Y, H:i') }}</p></div>
                <div class="flex h-32 w-32 flex-col items-center justify-center rounded-2xl bg-red-50 text-red-700"><span class="text-xs font-bold uppercase tracking-wide">Nilai</span><span class="mt-1 text-4xl font-black">{{ number_format((float) $grade->score, 2) }}</span></div>
            </div>
        </article>
    </div></div>
</x-app-layout>
