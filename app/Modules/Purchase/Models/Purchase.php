<?php

namespace App\Modules\Purchase\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model {

    protected $table = 'purchases';
    protected $fillable = [
        'id',
        'company_id',
        'supplier_id',
        'purchase_no',
        'purchase_date',
        'status',
        'is_archive',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    public static function getPurchaseList()
    {
        return Purchase::leftJoin('companies','companies.id','=','purchases.company_id')
            ->leftJoin('suppliers','suppliers.id','=','purchases.supplier_id')
            ->where('purchases.is_archive', 0);
    }


    public static function boot() {
        parent::boot();
        static::creating(function($purchaseProduct) {
            $purchaseProduct->created_by = auth()->user()->id;
            $purchaseProduct->updated_by = auth()->user()->id;
        });

        static::updating(function($purchaseProduct) {
            $purchaseProduct->updated_by = auth()->user()->id;
        });
    }
}
