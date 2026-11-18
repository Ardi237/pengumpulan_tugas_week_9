<x-app-layout>
    <x-slot name="header"><div><a href="{{ route('courses.grades.index', $course) }}" class="text-sm font-medium text-red-600">← Kembali</a><h1 class="mt-1 text-2xl font-bold text-slate-900">Tambah Nilai</h1></div></x-slot>
    <div class="py-8"><div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('courses.grades.store', $course) }}" class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            @csrf
            <div class="grid gap-6 md:grid-cols-2">
                <div class="md:col-span-2"><x-input-label for="student_id" value="Mahasiswa" /><select id="student_id" name="student_id" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-red-500 focus:ring-red-500" required><option value="">Pilih mahasiswa</option>@foreach ($students as $student)<option value="{{ $student->id }}" @selected(old('student_id') == $student->id)>{{ $student->name }} — {{ $student->email }}</option>@endforeach</select><x-input-error :messages="$errors->get('student_id')" class="mt-2" /></div>
                <div><x-input-label for="assessment_name" value="Nama penilaian" /><x-text-input id="assessment_name" name="assessment_name" class="mt-1 block w-full" :value="old('assessment_name')" placeholder="Contoh: UTS" required /><x-input-error :messages="$errors->get('assessment_name')" class="mt-2" /></div>
                <div><x-input-label for="score" value="Nilai (0–100)" /><x-text-input id="score" name="score" type="number" step="0.01" min="0" max="100" class="mt-1 block w-full" :value="old('score')" required /><x-input-error :messages="$errors->get('score')" class="mt-2" /></div>
                <div class="md:col-span-2"><x-input-label for="notes" value="Catatan (opsional)" /><textarea id="notes" name="notes" rows="4" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-red-500 focus:ring-red-500">{{ old('notes') }}</textarea><x-input-error :messages="$errors->get('notes')" class="mt-2" /></div>
            </div>
            <div class="mt-8 flex justify-end gap-3"><a href="{{ route('courses.grades.index', $course) }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700">Batal</a><button type="submit" class="rounded-lg bg-red-600 px-5 py-2 text-sm font-semibold text-white">Simpan Nilai</button></div>
        </form>
    </div></div>
</x-app-layout>
