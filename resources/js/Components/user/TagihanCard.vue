<template>
    <div class="bg-white rounded-lg shadow p-4">
        <h2 class="font-semibold mb-3">Tagihan Aktif</h2>

        <div v-if="loading" class="space-y-3">
            <div v-for="i in 2" :key="i" class="animate-pulse flex justify-between">
                <div class="space-y-2">
                    <div class="h-4 bg-gray-200 rounded w-24"></div>
                    <div class="h-3 bg-gray-200 rounded w-20"></div>
                </div>
                <div class="space-y-2 text-right">
                    <div class="h-4 bg-gray-200 rounded w-20 ml-auto"></div>
                    <div class="h-5 bg-gray-200 rounded w-14 ml-auto"></div>
                </div>
            </div>
        </div>

        <div v-else-if="tagihan.length === 0"
            class="text-center py-6 text-sm text-gray-400">
            🎉 Tidak ada tagihan aktif
        </div>

        <div v-else class="space-y-3">
            <div v-for="inv in tagihan" :key="inv.id"
                class="flex justify-between items-center border-b pb-3 last:border-0 last:pb-0">
                <div>
                    <p class="text-sm font-medium text-gray-700">{{ inv.no_invoice }}</p>
                    <p class="text-xs text-gray-500">Periode: {{ inv.periode }}</p>
                    <p class="text-xs text-gray-400">Jatuh Tempo: {{ formatDate(inv.tgl_jatuh_tempo) }}</p>
                </div>
                <div class="text-right">
                    <p class="font-bold text-blue-600">{{ formatRupiah(inv.total) }}</p>
                    <span class="text-xs px-2 py-0.5 rounded-full"
                        :class="inv.status === 'pending'
                            ? 'bg-yellow-100 text-yellow-800'
                            : 'bg-red-100 text-red-800'">
                        {{ inv.status }}
                    </span>
                    <div class="mt-1.5">
                        <Link :href="route('invoice.show', inv.id)"
                            class="text-xs bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition-colors">
                            Bayar
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    tagihan: { type: Array, default: () => [] },
    loading: { type: Boolean, default: false },
})

const formatRupiah = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(value)
}

const formatDate = (dateStr) => {
    if (!dateStr) return '-'
    return new Date(dateStr).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
    })
}
</script>
