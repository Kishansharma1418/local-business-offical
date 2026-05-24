<?php

/*
|--------------------------------------------------------------------------
| LocalBiz SaaS configuration
|--------------------------------------------------------------------------
|
| These values are read by both the client subscription page and the
| admin payment verification page.  Change the UPI id / display name in
| your .env without touching code:
|
|   SAAS_UPI_ID=9660741418@ptyes
|   SAAS_UPI_NAME="LocalBiz SaaS"
|
*/

return [
    'upi' => [
        'id'   => env('SAAS_UPI_ID', '9660741418@ptyes'),
        'name' => env('SAAS_UPI_NAME', 'LocalBiz SaaS'),
    ],

    'brand' => [
        'name'    => env('SAAS_BRAND_NAME', 'LocalBiz'),
        'support' => env('SAAS_SUPPORT_PHONE', '+91 96607 41418'),
    ],

    'themes' => [
        'boutique'  => ['label' => 'Boutique / Fashion', 'listings_label' => 'Shop'],
        'furniture' => ['label' => 'Furniture / Home', 'listings_label' => 'Products'],
        'service'   => ['label' => 'Services / Repairs', 'listings_label' => 'Services'],
        'clinic'    => ['label' => 'Clinic / Hospital', 'listings_label' => 'Services'],
        'property'  => ['label' => 'Property / Real Estate', 'listings_label' => 'Properties'],
    ],

    'listing_filters' => [
        'property' => [
            'property_type' => [
                'label'   => 'Property type',
                'options' => [
                    'Flat'       => 'Flat / Apartment',
                    'Villa'      => 'Villa / Bungalow',
                    'Plot'       => 'Plot / Land',
                    'Commercial' => 'Commercial',
                    'Farmhouse'  => 'Farmhouse',
                ],
            ],
            'purpose' => [
                'label'   => 'For',
                'options' => ['Sale' => 'For Sale', 'Rent' => 'For Rent'],
            ],
            'bhk' => [
                'label'   => 'BHK',
                'options' => [
                    '1' => '1 BHK', '2' => '2 BHK', '3' => '3 BHK',
                    '4' => '4 BHK', '5+' => '5+ BHK', 'Studio' => 'Studio',
                ],
            ],
            'location' => [
                'label'   => 'Area',
                'options' => [], // filled from DB + free text in admin
            ],
        ],
        'clinic' => [
            'specialty' => [
                'label'   => 'Specialty',
                'options' => [
                    'General'     => 'General Physician',
                    'Dental'      => 'Dental',
                    'Skin'        => 'Skin / Dermatology',
                    'Pediatrics'  => 'Pediatrics',
                    'Gynecology'  => 'Gynecology',
                    'Orthopedic'  => 'Orthopedic',
                    'Eye'         => 'Eye / ENT',
                ],
            ],
            'consultation_type' => [
                'label'   => 'Consultation',
                'options' => [
                    'OPD'         => 'OPD (In-clinic)',
                    'Online'      => 'Online / Video',
                    'Home Visit'  => 'Home Visit',
                    'Emergency'   => 'Emergency',
                ],
            ],
            'duration' => [
                'label'   => 'Duration',
                'options' => [
                    '15 min' => '15 minutes',
                    '30 min' => '30 minutes',
                    '45 min' => '45 minutes',
                    '60 min' => '1 hour',
                ],
            ],
        ],
    ],
];
