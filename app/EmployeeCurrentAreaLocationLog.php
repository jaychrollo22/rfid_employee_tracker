<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
class EmployeeCurrentAreaLocationLog extends Model
{
   
    protected $connection = 'mysql';
    protected $table = 'employee_current_area_location_logs';
    protected $guarded = [];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('F d, Y H:i:s');
    }

    public function rfid_controller()
    {
        return $this->belongsTo('App\RfidController','controller_id','controller_id')->select('id','controller_id','controller_name','location');
    }
}
