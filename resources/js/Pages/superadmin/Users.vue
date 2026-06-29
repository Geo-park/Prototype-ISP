<template>
    <SuperadminLayout>
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Manajemen User</h1>
                <div class="flex gap-2">
                    <Link href="/superadmin/users/tambah-user"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
                        + Tambah User
                    </Link>
                    <Link href="/superadmin/users/tambah-admin"
                        class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700">
                        + Tambah Admin
                    </Link>
                </div>
            </div>

            <!-- Filter Daerah -->
            <div class="mb-4 flex gap-2 flex-wrap">
                <button v-for="d in ['semua', ...daerahs]" :key="d"
                    @click="filterDaerah = d"
                    :class="filterDaerah === d ? 'bg-blue-600 text-white' : 'bg-white text-gray-600'"
                    class="px-3 py-1 rounded-lg text-sm border">
                    {{ d }}
                </button>
            </div>

            <!-- Tabel User -->
            <div class="bg-white rounded-lg shadow">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left border-b bg-gray-50">
                            <th class="p-4">Nama</th>
                            <th class="p-4">Email</th>
                            <th class="p-4">Role</th>
                            <th class="p-4">Daerah</th>
                            <th class="p-4">Status</th>
                            <th class="p-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="u in filteredUsers" :key="u.id" class="border-b hover:bg-gray-50">
                            <td class="p-4">{{ u.name }}</td>
                            <td class="p-4">{{ u.email }}</td>
                            <td class="p-4">
                                <span :class="u.role === 'admin' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'"
                                    class="px-2 py-1 rounded-full text-xs">
                                    {{ u.role }}
                                </span>
                            </td>
                            <td class="p-4">{{ u.daerah ?? '-' }}</td>
                            <td class="p-4">
                                <span :class="u.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                    class="px-2 py-1 rounded-full text-xs">
                                    {{ u.is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="p-4 space-x-2">
                                <button @click="bukaModalEdit(u)"
                                    class="text-xs bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600">
                                    Edit
                                </button>
                                <button v-if="u.is_active" @click="nonaktifkan(u.id)"
                                    class="text-xs bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">
                                    Nonaktifkan
                                </button>
                                <button v-else @click="aktifkan(u.id)"
                                    class="text-xs bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600">
                                    Aktifkan
                                </button>
                            </td>
                        </tr>
                        <tr v-if="filteredUsers.length === 0">
                            <td colspan="6" class="p-4 text-center text-gray-400">Tidak ada user</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Modal -->
            <div v-if="modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-lg p-6 w-96">
                    <div class="flex justify-between items-start mb-4">
                        <h2 class="font-bold text-lg">{{ modalTitle }}</h2>
                        <button @click="modal = false" class="text-gray-400 hover:text-gray-600">✕</button>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                            <input v-model="form.name" type="text"
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input v-model="form.email" type="email"
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Daerah</label>
                            <input v-model="form.daerah" type="text"
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <p class="text-xs text-gray-400" v-if="modalMode === 'tambah'">
                            Password default: demo1234
                        </p>
                    </div>

                    <div class="mt-4 flex gap-2">
                        <button @click="modal = false"
                            class="flex-1 bg-gray-100 text-gray-700 py-2 rounded hover:bg-gray-200 text-sm">
                            Batal
                        </button>
                        <button @click="simpan" :disabled="loading"
                            class="flex-1 bg-blue-600 text-white py-2 rounded hover:bg-blue-700 text-sm disabled:opacity-50">
                            {{ loading ? 'Menyimpan...' : 'Simpan' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </SuperadminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import SuperadminLayout from '@/Layouts/SuperadminLayout.vue'
import axios from 'axios'

const users = ref([])
const modal = ref(false)
const modalMode = ref('tambah')
const modalRole = ref('user')
const loading = ref(false)
const editId = ref(null)
const filterDaerah = ref('semua')

const form = ref({
    name: '',
    email: '',
    daerah: '',
    role: 'user',
})

const daerahs = computed(() => [...new Set(users.value.map(u => u.daerah).filter(Boolean))])

const filteredUsers = computed(() => {
    if (filterDaerah.value === 'semua') return users.value
    return users.value.filter(u => u.daerah === filterDaerah.value)
})

const modalTitle = computed(() => {
    if (modalMode.value === 'edit') return 'Edit User'
    return modalRole.value === 'admin' ? 'Tambah Admin' : 'Tambah User'
})

onMounted(async () => {
    const res = await axios.get('/superadmin/users')
    users.value = res.data
})

const bukaModalTambah = (role) => {
    modalMode.value = 'tambah'
    modalRole.value = role
    form.value = { name: '', email: '', daerah: '', role }
    modal.value = true
}

const bukaModalEdit = (u) => {
    modalMode.value = 'edit'
    editId.value = u.id
    form.value = { name: u.name, email: u.email, daerah: u.daerah, role: u.role }
    modal.value = true
}

const simpan = async () => {
    loading.value = true
    try {
        if (modalMode.value === 'tambah') {
            const endpoint = form.value.role === 'admin'
                ? '/superadmin/users/tambah-admin'
                : '/admin/users/tambah'
            const res = await axios.post(endpoint, form.value)
            users.value.push(res.data)
        } else {
            const res = await axios.put(`/admin/users/${editId.value}`, form.value)
            const index = users.value.findIndex(u => u.id === editId.value)
            if (index !== -1) users.value[index] = res.data
        }
        modal.value = false
    } catch (e) {
        alert('Gagal menyimpan data')
    } finally {
        loading.value = false
    }
}

const nonaktifkan = async (id) => {
    await axios.post(`/admin/users/${id}/nonaktifkan`)
    const index = users.value.findIndex(u => u.id === id)
    if (index !== -1) users.value[index].is_active = false
}

const aktifkan = async (id) => {
    await axios.post(`/admin/users/${id}/aktifkan`)
    const index = users.value.findIndex(u => u.id === id)
    if (index !== -1) users.value[index].is_active = true
}
</script>
