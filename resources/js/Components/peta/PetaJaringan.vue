<template>
    <div ref="mapContainer" class="w-full h-full min-h-[400px]"></div>
</template>

<script setup>
import { ref, onMounted, watch, onUnmounted } from 'vue'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'

const props = defineProps({
    popOlt: { type: Array, default: () => [] },
    odc: { type: Array, default: () => [] },
    odp: { type: Array, default: () => [] },
    pelanggan: { type: Array, default: () => [] },
    filters: { type: Object, default: () => ({}) },
})

const mapContainer = ref(null)
let map = null
const layers = {
    popOlt: null,
    odc: null,
    odp: null,
    pelanggan: null,
    lines: null,
}

// Custom DivIcons utilizing styled CSS and emojis
const popIcon = L.divIcon({
    html: `
        <div class="flex items-center justify-center w-9 h-9 rounded-xl bg-blue-600 border-2 border-white shadow-lg text-white font-bold transform -translate-y-1 scale-105 transition-transform duration-200 hover:scale-125">
            🗼
        </div>
    `,
    className: '',
    iconSize: [36, 36],
    iconAnchor: [18, 18],
    popupAnchor: [0, -18]
})

const odcL1Icon = L.divIcon({
    html: `
        <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-600 border-2 border-white shadow-md text-white font-bold transition-transform duration-200 hover:scale-120">
            📦
        </div>
    `,
    className: '',
    iconSize: [32, 32],
    iconAnchor: [16, 16],
    popupAnchor: [0, -16]
})

const odcL2Icon = L.divIcon({
    html: `
        <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-amber-500 border-2 border-white shadow-md text-white font-bold transition-transform duration-200 hover:scale-120">
            📦
        </div>
    `,
    className: '',
    iconSize: [32, 32],
    iconAnchor: [16, 16],
    popupAnchor: [0, -16]
})

const odpIcon = L.divIcon({
    html: `
        <div class="flex items-center justify-center w-7 h-7 rounded-lg bg-orange-500 border border-white shadow-sm text-white transition-transform duration-200 hover:scale-120">
            🎛️
        </div>
    `,
    className: '',
    iconSize: [28, 28],
    iconAnchor: [14, 14],
    popupAnchor: [0, -14]
})

function getPelangganIcon(p) {
    let colorClass = 'bg-red-500' // nonaktif
    if (p.status_koneksi === 'aktif') {
        colorClass = p.status_pembayaran === 'paid' ? 'bg-green-500' : 'bg-yellow-400'
    }
    return L.divIcon({
        html: `
            <div class="flex items-center justify-center w-7 h-7 rounded-full ${colorClass} border border-white shadow-sm text-white transition-transform duration-200 hover:scale-120">
                🏠
            </div>
        `,
        className: '',
        iconSize: [28, 28],
        iconAnchor: [14, 14],
        popupAnchor: [0, -14]
    })
}

