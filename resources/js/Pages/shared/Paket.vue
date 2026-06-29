<template>
    <component :is="Layout">
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-2">Paket Internet</h1>
            <p class="text-gray-500 text-sm mb-6">Pilih paket internet yang sesuai dengan kebutuhan Anda.</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div v-for="paket in pakets" :key="paket.id"
                    class="bg-white rounded-lg shadow p-6 flex flex-col">
                    <!-- Header -->
                    <div class="mb-4 pb-4 border-b">
                        <h2 class="text-xl font-bold text-blue-600">{{ paket.nama }}</h2>
                        <p class="text-3xl font-bold mt-2">{{ formatRupiah(paket.harga) }}
                            <span class="text-sm font-normal text-gray-500">/bulan</span>
                        </p>
                    </div>

                    <!-- Detail -->
                    <div class="space-y-2 flex-1 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Kecepatan Download</span>
                            <span class="font-medium">{{ paket.bandwidth_down }} {{ paket.satuan }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Kecepatan Upload</span>
                            <span class="font-medium">{{ paket.bandwidth_up }} {{ paket.satuan }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Masa Aktif</span>
                            <span class="font-medium">{{ paket.masa_aktif }} hari</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">PPN ({{ paket.persen_pajak }}%)</span>
                            <span class="font-medium">{{ formatRupiah(paket.harga_pajak) }}</span>
                        </div>
                        <div class="flex justify-between font-bold border-t pt-2 mt-2">
                            <span>Total</span>
                            <span class="text-blue-600">{{ formatRupiah(paket.total_harga) }}</span>
                        </div>
                    </div>

                    <!-- Badge -->
                    <div class="mt-4 pt-4 border-b pb-4">
                        <div class="flex flex-wrap gap-2">
                            <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded text-xs">✓ Tanpa FUP</span>
                            <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded text-xs">✓ SLA 99.9%</span>
                            <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded text-xs">✓ Support 24/7</span>
                        </div>
                    </div>

                    <!-- Tombol Beli -->
                    <button @click="bukaPembayaran(paket)"
                        class="mt-4 w-full bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 text-sm">
                        Beli Paket
                    </button>
                </div>
            </div>

            <!-- Modal Pembayaran -->
            <div v-if="modalPaket" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-lg p-6 w-96">
                    <div class="flex justify-between items-start mb-4">
                        <h2 class="font-bold text-lg">Pembayaran Paket</h2>
                        <button @click="modalPaket = null" class="text-gray-400 hover:text-gray-600">✕</button>
                    </div>

                    <!-- Info Paket -->
                    <div class="bg-gray-50 rounded-lg p-3 mb-4 text-sm">
                        <p class="font-medium">{{ modalPaket.nama }}</p>
                        <p class="text-blue-600 font-bold text-lg">{{ formatRupiah(modalPaket.total_harga) }}</p>
                    </div>

                    <!-- Pilih Metode -->
                    <p class="text-sm font-medium text-gray-700 mb-2">Pilih Metode Pembayaran</p>
                    <div class="flex gap-2 mb-4">
                        <button v-for="m in ['QRIS', 'virtual_account', 'transfer']" :key="m"
                            @click="metodeBayar = m"
                            :class="metodeBayar === m ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 border'"
                            class="flex-1 py-2 rounded-lg text-xs font-medium">
                            {{ m === 'virtual_account' ? 'VA' : m }}
                        </button>
                    </div>

                    <!-- ... rest of instructions ... -->
                    <div class="bg-gray-50 rounded-lg p-3 mb-4 text-sm text-gray-600">
                        <p v-if="metodeBayar === 'QRIS'">Scan QR Code menggunakan aplikasi dompet digital kamu.</p>
                        <p v-else-if="metodeBayar === 'virtual_account'">
                            Transfer ke Virtual Account: <span class="font-bold">8277-0001-2345-6789</span>
                        </p>
                        <p v-else>
                            Transfer ke rekening: <span class="font-bold">BCA 1234567890 a/n ISP Manager</span>
                        </p>
                        <p class="mt-2 font-semibold">Total: {{ formatRupiah(modalPaket.total_harga) }}</p>
                    </div>

                    <button @click="simulasiBayar" :disabled="loading"
                        class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700 disabled:opacity-50 text-sm">
                        {{ loading ? 'Memproses...' : 'Simulasi Bayar Berhasil' }}
                    </button>
                </div>
            </div>
            <PopupSukses v-if="dataSukses" :data="dataSukses" @tutup="selesai" />
        </div>
    </component>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import SuperadminLayout from '@/Layouts/SuperadminLayout.vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import UserLayout from '@/Layouts/UserLayout.vue'
import PopupSukses from '@/Components/shared/PopupSukses.vue'
import axios from 'axios'

const page = usePage()
const role = computed(() => page.props.auth.user.role)

const Layout = computed(() => {
    if (role.value === 'superadmin') return SuperadminLayout
    if (role.value === 'admin') return AdminLayout
    return UserLayout
})

const pakets = ref([])
const modalPaket = ref(null)
const metodeBayar = ref('QRIS')
const loading = ref(false)
const dataSukses = ref(null)

const formatRupiah = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(value)
}

const bukaPembayaran = (paket) => {
    modalPaket.value = paket
    metodeBayar.value = 'QRIS'
}

const simulasiBayar = async () => {
    loading.value = true
    try {
        const res = await axios.post('/user/beli-paket', {
            paket_id: modalPaket.value.id,
            metode: metodeBayar.value,
        })
        modalPaket.value = null
        dataSukses.value = res.data
    } catch (e) {
        alert('Gagal memproses pembayaran')
    } finally {
        loading.value = false
    }
}

const selesai = () => {
    dataSukses.value = null
    router.visit('/user/dashboard')
}

onMounted(async () => {
    const res = await axios.get('/paket/list')
    pakets.value = res.data
})
</script>
