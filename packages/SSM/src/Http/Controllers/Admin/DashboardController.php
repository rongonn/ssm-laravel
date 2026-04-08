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
    ];

    public function index()
    {
        return view('ssm::admin.dashboard', [
            'serviceCount' => Service::count(),
            'productCount' => Product::count(),
            'teamCount'    => Team::count(),
            'contactCount' => Contact::count(),
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
            'services' => $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString()
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
            'products' => $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString()
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
            'gallery' => $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString()
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
            'contacts' => $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString()
        ]);
    }

    // CRUD methods
    public function store(Request $request, $table)
    {
        $modelClass = $this->modelMap[$table] ?? null;
        if (!$modelClass) return back()->withErrors('Invalid table');

        $data = $request->except('image');
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('assets', 'public');
            $url = Storage::url($path);
            if ($table === 'testimonials') $data['avatar_url'] = $url;
            else $data['image_url'] = $url;
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
            $path = $request->file('image')->store('assets', 'public');
            $url = Storage::url($path);
            if ($table === 'testimonials') $data['avatar_url'] = $url;
            else $data['image_url'] = $url;
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
