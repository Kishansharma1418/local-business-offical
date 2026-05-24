<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Saas\LandingController;
use App\Http\Controllers\Saas\Auth\LoginController;
use App\Http\Controllers\Saas\Auth\RegisterController;

use App\Http\Controllers\Saas\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Saas\Admin\TenantController as AdminTenant;
use App\Http\Controllers\Saas\Admin\PlanController as AdminPlan;
use App\Http\Controllers\Saas\Admin\OrderController as AdminOrder;
use App\Http\Controllers\Saas\Admin\EnquiryController as AdminEnquiry;
use App\Http\Controllers\Saas\Admin\SubscriptionController as AdminSubscription;

use App\Http\Controllers\Saas\Client\DashboardController as ClientDashboard;
use App\Http\Controllers\Saas\Client\ProductController as ClientProduct;
use App\Http\Controllers\Saas\Client\OrderController as ClientOrder;
use App\Http\Controllers\Saas\Client\EnquiryController as ClientEnquiry;
use App\Http\Controllers\Saas\Client\PageController as ClientPage;
use App\Http\Controllers\Saas\Client\SettingsController as ClientSettings;
use App\Http\Controllers\Saas\Client\SubscriptionController as ClientSubscription;

use App\Http\Controllers\Saas\Frontend\WebsiteController;
use App\Http\Controllers\Saas\Frontend\CartController;
use App\Http\Controllers\Saas\Frontend\CheckoutController;
use App\Http\Controllers\Saas\Frontend\EnquiryController as FrontEnquiry;


/*
|--------------------------------------------------------------------------
| Public SaaS landing + auth
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('/login',  [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');

    Route::get('/register',  [RegisterController::class, 'showRegister'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');
Route::get('/logout',  [LoginController::class, 'logout'])->middleware('auth');


/*
|--------------------------------------------------------------------------
| Super Admin Panel
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'saas.admin'])->group(function () {
    Route::get('/', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [AdminDashboard::class, 'index']);

    Route::resource('tenants', AdminTenant::class)->except(['show']);
    Route::post('tenants/{tenant}/toggle', [AdminTenant::class, 'toggleStatus'])->name('tenants.toggle');
    Route::post('tenants/{tenant}/extend', [AdminTenant::class, 'extend'])->name('tenants.extend');

    Route::resource('plans', AdminPlan::class)->except(['show']);

    Route::get('orders',        [AdminOrder::class, 'index'])->name('orders.index');
    Route::get('orders/{id}',   [AdminOrder::class, 'show'])->name('orders.show');

    Route::get('enquiries', [AdminEnquiry::class, 'index'])->name('enquiries.index');

    Route::get('subscriptions',                [AdminSubscription::class, 'index'])->name('subscriptions.index');
    Route::get('subscriptions/{payment}',      [AdminSubscription::class, 'show'])->name('subscriptions.show');
    Route::post('subscriptions/{payment}/verify', [AdminSubscription::class, 'verify'])->name('subscriptions.verify');
    Route::post('subscriptions/{payment}/reject', [AdminSubscription::class, 'reject'])->name('subscriptions.reject');
});


/*
|--------------------------------------------------------------------------
| Client (Tenant) Panel
|--------------------------------------------------------------------------
*/
Route::prefix('client')->name('client.')->middleware(['auth'])->group(function () {
    // Always reachable: pay / renew subscription (even before first payment or after expiry).
    Route::middleware('saas.client')->group(function () {
        Route::get('expired', [ClientDashboard::class, 'expired'])->name('expired');
        Route::get('payment-required', [ClientDashboard::class, 'paymentRequired'])->name('payment.required');

        Route::get('subscription', [ClientSubscription::class, 'index'])->name('subscription.index');
        Route::post('subscription/pay/{plan}', [ClientSubscription::class, 'pay'])->name('subscription.pay');
        Route::get('subscription/payment/{payment}', [ClientSubscription::class, 'show'])->name('subscription.show');
        Route::post('subscription/payment/{payment}/confirm', [ClientSubscription::class, 'confirm'])->name('subscription.confirm');
    });

    // Locked until UPI payment is verified by admin and plan is active.
    Route::middleware(['saas.client', 'saas.client.paid'])->group(function () {
        Route::get('/', [ClientDashboard::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [ClientDashboard::class, 'index']);

        Route::resource('products', ClientProduct::class)->except(['show']);

        Route::get('orders', [ClientOrder::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [ClientOrder::class, 'show'])->name('orders.show');
        Route::post('orders/{order}/status', [ClientOrder::class, 'updateStatus'])->name('orders.status');

        Route::get('enquiries', [ClientEnquiry::class, 'index'])->name('enquiries.index');
        Route::post('enquiries/{enquiry}/status', [ClientEnquiry::class, 'updateStatus'])->name('enquiries.status');
        Route::delete('enquiries/{enquiry}', [ClientEnquiry::class, 'destroy'])->name('enquiries.destroy');

        Route::get('pages', [ClientPage::class, 'index'])->name('pages.index');
        Route::get('pages/{page}/edit', [ClientPage::class, 'edit'])->name('pages.edit');
        Route::put('pages/{page}', [ClientPage::class, 'update'])->name('pages.update');

        Route::get('settings', [ClientSettings::class, 'edit'])->name('settings.edit');
        Route::put('settings', [ClientSettings::class, 'update'])->name('settings.update');
    });
});


/*
|--------------------------------------------------------------------------
| Public tenant websites  /{slug}
|--------------------------------------------------------------------------
| Reserved first-segment paths are excluded via where() so they do not
| collide with /admin, /client, /login, /register, /logout.
*/
$reserved = 'admin|client|login|logout|register|landing|dashboard|up|storage';

Route::middleware('saas.tenant')->group(function () use ($reserved) {
    Route::get('/{slug}',                   [WebsiteController::class, 'home'])->name('tenant.home')->where('slug', "^(?!($reserved)$)[a-z0-9-]+");
    Route::get('/{slug}/about',             [WebsiteController::class, 'about'])->name('tenant.about');
    Route::get('/{slug}/products',          [WebsiteController::class, 'products'])->name('tenant.products');
    Route::get('/{slug}/product/{productSlug}', [WebsiteController::class, 'productShow'])->name('tenant.product.show');
    Route::get('/{slug}/contact',           [WebsiteController::class, 'contact'])->name('tenant.contact');

    Route::get('/{slug}/cart',              [CartController::class, 'view'])->name('tenant.cart');
    Route::post('/{slug}/cart/add',         [CartController::class, 'add'])->name('tenant.cart.add');
    Route::post('/{slug}/cart/update',      [CartController::class, 'update'])->name('tenant.cart.update');
    Route::post('/{slug}/cart/remove/{productId}', [CartController::class, 'remove'])->name('tenant.cart.remove');
    Route::post('/{slug}/cart/clear',       [CartController::class, 'clear'])->name('tenant.cart.clear');

    Route::get('/{slug}/checkout',          [CheckoutController::class, 'show'])->name('tenant.checkout');
    Route::post('/{slug}/checkout',         [CheckoutController::class, 'place'])->name('tenant.checkout.place');
    Route::get('/{slug}/order/{order}',     [CheckoutController::class, 'success'])->name('tenant.order.success');

    Route::post('/{slug}/enquiry',          [FrontEnquiry::class, 'store'])->name('tenant.enquiry');
});
