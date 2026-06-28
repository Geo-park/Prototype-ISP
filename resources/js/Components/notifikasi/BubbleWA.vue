<template>
    <div class="relative bg-[#e5ddd5] rounded-lg p-3 max-w-sm">
        <!-- Bubble tail -->
        <div class="absolute top-0 right-[-6px] w-3 h-3 bg-[#dcf8c6] transform rotate-45"></div>

        <!-- Message bubble -->
        <div class="bg-[#dcf8c6] rounded-lg px-3 py-2 relative shadow-sm">
            <!-- Avatar + sender -->
            <div class="flex items-center gap-2 mb-1.5">
                <div class="w-6 h-6 rounded-full bg-green-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                    ISP
                </div>
                <span class="text-xs font-semibold text-green-700">ISP Manager</span>
            </div>

            <!-- Message content -->
            <div class="text-sm text-gray-800 whitespace-pre-line leading-relaxed">
                {{ formattedMessage }}
            </div>

            <!-- Timestamp + checkmarks -->
            <div class="flex items-center justify-end gap-1 mt-1">
                <span class="text-[10px] text-gray-500">{{ timestamp }}</span>
                <svg class="w-4 h-4 text-blue-500" viewBox="0 0 16 16" fill="currentColor">
                    <path d="M3 8l2.5 2.5L11 5M6 8l2.5 2.5L14 5" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    message: { type: String, required: true },
    timestamp: { type: String, default: '14:30' },
    variables: { type: Object, default: () => ({}) },
})

const formattedMessage = computed(() => {
    let msg = props.message
    Object.entries(props.variables).forEach(([key, value]) => {
        msg = msg.replace(new RegExp(`\\[${key}\\]`, 'g'), value)
    })
    // Bold syntax: *text* → text (display as-is for preview)
    return msg
})
</script>
