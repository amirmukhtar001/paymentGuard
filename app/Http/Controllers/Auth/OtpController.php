<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use App\Actions\SmsAction;
use Carbon\Carbon;
use Exception;

class OtpController extends Controller
{

    public function showVerificationForm()
    {
        return view('auth.verify-otp');
    }

    public function verify(Request $request)
    {
        // Validate the OTP entered by the user
        $request->validate([
            'otp' => 'required|digits:4', // You may adjust validation rules as needed
        ]);

        try {
            $user = User::where('otp', $request->otp)->where('id', auth()->id())->first();
            if ($user) {
                if (!is_null($user->otp_time) || Carbon::now()->diffInMinutes($user->otp_time) < config('agriculture.otp_timeout')) {
                    $user->fill([
                        'otp'       =>  null,
                        'otp_time'  =>  null
                    ])->save();

                    return sendResponse(['redirect' => route('home')], 'OTP Verified');
                } else {
                    return sendError('OTP Expired. Please try again.', [], 200);
                }
            } else {
                return sendError('Invalid OTP. Please try again', [], 200);
            }
        } catch (Exception $exception) {
            return sendError('Invalid OTP. Please try again', [], 200);
        }
    }


    public function resend(SmsAction $sms)
    {
        $user = User::where('id', auth()->id())->first();

        if ($user) {
            $otp = random_int(1000, 9999);
            $user->fill([
                'otp'       =>  $otp,
                'otp_time'  =>  Carbon::now()
            ])->save();
            // Check if SmsAction class exists before using it
            if (class_exists(SmsAction::class)) {
                $otpMessage = "Your One-Time Password (OTP) for the Agriculture App is: " .  $otp . ". Please enter this code within the next 10 minutes to complete your Login.";
                $sms->sendSms($user->contact_number, $otpMessage);
                return sendResponse([], 'OTP sent to SMS');
            } else {
                // Log an error or handle the situation accordingly
                \Log::error('SmsAction class not found.');
                return sendError('SmsAction class not found', [], 500);
            }
        }

        return sendError('Invalid User', [], 400);
    }
}
