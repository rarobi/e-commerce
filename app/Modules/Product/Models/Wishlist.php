<?php

namespace App\Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{

    protected $table = 'wishlist';
    protected $fillable = [
        'id',
        'user_id',
        'product_id',

        'status',
        'is_archive',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
        'created_at',
        'updated_at'
    ];


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
