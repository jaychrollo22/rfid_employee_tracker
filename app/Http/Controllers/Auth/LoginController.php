<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use App\Employee;
use App\UserLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
            'log_date'=>Carbon::now()->toDateTimeString()
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
                    if($employee->user->user_role->role == "Administrator" || $employee->user->user_role->role == "President" || $employee->user->user_role->role == "Manager"){
                        $role = $employee->user->user_role->role;
                    }else{
                        $role = "Manager";
                    }
                }else{
                    $role = "Manager";
                }
            } 
        }
        session([
            'user' => $employee,
            'role' => $role
        ]);
    }
}
