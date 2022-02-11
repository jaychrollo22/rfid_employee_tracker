<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\AccessLog;
use App\EmployeeCurrentAreaLocation;
use App\EmployeeCurrentAreaLocationLog;

use Carbon\Carbon;
use DB;
class AccessLogController extends Controller
{
    public function getAccessLog(){

        $timestamp_from = date('Y-m-d 8:00:00');
        $timestamp_to = date('Y-m-d 18:00:00');

        $date = date('Y-m-d');
        $time_from = Carbon::parse($timestamp_from)->toTimeString();
        $time_to = Carbon::parse($timestamp_to)->toTimeString();
        
        $access_log = AccessLog::where('MsgID','=','1')
                                ->whereDate('LocalTime','=',$date)
                                ->get();

        return $access_log;
    }

    
    public function deleteInvalidAccessLog(){
        AccessLog::where('MsgID','!=','1')->delete();
    }

    //Transfer Access Logs to 
    public function transferAccessGrantedLogs(){
        
        $date = date('Y-m-d');
        
        $access_logs = AccessLog::where('MsgID','=','1')
                                    ->whereDate('LocalTime','=',$date)
                                    ->orderBy('LocalTime','ASC')
                                    ->get();

        DB::beginTransaction();
        try{
            if($access_logs){
                $x = 0;
                foreach($access_logs as $item){
                    //Save Access Log
                    $card_code = substr($item['CardCode'],3);
                    $local_time = date('Y-m-d H:i:s',strtotime($item['LocalTime']));
                    $data = [
                        'card_code'=>$card_code,
                        'controller_id'=>$item['ControllerID'],
                        'door_id'=>$item['DoorID'],
                        'local_time'=>$local_time,
                        'direction'=>$item['Direction'],
                    ];

                    
                    $employee_current_location = EmployeeCurrentAreaLocation::where('card_code',$card_code)->first();
                    if(empty($employee_current_location)){
                        EmployeeCurrentAreaLocation::create($data);
                        //Create Location Logs
                        if($save_access_log = EmployeeCurrentAreaLocationLog::create($data)){
                            AccessLog::where('LogID',$item->LogID)->delete();
                            DB::commit();
                            $x++;
                        }
                    }else{
                        //If Same Door Detected 
                        if($employee_current_location['door_id'] == $item['DoorID'] && $employee_current_location['controller_id'] == $item['ControllerID']){
                            //Update Current Location
                            $employee_current_location->update($data);

                            AccessLog::where('LogID',$item->LogID)->delete();
                            DB::commit();
                            $x++;
                        }else{
                            //Update Current Location
                            $employee_current_location->update($data);
                            //Create Location Logs
                            if($save_access_log = EmployeeCurrentAreaLocationLog::create($data)){
                                AccessLog::where('LogID',$item->LogID)->delete();
                                DB::commit();   
                                $x++;
                            }
                        }
                    }
                    
                }
                return $reponse = [
                    'count' => $x
                ];
            }
        }catch (Exception $e) {
            DB::rollBack();
            return 'error';
        }
        
    }
}
