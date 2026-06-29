<template>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-950 flex">
        <Sidebar />
        <div class="flex-1 flex flex-col overflow-hidden">
            <Topbar :title="title" />
            <main class="flex-1 overflow-auto">
                <slot />
            </main>
            <Footer />
        </div>
    </div>
</template>

<script setup>
import { useDarkMode } from '@/composables/useDarkMode'
import Sidebar from '@/Components/shared/Sidebar.vue'
import Topbar from '@/Components/shared/Topbar.vue'
import Footer from '@/Components/shared/Footer.vue'

useDarkMode()

defineProps({
    title: { type: String, default: '' },
})
</script>
