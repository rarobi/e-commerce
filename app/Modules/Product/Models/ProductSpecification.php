<?php

namespace App\Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ProductSpecification extends Model {

    protected $table = 'product_specifications';
    protected $fillable = [
        'id',
        'product_id',
        'specification',
        'value',
        'status',
        'is_archive',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    public static function boot() {
        parent::boot();
        static::creating(function($productSpecification) {
            $productSpecification->created_by = Auth::user()->id;
            $productSpecification->updated_by = Auth::user()->id;
        });

        static::updating(function($productSpecification) {
            $productSpecification->updated_by = Auth::user()->id;
        });
    }

}
