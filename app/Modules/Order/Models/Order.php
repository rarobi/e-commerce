<?php

namespace App\Modules\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model {
    use SoftDeletes;
    protected $table = 'orders';
    protected $fillable = [
        'id',
        'order_number',
        'customer_id',
        'shipping_id',
        'shipped_by',
        'payment_type_id',
        'shipping_amount',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'paid_amount',
        'payment_status',
        'payment_details',
        'order_status',
        'payment_date',
        'shipped_date',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static function getOrderList()
    {
        return $query = Order::orderBy('id', 'desc');
    }

    // load image

    public function getImagePathAttribute()
    {
        return  Storage :: url('order/'.$this->image);
    }



    public static function boot() {
        parent::boot();
        // static::creating(function($user) {
        //        $user->created_by = auth()->user()->id;
        // });

        // static::updating(function($user) {
        //        $user->updated_by = auth()->user()->id;
        // });

        // static::deleting(function($user) {
        //   $user->deleted_by = auth()->user()->id;
        //   $user->save();
        // });
    }

}
