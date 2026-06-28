<template>
    <div class="bg-white rounded-lg shadow p-4">
        <h2 class="font-semibold mb-3">Riwayat Pembayaran</h2>

        <div v-if="loading" class="space-y-2">
            <div v-for="i in 3" :key="i" class="animate-pulse h-8 bg-gray-200 rounded"></div>
        </div>

        <div v-else-if="pembayaran.length === 0"
            class="text-center py-6 text-sm text-gray-400">
            Belum ada riwayat pembayaran
        </div>

        <div v-else class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left border-b text-gray-500">
                        <th class="pb-2 font-medium">No Invoice</th>
                        <th class="pb-2 font-medium">Tanggal Bayar</th>
                        <th class="pb-2 font-medium">Metode</th>
                        <th class="pb-2 font-medium text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="p in pembayaran" :key="p.id" class="border-b last:border-0">
                        <td class="py-2 text-gray-700">{{ p.invoice?.no_invoice ?? '-' }}</td>
                        <td class="py-2 text-gray-600">{{ formatDate(p.tgl_bayar) }}</td>
                        <td class="py-2">
                            <span class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded">
                                {{ p.metode }}
                            </span>
                        </td>
                        <td class="py-2 text-right font-medium text-green-600">{{ formatRupiah(p.jumlah) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
defineProps({
    pembayaran: { type: Array, default: () => [] },
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
