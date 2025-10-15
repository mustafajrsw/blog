<?php

namespace App\Services\Auth;

use App\Mail\VerifyMail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RegisterService extends BaseAuthService
{
    public function handle(array $data): JsonResponse
    {
        // $data['password'] = Hash::make($data['password']);
        $user = User::create($data)
            ->forceFill([
                'role' => 'user',
                'email_verification_token' => Str::random(64),
                'email_verification_expires_at' => now()->addHour(),
            ]);

        $user->save();

        Mail::to($user->email)->send(new VerifyMail($user));

        return $this->success('User registered successfully. Please verify your email.', 201);
    }
}
