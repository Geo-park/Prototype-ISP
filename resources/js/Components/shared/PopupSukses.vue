<template>
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-96 text-center">
            <!-- Icon sukses -->
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="text-3xl text-green-600">✓</span>
            </div>

            <h2 class="text-xl font-bold text-green-600 mb-1">Pembayaran Berhasil!</h2>
            <p class="text-gray-500 text-sm mb-4">Terima kasih atas pembayaran Anda</p>

            <!-- Detail -->
            <div class="bg-gray-50 rounded-lg p-4 text-sm text-left space-y-2 mb-4">
                <div class="flex justify-between">
                    <span class="text-gray-500">Paket</span>
                    <span class="font-medium">{{ data.nama_paket }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Metode</span>
                    <span class="font-medium">{{ data.metode }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Subtotal</span>
                    <span class="font-medium">{{ formatRupiah(data.subtotal) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">PPN</span>
                    <span class="font-medium">{{ formatRupiah(data.nominal_pajak) }}</span>
                </div>
                <div class="flex justify-between font-bold border-t pt-2">
                    <span>Total Bayar</span>
                    <span class="text-green-600">{{ formatRupiah(data.total) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">No Invoice</span>
                    <span class="font-medium">{{ data.no_invoice }}</span>
                </div>
            </div>

            <button @click="$emit('tutup')"
                class="w-full bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 text-sm">
                Selesai
            </button>
        </div>
    </div>
</template>

<script setup>
defineProps({
    data: Object,
})

defineEmits(['tutup'])

const formatRupiah = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(value)
}
</script>
