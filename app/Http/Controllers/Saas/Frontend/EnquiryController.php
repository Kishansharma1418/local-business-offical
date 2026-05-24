<?php

namespace App\Http\Controllers\Saas\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    public function store(Request $request, $slug)
    {
        $tenant = $request->attributes->get('tenant');

        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'required|string|max:20',
            'email'   => 'nullable|email',
            'message' => 'nullable|string|max:2000',
        ]);

        Enquiry::create(array_merge($data, [
            'tenant_id' => $tenant->id,
            'status'    => 'new',
        ]));

        return back()->with('success', 'Thanks! Your enquiry has been received. We will contact you shortly.');
    }
}
