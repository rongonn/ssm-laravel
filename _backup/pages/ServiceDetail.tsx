
import React, { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import { supabase } from '../lib/supabase';
import { Service } from '../types';
import { ArrowLeft, Clock, ShieldCheck, RefreshCw, Star, MessageCircle, Loader2 } from 'lucide-react';

const ServiceDetailPage: React.FC = () => {
  const { id } = useParams<{ id: string }>();
  const [service, setService] = useState<Service | null>(null);
  const [loading, setLoading] = useState(true);

  const WHATSAPP_NUMBER = "01911-879571";

  useEffect(() => {
    if (id) {
      fetchServiceDetails();
      window.scrollTo(0, 0);
    }
  }, [id]);

  const fetchServiceDetails = async () => {
    setLoading(true);
    const { data, error } = await supabase
      .from('services')
      .select('*')
      .eq('id', id)
      .single();

    if (!error && data) {
      setService(data);
    }
    setLoading(false);
  };

  const openWhatsApp = () => {
    if (!service) return;
    const message = encodeURIComponent(`Hello Style Studio Mart! I'm interested in the service: ${service.name} (Category: ${service.category}, Price: ৳${service.price}). Can I book an appointment?`);
    window.open(`https://wa.me/${WHATSAPP_NUMBER.replace('-', '')}?text=${message}`, '_blank');
  };

  if (loading) {
    return (
      <div className="min-h-screen flex flex-col items-center justify-center bg-brand-50">
        <Loader2 className="animate-spin text-brand-900 w-12 h-12 mb-4" />
        <p className="text-brand-900 font-serif italic">Preparing your experience...</p>
      </div>
    );
  }

  if (!service) {
    return (
      <div className="min-h-screen flex flex-col items-center justify-center p-4">
        <h2 className="text-3xl font-serif mb-4">Service Not Found</h2>
        <Link to="/services" className="text-brand-900 font-bold flex items-center space-x-2">
          <ArrowLeft size={20} />
          <span>Back to Services</span>
        </Link>
      </div>
    );
  }

  return (
    <div className="bg-white min-h-screen">
      {/* Breadcrumb */}
      <div className="max-w-7xl mx-auto px-4 py-8">
        <Link to="/services" className="inline-flex items-center space-x-2 text-slate-400 hover:text-brand-900 transition-colors font-bold text-sm uppercase tracking-widest">
          <ArrowLeft size={16} />
          <span>Back to Menu</span>
        </Link>
      </div>

      <div className="max-w-7xl mx-auto px-4 pb-24">
        {/* Top Split Section */}
        <div className="grid lg:grid-cols-2 gap-16 items-start mb-16">
          {/* Image */}
          <div className="relative aspect-[4/5] rounded-[3rem] overflow-hidden shadow-2xl border border-brand-100">
            <img 
              src={service.image_url || 'https://picsum.photos/800/1000?random=' + service.id} 
              alt={service.name} 
              className="w-full h-full object-cover"
            />
            <div className="absolute top-8 left-8 bg-white/90 backdrop-blur px-6 py-2 rounded-full text-brand-900 font-black uppercase tracking-widest text-xs shadow-lg">
              {service.category}
            </div>
          </div>

          {/* Core Info */}
          <div className="space-y-10 py-4">
            <div>
              <div className="flex items-center space-x-2 text-brand-600 mb-4">
                <Star className="fill-brand-600" size={16} />
                <span className="font-black uppercase tracking-[0.4em] text-[10px]">Premium Salon Service</span>
              </div>
              <h1 className="text-5xl md:text-6xl font-serif text-slate-900 mb-8 leading-tight">{service.name}</h1>
              <div className="flex flex-wrap items-center gap-6">
                <p className="text-5xl font-bold text-brand-900">৳{service.price}</p>
                <div className="h-10 w-px bg-slate-200 hidden sm:block" />
                <div className="flex items-center space-x-2 text-slate-500 bg-slate-50 px-5 py-2.5 rounded-full border border-slate-100">
                  <Clock size={20} className="text-brand-600" />
                  <span className="font-bold">{service.duration} Session</span>
                </div>
              </div>
            </div>

            {/* CTA Section */}
            <div className="space-y-6 pt-6">
              <button 
                onClick={openWhatsApp}
                className="flex items-center justify-center space-x-4 w-full bg-[#25D366] text-white py-6 rounded-[2rem] text-xl font-bold shadow-2xl shadow-green-500/20 hover:scale-[1.02] active:scale-95 transition-all"
              >
                <MessageCircle size={28} />
                <span>Book via WhatsApp</span>
              </button>
              <p className="text-center text-slate-400 text-sm italic">Immediate response for appointment scheduling.</p>
            </div>

            {/* Quality Seals */}
            <div className="grid grid-cols-3 gap-6 pt-10 border-t border-slate-100">
              <div className="flex flex-col items-center text-center space-y-3">
                <div className="p-4 bg-brand-50 rounded-[1.5rem] text-brand-900 shadow-sm"><ShieldCheck size={24} /></div>
                <p className="text-[10px] font-black uppercase tracking-widest text-slate-500">Master Artisans</p>
              </div>
              <div className="flex flex-col items-center text-center space-y-3">
                <div className="p-4 bg-brand-50 rounded-[1.5rem] text-brand-900 shadow-sm"><Star size={24} /></div>
                <p className="text-[10px] font-black uppercase tracking-widest text-slate-500">Elite Care</p>
              </div>
              <div className="flex flex-col items-center text-center space-y-3">
                <div className="p-4 bg-brand-50 rounded-[1.5rem] text-brand-900 shadow-sm"><RefreshCw size={24} /></div>
                <p className="text-[10px] font-black uppercase tracking-widest text-slate-500">Safe Rituals</p>
              </div>
            </div>
          </div>
        </div>

        {/* Full Width Description Section */}
        <div className="bg-slate-50/50 rounded-[3rem] p-12 md:p-16 border border-slate-100">
          <div className="max-w-4xl">
            <h4 className="text-xs font-black text-brand-600 uppercase tracking-[0.3em] mb-6">Service Overview</h4>
            <h2 className="text-4xl font-serif text-slate-900 mb-8">What to Expect from this Ritual</h2>
            <div className="prose prose-lg prose-slate max-w-none">
              <p className="text-slate-600 leading-relaxed text-xl whitespace-pre-wrap">
                {service.description || "Indulge in a meticulously crafted experience designed to enhance your natural beauty while providing profound relaxation. Our master artisans use only the finest techniques and professional products to ensure your transformation is both visible and lasting.\n\nEvery session begins with a personalized consultation to tailor the treatment to your specific needs and aesthetic goals."}
              </p>
            </div>
            
            <div className="mt-12 p-8 bg-white rounded-3xl border border-brand-100 shadow-sm inline-block">
              <p className="text-slate-500 font-medium italic">
                "Our goal is to redefine your daily beauty ritual through expertise and unparalleled attention to detail."
              </p>
              <p className="text-brand-900 font-bold mt-4 uppercase tracking-widest text-xs">— Style Studio Mart Specialist</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default ServiceDetailPage;
