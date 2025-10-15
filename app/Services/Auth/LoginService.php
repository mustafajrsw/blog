<?php

namespace App\Services\Auth;

use App\Helpers\AbilityGenerator;
use App\Mail\LoggedInMail;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class LoginService extends BaseAuthService
{
    public function handle(array $credentials, string $platform, string $ip, string $userAgent): JsonResponse
    {
        if (! Auth::attempt($credentials)) {
            return $this->fail(statusCode: 401);
        }

        $user = Auth::user();
        $abilities = AbilityGenerator::generate($user->role, $platform);
        $token = $user->createToken($platform, $abilities);
        $user['token'] = $token->plainTextToken;

        $loginInfo = $this->getLoginInfo($ip, $userAgent);

        Mail::to($user->email)->send(new LoggedInMail($user, $loginInfo));

        return $this->success($user);
    }

    /**
     * Get login information
     */
    protected function getLoginInfo(string $ip, string $userAgent): array
    {
        return [
            'ip' => $ip,
            'user_agent' => $userAgent,
            'device' => $this->detectDevice($userAgent),
            'login_time' => now()->format('Y-m-d H:i:s'),
        ];
    }
}
