<?php

namespace App\Http\Controllers\Saas\Client;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Saas\Client\Concerns\EnsuresClientPaidAccess;
use App\Models\Enquiry;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    use EnsuresClientPaidAccess;

    public function index(Request $request)
    {
        $q = Enquiry::latest();
        if ($s = $request->get('search')) {
            $q->where(function ($qq) use ($s) {
                $qq->where('name', 'like', "%$s%")->orWhere('phone', 'like', "%$s%");
            });
        }
        $enquiries = $q->paginate(20)->withQueryString();
        return view('saas.client.enquiries.index', compact('enquiries'));
    }

    public function updateStatus(Request $request, Enquiry $enquiry)
    {
        $this->ensurePaidAccess();
        $enquiry->update($request->validate([
            'status' => 'required|in:new,contacted,closed',
        ]));
        return back()->with('success', 'Status updated.');
    }

    public function destroy(Enquiry $enquiry)
    {
        $this->ensurePaidAccess();
        $enquiry->delete();
        return back()->with('success', 'Enquiry deleted.');
    }
}
