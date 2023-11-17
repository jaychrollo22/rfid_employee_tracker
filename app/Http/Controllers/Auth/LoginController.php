<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use App\Employee;
use App\UserLog;
use App\AssignHead;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function authenticated(Request $request, $user) {
        $data = [
            'user_id'=>$user->id,
            'log_date'=>date('Y-m-d')
        ];
        UserLog::create($data);
        $employee = Employee::select('id','user_id','id_number','first_name','last_name','middle_name','position','level')
                            ->with('user.user_role')
                            ->where('user_id',$user->id)
                            ->first();

        //Validate Role
        $role = 'User';
        if(isset($employee->level)){
            if($employee->level == "RANK&FILE"){
                if($employee->user->user_role){
                    if($employee->user->user_role->role == "Administrator"){
                        $role = "Administrator";
                    }
                    elseif($employee->user->user_role->role == "Manager"){
                        $role = "Manager";
                    }
                    else{
                        $role = "User";
                    }
                }
            }else{
                if($employee->user->user_role){
                    if($employee->user->user_role->role == "Administrator" || $employee->user->user_role->role == "President"){
                        $role = "Administrator";
                    }else{
                        $role = "Manager";
                    }
                }else{
                    $role = "Manager";
                }
            } 
        }

        $employee_ids = [];
        $employee_rfids = [];
        if($role == "Manager"){
            $first_level = AssignHead::with('employee_info')
                                            ->whereHas('employee_info',function($q){
                                                $q->where('status','Active');
                                            })
                                            ->where('employee_head_id',$employee->id)
                                            ->get();
            if($first_level){
                foreach($first_level as $under)
                {
                    if (!in_array($under->employee_id, $employee_ids)){
                        array_push($employee_ids , $under->employee_id);
                    }
                    if (!in_array($under->employee_info->rfid_64, $employee_rfids) && $under->employee_info->rfid_64){
                        array_push($employee_rfids , $under->employee_info->rfid_64);
                    }
                    
                }
            }
            session([
                'user' => $employee,
                'role' => $role,
                'employee_ids' => $employee_ids,
                'employee_rfids' => $employee_rfids,
            ]);
        }else{
            session([
                'user' => $employee,
                'role' => $role,
            ]);
        }

       
    }
}
