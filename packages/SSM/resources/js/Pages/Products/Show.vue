<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { ChevronLeft, ChevronRight, ShoppingBag, ArrowLeft, Tag, ShieldCheck, Truck, RefreshCw, MessageCircle } from 'lucide-vue-next';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const props = defineProps<{
    id: string;
    product: any;
    relatedProducts: any[];
}>();

import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
const page = usePage();
const settings = computed(() => page.props.settings || {});
const APP_NAME = computed(() => (settings.value as any).application_name || 'Bioshah.com');

const mousePos = ref({ x: 0, y: 0 });
const isHovering = ref(false);
const scrollRef = ref<HTMLElement | null>(null);

const WHATSAPP_NUMBER = "01911-879571";

const handleMouseMove = (e: MouseEvent) => {
    const target = e.currentTarget as HTMLElement;
    const { left, top, width, height } = target.getBoundingClientRect();
    const x = ((e.pageX - left - window.scrollX) / width) * 100;
    const y = ((e.pageY - top - window.scrollY) / height) * 100;
    mousePos.value = { x, y };
};

const scroll = (direction: 'left' | 'right') => {
    if (scrollRef.value) {
        const { scrollLeft, clientWidth } = scrollRef.value;
        const scrollTo = direction === 'left' ? scrollLeft - clientWidth : scrollLeft + clientWidth;
        scrollRef.value.scrollTo({ left: scrollTo, behavior: 'smooth' });
    }
};

const openWhatsApp = () => {
    if (!props.product) return;
    const message = encodeURIComponent(`Hello ${APP_NAME.value}! I'm interested in the product: ${props.product.name} (Brand: ${props.product.brand}, Price: ৳${props.product.price}). Is it available?`);
    window.open(`https://wa.me/${WHATSAPP_NUMBER.replace('-', '')}?text=${message}`, '_blank');
};

onMounted(() => {
    window.scrollTo(0, 0);
});
</script>

