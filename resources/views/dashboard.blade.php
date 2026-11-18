<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-medium text-red-600">{{ auth()->user()->role->label() }}</p>
                <h1 class="text-2xl font-bold text-slate-900">Dashboard Akademik</h1>
            </div>
            <p class="text-sm text-slate-500">Selamat datang, {{ auth()->user()->name }}</p>
        </div>
    </x-slot>

    @php
        $dashboardStats = $stats ?? [];
        $statLabels = [
            'users' => 'Total Pengguna',
            'lecturers' => 'Dosen',
            'students' => 'Mahasiswa',
            'courses' => 'Mata Kuliah',
            'materials' => 'Materi',
            'grades' => 'Nilai',
        ];
    @endphp

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if (count($dashboardStats))
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($dashboardStats as $key => $value)
                        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                            <p class="text-sm font-medium text-slate-500">{{ $statLabels[$key] ?? str($key)->headline() }}</p>
                            <p class="mt-2 text-3xl font-bold text-slate-900">{{ $value }}</p>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                    <div>
                        <h2 class="font-semibold text-slate-900">Mata Kuliah</h2>
                        <p class="text-sm text-slate-500">Daftar mata kuliah sesuai hak akses Anda</p>
                    </div>
                    <a href="{{ route('courses.index') }}" class="text-sm font-semibold text-red-600 hover:text-red-700">Lihat semua</a>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse (($courses ?? collect())->take(5) as $course)
                        <a href="{{ route('courses.show', $course) }}" class="flex items-center justify-between gap-4 px-6 py-4 transition hover:bg-slate-50">
                            <div class="min-w-0">
                                <p class="truncate font-semibold text-slate-900">{{ $course->name }}</p>
                                <p class="text-sm text-slate-500">{{ $course->code }} · {{ $course->lecturer?->name ?? 'Belum ada dosen' }}</p>
                            </div>
                            <span class="text-sm font-medium text-red-600">Buka</span>
                        </a>
                    @empty
                        <div class="px-6 py-10 text-center text-sm text-slate-500">Belum ada mata kuliah yang dapat ditampilkan.</div>
                    @endforelse
                </div>
            </div>

            @if (isset($recentMaterials) || isset($recentGrades))
                <div class="grid gap-6 lg:grid-cols-2">
                    @isset($recentMaterials)
                        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                            <h2 class="font-semibold text-slate-900">Materi Terbaru</h2>
                            <div class="mt-4 space-y-3">
                                @forelse ($recentMaterials as $material)
                                    <a href="{{ route('materials.show', $material) }}" class="block rounded-lg bg-slate-50 p-3 hover:bg-slate-100">
                                        <p class="font-medium text-slate-900">{{ $material->title }}</p>
                                        <p class="text-xs text-slate-500">{{ $material->course?->name }}</p>
                                    </a>
                                @empty
                                    <p class="text-sm text-slate-500">Belum ada materi.</p>
                                @endforelse
                            </div>
                        </section>
                    @endisset

                    @isset($recentGrades)
                        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                            <h2 class="font-semibold text-slate-900">Nilai Terbaru</h2>
                            <div class="mt-4 space-y-3">
                                @forelse ($recentGrades as $grade)
                                    <a href="{{ route('student.grades.show', $grade) }}" class="flex items-center justify-between rounded-lg bg-slate-50 p-3 hover:bg-slate-100">
                                        <div>
                                            <p class="font-medium text-slate-900">{{ $grade->assessment_name }}</p>
                                            <p class="text-xs text-slate-500">{{ $grade->course?->name }}</p>
                                        </div>
                                        <span class="text-lg font-bold text-red-600">{{ number_format((float) $grade->score, 2) }}</span>
                                    </a>
                                @empty
                                    <p class="text-sm text-slate-500">Belum ada nilai.</p>
                                @endforelse
                            </div>
                        </section>
                    @endisset
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
