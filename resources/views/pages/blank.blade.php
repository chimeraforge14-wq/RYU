@extends('layouts.app')
@section('title', $title . ' - e-Rapor SD')
@section('header_title', $title)

@section('content')
<div class="animate-slide-up" style="background: var(--card-bg); border: var(--glass-border); border-radius: 16px; padding: 4rem 2rem; text-align: center; backdrop-filter: blur(10px);">
    <div style="width: 80px; height: 80px; background: rgba(59, 130, 246, 0.1); color: var(--accent); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem auto;">
        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
    </div>
    <h2 style="font-size: 1.5rem; font-weight: 600; margin-bottom: 0.5rem;">Fitur Sedang Dalam Pengembangan</h2>
    <p style="color: var(--text-secondary); max-width: 500px; margin: 0 auto 2rem auto;">
        Halaman <strong>{{ $title }}</strong> saat ini masih dalam tahap perangkaian logika sistem. Fitur ini akan diaktifkan secara penuh pada rilis pembaruan berikutnya.
    </p>
    <a href="{{ route('dashboard') }}" style="background: var(--accent-gradient); color: white; text-decoration: none; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 500; display: inline-block; transition: opacity 0.3s;" onmouseover="this.style.opacity=0.9" onmouseout="this.style.opacity=1">
        Kembali ke Dashboard
    </a>
</div>
@endsection
