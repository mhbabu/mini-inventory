<?php

namespace App\Modules\Company\Models;
use Illuminate\Database\Eloquent\Model;

class Company extends Model {

    protected $table = 'companies';
    protected $fillable = [
        'id',
        'name',
        'status',
        'is_archive',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public static function getCompanyList()
    {
        return Company::where('is_archive', 0)->orderBy('id', 'desc');
    }

}
