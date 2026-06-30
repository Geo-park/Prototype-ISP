<template>
    <SuperadminLayout>
        <div class="p-6 space-y-6">

            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Dashboard Superadmin</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Ringkasan performa dan metrik utama jaringan.</p>
                </div>
                <span class="text-xs text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-800 px-3 py-1.5 rounded-lg">
                    Terakhir diperbarui: Hari ini, {{ waktuSekarang }}
                </span>
            </div>

            <!-- Loading -->
            <div v-if="loading" class="flex items-center justify-center h-64">
                <div class="text-center">
                    <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-[#1e4db7] mx-auto mb-3"></div>
                    <p class="text-gray-400 text-sm">Memuat data...</p>
                </div>
            </div>

            <div v-else class="space-y-6">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

                    <!-- Pelanggan Aktif -->
                    <div class="stat-card bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
                        <div class="flex items-start justify-between mb-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-green-100 dark:bg-green-900/30">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-green-600 bg-green-50 dark:bg-green-900/20 px-2 py-0.5 rounded-full">↑ +5.2%</span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Pelanggan Aktif</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ stats.total_pelanggan_aktif.toLocaleString('id-ID') }}</p>
                    </div>

                    <!-- Pelanggan Nonaktif -->
                    <div class="stat-card bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
                        <div class="flex items-start justify-between mb-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-red-100 dark:bg-red-900/30">
                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-red-500 bg-red-50 dark:bg-red-900/20 px-2 py-0.5 rounded-full">↓ -1.1%</span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Pelanggan Nonaktif</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ stats.total_pelanggan_nonaktif }}</p>
                    </div>

                    <!-- Revenue -->
                    <div class="stat-card bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
                        <div class="flex items-start justify-between mb-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-blue-100 dark:bg-blue-900/30">
                                <svg class="w-5 h-5 text-[#1e4db7]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-blue-600 bg-blue-50 dark:bg-blue-900/20 px-2 py-0.5 rounded-full">↑ +12.5%</span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Total Revenue (Bulan Ini)</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ formatRupiah(stats.revenue_bulan_ini) }}</p>
                    </div>

                    <!-- Tagihan Pending -->
                    <div class="stat-card bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
                        <div class="flex items-start justify-between mb-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-orange-100 dark:bg-orange-900/30">
                                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-orange-500 bg-orange-50 dark:bg-orange-900/20 px-2 py-0.5 rounded-full">
                                {{ stats.tagihan_pending }} Transaksi
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Tagihan Pending</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ stats.tagihan_pending }}</p>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <!-- Bar chart -->
                    <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
                        <h2 class="font-semibold text-gray-800 dark:text-gray-100 mb-4">Revenue 6 Bulan Terakhir</h2>
                        <Bar v-if="revenueChart" :data="revenueChart" :options="barOptions" />
                    </div>
                    <!-- Pie chart -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
                        <h2 class="font-semibold text-gray-800 dark:text-gray-100 mb-4">Status Pembayaran Bulan Ini</h2>
                        <div class="flex justify-center">
                            <div class="w-44">
                                <Pie v-if="statusPembayaran" :data="statusPembayaran" :options="pieOptions" />
                            </div>
                        </div>
                        <div class="mt-4 space-y-2">
                            <div class="flex items-center justify-between text-sm">
                                <span class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span> Lunas</span>
                                <span class="font-semibold text-gray-700 dark:text-gray-300">75%</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-amber-400 inline-block"></span> Pending</span>
                                <span class="font-semibold text-gray-700 dark:text-gray-300">20%</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-red-500 inline-block"></span> Jatuh Tempo</span>
                                <span class="font-semibold text-gray-700 dark:text-gray-300">5%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabel Statistik Per Daerah -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                        <h2 class="font-semibold text-gray-800 dark:text-gray-100">Breakdown Statistik Per Daerah</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm ljn-table">
                            <thead>
                                <tr class="text-left bg-gray-50 dark:bg-gray-700/50">
                                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Daerah</th>
                                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aktif</th>
                                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nonaktif</th>
                                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                <tr v-for="d in statistikDaerah" :key="d.daerah"
                                    class="transition-colors duration-100">
                                    <td class="px-5 py-3.5 font-medium text-gray-700 dark:text-gray-300">{{ d.daerah }}</td>
                                    <td class="px-5 py-3.5 text-green-600 font-semibold">{{ d.aktif }}</td>
                                    <td class="px-5 py-3.5 text-red-500">{{ d.nonaktif }}</td>
                                    <td class="px-5 py-3.5 font-bold text-gray-800 dark:text-gray-200">{{ d.total }}</td>
                                </tr>
                                <tr v-if="statistikDaerah.length === 0">
                                    <td colspan="4" class="px-5 py-8 text-center text-gray-400">Belum ada data</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </SuperadminLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
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

const barOptions = {
    responsive: true,
    plugins: { legend: { display: false } },
    scales: {
        y: { grid: { color: '#f1f5f9' }, ticks: { color: '#94a3b8' } },
        x: { grid: { display: false }, ticks: { color: '#94a3b8' } },
    }
}

const pieOptions = {
    responsive: true,
    plugins: { legend: { display: false } },
    cutout: '65%',
}

const waktuSekarang = computed(() => {
    return new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })
})

const formatRupiah = (value) => {
    if (value >= 1_000_000_000) return `Rp ${(value / 1_000_000_000).toFixed(2)} M`
    if (value >= 1_000_000)     return `Rp ${(value / 1_000_000).toFixed(0)} Jt`
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value)
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
                backgroundColor: '#1e4db7',
                borderRadius: 6,
                borderSkipped: false,
            }]
        }

        statusPembayaran.value = {
            labels: ['Lunas', 'Pending', 'Jatuh Tempo'],
            datasets: [{
                data: [d.status_pembayaran.paid, d.status_pembayaran.pending, d.status_pembayaran.overdue],
                backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                borderWidth: 0,
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
