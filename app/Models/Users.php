<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table = 'users';
    protected $fillable = ['role_id', 'name'];

    public function role()
    {
        return $this->hasOne('App\Models\Roles','id','role_id');
    }
}
