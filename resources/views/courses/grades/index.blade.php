<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div><a href="{{ route('courses.show', $course) }}" class="text-sm font-medium text-red-600">← {{ $course->name }}</a><h1 class="mt-1 text-2xl font-bold text-slate-900">Nilai Mahasiswa</h1><p class="text-sm text-slate-500">{{ $course->code }}</p></div>
            @can('create', [App\Models\Grade::class, $course])
                <a href="{{ route('courses.grades.create', $course) }}" class="rounded-lg bg-red-600 px-4 py-2.5 text-center text-sm font-semibold text-white hover:bg-red-700">Tambah Nilai</a>
            @endcan
        </div>
    </x-slot>

    <div class="py-8"><div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50"><tr><th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-500">Mahasiswa</th><th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-500">Penilaian</th><th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-500">Nilai</th><th class="px-6 py-3 text-right text-xs font-semibold uppercase text-slate-500">Aksi</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($grades as $grade)
                            <tr>
                                <td class="px-6 py-4"><p class="font-semibold text-slate-900">{{ $grade->student?->name }}</p><p class="text-sm text-slate-500">{{ $grade->student?->email }}</p></td>
                                <td class="px-6 py-4 text-sm text-slate-700">{{ $grade->assessment_name }}</td>
                                <td class="px-6 py-4"><span class="inline-flex min-w-16 justify-center rounded-lg bg-slate-900 px-3 py-1.5 text-sm font-bold text-white">{{ number_format((float) $grade->score, 2) }}</span></td>
                                <td class="px-6 py-4"><div class="flex justify-end gap-3"><a href="{{ route('grades.edit', $grade) }}" class="text-sm font-semibold text-blue-600">Edit</a><form method="POST" action="{{ route('grades.destroy', $grade) }}" onsubmit="return confirm('Hapus nilai ini?')">@csrf @method('DELETE')<button type="submit" class="text-sm font-semibold text-red-600">Hapus</button></form></div></td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-12 text-center text-sm text-slate-500">Belum ada nilai.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if (method_exists($grades, 'links'))<div class="mt-6">{{ $grades->links() }}</div>@endif
    </div></div>
</x-app-layout>
