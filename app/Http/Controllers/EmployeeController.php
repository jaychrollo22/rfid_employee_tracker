<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use App\EmployeeCurrentAreaLocationLog;
use App\Location;
use App\Department;
use App\Company;
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
        
        $company = isset($request->company) ? $request->company : "";
        $department = isset($request->department) ? $request->department : "";
        $location = isset($request->location) ? $request->location : "";

        $employee_ids = session('employee_ids');
        if(session('role') == "Manager" || session('role') == "Administrator"){
            return $employee = Employee::select('id','id_number','first_name','middle_name','last_name','cluster','position','rfid_64','door_id_number')
                                        ->with('companies','departments','locations','employee_current_location_latest.rfid_controller')
                                        ->with(array('user_favorite'=>function($q){
                                            $q->where('auth_user_id',Auth::user()->id);
                                        }))
                                        ->when(!empty($company),function($q) use($company){
                                            $q->whereHas('companies', function ($w) use($company)  {
                                                $w->where('id', '=', $company);
                                            });
                                        })
                                        ->when(!empty($department),function($q) use($department){
                                            $q->whereHas('departments', function ($w) use($department)  {
                                                $w->where('id', '=', $department);
                                            });
                                        })
                                        ->when(!empty($location),function($q) use($location){
                                            $q->whereHas('locations', function ($w) use($location)  {
                                                $w->where('id', '=', $location);
                                            });
                                        })
                                        ->when(session('role') == "Manager",function($q) use($location,$employee_ids){
                                            $q->whereIn('id',$employee_ids);
                                        })
                                        ->where('status','Active')
                                        ->orderBy('last_name','ASC')
                                        ->get();

        }
        

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

    public function locations(){

        if(session('role') == "Manager"){
            $employees = Employee::select('id')
                                    ->with(
                                        array(
                                            'locations',
                                        )
                                    )
                                    ->whereIn('id',session('employee_ids'))
                                    ->where('status','Active')
                                    ->orderBy('last_name','ASC')
                                    ->get();
            $location_ids = [];
            foreach($employees as $employee){
                array_push($location_ids , $employee->locations[0]->id);
            }
            return Location::select('id','name')->whereIn('id',$location_ids)->get();
        }else{
            return Location::select('id','name')->get();
        }

    }

    public function departments(){
        if(session('role') == "Manager"){
            $employees = Employee::select('id')
                                    ->with(
                                        array(
                                            'departments',
                                        )
                                    )
                                    ->whereIn('id',session('employee_ids'))
                                    ->where('status','Active')
                                    ->orderBy('last_name','ASC')
                                    ->get();
            $department_ids = [];
            foreach($employees as $employee){
                array_push($department_ids , $employee->departments[0]->id);
            }
            return Department::select('id','name')->whereIn('id',$department_ids)->get();
        }else{
            return Department::select('id','name')->get();
        }
    }
    
    public function companies(){
       if(session('role') == "Manager"){
            $employees = Employee::select('id')
                                    ->with(
                                        array(
                                            'companies',
                                        )
                                    )
                                    ->whereIn('id',session('employee_ids'))
                                    ->where('status','Active')
                                    ->orderBy('last_name','ASC')
                                    ->get();
            $company_ids = [];
            foreach($employees as $employee){
                array_push($company_ids , $employee->companies[0]->id);
            }
            return Company::select('id','name')->whereIn('id',$company_ids)->get();
        }else{
            return Company::select('id','name')->get();
        }
    }
}
