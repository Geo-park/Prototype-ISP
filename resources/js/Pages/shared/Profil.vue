<template>
    <component :is="Layout">
        <div class="p-6 max-w-2xl mx-auto">
            <h1 class="text-2xl font-bold mb-6">Profil Saya</h1>

            <div class="bg-white rounded-lg shadow p-6">
                <!-- Avatar -->
                <div class="flex items-center gap-4 mb-6 pb-6 border-b">
                    <div class="w-16 h-16 rounded-full bg-blue-600 flex items-center justify-center text-white text-2xl font-bold">
                        {{ inisial }}
                    </div>
                    <div>
                        <p class="text-xl font-bold">{{ profil.name }}</p>
                        <span :class="badgeRole" class="px-2 py-1 rounded-full text-xs font-medium">
                            {{ profil.role }}
                        </span>
                    </div>
                </div>

                <!-- Info -->
                <div class="space-y-4 text-sm">
                    <div class="grid grid-cols-3 gap-4 py-3 border-b">
                        <span class="text-gray-500">Nama</span>
                        <span class="col-span-2 font-medium">{{ profil.name }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-4 py-3 border-b">
                        <span class="text-gray-500">Email</span>
                        <span class="col-span-2 font-medium">{{ profil.email }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-4 py-3 border-b">
                        <span class="text-gray-500">No WhatsApp</span>
                        <span class="col-span-2 font-medium">{{ profil.no_wa ?? '-' }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-4 py-3 border-b">
                        <span class="text-gray-500">Daerah</span>
                        <span class="col-span-2 font-medium">{{ profil.daerah ?? '-' }}</span>
                    </div>

                    <!-- Khusus user/pelanggan -->
                    <template v-if="profil.role === 'user' && pelanggan">
                        <div class="grid grid-cols-3 gap-4 py-3 border-b">
                            <span class="text-gray-500">ID Langganan</span>
                            <span class="col-span-2 font-medium">{{ pelanggan.no_pelanggan }}</span>
                        </div>
                        <div class="grid grid-cols-3 gap-4 py-3 border-b">
                            <span class="text-gray-500">Paket Aktif</span>
                            <span class="col-span-2 font-medium">{{ pelanggan.paket?.nama ?? '-' }}</span>
                        </div>
                        <div class="grid grid-cols-3 gap-4 py-3 border-b">
                            <span class="text-gray-500">Status Koneksi</span>
                            <span class="col-span-2">
                                <span :class="pelanggan.status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                    class="px-2 py-1 rounded-full text-xs font-medium">
                                    {{ pelanggan.status }}
                                </span>
                            </span>
                        </div>
                        <div class="grid grid-cols-3 gap-4 py-3 border-b">
                            <span class="text-gray-500">Alamat</span>
                            <span class="col-span-2 font-medium">{{ pelanggan.alamat }}</span>
                        </div>
                    </template>

                    <!-- Khusus admin -->
                    <template v-if="profil.role === 'admin' && profil.alamat">
                        <div class="grid grid-cols-3 gap-4 py-3 border-b">
                            <span class="text-gray-500">Alamat Operasional</span>
                            <span class="col-span-2 font-medium">{{ profil.alamat }}</span>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </component>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
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

const profil = ref({})
const pelanggan = ref(null)

const inisial = computed(() => {
    return profil.value.name?.charAt(0).toUpperCase() ?? '?'
})

const badgeRole = computed(() => ({
    'bg-blue-100 text-blue-800': role.value === 'superadmin',
    'bg-green-100 text-green-800': role.value === 'admin',
    'bg-gray-100 text-gray-800': role.value === 'user',
}))

onMounted(async () => {
    const res = await axios.get('/profil/data')
    profil.value = res.data.user
    pelanggan.value = res.data.pelanggan
})
</script>
