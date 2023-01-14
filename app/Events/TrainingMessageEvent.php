<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TrainingMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $media;
    public $group_id;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message, $media, $group_id)
    {
        $this->message = $message;
        $this->media = $media;
        $this->group_id = $group_id;


    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // return new Channel('trainer-message');
        return ['trainer-message.'.$this->group_id];
    }

    public function broadcastAs() {
        return 'training_message_event';
    }
}
