<?php

namespace App\Http\Controllers\Saas\Client;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Saas\Client\Concerns\EnsuresClientPaidAccess;
use App\Models\Product;
use App\Support\ListingFilters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    use EnsuresClientPaidAccess;

    public function index(Request $request)
    {
        $q = Product::latest();
        if ($s = $request->get('search')) {
            $q->where('name', 'like', "%$s%");
        }
        $products = $q->paginate(12)->withQueryString();
        return view('saas.client.products.index', compact('products'));
    }

    public function create()
    {
        $this->ensurePaidAccess();
        $this->enforcePlanLimit();
        return view('saas.client.products.create');
    }

    public function store(Request $request)
    {
        $this->ensurePaidAccess();
        $this->enforcePlanLimit();
        $data = $this->validated($request);
        $data['image'] = $this->handleImage($request);
        Product::create($data);
        return redirect()->route('client.products.index')->with('success', 'Product added.');
    }

    public function edit(Product $product)
    {
        return view('saas.client.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $this->ensurePaidAccess();
        $data = $this->validated($request);
        if ($image = $this->handleImage($request)) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $image;
        }
        $product->update($data);
        return redirect()->route('client.products.index')->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        $this->ensurePaidAccess();
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return back()->with('success', 'Product deleted.');
    }

    private function validated(Request $request): array
    {
        $theme = Auth::user()->tenant?->theme ?? 'boutique';

        $data = $request->validate([
            'name'              => 'required|string|max:255',
            'price'             => 'required|numeric|min:0',
            'mrp'               => 'nullable|numeric|min:0',
            'short_description' => 'nullable|string|max:500',
            'description'       => 'nullable|string',
            'category'          => 'nullable|string|max:100',
            'stock'             => 'nullable|integer|min:0',
            'image'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active'         => 'nullable|boolean',
            'is_featured'       => 'nullable|boolean',
            'property_type'     => 'nullable|string|max:50',
            'purpose'           => 'nullable|string|max:50',
            'bhk'               => 'nullable|string|max:20',
            'location'          => 'nullable|string|max:100',
            'area_sqft'         => 'nullable|string|max:30',
            'specialty'         => 'nullable|string|max:50',
            'consultation_type' => 'nullable|string|max:50',
            'duration'          => 'nullable|string|max:30',
        ]);

        if (ListingFilters::hasListingFilters($theme)) {
            $data['meta'] = ListingFilters::mergeMetaFromRequest($request, $theme);
        }

        return $data;
    }

    private function handleImage(Request $request): ?string
    {
        if ($request->hasFile('image')) {
            return $request->file('image')->store('products', 'public');
        }
        return null;
    }

    private function enforcePlanLimit(): void
    {
        $tenant = Auth::user()->tenant;
        $plan = $tenant?->plan;
        if (!$plan) return;
        $count = Product::count();
        if ($count >= $plan->max_products) {
            abort(403, "Your plan allows only {$plan->max_products} products. Upgrade to add more.");
        }
    }
}
