<?php

namespace App\Modules\Supplier\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model {

    protected $table = 'suppliers';
    protected $fillable = [
        'id',
        'company_id',
        'supplier_id_no',
        'name',
        'email',
        'mobile',
        'address',
        'status',
        'is_archive',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at'
    ];

}
