<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { ShoppingCart, ChevronLeft, ChevronRight, ShoppingBag, ArrowLeft, Tag, ShieldCheck, Truck, RefreshCw, MessageCircle, X, CheckCircle2, Star, User } from 'lucide-vue-next';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { cart } from '@/CartStore';
import { router } from '@inertiajs/vue3';

const props = defineProps<{
    id: string;
    product: any;
    reviews: any[];
    relatedProducts: any[];
}>();

const page = usePage();
const settings = computed(() => page.props.settings || {});
const APP_NAME = computed(() => (settings.value as any).application_name || "Bioshah.com");

const mousePos = ref({ x: 0, y: 0 });
const isHovering = ref(false);
const scrollRef = ref<HTMLElement | null>(null);
const activeImageIndex = ref(0);
const showAddedNotification = ref(false);

const allImages = computed(() => {
    const raw = props.product?.image_url;
    if (Array.isArray(raw) && raw.length > 0) return raw;
    if (typeof raw === 'string' && raw) return [raw];
    return [];
});

const activeImage = computed(() => allImages.value[activeImageIndex.value] ?? null);

const addToCart = (productObj) => {
    const finalProduct = (productObj && productObj.id) ? productObj : props.product;
    cart.addItem(finalProduct);
    showAddedNotification.value = true;
    setTimeout(() => {
        showAddedNotification.value = false;
    }, 3000);
};

const buyNow = (productObj) => {
    const finalProduct = (productObj && productObj.id) ? productObj : props.product;
    cart.addItem(finalProduct);
    router.visit('/checkout');
};

const openWhatsApp = () => {
    if (!props.product) return;
    const message = encodeURIComponent(`Hello ${APP_NAME.value}! I'm interested in the product: ${props.product.name} (Brand: ${props.product.brand}, Price: ৳${props.product.price}). Is it available?`);
    window.open(`https://wa.me/${WHATSAPP_NUMBER.replace('-', '')}?text=${message}`, '_blank');
};

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

onMounted(() => {
    window.scrollTo(0, 0);
});

// Description Read More Logic
const isDescriptionExpanded = ref(false);
const descriptionText = computed(() => {
    return props.product.description || "Our premium collection represents the pinnacle of beauty innovation. This product is carefully curated to meet the highest standards of professional care. \n\nWe select our apothecary items based on their active ingredients, ethical sourcing, and proven results. Whether you are maintaining a salon-fresh look at home or seeking specific therapeutic benefits, this essential item delivers consistent excellence.";
});
const TRUNCATE_LENGTH = 250;
const shouldTruncate = computed(() => descriptionText.value.length > TRUNCATE_LENGTH);
const displayedDescription = computed(() => {
    if (!shouldTruncate.value || isDescriptionExpanded.value) {
        return descriptionText.value;
    }
    return descriptionText.value.substring(0, TRUNCATE_LENGTH) + '...';
});

// Review form
const reviewForm = useForm({
    name: '',
    mobile: '',
    age: '',
    rating: 5,
    review_text: ''
});

const submitReview = () => {
    reviewForm.post(`/products/${props.id}/reviews`, {
        preserveScroll: true,
        onSuccess: () => {
            reviewForm.reset();
        }
    });
};

