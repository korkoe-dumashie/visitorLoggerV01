<?php

    namespace App\Http\Controllers;

    use App\Models\Activities;
    use App\Models\Device;
    use App\Models\Employee;
    use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

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
                    Log::error("Did not log device because: ". $e->getMessage() );
                    return redirect()->back()->withErrors(['error' => 'An error occurred while logging the device. Contact support.']);
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
                Log::error('Device logging failed: ' . $e->getMessage());
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

                Log::error('Device sign-out failed: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Error processing request. Contact support for immediate assistance.'
                ], 500);
            }
        }

        public function requestDeviceOtp(Device $device)
{
    request()->validate([
        'phone_number' => 'required|string',
    ]);

    try {
        $phone = request('phone_number');

        Log::debug("Device return - phone number: " . $phone);

        $credentials = base64_encode(config('otp.username') . ':' . config('otp.password'));
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $credentials,
            'Content-Type' => 'application/json'
        ])->post(config('otp.base_url').'/pin', [
            'phonenumber' => $phone
        ]);

        $responseData = $response->json();
        Log::debug("Device OTP Response: ". json_encode($responseData));

        if ($response->successful()) {
            session([
                'device_phonenumber' => $phone,
                'device_otp_key' => $responseData['key'] ?? null,
                'device_id' => $device->id,
                'device_otp_attempts' => 0
            ]);

            return response()->json([
                'success' => true,
                'message' => "An OTP has been sent to {$phone}. Please enter it to confirm the device return.",
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to send OTP. Please try again.',
        ], 400);
    } catch (\Exception $e) {
        Log::error("Device OTP error: " . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => "Failed to send OTP. Please try again."
        ], 500);
    }
}

public function verifyDeviceOtp(Request $request)
{
    Log::debug('verify device otp');
    $request->validate([
        'otp' => 'required',
    ]);

    $phoneNumber = session('device_phonenumber');
    $otpKey = session('device_otp_key');
    $attempts = session('device_otp_attempts', 0);

    Log::debug('Device Phone Number: '. $phoneNumber);
    Log::debug('Device OTP Key: '. $otpKey);
    Log::debug('Device Attempts: '. $attempts);

    if (!$phoneNumber || !$otpKey) {
        return response()->json([
            'success' => false,
            'message' => 'Session expired. Please try again'
        ], 400);
    }

    try {
        $credentials = base64_encode(config('otp.username') . ':' . config('otp.password'));

        $data_request = [
            'phonenumber' => $phoneNumber,
            'code' => $request->otp,
            'key' => $otpKey
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Basic '. $credentials,
            'Content-Type' => 'application/json'
        ])->post(config('otp.base_url').'/pin/verify', $data_request);

        $response_obj = json_decode($response);
        Log::debug('Device OTP Response: ' . $response);

        if ($response_obj->status == 200) {
            return $this->processDeviceReturn(false); // OTP verified
        }

        // Increment attempts
        $attempts++;
        session(['device_otp_attempts' => $attempts]);

        if ($attempts >= 3) {
            return response()->json([
                'success' => false,
                'message' => 'Maximum OTP attempts reached.',
                'max_attempts_reached' => true
            ], 400);
        }

        return response()->json([
            'success' => false,
            'message' => "Invalid code. Please try again. ({$attempts}/3 attempts)",
            'attempts' => $attempts
        ], 400);

    } catch(Exception $e) {
        Log::error("Device OTP verification error: " . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'An unexpected error occurred. Please try again.'
        ], 500);
    }
}

private function processDeviceReturn($otpSkipped = false)
{
    try {
        $deviceId = session('device_id');
        $phoneNumber = session('device_phonenumber');

        if (!$deviceId) {
            return response()->json([
                'success' => false,
                'message' => 'Session expired. Please try again'
            ], 400);
        }

        $device = Device::findOrFail($deviceId);
        $employee = Employee::findOrFail($device->employee_id);
        $employeeName = $employee->first_name . ' ' . $employee->last_name;

        $device->update([
            'status' => 'returned',
            'verified' => !$otpSkipped,
            'returned_at' => Carbon::now(),
        ]);

        $description = $employeeName . ' returned their device (Serial: ' . $device->serial_number . ')';
        if ($otpSkipped) {
            $description .= ' (OTP verification skipped)';
        } else {
            $description .= ' (OTP verified)';
        }

        Activities::log(
            action: 'Device Returned',
            description: $description
        );

        session()->forget(['device_phonenumber', 'device_otp_key', 'device_id', 'device_otp_attempts']);

        return response()->json([
            'success' => true,
            'message' => 'Device returned successfully.'
        ]);

    } catch(Exception $e) {
        Log::error("Process device return error: " . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to process device return. Please try again.'
        ], 500);
    }
}

