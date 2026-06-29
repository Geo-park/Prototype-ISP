<template>
    <component :is="Layout">
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-6">Laporan Keluhan</h1>

            <!-- Filter Status -->
            <div class="mb-4 flex gap-2">
                <button v-for="s in ['semua', 'open', 'proses', 'selesai']" :key="s"
                    @click="filterStatus = s"
                    :class="filterStatus === s ? 'bg-blue-600 text-white' : 'bg-white text-gray-600'"
                    class="px-4 py-2 rounded-lg text-sm border">
                    {{ s }}
                </button>
            </div>

            <!-- Tabel -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left border-b bg-gray-50 dark:bg-gray-700">
                            <th class="p-4">Pelanggan</th>
                            <th class="p-4">Judul</th>
                            <th class="p-4">Waktu</th>
                            <th class="p-4">Status</th>
                            <th class="p-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="k in keluhanFiltered" :key="k.id"
                            class="border-b hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="p-4">{{ k.user?.name }}</td>
                            <td class="p-4">{{ k.judul }}</td>
                            <td class="p-4 text-gray-500 text-xs">{{ formatDate(k.created_at) }}</td>
                            <td class="p-4">
                                <span :class="badgeStatus(k.status)"
                                    class="px-2 py-1 rounded-full text-xs font-medium">
                                    {{ k.status }}
                                </span>
                            </td>
                            <td class="p-4">
                                <button @click="bukaModal(k)"
                                    class="text-xs bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                                    Detail
                                </button>
                            </td>
                        </tr>
                        <tr v-if="keluhanFiltered.length === 0">
                            <td colspan="5" class="p-4 text-center text-gray-400">
                                Tidak ada laporan keluhan
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Modal Detail -->
            <div v-if="modalKeluhan"
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-[480px]">
                    <div class="flex justify-between items-start mb-4">
                        <h2 class="font-bold text-lg">Detail Keluhan</h2>
                        <button @click="modalKeluhan = null"
                            class="text-gray-400 hover:text-gray-600">✕</button>
                    </div>

                    <div class="space-y-3 text-sm mb-4">
                        <div class="grid grid-cols-3 gap-2">
                            <span class="text-gray-500">Pelanggan</span>
                            <span class="col-span-2 font-medium">{{ modalKeluhan.user?.name }}</span>
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            <span class="text-gray-500">Judul</span>
                            <span class="col-span-2 font-medium">{{ modalKeluhan.judul }}</span>
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            <span class="text-gray-500">Deskripsi</span>
                            <span class="col-span-2">{{ modalKeluhan.deskripsi }}</span>
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            <span class="text-gray-500">Status</span>
                            <span class="col-span-2">
                                <span :class="badgeStatus(modalKeluhan.status)"
                                    class="px-2 py-1 rounded-full text-xs font-medium">
                                    {{ modalKeluhan.status }}
                                </span>
                            </span>
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            <span class="text-gray-500">Waktu</span>
                            <span class="col-span-2">{{ formatDate(modalKeluhan.created_at) }}</span>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <button @click="updateStatus(modalKeluhan.id, 'proses')"
                            v-if="modalKeluhan.status === 'open'"
                            class="flex-1 bg-yellow-500 text-white py-2 rounded hover:bg-yellow-600 text-sm">
                            Tandai Diproses
                        </button>
                        <button @click="updateStatus(modalKeluhan.id, 'selesai')"
                            v-if="modalKeluhan.status !== 'selesai'"
                            class="flex-1 bg-green-600 text-white py-2 rounded hover:bg-green-700 text-sm">
                            Tandai Selesai
                        </button>
                        <button @click="modalKeluhan = null"
                            class="flex-1 bg-gray-100 text-gray-700 py-2 rounded hover:bg-gray-200 text-sm">
                            Tutup
                        </button>
                    </div>
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
import axios from 'axios'

const page = usePage()
const role = computed(() => page.props.auth.user.role)

const Layout = computed(() =>
    role.value === 'superadmin' ? SuperadminLayout : AdminLayout
)

const keluhan = ref([])
const filterStatus = ref('semua')
const modalKeluhan = ref(null)

const keluhanFiltered = computed(() => {
    if (filterStatus.value === 'semua') return keluhan.value
    return keluhan.value.filter(k => k.status === filterStatus.value)
})

const badgeStatus = (status) => ({
    'bg-yellow-100 text-yellow-800': status === 'open',
    'bg-blue-100 text-blue-800': status === 'proses',
    'bg-green-100 text-green-800': status === 'selesai',
})

const formatDate = (dateStr) => {
    if (!dateStr) return '-'
    return new Date(dateStr).toLocaleDateString('id-ID', {
        day: 'numeric', month: 'short', year: 'numeric',
        hour: '2-digit', minute: '2-digit',
    })
}

const bukaModal = (k) => {
    modalKeluhan.value = k
}

const updateStatus = async (id, status) => {
    const prefix = role.value === 'superadmin' ? '/superadmin' : '/admin'
    await axios.put(`${prefix}/keluhan/${id}/status`, { status })
    const index = keluhan.value.findIndex(k => k.id === id)
    if (index !== -1) keluhan.value[index].status = status
    modalKeluhan.value = null
}

onMounted(async () => {
    const prefix = role.value === 'superadmin' ? '/superadmin' : '/admin'
    const res = await axios.get(`${prefix}/keluhan`)
    keluhan.value = res.data
})
</script>
