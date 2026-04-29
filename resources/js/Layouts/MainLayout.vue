<script setup>
import { ref, onMounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const isSidebarOpen = ref(false);
const openSubmenus = ref([]);

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

const toggleSubmenu = (name) => {
    if (openSubmenus.value.includes(name)) {
        openSubmenus.value = openSubmenus.value.filter(i => i !== name);
    } else {
        openSubmenus.value = [name];
    }
};

const logout = () => {
    // We'll use Inertia post for logout
};
</script>

<template>
    <div class="min-h-screen bg-[#0f172a] text-white">
        <!-- Mobile Header -->
        <div class="mobile-header">
            <div class="brand" style="font-size: 1.25rem;">e-Rapor SD</div>
            <button class="menu-toggle" @click="toggleSidebar">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
            </button>
        </div>

        <!-- Sidebar Overlay -->
        <div class="sidebar-overlay" :class="{ 'show': isSidebarOpen }" @click="toggleSidebar"></div>

        <!-- Sidebar Navigation -->
        <aside class="sidebar" :class="{ 'open': isSidebarOpen }">
            <div class="brand">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--accent)"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"/></svg>
                e-Rapor SD
            </div>
            <ul class="nav-links">
                <li><Link :href="route('dashboard')" class="nav-link" :class="{ 'active': $page.component === 'Dashboard' }">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                    Dashboard
                </Link></li>
                
                <li><Link :href="route('profile')" class="nav-link" :class="{ 'active': $page.component === 'Profile' }">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    Profile
                </Link></li>

                <template v-if="$page.props.auth.role === 'admin'">
                    <li><Link :href="route('sync')" class="nav-link" :class="{ 'active': $page.component === 'Sync' }">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.5 2v6h-6M2.13 15.57a10 10 0 1 0 14.3-11.4l-3.2 3.1"></path></svg>
                        Ambil Data Dapodik
                    </Link></li>
                    <li><Link :href="route('settings')" class="nav-link" :class="{ 'active': $page.component === 'Settings' }">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                        Identitas Sekolah
                    </Link></li>
                    <li><Link :href="route('database.manage')" class="nav-link" :class="{ 'active': $page.component === 'DatabaseManage' }">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></svg>
                        Kelola Database
                    </Link></li>
                </template>

                <!-- Submenus would go here, simplified for now -->
                <li style="margin-top: 1rem;"><div style="font-size: 0.75rem; text-transform: uppercase; color: var(--text-secondary); margin-bottom: 0.5rem; padding-left: 1rem; font-weight: 600; letter-spacing: 0.05em;">Utility</div></li>
                
                <li><Link :href="route('backup')" class="nav-link">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                    Backup & Kirim Data
                </Link></li>

                <li><Link :href="route('logout')" method="post" as="button" class="nav-link w-full text-left" style="color: #ef4444;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    Keluar
                </Link></li>
            </ul>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            <header class="header animate-slide-up">
                <div>
                    <h1 v-if="$slots.headerTitle"><slot name="headerTitle" /></h1>
                    <p style="color: var(--text-secondary); margin-top: 0.5rem;" v-if="$slots.headerSubtitle">
                        <slot name="headerSubtitle" />
                    </p>
                </div>
                <div class="user-profile">
                    <div class="avatar">{{ $page.props.auth?.user?.nama?.charAt(0) || 'A' }}</div>
                    <div style="text-align: right;">
                        <div style="font-size: 0.875rem; font-weight: 600;">{{ $page.props.auth?.user?.nama || 'User' }}</div>
                        <div style="font-size: 0.65rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">{{ $page.props.auth?.user?.role || 'Role' }}</div>
                    </div>
                </div>
            </header>

            <div v-if="$page.props.flash.success" class="alert-success">
                {{ $page.props.flash.success }}
            </div>

            <slot />
        </main>
    </div>
</template>

<style scoped>
.alert-success {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
    padding: 1rem 1.5rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    border: 1px solid rgba(16, 185, 129, 0.2);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}
</style>
