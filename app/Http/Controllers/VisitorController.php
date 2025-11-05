<?php

namespace App\Http\Controllers;


use App\Models\{AccessCards, Visitor,Employee, VisitorAccessCard, Activities};
use App\Models\UserSessions;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\{Auth, DB,Log,Http};
use Illuminate\Http\Request;

class VisitorController extends Controller
{


    // private $baseUrl = 'https://smpp.theteller.net';


    public function index(){


        return view('visitor.visits',[
            'visitor' => Visitor::orderBy('status')->get(),

        ]);
    }


    public function create(Request $request){
        $employees = Employee::whereNot('employment_status','inactive')->get();
        $phone_number = $request->query('phone_number');
        // dd($phone_number);
        return view('visitor.entry', compact('employees','phone_number'));

    }


    public function store(){


        $availableCards = VisitorAccessCard::where('status', 'available')
        ->where('active','enabled')
        ->get();


        // dd($availableCards);

        function getCardId($index, $availableCards){
            Log::debug("Size Of Available Cards: ". sizeof($availableCards));
            Log::debug("index + 1: " . $index+1);
            try{
                if( $index+1 <= sizeof($availableCards)){
                    return $availableCards[$index];
                }else{
                    return 0;
                }


            }   catch (Exception $exception){

                Log::debug("Exception");
                return 0;
            }
        }


        try{
            $validatedData = request()->validate([
                'full_name' => 'required',
                'email' => '',
                'phone_number' => 'required',
                'employee' => 'required',
                'company_name' => '',
                'purpose' => 'required',
                'devices' => 'nullable|array',
                'companions' => 'nullable|array',
            ]);

            $phone = request()->phone_number;



            $formattedPhone = preg_replace('/^0/','233',$phone);


            // $checkVisitor = Visitor::where('phone_number', request()->phone)->first();

            $activeVisit = Visitor::where('phone_number',$formattedPhone)
            ->where('status','ongoing')
            ->whereNull('departed_at')
            ->first();

            if($activeVisit){
                return response()->json([
                    'success'=>false,
                    'message'=> 'Visitor has already signed in'
                ]);
            }

            // $checkVisitor = DB::table('visits')->where('phone_number', '=', $formattedPhone)->count();

            // Log::debug($checkVisitor);

            // if($checkVisitor > 0){
            //     $visitorStatus = 'oldVisitor';
            // } else{
            //     $visitorStatus = 'newVisitor';
            // }

    $devicesJson = request()->has('devices') ? ($validatedData['devices']) : null;

    $companionJson = $validatedData['companions'] ?? [];

    // Filter out items where both name and phone_number are null
    $validCompanions = array_filter($companionJson, function($companion) {
        return !empty($companion['name']) || !empty($companion['phone_number']);
    });



    $countCompanions = count($validCompanions);

    Log::debug('Companions count: ' . $countCompanions);


    $countVisitors = $countCompanions + 1;


    $visitee = Employee::where('id', $validatedData['employee'])->first();


        Log::debug('');

    // dd($visitee);
    if ($visitee->employment_status !== 'active') {
        return redirect()->back()->with([
            'notice' => "{$visitee->first_name} {$visitee->last_name} is currently unavailable. Please contact the front desk for further assistance."
        ]);
    }



    $visitor = Visitor::create([
        'full_name' => $validatedData['full_name'],
        'email' => $validatedData['email'],
        'phone_number' => $formattedPhone,
        'employee_Id' => $validatedData['employee'],
        'company_name' => $validatedData['company_name'],
        'purpose' => $validatedData['purpose'],
        'devices' => $devicesJson,
        'status' => 'ongoing',
        // 'visitorStatus' => $visitorStatus,
        'companions' => $companionJson,
    ]);


    $lastInsertedId = $visitor->id;


    Log::debug("Last Visitor ID: ". $lastInsertedId);


    if($availableCards->count() > 0){



        for($card = 0; $card < $countVisitors; $card++){
            $card_number = getCardId($card, $availableCards);


            Log::debug("Card Number: ". $card_number['card_number']);

            // try{
            DB::table('access_cards')->insert([
                'visitor_id'=>$lastInsertedId,
                'card_number'=>is_object($card_number)?$card_number['card_number']:$card_number
            ]);

            // }catch(Exception $e){
                // Log::debug("Error: ". $e);
            // }

            try{

            if(is_object($card_number)){
                DB::table('visitor_access_cards')
                    ->where('card_number','=',$card_number['card_number'])
                    ->update(['status'=>'unavailable']);
            }
        }catch(Exception $e){
            Log::debug("Error: ". $e);

        }
        }



    }   else{


        DB::table('access_cards')->insert([
            'visitor_id'=>$lastInsertedId,
            'card_number'=>NULL
        ]);

    }
    $activeSecurity = UserSessions::with('user')
    ?->whereNull('logged_out_at')
    ?->whereHas('user', fn($query) => $query->where('role_id', 3))
    ?->latest('last_activity') // pick the most recent one
    ?->first();

// Log::debug("Active Guard: ". $activeSecurity);

if ($activeSecurity) {
    Activities::log(
        action: 'Visitor Departed',
        description: "{$visitor->full_name} Arrived. Security on duty: " . $activeSecurity->user->name
    );
} else {
    Activities::log(
        action: 'Visitor Departed',
        description: "{$visitor->full_name} Arrived. No security user was logged in."
    );
}

    // return redirect('/')->with([
    //     'success',
    //     'We are happy to have you. Enjoy your visit'
    // ]);

    return redirect('/')->with('success', 'Welcome to PaySwitch! You will be given an access card, please keep it with you at all times.');

    // return response()->json([
    //     'success'=>true,
    //     'message'=>'Welcome back! You will be given an access card, please keep it with you at all times.'
    // ]);

        }   catch(Exception $e){
            return redirect()->back()->withErrors(['error' => 'An error occurred while creating the visitor record.']);

        }
    }


