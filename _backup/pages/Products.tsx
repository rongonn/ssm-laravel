
import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { supabase } from '../lib/supabase';
import { Product } from '../types';
import { Search, ShoppingBag, ArrowRight } from 'lucide-react';

const ProductsPage: React.FC = () => {
  const [products, setProducts] = useState<Product[]>([]);
  const [loading, setLoading] = useState(true);
  const [searchQuery, setSearchQuery] = useState('');
  const [category, setCategory] = useState('All');

  const categories = ['All', 'Hair Care', 'Skin Care', 'Fragrance', 'Tools', 'Body'];

  useEffect(() => {
    fetchProducts();
  }, []);

  const fetchProducts = async () => {
    setLoading(true);
    const { data, error } = await supabase
      .from('products')
      .select('*')
      .eq('is_active', true)
      .order('name');
    
    if (!error && data) setProducts(data);
    setLoading(false);
  };

  const filteredProducts = products.filter(p => {
    const matchesSearch = p.name.toLowerCase().includes(searchQuery.toLowerCase()) || 
                         p.brand.toLowerCase().includes(searchQuery.toLowerCase());
    const matchesCategory = category === 'All' || p.category === category;
    return matchesSearch && matchesCategory;
  });

  return (
    <div className="bg-brand-50 min-h-screen pb-20">
      {/* Hero Header */}
      <section className="bg-brand-900 text-white py-24 relative overflow-hidden">
        <div className="max-w-7xl mx-auto px-4 text-center relative z-10">
          <h1 className="text-5xl md:text-7xl font-serif mb-6 uppercase tracking-tighter">Apothecary</h1>
          <p className="text-xl text-brand-200 max-w-2xl mx-auto italic font-serif">
            Premium salon-grade essentials for your daily transformation.
          </p>
        </div>
        <div className="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
      </section>

      {/* Search & Category Filter */}
      <div className="max-w-7xl mx-auto px-4 -mt-12 relative z-20">
        <div className="bg-white p-6 rounded-[2.5rem] shadow-2xl border border-brand-100 space-y-6">
          <div className="flex items-center bg-slate-50 rounded-2xl px-6 border border-slate-100">
            <Search className="text-slate-400" size={20} />
            <input 
              type="text" 
              placeholder="Search by name or brand..."
              value={searchQuery}
              onChange={(e) => setSearchQuery(e.target.value)}
              className="w-full px-4 py-4 outline-none bg-transparent placeholder:text-slate-300 font-medium"
            />
          </div>
          
          <div className="flex flex-wrap gap-2 justify-center">
            {categories.map((cat) => (
              <button
                key={cat}
                onClick={() => setCategory(cat)}
                className={`px-6 py-2 rounded-full text-xs font-black uppercase tracking-widest transition-all ${
                  category === cat 
                  ? 'bg-brand-900 text-white' 
                  : 'bg-white text-slate-400 border border-slate-100 hover:border-brand-200'
                }`}
              >
                {cat}
              </button>
            ))}
          </div>
        </div>
      </div>

      {/* Grid */}
      <div className="max-w-7xl mx-auto px-4 py-20">
        {loading ? (
          <div className="grid grid-cols-1 md:grid-cols-3 gap-12">
            {[1, 2, 3].map(i => (
              <div key={i} className="space-y-6">
                <div className="aspect-[4/5] bg-slate-200 animate-pulse rounded-[3rem]" />
                <div className="h-6 w-2/3 bg-slate-200 animate-pulse mx-auto" />
              </div>
            ))}
          </div>
        ) : filteredProducts.length === 0 ? (
          <div className="text-center py-24 bg-white rounded-[3rem] border-2 border-dashed border-slate-200">
            <ShoppingBag className="mx-auto text-slate-200 w-20 h-20 mb-6" />
            <p className="text-slate-500 text-xl font-serif italic">No products found matching your selection.</p>
          </div>
        ) : (
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-12 gap-y-20">
            {filteredProducts.map((product) => (
              <div key={product.id} className="group flex flex-col items-center text-center">
                <div className="w-full aspect-[4/5] rounded-[3rem] overflow-hidden bg-white mb-8 relative border border-brand-100 group-hover:shadow-2xl transition-all duration-700">
                  <img 
                    src={product.image_url || 'https://images.unsplash.com/photo-1596462502278-27bfad450526?auto=format&fit=crop&q=80&w=800'} 
                    alt={product.name} 
                    className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-1000"
                  />
                  <div className="absolute top-6 left-6 bg-white/90 backdrop-blur-md text-brand-900 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-[0.2em] shadow-sm">
                    {product.category}
                  </div>
                </div>
                
                <div className="px-6 space-y-2 flex flex-col items-center">
                  <p className="text-[10px] font-black text-brand-500 uppercase tracking-[0.3em]">{product.brand}</p>
                  <h3 className="text-2xl font-serif text-slate-900 transition-colors leading-tight mb-2">
                    {product.name}
                  </h3>
                  <p className="text-xl font-bold text-slate-900 mb-6">৳{product.price}</p>
                  
                  <Link 
                    to={`/products/${product.id}`} 
                    className="inline-flex items-center justify-center space-x-2 bg-brand-900 text-white px-8 py-3.5 rounded-full font-bold text-[10px] uppercase tracking-widest transition-all hover:bg-brand-800 hover:scale-105 active:scale-95 shadow-lg shadow-brand-900/20"
                  >
                    <span>View Product</span>
                    <ArrowRight size={14} />
                  </Link>
                </div>
              </div>
            ))}
          </div>
        )}
      </div>
    </div>
  );
};

export default ProductsPage;
