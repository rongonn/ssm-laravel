<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';
import { CheckCircle2, X } from 'lucide-vue-next';
import { usePage } from '@inertiajs/vue3';

const show = ref(false);
const message = ref('');
const page = usePage();

const hideToast = () => {
    show.value = false;
};

watch(() => page.props.flash, (flash: any) => {
    if (flash?.success) {
        message.value = flash.success;
        show.value = true;
        setTimeout(hideToast, 5000);
    }
}, { immediate: true, deep: true });
</script>

<template>
    <Transition
        enter-active-class="transform ease-out duration-300 transition"
        enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
        leave-active-class="transition ease-in duration-100"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div v-if="show" class="fixed bottom-10 right-10 z-[200] max-w-md w-full bg-white rounded-[2rem] shadow-2xl border border-brand-100 overflow-hidden animate-in slide-in-from-right-10">
            <div class="p-6 flex items-start space-x-4">
                <div class="flex-shrink-0 bg-green-50 text-green-500 p-3 rounded-2xl">
                    <CheckCircle2 :size="24" />
                </div>
                <div class="flex-grow pt-0.5">
                    <p class="text-lg font-serif text-slate-900 mb-1">Success!</p>
                    <p class="text-slate-500 text-sm leading-relaxed">{{ message }}</p>
                </div>
                <button @click="hideToast" class="flex-shrink-0 text-slate-300 hover:text-slate-500 transition-colors p-1">
                    <X :size="20" />
                </button>
            </div>
            <div class="h-1.5 bg-brand-50 w-full">
                <div class="h-full bg-brand-900 animate-progress"></div>
            </div>
        </div>
    </Transition>
</template>

<style scoped>
@keyframes progress {
    from { width: 100%; }
    to { width: 0%; }
}
.animate-progress {
    animation: progress 5s linear forwards;
}
</style>
