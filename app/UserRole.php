<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $connection = 'mysql';
    
    protected $fillable = [
        'user_id', 'role'
    ];
}