<template>
    <PublicLayout>
        <Head :title="product ? `${product.name} | Product Details` : 'Product Details'" />
        
        <div v-if="!props.product" class="min-h-screen flex flex-col items-center justify-center p-4">
            <h2 class="text-3xl font-serif mb-4">Product Not Found</h2>
            <Link href="/products" class="text-brand-900 font-bold flex items-center space-x-2">
                <ArrowLeft :size="20" />
                <span>Back to Apothecary</span>
            </Link>
        </div>

        <div v-else class="bg-white min-h-screen">
            <!-- Breadcrumb / Back Button -->
            <div class="max-w-7xl mx-auto px-4 py-8">
                <Link href="/products" class="inline-flex items-center space-x-2 text-slate-400 hover:text-brand-900 transition-colors font-bold text-sm uppercase tracking-widest">
                    <ArrowLeft :size="16" />
                    <span>Back to Collection</span>
                </Link>
            </div>

            <div class="max-w-7xl mx-auto px-4 pb-12">
                <!-- Top Section -->
                <div class="grid lg:grid-cols-2 gap-16 items-start mb-16">
                    <!-- Image Section with Zoom -->
                    <div 
                        class="relative aspect-square rounded-[3rem] overflow-hidden bg-brand-50 cursor-crosshair border border-brand-100 group"
                        @mousemove="handleMouseMove"
                        @mouseenter="isHovering = true"
                        @mouseleave="isHovering = false"
                    >
                        <img 
                            :src="props.product.image_url" 
                            :alt="props.product.name" 
                            :class="['w-full h-full object-cover transition-transform duration-200', isHovering ? 'scale-[2]' : 'scale-100']"
                            :style="isHovering ? { transformOrigin: `${mousePos.x}% ${mousePos.y}%` } : {}"
                        />
                        <div class="absolute bottom-6 right-6 bg-white/90 backdrop-blur px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm pointer-events-none group-hover:opacity-0 transition-opacity">
                            Hover to Zoom
                        </div>
                    </div>

                    <!-- Details Section -->
                    <div class="space-y-10 py-4">
                        <div>
                            <p class="text-brand-600 font-black uppercase tracking-[0.3em] text-xs mb-3">{{ props.product.brand }}</p>
                            <h1 class="text-5xl font-serif text-slate-900 mb-6 leading-tight">{{ props.product.name }}</h1>
                            <div class="flex items-center space-x-4">
                                <p class="text-5xl font-bold text-brand-900">৳{{ props.product.price }}</p>
                                <div class="h-8 w-px bg-slate-200" />
                                <span class="bg-brand-50 text-brand-700 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest">
                                    {{ props.product.category }}
                                </span>
                            </div>
                        </div>

                        <!-- CTA Section -->
                        <div class="space-y-6 pt-4">
                            <button 
                                @click="openWhatsApp"
                                class="flex items-center justify-center space-x-4 w-full bg-[#25D366] text-white py-6 rounded-[2rem] text-xl font-bold shadow-2xl shadow-green-500/20 hover:scale-[1.02] active:scale-95 transition-all"
                            >
                                <MessageCircle :size="28" />
                                <span>Purchase via WhatsApp</span>
                            </button>
                            <p class="text-center text-slate-400 text-sm italic">Connect with us directly for availability and delivery details.</p>
                        </div>

                        <!-- Quality Seals -->
                        <div class="grid grid-cols-3 gap-6 pt-10 border-t border-slate-100">
                            <div class="flex flex-col items-center text-center space-y-3">
                                <div class="p-4 bg-brand-50 rounded-[1.5rem] text-brand-900 shadow-sm"><ShieldCheck :size="24" /></div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-slate-500">100% Authentic</p>
                            </div>
                            <div class="flex flex-col items-center text-center space-y-3">
                                <div class="p-4 bg-brand-50 rounded-[1.5rem] text-brand-900 shadow-sm"><Truck :size="24" /></div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-slate-500">Fast Shipping</p>
                            </div>
                            <div class="flex flex-col items-center text-center space-y-3">
                                <div class="p-4 bg-brand-50 rounded-[1.5rem] text-brand-900 shadow-sm"><RefreshCw :size="24" /></div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-slate-500">Support 24/7</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Full Width Description Section -->
                <div class="bg-slate-50/50 rounded-[3rem] p-12 md:p-16 border border-slate-100">
                    <div class="max-w-4xl">
                        <h4 class="text-xs font-black text-brand-600 uppercase tracking-[0.3em] mb-6">Product Insight</h4>
                        <h2 class="text-3xl font-serif text-slate-900 mb-8">Full Description & Benefits</h2>
                        <div class="prose prose-lg prose-slate max-w-none">
                            <p class="text-slate-600 leading-relaxed text-lg whitespace-pre-wrap">
                                {{ product.description || "Our premium collection represents the pinnacle of beauty innovation. This product is carefully curated to meet the highest standards of professional care. \n\nWe select our apothecary items based on their active ingredients, ethical sourcing, and proven results. Whether you are maintaining a salon-fresh look at home or seeking specific therapeutic benefits, this essential item delivers consistent excellence." }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products Slider -->
            <section v-if="props.relatedProducts.length > 0" class="py-24 bg-brand-50 overflow-hidden">
                <div class="max-w-7xl mx-auto px-4 mb-12 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-bold text-brand-600 uppercase tracking-[0.2em] mb-4">Curated Pairings</h2>
                        <p class="text-4xl md:text-5xl font-serif text-slate-900">You May Also Love</p>
                    </div>
                    <div class="flex space-x-4">
                        <button @click="scroll('left')" class="p-4 rounded-full border border-slate-200 hover:bg-white text-slate-400 hover:text-brand-900 transition-all">
                            <ChevronLeft :size="24" />
                        </button>
                        <button @click="scroll('right')" class="p-4 rounded-full border border-slate-200 hover:bg-white text-slate-400 hover:text-brand-900 transition-all">
                            <ChevronRight :size="24" />
                        </button>
                    </div>
                </div>

                <div 
                    ref="scrollRef"
                    class="flex overflow-x-auto gap-8 px-[max(1rem,calc((100vw-80rem)/2))] no-scrollbar scroll-smooth pb-12"
                >
                    <Link 
                        v-for="rel in props.relatedProducts"
                        :key="rel.id" 
                        :href="`/products/${rel.id}`"
                        class="min-w-[300px] md:min-w-[350px] group bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 border border-brand-100 flex flex-col"
                    >
                        <div class="h-64 overflow-hidden relative">
                            <img :src="rel.image_url" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" :alt="rel.name" />
                            <div class="absolute top-6 left-6 bg-white/90 backdrop-blur p-2.5 rounded-xl text-brand-900 shadow-md">
                                <Tag :size="18" />
                            </div>
                        </div>
                        <div class="p-8 flex-grow">
                            <p class="text-[10px] font-black text-brand-500 uppercase tracking-widest mb-2">{{ rel.brand }}</p>
                            <h3 class="text-xl font-serif text-slate-900 mb-4 truncate">{{ rel.name }}</h3>
                            <div class="flex items-center justify-between mt-auto">
                                <p class="text-2xl font-bold text-brand-900">৳{{ rel.price }}</p>
                                <div class="text-brand-900 p-2 bg-brand-50 rounded-full">
                                    <ChevronRight :size="20" />
                                </div>
                            </div>
                        </div>
                    </Link>
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
