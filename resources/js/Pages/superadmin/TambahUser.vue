<template>
    <SuperadminLayout>
        <div class="p-6 max-w-2xl mx-auto">
            <div class="mb-4">
                <Link href="/superadmin/users/page" class="text-blue-600 hover:underline text-sm">← Kembali</Link>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h1 class="text-2xl font-bold mb-6">Tambah Pelanggan Baru</h1>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input v-model="form.nama" type="text"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Nama lengkap pelanggan" />
                        <p v-if="errors.nama" class="text-red-500 text-xs mt-1">{{ errors.nama }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input v-model="form.email" type="email"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="email@example.com" />
                        <p v-if="errors.email" class="text-red-500 text-xs mt-1">{{ errors.email }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No WhatsApp</label>
                        <input v-model="form.no_wa" type="text"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="08xxxxxxxxxx" />
                        <p v-if="errors.no_wa" class="text-red-500 text-xs mt-1">{{ errors.no_wa }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <textarea v-model="form.alamat" rows="3"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Alamat lengkap" />
                        <p v-if="errors.alamat" class="text-red-500 text-xs mt-1">{{ errors.alamat }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Daerah</label>
                        <input v-model="form.daerah" type="text"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="contoh: Banten, Jakarta" />
                        <p v-if="errors.daerah" class="text-red-500 text-xs mt-1">{{ errors.daerah }}</p>
                    </div>

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

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Aktivasi</label>
                        <input v-model="form.tgl_aktivasi" type="date"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        <p v-if="errors.tgl_aktivasi" class="text-red-500 text-xs mt-1">{{ errors.tgl_aktivasi }}</p>
                    </div>

                    <div class="pt-4">
                        <button @click="submit" :disabled="loading"
                            class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 disabled:opacity-50">
                            {{ loading ? 'Menyimpan...' : 'Tambah Pelanggan' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </SuperadminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import SuperadminLayout from '@/Layouts/SuperadminLayout.vue'
import axios from 'axios'

const pakets = ref([])
const loading = ref(false)
const errors = ref({})

const form = ref({
    nama: '',
    email: '',
    no_wa: '',
    alamat: '',
    daerah: '',
    paket_id: '',
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
        await axios.post('/superadmin/users/tambah-user', form.value)
        router.visit('/superadmin/users/page')
    } catch (e) {
        if (e.response?.status === 422) {
            errors.value = e.response.data.errors
        } else {
            alert('Gagal menyimpan data')
        }
    } finally {
        loading.value = false
    }
}

onMounted(async () => {
    const res = await axios.get('/admin/paket')
    pakets.value = res.data
})
</script>
