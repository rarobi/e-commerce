<?php

namespace App\Modules\Settings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

class Advertisement extends Model {
    use SoftDeletes;
    protected $table = 'advertisements';
    protected $fillable = [
        'id',
        'title',
        'expired_date',
        'image',
        'description',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static function getAdvertisementList()
    {
        return $query = Advertisement::orderBy('id', 'desc');
    }

    // load image

    public function getImagePathAttribute()
    {
        return  Storage :: url('setting/advertisement/'.$this->image);
    }

   // check image is exist
    public function getCheckImageExistAttribute()
    {
      if($this->image)
        return Storage::exists('setting/advertisement/'.$this->image);
      else
      return false;
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
