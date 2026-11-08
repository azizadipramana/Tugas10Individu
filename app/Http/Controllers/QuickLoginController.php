<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuickLoginController extends Controller
{
    /**
     * Handle instant one-click login for testing/demo purposes.
     */
    public function login(Request $request, string $role): RedirectResponse
    {
        $email = match (strtolower($role)) {
            'admin' => 'admin@akademik.its.ac.id',
            'staf', 'staff' => 'staf@akademik.its.ac.id',
            default => null,
        };

        if (! $email) {
            return redirect()->route('login')->withErrors(['email' => 'Peran akun uji coba tidak dikenali.']);
        }

        $user = User::where('email', $email)->first();

        if (! $user) {
            return redirect()->route('login')->withErrors(['email' => 'Akun uji coba belum tersedia. Silakan jalankan seeder terlebih dahulu.']);
        }

        Auth::login($user);
        $request->session()->regenerate();

        $roleName = $user->getRoleNames()->first() ?? 'Pengguna';

        return redirect()->intended(route('dashboard'))
            ->with('status', "🚀 Berhasil login instan sebagai **{$roleName}** ({$user->email}). Selamat menguji otorisasi RBAC!");
    }
}
