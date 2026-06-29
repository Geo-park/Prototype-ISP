<template>
    <component :is="Layout">
        <div class="p-6 max-w-2xl mx-auto">
            <h1 class="text-2xl font-bold mb-2">Pusat Bantuan</h1>
            <p class="text-gray-500 text-sm mb-6">Ada kendala? Kirim keluhan dan tim kami akan segera menindaklanjuti.</p>

            <!-- Form Keluhan -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="font-semibold mb-4">Kirim Keluhan</h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Keluhan</label>
                        <input v-model="form.judul" type="text"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="contoh: Koneksi lambat, tidak bisa konek, dll" />
                        <p v-if="errors.judul" class="text-red-500 text-xs mt-1">{{ errors.judul }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea v-model="form.deskripsi" rows="4"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Jelaskan masalah yang kamu alami secara detail..." />
                        <p v-if="errors.deskripsi" class="text-red-500 text-xs mt-1">{{ errors.deskripsi }}</p>
                    </div>

                    <div v-if="sukses" class="bg-green-50 text-green-700 px-4 py-3 rounded-lg text-sm">
                        ✓ Keluhan berhasil dikirim. Tim kami akan segera menindaklanjuti.
                    </div>

                    <button @click="submit" :disabled="loading"
                        class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 disabled:opacity-50">
                        {{ loading ? 'Mengirim...' : 'Kirim Keluhan' }}
                    </button>
                </div>
            </div>

            <!-- FAQ -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="font-semibold mb-4">Pertanyaan Umum</h2>
                <div class="space-y-3">
                    <div v-for="faq in faqs" :key="faq.id" class="border-b pb-3">
                        <button @click="faq.open = !faq.open"
                            class="w-full text-left flex justify-between items-center text-sm font-medium text-gray-700">
                            {{ faq.pertanyaan }}
                            <span>{{ faq.open ? '−' : '+' }}</span>
                        </button>
                        <p v-if="faq.open" class="mt-2 text-sm text-gray-500">{{ faq.jawaban }}</p>
                    </div>
                </div>
            </div>
        </div>
    </component>
</template>

<script setup>
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import SuperadminLayout from '@/Layouts/SuperadminLayout.vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import UserLayout from '@/Layouts/UserLayout.vue'
import axios from 'axios'

const page = usePage()
const role = computed(() => page.props.auth.user.role)

const Layout = computed(() => {
    if (role.value === 'superadmin') return SuperadminLayout
    if (role.value === 'admin') return AdminLayout
    return UserLayout
})

const loading = ref(false)
const sukses = ref(false)
const errors = ref({})

const form = ref({
    judul: '',
    deskripsi: '',
})

const faqs = ref([
    {
        id: 1,
        pertanyaan: 'Bagaimana cara melakukan pembayaran tagihan?',
        jawaban: 'Login ke akun Anda, masuk ke menu Invoice, pilih invoice yang ingin dibayar, lalu klik Bayar Sekarang dan ikuti instruksi pembayaran.',
        open: false,
    },
    {
        id: 2,
        pertanyaan: 'Koneksi saya lambat, apa yang harus dilakukan?',
        jawaban: 'Coba restart router terlebih dahulu. Jika masih lambat, kirim keluhan melalui form di atas dengan menyertakan waktu kejadian dan kecepatan yang Anda rasakan.',
        open: false,
    },
    {
        id: 3,
        pertanyaan: 'Berapa lama keluhan akan ditangani?',
        jawaban: 'Tim kami menargetkan respons dalam 1x24 jam untuk setiap keluhan yang masuk. Untuk gangguan kritis, penanganan dilakukan dalam 4 jam.',
        open: false,
    },
    {
        id: 4,
        pertanyaan: 'Bagaimana cara mengubah paket internet?',
        jawaban: 'Hubungi admin daerah Anda melalui WhatsApp atau kirim keluhan melalui form di atas dengan mencantumkan paket yang Anda inginkan.',
        open: false,
    },
])

const submit = async () => {
    loading.value = true
    errors.value = {}
    sukses.value = false
    try {
        await axios.post('/keluhan', form.value)
        sukses.value = true
        form.value = { judul: '', deskripsi: '' }
    } catch (e) {
        if (e.response?.status === 422) {
            errors.value = e.response.data.errors
        } else {
            alert('Gagal mengirim keluhan')
        }
    } finally {
        loading.value = false
    }
}
</script>
