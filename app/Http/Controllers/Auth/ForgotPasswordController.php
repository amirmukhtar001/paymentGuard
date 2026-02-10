<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    public function showForm()
    {
        return view('auth.forgot.index');
    }

    public function sendOtpWeb(Request $request): JsonResponse
    {
        $request->validate(['identity' => 'required|string']);

        $user = User::query()
            ->where('email', $request->identity)
            ->orWhere('username', $request->identity)
            ->first();

        if (! $user) {
            return response()->json(['error' => true, 'message' => 'No account found with this email or username.']);
        }

        $otp = (string) random_int(1000, 9999);
        $user->forceFill([
            'otp' => $otp,
            'otp_time' => now(),
        ])->save();

        // TODO: Send OTP via email/SMS if needed (e.g. Mail::to($user)->send(...))

        return response()->json([
            'error' => false,
            'message' => 'OTP sent. Check your email or use the code if testing.',
            'data' => ['user_id' => $user->id],
        ]);
    }

    public function verifyOtp(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'otp' => 'required|string|size:4',
        ]);

        $user = User::findOrFail($request->user_id);
        $expiryMinutes = 10;

        if ($user->otp !== $request->otp) {
            return response()->json(['error' => true, 'message' => 'Invalid OTP.']);
        }

        if (! $user->otp_time || $user->otp_time->diffInMinutes(now(), false) > $expiryMinutes) {
            return response()->json(['error' => true, 'message' => 'OTP has expired. Please request a new one.']);
        }

        return response()->json(['error' => false, 'message' => 'OTP verified.']);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::findOrFail($request->user_id);

        if (! $user->otp || ! $user->otp_time) {
            return response()->json(['error' => true, 'message' => 'Please complete the OTP verification first.']);
        }

        $user->forceFill([
            'password' => Hash::make($request->password),
            'otp' => null,
            'otp_time' => null,
        ])->save();

        return response()->json(['error' => false, 'message' => 'Password updated. You can log in now.']);
    }
}
