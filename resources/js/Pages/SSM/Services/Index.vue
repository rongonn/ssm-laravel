<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { Clock, ArrowRight } from 'lucide-vue-next';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const props = defineProps<{
    services: any[];
    categories: any[];
}>();

const page = usePage();
const settings = computed(() => page.props.settings || {});
const BANNER_URL = computed(() => (settings.value as any).services_banner ? `/storage/${(settings.value as any).services_banner}` : "https://images.unsplash.com/photo-1519823551278-64ac92734fb4?auto=format&fit=crop&q=80&w=2000");

const category = ref('All');

const handleFilter = (cat: string) => {
    category.value = cat;
};

const displayCategories = computed(() => {
    const cats = new Set();
    props.services.forEach(s => {
        const name = s.category_item?.name || s.category;
        if (name) cats.add(name);
    });
    return ['All', ...Array.from(cats)].sort();
});

const filteredServices = computed(() => {
    if (category.value === 'All') return props.services;
    return props.services.filter(s => (s.category_item?.name || s.category) === category.value);
});
</script>

<template>
    <PublicLayout>
        <Head title="Services | Menu" />
        
        <div class="bg-brand-50 min-h-screen pb-24">
            <!-- Header -->
            <section class="bg-brand-900 text-white py-24 relative overflow-hidden">
                <div class="max-w-7xl mx-auto px-4 relative z-10">
                    <h1 class="text-5xl md:text-7xl font-serif mb-6 text-center">Service Menu</h1>
                    <p class="text-xl text-brand-200 text-center max-w-2xl mx-auto">
                        Experience the pinnacle of personal care with our range of professional treatments and artisanal styling.
                    </p>
                </div>
                <div class="absolute inset-0 opacity-20 bg-cover bg-center" :style="{ backgroundImage: `url(${BANNER_URL})`, mixBlendMode: 'overlay' }"></div>
            </section>

            <!-- Filters -->
            <div class="sticky top-20 z-40 bg-white/80 backdrop-blur-md border-b border-brand-100 py-6">
                <div class="max-w-7xl mx-auto px-4 flex flex-wrap justify-center gap-4">
                    <button
                        v-for="cat in displayCategories"
                        :key="cat"
                        @click="handleFilter(cat)"
                        :class="[
                            'px-8 py-2.5 rounded-full text-sm font-bold transition-all',
                            category === cat 
                            ? 'bg-brand-900 text-white shadow-lg' 
                            : 'bg-white text-slate-500 border border-brand-100 hover:border-brand-300'
                        ]"
                    >
                        {{ cat }}
                    </button>
                </div>
            </div>

            <!-- Grid -->
            <div class="max-w-7xl mx-auto px-4 mt-16">
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div v-for="service in filteredServices" :key="service.id" class="bg-white rounded-[2rem] overflow-hidden group hover:shadow-2xl transition-all duration-500 border border-brand-100 flex flex-col">
                        <div class="h-64 relative overflow-hidden">
                            <img 
                                :src="service.image_url || 'https://picsum.photos/800/600?random=' + service.id" 
                                :alt="service.name" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                            />
                            <div class="absolute top-4 right-4 bg-brand-900 text-white px-4 py-2 rounded-full font-bold shadow-lg">
                                ৳{{ service.price }}
                            </div>
                        </div>
                        <div class="p-10 flex-grow flex flex-col items-center text-center">
                            <ul class="flex items-center space-x-2 text-brand-500 font-bold text-xs uppercase tracking-widest mb-4">
                                <li>{{ service.category_item?.name || service.category }}</li>
                                <li>•</li>
                                <li class="flex items-center space-x-1">
                                    <Clock :size="14" />
                                    <span>{{ service.duration }}</span>
                                </li>
                            </ul>
                            <h3 class="text-2xl font-serif text-slate-900 mb-4">{{ service.name }}</h3>
                            <p class="text-slate-500 mb-8 line-clamp-2 leading-relaxed flex-grow">{{ service.description }}</p>
                            
                            <Link 
                                :href="`/services/${service.id}`" 
                                class="inline-flex items-center justify-center space-x-2 bg-brand-900 text-white px-8 py-3.5 rounded-full font-bold text-[10px] uppercase tracking-widest transition-all hover:bg-brand-800 hover:scale-105 active:scale-95 shadow-lg shadow-brand-900/20"
                            >
                                <span>View Details</span>
                                <ArrowRight :size="14" />
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
