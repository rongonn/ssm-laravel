<script setup lang="ts">
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { Instagram, Facebook, Youtube, Mail, MapPin, MessageCircle } from 'lucide-vue-next';

const page = usePage();
const settings = computed(() => page.props.settings || {});

const CONTACT_NUMBER = computed(() => (settings.value as any).company_phone || "01911-879571");
const ADDRESS = computed(() => (settings.value as any).company_address || "Plot no 14, Tota Mia complex shop no 19, 22 Mirpur 10 Dhaka Bangladesh");
const EMAIL = computed(() => (settings.value as any).company_email || "info@style-studio-mart.com");
const LOGO_URL = computed(() => (settings.value as any).company_logo ? `/storage/${(settings.value as any).company_logo}` : "https://via.placeholder.com/150");
const APP_NAME = computed(() => (settings.value as any).application_name || "Bioshah.com");
const APP_SLOGAN = computed(() => (settings.value as any).application_slogan || "Premium salon and wellness experience.");
const FB_URL = computed(() => (settings.value as any).facebook_url || "https://www.facebook.com/stylestudiomart2024/");
const INSTA_URL = computed(() => (settings.value as any).instagram_url || "https://www.instagram.com/shahjahan.khairul79");
const YT_URL = computed(() => (settings.value as any).youtube_url || "https://www.youtube.com/@ShahjahanKhairul79");
const FOOTER_TEXT = computed(() => (settings.value as any).footer_text || `© ${new Date().getFullYear()} Bioshah.com. All Rights Reserved.`);
</script>

<template>
    <footer class="bg-slate-900 text-slate-400 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-12 mb-16">
                <div class="col-span-1 md:col-span-1">
                    <Link href="/" class="flex items-center space-x-3 mb-8 group">
                        <img 
                            :src="LOGO_URL" 
                            alt="Logo" 
                            class="h-12 w-12 rounded-xl object-cover border border-slate-700 group-hover:border-brand-400 transition-colors"
                        />
                        <span class="text-2xl font-serif font-bold text-white tracking-tight uppercase">
                            {{ APP_NAME }}
                        </span>
                    </Link>
                    <p class="leading-relaxed mb-8">
                        {{ APP_SLOGAN }}
                    </p>
                    <div class="flex space-x-5">
                        <a :href="FB_URL" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors text-slate-400"><Facebook :size="22" /></a>
                        <a :href="INSTA_URL" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors text-slate-400"><Instagram :size="22" /></a>
                        <a :href="YT_URL" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors text-slate-400"><Youtube :size="22" /></a>
                    </div>
                </div>
                
                <div>
                    <h4 class="text-white font-bold uppercase tracking-widest text-sm mb-8">Navigation</h4>
                    <ul class="space-y-4">
                        <li><Link href="/services" class="hover:text-brand-300 transition-colors">Our Services</Link></li>
                        <li><Link href="/products" class="hover:text-brand-300 transition-colors">Product Shop</Link></li>
                        <li><Link href="/about" class="hover:text-brand-300 transition-colors">About Team</Link></li>
                        <li><Link href="/contact" class="hover:text-brand-300 transition-colors">Contact Us</Link></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-bold uppercase tracking-widest text-sm mb-8">Opening Hours</h4>
                    <ul class="space-y-4">
                        <li class="flex justify-between"><span>Saturday – Thursday</span> <span class="text-white">10am – 10pm</span></li>
                        <li class="flex justify-between"><span>Friday</span> <span class="text-white">10am – 10pm</span></li>
                        <li class="pt-2">
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-400 bg-emerald-900/40 px-3 py-1.5 rounded-full">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                Open Every Day
                            </span>
                        </li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-bold uppercase tracking-widest text-sm mb-8">Contact Info</h4>
                    <ul class="space-y-4">
                        <li class="flex items-start space-x-3">
                            <MapPin :size="20" class="text-brand-400 flex-shrink-0" />
                            <span class="text-sm leading-relaxed">{{ ADDRESS }}</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <MessageCircle :size="20" class="text-brand-400" />
                            <span>{{ CONTACT_NUMBER }}</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <Mail :size="20" class="text-brand-400" />
                            <span class="text-sm">{{ EMAIL }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="pt-8 border-t border-slate-800 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0 text-sm">
                <p v-html="FOOTER_TEXT"></p>
                <div class="flex space-x-6">
                    <Link href="/privacy-policy" class="hover:text-white transition-colors">Privacy Policy</Link>
                    <Link href="/terms-of-service" class="hover:text-white transition-colors">Terms of Service</Link>
                </div>
            </div>
        </div>
    </footer>
</template>
