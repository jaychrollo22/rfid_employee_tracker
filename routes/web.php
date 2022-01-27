<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//Employees
Route::get('/employees', 'EmployeeController@index')->name('employees');
Route::get('/get-employees-data', 'EmployeeController@getEmployeesData');

//Users
Route::get('/users', 'UsersController@index')->name('users');
Route::get('/get-users-data', 'UsersController@getUsersData');
Route::post('/save-change-user-role', 'UsersController@saveChangeUserRole');

//Activity Logs
Route::get('/activity-logs', 'ActivityLogsController@index')->name('activity-logs');
Route::get('/get-activity-logs', 'ActivityLogsController@activityLogs');

//RFID Settings Controllers
Route::get('/rfid-settings-controllers', 'RfidSettingsController@rfidSettingsControllers')->name('rfid-settings-controllers');
Route::get('/get-rfid-settings-controllers-data', 'RfidSettingsController@getRfidSettingsControllersData');
Route::post('/save-rfid-settings-controller', 'RfidSettingsController@saveRfidSettingsController');
Route::post('/update-rfid-settings-controller', 'RfidSettingsController@updateRfidSettingsController');

//RFID Settings Doors
Route::get('/rfid-settings-doors', 'RfidSettingsController@rfidSettingsDoors')->name('rfid-settings-doors');
Route::get('/get-rfid-settings-doors-data', 'RfidSettingsController@getRfidSettingsDoorsData');
Route::post('/save-rfid-settings-door', 'RfidSettingsController@saveRfidSettingsDoor');
Route::post('/update-rfid-settings-door', 'RfidSettingsController@updateRfidSettingsDoor');


//User Favorites
Route::post('/save-user-favorite', 'UserFavoritesController@saveUserFavorite');

//Settings | Show Favorites per User
Route::post('/save-session-show-favorites', 'UserFavoritesController@saveSessionShowFavorites');
Route::get('/get-session-show-favorites', 'UserFavoritesController@getSessionShowFavorites');

//Access Log Testings
Route::get('/access-log', 'AccessLogController@getAccessLog');
Route::get('/transfer-access-log', 'AccessLogController@transferAccessGrantedLogs');
Route::get('/delete-invalid-access-log', 'AccessLogController@deleteInvalidAccessLog');
