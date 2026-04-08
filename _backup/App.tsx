
import React, { useState, useEffect } from 'react';
import { HashRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import Navbar from './components/Navbar';
import Footer from './components/Footer';
import Home from './pages/Home';
import ServicesPage from './pages/Services';
import ServiceDetailPage from './pages/ServiceDetail';
import ProductsPage from './pages/Products';
import ProductDetailPage from './pages/ProductDetail';
import GalleryPage from './pages/Gallery';
import AboutPage from './pages/About';
import TeamMemberDetail from './pages/TeamMemberDetail';
import ContactPage from './pages/Contact';
import AdminDashboard from './pages/Admin';
import AuthPage from './pages/Auth';
import { supabase } from './lib/supabase';

const App: React.FC = () => {
  const [session, setSession] = useState<any>(null);

  useEffect(() => {
    supabase.auth.getSession().then(({ data: { session } }) => {
      setSession(session);
    });

    const { data: { subscription } } = supabase.auth.onAuthStateChange((_event, session) => {
      setSession(session);
    });

    return () => subscription.unsubscribe();
  }, []);

  return (
    <Router>
      <div className="flex flex-col min-h-screen">
        <Navbar session={session} />
        <main className="flex-grow">
          <Routes>
            <Route path="/" element={<Home />} />
            <Route path="/services" element={<ServicesPage />} />
            <Route path="/services/:id" element={<ServiceDetailPage />} />
            <Route path="/products" element={<ProductsPage />} />
            <Route path="/products/:id" element={<ProductDetailPage />} />
            <Route path="/gallery" element={<GalleryPage />} />
            <Route path="/about" element={<AboutPage />} />
            <Route path="/about/team/:id" element={<TeamMemberDetail />} />
            <Route path="/contact" element={<ContactPage />} />
            <Route path="/auth" element={!session ? <AuthPage /> : <Navigate to="/admin" />} />
            <Route 
              path="/admin/*" 
              element={session ? <AdminDashboard /> : <Navigate to="/auth" />} 
            />
          </Routes>
        </main>
        <Footer />
      </div>
    </Router>
  );
};

export default App;
