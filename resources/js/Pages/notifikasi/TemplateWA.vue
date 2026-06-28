<template>
    <SuperadminLayout>
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-2">Template WhatsApp</h1>
            <p class="text-gray-500 text-sm mb-6">Preview 7 template notifikasi yang akan dikirim ke pelanggan via WhatsApp</p>

            <!-- Variable preview controls -->
            <div class="bg-white rounded-lg shadow p-4 mb-6">
                <h2 class="font-semibold text-sm mb-3 text-gray-700">Data Preview</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Nama Pelanggan</label>
                        <input v-model="variables.Nama" type="text"
                            class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">No Invoice</label>
                        <input v-model="variables.NoInvoice" type="text"
                            class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Total</label>
                        <input v-model="variables.Total" type="text"
                            class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Tanggal</label>
                        <input v-model="variables.Tanggal" type="text"
                            class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>

            <!-- Loading -->
            <div v-if="loading" class="text-center py-10">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto mb-3"></div>
                <p class="text-gray-500 text-sm">Memuat template...</p>
            </div>

            <!-- Template list -->
            <div v-else class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <TemplateWACard
                    v-for="tmpl in templates"
                    :key="tmpl.id"
                    :template="tmpl"
                    :variables="variables"
                    @kirim="simulasiKirim"
                />
            </div>

            <!-- Toast notification -->
            <transition
                enter-active-class="transition ease-out duration-300"
                enter-from-class="opacity-0 translate-y-2"
                enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition ease-in duration-200"
                leave-from-class="opacity-100 translate-y-0"
                leave-to-class="opacity-0 translate-y-2">
                <div v-if="toast"
                    class="fixed bottom-6 right-6 bg-green-600 text-white px-5 py-3 rounded-lg shadow-lg z-50 flex items-center gap-2">
                    <span>✅</span>
                    <span class="text-sm">{{ toast }}</span>
                </div>
            </transition>
        </div>
    </SuperadminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import SuperadminLayout from '@/Layouts/SuperadminLayout.vue'
import TemplateWACard from '@/Components/notifikasi/TemplateWA.vue'

const loading = ref(true)
const templates = ref([])
const toast = ref(null)

const variables = ref({
    Nama: 'Budi Santoso',
    NoInvoice: 'INV-2025-001',
    Total: 'Rp 277.500',
    Tanggal: '1 Juli 2025',
    Paket: 'Rumahan 20 Mbps',
    Bandwidth: '20 Mbps',
    NoFaktur: 'FKT-2025-001',
})

onMounted(async () => {
    try {
        const res = await axios.get('/notifikasi/templates')
        templates.value = res.data
    } catch (err) {
        console.error('Gagal memuat templates:', err)
    } finally {
        loading.value = false
    }
})

const simulasiKirim = async (template) => {
    try {
        const res = await axios.post('/notifikasi/simulasi-kirim', {
            template_id: template.id,
            no_wa: '081234567890',
        })
        toast.value = res.data.message
        setTimeout(() => { toast.value = null }, 3000)
    } catch (err) {
        toast.value = 'Gagal mengirim pesan'
        setTimeout(() => { toast.value = null }, 3000)
    }
}
</script>
