<?php

namespace App\Modules\Stock\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model {

    protected $table = 'stocks';
    protected $fillable = [
        'id',
        'product_id',
        'quantity',
        'status',
        'is_archive',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    public static function boot() {
        parent::boot();
        static::creating(function($stock) {
            $stock->created_by = auth()->user()->id;
            $stock->updated_by = auth()->user()->id;
        });

        static::updating(function($stock) {
            $stock->updated_by = auth()->user()->id;
        });
    }

}
