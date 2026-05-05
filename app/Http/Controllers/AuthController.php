<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DapodikService;

class AuthController extends Controller
{
    protected DapodikService $dapodikService;

    public function __construct(DapodikService $dapodikService)
    {
        $this->dapodikService = $dapodikService;
    }

    public function showLoginForm()
    {
        // If already logged in, redirect to dashboard
        if (session()->has('logged_in')) {
            return redirect()->route('dashboard');
        }

        $settings = \App\Models\Setting::pluck('value', 'key')->toArray();
        $isCloud = config('database.connections.pgsql.host') !== '127.0.0.1';

        return view('login', compact('settings', 'isCloud'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $username = $request->username;
        $password = $request->password;

        // 1. Check Hardcoded Admin
        if ($username === 'administrator' && $password === 'administrator') {
            session(['logged_in' => true, 'username' => 'administrator', 'role' => 'superadmin', 'nama' => 'Super Administrator']);
            return redirect()->route('dashboard');
        }

        // 2. Check Dapodik Users & GTK
        $found = $this->dapodikService->findUser($username);

        if ($found) {
            $userData = $found['data'];
            $type = $found['type'];

            // Logic Password: 
            // 1. '123456' (Default)
            // 2. Identitas itu sendiri (Username/NUPTK/NIK)
            $isValidPassword = ($password === '123456' || $password === $username);

            if ($isValidPassword) {
                // Tentukan Role secara lebih akurat
                $peranStr = strtolower($userData['peran_id_str'] ?? '');
                $peranId = $userData['peran_id'] ?? 0;

                // Admin jika: peran adalah Administrator (1) atau Operator (9)
                $isAdmin = ($peranId == 1 || $peranId == 9 || str_contains($peranStr, 'admin') || str_contains($peranStr, 'operator'));
                
                // Guru jika: ditemukan di PTK list, atau peran mengandung kata guru/pendidik
                $isGuru = $type === 'ptk' || str_contains($peranStr, 'guru') || str_contains($peranStr, 'pendidik') || $peranId == 2;

                $sessionData = [
                    'logged_in' => true,
                    'username' => $username,
                    'nama' => $userData['nama'] ?? ($userData['username'] ?? $username),
                    'role' => $isAdmin ? 'admin' : 'guru', // Default ke guru jika bukan admin
                    'ptk_id' => $userData['ptk_id'] ?? null,
                ];

                session($sessionData);
                return redirect()->route('dashboard');
            }
        }

        return back()->withErrors(['auth' => 'Username atau Password salah!'])->withInput();
    }

    public function logout()
    {
        session()->forget(['logged_in', 'username', 'nama', 'role', 'ptk_id']);
        return redirect()->route('login');
    }
}
