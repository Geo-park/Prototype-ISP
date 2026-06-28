<template>
    <SuperadminLayout>
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-6">Dashboard Superadmin</h1>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-gray-500 text-sm">Pelanggan Aktif</p>
                    <p class="text-2xl font-bold text-green-600">{{ stats.total_pelanggan_aktif }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-gray-500 text-sm">Pelanggan Nonaktif</p>
                    <p class="text-2xl font-bold text-red-600">{{ stats.total_pelanggan_nonaktif }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-gray-500 text-sm">Revenue Bulan Ini</p>
                    <p class="text-2xl font-bold text-blue-600">{{ formatRupiah(stats.revenue_bulan_ini) }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-gray-500 text-sm">Tagihan Pending</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ stats.tagihan_pending }}</p>
                </div>
            </div>

            <!-- Charts -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-4">
                    <h2 class="font-semibold mb-4">Revenue 6 Bulan Terakhir</h2>
                    <Bar v-if="revenueChart" :data="revenueChart" :options="chartOptions" />
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <h2 class="font-semibold mb-4">Status Pembayaran</h2>
                    <Pie v-if="statusPembayaran" :data="statusPembayaran" :options="chartOptions" />
                </div>
            </div>

            <!-- Invoice Terbaru -->
            <div class="bg-white rounded-lg shadow p-4 mb-6">
                <h2 class="font-semibold mb-4">Invoice Terbaru</h2>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left border-b">
                            <th class="pb-2">No Invoice</th>
                            <th class="pb-2">Pelanggan</th>
                            <th class="pb-2">Total</th>
                            <th class="pb-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="invoice in invoiceTerbaru" :key="invoice.id" class="border-b">
                            <td class="py-2">{{ invoice.no_invoice }}</td>
                            <td class="py-2">{{ invoice.pelanggan?.nama }}</td>
                            <td class="py-2">{{ formatRupiah(invoice.total) }}</td>
                            <td class="py-2">
                                <span :class="badgeClass(invoice.status)" class="px-2 py-1 rounded-full text-xs">
                                    {{ invoice.status }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Log Aktivitas -->
            <div class="bg-white rounded-lg shadow p-4">
                <h2 class="font-semibold mb-4">Log Aktivitas</h2>
                <ul class="space-y-2">
                    <li v-for="(log, i) in aktivitasLog" :key="i" class="text-sm text-gray-600 border-b pb-2">
                        {{ log.aksi }}
                    </li>
                </ul>
            </div>
        </div>
    </SuperadminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Bar, Pie } from 'vue-chartjs'
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    BarElement,
    ArcElement,
    Title,
    Tooltip,
    Legend
} from 'chart.js'
import SuperadminLayout from '@/Layouts/SuperadminLayout.vue'
import axios from 'axios'

ChartJS.register(CategoryScale, LinearScale, BarElement, ArcElement, Title, Tooltip, Legend)

const stats = ref({
    total_pelanggan_aktif: 0,
    total_pelanggan_nonaktif: 0,
    revenue_bulan_ini: 0,
    tagihan_pending: 0,
})

const revenueChart = ref(null)
const statusPembayaran = ref(null)
const invoiceTerbaru = ref([])
const aktivitasLog = ref([])

const chartOptions = { responsive: true }

const formatRupiah = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(value)
}

const badgeClass = (status) => {
    return {
        'bg-green-100 text-green-800': status === 'paid',
        'bg-yellow-100 text-yellow-800': status === 'pending',
        'bg-red-100 text-red-800': status === 'overdue',
    }
}

onMounted(async () => {
    const [statsRes, revenueRes, statusRes, invoiceRes, logRes] = await Promise.all([
        axios.get('/superadmin/stats'),
        axios.get('/superadmin/revenue-chart'),
        axios.get('/superadmin/status-pembayaran'),
        axios.get('/superadmin/invoice-terbaru'),
        axios.get('/superadmin/aktivitas-log'),
    ])

    stats.value = statsRes.data

    revenueChart.value = {
        labels: revenueRes.data.map(d => d.bulan),
        datasets: [{
            label: 'Revenue',
            data: revenueRes.data.map(d => d.revenue),
            backgroundColor: '#3b82f6',
        }]
    }

    statusPembayaran.value = {
        labels: ['Paid', 'Pending', 'Overdue'],
        datasets: [{
            data: [statusRes.data.paid, statusRes.data.pending, statusRes.data.overdue],
            backgroundColor: ['#22c55e', '#eab308', '#ef4444'],
        }]
    }

    invoiceTerbaru.value = invoiceRes.data
    aktivitasLog.value = logRes.data
})
</script>
