<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RfidNumber;
use App\GocEmployee;
use DB;
class EmployeeReportsController extends Controller
{
    public function getEmployeePerCount(Request $request){
        
        $from = isset($request->from) ? date('Y-m-d',strtotime($request->from)) : date('Y-m-d');
        $to = isset($request->to) ? date('Y-m-d',strtotime($request->to)) : date('Y-m-d');
        $limit = isset($request->limit) ?  $request->limit : 10;
        $search = isset($request->search) ?  $request->search : '';

        $employee_ids = session('employee_ids');

        $employee = GocEmployee::where('status',"Active")
                                    ->with(['employee_current_location_logs'=>function($q) use($from, $to){
                                        $q->select('card_code', 'controller_id','door_id', DB::raw('count(*) as count'))
                                                ->whereBetween('local_time',[$from." 00:00:01", $to." 23:59:59"])
                                                ->groupBy('card_code', 'controller_id','door_id')
                                                ->orderBy('door_id','ASC');
                                    }])
                                    ->when(session('role') == "Manager",function($q) use($employee_ids){
                                        $q->whereIn('id',$employee_ids);
                                    });
        if($search){
            $employee->where('name','like','%'.$search.'%');
        }

        return $employee->paginate($limit);
    }

    public function getEmployeePerLateCount(Request $request){

        $from = isset($request->from) ? $request->from : date('Y-m-d');
        $to = isset($request->to) ? $request->to : date('Y-m-d');
        $limit = isset($request->limit) ?  $request->limit : 10;
        $search = isset($request->search) ?  $request->search : '';

        $employee_ids = session('employee_ids');
        
        $employee = GocEmployee::where('status',"Active")
                                    ->with(['employee_current_location_first_logs'=>function($q) use($from, $to){
                                        $q->with('rfid_controller')
                                                ->whereBetween('local_time',[$from." 00:00:01", $to." 23:59:59"]);
                                    }])
                                    ->when(session('role') == "Manager",function($q) use($employee_ids){
                                        $q->whereIn('id',$employee_ids);
                                    });
                                    
        if($search){
            $employee->where('name','like','%'.$search.'%');
        }

        return $employee->paginate($limit);

    }
}
