
import React, { useState, useEffect } from 'react';
import { 
  Scissors, 
  Package, 
  Users, 
  MessageSquare, 
  LogOut, 
  Plus, 
  Trash2, 
  Edit,
  Image as ImageIcon,
  XCircle,
  Loader2,
  CheckCircle2,
  AlertTriangle,
  Eye,
  EyeOff,
  Menu,
  ChevronLeft,
  ChevronRight,
  X,
  Tag,
  Clock,
  ExternalLink,
  Facebook,
  Instagram,
  MessageCircle,
  Info
} from 'lucide-react';
import { supabase, uploadImage } from '../lib/supabase';

type Tab = 'services' | 'products' | 'team' | 'testimonials' | 'gallery';

interface AlertState {
  show: boolean;
  type: 'success' | 'error' | 'confirm';
  title: string;
  message: string;
  onConfirm?: () => void;
}

const AdminDashboard: React.FC = () => {
  const [activeTab, setActiveTab] = useState<Tab>('services');
  const [data, setData] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);
  const [isSaving, setIsSaving] = useState(false);
  const [showModal, setShowModal] = useState(false);
  const [showViewModal, setShowViewModal] = useState(false);
  const [viewingItem, setViewingItem] = useState<any>(null);
  const [editingId, setEditingId] = useState<string | null>(null);
  const [imageFile, setImageFile] = useState<File | null>(null);
  const [formData, setFormData] = useState<any>({});
  const LOGO_URL = "https://wbnrfhmbwtldxhnauggx.supabase.co/storage/v1/object/public/assets/ssm%20logo.jpg";
  
  // Sidebar states
  const [isSidebarOpen, setIsSidebarOpen] = useState(false); // For mobile drawer
  const [isSidebarCollapsed, setIsSidebarCollapsed] = useState(false); // For desktop toggle
  
  const [alert, setAlert] = useState<AlertState>({
    show: false,
    type: 'success',
    title: '',
    message: ''
  });

  useEffect(() => {
    fetchData();
    // Close mobile sidebar on tab change
    setIsSidebarOpen(false);
  }, [activeTab]);

  const fetchData = async () => {
    setLoading(true);
    let query = supabase.from(activeTab).select('*');
    query = query.order('created_at', { ascending: false });
    const { data: result, error } = await query;
    if (!error && result) setData(result);
    setLoading(false);
  };

  const showAlert = (type: 'success' | 'error' | 'confirm', title: string, message: string, onConfirm?: () => void) => {
    setAlert({ show: true, type, title, message, onConfirm });
    if (type !== 'confirm') {
      setTimeout(() => setAlert(prev => ({ ...prev, show: false })), 2500);
    }
  };

  const handleSignOut = async () => {
    await supabase.auth.signOut();
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

  const handleSave = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsSaving(true);
    let imageUrl = formData.image_url || formData.avatar_url || '';
    
    if (imageFile) {
      const uploaded = await uploadImage(imageFile, 'assets');
      if (uploaded) imageUrl = uploaded;
      else {
        showAlert('error', 'Upload Failed', 'Storage policy might be blocking the upload.');
        setIsSaving(false);
        return;
      }
    }

    const payload: any = { ...formData };
    delete payload.id;
    delete payload.created_at;
    
    if (activeTab === 'testimonials') payload.avatar_url = imageUrl;
    else payload.image_url = imageUrl;

    if (activeTab === 'team' && typeof payload.specialty === 'string') {
      payload.specialty = payload.specialty.split(',').map((s: string) => s.trim());
    }

    if (activeTab === 'products' && payload.is_active === undefined) payload.is_active = true;

    let error;
    if (editingId) {
      const { error: updateError } = await supabase.from(activeTab).update(payload).eq('id', editingId);
      error = updateError;
    } else {
      const { error: insertError } = await supabase.from(activeTab).insert([payload]);
      error = insertError;
    }

    if (error) showAlert('error', 'Operation Failed', error.message);
    else {
      showAlert('success', 'Saved Successfully', `The ${getSingular(activeTab)} has been updated.`);
      resetForm();
      fetchData();
    }
    setIsSaving(false);
  };

  const resetForm = () => {
    setShowModal(false);
    setEditingId(null);
    setFormData({});
    setImageFile(null);
  };

  const executeDelete = async (id: string) => {
    setLoading(true);
    const { error } = await supabase.from(activeTab).delete().match({ id });
    if (!error) {
      showAlert('success', 'Deleted', 'Record removed from database.');
      fetchData();
    } else {
      showAlert('error', 'Delete Failed', error.message);
      setLoading(false);
    }
  };

  const handleDelete = (id: string) => {
    showAlert('confirm', 'Delete Permanently?', 'This record will be gone forever.', () => executeDelete(id));
  };

  const openEditModal = (item: any) => {
    setEditingId(item.id);
    const itemCopy = { ...item };
    if (activeTab === 'team' && Array.isArray(itemCopy.specialty)) {
        itemCopy.specialty = itemCopy.specialty.join(', ');
    }
    setFormData(itemCopy);
    setShowModal(true);
  };

  const openViewModal = (item: any) => {
    setViewingItem(item);
    setShowViewModal(true);
  };

  // Improved singularization helper to fix "tea" and "galler" bugs
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

  // Guidance logic for image uploads
  const getImageGuidance = () => {
    switch (activeTab) {
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

  const renderFormFields = () => {
    switch (activeTab) {
      case 'gallery':
        return (
          <>
            <div className="col-span-2">
              <label className="block text-xs font-bold text-slate-400 uppercase mb-2">Image Title</label>
              <input type="text" required value={formData.title || ''} onChange={e => setFormData({...formData, title: e.target.value})} className="w-full p-4 bg-slate-50 border rounded-xl outline-none focus:ring-2 focus:ring-brand-500" />
            </div>
            <div className="col-span-2">
              <label className="block text-xs font-bold text-slate-400 uppercase mb-2">Category</label>
              <select value={formData.category || 'Hair'} onChange={e => setFormData({...formData, category: e.target.value})} className="w-full p-4 bg-slate-50 border rounded-xl outline-none focus:ring-2 focus:ring-brand-500">
                <option value="Hair">Hair</option>
                <option value="Skin">Skin</option>
                <option value="Nails">Nails</option>
                <option value="Interior">Interior</option>
                <option value="Events">Events</option>
              </select>
            </div>
          </>
        );
      case 'services':
        return (
          <>
            <div className="col-span-2">
              <label className="block text-xs font-bold text-slate-400 uppercase mb-2">Service Name</label>
              <input type="text" required value={formData.name || ''} onChange={e => setFormData({...formData, name: e.target.value})} className="w-full p-4 bg-slate-50 border rounded-xl outline-none focus:ring-2 focus:ring-brand-500" />
            </div>
            <div>
              <label className="block text-xs font-bold text-slate-400 uppercase mb-2">Price (৳)</label>
              <input type="number" required value={formData.price || 0} onChange={e => setFormData({...formData, price: Number(e.target.value)})} className="w-full p-4 bg-slate-50 border rounded-xl outline-none focus:ring-2 focus:ring-brand-500" />
            </div>
            <div>
              <label className="block text-xs font-bold text-slate-400 uppercase mb-2">Duration</label>
              <input type="text" value={formData.duration || ''} onChange={e => setFormData({...formData, duration: e.target.value})} className="w-full p-4 bg-slate-50 border rounded-xl outline-none focus:ring-2 focus:ring-brand-500" placeholder="60 min" />
            </div>
            <div className="col-span-2">
              <label className="block text-xs font-bold text-slate-400 uppercase mb-2">Category</label>
              <select value={formData.category || 'Hair'} onChange={e => setFormData({...formData, category: e.target.value})} className="w-full p-4 bg-slate-50 border rounded-xl outline-none focus:ring-2 focus:ring-brand-500">
                <option value="Hair">Hair</option>
                <option value="Skin">Skin</option>
                <option value="Nails">Nails</option>
                <option value="Massage">Massage</option>
                <option value="Other">Other</option>
              </select>
            </div>
            <div className="col-span-2">
              <label className="block text-xs font-bold text-slate-400 uppercase mb-2">Description</label>
              <textarea value={formData.description || ''} onChange={e => setFormData({...formData, description: e.target.value})} className="w-full p-4 bg-slate-50 border rounded-xl outline-none focus:ring-2 focus:ring-brand-500" rows={4} />
            </div>
          </>
        );
      case 'products':
        return (
          <>
            <div className="col-span-2">
              <label className="block text-xs font-bold text-slate-400 uppercase mb-2">Product Name</label>
              <input type="text" required value={formData.name || ''} onChange={e => setFormData({...formData, name: e.target.value})} className="w-full p-4 bg-slate-50 border rounded-xl outline-none focus:ring-2 focus:ring-brand-500" />
            </div>
            <div>
              <label className="block text-xs font-bold text-slate-400 uppercase mb-2">Brand</label>
              <input type="text" value={formData.brand || ''} onChange={e => setFormData({...formData, brand: e.target.value})} className="w-full p-4 bg-slate-50 border rounded-xl outline-none focus:ring-2 focus:ring-brand-500" />
            </div>
            <div>
              <label className="block text-xs font-bold text-slate-400 uppercase mb-2">Price (৳)</label>
              <input type="number" required value={formData.price || 0} onChange={e => setFormData({...formData, price: Number(e.target.value)})} className="w-full p-4 bg-slate-50 border rounded-xl outline-none focus:ring-2 focus:ring-brand-500" />
            </div>
            <div className="col-span-2">
              <label className="block text-xs font-bold text-slate-400 uppercase mb-2">Category</label>
              <select value={formData.category || 'Hair Care'} onChange={e => setFormData({...formData, category: e.target.value})} className="w-full p-4 bg-slate-50 border rounded-xl outline-none focus:ring-2 focus:ring-brand-500">
                <option value="Hair Care">Hair Care</option>
                <option value="Skin Care">Skin Care</option>
                <option value="Fragrance">Fragrance</option>
                <option value="Tools">Tools</option>
                <option value="Body">Body</option>
              </select>
            </div>
            <div className="col-span-2">
              <label className="block text-xs font-bold text-slate-400 uppercase mb-2">Description</label>
              <textarea value={formData.description || ''} onChange={e => setFormData({...formData, description: e.target.value})} className="w-full p-4 bg-slate-50 border rounded-xl outline-none focus:ring-2 focus:ring-brand-500" rows={4} />
            </div>
            <div className="col-span-2 flex items-center space-x-3 bg-slate-50 p-4 rounded-xl">
              <input 
                type="checkbox" 
                id="is_active" 
                checked={formData.is_active !== false} 
                onChange={e => setFormData({...formData, is_active: e.target.checked})}
                className="w-5 h-5 accent-brand-900 rounded"
              />
              <label htmlFor="is_active" className="text-sm font-bold text-slate-700 uppercase">Visible on Public Site</label>
            </div>
          </>
        );
      case 'team':
        return (
          <>
            <div className="col-span-2">
              <label className="block text-xs font-bold text-slate-400 uppercase mb-2">Staff Name</label>
              <input type="text" required value={formData.name || ''} onChange={e => setFormData({...formData, name: e.target.value})} className="w-full p-4 bg-slate-50 border rounded-xl outline-none focus:ring-2 focus:ring-brand-500" />
            </div>
            <div className="col-span-2">
              <label className="block text-xs font-bold text-slate-400 uppercase mb-2">Role</label>
              <input type="text" required value={formData.role || ''} onChange={e => setFormData({...formData, role: e.target.value})} className="w-full p-4 bg-slate-50 border rounded-xl outline-none focus:ring-2 focus:ring-brand-500" />
            </div>
            <div className="col-span-2">
              <label className="block text-xs font-bold text-slate-400 uppercase mb-2">Specialties (comma separated)</label>
              <input type="text" value={formData.specialty || ''} onChange={e => setFormData({...formData, specialty: e.target.value})} className="w-full p-4 bg-slate-50 border rounded-xl outline-none focus:ring-2 focus:ring-brand-500" placeholder="Haircut, Styling, Coloring" />
            </div>
            <div className="col-span-2">
              <label className="block text-xs font-bold text-slate-400 uppercase mb-2">Bio</label>
              <textarea value={formData.bio || ''} onChange={e => setFormData({...formData, bio: e.target.value})} className="w-full p-4 bg-slate-50 border rounded-xl outline-none focus:ring-2 focus:ring-brand-500" rows={4} />
            </div>
            <div className="col-span-2 space-y-4">
              <h4 className="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">Social Media Links</h4>
              <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                  <label className="flex items-center space-x-2 text-[10px] font-bold text-slate-500 uppercase mb-2">
                    <Facebook size={12} />
                    <span>Facebook URL</span>
                  </label>
                  <input type="url" value={formData.facebook_url || ''} onChange={e => setFormData({...formData, facebook_url: e.target.value})} className="w-full p-3 bg-slate-50 border rounded-xl text-sm outline-none focus:ring-2 focus:ring-brand-500" placeholder="https://facebook.com/..." />
                </div>
                <div>
                  <label className="flex items-center space-x-2 text-[10px] font-bold text-slate-500 uppercase mb-2">
                    <Instagram size={12} />
                    <span>Instagram URL</span>
                  </label>
                  <input type="url" value={formData.instagram_url || ''} onChange={e => setFormData({...formData, instagram_url: e.target.value})} className="w-full p-3 bg-slate-50 border rounded-xl text-sm outline-none focus:ring-2 focus:ring-brand-500" placeholder="https://instagram.com/..." />
                </div>
                <div>
                  <label className="flex items-center space-x-2 text-[10px] font-bold text-slate-500 uppercase mb-2">
                    <MessageCircle size={12} />
                    <span>WhatsApp URL</span>
                  </label>
                  <input type="url" value={formData.whatsapp_url || ''} onChange={e => setFormData({...formData, whatsapp_url: e.target.value})} className="w-full p-3 bg-slate-50 border rounded-xl text-sm outline-none focus:ring-2 focus:ring-brand-500" placeholder="https://wa.me/..." />
                </div>
              </div>
            </div>
          </>
        );
      case 'testimonials':
        return (
          <>
            <div className="col-span-2">
              <label className="block text-xs font-bold text-slate-400 uppercase mb-2">Client Name</label>
              <input type="text" required value={formData.author || ''} onChange={e => setFormData({...formData, author: e.target.value})} className="w-full p-4 bg-slate-50 border rounded-xl outline-none focus:ring-2 focus:ring-brand-500" />
            </div>
            <div>
              <label className="block text-xs font-bold text-slate-400 uppercase mb-2">Rating (1-5)</label>
              <input type="number" min="1" max="5" value={formData.rating || 5} onChange={e => setFormData({...formData, rating: Number(e.target.value)})} className="w-full p-4 bg-slate-50 border rounded-xl outline-none focus:ring-2 focus:ring-brand-500" />
            </div>
            <div className="col-span-2">
              <label className="block text-xs font-bold text-slate-400 uppercase mb-2">Their Review</label>
              <textarea required value={formData.content || ''} onChange={e => setFormData({...formData, content: e.target.value})} className="w-full p-4 bg-slate-50 border rounded-xl outline-none focus:ring-2 focus:ring-brand-500" rows={4} />
            </div>
          </>
        );
    }
  };

  const navItems = [
    { id: 'services', icon: Scissors, label: 'Services' },
    { id: 'products', icon: Package, label: 'Products' },
    { id: 'team', icon: Users, label: 'Team' },
    { id: 'testimonials', icon: MessageSquare, label: 'Testimonials' },
    { id: 'gallery', icon: ImageIcon, label: 'Gallery' },
  ];

  return (
    <div className="min-h-screen bg-slate-50 flex flex-col md:flex-row">
      {/* Mobile Sidebar Overlay */}
      {isSidebarOpen && (
        <div 
          className="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[60] md:hidden"
          onClick={() => setIsSidebarOpen(false)}
        />
      )}

      {/* Sidebar Navigation */}
      <aside className={`
        fixed md:sticky top-0 inset-y-0 left-0 z-[70] 
        bg-slate-900 text-white flex flex-col shadow-2xl transition-all duration-300
        ${isSidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'}
        ${isSidebarCollapsed ? 'md:w-20' : 'w-72 md:w-64'}
      `}>
        <div className={`p-8 border-b border-slate-800 flex items-center justify-between ${isSidebarCollapsed ? 'justify-center p-6' : ''}`}>
          {!isSidebarCollapsed ? (
            <div className="flex items-center space-x-3 overflow-hidden">
               <img src={LOGO_URL} alt="Logo" className="w-10 h-10 rounded-lg object-cover border border-slate-700 shrink-0" />
               <h1 className="text-xl font-serif font-bold tracking-tight text-brand-300 uppercase truncate">Admin Hub</h1>
            </div>
          ) : (
            <img src={LOGO_URL} alt="Logo" className="w-8 h-8 rounded-lg object-cover border border-slate-700 shrink-0" />
          )}
          <button 
            onClick={() => setIsSidebarCollapsed(!isSidebarCollapsed)}
            className="hidden md:flex p-2 hover:bg-slate-800 rounded-lg text-slate-400 transition-colors ml-2"
          >
            {isSidebarCollapsed ? <ChevronRight size={20} /> : <ChevronLeft size={20} />}
          </button>
          <button 
            onClick={() => setIsSidebarOpen(false)}
            className="md:hidden p-2 text-slate-400 hover:text-white"
          >
            <X size={24} />
          </button>
        </div>
        
        <nav className="flex-grow p-4 space-y-2 overflow-y-auto">
          {navItems.map(item => (
            <button 
              key={item.id}
              onClick={() => setActiveTab(item.id as Tab)}
              className={`
                w-full flex items-center px-4 py-3 rounded-xl transition-all group
                ${activeTab === item.id ? 'bg-white text-slate-900 shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white'}
                ${isSidebarCollapsed ? 'justify-center px-2' : 'space-x-3'}
              `}
              title={isSidebarCollapsed ? item.label : ''}
            >
              <item.icon size={20} className="shrink-0" />
              {!isSidebarCollapsed && <span className="font-medium capitalize">{item.label}</span>}
              {isSidebarCollapsed && (
                <div className="absolute left-full ml-2 px-2 py-1 bg-slate-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity">
                  {item.label}
                </div>
              )}
            </button>
          ))}
        </nav>

        <div className={`p-4 border-t border-slate-800 ${isSidebarCollapsed ? 'flex justify-center' : ''}`}>
          <button 
            onClick={handleSignOut} 
            className={`
              w-full flex items-center px-4 py-3 rounded-xl text-red-400 hover:bg-red-500/10 transition-all font-medium
              ${isSidebarCollapsed ? 'justify-center px-2' : 'space-x-3'}
            `}
            title={isSidebarCollapsed ? 'Sign Out' : ''}
          >
            <LogOut size={20} className="shrink-0" />
            {!isSidebarCollapsed && <span>Sign Out</span>}
          </button>
        </div>
      </aside>

      {/* Main Content Area */}
      <main className="flex-grow min-h-screen">
        {/* Mobile Header */}
        <div className="md:hidden bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between sticky top-0 z-50">
          <button 
            onClick={() => setIsSidebarOpen(true)}
            className="p-2 text-slate-600 hover:bg-slate-100 rounded-lg"
          >
            <Menu size={24} />
          </button>
          <div className="flex items-center space-x-2">
            <img src={LOGO_URL} alt="Logo" className="w-8 h-8 rounded-lg object-cover" />
            <span className="font-serif font-bold text-brand-900 uppercase text-sm">Style Studio Admin</span>
          </div>
          <div className="w-10" /> {/* Spacer */}
        </div>

        <div className="p-6 lg:p-12 max-w-7xl mx-auto">
          <header className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 mb-12">
            <div>
              <h2 className="text-4xl font-serif text-slate-900 font-bold capitalize">{activeTab}</h2>
              <p className="text-slate-500 mt-1">Manage your website's {activeTab} content</p>
            </div>
            <button 
              onClick={() => { setShowModal(true); setEditingId(null); setFormData({}); setImageFile(null); }}
              className="w-full sm:w-auto bg-brand-900 text-white px-8 py-4 rounded-2xl flex items-center justify-center space-x-2 font-bold shadow-xl shadow-brand-900/20 hover:scale-105 active:scale-95 transition-all"
            >
              <Plus size={20} />
              <span>Create {getSingular(activeTab)}</span>
            </button>
          </header>

          {/* Data Table */}
          <div className="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div className="overflow-x-auto">
              <table className="w-full text-left min-w-[600px]">
                <thead>
                  <tr className="bg-slate-50 border-b border-slate-200">
                    <th className="px-8 py-5 font-bold text-slate-700 uppercase text-xs tracking-wider">Preview</th>
                    <th className="px-8 py-5 font-bold text-slate-700 uppercase text-xs tracking-wider">Info</th>
                    <th className="px-8 py-5 font-bold text-slate-700 uppercase text-xs tracking-wider">Details</th>
                    <th className="px-8 py-5 font-bold text-slate-700 uppercase text-xs tracking-wider text-right">Actions</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100">
                  {loading ? (
                    <tr><td colSpan={4} className="p-20 text-center"><Loader2 className="animate-spin mx-auto w-10 h-10 text-brand-900" /></td></tr>
                  ) : data.length === 0 ? (
                    <tr><td colSpan={4} className="p-20 text-center text-slate-400 italic">No items found in {activeTab}.</td></tr>
                  ) : data.map((item) => (
                    <tr key={item.id} className={`hover:bg-slate-50/80 transition-colors group ${activeTab === 'products' && !item.is_active ? 'opacity-60 bg-slate-50/50' : ''}`}>
                      <td className="px-8 py-5">
                        <div 
                          onClick={() => openViewModal(item)}
                          className="w-16 h-16 rounded-2xl overflow-hidden border-2 border-slate-100 group-hover:border-brand-200 transition-all cursor-zoom-in"
                        >
                          <img src={item.image_url || item.avatar_url || 'https://via.placeholder.com/100'} className="w-full h-full object-cover" alt="" />
                        </div>
                      </td>
                      <td className="px-8 py-5">
                        <button 
                          onClick={() => openViewModal(item)}
                          className="font-bold text-slate-900 text-lg leading-tight hover:text-brand-700 transition-colors text-left"
                        >
                          {item.name || item.author || item.title}
                        </button>
                        <div className="flex items-center space-x-2 mt-1">
                          <span className="text-xs font-black text-brand-600 uppercase tracking-widest inline-block">
                            {item.category || item.role || `${item.rating} Star Review`}
                          </span>
                          {activeTab === 'products' && (
                            <span className={`px-2 py-0.5 rounded text-[10px] font-bold uppercase ${item.is_active ? 'bg-green-100 text-green-700' : 'bg-slate-200 text-slate-600'}`}>
                              {item.is_active ? 'Active' : 'Hidden'}
                            </span>
                          )}
                        </div>
                      </td>
                      <td className="px-8 py-5">
                        <p className="text-slate-500 text-sm line-clamp-1 max-w-xs">{item.description || item.content || item.bio || item.category || 'No details available.'}</p>
                      </td>
                      <td className="px-8 py-5 text-right">
                        <div className="inline-flex space-x-2">
                          <button 
                            onClick={() => openViewModal(item)} 
                            className="p-3 text-slate-400 hover:text-brand-600 bg-slate-50 rounded-xl hover:bg-brand-50 transition-all"
                            title="Quick View"
                          >
                            <Eye size={18} />
                          </button>
                          {activeTab === 'products' && (
                            <button onClick={() => toggleProductStatus(item)} className="p-3 text-slate-400 hover:text-brand-600 bg-slate-50 rounded-xl hover:bg-brand-50 transition-all" title={item.is_active ? 'Hide Product' : 'Show Product'}>
                              {item.is_active ? <EyeOff size={18} /> : <ImageIcon size={18} />}
                            </button>
                          )}
                          <button onClick={() => openEditModal(item)} className="p-3 text-slate-400 hover:text-brand-600 bg-slate-50 rounded-xl hover:bg-brand-50 transition-all"><Edit size={18} /></button>
                          <button onClick={() => handleDelete(item.id)} className="p-3 text-slate-400 hover:text-red-500 bg-slate-50 rounded-xl hover:bg-red-50 transition-all"><Trash2 size={18} /></button>
                        </div>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </main>

      {/* Item View Modal */}
      {showViewModal && viewingItem && (
        <div className="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md animate-in fade-in duration-300">
          <div className="bg-white rounded-[3rem] w-full max-w-4xl max-h-[90vh] overflow-hidden shadow-2xl flex flex-col animate-in zoom-in duration-300">
            <div className="p-6 border-b border-slate-100 flex justify-between items-center shrink-0">
              <h3 className="text-xl font-serif font-bold text-slate-900 uppercase tracking-tight">Resource Insight</h3>
              <button 
                onClick={() => setShowViewModal(false)}
                className="p-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-full transition-colors"
              >
                <X size={20} />
              </button>
            </div>
            
            <div className="overflow-y-auto p-8 lg:p-12">
              <div className="grid grid-cols-1 md:grid-cols-2 gap-12">
                {/* Visual Area */}
                <div className="space-y-6">
                  <div className="aspect-square rounded-[2rem] overflow-hidden border-4 border-slate-50 shadow-inner group relative">
                    <img 
                      src={viewingItem.image_url || viewingItem.avatar_url || 'https://via.placeholder.com/400'} 
                      className="w-full h-full object-cover" 
                      alt="" 
                    />
                    <div className="absolute inset-0 bg-brand-900/10 group-hover:bg-transparent transition-colors" />
                  </div>
                  <div className="flex flex-wrap gap-3">
                    <span className="bg-brand-50 text-brand-700 px-4 py-2 rounded-full text-xs font-black uppercase tracking-widest border border-brand-100">
                      {viewingItem.category || viewingItem.role || 'Uncategorized'}
                    </span>
                    {viewingItem.is_active !== undefined && (
                      <span className={`px-4 py-2 rounded-full text-xs font-black uppercase tracking-widest border ${viewingItem.is_active ? 'bg-green-50 text-green-700 border-green-100' : 'bg-slate-50 text-slate-500 border-slate-200'}`}>
                        {viewingItem.is_active ? 'Public' : 'Hidden'}
                      </span>
                    )}
                  </div>
                </div>

                {/* Content Area */}
                <div className="space-y-8 flex flex-col">
                  <div>
                    {viewingItem.brand && (
                      <p className="text-brand-600 font-black uppercase tracking-[0.3em] text-[10px] mb-2">{viewingItem.brand}</p>
                    )}
                    <h2 className="text-4xl font-serif font-bold text-slate-900 leading-tight">
                      {viewingItem.name || viewingItem.author || viewingItem.title}
                    </h2>
                    {viewingItem.price !== undefined && (
                      <p className="text-3xl font-bold text-brand-900 mt-4">৳{viewingItem.price}</p>
                    )}
                  </div>

                  <div className="space-y-4 flex-grow">
                    <h4 className="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">Complete Details</h4>
                    <p className="text-slate-600 leading-relaxed whitespace-pre-wrap">
                      {viewingItem.description || viewingItem.content || viewingItem.bio || "No extended details provided for this entry. Visit the edit panel to enrich this content."}
                    </p>
                    
                    {viewingItem.duration && (
                      <div className="flex items-center space-x-2 text-slate-500 bg-slate-50 p-3 rounded-xl inline-flex">
                        <Clock size={16} />
                        <span className="text-sm font-bold">{viewingItem.duration}</span>
                      </div>
                    )}

                    {viewingItem.specialty && (
                      <div className="flex flex-wrap gap-2 pt-2">
                        {(Array.isArray(viewingItem.specialty) ? viewingItem.specialty : viewingItem.specialty.split(',')).map((s: string, i: number) => (
                          <span key={i} className="flex items-center space-x-1 text-[10px] font-bold text-brand-800 bg-brand-50 px-2 py-1 rounded">
                            <Tag size={10} />
                            <span>{s.trim()}</span>
                          </span>
                        ))}
                      </div>
                    )}
                  </div>

                  <div className="pt-8 border-t border-slate-100 flex gap-4">
                    <button 
                      onClick={() => { setShowViewModal(false); openEditModal(viewingItem); }}
                      className="flex-1 py-4 bg-brand-900 text-white rounded-2xl font-bold flex items-center justify-center space-x-2 shadow-xl shadow-brand-900/10 hover:scale-[1.02] active:scale-95 transition-all"
                    >
                      <Edit size={18} />
                      <span>Edit Item</span>
                    </button>
                    <a 
                      href={activeTab === 'products' ? `#/products/${viewingItem.id}` : '#'} 
                      target="_blank" 
                      rel="noopener noreferrer"
                      className="p-4 bg-slate-100 text-slate-600 rounded-2xl hover:bg-slate-200 transition-colors"
                      title="View on Site"
                    >
                      <ExternalLink size={20} />
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      )}

      {/* Alert Component */}
      {alert.show && (
        <div className="fixed inset-0 z-[300] flex items-center justify-center p-6 bg-slate-900/40 backdrop-blur-sm animate-in fade-in duration-200">
          <div className="bg-white rounded-[2.5rem] p-10 max-w-sm w-full shadow-2xl text-center animate-in zoom-in duration-300">
            <div className={`mx-auto w-20 h-20 rounded-full flex items-center justify-center mb-6 ${
              alert.type === 'success' ? 'bg-green-100 text-green-600' : 
              alert.type === 'error' ? 'bg-red-100 text-red-600' : 
              'bg-brand-100 text-brand-600'
            }`}>
              {alert.type === 'success' && <CheckCircle2 size={42} />}
              {alert.type === 'error' && <XCircle size={42} />}
              {alert.type === 'confirm' && <AlertTriangle size={42} />}
            </div>
            <h3 className="text-2xl font-serif font-bold text-slate-900 mb-2">{alert.title}</h3>
            <p className="text-slate-500 mb-8 leading-relaxed">{alert.message}</p>
            {alert.type === 'confirm' ? (
              <div className="flex space-x-4">
                <button onClick={() => setAlert({ ...alert, show: false })} className="flex-1 py-4 bg-slate-100 text-slate-600 rounded-2xl font-bold">Cancel</button>
                <button onClick={() => { alert.onConfirm?.(); setAlert({ ...alert, show: false }); }} className="flex-1 py-4 bg-red-500 text-white rounded-2xl font-bold shadow-xl shadow-red-500/20">Yes, Delete</button>
              </div>
            ) : (
              <button onClick={() => setAlert({ ...alert, show: false })} className="w-full py-4 bg-brand-900 text-white rounded-2xl font-bold shadow-xl">Okay</button>
            )}
          </div>
        </div>
      )}

      {/* Create/Edit Modal Overlay */}
      {showModal && (
        <div className="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-md p-4">
          <div className="bg-white rounded-[2.5rem] w-full max-w-2xl overflow-hidden shadow-2xl animate-in zoom-in duration-300">
            <div className="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/30">
              <h3 className="text-2xl font-serif font-bold text-slate-900">{editingId ? 'Modify' : 'Create New'} {getSingular(activeTab)}</h3>
              <button onClick={resetForm} className="text-slate-300 hover:text-slate-900 p-2 bg-white border border-slate-100 rounded-full transition-colors"><X size={24} /></button>
            </div>
            <form onSubmit={handleSave} className="p-8 space-y-8 max-h-[70vh] overflow-y-auto">
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-8">
                {renderFormFields()}
                
                {/* Upload Guidance Component */}
                <div className="col-span-1 sm:col-span-2">
                  <div className="bg-brand-50 border border-brand-200 rounded-2xl p-4 flex items-start space-x-3 mb-4">
                    <Info className="text-brand-600 shrink-0 mt-0.5" size={18} />
                    <div className="text-xs">
                      <p className="font-bold text-brand-900 uppercase tracking-widest mb-1">Image Upload Guide</p>
                      <ul className="text-slate-600 space-y-1 list-disc list-inside">
                        <li><strong>Aspect Ratio:</strong> {getImageGuidance().ratio}</li>
                        <li><strong>Resolution:</strong> {getImageGuidance().resolution}</li>
                        <li><strong>Pro Tip:</strong> {getImageGuidance().tip}</li>
                      </ul>
                    </div>
                  </div>

                  <label className="block text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Visual Asset</label>
                  <div className="flex flex-col sm:flex-row items-center gap-8 bg-slate-50 p-6 rounded-3xl border-2 border-dashed border-slate-200">
                    <div className="w-24 h-24 bg-white rounded-2xl flex items-center justify-center border-2 border-slate-100 overflow-hidden shadow-md shrink-0">
                      {(imageFile || formData.image_url || formData.avatar_url) ? (
                        <img src={imageFile ? URL.createObjectURL(imageFile) : (formData.image_url || formData.avatar_url)} className="w-full h-full object-cover" />
                      ) : (
                        <ImageIcon className="text-slate-200 w-8 h-8" />
                      )}
                    </div>
                    <div className="w-full">
                      <input type="file" accept="image/*" onChange={e => setImageFile(e.target.files?.[0] || null)} className="block w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-brand-50 file:text-brand-700 cursor-pointer" />
                    </div>
                  </div>
                </div>
              </div>
              <div className="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4 pt-6">
                <button type="button" onClick={resetForm} className="w-full sm:flex-1 py-4 border-2 border-slate-100 rounded-2xl font-bold text-slate-500 hover:bg-slate-50">Discard</button>
                <button type="submit" disabled={isSaving} className="w-full sm:flex-1 py-4 bg-brand-900 text-white rounded-2xl font-bold shadow-2xl disabled:opacity-50 flex items-center justify-center space-x-3">
                  {isSaving ? <Loader2 className="animate-spin w-5 h-5" /> : null}
                  <span>{isSaving ? 'Processing...' : 'Save Changes'}</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
};

export default AdminDashboard;
