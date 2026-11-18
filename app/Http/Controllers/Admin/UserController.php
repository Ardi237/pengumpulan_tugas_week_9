<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query()->withCount(['taughtCourses', 'courses']);

        if ($search = trim((string) $request->query('search'))) {
            $query->where(function ($users) use ($search): void {
                $users
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($role = $request->query('role')) {
            if (UserRole::tryFrom((string) $role) !== null) {
                $query->role((string) $role);
            }
        }

        $users = $query->orderBy('name')->paginate(15)->withQueryString();
        $roles = UserRole::cases();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create(): View
    {
        $roles = UserRole::cases();

        return view('admin.users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['email'] = Str::lower($validated['email']);

        User::query()->create($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Pengguna berhasil dibuat.');
    }

    public function edit(User $user): View
    {
        $roles = UserRole::cases();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();
        $validated['email'] = Str::lower($validated['email']);

        if (blank($validated['password'] ?? null)) {
            unset($validated['password']);
        }

        if ($request->user()->is($user) && $validated['role'] !== $user->role->value) {
            return back()
                ->withInput()
                ->with('error', 'Anda tidak dapat mengubah role akun sendiri.');
        }

        if (
            $user->isAdmin()
            && $validated['role'] !== UserRole::ADMIN->value
            && User::query()->role(UserRole::ADMIN)->count() <= 1
        ) {
            return back()
                ->withInput()
                ->with('error', 'Sistem harus memiliki minimal satu admin.');
        }

        $user->update($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($request->user()->is($user)) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        if ($user->isAdmin() && User::query()->role(UserRole::ADMIN)->count() <= 1) {
            return back()->with('error', 'Sistem harus memiliki minimal satu admin.');
        }

        if ($user->taughtCourses()->exists()) {
            return back()->with('error', 'Dosen masih menjadi pengampu mata kuliah dan tidak dapat dihapus.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
