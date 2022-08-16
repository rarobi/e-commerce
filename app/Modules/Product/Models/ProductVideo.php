<?php

namespace App\Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ProductVideo extends Model {

    protected $table = 'product_videos';
    protected $fillable = [
        'id',
        'product_id',
        'video_link',
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
