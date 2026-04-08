
import React, { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import { supabase } from '../lib/supabase';
import { TeamMember } from '../types';
import { ArrowLeft, Facebook, Instagram, MessageCircle, Star, Tag, Loader2, Quote } from 'lucide-react';

const TeamMemberDetail: React.FC = () => {
  const { id } = useParams<{ id: string }>();
  const [member, setMember] = useState<TeamMember | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    if (id) {
      fetchMemberDetails();
      window.scrollTo(0, 0);
    }
  }, [id]);

  const fetchMemberDetails = async () => {
    setLoading(true);
    const { data, error } = await supabase
      .from('team')
      .select('*')
      .eq('id', id)
      .single();

    if (!error && data) {
      setMember(data);
    }
    setLoading(false);
  };

  if (loading) {
    return (
      <div className="min-h-screen flex flex-col items-center justify-center bg-brand-50">
        <Loader2 className="animate-spin text-brand-900 w-12 h-12 mb-4" />
        <p className="text-brand-900 font-serif italic">Loading profile...</p>
      </div>
    );
  }

  if (!member) {
    return (
      <div className="min-h-screen flex flex-col items-center justify-center p-4">
        <h2 className="text-3xl font-serif mb-4 text-slate-900">Artisan Not Found</h2>
        <Link to="/about" className="text-brand-900 font-bold flex items-center space-x-2">
          <ArrowLeft size={20} />
          <span>Back to About Us</span>
        </Link>
      </div>
    );
  }

  return (
    <div className="bg-white min-h-screen">
      {/* Back Link */}
      <div className="max-w-7xl mx-auto px-4 py-8">
        <Link to="/about" className="inline-flex items-center space-x-2 text-slate-400 hover:text-brand-900 transition-colors font-bold text-sm uppercase tracking-widest">
          <ArrowLeft size={16} />
          <span>Back to Team</span>
        </Link>
      </div>

      <div className="max-w-7xl mx-auto px-4 pb-24">
        <div className="grid lg:grid-cols-2 gap-16 items-start">
          
          {/* Staff Image */}
          <div className="relative group">
            <div className="aspect-[3/4] rounded-[4rem] overflow-hidden shadow-2xl border-4 border-white">
              <img 
                src={member.image_url || 'https://via.placeholder.com/800x1000'} 
                alt={member.name} 
                className="w-full h-full object-cover"
              />
            </div>
            {/* Decoration */}
            <div className="absolute -z-10 -bottom-10 -left-10 w-48 h-48 bg-brand-100 rounded-[3rem] animate-pulse" />
          </div>

          {/* Details */}
          <div className="space-y-12">
            <div>
              <div className="flex items-center space-x-2 text-brand-600 mb-6">
                <Star className="fill-brand-600" size={16} />
                <span className="font-black uppercase tracking-[0.4em] text-[10px]">Master Artisan</span>
              </div>
              <h1 className="text-6xl md:text-7xl font-serif text-slate-900 mb-4 leading-none">{member.name}</h1>
              <p className="text-2xl font-serif text-brand-700 italic">{member.role}</p>
            </div>

            <div className="space-y-6">
              <h4 className="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">Areas of Expertise</h4>
              <div className="flex flex-wrap gap-3">
                {member.specialty.map((spec, i) => (
                  <span key={i} className="flex items-center space-x-2 bg-slate-50 text-slate-700 px-5 py-2.5 rounded-full border border-slate-100 text-sm font-bold shadow-sm">
                    <Tag size={14} className="text-brand-600" />
                    <span>{spec}</span>
                  </span>
                ))}
              </div>
            </div>

            <div className="space-y-6">
              <h4 className="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">Biography</h4>
              <div className="relative">
                <Quote className="absolute -left-8 -top-4 text-brand-100 w-16 h-16 -z-10" />
                <p className="text-slate-600 text-xl leading-relaxed font-light italic">
                  {member.bio || "This master artisan has dedicated years to perfecting their craft, ensuring that every client receives a world-class beauty experience at Style Studio Mart."}
                </p>
              </div>
            </div>

            {/* Platform Themed Social Media Links */}
            <div className="pt-8 border-t border-slate-100 space-y-6">
              <h4 className="text-[10px] font-black text-slate-400 uppercase tracking-widest">Connect with {member.name.split(' ')[0]}</h4>
              <div className="flex flex-wrap gap-4">
                {member.facebook_url && (
                  <a 
                    href={member.facebook_url} 
                    target="_blank" 
                    rel="noopener noreferrer"
                    className="flex-1 min-w-[160px] flex items-center justify-center space-x-3 bg-[#1877F2] py-6 rounded-3xl text-white transition-all duration-300 group shadow-lg hover:shadow-[#1877F2]/30 hover:scale-[1.05] active:scale-95"
                  >
                    <Facebook size={24} />
                    <span className="font-bold">Facebook</span>
                  </a>
                )}
                {member.instagram_url && (
                  <a 
                    href={member.instagram_url} 
                    target="_blank" 
                    rel="noopener noreferrer"
                    className="flex-1 min-w-[160px] flex items-center justify-center space-x-3 bg-gradient-to-tr from-[#f09433] via-[#e6683c] to-[#bc1888] py-6 rounded-3xl text-white transition-all duration-300 group shadow-lg hover:shadow-[#bc1888]/30 hover:scale-[1.05] active:scale-95"
                  >
                    <Instagram size={24} />
                    <span className="font-bold">Instagram</span>
                  </a>
                )}
                {member.whatsapp_url && (
                  <a 
                    href={member.whatsapp_url} 
                    target="_blank" 
                    rel="noopener noreferrer"
                    className="flex-1 min-w-[160px] flex items-center justify-center space-x-3 bg-[#25D366] py-6 rounded-3xl text-white transition-all duration-300 group shadow-lg hover:shadow-[#25D366]/30 hover:scale-[1.05] active:scale-95"
                  >
                    <MessageCircle size={24} />
                    <span className="font-bold">WhatsApp</span>
                  </a>
                )}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default TeamMemberDetail;
