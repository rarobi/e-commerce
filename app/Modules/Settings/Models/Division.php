<?php

namespace App\Modules\Settings\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model {
    protected $table = 'divisions';
    protected $fillable = [
        'id',
        'name',
        'bn_name',
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