    public function checkExit(){
        return view('visitor.check-exit');
    }

    public function confirmExit(Request $request){
        $request->validate([
            'phone_number'=>'required'
        ]);

        // dd($request->phone_number);

        $phone = $request->phone_number;
        Log::debug("Phone Number: ". $phone);

        $checkVisitor = Visitor::where('phone_number',$phone)
        ->where('status','ongoing')
        ->first();
        if($checkVisitor){
            return response()->json([
                'success'=>true,
                'message'=> 'Confirmed',
                'redirect' => route('showVisitor', ['visitor' => $checkVisitor->id])
            ]);
        }else{
            return redirect()->back()->withErrors([
                'error' => 'Visitor not signed in. Please sign in!'
            ])->withInput([
                'phone_number' => $request->phone_number
            ]);
        }
    }

    public function show(Visitor $visitor)
    {
        $access_cards = DB::table('access_cards')
            ->where('visitor_id', $visitor->id)
            ->get();


        return view('visitor.show', compact('visitor', 'access_cards'));
    }


    public function departure( Visitor $visitor){

        // Log::debug("visitor: " . base64_decode($request->visitor));

        // $decodedId = base64_decode($request->visitor);
        $visitor = Visitor::findOrFail($visitor->id);

        // Log::debug("visitor: " . $visitor);
        // if($visitor->visitorStatus == 'newVisitor'){
            Log::debug("new visitor");
            return view('visitor.exit', ['visitor' => $visitor]);
        // }else{

            // Log::debug("old visitor");
            // return $this->exitOldVisitor($visitor);
        }





    //exit


    public function exit(Visitor $visitor){


                // $visitor_id = base64_decode(request('masked_id'));
                $visitor = Visitor::findOrFail(id: $visitor->id);

                Log::debug("got here");


                // if($visitor->visitorStatus == 'newVisitor'){
                // request()->validate([
                //     'rating'=> '',
                //     'visitor_experience' => '',
                //     'marketing_consent' => '',
                // ]);


            $visitor->update([
                'rating' => request('rating'),
                'visitor_experience' => request('visitor_experience'),
                'marketing_consent' => request('marketing_consent'),
                'departed_at' =>Carbon::now(),
                'status' => 'departed',
                // 'visitorStatus' => 'oldVisitor'
            ]);

                // }   else{
                //     $visitor->update([
                //         'departed_at' => Carbon::now(),
                //         'status' => 'departed'
                //     ]);
                // }




                $access_cards = DB::table('access_cards')
                    ->where('visitor_id', $visitor->id)
                    ->get();

                if($access_cards->count()>0){
                    foreach($access_cards as $card){
                        $card_number = $card->card_number;
                        if($card_number != NULL){
                            DB::table('visitor_access_cards')
                                ->where('card_number', $card_number)
                                ->update(['status'=>'available']);
                        }
                    }
                }


                $activeSecurity = UserSessions::with('user')
                    ?->whereNull('logged_out_at')
                    ?->whereHas('user', fn($query) => $query->where('role_id', 3))
                    ?->latest('last_activity') // pick the most recent one
                    ?->first();

                Log::debug("Active Guard: ". $activeSecurity);

                if ($activeSecurity) {
                    Activities::log(
                        action: 'Visitor Departed',
                        description: "{$visitor->full_name} departed. Security on duty: " . $activeSecurity->user->name
                    );
                } else {
                    Activities::log(
                        action: 'Visitor Departed',
                        description: "{$visitor->full_name} departed. No security user was logged in."
                    );
                }

            // return redirect('/')->with([
            //     'success',
            //     'Visitor successfully departed.'
            // ]);

            return redirect('/')->with('success', 'Thank you for visiting us. Kindly return your access card at the front gate. We hope to see you again soon!');


            }




