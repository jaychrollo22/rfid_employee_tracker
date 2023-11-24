<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Employee;
use App\EmployeeCurrentAreaLocationLog;
use App\RfidDoor;

use DB;

class ReportsController extends Controller
{
     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function reportsEmployeeLocations(){
        return view('reports.reports_employee_locations');
    }


    public function reportsByDoors(){
        return view('reports.reports_by_doors');
    }

    public function reportsEmployeePerCount(){
        session([
            'title' => 'Employee per Device Count',
        ]);

        return view('reports.reports_employee_per_count');
    }

    public function reportsEmployeePerLateCount(){
        session([
            'title' => 'Employee per Late Count',
        ]);

        return view('reports.reports_employee_per_late_count');
    }
}
