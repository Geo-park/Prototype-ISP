<template>
    <span :class="badgeClass" class="px-2 py-1 rounded-full text-xs font-medium">
        {{ status }}
    </span>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    status: String
})

const badgeClass = computed(() => ({
    'bg-green-100 text-green-800': props.status === 'aktif' || props.status === 'paid',
    'bg-yellow-100 text-yellow-800': props.status === 'pending',
    'bg-red-100 text-red-800': props.status === 'nonaktif' || props.status === 'overdue',
}))
</script>
