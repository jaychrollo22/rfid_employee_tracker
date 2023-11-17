<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Employee;
use App\GocEmployee;

class AutoGetEmployee extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:auto_get_goc_employee';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $saveGocEmployees = $this->saveGocEmployees();
        $this->info('Save : ' . $saveGocEmployees);
    }

    public function saveGocEmployees(){
        $goc_employees = Employee::select('id','user_id','id_number','first_name','last_name','position','mobile_number','rfid_64','rfid_26','door_id_number','status')
                                        ->with(array(
                                            'companies',
                                            'departments',
                                            'locations',
                                        ))
                                        ->with('user')
                                        ->where(function($q){
                                            $date =  date("Y-m-d", strtotime("-1 months"));
                                            $q->where('status','Active')->orWhere('date_resigned','>=',$date);
                                        })
                                        ->get();
        
        
        if($goc_employees){
            $x = 0;
            foreach($goc_employees as $item){
                $name = $item['first_name'] . ' ' . $item['last_name'];
                $department = $item['departments'][0]['name'];
                $company = $item['companies'][0]['name'];
                $location = $item['locations'][0]['name'];

                $goc_employee_data = [
                    'employee_id'=>$item['id'],
                    'user_id'=>$item['user_id'],
                    'id_number'=>$item['id_number'],
                    'name'=>$name,
                    'position'=>$item['position'],
                    'department'=>$department,
                    'company'=>$company,
                    'location'=>$location,
                    'mobile_number'=>$item['mobile_number'],
                    'status'=>$item['status'],
                    'rfid_64'=> $item['rfid_64'],
                    'rfid_26'=> $item['rfid_26'],
                    'door_id_number'=> $item['door_id_number'],
                    'email'=> $item['user']['email'],
                ];

                $validate = GocEmployee::where('user_id',$item['user_id'])->first();
                if(empty($validate)){
                    GocEmployee::create($goc_employee_data);
                    $x++;
                }else{
                    $validate->update($goc_employee_data);
                    $x++;
                }
            }
            return $x;
        }
    }
}