// Compute review stats
const reviewStats = computed(() => {
    const total = props.reviews?.length || 0;
    if (total === 0) return { avg: 0, total: 0, breakdown: { 5:0, 4:0, 3:0, 2:0, 1:0 } };
    
    let sum = 0;
    const breakdown = { 5:0, 4:0, 3:0, 2:0, 1:0 } as Record<number, number>;
    
    props.reviews.forEach(r => {
        sum += r.rating;
        if (breakdown[r.rating] !== undefined) breakdown[r.rating]++;
    });
    
    return {
        avg: (sum / total).toFixed(1),
        total,
        breakdown
    };
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
                    <!-- Image Section with Zoom + Thumbnail Gallery -->
                    <div class="flex flex-col gap-4">
                        <!-- Main Image -->
                        <div 
                            class="relative aspect-square rounded-[3rem] overflow-hidden bg-brand-50 cursor-crosshair border border-brand-100 group"
                            @mousemove="handleMouseMove"
                            @mouseenter="isHovering = true"
                            @mouseleave="isHovering = false"
                        >
                            <img 
                                v-if="activeImage"
                                :src="activeImage" 
                                :alt="props.product.name" 
                                :class="['w-full h-full object-cover transition-transform duration-200', isHovering ? 'scale-[2]' : 'scale-100']"
                                :style="isHovering ? { transformOrigin: `${mousePos.x}% ${mousePos.y}%` } : {}"
                            />
                            <div v-else class="w-full h-full flex items-center justify-center text-slate-300">
                                <span class="text-lg">No Image</span>
                            </div>
                            <div class="absolute bottom-6 right-6 bg-white/90 backdrop-blur px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm pointer-events-none group-hover:opacity-0 transition-opacity">
                                Hover to Zoom
                            </div>
                        </div>

                        <!-- Thumbnails -->
                        <div v-if="allImages.length > 1" class="flex gap-3 flex-wrap">
                            <button
                                v-for="(img, idx) in allImages"
                                :key="idx"
                                @click="activeImageIndex = idx"
                                :class="[
                                    'w-20 h-20 rounded-2xl overflow-hidden border-2 transition-all duration-200 focus:outline-none',
                                    activeImageIndex === idx
                                        ? 'border-brand-900 shadow-md scale-105'
                                        : 'border-transparent opacity-60 hover:opacity-100 hover:border-brand-300'
                                ]"
                            >
                                <img :src="img" :alt="`${props.product.name} image ${idx + 1}`" class="w-full h-full object-cover" />
                            </button>
                        </div>
                    </div>

                    <!-- Details Section -->
                    <div class="space-y-10 py-4">
                        <div>
                            <p class="text-brand-600 font-black uppercase tracking-[0.3em] text-xs mb-3">{{ props.product.brand }}</p>
                            <h1 class="text-5xl font-serif text-slate-900 mb-6 leading-tight">{{ props.product.name }}</h1>
                            <div class="flex items-center flex-wrap gap-4">
                                <template v-if="Number(props.product.offer_price) > 0">
                                    <p class="text-5xl font-bold text-brand-900">৳{{ props.product.offer_price }}</p>
                                    <p class="text-2xl text-slate-400 line-through">৳{{ props.product.price }}</p>
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold">
                                        {{ Math.round(((Number(props.product.price) - Number(props.product.offer_price)) / Number(props.product.price)) * 100) }}% OFF
                                    </span>
                                </template>
                                <template v-else>
                                    <p class="text-5xl font-bold text-brand-900">৳{{ props.product.price }}</p>
                                </template>
                                <div class="h-8 w-px bg-slate-200" />
                                <span class="bg-brand-50 text-brand-700 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest">
                                    {{ props.product.category }}
                                </span>
                            </div>
                        </div>

                        <!-- CTA Section -->
                        <div class="space-y-4 pt-4">
                            <div class="flex gap-4">
                                <button 
                                    @click="addToCart"
                                    class="flex-1 flex items-center justify-center space-x-3 bg-brand-50 text-brand-900 py-6 rounded-[2rem] text-xl font-bold border-2 border-brand-200 hover:bg-brand-100 active:scale-95 transition-all"
                                >
                                    <ShoppingCart :size="24" />
                                    <span>Add to Cart</span>
                                </button>
                                <button 
                                    @click="buyNow"
                                    class="flex-[1.5] flex items-center justify-center space-x-4 bg-brand-900 text-white py-6 rounded-[2rem] text-xl font-bold shadow-2xl shadow-brand-900/20 hover:scale-[1.02] active:scale-95 transition-all"
                                >
                                    <ShoppingBag :size="28" />
                                    <span>Buy Now</span>
                                </button>
                            </div>
                            <button 
                                @click="openWhatsApp"
                                class="flex items-center justify-center space-x-4 w-full bg-[#25D366]/10 text-[#25D366] py-5 rounded-[2rem] text-lg font-bold hover:bg-[#25D366]/20 transition-all"
                            >
                                <MessageCircle :size="24" />
                                <span>Consult via WhatsApp</span>
                            </button>
                            <p class="text-center text-slate-400 text-sm italic">Secure direct ordering or professional consultation.</p>
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
                            <p class="text-slate-600 leading-relaxed text-lg whitespace-pre-wrap">{{ displayedDescription }}</p>
                            <button 
                                v-if="shouldTruncate" 
                                @click="isDescriptionExpanded = !isDescriptionExpanded"
                                class="mt-6 text-brand-600 font-bold hover:text-brand-900 transition-colors uppercase text-xs tracking-[0.2em] flex items-center gap-2 group"
                            >
                                <span>{{ isDescriptionExpanded ? 'Read Less' : 'Read More' }}</span>
                                <ChevronRight :size="16" class="transition-transform duration-300" :class="isDescriptionExpanded ? '-rotate-90' : 'group-hover:translate-x-1'" />
                            </button>
                        </div>
                    </div>
                </div>

                <!-- CUSTOMER REVIEWS SECTION -->
                <div class="mt-16 bg-white rounded-[3rem] border border-slate-100 p-8 md:p-16">
                    <div class="grid lg:grid-cols-2 gap-16">
                        <!-- Left: Stats -->
                        <div>
                            <h2 class="text-3xl font-serif text-slate-900 mb-2">Customer reviews</h2>
                            <p class="text-slate-500 mb-6">Real feedback from verified customers</p>
                            
                            <div class="flex items-center gap-4 mb-4">
                                <div class="flex items-center gap-1 text-[#FFD700]">
                                    <template v-for="i in 5" :key="i">
                                        <Star :size="24" :class="i <= Number(reviewStats.avg) ? 'fill-current' : 'text-slate-200'" />
                                    </template>
                                </div>
                                <span class="text-2xl font-bold text-slate-900">{{ reviewStats.avg }} out of 5</span>
                            </div>
                            <p class="text-slate-500 mb-8">{{ reviewStats.total }} global ratings</p>
                            
                            <div class="space-y-4">
                                <div v-for="star in [5,4,3,2,1]" :key="star" class="flex items-center gap-4">
                                    <span class="w-12 text-sm font-medium text-slate-600">{{ star }} star</span>
                                    <div class="flex-grow h-3 bg-slate-100 rounded-full overflow-hidden">
                                        <div 
                                            class="h-full bg-brand-500 rounded-full"
                                            :style="{ width: reviewStats.total ? (reviewStats.breakdown[star] / reviewStats.total * 100) + '%' : '0%' }"
                                        ></div>
                                    </div>
                                    <span class="w-12 text-sm text-slate-500 text-right">
                                        {{ reviewStats.total ? Math.round(reviewStats.breakdown[star] / reviewStats.total * 100) : 0 }}%
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Form -->
                        <div class="bg-brand-50 rounded-3xl p-8">
                            <h3 class="text-2xl font-serif text-slate-900 mb-2">Review this product</h3>
                            <p class="text-slate-500 mb-6">Share your thoughts with other customers</p>
                            
                            <form @submit.prevent="submitReview" class="space-y-5">
                                <div class="grid grid-cols-2 gap-5">
                                    <div class="col-span-2 sm:col-span-1">
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Overall rating</label>
                                        <div class="flex items-center gap-2">
                                            <button 
                                                type="button"
                                                v-for="i in 5" 
                                                :key="i"
                                                @click="reviewForm.rating = i"
                                                class="focus:outline-none transition-colors"
                                                :class="i <= reviewForm.rating ? 'text-[#FFD700]' : 'text-slate-300 hover:text-slate-400'"
                                            >
                                                <Star :size="24" :class="i <= reviewForm.rating ? 'fill-current' : ''" />
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-span-2 sm:col-span-1">
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Your Name</label>
                                        <input type="text" v-model="reviewForm.name" required placeholder="Enter your name" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none" />
                                    </div>
                                    <div class="col-span-2 sm:col-span-1">
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Mobile Number</label>
                                        <input type="text" v-model="reviewForm.mobile" placeholder="017xxxxxxxx" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none" />
                                    </div>
                                    <div class="col-span-2 sm:col-span-1">
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Age</label>
                                        <input type="number" v-model="reviewForm.age" placeholder="25" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none" />
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Add a written review</label>
                                        <textarea v-model="reviewForm.review_text" rows="3" placeholder="What did you like or dislike?" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none resize-none"></textarea>
                                    </div>
                                </div>
                                <button 
                                    type="submit"
                                    :disabled="reviewForm.processing"
                                    class="w-full bg-brand-900 text-white font-bold py-4 rounded-xl hover:bg-brand-800 transition-colors disabled:opacity-50"
                                >
                                    Submit Review
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Customers real experiences -->
                <div v-if="props.reviews && props.reviews.length > 0" class="mt-20 mb-8">
                    <h2 class="text-3xl md:text-4xl font-serif text-center text-slate-900 mb-12">Customers real experiences</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div 
                            v-for="review in props.reviews" 
                            :key="review.id"
                            class="bg-brand-50/50 border border-brand-100 rounded-3xl p-8 hover:shadow-lg transition-all"
                        >
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 bg-slate-200 rounded-full flex items-center justify-center text-slate-500">
                                    <User :size="24" />
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900">{{ review.name }}<span v-if="review.age">, {{ review.age }}</span></p>
                                    <div class="flex items-center gap-1 text-[#FFD700] text-sm mt-1">
                                        <template v-for="i in 5" :key="i">
                                            <Star :size="14" :class="i <= review.rating ? 'fill-current' : 'text-slate-300'" />
                                        </template>
                                        <span class="text-slate-400 ml-2">{{ review.rating }} Stars</span>
                                    </div>
                                </div>
                            </div>
                            <p class="text-slate-600 leading-relaxed">{{ review.review_text }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products Slider -->
            <section v-if="props.relatedProducts.length > 0" class="py-24 bg-brand-50">
                <div class="max-w-7xl mx-auto px-4 mb-12">
                    <h2 class="text-sm font-bold text-brand-600 uppercase tracking-[0.2em] mb-4">Curated Pairings</h2>
                    <p class="text-4xl md:text-5xl font-serif text-slate-900">You May Also Love</p>
                </div>

                <div class="max-w-7xl mx-auto px-4 relative group/rslider">
                    <!-- Navigation Overlays -->
                    <button 
                        @click="scroll('left')" 
                        class="absolute -left-6 top-1/2 -translate-y-1/2 z-20 p-4 rounded-full bg-white shadow-xl border border-brand-100 text-brand-900 opacity-0 group-hover/rslider:opacity-100 transition-all hover:bg-brand-900 hover:text-white disabled:hidden md:flex items-center justify-center hidden"
                    >
                        <ChevronLeft :size="24" />
                    </button>
                    <button 
                        @click="scroll('right')" 
                        class="absolute -right-6 top-1/2 -translate-y-1/2 z-20 p-4 rounded-full bg-white shadow-xl border border-brand-100 text-brand-900 opacity-0 group-hover/rslider:opacity-100 transition-all hover:bg-brand-900 hover:text-white disabled:hidden md:flex items-center justify-center hidden"
                    >
                        <ChevronRight :size="24" />
                    </button>

                    <div 
                        ref="scrollRef"
                        class="flex overflow-x-auto gap-8 no-scrollbar scroll-smooth pb-12"
                    >
                    <div 
                        v-for="rel in props.relatedProducts"
                        :key="rel.id" 
                        @click="router.visit('/products/' + rel.id)"
                        class="min-w-[300px] md:min-w-[350px] group bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 border border-brand-100 flex flex-col cursor-pointer"
                    >
                        <div class="h-64 overflow-hidden relative">
                            <img :src="(Array.isArray(rel.image_url) ? rel.image_url[0] : rel.image_url) || 'https://images.unsplash.com/photo-1596462502278-27bfad450526?auto=format&fit=crop&q=80&w=800'" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" :alt="rel.name" />
                            <div class="absolute top-6 left-6 bg-white/90 backdrop-blur p-2.5 rounded-xl text-brand-900 shadow-md">
                                <Tag :size="18" />
                            </div>
                            <div v-if="Number(rel.offer_price) > 0" class="absolute top-6 right-6 bg-[#FF007F] text-white px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm z-10">
                                {{ Math.round(((Number(rel.price) - Number(rel.offer_price)) / Number(rel.price)) * 100) }}% OFF
                            </div>
                        </div>
                        <div class="p-8 flex-grow flex flex-col justify-between">
                            <div>
                                <p class="text-[10px] font-black text-brand-500 uppercase tracking-widest mb-2">{{ rel.brand }}</p>
                                <h3 class="text-xl font-serif text-slate-900 mb-4 truncate">{{ rel.name }}</h3>
                                
                                <!-- Price Horizontally -->
                                <div class="flex items-center gap-2.5">
                                    <template v-if="Number(rel.offer_price) > 0">
                                        <p class="text-xl font-bold text-brand-900">৳{{ Number(rel.offer_price).toFixed(2) }}</p>
                                        <span class="text-sm text-slate-400 line-through">৳{{ Number(rel.price).toFixed(2) }}</span>
                                    </template>
                                    <template v-else>
                                        <p class="text-xl font-bold text-brand-900">৳{{ Number(rel.price).toFixed(2) }}</p>
                                    </template>
                                </div>
                            </div>

                            <!-- Actions Underneath -->
                            <div class="flex items-center gap-3 mt-6">
                                <button 
                                    @click.stop="addToCart(rel)"
                                    class="flex-1 bg-brand-50 hover:bg-brand-100 text-brand-900 text-xs font-bold py-3 rounded-xl transition-all border border-brand-200 text-center"
                                >
                                    Add to Cart
                                </button>
                                <button 
                                    @click.stop="buyNow(rel)"
                                    class="flex-1 bg-brand-900 hover:bg-brand-800 text-white text-xs font-bold py-3 rounded-xl transition-all text-center shadow-md shadow-brand-900/10"
                                >
                                    Order
                                </button>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Added to Cart Notification -->
        <Transition
            enter-active-class="transform ease-out duration-300 transition"
            enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
            leave-active-class="transition ease-in duration-100"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="showAddedNotification" class="fixed bottom-10 right-10 z-[100] bg-white border border-brand-200 rounded-2xl shadow-2xl p-4 flex items-center space-x-4 max-w-sm animate-in slide-in-from-right-4">
                <div class="bg-brand-50 text-brand-900 p-2 rounded-xl">
                    <CheckCircle2 :size="20" />
                </div>
                <div>
                    <p class="font-bold text-slate-900 text-sm">Added to Cart!</p>
                    <p class="text-slate-500 text-xs truncate max-w-[200px]">{{ product.name }} added successfully.</p>
                </div>
                <Link href="/checkout" class="bg-brand-900 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-brand-800 transition-colors">
                    Checkout
                </Link>
            </div>
        </Transition>
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
