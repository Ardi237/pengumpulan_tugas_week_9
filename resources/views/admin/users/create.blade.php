<x-app-layout>
    <x-slot name="header"><h1 class="text-2xl font-bold text-slate-900">Tambah Pengguna</h1></x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.users.store') }}" class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                @csrf
                @include('admin.users._form')
            </form>
        </div>
    </div>
</x-app-layout>
