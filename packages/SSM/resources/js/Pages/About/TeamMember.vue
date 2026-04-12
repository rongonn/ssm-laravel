<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, Facebook, Instagram, MessageCircle, Star, Tag, Quote } from 'lucide-vue-next';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const props = defineProps<{
    id: string;
    member: any;
}>();

const page = usePage();
const settings = computed(() => (page.props.settings as any) || {});
const APP_NAME = computed(() => settings.value.application_name || 'Bioshah.com');
</script>

<template>
    <PublicLayout>
        <Head :title="member ? `${member.name} | Expert Profile` : 'Expert Profile'" />
        
        <div v-if="!props.member" class="min-h-screen flex flex-col items-center justify-center p-4">
            <h2 class="text-3xl font-serif mb-4 text-slate-900">Artisan Not Found</h2>
            <Link href="/about" class="text-brand-900 font-bold flex items-center space-x-2">
                <ArrowLeft :size="20" />
                <span>Back to About Us</span>
            </Link>
        </div>

        <div v-else class="bg-white min-h-screen">

            <!-- Back Link -->
            <div class="max-w-7xl mx-auto px-4 py-8">
                <Link href="/about" class="inline-flex items-center space-x-2 text-slate-400 hover:text-brand-900 transition-colors font-bold text-sm uppercase tracking-widest">
                    <ArrowLeft :size="16" />
                    <span>Back to Team</span>
                </Link>
            </div>

            <div class="max-w-7xl mx-auto px-4 pb-24">
                <div class="grid lg:grid-cols-2 gap-16 items-start">
                    
                    <!-- Staff Image -->
                    <div class="relative group">
                        <div class="aspect-[3/4] rounded-[4rem] overflow-hidden shadow-2xl border-4 border-white">
                            <img 
                                :src="props.member.image_url || 'https://via.placeholder.com/800x1000'" 
                                :alt="props.member.name" 
                                class="w-full h-full object-cover"
                            />
                        </div>
                        <!-- Decoration -->
                        <div class="absolute -z-10 -bottom-10 -left-10 w-48 h-48 bg-brand-100 rounded-[3rem] animate-pulse" />
                    </div>

                    <!-- Details -->
                    <div class="space-y-12">
                        <div>
                            <div class="flex items-center space-x-2 text-brand-600 mb-6">
                                <Star class="fill-brand-600" :size="16" />
                                <span class="font-black uppercase tracking-[0.4em] text-[10px]">Master Artisan</span>
                            </div>
                            <h1 class="text-6xl md:text-7xl font-serif text-slate-900 mb-4 leading-none">{{ props.member.name }}</h1>
                            <p class="text-2xl font-serif text-brand-700 italic">{{ props.member.role }}</p>
                        </div>

                        <div class="space-y-6">
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">Areas of Expertise</h4>
                            <div class="flex flex-wrap gap-3">
                                <span v-for="(spec, i) in props.member.specialty" :key="i" class="flex items-center space-x-2 bg-slate-50 text-slate-700 px-5 py-2.5 rounded-full border border-slate-100 text-sm font-bold shadow-sm">
                                    <Tag :size="14" class="text-brand-600" />
                                    <span>{{ spec }}</span>
                                </span>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">Biography</h4>
                            <div class="relative">
                                <Quote class="absolute -left-8 -top-4 text-brand-100 w-16 h-16 -z-10" />
                                <p class="text-slate-600 text-xl leading-relaxed font-light italic">
                                    {{ props.member.bio || `This master artisan has dedicated years to perfecting their craft, ensuring that every client receives a world-class beauty experience at ${APP_NAME}.` }}
                                </p>
                            </div>
                        </div>

                        <!-- Social Media Links -->
                        <div class="pt-8 border-t border-slate-100 space-y-6">
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Connect with {{ props.member.name.split(' ')[0] }}</h4>
                            <div class="flex flex-wrap gap-4">
                                <a 
                                    v-if="props.member.facebook_url"
                                    :href="props.member.facebook_url" 
                                    target="_blank" 
                                    rel="noopener noreferrer"
                                    class="flex-1 min-w-[160px] flex items-center justify-center space-x-3 bg-[#1877F2] py-6 rounded-3xl text-white transition-all duration-300 group shadow-lg hover:shadow-[#1877F2]/30 hover:scale-[1.05] active:scale-95"
                                >
                                    <Facebook :size="24" />
                                    <span class="font-bold">Facebook</span>
                                </a>
                                <a 
                                    v-if="props.member.instagram_url"
                                    :href="props.member.instagram_url" 
                                    target="_blank" 
                                    rel="noopener noreferrer"
                                    class="flex-1 min-w-[160px] flex items-center justify-center space-x-3 bg-gradient-to-tr from-[#f09433] via-[#e6683c] to-[#bc1888] py-6 rounded-3xl text-white transition-all duration-300 group shadow-lg hover:shadow-[#bc1888]/30 hover:scale-[1.05] active:scale-95"
                                >
                                    <Instagram :size="24" />
                                    <span class="font-bold">Instagram</span>
                                </a>
                                <a 
                                    v-if="props.member.whatsapp_url"
                                    :href="props.member.whatsapp_url" 
                                    target="_blank" 
                                    rel="noopener noreferrer"
                                    class="flex-1 min-w-[160px] flex items-center justify-center space-x-3 bg-[#25D366] py-6 rounded-3xl text-white transition-all duration-300 group shadow-lg hover:shadow-[#25D366]/30 hover:scale-[1.05] active:scale-95"
                                >
                                    <MessageCircle :size="24" />
                                    <span class="font-bold">WhatsApp</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
