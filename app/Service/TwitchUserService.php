<?php

namespace App\Service;

use App\Client\TwitchApiProvider;
use App\User;

class TwitchUserService
{
    /**
     * @var TwitchApiProvider
     */
    protected $twitchApiProvider;

    /**
     * @param TwitchApiProvider $twitchApiProvider
     */
    public function __construct(TwitchApiProvider $twitchApiProvider)
    {
        $this->twitchApiProvider = $twitchApiProvider;
    }

    /**
     * @param User $user
     *
     * @return array
     */
    protected function getFollowedUsersIds(User $user): array
    {
        $apiClient = $this->twitchApiProvider->getNewTwitchApi();

        if (!$apiClient) {
            return [];
        }

        try {
            $followedUsersResponse = $apiClient->getUsersApi()->getUsersFollows($user->twitch_id);

        } catch (\Exception $exception) {
            return [];
        }

        $followedUsersData = (array)json_decode($followedUsersResponse->getBody()->getContents(), true);
        if (empty($followedUsersData) || !array_key_exists('data', $followedUsersData)) {
            return [];
        }

        return $followedUsersData['data'];
    }

    /**
     * @param array $userIds
     * @param       $bearer
     *
     * @return array|mixed
     */
    public function findUsers(array $userIds = [], array $userNames = [], string $bearer): array
    {
        $apiClient = $this->twitchApiProvider->getNewTwitchApi();

        if (!$apiClient) {
            return [];
        }

        try {
            $usersResponse = $apiClient->getUsersApi()->getUsers($userIds, $userNames, false, $bearer);

        } catch (\Exception $exception) {
            return [];
        }

        $usersData = (array)json_decode($usersResponse->getBody()->getContents(), true);
        if (empty($usersData) || !array_key_exists('data', $usersData)) {
            return [];
        }

        $users = $usersData['data'];

        return $users;
    }

    /**
     * @param User $user
     *
     * @return array
     */
    public function getFollowedUsers(User $user): array
    {
        $followedUsers    = $this->getFollowedUsersIds($user);
        $followedUsersIds = [];

        foreach ($followedUsers as $followedUser) {
            if (!array_key_exists('to_id', $followedUser)) {
                continue;
            }

            array_push($followedUsersIds, $followedUser['to_id']);
        }

        if (empty($followedUsersIds)) {
            return [];
        }

        $users = $this->findUsers($followedUsersIds, [], $user->token);

        return array_map(
            function ($user) {
                $user['followed'] = true;

                return $user;
            }, $users
        );
    }

    /**
     * @param      $channel_id
     * @param User $user
     *
     * @return array
     */
    public function followUser($channel, User $user): array
    {
        $apiClient = $this->twitchApiProvider->getTwitchApi();
        if (!$apiClient) {
            return ['error' => sprintf('No API client returned')];
        }

        $data = $apiClient->followChannel($user->name, $channel, $user->token, true);

        return $data;
    }

    /**
     * @param      $name
     * @param User $user
     *
     * @return array|mixed
     */
    public function findUsersByName(string $name, User $user): array
    {
        return $this->findUsers([], [$name], $user->token);
    }

    /**
     * @param      $twitchId
     * @param User $user
     *
     * @return array
     */
    public function subscribeToAStream(string $twitchId, User $user): array
    {
        return $this->subscribe($twitchId, $user->token);
    }

    /**
     * @param $twitchId
     * @param $accessToken
     *
     * @return array|bool
     */
    protected function subscribe(string $twitchId, string $accessToken): array
    {
        $apiClient = $this->twitchApiProvider->getNewTwitchApi();
        if (!$apiClient) {
            return ['error' => sprintf('No API client returned')];
        }

        try {
            $apiClient
                ->getWebhooksSubscriptionApi()
                ->subscribeToStream(
                    $twitchId,
                    $accessToken,
                    env('API_TWITCH_NOTIFICATION_CALLBACK','/'),
                    864000
                );
        } catch (\Exception $exception) {
            return ['error' => $exception];
        }

        return ['status' => 'ok'];
    }
}