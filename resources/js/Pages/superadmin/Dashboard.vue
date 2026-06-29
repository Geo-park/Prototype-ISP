<template>
    <SuperadminLayout>
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-6">Dashboard Superadmin</h1>

            <!-- Loading State -->
            <div v-if="loading" class="flex items-center justify-center h-64">
                <div class="text-center">
                    <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-600 mx-auto mb-3"></div>
                    <p class="text-gray-500 text-sm">Memuat data...</p>
                </div>
            </div>

            <!-- Konten -->
            <div v-else>
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

                <!-- Statistik Per Daerah -->
                <div class="bg-white rounded-lg shadow p-4 mb-6">
                    <h2 class="font-semibold mb-4">Statistik Per Daerah</h2>
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left border-b bg-gray-50">
                                <th class="p-3">Daerah</th>
                                <th class="p-3">Aktif</th>
                                <th class="p-3">Nonaktif</th>
                                <th class="p-3">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="d in statistikDaerah" :key="d.daerah" class="border-b hover:bg-gray-50">
                                <td class="p-3 font-medium">{{ d.daerah }}</td>
                                <td class="p-3 text-green-600">{{ d.aktif }}</td>
                                <td class="p-3 text-red-600">{{ d.nonaktif }}</td>
                                <td class="p-3 font-bold">{{ d.total }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Charts -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-white rounded-lg shadow p-4">
                        <h2 class="font-semibold mb-4">Revenue 6 Bulan Terakhir</h2>
                        <Bar v-if="revenueChart" :data="revenueChart" :options="chartOptions" />
                    </div>
                    <div class="bg-white rounded-lg shadow p-4">
                        <h2 class="font-semibold mb-4">Status Pembayaran</h2>
                        <Pie v-if="statusPembayaran" :data="statusPembayaran" :options="chartOptions" />
                    </div>
                </div>
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

const loading = ref(true)

const stats = ref({
    total_pelanggan_aktif: 0,
    total_pelanggan_nonaktif: 0,
    revenue_bulan_ini: 0,
    tagihan_pending: 0,
})

const revenueChart = ref(null)
const statusPembayaran = ref(null)
const statistikDaerah = ref([])

const chartOptions = { responsive: true }

const formatRupiah = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(value)
}

onMounted(async () => {
    try {
        const res = await axios.get('/superadmin/dashboard-data')
        const d = res.data

        stats.value = d.stats

        revenueChart.value = {
            labels: d.revenue_chart.map(item => item.bulan),
            datasets: [{
                label: 'Revenue',
                data: d.revenue_chart.map(item => item.revenue),
                backgroundColor: '#3b82f6',
            }]
        }

        statusPembayaran.value = {
            labels: ['Paid', 'Pending', 'Overdue'],
            datasets: [{
                data: [d.status_pembayaran.paid, d.status_pembayaran.pending, d.status_pembayaran.overdue],
                backgroundColor: ['#22c55e', '#eab308', '#ef4444'],
            }]
        }

        statistikDaerah.value = d.statistik_daerah
    } catch (e) {
        console.error('Gagal memuat data dashboard superadmin:', e)
    } finally {
        loading.value = false
    }
})
</script>
