<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import { 
    Scissors, Package, Users, MessageSquare, LogOut, Plus, Trash2, Edit,
    Image as ImageIcon, XCircle, Loader2, CheckCircle2, AlertTriangle,
    Eye, EyeOff, Menu, ChevronLeft, ChevronRight, X, Tag, Clock,
    ExternalLink, Facebook, Instagram, MessageCircle, Info
} from 'lucide-vue-next';
import { supabase, uploadImage } from '@/lib/supabase';

type Tab = 'services' | 'products' | 'team' | 'testimonials' | 'gallery';

interface AlertState {
    show: boolean;
    type: 'success' | 'error' | 'confirm';
    title: string;
    message: string;
    onConfirm?: () => void;
}

const activeTab = ref<Tab>('services');
const data = ref<any[]>([]);
const loading = ref(true);
const isSaving = ref(false);
const showModal = ref(false);
const showViewModal = ref(false);
const viewingItem = ref<any>(null);
const editingId = ref<string | null>(null);
const imageFile = ref<File | null>(null);
const formData = ref<any>({});
const LOGO_URL = "https://wbnrfhmbwtldxhnauggx.supabase.co/storage/v1/object/public/assets/ssm%20logo.jpg";

const isSidebarOpen = ref(false);
const isSidebarCollapsed = ref(false);

const alert = ref<AlertState>({
    show: false,
    type: 'success',
    title: '',
    message: ''
});

const fetchData = async () => {
    loading.value = true;
    let query = supabase.from(activeTab.value).select('*');
    query = query.order('created_at', { ascending: false });
    const { data: result, error } = await query;
    if (!error && result) data.value = result;
    loading.value = false;
};

const showAlert = (type: 'success' | 'error' | 'confirm', title: string, message: string, onConfirm?: () => void) => {
    alert.value = { show: true, type, title, message, onConfirm };
    if (type !== 'confirm') {
        setTimeout(() => alert.value.show = false, 2500);
    }
};

const handleSignOut = () => {
    router.post(route('logout'));
};

const toggleProductStatus = async (item: any) => {
    const newStatus = !item.is_active;
    const { error } = await supabase.from('products').update({ is_active: newStatus }).eq('id', item.id);
    if (!error) {
        showAlert('success', 'Status Updated', `Product is now ${newStatus ? 'Public' : 'Hidden'}`);
        fetchData();
    } else {
        showAlert('error', 'Update Failed', error.message);
    }
};

const resetForm = () => {
    showModal.value = false;
    editingId.value = null;
    formData.value = {};
    imageFile.value = null;
};

const handleSave = async () => {
    isSaving.value = true;
    let imageUrl = formData.value.image_url || formData.value.avatar_url || '';
    
    if (imageFile.value) {
        const uploaded = await uploadImage(imageFile.value, 'assets');
        if (uploaded) imageUrl = uploaded;
        else {
            showAlert('error', 'Upload Failed', 'Storage policy might be blocking the upload.');
            isSaving.value = false;
            return;
        }
    }

    const payload: any = { ...formData.value };
    delete payload.id;
    delete payload.created_at;
    
    if (activeTab.value === 'testimonials') payload.avatar_url = imageUrl;
    else payload.image_url = imageUrl;

    if (activeTab.value === 'team' && typeof payload.specialty === 'string') {
        payload.specialty = payload.specialty.split(',').map((s: string) => s.trim());
    }

    if (activeTab.value === 'products' && payload.is_active === undefined) payload.is_active = true;

    let error;
    if (editingId.value) {
        const { error: updateError } = await supabase.from(activeTab.value).update(payload).eq('id', editingId.value);
        error = updateError;
    } else {
        const { error: insertError } = await supabase.from(activeTab.value).insert([payload]);
        error = insertError;
    }

    if (error) showAlert('error', 'Operation Failed', error.message);
    else {
        showAlert('success', 'Saved Successfully', `The ${getSingular(activeTab.value)} has been updated.`);
        resetForm();
        fetchData();
    }
    isSaving.value = false;
};

