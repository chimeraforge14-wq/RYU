<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-RAPOR MODERN - Auth</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        {!! file_get_contents(resource_path('css/app.css')) !!}
        
        :root {
            --bg-dark: #0f172a;
            --glass-bg: rgba(255, 255, 255, 0.03);
            --glass-border: 1px solid rgba(255, 255, 255, 0.08);
            --transition-auth: 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        body {
            overflow: hidden;
            background: var(--bg-primary);
            color: var(--text-primary);
            background-image: 
                radial-gradient(at 0% 0%, rgba(225, 29, 72, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(59, 130, 246, 0.05) 0px, transparent 50%);
        }

        .auth-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            width: 100%;
            position: relative;
            z-index: 1;
            padding: 1.5rem;
        }

        .auth-card {
            background: var(--card-bg);
            border: var(--glass-border);
            border-radius: 32px;
            padding: 2rem;
            width: 100%;
            max-width: 480px;
            max-height: 90vh;
            overflow-y: auto;
            overflow-x: hidden;
            backdrop-filter: blur(20px);
            box-shadow: var(--shadow-lg), var(--shadow-glow);
            text-align: center;
            position: relative;
            transition: max-width 0.5s ease;
        }

        /* Custom Scrollbar for Card */
        .auth-card::-webkit-scrollbar {
            width: 5px;
        }
        .auth-card::-webkit-scrollbar-track {
            background: transparent;
        }
        .auth-card::-webkit-scrollbar-thumb {
            background: rgba(225, 29, 72, 0.1);
            border-radius: 10px;
        }
        .auth-card::-webkit-scrollbar-thumb:hover {
            background: rgba(225, 29, 72, 0.2);
        }

        .auth-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--accent-gradient);
        }

        /* Toggle System */
        .auth-tabs {
            display: flex;
            background: rgba(0,0,0,0.03);
            padding: 4px;
            border-radius: 16px;
            margin-bottom: 1.25rem;
            position: relative;
            z-index: 2;
        }

        .auth-tab {
            flex: 1;
            padding: 0.75rem;
            border: none;
            background: none;
            color: var(--text-secondary);
            font-weight: 700;
            font-size: 0.8rem;
            letter-spacing: 0.05em;
            cursor: pointer;
            transition: all 0.3s;
            border-radius: 12px;
            position: relative;
            z-index: 1;
        }

        .auth-tab.active {
            color: white;
        }

        .tab-indicator {
            position: absolute;
            height: calc(100% - 8px);
            width: calc(50% - 4px);
            background: var(--accent-gradient);
            border-radius: 12px;
            top: 4px;
            left: 4px;
            transition: transform var(--transition-auth);
            box-shadow: 0 4px 12px rgba(225, 29, 72, 0.2);
        }

        .register-active .tab-indicator {
            transform: translateX(100%);
        }

        /* Forms Wrapper */
        .forms-wrapper {
            display: flex;
            width: 200%;
            transition: transform var(--transition-auth);
            align-items: flex-start;
        }

        .register-active .forms-wrapper {
            transform: translateX(-50%);
        }

        .form-section {
            width: 50%;
            padding: 0 0.5rem;
            transition: opacity 0.3s, transform 0.5s, height 0.5s ease;
            overflow: hidden;
        }

        .register-active .login-section { opacity: 0; transform: scale(0.95); pointer-events: none; }
        .login-active .register-section { opacity: 0; transform: scale(0.95); pointer-events: none; }

        .form-group {
            margin-bottom: 1.25rem;
            text-align: left;
            position: relative;
        }

        .form-label {
            display: block;
            color: var(--text-secondary);
            font-size: 0.75rem;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 700;
            padding-left: 4px;
        }

        .form-control {
            width: 100%;
            padding: 0.875rem 1.25rem;
            border-radius: 14px;
            border: 1px solid var(--border-color);
            background: var(--bg-tertiary);
            color: var(--text-primary);
            font-size: 0.95rem;
            transition: all 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 4px rgba(225, 29, 72, 0.1);
            background: white;
        }

        .btn-auth {
            width: 100%;
            padding: 1.1rem;
            border-radius: 14px;
            border: none;
            background: var(--accent-gradient);
            color: white;
            font-weight: 800;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 1rem;
            box-shadow: 0 8px 20px rgba(225, 29, 72, 0.2);
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .btn-auth:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(225, 29, 72, 0.3);
            filter: brightness(1.1);
        }

        .btn-auth:active { transform: translateY(0); }

        .alert {
            padding: 1rem;
            border-radius: 14px;
            margin-bottom: 2rem;
            font-size: 0.85rem;
            text-align: left;
            border: 1px solid transparent;
        }
        .alert-success { background: rgba(16,185,129,0.08); color: #10b981; border-color: rgba(16,185,129,0.15); }
        .alert-danger { background: rgba(239,68,68,0.08); color: #ef4444; border-color: rgba(239,68,68,0.15); }

        /* Custom Scrollbar for form if needed */
        .auth-card::-webkit-scrollbar { width: 4px; }
        .auth-card::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.1); border-radius: 2px; }
    </style>
