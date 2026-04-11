<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { FileText, ShoppingCart, Ban, AlertTriangle, Scale, Mail } from 'lucide-vue-next';
</script>

<template>
    <PublicLayout>
        <Head title="Terms of Service | Style Studio Mart" />

        <!-- Page Hero -->
        <section class="relative bg-brand-900 text-white py-24 overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&q=80&w=2000" class="w-full h-full object-cover" alt="" />
            </div>
            <div class="relative z-10 max-w-4xl mx-auto px-4 text-center">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur px-5 py-2 rounded-full text-brand-200 text-sm font-semibold uppercase tracking-widest mb-6">
                    <FileText :size="16" />
                    <span>Legal</span>
                </div>
                <h1 class="text-5xl md:text-6xl font-serif mb-6">Terms of Service</h1>
                <p class="text-brand-200 text-lg leading-relaxed">Last updated: January 1, {{ new Date().getFullYear() }}</p>
            </div>
        </section>

        <!-- Content -->
        <section class="py-20 bg-white">
            <div class="max-w-4xl mx-auto px-4">

                <!-- Intro -->
                <div class="prose prose-lg max-w-none mb-16">
                    <p class="text-slate-600 text-lg leading-relaxed">
                        Welcome to <strong class="text-brand-900">Style Studio Mart</strong>. By accessing or using our website and services, you agree to be bound by the following Terms of Service. Please read them carefully before using our platform.
                    </p>
                </div>

                <!-- Sections -->
                <div class="space-y-12">

                    <!-- Section 1 -->
                    <div class="flex gap-6 p-8 bg-brand-50 rounded-3xl">
                        <div class="flex-shrink-0 w-12 h-12 bg-brand-900 rounded-2xl flex items-center justify-center">
                            <FileText :size="22" class="text-white" />
                        </div>
                        <div>
                            <h2 class="text-2xl font-serif text-slate-900 mb-4">Acceptance of Terms</h2>
                            <p class="text-slate-600 leading-relaxed">
                                By accessing and using the Style Studio Mart website, you acknowledge that you have read, understood, and agree to these Terms of Service. If you do not agree with any part of these terms, please do not use our services.
                            </p>
                        </div>
                    </div>

                    <!-- Section 2 -->
                    <div class="flex gap-6 p-8 bg-slate-50 rounded-3xl">
                        <div class="flex-shrink-0 w-12 h-12 bg-brand-900 rounded-2xl flex items-center justify-center">
                            <ShoppingCart :size="22" class="text-white" />
                        </div>
                        <div>
                            <h2 class="text-2xl font-serif text-slate-900 mb-4">Products & Services</h2>
                            <p class="text-slate-600 leading-relaxed mb-4">When using our salon services or purchasing products:</p>
                            <ul class="space-y-2 text-slate-600">
                                <li class="flex items-start gap-2"><span class="text-brand-500 font-bold mt-1">•</span> <span>All prices are listed in Bangladeshi Taka (BDT) and are subject to change without notice.</span></li>
                                <li class="flex items-start gap-2"><span class="text-brand-500 font-bold mt-1">•</span> <span>Service bookings are subject to availability. We reserve the right to refuse service.</span></li>
                                <li class="flex items-start gap-2"><span class="text-brand-500 font-bold mt-1">•</span> <span>Product images are for illustrative purposes; actual products may slightly vary.</span></li>
                                <li class="flex items-start gap-2"><span class="text-brand-500 font-bold mt-1">•</span> <span>We strive to maintain accurate product descriptions, but cannot guarantee completeness.</span></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Section 3: Opening Hours (highlight) -->
                    <div class="p-8 border-2 border-brand-200 rounded-3xl bg-white">
                        <h2 class="text-2xl font-serif text-slate-900 mb-6">Business Hours</h2>
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div class="bg-brand-50 rounded-2xl p-6 flex justify-between items-center">
                                <span class="font-semibold text-slate-700">Saturday – Thursday</span>
                                <span class="font-bold text-brand-900 bg-brand-100 px-4 py-1.5 rounded-full text-sm">10 AM – 10 PM</span>
                            </div>
                            <div class="bg-brand-50 rounded-2xl p-6 flex justify-between items-center">
                                <span class="font-semibold text-slate-700">Friday</span>
                                <span class="font-bold text-brand-900 bg-brand-100 px-4 py-1.5 rounded-full text-sm">10 AM – 10 PM</span>
                            </div>
                        </div>
                        <p class="text-slate-500 text-sm mt-4">Service availability may vary on public holidays. We recommend calling ahead to confirm.</p>
                    </div>

                    <!-- Section 4 -->
                    <div class="flex gap-6 p-8 bg-brand-50 rounded-3xl">
                        <div class="flex-shrink-0 w-12 h-12 bg-brand-900 rounded-2xl flex items-center justify-center">
                            <Ban :size="22" class="text-white" />
                        </div>
                        <div>
                            <h2 class="text-2xl font-serif text-slate-900 mb-4">Prohibited Conduct</h2>
                            <p class="text-slate-600 leading-relaxed mb-4">You agree not to:</p>
                            <ul class="space-y-2 text-slate-600">
                                <li class="flex items-start gap-2"><span class="text-brand-500 font-bold mt-1">•</span> <span>Use the website for any unlawful purpose or in violation of any local regulations.</span></li>
                                <li class="flex items-start gap-2"><span class="text-brand-500 font-bold mt-1">•</span> <span>Attempt to gain unauthorized access to any part of the website or its servers.</span></li>
                                <li class="flex items-start gap-2"><span class="text-brand-500 font-bold mt-1">•</span> <span>Copy, reproduce, or distribute our content without prior written permission.</span></li>
                                <li class="flex items-start gap-2"><span class="text-brand-500 font-bold mt-1">•</span> <span>Submit false or misleading information through any forms on this site.</span></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Section 5 -->
                    <div class="flex gap-6 p-8 bg-slate-50 rounded-3xl">
                        <div class="flex-shrink-0 w-12 h-12 bg-brand-900 rounded-2xl flex items-center justify-center">
                            <AlertTriangle :size="22" class="text-white" />
                        </div>
                        <div>
                            <h2 class="text-2xl font-serif text-slate-900 mb-4">Disclaimer of Warranties</h2>
                            <p class="text-slate-600 leading-relaxed">
                                Style Studio Mart provides this website and its services on an "as is" basis without warranty of any kind, express or implied. We do not warrant that the website will be uninterrupted, error-free, or free from viruses or harmful components.
                            </p>
                        </div>
                    </div>

                    <!-- Section 6 -->
                    <div class="flex gap-6 p-8 bg-brand-50 rounded-3xl">
                        <div class="flex-shrink-0 w-12 h-12 bg-brand-900 rounded-2xl flex items-center justify-center">
                            <Scale :size="22" class="text-white" />
                        </div>
                        <div>
                            <h2 class="text-2xl font-serif text-slate-900 mb-4">Governing Law</h2>
                            <p class="text-slate-600 leading-relaxed">
                                These Terms of Service are governed by the laws of Bangladesh. Any disputes arising from the use of our website or services shall be subject to the exclusive jurisdiction of the courts of Dhaka, Bangladesh.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Contact CTA -->
                <div class="mt-16 p-10 bg-brand-900 text-white rounded-3xl text-center">
                    <Mail :size="36" class="mx-auto mb-4 text-brand-300" />
                    <h3 class="text-2xl font-serif mb-4">Have Questions?</h3>
                    <p class="text-brand-200 mb-6 leading-relaxed">If you have any questions about these Terms of Service, feel free to get in touch with our team.</p>
                    <a href="/contact" class="inline-block bg-white text-brand-900 px-8 py-3.5 rounded-full font-bold hover:bg-brand-50 transition-all">
                        Contact Us
                    </a>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
