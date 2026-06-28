<template>
    <div class="min-h-screen bg-gray-100 flex flex-col">
        <!-- Header / Navbar -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <h1 class="text-xl font-bold text-blue-600">ISP Client</h1>
                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded">Pelanggan</span>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-600 font-medium">{{ $page.props.auth.user.name }}</span>
                    <Link :href="route('logout')" method="post" as="button"
                        class="text-sm text-red-600 hover:underline">
                        Logout
                    </Link>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow max-w-4xl w-full mx-auto p-4 md:py-8">
            <slot />
        </main>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
</script>
