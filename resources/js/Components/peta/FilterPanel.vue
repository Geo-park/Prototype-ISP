<template>
    <div class="bg-white rounded-lg shadow-lg p-4 w-64 max-h-[80vh] overflow-y-auto">
        <h3 class="font-semibold text-sm mb-3 text-gray-700">Filter Peta</h3>

        <!-- Toggle Layers -->
        <div class="space-y-2 mb-4">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Layer</p>
            <label class="flex items-center gap-2 text-sm cursor-pointer">
                <input type="checkbox" :checked="filters.showPopOlt"
                    @change="$emit('update', 'showPopOlt', $event.target.checked)"
                    class="rounded text-blue-600 focus:ring-blue-500">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                POP/OLT
            </label>
            <label class="flex items-center gap-2 text-sm cursor-pointer">
                <input type="checkbox" :checked="filters.showOdc"
                    @change="$emit('update', 'showOdc', $event.target.checked)"
                    class="rounded text-green-600 focus:ring-green-500">
                <span class="w-2.5 h-2.5 rounded-full bg-green-600"></span>
                ODC
            </label>
            <label class="flex items-center gap-2 text-sm cursor-pointer">
                <input type="checkbox" :checked="filters.showOdp"
                    @change="$emit('update', 'showOdp', $event.target.checked)"
                    class="rounded text-orange-500 focus:ring-orange-500">
                <span class="w-2.5 h-2.5 rounded-full bg-orange-500"></span>
                ODP
            </label>
            <label class="flex items-center gap-2 text-sm cursor-pointer">
                <input type="checkbox" :checked="filters.showPelanggan"
                    @change="$emit('update', 'showPelanggan', $event.target.checked)"
                    class="rounded text-emerald-500 focus:ring-emerald-500">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                Pelanggan
            </label>
        </div>

        <hr class="my-3">

        <!-- Filter Status Koneksi -->
        <div class="mb-3">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1.5">Status Koneksi</p>
            <select :value="filters.statusKoneksi"
                @change="$emit('update', 'statusKoneksi', $event.target.value)"
                class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="semua">Semua</option>
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
            </select>
        </div>

        <!-- Filter Status Bayar -->
        <div>
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1.5">Status Bayar</p>
            <select :value="filters.statusBayar"
                @change="$emit('update', 'statusBayar', $event.target.value)"
                class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="semua">Semua</option>
                <option value="lunas">Lunas</option>
                <option value="belum">Belum Bayar</option>
            </select>
        </div>
    </div>
</template>

<script setup>
defineProps({
    filters: { type: Object, required: true },
})

defineEmits(['update'])
</script>
