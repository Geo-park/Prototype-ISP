<template>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 flex items-center gap-4 hover:shadow-md transition-shadow duration-200">
        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl shrink-0"
            :class="bgIcon">
            {{ icon }}
        </div>
        <div>
            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wide">{{ label }}</p>
            <p class="text-2xl font-bold mt-0.5" :class="color">{{ value }}</p>
        </div>
    </div>
</template>

<script setup>
defineProps({
    label: String,
    value: [String, Number],
    icon: { type: String, default: '📊' },
    color: { type: String, default: 'text-gray-800 dark:text-gray-100' },
    bgIcon: { type: String, default: 'bg-blue-50 dark:bg-blue-900' },
})
</script>
