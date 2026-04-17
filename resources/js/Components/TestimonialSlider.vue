<script setup lang="ts">
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { Star, ChevronLeft, ChevronRight, Quote } from 'lucide-vue-next';
import type { Testimonial } from '@/types';

const props = defineProps<{
    testimonials: Testimonial[];
}>();

const currentIndex = ref(0);
let interval: any = null;

const next = () => {
    if (props.testimonials.length > 0) {
        currentIndex.value = (currentIndex.value + 1) % props.testimonials.length;
    }
};

const prev = () => {
    if (props.testimonials.length > 0) {
        currentIndex.value = (currentIndex.value - 1 + props.testimonials.length) % props.testimonials.length;
    }
};

const active = computed(() => props.testimonials[currentIndex.value]);

onMounted(() => {
    if (props.testimonials.length > 1) {
        interval = setInterval(next, 8000);
    }
});

onUnmounted(() => {
    if (interval) clearInterval(interval);
});
</script>

<template>
    <div v-if="testimonials.length === 0" class="text-center py-20 text-slate-400 italic font-serif">
        "Be the first to leave us a review..."
    </div>

    <div v-else class="relative max-w-4xl mx-auto px-4 py-16">
        <div class="flex flex-col items-center text-center">
            <Quote class="text-brand-300 w-16 h-16 mb-8 opacity-50" />
            
            <div :key="active.id" class="animate-in fade-in zoom-in duration-700">
                <div class="flex space-x-1 mb-6 justify-center">
                    <Star v-for="i in active.rating" :key="i" class="w-5 h-5 fill-brand-500 text-brand-500" />
                </div>
                
                <blockquote class="text-2xl md:text-3xl font-serif italic text-slate-800 mb-8 leading-relaxed">
                    "{{ active.content }}"
                </blockquote>
                
                <div class="flex items-center justify-center space-x-4">
                    <img 
                        :src="active.avatar_url || 'https://placehold.co/100'" 
                        :alt="active.author"
                        class="w-14 h-14 rounded-full border-2 border-brand-200 object-cover"
                    />
                    <div class="text-left">
                        <p class="font-bold text-slate-900">{{ active.author }}</p>
                        <p class="text-sm text-slate-500">Verified Client</p>
                    </div>
                </div>
            </div>

            <div v-if="testimonials.length > 1" class="flex space-x-4 mt-12">
                <button 
                    @click="prev"
                    class="p-3 rounded-full border border-brand-200 hover:bg-brand-50 text-slate-400 hover:text-brand-900 transition-all"
                >
                    <ChevronLeft :size="24" />
                </button>
                <button 
                    @click="next"
                    class="p-3 rounded-full border border-brand-200 hover:bg-brand-50 text-slate-400 hover:text-brand-900 transition-all"
                >
                    <ChevronRight :size="24" />
                </button>
            </div>
        </div>
    </div>
</template>
