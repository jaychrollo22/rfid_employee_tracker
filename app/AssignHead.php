<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssignHead extends Model
{
    protected $connection = 'mysql_hr';

    public function employee_info()
    {
        return $this->hasOne(Employee::class,'id','employee_id')->select('id','first_name','last_name','position','status','rfid_64');
    }
}
