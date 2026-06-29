<template>
    <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 px-6 py-4 mt-auto">
        <div class="flex flex-col md:flex-row justify-between items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
            <!-- Kiri -->
            <div class="flex items-center gap-1">
                <span class="font-medium text-blue-600">PT. Lintas Jaringan Nusantara</span>
                <span>© {{ tahun }} All rights reserved.</span>
            </div>

            <!-- Tengah -->
            <div class="flex items-center gap-1">
                <span>📡 SLA 99.9%</span>
                <span class="mx-2">•</span>
                <span>🌐 100+ Kota</span>
                <span class="mx-2">•</span>
                <span>🛠️ Support 24/7</span>
            </div>

            <!-- Kanan -->
            <div class="flex items-center gap-3">
                <Link :href="route('tentang')"
                    class="hover:text-blue-600 transition-colors">
                    Tentang PT
                </Link>
                <span>•</span>
                <Link :href="route('syarat-ketentuan')"
                    class="hover:text-blue-600 transition-colors">
                    Syarat & Ketentuan
                </Link>
                <span>•</span>
                <a href="mailto:marketing@lintas.net.id"
                    class="hover:text-blue-600 transition-colors">
                    Hubungi Kami
                </a>
            </div>
        </div>
    </footer>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

const tahun = new Date().getFullYear()
</script>
