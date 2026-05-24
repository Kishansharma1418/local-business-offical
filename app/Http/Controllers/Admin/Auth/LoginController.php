<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Exception;
use Validator;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;


class LoginController extends Controller
{

    public function index(Request $request)
    {

    
    // $user = User::find(1);
    // $user->password = Hash::make('Admin@123'); 
    // $user->save();
    // return "sd";
        if(Auth::check()){
            return redirect()->route('dashboard');
        }else{
            return view('auth.login');
        }
    }

    // public function signIn(Request $request)
    // {
    //     if($request->has('_token')){
    //         try{

    //             $request->request->add([
    //                 "remember" => strip_tags($request->remember)
    //             ]);

    //             $user  =  User::where('email',$request->email)->first();

    //             if($user){
    //                 $loginData['email']       =  strip_tags($request->email);
    //                 $loginData['password']    =  strip_tags($request->password);

    //                 if (!auth()->attempt($loginData)) {
    //                     return response()->json(['status'=>false,'message'=>__('Please enter valid email address and password to login')]);
    //                 }else{
    //                     return response()->json(['status'=>true,'message'=>__('Login'),'route'=>route('dashboard')]);
    //                 }
    //             }else{
    //                 return response()->json(['status'=>false,'message'=>__('Please enter valid email address and password to login')]);
    //             }
    //         }catch(Exception $e){
    //             DB::rollback();
    //             return redirect()->back()->with('error',$e->getMessage());
    //         }
    //     }
    // }


    public function signIn(Request $request)
    {
        if (! $request->has('_token')) {
            return response()->json(['status' => false, 'message' => __('Invalid request')]);
        }

        try {

            $emailOrPhone = strip_tags($request->email_or_phone);
            $password = strip_tags($request->password);
            $remember = $request->remember == 'true';

            $isEmail = filter_var($emailOrPhone, FILTER_VALIDATE_EMAIL);

            $user = User::where(function ($q) use ($emailOrPhone, $isEmail) {
                        if ($isEmail) {
                            $q->where('email', $emailOrPhone);
                        } else {
                            $q->where('phone', $emailOrPhone);
                        }
                    })
                    ->first();

            if (! $user) {
                return response()->json(['status' => false, 'message' => __('Invalid login credentials')]);
            }

            if ($user->status === '4' || $user->is_locked == 1) {
                return response()->json(['status' => false, 'message' => __('Your account is locked. Contact admin.')]);
            }

            if ($user->status === '0') {
                return response()->json(['status' => false, 'message' => __('Your account is pending. Contact admin.')]);
            }

            if ($user->status === '3') {
                return response()->json(['status' => false, 'message' => __('Your account is blocked. Contact admin.')]);
            }

            if ($user->status === '2') {
                return response()->json(['status' => false, 'message' => __('Your account is deactivated. Contact admin.')]);
            }

            $loginData = [
                $isEmail ? 'email' : 'phone' => $emailOrPhone,
                'password' => $password
            ];

            if (! Auth::attempt($loginData, $remember)) {

                $user->failed_login_attempts = $user->failed_login_attempts + 1;
                $user->last_ip = $request->ip();
                $user->save();

                if ($user->failed_login_attempts >= 5) {
                    $user->status = '4';
                    $user->save();
                    return response()->json([
                        'status' => false,
                        'message' => __('Account locked due to too many failed attempts. Contact admin.')
                    ]);
                }

                return response()->json([
                    'status' => false,
                    'message' => __('Invalid login credentials'),
                    'failed_attempts' => $user->failed_login_attempts
                ]);
            }

            $user->last_login = now();
            $user->last_ip = $request->ip();
            $user->failed_login_attempts = 0;
            $user->save();

            Cache::forget('user_permissions_' . $user->id);

            $request->session()->regenerate();

            return response()->json([
                'status' => true,
                'message' => __('Login successful'),
                'route' => route('dashboard')
            ]);

        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }


    // public function signIn(Request $request)
    // {
    //     if (! $request->has('_token')) {
    //         return response()->json(['status' => false, 'message' => __('Invalid request')]);
    //     }

    //     try {
    //         $request->merge([
    //             'remember' => strip_tags($request->remember)
    //         ]);

    //         $email_or_phone = strip_tags($request->email_or_phone);
    //         $password = strip_tags($request->password);
    //         $remember = boolval($request->remember);

    //         // $user = User::where('email', $email)->first();
            
    //          $user = User::where(function($query) use ($request) {
    //                             $query->where('email', $request->email_or_phone)
    //                                 ->orWhere('phone', substr($request->email_or_phone, 0, 11)); 
    //                         })
    //                         ->where('status', 1)
    //                         ->first();
    //         if (! $user) {
    //             return response()->json(['status' => false, 'message' => __('Please enter valid email address and password to login')]);
    //         }

           
    //         if ($user->status === '4' || $user->is_locked === '1') {
    //             return response()->json(['status' => false, 'message' => __('Your account is locked. Please contact admin.')]);
    //         }

    //           if ($user->status === '0') {
    //             return response()->json(['status' => false, 'message' => __('Your account is in pending. Please contact admin.')]);
    //         }

    //         if ($user->status === '3') {
    //             return response()->json(['status' => false, 'message' => __('Your account is Blocked. Please contact admin.')]);
    //         }
            
    //           if ($user->status === '2') {
    //             return response()->json(['status' => false, 'message' => __('Your account is Deactive. Please contact admin.')]);
    //         }

    //         $loginData = [
    //             'email' => $email,
    //             'password' => $password,
    //         ];

    //         if (! Auth::attempt($loginData, $remember)) {
             
    //             $user->failed_login_attempts = ((int) $user->failed_login_attempts) + 1;
    //             $user->last_ip = $request->ip();
    //             $user->save();

    //             $threshold = 5;
    //             if ((int) $user->failed_login_attempts >= $threshold) {
                    
    //                 $user->status = '4'; 
    //                 $user->save();
    //                 return response()->json([
    //                     'status' => false,
    //                     'message' => __('Your account has been locked due to multiple failed login attempts. Contact admin.')
    //                 ]);
    //             }

    //             return response()->json([
    //                 'status' => false,
    //                 'message' => __('Please enter valid email address and password to login'),
    //                 'failed_attempts' => $user->failed_login_attempts
    //             ]);
    //         }

    //         $user->last_login = Carbon::now();
    //         $user->last_ip = $request->ip();
    //         $user->failed_login_attempts = 0;

    //         $user->save();
    //          Cache::forget('user_permissions_' . $user->id); 

    //         $request->session()->regenerate();

    //         return response()->json([
    //             'status' => true,
    //             'message' => __('Login'),
    //             'route' => route('dashboard')
    //         ]);
    //     } catch (Exception $e) {
    //         return response()->json(['status' => false, 'message' => $e->getMessage()]);
    //     }
    // }


    public function logout()
    {
        auth()->logout();
        return redirect('/');
    }

}
