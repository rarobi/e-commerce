<?php

namespace App\Modules\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDetail extends Model {
    use SoftDeletes;
    protected $table = 'order_details';
    protected $fillable = [
        'id',
        'order_id',
        'product_id',
        'price',
        'quantity',
        'color',
        'size',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static function getOrderDetailList()
    {
        return $query = OrderDetail::orderBy('id', 'desc');
    }

    public static function boot() {
        parent::boot();
        static::creating(function($user) {
               $user->created_by = auth()->user()->id;
        });

        static::updating(function($user) {
               $user->updated_by = auth()->user()->id;
        });

        static::deleting(function($user) {
          $user->deleted_by = auth()->user()->id;
          $user->save();
        });
    }

}
