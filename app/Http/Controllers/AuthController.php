<?php

namespace App\Http\Controllers;

use App\Helpers\AbilityGenerator;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Mail\LoggedInMail;
use App\Mail\ResetPasswordMail;
use App\Mail\VerifyMail;
use App\Models\User;
use App\Services\Auth\LoginService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function __construct(protected LoginService $loginService) {}

    /**
     * Register a new user
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = User::create($data)
            ->forceFill([
                'role' => 'user',
                'email_verification_token' => Str::random(64),
                'email_verification_expires_at' => now()->addHours(1),
            ]);

        $user->save();
        $verificationUrl = url("/api/auth/email/verify?token={$user->email_verification_token}");

        Mail::to($user->email)->send(new VerifyMail($user, $verificationUrl));

        return $this->success([
            'message' => 'User registered successfully. Please check your email to verify your account.',
            'verify_url' => $verificationUrl,
        ], 201);
    }

    /**
     * Handle forgot password request
     */
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

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

        $resetUrl = url('/api/auth/password/reset?token='.$token.'&email='.urlencode($user->email));
        Mail::to($user->email)->send(new ResetPasswordMail($user, $resetUrl));

        return $this->success();
    }

    /**
     * Handle reset password confirmation
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (! $record) {
            return $this->fail(['message' => 'Invalid or expired token'], 400);
        }

        if (Carbon::parse($record->created_at)->addHour()->isPast()) {
            return $this->fail(['message' => 'Token has expired'], 400);
        }

        if (! Hash::check($request->token, $record->token)) {
            return $this->fail(['message' => 'Invalid token'], 400);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        // $user->update(['password' => Hash::make($request->password)]);
        $user->update(['password' => $request->password]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return $this->success();
    }

    /**
     * Extract login environment info.
     */
    protected function getLoginInfo(Request $request): array
    {
        return [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'device' => $this->detectDevice($request->userAgent()),
            'login_time' => now(),
        ];
    }

    /**
     * Simple device detector (minimal dependency approach)
     */
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

    public function web_login(LoginRequest $request): JsonResponse
    {
        return $this->loginService->handle($request, 'web');
    }

    public function mobile_login(LoginRequest $request): JsonResponse
    {
        return $this->loginService->handle($request, 'mobile');
    }

    // public function login(LoginRequest $request, string $platform): JsonResponse
    // {
    //     $data = $request->validated();

    //     if (! Auth::attempt($data)) {
    //         return $this->fail(statusCode: 401);
    //     }

    //     $user = Auth::user();

    //     $abilities = AbilityGenerator::generate($user->role, $platform);
    //     $token = $user->createToken($platform, $abilities);
    //     $user['token'] = $token->plainTextToken;

    //     $loginInfo = $this->getLoginInfo($request);

    //     Mail::to($user->email)->send(new LoggedInMail($user, $loginInfo));

    //     return $this->success($user);
    // }
    /**
     * Web login
     *
     * Validate login credentials and return user with token if valid
     */
    // public function web_login(LoginRequest $request): JsonResponse
    // {
    //     $data = $request->validated();
    //     $auth = Auth::attempt($data);
    //     if ($auth) {
    //         $user = Auth::user();
    //         $abilities = AbilityGenerator::generate($user->role, 'web');
    //         $token = $user->createToken('web', $abilities);
    //         $user['token'] = $token->plainTextToken;
    //         Mail::to($user['email'])->send(new LoggedInMail($user));

    //         return $this->success($user);
    //     }

    //     return $this->fail(statusCode: 401);
    // }

    /**
     * Mobile login
     *
     * Validate login credentials and return user with token if valid
     */
    // public function mobile_login(LoginRequest $request): JsonResponse
    // {
    //     $data = $request->validated();
    //     $auth = Auth::attempt($data);
    //     if ($auth) {
    //         $user = Auth::user();
    //         $abilities = AbilityGenerator::generate($user->role, 'mobile');
    //         $token = $user->createToken('mobile', $abilities);
    //         $user['token'] = $token->plainTextToken;
    //         // Send email to user
    //         Mail::to($user['email'])->send(new LoggedInMail($user));

    //         return $this->success($user);
    //     }

    //     return $this->fail(statusCode: 401);
    // }

    /**
     * Verify email address of user
     */
    public function verify_email(Request $request): JsonResponse
    {
        $token = $request->query('token');
        $user = User::where('email_verification_token', $token)->first();
        if (! $user) {
            $data = [
                'message' => 'Invalid verification token.',
            ];

            return $this->fail($data, 400);
        }

        if (! $user->is_active) {
            $data = [
                'message' => 'User is not active. Please contact support.',
            ];

            return $this->fail($data, 401);
        }

        if ($user->hasVerifiedEmail()) {
            $data = [
                'message' => 'Email already verified.',
            ];

            return $this->success($data, 200);
        }

        if ($user->email_verification_expires_at && $user->email_verification_expires_at->isPast()) {
            return $this->fail('Verification link expired.', 410);
        }

        $user->forceFill([
            'email_verified_at' => now(),
            'email_verification_token' => null,
            'email_verification_expires_at' => null,
        ])->save();

        return $this->success();
    }

    public function resendVerification(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            $data = [
                'message' => 'Email already verified.',
            ];

            return $this->success($data, 200);
        }

        $user->forceFill([
            'email_verification_token' => Str::random(64),
            'email_verification_expires_at' => now()->addHours(1),
        ]);
        $user->save();

        $verificationUrl = url("/api/auth/email/verify?token={$user->email_verification_token}");
        Mail::to($user->email)->send(new VerifyMail($user, $verificationUrl));

        return $this->success([
            'message' => 'Verification email resent successfully.',
            'verify_url' => $verificationUrl,
        ]);
    }

    // Session Management Routes
    // All Active Sessions for current user
    public function active_sessions(Request $request): JsonResponse
    {
        $user = $request->user();
        $tokens = $user->tokens;

        return response()->json($tokens, 200);
    }

    // Current Session for current user
    public function current_session(Request $request): JsonResponse
    {
        $user = $request->user();
        $currentTokenId = $user->currentAccessToken()->id;
        $currentToken = $user->tokens()->where('id', $currentTokenId)->first();
        if ($currentToken) {
            return response()->json($currentToken, 200);
        }

        return response()->json(['message' => 'No current session found'], 404);
    }

    // Other Sessions for current user
    public function other_sessions(Request $request): JsonResponse
    {
        $user = $request->user();
        $currentTokenId = $request->user()->currentAccessToken()->id;
        $otherTokens = $user->tokens()->whereNot('id', $currentTokenId)->get();
        if ($otherTokens->isNotEmpty()) {
            return response()->json($otherTokens, 200);
        }

        return response()->json(['message' => 'No other sessions found'], 404);
    }

    // Show specific session by ID for current user
    public function show_session(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        $token = $user->tokens()->where('id', $id)->first();
        if ($token) {
            return response()->json($token, 200);
        }

        return response()->json(['message' => 'Session not found'], 404);
    }

    // Logout from all sessions for current user
    public function logout_all(Request $request): JsonResponse
    {
        $delete = $request->user()->tokens()->delete();

        return $delete ? response()->json(['message' => 'Logged out from all sessions'], 200) : response()->json(['message' => 'Failed to logout'], 500);
    }

    // Logout from current session
    public function logout_current(Request $request): JsonResponse
    {
        $currentTokenId = $request->user()->currentAccessToken()->id;
        $delete = $request->user()->tokens()->where('id', $currentTokenId)->delete();

        return $delete ? response()->json(['message' => 'Logged out from current session'], 200) : response()->json(['message' => 'Failed to logout'], 500);
    }

    // Logout from other sessions
    public function logout_others(Request $request): JsonResponse
    {
        $currentTokenId = $request->user()->currentAccessToken()->id;
        $delete = $request->user()->tokens()->whereNot('id', $currentTokenId)->delete();

        return $delete ? response()->json(['message' => 'Logged out from other sessions'], 200) : response()->json(['message' => 'Failed to logout'], 500);
    }

    // Logout from specific session by ID
    public function logout_session(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        $token = $user->tokens()->where('id', $id)->first();
        if ($token) {
            $token->delete();

            return response()->json(['message' => 'Logged out from the session'], 200);
        }

        return response()->json(['message' => 'Session not found'], 404);
    }

    // Profile

    public function show_profile(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json($user, 200);
    }
}