const executeDelete = async (id: string) => {
    loading.value = true;
    const { error } = await supabase.from(activeTab.value).delete().match({ id });
    if (!error) {
        showAlert('success', 'Deleted', 'Record removed from database.');
        fetchData();
    } else {
        showAlert('error', 'Delete Failed', error.message);
        loading.value = false;
    }
};

const handleDelete = (id: string) => {
    showAlert('confirm', 'Delete Permanently?', 'This record will be gone forever.', () => executeDelete(id));
};

const openEditModal = (item: any) => {
    editingId.value = item.id;
    const itemCopy = { ...item };
    if (activeTab.value === 'team' && Array.isArray(itemCopy.specialty)) {
        itemCopy.specialty = itemCopy.specialty.join(', ');
    }
    formData.value = itemCopy;
    showModal.value = true;
};

const openViewModal = (item: any) => {
    viewingItem.value = item;
    showViewModal.value = true;
};

const getSingular = (tab: Tab) => {
    switch (tab) {
        case 'services': return 'Service';
        case 'products': return 'Product';
        case 'team': return 'Team Member';
        case 'testimonials': return 'Testimonial';
        case 'gallery': return 'Gallery Item';
        default: return 'Item';
    }
};

const getImageGuidance = () => {
    switch (activeTab.value) {
        case 'gallery':
            return { ratio: '1:1 (Square)', resolution: 'Min 1080x1080px', tip: 'Square images look best in the grid.' };
        case 'services':
        case 'products':
            return { ratio: '4:5 (Portrait) or 1:1', resolution: 'Min 800x1000px', tip: 'Use high-resolution images for zoom effects.' };
        case 'team':
            return { ratio: '3:4 (Portrait)', resolution: 'Min 800x1067px', tip: 'Portrait orientation fits the profile cards perfectly.' };
        case 'testimonials':
            return { ratio: '1:1 (Avatar)', resolution: 'Min 300x300px', tip: 'Ensure the client\'s face is centered.' };
        default:
            return { ratio: '1:1', resolution: 'Min 800x800px', tip: 'Clear, bright images are highly recommended.' };
    }
};

const navItems = [
    { id: 'services', icon: Scissors, label: 'Services' },
    { id: 'products', icon: Package, label: 'Products' },
    { id: 'team', icon: Users, label: 'Team' },
    { id: 'testimonials', icon: MessageSquare, label: 'Testimonials' },
    { id: 'gallery', icon: ImageIcon, label: 'Gallery' },
];

onMounted(() => {
    fetchData();
});

watch(activeTab, () => {
    fetchData();
    isSidebarOpen.value = false;
});

const onFileChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        imageFile.value = target.files[0];
    }
};
</script>

