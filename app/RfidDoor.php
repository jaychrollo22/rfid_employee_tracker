<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;  

class RfidDoor extends Model implements AuditableContract
{
    protected $connection = 'mysql';

    use Auditable;
    protected $auditIncluded = [];
    protected $auditTimestamps = true;

    
    protected $guarded = [];

    public function rfid_controller()
    {
        return $this->belongsTo('App\RfidController','controller_id','controller_id')->select('id','controller_id','controller_name','location');
    }
}
