<nav x-data="{ open: false }" class="border-b border-slate-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between">
            <div class="flex min-w-0">
                <div class="flex shrink-0 items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                        <x-application-logo class="block h-9 w-auto fill-current text-red-600" />
                        <span class="hidden text-sm font-bold tracking-wide text-slate-800 lg:block">Sistem Akademik</span>
                    </a>
                </div>

                <div class="hidden space-x-7 sm:-my-px sm:ms-8 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        Dashboard
                    </x-nav-link>

                    <x-nav-link :href="route('courses.index')" :active="request()->routeIs('courses.*', 'materials.*')">
                        Mata Kuliah
                    </x-nav-link>

                    @if (Auth::user()->isAdmin())
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                            Pengguna
                        </x-nav-link>
                        <x-nav-link :href="route('admin.courses.index')" :active="request()->routeIs('admin.courses.*')">
                            Kelola Mata Kuliah
                        </x-nav-link>
                    @endif

                    @if (Auth::user()->isMahasiswa())
                        <x-nav-link :href="route('student.grades.index')" :active="request()->routeIs('student.grades.*')">
                            Nilai Saya
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <span class="me-3 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-600">
                    {{ Auth::user()->role->label() }}
                </span>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-slate-600 transition hover:text-slate-900 focus:outline-none">
                            <span class="max-w-40 truncate">{{ Auth::user()->name }}</span>
                            <svg class="ms-2 h-4 w-4 fill-current" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">Profil</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                Keluar
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center rounded-md p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-700" aria-label="Buka navigasi">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden border-t border-slate-100 sm:hidden">
        <div class="space-y-1 pb-3 pt-2">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('courses.index')" :active="request()->routeIs('courses.*', 'materials.*')">Mata Kuliah</x-responsive-nav-link>

            @if (Auth::user()->isAdmin())
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">Pengguna</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.courses.index')" :active="request()->routeIs('admin.courses.*')">Kelola Mata Kuliah</x-responsive-nav-link>
            @endif

            @if (Auth::user()->isMahasiswa())
                <x-responsive-nav-link :href="route('student.grades.index')" :active="request()->routeIs('student.grades.*')">Nilai Saya</x-responsive-nav-link>
            @endif
        </div>

        <div class="border-t border-slate-200 pb-1 pt-4">
            <div class="px-4">
                <div class="text-base font-medium text-slate-800">{{ Auth::user()->name }}</div>
                <div class="text-sm font-medium text-slate-500">{{ Auth::user()->email }} · {{ Auth::user()->role->label() }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">Profil</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">Keluar</x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
