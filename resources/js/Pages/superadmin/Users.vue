<template>
    <SuperadminLayout>
        <div class="p-6 space-y-6">

            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Manajemen User</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Kelola seluruh akun admin dan pengguna.</p>
                </div>
                <div class="flex gap-2 shrink-0">
                    <Link href="/superadmin/users/tambah-user"
                        class="inline-flex items-center gap-1.5 text-sm font-semibold text-white px-4 py-2 rounded-xl transition-all shadow-sm"
                        style="background:#1e4db7;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                        </svg>
                        Tambah User
                    </Link>
                    <Link href="/superadmin/users/tambah-admin"
                        class="inline-flex items-center gap-1.5 text-sm font-semibold text-white px-4 py-2 rounded-xl transition-all shadow-sm bg-emerald-600 hover:bg-emerald-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                        </svg>
                        Tambah Admin
                    </Link>
                </div>
            </div>

            <!-- Filter Daerah -->
            <div class="flex gap-2 flex-wrap">
                <button v-for="d in ['semua', ...daerahs]" :key="d"
                    @click="filterDaerah = d"
                    :class="filterDaerah === d
                        ? 'text-white shadow-sm'
                        : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-700 hover:border-[#1e4db7] hover:text-[#1e4db7]'"
                    :style="filterDaerah === d ? 'background:#1e4db7;' : ''"
                    class="px-3.5 py-1.5 rounded-xl text-sm font-medium transition-all duration-150 capitalize">
                    {{ d }}
                </button>
            </div>

            <!-- Tabel -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm ljn-table">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-700/50 text-left">
                                <th class="px-5 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama</th>
                                <th class="px-5 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                                <th class="px-5 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Role</th>
                                <th class="px-5 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Daerah</th>
                                <th class="px-5 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-5 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            <tr v-for="u in filteredUsers" :key="u.id" class="transition-colors duration-100">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0"
                                            :style="u.role === 'admin' ? 'background:#059669' : 'background:#1e4db7'">
                                            {{ u.name?.charAt(0).toUpperCase() }}
                                        </div>
                                        <span class="font-medium text-gray-700 dark:text-gray-300">{{ u.name }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400">{{ u.email }}</td>
                                <td class="px-5 py-3.5">
                                    <span :class="u.role === 'admin'
                                        ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400'
                                        : 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'"
                                        class="px-2.5 py-1 rounded-full text-xs font-medium">
                                        {{ u.role }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-gray-600 dark:text-gray-400">{{ u.daerah ?? '—' }}</td>
                                <td class="px-5 py-3.5">
                                    <span :class="u.is_active
                                        ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400'
                                        : 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400'"
                                        class="px-2.5 py-1 rounded-full text-xs font-medium">
                                        {{ u.is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-1.5">
                                        <button @click="bukaModalEdit(u)"
                                            class="text-xs px-3 py-1.5 rounded-lg bg-amber-100 text-amber-700 hover:bg-amber-200 dark:bg-amber-900/20 dark:text-amber-400 font-medium transition-colors">
                                            Edit
                                        </button>
                                        <button v-if="u.is_active" @click="nonaktifkan(u.id)"
                                            class="text-xs px-3 py-1.5 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 dark:bg-red-900/20 dark:text-red-400 font-medium transition-colors">
                                            Nonaktifkan
                                        </button>
                                        <button v-else @click="aktifkan(u.id)"
                                            class="text-xs px-3 py-1.5 rounded-lg bg-green-100 text-green-700 hover:bg-green-200 dark:bg-green-900/20 dark:text-green-400 font-medium transition-colors">
                                            Aktifkan
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="filteredUsers.length === 0">
                                <td colspan="6" class="px-5 py-10 text-center text-gray-400">
                                    <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197"/>
                                    </svg>
                                    Tidak ada user
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modal Edit -->
            <Transition
                enter-active-class="transition ease-out duration-150"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition ease-in duration-100"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0">
                <div v-if="modal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-sm">
                        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                            <h2 class="font-bold text-gray-800 dark:text-gray-100">{{ modalTitle }}</h2>
                            <button @click="modal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                        <div class="px-6 py-5 space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Nama</label>
                                <input v-model="form.name" type="text"
                                    class="w-full border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-xl px-4 py-2.5 text-sm text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-[#1e4db7] focus:border-transparent transition"/>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Email</label>
                                <input v-model="form.email" type="email"
                                    class="w-full border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-xl px-4 py-2.5 text-sm text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-[#1e4db7] focus:border-transparent transition"/>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Daerah</label>
                                <input v-model="form.daerah" type="text"
                                    class="w-full border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-xl px-4 py-2.5 text-sm text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-[#1e4db7] focus:border-transparent transition"/>
                            </div>
                            <p v-if="modalMode === 'tambah'" class="text-xs text-gray-400">Password default: demo1234</p>
                        </div>
                        <div class="px-6 pb-5 flex gap-3">
                            <button @click="modal = false"
                                class="flex-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 py-2.5 rounded-xl text-sm font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                Batal
                            </button>
                            <button @click="simpan" :disabled="loading"
                                class="flex-1 text-white py-2.5 rounded-xl text-sm font-semibold transition-colors disabled:opacity-50"
                                style="background:#1e4db7;">
                                {{ loading ? 'Menyimpan...' : 'Simpan' }}
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>
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
