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

                    $validate_access_log = EmployeeCurrentAreaLocationLog::where('card_code',$card_code)
                                                                            ->where('controller_id',$item['ControllerID'])
                                                                            ->where('door_id',$item['DoorID'])
                                                                            ->where('local_time',$local_time)
                                                                            ->first();

                    $employee_current_location = EmployeeCurrentAreaLocation::where('card_code',$card_code)->first();

                    if(empty($validate_access_log)){
                        if($save_access_log = EmployeeCurrentAreaLocationLog::create($data)){
                            AccessLog::where('LogID',$item->LogID)->delete();

                            if(empty($employee_current_location)){
                                EmployeeCurrentAreaLocation::create($data);
                            }else{
                                $employee_current_location->update($data);
                            }
                            
                            DB::commit();
                            $x++;
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
