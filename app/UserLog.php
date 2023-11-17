<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
// use OwenIt\Auditing\Auditable;

class UserLog extends Model 
{
    protected $connection = 'mysql';
    
    protected $auditIncluded = [];
    protected $auditTimestamps = true;

    
    protected $fillable = [
        'user_id',
        'log_date',
    ];
}
