<?php

namespace App\Services\Auth;

use App\Traits\JsonResponseTrait;

abstract class BaseAuthService
{
    use JsonResponseTrait;

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
