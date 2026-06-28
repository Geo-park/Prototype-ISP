<template>
    <AdminLayout>
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Dashboard Admin</h1>
                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                    📍 {{ stats.daerah }}
                </span>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-gray-500 text-sm">Pelanggan Aktif</p>
                    <p class="text-2xl font-bold text-green-600">{{ stats.total_pelanggan_aktif }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-gray-500 text-sm">Pelanggan Nonaktif</p>
                    <p class="text-2xl font-bold text-red-600">{{ stats.total_pelanggan_nonaktif }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-gray-500 text-sm">Tagihan Pending</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ stats.tagihan_pending }}</p>
                </div>
            </div>

            <!-- Tabel Pelanggan -->
            <div class="bg-white rounded-lg shadow p-4 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="font-semibold">Daftar Pelanggan</h2>
                    <Link href="/admin/pelanggan/tambah"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
                        + Tambah Pelanggan
                    </Link>
                </div>
                <table class="w-full text-sm table-fixed">
                    <colgroup>
                        <col class="w-1/4" />
                        <col class="w-1/4" />
                        <col class="w-1/4" />
                        <col style="width: 110px" />
                        <col style="width: 100px" />
                    </colgroup>
                    <thead>
                        <tr class="text-left border-b">
                            <th class="pb-2">Nama</th>
                            <th class="pb-2">Paket</th>
                            <th class="pb-2">Daerah</th>
                            <th class="pb-2">Status</th>
                            <th class="pb-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="p in pelanggan" :key="p.id" class="border-b">
                            <td class="py-2">{{ p.nama }}</td>
                            <td class="py-2">{{ p.paket?.nama }}</td>
                            <td class="py-2">{{ p.daerah }}</td>
                            <td class="py-2">
                                <span
                                    :class="p.status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                    class="inline-block w-20 text-center px-2 py-1 rounded-full text-xs font-medium">
                                    {{ p.status }}
                                </span>
                            </td>
                            <td class="py-2">
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
                        <span>{{ laporan.judul }} — {{ laporan.pelanggan }}</span>
                        <button @click="bukaModal(laporan)"
                            class="text-xs bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600">
                            open
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
                        <p><span class="text-gray-500">Pelanggan:</span> {{ modalLaporan.pelanggan }}</p>
                        <p><span class="text-gray-500">Status:</span>
                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs ml-1">
                                {{ modalLaporan.status }}
                            </span>
                        </p>
                        <p><span class="text-gray-500">Deskripsi:</span> {{ modalLaporan.deskripsi }}</p>
                    </div>
                    <div class="mt-4 flex gap-2">
                        <button @click="modalLaporan = null"
                            class="flex-1 bg-gray-100 text-gray-700 py-2 rounded hover:bg-gray-200 text-sm">
                            Tutup
                        </button>
                        <button @click="modalLaporan = null"
                            class="flex-1 bg-green-600 text-white py-2 rounded hover:bg-green-700 text-sm">
                            Tandai Selesai
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import axios from 'axios'

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

onMounted(async () => {
    const [statsRes, pelangganRes, laporanRes] = await Promise.all([
        axios.get('/admin/stats'),
        axios.get('/admin/pelanggan'),
        axios.get('/admin/laporan-keluhan'),
    ])
    stats.value = statsRes.data
    pelanggan.value = pelangganRes.data
    laporanKeluhan.value = laporanRes.data
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
