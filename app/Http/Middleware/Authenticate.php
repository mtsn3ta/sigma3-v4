<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Filament\Http\Middleware\Authenticate as FilamentAuthenticate;

class Authenticate extends FilamentAuthenticate
{
    public function handle($request, $next, ...$guards)
    {
        Log::info('AUTH CHECK', [
            'check' => Auth::check(),
            'id' => Auth::id(),
            'user' => Auth::user()?->username,
            'roles' => Auth::check() ? Auth::user()->getRoleNames()->toArray() : [],
            'url' => $request->path(),
        ]);

        return parent::handle($request, $next, ...$guards);
    }

    protected function redirectTo($request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        return '/login';
    }
}