public function skipDeviceVerification(Request $request)
{
    $attempts = session('device_otp_attempts', 0);

    if ($attempts < 3) {
        return response()->json([
            'success' => false,
            'message' => 'You must attempt OTP verification 3 times before skipping.'
        ], 400);
    }

    return $this->processDeviceReturn(true); // Skip OTP
}


public function requestSignOutOtp(Device $device)
{
    request()->validate([
        'phone_number' => 'required|string',
    ]);

    try {
        $phone = request('phone_number');

        Log::debug("Device sign out - phone number: " . $phone);

        $credentials = base64_encode(config('otp.username') . ':' . config('otp.password'));
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $credentials,
            'Content-Type' => 'application/json'
        ])->post(config('otp.base_url').'/pin', [
            'phonenumber' => $phone
        ]);

        $responseData = $response->json();
        Log::debug("Sign Out OTP Response: ". json_encode($responseData));

        if ($response->successful()) {
            session([
                'signout_phonenumber' => $phone,
                'signout_otp_key' => $responseData['key'] ?? null,
                'signout_device_id' => $device->id,
                'signout_otp_attempts' => 0
            ]);

            return response()->json([
                'success' => true,
                'message' => "An OTP has been sent to {$phone}. Please enter it to confirm the device sign out.",
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to send OTP. Please try again.',
        ], 400);
    } catch (\Exception $e) {
        Log::error("Sign Out OTP error: " . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => "Failed to send OTP. Please try again."
        ], 500);
    }
}

public function verifySignOutOtp(Request $request)
{
    Log::debug('verify sign out otp');
    $request->validate([
        'otp' => 'required',
    ]);

    $phoneNumber = session('signout_phonenumber');
    $otpKey = session('signout_otp_key');
    $attempts = session('signout_otp_attempts', 0);

    Log::debug('Sign Out Phone Number: '. $phoneNumber);
    Log::debug('Sign Out OTP Key: '. $otpKey);
    Log::debug('Sign Out Attempts: '. $attempts);

    if (!$phoneNumber || !$otpKey) {
        return response()->json([
            'success' => false,
            'message' => 'Session expired. Please try again'
        ], 400);
    }

    try {
        $credentials = base64_encode(config('otp.username') . ':' . config('otp.password'));

        $data_request = [
            'phonenumber' => $phoneNumber,
            'code' => $request->otp,
            'key' => $otpKey
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Basic '. $credentials,
            'Content-Type' => 'application/json'
        ])->post(config('otp.base_url').'/pin/verify', $data_request);

        $response_obj = json_decode($response);
        Log::debug('Sign Out OTP Response: ' . $response);

        if ($response_obj->status == 200) {
            return $this->processDeviceSignOut(false); // OTP verified
        }

        // Increment attempts
        $attempts++;
        session(['signout_otp_attempts' => $attempts]);

        if ($attempts >= 3) {
            return response()->json([
                'success' => false,
                'message' => 'Maximum OTP attempts reached.',
                'max_attempts_reached' => true
            ], 400);
        }

        return response()->json([
            'success' => false,
            'message' => "Invalid code. Please try again. ({$attempts}/3 attempts)",
            'attempts' => $attempts
        ], 400);

    } catch(Exception $e) {
        Log::error("Sign Out OTP verification error: " . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'An unexpected error occurred. Please try again.'
        ], 500);
    }
}

private function processDeviceSignOut($otpSkipped = false)
{
    try {
        $deviceId = session('signout_device_id');
        $phoneNumber = session('signout_phonenumber');

        if (!$deviceId) {
            return response()->json([
                'success' => false,
                'message' => 'Session expired. Please try again'
            ], 400);
        }

        $device = Device::findOrFail($deviceId);
        $employee = Employee::findOrFail($device->employee_id);
        $employeeName = $employee->first_name . ' ' . $employee->last_name;

        $device->update([
            'status' => 'signed_out',
            'verified' => !$otpSkipped,
            'signed_out_at' => Carbon::now(),
        ]);

        $description = $employeeName . ' signed out their device (Serial: ' . $device->serial_number . ')';
        if ($otpSkipped) {
            $description .= ' (OTP verification skipped)';
        } else {
            $description .= ' (OTP verified)';
        }

        Activities::log(
            action: 'Device Signed Out',
            description: $description
        );

        session()->forget(['signout_phonenumber', 'signout_otp_key', 'signout_device_id', 'signout_otp_attempts']);

        return response()->json([
            'success' => true,
            'message' => 'Device signed out successfully.'
        ]);

    } catch(Exception $e) {
        Log::error("Process device sign out error: " . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to process device sign out. Please try again.'
        ], 500);
    }
}

public function skipSignOutVerification(Request $request)
{
    $attempts = session('signout_otp_attempts', 0);

    if ($attempts < 3) {
        return response()->json([
            'success' => false,
            'message' => 'You must attempt OTP verification 3 times before skipping.'
        ], 400);
    }

    return $this->processDeviceSignOut(true); // Skip OTP
}

    }
