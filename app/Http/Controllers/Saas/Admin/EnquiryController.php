<?php

namespace App\Http\Controllers\Saas\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    public function index(Request $request)
    {
        $q = Enquiry::withoutGlobalScopes()->with('tenant')->latest();

        if ($s = $request->get('search')) {
            $q->where(function ($qq) use ($s) {
                $qq->where('name', 'like', "%$s%")
                   ->orWhere('phone', 'like', "%$s%")
                   ->orWhere('email', 'like', "%$s%");
            });
        }

        $enquiries = $q->paginate(20)->withQueryString();
        return view('saas.admin.enquiries.index', compact('enquiries'));
    }
}
