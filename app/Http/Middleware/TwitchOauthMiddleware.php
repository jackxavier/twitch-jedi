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
        $token = session()->get('token');
        dd($token);

        if (empty($token) || !array_key_exists('oauth_token', $token)) {
            return redirect('login');
        }

        $user = Socialite::driver('twitch')->userFromToken($request['oauth_token']);

        if ($user->getName()) {
            return $next($request);
        }

        return $next($request);
    }
}
