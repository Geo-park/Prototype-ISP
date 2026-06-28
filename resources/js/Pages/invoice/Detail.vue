<template>
    <component :is="Layout">
        <div class="p-6 max-w-3xl mx-auto">
            <div class="mb-4">
                <Link href="/invoice" class="text-blue-600 hover:underline text-sm">← Kembali ke Daftar Invoice</Link>
            </div>

            <div class="bg-white rounded-lg shadow p-6 mb-4">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h1 class="text-2xl font-bold">{{ invoice.no_invoice }}</h1>
                        <p class="text-gray-500 text-sm">Periode: {{ invoice.periode }}</p>
                    </div>
                    <span :class="badgeClass(invoice.status)" class="px-3 py-1 rounded-full text-sm font-medium">
                        {{ invoice.status }}
                    </span>
                </div>

                <!-- Info Pelanggan -->
                <div class="border-b pb-4 mb-4">
                    <h2 class="font-semibold mb-2 text-gray-700">Info Pelanggan</h2>
                    <p class="text-sm">Nama: <span class="font-medium">{{ invoice.pelanggan?.nama }}</span></p>
                    <p class="text-sm">No Pelanggan: <span class="font-medium">{{ invoice.pelanggan?.no_pelanggan }}</span></p>
                    <p class="text-sm">Paket: <span class="font-medium">{{ invoice.nama_paket }} ({{ invoice.bandwidth }})</span></p>
                </div>

                <!-- Breakdown Harga -->
                <div class="border-b pb-4 mb-4">
                    <h2 class="font-semibold mb-2 text-gray-700">Rincian Tagihan</h2>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">Subtotal</span>
                        <span>{{ formatRupiah(invoice.subtotal) }}</span>
                    </div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">PPN ({{ invoice.persen_pajak }}%)</span>
                        <span>{{ formatRupiah(invoice.nominal_pajak) }}</span>
                    </div>
                    <div class="flex justify-between font-bold mt-2 text-base">
                        <span>Total</span>
                        <span class="text-blue-600">{{ formatRupiah(invoice.total) }}</span>
                    </div>
                </div>

                <!-- Info Tanggal -->
                <div class="mb-6">
                    <p class="text-sm text-gray-600">Tgl Invoice: <span class="font-medium">{{ invoice.tgl_invoice }}</span></p>
                    <p class="text-sm text-gray-600">Jatuh Tempo: <span class="font-medium">{{ invoice.tgl_jatuh_tempo }}</span></p>
                </div>

                <!-- Tombol Bayar -->
                <div v-if="invoice.status === 'pending' || invoice.status === 'overdue'">
                    <h2 class="font-semibold mb-3 text-gray-700">Simulasi Pembayaran</h2>
                    <div class="flex gap-2 mb-4">
                        <button v-for="m in ['QRIS', 'virtual_account', 'transfer']" :key="m"
                            @click="metodeBayar = m"
                            :class="metodeBayar === m ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 border'"
                            class="px-4 py-2 rounded-lg text-sm">
                            {{ m }}
                        </button>
                    </div>

                    <!-- Instruksi dummy -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-4 text-sm text-gray-600">
                        <p v-if="metodeBayar === 'QRIS'">Scan QR Code berikut menggunakan aplikasi dompet digital kamu.</p>
                        <p v-else-if="metodeBayar === 'virtual_account'">Transfer ke Virtual Account: <span class="font-bold">8277-0001-2345-6789</span></p>
                        <p v-else>Transfer ke rekening: <span class="font-bold">BCA 1234567890 a/n ISP Manager</span></p>
                        <p class="mt-2 font-semibold">Jumlah: {{ formatRupiah(invoice.total) }}</p>
                    </div>

                    <button @click="simulasiBayar"
                        :disabled="loading"
                        class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700 disabled:opacity-50">
                        {{ loading ? 'Memproses...' : 'Simulasi Bayar Berhasil' }}
                    </button>
                </div>

                <!-- Sudah Paid -->
                <div v-if="invoice.status === 'paid'" class="bg-green-50 rounded-lg p-4 text-center">
                    <p class="text-green-600 font-semibold">✓ Invoice ini sudah lunas</p>
                </div>
            </div>
        </div>
    </component>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import SuperadminLayout from '@/Layouts/SuperadminLayout.vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import axios from 'axios'

const props = defineProps({
    invoice: Object,
})

const page = usePage()
const role = computed(() => page.props.auth.user.role)
const Layout = computed(() => role.value === 'superadmin' ? SuperadminLayout : AdminLayout)

const metodeBayar = ref('QRIS')
const loading = ref(false)

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

const simulasiBayar = async () => {
    loading.value = true
    try {
        await axios.post(`/invoice/${props.invoice.id}/simulasi-bayar`, {
            metode: metodeBayar.value,
        })
        router.reload()
    } catch (e) {
        alert('Gagal memproses pembayaran')
    } finally {
        loading.value = false
    }
}
</script>
