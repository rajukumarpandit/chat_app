<?php

namespace App\Events;

use App\Models\GroupMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class GroupMessageSent implements ShouldBroadcast {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct( GroupMessage $message ) {
        $this->message = $message->load( 'sender' );
    }

    public function broadcastOn() {
        return new Channel( 'group.' . $this->message->group_id );
    }

    public function broadcastAs() {
        return 'GroupMessageSent';
    }

    public function broadcastWith() {
        return [
            'message' => [
                'id' => $this->message->id,
                'message' => $this->message->message,
                'group_id_' => $this->message->group_id,
                'file_path' => $this->message->file_path,
                // 'file_type' => $this->message->file_type,
                'created_at' => $this->message->created_at,
                'sender' => [
                    'id' => $this->message->sender->id,
                    'name' => $this->message->sender->name,
                ]
            ]
        ];
    }

}