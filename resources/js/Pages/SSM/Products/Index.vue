<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { Search, ShoppingBag, ArrowRight, SlidersHorizontal, X, ChevronDown, ShoppingCart, Zap } from 'lucide-vue-next';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { cart } from '@/CartStore';
import { router } from '@inertiajs/vue3';

const props = defineProps<{
    products: any[];
    categories: any[];
}>();

const page = usePage();
const settings = computed(() => page.props.settings || {});
const BANNER_URL = computed(() => (settings.value as any).products_banner ? `/storage/${(settings.value as any).products_banner}` : "https://images.unsplash.com/photo-1620916566398-39f1143ab7be?auto=format&fit=crop&q=80&w=2000");

// --- FILTERS ---
const searchQuery = ref('');
const selectedCategory = ref('All');
const selectedBrands = ref<string[]>([]);
const sortBy = ref('default');
const priceMin = ref(0);
const priceMax = ref(10000);
const inStockOnly = ref(false);
const mobileSidebarOpen = ref(false);

// Derive unique categories and brands from data
const allCategories = computed(() => {
    const cats = new Set();
    props.products.forEach(p => {
        const name = p.category_item?.name || p.category;
        if (name) cats.add(name);
    });
    return ['All', ...Array.from(cats)].sort();
});

const allBrands = computed(() => {
    const brands = new Set(props.products.map(p => p.brand).filter(Boolean));
    return Array.from(brands) as string[];
});

const maxPrice = computed(() => Math.max(...props.products.map(p => Number(p.price) || 0), 500));

const toggleBrand = (brand: string) => {
    const idx = selectedBrands.value.indexOf(brand);
    if (idx >= 0) selectedBrands.value.splice(idx, 1);
    else selectedBrands.value.push(brand);
};

const resetFilters = () => {
    searchQuery.value = '';
    selectedCategory.value = 'All';
    selectedBrands.value = [];
    sortBy.value = 'default';
    priceMin.value = 0;
    priceMax.value = maxPrice.value;
    inStockOnly.value = false;
};

const activeFiltersCount = computed(() => {
    let count = 0;
    if (searchQuery.value) count++;
    if (selectedCategory.value !== 'All') count++;
    if (selectedBrands.value.length) count++;
    if (sortBy.value !== 'default') count++;
    if (priceMin.value > 0 || priceMax.value < maxPrice.value) count++;
    if (inStockOnly.value) count++;
    return count;
});

const filteredProducts = computed(() => {
    let list = props.products.filter(p => {
        const name = (p.name || '').toLowerCase();
        const brand = (p.brand || '').toLowerCase();
        const q = searchQuery.value.toLowerCase();

        const matchesSearch = !q || name.includes(q) || brand.includes(q);
        const productCategory = p.category_item?.name || p.category;
        const matchesCategory = selectedCategory.value === 'All' || productCategory === selectedCategory.value;
        const matchesBrand = selectedBrands.value.length === 0 || selectedBrands.value.includes(p.brand);
        const price = Number(p.price) || 0;
        const matchesPrice = price >= priceMin.value && price <= priceMax.value;
        const matchesStock = !inStockOnly.value || (Number(p.stock) > 0);

        return matchesSearch && matchesCategory && matchesBrand && matchesPrice && matchesStock;
    });

    if (sortBy.value === 'price_asc') list = [...list].sort((a, b) => Number(a.price) - Number(b.price));
    else if (sortBy.value === 'price_desc') list = [...list].sort((a, b) => Number(b.price) - Number(a.price));
    else if (sortBy.value === 'name_asc') list = [...list].sort((a, b) => (a.name || '').localeCompare(b.name || ''));
    else if (sortBy.value === 'name_desc') list = [...list].sort((a, b) => (b.name || '').localeCompare(a.name || ''));

    return list;
});

const addToCart = (product: any) => {
    cart.addItem(product);
};

const buyNow = (product: any) => {
    cart.addItem(product);
    router.visit('/checkout');
};
</script>

