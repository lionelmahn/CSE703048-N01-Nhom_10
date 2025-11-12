<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }

    protected function authenticate($request, array $guards)
    {
        $user = $this->auth->guard($guards[0] ?? null)->user();

        if ($user && !$user->isActive()) {
            $this->auth->guard($guards[0] ?? null)->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw new \Illuminate\Auth\AuthenticationException(
                'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.',
                $guards,
                $this->redirectTo($request)
            );
        }

        return parent::authenticate($request, $guards);
    }
}
