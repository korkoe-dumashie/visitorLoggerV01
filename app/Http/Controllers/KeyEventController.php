<?php

namespace App\Http\Controllers;

use App\Models\{Activities, Employee, Key, KeyEvent};
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Http,Log};

class KeyEventController extends Controller
{



    public function pickedKeys(){
        $keys = KeyEvent::with(['key', 'employee'])
        ->latest()
        ->get();


        return view('keys.keys',compact('keys'));
    }

    
    public function pickKey(){
        
        $employees = Employee::whereNot('employment_status','inactive')->get();
        $keys = Key::where('status', 'active')->get();
        return view('keys.create',compact('employees','keys'));
        }
    
        public function logKey(Request $request) {

            Log::debug('pick key');
            $request->validate([
                'picked_by' => 'required|exists:employees,id',
                'key_number' => 'required|exists:keys,id',
            ]);
            Log::debug('staff ' . request('picked_by'));




            $key=  Key::findOrFail($request->key_number) ;

            $key_name = $key->key_name;

            // Log::debug('key details: ' . $pickedKey);
        

            $employee = Employee::findOrFail(request('picked_by'));
            

            $employeeName = $employee->first_name . ' ' . $employee->last_name;
            // dd('Hello there');
            // dd($employee);
        
            $activeKeyEvent =   KeyEvent::where('key_number',$request->key_number)
            ->where('status','picked')
            ->whereNull('returned_at')
            ->first();
            
            Log::debug('Active key details: ' . $activeKeyEvent);
            if ($activeKeyEvent) {
                return response()->json([
                    'success'=>false,
                    'message'=> $key_name . ' key has already been picked by ' . $activeKeyEvent->employee->first_name . ' ' . $activeKeyEvent->employee->last_name . '.'
                ]);
            }
        
            KeyEvent::create([
                'key_number' => request('key_number'),
                'picked_by' => $employee->id,
                'picked_at' => Carbon::now(),
                'status' => 'picked'
            ]);

            Activities::log(
                action: 'Updated user role',
                description: $employeeName . " picked the " . $key_name . " key."
            );
        
            return response()->json([
                'success'=>true,
                'success_type'=>'key_pickup',
                'message'=> $employeeName . ' picked the ' . $key_name . ' key.'
            ]);
            // return redirect()->back()->with([
            //     'success'=>true,
            //     'success_type'=>'key_pickup'
            // ]);
        }
        
        



        public function submitKey(KeyEvent $keyEvent){
            $employees = Employee::whereNot('employment_status','inactive')->get();

            // dd($keyEvent->key()->first()->key_name);
            return view('keys.submit-key',[
                'employees' => $employees,
                'keyEvent'  => $keyEvent
            ]);
        }


