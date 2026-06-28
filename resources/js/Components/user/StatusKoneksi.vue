<template>
    <div class="bg-white rounded-lg shadow p-6 text-center">
        <p class="text-gray-500 text-sm mb-3">Status Koneksi</p>

        <div v-if="loading" class="animate-pulse">
            <div class="h-10 bg-gray-200 rounded-full w-32 mx-auto mb-3"></div>
            <div class="h-4 bg-gray-200 rounded w-40 mx-auto mb-2"></div>
            <div class="h-4 bg-gray-200 rounded w-36 mx-auto"></div>
        </div>

        <template v-else>
            <span class="inline-block px-6 py-2 rounded-full text-lg font-bold"
                :class="profil.status_koneksi === 'aktif'
                    ? 'bg-green-100 text-green-700'
                    : 'bg-red-100 text-red-700'">
                {{ profil.status_koneksi === 'aktif' ? '🟢 AKTIF' : '🔴 NONAKTIF' }}
            </span>

            <div class="mt-3 space-y-1">
                <p class="text-sm text-gray-600">
                    <span class="font-medium">{{ profil.paket ?? 'Tidak ada paket' }}</span>
                    <span v-if="profil.bandwidth"> — {{ profil.bandwidth }}</span>
                </p>
                <p class="text-sm text-gray-500">
                    Jatuh Tempo: <span class="font-medium">{{ formatDate(profil.tgl_jatuh_tempo) }}</span>
                </p>
                <p class="text-xs text-gray-400">
                    Aktif sejak {{ formatDate(profil.tgl_aktivasi) }}
                </p>
            </div>
        </template>
    </div>
</template>

<script setup>
defineProps({
    profil: { type: Object, default: () => ({}) },
    loading: { type: Boolean, default: false },
})

const formatDate = (dateStr) => {
    if (!dateStr) return '-'
    return new Date(dateStr).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    })
}
</script>
