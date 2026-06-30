<script setup>
// GuestLayout — LJN Portal style
</script>

<template>
    <!-- Animated mesh background -->
    <div class="ljn-bg min-h-screen flex flex-col items-center justify-center px-4 relative">
        <!-- Flowing light lines -->
        <div class="ljn-line" />
        <div class="ljn-line" />
        <div class="ljn-line" />
        <div class="ljn-line" />

        <!-- Card -->
        <div class="relative z-10 w-full max-w-md">
            <div class="bg-white rounded-2xl shadow-2xl px-8 py-10">
                <slot />
            </div>
            <!-- Footer -->
            <p class="text-center text-xs text-gray-400/80 mt-5">
                © 2024 PT. Lintas Jaringan Nusantara. All rights reserved.
            </p>
        </div>
    </div>
</template>
