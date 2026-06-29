<template>
    <SuperadminLayout>
        <div class="p-6 max-w-2xl mx-auto">
            <div class="mb-4">
                <Link href="/superadmin/users/page" class="text-blue-600 hover:underline text-sm font-medium">← Kembali</Link>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h1 class="text-2xl font-bold mb-6">Tambah Admin Daerah</h1>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Penanggung Jawab</label>
                        <input v-model="form.name" type="text"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Nama lengkap penanggung jawab" />
                        <p v-if="errors.name" class="text-red-500 text-xs mt-1">{{ errors.name }}</p>
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">Daerah yang Ditangani</label>
                        <input v-model="form.daerah" type="text"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="contoh: Banten, Jakarta, dll" />
                        <p v-if="errors.daerah" class="text-red-500 text-xs mt-1">{{ errors.daerah }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Operasional</label>
                        <textarea v-model="form.alamat" rows="3"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Alamat lengkap kantor/operasional daerah" />
                        <p v-if="errors.alamat" class="text-red-500 text-xs mt-1">{{ errors.alamat }}</p>
                    </div>

                    <p class="text-xs text-gray-400">Password default: <span class="font-medium">demo1234</span></p>

                    <div class="pt-4">
                        <button @click="submit" :disabled="loading"
                            class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 disabled:opacity-50">
                            {{ loading ? 'Menyimpan...' : 'Tambah Admin' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </SuperadminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import SuperadminLayout from '@/Layouts/SuperadminLayout.vue'
import axios from 'axios'

const loading = ref(false)
const errors = ref({})

const form = ref({
    name: '',
    email: '',
    no_wa: '',
    daerah: '',
    alamat: '',
})

const submit = async () => {
    loading.value = true
    errors.value = {}
    try {
        await axios.post('/superadmin/users/tambah-admin', form.value)
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
</script>
