<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\RfidNumber;

use App\GocEmployee;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::get('/rfid_number',function(){

//     $rfid_64 = RfidNumber::where('CardBits' , '64')->orderBy('LocalTime','DESC')->first();
//     $rfid_26 = RfidNumber::where('CardBits' , '26')->orderBy('LocalTime','DESC')->first();

//     $rfid_number_data = [];

   
//     if($rfid_26){
           
//         $rfid_door_access_code = $rfid_26['CardCode'];
//         $rfid_door = ltrim($rfid_door_access_code,"0x");

//         $rfid_door_str = ltrim($rfid_door, '0');
        
        
//         $hex_to_decimal = hexdec($rfid_door_str);
//         $decimal_to_binary = decbin($hex_to_decimal);

//         //Facility Code 
//         $decimal_to_binary = str_pad($decimal_to_binary, 26, '0', STR_PAD_LEFT);
//         $get_facility_code = substr($decimal_to_binary, 0, 9);
       
//         $get_facility_code_fc = substr($get_facility_code, 1);
        
//         $facility_code = bindec($get_facility_code_fc); 

//         //Card Code
//         $get_card_code = substr($decimal_to_binary, -17);
//         $get_card_code = substr($get_card_code, 0,16);
//         $card_code = bindec($get_card_code);

//         //facility and card code to decimal
//         $facility_code = bindec($get_facility_code_fc); 

//         $rfid_number_data['rfid_door_access_code'] = $rfid_door_access_code;
//         $rfid_number_data['rfid_door'] = $rfid_door;
//         $rfid_number_data['rfid_door_str'] = $rfid_door_str;
//         $rfid_number_data['hex_to_decimal'] = $hex_to_decimal;
//         $rfid_number_data['decimal_to_binary'] = $decimal_to_binary;
//         $rfid_number_data['facility_code'] = '0' . $facility_code;
//         $rfid_number_data['card_code'] =  $card_code;
//         $rfid_number_data['rfid_26'] = '0' . $facility_code . '-' .$card_code;


//         $decimal_number  = bindec(0 . $get_facility_code_fc . $get_card_code);
//         $rfid_number_data['decimal_number'] = str_pad($decimal_number, 10, '0', STR_PAD_LEFT);

            
//     }else{
//         $rfid_number_data['facility_code'] = "";
//         $rfid_number_data['card_code'] = "";
//         $rfid_number_data['rfid_26'] = "";
//     }
//     if($rfid_64){
//         $rfid_code = $rfid_64['CardCode'];
//         $rfid_door = ltrim($rfid_code,"0x");
//         $rfid_number_data['rfid_64'] = $rfid_door;
        
//     }else{
//         $rfid_number_data['rfid_64'] = "";
//     }

//     return $rfid_number_data;
// });

// Route::get('/scan-rfids',function(){
// return $rfid_scans = RfidNumber::orderBy('LocalTime','DESC')->orderBy('CardBits','DESC')->get()->take(2);
// });

Route::get('/employee-per-count',function(){
    return $employee = GocEmployee::where('status',"Active")
                                ->with(['employee_current_location_logs'=>function($q){
                                    $q->select('card_code','controller_id', \DB::raw('count(*) as log_count'))
                                        ->with('rfid_controller')
                                        ->whereDate('created_at',date('Y-m-d'))
                                        ->groupBy('controller_id','card_code');
                                }])
                                ->get();
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
