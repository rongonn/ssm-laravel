
import React, { useState } from 'react';
import { Link, useLocation } from 'react-router-dom';
import { Menu, X, User, LogIn } from 'lucide-react';

interface NavbarProps {
  session: any;
}

const Navbar: React.FC<NavbarProps> = ({ session }) => {
  const [isOpen, setIsOpen] = useState(false);
  const location = useLocation();
  const LOGO_URL = "https://wbnrfhmbwtldxhnauggx.supabase.co/storage/v1/object/public/assets/ssm%20logo.jpg";

  const navLinks = [
    { name: 'Home', path: '/' },
    { name: 'Services', path: '/services' },
    { name: 'Products', path: '/products' },
    { name: 'Gallery', path: '/gallery' },
    { name: 'About', path: '/about' },
    { name: 'Contact', path: '/contact' },
  ];

  const isActive = (path: string) => location.pathname === path;

  return (
    <nav className="sticky top-0 z-50 glass border-b border-brand-200">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex justify-between h-20">
          <div className="flex items-center">
            <Link to="/" className="flex-shrink-0 flex items-center group space-x-3">
              <img 
                src={LOGO_URL} 
                alt="Style Studio Mart Logo" 
                className="h-10 w-10 md:h-12 md:w-12 rounded-xl object-cover shadow-sm group-hover:scale-105 transition-transform border border-brand-100"
              />
              <span className="text-xl md:text-2xl font-serif font-bold text-brand-900 tracking-tight group-hover:text-brand-600 transition-colors uppercase">
                Style Studio Mart
              </span>
            </Link>
          </div>

          <div className="hidden md:flex items-center space-x-8">
            {navLinks.map((link) => (
              <Link
                key={link.name}
                to={link.path}
                className={`text-sm font-medium tracking-wide transition-colors ${
                  isActive(link.path) ? 'text-brand-900' : 'text-slate-500 hover:text-brand-600'
                }`}
              >
                {link.name}
              </Link>
            ))}
            {session ? (
              <Link
                to="/admin"
                className="flex items-center space-x-2 bg-brand-900 text-white px-6 py-2.5 rounded-full text-sm font-semibold hover:bg-brand-800 transition-all transform hover:scale-105 active:scale-95 shadow-lg shadow-brand-900/10"
              >
                <User size={18} />
                <span>Admin</span>
              </Link>
            ) : (
              <Link
                to="/auth"
                className="flex items-center space-x-2 bg-brand-900 text-white px-6 py-2.5 rounded-full text-sm font-semibold hover:bg-brand-800 transition-all transform hover:scale-105 active:scale-95 shadow-lg shadow-brand-900/10"
              >
                <LogIn size={18} />
                <span>Login</span>
              </Link>
            )}
          </div>

          <div className="md:hidden flex items-center">
            <button
              onClick={() => setIsOpen(!isOpen)}
              className="text-slate-600 hover:text-brand-900 p-2"
            >
              {isOpen ? <X size={24} /> : <Menu size={24} />}
            </button>
          </div>
        </div>
      </div>

      {/* Mobile menu */}
      {isOpen && (
        <div className="md:hidden bg-white/95 backdrop-blur-md border-b border-brand-100 animate-in fade-in slide-in-from-top-4 duration-300">
          <div className="px-4 pt-2 pb-6 space-y-2">
            {navLinks.map((link) => (
              <Link
                key={link.name}
                to={link.path}
                onClick={() => setIsOpen(false)}
                className={`block px-3 py-3 rounded-md text-base font-medium ${
                  isActive(link.path) ? 'bg-brand-50 text-brand-900' : 'text-slate-600 hover:bg-brand-50'
                }`}
              >
                {link.name}
              </Link>
            ))}
            {session ? (
              <Link
                to="/admin"
                onClick={() => setIsOpen(false)}
                className="flex items-center justify-center space-x-2 w-full mt-4 bg-brand-900 text-white px-6 py-4 rounded-xl text-lg font-semibold"
              >
                <User size={20} />
                <span>Admin Dashboard</span>
              </Link>
            ) : (
              <Link
                to="/auth"
                onClick={() => setIsOpen(false)}
                className="flex items-center justify-center space-x-2 w-full mt-4 bg-brand-900 text-white px-6 py-4 rounded-xl text-lg font-semibold"
              >
                <LogIn size={20} />
                <span>Login</span>
              </Link>
            )}
          </div>
        </div>
      )}
    </nav>
  );
};

export default Navbar;
