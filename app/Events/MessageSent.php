<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast {
    use InteractsWithSockets, SerializesModels;

    public $message;

    /**
    * Create a new event instance.
    */

    public function __construct( Message $message ) {
        $this->message = $message->load( 'sender' );
        // load sender relation
    }

    /**
    * The channel the event should broadcast on.
    */

    public function broadcastOn() {
        return new PrivateChannel( 'chat.' . $this->message->receiver_id );

    }

    public function broadcastAs() {
        return 'singlemessage';
    }

    public function broadcastWith(): array {
        return [
            'id'        => $this->message->id,
            'message'   => $this->message->message,
            'sender'    => $this->message->sender->name,
            'sender_id' => $this->message->sender_id,
            'receiverid'=> $this->message->receiver_id,
            'file_path' => $this->message->file_path,
            'created_at'=> $this->message->created_at->toDateTimeString(),
        ];
    }

}