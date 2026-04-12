<script setup lang="ts">
import { onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, Clock, ShieldCheck, RefreshCw, Star, MessageCircle } from 'lucide-vue-next';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const props = defineProps<{
    id: string;
    service: any;
}>();

const page = usePage();
const settings = computed(() => (page.props.settings as any) || {});
const APP_NAME = computed(() => settings.value.application_name || 'Bioshah.com');

const WHATSAPP_NUMBER = "01911-879571";

const openWhatsApp = () => {
    if (!props.service) return;
    const message = encodeURIComponent(`Hello ${APP_NAME.value}! I'm interested in the service: ${props.service.name} (Category: ${props.service.category}, Price: ৳${props.service.price}). Can I book an appointment?`);
    window.open(`https://wa.me/${WHATSAPP_NUMBER.replace('-', '')}?text=${message}`, '_blank');
};

onMounted(() => {
    window.scrollTo(0, 0);
});
</script>

<template>
    <PublicLayout>
        <Head :title="service ? `${service.name} | Service Details` : 'Service Details'" />
        
        <div v-if="!props.service" class="min-h-screen flex flex-col items-center justify-center p-4">
            <h2 class="text-3xl font-serif mb-4">Service Not Found</h2>
            <Link href="/services" class="text-brand-900 font-bold flex items-center space-x-2">
                <ArrowLeft :size="20" />
                <span>Back to Services</span>
            </Link>
        </div>

        <div v-else class="bg-white min-h-screen">
            <!-- Breadcrumb -->
            <div class="max-w-7xl mx-auto px-4 py-8">
                <Link href="/services" class="inline-flex items-center space-x-2 text-slate-400 hover:text-brand-900 transition-colors font-bold text-sm uppercase tracking-widest">
                    <ArrowLeft :size="16" />
                    <span>Back to Menu</span>
                </Link>
            </div>

            <div class="max-w-7xl mx-auto px-4 pb-24">
                <!-- Top Split Section -->
                <div class="grid lg:grid-cols-2 gap-16 items-start mb-16">
                    <!-- Image -->
                    <div class="relative aspect-[4/5] rounded-[3rem] overflow-hidden shadow-2xl border border-brand-100">
                        <img 
                            :src="props.service.image_url || 'https://picsum.photos/800/1000?random=' + props.service.id" 
                            :alt="props.service.name" 
                            class="w-full h-full object-cover"
                        />
                        <div class="absolute top-8 left-8 bg-white/90 backdrop-blur px-6 py-2 rounded-full text-brand-900 font-black uppercase tracking-widest text-xs shadow-lg">
                            {{ props.service.category }}
                        </div>
                    </div>

                    <!-- Core Info -->
                    <div class="space-y-10 py-4">
                        <div>
                            <div class="flex items-center space-x-2 text-brand-600 mb-4">
                                <Star class="fill-brand-600" :size="16" />
                                <span class="font-black uppercase tracking-[0.4em] text-[10px]">Premium Salon Service</span>
                            </div>
                            <h1 class="text-5xl md:text-6xl font-serif text-slate-900 mb-8 leading-tight">{{ props.service.name }}</h1>
                            <div class="flex flex-wrap items-center gap-6">
                                <p class="text-5xl font-bold text-brand-900">৳{{ props.service.price }}</p>
                                <div class="h-10 w-px bg-slate-200 hidden sm:block" />
                                <div class="flex items-center space-x-2 text-slate-500 bg-slate-50 px-5 py-2.5 rounded-full border border-slate-100">
                                    <Clock :size="20" class="text-brand-600" />
                                    <span class="font-bold">{{ props.service.duration }} Session</span>
                                </div>
                            </div>
                        </div>

                        <!-- CTA Section -->
                        <div class="space-y-6 pt-6">
                            <button 
                                @click="openWhatsApp"
                                class="flex items-center justify-center space-x-4 w-full bg-[#25D366] text-white py-6 rounded-[2rem] text-xl font-bold shadow-2xl shadow-green-500/20 hover:scale-[1.02] active:scale-95 transition-all"
                            >
                                <MessageCircle :size="28" />
                                <span>Book via WhatsApp</span>
                            </button>
                            <p class="text-center text-slate-400 text-sm italic">Immediate response for appointment scheduling.</p>
                        </div>

                        <!-- Quality Seals -->
                        <div class="grid grid-cols-3 gap-6 pt-10 border-t border-slate-100">
                            <div class="flex flex-col items-center text-center space-y-3">
                                <div class="p-4 bg-brand-50 rounded-[1.5rem] text-brand-900 shadow-sm"><ShieldCheck :size="24" /></div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-slate-500">Master Artisans</p>
                            </div>
                            <div class="flex flex-col items-center text-center space-y-3">
                                <div class="p-4 bg-brand-50 rounded-[1.5rem] text-brand-900 shadow-sm"><Star :size="24" /></div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-slate-500">Elite Care</p>
                            </div>
                            <div class="flex flex-col items-center text-center space-y-3">
                                <div class="p-4 bg-brand-50 rounded-[1.5rem] text-brand-900 shadow-sm"><RefreshCw :size="24" /></div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-slate-500">Safe Rituals</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Full Width Description Section -->
                <div class="bg-slate-50/50 rounded-[3rem] p-12 md:p-16 border border-slate-100">
                    <div class="max-w-4xl">
                        <h4 class="text-xs font-black text-brand-600 uppercase tracking-[0.3em] mb-6">Service Overview</h4>
                        <h2 class="text-4xl font-serif text-slate-900 mb-8">What to Expect from this Ritual</h2>
                        <div class="prose prose-lg prose-slate max-w-none">
                            <p class="text-slate-600 leading-relaxed text-xl whitespace-pre-wrap">
                                {{ props.service.description || "Indulge in a meticulously crafted experience designed to enhance your natural beauty while providing profound relaxation. Our master artisans use only the finest techniques and professional products to ensure your transformation is both visible and lasting.\n\nEvery session begins with a personalized consultation to tailor the treatment to your specific needs and aesthetic goals." }}
                            </p>
                        </div>
                        
                        <div class="mt-12 p-8 bg-white rounded-3xl border border-brand-100 shadow-sm inline-block">
                            <p class="text-slate-500 font-medium italic">
                                "Our goal is to redefine your daily beauty ritual through expertise and unparalleled attention to detail."
                            </p>
                            <p class="text-brand-900 font-bold mt-4 uppercase tracking-widest text-xs">— {{ APP_NAME }} Specialist</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
