<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use App\UserFavorite;
use App\SettingsUserConfiguration;

use DB;
use Auth;

class UserFavoritesController extends Controller
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

    public function saveUserFavorite(Request $request){
        DB::beginTransaction();
        try {
            $data = $request->all();
            

            $user_favorite = UserFavorite::where('employee_id',$request->employee_id)
                                            ->where('auth_user_id',Auth::user()->id)
                                            ->first();
            if(empty($user_favorite)){
                $data['status'] = '1';
                $data['auth_user_id'] = Auth::user()->id;
                UserFavorite::create($data);
                DB::commit();
                $employee = Employee::select('id','id_number','first_name','middle_name','last_name','cluster','position','rfid_64')
                                            ->with('companies','departments','locations','employee_current_location_latest')
                                            ->with(array('user_favorite'=>function($q){
                                                $q->where('auth_user_id',Auth::user()->id);
                                            }))
                                            ->where('id',$request->employee_id)
                                            ->first();
                return $response = [
                    'status'=>'saved',
                    'employee'=>$employee
                ];
            }else{
                $data['status'] = $user_favorite['status'] == '1' ? 0 : 1;
                $user_favorite->update($data);
                DB::commit();
                $employee = Employee::select('id','id_number','first_name','middle_name','last_name','cluster','position','rfid_64')
                                            ->with('companies','departments','locations','employee_current_location_latest')
                                            ->with(array('user_favorite'=>function($q){
                                                $q->where('auth_user_id',Auth::user()->id);
                                            }))
                                            ->where('id',$request->employee_id)
                                            ->first();
                return $response = [
                    'status'=>'saved',
                    'employee'=>$employee
                ];

            }

        }catch (Exception $e) {
            DB::rollBack();
            return $reponse = [
                'status'=>'error'
            ];
        }
    }

    public function saveSessionShowFavorites(Request $request){
        session([
            'show_favorites' => $request->show_favorites
        ]);
        return $request->show_favorites;
    }

    public function getSessionShowFavorites(){
        return session('show_favorites');
    }

}
