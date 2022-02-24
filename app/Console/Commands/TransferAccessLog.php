<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\AccessLog;
use App\EmployeeCurrentAreaLocation;
use App\EmployeeCurrentAreaLocationLog;

use Carbon\Carbon;
use DB;

class TransferAccessLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:transfer_access_log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'RFID Transfer Access Logs';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $auto_transfer = $this->transferAccessGrantedLogs();
        $this->info($auto_transfer . ' Logs');
    }

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
                        $date_today = date('Y-m-d');
                        $date_local_time = date('Y-m-d',strtotime($employee_current_location['local_time']));
                        //If Same Door Detected 
                        if($employee_current_location['door_id'] == $item['DoorID'] && $employee_current_location['controller_id'] == $item['ControllerID'] && $date_today == $date_local_time){
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

                return $x;
            }
        }catch (Exception $e) {
            DB::rollBack();
            return 'error';
        }
        
    }
}
