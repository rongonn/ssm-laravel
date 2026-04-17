<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import { Loader2, Image as ImageIcon, X, Maximize2 } from 'lucide-vue-next';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const props = defineProps<{
    items: any[];
    categories: any[];
}>();

const page = usePage();
const settings = computed(() => page.props.settings || {});
const BANNER_URL = computed(() => (settings.value as any).gallery_banner ? `/storage/${(settings.value as any).gallery_banner}` : "https://images.unsplash.com/photo-1522337660859-02fbefca4702?auto=format&fit=crop&q=80&w=2000");

const category = ref('All');
const selectedImage = ref<string | null>(null);

const displayCategories = computed(() => {
    const cats = new Set();
    props.items.forEach(item => {
        const name = item.category_item?.name || item.category;
        if (name) cats.add(name);
    });
    return ['All', ...Array.from(cats)].sort();
});

const filteredItems = computed(() => {
    return category.value === 'All' 
        ? props.items 
        : props.items.filter(item => (item.category_item?.name || item.category) === category.value);
});
</script>

<template>
    <PublicLayout>
        <Head title="Gallery | Artistic Portfolio" />
        
        <div class="bg-brand-50 min-h-screen">
            <!-- Header -->
            <section class="bg-brand-900 text-white py-24 relative overflow-hidden">
                <div class="max-w-7xl mx-auto px-4 text-center relative z-10">
                    <h1 class="text-5xl md:text-7xl font-serif mb-6">Artistic Portfolio</h1>
                    <p class="text-xl text-brand-200 max-w-2xl mx-auto italic font-serif">
                        A visual journey through our finest transformations and studio aesthetics.
                    </p>
                </div>
                <div class="absolute inset-0 opacity-20 bg-cover bg-center" :style="{ backgroundImage: `url(${BANNER_URL})`, mixBlendMode: 'overlay' }"></div>
            </section>

            <!-- Category Filters -->
            <div class="sticky top-20 z-40 bg-white/80 backdrop-blur-md border-b border-brand-100 py-6">
                <div class="max-w-7xl mx-auto px-4 flex flex-wrap justify-center gap-4">
                    <button
                        v-for="cat in displayCategories"
                        :key="cat"
                        @click="category = cat"
                        :class="[
                            'px-8 py-2.5 rounded-full text-sm font-bold transition-all',
                            category === cat 
                            ? 'bg-brand-900 text-white shadow-lg shadow-brand-900/20' 
                            : 'bg-white text-slate-500 border border-brand-100 hover:border-brand-300'
                        ]"
                    >
                        {{ cat }}
                    </button>
                </div>
            </div>

            <!-- Gallery Grid -->
            <div class="max-w-7xl mx-auto px-4 py-20">
                <div v-if="filteredItems.length === 0" class="text-center py-24 bg-white rounded-[3rem] border-2 border-dashed border-slate-200">
                    <ImageIcon class="mx-auto text-slate-200 w-24 h-24 mb-6" />
                    <p class="text-slate-500 text-2xl font-serif italic">Our gallery for "{{ category }}" is currently being curated.</p>
                </div>
                <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div 
                        v-for="item in filteredItems"
                        :key="item.id" 
                        @click="selectedImage = item.image_url"
                        class="bg-white rounded-[2.5rem] overflow-hidden group hover:shadow-2xl transition-all duration-500 border border-brand-100 shadow-sm cursor-zoom-in"
                    >
                        <div class="relative aspect-square w-full overflow-hidden bg-slate-100">
                            <img 
                                :src="item.image_url" 
                                :alt="item.title" 
                                class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000"
                            />
                            <div class="absolute inset-0 bg-brand-900/60 opacity-0 group-hover:opacity-100 transition-all duration-500 flex flex-col items-center justify-center p-10 text-center">
                                <Maximize2 class="text-white mb-4 w-8 h-8 opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 transition-all duration-500 delay-100" />
                                <span class="text-brand-300 text-[10px] font-black uppercase tracking-widest mb-2">{{ item.category_item?.name || item.category }}</span>
                                <h3 class="text-white text-2xl font-serif font-bold leading-tight">{{ item.title || 'Untitled Work' }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
        </div>
    </PublicLayout>
</template>
