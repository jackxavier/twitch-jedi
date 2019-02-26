<?php

namespace App\Events;

use App\Comment;
use App\UserNotification;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewNotification implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var UserNotification $notification
     */
    public $notification;

    /**
     * @param UserNotification $notification
     */
    public function __construct(UserNotification $notification)
    {
        $this->notification = $notification;

    }

    /**
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('user-notify');
    }

    /**
     * @return string
     */
    public function broadcastWith(): array
    {
        return [
            'body' => $this->notification->body,
        ];
    }
}
