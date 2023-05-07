<?php

namespace App\Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model {



    protected $table = 'products';
    protected $fillable = [
        'id',
        'name',
        'code',
        'price',
        'quantity',
        'photo',
        'status',
        'is_archive',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public static function getProductList()
    {
        return Product::where('is_archive', 0)->orderBy('products.id', 'desc');
    }

    public static function boot() {
        parent::boot();
        static::creating(function($product) {
            $product->created_by = auth()->user()->id;
            $product->updated_by = auth()->user()->id;
        });

        static::updating(function($product) {
            $product->updated_by = auth()->user()->id;
        });
    }

}
