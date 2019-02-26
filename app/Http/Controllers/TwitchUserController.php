<?php

namespace App\Http\Controllers;

use App\Service\TwitchUserService;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TwitchUserController extends Controller
{
    /**
     * @var TwitchUserService
     */
    protected $twitchUserService;

    /***
     * @param TwitchUserService $twitchUserService
     */
    public function __construct(TwitchUserService $twitchUserService)
    {
        //  $this->middleware('auth');
        $this->twitchUserService = $twitchUserService;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $users = $this->twitchUserService->getFollowedUsers($this->__getMockedUser());

        return response()->json($users);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function searchUser(Request $request): JsonResponse
    {
        $users         = [];
        $userNameParam = $request->get('twitch_user_search') ?? null;

        if ($userNameParam) {
            $users = $this->twitchUserService->findUsersByName($userNameParam, $this->__getMockedUser());
        }

        return response()->json($users);
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function subscribeToAUser(Request $request)
    {
        $twitchUserId = $request->get('twitch_user_id');

        if (empty($twitchUserId)) {
            return response()->json(['status' => 'No user set']);
        }

        $result = $this->twitchUserService->subscribeToAStream($twitchUserId, $this->__getMockedUser());

        return response()->json($result);
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function followUser(Request $request): JsonResponse
    {
        $twitchUserId = $request->get('twitch_user_id');

        if (empty($twitchUserId)) {
            return response()->json(['status' => 'No user set']);
        }

        $result = $this->twitchUserService->followUser($twitchUserId, $this->__getMockedUser());

        return response()->json($result);
    }

    /**
     * @return User
     */
    protected function __getMockedUser()
    {
        $user = new User();

        $user->twitch_id      = '418279650';
        $user->nickname       = 'jackxavier11';
        $user->name           = 'jackxavier11';
        $user->email          = 'jack.al.xavier@gmail.com';
        $user->avatar
                              = 'https://static-cdn.jtvnw.net/user-default-pictures/4cbf10f1-bb9f-4f57-90e1-15bf06cfe6f5-profile_image-300x300.jpg';
        $user->remember_token = '3Y0kPPs2XSHYTvbsrEXGROj3nRnFEZ189CnoE95Ma0xanvnJeF9zYP0UqgTy       ';
        $user->token          = 'l8f7fjs5wi67iszsf7h16u9k8q9hyu';
        $user->refresh_token  = 'xpd60qi7mi3s6mhzqk1kw7ghkigqiaxpiq0d5mv5hxahml3epx';
        $user->expires_in     = '15524';

        return $user;
    }
}

