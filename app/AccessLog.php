<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccessLog extends Model
{
    protected $connection = 'sqlsrv';

    protected $table = 'AccessLog';
}
