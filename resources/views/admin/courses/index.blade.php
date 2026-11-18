<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-red-600">Administrasi</p>
                <h1 class="text-2xl font-bold text-slate-900">Kelola Mata Kuliah</h1>
            </div>
            <a href="{{ route('admin.courses.create') }}" class="rounded-lg bg-red-600 px-4 py-2.5 text-center text-sm font-semibold text-white hover:bg-red-700">Tambah Mata Kuliah</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Mata kuliah</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Dosen</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Mahasiswa</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($courses as $course)
                                <tr>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('admin.courses.show', $course) }}" class="font-semibold text-slate-900 hover:text-red-600">{{ $course->name }}</a>
                                        <p class="text-sm text-slate-500">{{ $course->code }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $course->lecturer?->name ?? '—' }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $course->students_count ?? $course->students?->count() ?? 0 }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-end gap-3">
                                            <a href="{{ route('admin.courses.edit', $course) }}" class="text-sm font-semibold text-blue-600">Edit</a>
                                            <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" onsubmit="return confirm('Hapus mata kuliah beserta data terkait?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="text-sm font-semibold text-red-600" type="submit">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-6 py-12 text-center text-sm text-slate-500">Belum ada mata kuliah.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if (method_exists($courses, 'links'))<div class="mt-6">{{ $courses->links() }}</div>@endif
        </div>
    </div>
</x-app-layout>
