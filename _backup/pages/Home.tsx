
import React, { useEffect, useState, useRef } from 'react';
import { Link } from 'react-router-dom';
import { Scissors, ArrowRight, ShoppingBag, Image as ImageIcon, ChevronLeft, ChevronRight, Tag, Clock, Facebook, Instagram, X, Maximize2 } from 'lucide-react';
import TestimonialSlider from '../components/TestimonialSlider';
import { supabase } from '../lib/supabase';
import { Testimonial, GalleryItem, Product, Service, TeamMember } from '../types';

const Home: React.FC = () => {
  const [testimonials, setTestimonials] = useState<Testimonial[]>([]);
  const [galleryItems, setGalleryItems] = useState<GalleryItem[]>([]);
  const [latestProducts, setLatestProducts] = useState<Product[]>([]);
  const [latestServices, setLatestServices] = useState<Service[]>([]);
  const [teamMembers, setTeamMembers] = useState<TeamMember[]>([]);
  const [selectedImage, setSelectedImage] = useState<string | null>(null);
  const LOGO_URL = "https://wbnrfhmbwtldxhnauggx.supabase.co/storage/v1/object/public/assets/ssm%20logo.jpg";
  const HERO_IMAGE = "https://wbnrfhmbwtldxhnauggx.supabase.co/storage/v1/object/public/assets/Gemini_Generated_Image_fthzovfthzovfthz.avif";
  
  const productScrollRef = useRef<HTMLDivElement>(null);
  const serviceScrollRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    const fetchData = async () => {
      const [tRes, gRes, pRes, sRes, teamRes] = await Promise.all([
        supabase.from('testimonials').select('*').limit(10),
        supabase.from('gallery').select('*').limit(6).order('created_at', { ascending: false }),
        supabase.from('products').select('*').eq('is_active', true).limit(10).order('created_at', { ascending: false }),
        supabase.from('services').select('*').limit(10).order('created_at', { ascending: false }),
        supabase.from('team').select('*').limit(4).order('name')
      ]);
      
      if (tRes.data) setTestimonials(tRes.data);
      if (gRes.data) setGalleryItems(gRes.data);
      if (pRes.data) setLatestProducts(pRes.data);
      if (sRes.data) setLatestServices(sRes.data);
      if (teamRes.data) setTeamMembers(teamRes.data);
    };
    fetchData();
  }, []);

  const scrollHorizontally = (ref: React.RefObject<HTMLDivElement | null>, direction: 'left' | 'right') => {
    if (ref.current) {
      const { scrollLeft, clientWidth } = ref.current;
      const scrollTo = direction === 'left' ? scrollLeft - clientWidth : scrollLeft + clientWidth;
      ref.current.scrollTo({ left: scrollTo, behavior: 'smooth' });
    }
  };

  const ViewMoreBtn = ({ to, label }: { to: string, label: string }) => (
    <div className="mt-16 text-center">
      <Link 
        to={to} 
        className="inline-flex items-center justify-center space-x-3 bg-brand-900 text-white px-12 py-5 rounded-full font-bold text-xs uppercase tracking-[0.3em] transition-all hover:bg-brand-800 hover:scale-105 active:scale-95 shadow-xl shadow-brand-900/20"
      >
        <span>{label}</span>
        <ArrowRight size={20} />
      </Link>
    </div>
  );

  return (
    <div className="overflow-x-hidden">
      {/* Hero Section */}
      <section className="relative h-[90vh] flex items-center">
        <div className="absolute inset-0 z-0">
          <img 
            src={HERO_IMAGE} 
            className="w-full h-full object-cover brightness-[0.65]"
            alt="Style Studio Mart Hero"
          />
          <div className="absolute inset-0 bg-gradient-to-r from-brand-900/60 via-brand-900/20 to-transparent" />
        </div>
        
        <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
          <div className="max-w-2xl animate-in slide-in-from-left duration-1000">
            <div className="flex items-center space-x-3 text-brand-200 mb-6 tracking-widest uppercase text-sm font-semibold bg-white/10 backdrop-blur-md w-fit px-5 py-2.5 rounded-2xl border border-white/20">
              <img src={LOGO_URL} className="h-6 w-6 rounded-lg object-cover" alt="" />
              <span>Style Studio Mart Experience</span>
            </div>
            <h1 className="text-6xl md:text-8xl font-serif text-white leading-tight mb-8">
              Where Beauty <br /> Meets <span className="italic">Perfection</span>
            </h1>
            <p className="text-xl text-brand-50/90 mb-10 max-w-lg leading-relaxed">
              Step into a world of curated beauty experiences and timeless elegance at Style Studio Mart. 
              Discover premium products and artistry.
            </p>
            <div className="flex">
              <Link to="/products" className="flex items-center justify-center space-x-2 bg-white text-brand-900 px-10 py-5 rounded-full text-lg font-bold hover:bg-brand-50 transition-all transform hover:scale-105 shadow-xl">
                <ShoppingBag size={20} />
                <span>Shop Products</span>
              </Link>
            </div>
          </div>
        </div>
      </section>

      {/* DYNAMIC SERVICES SLIDER */}
      <section className="py-24 bg-brand-50 overflow-hidden">
        <div className="max-w-7xl mx-auto px-4 mb-12 flex items-center justify-between">
          <div className="max-w-xl">
            <h2 className="text-sm font-bold text-brand-600 uppercase tracking-[0.2em] mb-4">Artisanal Rituals</h2>
            <p className="text-4xl md:text-5xl font-serif text-slate-900">Our Signature Services</p>
          </div>
          <div className="flex space-x-4">
            <button onClick={() => scrollHorizontally(serviceScrollRef, 'left')} className="p-4 rounded-full border border-slate-200 hover:bg-white text-slate-400 hover:text-brand-900 transition-all shadow-sm">
              <ChevronLeft size={24} />
            </button>
            <button onClick={() => scrollHorizontally(serviceScrollRef, 'right')} className="p-4 rounded-full border border-slate-200 hover:bg-white text-slate-400 hover:text-brand-900 transition-all shadow-sm">
              <ChevronRight size={24} />
            </button>
          </div>
        </div>

        <div 
          ref={serviceScrollRef}
          className="flex overflow-x-auto gap-8 px-[max(1rem,calc((100vw-80rem)/2))] no-scrollbar scroll-smooth pb-4"
        >
          {latestServices.map((service) => (
            <div 
              key={service.id} 
              className="min-w-[320px] md:min-w-[400px] bg-white rounded-[2rem] overflow-hidden group hover:shadow-2xl transition-all duration-500 border border-brand-100 flex flex-col"
            >
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
                <h3 className="text-2xl font-serif text-slate-900 mb-4 line-clamp-1">{service.name}</h3>
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
          
          <div className="min-w-[320px] md:min-w-[400px] flex flex-col items-center justify-center p-12 bg-white rounded-[2rem] border-2 border-dashed border-brand-200 group hover:border-brand-900 transition-colors">
            <Link to="/services" className="text-center group flex flex-col items-center">
              <div className="w-20 h-20 bg-brand-50 rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm transition-transform group-hover:scale-110 group-hover:bg-brand-900 group-hover:text-white">
                <Scissors size={28} />
              </div>
              <h3 className="text-2xl font-serif text-slate-900 mb-2">Service Menu</h3>
              <p className="text-slate-500 mb-6 text-sm">View all artisanal rituals</p>
              <div className="flex items-center space-x-2 text-brand-900 font-bold uppercase text-xs tracking-[0.2em]">
                <span>Full Menu</span>
                <ArrowRight size={16} />
              </div>
            </Link>
          </div>
        </div>
        <ViewMoreBtn to="/services" label="view more service" />
      </section>

      {/* PRODUCT SLIDER SECTION */}
      <section className="py-24 bg-white overflow-hidden">
        <div className="max-w-7xl mx-auto px-4 mb-12 flex items-center justify-between">
          <div>
            <h2 className="text-sm font-bold text-brand-600 uppercase tracking-[0.2em] mb-4">Elite Apothecary</h2>
            <p className="text-4xl md:text-5xl font-serif text-slate-900">Featured Essentials</p>
          </div>
          <div className="flex space-x-4">
            <button onClick={() => scrollHorizontally(productScrollRef, 'left')} className="p-4 rounded-full border border-slate-200 hover:bg-slate-50 text-slate-400 hover:text-brand-900 transition-all">
              <ChevronLeft size={24} />
            </button>
            <button onClick={() => scrollHorizontally(productScrollRef, 'right')} className="p-4 rounded-full border border-slate-200 hover:bg-slate-50 text-slate-400 hover:text-brand-900 transition-all">
              <ChevronRight size={24} />
            </button>
          </div>
        </div>

        <div 
          ref={productScrollRef}
          className="flex overflow-x-auto gap-8 px-[max(1rem,calc((100vw-80rem)/2))] no-scrollbar scroll-smooth pb-4"
        >
          {latestProducts.map((product) => (
            <div 
              key={product.id} 
              className="min-w-[300px] md:min-w-[350px] group bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 border border-brand-100 flex flex-col"
            >
              <div className="h-64 overflow-hidden relative">
                <img src={product.image_url} className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt={product.name} />
                <div className="absolute top-6 left-6 bg-white/90 backdrop-blur p-2.5 rounded-xl text-brand-900 shadow-md">
                   <Tag size={18} />
                </div>
              </div>
              <div className="p-8 flex-grow flex flex-col items-center text-center">
                <p className="text-[10px] font-black text-brand-500 uppercase tracking-widest mb-2">{product.brand}</p>
                <h3 className="text-xl font-serif text-slate-900 mb-4 truncate w-full">{product.name}</h3>
                <p className="text-2xl font-bold text-brand-900 mb-6">৳{product.price}</p>
                
                <Link 
                  to={`/products/${product.id}`} 
                  className="inline-flex items-center justify-center space-x-2 bg-brand-900 text-white px-8 py-3.5 rounded-full font-bold text-[10px] uppercase tracking-widest transition-all hover:bg-brand-800 hover:scale-105 active:scale-95 shadow-lg shadow-brand-900/20"
                >
                  <span>Explore Item</span>
                  <ArrowRight size={14} />
                </Link>
              </div>
            </div>
          ))}
          
          <div className="min-w-[300px] md:min-w-[350px] flex flex-col items-center justify-center p-12 bg-brand-50 rounded-3xl border-2 border-dashed border-brand-200 group hover:border-brand-900 transition-colors">
            <Link to="/products" className="text-center group flex flex-col items-center">
              <div className="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-xl transition-transform group-hover:scale-110 group-hover:bg-brand-900 group-hover:text-white">
                <ShoppingBag size={28} />
              </div>
              <h3 className="text-2xl font-serif text-slate-900 mb-2">Explore All</h3>
              <p className="text-slate-500 mb-6 text-sm">Discover our full curated collection</p>
              <div className="flex items-center space-x-2 text-brand-900 font-bold uppercase text-xs tracking-[0.2em]">
                <span>Visit Apothecary</span>
                <ArrowRight size={16} />
              </div>
            </Link>
          </div>
        </div>
        <ViewMoreBtn to="/products" label="view more product" />
      </section>

      {/* TEAM SECTION ON HOME PAGE */}
      <section className="py-24 bg-brand-50">
        <div className="max-w-7xl mx-auto px-4">
          <div className="flex flex-col md:flex-row justify-between items-end mb-16 gap-6">
            <div className="max-w-xl">
              <h2 className="text-sm font-bold text-brand-600 uppercase tracking-[0.3em] mb-4">The Visionaries</h2>
              <h2 className="text-4xl md:text-5xl font-serif text-slate-900 leading-tight">Meet Our Master Artisans</h2>
            </div>
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
            {teamMembers.map((member) => (
              <div key={member.id} className="group flex flex-col items-center text-center">
                <div className="w-full aspect-[3/4] rounded-[2.5rem] overflow-hidden mb-8 relative border-2 border-transparent group-hover:border-brand-300 transition-all duration-500 shadow-lg group-hover:shadow-2xl">
                  <img 
                    src={member.image_url || 'https://via.placeholder.com/600x800'} 
                    alt={member.name} 
                    className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000" 
                  />
                </div>
                
                <h3 className="text-2xl font-serif text-slate-900 font-bold mb-1">{member.name}</h3>
                <p className="text-brand-600 font-black text-[10px] uppercase tracking-[0.2em] mb-6">{member.role}</p>
                
                <Link 
                  to={`/about/team/${member.id}`} 
                  className="inline-flex items-center justify-center space-x-2 bg-brand-900 text-white px-8 py-3.5 rounded-full font-bold text-[10px] uppercase tracking-widest transition-all hover:bg-brand-800 hover:scale-105 active:scale-95 shadow-lg shadow-brand-900/20"
                >
                  <span>View Profile</span>
                  <ArrowRight size={14} />
                </Link>
              </div>
            ))}
          </div>
          <ViewMoreBtn to="/about" label="view more expert" />
        </div>
      </section>

      {/* GALLERY SECTION - FIXED UNIFORM GRID WITH ZOOM */}
      <section className="py-24 bg-white">
        <div className="max-w-7xl mx-auto px-4">
          <div className="text-center mb-16">
            <h2 className="text-sm font-bold text-brand-600 uppercase tracking-[0.2em] mb-4">Aesthetic Highlights</h2>
            <p className="text-4xl md:text-5xl font-serif text-slate-900">Recent Masterpieces</p>
          </div>
          
          <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            {galleryItems.length > 0 ? galleryItems.map((item) => (
              <div 
                key={item.id} 
                onClick={() => setSelectedImage(item.image_url)}
                className="group relative aspect-square rounded-[2rem] overflow-hidden bg-slate-100 border border-brand-100 cursor-zoom-in"
              >
                <img src={item.image_url} className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000" alt={item.title} />
                <div className="absolute inset-0 bg-brand-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center p-6 text-center">
                  <Maximize2 className="text-white mb-2 w-6 h-6" />
                  <p className="text-white font-serif italic text-lg">{item.title}</p>
                </div>
              </div>
            )) : (
              <div className="col-span-full py-20 text-center bg-white rounded-[3rem] border-2 border-dashed border-brand-100">
                <ImageIcon className="mx-auto text-brand-200 w-16 h-16 mb-4" />
                <p className="text-slate-400 italic font-serif">Awaiting our next artistic transformation...</p>
              </div>
            )}
          </div>

          <div className="mt-16 text-center">
            <Link 
              to="/gallery" 
              className="inline-flex items-center justify-center space-x-3 bg-brand-900 text-white px-12 py-5 rounded-full font-bold text-xs uppercase tracking-[0.3em] transition-all hover:bg-brand-800 hover:scale-105 active:scale-95 shadow-xl shadow-brand-900/20"
            >
              <span>View Full Gallery</span>
              <ArrowRight size={20} />
            </Link>
          </div>
        </div>
      </section>

      {/* Testimonials */}
      <section className="bg-brand-50 py-24 overflow-hidden">
        <div className="max-w-7xl mx-auto px-4">
          <div className="text-center mb-16">
            <h2 className="text-sm font-bold text-brand-600 uppercase tracking-[0.2em] mb-4">Testimonials</h2>
            <p className="text-4xl md:text-5xl font-serif text-slate-900">Voices of Excellence</p>
          </div>
          <TestimonialSlider testimonials={testimonials} />
        </div>
      </section>

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

      {/* CTA Section */}
      <section className="py-24 relative overflow-hidden bg-brand-900 text-white">
        <div className="absolute inset-0 opacity-10">
          <img src="https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&q=80&w=2000" className="w-full h-full object-cover" alt="" />
        </div>
        <div className="relative z-10 max-w-7xl mx-auto px-4 text-center">
          <h2 className="text-4xl md:text-6xl font-serif mb-8">Ready to Radiate?</h2>
          <p className="text-xl text-brand-200 mb-12 max-w-2xl mx-auto leading-relaxed">
            Join the Style Studio Mart family today. Experience premium care and professional products.
          </p>
          <div className="flex justify-center">
            <Link to="/contact" className="bg-white text-brand-900 px-12 py-5 rounded-full text-lg font-bold hover:bg-brand-50 transition-all shadow-xl">
              Contact Us
            </Link>
          </div>
        </div>
      </section>
    </div>
  );
};

export default Home;
