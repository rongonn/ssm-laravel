
import React from 'react';
import { Link } from 'react-router-dom';
import { Instagram, Facebook, Youtube, Mail, MapPin, MessageCircle } from 'lucide-react';

const Footer: React.FC = () => {
  const CONTACT_NUMBER = "01911-879571";
  const ADDRESS = "Plot no 14, Tota Mia complex shop no 19, 22 Mirpur 10 Dhaka Bangladesh. Road no 1, 15 Dhaka north city, 1216";
  const LOGO_URL = "https://wbnrfhmbwtldxhnauggx.supabase.co/storage/v1/object/public/assets/ssm%20logo.jpg";

  return (
    <footer className="bg-slate-900 text-slate-400 py-20">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="grid md:grid-cols-4 gap-12 mb-16">
          <div className="col-span-1 md:col-span-1">
            <Link to="/" className="flex items-center space-x-3 mb-8 group">
              <img 
                src={LOGO_URL} 
                alt="Style Studio Mart" 
                className="h-12 w-12 rounded-xl object-cover border border-slate-700 group-hover:border-brand-400 transition-colors"
              />
              <span className="text-2xl font-serif font-bold text-white tracking-tight uppercase">
                Style Studio Mart
              </span>
            </Link>
            <p className="leading-relaxed mb-8">
              Premium salon and wellness experience dedicated to redefining beauty standards through artisanal techniques and luxury care.
            </p>
            <div className="flex space-x-5">
              <a href="https://www.facebook.com/stylestudiomart2024/" target="_blank" rel="noopener noreferrer" className="hover:text-white transition-colors text-slate-400"><Facebook size={22} /></a>
              <a href="https://www.instagram.com/shahjahan.khairul79" target="_blank" rel="noopener noreferrer" className="hover:text-white transition-colors text-slate-400"><Instagram size={22} /></a>
              <a href="https://www.youtube.com/@ShahjahanKhairul79" target="_blank" rel="noopener noreferrer" className="hover:text-white transition-colors text-slate-400"><Youtube size={22} /></a>
            </div>
          </div>
          
          <div>
            <h4 className="text-white font-bold uppercase tracking-widest text-sm mb-8">Navigation</h4>
            <ul className="space-y-4">
              <li><Link to="/services" className="hover:text-brand-300 transition-colors">Our Services</Link></li>
              <li><Link to="/products" className="hover:text-brand-300 transition-colors">Product Shop</Link></li>
              <li><Link to="/about" className="hover:text-brand-300 transition-colors">About Team</Link></li>
              <li><Link to="/contact" className="hover:text-brand-300 transition-colors">Contact Us</Link></li>
            </ul>
          </div>

          <div>
            <h4 className="text-white font-bold uppercase tracking-widest text-sm mb-8">Opening Hours</h4>
            <ul className="space-y-4">
              <li className="flex justify-between"><span>Mon - Fri</span> <span className="text-white">9am - 8pm</span></li>
              <li className="flex justify-between"><span>Saturday</span> <span className="text-white">10am - 6pm</span></li>
              <li className="flex justify-between"><span>Sunday</span> <span className="text-white">Closed</span></li>
            </ul>
          </div>

          <div>
            <h4 className="text-white font-bold uppercase tracking-widest text-sm mb-8">Contact Info</h4>
            <ul className="space-y-4">
              <li className="flex items-start space-x-3">
                <MapPin size={20} className="text-brand-400 flex-shrink-0" />
                <span className="text-sm leading-relaxed">{ADDRESS}</span>
              </li>
              <li className="flex items-center space-x-3">
                <MessageCircle size={20} className="text-brand-400" />
                <span>{CONTACT_NUMBER}</span>
              </li>
              <li className="flex items-center space-x-3">
                <Mail size={20} className="text-brand-400" />
                <span className="text-sm">shahjahan.khairul79@gmail.com</span>
              </li>
            </ul>
          </div>
        </div>
        
        <div className="pt-8 border-t border-slate-800 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0 text-sm">
          <p>© 2024 Style Studio Mart. All Rights Reserved.</p>
          <div className="flex space-x-6">
            <a href="#" className="hover:text-white">Privacy Policy</a>
            <a href="#" className="hover:text-white">Terms of Service</a>
          </div>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
