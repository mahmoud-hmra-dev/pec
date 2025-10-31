<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VerificationCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    //
    public function login()
    {
        return view('consent.auth.otp-login');
    }
    public function generate(Request $request)
    {
        # Validate Data
        $request->validate([
            'email' => 'required|exists:users,email'
        ]);

        # Generate An OTP
        $verificationCode = $this->generateOtp($request->email);
        $message = "Your OTP To Login is - ".$verificationCode->otp;

        $data = [
            'subject' => 'Login email',
            'email' => $request->email,
            'content' => $message,
        ];

        Mail::send('consent.auth.mail', $data, function ($message) use ($data) {
            $message->to($data['email'])->subject($data['subject']);
        });

        # Return With OTP

        return redirect()->route('otp.verification', ['uuid' => $verificationCode->user->uuid]);
    }
    public function generateOtp($email)
    {
        $user = User::where('email', $email)->first();

        # User Does not Have Any Existing OTP
        $verificationCode = VerificationCode::where('user_id', $user->id)->latest()->first();

        $now = Carbon::now();

        if($verificationCode && $now->isBefore($verificationCode->expire_at)){
            return $verificationCode;
        }

        // Create a New OTP
        return VerificationCode::create([
            'user_id' => $user->id,
            'otp' => rand(123456, 999999),
            'expire_at' => Carbon::now()->addMinutes(10),

        ]);
    }

    public function loginWithOtp(Request $request)
    {
        #Validation
        $request->validate([
            'uuid' => 'required|exists:users,uuid',
            'otp' => 'required'
        ]);

        #Validation Logic
        $user = User::where('uuid',$request->uuid)->first();

        $verificationCode   = $user->verificationCodes()->where('otp', $request->otp)->first();

        $now = Carbon::now();
        if (!$verificationCode) {
            return redirect()->back()->with('error', 'Your OTP is not correct');
        }elseif($verificationCode && $now->isAfter($verificationCode->expire_at)){
            return redirect()->route('otp.login')->with('error', 'Your OTP has been expired');
        }
        if($user){
            // Expire The OTP
            $verificationCode->update([
                'expire_at' => Carbon::now()
            ]);

            Auth::login($user);

            return redirect()->route('consent.index');
        }

        return redirect()->route('otp.login')->with('error', 'Your Otp is not correct');
    }
    public function verification($uuid)
    {
        return view('consent.auth.otp-verification')->with([
            'uuid' => $uuid
        ]);
    }
}
