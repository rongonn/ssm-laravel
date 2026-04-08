
import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { supabase } from '../lib/supabase';
import { Lock, Mail, ChevronRight, Sparkles, UserPlus, LogIn, CheckCircle2 } from 'lucide-react';

const AuthPage: React.FC = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [success, setSuccess] = useState<string | null>(null);
  const [isSignUp, setIsSignUp] = useState(false);
  const navigate = useNavigate();
  const LOGO_URL = "https://wbnrfhmbwtldxhnauggx.supabase.co/storage/v1/object/public/assets/ssm%20logo.jpg";

  const handleAuth = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    setError(null);
    setSuccess(null);

    if (isSignUp) {
      const { data, error } = await supabase.auth.signUp({ email, password });
      if (error) {
        setError(error.message);
        setLoading(false);
      } else {
        if (data.session) {
          navigate('/admin');
        } else {
          setSuccess('Account created successfully! You can now log in.');
          setIsSignUp(false);
          setLoading(false);
          setEmail('');
          setPassword('');
        }
      }
    } else {
      const { error } = await supabase.auth.signInWithPassword({ email, password });
      if (error) {
        setError(error.message);
        setLoading(false);
      } else {
        navigate('/admin');
      }
    }
  };

  return (
    <div className="min-h-screen bg-brand-50 flex items-center justify-center px-4 py-12">
      <div className="absolute top-0 left-0 w-full h-full overflow-hidden z-0 opacity-20 pointer-events-none">
        <div className="absolute -top-24 -left-24 w-96 h-96 bg-brand-300 rounded-full blur-3xl animate-pulse" />
        <div className="absolute top-1/2 -right-24 w-64 h-64 bg-brand-200 rounded-full blur-3xl" />
      </div>

      <div className="w-full max-w-md relative z-10">
        <div className="text-center mb-10">
          <div className="inline-flex items-center justify-center p-1 bg-white rounded-3xl shadow-2xl shadow-brand-900/10 mb-6 overflow-hidden border-4 border-white">
            <img src={LOGO_URL} alt="Logo" className="w-24 h-24 object-cover rounded-[1.25rem]" />
          </div>
          <h1 className="text-4xl font-serif text-slate-900 font-bold mb-2">
            {isSignUp ? 'Join Style Studio' : 'Welcome Back'}
          </h1>
          <p className="text-slate-500">
            {isSignUp ? 'Create an admin account to manage your mart.' : 'Access your management dashboard.'}
          </p>
        </div>

        <div className="bg-white p-10 rounded-[2.5rem] shadow-2xl shadow-brand-900/10 border border-brand-100">
          {error && (
            <div className="mb-6 p-4 bg-red-50 text-red-600 rounded-xl text-sm font-medium border border-red-100 flex items-center gap-2">
              <span className="shrink-0">⚠️</span>
              {error}
            </div>
          )}
          {success && (
            <div className="mb-6 p-4 bg-green-50 text-green-600 rounded-xl text-sm font-medium border border-green-100 flex items-center gap-2">
              <CheckCircle2 size={18} className="shrink-0" />
              {success}
            </div>
          )}
          <form onSubmit={handleAuth} className="space-y-6">
            <div>
              <label className="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Email Address</label>
              <div className="relative">
                <Mail className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
                <input 
                  type="email" 
                  required
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  className="w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-brand-500 outline-none transition-all placeholder:text-slate-300"
                  placeholder="admin@stylestudiomart.com"
                />
              </div>
            </div>

            <div>
              <label className="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Password</label>
              <div className="relative">
                <Lock className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
                <input 
                  type="password" 
                  required
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                  className="w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-brand-500 outline-none transition-all placeholder:text-slate-300"
                  placeholder="••••••••"
                />
              </div>
            </div>

            <button 
              type="submit" 
              disabled={loading}
              className="w-full bg-brand-900 text-white py-5 rounded-2xl font-bold text-lg hover:bg-brand-800 transition-all flex items-center justify-center space-x-2 transform active:scale-[0.98] shadow-xl shadow-brand-900/20 disabled:opacity-50"
            >
              <span>{loading ? 'Processing...' : (isSignUp ? 'Create Account' : 'Enter Dashboard')}</span>
              {!loading && <ChevronRight size={20} />}
            </button>
          </form>

          <div className="mt-8 pt-6 border-t border-slate-100 text-center">
            <button 
              onClick={() => {
                setIsSignUp(!isSignUp);
                setError(null);
                setSuccess(null);
              }}
              className="text-brand-700 font-bold hover:text-brand-900 transition-colors flex items-center justify-center space-x-2 mx-auto"
            >
              {isSignUp ? (
                <>
                  <LogIn size={18} />
                  <span>Already have an account? Login</span>
                </>
              ) : (
                <>
                  <UserPlus size={18} />
                  <span>Don't have an account? Sign Up</span>
                </>
              )}
            </button>
          </div>
        </div>
      </div>
    </div>
  );
};

export default AuthPage;
