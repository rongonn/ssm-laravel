<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { Instagram, Facebook, MessageCircle, ArrowRight, Sparkles } from 'lucide-vue-next';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { computed } from 'vue';

const props = defineProps<{
    team: any[];
}>();

const page = usePage();
const settings = computed(() => (page.props.settings as any) || {});
const APP_NAME = computed(() => settings.value.application_name || 'Bioshah.com');
</script>

<template>
    <PublicLayout>
        <Head title="About Us | Our Story & Team" />
        
        <div class="bg-white">
            <!-- Brand Story -->
            <section class="py-24 max-w-7xl mx-auto px-4">
                <div class="grid md:grid-cols-2 gap-12 lg:gap-20 items-center">
                    <div class="relative group order-2 md:order-1">
                        <!-- Main Image Frame -->
                        <div class="aspect-[3/4] rounded-[2.5rem] overflow-hidden shadow-[0_32px_64px_-16px_rgba(67,48,43,0.3)] border-[6px] border-white transition-all duration-700 group-hover:scale-[1.01] bg-slate-100">
                            <img 
                                src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&q=80&w=800" 
                                :alt="`${APP_NAME} Master Artisan`" 
                                class="w-full h-full object-cover object-center"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-brand-900/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500" />
                        </div>
                        
                        <!-- Floating Info Tag -->
                        <div class="absolute top-10 -right-4 md:-right-8 bg-white p-6 rounded-[2rem] shadow-2xl border border-brand-50 flex items-center space-x-4 animate-bounce-slow z-20">
                            <div class="bg-brand-900 text-white p-3.5 rounded-2xl">
                                <Sparkles :size="28" />
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-brand-600 uppercase tracking-widest">Master Tech</p>
                                <p class="text-base font-bold text-slate-900">Advanced Skincare</p>
                            </div>
                        </div>

                        <!-- decorative element -->
                        <div class="absolute -bottom-10 -left-10 w-48 h-48 bg-brand-50 rounded-[3rem] -z-10 animate-pulse" />
                    </div>

                    <div class="order-1 md:order-2 space-y-8">
                        <div class="space-y-4">
                            <h2 class="text-sm font-bold text-brand-600 uppercase tracking-[0.4em] flex items-center gap-3">
                                <div class="h-0.5 w-8 bg-brand-600"></div>
                                Our Legacy
                            </h2>
                            <h1 class="text-5xl md:text-7xl font-serif text-slate-900 leading-[1.1]">
                                Crafting Beauty <br /> Since <span class="italic text-brand-800">2023</span>
                            </h1>
                        </div>
                        
                        <div class="space-y-6">
                            <p class="text-xl text-slate-600 leading-relaxed font-medium">
                                {{ APP_NAME }} brings the future of aesthetic care to you. Since 2023, we have committed to delivering results that speak for themselves.
                            </p>
                            <p class="text-lg text-slate-500 leading-relaxed">
                                As seen in our studio, we invest in high-end, certified technology to ensure every treatment is effective and painless. Our master artisans are trained to handle the most complex aesthetic challenges with precision and care. 
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-10 pt-4">
                            <div class="space-y-1">
                                <p class="text-5xl font-serif text-brand-900 font-bold">2023</p>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Established</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-5xl font-serif text-brand-900 font-bold">100%</p>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Quality Care</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Team Section -->
            <section class="bg-brand-50 py-24">
                <div class="max-w-7xl mx-auto px-4">
                    <div class="text-center mb-20 space-y-4">
                        <h2 class="text-sm font-bold text-brand-600 uppercase tracking-[0.3em]">The Visionaries</h2>
                        <h2 class="text-4xl md:text-5xl font-serif text-slate-900">Meet Our Experts</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-x-10 gap-y-16">
                        <template v-if="props.team.length > 0">
                            <div v-for="member in props.team" :key="member.id" class="group text-center flex flex-col items-center">
                                <div class="w-full aspect-[3/4] rounded-[2.5rem] overflow-hidden mb-8 relative border-2 border-transparent group-hover:border-brand-300 transition-all duration-500 shadow-sm hover:shadow-2xl">
                                    <img 
                                        :src="member.image_url || 'https://via.placeholder.com/600x800'" 
                                        :alt="member.name" 
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000" 
                                    />
                                    
                                    <!-- Social Overlay -->
                                    <div class="absolute inset-0 bg-brand-900/40 backdrop-blur-[2px] flex items-center justify-center space-x-4 opacity-0 group-hover:opacity-100 transition-all duration-500">
                                        <a v-if="member.facebook_url" :href="member.facebook_url" target="_blank" rel="noopener noreferrer" class="bg-white p-3 rounded-full text-brand-900 transition-transform hover:scale-110 hover:bg-brand-900 hover:text-white shadow-lg">
                                            <Facebook :size="20" />
                                        </a>
                                        <a v-if="member.instagram_url" :href="member.instagram_url" target="_blank" rel="noopener noreferrer" class="bg-white p-3 rounded-full text-brand-900 transition-transform hover:scale-110 hover:bg-brand-900 hover:text-white shadow-lg">
                                            <Instagram :size="20" />
                                        </a>
                                        <a v-if="member.whatsapp_url" :href="member.whatsapp_url" target="_blank" rel="noopener noreferrer" class="bg-white p-3 rounded-full text-brand-900 transition-transform hover:scale-110 hover:bg-brand-900 hover:text-white shadow-lg">
                                            <MessageCircle :size="20" />
                                        </a>
                                    </div>
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
                        </template>
                        <p v-else class="col-span-full text-center text-slate-400 italic py-12">Team profiles are ready and waiting...</p>
                    </div>
                </div>
            </section>
        </div>
    </PublicLayout>
</template>
