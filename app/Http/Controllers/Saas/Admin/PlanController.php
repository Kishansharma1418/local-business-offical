<?php

namespace App\Http\Controllers\Saas\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::latest()->paginate(15);
        return view('saas.admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('saas.admin.plans.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['features'] = $this->normalizeFeatures($data['features'] ?? null);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        Plan::create($data);
        return redirect()->route('admin.plans.index')->with('success', 'Plan created.');
    }

    public function edit(Plan $plan)
    {
        return view('saas.admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $data = $this->validated($request, $plan->id);
        $data['features'] = $this->normalizeFeatures($data['features'] ?? null);
        $plan->update($data);
        return redirect()->route('admin.plans.index')->with('success', 'Plan updated.');
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();
        return back()->with('success', 'Plan deleted.');
    }

    private function validated(Request $request, ?int $ignore = null): array
    {
        return $request->validate([
            'name'          => 'required|string|max:255',
            'slug'          => 'nullable|string|max:255|alpha_dash|unique:plans,slug' . ($ignore ? ",$ignore" : ''),
            'price'         => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'max_products'  => 'required|integer|min:1',
            'features'      => 'nullable|string',
            'is_active'     => 'nullable|boolean',
        ]);
    }

    private function normalizeFeatures($raw): array
    {
        if (!$raw) return [];
        return array_values(array_filter(array_map('trim', preg_split('/\r?\n/', $raw))));
    }
}
