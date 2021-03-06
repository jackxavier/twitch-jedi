<?php

namespace App\Service;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use SocialiteProviders\Manager\OAuth2\User as OAuthUser;

class UserService
{
    /**
     * @var User | null
     */
    protected $authenticatedIdentity;

    /**
     * @param int|null $twitchId
     *
     * @return User|null
     */
    public function findUserByTwitchId(?int $twitchId): ?User
    {
        return User::where('twitch_id', $twitchId)->first() ?? null;
    }

    /**
     * @param OAuthUser      $oauthUser
     * @param User|null|null $user
     *
     * @return User
     */
    public function populateUser(OAuthUser $oauthUser, ?User $user = null): User
    {
        $token = $oauthUser->accessTokenResponseBody;

        $user->twitch_id     = $oauthUser->getId();
        $user->email         = $oauthUser->getEmail() ?? null;
        $user->nickname      = $oauthUser->getNickname() ?? null;
        $user->name          = $oauthUser->getName() ?? null;
        $user->avatar        = $oauthUser->getAvatar() ?? null;
        $user->token         = $token['access_token'] ?? null;
        $user->refresh_token = $token['refresh_token'] ?? null;
        $user->expires_in    = $token['expires_in'] ?? null;
        $user->save();

        return $user;
    }

    /**
     * @param null|OAuthUser $oauthUser
     *
     * @return void
     */
    public function authenticate(?OAuthUser $oauthUser): void
    {
        if (!$oauthUser || !$oauthUser->getId()) {
            return;
        }

        $twitchUserId = $oauthUser->getId();
        $user = $this->findUserByTwitchId((int)$twitchUserId);
        $user = $this->updateUserOnAuth($oauthUser, $user ?? new User());

        Auth::login($user, true);
    }

    /**
     * @param OAuthUser $oauthUser
     * @param User      $user
     *
     * @return User
     */
    protected function updateUserOnAuth(OAuthUser $oauthUser, User $user): User
    {
        $user = $this->populateUser($oauthUser, $user);

        $sessionData = [
            'oauth_token'   => $user->token,
            'refresh_token' => $user->refresh_token,
            'expires_in'    => $user->expires_in,
        ];
        $user->save();

        Session::remove('token');
        Session::flash('twitch-auth-token', $sessionData);

        return $user;
    }
}