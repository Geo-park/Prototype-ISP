<template>
    <div class="min-h-screen bg-gray-100 flex">
        <Sidebar />
        <main class="flex-1 overflow-auto">
            <slot />
        </main>
    </div>
</template>

<script setup>
import Sidebar from '@/Components/shared/Sidebar.vue'
</script>
