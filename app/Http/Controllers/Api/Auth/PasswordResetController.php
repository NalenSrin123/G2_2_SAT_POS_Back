<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;


class PasswordResetController extends Controller
{
  public function sendOtp(Request $request){

        $request->validate([

            'email' => 'required|email|exists:users,email'

        ]);



        $otp = rand(100000, 999999);

        DB::table('password_resets_otps')->updateOrInsert(

            ['email' => $request->email],

            [

                'otp' => $otp,

                'expires_at' => Carbon::now()->addMinutes(10),

                'created_at' => now(),

                'updated_at' => now()

            ]

        );

        Mail::raw("Your OTP is: $otp", function ($message) use ($request){

            $message->to($request->email)

                    ->subject('Password Reset OTP');

        });



        return response()->json([

               'message' => 'OTP sent successfully'

        ]);

    }

    public function verifyOtp(Request $request)

    {

        $request->validate([

           'email' => 'required|email',

           'otp' => 'required'

        ]);



        $record = DB::table('password_resets_otps')

        ->where('email', $request->email)

        ->where('otp', $request->otp)

        ->first();



        if(!$record){

           return response()->json(['message' => 'Invalid OTP'], 400);

        }



        if(Carbon::now()->gt($record->expires_at)){

            return response()->json(['message' => 'OTP expired'], 400);

        }

        return response()->json([

          'message' => 'OTP verified successfully'

        ]);

    }



    public function resetPassword(Request $request)

    {

        $request->validate([

            'email' => 'required|email',

            'otp' => 'required',

            'password' => 'required|min:6|confirmed'

        ]);



        $record = DB::table('password_resets_otps')

         ->where('email', $request->email)

         ->where('otp', $request->otp)

         ->first();

         if (!$record){

            return response()->json(['message' =>'Invalid'], 400);

         }

         if (Carbon::now()->gt($record->expires_at)) {

        return response()->json(['message' => 'OTP expired'], 400);

    }



   

    User::where('email', $request->email)

        ->update([

            'password' => Hash::make($request->password)

        ]);



    // delete OTP after success

    DB::table('password_resets_otps')->where('email', $request->email)->delete();



    return response()->json([

        'message' => 'Password reset successfully'

    ]);

    }
}
