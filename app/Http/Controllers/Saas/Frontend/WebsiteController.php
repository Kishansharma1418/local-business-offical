<?php

namespace App\Http\Controllers\Saas\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Product;
use App\Support\ListingFilters;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    public function home(Request $request)
    {
        $tenant = $request->attributes->get('tenant');

        $featured = Product::withoutGlobalScopes()
            ->where('tenant_id', $tenant->id)
            ->where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->take(8)
            ->get();

        $latest = Product::withoutGlobalScopes()
            ->where('tenant_id', $tenant->id)
            ->where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        $page = Page::withoutGlobalScopes()
            ->where('tenant_id', $tenant->id)
            ->where('slug', 'home')
            ->first();

        $categories = Product::withoutGlobalScopes()
            ->where('tenant_id', $tenant->id)
            ->where('is_active', true)
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');

        return $this->theme($tenant, 'home', compact('tenant', 'featured', 'latest', 'page', 'categories'));
    }

    public function about(Request $request)
    {
        $tenant = $request->attributes->get('tenant');
        $page = Page::withoutGlobalScopes()->where('tenant_id', $tenant->id)->where('slug', 'about')->first();
        return $this->theme($tenant, 'about', compact('tenant', 'page'));
    }

    public function products(Request $request)
    {
        $tenant = $request->attributes->get('tenant');
        if (!$tenant->isShopMode()) {
            return redirect()->route('tenant.home', $tenant->slug);
        }
        $q = Product::withoutGlobalScopes()->where('tenant_id', $tenant->id)->where('is_active', true);
        if ($s = $request->get('q')) {
            $q->where('name', 'like', "%$s%");
        }
        if ($cat = $request->get('category')) {
            $q->where('category', $cat);
        }

        $theme = $tenant->theme ?: 'boutique';
        ListingFilters::apply($q, $request, $theme);

        $products = $q->latest()->paginate(12)->withQueryString();
        $categories = Product::withoutGlobalScopes()->where('tenant_id', $tenant->id)->where('is_active', true)
            ->whereNotNull('category')->distinct()->pluck('category');

        $filterDefinitions = ListingFilters::definitions($theme);
        $filterOptions = ListingFilters::optionsForTenant($tenant, $theme);

        return $this->theme($tenant, 'products', compact(
            'tenant', 'products', 'categories', 'filterDefinitions', 'filterOptions'
        ));
    }

    public function productShow(Request $request, $slug, $productSlug)
    {
        $tenant = $request->attributes->get('tenant');
        if (!$tenant->isShopMode()) {
            return redirect()->route('tenant.home', $tenant->slug);
        }
        $product = Product::withoutGlobalScopes()->where('tenant_id', $tenant->id)
            ->where('slug', $productSlug)->firstOrFail();
        return $this->theme($tenant, 'product', compact('tenant', 'product'));
    }

    public function contact(Request $request)
    {
        $tenant = $request->attributes->get('tenant');
        $page = Page::withoutGlobalScopes()->where('tenant_id', $tenant->id)->where('slug', 'contact')->first();
        return $this->theme($tenant, 'contact', compact('tenant', 'page'));
    }

    /**
     * Dynamic theme loader.
     * Looks up the tenant's selected theme (e.g. "boutique") and loads
     *   saas.themes.{theme}.{viewName}
     * Falls back to saas.themes.boutique.{viewName} if the theme-specific view is missing.
     */
    protected function theme($tenant, string $viewName, array $data)
    {
        $theme = $tenant->theme ?: 'boutique';
        $view = "saas.themes.$theme.$viewName";
        if (!view()->exists($view)) {
            $view = "saas.themes.boutique.$viewName";
        }
        return view($view, $data);
    }
}
