<?php

namespace SSM\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SSM\Models\Service;
use SSM\Models\Product;
use SSM\Models\Team;
use SSM\Models\Testimonial;
use SSM\Models\Gallery;
use SSM\Models\Contact;
use SSM\Models\Category;
use SSM\Models\Order;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    protected $modelMap = [
        'services'     => Service::class,
        'products'     => Product::class,
        'team'         => Team::class,
        'testimonials' => Testimonial::class,
        'gallery'      => Gallery::class,
        'categories'   => Category::class,
        'orders'       => Order::class,
        'contacts'     => Contact::class,
    ];

    public function index()
    {
        return view('ssm::admin.dashboard', [
            'serviceCount' => Service::count(),
            'productCount' => Product::count(),
            'teamCount'    => Team::count(),
            'contactCount' => Contact::count(),
            'unreadContacts' => Contact::where('is_read', false)->count(),
            'newOrders'    => Order::where('status', 'New Order')->count(),
            'totalOrders'  => Order::count(),
            'shippedOrders'=> Order::where('status', 'Shipped')->count(),
        ]);
    }

    public function servicesIndex(Request $request)
    {
        $query = Service::query();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('category', 'like', '%' . $search . '%');
            });
        }
        return view('ssm::admin.services.index', [
            'services' => $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString(),
            'categories' => Category::where('is_active', true)->get()
        ]);
    }

    public function productsIndex(Request $request)
    {
        $query = Product::query();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }
        return view('ssm::admin.products.index', [
            'products' => $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString(),
            'categories' => Category::where('is_active', true)->get()
        ]);
    }

    public function teamIndex(Request $request)
    {
        $query = Team::query();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('role', 'like', '%' . $search . '%');
            });
        }
        return view('ssm::admin.team.index', [
            'team' => $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString()
        ]);
    }

    public function testimonialsIndex(Request $request)
    {
        $query = Testimonial::query();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('author', 'like', '%' . $search . '%')
                  ->orWhere('content', 'like', '%' . $search . '%');
            });
        }
        return view('ssm::admin.testimonials.index', [
            'testimonials' => $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString()
        ]);
    }

    public function galleryIndex(Request $request)
    {
        $query = Gallery::query();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('category', 'like', '%' . $search . '%');
            });
        }
        return view('ssm::admin.gallery.index', [
            'gallery' => $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString(),
            'categories' => Category::where('is_active', true)->get()
        ]);
    }

    public function contactsIndex(Request $request)
    {
        $query = Contact::query();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('subject', 'like', '%' . $search . '%');
            });
        }
        return view('ssm::admin.contacts.index', [
            'contacts' => $query->orderBy('is_read', 'asc')->orderBy('created_at', 'desc')->paginate(20)->withQueryString()
        ]);
    }

    public function markContactRead($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->update(['is_read' => true]);
        return back()->with('success', 'Contact marked as read');
    }

    public function categoriesIndex(Request $request)
    {
        $query = Category::query();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', '%' . $search . '%');
        }
        return view('ssm::admin.categories.index', [
            'categories' => $query->orderBy('name')->paginate(20)->withQueryString()
        ]);
    }

    public function ordersIndex(Request $request)
    {
        $query = Order::with('items.product');
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('customer_name', 'like', '%' . $search . '%')
                  ->orWhere('customer_phone', 'like', '%' . $search . '%')
                  ->orWhere('order_no', 'like', '%' . $search . '%');
            });
        }

        return view('ssm::admin.orders.index', [
            'orders' => $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString(),
            'products' => Product::all(),
            'statuses' => Order::$statuses,
            'sources' => ['Website', 'Manual']
        ]);
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);
        return back()->with('success', 'Order status updated');
    }

    public function manualOrderStore(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric',
            'subtotal' => 'required|numeric',
            'delivery_charge' => 'required|numeric',
            'discount' => 'required|numeric',
            'total_price' => 'required|numeric'
        ]);

        \DB::transaction(function () use ($request) {
            $order = Order::create([
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'subtotal' => $request->subtotal,
                'delivery_charge' => $request->delivery_charge,
                'discount' => $request->discount,
                'total_price' => $request->total_price,
                'status' => 'New Order',
                'source' => 'Manual'
            ]);

            foreach ($request->items as $item) {
                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
            }
        });

        return back()->with('success', 'Manual order created successfully');
    }

    public function updateManualOrder(Request $request, $id)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric',
            'subtotal' => 'required|numeric',
            'delivery_charge' => 'required|numeric',
            'discount' => 'required|numeric',
            'total_price' => 'required|numeric'
        ]);

        \DB::transaction(function () use ($request, $id) {
            $order = Order::findOrFail($id);
            $order->update([
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'subtotal' => $request->subtotal,
                'delivery_charge' => $request->delivery_charge,
                'discount' => $request->discount,
                'total_price' => $request->total_price,
            ]);

            $order->items()->delete();

            foreach ($request->items as $item) {
                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
            }
        });

        return back()->with('success', 'Order updated successfully');
    }
    public function store(Request $request, $table)
    {
        $modelClass = $this->modelMap[$table] ?? null;
        if (!$modelClass) return back()->withErrors('Invalid table');

        $data = $request->except('image', 'images');

        if ($table === 'products') {
            // Multi-image support for products
            $images = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    if ($file && $file->isValid()) {
                        $path = $file->store('assets', 'public');
                        $images[] = Storage::url($path);
                        if (count($images) >= 5) break;
                    }
                }
            } elseif ($request->hasFile('image')) {
                $file = $request->file('image');
                if ($file && $file->isValid()) {
                    $path = $file->store('assets', 'public');
                    $images[] = Storage::url($path);
                }
            }
            $data['image_url'] = $images;
        } else {
            $file = $request->file('image') ?? $request->image;
            if ($file instanceof \Illuminate\Http\UploadedFile) {
                $path = $file->store('assets', 'public');
                $url = Storage::url($path);
                if ($table === 'testimonials') {
                    $data['avatar_url'] = $url;
                } else {
                    $data['image_url'] = $url;
                }
            } else {
                if ($table === 'testimonials' && !isset($data['avatar_url'])) {
                    $data['avatar_url'] = null;
                } elseif (!in_array($table, ['categories', 'contacts']) && !isset($data['image_url'])) {
                    $data['image_url'] = null;
                }
            }
        }

        if ($table === 'team' && isset($data['specialty']) && is_string($data['specialty'])) {
            $data['specialty'] = array_map('trim', explode(',', $data['specialty']));
        }
        $modelClass::create($data);

        return back()->with('success', 'Created successfully');
    }

    public function update(Request $request, $table, $id)
    {
        $modelClass = $this->modelMap[$table] ?? null;
        if (!$modelClass) return back()->withErrors('Invalid table');
        
        $item = $modelClass::findOrFail($id);
        $data = $request->except('image', 'images');

        if ($table === 'products') {
            // If new images uploaded, append to existing list
            if ($request->hasFile('images')) {
                $existingImages = is_array($item->image_url) ? $item->image_url : [];
                foreach ($request->file('images') as $file) {
                    if (count($existingImages) >= 5) break;
                    if ($file && $file->isValid()) {
                        $path = $file->store('assets', 'public');
                        $existingImages[] = Storage::url($path);
                    }
                }
                $data['image_url'] = $existingImages;
            } elseif ($request->hasFile('image')) {
                $file = $request->file('image');
                if ($file && $file->isValid()) {
                    $path = $file->store('assets', 'public');
                    $url = Storage::url($path);
                    $existingImages = is_array($item->image_url) ? $item->image_url : [];
                    if (empty($existingImages)) {
                        $existingImages[] = $url;
                    } else {
                        $existingImages[0] = $url;
                    }
                    $data['image_url'] = $existingImages;
                }
            }
            // else don't touch images
        } else {
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $path = $file->store('assets', 'public');
                $url = Storage::url($path);
                if ($table === 'testimonials') {
                    $data['avatar_url'] = $url;
                } else {
                    $data['image_url'] = $url;
                }
            }
        }

        if ($table === 'team' && isset($data['specialty']) && is_string($data['specialty'])) {
            $data['specialty'] = array_map('trim', explode(',', $data['specialty']));
        }

        $item->update($data);

        return back()->with('success', 'Updated successfully');
    }

    public function destroy($table, $id)
    {
        $modelClass = $this->modelMap[$table] ?? null;
        if (!$modelClass) return back()->withErrors('Invalid table');

        $item = $modelClass::findOrFail($id);
        $item->delete();

        return back()->with('success', 'Deleted successfully');
    }

    public function toggleProductStatus($id)
    {
        $product = Product::findOrFail($id);
        $product->update(['is_active' => !$product->is_active]);
        return back()->with('success', 'Status updated');
    }

    public function addProductImage(Request $request, $id)
    {
        $request->validate(['image' => 'required|image|max:5120']);
        $product = Product::findOrFail($id);
        $images = is_array($product->image_url) ? $product->image_url : [];

        if (count($images) >= 5) {
            return back()->withErrors('Maximum 5 images allowed.');
        }

        $path = $request->file('image')->store('assets', 'public');
        $images[] = Storage::url($path);
        $product->update(['image_url' => $images]);

        return back()->with('success', 'Image added.');
    }

    public function removeProductImage(Request $request, $id)
    {
        $url = $request->input('url') ?? $request->url;

        if (!$url) {
            return response()->json(['success' => false, 'message' => 'URL required'], 422);
        }

        $product = Product::findOrFail($id);
        $images  = is_array($product->image_url) ? $product->image_url : [];
        $images  = array_values(array_filter($images, fn($img) => $img !== $url));
        $product->update(['image_url' => $images]);

        // If AJAX request, return JSON
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Image removed.');
    }
}