<template>
    <PublicLayout>
        <Head title="Products | Apothecary" />
        
        <div class="bg-brand-50 min-h-screen">
            <!-- Hero Header -->
            <section class="bg-brand-900 text-white py-24 relative overflow-hidden">
                <div class="max-w-7xl mx-auto px-4 text-center relative z-10">
                    <h1 class="text-5xl md:text-7xl font-serif mb-6 uppercase tracking-tighter">Apothecary</h1>
                    <p class="text-xl text-brand-200 max-w-2xl mx-auto italic font-serif">
                        Premium salon-grade essentials for your daily transformation.
                    </p>
                </div>
                <div class="absolute inset-0 opacity-30 bg-cover bg-center" :style="{ backgroundImage: `url(${BANNER_URL})`, mixBlendMode: 'overlay' }"></div>
            </section>

            <!-- Main Content: Sidebar + Products -->
            <div class="max-w-7xl mx-auto px-4 py-12">

                <!-- Mobile Filter Toggle -->
                <div class="flex items-center justify-between mb-6 lg:hidden">
                    <p class="text-slate-600 font-semibold text-sm">{{ filteredProducts.length }} products found</p>
                    <button
                        @click="mobileSidebarOpen = !mobileSidebarOpen"
                        class="flex items-center gap-2 bg-brand-900 text-white px-5 py-2.5 rounded-full text-sm font-bold shadow-lg"
                    >
                        <SlidersHorizontal :size="16" />
                        <span>Filters</span>
                        <span v-if="activeFiltersCount > 0" class="bg-white text-brand-900 rounded-full w-5 h-5 flex items-center justify-center text-xs font-black">{{ activeFiltersCount }}</span>
                    </button>
                </div>

                <div class="flex gap-8 items-start">

                    <!-- ===== SIDEBAR ===== -->
                    <aside
                        class="w-72 flex-shrink-0 sticky top-6"
                        :class="mobileSidebarOpen ? 'block' : 'hidden lg:block'"
                    >
                        <!-- Mobile Overlay -->
                        <div v-if="mobileSidebarOpen" @click="mobileSidebarOpen = false" class="fixed inset-0 bg-black/50 z-30 lg:hidden"></div>

                        <div class="bg-white rounded-[2rem] shadow-xl border border-brand-100 overflow-hidden relative z-40">
                            <!-- Sidebar Header -->
                            <div class="bg-gradient-to-br from-brand-900 to-brand-800 p-6 text-white">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <SlidersHorizontal :size="20" />
                                        <span class="font-black uppercase tracking-widest text-sm">Filters</span>
                                    </div>
                                    <button
                                        v-if="activeFiltersCount > 0"
                                        @click="resetFilters"
                                        class="text-xs bg-white/20 hover:bg-white/30 px-3 py-1.5 rounded-full font-bold transition-all flex items-center gap-1"
                                    >
                                        <X :size="12" /> Clear All
                                    </button>
                                </div>
                                <p class="text-brand-200 text-xs mt-2">{{ filteredProducts.length }} of {{ props.products.length }} products</p>
                            </div>

                            <div class="p-6 space-y-7">

                                <!-- Search -->
                                <div>
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Search</label>
                                    <div class="flex items-center bg-slate-50 rounded-xl px-4 border border-slate-100 focus-within:border-brand-300 transition-colors">
                                        <Search class="text-slate-300" :size="16" />
                                        <input
                                            type="text"
                                            placeholder="Name or brand..."
                                            v-model="searchQuery"
                                            class="w-full px-3 py-3 outline-none bg-transparent text-sm placeholder:text-slate-300 font-medium"
                                        />
                                        <button v-if="searchQuery" @click="searchQuery = ''" class="text-slate-300 hover:text-slate-500">
                                            <X :size="14" />
                                        </button>
                                    </div>
                                </div>

                                <!-- Divider -->
                                <div class="h-px bg-slate-100"></div>

                                <!-- Sort By -->
                                <div>
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Sort By</label>
                                    <div class="relative">
                                        <select
                                            v-model="sortBy"
                                            class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 outline-none appearance-none cursor-pointer focus:border-brand-300 transition-colors"
                                        >
                                            <option value="default">Default</option>
                                            <option value="price_asc">Price: Low → High</option>
                                            <option value="price_desc">Price: High → Low</option>
                                            <option value="name_asc">Name: A → Z</option>
                                            <option value="name_desc">Name: Z → A</option>
                                        </select>
                                        <ChevronDown class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" :size="16" />
                                    </div>
                                </div>

                                <!-- Divider -->
                                <div class="h-px bg-slate-100"></div>

                                <!-- Price Range -->
                                <div>
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Price Range</label>
                                    <div class="flex items-center justify-between text-sm font-bold text-brand-900 mb-4">
                                        <span>৳{{ priceMin }}</span>
                                        <span>৳{{ priceMax }}</span>
                                    </div>
                                    <div class="space-y-3">
                                        <div>
                                            <p class="text-xs text-slate-400 mb-1">Min Price</p>
                                            <input
                                                type="range"
                                                v-model.number="priceMin"
                                                :min="0"
                                                :max="maxPrice"
                                                :step="50"
                                                class="w-full accent-brand-900 cursor-pointer"
                                            />
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-400 mb-1">Max Price</p>
                                            <input
                                                type="range"
                                                v-model.number="priceMax"
                                                :min="0"
                                                :max="maxPrice"
                                                :step="50"
                                                class="w-full accent-brand-900 cursor-pointer"
                                            />
                                        </div>
                                    </div>
                                </div>

                                <!-- Divider -->
                                <div class="h-px bg-slate-100"></div>

                                <!-- Category -->
                                <div>
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Category</label>
                                    <div class="space-y-1.5">
                                        <button
                                            v-for="cat in allCategories"
                                            :key="cat"
                                            @click="selectedCategory = cat"
                                            class="w-full flex items-center justify-between px-4 py-2.5 rounded-xl text-sm font-semibold transition-all"
                                            :class="selectedCategory === cat
                                                ? 'bg-brand-900 text-white shadow-md'
                                                : 'text-slate-600 hover:bg-brand-50 hover:text-brand-900'"
                                        >
                                            <span>{{ cat }}</span>
                                            <span
                                                class="text-xs rounded-full px-2 py-0.5"
                                                :class="selectedCategory === cat ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-400'"
                                            >
                                                {{ cat === 'All' ? props.products.length : props.products.filter(p => (p.category_item?.name || p.category) === cat).length }}
                                            </span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Divider -->
                                <div class="h-px bg-slate-100"></div>

                                <!-- Brand -->
                                <div v-if="allBrands.length > 0">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Brand</label>
                                    <div class="space-y-2">
                                        <label
                                            v-for="brand in allBrands"
                                            :key="brand"
                                            class="flex items-center gap-3 cursor-pointer group"
                                        >
                                            <div
                                                @click="toggleBrand(brand)"
                                                class="w-5 h-5 rounded-md border-2 flex items-center justify-center transition-all flex-shrink-0"
                                                :class="selectedBrands.includes(brand)
                                                    ? 'bg-brand-900 border-brand-900'
                                                    : 'border-slate-200 group-hover:border-brand-300'"
                                            >
                                                <svg v-if="selectedBrands.includes(brand)" class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                            <span @click="toggleBrand(brand)" class="text-sm font-semibold text-slate-600 group-hover:text-brand-900 transition-colors">{{ brand }}</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Divider -->
                                <div class="h-px bg-slate-100"></div>

                                <!-- In Stock Only -->
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-bold text-slate-700">In Stock Only</p>
                                        <p class="text-xs text-slate-400">Show available items</p>
                                    </div>
                                    <button
                                        @click="inStockOnly = !inStockOnly"
                                        class="relative w-12 h-6 rounded-full transition-all flex-shrink-0"
                                        :class="inStockOnly ? 'bg-brand-900' : 'bg-slate-200'"
                                    >
                                        <div
                                            class="absolute top-1 w-4 h-4 bg-white rounded-full shadow transition-all"
                                            :class="inStockOnly ? 'left-7' : 'left-1'"
                                        ></div>
                                    </button>
                                </div>

                            </div>
                        </div>
                    </aside>

                    <!-- ===== PRODUCT GRID ===== -->
                    <div class="flex-1 min-w-0">

                        <!-- Active filters + Result count bar -->
                        <div class="flex flex-wrap items-center justify-between gap-3 mb-8">
                            <p class="text-slate-500 text-sm hidden lg:block">
                                Showing <span class="font-black text-brand-900">{{ filteredProducts.length }}</span> of {{ props.products.length }} products
                            </p>
                            <div class="flex flex-wrap gap-2">
                                <span v-if="selectedCategory !== 'All'" class="inline-flex items-center gap-1.5 bg-brand-100 text-brand-900 px-3 py-1 rounded-full text-xs font-bold">
                                    {{ selectedCategory }}
                                    <button @click="selectedCategory = 'All'"><X :size="12" /></button>
                                </span>
                                <span v-if="selectedBrands.length > 0" class="inline-flex items-center gap-1.5 bg-brand-100 text-brand-900 px-3 py-1 rounded-full text-xs font-bold">
                                    {{ selectedBrands.length }} brand(s)
                                    <button @click="selectedBrands = []"><X :size="12" /></button>
                                </span>
                                <span v-if="sortBy !== 'default'" class="inline-flex items-center gap-1.5 bg-brand-100 text-brand-900 px-3 py-1 rounded-full text-xs font-bold">
                                    Sorted
                                    <button @click="sortBy = 'default'"><X :size="12" /></button>
                                </span>
                                <span v-if="inStockOnly" class="inline-flex items-center gap-1.5 bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold">
                                    In Stock
                                    <button @click="inStockOnly = false"><X :size="12" /></button>
                                </span>
                            </div>
                        </div>

                        <!-- Empty State -->
                        <div v-if="filteredProducts.length === 0" class="text-center py-24 bg-white rounded-[3rem] border-2 border-dashed border-slate-200">
                            <ShoppingBag class="mx-auto text-slate-200 w-20 h-20 mb-6" />
                            <p class="text-slate-500 text-xl font-serif italic mb-4">No products found.</p>
                            <button @click="resetFilters" class="bg-brand-900 text-white px-8 py-3 rounded-full text-sm font-bold">Reset Filters</button>
                        </div>

                        <!-- Grid -->
                        <div v-else class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-8">
                            <div
                                v-for="product in filteredProducts"
                                :key="product.id"
                                class="group bg-white rounded-[2rem] overflow-hidden border border-brand-100 hover:shadow-2xl hover:-translate-y-1 transition-all duration-500 flex flex-col"
                            >
                                <div class="aspect-[4/3] overflow-hidden relative">
                                    <img
                                        :src="(Array.isArray(product.image_url) ? product.image_url[0] : product.image_url) || 'https://images.unsplash.com/photo-1596462502278-27bfad450526?auto=format&fit=crop&q=80&w=800'"
                                        :alt="product.name"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                                    />
                                    <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-md text-brand-900 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm">
                                        {{ product.category_item?.name || product.category }}
                                    </div>
                                    <div v-if="Number(product.stock) <= 0" class="absolute top-4 right-4 bg-red-500 text-white px-3 py-1 rounded-full text-[10px] font-black uppercase">
                                        Sold Out
                                    </div>
                                </div>

                                <div class="p-6 flex-grow flex flex-col">
                                    <p class="text-[10px] font-black text-brand-500 uppercase tracking-[0.3em] mb-1">{{ product.brand }}</p>
                                    <h3 class="text-lg font-serif text-slate-900 leading-tight mb-2 flex-grow">{{ product.name }}</h3>
                                    <div class="flex items-center gap-2 mt-4">
                                        <div class="flex-grow">
                                            <p class="text-xl font-bold text-brand-900">৳{{ Number(product.price).toFixed(2) }}</p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <button 
                                                @click="addToCart(product)"
                                                class="p-2.5 bg-brand-50 text-brand-900 rounded-xl hover:bg-brand-900 hover:text-white transition-all shadow-sm"
                                                title="Add to Cart"
                                            >
                                                <ShoppingCart :size="18" />
                                            </button>
                                            <button 
                                                @click="buyNow(product)"
                                                class="p-2.5 bg-brand-900 text-white rounded-xl hover:bg-brand-800 hover:scale-105 transition-all shadow-md"
                                                title="Order Now"
                                            >
                                                <Zap :size="18" />
                                            </button>
                                            <Link
                                                :href="`/products/${product.id}`"
                                                class="p-2.5 bg-slate-100 text-slate-500 rounded-xl hover:bg-slate-200 transition-all"
                                                title="View Details"
                                            >
                                                <ArrowRight :size="18" />
                                            </Link>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </PublicLayout>
</template>
