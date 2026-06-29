<template>
    <component :is="Layout">
        <div class="p-6 max-w-3xl mx-auto">
            <h1 class="text-2xl font-bold mb-2">Syarat & Ketentuan</h1>
            <p class="text-gray-500 text-sm mb-6">Terakhir diperbarui: 1 Januari 2025</p>

            <div class="bg-white rounded-lg shadow p-6 space-y-6 text-sm text-gray-600 leading-relaxed">

                <div v-for="pasal in pasals" :key="pasal.id">
                    <h2 class="font-bold text-gray-800 text-base mb-2">{{ pasal.judul }}</h2>
                    <ul class="space-y-2">
                        <li v-for="(poin, i) in pasal.poin" :key="i" class="flex gap-2">
                            <span class="text-blue-600 shrink-0">{{ i + 1 }}.</span>
                            <span>{{ poin }}</span>
                        </li>
                    </ul>
                </div>

                <div class="border-t pt-4 text-xs text-gray-400">
                    Dengan menggunakan layanan PT. Lintas Jaringan Nusantara, Anda dianggap telah membaca, 
                    memahami, dan menyetujui seluruh syarat dan ketentuan yang berlaku.
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

const pasals = [
    {
        id: 1,
        judul: 'Pasal 1 — Definisi',
        poin: [
            'Layanan adalah akses internet and layanan IT yang disediakan oleh PT. Lintas Jaringan Nusantara (LJN).',
            'Pelanggan adalah pihak yang telah mendaftar dan menggunakan layanan LJN.',
            'Sistem Billing adalah platform digital yang digunakan untuk mengelola tagihan dan pembayaran layanan.',
        ],
    },
    {
        id: 2,
        judul: 'Pasal 2 — Penggunaan Layanan',
        poin: [
            'Pelanggan wajib menggunakan layanan sesuai dengan ketentuan yang berlaku dan tidak melanggar hukum.',
            'Pelanggan dilarang menggunakan layanan untuk aktivitas yang merugikan pihak lain.',
            'LJN berhak menangguhkan layanan jika ditemukan pelanggaran terhadap ketentuan ini.',
        ],
    },
    {
        id: 3,
        judul: 'Pasal 3 — Pembayaran',
        poin: [
            'Tagihan dikeluarkan setiap bulan sesuai paket yang dipilih pelanggan.',
            'Pembayaran wajib dilakukan sebelum tanggal jatuh tempo yang tertera pada invoice.',
            'Keterlambatan pembayaran dapat mengakibatkan penangguhan layanan.',
            'Seluruh harga yang tertera sudah termasuk PPN 11%.',
        ],
    },
    {
        id: 4,
        judul: 'Pasal 4 — Kualitas Layanan',
        poin: [
            'LJN berkomitmen memberikan layanan dengan SLA 99.9% uptime.',
            'Gangguan layanan yang disebabkan oleh force majeure tidak termasuk dalam perhitungan SLA.',
            'Pelanggan dapat melaporkan gangguan layanan melalui Pusat Bantuan yang tersedia di sistem.',
        ],
    },
    {
        id: 5,
        judul: 'Pasal 5 — Privasi Data',
        poin: [
            'LJN berkomitmen menjaga kerahasiaan data pribadi pelanggan.',
            'Data pelanggan tidak akan disebarkan kepada pihak ketiga tanpa persetujuan pelanggan.',
            'Pelanggan bertanggung jawab menjaga kerahasiaan akun dan kata sandi mereka.',
        ],
    },
    {
        id: 6,
        judul: 'Pasal 6 — Penghentian Layanan',
        poin: [
            'Pelanggan dapat mengajukan penghentian layanan dengan pemberitahuan minimal 30 hari sebelumnya.',
            'LJN berhak menghentikan layanan jika pelanggan melanggar syarat dan ketentuan yang berlaku.',
            'Tagihan yang sudah dibayar tidak dapat dikembalikan kecuali terdapat kesalahan dari pihak LJN.',
        ],
    },
]
</script>
