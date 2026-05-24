<?php

namespace App\Http\Controllers\Saas\Client;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Saas\Client\Concerns\EnsuresClientPaidAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    use EnsuresClientPaidAccess;

    /**
     * Curated palettes the client can one-click apply.  Each palette defines
     * the primary accent + a safe background / text combo so the storefront
     * always stays readable.
     */
    public static function palettes(): array
    {
        return [
            ['key'=>'rose',     'name'=>'Rose Gold',     'primary'=>'#e91e63', 'background'=>'#ffffff', 'text'=>'#1f1b24', 'accent'=>'#fce4ec'],
            ['key'=>'royal',    'name'=>'Royal Purple',  'primary'=>'#6c5ce7', 'background'=>'#ffffff', 'text'=>'#0b1020', 'accent'=>'#ece9ff'],
            ['key'=>'midnight', 'name'=>'Midnight Blue', 'primary'=>'#1e88e5', 'background'=>'#f8fafc', 'text'=>'#0f172a', 'accent'=>'#e0f2fe'],
            ['key'=>'emerald',  'name'=>'Emerald',       'primary'=>'#059669', 'background'=>'#ffffff', 'text'=>'#0b2e25', 'accent'=>'#d1fae5'],
            ['key'=>'sunset',   'name'=>'Sunset Orange', 'primary'=>'#ff6b35', 'background'=>'#fffaf6', 'text'=>'#2b1a10', 'accent'=>'#ffe4d6'],
            ['key'=>'mocha',    'name'=>'Mocha Wood',    'primary'=>'#8d6e63', 'background'=>'#fbf7f1', 'text'=>'#2b1d15', 'accent'=>'#efe3d4'],
            ['key'=>'charcoal', 'name'=>'Dark Luxe',     'primary'=>'#f59e0b', 'background'=>'#0b1020', 'text'=>'#f8fafc', 'accent'=>'#1f2937'],
            ['key'=>'ocean',    'name'=>'Ocean Teal',    'primary'=>'#0891b2', 'background'=>'#f0fdff', 'text'=>'#0b2a33', 'accent'=>'#cffafe'],
        ];
    }

    public function edit()
    {
        $tenant = Auth::user()->tenant;
        $themes = array_keys(config('saas.themes', [
            'boutique' => [], 'furniture' => [], 'service' => [],
            'clinic' => [], 'property' => [],
        ]));
        $palettes = self::palettes();
        return view('saas.client.settings.edit', compact('tenant', 'themes', 'palettes'));
    }

    public function update(Request $request)
    {
        $this->ensurePaidAccess();
        $tenant = Auth::user()->tenant;

        $data = $request->validate([
            'business_name'    => 'required|string|max:255',
            'phone'            => 'nullable|string|max:20',
            'email'            => 'nullable|email',
            'whatsapp'         => 'nullable|string|max:20',
            'address'          => 'nullable|string',
            'city'             => 'nullable|string|max:255',
            'tagline'          => 'nullable|string|max:500',
            'about'            => 'nullable|string',
            'theme'            => 'required|in:' . implode(',', array_keys(config('saas.themes', []))),
            'website_mode'     => 'required|in:shop,simple',
            'primary_color'    => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'background_color' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'text_color'       => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'accent_color'     => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'logo'             => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'banner'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'remove_logo'      => 'nullable|boolean',
            'remove_banner'    => 'nullable|boolean',
        ]);

        foreach (['logo', 'banner'] as $field) {
            if ($request->boolean('remove_' . $field)) {
                if ($tenant->$field) {
                    Storage::disk('public')->delete($tenant->$field);
                }
                $data[$field] = null;
            }
            if ($request->hasFile($field)) {
                if ($tenant->$field) {
                    Storage::disk('public')->delete($tenant->$field);
                }
                $data[$field] = $request->file($field)->store('tenants', 'public');
            }
        }

        // Drop the helper flags so they don't get saved
        unset($data['remove_logo'], $data['remove_banner']);

        $tenant->update($data);
        return back()->with('success', 'Business settings updated — your website now reflects the new branding.');
    }
}
