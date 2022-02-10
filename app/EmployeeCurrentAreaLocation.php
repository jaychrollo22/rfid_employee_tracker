<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
class EmployeeCurrentAreaLocation extends Model
{
    protected $connection = 'mysql';
    protected $table = 'employee_current_area_locations';
    protected $guarded = [];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('F d, Y H:i:s');
    }

    public function rfid_controller()
    {
        return $this->belongsTo('App\RfidController','controller_id','controller_id')->select('id','controller_id','controller_name','location');
    }

    public function employee(){
        return $this->belongsTo('App\Employee','card_code','rfid_64')->select('id','user_id','first_name','last_name','position','door_id_number','rfid_64');
    }
}
