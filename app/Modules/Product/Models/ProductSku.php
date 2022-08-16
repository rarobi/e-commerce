<?php

namespace App\Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ProductSku extends Model {

    protected $table = 'product_skus';
    protected $fillable = [
        'id',
        'name',
        'status',
        'is_archive',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    public static function getProductSkuList()
    {
        $query = ProductSku::where('is_archive',false);
        return $query->orderBy('id','desc');
    }

    public static function getProductSkuInfo($productSkuId)
    {
      return ProductSku::join('users','users.id','=','product_skus.created_by')
      ->where('product_skus.id',$productSkuId)
      ->select('product_skus.*','users.name as author_name')
      ->first();
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function($data){
          if(auth()->check())
          {
            $data->created_by = auth()->user()->id;
            $data->updated_by = auth()->user()->id;
          }
        });

        static::updating(function($data) {
            $data->updated_by = auth()->user()->id;
        });
    }

}
