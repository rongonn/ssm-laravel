<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Shield, Eye, Lock, Server, UserCheck, RefreshCw } from 'lucide-vue-next';
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const settings = computed(() => page.props.settings || {});
const APP_NAME = computed(() => (settings.value as any).application_name || "Bioshah.com");
</script>

<template>
    <PublicLayout>
        <Head :title="`Privacy Policy | ${APP_NAME}`" />

        <!-- Page Hero -->
        <section class="relative bg-brand-900 text-white py-24 overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <img src="https://images.unsplash.com/photo-1516975080664-ed2fc6a32937?auto=format&fit=crop&q=80&w=2000" class="w-full h-full object-cover" alt="" />
            </div>
            <div class="relative z-10 max-w-4xl mx-auto px-4 text-center">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur px-5 py-2 rounded-full text-brand-200 text-sm font-semibold uppercase tracking-widest mb-6">
                    <Shield :size="16" />
                    <span>Legal</span>
                </div>
                <h1 class="text-5xl md:text-6xl font-serif mb-6">Privacy Policy</h1>
                <p class="text-brand-200 text-lg leading-relaxed">Last updated: January 1, {{ new Date().getFullYear() }}</p>
            </div>
        </section>

        <!-- Content -->
        <section class="py-20 bg-white">
            <div class="max-w-4xl mx-auto px-4">

                <!-- Intro -->
                <div class="prose prose-lg max-w-none mb-16">
                    <p class="text-slate-600 text-lg leading-relaxed">
                        At <strong class="text-brand-900">{{ APP_NAME }}</strong>, your privacy is our priority. This Privacy Policy explains how we collect, use, and safeguard your personal information when you visit our website or use our services.
                    </p>
                </div>

                <!-- Sections -->
                <div class="space-y-12">

                    <!-- Section 1 -->
                    <div class="flex gap-6 p-8 bg-brand-50 rounded-3xl">
                        <div class="flex-shrink-0 w-12 h-12 bg-brand-900 rounded-2xl flex items-center justify-center">
                            <Eye :size="22" class="text-white" />
                        </div>
                        <div>
                            <h2 class="text-2xl font-serif text-slate-900 mb-4">Information We Collect</h2>
                            <p class="text-slate-600 leading-relaxed mb-4">We may collect the following types of information when you use our services:</p>
                            <ul class="space-y-2 text-slate-600">
                                <li class="flex items-start gap-2"><span class="text-brand-500 font-bold mt-1">•</span> <span><strong>Personal identification</strong> — Name, email address, phone number when you fill out contact forms or register.</span></li>
                                <li class="flex items-start gap-2"><span class="text-brand-500 font-bold mt-1">•</span> <span><strong>Usage data</strong> — Pages visited, time spent on site, browser type, and device information collected automatically.</span></li>
                                <li class="flex items-start gap-2"><span class="text-brand-500 font-bold mt-1">•</span> <span><strong>Communication data</strong> — Messages or inquiries you send us through our contact forms.</span></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Section 2 -->
                    <div class="flex gap-6 p-8 bg-slate-50 rounded-3xl">
                        <div class="flex-shrink-0 w-12 h-12 bg-brand-900 rounded-2xl flex items-center justify-center">
                            <UserCheck :size="22" class="text-white" />
                        </div>
                        <div>
                            <h2 class="text-2xl font-serif text-slate-900 mb-4">How We Use Your Information</h2>
                            <p class="text-slate-600 leading-relaxed mb-4">We use the information we collect to:</p>
                            <ul class="space-y-2 text-slate-600">
                                <li class="flex items-start gap-2"><span class="text-brand-500 font-bold mt-1">•</span> <span>Provide and improve our salon services and online experience.</span></li>
                                <li class="flex items-start gap-2"><span class="text-brand-500 font-bold mt-1">•</span> <span>Respond to inquiries and customer service requests.</span></li>
                                <li class="flex items-start gap-2"><span class="text-brand-500 font-bold mt-1">•</span> <span>Send promotional communications (only with your consent).</span></li>
                                <li class="flex items-start gap-2"><span class="text-brand-500 font-bold mt-1">•</span> <span>Analyze website performance and improve user experience.</span></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Section 3 -->
                    <div class="flex gap-6 p-8 bg-brand-50 rounded-3xl">
                        <div class="flex-shrink-0 w-12 h-12 bg-brand-900 rounded-2xl flex items-center justify-center">
                            <Server :size="22" class="text-white" />
                        </div>
                        <div>
                            <h2 class="text-2xl font-serif text-slate-900 mb-4">Data Storage & Sharing</h2>
                            <p class="text-slate-600 leading-relaxed mb-4">
                                Your data is stored securely on our servers. We do <strong>not</strong> sell, trade, or rent your personal information to third parties. We may share anonymized, aggregated data for analytical purposes only.
                            </p>
                            <p class="text-slate-600 leading-relaxed">
                                We may disclose your information only if required by law or to protect the rights and safety of {{ APP_NAME }}, its customers, or others.
                            </p>
                        </div>
                    </div>

                    <!-- Section 4 -->
                    <div class="flex gap-6 p-8 bg-slate-50 rounded-3xl">
                        <div class="flex-shrink-0 w-12 h-12 bg-brand-900 rounded-2xl flex items-center justify-center">
                            <Lock :size="22" class="text-white" />
                        </div>
                        <div>
                            <h2 class="text-2xl font-serif text-slate-900 mb-4">Security</h2>
                            <p class="text-slate-600 leading-relaxed">
                                We implement industry-standard security measures to protect your personal information from unauthorized access, disclosure, or destruction. While we strive to use commercially acceptable means to protect your data, no method of transmission over the internet is 100% secure.
                            </p>
                        </div>
                    </div>

                    <!-- Section 5 -->
                    <div class="flex gap-6 p-8 bg-brand-50 rounded-3xl">
                        <div class="flex-shrink-0 w-12 h-12 bg-brand-900 rounded-2xl flex items-center justify-center">
                            <RefreshCw :size="22" class="text-white" />
                        </div>
                        <div>
                            <h2 class="text-2xl font-serif text-slate-900 mb-4">Changes to This Policy</h2>
                            <p class="text-slate-600 leading-relaxed">
                                We may update this Privacy Policy from time to time. Changes will be posted on this page with an updated revision date. We encourage you to review this page periodically to stay informed about how we protect your information.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Contact -->
                <div class="mt-16 p-10 bg-brand-900 text-white rounded-3xl text-center">
                    <h3 class="text-2xl font-serif mb-4">Questions or Concerns?</h3>
                    <p class="text-brand-200 mb-6 leading-relaxed">If you have any questions about this Privacy Policy or how we handle your data, please don't hesitate to reach out.</p>
                    <a href="/contact" class="inline-block bg-white text-brand-900 px-8 py-3.5 rounded-full font-bold hover:bg-brand-50 transition-all">
                        Contact Us
                    </a>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
