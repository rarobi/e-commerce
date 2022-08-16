<?php

namespace App\Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ProductSubcategory extends Model
{

    protected $table = 'product_subcategories';
    protected $fillable = [
        'id',
        'category_id',
        'name',
        'slug',
        'image',
        'description',
        'status',
        'is_archive',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    public static function getProductSubcategoryList()
    {
        $query = ProductSubcategory::leftJoin('product_categories', 'product_categories.id', '=', 'product_subcategories.category_id')
            ->where('product_subcategories.is_archive', false);
        return $query->orderBy('id', 'desc');
    }

    public static function getProductSubcategoryInfo($productSubcategoryId)
    {
        return ProductSubcategory::join('users', 'users.id', '=', 'product_subcategories.created_by')
            ->leftJoin('product_categories', 'product_categories.id', '=', 'product_subcategories.category_id')
            ->where('product_subcategories.id', $productSubcategoryId)
            ->select('product_subcategories.*', 'product_categories.name as category_name')
            ->first();
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
