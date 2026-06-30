<script setup>
import { ref } from 'vue'
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: { type: Boolean },
    status: { type: String },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const showPassword = ref(false)

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

const loginAs = (role) => {
    const accounts = {
        superadmin: { email: 'superadmin@demo.com', password: 'demo1234' },
        admin:      { email: 'admin@demo.com', password: 'demo1234' },
        adminJkt:   { email: 'admin.jakarta@demo.com', password: 'demo1234' },
        user:       { email: 'budi@demo.com', password: 'demo1234' },
    }
    form.email = accounts[role].email
    form.password = accounts[role].password
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    })
}
</script>

<template>
    <GuestLayout>
        <Head title="LJN Portal — Masuk" />

        <!-- Logo + Brand -->
        <div class="flex flex-col items-center mb-8">
            <div class="w-14 h-14 bg-[#1a3a8f] rounded-2xl flex items-center justify-center shadow-lg mb-4">
                <!-- Wifi / Router icon -->
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8.288 15.038a5.25 5.25 0 017.424 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 011.06 0z" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-[#1a3a8f]">LJN Portal</h1>
            <p class="text-sm text-gray-500 mt-0.5">PT. Lintas Jaringan Nusantara</p>
        </div>

        <!-- Status msg -->
        <div v-if="status" class="mb-4 text-sm font-medium text-green-600 bg-green-50 px-3 py-2 rounded-lg">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                        </svg>
                    </span>
                    <input
                        id="email"
                        v-model="form.email"
                        type="email"
                        placeholder="admin@ljn.co.id"
                        required
                        autofocus
                        autocomplete="username"
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm focus:outline-none focus:ring-2 focus:ring-[#1e4db7] focus:border-transparent transition"
                    />
                </div>
                <InputError class="mt-1.5" :message="form.errors.email" />
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                    </span>
                    <input
                        id="password"
                        v-model="form.password"
                        :type="showPassword ? 'text' : 'password'"
                        placeholder="••••••••"
                        required
                        autocomplete="current-password"
                        class="w-full pl-10 pr-10 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm focus:outline-none focus:ring-2 focus:ring-[#1e4db7] focus:border-transparent transition"
                    />
                    <button type="button" @click="showPassword = !showPassword"
                        class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600">
                        <!-- Eye / Eye-off -->
                        <svg v-if="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </button>
                </div>
                <InputError class="mt-1.5" :message="form.errors.password" />
            </div>

            <!-- Remember + Forgot -->
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 cursor-pointer">
                    <Checkbox name="remember" v-model:checked="form.remember"
                        class="rounded border-gray-300" />
                    <span class="text-sm text-gray-600">Ingat saya</span>
                </label>
                <Link v-if="canResetPassword" :href="route('password.request')"
                    class="text-sm text-[#1e4db7] hover:text-[#1a3a8f] font-medium transition">
                    Lupa Password?
                </Link>
            </div>

            <!-- Submit -->
            <button
                type="submit"
                :disabled="form.processing"
                class="w-full flex items-center justify-center gap-2 bg-[#1a3a8f] hover:bg-[#0f1b3d] text-white font-semibold py-3 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg disabled:opacity-50 mt-2">
                <span>Masuk</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </button>
        </form>

        <!-- Demo Role Switcher -->
        <div class="mt-6 pt-5 border-t border-gray-100">
            <p class="text-xs text-gray-500 flex items-center gap-1.5 mb-3">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Preview as Role (Demo)
            </p>
            <div class="grid grid-cols-2 gap-2">
                <button @click="loginAs('superadmin')"
                    class="text-xs border border-gray-200 text-gray-700 py-2 px-3 rounded-lg hover:bg-[#1a3a8f] hover:text-white hover:border-[#1a3a8f] transition-all duration-150 font-medium">
                    Superadmin
                </button>
                <button @click="loginAs('admin')"
                    class="text-xs border border-gray-200 text-gray-700 py-2 px-3 rounded-lg hover:bg-[#1a3a8f] hover:text-white hover:border-[#1a3a8f] transition-all duration-150 font-medium">
                    Admin Banten
                </button>
                <button @click="loginAs('adminJkt')"
                    class="text-xs border border-gray-200 text-gray-700 py-2 px-3 rounded-lg hover:bg-[#1a3a8f] hover:text-white hover:border-[#1a3a8f] transition-all duration-150 font-medium">
                    Admin Jakarta
                </button>
                <button @click="loginAs('user')"
                    class="text-xs border border-gray-200 text-gray-700 py-2 px-3 rounded-lg hover:bg-[#1a3a8f] hover:text-white hover:border-[#1a3a8f] transition-all duration-150 font-medium">
                    User
                </button>
            </div>
        </div>
    </GuestLayout>
</template>
