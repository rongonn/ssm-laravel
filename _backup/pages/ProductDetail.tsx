
import React, { useState, useEffect, useRef } from 'react';
import { useParams, Link } from 'react-router-dom';
import { supabase } from '../lib/supabase';
import { Product } from '../types';
import { ChevronLeft, ChevronRight, ShoppingBag, ArrowLeft, Tag, ShieldCheck, Truck, RefreshCw, Loader2, MessageCircle } from 'lucide-react';

const ProductDetailPage: React.FC = () => {
  const { id } = useParams<{ id: string }>();
  const [product, setProduct] = useState<Product | null>(null);
  const [relatedProducts, setRelatedProducts] = useState<Product[]>([]);
  const [loading, setLoading] = useState(true);
  const [mousePos, setMousePos] = useState({ x: 0, y: 0 });
  const [isHovering, setIsHovering] = useState(false);
  const scrollRef = useRef<HTMLDivElement>(null);

  const WHATSAPP_NUMBER = "01911-879571";

  useEffect(() => {
    if (id) {
      fetchProductDetails();
      window.scrollTo(0, 0);
    }
  }, [id]);

  const fetchProductDetails = async () => {
    setLoading(true);
    const { data, error } = await supabase
      .from('products')
      .select('*')
      .eq('id', id)
      .single();

    if (!error && data) {
      setProduct(data);
      fetchRelatedProducts(data.category, data.id);
    }
    setLoading(false);
  };

  const fetchRelatedProducts = async (category: string, currentId: string) => {
    const { data } = await supabase
      .from('products')
      .select('*')
      .eq('category', category)
      .eq('is_active', true)
      .neq('id', currentId)
      .limit(6);
    
    if (data) setRelatedProducts(data);
  };

  const handleMouseMove = (e: React.MouseEvent<HTMLDivElement>) => {
    const { left, top, width, height } = e.currentTarget.getBoundingClientRect();
    const x = ((e.pageX - left - window.scrollX) / width) * 100;
    const y = ((e.pageY - top - window.scrollY) / height) * 100;
    setMousePos({ x, y });
  };

  const scroll = (direction: 'left' | 'right') => {
    if (scrollRef.current) {
      const { scrollLeft, clientWidth } = scrollRef.current;
      const scrollTo = direction === 'left' ? scrollLeft - clientWidth : scrollLeft + clientWidth;
      scrollRef.current.scrollTo({ left: scrollTo, behavior: 'smooth' });
    }
  };

  const openWhatsApp = () => {
    if (!product) return;
    const message = encodeURIComponent(`Hello Style Studio Mart! I'm interested in the product: ${product.name} (Brand: ${product.brand}, Price: ৳${product.price}). Is it available?`);
    window.open(`https://wa.me/${WHATSAPP_NUMBER.replace('-', '')}?text=${message}`, '_blank');
  };

  if (loading) {
    return (
      <div className="min-h-screen flex flex-col items-center justify-center bg-brand-50">
        <Loader2 className="animate-spin text-brand-900 w-12 h-12 mb-4" />
        <p className="text-brand-900 font-serif italic">Unveiling luxury...</p>
      </div>
    );
  }

  if (!product) {
    return (
      <div className="min-h-screen flex flex-col items-center justify-center p-4">
        <h2 className="text-3xl font-serif mb-4">Product Not Found</h2>
        <Link to="/products" className="text-brand-900 font-bold flex items-center space-x-2">
          <ArrowLeft size={20} />
          <span>Back to Apothecary</span>
        </Link>
      </div>
    );
  }

  return (
    <div className="bg-white min-h-screen">
      {/* Breadcrumb / Back Button */}
      <div className="max-w-7xl mx-auto px-4 py-8">
        <Link to="/products" className="inline-flex items-center space-x-2 text-slate-400 hover:text-brand-900 transition-colors font-bold text-sm uppercase tracking-widest">
          <ArrowLeft size={16} />
          <span>Back to Collection</span>
        </Link>
      </div>

      <div className="max-w-7xl mx-auto px-4 pb-12">
        {/* Top Section: Image & Basic Info */}
        <div className="grid lg:grid-cols-2 gap-16 items-start mb-16">
          
          {/* Image Section with Zoom */}
          <div 
            className="relative aspect-square rounded-[3rem] overflow-hidden bg-brand-50 cursor-crosshair border border-brand-100 group"
            onMouseMove={handleMouseMove}
            onMouseEnter={() => setIsHovering(true)}
            onMouseLeave={() => setIsHovering(false)}
          >
            <img 
              src={product.image_url} 
              alt={product.name} 
              className={`w-full h-full object-cover transition-transform duration-200 ${isHovering ? 'scale-[2]' : 'scale-100'}`}
              style={isHovering ? { transformOrigin: `${mousePos.x}% ${mousePos.y}%` } : {}}
            />
            <div className="absolute bottom-6 right-6 bg-white/90 backdrop-blur px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm pointer-events-none group-hover:opacity-0 transition-opacity">
              Hover to Zoom
            </div>
          </div>

          {/* Details Section */}
          <div className="space-y-10 py-4">
            <div>
              <p className="text-brand-600 font-black uppercase tracking-[0.3em] text-xs mb-3">{product.brand}</p>
              <h1 className="text-5xl font-serif text-slate-900 mb-6 leading-tight">{product.name}</h1>
              <div className="flex items-center space-x-4">
                <p className="text-5xl font-bold text-brand-900">৳{product.price}</p>
                <div className="h-8 w-px bg-slate-200" />
                <span className="bg-brand-50 text-brand-700 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest">
                  {product.category}
                </span>
              </div>
            </div>

            {/* CTA Section - WhatsApp Button */}
            <div className="space-y-6 pt-4">
              <button 
                onClick={openWhatsApp}
                className="flex items-center justify-center space-x-4 w-full bg-[#25D366] text-white py-6 rounded-[2rem] text-xl font-bold shadow-2xl shadow-green-500/20 hover:scale-[1.02] active:scale-95 transition-all"
              >
                <MessageCircle size={28} />
                <span>Purchase via WhatsApp</span>
              </button>
              <p className="text-center text-slate-400 text-sm italic">Connect with us directly for availability and delivery details.</p>
            </div>

            {/* Quality Seals */}
            <div className="grid grid-cols-3 gap-6 pt-10 border-t border-slate-100">
              <div className="flex flex-col items-center text-center space-y-3">
                <div className="p-4 bg-brand-50 rounded-[1.5rem] text-brand-900 shadow-sm"><ShieldCheck size={24} /></div>
                <p className="text-[10px] font-black uppercase tracking-widest text-slate-500">100% Authentic</p>
              </div>
              <div className="flex flex-col items-center text-center space-y-3">
                <div className="p-4 bg-brand-50 rounded-[1.5rem] text-brand-900 shadow-sm"><Truck size={24} /></div>
                <p className="text-[10px] font-black uppercase tracking-widest text-slate-500">Fast Shipping</p>
              </div>
              <div className="flex flex-col items-center text-center space-y-3">
                <div className="p-4 bg-brand-50 rounded-[1.5rem] text-brand-900 shadow-sm"><RefreshCw size={24} /></div>
                <p className="text-[10px] font-black uppercase tracking-widest text-slate-500">Support 24/7</p>
              </div>
            </div>
          </div>
        </div>

        {/* Full Width Description Section */}
        <div className="bg-slate-50/50 rounded-[3rem] p-12 md:p-16 border border-slate-100">
          <div className="max-w-4xl">
            <h4 className="text-xs font-black text-brand-600 uppercase tracking-[0.3em] mb-6">Product Insight</h4>
            <h2 className="text-3xl font-serif text-slate-900 mb-8">Full Description & Benefits</h2>
            <div className="prose prose-lg prose-slate max-w-none">
              <p className="text-slate-600 leading-relaxed text-lg whitespace-pre-wrap">
                {product.description || "Our premium collection represents the pinnacle of beauty innovation. This product is carefully curated to meet the highest standards of professional care. \n\nWe select our apothecary items based on their active ingredients, ethical sourcing, and proven results. Whether you are maintaining a salon-fresh look at home or seeking specific therapeutic benefits, this essential item delivers consistent excellence."}
              </p>
            </div>
          </div>
        </div>
      </div>

      {/* Related Products Slider */}
      {relatedProducts.length > 0 && (
        <section className="py-24 bg-brand-50 overflow-hidden">
          <div className="max-w-7xl mx-auto px-4 mb-12 flex items-center justify-between">
            <div>
              <h2 className="text-sm font-bold text-brand-600 uppercase tracking-[0.2em] mb-4">Curated Pairings</h2>
              <p className="text-4xl md:text-5xl font-serif text-slate-900">You May Also Love</p>
            </div>
            <div className="flex space-x-4">
              <button onClick={() => scroll('left')} className="p-4 rounded-full border border-slate-200 hover:bg-white text-slate-400 hover:text-brand-900 transition-all">
                <ChevronLeft size={24} />
              </button>
              <button onClick={() => scroll('right')} className="p-4 rounded-full border border-slate-200 hover:bg-white text-slate-400 hover:text-brand-900 transition-all">
                <ChevronRight size={24} />
              </button>
            </div>
          </div>

          <div 
            ref={scrollRef}
            className="flex overflow-x-auto gap-8 px-[max(1rem,calc((100vw-80rem)/2))] no-scrollbar scroll-smooth pb-12"
          >
            {relatedProducts.map((rel) => (
              <Link 
                key={rel.id} 
                to={`/products/${rel.id}`}
                className="min-w-[300px] md:min-w-[350px] group bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 border border-brand-100 flex flex-col"
              >
                <div className="h-64 overflow-hidden relative">
                  <img src={rel.image_url} className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt={rel.name} />
                  <div className="absolute top-6 left-6 bg-white/90 backdrop-blur p-2.5 rounded-xl text-brand-900 shadow-md">
                     <Tag size={18} />
                  </div>
                </div>
                <div className="p-8 flex-grow">
                  <p className="text-[10px] font-black text-brand-500 uppercase tracking-widest mb-2">{rel.brand}</p>
                  <h3 className="text-xl font-serif text-slate-900 mb-4 truncate">{rel.name}</h3>
                  <div className="flex items-center justify-between mt-auto">
                    <p className="text-2xl font-bold text-brand-900">৳{rel.price}</p>
                    <div className="text-brand-900 p-2 bg-brand-50 rounded-full">
                       <ChevronRight size={20} />
                    </div>
                  </div>
                </div>
              </Link>
            ))}
          </div>
        </section>
      )}
    </div>
  );
};

export default ProductDetailPage;
