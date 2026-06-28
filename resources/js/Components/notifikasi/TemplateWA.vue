<template>
    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-start justify-between mb-3">
            <div>
                <h3 class="font-semibold text-sm">{{ template.nama }}</h3>
                <span class="text-xs px-2 py-0.5 rounded-full"
                    :class="kategoriBadge">
                    {{ template.kategori }}
                </span>
            </div>
            <button @click="$emit('kirim', template)"
                class="px-3 py-1.5 bg-green-600 text-white text-xs rounded-lg hover:bg-green-700 transition-colors flex items-center gap-1">
                <span>📤</span> Simulasi Kirim
            </button>
        </div>

        <BubbleWA
            :message="template.pesan"
            :variables="variables"
            :timestamp="currentTime"
        />
    </div>
</template>

<script setup>
import { computed } from 'vue'
import BubbleWA from './BubbleWA.vue'

const props = defineProps({
    template: { type: Object, required: true },
    variables: { type: Object, default: () => ({}) },
})

defineEmits(['kirim'])

const kategoriBadge = computed(() => ({
    'bg-blue-100 text-blue-700': props.template.kategori === 'billing',
    'bg-yellow-100 text-yellow-700': props.template.kategori === 'reminder',
    'bg-purple-100 text-purple-700': props.template.kategori === 'status',
}))

const currentTime = computed(() => {
    const now = new Date()
    return now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0')
})
</script>
