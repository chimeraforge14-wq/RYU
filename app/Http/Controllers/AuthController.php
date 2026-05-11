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

    public function showRegistrationForm()
    {
        return view('register');
    }

    public function registerAdmin(Request $request)
    {
        $request->validate([
            'npsn' => 'required|string',
            'registration_code' => 'required|string',
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Cari sekolah berdasarkan NPSN dan Kode Registrasi
        $school = \App\Models\School::where('npsn', $request->npsn)
                    ->where('registration_code', $request->registration_code)
                    ->first();

        if (!$school) {
            return back()->withErrors(['registration_code' => 'NPSN atau Kode Registrasi tidak valid. Silakan hubungi Superadmin.'])->withInput();
        }

        // Buat user admin
        \App\Models\User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'admin',
            'school_id' => $school->id,
        ]);

        return redirect()->route('login')->with('success', 'Pendaftaran Admin berhasil! Silakan login.');
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
        $npsn = $request->npsn;

        // 1. Check Hardcoded Superadmin FIRST
        // Superadmin doesn't need NPSN
        if ($username === 'administrator' && $password === 'administrator') {
            session([
                'logged_in' => true, 
                'username' => 'administrator', 
                'role' => 'superadmin', 
                'nama' => 'Super Administrator',
                'school_id' => null,
                'npsn' => $npsn,
                'registration_code' => null
            ]);
            return redirect()->route('dashboard');
        }

        // For all other roles, NPSN is MANDATORY
        if (!$npsn) {
            return back()->withErrors(['auth' => 'NPSN Sekolah wajib diisi untuk login Guru atau Admin.'])->withInput();
        }

        // Tentukan context NPSN untuk session
        session(['npsn' => $npsn]);

        // 2. Cari atau buat record School berdasarkan NPSN
        $school = \App\Models\School::where('npsn', $npsn)->first();

        // 3. Check Database Users (Manual Registered Admins)
        $dbUser = \App\Models\User::where('username', $username)
                    ->where('school_id', $school ? $school->id : null)
                    ->first();

        if ($dbUser && \Illuminate\Support\Facades\Hash::check($password, $dbUser->password)) {
            // Device Lock for Admin
            if ($dbUser->role === 'admin' && $school) {
                $incomingDeviceId = $request->input('device_id');
                if (!$school->admin_device_id) {
                    $school->update(['admin_device_id' => $incomingDeviceId]);
                } elseif ($school->admin_device_id !== $incomingDeviceId) {
                    return back()->withErrors([
                        'auth' => 'Akun Admin terkunci pada perangkat lain.'
                    ])->with('show_reset_device', true)->withInput();
                }
            }

            session([
                'logged_in' => true,
                'username' => $dbUser->username,
                'nama' => $dbUser->name,
                'role' => $dbUser->role,
                'school_id' => $dbUser->school_id,
                'npsn' => $npsn,
                'registration_code' => $school ? $school->registration_code : null
            ]);
            return redirect()->route('dashboard');
        }

        // 4. Check Dapodik Users & GTK via Service (Guru/Operator)
        // findUser akan menggunakan context session('npsn') yang baru saja kita set
        $found = $this->dapodikService->findUser($username);

        if ($found) {
            $userData = $found['data'];
            $type = $found['type'];

            $isValidPassword = ($password === '123456' || $password === $username);

            if ($isValidPassword) {
                $peranStr = strtolower($userData['peran_id_str'] ?? '');
                $peranId = $userData['peran_id'] ?? 0;

                $isAdmin = ($peranId == 1 || $peranId == 9 || str_contains($peranStr, 'admin') || str_contains($peranStr, 'operator'));
                
                // Device Lock for Admin
                if ($isAdmin && $school) {
                    $incomingDeviceId = $request->input('device_id');
                    
                    if (!$school->admin_device_id) {
                        // First time login - bind device
                        $school->update(['admin_device_id' => $incomingDeviceId]);
                    } elseif ($school->admin_device_id !== $incomingDeviceId) {
                        // Different device detected
                        return back()->withErrors([
                            'auth' => 'Akun Admin terkunci pada perangkat lain. Silakan hubungi Superadmin atau masukkan Kode Registrasi untuk mereset perangkat.'
                        ])->with('show_reset_device', true)->withInput();
                    }
                }

                $sessionData = [
                    'logged_in' => true,
                    'username' => $username,
                    'nama' => $userData['nama'] ?? ($userData['username'] ?? $username),
                    'role' => $isAdmin ? 'admin' : 'guru',
                    'ptk_id' => $userData['ptk_id'] ?? null,
                    'school_id' => $school ? $school->id : null,
                    'npsn' => $npsn,
                    'registration_code' => $school ? $school->registration_code : null
                ];

                session($sessionData);
                return redirect()->route('dashboard');
            }
        }

        return back()->withErrors(['auth' => 'NPSN, Username atau Password salah!'])->withInput();
    }

    public function resetDevice(Request $request)
    {
        $request->validate([
            'npsn' => 'required|string',
            'registration_code' => 'required|string',
            'device_id' => 'required|string',
        ]);

        $school = \App\Models\School::where('npsn', $request->npsn)
                    ->where('registration_code', $request->registration_code)
                    ->first();

        if ($school) {
            $school->update(['admin_device_id' => $request->device_id]);
            return back()->with('success', 'Perangkat berhasil di-reset! Silakan login kembali.');
        }

        return back()->withErrors(['auth' => 'NPSN atau Kode Registrasi tidak valid.']);
    }

    public function logout()
    {
        session()->forget(['logged_in', 'username', 'nama', 'role', 'ptk_id', 'school_id', 'npsn', 'registration_code']);
        return redirect()->route('login');
    }
}
