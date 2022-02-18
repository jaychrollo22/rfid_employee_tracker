<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\RfidController;
use App\RfidDoor;

use DB;
use Auth;
use Storage;

class RfidSettingsController extends Controller
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

    public function rfidSettingsControllers(){
        if(session('role') == "Administrator"){
            session([
                'title' => 'RFID Controllers',
            ]);
            return view('rfid_controllers.index'); 
        }else{
            return redirect('/home');
        }
    }

    public function getRfidSettingsControllersData(Request $request){
        return RfidController::all();
    }

    public function saveRfidSettingsController(Request $request){
       
        $request->validate([
            'controller_id' => 'required|integer|unique:rfid_controllers',
            'controller_name' => 'required',
            'location' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->all();
            $data['created_by'] = Auth::user()->id;
            if($controller = RfidController::create($data)){
                DB::commit();
                $new_controller = RfidController::where('id',$controller->id)->first();
                return $reponse = [
                    'status'=>'saved',
                    'controller'=> $new_controller
                ];  
            }
        }catch (Exception $e) {
            DB::rollBack();
            return $reponse = [
                'status'=>'error'
            ];
        }
    }

    public function updateRfidSettingsController(Request $request){
       
        $request->validate([
            'controller_id' => 'required|integer',
            'controller_name' => 'required',
            'location' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->all();
            $data['updated_by'] = Auth::user()->id;
            $controller = RfidController::where('id',$data['id'])->first();
            if($controller){
                unset($data['id']);
                if($controller->update($data)){
                    DB::commit();
                    $updated_controller = RfidController::where('id',$controller->id)->first();
                    return $reponse = [
                        'status'=>'saved',
                        'controller'=> $updated_controller
                    ]; 
                }
            }
        }catch (Exception $e) {
            DB::rollBack();
            return $reponse = [
                'status'=>'error'
            ];
        }
    }

    //RFID DOORS------------------------------------------------------------------------------------

    public function rfidSettingsDoors(){
        if(session('role') == "Administrator"){
            session([
                'title' => 'RFID Doors',
            ]);
            return view('rfid_doors.index');
        }else{
            return redirect('/home');
        }
    }

    public function getRfidSettingsDoorsData(Request $request){
        return RfidDoor::with('rfid_controller')->get();
    }

    public function saveRfidSettingsDoor(Request $request){
       
        $request->validate([
            'door_id' => 'required|integer',
            'door_name' => 'required',
            'controller_id' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->all();
            $data['created_by'] = Auth::user()->id;
            if($door = RfidDoor::create($data)){
                DB::commit();
                $new_door = RfidDoor::with('rfid_controller')->where('id',$door->id)->first();
                return $reponse = [
                    'status'=>'saved',
                    'door'=> $new_door
                ];  
            }
        }catch (Exception $e) {
            DB::rollBack();
            return $reponse = [
                'status'=>'error'
            ];
        }
    }

    public function updateRfidSettingsDoor(Request $request){
        $request->validate([
            'door_id' => 'required|integer',
            'door_name' => 'required',
            'controller_id' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $data = $request->all();
            $data['updated_by'] = Auth::user()->id;
            $door = RfidDoor::where('id',$data['id'])->first();
            if($door){
                if(isset($request->map_file)){
                    if($request->file('map_file')){
                        $attachment = $request->file('map_file');   
                        $filename = $door->id . '.' . $attachment->getClientOriginalExtension();
                        $path = Storage::disk('public')->putFileAs('map_file', $attachment , $filename);
                        $data['map_file'] = $filename;
                    }    
                }
                unset($data['id']);
                if($door->update($data)){
                    DB::commit();
                    $updated_door = RfidDoor::with('rfid_controller')->where('id',$door->id)->first();
                    return $reponse = [
                        'status'=>'saved',
                        'door'=> $updated_door
                    ]; 
                }
            }
        }catch (Exception $e) {
            DB::rollBack();
            return $reponse = [
                'status'=>'error'
            ];
        }
    }
}
