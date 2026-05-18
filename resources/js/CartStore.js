import { reactive, watch } from 'vue';

const CART_STORAGE_KEY = 'ssm_cart';

const storedCart = localStorage.getItem(CART_STORAGE_KEY);
const initialState = storedCart ? JSON.parse(storedCart) : { items: [] };

export const cart = reactive({
    items: initialState.items,
    
    addItem(product, quantity = 1) {
        const existingItem = this.items.find(item => item.id === product.id);
        if (existingItem) {
            existingItem.quantity += quantity;
        } else {
            let rawImage = product.image_url || product.image;
            let imageUrl = Array.isArray(rawImage) ? rawImage[0] : rawImage;
            
            if (imageUrl && !imageUrl.startsWith('http') && !imageUrl.startsWith('/storage/') && !imageUrl.startsWith('/')) {
                imageUrl = `/storage/${imageUrl}`;
            }
            
            if (!imageUrl || imageUrl === 'undefined' || imageUrl.includes('undefined')) {
                imageUrl = 'https://placehold.co/400x400';
            }

            const finalPrice = parseFloat(product.offer_price) > 0 
                ? parseFloat(product.offer_price) 
                : parseFloat(product.price);

            this.items.push({
                id: product.id,
                name: product.name,
                price: finalPrice,
                image: imageUrl,
                brand: product.brand,
                quantity: quantity
            });
        }
    },
    
    removeItem(productId) {
        const index = this.items.findIndex(item => item.id === productId);
        if (index !== -1) {
            this.items.splice(index, 1);
        }
    },
    
    updateQuantity(productId, quantity) {
        const item = this.items.find(item => item.id === productId);
        if (item) {
            item.quantity = Math.max(1, quantity);
        }
    },
    
    clear() {
        this.items = [];
    },
    
    get subtotal() {
        return this.items.reduce((total, item) => total + (item.price * item.quantity), 0);
    },
    
    get totalItems() {
        return this.items.reduce((total, item) => total + item.quantity, 0);
    }
});

watch(() => cart.items, (newItems) => {
    localStorage.setItem(CART_STORAGE_KEY, JSON.stringify({ items: newItems }));
}, { deep: true });
