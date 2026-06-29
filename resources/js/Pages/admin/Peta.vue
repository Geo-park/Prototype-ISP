<template>
    <AdminLayout>
        <div class="p-6 h-[calc(100vh-0px)] flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-2xl font-bold">Peta Jaringan</h1>
                    <p class="text-sm text-gray-500">Daerah: {{ daerah }}</p>
                </div>
                <button @click="showFilter = !showFilter"
                    class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors"
                >
                    {{ showFilter ? 'Tutup Filter' : 'Filter' }}
                </button>
            </div>

            <div class="flex-1 relative rounded-lg overflow-hidden shadow-lg">
                <FilterPanel
                    v-if="showFilter"
                    :filters="filters"
                    @update="updateFilter"
                    class="absolute top-3 right-3 z-[1000]"
                />
                <PetaJaringan
                    v-if="!loading"
                    :pop-olt="data.pop_olt"
                    :odc="data.odc"
                    :odp="data.odp"
                    :pelanggan="filteredPelanggan"
                    :filters="filters"
                />
                <div v-else class="flex items-center justify-center h-full bg-gray-100">
                    <div class="text-center">
                        <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-600 mx-auto mb-3"></div>
                        <p class="text-gray-500 text-sm">Memuat data peta...</p>
                    </div>
                </div>
            </div>

            <!-- Legend -->
            <div class="mt-3 bg-white rounded-lg shadow p-3 flex flex-wrap gap-4 text-xs">
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-blue-600 inline-block"></span> POP/OLT
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-green-600 inline-block"></span> ODC L1
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-yellow-500 inline-block"></span> ODC L2
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-orange-500 inline-block"></span> ODP
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span> Pelanggan Aktif (Lunas)
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-amber-400 inline-block"></span> Pelanggan Aktif (Belum Bayar)
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-red-500 inline-block"></span> Pelanggan Nonaktif
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import PetaJaringan from '@/Components/peta/PetaJaringan.vue'
import FilterPanel from '@/Components/peta/FilterPanel.vue'

// Loading state
const loading = ref(true)
const showFilter = ref(false)
const data = ref({ pop_olt: [], odc: [], odp: [], pelanggan: [] })

// Filters – admin view automatically filters by the admin's assigned daerah.
const filters = ref({
    showPopOlt: true,
    showOdc: true,
    showOdp: true,
    showPelanggan: true,
    statusKoneksi: 'semua',
    statusBayar: 'semua',
    // The daerah filter is derived from the authenticated admin user.
    daerah: ''
})

// Fetch admin daerah from a lightweight endpoint (you may create this endpoint if not existing).
const fetchAdminDaerah = async () => {
    try {
        const res = await axios.get('/admin/daerah')
        filters.value.daerah = res.data.daerah || ''
    } catch (e) {
        console.error('Failed to fetch admin daerah', e)
    }
}

const filteredPelanggan = computed(() => {
    return data.value.pelanggan.filter(p => {
        if (filters.value.daerah && p.daerah !== filters.value.daerah) return false
        if (filters.value.statusKoneksi !== 'semua' && p.status_koneksi !== filters.value.statusKoneksi) return false
        if (filters.value.statusBayar !== 'semua') {
            if (filters.value.statusBayar === 'lunas' && p.status_pembayaran !== 'paid') return false
            if (filters.value.statusBayar === 'belum' && p.status_pembayaran === 'paid') return false
        }
        return true
    })
})

const updateFilter = (key, value) => {
    filters.value[key] = value
}

onMounted(async () => {
    await fetchAdminDaerah()
    try {
        const res = await axios.get('/peta/semua')
        data.value = res.data
    } catch (err) {
        console.error('Gagal memuat data peta:', err)
    } finally {
        loading.value = false
    }
})
</script>
