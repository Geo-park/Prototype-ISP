<template>
    <SuperadminLayout>
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-6">Daftar Invoice</h1>

            <!-- Filter -->
            <div class="mb-4 flex gap-2">
                <button v-for="s in ['semua', 'pending', 'paid', 'overdue']" :key="s"
                    @click="filterStatus = s"
                    :class="filterStatus === s ? 'bg-blue-600 text-white' : 'bg-white text-gray-600'"
                    class="px-4 py-2 rounded-lg text-sm border">
                    {{ s }}
                </button>
            </div>

            <!-- Tabel -->
            <div class="bg-white rounded-lg shadow">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left border-b bg-gray-50">
                            <th class="p-4">No Invoice</th>
                            <th class="p-4">Pelanggan</th>
                            <th class="p-4">Periode</th>
                            <th class="p-4">Total</th>
                            <th class="p-4">Status</th>
                            <th class="p-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="invoice in invoiceFiltered" :key="invoice.id" class="border-b hover:bg-gray-50">
                            <td class="p-4">{{ invoice.no_invoice }}</td>
                            <td class="p-4">{{ invoice.pelanggan?.nama }}</td>
                            <td class="p-4">{{ invoice.periode }}</td>
                            <td class="p-4">{{ formatRupiah(invoice.total) }}</td>
                            <td class="p-4">
                                <span :class="badgeClass(invoice.status)" class="px-2 py-1 rounded-full text-xs">
                                    {{ invoice.status }}
                                </span>
                            </td>
                            <td class="p-4">
                                <Link :href="`/invoice/${invoice.id}`"
                                    class="text-blue-600 hover:underline text-xs">
                                    Detail
                                </Link>
                            </td>
                        </tr>
                        <tr v-if="invoiceFiltered.length === 0">
                            <td colspan="6" class="p-4 text-center text-gray-400">Tidak ada invoice</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </SuperadminLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import SuperadminLayout from '@/Layouts/SuperadminLayout.vue'

const props = defineProps({
    invoices: Array,
})

const filterStatus = ref('semua')

const invoiceFiltered = computed(() => {
    if (filterStatus.value === 'semua') return props.invoices
    return props.invoices.filter(i => i.status === filterStatus.value)
})

const formatRupiah = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(value)
}

const badgeClass = (status) => ({
    'bg-green-100 text-green-800': status === 'paid',
    'bg-yellow-100 text-yellow-800': status === 'pending',
    'bg-red-100 text-red-800': status === 'overdue',
})
</script>
