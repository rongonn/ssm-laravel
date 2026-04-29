<script setup lang="ts">
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';
import Footer from '@/Components/Footer.vue';
import Toast from '@/Components/Toast.vue';

const props = defineProps<{
    session?: any;
}>();
</script>

<template>
    <div class="flex flex-col min-h-screen selection:bg-brand-200 selection:text-brand-900">
        <Toast />
        <Navbar :session="session" />
        
        <main class="flex-grow">
            <slot />
        </main>
        
        <Footer />
    </div>
</template>
