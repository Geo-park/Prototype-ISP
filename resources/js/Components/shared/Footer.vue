<template>
    <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 px-6 py-3 mt-auto">
        <div class="flex flex-col md:flex-row justify-between items-center gap-1 text-xs text-gray-400 dark:text-gray-500">
            <span>© {{ tahun }} PT. Lintas Jaringan Nusantara. All rights reserved.</span>
            <div class="flex items-center gap-3">
                <Link :href="route('tentang')" class="hover:text-blue-600 transition-colors">Tentang PT</Link>
                <span>·</span>
                <Link :href="route('syarat-ketentuan')" class="hover:text-blue-600 transition-colors">Syarat & Ketentuan</Link>
                <span>·</span>
                <a href="mailto:marketing@lintas.net.id" class="hover:text-blue-600 transition-colors">Kontak</a>
            </div>
        </div>
    </footer>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
const tahun = new Date().getFullYear()
</script>
