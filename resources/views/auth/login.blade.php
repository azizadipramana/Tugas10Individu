<x-guest-layout>
    <div class="mb-4 text-center">
        <h2 class="text-2xl font-bold text-gray-800">Sistem Informasi Akademik</h2>
        <p class="text-sm text-gray-600 mt-1">Portal Akademik Mandiri — RBAC & Dashboard Integration</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- TANTANGAN TAMBAHAN: Tombol Auto-Login Test Accounts untuk Evaluasi Demo Kelas -->
    <div class="mb-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg shadow-sm">
        <div class="flex items-center mb-2">
            <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
            <h3 class="text-sm font-semibold text-gray-800">Uji Coba Cepat (Evaluasi Dosen / Asdos)</h3>
        </div>
        <p class="text-xs text-gray-600 mb-3">Klik tombol di bawah ini untuk langsung masuk otomatis tanpa perlu mengetik kredensial:</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
            <a href="{{ route('quick.login', 'admin') }}" 
               class="inline-flex items-center justify-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-md shadow transition duration-150">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                🚀 Login sebagai ADMIN (Akses Penuh)
            </a>
            <a href="{{ route('quick.login', 'staf') }}" 
               class="inline-flex items-center justify-center px-3 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold rounded-md shadow transition duration-150">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                👤 Login sebagai STAF (Akses Terbatas)
            </a>
        </div>
        <div class="mt-3 pt-2 border-t border-blue-100 flex justify-between items-center text-[11px] text-gray-500">
            <span>Admin: <code>admin@akademik.its.ac.id</code> | Pass: <code>password</code></span>
            <span>Staf: <code>staf@akademik.its.ac.id</code></span>
        </div>
    </div>

    <div class="relative my-4">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-300"></div>
        </div>
        <div class="relative flex justify-center text-xs">
            <span class="bg-white px-2 text-gray-500 uppercase tracking-wider">Atau Masuk Manual</span>
        </div>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Pengguna')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Kata Sandi')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Ingat Saya') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-4">
            <div class="text-xs text-gray-500 space-x-2">
                <button type="button" onclick="document.getElementById('email').value='admin@akademik.its.ac.id'; document.getElementById('password').value='password';" class="text-indigo-600 hover:underline">
                    Isi Admin
                </button>
                <span>&bull;</span>
                <button type="button" onclick="document.getElementById('email').value='staf@akademik.its.ac.id'; document.getElementById('password').value='password';" class="text-indigo-600 hover:underline">
                    Isi Staf
                </button>
            </div>

            <x-primary-button class="ms-3">
                {{ __('Masuk Portal') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
