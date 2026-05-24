<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\Loggable;
class EmployeeDocument extends Model
{   
    use Loggable;
    protected $table = 'employee_documents';
  

    protected $fillable = [
        'employee_id',
        'document_type',
        'document_number',
        'document_name',
        'document_filepath1',
        'document_filepath2',
        'issue_date',
        'expiry_date',
        'verified_by',
        'verifiedon',
        'status',
        'remarks',
        'created_by',
        'updated_by',
    ];


    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         // 🔹 Auto-generate UUID if not present
    //         if (empty($model->document_id)) {
    //             $model->document_id = (string) Str::uuid();
    //         }

    //         // 🔹 Auto-assign document_number based on document_type
    //         $mapping = [
    //             'Aadhaar'              => 1,
    //             'PAN'                  => 2,
    //             'Passport'             => 3,
    //             'OfferLetter'          => 4,
    //             'AppointmentLetter'    => 5,
    //             'ExperienceLetter'     => 6,
    //             'RelievingLetter'      => 7,
    //             'Resume'               => 8,
    //             'EducationCertificate' => 9,
    //             'AddressProof'         => 10,
    //             'Other'                => 11,
    //         ];

    //         if (isset($mapping[$model->document_type])) {
    //             $model->document_number = $mapping[$model->document_type];
    //         }

    //         // 🔹 Set createdon timestamp
    //         $model->createdon = now();
    //     });

    //     static::updating(function ($model) {
    //         $model->updatedon = now();
    //     });
    // }

 
}
