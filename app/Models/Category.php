<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory,Loggable,SoftDeletes;

    protected $table = 'categories';

    protected $fillable = [
        
        'category_name',
        'parent_category_id',
        'code',
        'description',
        'image',
        'status',
        'created_by',     
        'updated_by',
       
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_category_id');
    }

    public function subcategories()
    {
        return $this->hasMany(Category::class, 'parent_category_id');
    }

}
