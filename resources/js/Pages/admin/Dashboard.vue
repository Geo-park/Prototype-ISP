<template>
    <AdminLayout>
        <div class="p-6 space-y-6">

            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Dashboard Admin</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Monitoring pelanggan dan laporan daerah Anda.</p>
                </div>
                <span v-if="!loading"
                    class="inline-flex items-center gap-1.5 text-xs font-medium text-blue-700 bg-blue-50 dark:bg-blue-900/20 dark:text-blue-300 px-3 py-1.5 rounded-lg">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                    </svg>
                    {{ stats.daerah }}
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
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                    <div class="stat-card bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
                        <div class="flex items-start justify-between mb-3">
                            <div class="w-10 h-10 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                                </svg>
                            </div>
                            <span class="text-xs font-medium text-green-600 bg-green-50 dark:bg-green-900/20 px-2 py-0.5 rounded-full">Aktif</span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Pelanggan Aktif</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ stats.total_pelanggan_aktif }}</p>
                    </div>

                    <div class="stat-card bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
                        <div class="flex items-start justify-between mb-3">
                            <div class="w-10 h-10 rounded-xl bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                            </div>
                            <span class="text-xs font-medium text-red-500 bg-red-50 dark:bg-red-900/20 px-2 py-0.5 rounded-full">Nonaktif</span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Pelanggan Nonaktif</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ stats.total_pelanggan_nonaktif }}</p>
                    </div>

                    <div class="stat-card bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
                        <div class="flex items-start justify-between mb-3">
                            <div class="w-10 h-10 rounded-xl bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center">
                                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="text-xs font-medium text-orange-500 bg-orange-50 dark:bg-orange-900/20 px-2 py-0.5 rounded-full">Pending</span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Tagihan Pending</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ stats.tagihan_pending }}</p>
                    </div>
                </div>

                <!-- Tabel Pelanggan -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                        <h2 class="font-semibold text-gray-800 dark:text-gray-100">Daftar Pelanggan</h2>
                        <Link href="/admin/pelanggan/tambah"
                            class="inline-flex items-center gap-1.5 text-xs font-semibold text-white px-3 py-2 rounded-xl transition-colors"
                            style="background:#1e4db7;" >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                            </svg>
                            Tambah Pelanggan
                        </Link>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm ljn-table">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-700/50 text-left">
                                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama</th>
                                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Paket</th>
                                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Daerah</th>
                                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                <tr v-for="p in pelanggan" :key="p.id" class="transition-colors duration-100">
                                    <td class="px-5 py-3.5 font-medium text-gray-700 dark:text-gray-300">{{ p.nama }}</td>
                                    <td class="px-5 py-3.5 text-gray-600 dark:text-gray-400">{{ p.paket?.nama }}</td>
                                    <td class="px-5 py-3.5 text-gray-600 dark:text-gray-400">{{ p.daerah }}</td>
                                    <td class="px-5 py-3.5">
                                        <span :class="p.status === 'aktif'
                                            ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'
                                            : 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400'"
                                            class="px-2.5 py-1 rounded-full text-xs font-medium capitalize">
                                            {{ p.status }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <button
                                            @click="p.status === 'aktif' ? matikanKoneksi(p.id) : hidupkanKoneksi(p.id)"
                                            :class="p.status === 'aktif'
                                                ? 'border-red-300 text-red-600 hover:bg-red-600 hover:text-white'
                                                : 'border-green-300 text-green-600 hover:bg-green-600 hover:text-white'"
                                            class="text-xs px-3 py-1.5 rounded-lg border bg-white dark:bg-transparent transition-all duration-150 font-medium">
                                            {{ p.status === 'aktif' ? 'Matikan' : 'Hidupkan' }}
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="pelanggan.length === 0">
                                    <td colspan="5" class="px-5 py-8 text-center text-gray-400">Belum ada data pelanggan</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Laporan Keluhan -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                        <h2 class="font-semibold text-gray-800 dark:text-gray-100">Laporan Keluhan</h2>
                    </div>
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        <div v-for="laporan in laporanKeluhan" :key="laporan.id"
                            class="flex items-center justify-between px-5 py-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <div>
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ laporan.judul }}</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                                    {{ laporan.user?.name }} — {{ laporan.created_at }}
                                </p>
                            </div>
                            <button @click="bukaModal(laporan)"
                                :class="{
                                    'bg-blue-100 text-blue-700 hover:bg-blue-200': laporan.status === 'open',
                                    'bg-yellow-100 text-yellow-700 hover:bg-yellow-200': laporan.status === 'proses',
                                    'bg-green-100 text-green-700 hover:bg-green-200': laporan.status === 'selesai',
                                }"
                                class="text-xs px-3 py-1.5 rounded-lg font-medium capitalize transition-colors shrink-0">
                                {{ laporan.status }}
                            </button>
                        </div>
                        <div v-if="laporanKeluhan.length === 0" class="px-5 py-8 text-center text-gray-400 text-sm">
                            Tidak ada laporan keluhan
                        </div>
                    </div>
                </div>

                <!-- Modal Laporan -->
                <Transition
                    enter-active-class="transition ease-out duration-150"
                    enter-from-class="opacity-0"
                    enter-to-class="opacity-100"
                    leave-active-class="transition ease-in duration-100"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0">
                    <div v-if="modalLaporan" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-sm">
                            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                                <h2 class="font-bold text-gray-800 dark:text-gray-100">Detail Laporan</h2>
                                <button @click="modalLaporan = null" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="px-6 py-4 space-y-3 text-sm">
                                <div class="flex justify-between"><span class="text-gray-500">ID</span><span class="font-medium">#{{ modalLaporan.id }}</span></div>
                                <div class="flex justify-between"><span class="text-gray-500">Judul</span><span class="font-medium text-right ml-4">{{ modalLaporan.judul }}</span></div>
                                <div class="flex justify-between"><span class="text-gray-500">Pengirim</span><span class="font-medium">{{ modalLaporan.user?.name }}</span></div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-500">Status</span>
                                    <span :class="{
                                        'bg-blue-100 text-blue-700': modalLaporan.status === 'open',
                                        'bg-yellow-100 text-yellow-700': modalLaporan.status === 'proses',
                                        'bg-green-100 text-green-700': modalLaporan.status === 'selesai',
                                    }" class="px-2.5 py-1 rounded-full text-xs font-medium capitalize">
                                        {{ modalLaporan.status }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-gray-500 mb-1">Deskripsi</p>
                                    <p class="text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 rounded-xl px-3 py-2">{{ modalLaporan.deskripsi }}</p>
                                </div>
                            </div>
                            <div class="px-6 pb-5 space-y-2">
                                <button @click="updateStatus(modalLaporan.id, 'proses')"
                                    v-if="modalLaporan.status === 'open'"
                                    class="w-full bg-amber-500 hover:bg-amber-600 text-white py-2.5 rounded-xl text-sm font-semibold transition-colors">
                                    Tandai Sedang Diproses
                                </button>
                                <button @click="updateStatus(modalLaporan.id, 'selesai')"
                                    v-if="modalLaporan.status !== 'selesai'"
                                    class="w-full bg-green-600 hover:bg-green-700 text-white py-2.5 rounded-xl text-sm font-semibold transition-colors">
                                    Tandai Selesai
                                </button>
                                <button @click="modalLaporan = null"
                                    class="w-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 py-2.5 rounded-xl text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                </Transition>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import axios from 'axios'

const loading = ref(true)
const stats = ref({
    total_pelanggan_aktif: 0,
    total_pelanggan_nonaktif: 0,
    tagihan_pending: 0,
})
const pelanggan = ref([])
const laporanKeluhan = ref([])
const modalLaporan = ref(null)

const bukaModal = (laporan) => {
    modalLaporan.value = laporan
}

const updateStatus = async (id, status) => {
    await axios.put(`/admin/keluhan/${id}/status`, { status })
    const index = laporanKeluhan.value.findIndex(l => l.id === id)
    if (index !== -1) laporanKeluhan.value[index].status = status
    modalLaporan.value = null
}

onMounted(async () => {
    try {
        const [dashRes, keluhanRes] = await Promise.all([
            axios.get('/admin/dashboard-data'),
            axios.get('/admin/keluhan'),
        ])
        stats.value = dashRes.data.stats
        pelanggan.value = dashRes.data.pelanggan
        laporanKeluhan.value = keluhanRes.data
    } catch (e) {
        console.error('Gagal memuat data dashboard admin:', e)
    } finally {
        loading.value = false
    }
})

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
</script>
