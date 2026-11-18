@php($editing = isset($course))

<div class="grid gap-6 md:grid-cols-2">
    <div>
        <x-input-label for="code" value="Kode mata kuliah" />
        <x-text-input id="code" name="code" type="text" class="mt-1 block w-full uppercase" :value="old('code', $course->code ?? '')" maxlength="20" required autofocus />
        <x-input-error :messages="$errors->get('code')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="name" value="Nama mata kuliah" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $course->name ?? '')" required />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div class="md:col-span-2">
        <x-input-label for="lecturer_id" value="Dosen pengampu" />
        <select id="lecturer_id" name="lecturer_id" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-red-500 focus:ring-red-500" required>
            <option value="">Pilih dosen</option>
            @foreach ($lecturers as $lecturer)
                <option value="{{ $lecturer->id }}" @selected((string) old('lecturer_id', $course->lecturer_id ?? '') === (string) $lecturer->id)>{{ $lecturer->name }} — {{ $lecturer->email }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('lecturer_id')" class="mt-2" />
    </div>

    <div class="md:col-span-2">
        <x-input-label for="description" value="Deskripsi" />
        <textarea id="description" name="description" rows="5" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-red-500 focus:ring-red-500">{{ old('description', $course->description ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>
</div>

<div class="mt-8 flex flex-wrap items-center justify-end gap-3">
    <a href="{{ route('admin.courses.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</a>
    <button type="submit" class="rounded-lg bg-red-600 px-5 py-2 text-sm font-semibold text-white hover:bg-red-700">{{ $editing ? 'Simpan Perubahan' : 'Buat Mata Kuliah' }}</button>
</div>
