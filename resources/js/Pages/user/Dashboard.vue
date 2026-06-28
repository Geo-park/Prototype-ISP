<template>
    <UserLayout>
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-6">Dashboard Pelanggan</h1>

            <!-- Status Koneksi -->
            <div class="bg-white rounded-lg shadow p-6 mb-4 text-center">
                <p class="text-gray-500 text-sm mb-2">Status Koneksi</p>
                <span class="px-6 py-2 rounded-full text-lg font-bold bg-green-100 text-green-700">
                    AKTIF
                </span>
                <p class="text-sm text-gray-500 mt-2">Paket Rumahan — 20 Mbps</p>
                <p class="text-sm text-gray-500">Jatuh Tempo: 1 Juli 2025</p>
            </div>

            <!-- Tagihan Aktif -->
            <div class="bg-white rounded-lg shadow p-4 mb-4">
                <h2 class="font-semibold mb-3">Tagihan Aktif</h2>
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600">INV-2025-002</p>
                        <p class="text-sm text-gray-600">Periode: 2025-06</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-blue-600">Rp 277.500</p>
                        <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">pending</span>
                    </div>
                </div>
            </div>

            <!-- Riwayat Pembayaran -->
            <div class="bg-white rounded-lg shadow p-4">
                <h2 class="font-semibold mb-3">Riwayat Pembayaran</h2>
                <p class="text-sm text-gray-400 text-center">Belum ada riwayat pembayaran</p>
            </div>
        </div>
    </UserLayout>
</template>

<script setup>
import UserLayout from '@/Layouts/UserLayout.vue'
</script>

