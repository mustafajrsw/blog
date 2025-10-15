<?php

namespace App\Services\Auth;

use App\Mail\ResetPasswordMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordService extends BaseAuthService
{
    public function forgot(array $data): JsonResponse
    {
        $user = User::where('email', $data['email'])->first();

        if (! $user) {
            return $this->fail(statusCode: 404);
        }

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        Mail::to($user->email)->send(new ResetPasswordMail($user, $token));

        return $this->success();
    }

    public function reset(array $data): JsonResponse
    {

        $record = DB::table('password_reset_tokens')
            ->where('email', $data['email'])
            ->where('token', $data['token'])
            ->first();

        if (! $record) {
            return $this->fail(['message' => 'Invalid or expired token'], 400);
        }

        if (Carbon::parse($record->created_at)->addHour()->isPast()) {
            return $this->fail(['message' => 'Token has expired'], 400);
        }

        if (! Hash::check($data['token'], $record->token)) {
            return $this->fail(['message' => 'Invalid token'], 400);
        }

        User::where('email', $data['email'])->update([
            'password' => $data['password'],
            // 'password' => Hash::make($data['password']),
        ]);

        DB::table('password_reset_tokens')->where('email', $data['email'])->delete();

        return $this->success();
    }
}
