<x-app-layout>
    <x-slot name="header">
        <div>
            <a href="{{ route('admin.courses.show', $course) }}" class="text-sm font-medium text-red-600">← Kembali ke mata kuliah</a>
            <h1 class="mt-1 text-2xl font-bold text-slate-900">Mahasiswa · {{ $course->name }}</h1>
        </div>
    </x-slot>

    @php($enrolledStudents = $students ?? $course->students)

    <div class="py-8"><div class="mx-auto max-w-5xl space-y-6 px-4 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('admin.courses.students.store', $course) }}" class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            @csrf
            <h2 class="font-semibold text-slate-900">Tambahkan Mahasiswa</h2>
            <div class="mt-4 flex flex-col gap-3 sm:flex-row">
                <div class="flex-1">
                    <select name="student_id" class="block w-full rounded-md border-slate-300 shadow-sm focus:border-red-500 focus:ring-red-500" required>
                        <option value="">Pilih mahasiswa</option>
                        @foreach ($availableStudents as $student)
                            <option value="{{ $student->id }}" @selected(old('student_id') == $student->id)>{{ $student->name }} — {{ $student->email }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
                </div>
                <button type="submit" class="rounded-lg bg-red-600 px-5 py-2 text-sm font-semibold text-white hover:bg-red-700">Tambahkan</button>
            </div>
        </form>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-6 py-4"><h2 class="font-semibold text-slate-900">Mahasiswa Terdaftar</h2></div>
            <div class="divide-y divide-slate-100">
                @forelse ($enrolledStudents as $student)
                    <div class="flex items-center justify-between gap-4 px-6 py-4">
                        <div><p class="font-semibold text-slate-900">{{ $student->name }}</p><p class="text-sm text-slate-500">{{ $student->email }}</p></div>
                        <form method="POST" action="{{ route('admin.courses.students.destroy', [$course, $student]) }}" onsubmit="return confirm('Keluarkan mahasiswa dari kelas ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm font-semibold text-red-600">Keluarkan</button>
                        </form>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center text-sm text-slate-500">Belum ada mahasiswa terdaftar.</div>
                @endforelse
            </div>
        </div>
    </div></div>
</x-app-layout>
