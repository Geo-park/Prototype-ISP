<template>
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-gray-500 text-sm">{{ label }}</p>
        <p class="text-2xl font-bold" :class="color">{{ value }}</p>
    </div>
</template>

<script setup>
defineProps({
    label: String,
    value: [String, Number],
    color: {
        type: String,
        default: 'text-gray-800'
    }
})
</script>
