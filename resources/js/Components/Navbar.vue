<script setup lang="ts">
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { Menu, X, User } from 'lucide-vue-next';

const isOpen = ref(false);

const page = usePage();
const session = computed(() => page.props.auth?.user);
const settings = computed(() => page.props.settings || {});

const LOGO_URL = computed(() => {
    return settings.value.company_logo 
        ? `/storage/${settings.value.company_logo}` 
        : "https://via.placeholder.com/150";
});

const APP_NAME = computed(() => settings.value.application_name || 'Bioshah.com');

const navLinks = [
    { name: 'Home', path: '/' },
    { name: 'Services', path: '/services' },
    { name: 'Products', path: '/products' },
    { name: 'Gallery', path: '/gallery' },
    { name: 'About', path: '/about' },
    { name: 'Contact', path: '/contact' },
];

const isActive = (path: string) => {
    return usePage().url === path;
};
</script>

<template>
    <nav class="sticky top-0 z-50 glass border-b border-brand-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <Link href="/" class="flex-shrink-0 flex items-center group space-x-3">
                        <img 
                            :src="LOGO_URL" 
                            alt="Logo" 
                            class="h-10 w-10 md:h-12 md:w-12 rounded-xl object-cover shadow-sm group-hover:scale-105 transition-transform border border-brand-100"
                        />
                        <span class="text-xl md:text-2xl font-serif font-bold text-brand-900 tracking-tight group-hover:text-brand-600 transition-colors uppercase">
                            {{ APP_NAME }}
                        </span>
                    </Link>
                </div>

                <div class="hidden md:flex items-center space-x-8">
                    <Link
                        v-for="link in navLinks"
                        :key="link.name"
                        :href="link.path"
                        :class="[
                            'text-sm font-medium tracking-wide transition-colors',
                            isActive(link.path) ? 'text-brand-900' : 'text-slate-500 hover:text-brand-600'
                        ]"
                    >
                        {{ link.name }}
                    </Link>
                    
                    <template v-if="session">
                        <Link
                            href="/admin"
                            class="flex items-center space-x-2 bg-brand-900 text-white px-6 py-2.5 rounded-full text-sm font-semibold hover:bg-brand-800 transition-all transform hover:scale-105 active:scale-95 shadow-lg shadow-brand-900/10"
                        >
                            <User :size="18" />
                            <span>Admin</span>
                        </Link>
                    </template>

                </div>

                <div class="md:hidden flex items-center">
                    <button
                        @click="isOpen = !isOpen"
                        class="text-slate-600 hover:text-brand-900 p-2"
                    >
                        <X v-if="isOpen" :size="24" />
                        <Menu v-else :size="24" />
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div v-if="isOpen" class="md:hidden bg-white/95 backdrop-blur-md border-b border-brand-100 animate-in fade-in slide-in-from-top-4 duration-300">
            <div class="px-4 pt-2 pb-6 space-y-2">
                <Link
                    v-for="link in navLinks"
                    :key="link.name"
                    :href="link.path"
                    @click="isOpen = false"
                    :class="[
                        'block px-3 py-3 rounded-md text-base font-medium',
                        isActive(link.path) ? 'bg-brand-50 text-brand-900' : 'text-slate-600 hover:bg-brand-50'
                    ]"
                >
                    {{ link.name }}
                </Link>
                
                <template v-if="session">
                    <Link
                        href="/admin"
                        @click="isOpen = false"
                        class="flex items-center justify-center space-x-2 w-full mt-4 bg-brand-900 text-white px-6 py-4 rounded-xl text-lg font-semibold"
                    >
                        <User :size="20" />
                        <span>Admin Dashboard</span>
                    </Link>
                </template>

            </div>
        </div>
    </nav>
</template>
