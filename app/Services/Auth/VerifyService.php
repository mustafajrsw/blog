<?php

namespace App\Services\Auth;

use App\Mail\VerifyMail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class VerifyService extends BaseAuthService
{
    public function verify(string $token): JsonResponse
    {
        $user = User::where('email_verification_token', $token)->first();

        if (! $user) {
            return $this->fail('Invalid verification token.');
        }

        if ($user->hasVerifiedEmail()) {
            return $this->success('Email already verified.', 200);
        }

        if (! $user->is_active) {
            return $this->fail('User is not active. Please contact support.', 401);
        }
        if ($user->email_verification_expires_at->isPast()) {
            return $this->fail('Verification link expired.', 410);
        }

        $user->forceFill([
            'is_active' => true,
            'email_verification_token' => null,
            'email_verified_at' => now(),
        ])->save();

        return $this->success();
    }

    public function resend(User $user): JsonResponse
    {
        if ($user->hasVerifiedEmail()) {
            return $this->success('Email already verified.');
        }
        $user->forceFill([
            'email_verification_token' => Str::random(64),
            'email_verification_expires_at' => now()->addHour(),
        ])->save();

        Mail::to($user->email)->send(new VerifyMail($user));

        return $this->success('Verification email resent.');
    }
}
