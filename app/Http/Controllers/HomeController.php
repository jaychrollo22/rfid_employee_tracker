<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use App\EmployeeCurrentAreaLocationLog;
use Auth;
class HomeController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        session([
            'title' => 'Dashboard',
        ]);
        return view('home');
    }

    public function lastScannedEmployees(){
        $employee_ids = session('employee_ids');
        $employee_rfids = session('employee_rfids');
        if(session('role') == "Manager" || session('role') == "Administrator"){
            return EmployeeCurrentAreaLocationLog::with('employee.companies','employee.departments','employee.locations')
                                                    ->whereDate('local_time','=',date('Y-m-d'))
                                                    ->when(session('role') == "Manager",function($q) use($employee_rfids){
                                                        $q->whereIn('card_code',$employee_rfids);
                                                    })
                                                    ->orderBy('local_time','DESC')
                                                    ->get()
                                                    ->take(50);
        }
    }
}
