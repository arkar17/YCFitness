<?php

namespace App\Events;

use App\Models\ChatGroupMember;
use App\Models\ChatGroupMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GroupChatting implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    // public $message;
    // public $senderImg;
    // public $senderName;

    public function __construct()
    {
        // $this->message = $message;
        // $this->senderImg = $senderImg;
        // $this->senderName = $senderName;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel("groupChatting");
    }

    public function broadcastAs(){
        return 'group-chatting-event';
    }
}
