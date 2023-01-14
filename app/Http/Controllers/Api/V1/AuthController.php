<?php

namespace App\Http\Controllers\Api\V1;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Member;
use App\Models\Payment;
use App\Models\BankingInfo;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\WeightHistory;
use App\Models\PersonalChoice;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Models\ShopPost;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

use function PHPUnit\Framework\isEmpty;

class AuthController extends Controller
{

    // public function checkUserExists(Request $request)
    // {
    //     $usr_phone = User::where('phone', $request->phone)->first();
    //     $usr_email = User::where('email', $request->email)->first();

    //     if ($usr_phone || $usr_email) {
    //         return response()->json([
    //             "message" => "Already Registered!"
    //         ]);
    //     } else {
    //         return response()->json([
    //             "message" => "OK"
    //         ]);
    //     }
    // }

    // public function register(Request $request)
    // {
    //     $user = new User();

    //     $user->name = $request->name;
    //     $user->phone = $request->phone;
    //     $user->email = $request->email;
    //     $user->address = $request->address;
    //     $user->password = Hash::make($request->password);

    //     $user->height = $request->height;
    //     $user->weight = $request->weight;
    //     $user->age = $request->age;
    //     $user->gender = $request->gender;
    //     $user->neck = $request->neck;
    //     $user->waist = $request->waist;
    //     $user->hip = $request->hip ?? null;
    //     $user->shoulders = $request->shoulders;
    //     $user->member_code = 'yc-' . substr(Str::uuid(),0,8);

    //     // bmi , bmr
    //     $user->bmi = $request->bmi;
    //     $user->bmr = $request->bmr;
    //     $user->bfp = $request->bfp;

    //     // $physical_limitations = $request->physical_limitation;

    //     $user->physical_limitation = json_encode($request->physical_limitation); //
    //     $user->activities = json_encode($request->activities); //
    //     $user->body_type = $request->body_type;
    //     $user->goal = $request->goal;
    //     $user->daily_life = $request->daily_life;
    //     $user->diet_type = $request->diet_type;
    //     $user->average_night = $request->average_night;
    //     $user->energy_level = $request->energy_level;
    //     $user->ideal_weight = $request->ideal_weight;
    //     $user->most_attention_areas = json_encode($request->most_attention_areas); //
    //     $user->physical_activity = $request->physical_activity;
    //     $user->bad_habits = json_encode($request->bad_habbits); //

    //     $user->hydration = $request->hydration;
    //     // Thandar style start

    //     $user_member_type_id = $request->member_id;
    //     // $member = Member::findOrFail($user_member_type_id);
    //     $user_member_type_level = $request->member_type_level;
    //     $user->membertype_level = $request->member_type_level;
    //     // $user->member_type = $member->member_type;

    //     $user->member_type = 'Free'; ///
    //     $user->request_type = $user_member_type_id;  ///

    //     if ($user_member_type_id == 1) {
    //         $user->active_status = 0;
    //     } else {
    //         $user->active_status = 1;
    //     }

    //     $member_id = 1; ///
    //     $user->save();
    //     $user->members()->attach($member_id, ['member_type_level' => $user_member_type_level]);
    //     $user->assignRole('Free');
    //     // Thandar style end

    //     Auth::login($user);

    //     $weight_history = new WeightHistory();
    //     $weight_date = Carbon::now()->toDateString();
    //     $weight_history->weight = $request->weight;
    //     $weight_history->user_id = auth()->user()->id;
    //     $weight_history->date = $weight_date;
    //     $weight_history->save();


    //     $token = $user->createToken('gym');

    //     return response()->json([
    //         'message' => 'Register successfully!',
    //         'user' => $user,
    //         'user_role' => $user->roles->pluck('name')[0],
    //         'token' => $token->plainTextToken
    //     ]);
    // }

    public function register(Request $request)
    {
        $usr_phone = User::where('phone', $request->phone)->first();
        $usr_email = User::where('email', $request->email)->first();

        if ($usr_phone || $usr_email) {
            return response()->json([
                'message' => 'Already Registered!'
            ]);
        } else {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->address = $request->address;
            $user->password = Hash::make($request->password);
            $user->member_code = 'yc-' . substr(Str::uuid(), 0, 8);
            $user->member_type = "Free";
            $user->active_status = 0;

            $user->save();
            // attach detach lote yan
            $token = $user->createToken('gym');

            return response()->json([
                'message' => 'Register successfully!',
                'token' => $token->plainTextToken,
                'user_role' => 'Free',
                'user' => $user
            ]);
        }
    }

