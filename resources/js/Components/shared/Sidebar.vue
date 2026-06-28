<template>
    <aside class="w-64 bg-white shadow-md flex flex-col min-h-screen">
        <div class="p-4 border-b">
            <h1 class="text-xl font-bold" :class="colorClass">ISP Manager</h1>
            <p class="text-xs text-gray-500">{{ roleLabel }}</p>
        </div>

        <nav class="flex-1 p-4 space-y-1">
            <Link v-for="item in menuItems" :key="item.route"
                :href="route(item.route)"
                class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-50 text-gray-700 text-sm"
                :class="{ 'bg-gray-100 font-medium': isActive(item.route) }">
                <span>{{ item.icon }}</span>
                {{ item.label }}
            </Link>
        </nav>

        <div class="p-4 border-t">
            <p class="text-sm text-gray-600 mb-2">{{ $page.props.auth.user.name }}</p>
            <Link :href="route('logout')" method="post" as="button"
                class="text-sm text-red-600 hover:underline">
                Logout
            </Link>
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
        ]
    }
    if (role.value === 'admin') {
        return [
            { icon: '📊', label: 'Dashboard', route: 'admin.dashboard' },
            { icon: '📄', label: 'Invoice', route: 'invoice.index' },
            { icon: '💬', label: 'Template WA', route: 'notifikasi.index' },
        ]
    }
    return [
        { icon: '🏠', label: 'Dashboard', route: 'user.dashboard' },
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
