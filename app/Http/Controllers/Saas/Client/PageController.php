<?php

namespace App\Http\Controllers\Saas\Client;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Saas\Client\Concerns\EnsuresClientPaidAccess;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    use EnsuresClientPaidAccess;

    protected array $defaultPages = ['home', 'about', 'contact'];

    public function index()
    {
        // Ensure default pages exist for this tenant
        foreach ($this->defaultPages as $slug) {
            Page::firstOrCreate(
                ['tenant_id' => Auth::user()->tenant_id, 'slug' => $slug],
                ['title' => ucfirst($slug) . ' Page', 'content' => '']
            );
        }
        $pages = Page::orderBy('slug')->get();
        return view('saas.client.pages.index', compact('pages'));
    }

    public function edit(Page $page)
    {
        return view('saas.client.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $this->ensurePaidAccess();
        $page->update($request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'nullable|string',
        ]));
        return redirect()->route('client.pages.index')->with('success', 'Page updated.');
    }
}
