<script setup lang="ts">
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import { Link, Head, usePage } from '@inertiajs/vue3';
import { Scissors, ArrowRight, ShoppingBag, Image as ImageIcon, ChevronLeft, ChevronRight, Tag, Clock, X, Maximize2 } from 'lucide-vue-next';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import TestimonialSlider from '@/Components/TestimonialSlider.vue';

const props = defineProps<{
    testimonials: any[];
    gallery: any[];
    products: any[];
    services: any[];
    team: any[];
}>();

const page = usePage();
const settings = computed(() => page.props.settings || {});

const selectedImage = ref<string | null>(null);

const LOGO_URL = computed(() => (settings.value as any).company_logo ? `/storage/${(settings.value as any).company_logo}` : "https://via.placeholder.com/150");
const HERO_IMAGE = computed(() => {
    return (settings.value as any).landing_banner 
        ? `/storage/${(settings.value as any).landing_banner}`
        : "https://images.unsplash.com/photo-1560066984-138dadb4c035?auto=format&fit=crop&q=80&w=2000";
});

// --- SERVICE CAROUSEL ---
const serviceScrollRef = ref<HTMLElement | null>(null);
const serviceIndex = ref(0);

const serviceItemWidth = computed(() => {
    if (!serviceScrollRef.value) return 408; // 400px + 8px gap
    const firstItem = serviceScrollRef.value.querySelector(':scope > div') as HTMLElement | null;
    if (!firstItem) return 408;
    return firstItem.offsetWidth + 32; // 32px = gap-8
});

const serviceTotalItems = computed(() => props.services.length + 1); // +1 for "view all" card

const scrollService = (dir: 'left' | 'right') => {
    if (!serviceScrollRef.value) return;
    if (dir === 'right' && serviceIndex.value < serviceTotalItems.value - 1) {
        serviceIndex.value++;
    } else if (dir === 'left' && serviceIndex.value > 0) {
        serviceIndex.value--;
    }
    serviceScrollRef.value.scrollTo({ left: serviceIndex.value * serviceItemWidth.value, behavior: 'smooth' });
};

const goToService = (idx: number) => {
    if (!serviceScrollRef.value) return;
    serviceIndex.value = idx;
    serviceScrollRef.value.scrollTo({ left: idx * serviceItemWidth.value, behavior: 'smooth' });
};

const onServiceScroll = () => {
    if (!serviceScrollRef.value) return;
    const idx = Math.round(serviceScrollRef.value.scrollLeft / serviceItemWidth.value);
    serviceIndex.value = idx;
};

// --- PRODUCT CAROUSEL ---
const productScrollRef = ref<HTMLElement | null>(null);
const productIndex = ref(0);

const productItemWidth = computed(() => {
    if (!productScrollRef.value) return 358; // 350px + 8px gap
    const firstItem = productScrollRef.value.querySelector(':scope > div') as HTMLElement | null;
    if (!firstItem) return 358;
    return firstItem.offsetWidth + 32;
});

const productTotalItems = computed(() => props.products.length + 1);

const scrollProduct = (dir: 'left' | 'right') => {
    if (!productScrollRef.value) return;
    if (dir === 'right' && productIndex.value < productTotalItems.value - 1) {
        productIndex.value++;
    } else if (dir === 'left' && productIndex.value > 0) {
        productIndex.value--;
    }
    productScrollRef.value.scrollTo({ left: productIndex.value * productItemWidth.value, behavior: 'smooth' });
};

const goToProduct = (idx: number) => {
    if (!productScrollRef.value) return;
    productIndex.value = idx;
    productScrollRef.value.scrollTo({ left: idx * productItemWidth.value, behavior: 'smooth' });
};

const onProductScroll = () => {
    if (!productScrollRef.value) return;
    const idx = Math.round(productScrollRef.value.scrollLeft / productItemWidth.value);
    productIndex.value = idx;
};

// Auto-play timers
let serviceTimer: ReturnType<typeof setInterval> | null = null;
let productTimer: ReturnType<typeof setInterval> | null = null;

