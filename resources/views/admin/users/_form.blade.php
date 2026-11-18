@php($editing = isset($user))

<div class="grid gap-6 md:grid-cols-2">
    <div class="md:col-span-2">
        <x-input-label for="name" value="Nama lengkap" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name ?? '')" required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="email" value="Alamat email" />
        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email ?? '')" required />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="role" value="Role pengguna" />
        <select id="role" name="role" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-red-500 focus:ring-red-500" required>
            @foreach (['admin' => 'Admin', 'dosen' => 'Dosen', 'mahasiswa' => 'Mahasiswa'] as $value => $label)
                <option value="{{ $value }}" @selected(old('role', isset($user) ? $user->role->value : 'mahasiswa') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('role')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="password" :value="$editing ? 'Password baru (opsional)' : 'Password'" />
        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" :required="! $editing" autocomplete="new-password" />
        <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="password_confirmation" value="Konfirmasi password" />
        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" :required="! $editing" autocomplete="new-password" />
    </div>
</div>

<div class="mt-8 flex flex-wrap items-center justify-end gap-3">
    <a href="{{ route('admin.users.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</a>
    <button type="submit" class="rounded-lg bg-red-600 px-5 py-2 text-sm font-semibold text-white hover:bg-red-700">
        {{ $editing ? 'Simpan Perubahan' : 'Buat Pengguna' }}
    </button>
</div>
