
import React, { useState, useEffect } from 'react';
import { supabase } from '../lib/supabase';
import { Service } from '../types';
import { Clock, Info, ArrowRight } from 'lucide-react';
import { Link } from 'react-router-dom';

const ServicesPage: React.FC = () => {
  const [services, setServices] = useState<Service[]>([]);
  const [filtered, setFiltered] = useState<Service[]>([]);
  const [category, setCategory] = useState('All');
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchServices();
  }, []);

  const fetchServices = async () => {
    setLoading(true);
    const { data, error } = await supabase.from('services').select('*').order('name');
    if (!error && data) {
      setServices(data);
      setFiltered(data);
    }
    setLoading(false);
  };

  const handleFilter = (cat: string) => {
    setCategory(cat);
    if (cat === 'All') {
      setFiltered(services);
    } else {
      setFiltered(services.filter(s => s.category === cat));
    }
  };

  return (
    <div className="bg-brand-50 min-h-screen pb-24">
      {/* Header */}
      <section className="bg-brand-900 text-white py-24 relative overflow-hidden">
        <div className="max-w-7xl mx-auto px-4 relative z-10">
          <h1 className="text-5xl md:text-7xl font-serif mb-6 text-center">Service Menu</h1>
          <p className="text-xl text-brand-200 text-center max-w-2xl mx-auto">
            Experience the pinnacle of personal care with our range of professional treatments and artisanal styling.
          </p>
        </div>
        <div className="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/black-paper.png')]"></div>
      </section>

      {/* Filters */}
      <div className="sticky top-20 z-40 bg-white/80 backdrop-blur-md border-b border-brand-100 py-6">
        <div className="max-w-7xl mx-auto px-4 flex flex-wrap justify-center gap-4">
          {['All', 'Hair', 'Skin', 'Nails', 'Massage'].map((cat) => (
            <button
              key={cat}
              onClick={() => handleFilter(cat)}
              className={`px-8 py-2.5 rounded-full text-sm font-bold transition-all ${
                category === cat 
                ? 'bg-brand-900 text-white shadow-lg' 
                : 'bg-white text-slate-500 border border-brand-100 hover:border-brand-300'
              }`}
            >
              {cat}
            </button>
          ))}
        </div>
      </div>

      {/* Grid */}
      <div className="max-w-7xl mx-auto px-4 mt-16">
        {loading ? (
          <div className="flex justify-center items-center h-64">
            <div className="animate-spin rounded-full h-12 w-12 border-t-2 border-brand-900"></div>
          </div>
        ) : (
          <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            {filtered.map((service) => (
              <div key={service.id} className="bg-white rounded-[2rem] overflow-hidden group hover:shadow-2xl transition-all duration-500 border border-brand-100 flex flex-col">
                <div className="h-64 relative overflow-hidden">
                  <img 
                    src={service.image_url || 'https://picsum.photos/800/600?random=' + service.id} 
                    alt={service.name} 
                    className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                  />
                  <div className="absolute top-4 right-4 bg-brand-900 text-white px-4 py-2 rounded-full font-bold shadow-lg">
                    ৳{service.price}
                  </div>
                </div>
                <div className="p-10 flex-grow flex flex-col items-center text-center">
                  <div className="flex items-center space-x-2 text-brand-500 font-bold text-xs uppercase tracking-widest mb-4">
                    <span>{service.category}</span>
                    <span>•</span>
                    <div className="flex items-center space-x-1">
                      <Clock size={14} />
                      <span>{service.duration}</span>
                    </div>
                  </div>
                  <h3 className="text-2xl font-serif text-slate-900 mb-4">{service.name}</h3>
                  <p className="text-slate-500 mb-8 line-clamp-2 leading-relaxed flex-grow">{service.description}</p>
                  
                  <Link 
                    to={`/services/${service.id}`} 
                    className="inline-flex items-center justify-center space-x-2 bg-brand-900 text-white px-8 py-3.5 rounded-full font-bold text-[10px] uppercase tracking-widest transition-all hover:bg-brand-800 hover:scale-105 active:scale-95 shadow-lg shadow-brand-900/20"
                  >
                    <span>View Details</span>
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

export default ServicesPage;
