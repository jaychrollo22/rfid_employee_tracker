<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
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
        $employee = Employee::select('id','user_id','id_number','first_name','last_name','middle_name','position','level')
                            ->with('user.user_role')
                            ->where('user_id',Auth::user()->id)
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
            'title' => 'Dashboard',
            'user' => $employee,
            'role' => $role
        ]);

        return view('home');
    }
}
