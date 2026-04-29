<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ShoppingBag, ArrowLeft, Trash2, Plus, Minus, CheckCircle2, AlertTriangle, ShieldCheck, Truck, RefreshCw } from 'lucide-vue-next';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { cart } from '@/CartStore';
import { computed, ref, onMounted } from 'vue';

const orderSuccess = ref(false);
const form = useForm({
    customer_name: '',
    customer_phone: '',
    customer_address: '',
    items: [] as any[]
});

const submitOrder = () => {
    if (cart.items.length === 0) return;
    
    form.items = cart.items.map(item => ({
        id: item.id,
        quantity: item.quantity
    }));

    form.post(route('products.purchase'), {
        onSuccess: () => {
            orderSuccess.value = true;
            cart.clear();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });
};

onMounted(() => {
    window.scrollTo(0, 0);
});
</script>

<template>
    <PublicLayout>
        <Head title="Checkout | Style Studio Mart" />

        <div class="bg-slate-50 min-h-screen py-12 md:py-20">
            <div class="max-w-7xl mx-auto px-4">
                
                <div v-if="orderSuccess" class="max-w-2xl mx-auto bg-white rounded-[3rem] p-12 md:p-20 shadow-2xl text-center animate-in fade-in zoom-in duration-500">
                    <div class="w-24 h-24 bg-green-50 text-green-500 rounded-full flex items-center justify-center mx-auto mb-10">
                        <CheckCircle2 :size="48" />
                    </div>
                    <h1 class="text-4xl md:text-5xl font-serif text-slate-900 mb-6">Order Placed!</h1>
                    <p class="text-slate-500 text-lg mb-10 leading-relaxed">
                        Thank you for your trust. We've received your request and our team will contact you shortly at your provided phone number to confirm the delivery details.
                    </p>
                    <Link href="/products" class="inline-flex items-center justify-center space-x-3 bg-brand-900 text-white px-10 py-5 rounded-2xl font-bold hover:scale-105 transition-all shadow-xl shadow-brand-900/20">
                        <ArrowLeft :size="20" />
                        <span>Continue Shopping</span>
                    </Link>
                </div>

                <div v-else-if="cart.items.length === 0" class="max-w-2xl mx-auto bg-white rounded-[3rem] p-12 md:p-20 shadow-2xl text-center">
                    <div class="w-24 h-24 bg-brand-50 text-brand-900 rounded-full flex items-center justify-center mx-auto mb-10">
                        <ShoppingBag :size="48" />
                    </div>
                    <h1 class="text-3xl font-serif text-slate-900 mb-6">Your Cart is Empty</h1>
                    <p class="text-slate-500 text-lg mb-10">
                        Looks like you haven't added anything to your apothecary collection yet.
                    </p>
                    <Link href="/products" class="inline-flex items-center justify-center space-x-3 bg-brand-900 text-white px-10 py-5 rounded-2xl font-bold hover:scale-105 transition-all">
                        <span>Explore Products</span>
                    </Link>
                </div>

                <div v-else class="grid lg:grid-cols-12 gap-12 items-start">
                    <!-- Left: Cart Items -->
                    <div class="lg:col-span-7 space-y-8">
                        <div class="flex items-center justify-between mb-2">
                            <h2 class="text-3xl font-serif text-slate-900">Your Apothecary Bag</h2>
                            <span class="bg-brand-100 text-brand-900 px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest">
                                {{ cart.totalItems }} Items
                            </span>
                        </div>

                        <div class="space-y-4">
                            <div v-for="item in cart.items" :key="item.id" class="bg-white rounded-[2rem] p-6 shadow-sm border border-brand-100 flex items-center gap-6 group hover:shadow-md transition-all">
                                <div class="w-24 h-24 flex-shrink-0 bg-brand-50 rounded-2xl overflow-hidden">
                                    <img :src="item.image && item.image !== '/storage/undefined' ? (item.image.startsWith('http') || item.image.startsWith('/') ? item.image : `/storage/${item.image}`) : 'https://placehold.co/400x400'" class="w-full h-full object-cover" />
                                </div>
                                <div class="flex-grow">
                                    <p class="text-[10px] font-black text-brand-500 uppercase tracking-widest mb-1">{{ item.brand }}</p>
                                    <h3 class="text-lg font-serif text-slate-900 mb-2">{{ item.name }}</h3>
                                    <p class="text-xl font-bold text-brand-900">৳{{ item.price }}</p>
                                </div>
                                <div class="flex flex-col items-end gap-4">
                                    <button @click="cart.removeItem(item.id)" class="text-slate-300 hover:text-red-500 transition-colors">
                                        <Trash2 :size="20" />
                                    </button>
                                    <div class="flex items-center bg-slate-50 rounded-xl p-1 border border-slate-100">
                                        <button @click="cart.updateQuantity(item.id, item.quantity - 1)" class="p-1.5 hover:bg-white hover:text-brand-900 rounded-lg transition-all text-slate-400">
                                            <Minus :size="16" />
                                        </button>
                                        <span class="w-10 text-center font-bold text-slate-900 text-sm">{{ item.quantity }}</span>
                                        <button @click="cart.updateQuantity(item.id, item.quantity + 1)" class="p-1.5 hover:bg-white hover:text-brand-900 rounded-lg transition-all text-slate-400">
                                            <Plus :size="16" />
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <Link href="/products" class="inline-flex items-center space-x-2 text-slate-400 hover:text-brand-900 transition-colors font-bold text-sm uppercase tracking-widest px-4">
                            <ArrowLeft :size="16" />
                            <span>Add more products</span>
                        </Link>
                    </div>

                    <!-- Right: Checkout Form -->
                    <div class="lg:col-span-5 space-y-8 lg:sticky lg:top-32">
                        <div class="bg-white rounded-[3rem] p-8 md:p-10 shadow-2xl border border-brand-100">
                            <h2 class="text-2xl font-serif text-slate-900 mb-8">Shipping & Checkout</h2>
                            
                            <form @submit.prevent="submitOrder" class="space-y-6">
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4">Full Name</label>
                                    <input 
                                        v-model="form.customer_name"
                                        type="text" 
                                        required
                                        class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-brand-900/20 text-slate-900"
                                        placeholder="Enter your name"
                                    />
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4">Phone Number</label>
                                    <input 
                                        v-model="form.customer_phone"
                                        type="tel" 
                                        required
                                        class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-brand-900/20 text-slate-900"
                                        placeholder="01XXX-XXXXXX"
                                    />
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4">Delivery Address</label>
                                    <textarea 
                                        v-model="form.customer_address"
                                        required
                                        rows="3"
                                        class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-brand-900/20 text-slate-900 resize-none"
                                        placeholder="Apt, Street, Area, City"
                                    ></textarea>
                                </div>

                                <div class="bg-red-50 border border-red-100 rounded-2xl p-4 flex gap-4 items-start animate-pulse">
                                    <div class="bg-white p-2 rounded-xl text-red-500 shadow-sm">
                                        <AlertTriangle :size="20" />
                                    </div>
                                    <p class="text-[11px] font-bold text-red-600 leading-relaxed uppercase tracking-wider">
                                        Note: Order price may increase based on delivery location. We will call you soon to confirm the final amount.
                                    </p>
                                </div>

                                <div class="pt-6 border-t border-slate-100 space-y-4">
                                    <div class="flex justify-between items-center px-2">
                                        <span class="text-slate-500 font-medium">Subtotal</span>
                                        <span class="text-slate-900 font-bold">৳{{ cart.subtotal }}</span>
                                    </div>
                                    <div class="flex justify-between items-center px-2">
                                        <span class="text-slate-500 font-medium">Delivery</span>
                                        <span class="text-brand-600 text-xs font-black uppercase tracking-widest italic">Calculated at Call</span>
                                    </div>
                                    <div class="flex justify-between items-center px-2 pt-4 border-t border-slate-50">
                                        <span class="text-slate-900 font-serif text-2xl">Total Price</span>
                                        <span class="text-brand-900 font-bold text-3xl">৳{{ cart.subtotal }}</span>
                                    </div>
                                </div>

                                <button 
                                    type="submit" 
                                    :disabled="form.processing"
                                    class="w-full bg-brand-900 text-white py-6 rounded-2xl text-xl font-bold shadow-2xl shadow-brand-900/40 hover:scale-[1.02] active:scale-95 disabled:opacity-50 transition-all mt-4"
                                >
                                    <span v-if="form.processing">Processing...</span>
                                    <span v-else>Confirm Order</span>
                                </button>
                            </form>

                            <div class="mt-10 grid grid-cols-3 gap-4 pt-10 border-t border-slate-50">
                                <div class="flex flex-col items-center text-center">
                                    <div class="p-3 bg-brand-50 rounded-xl text-brand-900 mb-2"><ShieldCheck :size="18" /></div>
                                    <p class="text-[8px] font-black uppercase tracking-widest text-slate-400">Secure</p>
                                </div>
                                <div class="flex flex-col items-center text-center">
                                    <div class="p-3 bg-brand-50 rounded-xl text-brand-900 mb-2"><Truck :size="18" /></div>
                                    <p class="text-[8px] font-black uppercase tracking-widest text-slate-400">Fast</p>
                                </div>
                                <div class="flex flex-col items-center text-center">
                                    <div class="p-3 bg-brand-50 rounded-xl text-brand-900 mb-2"><RefreshCw :size="18" /></div>
                                    <p class="text-[8px] font-black uppercase tracking-widest text-slate-400">Support</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
