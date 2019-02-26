<?php

namespace App\Service;

use App\UserNotification;

class NotificationService
{
    /**
     * @param array $data
     *
     * @return UserNotification
     */
    public function create($data = [])
    {
        $notification            = new UserNotification();
        $notification->body      = json_encode($data);
        $notification->twitch_id = 12123;

        $notification->save();

        return $notification;
    }
}