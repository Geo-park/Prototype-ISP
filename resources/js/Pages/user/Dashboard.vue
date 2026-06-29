<template>
    <UserLayout>
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-6">Dashboard Pelanggan</h1>

            <!-- Loading State -->
            <div v-if="loading" class="flex items-center justify-center h-64">
                <div class="text-center">
                    <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-600 mx-auto mb-3"></div>
                    <p class="text-gray-500 text-sm">Memuat data...</p>
                </div>
            </div>

            <!-- Konten -->
            <div v-else>
                <!-- Status Koneksi -->
                <div class="mb-4">
                    <StatusKoneksi :profil="profil" :loading="loading" />
                </div>

                <!-- Tagihan Aktif -->
                <div class="mb-4">
                    <TagihanCard :tagihan="tagihan" :loading="loading" />
                </div>

                <!-- Riwayat Pembayaran -->
                <div class="mb-4">
                    <RiwayatPembayaran :pembayaran="pembayaran" :loading="loading" />
                </div>

                <!-- Riwayat Catatan Pajak -->
                <div class="bg-white rounded-lg shadow p-4">
                    <h2 class="font-semibold mb-3">Catatan Pajak</h2>

                    <div v-if="pajak.length === 0"
                        class="text-center py-6 text-sm text-gray-400">
                        Belum ada catatan pajak
                    </div>

                    <div v-else class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-left border-b text-gray-500">
                                    <th class="pb-2 font-medium">No Faktur</th>
                                    <th class="pb-2 font-medium">Tanggal</th>
                                    <th class="pb-2 font-medium text-right">Subtotal</th>
                                    <th class="pb-2 font-medium text-right">Pajak</th>
                                    <th class="pb-2 font-medium text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="p in pajak" :key="p.id" class="border-b last:border-0">
                                    <td class="py-2 text-gray-700 font-medium">{{ p.no_faktur }}</td>
                                    <td class="py-2 text-gray-600">{{ formatDate(p.tgl_faktur) }}</td>
                                    <td class="py-2 text-right text-gray-600">{{ formatRupiah(p.subtotal) }}</td>
                                    <td class="py-2 text-right text-gray-600">{{ formatRupiah(p.nominal_pajak) }}</td>
                                    <td class="py-2 text-right font-medium text-blue-600">{{ formatRupiah(p.total) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </UserLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import UserLayout from '@/Layouts/UserLayout.vue'
import StatusKoneksi from '@/Components/user/StatusKoneksi.vue'
import TagihanCard from '@/Components/user/TagihanCard.vue'
import RiwayatPembayaran from '@/Components/user/RiwayatPembayaran.vue'

const loading = ref(true)
const profil = ref({})
const tagihan = ref([])
const pembayaran = ref([])
const pajak = ref([])

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

onMounted(async () => {
    try {
        const [res] = await Promise.all([
            axios.get('/user/dashboard-data')
        ])
        profil.value = res.data.profil
        tagihan.value = res.data.tagihan
        pembayaran.value = res.data.pembayaran
        pajak.value = res.data.pajak
    } catch (err) {
        console.error('Gagal memuat data dashboard:', err)
    } finally {
        loading.value = false
    }
})
</script>
