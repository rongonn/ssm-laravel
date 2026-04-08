
export interface Service {
  id: string;
  name: string;
  description: string;
  price: number;
  duration: string;
  category: 'Hair' | 'Skin' | 'Nails' | 'Massage' | 'Other';
  image_url: string;
  created_at?: string;
}

export interface Product {
  id: string;
  name: string;
  description: string;
  price: number;
  image_url: string;
  brand: string;
  stock: number;
  category: string;
  is_active: boolean;
  created_at?: string;
}

export interface TeamMember {
  id: string;
  name: string;
  role: string;
  bio: string;
  image_url: string;
  specialty: string[];
  facebook_url?: string;
  instagram_url?: string;
  whatsapp_url?: string;
  created_at?: string;
}

export interface Testimonial {
  id: string;
  author: string;
  content: string;
  rating: number;
  avatar_url?: string;
  date: string;
}

export interface GalleryItem {
  id: string;
  title: string;
  image_url: string;
  category: string;
}
