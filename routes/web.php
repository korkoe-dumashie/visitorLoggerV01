<?php

use App\Http\Controllers\{ActivitiesController,AssignUserController,DepartmentController,DeviceController,EmployeeController,KeyController,KeyEventController,RolesController,UserAuthController,PermissionController,VisitorAccessCardController,VisitorController};

use App\Models\{Device,Visitor,KeyEvent,Roles};
use Carbon\Carbon;
use Illuminate\Support\Facades\{Log,Route};
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/




//auth

Route::middleware('guest')->group(function () {
        // Updated password reset routes
        Route::get('/reset-password/{token}', [AssignUserController::class, 'showResetForm'])
            ->name('password.reset'); // Changed to standard name


        Route::get('/newUser/{username}',[AssignUserController::class, 'newUser'])->name('newUser');

        Route::post('/newUser', [AssignUserController::class, 'newUserStore'])
            ->name('newUser.store'); // Changed to standard name

        Route::post('/reset-password', [AssignUserController::class, 'resetPassword'])
            ->name('password.update'); // Changed to standard name

        // Your existing auth routes
        Route::view('login', 'auth.login')->name('login');
        Route::post('login', [UserAuthController::class, 'login']);
    });

Route::middleware('auth')->group(function(){




Route::get('/', function () {


        $devices = Device::where('status', 'takeHome')
        ->orWhere('status', 'deviceLoggedIn')
        ->orWhere('created_at', Carbon::today())
        ->with('employee')
        ->simplePaginate(10);



        $keys = KeyEvent::where('status', 'picked')
        ->with(['key', 'employee'])
        ->simplePaginate(10);

        $all_visits = Visitor::where('status', 'ongoing')->get();

        Log::debug( count($all_visits));
        return view('index',[
                'visitor' => Visitor::where('status', 'ongoing')
                ->whereDate('created_at', Carbon::today())
                ->simplePaginate(5),


        'keys' => KeyEvent::with('employee')->where('status', 'picked')->simplePaginate(10),
        'all_keys' => Visitor::where('created_at', Carbon::today())->get(),

        ],compact('devices','keys','all_visits'));


})->name('/');



                Route::post('logout', [UserAuthController::class, 'logout']);


                //staff

                Route::controller(EmployeeController::class)->group(function(){

                        Route::get('staff',  'index')->middleware('module.permission:staff,view');

                        Route::get('staff/{staff}',  'show')->middleware('module.permission:staff,view');

                        Route::post('store-staff',  'store')->middleware('module.permission:staff,create,modify,delete');

                        Route::get('create-staff',  'create')->middleware('module.permission:staff,create,modify,delete');

                        Route::get('edit-staff/{employee}','edit')->middleware('module.permission:staff,modify');
                        Route::patch('update/{employee}','update')->middleware('module.permission:staff,modify');

                        Route::get('export-staff','exportEmployees')->name('staff.export');
                        Route::get('export-template','exportTemplate')->name('staff.export.template');
                        Route::get('import-staff','showImport')->name('staff.import');
                        Route::post('import-staff','importEmployees')->name('staff.import.post');
                });



                //visitor


                Route::controller(VisitorController::class)->group(function(){

                        Route::get('visits', 'index');

                        Route::get('/departure/{visitor}',  'departure')->name('visitor.departure');

                        Route::get('create-visit',  'create')->name('create-visit');

                        Route::get('visitor/{visitor}', 'display')->name('display');

                        Route::get('old-visitor/{visitor}',  'oldVisitorSignIn')->name('old-visitor');

                        Route::get('check-visitor',  'checkVisitor')->name('check-visitor');

                        Route::get('check-exit', 'checkExit');

                        Route::post('confirmExit', 'confirmExit')->name('confirmExit');


                        Route::get('visit/{visitor}',  'show')->name('showVisitor');

                        Route::post('verify-otp', 'verifyOtp')->name('verify-otp');

                        Route::post('find-visitor','oldVisitor')->name('find-visitor');

                        Route::post('visit', 'store');


                        Route::patch('exit/{visitor}', 'exit')->name('exit');
                })->middleware('module.permission:visits,view,create,modify,delete');




                //keys


                Route::controller(KeyEventController::class)->group(function(){

                        Route::get('keys', 'pickedKeys')->name('keys');
                        Route::get('pick-key', 'pickKey');

                        Route::post('log-key',  'logKey');

                        Route::get('submit-key/{keyEvent}', 'submitKey');

                        Route::post('confirmKey','verifyOtp')->name('confirmKey');

                        Route::patch('return-key/{keyEvent}',  'returnKey');


                })->middleware('module.permission:keys,view');

                Route::controller(KeyController::class)->group(function(){

                        Route::get('all_keys', [KeyController::class, 'keys']);


                        Route::get('create-key', [KeyController::class, 'create']);

                        Route::post('store-key', [KeyController::class, 'store']);


                        Route::patch('/activate-key/{id}', [KeyController::class, 'activate']);

                        Route::patch('/deactivate-key/{id}', [KeyController::class, 'deactivate']);

                })->middleware('module.permission:keys,view,create,modify,delete');




                //device

                Route::controller(DeviceController::class)->group(function(){

                        Route::get('devices', 'index')->name('devices');

                        Route::get('log',  'create');

                        Route::patch('sign-out-device/{device}',  'signOutDevice');

                        Route::post('log-device',  'store');


                })->middleware('module.permission:settings,view,create,modify,delete');


                //departments


                Route::controller(DepartmentController::class)->group(function(){
                        Route::get('departments',  'index');

                        Route::get('create-department',  'create');

                        Route::post('store-department',  'store');

                })->middleware('module.permission:departments,view,create,modify,delete');



                //roles

                Route::controller(RolesController::class)->group(function(){
                        Route::get('roles','index')->middleware('module.permission:roles,view');
                        Route::get('create-role','create')->middleware('module.permission:roles,view,create,modify,delete');
                        Route::post('store-role','store')->middleware('module.permission:roles,create,modify,delete');
                        Route::delete('/delete-role/{id}','delete')->middleware('module.permission:roles,delete');
                });


                //Activity Logs
                Route::controller(ActivitiesController::class)->group(function(){
                        Route::get('logs','index')->middleware('module.permission:logs,view');
                });


                //access card

                Route::controller(VisitorAccessCardController::class)->group(function(){
                        Route::get('create-access-card', 'create')->middleware('module.permission:settings,view,create,modify,delete');

                        Route::post('store-access-card',  'store')->middleware('module.permission:settings,create,modify,delete');

                        Route::get('access-cards','index')->middleware('module.permission:reports,view');

                        Route::patch('disable-access-card/{visitorAccessCard}',  'disable')->middleware('module.permission:settings,delete');

                        Route::patch('enable-access-card/{visitorAccessCard}',  'enable')->middleware('module.permission:settings,delete');
                });



                //user assignment


                Route::controller(AssignUserController::class)->group(function(){
                        Route::get('users','index')->middleware('module.permission:user,view');
                        Route::get('create-user','create')->middleware('module.permission:user,create,modify,delete');

                        Route::get('update/{user}','changeRole')->middleware('module.permission:user,create,modify,delete');


                        Route::patch('user/{user}', 'modify')->middleware('module.permission:user,create,modify,delete');

                        Route::post('assign-user','store')->middleware('module.permission:user,create,modify,delete');

                        Route::delete('/revoke-access/{id}','destroy')->middleware('module.permission:user,create,modify,delete');

                });


                //permissions

                Route::controller(PermissionController::class)->group(function(){
                        Route::get('permissions','index')->middleware('module.permission:roles,view');
                        Route::get('create-permission','create')->middleware('module.permission:roles,view,create,modify,delete');
                        Route::post('store-permission','store')->middleware('module.permission:roles,create,modify,delete');
                        Route::delete('/delete-permission/{id}','destroy')->middleware('module.permission:permissions,delete');
                })->middleware('module.permission:roles,view,create,modify,delete');
                });


                Route::get('records', function () {
                return view('records');
                })->middleware('module.permission:reports,view,create,modify,delete');


                Route::view('settings', 'settings.index')
                ->middleware('module.permission:settings,view,create,modify,delete');

Route::get('/test-error/{code}', function ($code) {
    abort($code);
})->where('code', '[0-9]+');

// Test specific errors
Route::get('/test-404', function () {
    abort(404);
});

Route::get('/test-500', function () {
    throw new Exception('Test server error');
});
