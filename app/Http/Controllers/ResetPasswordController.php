<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\PasswordReset;
use Session;
use Hash;
use Nexmo;

class ResetPasswordController extends Controller
{
    public function resetpassword(Request $request)
    {
        $validated = $request->validate([
            'mobile_number' => 'required|string'
        ]);

        // if user is not found , send error message
        $user = User::where('mobile_number', $validated['mobile_number'])->first();
        if( !$user ) {
            return response([
                'status_code' => 400,
                'msg' => 'User is not found'      
            ]);
        }

        // delete already existing request
        PasswordReset::where('user_id', $user->id)->delete(); 

        // create new reset request
        $otp = strval(rand(111111,999999));
        PasswordReset::create([
            'user_id' => $user->id,
            'otp' => $otp,
            'otp_expire_date' => now()->addMinutes(5),
            'token_expire_date' => now()->addHours(24),
            'reset_token' => bin2hex(random_bytes(50))
        ]);

        $to = $user->mobile_number;
        
        // send OTP as SMS
        Nexmo::message()->send([
            'to'   => $to,
            'from' => 'Vonage SMS API',
            'text' => 'OTP is ' . $otp
        ]);

        return response([
            'status_code' => 200,
            'msg' => 'OTP is sent to *******'.substr($to, -3),
            'user_id' => $user->id
        ]);
    }

    public function verifyOTP(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|string',
            'OTP' => 'required|string'
        ]);

        $reset = PasswordReset::where('user_id', $validated['user_id'])->first();
        
        if( $reset->otp_expire_date->isPast() ) {
            return response([
                'status_code' => 400,
                'msg' => 'OTP Expired'
            ]);
        }

        if ( $validated['OTP'] != $reset->otp ) {
            return response([
                'status_code' => 400,
                'msg' => 'OTP verification failed'
            ]);
        }

        return response([
            'status_code' => 200,
            'msg' => 'OTP verification successfull',
            'reset_token' => $reset->reset_token
        ]);
        
    }


    public function updatepassword(Request $request)
    {
        $validated = $request->validate([
            'reset_token' => 'required|string',
            'password' => 'required|string'
        ]);
        
        $reset = PasswordReset::where('reset_token', $validated['reset_token'])->first();

        if ( !$reset || $reset->token_expire_date->isPast() ) {
            return response([
                'status_code' => 400,
                'msg' => 'Reset Token Not exist or expired'
            ]);
        }

        // update password
        User::where('id', $reset->user_id)->update([ 
            'password' => Hash::make( $validated['password'] )
        ]);

        return response([
            'status_code' => 200,
            'msg' => 'Password updated successfully'
        ]);
        
    }
}
