
import React, { useState, useEffect } from 'react';
import { Star, ChevronLeft, ChevronRight, Quote } from 'lucide-react';
import { Testimonial } from '../types';

interface TestimonialSliderProps {
  testimonials: Testimonial[];
}

const TestimonialSlider: React.FC<TestimonialSliderProps> = ({ testimonials }) => {
  const [currentIndex, setCurrentIndex] = useState(0);

  const next = () => setCurrentIndex((prev) => (prev + 1) % testimonials.length);
  const prev = () => setCurrentIndex((prev) => (prev - 1 + testimonials.length) % testimonials.length);

  useEffect(() => {
    if (testimonials.length > 1) {
      const interval = setInterval(next, 8000);
      return () => clearInterval(interval);
    }
  }, [testimonials]);

  if (testimonials.length === 0) {
    return (
      <div className="text-center py-20 text-slate-400 italic font-serif">
        "Be the first to leave us a review..."
      </div>
    );
  }

  const active = testimonials[currentIndex];

  return (
    <div className="relative max-w-4xl mx-auto px-4 py-16">
      <div className="flex flex-col items-center text-center">
        <Quote className="text-brand-300 w-16 h-16 mb-8 opacity-50" />
        
        <div key={active.id} className="animate-in fade-in zoom-in duration-700">
          <div className="flex space-x-1 mb-6 justify-center">
            {[...Array(active.rating)].map((_, i) => (
              <Star key={i} className="w-5 h-5 fill-brand-500 text-brand-500" />
            ))}
          </div>
          
          <blockquote className="text-2xl md:text-3xl font-serif italic text-slate-800 mb-8 leading-relaxed">
            "{active.content}"
          </blockquote>
          
          <div className="flex items-center justify-center space-x-4">
            <img 
              src={active.avatar_url || 'https://via.placeholder.com/100'} 
              alt={active.author}
              className="w-14 h-14 rounded-full border-2 border-brand-200 object-cover"
            />
            <div className="text-left">
              <p className="font-bold text-slate-900">{active.author}</p>
              <p className="text-sm text-slate-500">Verified Client</p>
            </div>
          </div>
        </div>

        {testimonials.length > 1 && (
          <div className="flex space-x-4 mt-12">
            <button 
              onClick={prev}
              className="p-3 rounded-full border border-brand-200 hover:bg-brand-50 text-slate-400 hover:text-brand-900 transition-all"
            >
              <ChevronLeft size={24} />
            </button>
            <button 
              onClick={next}
              className="p-3 rounded-full border border-brand-200 hover:bg-brand-50 text-slate-400 hover:text-brand-900 transition-all"
            >
              <ChevronRight size={24} />
            </button>
          </div>
        )}
      </div>
    </div>
  );
};

export default TestimonialSlider;
