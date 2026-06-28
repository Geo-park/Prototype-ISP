<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
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

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

const loginAs = (role) => {
    const accounts = {
        superadmin: { email: 'superadmin@demo.com', password: 'demo1234' },
        admin: { email: 'admin@demo.com', password: 'demo1234' },
        user: { email: 'budi@demo.com', password: 'demo1234' },
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
        <Head title="Log in" />

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />
                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4 block">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-gray-600">Remember me</span>
                </label>
            </div>

            <div class="mt-4 flex items-center justify-end">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900"
                >
                    Forgot your password?
                </Link>
                <PrimaryButton
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Log in
                </PrimaryButton>
            </div>
        </form>

        <!-- Demo Akun — Preview as Role -->
        <div class="mt-6 border-t pt-4">
            <p class="text-xs text-gray-500 text-center mb-3">Preview as Role (Demo)</p>
            <div class="flex gap-2">
                <button @click="loginAs('superadmin')"
                    class="flex-1 text-xs bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
                    Superadmin
                </button>
                <button @click="loginAs('admin')"
                    class="flex-1 text-xs bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">
                    Admin
                </button>
                <button @click="loginAs('user')"
                    class="flex-1 text-xs bg-gray-600 text-white py-2 rounded-lg hover:bg-gray-700">
                    User
                </button>
            </div>
        </div>
    </GuestLayout>
</template>
