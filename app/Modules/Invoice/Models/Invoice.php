<?php

namespace App\Modules\Invoice\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Invoice extends Model {

    protected $table = 'invoices';
    protected $fillable = [
        'id',
        'order_id',
        'invoice_number',
        'invoice_path',
        'status',
        'is_archive',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    public static function getInvoiceList()
    {
        $query = Invoice::where('is_archive',false);
        return $query->orderBy('id','desc');
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
