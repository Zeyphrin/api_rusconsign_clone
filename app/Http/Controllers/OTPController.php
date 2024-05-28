<?php

namespace App\Http\Controllers;

use App\Models\OTP;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OTPController extends Controller
{

    public function sendOTP(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();
        $otp = rand(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(10);

        OTP::create([
            'user_id' => $user->id,
            'otp' => $otp,
            'expires_at' => $expiresAt,
        ]);

        Mail::to($user->email)->send(new OTPMail($otp));

        return response()->json(['message' => 'OTP sent successfully.']);
    }

    public function verifyOTP(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|digits:6',
        ]);

        $user = User::where('email', $request->email)->first();
        $otp = OTP::where('user_id', $user->id)
            ->where('otp', $request->otp)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if ($otp) {
            $otp->is_used = true;
            $otp->save();

            return response()->json(['message' => 'OTP verified successfully.']);
        }

        return response()->json(['message' => 'Invalid or expired OTP.'], 400);
    }
}
