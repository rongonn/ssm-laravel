<script setup lang="ts">
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import { Link, Head, usePage } from '@inertiajs/vue3';
import { Scissors, ArrowRight, ShoppingBag, Image as ImageIcon, ChevronLeft, ChevronRight, Tag, Clock, X, Maximize2, ShoppingCart, Zap } from 'lucide-vue-next';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import TestimonialSlider from '@/Components/TestimonialSlider.vue';
import { cart } from '@/CartStore';
import { router } from '@inertiajs/vue3';

const props = defineProps<{
    testimonials: any[];
    gallery: any[];
    products: any[];
    services: any[];
    team: any[];
    categories?: any[];
}>();

const page = usePage();
const settings = computed(() => page.props.settings || {});

const selectedImage = ref<string | null>(null);

const LOGO_URL = computed(() => (settings.value as any).company_logo ? `/storage/${(settings.value as any).company_logo}` : "https://placehold.co/150");
const APP_NAME = computed(() => (settings.value as any).application_name || "Bioshah.com");

const heroSliderIndex = ref(0);
const sliderImages = computed(() => {
    try {
        const raw = (settings.value as any).slider_images;
        if (!raw) throw new Error('No slider images');
        const parsed = typeof raw === 'string' ? JSON.parse(raw) : raw;
        if (Array.isArray(parsed) && parsed.length > 0) {
            return parsed.map(img => `/storage/${img}`);
        }
    } catch(e) {}
    
    // Fallback if no slider images are set
    const fallbackDesktop = (settings.value as any).landing_banner 
        ? `/storage/${(settings.value as any).landing_banner}`
        : "https://images.unsplash.com/photo-1560066984-138dadb4c035?auto=format&fit=crop&q=80&w=2000";
        
    return [fallbackDesktop];
});

// --- CATEGORY CAROUSEL ---
const categoryScrollRef = ref<HTMLElement | null>(null);
const categoryIndex = ref(0);

const categoryItemWidth = computed(() => {
    if (!categoryScrollRef.value) return 316; // e.g. 284px width + 32px gap
    const firstItem = categoryScrollRef.value.querySelector(':scope > a') as HTMLElement | null;
    if (!firstItem) return 316;
    return firstItem.offsetWidth + 32; 
});

const categoryTotalItems = computed(() => (props.categories?.length || 0));

const scrollCategory = (dir: 'left' | 'right') => {
    if (!categoryScrollRef.value) return;
    if (dir === 'right' && categoryIndex.value < categoryTotalItems.value - 1) {
        categoryIndex.value++;
    } else if (dir === 'left' && categoryIndex.value > 0) {
        categoryIndex.value--;
    }
    categoryScrollRef.value.scrollTo({ left: categoryIndex.value * categoryItemWidth.value, behavior: 'smooth' });
};

const goToCategory = (idx: number) => {
    if (!categoryScrollRef.value) return;
    categoryIndex.value = idx;
    categoryScrollRef.value.scrollTo({ left: idx * categoryItemWidth.value, behavior: 'smooth' });
};

const onCategoryScroll = () => {
    if (!categoryScrollRef.value) return;
    const idx = Math.round(categoryScrollRef.value.scrollLeft / categoryItemWidth.value);
    categoryIndex.value = idx;
};

const activeFeaturedCategory = ref('All');

const featuredCategories = computed(() => {
    const cats = new Set();
    props.products.forEach(p => {
        const name = p.category_item?.name || p.category;
        if (name) cats.add(name);
    });
    return ['All', ...Array.from(cats)].sort();
});

const featuredProductsList = computed(() => {
    if (activeFeaturedCategory.value === 'All') {
        return props.products.slice(0, 8); // show max 8
    }
    return props.products.filter(p => {
        const name = p.category_item?.name || p.category;
        return name === activeFeaturedCategory.value;
    }).slice(0, 8);
});

const activeFeaturedServiceCategory = ref('All');

const featuredServiceCategories = computed(() => {
    const cats = new Set();
    if (props.services) {
        props.services.forEach(s => {
            const name = s.category_item?.name || s.category;
            if (name) cats.add(name);
        });
    }
    return ['All', ...Array.from(cats)].sort();
});

const featuredServicesList = computed(() => {
    if (!props.services) return [];
    if (activeFeaturedServiceCategory.value === 'All') {
        return props.services.slice(0, 8); // show max 8
    }
    return props.services.filter(s => {
        const name = s.category_item?.name || s.category;
        return name === activeFeaturedServiceCategory.value;
    }).slice(0, 8);
});

