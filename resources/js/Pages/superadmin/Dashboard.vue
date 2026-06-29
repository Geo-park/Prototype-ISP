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
                <div class="bg-white rounded-lg shadow p-4 mb-6">
                    <h2 class="font-semibold mb-4">Log Aktivitas</h2>
                    <ul class="space-y-2">
                        <li v-for="(log, i) in aktivitasLog" :key="i" class="text-sm text-gray-600 border-b pb-2">
                            {{ log.aksi }}
                        </li>
                    </ul>
                </div>

                <!-- Tabel Pelanggan -->
                <div class="bg-white rounded-lg shadow p-4 mb-6">
                    <h2 class="font-semibold mb-4">Daftar Pelanggan</h2>
                    <table class="w-full text-sm table-fixed">
                        <colgroup>
                            <col class="w-1/3" />
                            <col class="w-1/3" />
                            <col style="width: 110px" />
                            <col style="width: 100px" />
                        </colgroup>
                        <thead>
                            <tr class="text-left border-b bg-gray-50">
                                <th class="p-3">Nama</th>
                                <th class="p-3">Paket</th>
                                <th class="p-3">Status</th>
                                <th class="p-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="p in pelanggan" :key="p.id" class="border-b hover:bg-gray-50">
                                <td class="p-3">{{ p.nama }}</td>
                                <td class="p-3">{{ p.paket?.nama }}</td>
                                <td class="p-3">
                                    <span
                                        :class="p.status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                        class="inline-block w-20 text-center px-2 py-1 rounded-full text-xs font-medium">
                                        {{ p.status }}
                                    </span>
                                </td>
                                <td class="p-3">
                                    <button
                                        @click="p.status === 'aktif' ? matikanKoneksi(p.id) : hidupkanKoneksi(p.id)"
                                        :class="p.status === 'aktif'
                                            ? 'bg-red-500 hover:bg-red-600'
                                            : 'bg-green-500 hover:bg-green-600'"
                                        class="text-xs text-white w-20 py-1 rounded text-center transition-colors duration-150">
                                        {{ p.status === 'aktif' ? 'Matikan' : 'Hidupkan' }}
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Laporan Keluhan -->
                <div class="bg-white rounded-lg shadow p-4">
                    <h2 class="font-semibold mb-4">Laporan Keluhan</h2>
                    <ul class="space-y-2">
                        <li v-for="laporan in laporanKeluhan" :key="laporan.id"
                            class="text-sm border-b pb-2 flex justify-between items-center">
                            <div>
                                <p class="font-medium">{{ laporan.judul }}</p>
                                <p class="text-gray-500 text-xs">{{ laporan.user?.name }} — {{ laporan.created_at }}</p>
                            </div>
                            <button @click="bukaModal(laporan)"
                                class="text-xs bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600 shrink-0">
                                {{ laporan.status }}
                            </button>
                        </li>
                    </ul>
                </div>

                <!-- Modal Laporan -->
                <div v-if="modalLaporan" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white rounded-lg shadow-lg p-6 w-96">
                        <div class="flex justify-between items-start mb-4">
                            <h2 class="font-bold text-lg">Detail Laporan</h2>
                            <button @click="modalLaporan = null" class="text-gray-400 hover:text-gray-600">✕</button>
                        </div>
                        <div class="space-y-2 text-sm">
                            <p><span class="text-gray-500">ID:</span> #{{ modalLaporan.id }}</p>
                            <p><span class="text-gray-500">Judul:</span> {{ modalLaporan.judul }}</p>
                            <p><span class="text-gray-500">Pengirim:</span> {{ modalLaporan.user?.name }}</p>
                            <p><span class="text-gray-500">Status:</span>
                                <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs ml-1">
                                    {{ modalLaporan.status }}
                                </span>
                            </p>
                            <p><span class="text-gray-500">Deskripsi:</span> {{ modalLaporan.deskripsi }}</p>
                        </div>
                        <div class="mt-4 space-y-2">
                            <button @click="updateStatus(modalLaporan.id, 'proses')"
                                v-if="modalLaporan.status === 'open'"
                                class="w-full bg-yellow-500 text-white py-2 rounded hover:bg-yellow-600 text-sm">
                                Tandai Sedang Diproses
                            </button>
                            <button @click="updateStatus(modalLaporan.id, 'selesai')"
                                v-if="modalLaporan.status !== 'selesai'"
                                class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700 text-sm">
                                Tandai Selesai
                            </button>
                            <button @click="modalLaporan = null"
                                class="w-full bg-gray-100 text-gray-700 py-2 rounded hover:bg-gray-200 text-sm">
                                Tutup
                            </button>
                        </div>
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
const invoiceTerbaru = ref([])
const aktivitasLog = ref([])
const pelanggan = ref([])
const laporanKeluhan = ref([])
const modalLaporan = ref(null)
const statistikDaerah = ref([])

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

const bukaModal = (laporan) => {
    modalLaporan.value = laporan
}

const matikanKoneksi = async (id) => {
    await axios.post(`/admin/koneksi/matikan/${id}`)
    const index = pelanggan.value.findIndex(p => p.id === id)
    if (index !== -1) {
        pelanggan.value[index].status = 'nonaktif'
    }
}

const hidupkanKoneksi = async (id) => {
    await axios.post(`/admin/koneksi/hidupkan/${id}`)
    const index = pelanggan.value.findIndex(p => p.id === id)
    if (index !== -1) {
        pelanggan.value[index].status = 'aktif'
    }
}

const updateStatus = async (id, status) => {
    await axios.put(`/superadmin/keluhan/${id}/status`, { status })
    const index = laporanKeluhan.value.findIndex(l => l.id === id)
    if (index !== -1) laporanKeluhan.value[index].status = status
    modalLaporan.value = null
}

onMounted(async () => {
    try {
        const [res, keluhanRes] = await Promise.all([
            axios.get('/superadmin/dashboard-data'),
            axios.get('/superadmin/keluhan'),
        ])
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

        invoiceTerbaru.value = d.invoice_terbaru
        aktivitasLog.value = d.aktivitas_log
        pelanggan.value = d.pelanggan
        laporanKeluhan.value = keluhanRes.data
        statistikDaerah.value = d.statistik_daerah
    } catch (e) {
        console.error('Gagal memuat data dashboard superadmin:', e)
    } finally {
        loading.value = false
    }
})
</script>
