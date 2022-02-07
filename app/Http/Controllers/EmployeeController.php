<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use App\EmployeeCurrentAreaLocationLog;
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
        return $employee = Employee::select('id','id_number','first_name','middle_name','last_name','cluster','position','rfid_64','door_id_number')
                                        ->with('companies','departments','locations','employee_current_location_latest')
                                        ->with(array('user_favorite'=>function($q){
                                            $q->where('auth_user_id',Auth::user()->id);
                                        }))
                                        ->where('status','Active')
                                        ->orderBy('last_name','ASC')
                                        ->get();
    }   

    public function viewHistoryLogs(Request $request){
        $employee = Employee::findOrFail($request->id);
        session([
            'title' => $employee['first_name'] . ' ' . $employee['last_name'],
            'view_history_employee_id' => $employee['id']
        ]);
        return view('employees.view_history_logs');
    }

    public function viewHistoryLogsData(Request $request){

        $employee_id = isset($request->employee_id) ? $request->employee_id : session('view_history_employee_id');
        $from = $request->from ? $request->from : date('Y-m-d');
        $to = $request->to ? $request->to : date('Y-m-d');

        $date_filter = [
            'from'=>$from,
            'to'=>$to,
        ];

        return $employee_current_area_location_logs = Employee::select('id','user_id','id_number','first_name','middle_name','last_name','cluster','position','rfid_64','door_id_number')
                                ->with('user','companies','departments','locations')
                                ->with('employee_current_location_latest')
                                ->with(array('employee_current_location_logs'=>function($q) use($date_filter){
                                    $q->whereDate('local_time','>=',$date_filter['from']);
                                    $q->whereDate('local_time','<=',$date_filter['to']);
                                }))
                                ->where('id',$employee_id)
                                ->first();
    }
}
