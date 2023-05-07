<?php

namespace App\Modules\Purchase\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model {

    protected $table = 'purchase_details';
    protected $fillable = [
        'id',
        'purchase_id',
        'product_id',
        'unit_price',
        'quantity',
        'status',
        'is_archive',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public static function getPurchaseDetailsList($purchaseId)
    {
        return PurchaseDetail::leftJoin('purchases', 'purchases.id', '=', 'purchase_details.purchase_id')
            ->leftJoin('products', 'products.id', '=', 'purchase_details.product_id')
            ->where('purchase_details.status', 1)
            ->where('purchase_details.is_archive', 0)
            ->where('purchases.id', $purchaseId)
            ->orderBy('purchase_details.id', 'desc')
            ->get([
                'purchase_details.*',
                'products.name as product_name',
                'products.code as product_code',
                'products.price'
            ]);
    }

    public static function boot() {
        parent::boot();
        static::creating(function($purchaseDetail) {
            $purchaseDetail->created_by = auth()->user()->id;
            $purchaseDetail->updated_by = auth()->user()->id;
        });

        static::updating(function($purchaseDetail) {
            $purchaseDetail->updated_by = auth()->user()->id;
        });
    }
}