            public function checkVisitor(){
                return view('visitor.old-visitor');
            }




            public function oldVisitorSignIn($visitor){
                $visitor =Visitor::findOrFail($visitor);
                $employees = Employee::get();
                return view('visitor.old-visitor-sign-in',compact('visitor','employees'));

            }





            //
            public function oldVisitor(Request $request)
            {
                $request->validate([
                    'phone_number' => 'required'
                ]);

                Log::debug("Phone Number: ". $request->phone_number);

                $phone = $request->phone_number;



                $formattedPhone = preg_replace('/^0/','233',$phone);



            $activeVisit = Visitor::where('phone_number',$formattedPhone)
            ->where('status','ongoing')
            ->whereNull('departed_at')
            ->first();

            if($activeVisit){
                return response()->json([
                    'success'=>false,
                    'message'=> 'Visitor is already signed in. Please Sign out.'
                ]);
            }

                $visitor = Visitor::where('phone_number', $request->phone_number)->first();

                if ($visitor) {
                    try {
                        // Credentials from cURL example
                        $credentials = base64_encode(config('otp.username') . ':' . config('otp.password'));

                        $response = Http::withHeaders([
                            'Authorization' => 'Basic ' . $credentials,
                            'Content-Type' => 'application/json'
                            ])->post(config('otp.base_url').'/pin', [
                                'phonenumber' => $request->phone_number
                            ]);
                            Log::debug("Visitor: ". json_encode($visitor));

                        Log::debug("Raw Response: ". $response->body());

                        $responseData = $response->json();
                        Log::debug("Response Data: ". json_encode($responseData));

                        // Adjust the response handling based on the actual response structure
                        if ($response->successful()) {
                            // Modify session storage based on actual response
                            session([
                                'phonenumber' => $request->phone_number,
                                'otp_key' => $responseData['key'] ?? null // Adjust this based on actual response
                            ]);

                            return response()->json([
                                'success' => true,
                                'message' => 'OTP sent successfully.',
                                'data' => $responseData // Include full response data
                            ]);
                        }

                        return response()->json([
                            'success' => false,
                            'message' => 'Failed to send code.',
                            'error' => $responseData
                        ], 400);

                    } catch (Exception $e) {
                        Log::error('OTP sending failed: ' . $e->getMessage());
                        return response()->json([
                            'success' => false,
                            'message' => 'An unexpected error occurred: ' . $e->getMessage()
                        ], 500);
                    }
                }

                return response()->json([
                    'success' => false,
                    'redirect' => route('create-visit', ['phone_number' => $request->phone_number]),
                    'message' => 'First time visiting? Please sign up.'
                ]);
            }




            //Verify Otp sent to visitors
            public function verifyOtp(Request $request)
            {
                $request->validate(['otp' => 'required']);

                $phone_number = session('phonenumber');
                $otpKey = session('otp_key');


                Log::debug("Phone Number: ". $phone_number);
                Log::debug("OTP Key: ". $otpKey);

                if (!$phone_number || !$otpKey) {
                    return response()->json(['success' => false, 'message' => 'Session expired. Try again.'], 400);
                }



                try {
                        $credentials = base64_encode(config('otp.username') . ':' . config('otp.password'));


                        $data_request = [
                            'phonenumber' => $phone_number,
                            'code' => $request->otp,
                            'key' => $otpKey
                        ];

                        Log::debug("Data request: ", $data_request);

                    $response = Http::withHeaders([
                        'Authorization' => 'Basic ' . $credentials,
                        'Content-Type' => 'application/json'
                    ])->post(config('otp.base_url').'/pin/verify', $data_request);

                    Log::debug("response: " . $response);

                    // $responseData = $response->json();
                    // Log::debug('OTP Verify Response: ' . json_encode($responseData));

                    $response_obj = json_decode($response);
                    if ($response_obj->status == 200) {
                        session()->forget(['otp_key', 'phonenumber']);
                        $visitor = Visitor::where('phone_number', $phone_number)->first();
                        return response()->json([
                            'success' => true,
                            'redirect' => route('old-visitor', $visitor->id),
                            'message' => $visitor->full_name . ', Welcome back! You will be given an access card, please keep it with you at all times.' ,
                            'data' => $response
                        ]);
                    }

                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid OTP. Please try again.',
                        'error' => $response
                    ], 400);

                } catch (Exception $e) {
                    Log::error('OTP verification failed: ' . $e->getMessage());

                    return response()->json([
                        'success' => false,
                        'message' => 'An unexpected error occurred: ' . $e->getMessage()
                    ], 500);
                }
            }



}
