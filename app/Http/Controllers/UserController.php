<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller implements HasMiddleware
{
    /**
     * Strictly guard user and role management endpoints to Admin role only.
     */
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('role:Admin'),
        ];
    }

    /**
     * Display a listing of users along with their roles.
     */
    public function index(): View
    {
        $users = User::with('roles')->latest()->get();
        $roles = Role::all();

        return view('users.index', compact('users', 'roles'));
    }

    /**
     * Store a newly created user and assign their role.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'exists:roles,name'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(),
        ]);

        $user->assignRole($validated['role']);

        return redirect()->route('users.index')
            ->with('success', "Akun pengguna {$user->name} berhasil dibuat dengan peran {$validated['role']}.");
    }

    /**
     * Update the assigned role for the specified user.
     */
    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', 'exists:roles,name'],
        ]);

        // Mencegah super admin mencabut role diri sendiri agar tidak terkunci
        if ($user->id === auth()->id() && $validated['role'] !== 'Admin') {
            return redirect()->route('users.index')
                ->withErrors(['role' => 'Demi keamanan sistem, Anda tidak dapat mencabut peran Admin dari akun Anda sendiri yang sedang aktif!']);
        }

        $user->syncRoles([$validated['role']]);

        return redirect()->route('users.index')
            ->with('success', "Peran untuk pengguna {$user->name} ({$user->email}) berhasil diperbarui menjadi {$validated['role']}.");
    }
}
