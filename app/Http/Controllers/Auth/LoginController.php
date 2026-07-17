<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class LoginController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Auth/Login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'username' => 'Username atau password salah.',
            ]);
        }

        $request->session()->regenerate();

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->status !== 'aktif') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'username' => 'Akun Anda tidak aktif. Silakan hubungi admin.',
            ]);
        }

        if ($user->hasRole('siswa')) {
            return Inertia::location('/siswa');
        }

        return Inertia::location('/admin');
    }
}
