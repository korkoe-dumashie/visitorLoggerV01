<?php

    namespace App\Http\Controllers;

    use App\Models\Activities;
    use App\Models\Device;
    use App\Models\Employee;
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;

    class DeviceController extends Controller
    {
        //


        public function index()
        {
            return view('devices.index', [
                'devices' => Device::orderByRaw("
                        CASE 
                            WHEN status IN ('takeHome', 'deviceLoggedIn') THEN 0 
                            ELSE 1 
                        END
                    ")
                    ->orderBy('created_at', 'asc') // Maintain order by time
                    ->get()
            ]);
        }
        

        public function create(){
            $employees = Employee::get();
            return view('devices.create', compact('employees'));

        }

        public function store(Request $request) {
            try {
                $request->validate([
                    'serial_number' => 'required',
                    'device_brand' => 'required',
                    'is_personal'=>'required',
                    'employee_id' => 'required|exists:employees,id',
                    'action' => 'required',
                ]);
                // dd($request->action);

                if($request->action ==='bringDevice'){
                    $status = 'deviceLoggedIn';
                }else{
                    $status = 'takeHome';
                }
        
                // dd($status);
                $staff = Employee::findOrFail(request('employee_id'));
                $employeeName = $staff->first_name . ' ' . $staff->last_name;
                try{
                Device::create([
                    'device_brand' => $request->device_brand,
                    'serial_number' => $request->serial_number,
                    'employee_id' => $staff->id,
                    'action' => $request->action,
                    'is_personal'=>$request->is_personal,
                    'status' => $status,
                    'logged_at' => Carbon::now(),
                ]);

                } catch(\Exception $e){
                    Log::debug("Did not log device because: ". $e);
                }
                // Log::debug('Hello: ' );
                Activities::log(
                    action: 'Logged Device',
                    description: $employeeName . ' logged their device!'
                );

                return redirect('/')->with([
                    'sucess' => true,
                    'sucess_type' => 'device_logged'
                ]);
                // return redirect()->back()->with('success', 'Device logged successfully.');
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['error' => 'An error occurred while logging the device.']);
            }
        }

        public function signOutDevice(Device $device)
        {
            try {
                // Remove the dd() as it stops execution
                // dd($device->status);
                
                if($device->status == 'takeHome'){
                    $status = 'returned';
                    $device->update([
                        'status' => $status,
                        'returned_at' => Carbon::now(),
                    ]);
                    $message = 'Device returned successfully.';
                } else {
                    $status = 'signed_out';
                    $device->update([
                        'status' => $status,
                        'signed_out_at' => Carbon::now(),
                    ]);
                    $message = 'Device signed out successfully.';
                }
                
                Activities::log(
                    action: 'Updated Device Log'
                );
                
                // Return JSON response for AJAX request
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'device' => $device
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error processing request: ' . $e->getMessage()
                ], 500);
            }
        }

    }
