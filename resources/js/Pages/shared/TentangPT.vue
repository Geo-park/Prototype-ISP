<template>
    <component :is="Layout">
        <div class="p-6 max-w-4xl mx-auto">
            <h1 class="text-2xl font-bold mb-6">Tentang PT. Lintas Jaringan Nusantara</h1>

            <!-- Header -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-16 h-16 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-xl">
                        LJN
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">PT. Lintas Jaringan Nusantara</h2>
                        <p class="text-gray-500 text-sm">Internet Service Provider sejak 2004</p>
                    </div>
                </div>
                <p class="text-gray-600 text-sm leading-relaxed">
                    PT. Lintas Jaringan Nusantara (LJN) adalah perusahaan Internet Service Provider (ISP) yang berbasis di Indonesia. 
                    Berdiri sejak 2004, LJN berkomitmen menyediakan layanan terbaik di bidang teknologi informasi dan digital 
                    di seluruh Indonesia.
                </p>
            </div>

            <!-- Visi Misi -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="font-bold text-lg mb-3 text-blue-600">🎯 Visi</h2>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        "Uniting the Nation, Connecting the World"
                    </p>
                    <p class="text-gray-600 text-sm leading-relaxed mt-2">
                        Menjadi perusahaan ISP terdepan yang menghubungkan seluruh lapisan masyarakat Indonesia 
                        dengan jaringan internet berkualitas tinggi.
                    </p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="font-bold text-lg mb-3 text-blue-600">🚀 Misi</h2>
                    <ul class="text-gray-600 text-sm space-y-2">
                        <li>✓ Menyediakan akses internet berkecepatan tinggi dengan harga terjangkau</li>
                        <li>✓ Memastikan ketersediaan layanan hingga 99.9% SLA</li>
                        <li>✓ Memberikan solusi IT menyeluruh untuk berbagai kebutuhan bisnis</li>
                        <li>✓ Terus berkembang ke 100+ kota di seluruh Indonesia</li>
                    </ul>
                </div>
            </div>

            <!-- Keunggulan -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="font-bold text-lg mb-4">Keunggulan Kami</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div v-for="item in keunggulan" :key="item.judul" class="text-center p-4 bg-gray-50 rounded-lg">
                        <div class="text-3xl mb-2">{{ item.icon }}</div>
                        <h3 class="font-semibold text-sm mb-1">{{ item.judul }}</h3>
                        <p class="text-gray-500 text-xs">{{ item.deskripsi }}</p>
                    </div>
                </div>
            </div>

            <!-- Kantor -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="font-bold text-lg mb-4">Kantor Kami</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div v-for="kantor in kantors" :key="kantor.nama" class="border rounded-lg p-4">
                        <h3 class="font-semibold mb-1">{{ kantor.nama }}</h3>
                        <p class="text-gray-500">{{ kantor.alamat }}</p>
                        <p class="text-blue-600 mt-1">{{ kantor.email }}</p>
                    </div>
                </div>
            </div>

            <!-- Sertifikasi -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="font-bold text-lg mb-4">Keanggotaan & Sertifikasi</h2>
                <div class="flex gap-4 flex-wrap">
                    <div class="bg-gray-50 px-4 py-2 rounded-lg text-sm font-medium">🏅 APJII</div>
                    <div class="bg-gray-50 px-4 py-2 rounded-lg text-sm font-medium">🏅 Apjatel</div>
                    <div class="bg-gray-50 px-4 py-2 rounded-lg text-sm font-medium">🏅 ISO Certified</div>
                    <div class="bg-gray-50 px-4 py-2 rounded-lg text-sm font-medium">📡 SLA 99.9%</div>
                    <div class="bg-gray-50 px-4 py-2 rounded-lg text-sm font-medium">🌐 100+ Kota</div>
                </div>
            </div>
        </div>
    </component>
</template>

<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import SuperadminLayout from '@/Layouts/SuperadminLayout.vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import UserLayout from '@/Layouts/UserLayout.vue'

const page = usePage()
const role = computed(() => page.props.auth.user.role)

const Layout = computed(() => {
    if (role.value === 'superadmin') return SuperadminLayout
    if (role.value === 'admin') return AdminLayout
    return UserLayout
})

const keunggulan = [
    { icon: '⚡', judul: 'Dedicated Bandwidth', deskripsi: 'Bandwidth 1:1 tanpa berbagi dengan pengguna lain' },
    { icon: '🔒', judul: 'SLA 99.9%', deskripsi: 'Jaminan uptime tertinggi dengan link redundan' },
    { icon: '🛠️', judul: 'Support 24/7', deskripsi: 'Tim NOC dan Field Operations siap 24 jam' },
    { icon: '🚫', judul: 'Tanpa FUP', deskripsi: 'Akses internet tanpa batas penggunaan' },
    { icon: '📊', judul: 'Self Monitoring', deskripsi: 'Monitor penggunaan jaringan secara mandiri' },
    { icon: '📍', judul: '100+ Kota', deskripsi: 'Cabang di lebih dari 100 kota Indonesia' },
]

const kantors = [
    {
        nama: 'Head Office — Bekasi',
        alamat: 'Jl. KH. Agus Salim No.25, Bekasi Jaya, Bekasi Timur, Kota Bekasi, Jawa Barat 17112',
        email: 'marketing@lintas.net.id',
    },
    {
        nama: 'Marketing Gallery — Jakarta',
        alamat: 'Wisma 46, Lt 48 Suite 20, Jl Jenderal Sudirman Kavling 1, Jakarta 10220',
        email: 'marketing@lintas.net.id',
    },
    {
        nama: 'Karawang Head Office',
        alamat: 'Jl. Jenderal Ahmad Yani No.25 B, Tanjungpura, Karawang Barat, Karawang, Jawa Barat 41315',
        email: '',
    },
    {
        nama: 'Pinrang Head Office',
        alamat: 'Jl. Poros Pinrang – Parepare No.30, Jaya, Watang Sawitto, Kabupaten Pinrang, Sulawesi Selatan 91213',
        email: '',
    },
]
</script>
