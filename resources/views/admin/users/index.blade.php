<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-red-600">Administrasi</p>
                <h1 class="text-2xl font-bold text-slate-900">Manajemen Pengguna</h1>
            </div>
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center justify-center rounded-lg bg-red-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-red-700">Tambah Pengguna</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Pengguna</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Role</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($users as $user)
                                <tr>
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-slate-900">{{ $user->name }}</p>
                                        <p class="text-sm text-slate-500">{{ $user->email }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">{{ $user->role->label() }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-end gap-3">
                                            <a href="{{ route('admin.users.edit', $user) }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800">Edit</a>
                                            @if ($user->isNot(auth()->user()))
                                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Hapus pengguna ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-800">Hapus</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="px-6 py-12 text-center text-sm text-slate-500">Belum ada pengguna.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if (method_exists($users, 'links'))
                <div class="mt-6">{{ $users->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
