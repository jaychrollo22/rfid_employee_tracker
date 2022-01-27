<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\UserRole;
use App\Employee;

use DB;
use Auth;

class UsersController extends Controller
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
            'title' => 'Users',
        ]);

        return view('users.index');
    }

    public function getUsersData(){
        return User::select('id','name','email')->with('user_role','employee')
                    ->whereHas('employee',function($q){
                        $q->where('status','Active');
                    })
                    ->orderBy('name','ASC')
                    ->get(); 
    }

    public function saveChangeUserRole(Request $request){

        $data = $request->all();
      
        DB::beginTransaction();
        try {
            $check_user_role = UserRole::where('user_id',$data['user_id'])->first();
            if($check_user_role){
                unset($data['user_id']);
                $check_user_role->update($data);
                DB::commit();
                return 'saved';
            }else{
                UserRole::create($data);
                DB::commit();
                return 'saved';
            }
        }catch (Exception $e) {
            DB::rollBack();
            return 'error';
        }
    }
}
