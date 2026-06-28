<template>
    <AdminLayout>
        <div class="p-6 max-w-2xl mx-auto">
            <div class="mb-4">
                <Link href="/admin/dashboard" class="text-blue-600 hover:underline text-sm">← Kembali</Link>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h1 class="text-2xl font-bold mb-6">Tambah Pelanggan Baru</h1>

                <div class="space-y-4">
                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input v-model="form.nama" type="text"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Nama lengkap pelanggan" />
                        <p v-if="errors.nama" class="text-red-500 text-xs mt-1">{{ errors.nama }}</p>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input v-model="form.email" type="email"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="email@example.com" />
                        <p v-if="errors.email" class="text-red-500 text-xs mt-1">{{ errors.email }}</p>
                    </div>

                    <!-- No WA -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No WhatsApp</label>
                        <input v-model="form.no_wa" type="text"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="08xxxxxxxxxx" />
                        <p v-if="errors.no_wa" class="text-red-500 text-xs mt-1">{{ errors.no_wa }}</p>
                    </div>

                    <!-- Alamat -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <textarea v-model="form.alamat" rows="3"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Alamat lengkap" />
                        <p v-if="errors.alamat" class="text-red-500 text-xs mt-1">{{ errors.alamat }}</p>
                    </div>

                    <!-- Paket -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Paket Internet</label>
                        <select v-model="form.paket_id"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih paket</option>
                            <option v-for="paket in pakets" :key="paket.id" :value="paket.id">
                                {{ paket.nama }} — {{ formatRupiah(paket.harga) }}/bulan
                            </option>
                        </select>
                        <p v-if="errors.paket_id" class="text-red-500 text-xs mt-1">{{ errors.paket_id }}</p>
                    </div>

                    <!-- ODP Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Optical Distribution Point (ODP)</label>
                        <select v-model="form.odp_id"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih ODP Terdekat</option>
                            <option v-for="o in odps" :key="o.id" :value="o.id">
                                📍 {{ o.nama }} ({{ o.kode }})
                            </option>
                        </select>
                        <p v-if="errors.odp_id" class="text-red-500 text-xs mt-1">{{ errors.odp_id }}</p>
                    </div>

                    <!-- PPPoE -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">PPPoE Username</label>
                            <input v-model="form.pppoe_username" type="text"
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="username.pppoe" />
                            <p v-if="errors.pppoe_username" class="text-red-500 text-xs mt-1">{{ errors.pppoe_username }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">PPPoE Password</label>
                            <input v-model="form.pppoe_password" type="text"
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="password" />
                            <p v-if="errors.pppoe_password" class="text-red-500 text-xs mt-1">{{ errors.pppoe_password }}</p>
                        </div>
                    </div>

                    <!-- Tanggal Aktivasi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Aktivasi</label>
                        <input v-model="form.tgl_aktivasi" type="date"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        <p v-if="errors.tgl_aktivasi" class="text-red-500 text-xs mt-1">{{ errors.tgl_aktivasi }}</p>
                    </div>

                    <!-- Submit -->
                    <div class="pt-4">
                        <button @click="submit" :disabled="loading"
                            class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 disabled:opacity-50">
                            {{ loading ? 'Menyimpan...' : 'Tambah Pelanggan' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import axios from 'axios'

const pakets = ref([])
const odps = ref([])
const loading = ref(false)
const errors = ref({})

const form = ref({
    nama: '',
    email: '',
    no_wa: '',
    alamat: '',
    paket_id: '',
    odp_id: '',
    pppoe_username: '',
    pppoe_password: '',
    tgl_aktivasi: new Date().toISOString().split('T')[0],
})

const formatRupiah = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(value)
}

const submit = async () => {
    loading.value = true
    errors.value = {}
    try {
        await axios.post('/admin/pelanggan/tambah', form.value)
        router.visit('/admin/dashboard')
    } catch (e) {
        if (e.response?.status === 422) {
            errors.value = e.response.data.errors
        } else {
            const msg = e.response?.data?.message || e.response?.data?.error || e.message || 'Gagal menyimpan data'
            alert('Error: ' + msg)
            console.error('Simpan pelanggan error:', e.response?.data)
        }
    } finally {
        loading.value = false
    }
}

onMounted(async () => {
    const [paketRes, odpRes] = await Promise.all([
        axios.get('/admin/paket'),
        axios.get('/admin/get-odps')
    ])
    pakets.value = paketRes.data
    odps.value = odpRes.data
})
</script>