function popupHtml(type, item) {
    if (type === 'pop') {
        return `<div class="text-sm p-1">
                    <strong class="text-blue-700 font-semibold block text-base border-b pb-1 mb-1 border-gray-200">🏗️ POP / OLT (Node Utama)</strong>
                    <table class="w-full text-xs space-y-1">
                        <tr><td class="text-gray-500 pr-2">Nama:</td><td class="font-medium">${item.nama}</td></tr>
                        <tr><td class="text-gray-500 pr-2">Kode:</td><td><code>${item.kode}</code></td></tr>
                        <tr><td class="text-gray-500 pr-2">Status:</td><td><span class="px-1.5 py-0.5 rounded text-[10px] bg-green-100 text-green-800 font-semibold uppercase">${item.status}</span></td></tr>
                        <tr><td class="text-gray-500 pr-2">Kapasitas:</td><td class="font-medium">${item.kapasitas} Port</td></tr>
                        <tr><td class="text-gray-500 pr-2">Wilayah:</td><td class="font-medium">${item.daerah || '-'}</td></tr>
                    </table>
                </div>`
    }
    if (type === 'odc') {
        const isL1 = item.level === 'L1'
        const badgeColor = isL1 ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800'
        return `<div class="text-sm p-1">
                    <strong class="text-green-700 font-semibold block text-base border-b pb-1 mb-1 border-gray-200">📦 ODC (Distribution Cabinet)</strong>
                    <table class="w-full text-xs space-y-1">
                        <tr><td class="text-gray-500 pr-2">Nama:</td><td class="font-medium">${item.nama}</td></tr>
                        <tr><td class="text-gray-500 pr-2">Kode:</td><td><code>${item.kode}</code></td></tr>
                        <tr><td class="text-gray-500 pr-2">Level:</td><td><span class="px-1.5 py-0.5 rounded text-[10px] ${badgeColor} font-semibold">${item.level}</span></td></tr>
                        <tr><td class="text-gray-500 pr-2">Status:</td><td><span class="px-1.5 py-0.5 rounded text-[10px] bg-green-100 text-green-800 font-semibold uppercase">${item.status}</span></td></tr>
                        <tr><td class="text-gray-500 pr-2">Kapasitas:</td><td class="font-medium">${item.kapasitas} Port</td></tr>
                    </table>
                </div>`
    }
    if (type === 'odp') {
        return `<div class="text-sm p-1">
                    <strong class="text-orange-700 font-semibold block text-base border-b pb-1 mb-1 border-gray-200">🎛️ ODP (Optical Splitter)</strong>
                    <table class="w-full text-xs space-y-1">
                        <tr><td class="text-gray-500 pr-2">Nama:</td><td class="font-medium">${item.nama}</td></tr>
                        <tr><td class="text-gray-500 pr-2">Kode:</td><td><code>${item.kode}</code></td></tr>
                        <tr><td class="text-gray-500 pr-2">Status:</td><td><span class="px-1.5 py-0.5 rounded text-[10px] ${item.status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'} font-semibold uppercase">${item.status}</span></td></tr>
                        <tr><td class="text-gray-500 pr-2">Kapasitas:</td><td class="font-medium">${item.kapasitas} Port</td></tr>
                    </table>
                </div>`
    }
    if (type === 'pelanggan') {
        const isAktif = item.status_koneksi === 'aktif'
        const statusColor = isAktif ? 'text-green-600' : 'text-red-600'
        const bayarBadge = item.status_pembayaran === 'paid'
            ? '<span class="bg-green-100 text-green-800 px-1.5 py-0.5 rounded text-[10px] font-semibold">LUNAS</span>'
            : '<span class="bg-yellow-100 text-yellow-800 px-1.5 py-0.5 rounded text-[10px] font-semibold">BELUM BAYAR</span>'
        return `<div class="text-sm p-1">
                    <strong class="text-gray-800 font-semibold block text-base border-b pb-1 mb-1 border-gray-200">🏠 Detail Pelanggan</strong>
                    <table class="w-full text-xs space-y-1">
                        <tr><td class="text-gray-500 pr-2">Nama:</td><td class="font-medium">${item.nama}</td></tr>
                        <tr><td class="text-gray-500 pr-2">ID:</td><td><code>${item.no_pelanggan}</code></td></tr>
                        <tr><td class="text-gray-500 pr-2">Paket:</td><td class="font-medium">${item.paket} (${item.bandwidth})</td></tr>
                        <tr><td class="text-gray-500 pr-2">Koneksi:</td><td><span class="font-bold ${statusColor} uppercase">${item.status_koneksi}</span></td></tr>
                        <tr><td class="text-gray-500 pr-2">Tagihan:</td><td>${bayarBadge}</td></tr>
                    </table>
                </div>`
    }
    return ''
}

