<?php

namespace App\Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $table = 'products';
    protected $fillable = [
        'id',
        'name',
        'category_id',
        'sub_category_id',
        'brand_id',
        'sku_id',
        'supplier_id',
        'title',
        'slug',
        'short_description',
        'long_description',
        'price',
        'discount',
        'size',
        'color',
        'weight',
        'tax',
        'total_view',
        'is_feature_product',

        'status',
        'is_archive',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    public static function getProductList()
    {
        $query = Product::leftJoin('product_categories', 'product_categories.id', 'products.category_id')
            ->leftJoin('product_subcategories', 'product_subcategories.id', 'products.sub_category_id')
            ->leftJoin('product_brands', 'product_brands.id', 'products.brand_id')
            ->leftJoin('product_skus', 'product_skus.id', 'products.sku_id')
            ->where('products.is_archive', false);
        return $query->orderBy('products.id', 'desc');
    }

    public static function getProductInfo($productId)
    {
        return Product::leftJoin('users', 'users.id', 'products.created_by')
            ->leftJoin('product_categories', 'product_categories.id', 'products.category_id')
            ->leftJoin('product_subcategories', 'product_subcategories.id', 'products.sub_category_id')
            ->leftJoin('product_brands', 'product_brands.id', 'products.brand_id')
            ->leftJoin('product_skus', 'product_skus.id', 'products.sku_id')
            ->where('products.is_archive', false)
            ->where('products.id', $productId)
            ->first([
                'products.*',
                'users.name as user_name',
                'product_categories.name as category_name',
                'product_subcategories.name as subcategory_name',
                'product_brands.name as brand_name',
                'product_skus.name as sku_name'
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
