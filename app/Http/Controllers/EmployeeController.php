<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use Auth;

class EmployeeController extends Controller
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

    public function index(){
        
        session([
            'title' => 'Employees',
        ]);

        return view('employees.index');
    }

    public function getEmployeesData(Request $request){
        return $employee = Employee::select('id','id_number','first_name','middle_name','last_name','cluster','position','rfid_64')
                                        ->with('companies','departments','locations','employee_current_location_latest')
                                        ->with(array('user_favorite'=>function($q){
                                            $q->where('auth_user_id',Auth::user()->id);
                                        }))
                                        ->where('status','Active')
                                        ->orderBy('last_name','ASC')
                                        ->get();
    }   
}
