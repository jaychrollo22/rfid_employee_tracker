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
}
