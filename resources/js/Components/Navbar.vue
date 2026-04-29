<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { Menu, X, User, ShoppingCart } from 'lucide-vue-next';
import { cart } from '@/CartStore';

const isOpen = ref(false);
const isScrolled = ref(false);

const handleScroll = () => {
    isScrolled.value = window.scrollY > 20;
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});

const page = usePage();
const settings = computed(() => page.props.settings || {});

const LOGO_URL = computed(() => {
    return settings.value.company_logo 
        ? `/storage/${settings.value.company_logo}` 
        : "https://placehold.co/150";
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
    <nav 
        class="fixed top-0 left-0 right-0 z-50 transition-all duration-500 border-b flex items-center"
        :class="[
            isScrolled 
                ? 'bg-white/80 backdrop-blur-xl border-brand-100 h-16 shadow-sm' 
                : 'bg-transparent border-transparent h-20 md:h-24'
        ]"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="flex justify-between items-center w-full transition-all duration-500">
                <div class="flex items-center">
                    <Link href="/" class="flex-shrink-0 flex items-center group space-x-3">
                        <div class="bg-white p-1 rounded-xl shadow-sm border border-brand-50 group-hover:scale-105 transition-transform">
                            <img 
                                :src="LOGO_URL" 
                                alt="Logo" 
                                class="h-8 md:h-10 w-auto object-contain transition-all"
                            />
                        </div>
                        <span class="text-lg md:text-xl font-serif font-bold text-brand-900 tracking-tight group-hover:text-brand-600 transition-colors uppercase hidden sm:block">
                            {{ APP_NAME }}
                        </span>
                    </Link>
                </div>

                <div class="hidden md:flex items-center space-x-10">
                    <div class="flex items-center space-x-8">
                        <Link
                            v-for="link in navLinks"
                            :key="link.name"
                            :href="link.path"
                            class="relative group py-2"
                        >
                            <span :class="[
                                'text-[11px] font-black uppercase tracking-[0.2em] transition-colors',
                                isActive(link.path) ? 'text-brand-900' : 'text-slate-500 group-hover:text-brand-900'
                            ]">
                                {{ link.name }}
                            </span>
                            <span 
                                class="absolute bottom-0 left-0 h-0.5 bg-brand-900 transition-all duration-300"
                                :class="isActive(link.path) ? 'w-full' : 'w-0 group-hover:w-full'"
                            ></span>
                        </Link>
                    </div>
                    
                    <div class="h-6 w-px bg-slate-200"></div>

                    <Link href="/checkout" class="relative p-2.5 text-slate-600 hover:text-brand-900 bg-brand-50 rounded-xl hover:bg-white hover:shadow-md transition-all group">
                        <ShoppingCart :size="20" />
                        <span v-if="cart.totalItems > 0" class="absolute -top-1 -right-1 bg-brand-900 text-white text-[9px] font-black w-5 h-5 flex items-center justify-center rounded-full border-2 border-white group-hover:scale-110 transition-transform shadow-sm">
                            {{ cart.totalItems }}
                        </span>
                    </Link>
                </div>

                <div class="md:hidden flex items-center space-x-4">
                    <Link href="/checkout" class="relative p-2.5 text-slate-600 hover:text-brand-900 bg-brand-50 rounded-xl transition-all">
                        <ShoppingCart :size="20" />
                        <span v-if="cart.totalItems > 0" class="absolute -top-1 -right-1 bg-brand-900 text-white text-[9px] font-black w-5 h-5 flex items-center justify-center rounded-full border-2 border-white">
                            {{ cart.totalItems }}
                        </span>
                    </Link>
                    <button
                        @click="isOpen = !isOpen"
                        class="p-2.5 bg-brand-900 text-white rounded-xl hover:bg-brand-800 transition-all shadow-lg shadow-brand-900/20"
                    >
                        <X v-if="isOpen" :size="20" />
                        <Menu v-else :size="20" />
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <Transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="transform -translate-y-4 opacity-0"
            enter-to-class="transform translate-y-0 opacity-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="transform translate-y-0 opacity-100"
            leave-to-class="transform -translate-y-4 opacity-0"
        >
            <div v-if="isOpen" class="md:hidden absolute top-full left-0 right-0 bg-white/95 backdrop-blur-2xl border-b border-brand-100 shadow-2xl overflow-hidden">
                <div class="px-6 pt-4 pb-10 space-y-2">
                    <Link
                        v-for="link in navLinks"
                        :key="link.name"
                        :href="link.path"
                        @click="isOpen = false"
                        :class="[
                            'block px-4 py-4 rounded-2xl text-xs font-black uppercase tracking-[0.2em] transition-all',
                            isActive(link.path) ? 'bg-brand-900 text-white shadow-lg shadow-brand-900/20' : 'text-slate-600 hover:bg-brand-50'
                        ]"
                    >
                        {{ link.name }}
                    </Link>
                </div>
            </div>
        </Transition>
    </nav>
    
    <!-- Spacer for fixed navbar -->
    <div class="h-20 md:h-24"></div>
</template>
