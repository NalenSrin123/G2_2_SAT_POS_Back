<?php

namespace App\Http\Controllers;

use App\Mail\LoginOtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid email or password',
            ], 401);
        }

        $otp = rand(100000, 999999);

        $user->otp = Hash::make((string) $otp);
        $user->otp_expires_at = now()->addMinutes(5);
        $user->is_verified = false;
        $user->save();

        try {
            Mail::to($user->email)->send(new LoginOtpMail($otp));
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to send OTP email',
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'message' => 'OTP sent to your email',
            'email' => $user->email,
            'requires_otp' => true,
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        if (!$user->otp) {
            return response()->json([
                'message' => 'Please login first to request OTP',
            ], 400);
        }

        if ($user->otp_expires_at < now()) {
            return response()->json([
                'message' => 'OTP expired',
            ], 400);
        }

        if (!Hash::check($request->otp, $user->otp)) {
            return response()->json([
                'message' => 'Invalid OTP',
            ], 400);
        }

        $user->otp = null;
        $user->otp_expires_at = null;
        $user->is_verified = true;
        $user->save();

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token,
        ]);
    }
}