        public function returnKey(KeyEvent $keyEvent)
        {
            request()->validate([
                'returned_by' => 'required|exists:employees,id',
            ]);
            
            try {
                $returnEmployee = Employee::findOrFail(request('returned_by'));
                $employeeName = $returnEmployee->first_name . ' ' . $returnEmployee->last_name;
                $key = Key::findOrFail($keyEvent->key_number);
                
                $credentials = base64_encode(config('otp.username') . ':' . config('otp.password'));
                $response = Http::withHeaders([
                    'Authorization' => 'Basic ' . $credentials,
                    'Content-Type' => 'application/json'
                ])->post(config('otp.base_url').'/pin', [
                    'phonenumber' => $returnEmployee->phone_number
                ]);
                
                $responseData = $response->json();
                Log::debug("Response Data: ". json_encode($responseData));
                
                if ($response->successful()) {
                    session([
                        'phonenumber' => $returnEmployee->phone_number,
                        'otp_key' => $responseData['key'] ?? null,
                        'key_event_id' => $keyEvent->id,
                        'returned_by' => $returnEmployee->id
                    ]);
                    
                    return response()->json([
                        'success' => true,
                        'message' => "An OTP has been sent to {$returnEmployee->phone_number}. Please enter it below to confirm the key return.",
                        'data' => $responseData
                    ]);
                }
                
                return response()->json([
                    'success' => false, 
                    'message' => 'Failed to send code.',
                    'error' => $responseData
                ], 400);
            } catch (\Exception $e) {
                Log::error("Key return error: " . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'error' => "Failed to return key. Please try again"
                ], 500);
            }
        }


        public function verifyOtp(Request $request){

            Log::debug('verify otp');
            $request->validate([
                'otp'=>'required',
            ]);

            $phoneNumber = session('phonenumber');
            $otpKey = session('otp_key');


            Log::debug('Phone Number: '. $phoneNumber);
            Log::debug('OTP Key: '. $otpKey);

            if(!$phoneNumber || !$otpKey){
                return response()->json([
                    'success'=>false,
                    'message'=>'Session expired. Please try again'
                ], 400);
            }


            try{
                $credentials = base64_encode(config('otp.username') . ':' . config('otp.password'));
                    



                $data_request = [
                    'phonenumber' => $phoneNumber,
                    'code' => $request->otp,
                    'key' => $otpKey
                ];

                $response = Http::withHeaders([
                    'Authorization'=> 'Basic '. $credentials,
                    'Content-Type'=>'application/json'
                ])->post(config('otp.base_url').'/pin/verify', $data_request);


                $response_obj = json_decode($response);

                Log::debug('Response: ' . $response);


                if($response_obj->status == 200){
                    // return $this->confirmSubmit();

                    try{
                        $keyEvent = session('key_event_id');
                        $returnedBy = session('returned_by');
        
        
                        Log::debug($keyEvent);
                        Log::debug($returnedBy);
        
        
                        if(!$keyEvent || !$returnedBy){
                            return response()->json([
                                'success'=>false,
                                'message'=>'Session expired. Please try again'
                            ], 400);
                        }
        
                        $keyEvent = KeyEvent::findOrFail($keyEvent);
                        $returnedBy = Employee::findOrFail($returnedBy);
                        $key = Key::findOrFail($keyEvent->key_number);
                        $employeeName = $returnedBy->first_name . ' ' . $returnedBy->last_name;
        
        
                        $keyEvent->update([
                            'returned_by'=>$returnedBy->id,
                            'status'=>'returned',
                            'returned_at'=>Carbon::now()
                        ]);
        
        
                        Activities::log(
                            action: 'Key Returned',
                            description: $employeeName . ' returned the ' . $key->key_name . ' key.'
                        );
        
                        session()->forget(['phonenumber', 'otp_key', 'key_event_id', 'returned_by']);
                        return response()->json([
                            'success'=> true,
                            'message'=> $employeeName . " returned the '{$key->key_name}' key."
                        ]);
        
        
                    }catch(Exception $e){
        
                        Log::error("Confirm submit error: " . $e->getMessage());
                        return response()->json([
                            'success' => false,
                            'message' => 'Failed to process key return. Please try again.'
                        ], 500);
                    }
                }

                return response()->json([
                    'success'=>false,
                    'message'=>'Invalid code. Please try again.'
                ], 400);

            } catch(Exception $e){
                Log::error("Key return error: " . $e->getMessage());

                return response()->json([
                    'success'=>false,
                    'message'=>'An unexpected error occured: ' . $e->getMessage()
                ], 500);
            }
        }

        public function confirmSubmit(){

            try{
                $keyEvent = session('key_event_id');
                $returnedBy = session('returned_by');


                Log::debug($keyEvent);
                Log::debug($returnedBy);


                if(!$keyEvent || !$returnedBy){
                    return response()->json([
                        'success'=>false,
                        'message'=>'Session expired. Please try again'
                    ], 400);
                }

                $keyEvent = KeyEvent::findOrFail($keyEvent);
                $returnedBy = Employee::findOrFail($returnedBy);
                $key = Key::findOrFail($keyEvent->key_number);
                $employeeName = $returnedBy->first_name . ' ' . $returnedBy->last_name;


                $keyEvent->update([
                    'returned_by'=>$returnedBy,
                    'status'=>'returned',
                    'returned_at'=>Carbon::now()
                ]);


                Activities::log(
                    action: 'Key Returned',
                    description: $employeeName . ' returned the ' . $key->key_name . ' key.'
                );

                session()->forget(['phonenumber', 'otp_key', 'key_event_id', 'returned_by']);
                return response()->json([
                    'success'=> true,
                    'message'=> $returnedBy->first_name . ' ' . $returnedBy->last_name . " returned the '{$key->key_name}' key."
                ]);


            }catch(Exception $e){

                Log::error("Confirm submit error: " . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to process key return. Please try again.'
                ], 500);
            }
        }
}