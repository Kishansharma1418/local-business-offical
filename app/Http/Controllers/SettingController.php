<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
   
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setting = Setting::first();
 
        return view('admin.setting.create', compact('setting'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|mimes:jpg,png,jpeg,svg',
            'company_name' => 'required',
            'company_email' => 'required|email',
            'company_phone' => 'required',
            'company_address' => 'required',
        ]);

        DB::beginTransaction();

        try {
            if ($request->id) {
                $setting = Setting::findOrFail($request->id);
                $message = "Setting updated successfully!";
                cache()->forget('app_settings');
            } else {
                $setting = new Setting();                    
                $setting->created_by = auth()->id();
                $message = "Setting created successfully!";
            }

            if ($request->hasFile('logo')) {
                cache()->forget('app_settings');
                if (!empty($setting->logo) && file_exists(public_path($setting->logo))) {
                    unlink(public_path($setting->logo));
                }

                $file = $request->file('logo');
                $logoPath = 'uploads/settings/' . time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/settings'), $logoPath);

                $setting->logo = $logoPath;
            }

            if ($request->hasFile('favicon')) {
                cache()->forget('app_settings');

                if (!empty($setting->favicon) && file_exists(public_path($setting->favicon))) {
                    unlink(public_path($setting->favicon));
                }
                $file = $request->file('favicon');
                $faviconPath = 'uploads/settings/' . time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/settings'), $faviconPath);
                $setting->favicon = $faviconPath;
            }

            $setting->company_name = $request->company_name;
            $setting->company_address = $request->company_address;
            $setting->company_phone = $request->company_phone;
            $setting->company_email = $request->company_email;
            $setting->dl_no_1 = $request->dl_no_1;
            $setting->dl_no_2 = $request->dl_no_2;
            $setting->cbn_registration_no = $request->cbn_registration_no;
            $setting->gst = $request->gst;
            $setting->policy_no = $request->policy_no;
            $setting->start_date = $request->start_date;
            $setting->end_date = $request->end_date;
            $setting->updated_by = auth()->id();

            $setting->save();

            DB::commit();

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

}