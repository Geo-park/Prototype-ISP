<template>
    <div class="h-14 bg-white border-b border-gray-200 flex items-center justify-between px-5 sticky top-0 z-40 dark:bg-gray-900 dark:border-gray-800">

        <!-- Left: Brand + Nav tabs -->
        <div class="flex items-center gap-6">
            <span class="text-base font-bold text-[#1a3a8f] dark:text-blue-400 tracking-tight">
                LJN Management
            </span>
            <nav v-if="user?.role === 'superadmin' || user?.role === 'admin'" class="hidden sm:flex items-center gap-1">
                <button
                    v-for="tab in tabs" :key="tab"
                    @click="activeTab = tab"
                    class="px-3 py-1.5 text-sm rounded-lg transition-colors duration-150"
                    :class="activeTab === tab
                        ? 'text-[#1a3a8f] font-semibold border-b-2 border-[#1a3a8f] rounded-none pb-[14px]'
                        : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'">
                    {{ tab }}
                </button>
            </nav>
        </div>

        <!-- Right: Search (superadmin) + Dark mode + Bell + Avatar -->
        <div class="flex items-center gap-2">

            <!-- Search — hanya superadmin -->
            <div v-if="user?.role === 'superadmin'"
                class="hidden md:flex items-center gap-2 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-3 py-1.5 w-44">
                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 15.803 7.5 7.5 0 0016.803 15.803z" />
                </svg>
                <input type="text" placeholder="Cari..." class="bg-transparent text-xs text-gray-500 outline-none w-full dark:text-gray-300 placeholder-gray-400" />
            </div>

            <!-- Dark mode toggle -->
            <button @click="toggle"
                class="w-8 h-8 rounded-xl flex items-center justify-center text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                <svg v-if="isDark" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                </svg>
                <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                </svg>
            </button>

            <!-- Notification bell -->
            <button class="w-8 h-8 rounded-xl flex items-center justify-center text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors relative">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                </svg>
            </button>

            <!-- Avatar + Popup -->
            <div class="relative">
                <button @click="showPopup = !showPopup"
                    class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold hover:ring-2 hover:ring-[#3b82f6] hover:ring-offset-1 transition-all"
                    style="background:#1e4db7;">
                    {{ inisial }}
                </button>

                <div v-if="showPopup" @click="showPopup = false" class="fixed inset-0 z-40" />

                <Transition
                    enter-active-class="transition ease-out duration-100"
                    enter-from-class="opacity-0 scale-95"
                    enter-to-class="opacity-100 scale-100"
                    leave-active-class="transition ease-in duration-75"
                    leave-from-class="opacity-100 scale-100"
                    leave-to-class="opacity-0 scale-95">
                    <div v-if="showPopup"
                        class="absolute right-0 top-10 w-60 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 z-50 overflow-hidden">

                        <!-- User header -->
                        <div class="p-4 bg-gradient-to-br from-[#1a3a8f] to-[#1e4db7]">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center text-white font-bold text-sm">
                                    {{ inisial }}
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-sm font-semibold text-white truncate">{{ user.name }}</p>
                                    <p class="text-xs text-blue-200 truncate">{{ user.email }}</p>
                                    <span class="text-xs bg-white/20 text-white px-1.5 py-0.5 rounded-full">
                                        {{ user.role }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Menu -->
                        <div class="p-1.5">
                            <Link :href="route('profil')" @click="showPopup = false"
                                class="flex items-center gap-2.5 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-xl transition-colors">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                </svg>
                                Profil Saya
                            </Link>
                            <Link v-if="user.role === 'user'" :href="route('pusat-bantuan')" @click="showPopup = false"
                                class="flex items-center gap-2.5 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-xl transition-colors">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z"/>
                                </svg>
                                Pusat Bantuan
                            </Link>
                            <Link :href="route('tentang')" @click="showPopup = false"
                                class="flex items-center gap-2.5 px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-xl transition-colors">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/>
                                </svg>
                                Tentang PT
                            </Link>
                            <div class="border-t border-gray-100 dark:border-gray-700 my-1" />
                            <Link :href="route('logout')" method="post" as="button" @click="showPopup = false"
                                class="flex items-center gap-2.5 px-3 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-colors w-full text-left">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
                                </svg>
                                Logout
                            </Link>
                        </div>
                    </div>
                </Transition>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { useDarkMode } from '@/composables/useDarkMode'

const { isDark, toggle } = useDarkMode()
const page = usePage()
const user = computed(() => page.props.auth.user)
const showPopup = ref(false)
const inisial = computed(() => user.value?.name?.charAt(0).toUpperCase() ?? '?')

const tabs = ['Monitoring', 'Log Sistem']
const activeTab = ref('Monitoring')

defineProps({
    title: { type: String, default: '' },
})
</script>
