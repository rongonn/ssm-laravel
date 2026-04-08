
import React, { useState, useEffect } from 'react';
import { supabase } from '../lib/supabase';
import { GalleryItem } from '../types';
import { Loader2, Image as ImageIcon, X, Maximize2 } from 'lucide-react';

const GalleryPage: React.FC = () => {
  const [items, setItems] = useState<GalleryItem[]>([]);
  const [loading, setLoading] = useState(true);
  const [category, setCategory] = useState('All');
  const [selectedImage, setSelectedImage] = useState<string | null>(null);

  const categories = ['All', 'Hair', 'Interior', 'Nails', 'Events', 'Skin'];

  useEffect(() => {
    fetchGallery();
  }, []);

  const fetchGallery = async () => {
    setLoading(true);
    const { data, error } = await supabase
      .from('gallery')
      .select('*')
      .order('created_at', { ascending: false });
    
    if (!error && data) setItems(data);
    setLoading(false);
  };

  const filteredItems = category === 'All' 
    ? items 
    : items.filter(item => item.category === category);

  return (
    <div className="bg-brand-50 min-h-screen">
      {/* Header */}
      <section className="bg-brand-900 text-white py-24 relative overflow-hidden">
        <div className="max-w-7xl mx-auto px-4 text-center relative z-10">
          <h1 className="text-5xl md:text-7xl font-serif mb-6">Artistic Portfolio</h1>
          <p className="text-xl text-brand-200 max-w-2xl mx-auto italic font-serif">
            A visual journey through our finest transformations and studio aesthetics.
          </p>
        </div>
        <div className="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/natural-paper.png')]"></div>
      </section>

      {/* Category Filters */}
      <div className="sticky top-20 z-40 bg-white/80 backdrop-blur-md border-b border-brand-100 py-6">
        <div className="max-w-7xl mx-auto px-4 flex flex-wrap justify-center gap-4">
          {categories.map((cat) => (
            <button
              key={cat}
              onClick={() => setCategory(cat)}
              className={`px-8 py-2.5 rounded-full text-sm font-bold transition-all ${
                category === cat 
                ? 'bg-brand-900 text-white shadow-lg shadow-brand-900/20' 
                : 'bg-white text-slate-500 border border-brand-100 hover:border-brand-300'
              }`}
            >
              {cat}
            </button>
          ))}
        </div>
      </div>

      {/* Gallery Grid */}
      <div className="max-w-7xl mx-auto px-4 py-20">
        {loading ? (
          <div className="flex flex-col items-center justify-center py-20 space-y-4">
            <Loader2 className="animate-spin text-brand-900 w-12 h-12" />
            <p className="text-slate-400 font-medium">Loading masterpieces...</p>
          </div>
        ) : filteredItems.length === 0 ? (
          <div className="text-center py-24 bg-white rounded-[3rem] border-2 border-dashed border-slate-200">
            <ImageIcon className="mx-auto text-slate-200 w-24 h-24 mb-6" />
            <p className="text-slate-500 text-2xl font-serif italic">Our gallery for "{category}" is currently being curated.</p>
          </div>
        ) : (
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            {filteredItems.map((item) => (
              <div 
                key={item.id} 
                onClick={() => setSelectedImage(item.image_url)}
                className="bg-white rounded-[2.5rem] overflow-hidden group hover:shadow-2xl transition-all duration-500 border border-brand-100 shadow-sm cursor-zoom-in"
              >
                <div className="relative aspect-square w-full overflow-hidden bg-slate-100">
                  <img 
                    src={item.image_url} 
                    alt={item.title} 
                    className="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000"
                  />
                  <div className="absolute inset-0 bg-brand-900/60 opacity-0 group-hover:opacity-100 transition-all duration-500 flex flex-col items-center justify-center p-10 text-center">
                    <Maximize2 className="text-white mb-4 w-8 h-8 opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 transition-all duration-500 delay-100" />
                    <span className="text-brand-300 text-[10px] font-black uppercase tracking-widest mb-2">{item.category}</span>
                    <h3 className="text-white text-2xl font-serif font-bold leading-tight">{item.title || 'Untitled Work'}</h3>
                  </div>
                </div>
              </div>
            ))}
          </div>
        )}
      </div>

      {/* Lightbox / Zoom Modal */}
      {selectedImage && (
        <div 
          className="fixed inset-0 z-[100] flex items-center justify-center p-4 md:p-12 bg-slate-950/90 backdrop-blur-xl animate-in fade-in duration-300"
          onClick={() => setSelectedImage(null)}
        >
          <button 
            className="absolute top-8 right-8 z-[110] p-3 bg-white/10 hover:bg-white/20 text-white rounded-full transition-all hover:rotate-90"
            onClick={(e) => { e.stopPropagation(); setSelectedImage(null); }}
          >
            <X size={32} />
          </button>
          
          <div 
            className="relative max-w-full max-h-full flex items-center justify-center animate-in zoom-in duration-300"
            onClick={(e) => e.stopPropagation()}
          >
            <img 
              src={selectedImage} 
              className="max-w-full max-h-[90vh] object-contain rounded-2xl shadow-2xl border-4 border-white/5" 
              alt="Zoomed preview" 
            />
          </div>
        </div>
      )}
    </div>
  );
};

export default GalleryPage;