function renderMap() {
    if (!map) return

    // Clear existing layers
    Object.values(layers).forEach(layer => {
        if (layer) map.removeLayer(layer)
    })

    const lineGroup = []
    const allCoords = []

    // POP/OLT markers
    if (props.filters.showPopOlt) {
        const markers = props.popOlt.map(p => {
            allCoords.push([p.lat, p.lng])
            return L.marker([p.lat, p.lng], { icon: popIcon }).bindPopup(popupHtml('pop', p))
        })
        layers.popOlt = L.layerGroup(markers).addTo(map)
    }

    // ODC markers + lines to POP
    if (props.filters.showOdc) {
        const markers = props.odc.map(o => {
            allCoords.push([o.lat, o.lng])
            const icon = o.level === 'L1' ? odcL1Icon : odcL2Icon
            return L.marker([o.lat, o.lng], { icon: icon }).bindPopup(popupHtml('odc', o))
        })
        layers.odc = L.layerGroup(markers).addTo(map)

        // Lines: POP → ODC
        props.odc.forEach(o => {
            const pop = props.popOlt.find(p => p.id === o.pop_olt_id)
            if (pop && props.filters.showPopOlt) {
                const isLineActive = o.status === 'aktif' && pop.status === 'aktif'
                lineGroup.push(
                    L.polyline([[pop.lat, pop.lng], [o.lat, o.lng]], {
                        className: isLineActive ? 'cable-active' : 'cable-inactive',
                        weight: 3,
                        opacity: 0.8,
                    })
                )
            }
        })
    }

    // ODP markers + lines to ODC
    if (props.filters.showOdp) {
        const markers = props.odp.map(o => {
            allCoords.push([o.lat, o.lng])
            return L.marker([o.lat, o.lng], { icon: odpIcon }).bindPopup(popupHtml('odp', o))
        })
        layers.odp = L.layerGroup(markers).addTo(map)

        // Lines: ODC → ODP
        props.odp.forEach(o => {
            const odc = props.odc.find(c => c.id === o.odc_id)
            if (odc && props.filters.showOdc) {
                const isLineActive = o.status === 'aktif' && odc.status === 'aktif'
                lineGroup.push(
                    L.polyline([[odc.lat, odc.lng], [o.lat, o.lng]], {
                        className: isLineActive ? 'cable-odc-active' : 'cable-inactive',
                        weight: 2.5,
                        opacity: 0.7,
                    })
                )
            }
        })
    }

    // Pelanggan markers + lines to ODP
    if (props.filters.showPelanggan) {
        const markers = props.pelanggan.map(p => {
            allCoords.push([p.lat, p.lng])
            return L.marker([p.lat, p.lng], { icon: getPelangganIcon(p) }).bindPopup(popupHtml('pelanggan', p))
        })
        layers.pelanggan = L.layerGroup(markers).addTo(map)

        // Lines: ODP → Pelanggan
        props.pelanggan.forEach(p => {
            if (p.odp_id) {
                const odp = props.odp.find(o => o.id === p.odp_id)
                if (odp && props.filters.showOdp) {
                    const isLineActive = p.status_koneksi === 'aktif' && odp.status === 'aktif'
                    lineGroup.push(
                        L.polyline([[odp.lat, odp.lng], [p.lat, p.lng]], {
                            className: isLineActive ? 'cable-odp-active' : 'cable-inactive',
                            weight: 2,
                            opacity: 0.6,
                        })
                    )
                }
            }
        })
    }

    layers.lines = L.layerGroup(lineGroup).addTo(map)

    // Dynamic viewport fit bounds (centers on Banten/Jakarta region dynamically depending on active points)
    if (allCoords.length > 0 && map) {
        const bounds = L.latLngBounds(allCoords)
        map.fitBounds(bounds, { padding: [40, 40], maxZoom: 15 })
    }
}

onMounted(() => {
    map = L.map(mapContainer.value, {
        center: [-6.1250, 106.1550],
        zoom: 14,
        zoomControl: true,
    })

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 19,
    }).addTo(map)

    renderMap()
})

watch(() => [props.popOlt, props.odc, props.odp, props.pelanggan, props.filters], () => {
    renderMap()
}, { deep: true })

onUnmounted(() => {
    if (map) {
        map.remove()
        map = null
    }
})
</script>

<style>
/* Signal Flow Dash Animations (Cisco cable simulation style) */
@keyframes flowDash {
  to {
    stroke-dashoffset: -40;
  }
}

/* Active Backbone lines */
.cable-active {
  stroke-dasharray: 8, 8;
  animation: flowDash 1.2s linear infinite;
  stroke: #2563eb !important; /* blue */
}

/* Active Distribution / Drop lines */
.cable-odc-active {
  stroke-dasharray: 6, 6;
  animation: flowDash 1.8s linear infinite;
  stroke: #10b981 !important; /* green */
}

.cable-odp-active {
  stroke-dasharray: 5, 5;
  animation: flowDash 2.4s linear infinite;
  stroke: #f97316 !important; /* orange */
}

/* Broken / Inactive / Overdue Link indicating error */
.cable-inactive {
  stroke-dasharray: none !important;
  animation: none !important;
  stroke: #ef4444 !important; /* solid red showing signal breakdown */
  stroke-width: 3px !important;
}
</style>
