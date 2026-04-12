<script setup lang="ts">
import { computed } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import { Mail, MapPin, MessageCircle, Facebook, Instagram, Youtube, ExternalLink } from 'lucide-vue-next';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const page = usePage();
const settings = computed(() => page.props.settings || {});

const CONTACT_NUMBER = computed(() => (settings.value as any).company_phone || "01911-879571");
const ADDRESS = computed(() => (settings.value as any).company_address || "Plot no 14, Tota Mia complex shop no 19, 22 Mirpur 10 Dhaka Bangladesh. Road no 1, 15 Dhaka north city, 1216");
const EMAIL = computed(() => (settings.value as any).company_email || "shahjahan.khairul79@gmail.com");
const BANNER_URL = computed(() => (settings.value as any).contact_banner ? `/storage/${(settings.value as any).contact_banner}` : "https://images.unsplash.com/photo-1516726817505-f5ed825624d8?auto=format&fit=crop&q=80&w=2000");
const APP_NAME = computed(() => (settings.value as any).application_name || 'Bioshah.com');

const socialLinks = computed(() => [
    { 
        name: 'Facebook', 
        url: (settings.value as any).facebook_url || 'https://www.facebook.com/stylestudiomart2024/', 
        icon: Facebook, 
        color: 'bg-[#1877F2]',
        hover: 'hover:bg-[#0d62d1]'
    },
    { 
        name: 'Instagram', 
        url: (settings.value as any).instagram_url || 'https://www.instagram.com/shahjahan.khairul79', 
        icon: Instagram, 
        color: 'bg-gradient-to-tr from-[#f09433] via-[#e6683c] to-[#bc1888]',
        hover: 'hover:opacity-90'
    },
    { 
        name: 'YouTube', 
        url: (settings.value as any).youtube_url || 'https://www.youtube.com/@ShahjahanKhairul79', 
        icon: Youtube, 
        color: 'bg-[#FF0000]',
        hover: 'hover:bg-[#cc0000]'
    }
]);
</script>

<template>
    <PublicLayout>
        <Head title="Contact Us | Visit Our Studio" />
        
        <div class="bg-brand-50 min-h-screen">
            <!-- Header -->
            <section class="bg-brand-900 text-white py-24 relative overflow-hidden">
                <div class="max-w-7xl mx-auto px-4 relative z-10 text-center">
                    <h1 class="text-5xl md:text-7xl font-serif mb-6">Contact Us</h1>
                    <p class="text-xl text-brand-200 max-w-2xl mx-auto">
                        Experience the artistry in person. We are located in the heart of Mirpur, ready to transform your look with precision and care.
                    </p>
                </div>
                <div class="absolute inset-0 opacity-20 bg-cover bg-center" :style="{ backgroundImage: `url(${BANNER_URL})`, mixBlendMode: 'overlay' }"></div>
            </section>

            <section class="py-24 max-w-7xl mx-auto px-4">
                <div class="grid md:grid-cols-5 gap-16 items-start">
                    <!-- Contact Info & Socials -->
                    <div class="md:col-span-2 space-y-12">
                        <div>
                            <h1 class="text-5xl font-serif text-slate-900 mb-6 leading-tight">Visit Our <br /><span class="italic text-brand-800">Studio</span></h1>
                        </div>

                        <div class="space-y-8">
                            <div class="flex items-start space-x-6">
                                <div class="bg-white p-4 rounded-2xl shadow-sm text-brand-900">
                                    <MapPin :size="24" />
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 uppercase tracking-widest text-xs mb-2">Our Address</h4>
                                    <p class="text-slate-600 leading-relaxed text-sm">{{ ADDRESS }}</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-6">
                                <div class="bg-white p-4 rounded-2xl shadow-sm text-brand-900">
                                    <MessageCircle :size="24" />
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 uppercase tracking-widest text-xs mb-2">Phone / WhatsApp</h4>
                                    <p class="text-slate-600 font-bold text-lg">{{ CONTACT_NUMBER }}</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-6">
                                <div class="bg-white p-4 rounded-2xl shadow-sm text-brand-900">
                                    <Mail :size="24" />
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 uppercase tracking-widest text-xs mb-2">Email Us</h4>
                                    <p class="text-slate-600 text-sm">{{ EMAIL }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Social Media Buttons -->
                        <div class="pt-8 border-t border-slate-200">
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Connect Digitally</h4>
                            <div class="grid grid-cols-1 gap-4">
                                <a 
                                    v-for="social in socialLinks"
                                    :key="social.name"
                                    :href="social.url"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    :class="[`flex items-center justify-between px-8 py-5 rounded-[2rem] text-white transition-all transform hover:scale-[1.02] active:scale-95 shadow-lg ${social.color} ${social.hover}`]"
                                >
                                    <div class="flex items-center space-x-4">
                                        <component :is="social.icon" :size="24" />
                                        <span class="font-bold uppercase tracking-widest text-xs">Join us on {{ social.name }}</span>
                                    </div>
                                    <ExternalLink :size="16" class="opacity-50" />
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Google Map Embed -->
                    <div class="md:col-span-3 h-[600px] md:h-[800px] rounded-[3rem] overflow-hidden border-8 border-white shadow-2xl bg-slate-100">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d329.3073613545658!2d90.36956934199728!3d23.806316847966162!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c1006fc192df%3A0x808010443f960817!2sStyle%20studio%20Mart!5e0!3m2!1sen!2sbd!4v1769796157582!5m2!1sen!2sbd" 
                            width="100%" 
                            height="100%" 
                            style="border: 0" 
                            allowfullscreen="true" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            :title="`${APP_NAME} Location`"
                        ></iframe>
                    </div>
                </div>
            </section>
        </div>
    </PublicLayout>
</template>
