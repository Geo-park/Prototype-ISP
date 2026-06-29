<template>
    <div class="h-14 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between px-6 sticky top-0 z-40">
        <!-- Judul halaman -->
        <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-200">
            {{ title }}
        </h2>

        <div class="flex items-center gap-3">
            <!-- Toggle dark mode -->
            <button @click="toggle"
                class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400 transition-colors">
                <span v-if="isDark">☀️</span>
                <span v-else>🌙</span>
            </button>

            <!-- Avatar + Popup -->
            <div class="relative">
                <button @click="showPopup = !showPopup"
                    class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white text-sm font-bold hover:bg-blue-700 transition-colors">
                    {{ inisial }}
                </button>

                <!-- Popup -->
                <div v-if="showPopup"
                    class="absolute right-0 top-10 w-64 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50">
                    <!-- Info user -->
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold">
                                {{ inisial }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">{{ user.name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ user.email }}</p>
                                <span class="text-xs bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 px-2 py-0.5 rounded-full">
                                    {{ user.role }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Menu -->
                    <div class="p-2">
                        <Link :href="route('profil')"
                            @click="showPopup = false"
                            class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                            👤 Profil Saya
                        </Link>
                        <Link :href="route('pusat-bantuan')"
                            @click="showPopup = false"
                            v-if="user.role === 'user'"
                            class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                            🆘 Pusat Bantuan
                        </Link>
                        <Link :href="route('syarat-ketentuan')"
                            @click="showPopup = false"
                            class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                            📋 Syarat & Ketentuan
                        </Link>
                        <Link :href="route('tentang')"
                            @click="showPopup = false"
                            class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                            🏢 Tentang PT
                        </Link>
                        <div class="border-t border-gray-200 dark:border-gray-700 mt-1 pt-1">
                            <Link :href="route('logout')" method="post" as="button"
                                @click="showPopup = false"
                                class="flex items-center gap-2 px-3 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900 rounded-lg w-full text-left">
                                🚪 Logout
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Overlay tutup popup -->
                <div v-if="showPopup" @click="showPopup = false"
                    class="fixed inset-0 z-40">
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { useDarkMode } from '@/composables/useDarkMode'

const { isDark, toggle } = useDarkMode()
const page = usePage()
const user = computed(() => page.props.auth.user)
const showPopup = ref(false)

const inisial = computed(() => user.value?.name?.charAt(0).toUpperCase() ?? '?')

defineProps({
    title: {
        type: String,
        default: '',
    },
})
</script>
