<?php

namespace App\Modules\User\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model {

    protected $table = 'users';
    protected $fillable = [
        'id',
        'employee_id',
        'name',
        'email',
        'password',
        'status',
        'is_archive',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at',
        'remember_token'
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function($user) {
            if(auth()->user()){
                $user->created_by = auth()->user()->id;
                $user->updated_by = auth()->user()->id;
            }
        });

        static::updating(function($user) {
            if(auth()->user())
                $user->updated_by = auth()->user()->id;
        });
    }

}
