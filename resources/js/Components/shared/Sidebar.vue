<template>
    <aside class="w-64 bg-white dark:bg-gray-800 shadow-md flex flex-col min-h-screen border-r border-gray-200 dark:border-gray-700">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <h1 class="text-xl font-bold" :class="colorClass">ISP Manager</h1>
            <p class="text-xs text-gray-500 dark:text-gray-400">{{ roleLabel }}</p>
        </div>

        <nav class="flex-1 p-4 space-y-1">
            <Link v-for="item in menuItems" :key="item.route"
                :href="route(item.route)"
                class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm transition-colors"
                :class="{ 'bg-gray-100 dark:bg-gray-700 font-medium': isActive(item.route) }">
                <span>{{ item.icon }}</span>
                {{ item.label }}
            </Link>
        </nav>

        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ $page.props.auth.user.name }}
            </p>
            <p class="text-xs text-gray-500 dark:text-gray-400">
                {{ $page.props.auth.user.daerah ?? 'Semua Daerah' }}
            </p>
        </div>
    </aside>
</template>

<script setup>
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage()
const role = computed(() => page.props.auth.user.role)

const colorClass = computed(() => ({
    'text-blue-600': role.value === 'superadmin',
    'text-green-600': role.value === 'admin',
    'text-gray-600': role.value === 'user',
}))

const roleLabel = computed(() => ({
    superadmin: 'Superadmin Panel',
    admin: 'Admin Panel',
    user: 'Pelanggan Panel',
}[role.value]))

const menuItems = computed(() => {
    if (role.value === 'superadmin') {
        return [
            { icon: '📊', label: 'Dashboard', route: 'superadmin.dashboard' },
            { icon: '🗺️', label: 'Peta Jaringan', route: 'superadmin.peta' },
            { icon: '📄', label: 'Invoice', route: 'invoice.index' },
            { icon: '💬', label: 'Template WA', route: 'notifikasi.index' },
            { icon: '👤', label: 'Manajemen User', route: 'superadmin.users.page' },
            { icon: '📋', label: 'Laporan Keluhan', route: 'superadmin.laporan-keluhan' },
        ]
    }
    if (role.value === 'admin') {
        return [
            { icon: '📊', label: 'Dashboard', route: 'admin.dashboard' },
            { icon: '🗺️', label: 'Peta Jaringan', route: 'admin.peta' },
            { icon: '📄', label: 'Invoice', route: 'invoice.index' },
            { icon: '💬', label: 'Template WA', route: 'notifikasi.index' },
            { icon: '👤', label: 'Manajemen User', route: 'admin.users' },
            { icon: '📋', label: 'Laporan Keluhan', route: 'admin.laporan-keluhan' },
        ]
    }
    return [
        { icon: '🏠', label: 'Dashboard', route: 'user.dashboard' },
        { icon: '📦', label: 'Paket Internet', route: 'paket-internet' },
        { icon: '🆘', label: 'Pusat Bantuan', route: 'pusat-bantuan' },
        { icon: '🏢', label: 'Tentang PT', route: 'tentang' },
        { icon: '📜', label: 'Syarat & Ketentuan', route: 'syarat-ketentuan' },
    ]
})

const isActive = (routeName) => {
    try {
        return page.url === route(routeName)
    } catch {
        return false
    }
}
</script>