onMounted(() => {
    serviceTimer = setInterval(() => scrollService('right'), 4000);
    productTimer = setInterval(() => scrollProduct('right'), 3500);
    serviceScrollRef.value?.addEventListener('scroll', onServiceScroll);
    productScrollRef.value?.addEventListener('scroll', onProductScroll);
});

onBeforeUnmount(() => {
    if (serviceTimer) clearInterval(serviceTimer);
    if (productTimer) clearInterval(productTimer);
    serviceScrollRef.value?.removeEventListener('scroll', onServiceScroll);
    productScrollRef.value?.removeEventListener('scroll', onProductScroll);
});
</script>

<template>
    <PublicLayout>
        <Head title="Home | Premium Salon & Spa" />
        
        <div class="overflow-x-hidden">
            <!-- Hero Section -->
            <section class="relative h-[90vh] flex items-center">
                <div class="absolute inset-0 z-0">
                    <img 
                        :src="HERO_IMAGE" 
                        class="w-full h-full object-cover brightness-[0.65]"
                        alt="Style Studio Mart Hero"
                    />
                    <div class="absolute inset-0 bg-gradient-to-r from-brand-900/60 via-brand-900/20 to-transparent" />
                </div>
                
                <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                    <div class="max-w-2xl animate-in slide-in-from-left duration-1000">
                        <div class="flex items-center space-x-3 text-brand-200 mb-6 tracking-widest uppercase text-sm font-semibold bg-white/10 backdrop-blur-md w-fit px-5 py-2.5 rounded-2xl border border-white/20">
                            <img :src="LOGO_URL" class="h-6 w-6 rounded-lg object-cover" alt="" />
                            <span>Style Studio Mart Experience</span>
                        </div>
                        <h1 class="text-6xl md:text-8xl font-serif text-white leading-tight mb-8">
                            Where Beauty <br /> Meets <span class="italic">Perfection</span>
                        </h1>
                        <p class="text-xl text-brand-50/90 mb-10 max-w-lg leading-relaxed">
                            Step into a world of curated beauty experiences and timeless elegance at Style Studio Mart. 
                            Discover premium products and artistry.
                        </p>
                        <div class="flex">
                            <Link href="/products" class="flex items-center justify-center space-x-2 bg-white text-brand-900 px-10 py-5 rounded-full text-lg font-bold hover:bg-brand-50 transition-all transform hover:scale-105 shadow-xl">
                                <ShoppingBag :size="20" />
                                <span>Shop Products</span>
                            </Link>
                        </div>
                    </div>
                </div>
            </section>

            <!-- DYNAMIC SERVICES CAROUSEL -->
            <section class="py-24 bg-brand-50 overflow-hidden">
                <div class="max-w-7xl mx-auto px-4 mb-12 flex items-center justify-between">
                    <div class="max-w-xl">
                        <h2 class="text-sm font-bold text-brand-600 uppercase tracking-[0.2em] mb-4">Artisanal Rituals</h2>
                        <p class="text-4xl md:text-5xl font-serif text-slate-900">Our Signature Services</p>
                    </div>
                    <div class="flex space-x-4">
                        <button @click="scrollService('left')" class="p-4 rounded-full border border-slate-200 hover:bg-white text-slate-400 hover:text-brand-900 transition-all shadow-sm" :disabled="serviceIndex === 0" :class="{ 'opacity-40 cursor-not-allowed': serviceIndex === 0 }">
                            <ChevronLeft :size="24" />
                        </button>
                        <button @click="scrollService('right')" class="p-4 rounded-full border border-slate-200 hover:bg-white text-slate-400 hover:text-brand-900 transition-all shadow-sm" :disabled="serviceIndex >= serviceTotalItems - 1" :class="{ 'opacity-40 cursor-not-allowed': serviceIndex >= serviceTotalItems - 1 }">
                            <ChevronRight :size="24" />
                        </button>
                    </div>
                </div>

                <div 
                    ref="serviceScrollRef"
                    class="flex overflow-x-auto gap-8 px-[max(1rem,calc((100vw-80rem)/2))] no-scrollbar scroll-smooth pb-4"
                >
                    <div 
                        v-for="service in props.services"
                        :key="service.id" 
                        class="min-w-[320px] md:min-w-[400px] bg-white rounded-[2rem] overflow-hidden group hover:shadow-2xl transition-all duration-500 border border-brand-100 flex flex-col"
                    >
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
                            <div class="flex items-center space-x-2 text-brand-500 font-bold text-xs uppercase tracking-widest mb-4">
                                <span>{{ service.category }}</span>
                                <span>•</span>
                                <div class="flex items-center space-x-1">
                                    <Clock :size="14" />
                                    <span>{{ service.duration }}</span>
                                </div>
                            </div>
                            <h3 class="text-2xl font-serif text-slate-900 mb-4 line-clamp-1">{{ service.name }}</h3>
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
                    
                    <div class="min-w-[320px] md:min-w-[400px] flex flex-col items-center justify-center p-12 bg-white rounded-[2rem] border-2 border-dashed border-brand-200 group hover:border-brand-900 transition-colors">
                        <Link href="/services" class="text-center group flex flex-col items-center">
                            <div class="w-20 h-20 bg-brand-50 rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm transition-transform group-hover:scale-110 group-hover:bg-brand-900 group-hover:text-white">
                                <Scissors :size="28" />
                            </div>
                            <h3 class="text-2xl font-serif text-slate-900 mb-2">Service Menu</h3>
                            <p class="text-slate-500 mb-6 text-sm">View all artisanal rituals</p>
                            <div class="flex items-center space-x-2 text-brand-900 font-bold uppercase text-xs tracking-[0.2em]">
                                <span>Full Menu</span>
                                <ArrowRight :size="16" />
                            </div>
                        </Link>
                    </div>
                </div>

                <!-- Dot Indicators -->
                <div class="flex justify-center gap-2 mt-8">
                    <button
                        v-for="(_, i) in serviceTotalItems"
                        :key="i"
                        @click="goToService(i)"
                        class="transition-all duration-300 rounded-full"
                        :class="serviceIndex === i ? 'w-8 h-2.5 bg-brand-900' : 'w-2.5 h-2.5 bg-slate-300 hover:bg-brand-400'"
                    />
                </div>

                <!-- View More Btn -->
                <div class="mt-10 text-center">
                    <Link 
                        href="/services"
                        class="inline-flex items-center justify-center space-x-3 bg-brand-900 text-white px-12 py-5 rounded-full font-bold text-xs uppercase tracking-[0.3em] transition-all hover:bg-brand-800 hover:scale-105 active:scale-95 shadow-xl shadow-brand-900/20"
                    >
                        <span>view more service</span>
                        <ArrowRight :size="20" />
                    </Link>
                </div>
            </section>

            <!-- PRODUCT CAROUSEL SECTION -->
            <section class="py-24 bg-white overflow-hidden">
                <div class="max-w-7xl mx-auto px-4 mb-12 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-bold text-brand-600 uppercase tracking-[0.2em] mb-4">Elite Apothecary</h2>
                        <p class="text-4xl md:text-5xl font-serif text-slate-900">Featured Essentials</p>
                    </div>
                    <div class="flex space-x-4">
                        <button @click="scrollProduct('left')" class="p-4 rounded-full border border-slate-200 hover:bg-slate-50 text-slate-400 hover:text-brand-900 transition-all" :disabled="productIndex === 0" :class="{ 'opacity-40 cursor-not-allowed': productIndex === 0 }">
                            <ChevronLeft :size="24" />
                        </button>
                        <button @click="scrollProduct('right')" class="p-4 rounded-full border border-slate-200 hover:bg-slate-50 text-slate-400 hover:text-brand-900 transition-all" :disabled="productIndex >= productTotalItems - 1" :class="{ 'opacity-40 cursor-not-allowed': productIndex >= productTotalItems - 1 }">
                            <ChevronRight :size="24" />
                        </button>
                    </div>
                </div>

                <div 
                    ref="productScrollRef"
                    class="flex overflow-x-auto gap-8 px-[max(1rem,calc((100vw-80rem)/2))] no-scrollbar scroll-smooth pb-4"
                >
                    <div 
                        v-for="product in props.products"
                        :key="product.id" 
                        class="min-w-[300px] md:min-w-[350px] group bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 border border-brand-100 flex flex-col"
                    >
                        <div class="h-64 overflow-hidden relative">
                            <img :src="product.image_url || 'https://images.unsplash.com/photo-1596462502278-27bfad450526?auto=format&fit=crop&q=80&w=800'" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" :alt="product.name" />
                            <div class="absolute top-6 left-6 bg-white/90 backdrop-blur p-2.5 rounded-xl text-brand-900 shadow-md">
                                <Tag :size="18" />
                            </div>
                        </div>
                        <div class="p-8 flex-grow flex flex-col items-center text-center">
                            <p class="text-[10px] font-black text-brand-500 uppercase tracking-widest mb-2">{{ product.brand }}</p>
                            <h3 class="text-xl font-serif text-slate-900 mb-4 truncate w-full">{{ product.name }}</h3>
                            <p class="text-2xl font-bold text-brand-900 mb-6">৳{{ product.price }}</p>
                            
                            <Link 
                                :href="`/products/${product.id}`" 
                                class="inline-flex items-center justify-center space-x-2 bg-brand-900 text-white px-8 py-3.5 rounded-full font-bold text-[10px] uppercase tracking-widest transition-all hover:bg-brand-800 hover:scale-105 active:scale-95 shadow-lg shadow-brand-900/20"
                            >
                                <span>Explore Item</span>
                                <ArrowRight :size="14" />
                            </Link>
                        </div>
                    </div>
                    
                    <div class="min-w-[300px] md:min-w-[350px] flex flex-col items-center justify-center p-12 bg-brand-50 rounded-3xl border-2 border-dashed border-brand-200 group hover:border-brand-900 transition-colors">
                        <Link href="/products" class="text-center group flex flex-col items-center">
                            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-xl transition-transform group-hover:scale-110 group-hover:bg-brand-900 group-hover:text-white">
                                <ShoppingBag :size="28" />
                            </div>
                            <h3 class="text-2xl font-serif text-slate-900 mb-2">Explore All</h3>
                            <p class="text-slate-500 mb-6 text-sm">Discover our full curated collection</p>
                            <div class="flex items-center space-x-2 text-brand-900 font-bold uppercase text-xs tracking-[0.2em]">
                                <span>Visit Apothecary</span>
                                <ArrowRight :size="16" />
                            </div>
                        </Link>
                    </div>
                </div>

                <!-- Dot Indicators -->
                <div class="flex justify-center gap-2 mt-8">
                    <button
                        v-for="(_, i) in productTotalItems"
                        :key="i"
                        @click="goToProduct(i)"
                        class="transition-all duration-300 rounded-full"
                        :class="productIndex === i ? 'w-8 h-2.5 bg-brand-900' : 'w-2.5 h-2.5 bg-slate-300 hover:bg-brand-400'"
                    />
                </div>
                
                <div class="mt-10 text-center">
                    <Link 
                        href="/products"
                        class="inline-flex items-center justify-center space-x-3 bg-brand-900 text-white px-12 py-5 rounded-full font-bold text-xs uppercase tracking-[0.3em] transition-all hover:bg-brand-800 hover:scale-105 active:scale-95 shadow-xl shadow-brand-900/20"
                    >
                        <span>view more product</span>
                        <ArrowRight :size="20" />
                    </Link>
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
                                    :src="member.image_url || 'https://via.placeholder.com/600x800'" 
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
                        Join the Style Studio Mart family today. Experience premium care and professional products.
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
</style>
