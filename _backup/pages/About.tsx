
import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { supabase } from '../lib/supabase';
import { TeamMember } from '../types';
import { Instagram, Linkedin, Mail, Facebook, MessageCircle, ArrowRight, Sparkles, ShieldCheck } from 'lucide-react';

const AboutPage: React.FC = () => {
  const [team, setTeam] = useState<TeamMember[]>([]);

  useEffect(() => {
    const fetchTeam = async () => {
      const { data } = await supabase.from('team').select('*').order('name');
      if (data) setTeam(data);
    };
    fetchTeam();
  }, []);

  return (
    <div className="bg-white">
      {/* Brand Story - Featuring the Master Artisan and Studio Tech */}
      <section className="py-24 max-w-7xl mx-auto px-4">
        <div className="grid md:grid-cols-2 gap-12 lg:gap-20 items-center">
          <div className="relative group order-2 md:order-1">
            {/* The Main Reference Image Frame - Using your specific image */}
            <div className="aspect-[3/4] rounded-[2.5rem] overflow-hidden shadow-[0_32px_64px_-16px_rgba(67,48,43,0.3)] border-[6px] border-white transition-all duration-700 group-hover:scale-[1.01] bg-slate-100">
              <img 
                src="https://wbnrfhmbwtldxhnauggx.supabase.co/storage/v1/object/public/assets/sahajahan1.png" 
                alt="Style Studio Mart Master Artisan with Advanced Equipment" 
                className="w-full h-full object-cover object-center"
              />
              <div className="absolute inset-0 bg-gradient-to-t from-brand-900/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500" />
            </div>
            
            {/* Floating Info Tag - Moved to Top Right as requested */}
            <div className="absolute top-10 -right-4 md:-right-8 bg-white p-6 rounded-[2rem] shadow-2xl border border-brand-50 flex items-center space-x-4 animate-bounce-slow z-20">
              <div className="bg-brand-900 text-white p-3.5 rounded-2xl">
                <Sparkles size={28} />
              </div>
              <div>
                <p className="text-[10px] font-black text-brand-600 uppercase tracking-widest">Master Tech</p>
                <p className="text-base font-bold text-slate-900">Advanced Skincare</p>
              </div>
            </div>

            {/* Subtle decorative background element */}
            <div className="absolute -bottom-10 -left-10 w-48 h-48 bg-brand-50 rounded-[3rem] -z-10 animate-pulse" />
          </div>

          <div className="order-1 md:order-2 space-y-8">
            <div className="space-y-4">
               <h2 className="text-sm font-bold text-brand-600 uppercase tracking-[0.4em] flex items-center gap-3">
                 <div className="h-0.5 w-8 bg-brand-600"></div>
                 Our Legacy
               </h2>
               <h1 className="text-5xl md:text-7xl font-serif text-slate-900 leading-[1.1]">
                 Crafting Beauty <br /> Since <span className="italic text-brand-800">2023</span>
               </h1>
            </div>
            
            <div className="space-y-6">
              <p className="text-xl text-slate-600 leading-relaxed font-medium">
                Style Studio Mart brings the future of aesthetic care to you. Since 2023, we have committed to delivering results that speak for themselves.
              </p>
              <p className="text-lg text-slate-500 leading-relaxed">
                As seen in our studio, we invest in high-end, certified technology to ensure every treatment is effective and painless. Our master artisans are trained to handle the most complex aesthetic challenges with precision and care. 
              </p>
            </div>

            <div className="grid grid-cols-2 gap-10 pt-4">
              <div className="space-y-1">
                <p className="text-5xl font-serif text-brand-900 font-bold">2023</p>
                <p className="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Established</p>
              </div>
              <div className="space-y-1">
                <p className="text-5xl font-serif text-brand-900 font-bold">100%</p>
                <p className="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Quality Care</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Team Section */}
      <section className="bg-brand-50 py-24">
        <div className="max-w-7xl mx-auto px-4">
          <div className="text-center mb-20 space-y-4">
            <h2 className="text-sm font-bold text-brand-600 uppercase tracking-[0.3em]">The Visionaries</h2>
            <h2 className="text-4xl md:text-5xl font-serif text-slate-900">Meet Our Experts</h2>
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-x-10 gap-y-16">
            {team.length > 0 ? team.map((member) => (
              <div key={member.id} className="group text-center flex flex-col items-center">
                <div className="w-full aspect-[3/4] rounded-[2.5rem] overflow-hidden mb-8 relative border-2 border-transparent group-hover:border-brand-300 transition-all duration-500 shadow-sm hover:shadow-2xl">
                  <img 
                    src={member.image_url || 'https://via.placeholder.com/600x800'} 
                    alt={member.name} 
                    className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000" 
                  />
                  
                  {/* Social Overlay */}
                  <div className="absolute inset-0 bg-brand-900/40 backdrop-blur-[2px] flex items-center justify-center space-x-4 opacity-0 group-hover:opacity-100 transition-all duration-500">
                    {member.facebook_url && (
                      <a href={member.facebook_url} target="_blank" rel="noopener noreferrer" className="bg-white p-3 rounded-full text-brand-900 transition-transform hover:scale-110 hover:bg-brand-900 hover:text-white shadow-lg">
                        <Facebook size={20} />
                      </a>
                    )}
                    {member.instagram_url && (
                      <a href={member.instagram_url} target="_blank" rel="noopener noreferrer" className="bg-white p-3 rounded-full text-brand-900 transition-transform hover:scale-110 hover:bg-brand-900 hover:text-white shadow-lg">
                        <Instagram size={20} />
                      </a>
                    )}
                    {member.whatsapp_url && (
                      <a href={member.whatsapp_url} target="_blank" rel="noopener noreferrer" className="bg-white p-3 rounded-full text-brand-900 transition-transform hover:scale-110 hover:bg-brand-900 hover:text-white shadow-lg">
                        <MessageCircle size={20} />
                      </a>
                    )}
                  </div>
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
            )) : (
              <p className="col-span-full text-center text-slate-400 italic py-12">Team profiles are ready and waiting...</p>
            )}
          </div>
        </div>
      </section>
    </div>
  );
};

export default AboutPage;
