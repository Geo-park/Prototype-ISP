<template>
    <AdminLayout>
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-6">Dashboard Admin</h1>

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
                <h2 class="font-semibold mb-4">Daftar Pelanggan</h2>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left border-b">
                            <th class="pb-2">Nama</th>
                            <th class="pb-2">Paket</th>
                            <th class="pb-2">Status</th>
                            <th class="pb-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="p in pelanggan" :key="p.id" class="border-b">
                            <td class="py-2">{{ p.nama }}</td>
                            <td class="py-2">{{ p.paket?.nama }}</td>
                            <td class="py-2">
                                <span :class="p.status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                    class="px-2 py-1 rounded-full text-xs">
                                    {{ p.status }}
                                </span>
                            </td>
                            <td class="py-2 space-x-2">
                                <button v-if="p.status === 'aktif'"
                                    @click="matikanKoneksi(p.id)"
                                    class="text-xs bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">
                                    Matikan
                                </button>
                                <button v-else
                                    @click="hidupkanKoneksi(p.id)"
                                    class="text-xs bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600">
                                    Hidupkan
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Tiket Aktif -->
            <div class="bg-white rounded-lg shadow p-4">
                <h2 class="font-semibold mb-4">Tiket Aktif</h2>
                <ul class="space-y-2">
                    <li v-for="tiket in tiketAktif" :key="tiket.id"
                        class="text-sm border-b pb-2 flex justify-between">
                        <span>{{ tiket.judul }} — {{ tiket.pelanggan }}</span>
                        <span class="text-yellow-600 text-xs">{{ tiket.status }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import axios from 'axios'

const stats = ref({
    total_pelanggan_aktif: 0,
    total_pelanggan_nonaktif: 0,
    tagihan_pending: 0,
})
const pelanggan = ref([])
const tiketAktif = ref([])

onMounted(async () => {
    const [statsRes, pelangganRes, tiketRes] = await Promise.all([
        axios.get('/admin/stats'),
        axios.get('/admin/pelanggan'),
        axios.get('/admin/tiket-aktif'),
    ])
    stats.value = statsRes.data
    pelanggan.value = pelangganRes.data
    tiketAktif.value = tiketRes.data
})

const matikanKoneksi = async (id) => {
    await axios.post(`/admin/koneksi/matikan/${id}`)
    pelanggan.value = pelanggan.value.map(p =>
        p.id === id ? { ...p, status: 'nonaktif' } : p
    )
}

const hidupkanKoneksi = async (id) => {
    await axios.post(`/admin/koneksi/hidupkan/${id}`)
    pelanggan.value = pelanggan.value.map(p =>
        p.id === id ? { ...p, status: 'aktif' } : p
    )
}
</script>