</head>
<body class="login-active">
    <div class="auth-container">
        <div class="auth-card animate-slide-up">
            <!-- Connection Badge -->
            <div style="position: absolute; top: 1.5rem; left: 1.5rem;">
                @if($isCloud ?? false)
                    <span style="font-size: 0.65rem; background: rgba(59, 130, 246, 0.08); color: #3b82f6; padding: 4px 10px; border-radius: 20px; border: 1px solid rgba(59,130,246,0.15); font-weight: 700; display: flex; align-items: center; gap: 0.4rem;">
                        <span style="width: 6px; height: 6px; background: #3b82f6; border-radius: 50%;"></span> CLOUD
                    </span>
                @else
                    <span style="font-size: 0.65rem; background: rgba(16, 185, 129, 0.08); color: #10b981; padding: 4px 10px; border-radius: 20px; border: 1px solid rgba(16,185,129,0.15); font-weight: 700; display: flex; align-items: center; gap: 0.4rem;">
                        <span style="width: 6px; height: 6px; background: #10b981; border-radius: 50%;"></span> LOCAL
                    </span>
                @endif
            </div>

            <div style="display:flex; flex-direction: column; align-items:center; margin-bottom: 1.25rem; margin-top: 0.5rem;">
                @if(isset($settings['school_logo']))
                    <img src="{{ Storage::url($settings['school_logo']) }}" style="width: 64px; height: 64px; object-fit: contain; margin-bottom: 1.25rem;">
                @else
                    <div style="width: 64px; height: 64px; background: var(--accent-light); border-radius: 18px; display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem; border: 1px solid rgba(225, 29, 72, 0.1);">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--accent)"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"/></svg>
                    </div>
                @endif
                
                <h2 style="margin-bottom: 0.25rem; font-size: 1.35rem; letter-spacing: -0.03em; font-weight: 800; color: var(--text-primary);">
                    E-RAPOR MODERN
                </h2>
                <p style="color: var(--text-secondary); font-size: 0.85rem; font-weight: 500;">Sistem Penilaian Kurikulum Merdeka</p>
            </div>

            <div class="auth-tabs">
                <div class="tab-indicator"></div>
                <button class="auth-tab active" onclick="switchAuth('login')">MASUK</button>
                <button class="auth-tab" onclick="switchAuth('register')">DAFTAR</button>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any() && !$errors->has('auth'))
                <div class="alert alert-danger">
                    <ul style="margin: 0; padding-left: 1rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="forms-wrapper">
                <!-- Login Section -->
                <div class="form-section login-section">
                    @if($errors->has('auth'))
                        <div class="alert alert-danger">
                            {{ $errors->first('auth') }}
                            
                            @if(session('show_reset_device'))
                                <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(239,68,68,0.15);">
                                    <p style="margin-bottom: 0.75rem; font-weight: 700; font-size: 0.8rem;">Reset Binding Perangkat?</p>
                                    <form action="{{ route('login.reset_device') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="npsn" value="{{ old('npsn') }}">
                                        <input type="hidden" name="device_id" class="device_id_field">
                                        <div style="display: flex; gap: 0.5rem;">
                                            <input type="password" name="registration_code" placeholder="Kode Registrasi..." required 
                                                style="flex: 1; padding: 0.6rem; border-radius: 10px; border: 1px solid rgba(0,0,0,0.1); background: white; outline: none; font-size: 0.75rem;">
                                            <button type="submit" style="background: var(--accent); border: none; color: white; padding: 0.5rem 0.75rem; border-radius: 10px; font-weight: 700; cursor: pointer; font-size: 0.75rem;">
                                                Reset
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endif

                    <form action="{{ route('login.post') }}" method="POST" id="loginForm">
                        @csrf
                        <input type="hidden" name="device_id" class="device_id_field">
                        
                        <div class="form-group">
                            <label class="form-label">NPSN Sekolah</label>
                            <input type="text" name="npsn" class="form-control" placeholder="Masukkan NPSN..." value="{{ old('npsn') }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Username / Email</label>
                            <input type="text" name="username" class="form-control" placeholder="Email atau username..." value="{{ old('username') }}" required>
                        </div>
                        
                        <div class="form-group" style="margin-bottom: 1.25rem;">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                        </div>

                        <button type="submit" class="btn-auth">MASUK SEKARANG</button>
                    </form>
                </div>

                <!-- Register Section -->
                <div class="form-section register-section">
                    <form action="{{ route('register.post') }}" method="POST">
                        @csrf
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div class="form-group">
                                <label class="form-label">NPSN</label>
                                <input type="text" name="npsn" class="form-control" placeholder="NPSN..." value="{{ old('npsn') }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Kode Reg</label>
                                <input type="text" name="registration_code" class="form-control" placeholder="Kode..." value="{{ old('registration_code') }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" placeholder="Nama Anda..." value="{{ old('name') }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" placeholder="Username login..." value="{{ old('username') }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="email@sekolah.sch.id" value="{{ old('email') }}" required>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 0.5rem;">
                            <div class="form-group">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Min 6 char" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Konfirmasi</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi..." required>
                            </div>
                        </div>

                        <button type="submit" class="btn-auth">DAFTAR AKUN</button>
                    </form>
                </div>
            </div>

            <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid rgba(0,0,0,0.04);">
                <p style="font-size: 0.8rem; color: var(--text-secondary); opacity: 0.7; margin-bottom: 0.5rem;">
                    &copy; {{ date('Y') }} e-Rapor SD Modern. All rights reserved.
                </p>
                <div style="font-size: 0.7rem; font-weight: 800; color: var(--accent); letter-spacing: 0.1em; text-transform: uppercase; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                    <span style="width: 20px; height: 1px; background: var(--accent); opacity: 0.3;"></span>
                    POWERED BY 19.15 TEAM
                    <span style="width: 20px; height: 1px; background: var(--accent); opacity: 0.3;"></span>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchAuth(type) {
            const body = document.body;
            const tabs = document.querySelectorAll('.auth-tab');
            const card = document.querySelector('.auth-card');
            const loginSection = document.querySelector('.login-section');
            const registerSection = document.querySelector('.register-section');
            
            if (type === 'login') {
                body.classList.remove('register-active');
                body.classList.add('login-active');
                tabs[0].classList.add('active');
                tabs[1].classList.remove('active');
                card.style.maxWidth = '480px';
                
                // Height adjustment
                registerSection.style.height = '0';
                loginSection.style.height = 'auto';
            } else {
                body.classList.remove('login-active');
                body.classList.add('register-active');
                tabs[1].classList.add('active');
                tabs[0].classList.remove('active');
                card.style.maxWidth = '520px';
                
                // Height adjustment
                loginSection.style.height = '0';
                registerSection.style.height = 'auto';
            }
        }

        // Initialize Device Fingerprinting
        document.addEventListener('DOMContentLoaded', function() {
            let deviceId = localStorage.getItem('ryu_device_id');
            if (!deviceId) {
                deviceId = 'dev_' + Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
                localStorage.setItem('ryu_device_id', deviceId);
            }
            document.querySelectorAll('.device_id_field').forEach(field => {
                field.value = deviceId;
            });

            // Check query params for tab
            const urlParams = new URLSearchParams(window.location.search);
            const tabParam = urlParams.get('tab');
            if (tabParam === 'register') {
                switchAuth('register');
            }

            // Check if there are registration errors to automatically switch to register tab
            @if($errors->has('registration_code') || $errors->has('email') || $errors->has('username') || $errors->has('name') || $errors->has('password') || session('tab') === 'register')
                switchAuth('register');
            @endif
            // Initialize heights
            document.querySelector('.register-section').style.height = '0';
            document.querySelector('.login-section').style.height = 'auto';
        });
    </script>
</body>
</html>
