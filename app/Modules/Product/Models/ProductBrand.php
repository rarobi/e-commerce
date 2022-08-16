<?php

namespace App\Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;

class ProductBrand extends Model
{

    protected $table = 'product_brands';
    protected $fillable = [
        'id',
        'name',
        'website',
        'photo',
        'status',
        'is_archive',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    public static function getProductBrandList()
    {
        $query = ProductBrand::where('is_archive', false);
        return $query->orderBy('id', 'desc');
    }

    public static function getProductBrandInfo($productCategoryId)
    {
        return ProductBrand::join('users', 'users.id', '=', 'product_brands.created_by')
            ->where('product_brands.id', $productCategoryId)
            ->first([
                'product_brands.*', 'users.name as author_name'
            ]);
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($data) {
            if (auth()->check()) {
                $data->created_by = auth()->user()->id;
                $data->updated_by = auth()->user()->id;
            }
        });

        static::updating(function ($data) {
            $data->updated_by = auth()->user()->id;
        });
    }

}
