<?php

namespace App\Modules\Settings\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model {

    protected $table = 'districts';
    protected $fillable = [
        'id',
        'division_id',
        'name',
        'bn_name',
        'lat',
        'lon',
        'url',
        'status',
        'is_archive',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at'
    ];


}
