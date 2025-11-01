<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Menampilkan halaman login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses login - VERSI DATABASE
     */
    public function login(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Cari user di database by email
        $user = User::where('email', $request->email)->first();

        // 3. Cek apakah user ada DAN password cocok
        if ($user && Hash::check($request->password, $user->password)) {
            // 4. LOGIN BERHASIL
            Auth::login($user);

            // 5. Redirect ke halaman users
            return redirect()->route('dashboard')->with('success', 'Login berhasil! Selamat datang ' . $user->name);
        }

        // 6. LOGIN GAGAL
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->withInput($request->only('email'));
    }

    /**
     * Proses logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Logout berhasil!');
    }
}