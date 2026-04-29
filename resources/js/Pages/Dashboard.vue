<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    sekolah: Object,
    stats: Object,
    rombonganBelajar: Array
});
</script>

<template>
    <Head title="Dashboard" />

    <MainLayout>
        <template #headerTitle>Dashboard Overview</template>
        <template #headerSubtitle>Selamat Datang di {{ sekolah?.nama || 'e-Rapor SD' }}</template>

        <div class="stats-grid animate-slide-up delay-1">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ stats?.totalGuru || 0 }}</div>
                    <div class="stat-label">Total Guru & Staf</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ stats?.totalSiswa || 0 }}</div>
                    <div class="stat-label">Siswa Terdaftar</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ stats?.totalRombel || 0 }}</div>
                    <div class="stat-label">Rombongan Belajar</div>
                </div>
            </div>
        </div>

        <div class="stat-card animate-slide-up delay-2" style="margin-top: 2rem; padding: 2rem; display: block;">
            <h3 style="margin-bottom: 1.5rem; font-weight: 700;">Data Sekolah (Dapodik)</h3>
            <div style="color: var(--text-secondary); font-size: 0.875rem; display: grid; gap: 0.5rem;">
                <div>NPSN: <span style="color: #fff;">{{ sekolah?.npsn || '-' }}</span></div>
                <div>Alamat: <span style="color: #fff;">{{ sekolah?.alamat_jalan || '-' }}</span></div>
                <div>Status: <span class="badge-dapodik" style="font-size: 0.6rem;">Sinkron</span></div>
            </div>
        </div>
    </MainLayout>
</template>
