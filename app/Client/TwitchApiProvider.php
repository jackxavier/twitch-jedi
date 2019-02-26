<?php

namespace App\Client;

use NewTwitchApi\HelixGuzzleClient;
use NewTwitchApi\NewTwitchApi;
use TwitchApi\TwitchApi;

class TwitchApiProvider
{
    /**
     * @var NewTwitchApi | TwitchApi
     */
    protected $twitchApi = null;

    /**
     * @return NewTwitchApi
     */
    public function getNewTwitchApi(): NewTwitchApi
    {
        if ($this->twitchApi && $this->twitchApi instanceof NewTwitchApi) {
            return $this->twitchApi;
        }

        $clientId     = env('TWITCH_KEY');
        $clientSecret = env('TWITCH_SECRET');

        if (empty($clientId) || empty($clientSecret)) {
            return null;
        }

        $helixGuzzleClient = new HelixGuzzleClient($clientId);
        $this->twitchApi   = new NewTwitchApi($helixGuzzleClient, $clientId, $clientSecret);

        return $this->twitchApi;
    }

    /**
     * @return TwitchApi
     */
    public function getTwitchApi(): TwitchApi
    {
        if ($this->twitchApi && $this->twitchApi instanceof TwitchApi) {
            return $this->twitchApi;
        }

        $clientId     = env('TWITCH_KEY');
        $clientSecret = env('TWITCH_SECRET');

        if (empty($clientId) || empty($clientSecret)) {
            return null;
        }

        $options = [
            'client_id' => $clientId,
            'secret'    => $clientSecret,
        ];

        $this->twitchApi = new TwitchApi($options);

        return $this->twitchApi;
    }
}