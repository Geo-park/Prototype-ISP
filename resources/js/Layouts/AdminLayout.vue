<template>
    <div class="min-h-screen bg-gray-100 flex">
        <aside class="w-64 bg-white shadow-md flex flex-col">
            <div class="p-4 border-b">
                <h1 class="text-xl font-bold text-green-600">ISP Manager</h1>
                <p class="text-xs text-gray-500">Admin Panel</p>
            </div>
            <nav class="flex-1 p-4 space-y-1">
                <Link :href="route('admin.dashboard')"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-green-50 text-gray-700">
                    Dashboard
                </Link>
                <Link :href="route('invoice.index')"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-green-50 text-gray-700">
                    Invoice
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
        <main class="flex-1 overflow-auto">
            <slot />
        </main>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
</script>
