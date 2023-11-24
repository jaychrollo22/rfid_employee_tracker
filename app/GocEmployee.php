<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GocEmployee extends Model
{
    protected $connection = "mysql";

    protected $guarded = [];

    public function employee_current_location_latest()
    {
        return $this->hasOne('App\EmployeeCurrentAreaLocation','card_code','rfid_64')->whereDate('local_time','=',date('Y-m-d'))->latest('local_time');
    }

    public function employee_current_location_logs()
    {
        return $this->hasMany('App\EmployeeCurrentAreaLocationLog','card_code','rfid_64')->select('card_code','controller_id','door_id');
    }
    
    public function employee_current_location_first_logs()
    {
        return $this->hasOne('App\EmployeeCurrentAreaLocationLog','card_code','rfid_64')->select('card_code','controller_id','door_id','local_time')->orderBy('local_time','ASC');
    }
}
