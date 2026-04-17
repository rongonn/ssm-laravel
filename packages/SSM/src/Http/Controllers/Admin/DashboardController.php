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
        $query = Order::with('product');
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('customer_name', 'like', '%' . $search . '%')
                  ->orWhere('customer_phone', 'like', '%' . $search . '%');
            });
        }

        return view('ssm::admin.orders.index', [
            'orders' => $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString(),
            'products' => Product::all(),
            'statuses' => Order::$statuses
        ]);
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);
        return back()->with('success', 'Order status updated');
    }

    // CRUD methods
    public function store(Request $request, $table)
    {
        $modelClass = $this->modelMap[$table] ?? null;
        if (!$modelClass) return back()->withErrors('Invalid table');

        $data = $request->except('image');
        
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
            // Fallback for missing image
            if ($table === 'testimonials' && !isset($data['avatar_url'])) {
                $data['avatar_url'] = null;
            } elseif (!in_array($table, ['categories', 'contacts']) && !isset($data['image_url'])) {
                $data['image_url'] = null;
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
        $data = $request->except('image');
   
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
}