<template>
    <div class="min-h-screen bg-slate-50 flex flex-col md:flex-row">
        <Head title="Admin Dashboard" />

        <!-- Mobile Sidebar Overlay -->
        <div 
            v-if="isSidebarOpen"
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[60] md:hidden"
            @click="isSidebarOpen = false"
        />

        <!-- Sidebar Navigation -->
        <aside :class="[
            'fixed md:sticky top-0 inset-y-0 left-0 z-[70] bg-slate-900 text-white flex flex-col shadow-2xl transition-all duration-300',
            isSidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0',
            isSidebarCollapsed ? 'md:w-20' : 'w-72 md:w-64'
        ]">
            <div :class="['p-8 border-b border-slate-800 flex items-center justify-between', isSidebarCollapsed ? 'justify-center p-6' : '']">
                <div v-if="!isSidebarCollapsed" class="flex items-center space-x-3 overflow-hidden">
                    <img :src="LOGO_URL" alt="Logo" class="w-10 h-10 rounded-lg object-cover border border-slate-700 shrink-0" />
                    <h1 class="text-xl font-serif font-bold tracking-tight text-brand-300 uppercase truncate">Admin Hub</h1>
                </div>
                <img v-else :src="LOGO_URL" alt="Logo" class="w-8 h-8 rounded-lg object-cover border border-slate-700 shrink-0" />
                
                <button 
                    @click="isSidebarCollapsed = !isSidebarCollapsed"
                    class="hidden md:flex p-2 hover:bg-slate-800 rounded-lg text-slate-400 transition-colors ml-2"
                >
                    <ChevronRight v-if="isSidebarCollapsed" :size="20" />
                    <ChevronLeft v-else :size="20" />
                </button>
                <button 
                    @click="isSidebarOpen = false"
                    class="md:hidden p-2 text-slate-400 hover:text-white"
                >
                    <X :size="24" />
                </button>
            </div>
            
            <nav class="flex-grow p-4 space-y-2 overflow-y-auto">
                <button 
                    v-for="item in navItems"
                    :key="item.id"
                    @click="activeTab = item.id as Tab"
                    :class="[
                        'w-full flex items-center px-4 py-3 rounded-xl transition-all group relative',
                        activeTab === item.id ? 'bg-white text-slate-900 shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white',
                        isSidebarCollapsed ? 'justify-center px-2' : 'space-x-3'
                    ]"
                    :title="isSidebarCollapsed ? item.label : ''"
                >
                    <component :is="item.icon" :size="20" class="shrink-0" />
                    <span v-if="!isSidebarCollapsed" class="font-medium capitalize">{{ item.label }}</span>
                    <div v-if="isSidebarCollapsed" class="absolute left-full ml-2 px-2 py-1 bg-slate-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity">
                        {{ item.label }}
                    </div>
                </button>
            </nav>

            <div :class="['p-4 border-t border-slate-800', isSidebarCollapsed ? 'flex justify-center' : '']">
                <button 
                    @click="handleSignOut" 
                    :class="[
                        'w-full flex items-center px-4 py-3 rounded-xl text-red-400 hover:bg-red-500/10 transition-all font-medium',
                        isSidebarCollapsed ? 'justify-center px-2' : 'space-x-3'
                    ]"
                    :title="isSidebarCollapsed ? 'Sign Out' : ''"
                >
                    <LogOut :size="20" class="shrink-0" />
                    <span v-if="!isSidebarCollapsed">Sign Out</span>
                </button>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-grow min-h-screen">
            <!-- Mobile Header -->
            <div class="md:hidden bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between sticky top-0 z-50">
                <button 
                    @click="isSidebarOpen = true"
                    class="p-2 text-slate-600 hover:bg-slate-100 rounded-lg"
                >
                    <Menu :size="24" />
                </button>
                <div class="flex items-center space-x-2">
                    <img :src="LOGO_URL" alt="Logo" class="w-8 h-8 rounded-lg object-cover" />
                    <span class="font-serif font-bold text-brand-900 uppercase text-sm">Style Studio Admin</span>
                </div>
                <div class="w-10" />
            </div>

            <div class="p-6 lg:p-12 max-w-7xl mx-auto">
                <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 mb-12">
                    <div>
                        <h2 class="text-4xl font-serif text-slate-900 font-bold capitalize">{{ activeTab }}</h2>
                        <p class="text-slate-500 mt-1">Manage your website's {{ activeTab }} content</p>
                    </div>
                    <button 
                        @click="showModal = true; editingId = null; formData = {}; imageFile = null;"
                        class="w-full sm:w-auto bg-brand-900 text-white px-8 py-4 rounded-2xl flex items-center justify-center space-x-2 font-bold shadow-xl shadow-brand-900/20 hover:scale-105 active:scale-95 transition-all"
                    >
                        <Plus :size="20" />
                        <span>Create {{ getSingular(activeTab) }}</span>
                    </button>
                </header>

                <!-- Data Table -->
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left min-w-[600px]">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200">
                                    <th class="px-8 py-5 font-bold text-slate-700 uppercase text-xs tracking-wider">Preview</th>
                                    <th class="px-8 py-5 font-bold text-slate-700 uppercase text-xs tracking-wider">Info</th>
                                    <th class="px-8 py-5 font-bold text-slate-700 uppercase text-xs tracking-wider">Details</th>
                                    <th class="px-8 py-5 font-bold text-slate-700 uppercase text-xs tracking-wider text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr v-if="loading">
                                    <td colSpan="4" class="p-20 text-center"><Loader2 class="animate-spin mx-auto w-10 h-10 text-brand-900" /></td>
                                </tr>
                                <tr v-else-if="data.length === 0">
                                    <td colSpan="4" class="p-20 text-center text-slate-400 italic">No items found in {{ activeTab }}.</td>
                                </tr>
                                <tr v-else v-for="item in data" :key="item.id" :class="['hover:bg-slate-50/80 transition-colors group', activeTab === 'products' && !item.is_active ? 'opacity-60 bg-slate-50/50' : '']">
                                    <td class="px-8 py-5">
                                        <div 
                                            @click="openViewModal(item)"
                                            class="w-16 h-16 rounded-2xl overflow-hidden border-2 border-slate-100 group-hover:border-brand-200 transition-all cursor-zoom-in"
                                        >
                                            <img :src="item.image_url || item.avatar_url || 'https://placehold.co/100'" class="w-full h-full object-cover" alt="" />
                                        </div>
                                    </td>
                                    <td class="px-8 py-5">
                                        <button 
                                            @click="openViewModal(item)"
                                            class="font-bold text-slate-900 text-lg leading-tight hover:text-brand-700 transition-colors text-left"
                                        >
                                            {{ item.name || item.author || item.title }}
                                        </button>
                                        <div class="flex items-center space-x-2 mt-1">
                                            <span class="text-xs font-black text-brand-600 uppercase tracking-widest inline-block">
                                                {{ item.category || item.role || (item.rating ? `${item.rating} Star Review` : '') }}
                                            </span>
                                            <span v-if="activeTab === 'products'" :class="['px-2 py-0.5 rounded text-[10px] font-bold uppercase', item.is_active ? 'bg-green-100 text-green-700' : 'bg-slate-200 text-slate-600']">
                                                {{ item.is_active ? 'Active' : 'Hidden' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5">
                                        <p class="text-slate-500 text-sm line-clamp-1 max-w-xs">{{ item.description || item.content || item.bio || item.category || 'No details available.' }}</p>
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        <div class="inline-flex space-x-2">
                                            <button 
                                                @click="openViewModal(item)" 
                                                class="p-3 text-slate-400 hover:text-brand-600 bg-slate-50 rounded-xl hover:bg-brand-50 transition-all"
                                                title="Quick View"
                                            >
                                                <Eye :size="18" />
                                            </button>
                                            <button v-if="activeTab === 'products'" @click="toggleProductStatus(item)" class="p-3 text-slate-400 hover:text-brand-600 bg-slate-50 rounded-xl hover:bg-brand-50 transition-all" :title="item.is_active ? 'Hide Product' : 'Show Product'">
                                                <EyeOff v-if="item.is_active" :size="18" />
                                                <ImageIcon v-else :size="18" />
                                            </button>
                                            <button @click="openEditModal(item)" class="p-3 text-slate-400 hover:text-brand-600 bg-slate-50 rounded-xl hover:bg-brand-50 transition-all">
                                                <Edit :size="18" />
                                            </button>
                                            <button @click="handleDelete(item.id)" class="p-3 text-slate-400 hover:text-red-500 bg-slate-50 rounded-xl hover:bg-red-50 transition-all">
                                                <Trash2 :size="18" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>

        <!-- Item View Modal -->
        <div v-if="showViewModal && viewingItem" class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md animate-in fade-in duration-300" @click="showViewModal = false">
            <div class="bg-white rounded-[3rem] w-full max-w-4xl max-h-[90vh] overflow-hidden shadow-2xl flex flex-col animate-in zoom-in duration-300" @click.stop>
                <div class="p-6 border-b border-slate-100 flex justify-between items-center shrink-0">
                    <h3 class="text-xl font-serif font-bold text-slate-900 uppercase tracking-tight">Resource Insight</h3>
                    <button 
                        @click="showViewModal = false"
                        class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-full transition-colors"
                    >
                        <X :size="20" />
                    </button>
                </div>
                
                <div class="overflow-y-auto p-8 lg:p-12">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                        <!-- Visual Area -->
                        <div class="space-y-6">
                            <div class="aspect-square rounded-[2rem] overflow-hidden border-4 border-slate-50 shadow-inner group relative">
                                <img 
                                    :src="viewingItem.image_url || viewingItem.avatar_url || 'https://placehold.co/400'" 
                                    class="w-full h-full object-cover" 
                                    alt="" 
                                />
                                <div class="absolute inset-0 bg-brand-900/10 group-hover:bg-transparent transition-colors" />
                            </div>
                            <div class="flex flex-wrap gap-3">
                                <span class="bg-brand-50 text-brand-700 px-4 py-2 rounded-full text-xs font-black uppercase tracking-widest border border-brand-100">
                                    {{ viewingItem.category || viewingItem.role || 'Uncategorized' }}
                                </span>
                                <span v-if="viewingItem.is_active !== undefined" :class="['px-4 py-2 rounded-full text-xs font-black uppercase tracking-widest border', viewingItem.is_active ? 'bg-green-50 text-green-700 border-green-100' : 'bg-slate-50 text-slate-500 border-slate-200']">
                                    {{ viewingItem.is_active ? 'Public' : 'Hidden' }}
                                </span>
                            </div>
                        </div>

                        <!-- Content Area -->
                        <div class="space-y-8 flex flex-col">
                            <div>
                                <p v-if="viewingItem.brand" class="text-brand-600 font-black uppercase tracking-[0.3em] text-[10px] mb-2">{{ viewingItem.brand }}</p>
                                <h2 class="text-4xl font-serif font-bold text-slate-900 leading-tight">
                                    {{ viewingItem.name || viewingItem.author || viewingItem.title }}
                                </h2>
                                <p v-if="viewingItem.price !== undefined" class="text-3xl font-bold text-brand-900 mt-4">৳{{ viewingItem.price }}</p>
                            </div>

                            <div class="space-y-4 flex-grow">
                                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">Complete Details</h4>
                                <p class="text-slate-600 leading-relaxed whitespace-pre-wrap">
                                    {{ viewingItem.description || viewingItem.content || viewingItem.bio || "No extended details provided for this entry." }}
                                </p>
                                
                                <div v-if="viewingItem.duration" class="flex items-center space-x-2 text-slate-500 bg-slate-50 p-3 rounded-xl inline-flex">
                                    <Clock :size="16" />
                                    <span class="text-sm font-bold">{{ viewingItem.duration }}</span>
                                </div>

                                <div v-if="viewingItem.specialty" class="flex flex-wrap gap-2 pt-2">
                                    <span v-for="(s, i) in (Array.isArray(viewingItem.specialty) ? viewingItem.specialty : (viewingItem.specialty || '').split(','))" :key="i" class="flex items-center space-x-1 text-[10px] font-bold text-brand-800 bg-brand-50 px-2 py-1 rounded">
                                        <Tag :size="10" />
                                        <span>{{ s.trim() }}</span>
                                    </span>
                                </div>
                            </div>

                            <div class="pt-8 border-t border-slate-100 flex gap-4">
                                <button 
                                    @click="showViewModal = false; openEditModal(viewingItem);"
                                    class="flex-1 py-4 bg-brand-900 text-white rounded-2xl font-bold flex items-center justify-center space-x-2 shadow-xl shadow-brand-900/10 hover:scale-[1.02] active:scale-95 transition-all"
                                >
                                    <Edit :size="18" />
                                    <span>Edit Item</span>
                                </button>
                                <a 
                                    :href="activeTab === 'products' ? `/products/${viewingItem.id}` : '#'" 
                                    target="_blank" 
                                    rel="noopener noreferrer"
                                    class="p-4 bg-slate-100 text-slate-600 rounded-2xl hover:bg-slate-200 transition-colors"
                                    title="View on Site"
                                >
                                    <ExternalLink :size="20" />
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create/Edit Modal Overlay -->
        <div v-if="showModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-md p-4" @click="resetForm">
            <div class="bg-white rounded-[2.5rem] w-full max-w-2xl overflow-hidden shadow-2xl animate-in zoom-in duration-300" @click.stop>
                <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/30">
                    <h3 class="text-2xl font-serif font-bold text-slate-900">{{ editingId ? 'Modify' : 'Create New' }} {{ getSingular(activeTab) }}</h3>
                    <button @click="resetForm" class="text-slate-300 hover:text-slate-900 p-2 bg-white border border-slate-100 rounded-full transition-colors">
                        <X :size="24" />
                    </button>
                </div>
                <form @submit.prevent="handleSave" class="p-8 space-y-8 max-h-[70vh] overflow-y-auto">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                        
                        <!-- Dynamic Fields Based on activeTab -->
                        <template v-if="activeTab === 'gallery'">
                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Image Title</label>
                                <input type="text" required v-model="formData.title" class="w-full p-4 bg-slate-50 border rounded-xl outline-none focus:ring-2 focus:ring-brand-500" />
                            </div>
                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Category</label>
                                <select v-model="formData.category" class="w-full p-4 bg-slate-50 border rounded-xl outline-none focus:ring-2 focus:ring-brand-500">
                                    <option v-for="cat in ['Hair', 'Skin', 'Nails', 'Interior', 'Events']" :key="cat" :value="cat">{{ cat }}</option>
                                </select>
                            </div>
                        </template>

                        <template v-if="activeTab === 'services'">
                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Service Name</label>
                                <input type="text" required v-model="formData.name" class="w-full p-4 bg-slate-50 border rounded-xl outline-none focus:ring-2 focus:ring-brand-500" />
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Price (৳)</label>
                                <input type="number" required v-model="formData.price" class="w-full p-4 bg-slate-50 border rounded-xl outline-none focus:ring-2 focus:ring-brand-500" />
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Duration</label>
                                <input type="text" v-model="formData.duration" class="w-full p-4 bg-slate-50 border rounded-xl outline-none focus:ring-2 focus:ring-brand-500" placeholder="60 min" />
                            </div>
                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Category</label>
                                <select v-model="formData.category" class="w-full p-4 bg-slate-50 border rounded-xl outline-none focus:ring-2 focus:ring-brand-500">
                                    <option v-for="cat in ['Hair', 'Skin', 'Nails', 'Massage', 'Other']" :key="cat" :value="cat">{{ cat }}</option>
                                </select>
                            </div>
                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Description</label>
                                <textarea v-model="formData.description" class="w-full p-4 bg-slate-50 border rounded-xl outline-none focus:ring-2 focus:ring-brand-500" rows="4" />
                            </div>
                        </template>

                        <template v-if="activeTab === 'products'">
                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Product Name</label>
                                <input type="text" required v-model="formData.name" class="w-full p-4 bg-slate-50 border rounded-xl outline-none focus:ring-2 focus:ring-brand-500" />
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Brand</label>
                                <input type="text" v-model="formData.brand" class="w-full p-4 bg-slate-50 border rounded-xl outline-none focus:ring-2 focus:ring-brand-500" />
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Price (৳)</label>
                                <input type="number" required v-model="formData.price" class="w-full p-4 bg-slate-50 border rounded-xl outline-none focus:ring-2 focus:ring-brand-500" />
                            </div>
                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Category</label>
                                <select v-model="formData.category" class="w-full p-4 bg-slate-50 border rounded-xl outline-none focus:ring-2 focus:ring-brand-500">
                                    <option v-for="cat in ['Hair Care', 'Skin Care', 'Fragrance', 'Tools', 'Body']" :key="cat" :value="cat">{{ cat }}</option>
                                </select>
                            </div>
                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Description</label>
                                <textarea v-model="formData.description" class="w-full p-4 bg-slate-50 border rounded-xl outline-none focus:ring-2 focus:ring-brand-500" rows="4" />
                            </div>
                            <div class="col-span-2 flex items-center space-x-3 bg-slate-50 p-4 rounded-xl">
                                <input 
                                    type="checkbox" 
                                    id="is_active" 
                                    v-model="formData.is_active"
                                    class="w-5 h-5 accent-brand-900 rounded"
                                />
                                <label htmlFor="is_active" class="text-sm font-bold text-slate-700 uppercase">Visible on Public Site</label>
                            </div>
                        </template>

                        <template v-if="activeTab === 'team'">
                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Staff Name</label>
                                <input type="text" required v-model="formData.name" class="w-full p-4 bg-slate-50 border rounded-xl outline-none focus:ring-2 focus:ring-brand-500" />
                            </div>
                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Role</label>
                                <input type="text" required v-model="formData.role" class="w-full p-4 bg-slate-50 border rounded-xl outline-none focus:ring-2 focus:ring-brand-500" />
                            </div>
                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Specialties (comma separated)</label>
                                <input type="text" v-model="formData.specialty" class="w-full p-4 bg-slate-50 border rounded-xl outline-none focus:ring-2 focus:ring-brand-500" placeholder="Haircut, Styling, Coloring" />
                            </div>
                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Bio</label>
                                <textarea v-model="formData.bio" class="w-full p-4 bg-slate-50 border rounded-xl outline-none focus:ring-2 focus:ring-brand-500" rows="4" />
                            </div>
                            <div class="col-span-2 space-y-4">
                                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">Social Media Links</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div>
                                        <label class="flex items-center space-x-2 text-[10px] font-bold text-slate-500 uppercase mb-2">
                                            <Facebook :size="12" />
                                            <span>Facebook URL</span>
                                        </label>
                                        <input type="url" v-model="formData.facebook_url" class="w-full p-3 bg-slate-50 border rounded-xl text-sm outline-none focus:ring-2 focus:ring-brand-500" placeholder="https://facebook.com/..." />
                                    </div>
                                    <div>
                                        <label class="flex items-center space-x-2 text-[10px] font-bold text-slate-500 uppercase mb-2">
                                            <Instagram :size="12" />
                                            <span>Instagram URL</span>
                                        </label>
                                        <input type="url" v-model="formData.instagram_url" class="w-full p-3 bg-slate-50 border rounded-xl text-sm outline-none focus:ring-2 focus:ring-brand-500" placeholder="https://instagram.com/..." />
                                    </div>
                                    <div>
                                        <label class="flex items-center space-x-2 text-[10px] font-bold text-slate-500 uppercase mb-2">
                                            <MessageCircle :size="12" />
                                            <span>WhatsApp URL</span>
                                        </label>
                                        <input type="url" v-model="formData.whatsapp_url" class="w-full p-3 bg-slate-50 border rounded-xl text-sm outline-none focus:ring-2 focus:ring-brand-500" placeholder="https://wa.me/..." />
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template v-if="activeTab === 'testimonials'">
                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Client Name</label>
                                <input type="text" required v-model="formData.author" class="w-full p-4 bg-slate-50 border rounded-xl outline-none focus:ring-2 focus:ring-brand-500" />
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Rating (1-5)</label>
                                <input type="number" min="1" max="5" v-model="formData.rating" class="w-full p-4 bg-slate-50 border rounded-xl outline-none focus:ring-2 focus:ring-brand-500" />
                            </div>
                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Their Review</label>
                                <textarea required v-model="formData.content" class="w-full p-4 bg-slate-50 border rounded-xl outline-none focus:ring-2 focus:ring-brand-500" rows="4" />
                            </div>
                        </template>
                        
                        <!-- Upload Guidance Component -->
                        <div class="col-span-1 sm:col-span-2">
                            <div class="bg-brand-50 border border-brand-200 rounded-2xl p-4 flex items-start space-x-3 mb-4">
                                <Info class="text-brand-600 shrink-0 mt-0.5" :size="18" />
                                <div class="text-xs">
                                    <p class="font-bold text-brand-900 uppercase tracking-widest mb-1">Image Upload Guide</p>
                                    <ul class="text-slate-600 space-y-1 list-disc list-inside">
                                        <li><strong>Aspect Ratio:</strong> {{ getImageGuidance().ratio }}</li>
                                        <li><strong>Resolution:</strong> {{ getImageGuidance().resolution }}</li>
                                        <li><strong>Pro Tip:</strong> {{ getImageGuidance().tip }}</li>
                                    </ul>
                                </div>
                            </div>

                            <label class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Visual Asset</label>
                            <div class="flex flex-col sm:flex-row items-center gap-8 bg-slate-50 p-6 rounded-3xl border-2 border-dashed border-slate-200">
                                <div class="w-24 h-24 bg-white rounded-2xl flex items-center justify-center border-2 border-slate-100 overflow-hidden shadow-md shrink-0">
                                    <img v-if="imageFile || formData.image_url || formData.avatar_url" :src="imageFile ? URL.createObjectURL(imageFile) : (formData.image_url || formData.avatar_url)" class="w-full h-full object-cover" />
                                    <ImageIcon v-else class="text-slate-200 w-8 h-8" />
                                </div>
                                <div class="w-full">
                                    <input type="file" accept="image/*" @change="onFileChange" class="block w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-brand-50 file:text-brand-700 cursor-pointer" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4 pt-6">
                        <button type="button" @click="resetForm" class="w-full sm:flex-1 py-4 border-2 border-slate-100 rounded-2xl font-bold text-slate-500 hover:bg-slate-50">Discard</button>
                        <button type="submit" :disabled="isSaving" class="w-full sm:flex-1 py-4 bg-brand-900 text-white rounded-2xl font-bold shadow-2xl disabled:opacity-50 flex items-center justify-center space-x-3">
                            <Loader2 v-if="isSaving" class="animate-spin w-5 h-5" />
                            <span>{{ isSaving ? 'Processing...' : 'Save Changes' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Alert Component -->
        <div v-if="alert.show" class="fixed inset-0 z-[300] flex items-center justify-center p-6 bg-slate-900/40 backdrop-blur-sm animate-in fade-in duration-200">
            <div class="bg-white rounded-[2.5rem] p-10 max-w-sm w-full shadow-2xl text-center animate-in zoom-in duration-300">
                <div :class="['mx-auto w-20 h-20 rounded-full flex items-center justify-center mb-6', alert.type === 'success' ? 'bg-green-100 text-green-600' : alert.type === 'error' ? 'bg-red-100 text-red-600' : 'bg-brand-100 text-brand-600']">
                    <CheckCircle2 v-if="alert.type === 'success'" :size="42" />
                    <XCircle v-else-if="alert.type === 'error'" :size="42" />
                    <AlertTriangle v-else :size="42" />
                </div>
                <h3 class="text-2xl font-serif font-bold text-slate-900 mb-2">{{ alert.title }}</h3>
                <p class="text-slate-500 mb-8 leading-relaxed">{{ alert.message }}</p>
                <div v-if="alert.type === 'confirm'" class="flex space-x-4">
                    <button @click="alert.show = false" class="flex-1 py-4 bg-slate-100 text-slate-600 rounded-2xl font-bold">Cancel</button>
                    <button @click="alert.onConfirm?.(); alert.show = false;" class="flex-1 py-4 bg-red-500 text-white rounded-2xl font-bold shadow-xl shadow-red-500/20">Yes, Delete</button>
                </div>
                <button v-else @click="alert.show = false" class="w-full py-4 bg-brand-900 text-white rounded-2xl font-bold shadow-xl">Okay</button>
            </div>
        </div>
    </div>
</template>
