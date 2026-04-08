export interface Service {
    id: string;
    name: string;
    description: string;
    price: number;
    duration: string;
    category: string;
    image_url: string;
}

export interface Product {
    id: string;
    name: string;
    description: string;
    price: number;
    brand: string;
    category: string;
    image_url: string;
    is_active: boolean;
}

export interface GalleryItem {
    id: string;
    title: string;
    category: string;
    image_url: string;
}

export interface TeamMember {
    id: string;
    name: string;
    role: string;
    bio: string;
    specialty: string[];
    image_url: string;
    facebook_url?: string;
    instagram_url?: string;
    whatsapp_url?: string;
}

export interface Testimonial {
    id: string;
    author: string;
    content: string;
    rating: number;
    avatar_url: string;
}
