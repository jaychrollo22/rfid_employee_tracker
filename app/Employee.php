<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $connection = 'mysql_hr';

    public function assign_heads()
    {
        return $this->hasMany('App\AssignHead')->orderBy('id','ASC');
    }
    
    public function immediate_superior()
    {
        return $this->hasMany('App\AssignHead')->orderBy('id','ASC')->where('head_id','3');
    }

    public function immediate_superior_2()
    {
        return $this->hasMany('App\AssignHead')->orderBy('id','ASC')->where('head_id','6');
    }
    
    public function department_manager()
    {
        return $this->hasMany('App\AssignHead')->orderBy('id','ASC')->where('head_id','7');
    }
    public function bu_head()
    {
        return $this->hasMany('App\AssignHead')->orderBy('id','ASC')->where('head_id','4');
    }
    public function cluster_head()
    {
        return $this->hasMany('App\AssignHead')->orderBy('id','ASC')->where('head_id','5');
    }
    
    public function companies()
    {
        return $this->belongsToMany('App\Company')->select('id','name');
    }

    public function departments()
    {
        return $this->belongsToMany('App\Department')->select('id','name');
    }

    public function locations()
    {
        return $this->belongsToMany('App\Location')->select('id','name');
    }

    public function user()
    {
        return $this->belongsTo('App\User')->select('id','name','email');
    }

    public function user_favorite()
    {
        return $this->hasMany('App\UserFavorite')->select('id','employee_id','auth_user_id','status');
    }

    public function employee_current_location_latest()
    {
        return $this->hasOne('App\EmployeeCurrentAreaLocationLog','card_code','rfid_64')->latest('local_time');
    }
}
