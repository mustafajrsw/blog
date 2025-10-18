<?php

namespace App\Http\Controllers;

use App\Http\Auth\Requests\ChangePasswordRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\VerifyEmailRequest;
use App\Services\Auth\LoginService;
use App\Services\Auth\PasswordService;
use App\Services\Auth\RegisterService;
use App\Services\Auth\VerifyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        protected LoginService $loginService,
        protected RegisterService $registerService,
        protected PasswordService $passwordService,
        protected VerifyService $verifyService
    ) {}

    /**
     * Register a new user
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        return $this->registerService->handle($data);
    }

    /**
     * Handle forgot password request
     */
    public function forgot_password(ForgotPasswordRequest $request): JsonResponse
    {
        $data = $request->validated();

        return $this->passwordService->forgot($data);
    }

    /**
     * Handle reset password confirmation
     */
    public function reset_password(ResetPasswordRequest $request): JsonResponse
    {
        $data = $request->validated();

        return $this->passwordService->reset($data);
    }

    public function change_password(ChangePasswordRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = $request->user();

        return $this->passwordService->change($user, $data);
    }

    /**
     * Login a user
     */
    public function login(LoginRequest $request, string $platform): JsonResponse
    {
        return $this->loginService->handle(
            $request->validated(),
            $platform,
            $request->ip(),
            $request->userAgent(),
        );
    }

    /**
     * Verify email address of user
     */
    public function verify_email(VerifyEmailRequest $request): JsonResponse
    {
        $token = $request->token;

        return $this->verifyService->verify($token);
    }

    public function re_verify_email(): JsonResponse
    {
        $user = auth()->user();

        return $this->verifyService->resend($user);
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
