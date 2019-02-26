<?php

namespace App\Http\Middleware;

use Closure;

class TwitchOauthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Socialite::driver('twitch')->userFromToken($request->token);

        if ($user->getName()) {
            return $next($request);
        }

        return $next($request);
    }
}