    public function login(Request $request)
    {
        $credentails = ['phone' => $request->phone, 'password' => $request->password];

        if (Auth::attempt($credentails)) {
            // Auth::login($user);
            $user = Auth::user();
            $id = Auth::user()->id;
            $user_info = User::select('users.*','profiles.profile_image')
                    ->leftJoin('profiles','users.profile_id','profiles.id')
                    ->where('users.id',$id)
                    ->first();
            $token = $user->createToken('gym');

            return response()->json([
                'message' => 'Successfully Login!',
                'token' => $token->plainTextToken,
                'user_role' => count($user->roles) < 1 ? 'Free' : $user->roles->pluck('name')[0],
                'user' =>$user_info
            ]);
        } else {
            return response()->json([
                'message' => 'User credential do not match our records!'
            ]);
        }
    }

    public function getRole(){
        $user = Auth::user();
        return response()->json([
            'user_role' => count($user->roles) < 1 ? 'Free' : $user->roles->pluck('name')[0],
        ]);
    }


    public function checkPhone(Request $request)
    {
        $user = User::where('phone', $request->phone)->first();
        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated phone number!'
            ]);
        }

        return response()->json([
            'message' => 'success',
            'user' => $user
        ]);
    }

    public function passwordChange(Request $request)
    {
        $user = User::where('phone', $request->phone)->first();

        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json([
                'message' => "You have changed password successfully",
                'user' => $user
            ]);
        } else {
            return response()->json([
                'message' => 'Your phone is not in our database'
            ]);
        }
    }

    public function getMemberPlans()
    {
        $members = Member::where('member_type', '!=', 'Gym Member')->get();
        return response()->json([
            'members' => $members
        ]);
    }

    public function personalChoices(Request $request)
    {
        $auth_user = auth()->user();
        $user = User::findOrFail($auth_user->id);

        $user->height = $request->height;
        $user->weight = $request->weight;
        $user->age = $request->age;
        $user->gender = $request->gender;
        $user->neck = $request->neck;
        $user->waist = $request->waist;
        $user->hip = $request->hip ?? null;
        $user->shoulders = $request->shoulders;
        $user->member_code = 'yc-' . substr(Str::uuid(), 0, 8);

        // bmi , bmr, bfp
        $user->bmi = $request->bmi;
        $user->bmr = $request->bmr;
        $user->bfp = $request->bfp;

        $user->physical_limitation = json_encode($request->physical_limitation); //
        $user->activities = json_encode($request->activities); //
        $user->body_type = $request->body_type;
        $user->goal = $request->goal;
        $user->daily_life = $request->daily_life;
        $user->diet_type = $request->diet_type;
        $user->average_night = $request->average_night;
        $user->energy_level = $request->energy_level;
        $user->ideal_weight = $request->ideal_weight;
        $user->most_attention_areas = json_encode($request->most_attention_areas); //
        $user->physical_activity = $request->physical_activity;
        $user->bad_habits = json_encode($request->bad_habbits); //

        $user->hydration = $request->hydration;
        $user->request_type = $request->member_id; // member_id from Members table
        $user->membertype_level = $request->member_type_level;
        // for member history to  attach or detach

        // Thandar style start

        // $member = Member::findOrFail($request->member_id);
        // $user->membertype_level = $request->member_type_level;
        // $user->member_type = $member->member_type;
        // $user->members()->attach(['to_member_id' => $request->member_id, 'member_type_level' => $request->member_type_level]);


        // $user->syncRoles($member->member_type);
        //     // Thandar style end

        // to save weight history
        $weight_history = new WeightHistory();
        $current_date = Carbon::now()->toDateString();
        $weight_history->weight = $request->weight;
        $weight_history->user_id = auth()->user()->id;
        $weight_history->date = $current_date;
        $weight_history->save();

        $user->active_status = 0;
        $user->save();

        return response()->json([
            'message' => 'success'
        ]);
    }

    // public function upgradePlan(Request $request)
    // {
    //     $auth_user = auth()->user();
    //     $user = User::findOrFail($auth_user->id);

    //     $member = Member::findOrFail($request->member_id);
    //     $user->membertype_level = $request->member_type_level;
    //     $user->member_type = $member->member_type;
    //     $user->members()->attach(['from_member_id' => $user->request_type, 'to_member_id' => $request->member_id, 'member_type_level' => $request->member_type_level]);

    //     //     if ($user_member_type_id == 1) {
    //     //         $user->active_status = 0;
    //     //     } else {
    //     //         $user->active_status = 1;
    //     //     }

    //     //     $member_id = 1; ///
    //     //     $user->save();
    //     //
    //     $user->syncRoles($member->member_type);
    //     $user->save();

    //     return response()->json([
    //         'message' => 'success'
    //     ]);
    // }

    public function storeBankPayment(Request $request)
    {
        $auth_user = auth()->user();

        $payment = new Payment();
        $payment->user_id = $auth_user->id;
        $payment->payment_type = 'banking';
        $payment->payment_name = $request->payment_name;
        $payment->bank_account_number = $request->bank_account_number;
        $payment->bank_account_holder = $request->bank_account_holder;
        $payment->amount = $request->amount;

        // Store Image
        $tmp = $request->image;

        $file = base64_decode($tmp);
        $image_name = $request->name;

        Storage::disk('local')->put(
            'public/payments/' . $image_name,
            $file
        );

        $payment->photo = $image_name;

        $payment->save();

        $user = User::findOrFail($auth_user->id);
        if(empty($request->shopmember_type_id) || $request->shopmember_type_id==0 || $request->shopmember_type_id==null){
            $user->active_status = 1;
            $user->request_type = $request->member_id;
        }else{
            $user->shopmember_type_id= $request->shopmember_type_id;
            $user->shop_request=1;
        }
        $user->update();

        return response()->json([
            'message' => 'success'
        ]);
    }


    public function storeWalletPayment(Request $request)
    {
        $auth_user = auth()->user();
        $payment = new Payment();
        $payment->user_id = $auth_user->id;
        $payment->payment_type = 'ewallet';
        $payment->payment_name = $request->payment_name;
        $payment->account_name = $request->account_name;
        $payment->phone = $request->phone;
        $payment->amount = $request->amount;

        // Store Image
        $tmp = $request->image;

        $file = base64_decode($tmp);
        $image_name = $request->name;

        Storage::disk('local')->put(
            'public/payments/' . $image_name,
            $file
        );

        $payment->photo = $image_name;
        $payment->save();

        $user = User::findOrFail($auth_user->id);
        if(empty($request->shopmember_type_id) || $request->shopmember_type_id==0 || $request->shopmember_type_id==null){
            $user->active_status = 1;
            $user->request_type = $request->member_id;
        }else{
            if($user->shop_request == 2){
                $user->shopmember_type_id= $request->shopmember_type_id;
                $user->shop_request=3;
            }
            else{
                $user->shopmember_type_id= $request->shopmember_type_id;
                $user->shop_request=1;
            }

        }
        $user->update();
        return response()->json([
            'message' => 'success'
        ]);
    }


    public function getEwalletInfos()
    {
        $banking_infos = BankingInfo::where('payment_type', 'ewallet')->get();
        return response()->json([
            'banking_infos' => $banking_infos
        ]);
    }

    public function getBankingInfos()
    {
        $banking_infos = BankingInfo::where('payment_type', 'bank transfer')->get();
        return response()->json([
            'banking_infos' => $banking_infos
        ]);
    }

    public function me()
    {
        $user = auth()->user();
        $token = $user->currentAccessToken();

        return response()->json([
            'user' => $user,
        ]);
    }

    public function logout()
    {
        $user = auth()->user();
        $user->currentAccessToken()->delete();
        return response()->json([
            "message" => "User successfully logout!"
        ]);
    }


    public function test()
    {
        $name ="Mg Mg";
        if(true) {
            // $name = "Ko Ko";
            return $name;
        }

        return 'yc-' . Str::uuid();
    }
}
