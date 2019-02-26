<?php

namespace App\Events;

use App\Comment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewComment implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Comment
     */
    public $comment;

    /**
     * @return void
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;

    }

    /**
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('post.' . $this->comment->post->id);
    }

    /**
     * @return string
     */
    public function broadcastWith()
    {
        return [
            'body'       => $this->comment->body,
            'created_at' => $this->comment->created_at->toFormattedDateString(),
            'user'       => [
                'name'   => $this->comment->user->name,
                'avatar' => 'http://lorempixel/50/50',
            ],
        ];
    }
}
