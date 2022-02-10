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

                return $x;
            }
        }catch (Exception $e) {
            DB::rollBack();
            return 'error';
        }
        
    }
}
