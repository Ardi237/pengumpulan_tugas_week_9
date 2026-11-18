<x-app-layout>
    <x-slot name="header">
        <div><a href="{{ route('courses.materials.index', $course) }}" class="text-sm font-medium text-red-600">← Kembali</a><h1 class="mt-1 text-2xl font-bold text-slate-900">Unggah Materi</h1></div>
    </x-slot>

    <div class="py-8"><div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('courses.materials.store', $course) }}" enctype="multipart/form-data" class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            @csrf
            <div class="space-y-6">
                <div><x-input-label for="title" value="Judul materi" /><x-text-input id="title" name="title" class="mt-1 block w-full" :value="old('title')" required autofocus /><x-input-error :messages="$errors->get('title')" class="mt-2" /></div>
                <div><x-input-label for="description" value="Deskripsi" /><textarea id="description" name="description" rows="5" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-red-500 focus:ring-red-500">{{ old('description') }}</textarea><x-input-error :messages="$errors->get('description')" class="mt-2" /></div>
                <div>
                    <x-input-label for="file" value="File materi" />
                    <input id="file" name="file" type="file" class="mt-1 block w-full rounded-lg border border-slate-300 bg-white text-sm text-slate-600 file:me-4 file:border-0 file:bg-slate-100 file:px-4 file:py-2.5 file:font-semibold" accept=".pdf,.ppt,.pptx,.doc,.docx" required />
                    <p class="mt-1 text-xs text-slate-500">PDF, PPT, PPTX, DOC, atau DOCX. Maksimal 10 MB.</p>
                    <x-input-error :messages="$errors->get('file')" class="mt-2" />
                </div>
            </div>
            <div class="mt-8 flex justify-end gap-3"><a href="{{ route('courses.materials.index', $course) }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700">Batal</a><button class="rounded-lg bg-red-600 px-5 py-2 text-sm font-semibold text-white hover:bg-red-700" type="submit">Unggah</button></div>
        </form>
    </div></div>
</x-app-layout>
