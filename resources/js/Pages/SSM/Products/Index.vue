<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { Search, ShoppingBag, ArrowRight } from 'lucide-vue-next';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const props = defineProps<{
    products: any[];
}>();

const page = usePage();
const settings = computed(() => page.props.settings || {});
const BANNER_URL = computed(() => (settings.value as any).products_banner ? `/storage/${(settings.value as any).products_banner}` : "https://images.unsplash.com/photo-1620916566398-39f1143ab7be?auto=format&fit=crop&q=80&w=2000");

const searchQuery = ref('');
const category = ref('All');

const categories = ['All', 'Hair Care', 'Skin Care', 'Fragrance', 'Tools', 'Body'];

const filteredProducts = computed(() => {
    return props.products.filter(p => {
        const name = p.name || '';
        const brand = p.brand || '';
        const matchesSearch = name.toLowerCase().includes(searchQuery.value.toLowerCase()) || 
                             brand.toLowerCase().includes(searchQuery.value.toLowerCase());
        const matchesCategory = category.value === 'All' || p.category === category.value;
        return matchesSearch && matchesCategory;
    });
});
</script>

<template>
    <PublicLayout>
        <Head title="Products | Apothecary" />
        
        <div class="bg-brand-50 min-h-screen pb-20">
            <!-- Hero Header -->
            <section class="bg-brand-900 text-white py-24 relative overflow-hidden">
                <div class="max-w-7xl mx-auto px-4 text-center relative z-10">
                    <h1 class="text-5xl md:text-7xl font-serif mb-6 uppercase tracking-tighter">Apothecary</h1>
                    <p class="text-xl text-brand-200 max-w-2xl mx-auto italic font-serif">
                        Premium salon-grade essentials for your daily transformation.
                    </p>
                </div>
                <div class="absolute inset-0 opacity-20 bg-cover bg-center" :style="{ backgroundImage: `url(${BANNER_URL})`, mixBlendMode: 'overlay' }"></div>
            </section>

            <!-- Search & Category Filter -->
            <div class="max-w-7xl mx-auto px-4 -mt-12 relative z-20">
                <div class="bg-white p-6 rounded-[2.5rem] shadow-2xl border border-brand-100 space-y-6">
                    <div class="flex items-center bg-slate-50 rounded-2xl px-6 border border-slate-100">
                        <Search class="text-slate-400" :size="20" />
                        <input 
                            type="text" 
                            placeholder="Search by name or brand..."
                            v-model="searchQuery"
                            class="w-full px-4 py-4 outline-none bg-transparent placeholder:text-slate-300 font-medium"
                        />
                    </div>
                    
                    <div class="flex flex-wrap gap-2 justify-center">
                        <button
                            v-for="cat in categories"
                            :key="cat"
                            @click="category = cat"
                            :class="[
                                'px-6 py-2 rounded-full text-xs font-black uppercase tracking-widest transition-all',
                                category === cat 
                                ? 'bg-brand-900 text-white' 
                                : 'bg-white text-slate-400 border border-slate-100 hover:border-brand-200'
                            ]"
                        >
                            {{ cat }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Grid -->
            <div class="max-w-7xl mx-auto px-4 py-20">
                <div v-if="filteredProducts.length === 0" class="text-center py-24 bg-white rounded-[3rem] border-2 border-dashed border-slate-200">
                    <ShoppingBag class="mx-auto text-slate-200 w-20 h-20 mb-6" />
                    <p class="text-slate-500 text-xl font-serif italic">No products found matching your selection.</p>
                </div>
                <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-12 gap-y-20">
                    <div v-for="product in filteredProducts" :key="product.id" class="group flex flex-col items-center text-center">
                        <div class="w-full aspect-[4/5] rounded-[3rem] overflow-hidden bg-white mb-8 relative border border-brand-100 group-hover:shadow-2xl transition-all duration-700">
                            <img 
                                :src="product.image_url || 'https://images.unsplash.com/photo-1596462502278-27bfad450526?auto=format&fit=crop&q=80&w=800'" 
                                :alt="product.name" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-1000"
                            />
                            <div class="absolute top-6 left-6 bg-white/90 backdrop-blur-md text-brand-900 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-[0.2em] shadow-sm">
                                {{ product.category }}
                            </div>
                        </div>
                        
                        <div class="px-6 space-y-2 flex flex-col items-center">
                            <p class="text-[10px] font-black text-brand-500 uppercase tracking-[0.3em]">{{ product.brand }}</p>
                            <h3 class="text-2xl font-serif text-slate-900 transition-colors leading-tight mb-2">
                                {{ product.name }}
                            </h3>
                            <p class="text-xl font-bold text-slate-900 mb-6">৳{{ product.price }}</p>
                            
                            <Link 
                                :href="`/products/${product.id}`" 
                                class="inline-flex items-center justify-center space-x-2 bg-brand-900 text-white px-8 py-3.5 rounded-full font-bold text-[10px] uppercase tracking-widest transition-all hover:bg-brand-800 hover:scale-105 active:scale-95 shadow-lg shadow-brand-900/20"
                            >
                                <span>View Product</span>
                                <ArrowRight :size="14" />
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
