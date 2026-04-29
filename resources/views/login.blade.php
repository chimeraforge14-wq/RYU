<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - e-Rapor SD</title>
    <style>
        {!! file_get_contents(resource_path('css/app.css')) !!}
        
        body {
            background: radial-gradient(circle at top right, #1e293b, #0f172a);
            overflow: hidden;
        }

        .login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            width: 100%;
            background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 86c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zm66-3c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zm-46-45c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zm40 24c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm-11 30c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm-45-28c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm-24-32c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zM46 94c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm41-35c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zM21 2c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zM6 46c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zM25 64c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zm63 5c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zM53 48c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1zM77 8c.552 0 1-.448 1-1s-.448-1-1-1-1 .448-1 1 .448 1 1 1z' fill='%233b82f6' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
        }

        .login-card {
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 24px;
            padding: 3rem;
            width: 100%;
            max-width: 420px;
            backdrop-filter: blur(20px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--accent-gradient);
        }

        .form-group {
            margin-bottom: 1.5rem;
            text-align: left;
            position: relative;
        }

        .form-label {
            display: block;
            color: var(--text-secondary);
            font-size: 0.8rem;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 600;
        }

        .input-icon {
            position: absolute;
            left: 12px;
            top: 36px;
            color: var(--text-secondary);
        }

        .form-control {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 40px;
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,0.1);
            background: rgba(15, 23, 42, 0.5);
            color: white;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
        }

        .btn-login {
            width: 100%;
            padding: 1rem;
            border-radius: 10px;
            border: none;
            background: var(--accent-gradient);
            color: white;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: opacity 0.3s, transform 0.2s;
            margin-top: 1rem;
        }

        .btn-login:hover {
            opacity: 0.9;
        }

        .btn-login:active {
            transform: scale(0.98);
        }
        
        .error-message {
            color: #ef4444;
            background: rgba(239, 68, 68, 0.1);
            padding: 0.75rem;
            border-radius: 8px;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card animate-slide-up">
            <!-- Connection Badge -->
            <div style="position: absolute; top: 1.5rem; left: 1.5rem;">
                @if($isCloud)
                    <span style="font-size: 0.65rem; background: rgba(59, 130, 246, 0.1); color: #60a5fa; padding: 4px 10px; border-radius: 20px; border: 1px solid rgba(59,130,246,0.2); font-weight: 700; display: flex; align-items: center; gap: 0.4rem;">
                        <span style="width: 6px; height: 6px; background: #60a5fa; border-radius: 50%;"></span> CLOUD MODE
                    </span>
                @else
                    <span style="font-size: 0.65rem; background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 4px 10px; border-radius: 20px; border: 1px solid rgba(16,185,129,0.2); font-weight: 700; display: flex; align-items: center; gap: 0.4rem;">
                        <span style="width: 6px; height: 6px; background: #10b981; border-radius: 50%;"></span> LOCAL MODE
                    </span>
                @endif
            </div>

            <div style="display:flex; flex-direction: column; align-items:center; margin-bottom: 2rem; margin-top: 1rem;">
                @if(isset($settings['school_logo']))
                    <img src="{{ Storage::url($settings['school_logo']) }}" style="width: 80px; height: 80px; object-fit: contain; margin-bottom: 1.5rem;">
                @else
                    <div style="width: 80px; height: 80px; background: rgba(59, 130, 246, 0.1); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem; border: 1px solid rgba(59,130,246,0.2);">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--accent)"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"/></svg>
                    </div>
                @endif
                
                <h2 style="margin-bottom: 0.25rem; font-size: 1.5rem; letter-spacing: -0.02em;">
                    {{ $settings['school_name'] ?? 'e-Rapor SD Modern' }}
                </h2>
                <p style="color: var(--text-secondary); font-size: 0.875rem;">Sistem Penilaian Kurikulum Merdeka</p>
            </div>

            @if($errors->has('auth'))
                <div class="error-message">
                    {{ $errors->first('auth') }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Email / Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username..." value="{{ old('username') }}" required autofocus>
                </div>
                
                <div class="form-group" style="margin-bottom: 2rem;">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password..." required>
                </div>

                <button type="submit" class="btn-login" style="height: 55px; border-radius: 14px; font-weight: 700; letter-spacing: 0.02em;">MASUK KE DASHBOARD</button>
            </form>
            
            <p style="margin-top: 2rem; font-size: 0.75rem; color: var(--text-secondary);">
                &copy; {{ date('Y') }} e-Rapor SD Modern. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
