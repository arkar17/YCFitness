<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;


class PassResetController extends Controller
{
    //
    public function passResetView(){
        return view ('auth.passReset');
    }
    public function checkPhoneGetOTP(Request $request){
            $phone = $request->phone;
            $user = User::where('phone', $phone)->first();
            if($user){
                // return response()->json([
                //     'status' => 200,
                //     'message' => $user->phone,
                // ]);
                $response = Http::get('https://verify.smspoh.com/api/v1/request', [
                    'access-token' => 'vJMxoWJOITaHCjm-bMoUe8PNZcFh79Z1-R4VpzRPjOnMB6mTd06FE6U497SldLe-',
                    'code_length' => '4',
                    'brand_name' => 'Gym',
                    'sender_name' => 'Gym',
                    'ttl' => '300',
                    'number' => $user->phone,
                ]);
                return response()->json([
                    'status' => 200,
                    'message' => $response['request_id'],
                    // 'message' => $response['request_id'],
                ]);
            }
            else{
                return response()->json([
                            'status' => 300,
                            'message' => "Your Phone Number is not register yet!",
                        ]);
            }
    }
    public function password_reset(Request $request){

        //  $validator = Validator::make($request, [
        //     'password' => 'required|min:6',
        //     'cpassword' => 'required|same:password', // this will check password
        // ]);


        if($request->password==$request->cpassword){
    // dd($request->password);
            $phone = $request->phone;
            $user = User::where('phone', $phone)->first();
            $user->password = Hash::make($request->password);
            $user->update();
            Auth::logout();
            Alert::success('Success', 'Password Changed Successfully!');
            return redirect('/');
        }else{
            return redirect()->back()->with('success','Passwords do not match');
        }


    }
}
