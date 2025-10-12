<?php

namespace App\Services\Auth;

use App\Helpers\AbilityGenerator;
use App\Mail\LoggedInMail;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class LoginService
{
    use JsonResponseTrait;

    public function handle(Request $request, string $platform): JsonResponse
    {
        $credentials = $request->validated();

        if (! Auth::attempt($credentials)) {
            return $this->fail(statusCode: 401);
        }

        $user = Auth::user();
        $abilities = AbilityGenerator::generate($user->role, $platform);
        $token = $user->createToken($platform, $abilities);
        $user['token'] = $token->plainTextToken;

        $loginInfo = $this->getLoginInfo($request);

        Mail::to($user->email)->send(new LoggedInMail($user, $loginInfo));

        return $this->success($user);
    }

    protected function getLoginInfo(Request $request): array
    {
        return [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'device' => $this->detectDevice($request->userAgent()),
            'login_time' => now()->format('Y-m-d H:i:s'),
        ];
    }

    protected function detectDevice(string $userAgent): string
    {
        if (preg_match('/mobile/i', $userAgent)) {
            return 'Mobile Device';
        }
        if (preg_match('/tablet/i', $userAgent)) {
            return 'Tablet';
        }
        if (preg_match('/windows|macintosh|linux/i', $userAgent)) {
            return 'Desktop';
        }

        return 'Unknown Device';
    }
}