const WHATSAPP_NUMBER = "01911-879571";

const openWhatsAppForService = (service: any) => {
    if (!service) return;
    const message = encodeURIComponent(`Hello ${APP_NAME.value}! I'm interested in booking the service: ${service.name} (Category: ${service.category_item?.name || service.category}, Price: ৳${service.price}). Can I book an appointment?`);
    window.open(`https://wa.me/${WHATSAPP_NUMBER.replace('-', '')}?text=${message}`, '_blank');
};

let heroTimer: ReturnType<typeof setInterval> | null = null;
let categoryTimer: ReturnType<typeof setInterval> | null = null;

onMounted(() => {
    if (sliderImages.value.length > 1) {
        heroTimer = setInterval(() => {
            heroSliderIndex.value = (heroSliderIndex.value + 1) % sliderImages.value.length;
        }, 5000); // 5 seconds per slide
    }
    categoryTimer = setInterval(() => scrollCategory('right'), 4500);
    categoryScrollRef.value?.addEventListener('scroll', onCategoryScroll);
});

onBeforeUnmount(() => {
    if (heroTimer) clearInterval(heroTimer);
    if (categoryTimer) clearInterval(categoryTimer);
    categoryScrollRef.value?.removeEventListener('scroll', onCategoryScroll);
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
        <Head title="Home | Premium Salon & Spa" />
        
        <div class="overflow-x-hidden">
            <!-- Hero Section (Slider) -->
            <section class="relative h-[60vh] md:h-[90vh] flex items-center overflow-hidden">
                <div class="absolute inset-0 z-0">
                    <transition-group name="fade" tag="div" class="w-full h-full relative">
                        <div 
                            v-for="(img, index) in sliderImages" 
                            :key="img"
                            v-show="heroSliderIndex === index"
                            class="absolute inset-0 w-full h-full"
                        >
                            <img 
                                :src="img" 
                                class="w-full h-full object-cover"
                                :alt="`${APP_NAME} Hero Slide ${index + 1}`"
                            />
                        </div>
                    </transition-group>
                    
                    <div class="absolute inset-0 bg-black/20"></div>
                </div>

                <!-- Slider Controls -->
                <button 
                    v-if="sliderImages.length > 1"
                    @click="heroSliderIndex = (heroSliderIndex - 1 + sliderImages.length) % sliderImages.length" 
                    class="absolute left-4 md:left-8 top-1/2 -translate-y-1/2 z-20 p-3 md:p-4 rounded-full bg-white/20 hover:bg-white/40 text-white backdrop-blur-sm transition-all shadow-lg"
                >
                    <ChevronLeft :size="28" />
                </button>
                <button 
                    v-if="sliderImages.length > 1"
                    @click="heroSliderIndex = (heroSliderIndex + 1) % sliderImages.length" 
                    class="absolute right-4 md:right-8 top-1/2 -translate-y-1/2 z-20 p-3 md:p-4 rounded-full bg-white/20 hover:bg-white/40 text-white backdrop-blur-sm transition-all shadow-lg"
                >
                    <ChevronRight :size="28" />
                </button>

                <!-- Dot Indicators -->
                <div v-if="sliderImages.length > 1" class="absolute bottom-8 left-0 right-0 z-20 flex justify-center gap-3">
                    <button
                        v-for="(_, i) in sliderImages.length"
                        :key="i"
                        @click="heroSliderIndex = i"
                        class="transition-all duration-300 rounded-full h-2.5 shadow-sm"
                        :class="heroSliderIndex === i ? 'w-10 bg-white' : 'w-2.5 bg-white/50 hover:bg-white/80'"
                    />
                </div>
            </section>

            <!-- SHOP BY CATEGORY SECTION -->
            <section v-if="props.categories && props.categories.length > 0" class="py-20 bg-white">
                <div class="max-w-7xl mx-auto px-4 mb-12 text-center">
                    <h2 class="text-4xl md:text-5xl font-serif text-slate-900 mb-2">Shop By Category</h2>
                </div>

                <div class="max-w-7xl mx-auto px-4 relative group/catslider">
                    <!-- Navigation Overlays -->
                    <button 
                        @click="scrollCategory('left')" 
                        class="absolute -left-4 md:-left-6 top-[40%] -translate-y-1/2 z-20 p-3 md:p-4 rounded-full bg-white shadow-[0_4px_20px_rgba(0,0,0,0.1)] text-slate-700 opacity-0 group-hover/catslider:opacity-100 transition-all hover:text-black disabled:opacity-0 flex items-center justify-center" 
                        :disabled="categoryIndex === 0"
                    >
                        <ChevronLeft :size="24" />
                    </button>
                    <button 
                        @click="scrollCategory('right')" 
                        class="absolute -right-4 md:-right-6 top-[40%] -translate-y-1/2 z-20 p-3 md:p-4 rounded-full bg-white shadow-[0_4px_20px_rgba(0,0,0,0.1)] text-slate-700 opacity-0 group-hover/catslider:opacity-100 transition-all hover:text-black disabled:opacity-0 flex items-center justify-center" 
                        :disabled="categoryIndex >= categoryTotalItems - 1"
                    >
                        <ChevronRight :size="24" />
                    </button>

                    <div 
                        ref="categoryScrollRef"
                        class="flex overflow-x-auto gap-8 no-scrollbar scroll-smooth pb-8"
                    >
                        <Link 
                            v-for="category in props.categories"
                            :key="category.id" 
                            :href="`/products?category=${category.slug}`"
                            class="min-w-[260px] md:min-w-[280px] flex flex-col group block"
                        >
                            <div class="aspect-square mb-6 rounded-3xl overflow-hidden bg-[#F8F9FA] relative transition-transform duration-500 group-hover:-translate-y-2">
                                <img 
                                    :src="category.image_url || 'https://placehold.co/600x600/F8F9FA/CBD5E1?text=' + category.name" 
                                    :alt="category.name" 
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                />
                            </div>
                            <div class="text-center">
                                <h3 class="text-xl text-slate-900 font-medium mb-1 group-hover:text-brand-900 transition-colors">{{ category.name }}</h3>
                                <p class="text-sm text-slate-500">{{ category.products_count }} items</p>
                            </div>
                        </Link>
                    </div>
                </div>

                <!-- Dot Indicators -->
                <div class="flex justify-center gap-2 mt-4">
                    <button
                        v-for="(_, i) in categoryTotalItems"
                        :key="i"
                        @click="goToCategory(i)"
                        class="transition-all duration-300 rounded-full"
                        :class="categoryIndex === i ? 'w-2.5 h-2.5 bg-slate-400' : 'w-2.5 h-2.5 bg-slate-200 hover:bg-slate-300'"
                    />
                </div>
            </section>

            <!-- FEATURED PRODUCTS SECTION -->
            <section class="py-20 bg-white">
                <div class="max-w-7xl mx-auto px-4">
                    <div class="text-center mb-10">
                        <h2 class="text-4xl md:text-5xl font-serif text-slate-900 mb-8">Featured Products</h2>
                        
                        <!-- Category Tabs -->
                        <div class="flex flex-wrap justify-center gap-3 mb-12">
                            <button 
                                v-for="cat in featuredCategories" 
                                :key="cat"
                                @click="activeFeaturedCategory = cat"
                                class="px-5 py-2.5 rounded-full text-sm font-bold transition-all border shadow-sm"
                                :class="activeFeaturedCategory === cat ? 'bg-brand-900 text-white border-brand-900 shadow-md shadow-brand-900/10' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300 hover:text-brand-900 hover:bg-slate-50'"
                            >
                                {{ cat }}
                            </button>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                        <div 
                            v-for="product in featuredProductsList" 
                            :key="product.id"
                            @click="router.visit('/products/' + product.id)"
                            class="bg-white rounded-3xl overflow-hidden group flex flex-col relative cursor-pointer border border-brand-100 hover:shadow-2xl hover:-translate-y-1 transition-all duration-500"
                        >
                            <!-- Badge -->
                            <div v-if="Number(product.offer_price) > 0" class="absolute top-4 right-4 z-10 bg-brand-900 text-white text-[10px] font-black px-2.5 py-1 rounded-full shadow-md shadow-brand-900/10">
                                {{ Math.round(((Number(product.price) - Number(product.offer_price)) / Number(product.price)) * 100) }}% OFF
                            </div>
                            
                            <!-- Image -->
                            <div class="aspect-square bg-[#F8F9FA] rounded-3xl overflow-hidden relative flex items-center justify-center p-4 mb-4">
                                <img 
                                    :src="(Array.isArray(product.image_url) ? product.image_url[0] : product.image_url) || 'https://placehold.co/400x400/F8F9FA/CBD5E1?text=' + product.name" 
                                    :alt="product.name"
                                    class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-500"
                                
                                />
                            </div>
                            
                            <!-- Details -->
                            <div class="px-2 pb-4 flex-grow flex flex-col">
                                <p class="text-[10px] font-black text-brand-500 uppercase tracking-[0.3em] mb-1">{{ product.brand }}</p>
                                <h3 class="text-[15px] font-medium text-slate-800 leading-snug mb-3 flex-grow">{{ product.name }}</h3>
                                
                                <!-- Price & Review Star -->
                                <div class="mt-2">
                                    <!-- Review Star -->
                                    <div class="h-5 flex items-center mb-1.5">
                                        <div v-if="product.reviews_count > 0" class="flex items-center gap-1 text-[#FFD700]">
                                            <template v-for="i in 5" :key="i">
                                                <svg class="w-3.5 h-3.5" :class="i <= Math.round(Number(product.reviews_avg_rating)) ? 'fill-current' : 'text-slate-200'" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                            </template>
                                            <span class="text-[10px] text-slate-400 ml-1">({{ product.reviews_count }})</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Price Section Horizontally -->
                                    <div class="flex items-center gap-2.5 mb-2">
                                        <template v-if="Number(product.offer_price) > 0">
                                            <span class="text-brand-900 text-lg font-black">৳{{ Number(product.offer_price) }}</span>
                                            <span class="text-slate-400 text-xs font-semibold line-through decoration-slate-300">৳{{ Number(product.price) }}</span>
                                        </template>
                                        <template v-else>
                                            <span class="text-brand-900 text-lg font-black">৳{{ Number(product.price) }}</span>
                                        </template>
                                    </div>
                                </div>
                                
                                <!-- Actions Underneath -->
                                <div class="flex items-center gap-3 mt-4">
                                    <button 
                                        @click.stop="addToCart(product)"
                                        class="flex-grow bg-brand-50 hover:bg-brand-100 text-brand-900 text-[11px] font-black py-3 rounded-xl transition-all border border-brand-200 text-center uppercase tracking-wider"
                                    >
                                        Add to Cart
                                    </button>
                                    <button 
                                        @click.stop="buyNow(product)"
                                        class="flex-grow bg-brand-900 hover:bg-brand-800 text-white text-[11px] font-black py-3 rounded-xl transition-all text-center shadow-md shadow-brand-900/10 uppercase tracking-wider"
                                    >
                                        Order
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-12 text-center">
                        <Link 
                            href="/products"
                            class="inline-block bg-white text-slate-800 border border-slate-300 px-10 py-2.5 rounded-full text-sm font-semibold hover:border-slate-400 hover:bg-slate-50 transition-colors"
                        >
                            See All
                        </Link>
                    </div>
                </div>
            </section>

            <!-- SPECIALIZED SERVICES SECTION -->
            <section class="py-20 bg-slate-50 border-t border-brand-100">
                <div class="max-w-7xl mx-auto px-4">
                    <div class="text-center mb-10">
                        <h2 class="text-4xl md:text-5xl font-serif text-slate-900 mb-8">Our spicalize services</h2>
                        
                        <!-- Category Tabs -->
                        <div class="flex flex-wrap justify-center gap-3 mb-12">
                            <button 
                                v-for="cat in featuredServiceCategories" 
                                :key="cat"
                                @click="activeFeaturedServiceCategory = cat"
                                class="px-5 py-2.5 rounded-full text-sm font-bold transition-all border shadow-sm"
                                :class="activeFeaturedServiceCategory === cat ? 'bg-brand-900 text-white border-brand-900 shadow-md shadow-brand-900/10' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300 hover:text-brand-900 hover:bg-white'"
                            >
                                {{ cat }}
                            </button>
                        </div>
                    </div>

                    <!-- Services Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                        <div 
                            v-for="service in featuredServicesList" 
                            :key="service.id"
                            @click="router.visit('/services/' + service.id)"
                            class="bg-white rounded-3xl overflow-hidden group flex flex-col relative cursor-pointer border border-brand-100 hover:shadow-2xl hover:-translate-y-1 transition-all duration-500"
                        >
                            <!-- Duration Badge -->
                            <div class="absolute top-4 right-4 z-10 bg-brand-900 text-white text-[10px] font-black px-2.5 py-1 rounded-full shadow-md shadow-brand-900/10 flex items-center gap-1">
                                <Clock :size="10" />
                                <span>{{ service.duration }}</span>
                            </div>
                            
                            <!-- Image -->
                            <div class="aspect-square bg-[#F8F9FA] rounded-3xl overflow-hidden relative flex items-center justify-center p-4 mb-4">
                                <img 
                                    :src="service.image_url || 'https://picsum.photos/400/400?random=' + service.id" 
                                    :alt="service.name"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 rounded-2xl"
                                />
                            </div>
                            
                            <!-- Details -->
                            <div class="px-2 pb-4 flex-grow flex flex-col">
                                <p class="text-[10px] font-black text-brand-500 uppercase tracking-[0.3em] mb-1">
                                    {{ service.category_item?.name || service.category }}
                                </p>
                                <h3 class="text-[15px] font-medium text-slate-800 leading-snug mb-2 flex-grow">{{ service.name }}</h3>
                                <p class="text-xs text-slate-400 line-clamp-2 mb-3 leading-relaxed">{{ service.description }}</p>
                                
                                <!-- Price Section -->
                                <div class="mt-auto">
                                    <div class="flex items-center gap-2 mb-4">
                                        <span class="text-brand-900 text-lg font-black">৳{{ Number(service.price) }}</span>
                                    </div>
                                </div>
                                
                                <!-- Actions Underneath -->
                                <div class="flex items-center gap-3 mt-auto">
                                    <button 
                                        @click.stop="router.visit('/services/' + service.id)"
                                        class="flex-grow bg-brand-50 hover:bg-brand-100 text-brand-900 text-[11px] font-black py-3 rounded-xl transition-all border border-brand-200 text-center uppercase tracking-wider"
                                    >
                                        Details
                                    </button>
                                    <button 
                                        @click.stop="openWhatsAppForService(service)"
                                        class="flex-grow bg-brand-900 hover:bg-brand-800 text-white text-[11px] font-black py-3 rounded-xl transition-all text-center shadow-md shadow-brand-900/10 uppercase tracking-wider"
                                    >
                                        Book Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-12 text-center">
                        <Link 
                            href="/services"
                            class="inline-block bg-white text-slate-800 border border-slate-300 px-10 py-2.5 rounded-full text-sm font-semibold hover:border-slate-400 hover:bg-slate-50 transition-colors"
                        >
                            See All Services
                        </Link>
                    </div>
                </div>
            </section>

            <!-- TEAM SECTION ON HOME PAGE -->
            <section class="py-24 bg-brand-50">
                <div class="max-w-7xl mx-auto px-4">
                    <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6">
                        <div class="max-w-xl">
                            <h2 class="text-sm font-bold text-brand-600 uppercase tracking-[0.3em] mb-4">The Visionaries</h2>
                            <h2 class="text-4xl md:text-5xl font-serif text-slate-900 leading-tight">Meet Our Master Artisans</h2>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
                        <div v-for="member in props.team" :key="member.id" class="group flex flex-col items-center text-center">
                            <div class="w-full aspect-[3/4] rounded-[2.5rem] overflow-hidden mb-8 relative border-2 border-transparent group-hover:border-brand-300 transition-all duration-500 shadow-lg group-hover:shadow-2xl">
                                <img 
                                    :src="member.image_url || 'https://placehold.co/600x800'" 
                                    :alt="member.name" 
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000" 
                                />
                            </div>
                            
                            <h3 class="text-2xl font-serif text-slate-900 font-bold mb-1">{{ member.name }}</h3>
                            <p class="text-brand-600 font-black text-[10px] uppercase tracking-[0.2em] mb-6">{{ member.role }}</p>
                            
                            <Link 
                                :href="`/about/team/${member.id}`" 
                                class="inline-flex items-center justify-center space-x-2 bg-brand-900 text-white px-8 py-3.5 rounded-full font-bold text-[10px] uppercase tracking-widest transition-all hover:bg-brand-800 hover:scale-105 active:scale-95 shadow-lg shadow-brand-900/20"
                            >
                                <span>View Profile</span>
                                <ArrowRight :size="14" />
                            </Link>
                        </div>
                    </div>
                    
                    <div class="mt-16 text-center">
                        <Link 
                            href="/about"
                            class="inline-flex items-center justify-center space-x-3 bg-brand-900 text-white px-12 py-5 rounded-full font-bold text-xs uppercase tracking-[0.3em] transition-all hover:bg-brand-800 hover:scale-105 active:scale-95 shadow-xl shadow-brand-900/20"
                        >
                            <span>view more expert</span>
                            <ArrowRight :size="20" />
                        </Link>
                    </div>
                </div>
            </section>

            <!-- GALLERY SECTION -->
            <section class="py-24 bg-white">
                <div class="max-w-7xl mx-auto px-4">
                    <div class="text-center mb-16">
                        <h2 class="text-sm font-bold text-brand-600 uppercase tracking-[0.2em] mb-4">Aesthetic Highlights</h2>
                        <p class="text-4xl md:text-5xl font-serif text-slate-900">Recent Masterpieces</p>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        <template v-if="props.gallery.length > 0">
                            <div 
                                v-for="item in props.gallery"
                                :key="item.id" 
                                @click="selectedImage = item.image_url"
                                class="group relative aspect-square rounded-[2rem] overflow-hidden bg-slate-100 border border-brand-100 cursor-zoom-in"
                            >
                                <img :src="item.image_url" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000" :alt="item.title" />
                                <div class="absolute inset-0 bg-brand-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center p-6 text-center">
                                    <Maximize2 class="text-white mb-2 w-6 h-6" />
                                    <p class="text-white font-serif italic text-lg">{{ item.title }}</p>
                                </div>
                            </div>
                        </template>
                        <template v-else>
                            <div class="col-span-full py-20 text-center bg-white rounded-[3rem] border-2 border-dashed border-brand-100">
                                <ImageIcon class="mx-auto text-brand-200 w-16 h-16 mb-4" />
                                <p class="text-slate-400 italic font-serif">Awaiting our next artistic transformation...</p>
                            </div>
                        </template>
                    </div>

                    <div class="mt-16 text-center">
                        <Link 
                            href="/gallery" 
                            class="inline-flex items-center justify-center space-x-3 bg-brand-900 text-white px-12 py-5 rounded-full font-bold text-xs uppercase tracking-[0.3em] transition-all hover:bg-brand-800 hover:scale-105 active:scale-95 shadow-xl shadow-brand-900/20"
                        >
                            <span>View Full Gallery</span>
                            <ArrowRight :size="20" />
                        </Link>
                    </div>
                </div>
            </section>

            <!-- Testimonials -->
            <section class="bg-brand-50 py-24 overflow-hidden">
                <div class="max-w-7xl mx-auto px-4">
                    <div class="text-center mb-16">
                        <h2 class="text-sm font-bold text-brand-600 uppercase tracking-[0.2em] mb-4">Testimonials</h2>
                        <p class="text-4xl md:text-5xl font-serif text-slate-900">Voices of Excellence</p>
                    </div>
                    <TestimonialSlider :testimonials="props.testimonials" />
                </div>
            </section>

            <!-- Lightbox Modal -->
            <div 
                v-if="selectedImage"
                class="fixed inset-0 z-[100] flex items-center justify-center p-4 md:p-12 bg-slate-950/90 backdrop-blur-xl animate-in fade-in duration-300"
                @click="selectedImage = null"
            >
                <button 
                    class="absolute top-8 right-8 z-[110] p-3 bg-white/10 hover:bg-white/20 text-white rounded-full transition-all hover:rotate-90"
                    @click.stop="selectedImage = null"
                >
                    <X :size="32" />
                </button>
                
                <div 
                    class="relative max-w-full max-h-full flex items-center justify-center animate-in zoom-in duration-300"
                    @click.stop
                >
                    <img 
                        :src="selectedImage" 
                        class="max-w-full max-h-[90vh] object-contain rounded-2xl shadow-2xl border-4 border-white/5" 
                        alt="Zoomed preview" 
                    />
                </div>
            </div>

            <!-- CTA Section -->
            <section class="py-24 relative overflow-hidden bg-brand-900 text-white">
                <div class="absolute inset-0 opacity-10">
                    <img src="https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&q=80&w=2000" class="w-full h-full object-cover" alt="" />
                </div>
                <div class="relative z-10 max-w-7xl mx-auto px-4 text-center">
                    <h2 class="text-4xl md:text-6xl font-serif mb-8">Ready to Radiate?</h2>
                    <p class="text-xl text-brand-200 mb-12 max-w-2xl mx-auto leading-relaxed">
                        Join the {{ APP_NAME }} family today. Experience premium care and professional products.
                    </p>
                    <div class="flex justify-center">
                        <Link href="/contact" class="bg-white text-brand-900 px-12 py-5 rounded-full text-lg font-bold hover:bg-brand-50 transition-all shadow-xl">
                            Contact Us
                        </Link>
                    </div>
                </div>
            </section>
        </div>
    </PublicLayout>
</template>

<style scoped>
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

/* Fade transition for hero slider */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 1s ease-in-out;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
