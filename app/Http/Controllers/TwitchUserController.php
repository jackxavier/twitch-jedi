<?php

namespace App\Http\Controllers;

use App\Service\TwitchUserService;
use App\Service\UserService;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwitchUserController extends Controller
{
    /**
     * @var TwitchUserService
     */
    protected $twitchUserService;

    /**
     * @param TwitchUserService $twitchUserService
     * @param UserService       $userService
     */
    public function __construct(TwitchUserService $twitchUserService)
    {
        $this->twitchUserService = $twitchUserService;

        $this->middleware('auth');
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {

        $users = $this->twitchUserService->getFollowedUsers($this->getUser());

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
            $users = $this->twitchUserService->findUsersByName($userNameParam, $this->getUser());
        }

        return response()->json($users);
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function subscribeToAUser(Request $request): JsonResponse
    {

        $twitchUserId = $request->get('twitch_user_id');

        if (empty($twitchUserId)) {
            return response()->json(['status' => 'No user set']);
        }

        $result = $this->twitchUserService->subscribeToAStream($twitchUserId, $this->getUser());

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

        $result = $this->twitchUserService->followUser($twitchUserId, $this->getUser());

        return response()->json($result);
    }

    /**
     * @return mixed
     */
    protected function getUser(): User
    {
        return Auth::user();
    }
}

