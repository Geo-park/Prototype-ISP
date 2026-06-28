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

// Color config
const colors = {
    pop: '#2563eb',       // blue-600
    odcL1: '#16a34a',     // green-600
    odcL2: '#eab308',     // yellow-500
    odp: '#f97316',       // orange-500
    pelangganAktifPaid: '#10b981',    // emerald-500
    pelangganAktifPending: '#fbbf24', // amber-400
    pelangganNonaktif: '#ef4444',     // red-500
    linePop: '#93c5fd',   // blue-300
    lineOdc: '#86efac',   // green-300
    lineOdp: '#fdba74',   // orange-300
}

function createCircleMarker(lat, lng, color, radius, popupContent) {
    return L.circleMarker([lat, lng], {
        radius,
        fillColor: color,
        color: '#fff',
        weight: 2,
        opacity: 1,
        fillOpacity: 0.9,
    }).bindPopup(popupContent)
}

function getPelangganColor(p) {
    if (p.status_koneksi === 'nonaktif') return colors.pelangganNonaktif
    if (p.status_pembayaran === 'paid') return colors.pelangganAktifPaid
    return colors.pelangganAktifPending
}

function popupHtml(type, item) {
    if (type === 'pop') {
        return `<div class="text-sm"><strong class="text-blue-700">🏗️ ${item.nama}</strong><br>Kode: ${item.kode}<br>Status: <span class="font-semibold">${item.status}</span><br>Kapasitas: ${item.kapasitas} port</div>`
    }
    if (type === 'odc') {
        return `<div class="text-sm"><strong class="text-green-700">📦 ${item.nama}</strong><br>Kode: ${item.kode}<br>Level: ${item.level}<br>Status: <span class="font-semibold">${item.status}</span><br>Kapasitas: ${item.kapasitas} port</div>`
    }
    if (type === 'odp') {
        return `<div class="text-sm"><strong class="text-orange-700">📍 ${item.nama}</strong><br>Kode: ${item.kode}<br>Status: <span class="font-semibold">${item.status}</span><br>Kapasitas: ${item.kapasitas} port</div>`
    }
    if (type === 'pelanggan') {
        const statusColor = item.status_koneksi === 'aktif' ? 'text-green-600' : 'text-red-600'
        const bayarBadge = item.status_pembayaran === 'paid'
            ? '<span class="bg-green-100 text-green-800 px-1.5 py-0.5 rounded text-xs">Lunas</span>'
            : '<span class="bg-yellow-100 text-yellow-800 px-1.5 py-0.5 rounded text-xs">Belum</span>'
        return `<div class="text-sm"><strong>👤 ${item.nama}</strong><br>${item.no_pelanggan}<br>Paket: ${item.paket}<br>Bandwidth: ${item.bandwidth}<br>Koneksi: <span class="font-semibold ${statusColor}">${item.status_koneksi}</span><br>Bayar: ${bayarBadge}</div>`
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

    // POP/OLT markers
    if (props.filters.showPopOlt) {
        const markers = props.popOlt.map(p =>
            createCircleMarker(p.lat, p.lng, colors.pop, 12, popupHtml('pop', p))
        )
        layers.popOlt = L.layerGroup(markers).addTo(map)
    }

    // ODC markers + lines to POP
    if (props.filters.showOdc) {
        const markers = props.odc.map(o => {
            const color = o.level === 'L1' ? colors.odcL1 : colors.odcL2
            return createCircleMarker(o.lat, o.lng, color, 9, popupHtml('odc', o))
        })
        layers.odc = L.layerGroup(markers).addTo(map)

        // Lines: POP → ODC
        props.odc.forEach(o => {
            const pop = props.popOlt.find(p => p.id === o.pop_olt_id)
            if (pop && props.filters.showPopOlt) {
                lineGroup.push(
                    L.polyline([[pop.lat, pop.lng], [o.lat, o.lng]], {
                        color: colors.linePop,
                        weight: 2,
                        opacity: 0.6,
                        dashArray: '6 4',
                    })
                )
            }
        })
    }

    // ODP markers + lines to ODC
    if (props.filters.showOdp) {
        const markers = props.odp.map(o =>
            createCircleMarker(o.lat, o.lng, colors.odp, 7, popupHtml('odp', o))
        )
        layers.odp = L.layerGroup(markers).addTo(map)

        // Lines: ODC → ODP
        props.odp.forEach(o => {
            const odc = props.odc.find(c => c.id === o.odc_id)
            if (odc && props.filters.showOdc) {
                lineGroup.push(
                    L.polyline([[odc.lat, odc.lng], [o.lat, o.lng]], {
                        color: colors.lineOdc,
                        weight: 1.5,
                        opacity: 0.5,
                        dashArray: '4 3',
                    })
                )
            }
        })
    }

    // Pelanggan markers + lines to ODP
    if (props.filters.showPelanggan) {
        const markers = props.pelanggan.map(p =>
            createCircleMarker(p.lat, p.lng, getPelangganColor(p), 5, popupHtml('pelanggan', p))
        )
        layers.pelanggan = L.layerGroup(markers).addTo(map)

        // Lines: ODP → Pelanggan
        props.pelanggan.forEach(p => {
            if (p.odp_id) {
                const odp = props.odp.find(o => o.id === p.odp_id)
                if (odp && props.filters.showOdp) {
                    lineGroup.push(
                        L.polyline([[odp.lat, odp.lng], [p.lat, p.lng]], {
                            color: colors.lineOdp,
                            weight: 1,
                            opacity: 0.4,
                        })
                    )
                }
            }
        })
    }

    layers.lines = L.layerGroup(lineGroup).addTo(map)
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
