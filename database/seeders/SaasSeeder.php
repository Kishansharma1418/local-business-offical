<?php

namespace Database\Seeders;

use App\Models\Enquiry;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Page;
use App\Models\Plan;
use App\Models\Product;
use App\Models\SubscriptionPayment;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SaasSeeder extends Seeder
{
    public function run(): void
    {
        // -------------------- Plans --------------------
        $starter = Plan::updateOrCreate(['slug' => 'starter'], [
            'name' => 'Starter',
            'price' => 499,
            'duration_days' => 30,
            'max_products' => 20,
            'features' => ['Free website', '20 products', 'WhatsApp button', 'COD checkout'],
            'is_active' => true,
        ]);

        $growth = Plan::updateOrCreate(['slug' => 'growth'], [
            'name' => 'Growth',
            'price' => 999,
            'duration_days' => 30,
            'max_products' => 100,
            'features' => ['Everything in Starter', '100 products', 'Online payment', 'Order analytics', 'Priority support'],
            'is_active' => true,
        ]);

        $pro = Plan::updateOrCreate(['slug' => 'pro'], [
            'name' => 'Pro',
            'price' => 1999,
            'duration_days' => 30,
            'max_products' => 500,
            'features' => ['Everything in Growth', '500 products', 'Custom domain (soon)', 'Advanced reports', 'Dedicated manager'],
            'is_active' => true,
        ]);

        // -------------------- Super Admin --------------------
        User::updateOrCreate(
            ['email' => 'admin@localbiz.test'],
            [
                'name' => 'Super Admin',
                'full_name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'tenant_id' => null,
                'status' => '1',
                'user_type' => 'admin',
            ]
        );

        // -------------------- Tenant 1: Boutique --------------------
        $boutique = Tenant::updateOrCreate(
            ['slug' => 'saanvi-boutique'],
            [
                'business_name' => 'Saanvi Boutique',
                'phone'         => '+919876543210',
                'email'         => 'hello@saanviboutique.test',
                'whatsapp'      => '919876543210',
                'address'       => 'C-42, MI Road',
                'city'          => 'Jaipur',
                'tagline'       => 'Handpicked ethnic wear & designer sarees',
                'about'         => 'Saanvi Boutique is a Jaipur-based label specialising in handcrafted lehengas, sarees, and anarkalis. Every piece blends traditional Rajasthani craftsmanship with modern aesthetics.',
                'theme'         => 'boutique',
                'primary_color' => '#e91e63',
                'plan_id'       => $growth->id,
                'status'        => 'active',
                'expiry_date'   => now()->addDays(30),
            ]
        );

        User::updateOrCreate(
            ['email' => 'owner@saanviboutique.test'],
            [
                'name' => 'Priya Sharma',
                'full_name' => 'Priya Sharma',
                'phone' => '+919876543210',
                'password' => Hash::make('password'),
                'role' => 'client',
                'tenant_id' => $boutique->id,
                'status' => '1',
                'user_type' => 'customer',
            ]
        );

        $boutiqueProducts = [
            ['Bandhani Lehenga Choli', 'Lehenga', 4999, 6999, 'Hand-tied Bandhani work on soft georgette with mirror detailing.', true],
            ['Banarasi Silk Saree',    'Saree',   3499, 4999, 'Pure Banarasi silk with zari weaving. Gift-ready packaging.', true],
            ['Block Print Anarkali',   'Anarkali', 1799, 2499, 'Breathable cotton Anarkali with Sanganeri block prints.', true],
            ['Designer Kurti Set',     'Kurti',    1299, 1899, '3-piece kurti-pant-dupatta set in pastel chanderi.', false],
            ['Zari Work Dupatta',      'Accessories', 899, 1299, 'Lightweight art-silk dupatta with zari border.', false],
            ['Embroidered Blouse',     'Blouse',   699, 999,  'Ready-to-stitch embroidered blouse piece.', false],
        ];

        foreach ($boutiqueProducts as $i => [$name, $cat, $price, $mrp, $desc, $featured]) {
            Product::updateOrCreate(
                ['tenant_id' => $boutique->id, 'name' => $name],
                [
                    'slug' => \Illuminate\Support\Str::slug($name) . '-' . ($i + 1),
                    'price' => $price, 'mrp' => $mrp, 'category' => $cat,
                    'short_description' => $desc, 'description' => $desc,
                    'stock' => rand(3, 25), 'is_active' => true, 'is_featured' => $featured,
                ]
            );
        }

        Page::updateOrCreate(['tenant_id'=>$boutique->id,'slug'=>'home'], ['title'=>'Welcome to Saanvi', 'content'=>'<h2 class="text-center">Crafted in Jaipur, Loved Everywhere</h2><p class="text-center text-muted">Every piece tells a story of tradition and elegance.</p>']);
        Page::updateOrCreate(['tenant_id'=>$boutique->id,'slug'=>'about'], ['title'=>'About Saanvi', 'content'=>'<h3>Our Story</h3><p>Founded in 2015 in the heart of Jaipur, Saanvi Boutique brings together master weavers, block-printers, and embroiderers to create apparel that honors Rajasthani heritage.</p><h4>Our Values</h4><ul><li>Authentic handcraft</li><li>Fair wages to artisans</li><li>Women-led enterprise</li></ul>']);
        Page::updateOrCreate(['tenant_id'=>$boutique->id,'slug'=>'contact'], ['title'=>'Contact Us', 'content'=>'<p>Visit our flagship at C-42, MI Road, Jaipur or message us on WhatsApp.</p>']);
        $this->seedVerifiedPayment($boutique, $growth);

        // -------------------- Tenant 2: Furniture --------------------
        $furniture = Tenant::updateOrCreate(
            ['slug' => 'royal-wood-crafts'],
            [
                'business_name' => 'Royal Wood Crafts',
                'phone'         => '+919812345678',
                'email'         => 'sales@royalwood.test',
                'whatsapp'      => '919812345678',
                'address'       => 'Sanganer Industrial Area',
                'city'          => 'Jaipur',
                'tagline'       => 'Solid sheesham furniture for your dream home',
                'about'         => 'Royal Wood Crafts has been crafting solid-wood furniture in Jaipur for over 20 years. Direct-from-factory pricing, 5-year warranty.',
                'theme'         => 'furniture',
                'primary_color' => '#8d6e63',
                'plan_id'       => $pro->id,
                'status'        => 'active',
                'expiry_date'   => now()->addDays(60),
            ]
        );

        User::updateOrCreate(
            ['email' => 'owner@royalwood.test'],
            [
                'name' => 'Rakesh Jangid',
                'full_name' => 'Rakesh Jangid',
                'phone' => '+919812345678',
                'password' => Hash::make('password'),
                'role' => 'client',
                'tenant_id' => $furniture->id,
                'status' => '1',
                'user_type' => 'customer',
            ]
        );

        $furnitureProducts = [
            ['Sheesham 6-Seater Dining Table', 'Dining', 38999, 49999, 'Solid sheesham wood 6-seater dining set with chairs.', true],
            ['King Size Bed with Storage',     'Bedroom', 42999, 54999, 'Premium sheesham king bed with hydraulic storage.', true],
            ['3-Door Wardrobe',                'Bedroom', 29999, 39999, '3-door mirrored wardrobe with inbuilt drawers.', true],
            ['Wooden Sofa Set (3+1+1)',        'Living',  54999, 69999, 'Traditional 3+1+1 sofa with cushioned seating.', false],
            ['Coffee Table',                   'Living',   7999, 10999, 'Rustic sheesham coffee table with magazine rack.', false],
            ['Study Desk with Drawers',        'Office',   9499, 12999, 'Compact study desk with 3 drawers and cable slot.', false],
        ];

        foreach ($furnitureProducts as $i => [$name, $cat, $price, $mrp, $desc, $featured]) {
            Product::updateOrCreate(
                ['tenant_id' => $furniture->id, 'name' => $name],
                [
                    'slug' => \Illuminate\Support\Str::slug($name) . '-' . ($i + 1),
                    'price' => $price, 'mrp' => $mrp, 'category' => $cat,
                    'short_description' => $desc, 'description' => $desc,
                    'stock' => rand(2, 10), 'is_active' => true, 'is_featured' => $featured,
                ]
            );
        }

        Page::updateOrCreate(['tenant_id'=>$furniture->id,'slug'=>'home'], ['title'=>'Welcome to Royal Wood', 'content'=>'<h2 class="text-center">Built by Hand. Made to Last.</h2><p class="text-center">Direct-from-factory pricing. 5-year warranty.</p>']);
        Page::updateOrCreate(['tenant_id'=>$furniture->id,'slug'=>'about'], ['title'=>'About Royal Wood', 'content'=>'<h3>Two Decades of Craft</h3><p>We use only premium-grade sheesham, teak, and mango wood — seasoned for 6 months before every build.</p>']);
        Page::updateOrCreate(['tenant_id'=>$furniture->id,'slug'=>'contact'], ['title'=>'Visit Our Workshop', 'content'=>'<p>Sanganer Industrial Area, Jaipur. Open Mon–Sat 10am–7pm.</p>']);
        $this->seedVerifiedPayment($furniture, $pro);

        // -------------------- Tenant 3: Simple info website (service) --------------------
        $service = Tenant::updateOrCreate(
            ['slug' => 'jaipur-home-services'],
            [
                'business_name' => 'Jaipur Home Services',
                'phone'         => '+919900112233',
                'email'         => 'info@jaipurhomeservices.test',
                'whatsapp'      => '919900112233',
                'address'       => 'Malviya Nagar, Jaipur',
                'city'          => 'Jaipur',
                'tagline'       => 'Plumbing, electrical & AC repairs — at your door in 2 hours',
                'about'         => 'We are a trusted team of certified technicians providing reliable home repair services across Jaipur. No product catalogue — just call or submit the enquiry form and we will reach you.',
                'theme'            => 'service',
                'website_mode'     => 'simple',
                'primary_color'    => '#1e88e5',
                'background_color' => '#f8fafc',
                'text_color'       => '#0f172a',
                'accent_color'     => '#e0f2fe',
                'plan_id'       => $starter->id,
                'status'        => 'active',
                'expiry_date'   => now()->addDays(20),
            ]
        );

        User::updateOrCreate(
            ['email' => 'owner@jaipurhomeservices.test'],
            [
                'name' => 'Mahesh Kumawat',
                'full_name' => 'Mahesh Kumawat',
                'phone' => '+919900112233',
                'password' => Hash::make('password'),
                'role' => 'client',
                'tenant_id' => $service->id,
                'status' => '1',
                'user_type' => 'customer',
            ]
        );

        Page::updateOrCreate(['tenant_id'=>$service->id,'slug'=>'home'],    ['title'=>'Welcome',   'content'=>'<h2>Trusted home services, at your doorstep</h2><p>Same-day bookings. Trained technicians. Fair pricing.</p>']);
        Page::updateOrCreate(['tenant_id'=>$service->id,'slug'=>'about'],   ['title'=>'About Us',  'content'=>'<p>5+ years serving Jaipur households. 10,000+ jobs completed.</p>']);
        Page::updateOrCreate(['tenant_id'=>$service->id,'slug'=>'contact'], ['title'=>'Book Now',  'content'=>'<p>Call or WhatsApp anytime 9 AM – 9 PM.</p>']);

        Enquiry::create([
            'tenant_id' => $service->id,
            'name' => 'Anita Mehta', 'phone' => '+919812340001',
            'email' => 'anita@example.com', 'message' => 'AC not cooling, please schedule a visit.',
            'status' => 'new',
        ]);
        $this->seedVerifiedPayment($service, $starter);

        // -------------------- Tenant 4: Property --------------------
        $property = Tenant::updateOrCreate(
            ['slug' => 'jaipur-properties'],
            [
                'business_name' => 'Jaipur Properties Hub',
                'phone'         => '+919888877766',
                'email'         => 'info@jaipurproperties.test',
                'whatsapp'      => '919888877766',
                'address'       => 'C-Scheme, Jaipur',
                'city'          => 'Jaipur',
                'tagline'       => 'Flats, villas & plots — sale & rent across Jaipur',
                'theme'         => 'property',
                'website_mode'  => 'shop',
                'primary_color' => '#059669',
                'plan_id'       => $growth->id,
                'status'        => 'active',
                'expiry_date'   => now()->addDays(45),
            ]
        );
        User::updateOrCreate(
            ['email' => 'owner@jaipurproperties.test'],
            ['name' => 'Vikram Singh', 'full_name' => 'Vikram Singh', 'phone' => '+919888877766',
                'password' => Hash::make('password'), 'role' => 'client', 'tenant_id' => $property->id, 'status' => '1', 'user_type' => 'customer']
        );
        $propertyListings = [
            ['3 BHK Flat in Malviya Nagar', 4500000, 5200000, 'Sale', 'Flat', '3', 'Malviya Nagar', '1450', true],
            ['2 BHK Apartment Vaishali Nagar', 2800000, 3200000, 'Sale', 'Flat', '2', 'Vaishali Nagar', '1100', true],
            ['Villa in Jagatpura', 12500000, null, 'Sale', 'Villa', '4', 'Jagatpura', '3200', false],
            ['1 BHK for Rent C-Scheme', 18000, null, 'Rent', 'Flat', '1', 'C-Scheme', '650', false],
            ['Commercial Shop MI Road', 8500000, null, 'Sale', 'Commercial', 'Studio', 'MI Road', '800', false],
        ];
        foreach ($propertyListings as $i => [$name, $price, $mrp, $purpose, $type, $bhk, $loc, $sqft, $feat]) {
            Product::updateOrCreate(
                ['tenant_id' => $property->id, 'name' => $name],
                [
                    'slug' => \Illuminate\Support\Str::slug($name) . '-p' . ($i + 1),
                    'price' => $price, 'mrp' => $mrp,
                    'category' => 'Residential',
                    'short_description' => "$bhk BHK $type in $loc — $purpose",
                    'meta' => [
                        'property_type' => $type, 'purpose' => $purpose, 'bhk' => $bhk,
                        'location' => $loc, 'area_sqft' => $sqft,
                    ],
                    'stock' => 1, 'is_active' => true, 'is_featured' => $feat,
                ]
            );
        }
        $this->seedVerifiedPayment($property, $growth);

        // -------------------- Tenant 5: Clinic --------------------
        $clinic = Tenant::updateOrCreate(
            ['slug' => 'city-care-clinic'],
            [
                'business_name' => 'City Care Clinic',
                'phone'         => '+919777766655',
                'email'         => 'appointments@citycare.test',
                'whatsapp'      => '919777766655',
                'address'       => 'Tonk Road, Jaipur',
                'city'          => 'Jaipur',
                'tagline'       => 'Multi-specialty clinic — OPD & online consultations',
                'theme'         => 'clinic',
                'website_mode'  => 'shop',
                'primary_color' => '#0284c7',
                'plan_id'       => $growth->id,
                'status'        => 'active',
                'expiry_date'   => now()->addDays(45),
            ]
        );
        User::updateOrCreate(
            ['email' => 'owner@citycare.test'],
            ['name' => 'Dr. Neha Mehta', 'full_name' => 'Dr. Neha Mehta', 'phone' => '+919777766655',
                'password' => Hash::make('password'), 'role' => 'client', 'tenant_id' => $clinic->id, 'status' => '1', 'user_type' => 'customer']
        );
        $clinicServices = [
            ['General Physician Consultation', 500, 'General', 'OPD', '30 min', true],
            ['Dental Check-up & Cleaning', 800, 'Dental', 'OPD', '45 min', true],
            ['Skin Specialist Consultation', 1200, 'Skin', 'OPD', '30 min', false],
            ['Pediatric Consultation', 600, 'Pediatrics', 'OPD', '30 min', false],
            ['Online Video Consultation', 400, 'General', 'Online', '15 min', true],
            ['Home Visit — General', 1500, 'General', 'Home Visit', '60 min', false],
        ];
        foreach ($clinicServices as $i => [$name, $price, $spec, $ctype, $dur, $feat]) {
            Product::updateOrCreate(
                ['tenant_id' => $clinic->id, 'name' => $name],
                [
                    'slug' => \Illuminate\Support\Str::slug($name) . '-c' . ($i + 1),
                    'price' => $price, 'category' => 'Consultation',
                    'short_description' => "$spec — $ctype ($dur)",
                    'meta' => [
                        'specialty' => $spec, 'consultation_type' => $ctype, 'duration' => $dur,
                    ],
                    'stock' => 99, 'is_active' => true, 'is_featured' => $feat,
                ]
            );
        }
        $this->seedVerifiedPayment($clinic, $growth);

        // -------------------- Sample orders & enquiries --------------------
        foreach ([$boutique, $furniture, $property, $clinic] as $t) {
            $prods = Product::withoutGlobalScopes()->where('tenant_id', $t->id)->take(3)->get();
            for ($k = 0; $k < 3; $k++) {
                $order = Order::create([
                    'tenant_id' => $t->id,
                    'customer_name' => ['Aarti Gupta', 'Mohit Verma', 'Neha Singh'][$k],
                    'phone' => '+9199000' . rand(10000, 99999),
                    'email' => null,
                    'address' => 'Sample address, Jaipur',
                    'total_amount' => 0,
                    'payment_method' => $k === 0 ? 'cod' : 'online',
                    'payment_status' => $k === 2 ? 'paid' : 'pending',
                    'order_status'  => ['new','confirmed','delivered'][$k],
                ]);
                $total = 0;
                foreach ($prods->take(rand(1, 3)) as $p) {
                    $q = rand(1, 2);
                    OrderItem::create([
                        'order_id' => $order->id, 'product_id' => $p->id,
                        'product_name' => $p->name, 'quantity' => $q,
                        'price' => $p->price, 'subtotal' => $p->price * $q,
                    ]);
                    $total += $p->price * $q;
                }
                $order->update(['total_amount' => $total]);
            }

            foreach (['Sunita Yadav', 'Ravi Khandelwal'] as $n) {
                Enquiry::create([
                    'tenant_id' => $t->id,
                    'name' => $n,
                    'phone' => '+9198' . rand(1000000, 9999999),
                    'email' => strtolower(str_replace(' ', '.', $n)) . '@example.com',
                    'message' => 'Hi, interested in your products. Please share details.',
                    'status' => 'new',
                ]);
            }
        }
    }

    private function seedVerifiedPayment(Tenant $tenant, Plan $plan): void
    {
        SubscriptionPayment::updateOrCreate(
            [
                'tenant_id' => $tenant->id,
                'status'    => 'verified',
            ],
            [
                'plan_id'         => $plan->id,
                'amount'          => $plan->price,
                'upi_id'          => config('saas.upi.id', '9660741418@ptyes'),
                'status'          => 'verified',
                'new_expiry_date' => $tenant->expiry_date,
                'verified_at'     => now(),
                'admin_note'      => 'Demo seed — subscription active',
            ]
        );
    }
}
